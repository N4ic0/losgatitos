<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ocupaciones', function (Blueprint $table) {
            $table->integer('hora_adicional')->default(0)->after('propinas');
            $table->integer('valor_h_adi')->default(0)->after('hora_adicional');
        });
    }

    public function down(): void
    {
        Schema::table('ocupaciones', function (Blueprint $table) {
            $table->dropColumn(['hora_adicional', 'valor_h_adi']);
        });
    }
};