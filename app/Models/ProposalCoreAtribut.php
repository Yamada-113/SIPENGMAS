<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalCoreAtribut extends Model
{
    protected $table = 'proposal_core_atribut';

    protected $fillable = [
        'proposal_id',
        'rumpun_ilmu',
        'nama_institusi_mitra',
        'penanggung_jawab_mitra',
        'alamat_mitra',
    ];
}
