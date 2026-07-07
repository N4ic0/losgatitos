<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ocupaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habitacion_id')->constrained('habitaciones')->cascadeOnDelete();
            $table->foreignId('tarifa_id')->nullable()->constrained('tarifas')->nullOnDelete();
            $table->integer('precio_base')->default(0);
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin')->nullable();
            $table->foreignId('promocion_id')->nullable()->constrained('promociones')->nullOnDelete();
            $table->integer('horas_beneficio')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['habitacion_id', 'fecha_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ocupaciones');
    }
};
