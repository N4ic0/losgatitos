<?php
namespace App\Repositories;

use App\Models\Tarifa;

class TarifaRepository
{
    public function getAll()
    {
        return Tarifa::orderBy('categoria')->orderBy('tipo_tiempo')->get();
    }

    public function findById(int $id): ?Tarifa
    {
        return Tarifa::find($id);
    }

    public function update(int $id, array $data): Tarifa
    {
        $tarifa = Tarifa::findOrFail($id);
        $tarifa->update($data);
        return $tarifa->fresh();
    }
}
