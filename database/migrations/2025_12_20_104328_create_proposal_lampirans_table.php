<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('proposal_lampiran', function (Blueprint $table) {
        $table->id();
        $table->foreignId('proposal_id')->constrained('proposal')->onDelete('cascade');
        $table->enum('kategori', ['dokumen', 'artikel', 'sertifikat', 'hki']);
        $table->string('judul');
        $table->string('file_path');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_lampiran');
    }
};