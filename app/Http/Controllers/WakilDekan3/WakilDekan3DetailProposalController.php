<?php

namespace App\Http\Controllers\WakilDekan3;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ProposalLampiran;
use App\Models\User;

class WakilDekan3DetailProposalController extends Controller
{
    public function show($id)
    {
        $detailProposal = Proposal::with([
            'identitas',
            'biaya',
            'atribut',
            'user',
            'skemaRef',
            'anggotaDosen',
            'anggotaMahasiswa'
        ])->where('id', $id)->firstOrFail();
        $dekan = User::where('role', 'Dekan')->first();
        $dekanHasSignature = $dekan && !empty($dekan->tanda_tangan);
        $ketua = (object) [
            'nama'  => $detailProposal->user->name ?? '-',
            'peran' => 'Ketua Pengusul',
            'tipe'  => 'Ketua'
        ];
        $anggota = collect([$ketua])
            ->merge($detailProposal->anggotaDosen)
            ->merge($detailProposal->anggotaMahasiswa);
        $lampiran = ProposalLampiran::where('proposal_id', $id)->get();
        return view('wakil_dekan3.wakil_dekan3_detail_proposal', compact(
            'detailProposal',
            'anggota',
            'lampiran',
            'dekanHasSignature'
        ));
    }
}
