<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promocion_producto', function (Blueprint $table) {
            $table->string('nombre')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('promocion_producto', function (Blueprint $table) {
            $table->dropColumn('nombre');
        });
    }
};
