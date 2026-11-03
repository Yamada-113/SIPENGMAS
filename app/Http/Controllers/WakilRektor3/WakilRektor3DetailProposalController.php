<?php

namespace App\Http\Controllers\WakilRektor3;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ProposalLampiran;

class WakilRektor3DetailProposalController extends Controller
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
        ])->where('id', $id)->first();
        if (!$detailProposal) {
            return redirect()->route('wakil_rektor.validasi')->with('error', 'Proposal tidak ditemukan.');
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
        return view('wakil_rektor3.wakil_rektor3_detail_proposal', compact('detailProposal', 'anggota', 'lampiran'));
    }
}
