<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Proposal;
use App\Models\ProposalLampiran;
use App\Models\ProposalCoreAnggotaDosen;
use App\Models\Panduan;
use App\Models\Fakultas;
use App\Models\Jabatan;

class DosenDashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $userId = $user->id;
        $userNidn = $user->nidn;
        $ketuaDidanai = Proposal::where('user_id', $userId)
            ->where('status_progress', 4)
            ->count();
        $anggotaDidanai = ProposalCoreAnggotaDosen::where('nidn', $userNidn)
            ->where('is_approved_dosen', 1)
            ->whereHas('proposal', function ($q) use ($userId) {
                $q->where('user_id', '!=', $userId)->where('status_progress', 4);
            })
            ->count();
        $totalDidanai = $ketuaDidanai + $anggotaDidanai;
        $countKetuaAll = Proposal::where('user_id', $userId)->count();
        $countAnggotaAll = ProposalCoreAnggotaDosen::where('nidn', $userNidn)
            ->where('is_approved_dosen', 1)
            ->whereHas('proposal', function ($q) use ($userId) {
                $q->where('user_id', '!=', $userId);
            })
            ->count();
        $totalSemuaUsulan = $countKetuaAll + $countAnggotaAll;
        $lampiranCounts = ProposalLampiran::whereHas('proposal', function ($q) use ($userId, $userNidn) {
            $q->where('user_id', $userId)
                ->orWhereHas('anggotaDosen', function ($subQ) use ($userNidn) {
                    $subQ->where('nidn', $userNidn)->where('is_approved_dosen', 1);
                });
        })
            ->selectRaw('kategori, count(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');
        $stats = [
            'pengabdian' => $totalDidanai,
            'anggota'    => $totalSemuaUsulan,
            'artikel'    => $lampiranCounts->get('artikel', 0),
            'sertifikat' => $lampiranCounts->get('sertifikat', 0),
            'hki'        => $lampiranCounts->get('hki', 0),
            'dokumen'    => $lampiranCounts->get('dokumen', 0),
        ];
        $lastProposal = Proposal::with('identitas')
            ->where('user_id', $userId)
            ->latest()
            ->first();
        $showProfileModal = empty($user->fakultas) || empty($user->prodi);
        $kumpulanPanduan = Panduan::all();
        $fakultas = Fakultas::with('prodis')->get();
        $jabatans = Jabatan::all();
        $fakultasData = [];
        foreach ($fakultas as $f) {
            $fakultasData[$f->nama] = $f->prodis->pluck('nama');
        }
        return view('dosen.dosen_dashboard', compact('stats', 'lastProposal', 'showProfileModal', 'kumpulanPanduan', 'fakultas', 'jabatans', 'fakultasData'));
    }
}
