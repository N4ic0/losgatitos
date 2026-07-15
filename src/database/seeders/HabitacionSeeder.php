<?php

namespace Database\Seeders;

use App\Models\Habitacion;
use Illuminate\Database\Seeder;

class HabitacionSeeder extends Seeder
{
    public function run(): void
    {
        $habitaciones = [
            ['numero' => 'A', 'categoria' => 'Suite'],
            ['numero' => 'B', 'categoria' => 'Suite'],
            ['numero' => 'S', 'categoria' => 'Suite'],
            ['numero' => '4', 'categoria' => 'Suite'],
            ['numero' => '5', 'categoria' => 'Suite'],
            ['numero' => '6', 'categoria' => 'Suite'],
            ['numero' => '7', 'categoria' => 'Suite'],
            ['numero' => '8', 'categoria' => 'Suite'],
            ['numero' => '9', 'categoria' => 'Suite'],
            ['numero' => '10', 'categoria' => 'Suite'],
            ['numero' => '21', 'categoria' => 'Suite'],
            ['numero' => '22', 'categoria' => 'Suite'],
            ['numero' => '23', 'categoria' => 'Suite'],
            ['numero' => '24', 'categoria' => 'Suite'],
            ['numero' => '25', 'categoria' => 'Suite'],
            ['numero' => '1', 'categoria' => 'Departamento'],
            ['numero' => '2', 'categoria' => 'Departamento'],
            ['numero' => '3', 'categoria' => 'Departamento'],
            ['numero' => '26', 'categoria' => 'Departamento'],
            ['numero' => '27', 'categoria' => 'Departamento'],
            ['numero' => '28', 'categoria' => 'Departamento'],
            ['numero' => '29', 'categoria' => 'Departamento'],
            ['numero' => '30', 'categoria' => 'Departamento'],
            ['numero' => '31', 'categoria' => 'Departamento'],
            ['numero' => '32', 'categoria' => 'Departamento'],
        ];

        foreach ($habitaciones as $h) {
            Habitacion::create($h);
        }
    }
}
