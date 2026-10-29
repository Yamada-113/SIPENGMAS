<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalLampiran extends Model
{
    use HasFactory;

    protected $table = 'proposal_lampiran';

    protected $fillable = [
        'proposal_id',
        'kategori',
        'judul',
        'file_path',
    ];

    /**
     * Relasi ke Model Proposal
     */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }
}