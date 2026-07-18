<?php
namespace App\Http\Controllers;

use App\Models\Tarifa;
use App\Http\Requests\StoreTarifaRequest;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;

class TarifaController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService
    ) {}

    public function index()
    {
        $tarifas = Tarifa::orderBy('categoria')->orderBy('tipo_tiempo')->get();
        return view('admin.tarifas.index', compact('tarifas'));
    }

    public function data()
    {
        $tarifas = Tarifa::orderBy('categoria')->orderBy('tipo_tiempo')->get();
        return response()->json($tarifas->map(fn($t) => [
            'id' => $t->id,
            'categoria' => $t->categoria,
            'tipo_tiempo' => $t->tipo_tiempo,
            'precio_dj' => '$' . number_format($t->precio_dj, 0, '', '.'),
            'precio_viernes' => '$' . number_format($t->precio_viernes, 0, '', '.'),
            'precio_sabado' => '$' . number_format($t->precio_sabado, 0, '', '.'),
            'precio_vispera' => $t->precio_vispera ? '$' . number_format($t->precio_vispera, 0, '', '.') : '-',
            'hora_inicio' => $t->hora_inicio ?? '-',
            'hora_termino' => $t->hora_termino ?? '-',
            'activo' => $t->activo,
        ]));
    }

    public function getJson(Tarifa $tarifa)
    {
        return response()->json($tarifa);
    }

    public function edit(Tarifa $tarifa)
    {
        return view('admin.tarifas.edit', compact('tarifa'));
    }

    public function update(StoreTarifaRequest $request, Tarifa $tarifa)
    {
        $antiguo = $tarifa->toArray();
        $data = $request->validated();
        $data['activo'] = $request->boolean('activo');
        $tarifa->update($data);
        $this->auditoriaService->registrar('modificar', 'tarifas', $tarifa->id, $antiguo, $tarifa->toArray());
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Tarifa actualizada exitosamente.']);
        }
        return redirect()->route('admin.tarifas.index')->with('success', 'Tarifa actualizada exitosamente.');
    }

    public function toggle(Request $request, Tarifa $tarifa)
    {
        $request->validate(['field' => 'required|in:activo']);
        $antiguo = $tarifa->toArray();
        $tarifa->activo = !$tarifa->activo;
        $tarifa->save();
        $this->auditoriaService->registrar('modificar', 'tarifas', $tarifa->id, $antiguo, $tarifa->toArray());
        return response()->json(['success' => true, 'value' => $tarifa->activo]);
    }
}
