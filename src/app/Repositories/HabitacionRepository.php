<?php
namespace App\Repositories;

use App\Models\Habitacion;

class HabitacionRepository
{
    public function getAll()
    {
        return Habitacion::orderBy('numero')->get();
    }

    public function getDisponibles()
    {
        return Habitacion::where('estado', 'Disponible')->orderBy('numero')->get();
    }

    public function findById(int $id): ?Habitacion
    {
        return Habitacion::find($id);
    }

    public function create(array $data): Habitacion
    {
        return Habitacion::create($data);
    }

    public function update(int $id, array $data): Habitacion
    {
        $habitacion = Habitacion::findOrFail($id);
        $habitacion->update($data);
        return $habitacion->fresh();
    }

    public function delete(int $id): bool
    {
        return Habitacion::destroy($id) > 0;
    }

    public function getReservasActivas()
    {
        return Habitacion::with(['reservaActiva' => function($q) {
            $q->with('habitacion');
        }])->get();
    }
}
