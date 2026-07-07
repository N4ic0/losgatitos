<?php

namespace Database\Seeders;

use App\Models\Promocion;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class PromocionSeeder extends Seeder
{
    public function run(): void
    {
        $promocion = Promocion::create([
            'titulo' => 'Promoción Noche Especial',
            'descripcion' => 'Domingo a Jueves de 21:00 a 00:00 hrs. La estadía pasa de 8 horas a 12 horas. Incluye cortesía: 2 té o café, 2 tostadas, mantequilla y mermelada.',
            'imagen' => null,
            'fecha_inicio' => now(),
            'fecha_fin' => now()->addYear(),
            'activo' => true,
            'orden' => 1,
            'reglas' => [
                'dias' => ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves'],
                'tipo_tiempo' => '8h',
                'horas_beneficio' => 12,
                'hora_desde' => '21:00',
                'hora_hasta' => '00:00',
            ],
        ]);

        $colaciones = Producto::where('categoria', 'Colacion')->get();
        foreach ($colaciones as $producto) {
            $cantidad = in_array($producto->nombre, ['Té o Café', 'Tostadas']) ? 2 : 1;
            $promocion->productos()->attach($producto->id, ['cantidad' => $cantidad]);
        }
    }
}
