<?php
namespace App\Repositories;

use App\Models\Promocion;

class PromocionRepository
{
    public function getAll()
    {
        return Promocion::orderBy('orden')->get();
    }

    public function getActivas()
    {
        return Promocion::activas()->orderBy('orden')->get();
    }

    public function findById(int $id): ?Promocion
    {
        return Promocion::find($id);
    }

    public function create(array $data): Promocion
    {
        return Promocion::create($data);
    }

    public function update(int $id, array $data): Promocion
    {
        $promocion = Promocion::findOrFail($id);
        $promocion->update($data);
        return $promocion->fresh();
    }

    public function delete(int $id): bool
    {
        return Promocion::destroy($id) > 0;
    }
}
