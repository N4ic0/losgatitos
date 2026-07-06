<?php

namespace Database\Seeders;

use App\Models\Promocion;
use Illuminate\Database\Seeder;

class PromocionSeeder extends Seeder
{
    public function run(): void
    {
        Promocion::create([
            'titulo' => 'Promoción Noche Especial',
            'descripcion' => 'Domingo a Jueves de 21:00 a 00:00 hrs. La estadía pasa de 8 horas a 12 horas. Incluye cortesía: 2 té o café, 2 tostadas, mantequilla y mermelada.',
            'imagen' => null,
            'fecha_inicio' => now(),
            'fecha_fin' => now()->addYear(),
            'activo' => true,
            'orden' => 1,
        ]);
    }
}
