<?php

namespace Database\Seeders;

use App\Models\Configuracion;
use Illuminate\Database\Seeder;

class ConfiguracionSeeder extends Seeder
{
    public function run(): void
    {
        Configuracion::create(['clave' => 'direccion', 'valor' => 'Macul 4849, Santiago, Chile']);
        Configuracion::create(['clave' => 'email', 'valor' => 'motellosgatitos@gmail.com']);
        Configuracion::create(['clave' => 'telefono', 'valor' => '+56 4 4358 7999']);
        Configuracion::create(['clave' => 'horario_atencion', 'valor' => 'Lunes a Domingo 24 horas']);
        Configuracion::create(['clave' => 'tercera_persona_porcentaje', 'valor' => '50']);
    }
}
