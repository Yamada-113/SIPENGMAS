<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProposalCoreBiaya extends Model
{
    protected $table = 'proposal_core_biaya';

    protected $fillable = [
        'proposal_id',
        'honor_output',
        'belanja_non_operasional',
        // 'bahan_habis_pakai',
        'transportasi',
        'lain_lain',
        'jumlah_tendik',
        'jumlah_mahasiswa',
    ];

    protected $casts = [
        'honor_output' => 'integer',
        'belanja_non_operasional' => 'integer',
        // 'bahan_habis_pakai' => 'integer',
        'transportasi' => 'integer',
        'lain_lain' => 'integer',
        'jumlah_tendik' => 'integer',
        'jumlah_mahasiswa' => 'integer',
    ];
}
