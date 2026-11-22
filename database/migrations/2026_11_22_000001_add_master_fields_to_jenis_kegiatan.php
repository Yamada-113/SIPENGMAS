<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis_kegiatans', function (Blueprint $table) {
            $table->boolean('is_active')->default(true)->after('nama');
            $table->unique('nama');
        });

        Schema::table('proposal_core_identitas', function (Blueprint $table) {
            $table->foreignId('jenis_kegiatan_id')
                ->nullable()
                ->after('bidang_fokus')
                ->constrained('jenis_kegiatans')
                ->nullOnDelete();
            $table->string('jenis_kegiatan_nama', 255)
                ->nullable()
                ->after('jenis_kegiatan_id');
        });
    }

    public function down(): void
    {
        Schema::table('proposal_core_identitas', function (Blueprint $table) {
            $table->dropForeign(['jenis_kegiatan_id']);
            $table->dropColumn(['jenis_kegiatan_id', 'jenis_kegiatan_nama']);
        });

        Schema::table('jenis_kegiatans', function (Blueprint $table) {
            $table->dropUnique(['nama']);
            $table->dropColumn('is_active');
        });
    }
};
