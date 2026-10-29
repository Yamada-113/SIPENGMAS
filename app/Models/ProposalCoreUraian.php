<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalCoreUraian extends Model
{
    protected $table = 'proposal_core_uraian';

    protected $fillable = [
        'proposal_id',
        'objek_pengabdian',
        'instansi_terlibat',
        'lokasi_pengabdian',
        'temuan_ditargetkan',
    ];
}
