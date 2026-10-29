<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalCoreIdentitas extends Model
{
    protected $table = 'proposal_core_identitas';

    protected $fillable = [
        'proposal_id',
        'judul',
        'abstrak',
        'keyword',
        'periode_kegiatan',
        'bidang_fokus',
    ];
}
