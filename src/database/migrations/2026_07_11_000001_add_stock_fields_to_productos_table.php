<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->integer('stock_actual')->default(0)->after('precio');
            $table->integer('stock_minimo')->default(0)->after('stock_actual');
            $table->integer('stock_maximo')->default(0)->after('stock_minimo');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['stock_actual', 'stock_minimo', 'stock_maximo']);
        });
    }
};
