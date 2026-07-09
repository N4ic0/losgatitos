<?php
namespace App\Http\Controllers;

use App\Models\Ocupacion;
use App\Models\Habitacion;
use App\Models\Tarifa;
use App\Models\Promocion;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;

class OcupacionController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService
    ) {}

    public function index(Request $request)
    {
        $query = Ocupacion::with('habitacion', 'tarifa', 'promocion', 'clientes');

        if ($request->filled('habitacion_id')) {
            $query->where('habitacion_id', $request->habitacion_id);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_inicio', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_inicio', '<=', $request->fecha_hasta);
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'activa') {
                $query->whereNull('fecha_fin');
            } elseif ($request->estado === 'finalizada') {
                $query->whereNotNull('fecha_fin');
            }
        }

        $ocupaciones = $query->orderBy('fecha_inicio', 'desc')->paginate(20);
        $habitaciones = Habitacion::orderBy('numero')->get();

        return view('admin.ocupaciones.index', compact('ocupaciones', 'habitaciones'));
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
        return redirect()->route('admin.ocupaciones.index')->with('success', 'Ocupación eliminada.');
    }
}
