<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Habitacion;

class HabitacionPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['administrador', 'recepcionista']);
    }

    public function create(User $user): bool
    {
        return $user->role === 'administrador';
    }

    public function update(User $user, Habitacion $habitacion): bool
    {
        return $user->role === 'administrador';
    }

    public function delete(User $user, Habitacion $habitacion): bool
    {
        return $user->role === 'administrador';
    }
}
