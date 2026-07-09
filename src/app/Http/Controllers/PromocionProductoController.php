<?php
namespace App\Http\Controllers;

use App\Models\Promocion;
use App\Models\Producto;
use App\Models\PromocionProducto;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;

class PromocionProductoController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService
    ) {}

    public function index()
    {
        $items = PromocionProducto::with('promocion', 'producto')->orderBy('nombre')->orderBy('promocion_id')->get();
        return view('admin.promocion-productos.index', compact('items'));
    }

    public function create()
    {
        $promociones = Promocion::orderBy('titulo')->get();
        $productos = Producto::orderBy('categoria')->orderBy('nombre')->get();
        return view('admin.promocion-productos.create', compact('promociones', 'productos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'nullable|string|max:255',
            'promocion_id' => 'required|exists:promociones,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $item = PromocionProducto::create($data);
        $this->auditoriaService->registrar('crear', 'promocion_producto', $item->id, null, $item->toArray());
        return redirect()->route('admin.promocion-productos.index')->with('success', 'Paquete creado exitosamente.');
    }

    public function edit(PromocionProducto $promocionProducto)
    {
        $promocionProducto->load('promocion', 'producto');
        $promociones = Promocion::orderBy('titulo')->get();
        $productos = Producto::orderBy('categoria')->orderBy('nombre')->get();
        return view('admin.promocion-productos.edit', compact('promocionProducto', 'promociones', 'productos'));
    }

    public function update(Request $request, PromocionProducto $promocionProducto)
    {
        $antiguo = $promocionProducto->toArray();

        $data = $request->validate([
            'nombre' => 'nullable|string|max:255',
            'promocion_id' => 'required|exists:promociones,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $promocionProducto->update($data);
        $this->auditoriaService->registrar('modificar', 'promocion_producto', $promocionProducto->id, $antiguo, $promocionProducto->toArray());
        return redirect()->route('admin.promocion-productos.index')->with('success', 'Paquete actualizado exitosamente.');
    }

    public function destroy(PromocionProducto $promocionProducto)
    {
        $this->auditoriaService->registrar('eliminar', 'promocion_producto', $promocionProducto->id, $promocionProducto->toArray(), null);
        $promocionProducto->delete();
        return redirect()->route('admin.promocion-productos.index')->with('success', 'Paquete eliminado.');
    }
}
