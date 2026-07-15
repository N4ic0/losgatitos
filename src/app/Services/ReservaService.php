<?php
namespace App\Services;

use App\Models\Reserva;
use App\Models\Habitacion;
use App\Models\Promocion;
use Carbon\Carbon;

class ReservaService
{
    public function __construct(
        private TarifaService $tarifaService,
        private AuditoriaService $auditoriaService
    ) {}

    public function crearReserva(array $data): Reserva
    {
        $precios = $this->tarifaService->calcularPrecio(
            $data['categoria'] ?? 'Suite',
            $data['fecha'],
            '8h',
            $data['horas_adicionales'] ?? 0,
            $data['tercera_persona'] ?? false
        );
        
        $data['precio_base'] = $precios['precio_base'];
        $data['total'] = $precios['total'];
        
        $reserva = Reserva::create($data);
        
        $this->auditoriaService->registrar('crear', 'reservas', $reserva->id, null, $reserva->toArray());
        
        return $reserva;
    }

    public function asignarHabitacion(int $reservaId, int $habitacionId): Reserva
    {
        $reserva = Reserva::findOrFail($reservaId);
        $reserva->update([
            'habitacion_id' => $habitacionId,
            'hora_ingreso' => now(),
            'estado' => 'Ingresada',
        ]);
        
        $habitacion = Habitacion::findOrFail($habitacionId);
        $habitacion->update(['estado' => 'Ocupada']);
        
        $this->auditoriaService->registrar('asignar', 'reservas', $reservaId, null, $reserva->toArray());
        
        return $reserva->fresh();
    }

    public function liberarHabitacion(int $reservaId): Reserva
    {
        $reserva = Reserva::findOrFail($reservaId);
        $reserva->update([
            'hora_salida' => now(),
            'estado' => 'Finalizada',
        ]);
        
        if ($reserva->habitacion_id) {
            $habitacion = Habitacion::findOrFail($reserva->habitacion_id);
            $habitacion->update(['estado' => 'Limpieza']);
        }
        
        $this->auditoriaService->registrar('liberar', 'reservas', $reservaId, null, $reserva->toArray());
        
        return $reserva->fresh();
    }

    public function cambiarEstadoHabitacion(int $habitacionId, string $estado): Habitacion
    {
        $habitacion = Habitacion::findOrFail($habitacionId);
        $habitacion->update(['estado' => $estado]);
        return $habitacion->fresh();
    }

    public function cobrarHorasAdicionales(int $reservaId, int $horas): array
    {
        $reserva = Reserva::with('habitacion')->findOrFail($reservaId);
        $categoria = $reserva->habitacion->categoria ?? 'Suite';
        
        $precios = $this->tarifaService->calcularPrecio(
            $categoria,
            $reserva->fecha->format('Y-m-d'),
            'Hora adicional',
            $horas,
            false
        );
        
        $reserva->increment('horas_adicionales', $horas);
        $reserva->increment('total', $precios['total']);
        
        $this->auditoriaService->registrar('cobrar_horas', 'reservas', $reservaId, null, $reserva->toArray());
        
        return ['reserva' => $reserva->fresh(), 'cobro' => $precios['total']];
    }

    public function buscarPorRUT(string $rut): array
    {
        return Reserva::where('rut', 'LIKE', "%$rut%")
            ->with('habitacion')
            ->orderBy('fecha', 'desc')
            ->get()
            ->toArray();
    }
}
