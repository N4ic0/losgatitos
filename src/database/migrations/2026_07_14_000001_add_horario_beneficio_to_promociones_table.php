<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promociones', function (Blueprint $table) {
            $table->dropColumn(['orden', 'reglas']);

            $table->integer('horas_beneficio')->default(0)->after('valor');
            $table->json('tarifas')->nullable()->after('horas_beneficio');
        });
    }

    public function down(): void
    {
        Schema::table('promociones', function (Blueprint $table) {
            $table->dropColumn(['horas_beneficio', 'tarifas']);
            $table->integer('orden')->default(0)->after('activo');
            $table->json('reglas')->nullable()->after('orden');
        });
    }
};
