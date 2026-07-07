<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_documento', ['RUT', 'Pasaporte']);
            $table->string('numero_documento');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('nacionalidad')->default('Chilena');
            $table->date('fecha_nacimiento')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tipo_documento', 'numero_documento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
