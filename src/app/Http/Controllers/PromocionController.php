<?php
namespace App\Http\Controllers;

use App\Models\Promocion;
use App\Models\Producto;
use App\Http\Requests\StorePromocionRequest;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;

class PromocionController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService
    ) {}

    public function index()
    {
        $productos = Producto::orderBy('categoria')->orderBy('nombre')->get(['id', 'nombre', 'categoria', 'precio', 'stock_actual', 'activo']);
        return view('admin.promociones.index', compact('productos'));
    }

    public function data()
    {
        $promociones = Promocion::orderBy('created_at', 'desc')->withCount('productos')->get();
        return response()->json($promociones->map(fn($p) => [
            'id' => $p->id,
            'titulo' => $p->titulo,
            'horario' => $p->desde ? $p->desde . ' - ' . $p->hasta : '-',
            'valor' => $p->valor ? '$' . number_format($p->valor, 0, '', '.') : '-',
            'horas_beneficio' => $p->horas_beneficio,
            'tarifas' => $p->tarifas ? implode(', ', array_map(fn($t) => str_replace('_', ' ', $t), $p->tarifas)) : '-',
            'fecha_inicio' => $p->fecha_inicio->format('d/m/Y'),
            'fecha_fin' => $p->fecha_fin->format('d/m/Y'),
            'activo' => $p->activo,
            'productos_count' => $p->productos_count,
        ]));
    }

    public function getJson(Promocion $promocion)
    {
        $promocion->load('productos');
        return response()->json($promocion);
    }

    public function create()
    {
        return redirect()->route('admin.promociones.index');
    }

    public function store(StorePromocionRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('promociones', 'public');
        }
        $promocion = Promocion::create($data);
        $this->syncProductos($promocion, $request);
        $this->auditoriaService->registrar('crear', 'promociones', $promocion->id, null, $promocion->toArray());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Promoción creada exitosamente.']);
        }
        return redirect()->route('admin.promociones.index')->with('success', 'Promoción creada exitosamente.');
    }

    public function edit(Promocion $promocion)
    {
        return redirect()->route('admin.promociones.index');
    }

    public function update(StorePromocionRequest $request, Promocion $promocion)
    {
        $antiguo = $promocion->toArray();
        $data = $request->validated();
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('promociones', 'public');
        }
        $promocion->update($data);
        $this->syncProductos($promocion, $request);
        $this->auditoriaService->registrar('modificar', 'promociones', $promocion->id, $antiguo, $promocion->toArray());

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Promoción actualizada exitosamente.']);
        }
        return redirect()->route('admin.promociones.index')->with('success', 'Promoción actualizada exitosamente.');
    }

    public function destroy(Promocion $promocion)
    {
        $this->auditoriaService->registrar('eliminar', 'promociones', $promocion->id, $promocion->toArray(), null);
        $promocion->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Promoción eliminada.']);
        }
        return redirect()->route('admin.promociones.index')->with('success', 'Promoción eliminada.');
    }

    private function syncProductos(Promocion $promocion, Request $request)
    {
        if ($request->has('productos')) {
            $productos = [];
            foreach ($request->productos as $productoId) {
                if (!$productoId) continue;
                $productos[$productoId] = [
                    'cantidad' => $request->cantidades[$productoId] ?? 1,
                    'valor_promocion' => $request->valores_promocion[$productoId] ?? null,
                ];
            }
            $promocion->productos()->sync($productos);
        }
    }
}
