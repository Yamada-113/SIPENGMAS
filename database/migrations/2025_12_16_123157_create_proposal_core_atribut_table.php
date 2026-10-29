<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('proposal_core_atribut', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposal')->onDelete('cascade');
            $table->string('rumpun_ilmu', 50);
            $table->string('nama_institusi_mitra', 50);
            $table->string('penanggung_jawab_mitra', 50)->nullable();;
            $table->string('alamat_mitra', 250);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_core_atribut');
    }
};
