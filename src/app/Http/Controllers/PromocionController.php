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
        $promociones = Promocion::orderBy('created_at', 'desc')->get();
        return view('admin.promociones.index', compact('promociones'));
    }

    public function create()
    {
        $productos = Producto::orderBy('categoria')->orderBy('nombre')->get();
        return view('admin.promociones.create', compact('productos'));
    }

    public function store(StorePromocionRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('promociones', 'public');
        }
        $promocion = Promocion::create($data);
        if ($request->has('productos')) {
            $productos = [];
            foreach ($request->productos as $productoId) {
                $productos[$productoId] = ['cantidad' => $request->cantidades[$productoId] ?? 1];
            }
            $promocion->productos()->sync($productos);
        }
        $this->auditoriaService->registrar('crear', 'promociones', $promocion->id, null, $promocion->toArray());
        return redirect()->route('admin.promociones.index')->with('success', 'Promoción creada exitosamente.');
    }

    public function edit(Promocion $promocion)
    {
        $productos = Producto::orderBy('categoria')->orderBy('nombre')->get();
        $promocion->load('productos');
        return view('admin.promociones.edit', compact('promocion', 'productos'));
    }

    public function update(StorePromocionRequest $request, Promocion $promocion)
    {
        $antiguo = $promocion->toArray();
        $data = $request->validated();
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('promociones', 'public');
        }
        $promocion->update($data);
        if ($request->has('productos')) {
            $productos = [];
            foreach ($request->productos as $productoId) {
                $productos[$productoId] = ['cantidad' => $request->cantidades[$productoId] ?? 1];
            }
            $promocion->productos()->sync($productos);
        } else {
            $promocion->productos()->detach();
        }
        $this->auditoriaService->registrar('modificar', 'promociones', $promocion->id, $antiguo, $promocion->toArray());
        return redirect()->route('admin.promociones.index')->with('success', 'Promoción actualizada exitosamente.');
    }

    public function destroy(Promocion $promocion)
    {
        $this->auditoriaService->registrar('eliminar', 'promociones', $promocion->id, $promocion->toArray(), null);
        $promocion->delete();
        return redirect()->route('admin.promociones.index')->with('success', 'Promoción eliminada.');
    }
}
