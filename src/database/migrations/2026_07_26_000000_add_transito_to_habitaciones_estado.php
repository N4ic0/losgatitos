<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE habitaciones MODIFY COLUMN estado ENUM('Disponible', 'Reservada', 'Ocupada', 'Limpieza', 'Mantenimiento', 'Transito') NOT NULL DEFAULT 'Disponible'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE habitaciones MODIFY COLUMN estado ENUM('Disponible', 'Reservada', 'Ocupada', 'Limpieza', 'Mantenimiento') NOT NULL DEFAULT 'Disponible'");
    }
};
