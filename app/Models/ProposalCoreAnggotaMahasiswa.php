<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalCoreAnggotaMahasiswa extends Model
{
    protected $table = 'proposal_core_anggota_mahasiswa';

    protected $fillable = [
        'proposal_id',
        'npm',
        'nama',
        'prodi',
        'peran',
    ];
}
