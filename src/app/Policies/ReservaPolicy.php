<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Reserva;

class ReservaPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['administrador', 'recepcionista']);
    }

    public function view(User $user, Reserva $reserva): bool
    {
        return in_array($user->role, ['administrador', 'recepcionista']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['administrador', 'recepcionista']);
    }

    public function update(User $user, Reserva $reserva): bool
    {
        return in_array($user->role, ['administrador', 'recepcionista']);
    }

    public function delete(User $user, Reserva $reserva): bool
    {
        return $user->role === 'administrador';
    }

    public function liberar(User $user): bool
    {
        return in_array($user->role, ['administrador', 'recepcionista']);
    }
}
