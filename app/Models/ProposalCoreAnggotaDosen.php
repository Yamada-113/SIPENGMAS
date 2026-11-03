<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalCoreAnggotaDosen extends Model
{
    use HasFactory;

    protected $table = 'proposal_core_anggota_dosen';
    protected $guarded = ['id'];

    // Relasi ke Proposal Utama (Parent)
    public function proposal()
    {
        return $this->belongsTo(Proposal::class, 'proposal_id');
    }
    protected $fillable = [
        'proposal_id',
        'nidn',
        'nama',
        'fakultas',
        'prodi',
        'peran',
        'is_approved_dosen'
    ];
}
