<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('proposal_core_biaya', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposal')->onDelete('cascade');
            $table->unsignedBigInteger('honor_output');
            $table->unsignedBigInteger('belanja_non_operasional');
            // $table->unsignedBigInteger('bahan_habis_pakai');
            $table->unsignedBigInteger('transportasi');
            $table->unsignedBigInteger('lain_lain');
            $table->unsignedBigInteger('jumlah_tendik')->default(0);
            $table->unsignedBigInteger('jumlah_mahasiswa')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_core_biaya');
    }
};
