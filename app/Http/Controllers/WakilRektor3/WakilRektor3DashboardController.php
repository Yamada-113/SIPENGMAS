<?php

namespace App\Http\Controllers\WakilRektor3;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ProposalLampiran;

class WakilRektor3DashboardController extends Controller
{
    public function index()
    {
        $pendingProposals = Proposal::with(['identitas', 'user'])
            ->where('status_progress', 3)
            ->orderBy('updated_at', 'asc')
            ->get();
        $stats = [
            'total_usulan'     => Proposal::where('status_progress', '>=', 1)->count(),
            'disetujui'        => Proposal::where('status_progress', 4)->count(),
            'ditolak'          => Proposal::where('status_progress', 99)->count(),
            'pending_count'    => $pendingProposals->count(),
            'total_artikel'    => $this->countLuaran('artikel'),
            'total_sertifikat' => $this->countLuaran('sertifikat'),
            'total_hki'        => $this->countLuaran('hki'),
        ];
        return view('wakil_rektor3.wakil_rektor3_dashboard', compact('pendingProposals', 'stats'));
    }

    private function countLuaran($kategori)
    {
        return ProposalLampiran::where('kategori', $kategori)
            ->whereHas('proposal', function ($q) {
                $q->where('status_progress', 4);
            })
            ->count();
    }
}
