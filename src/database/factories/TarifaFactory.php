<?php

namespace Database\Factories;

use App\Models\Tarifa;
use Illuminate\Database\Eloquent\Factories\Factory;

class TarifaFactory extends Factory
{
    protected $model = Tarifa::class;

    public function definition(): array
    {
        return [
            'categoria' => fake()->randomElement(['Suite', 'Departamento']),
            'tipo_tiempo' => '8h',
            'precio_dj' => fake()->numberBetween(40000, 50000),
            'precio_viernes' => fake()->numberBetween(45000, 55000),
            'precio_sabado' => fake()->numberBetween(45000, 55000),
            'precio_vispera' => null,
            'activo' => true,
        ];
    }
}
