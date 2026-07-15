<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ocupaciones', function (Blueprint $table) {
            $table->boolean('vehiculo')->default(true)->after('horas_beneficio');
            $table->string('patente', 20)->nullable()->after('vehiculo');
        });
    }

    public function down(): void
    {
        Schema::table('ocupaciones', function (Blueprint $table) {
            $table->dropColumn(['vehiculo', 'patente']);
        });
    }
};
