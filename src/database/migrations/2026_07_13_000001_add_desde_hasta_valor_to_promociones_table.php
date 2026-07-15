<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promociones', function (Blueprint $table) {
            $table->string('desde')->nullable()->after('reglas');
            $table->string('hasta')->nullable()->after('desde');
            $table->integer('valor')->default(0)->after('hasta');
        });
    }

    public function down(): void
    {
        Schema::table('promociones', function (Blueprint $table) {
            $table->dropColumn(['desde', 'hasta', 'valor']);
        });
    }
};
