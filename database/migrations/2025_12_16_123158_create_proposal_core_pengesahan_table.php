<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('proposal_core_pengesahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposal')->onDelete('cascade');
            $table->string('kota', 50);
            $table->string('jabatan_mengetahui', 50);
            $table->string('nama_mengetahui', 50);
            $table->string('nip_mengetahui', 20);
            $table->string('jabatan_menyetujui', 50);
            $table->string('nama_menyetujui', 50);
            $table->string('nip_menyetujui', 20);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_core_pengesahan');
    }
};
