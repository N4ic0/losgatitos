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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->string('rut', 20);
            $table->string('nombre', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->date('fecha');
            $table->time('hora');
            $table->integer('personas')->default(2);
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['Reservada', 'Ingresada', 'Finalizada', 'Cancelada'])->default('Reservada');
            $table->foreignId('habitacion_id')->nullable()->constrained('habitaciones')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('hora_ingreso')->nullable();
            $table->dateTime('hora_salida')->nullable();
            $table->integer('horas_adicionales')->default(0);
            $table->boolean('tercera_persona')->default(false);
            $table->integer('precio_base')->default(0);
            $table->integer('total')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
