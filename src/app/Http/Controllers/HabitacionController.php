<?php
namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Http\Requests\StoreHabitacionRequest;
use App\Services\AuditoriaService;

class HabitacionController extends Controller
{
    public function __construct(
        private AuditoriaService $auditoriaService
    ) {}

    public function index()
    {
        $habitaciones = Habitacion::orderBy('numero')->get();
        return view('admin.habitaciones.index', compact('habitaciones'));
    }

    public function create()
    {
        return view('admin.habitaciones.create');
    }

    public function store(StoreHabitacionRequest $request)
    {
        $habitacion = Habitacion::create($request->validated());
        $this->auditoriaService->registrar('crear', 'habitaciones', $habitacion->id, null, $habitacion->toArray());
        return redirect()->route('admin.habitaciones.index')->with('success', 'Habitación creada exitosamente.');
    }

    public function edit(Habitacion $habitacion)
    {
        return view('admin.habitaciones.edit', compact('habitacion'));
    }

    public function update(StoreHabitacionRequest $request, Habitacion $habitacion)
    {
        $antiguo = $habitacion->toArray();
        $habitacion->update($request->validated());
        $this->auditoriaService->registrar('modificar', 'habitaciones', $habitacion->id, $antiguo, $habitacion->toArray());
        return redirect()->route('admin.habitaciones.index')->with('success', 'Habitación actualizada exitosamente.');
    }

    public function destroy(Habitacion $habitacion)
    {
        $this->auditoriaService->registrar('eliminar', 'habitaciones', $habitacion->id, $habitacion->toArray(), null);
        $habitacion->delete();
        return redirect()->route('admin.habitaciones.index')->with('success', 'Habitación eliminada exitosamente.');
    }
}
