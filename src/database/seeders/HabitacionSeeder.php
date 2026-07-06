<?php

namespace Database\Seeders;

use App\Models\Habitacion;
use Illuminate\Database\Seeder;

class HabitacionSeeder extends Seeder
{
    public function run(): void
    {
        $habitaciones = [
            ['numero' => '101', 'categoria' => 'Suite'],
            ['numero' => '102', 'categoria' => 'Suite'],
            ['numero' => '103', 'categoria' => 'Suite'],
            ['numero' => '104', 'categoria' => 'Suite'],
            ['numero' => '201', 'categoria' => 'Departamento'],
            ['numero' => '202', 'categoria' => 'Departamento'],
            ['numero' => '203', 'categoria' => 'Departamento'],
            ['numero' => '204', 'categoria' => 'Departamento'],
        ];

        foreach ($habitaciones as $h) {
            Habitacion::create($h);
        }
    }
}
