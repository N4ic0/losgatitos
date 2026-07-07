<?php
namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\Ocupacion;
use App\Models\Producto;
use App\Models\Promocion;
use App\Services\OcupacionService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private OcupacionService $ocupacionService
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
        $request->validate(['promocion_id' => 'nullable|exists:promociones,id']);

        if (!in_array($habitacion->estado, ['Disponible', 'Reservada'])) {
            return response()->json(['error' => 'La habitación debe estar disponible o reservada.'], 422);
        }

        $ocupacion = $this->ocupacionService->iniciarOcupacion($habitacion, $request->promocion_id);

        return response()->json(['success' => true, 'ocupacion' => $ocupacion->load('clientes', 'consumos.producto', 'pagos', 'promocion')]);
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

        $consumo = $this->ocupacionService->agregarConsumo($ocupacion, $request->producto_id, $request->cantidad);

        return response()->json(['success' => true, 'consumo' => $consumo->load('producto')]);
    }

    public function registrarPago(Request $request, Ocupacion $ocupacion)
    {
        $request->validate([
            'monto' => 'required|integer|min:1',
            'forma_pago' => 'required|in:efectivo,transferencia,tarjeta',
        ]);

        $pago = $this->ocupacionService->registrarPago($ocupacion, $request->monto, $request->forma_pago);

        $restante = $ocupacion->fresh()->saldo;

        if ($restante <= 0) {
            $this->ocupacionService->finalizarOcupacion($ocupacion);
        }

        return response()->json(['success' => true, 'pago' => $pago, 'saldo_restante' => $restante]);
    }

    public function finalizarOcupacion(Ocupacion $ocupacion)
    {
        $this->ocupacionService->finalizarOcupacion($ocupacion);
        return response()->json(['success' => true]);
    }

    public function agregarObservacion(Request $request, Ocupacion $ocupacion)
    {
        $request->validate(['contenido' => 'required|string']);

        $observacion = $this->ocupacionService->agregarObservacion($ocupacion, $request->contenido);

        return response()->json(['success' => true, 'observacion' => $observacion->load('user')]);
    }

    public function productos()
    {
        $productos = Producto::where('activo', true)
            ->orderBy('categoria')
            ->orderBy('nombre')
            ->get();

        return response()->json($productos);
    }

    public function promociones()
    {
        $promociones = Promocion::with('productos')
            ->activas()
            ->orderBy('orden')
            ->get();

        return response()->json($promociones);
    }
}
