<?php
namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Http\Requests\StoreProductoRequest;
use App\Services\AuditoriaService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProductoController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService
    ) {}

    public function index()
    {
        return view('admin.productos.index');
    }

    public function catalogo()
    {
        $productos = Producto::where('activo', true)->orderBy('categoria')->orderBy('nombre')->get()->groupBy('categoria');
        $iconoPath = public_path('img/icono.png');
        $pdf = Pdf::loadView('admin.productos.pdf.catalogo', compact('productos', 'iconoPath'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('catalogo-productos.pdf');
    }

    public function data()
    {
        $productos = Producto::orderBy('categoria')->orderBy('nombre')->get();
        return response()->json($productos->map(fn($p) => [
            'id' => $p->id,
            'nombre' => $p->nombre,
            'categoria' => $p->categoria,
            'precio' => '$' . number_format($p->precio, 0, '', '.'),
            'precio_raw' => $p->precio,
            'factor' => strtoupper($p->factor ?? 'unidad'),
            'stock_actual' => (float)$p->stock_actual,
            'stock_minimo' => (float)$p->stock_minimo,
            'stock_maximo' => (float)$p->stock_maximo,
            'sin_stock' => $p->stock_actual <= 0,
            'bajo_stock' => $p->stock_maximo > 0 && $p->stock_actual <= $p->stock_minimo,
            'activo' => $p->activo,
            'cortesia' => $p->cortesia,
        ]));
    }

    public function create()
    {
        $categorias = $this->obtenerCategorias();
        return view('admin.productos.create', compact('categorias'));
    }

    public function store(StoreProductoRequest $request)
    {
        $data = $request->validated();
        $data['activo'] = $request->boolean('activo');
        $data['cortesia'] = $request->boolean('cortesia');
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }
        $producto = Producto::create($data);
        $this->auditoriaService->registrar('crear', 'productos', $producto->id, null, $producto->toArray());
        return redirect()->route('admin.productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function edit(Producto $producto)
    {
        $categorias = $this->obtenerCategorias();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    private function obtenerCategorias()
    {
        if (!Schema::hasTable('categorias')) {
            return collect([
                (object) ['id' => 0, 'nombre' => 'Producto'],
                (object) ['id' => 0, 'nombre' => 'Colacion'],
            ]);
        }
        return Categoria::orderBy('nombre')->get();
    }

    public function update(StoreProductoRequest $request, Producto $producto)
    {
        $antiguo = $producto->toArray();
        $data = $request->validated();
        $data['activo'] = $request->boolean('activo');
        $data['cortesia'] = $request->boolean('cortesia');
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }
        $producto->update($data);
        $this->auditoriaService->registrar('modificar', 'productos', $producto->id, $antiguo, $producto->toArray());
        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Producto $producto)
    {
        $this->auditoriaService->registrar('eliminar', 'productos', $producto->id, $producto->toArray(), null);
        $producto->delete();
        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado.');
    }

    public function toggle(Request $request, Producto $producto)
    {
        $request->validate([
            'field' => 'required|in:activo,cortesia',
        ]);

        $field = $request->field;
        $antiguo = $producto->toArray();
        $producto->$field = !$producto->$field;
        $producto->save();

        $this->auditoriaService->registrar('modificar', 'productos', $producto->id, $antiguo, $producto->toArray());

        return response()->json(['success' => true, 'value' => $producto->$field]);
    }
}
