<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ocupacion_id')->constrained('ocupaciones')->cascadeOnDelete();
            $table->integer('monto');
            $table->enum('forma_pago', ['efectivo', 'transferencia', 'tarjeta']);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('ocupacion_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
