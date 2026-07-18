<?php
namespace App\Http\Controllers;

use App\Models\Consumo;
use App\Models\Habitacion;
use App\Models\Ocupacion;
use App\Models\Producto;
use App\Models\Promocion;
use App\Models\Tarifa;
use App\Models\Feriado;
use Illuminate\Support\Facades\Schema;
use App\Services\OcupacionService;
use App\Services\TarifaService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private OcupacionService $ocupacionService,
        private TarifaService $tarifaService
    ) {}

    public function datosHabitacion(Habitacion $habitacion)
    {
        $habitacion->load(['ultimoEstado', 'ocupacionActiva' => function ($q) {
            $q->with(['consumos.producto', 'pagos', 'clientes', 'promocion.productos', 'observaciones.user']);
        }, 'reservaActiva']);

        return response()->json($habitacion);
    }

    public function cambiarEstado(Request $request, Habitacion $habitacion)
    {
        $request->validate(['estado' => 'required|in:Disponible,Reservada,Ocupada,Limpieza,Mantenimiento']);

        if ($habitacion->estado === 'Ocupada' && $request->estado !== 'Disponible') {
            return response()->json(['error' => 'Debe finalizar la ocupación primero.'], 422);
        }

        $this->ocupacionService->cambiarEstado($habitacion, $request->estado);

        return response()->json(['success' => true, 'estado' => $request->estado]);
    }

    public function iniciarOcupacion(Request $request, Habitacion $habitacion)
    {
        $request->validate([
            'tipo_tiempo' => 'required|in:3h,8h',
            'personas_adicionales' => 'nullable|integer|min:0|max:10',
        ]);

        if (!in_array($habitacion->estado, ['Disponible', 'Reservada'])) {
            return response()->json(['error' => 'La habitación debe estar disponible o reservada.'], 422);
        }

        $ocupacion = $this->ocupacionService->iniciarOcupacion($habitacion, $request->tipo_tiempo, $request->integer('personas_adicionales', 0));

        return response()->json(['success' => true, 'ocupacion' => $ocupacion->load('clientes', 'consumos.producto', 'pagos', 'tarifa')]);
    }

    public function calcularTarifa(Request $request)
    {
        $request->validate([
            'categoria' => 'required|in:Suite,Departamento',
            'tipo_tiempo' => 'required|in:3h,8h',
        ]);

        $hoy = now();
        $manana = $hoy->copy()->addDay()->startOfDay();
        $esVispera = Feriado::whereDate('fecha', $manana)->exists();
        $dia = $hoy->dayOfWeek;

        $tarifa = Tarifa::where('categoria', $request->categoria)
            ->where('tipo_tiempo', $request->tipo_tiempo)
            ->where('activo', true)
            ->first();

        if (!$tarifa) {
            return response()->json(['error' => 'Tarifa no encontrada'], 404);
        }

        if ($esVispera) {
            $precio = $tarifa->precio_vispera ?? $tarifa->precio_dj;
            $regla = 'Víspera de feriado';
        } elseif (in_array($dia, [0, 1, 2, 3, 4])) {
            $precio = $tarifa->precio_dj;
            $regla = $dia === 0 ? 'Domingo' : 'D-J';
        } elseif ($dia === 5) {
            $precio = $tarifa->precio_viernes;
            $regla = 'Viernes';
        } else {
            $precio = $tarifa->precio_sabado;
            $regla = 'Sábado';
        }

        return response()->json([
            'success' => true,
            'precio' => $precio,
            'regla' => $regla,
            'tarifa_id' => $tarifa->id,
            'categoria' => $tarifa->categoria,
            'tipo_tiempo' => $tarifa->tipo_tiempo,
            'hora_inicio' => $tarifa->hora_inicio,
            'hora_termino' => $tarifa->hora_termino,
        ]);
    }

    public function datosOcupacion(Ocupacion $ocupacion)
    {
        $data = $this->ocupacionService->getDatosOcupacion($ocupacion);
        return response()->json($data);
    }

    public function registrarCliente(Request $request, Ocupacion $ocupacion)
    {
        $request->validate([
            'tipo_documento' => 'required|in:RUT,Pasaporte',
            'numero_documento' => 'required|string',
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'nacionalidad' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
        ]);

        $cliente = $this->ocupacionService->registrarCliente($ocupacion, $request->all());

        return response()->json(['success' => true, 'cliente' => $cliente]);
    }

    public function agregarConsumo(Request $request, Ocupacion $ocupacion)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        try {
            $consumo = $this->ocupacionService->agregarConsumo($ocupacion, $request->producto_id, $request->cantidad);
            return response()->json(['success' => true, 'consumo' => $consumo->load('producto')]);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
        }
    }

    public function agregarCortesia(Request $request, Ocupacion $ocupacion)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        try {
            $consumo = $this->ocupacionService->agregarCortesia($ocupacion, $request->producto_id, $request->cantidad);
            return response()->json(['success' => true, 'consumo' => $consumo->load('producto')]);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
        }
    }

    public function agregarConsumosBatch(Request $request, Ocupacion $ocupacion)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'cortesia' => 'boolean',
        ]);

        try {
            $consumos = $this->ocupacionService->agregarConsumosBatch(
                $ocupacion,
                $request->items,
                $request->boolean('cortesia')
            );
            return response()->json(['success' => true, 'consumos' => $consumos]);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
        }
    }

    public function actualizarConsumo(Request $request, Consumo $consumo)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:0',
        ]);

        if ($request->cantidad < 1) {
            $this->ocupacionService->eliminarConsumo($consumo);
            return response()->json(['success' => true, 'deleted' => true]);
        }

        $consumo = $this->ocupacionService->actualizarConsumo($consumo, $request->cantidad);
        return response()->json(['success' => true, 'consumo' => $consumo->load('producto')]);
    }

    public function eliminarConsumo(Consumo $consumo)
    {
        $this->ocupacionService->eliminarConsumo($consumo);
        return response()->json(['success' => true]);
    }

    public function registrarPago(Request $request, Ocupacion $ocupacion)
    {
        $request->validate([
            'monto' => 'required|integer|min:1',
            'forma_pago' => 'required|in:efectivo,transferencia,tarjeta',
        ]);

        $pago = $this->ocupacionService->registrarPago($ocupacion, $request->monto, $request->forma_pago);

        $ocupacion->refresh();

        return response()->json(['success' => true, 'pago' => $pago, 'saldo_restante' => $ocupacion->saldo]);
    }

    public function finalizarOcupacion(Ocupacion $ocupacion)
    {
        $this->ocupacionService->finalizarOcupacion($ocupacion);
        return response()->json(['success' => true]);
    }

    public function actualizarVehiculo(Request $request, Ocupacion $ocupacion)
    {
        $request->validate([
            'vehiculo' => 'required|boolean',
            'patente' => 'nullable|string|max:20',
        ]);

        $ocupacion->update([
            'vehiculo' => $request->boolean('vehiculo'),
            'patente' => $request->patente ? strtoupper($request->patente) : null,
        ]);

        return response()->json(['success' => true]);
    }

    public function actualizarPersonasAdicionales(Request $request, Ocupacion $ocupacion)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:0|max:10',
        ]);

        $ocupacion = $this->ocupacionService->actualizarPersonasAdicionales($ocupacion, $request->integer('cantidad'));

        return response()->json([
            'success' => true,
            'ocupacion' => $ocupacion->load('consumos.producto', 'pagos', 'clientes', 'promocion.productos', 'tarifa'),
        ]);
    }

    public function agregarObservacion(Request $request, Ocupacion $ocupacion)
    {
        $request->validate(['contenido' => 'required|string']);

        $observacion = $this->ocupacionService->agregarObservacion($ocupacion, $request->contenido);

        return response()->json(['success' => true, 'observacion' => $observacion->load('user')]);
    }

    public function productos()
    {
        $columns = ['id', 'nombre', 'precio', 'imagen', 'categoria'];
        if (Schema::hasColumn('productos', 'cortesia')) {
            $columns[] = 'cortesia';
        }

        $productos = Producto::where('activo', true)
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->get($columns);

        return response()->json($productos);
    }

    public function promociones()
    {
        $promociones = Promocion::with('productos')
            ->activas()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($promociones);
    }

    public function tomarPromocion(Ocupacion $ocupacion, Promocion $promocion)
    {
        $data = $this->ocupacionService->tomarPromocion($ocupacion, $promocion);
        return response()->json(['success' => true] + $data);
    }

    public function agregarProductosPromocion(Request $request, Ocupacion $ocupacion)
    {
        try {
            $promocionId = $request->input('promocion_id', $ocupacion->promocion_id);
            $promocion = $promocionId ? Promocion::findOrFail($promocionId) : null;

            if (!$promocion) {
                return response()->json(['success' => false, 'error' => 'No se especificó una promoción.'], 400);
            }

            $data = $this->ocupacionService->agregarProductosPromocion($ocupacion, $promocion);
            return response()->json(['success' => true] + $data);
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }
}
