<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->integer('precio');
            $table->string('imagen')->nullable();
            $table->enum('categoria', ['Producto', 'Colacion'])->default('Producto');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('categoria');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
