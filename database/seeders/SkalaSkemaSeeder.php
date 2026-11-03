<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skala;
use App\Models\Skema;
use Illuminate\Support\Facades\DB;

class SkalaSkemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan data lama agar tidak duplikat saat db:seed ulang (Opsional)
        // DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Skema::truncate();
        // Skala::truncate();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data Hierarki berdasarkan gambar
        $data = [
            'Prodi' => [
                [
                    'label_dropdown' => 'Teknik Informatika',
                    'nama' => 'Skema Program Internal Prodi Teknik Informatika'
                ],
                [
                    'label_dropdown' => 'Perpustakaan dan Sains Informasi',
                    'nama' => 'Skema Program Internal Prodi Perpustakaan dan Sains Informasi'
                ],
            ],
            'Pusat' => [
                [
                    'label_dropdown' => 'Pusat YARSI Peduli TB',
                    'nama' => 'Skema Program Pusat Peduli TB'
                ],
                [
                    'label_dropdown' => 'Pusat YARSI Peduli HIV/AIDS',
                    'nama' => 'Skema Program YARSI Peduli HIV/AIDS'
                ],
                [
                    'label_dropdown' => 'Pusat YARSI Pemberdayaan Desa',
                    'nama' => 'Skema Program Pusat YARSI Pemberdayaan Desa'
                ],
                [
                    'label_dropdown' => 'Pusat YARSI Peduli Penglihatan',
                    'nama' => 'Skema Program Pusat YARSI Peduli Penglihatan'
                ],
                [
                    'label_dropdown' => 'Pusat Pelayanan Keluarga Sejahtera (PPKS)',
                    'nama' => 'Skema Program Pelayanan Keluarga Sejahtera (PPKS)'
                ],
            ],
        ];

        // Loop untuk insert data
        foreach ($data as $namaSkala => $listSkema) {
            // 1. Buat Skala (Parent)
            // Menggunakan firstOrCreate agar aman jika dijalankan berulang tanpa refresh
            $skala = Skala::firstOrCreate(
                ['nama' => $namaSkala]
            );

            // 2. Buat Skema (Children)
            foreach ($listSkema as $item) {
                Skema::firstOrCreate(
                    [
                        'skala_id' => $skala->id,
                        'label_dropdown' => $item['label_dropdown']
                    ],
                    [
                        'nama' => $item['nama']
                    ]
                );
            }
        }
    }
}