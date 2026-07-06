<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@motellosgatitos.cl',
            'password' => Hash::make('admin123'),
            'role' => 'administrador',
            'rut' => '11.111.111-1',
            'telefono' => '+56 9 1111 1111',
        ]);

        User::create([
            'name' => 'Recepcionista',
            'email' => 'recepcion@motellosgatitos.cl',
            'password' => Hash::make('recepcion123'),
            'role' => 'recepcionista',
            'rut' => '22.222.222-2',
            'telefono' => '+56 9 2222 2222',
        ]);
    }
}
