<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Tarifa;

class TarifaPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['administrador', 'recepcionista']);
    }

    public function update(User $user, Tarifa $tarifa): bool
    {
        return $user->role === 'administrador';
    }
}
