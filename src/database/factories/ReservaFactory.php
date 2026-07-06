<?php

namespace Database\Factories;

use App\Models\Reserva;
use App\Models\Habitacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservaFactory extends Factory
{
    protected $model = Reserva::class;

    public function definition(): array
    {
        return [
            'rut' => fake()->unique()->bothify('##.###.###-#'),
            'nombre' => fake()->name(),
            'email' => fake()->email(),
            'telefono' => fake()->phoneNumber(),
            'fecha' => fake()->dateTimeBetween('now', '+1 month'),
            'hora' => fake()->randomElement(['21:00', '22:00', '23:00', '00:00']),
            'personas' => fake()->numberBetween(2, 4),
            'observaciones' => fake()->optional()->sentence(),
            'estado' => fake()->randomElement(['Reservada', 'Ingresada', 'Finalizada', 'Cancelada']),
            'habitacion_id' => Habitacion::factory(),
            'precio_base' => fake()->numberBetween(40000, 55000),
            'total' => fake()->numberBetween(40000, 70000),
        ];
    }
}
