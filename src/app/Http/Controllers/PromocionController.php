<?php
namespace App\Http\Controllers;

use App\Models\Promocion;
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
        $promociones = Promocion::orderBy('orden')->get();
        return view('admin.promociones.index', compact('promociones'));
    }

    public function create()
    {
        return view('admin.promociones.create');
    }

    public function store(StorePromocionRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('promociones', 'public');
        }
        $promocion = Promocion::create($data);
        $this->auditoriaService->registrar('crear', 'promociones', $promocion->id, null, $promocion->toArray());
        return redirect()->route('admin.promociones.index')->with('success', 'Promoción creada exitosamente.');
    }

    public function edit(Promocion $promocion)
    {
        return view('admin.promociones.edit', compact('promocion'));
    }

    public function update(StorePromocionRequest $request, Promocion $promocion)
    {
        $antiguo = $promocion->toArray();
        $data = $request->validated();
        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('promociones', 'public');
        }
        $promocion->update($data);
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
