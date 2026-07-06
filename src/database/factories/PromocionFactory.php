<?php

namespace Database\Factories;

use App\Models\Promocion;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromocionFactory extends Factory
{
    protected $model = Promocion::class;

    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(3),
            'descripcion' => fake()->paragraph(),
            'imagen' => null,
            'fecha_inicio' => now(),
            'fecha_fin' => now()->addMonth(),
            'activo' => true,
            'orden' => fake()->numberBetween(1, 10),
        ];
    }
}
