<?php

namespace App\Http\Controllers\Dekan;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ProposalLampiran;

class DekanDetailProposalController extends Controller
{
    public function show($id)
    {
        $detailProposal = Proposal::with([
            'identitas',
            'biaya',
            'atribut',
            'user',
            'anggotaDosen',
            'anggotaMahasiswa'
        ])
            ->where('id', $id)
            ->where('skala_pelaksanaan', 'Prodi')
            ->first();
        if (!$detailProposal) {
            return redirect()->route('dekan.monitoring')
                ->with('error', 'Proposal tidak ditemukan atau tidak berada dalam lingkup monitoring Fakultas.');
        }
        $ketua = (object) [
            'nama'  => $detailProposal->user->name ?? '-',
            'peran' => 'Ketua Pengusul',
            'tipe'  => 'Ketua'
        ];
        $anggota = collect([$ketua])
            ->merge($detailProposal->anggotaDosen)
            ->merge($detailProposal->anggotaMahasiswa);
        $lampiran = ProposalLampiran::where('proposal_id', $id)->get();
        return view('dekan.dekan_detail_proposal', compact('detailProposal', 'anggota', 'lampiran'));
    }
}
