<?php

namespace App\Http\Controllers\KepalaPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Proposal;
use App\Models\ProposalLampiran;
use App\Models\Skema;


class KepalaPusatListStatistikController extends Controller
{
    public function showList(Request $request, $kategori)
    {
        $user = Auth::user();
        $perPage = in_array($request->input('per_page'), [5, 10, 25, 50]) ? $request->input('per_page') : 10;
        $search = $request->input('search');
        $selectedYear = $request->input('year', date('Y'));
        $years = range(date('Y') + 1, 2019);
        $targetSkemaId = 0;
        $skemas = Skema::all();
        foreach ($skemas as $skema) {
            if ($skema->label_dropdown && stripos($user->name, $skema->label_dropdown) !== false) {
                $targetSkemaId = $skema->id;
                break;
            }
        }
        if ($targetSkemaId == 0) $targetSkemaId = (int) filter_var($user->role, FILTER_SANITIZE_NUMBER_INT);
        if (in_array($kategori, ['total_usulan', 'proposal_disetujui'])) {
            $pageTitle = ($kategori == 'total_usulan') ? 'Usulan Masuk (Pusat)' : 'Proposal Disetujui (Pusat)';
            $query = Proposal::with(['identitas', 'user', 'biaya'])
                ->where('tahun_pelaksanaan', $selectedYear)
                ->where('skala_pelaksanaan', 'Pusat')
                ->where('skema', $targetSkemaId);
            if ($kategori == 'total_usulan') {
                $query->where('status_progress', '>=', 2);
            } else {
                $query->where('status_progress', '>', 2)
                    ->where('status_progress', '!=', 99);
            }
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('identitas', fn($sub) => $sub->where('judul', 'like', "%{$search}%"))
                        ->orWhereHas('user', fn($sub) => $sub->where('name', 'like', "%{$search}%"));
                });
            }
            $items = $query->latest('updated_at')
                ->paginate($perPage)
                ->withQueryString();
        } else {
            $titles = ['artikel' => 'Rekap Artikel', 'sertifikat' => 'Rekap Sertifikat', 'hki' => 'Rekap HKI'];
            $pageTitle = $titles[$kategori] ?? ucfirst($kategori);
            $query = ProposalLampiran::with(['proposal.identitas', 'proposal.user'])
                ->where('kategori', $kategori)
                ->whereHas('proposal', function ($q) use ($targetSkemaId, $selectedYear) {
                    $q->where('skala_pelaksanaan', 'Pusat')
                        ->where('skema', $targetSkemaId)
                        ->where('tahun_pelaksanaan', $selectedYear)
                        ->where('status_progress', '>', 2)
                        ->where('status_progress', '!=', 99);
                });
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhereHas('proposal.identitas', fn($sub) => $sub->where('judul', 'like', "%{$search}%"));
                });
            }
            $items = $query->latest()->paginate($perPage)->withQueryString();
        }
        return view('kepala_pusat.kepala_pusat_list_statistik', compact(
            'items',
            'pageTitle',
            'kategori',
            'search',
            'years',
            'selectedYear'
        ));
    }
}
