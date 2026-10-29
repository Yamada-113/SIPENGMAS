<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalCorePengesahan extends Model
{
    protected $table = 'proposal_core_pengesahan';

    protected $fillable = [
        'proposal_id',
        'kota',
        'jabatan_mengetahui',
        'nama_mengetahui',
        'nip_mengetahui',
        'jabatan_menyetujui',
        'nama_menyetujui',
        'nip_menyetujui',
    ];
}
