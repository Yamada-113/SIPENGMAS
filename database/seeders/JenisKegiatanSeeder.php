<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisKegiatan;

class JenisKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (['Internal', 'Eksternal'] as $nama) {
            JenisKegiatan::updateOrCreate(
                ['nama' => $nama],
                ['is_active' => true]
            );
        }
    }
}
