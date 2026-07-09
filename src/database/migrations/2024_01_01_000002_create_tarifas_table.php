<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tarifas', function (Blueprint $table) {
            $table->id();
            $table->enum('categoria', ['Suite', 'Departamento']);
            $table->enum('tipo_tiempo', ['3h', '8h', 'Hora adicional']);
            $table->integer('precio_dj')->comment('Domingo a Jueves');
            $table->integer('precio_viernes');
            $table->integer('precio_sabado');
            $table->integer('precio_vispera')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_termino')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifas');
    }
};
