<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('skemas', function (Blueprint $table) {
            // Drop the old 'jenis' enum column
            $table->dropColumn('jenis'); 
            
            // Add new Foreign Key
            $table->foreignId('skala_id')->constrained('skalas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
