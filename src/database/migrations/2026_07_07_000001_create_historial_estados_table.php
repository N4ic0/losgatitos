<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habitacion_id')->constrained('habitaciones')->cascadeOnDelete();
            $table->enum('estado', ['Disponible', 'Reservada', 'Ocupada', 'Limpieza', 'Mantenimiento']);
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('ocupacion_id')->nullable();
            $table->timestamps();

            $table->index(['habitacion_id', 'fecha_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_estados');
    }
};
