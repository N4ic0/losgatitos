<?php
namespace App\Repositories;

use App\Models\Reserva;
use Carbon\Carbon;

class ReservaRepository
{
    public function getAll()
    {
        return Reserva::with('habitacion', 'user')->orderBy('fecha', 'desc')->get();
    }

    public function findById(int $id): ?Reserva
    {
        return Reserva::with('habitacion', 'user')->find($id);
    }

    public function create(array $data): Reserva
    {
        return Reserva::create($data);
    }

    public function update(int $id, array $data): Reserva
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->update($data);
        return $reserva->fresh();
    }

    public function delete(int $id): bool
    {
        return Reserva::destroy($id) > 0;
    }

    public function getReservasDelDia(string $fecha = null)
    {
        $fecha = $fecha ?? now()->format('Y-m-d');
        return Reserva::with('habitacion')
            ->whereDate('fecha', $fecha)
            ->orderBy('hora')
            ->get();
    }

    public function getReservasActivas()
    {
        return Reserva::with('habitacion')
            ->whereIn('estado', ['Reservada', 'Ingresada'])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();
    }

    public function getReservasPorRUT(string $rut)
    {
        return Reserva::with('habitacion')
            ->where('rut', 'LIKE', "%$rut%")
            ->orderBy('fecha', 'desc')
            ->get();
    }
}
