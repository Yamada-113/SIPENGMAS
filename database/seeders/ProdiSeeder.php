<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prodi; // Pastikan import Model Prodi

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $daftarProdi = [
            'Teknik Informatika',
            'Perpustakaan dan Sains Informasi',
        ];

        foreach ($daftarProdi as $namaProdi) {
            Prodi::firstOrCreate([
                'nama' => $namaProdi
            ]);
        }
    }
}