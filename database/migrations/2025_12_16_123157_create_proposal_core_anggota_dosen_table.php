<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('proposal_core_anggota_dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposal')->onDelete('cascade');
            $table->string('nidn', 20);
            $table->string('nama', 50);
            $table->string('fakultas', 100);
            $table->string('prodi', 100);
            $table->string('peran', 50);
            $table->boolean('is_approved_dosen')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_core_anggota_dosen');
    }
};
