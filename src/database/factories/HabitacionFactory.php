<?php

namespace Database\Factories;

use App\Models\Habitacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class HabitacionFactory extends Factory
{
    protected $model = Habitacion::class;

    public function definition(): array
    {
        static $numero = 101;
        return [
            'numero' => (string) $numero++,
            'categoria' => fake()->randomElement(['Suite', 'Departamento']),
            'estado' => 'Disponible',
            'observaciones' => null,
        ];
    }
}
