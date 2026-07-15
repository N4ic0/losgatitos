<?php
namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService
    ) {}

    public function index()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('categorias')) {
            return response()->json([
                ['id' => 0, 'nombre' => 'Producto'],
                ['id' => 0, 'nombre' => 'Colacion'],
            ]);
        }
        return Categoria::orderBy('nombre')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre',
        ]);

        $categoria = Categoria::create($data);

        $this->auditoriaService->registrar('crear', 'categorias', $categoria->id, null, $categoria->toArray());

        return response()->json($categoria);
    }

    public function update(Request $request, Categoria $categoria)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre,' . $categoria->id,
        ]);

        $antiguo = $categoria->toArray();
        $categoria->update($data);

        // Update all products using the old category name
        \App\Models\Producto::where('categoria', $antiguo['nombre'])
            ->update(['categoria' => $categoria->nombre]);

        $this->auditoriaService->registrar('modificar', 'categorias', $categoria->id, $antiguo, $categoria->toArray());

        return response()->json($categoria);
    }
}
