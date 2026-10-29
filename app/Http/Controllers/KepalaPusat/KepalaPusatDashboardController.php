<?php

namespace App\Http\Controllers\KepalaPusat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Proposal;
use App\Models\ProposalLampiran;
use App\Models\Skema;

class KepalaPusatDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $targetSkemaId = 0;
        $skemas = Skema::all();
        foreach ($skemas as $skema) {
            $label = $skema->label_dropdown;
            $cleanLabel = preg_replace('/\s*\(.*?\)\s*/', '', $label);
            $cleanLabel = str_ireplace(['Pusat', 'Yarsi', 'YARSI'], '', $cleanLabel);
            $cleanLabel = trim($cleanLabel);
            if (!empty($cleanLabel) && stripos($user->name, $cleanLabel) !== false) {
                $targetSkemaId = $skema->id;
                break;
            }
        }
        if ($targetSkemaId == 0) {
            $targetSkemaId = (int) filter_var($user->role, FILTER_SANITIZE_NUMBER_INT);
        }
        $baseQuery = Proposal::where('skala_pelaksanaan', 'Pusat')
            ->where('skema', $targetSkemaId)
            ->where('status_progress', '>=', 2);
        $stats = [
            'total_usulan' => (clone $baseQuery)->count(),
            'disetujui'    => (clone $baseQuery)->where('status_progress', '>', 2)->count(),
            'pending_count' => (clone $baseQuery)->where('status_progress', 2)->count(),
            'total_artikel'    => $this->countLuaran('artikel', $targetSkemaId),
            'total_sertifikat' => $this->countLuaran('sertifikat', $targetSkemaId),
            'total_hki'        => $this->countLuaran('hki', $targetSkemaId),
        ];
        $pendingProposals = Proposal::with(['identitas', 'user'])
            ->where('skala_pelaksanaan', 'Pusat')
            ->where('skema', $targetSkemaId)
            ->where('status_progress', 2)
            ->orderBy('updated_at', 'asc')
            ->get();
        return view('kepala_pusat.kepala_pusat_dashboard', compact('stats', 'pendingProposals'));
    }

    private function countLuaran($kategori, $skemaId)
    {
        return ProposalLampiran::where('kategori', $kategori)
            ->whereHas('proposal', function ($q) use ($skemaId) {
                $q->where('skala_pelaksanaan', 'Pusat')
                    ->where('skema', $skemaId)
                    ->where('status_progress', '>', 2);
            })
            ->count();
    }
}
