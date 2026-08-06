<?php
namespace App\Http\Controllers;

use App\Models\Ocupacion;
use App\Models\Habitacion;
use App\Services\AuditoriaService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
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

    public function informeCierre(Request $request)
    {
        $request->validate([
            'desde' => 'required|date_format:Y-m-d\TH:i',
            'hasta' => 'required|date_format:Y-m-d\TH:i|after_or_equal:desde',
        ]); // periodo del informe

        $desde = Carbon::parse($request->desde);
        $hasta = Carbon::parse($request->hasta);

        $ocupaciones = Ocupacion::with([
            'habitacion',
            'tarifa',
            'promocion',
            'clientes',
            'consumos' => fn ($q) => $q->with('producto')->whereBetween('created_at', [$desde, $hasta])->orderBy('created_at'),
            'pagos' => fn ($q) => $q->whereBetween('created_at', [$desde, $hasta])->orderBy('created_at'),
        ])
            ->whereBetween('fecha_inicio', [$desde, $hasta])
            ->orderBy('fecha_inicio')
            ->get();

        $totalConsumos = $ocupaciones->sum(fn ($o) => $o->consumos->sum('total'));
        $totalPagos = $ocupaciones->sum(fn ($o) => $o->pagos->sum('monto'));
        $totalPropinas = $ocupaciones->sum('propinas');
        $totalOcupaciones = $totalConsumos + $totalPropinas + $ocupaciones->sum('precio_base');

        $totalesPorForma = $ocupaciones
            ->flatMap(fn ($o) => $o->pagos)
            ->groupBy('forma_pago')
            ->map(fn ($p) => $p->sum('monto'));

        $completadas = $ocupaciones->filter(fn ($o) => $o->fecha_fin !== null)->count();
        $enCurso = $ocupaciones->filter(fn ($o) => $o->fecha_fin === null && $o->habitacion?->estado === 'Ocupada')->count();

        $logoPath = public_path('img/logo.png');

        $pdf = Pdf::loadView('admin.ocupaciones.pdf.informe_cierre', compact(
            'ocupaciones', 'desde', 'hasta', 'totalOcupaciones', 'totalConsumos',
            'totalPagos', 'totalPropinas', 'totalesPorForma', 'logoPath',
            'completadas', 'enCurso'
        ));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('informe-cierre-' . $desde->format('Ymd_His') . '-' . $hasta->format('Ymd_His') . '.pdf');
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
