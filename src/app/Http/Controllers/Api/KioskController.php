<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use App\Models\Reserva;
use App\Models\Ocupacion;
use App\Services\OcupacionService;
use App\Services\TarifaService;
use App\Services\RUTService;
use App\Repositories\HabitacionRepository;
use App\Repositories\ReservaRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KioskController extends Controller
{
    public function __construct(
        private OcupacionService $ocupacionService,
        private TarifaService $tarifaService,
        private HabitacionRepository $habitacionRepository,
        private ReservaRepository $reservaRepository
    ) {}

    public function ping(): JsonResponse
    {
        return response()->json(['status' => 'ok']);
    }

    public function reservasHoy(): JsonResponse
    {
        $hoy = now()->format('Y-m-d');
        $reservas = Reserva::whereDate('fecha', $hoy)
            ->whereIn('estado', ['Reservada', 'Ingresada'])
            ->with('habitacion')
            ->get();

        return response()->json([
            'tiene_reservas' => $reservas->isNotEmpty(),
            'cantidad' => $reservas->count(),
            'reservas' => $reservas->map(fn ($r) => [
                'id' => $r->id,
                'rut' => $r->rut,
                'nombre' => $r->nombre,
                'hora' => $r->hora?->format('H:i'),
                'personas' => $r->personas,
                'estado' => $r->estado,
                'habitacion_numero' => $r->habitacion?->numero,
            ]),
        ]);
    }

    public function disponibilidad(): JsonResponse
    {
        $habitaciones = $this->habitacionRepository->getDisponibles();

        $departamentos = $habitaciones->where('categoria', 'Departamento')
            ->sortBy(fn ($h) => (!$h->aire ? 1000 : 0) + (int) $h->numero)
            ->values()
            ->map(fn ($h) => [
            'id' => $h->id,
            'numero' => $h->numero,
            'tarifa' => $this->obtenerTarifaMinima('Departamento'),
            'aire' => (bool) $h->aire,
        ])->values();

        $suites = $habitaciones->where('categoria', 'Suite')
            ->sortBy(fn ($h) => (!$h->aire ? 1000 : 0) + (int) $h->numero)
            ->values()
            ->map(fn ($h) => [
            'id' => $h->id,
            'numero' => $h->numero,
            'tarifa' => $this->obtenerTarifaMinima('Suite'),
            'aire' => (bool) $h->aire,
        ])->values();

        return response()->json([
            'departamentos' => $departamentos,
            'suites' => $suites,
        ]);
    }

    public function validarReserva(Request $request): JsonResponse
    {
        $request->validate([
            'rut' => 'required|string',
        ]);

        $rut = RUTService::limpiar($request->rut);

        $reservas = $this->reservaRepository->getReservasPorRUT($rut)
            ->whereIn('estado', ['Reservada', 'Ingresada'])
            ->values();

        if ($reservas->isEmpty()) {
            return response()->json([
                'valido' => false,
                'mensaje' => 'No se encontró una reserva activa para este RUT',
            ]);
        }

        $reserva = $reservas->first();

        $habitacion = $reserva->relationLoaded('habitacion') ? $reserva->habitacion : $reserva->habitacion()->first();

        return response()->json([
            'valido' => true,
            'reserva' => [
                'id' => $reserva->id,
                'rut' => $reserva->rut,
                'nombre' => $reserva->nombre,
                'fecha' => $reserva->fecha->format('Y-m-d'),
                'hora' => $reserva->hora?->format('H:i'),
                'personas' => $reserva->personas,
                'estado' => $reserva->estado,
                'precio_base' => $reserva->precio_base,
                'total' => $reserva->total,
                'habitacion_id' => $habitacion?->id,
                'habitacion_numero' => $habitacion?->numero,
            ],
        ]);
    }

    public function cambiarEstado(Request $request): JsonResponse
    {
        $request->validate([
            'habitacion_id' => 'required|integer|exists:habitaciones,id',
            'estado' => 'required|in:DISPONIBLE,RESERVADA,TRANSITO,OCUPADA,LIMPIEZA',
        ]);

        $habitacion = $this->habitacionRepository->findById($request->habitacion_id);
        if (!$habitacion) {
            return response()->json(['error' => 'Habitación no encontrada'], 404);
        }

        $estado = match ($request->estado) {
            'DISPONIBLE' => 'Disponible',
            'RESERVADA' => 'Reservada',
            'TRANSITO' => 'Transito',
            'OCUPADA' => 'Ocupada',
            'LIMPIEZA' => 'Limpieza',
            default => null,
        };

        if ($request->estado === 'TRANSITO' && $habitacion->estado === 'Disponible') {
            $ocupacion = $this->ocupacionService->iniciarOcupacion($habitacion, '8h', 0, 'Transito');
            return response()->json([
                'success' => true,
                'estado' => 'TRANSITO',
                'ocupacion_id' => $ocupacion->id,
                'mensaje' => "Habitación {$habitacion->numero} asignada. Portón abierto.",
            ]);
        }

        $this->ocupacionService->cambiarEstado($habitacion, $estado);

        return response()->json([
            'success' => true,
            'estado' => $request->estado,
            'mensaje' => "Habitación {$habitacion->numero} en estado {$request->estado}",
        ]);
    }

    public function asignar(Request $request): JsonResponse
    {
        $request->validate([
            'reserva_id' => 'required|integer|exists:reservas,id',
            'habitacion_id' => 'required|integer|exists:habitaciones,id',
        ]);

        $habitacion = $this->habitacionRepository->findById($request->habitacion_id);
        if (!$habitacion || $habitacion->estado !== 'Disponible') {
            return response()->json(['error' => 'Habitación no disponible'], 400);
        }

        $reserva = Reserva::findOrFail($request->reserva_id);
        $reserva->update([
            'habitacion_id' => $habitacion->id,
            'hora_ingreso' => now(),
            'estado' => 'Ingresada',
        ]);

        $habitacion->update(['estado' => 'Ocupada']);
        $this->ocupacionService->cambiarEstado($habitacion, 'Ocupada');

        return response()->json([
            'success' => true,
            'habitacion_numero' => $habitacion->numero,
            'mensaje' => "Habitación {$habitacion->numero} asignada a la reserva.",
        ]);
    }

    private function obtenerTarifaMinima(string $categoria): int
    {
        $precios = $this->tarifaService->calcularPrecio($categoria, now()->format('Y-m-d'), '8h');
        return $precios['precio_base'] ?? 0;
    }
}
