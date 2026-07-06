<?php

namespace Database\Seeders;

use App\Models\Tarifa;
use Illuminate\Database\Seeder;

class TarifaSeeder extends Seeder
{
    public function run(): void
    {
        Tarifa::create([
            'categoria' => 'Suite',
            'tipo_tiempo' => '8h',
            'precio_dj' => 44200,
            'precio_viernes' => 47200,
            'precio_sabado' => 47200,
            'precio_vispera' => null,
            'activo' => true,
        ]);

        Tarifa::create([
            'categoria' => 'Departamento',
            'tipo_tiempo' => '8h',
            'precio_dj' => 49200,
            'precio_viernes' => 53200,
            'precio_sabado' => 53200,
            'precio_vispera' => null,
            'activo' => true,
        ]);

        Tarifa::create([
            'categoria' => 'Suite',
            'tipo_tiempo' => 'Hora adicional',
            'precio_dj' => 5500,
            'precio_viernes' => 6000,
            'precio_sabado' => 6000,
            'precio_vispera' => null,
            'activo' => true,
        ]);

        Tarifa::create([
            'categoria' => 'Departamento',
            'tipo_tiempo' => 'Hora adicional',
            'precio_dj' => 5500,
            'precio_viernes' => 6000,
            'precio_sabado' => 6000,
            'precio_vispera' => null,
            'activo' => true,
        ]);
    }
}
