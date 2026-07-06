<?php
namespace App\Http\Controllers;

use App\Models\Tarifa;
use App\Http\Requests\StoreTarifaRequest;
use App\Services\AuditoriaService;

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

    public function edit(Tarifa $tarifa)
    {
        return view('admin.tarifas.edit', compact('tarifa'));
    }

    public function update(StoreTarifaRequest $request, Tarifa $tarifa)
    {
        $antiguo = $tarifa->toArray();
        $tarifa->update($request->validated());
        $this->auditoriaService->registrar('modificar', 'tarifas', $tarifa->id, $antiguo, $tarifa->toArray());
        return redirect()->route('admin.tarifas.index')->with('success', 'Tarifa actualizada exitosamente.');
    }
}
