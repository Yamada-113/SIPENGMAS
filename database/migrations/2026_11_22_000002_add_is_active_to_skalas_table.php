<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skalas', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('nama');
        });
    }

    public function down(): void
    {
        Schema::table('skalas', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
