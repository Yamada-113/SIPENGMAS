<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'jenis_kegiatan_id',
        'jenis_kegiatan_nama',
    ];

    public function jenisKegiatan(): BelongsTo
    {
        return $this->belongsTo(JenisKegiatan::class, 'jenis_kegiatan_id');
    }
}
