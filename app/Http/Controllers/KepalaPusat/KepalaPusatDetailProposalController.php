<?php

namespace App\Http\Controllers\KepalaPusat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Proposal;
use App\Models\ProposalLampiran;
use App\Models\Skema;


class KepalaPusatDetailProposalController extends Controller
{
    public function show($id)
    {
        $user = Auth::user();
        $centerId = 0;
        $skemas = Skema::all();
        foreach ($skemas as $skema) {
            $label = $skema->label_dropdown;
            $cleanLabel = preg_replace('/\s*\(.*?\)\s*/', '', $label);
            $cleanLabel = str_ireplace(['Pusat', 'Yarsi', 'YARSI'], '', $cleanLabel);
            $cleanLabel = trim($cleanLabel);
            if (!empty($cleanLabel) && stripos($user->name, $cleanLabel) !== false) {
                $centerId = $skema->id;
                break;
            }
        }
        if ($centerId == 0) {
            $centerId = (int) filter_var($user->role, FILTER_SANITIZE_NUMBER_INT);
        }
        $detailProposal = Proposal::with([
            'identitas',
            'biaya',
            'atribut',
            'user',
            'skemaRef',
            'anggotaDosen',
            'anggotaMahasiswa'
        ])->where('id', $id)->first();
        if (!$detailProposal) {
            return redirect()->back()->with('error', 'Proposal tidak ditemukan.');
        }
        if ($detailProposal->skala_pelaksanaan != 'Pusat' || $detailProposal->skema != $centerId) {
            return redirect()->route('kepala_pusat.validasi')->with('error', 'Anda tidak memiliki akses ke proposal ini.');
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
        return view('kepala_pusat.kepala_pusat_detail_proposal', compact('detailProposal', 'anggota', 'lampiran'));
    }
}
