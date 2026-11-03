<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('proposal_core_identitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposal')->onDelete('cascade');
            $table->string('judul', 300);
            $table->text('abstrak');
            $table->string('keyword', 150);
            $table->unsignedTinyInteger('periode_kegiatan');
            $table->string('bidang_fokus', 50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_core_identitas');
    }
};
