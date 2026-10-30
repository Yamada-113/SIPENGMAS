<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Proposal;
use App\Models\ProposalLampiran;
use Barryvdh\DomPDF\Facade\Pdf;

class DosenDetailProposalController extends Controller
{
    public function detail($id)
    {
        $detailProposal = Proposal::with([
            'identitas',
            'biaya',
            'atribut',
            'skemaRef',
            'user',
            'anggotaDosen',
            'anggotaMahasiswa'
        ])->where('id', $id)->first();

        if (!$detailProposal) {
            abort(404);
        }

        $user = Auth::user();
        $isKetua = $detailProposal->user_id == $user->id;
        $isAnggota = $detailProposal->anggotaDosen
            ->contains('nidn', $user->nidn);

        if (!$isKetua && !$isAnggota) {
            return response()->view('dosen.dosen_akses_ditolak', [], 403);
        }
        // if (!$detailProposal) {
        //     return redirect()->back()->with('error', 'Proposal tidak ditemukan.');
        // }
    
        $ketua = (object) [
            'nama'  => $detailProposal->user->name ?? '-',
            'nidn'  => $detailProposal->user->nidn ?? '-',
            'peran' => 'Ketua Pengusul',
            'tipe'  => 'Ketua',
            'is_approved_dosen' => 1
        ];
        $anggota = collect([$ketua])
            ->merge($detailProposal->anggotaDosen)
            ->merge($detailProposal->anggotaMahasiswa);
        $userLogin = Auth::user();
        $myInvitation = $detailProposal->anggotaDosen
            ->where('nidn', $userLogin->nidn)
            ->first();
        $statusUndanganSaya = $myInvitation ? $myInvitation->is_approved_dosen : null;
        $idUndanganSaya     = $myInvitation ? $myInvitation->id : null;
        $lampiran = ProposalLampiran::where('proposal_id', $id)->get();
        return view('dosen.dosen_detail_proposal', compact(
            'detailProposal',
            'anggota',
            'lampiran',
            'statusUndanganSaya',
            'idUndanganSaya'
        ));
    }

    public function exportPdf($id)
    {
        $detailProposal = Proposal::with([
            'identitas',
            'biaya',
            'atribut',
            'user',
            'pengesahan',
            'anggotaDosen',
            'anggotaMahasiswa'
        ])->findOrFail($id);

        $user = Auth::user();
        $isKetua = $detailProposal->user_id == $user->id;
        $isAnggota = $detailProposal->anggotaDosen
            ->contains('nidn', $user->nidn);

        if (!$isKetua && !$isAnggota) {
            return response()->view('dosen.dosen_akses_ditolak', [], 403);
        }
        $ketua = (object) [
            'nama' => $detailProposal->user->name ?? '-',
            'nidn' => $detailProposal->user->nidn ?? '-',
            'prodi' => $detailProposal->user->prodi ?? '-',
            'jabatan_fungsional' => $detailProposal->user->jabatan_fungsional ?? '-'
        ];

        $pdf = Pdf::loadView('dosen.dosen_unduh_pengesahan', [
            'p'         => $detailProposal,
            'ketua'     => $ketua,
            'dosen'     => $detailProposal->anggotaDosen,
            'mahasiswa' => $detailProposal->anggotaMahasiswa
        ]);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('Lembar_Pengesahan_' . time() . '.pdf');
    }
}
