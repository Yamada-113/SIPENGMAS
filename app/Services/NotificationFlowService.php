<?php

namespace App\Services;

use App\Models\User;
use App\Models\Skema;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NotifProposal;
use App\Notifications\DekanUploadSignatureNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class NotificationFlowService
{
    /**
     * Helper: Ambil seluruh Tim (Pengusul + Anggota yang sudah ACC)
     * Digunakan untuk mengirim notifikasi broadcast ke satu tim.
     */
    private static function getTeamUsers($proposal)
    {
        // 1. Ambil Ketua (Pengusul)
        $users = collect([$proposal->user]);

        // 2. Ambil Anggota yang sudah approved (Dosen)
        $anggotaDosen = \App\Models\ProposalCoreAnggotaDosen::where('proposal_id', $proposal->id)
            ->where('is_approved_dosen', 1)
            ->get();

        foreach ($anggotaDosen as $anggota) {
            // Gunakan trim untuk keamanan jika ada spasi di NIDN
            $userAnggota = User::where('nidn', trim($anggota->nidn))->first();
            if ($userAnggota) {
                $users->push($userAnggota);
            }
        }

        return $users;
    }

    /**
     * Helper: Cari Kepala Pusat Berdasarkan ID Skema
     */
    private static function getKepalaPusatUsers($skemaId)
    {
        // 1. Ambil Skema
        $skema = Skema::find($skemaId);

        if (!$skema || empty($skema->label_dropdown)) {
            Log::warning("Notifikasi Gagal: Skema ID $skemaId tidak ditemukan atau label kosong.");
            return collect([]);
        }

        // 2. Ambil SEMUA kandidat Kepala Pusat (Role mengandung kata "Kepala Pusat")
        $candidates = User::where('role', 'LIKE', '%Kepala Pusat%')->get();

        // 3. Filter manual menggunakan PHP (stripos) agar SAMA PERSIS dengan Controller
        $matchedUsers = $candidates->filter(function ($user) use ($skema) {
            // Cek apakah Nama User mengandung Label dari CMS (Case-Insensitive)
            return stripos($user->name, $skema->label_dropdown) !== false;
        });

        if ($matchedUsers->isEmpty()) {
            Log::warning("Notifikasi Gagal: Tidak ada user 'Kepala Pusat' yang namanya mengandung: " . $skema->label_dropdown);
        }

        return $matchedUsers;
    }

    // =========================================================================
    // 1. FLOW AWAL: KIRIM UNDANGAN KE ANGGOTA
    // =========================================================================
    public static function sendInvitation($listAnggota, $proposal)
    {
        // [DEBUG] Cek apakah fungsi terpanggil
        Log::info("Memulai proses kirim undangan untuk Proposal ID: " . $proposal->id);
        // A. Notifikasi ke Anggota (Ajakan Bergabung)
        foreach ($listAnggota as $anggota) {
            $userAnggota = User::where('nidn', trim($anggota->nidn))->first();

            // Pastikan user ketemu & bukan pengusul sendiri
            if ($userAnggota && $userAnggota->id !== $proposal->user_id) {
                $userAnggota->notify(new NotifProposal([
                    'title' => 'Undangan Anggota',
                    'pesan' => $proposal->user->name . ' mengajak Anda bergabung di proposal: "' . Str::limit($proposal->identitas->judul, 40) . '".',
                    'proposal_id' => $proposal->id,
                    'url'   => route('dosen.anggota_request'), // URL KHUSUS ANGGOTA
                    'icon'  => 'fas fa-user-plus'
                ]));
            }
        }

        // B. Notifikasi ke Admin (Monitoring)
        $admins = User::where('role', 'Admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NotifProposal([
                'title' => 'Pengajuan Proposal Baru',
                'pesan' => 'Proposal baru masuk: "' . Str::limit($proposal->identitas->judul, 40) . '" oleh ' . $proposal->user->name . '.',
                'proposal_id' => $proposal->id,
                'url'   => route('admin.rekapitulasi.detail', $proposal->id), // URL ADMIN
                'icon'  => 'fas fa-file-alt'
            ]));
        }
    }

    // =========================================================================
    // 1.5. SAAT ANGGOTA MENOLAK
    // =========================================================================
    public static function memberRejected($proposal, $memberName)
    {
        $proposal->user->notify(new NotifProposal([
            'title' => 'Anggota Menolak',
            'pesan' => 'Anggota ' . $memberName . ' menolak bergabung.',
            'proposal_id' => $proposal->id,
            'url'   => route('dosen.detail_proposal', $proposal->id), // URL DOSEN
            'icon'  => 'fas fa-user-times'
        ]));
    }

    // =========================================================================
    // 2. SAAT SEMUA ANGGOTA SUDAH ACC (Lanjut ke Wakil Dekan 3)
    // =========================================================================
    public static function anggotaApproved($proposal)
    {
        // A. Kirim ke Dosen Pengusul (Info)
        $proposal->user->notify(new NotifProposal([
            'title' => 'Tim Lengkap',
            'pesan' => 'Semua anggota setuju. Proposal diteruskan ke Wakil Dekan 3.',
            'proposal_id' => $proposal->id,
            'url'   => route('dosen.detail_proposal', $proposal->id), // URL DOSEN
            'icon'  => 'fas fa-check-double'
        ]));

        // B. Kirim ke WAKIL DEKAN 3 (Validasi)
        $wakilDekan3 = User::where('role', 'Wakil Dekan 3')->get();

        foreach ($wakilDekan3 as $wd3) {
            $wd3->notify(new NotifProposal([
                'title' => 'Validasi Proposal Masuk',
                'pesan' => 'Proposal menunggu validasi Anda: "' . Str::limit($proposal->identitas->judul, 40) . '".',
                'proposal_id' => $proposal->id,

                // [PENTING] URL INI MENGARAH KE HALAMAN VALIDASI WADEK
                'url'   => route('wakil_dekan3.validasi.detail', $proposal->id),

                'icon'  => 'fas fa-file-signature'
            ]));
        }
    }

    // =========================================================================
    // 3. SAAT WAKIL DEKAN 3 SUDAH VALIDASI (Terima)
    // =========================================================================
    public static function wadekApproved($proposal)
    {
        // 1. Notif ke TIM DOSEN (Info Lolos Wadek)
        $teamUsers = self::getTeamUsers($proposal);
        Notification::send($teamUsers, new NotifProposal([
            'title' => 'Lolos Validasi Wadek 3',
            'pesan' => 'Proposal disetujui Wakil Dekan 3. Diteruskan ke tahap selanjutnya.',
            'proposal_id' => $proposal->id,
            'url'   => route('dosen.detail_proposal', $proposal->id), // URL DOSEN
            'icon'  => 'fas fa-check-circle'
        ]));

        // 2. Cek Skala untuk Next Approver
        $skala = strtolower(trim($proposal->skala_pelaksanaan)); // 'prodi' atau 'pusat'

        if ($skala == 'prodi') {
            // --- ALUR PRODI: Wadek 3 -> Warek 3 ---
            $wakilRektor3 = User::where('role', 'Wakil Rektor 3')->get();

            foreach ($wakilRektor3 as $wr3) {
                $wr3->notify(new NotifProposal([
                    'title' => 'Validasi Proposal (Prodi)',
                    'pesan' => 'Proposal "' . Str::limit($proposal->identitas->judul, 30) . '" lolos Wadek 3. Menunggu validasi Anda.',
                    'proposal_id' => $proposal->id,

                    // [PENTING] URL INI MENGARAH KE HALAMAN VALIDASI WAREK
                    'url'   => route('wakil_rektor.validasi.detail', $proposal->id),

                    'icon'  => 'fas fa-file-signature'
                ]));
            }
        } else {
            // --- ALUR PUSAT: Wadek 3 -> Kepala Pusat ---
            $kapusList = self::getKepalaPusatUsers($proposal->skema);

            foreach ($kapusList as $kapus) {
                $kapus->notify(new NotifProposal([
                    'title' => 'Validasi Proposal Pusat',
                    'pesan' => 'Proposal "' . Str::limit($proposal->identitas->judul, 30) . '" masuk untuk validasi Pusat.',
                    'proposal_id' => $proposal->id,

                    // [PENTING] URL INI MENGARAH KE HALAMAN VALIDASI KAPUS
                    'url'   => route('kepala_pusat.validasi.detail', $proposal->id),

                    'icon'  => 'fas fa-file-medical'
                ]));
            }
        }
    }

    // =========================================================================
    // 4. SAAT KEPALA PUSAT SUDAH VALIDASI (Khusus Skala Pusat)
    // =========================================================================
    public static function pusatApproved($proposal)
    {
        // 1. Notif ke TIM DOSEN (Info Lolos Pusat)
        $teamUsers = self::getTeamUsers($proposal);
        Notification::send($teamUsers, new NotifProposal([
            'title' => 'Lolos Validasi Pusat',
            'pesan' => 'Proposal disetujui Pusat. Diteruskan ke Wakil Rektor 3.',
            'proposal_id' => $proposal->id,
            'url'   => route('dosen.detail_proposal', $proposal->id), // URL DOSEN
            'icon'  => 'fas fa-check-circle'
        ]));

        // 2. Notif ke NEXT APPROVER (Wakil Rektor 3)
        $wakilRektor3 = User::where('role', 'Wakil Rektor 3')->get();

        foreach ($wakilRektor3 as $wr3) {
            $wr3->notify(new NotifProposal([
                'title' => 'Validasi Proposal (Pusat)',
                'pesan' => 'Proposal "' . Str::limit($proposal->identitas->judul, 30) . '" lolos Pusat. Menunggu validasi Anda.',
                'proposal_id' => $proposal->id,

                // [PENTING] URL INI MENGARAH KE HALAMAN VALIDASI WAREK
                'url'   => route('wakil_rektor.validasi.detail', $proposal->id),

                'icon'  => 'fas fa-file-signature'
            ]));
        }
    }

    // =========================================================================
    // 5. SAAT WAKIL REKTOR 3 SUDAH VALIDASI (FINAL)
    // =========================================================================
    public static function warekApproved($proposal)
    {
        // 1. Notif ke TIM DOSEN (Info Final)
        $teamUsers = self::getTeamUsers($proposal);
        Notification::send($teamUsers, new NotifProposal([
            'title' => 'Proposal Disetujui (Selesai)',
            'pesan' => 'Selamat! Proposal "' . Str::limit($proposal->identitas->judul, 30) . '" telah disetujui Wakil Rektor 3.',
            'proposal_id' => $proposal->id,
            'url'   => route('dosen.detail_proposal', $proposal->id), // URL DOSEN
            'icon'  => 'fas fa-trophy'
        ]));

        // 2. Notif ke ADMIN (Monitoring)
        $admins = User::where('role', 'Admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NotifProposal([
                'title' => 'Proposal Disetujui Warek 3',
                'pesan' => 'Proposal "' . Str::limit($proposal->identitas->judul, 30) . '" telah selesai proses validasi.',
                'proposal_id' => $proposal->id,
                'url'   => route('admin.rekapitulasi.detail', $proposal->id), // URL ADMIN
                'icon'  => 'fas fa-check-double'
            ]));
        }
    }

    // =========================================================================
    // 6. SAAT PROPOSAL DITOLAK (General)
    // =========================================================================
    public static function proposalRejected($proposal, $reason, $rejectedByRole)
    {
        $teamUsers = self::getTeamUsers($proposal);

        Notification::send($teamUsers, new NotifProposal([
            'title' => 'Proposal Ditolak',
            'pesan' => 'Proposal ditolak oleh ' . $rejectedByRole . '. Alasan: ' . $reason,
            'proposal_id' => $proposal->id,
            'url'   => route('dosen.detail_proposal', $proposal->id), // URL DOSEN
            'icon'  => 'fas fa-times-circle'
        ]));
    }

    // =========================================================================
    // 7. REMINDER DEKAN
    // =========================================================================
    public static function remindDekanToUploadSignature()
    {
        $dekan = User::where('role', 'Dekan')->first();
        if ($dekan) {
            $dekan->notify(new DekanUploadSignatureNotification());
            return true;
        }
        return false;
    }
}
