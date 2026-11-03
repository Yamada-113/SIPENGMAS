<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'id'        => 1,
                'name'      => 'Administrator',
                'username'  => 'admin',
                'password'  => '$2y$12$dV3m.mIB5FaQlAONqr3/qe/hJvTxWTvQKsJ2yo4dl/rwQTXqhdWPm', 
                'nidn'      => null,
                'fakultas'  => null,
                'prodi'     => null,
                'role'      => 'Admin',
            ],
            [
                'id'        => 2,
                'name'      => 'Wakil Dekan 3',
                'username'  => 'wadek',
                'password'  => '$2y$12$oVjcjyaQlug1v4xj4BugRuCZjH7c8YQWcKoqCUjXLghwSbmAdZUVe', 
                'nidn'      => '111111111111',
                'fakultas'  => 'Teknologi Informasi',
                'prodi'     => 'Teknik Informatika',
                'jabatan_fungsional' => 'Asisten Ahli',
                'role'      => 'Wakil Dekan 3',
            ],
            [
                'id'        => 3,
                'name'      => 'Dekan',
                'username'  => 'dekan',
                'password'  => '$2y$12$5SWRbCFaQEoPoG6x9.YCx.GYhdHUg1HtACPPOJO4heYinnBZr7RvC', 
                'nidn'      => '22222222222',
                'fakultas'  => 'Teknologi Informasi',
                'prodi'     => 'Teknik Informatika',
                'jabatan_fungsional' => 'Asisten Ahli',
                'role'      => 'Dekan',
            ],
            
            [
                'id'        => 4,
                'name'      => 'Kepala Pusat Yarsi Peduli Penglihatan',
                'username'  => 'pusat1',
                'password'  => '$2y$12$IntddY5mDNSIDDMky90iyOEP6j.wO/EpekuxkYYPvj/O514K2Jv5W', 
                'nidn'      => '33333333333',
                'fakultas'  => 'Teknologi Informasi',
                'prodi'     => 'Teknik Informatika',
                'jabatan_fungsional' => 'Asisten Ahli',
                'role'      => 'Kepala Pusat 1',
            ],
            [
                'id'        => 5,
                'name'      => 'Kepala Pusat Yarsi Peduli TB',
                'username'  => 'pusat2',
                'password'  => '$2y$12$rH37.W3h74JwGMQzccMrQuBVwP3lcqczTZUy0OedE6tOrqQCjLXBC', 
                'nidn'      => '443241412',
                'fakultas'  => 'Teknologi Informasi',
                'prodi'     => 'Teknik Informatika',
                'jabatan_fungsional' => 'Asisten Ahli',
                'role'      => 'Kepala Pusat 2',
            ],
            [
                'id'        => 6,
                'name'      => 'Kepala Pusat Yarsi Pemberdayaan Desa',
                'username'  => 'pusat3',
                'password'  => '$2y$12$yKeq.5EbZLCKPwYLlULxneS.DLDC47UF2TPIfBJ8G7Um2SqUpFLFm', 
                'nidn'      => '55555555555',
                'fakultas'  => 'Teknologi Informasi',
                'prodi'     => 'Teknik Informatika',
                'jabatan_fungsional' => 'Asisten Ahli',
                'role'      => 'Kepala Pusat 3',
            ],
            [
                'id'        => 7,
                'name'      => 'Kepala Pusat Yarsi Peduli HIV/AIDS',
                'username'  => 'pusat4',
                'password'  => '$2y$12$LpmS08CKCgW9AB2IO1f8re3ziXzsl5.T7L2htDLCfrAjWyjquAfyq', 
                'nidn'      => '66666666666',
                'fakultas'  => 'Teknologi Informasi',
                'prodi'     => 'Teknik Informatika',
                'jabatan_fungsional' => 'Asisten Ahli',
                'role'      => 'Kepala Pusat 4',
            ],
            [
                'id'        => 8,
                'name'      => 'Kepala Pusat Yarsi Pelayanan Keluarga Sejahtera',
                'username'  => 'pusat5',
                'password'  => '$2y$12$JKCmyrGjNebkfx3xsz9tBeTah8zmTdUmTrNJl8OrhQW6MbyaWvTXe', 
                'nidn'      => '77777777777',
                'fakultas'  => 'Teknologi Informasi',
                'prodi'     => 'Teknik Informatika',
                'jabatan_fungsional' => 'Asisten Ahli',
                'role'      => 'Kepala Pusat 5',
            ],
            [
                'id'        => 9,
                'name'      => 'Wakil Rektor 3',
                'username'  => 'warek',
                'password'  => '$2y$12$vel2H3v71XvntP0JFzQLZu/aM3M2N/Xo0/0I.avY6.uP7pSg.OOKC', 
                'nidn'      => '88888888888',
                'fakultas'  => 'Teknologi Informasi',
                'prodi'     => 'Teknik Informatika',
                'jabatan_fungsional' => 'Asisten Ahli',
                'role'      => 'Wakil Rektor 3',
            ],
            [
                'id'        => 10,
                'name'      => 'Dosen',
                'username'  => 'dosen',
                'password'  => '$2y$12$NvJSsCWLMPHrDrYcL2SryuFkLsf6.z/C1lM7vc3IxLPZgKnt1pTmu', 
                'nidn'      => '99999999999',
                'fakultas'  => 'Teknologi Informasi',
                'prodi'     => 'Perpustakaan & Sains Informasi',
                'jabatan_fungsional' => 'Asisten Ahli',
                'role'      => 'Dosen',
            ],

        ];

        foreach ($users as $user) {
            User::updateOrCreate(['id' => $user['id']], $user);
        }
    }
}