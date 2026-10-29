<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fakultas;
use App\Models\FakultasProdi;
use App\Models\Jabatan;

class ProfileAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==========================================
        // 1. DATA FAKULTAS DAN PRODI
        // ==========================================
        $dataFakultas = [
            "Teknologi Informasi" => [
                "Teknik Informatika", 
                "Perpustakaan & Sains Informasi"
            ],
            "Kedokteran" => [
                "Kedokteran Umum"
            ],
            "Kedokteran Gigi" => [
                "Kedokteran Gigi"
            ],
            "Ekonomi dan Bisnis" => [
                "Manajemen", 
                "Akuntansi"
            ],
            "Hukum" => [
                "Ilmu Hukum"
            ],
            "Psikologi" => [
                "Psikologi"
            ]
        ];

        foreach ($dataFakultas as $namaFakultas => $listProdi) {
            // A. Simpan Fakultas
            // Gunakan firstOrCreate agar tidak duplikat jika dijalankan berulang
            $fakultas = Fakultas::firstOrCreate([
                'nama' => $namaFakultas
            ]);

            // B. Simpan Prodi di bawah Fakultas tersebut
            foreach ($listProdi as $namaProdi) {
                FakultasProdi::firstOrCreate([
                    'fakultas_id' => $fakultas->id,
                    'nama' => $namaProdi
                ]);
            }
        }

        // ==========================================
        // 2. DATA JABATAN FUNGSIONAL
        // ==========================================
        $dataJabatan = [
            "Lektor",
            "Lektor Kepala",
            "Guru Besar(Profesor)", // Sesuai request (tanpa spasi di kurung)
            "Asisten Ahli"
        ];

        foreach ($dataJabatan as $namaJabatan) {
            Jabatan::firstOrCreate([
                'nama' => $namaJabatan
            ]);
        }
    }
}