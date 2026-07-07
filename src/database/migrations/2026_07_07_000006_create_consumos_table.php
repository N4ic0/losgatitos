<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consumos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ocupacion_id')->constrained('ocupaciones')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->integer('cantidad')->default(1);
            $table->integer('precio_unitario');
            $table->integer('total');
            $table->enum('origen', ['Consumo', 'Promocion'])->default('Consumo');
            $table->string('observacion')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('ocupacion_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consumos');
    }
};
