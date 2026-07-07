<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ocupacion_cliente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ocupacion_id')->constrained('ocupaciones')->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['ocupacion_id', 'cliente_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ocupacion_cliente');
    }
};
