<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ProposalLampiran;

class Proposal extends Model
{
    use HasFactory;

    protected $table = 'proposal';

    protected $fillable = [
        'user_id',
        'tahun_pelaksanaan',
        'skala_pelaksanaan',
        'skema',
        'status_progress',
        'feedback',
        'file_proposal',
    ];

    protected $guarded = ['id'];

    // =========================================================================
    // RELATIONS (ELOQUENT RELATIONSHIPS)
    // =========================================================================

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function identitas(): HasOne
    {
        return $this->hasOne(ProposalCoreIdentitas::class, 'proposal_id');
    }

    public function atribut(): HasOne
    {
        return $this->hasOne(ProposalCoreAtribut::class, 'proposal_id');
    }
    public function skemaRef()
    {
        return $this->belongsTo(\App\Models\Skema::class, 'skema', 'id');
    }

    public function uraian(): HasOne
    {
        return $this->hasOne(ProposalCoreUraian::class, 'proposal_id');
    }

    public function anggotaDosen(): HasMany
    {
        return $this->hasMany(ProposalCoreAnggotaDosen::class, 'proposal_id');
    }

    public function anggotaMahasiswa(): HasMany
    {
        return $this->hasMany(ProposalCoreAnggotaMahasiswa::class, 'proposal_id');
    }

    public function biaya(): HasOne
    {
        return $this->hasOne(ProposalCoreBiaya::class, 'proposal_id');
    }

    public function pengesahan(): HasOne
    {
        return $this->hasOne(ProposalCorePengesahan::class, 'proposal_id');
    }
    public function lampiran(): HasMany
    {
        return $this->hasMany(ProposalLampiran::class, 'proposal_id');
    }

    // =========================================================================
    // ACCESSORS (VIRTUAL ATTRIBUTES)
    // =========================================================================

    /**
     * 1. Total Dana (Dihitung Otomatis dari Relasi Biaya)
     * Cara panggil: $proposal->total_dana
     */
    public function getTotalDanaAttribute()
    {
        if (!$this->biaya) {
            return 0;
        }

        return $this->biaya->honor_output +
               $this->biaya->belanja_non_operasional +
            //    $this->biaya->bahan_habis_pakai +
               $this->biaya->transportasi +
               $this->biaya->lain_lain;

    }

    /**
     * 2. Label Status (Konsisten untuk Semua Role)
     * Cara panggil: $proposal->status_label
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status_progress) {
            0 => 'Menunggu Anggota',
            1 => 'Menunggu Validasi Wadek',
            2 => 'Menunggu Validasi Pusat',
            3 => 'Menunggu Validasi Warek',
            4 => 'Didanai / Selesai',
            99 => 'Ditolak',
            default => 'Status Tidak Diketahui',
        };
    }

    /**
     * 3. Warna Status (Bootstrap Class)
     * Cara panggil: $proposal->status_color
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status_progress) {
            0 => 'warning',      // Kuning
            1 => 'info',         // Biru Muda
            2 => 'primary',      // Biru
            3 => 'primary',      // Biru
            4 => 'success',      // Hijau
            99 => 'danger',      // Merah
            default => 'secondary', // Abu-abu
        };
    }


    /**
     * 4. Label Skema Lengkap (Gabungan Skala & ID)
     * Cara panggil: $proposal->skema_label
     */
    public function getSkemaLabelAttribute()
    {
        $idSkema = (string) $this->skema;
        $skala = $this->skala_pelaksanaan;

        if ($skala == 'Prodi') {
            return match ($idSkema) {
                '1' => 'Prodi - Teknik Informatika',
                '2' => 'Prodi - Perpustakaan & Sains Informasi',
                default => 'Prodi - ID: ' . $idSkema,
            };
        } elseif ($skala == 'Pusat') {
            return match ($idSkema) {
                '1' => 'Pusat - Peduli Penglihatan',
                '2' => 'Pusat - Peduli TB',
                '3' => 'Pusat - Pemberdayaan Desa',
                '4' => 'Pusat - Peduli HIV/AIDS',
                '5' => 'Pusat - Pelayanan Keluarga Sejahtera',
                default => 'Pusat - ID: ' . $idSkema,
            };
        }

        return $skala . ' - ' . $idSkema;
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    public function isJalurPusat()
    {
        return $this->skala_pelaksanaan === 'Pusat';
    }
}