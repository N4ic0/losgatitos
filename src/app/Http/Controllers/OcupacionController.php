<?php
namespace App\Http\Controllers;

use App\Models\Ocupacion;
use App\Models\Habitacion;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;

class OcupacionController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService
    ) {}

    public function index()
    {
        return view('admin.ocupaciones.index');
    }

    public function data()
    {
        $ocupaciones = Ocupacion::with('habitacion', 'tarifa', 'promocion', 'clientes')
            ->orderBy('fecha_inicio', 'desc')
            ->get();

        return response()->json($ocupaciones->map(fn($o) => [
            'id' => $o->id,
            'habitacion' => $o->habitacion?->numero ?? '-',
            'fecha_inicio' => $o->fecha_inicio->format('d/m/Y H:i'),
            'fecha_fin' => $o->fecha_fin?->format('d/m/Y H:i') ?? '-',
            'tarifa' => $o->tarifa?->tipo_tiempo ?? '-',
            'clientes' => $o->clientes->count(),
            'vehiculo' => $o->vehiculo,
            'patente' => $o->patente ?? '-',
            'total' => '$' . number_format($o->total, 0, '', '.'),
            'activa' => $o->fecha_fin === null,
        ]));
    }

    public function show(Ocupacion $ocupacion)
    {
        $ocupacion->load('habitacion', 'tarifa', 'promocion', 'clientes', 'consumos.producto', 'pagos', 'observaciones', 'historialEstados');
        return view('admin.ocupaciones.show', compact('ocupacion'));
    }

    public function destroy(Ocupacion $ocupacion)
    {
        $this->auditoriaService->registrar('eliminar', 'ocupaciones', $ocupacion->id, $ocupacion->toArray(), null);
        $ocupacion->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Ocupación eliminada.']);
        }
        return redirect()->route('admin.ocupaciones.index')->with('success', 'Ocupación eliminada.');
    }
}
