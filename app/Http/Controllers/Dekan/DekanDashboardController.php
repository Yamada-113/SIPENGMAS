<?php

namespace App\Http\Controllers\Dekan;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ProposalLampiran;

class DekanDashboardController extends Controller
{
    public function index()
    {
        $pendingProposals = Proposal::with(['identitas', 'user'])
            ->where('skala_pelaksanaan', 'Prodi')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        $baseQuery = Proposal::where('skala_pelaksanaan', 'Prodi');
        $stats = [
            'total_usulan'  => (clone $baseQuery)->count(),
            'disetujui'     => (clone $baseQuery)->where('status_progress', '>=', 2)->count(),
            'menunggu'      => (clone $baseQuery)->where('status_progress', 1)->count(),
            'ditolak'       => (clone $baseQuery)->where('status_progress', 99)->count(),
            'total_artikel'    => $this->countLuaran('artikel'),
            'total_sertifikat' => $this->countLuaran('sertifikat'),
            'total_hki'        => $this->countLuaran('hki'),
        ];
        return view('dekan.dekan_dashboard', compact('stats', 'pendingProposals'));
    }

    private function countLuaran($kategori)
    {
        return ProposalLampiran::where('kategori', $kategori)
            ->whereHas('proposal', function ($q) {
                $q->where('skala_pelaksanaan', 'Prodi')
                    ->where('status_progress', '>=', 2);
            })
            ->count();
    }
}
