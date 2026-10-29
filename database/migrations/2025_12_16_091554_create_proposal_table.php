<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('proposal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // ID Pengusul
            $table->year('tahun_pelaksanaan');
            $table->string('skala_pelaksanaan', 50);
            $table->unsignedTinyInteger('skema');
            /**
             * Status Progress:
             * 0: Menunggu Persetujuan Anggota
             * 1: Menunggu Persetujuan Wadek 3
             * 2: Menunggu Persetujuan Dekan
             * 3: Menunggu Persetujuan Admin (LPM)
             * 4: Disetujui Sepenuhnya (Selesai)
             * 99: Ditolak
             */
            $table->integer('status_progress')->default(0);
            $table->string('file_proposal')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();

            // Foreign key ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal');
    }
};