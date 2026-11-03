<?php

namespace App\Http\Controllers\Dekan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;

class DekanMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = date('Y');
        $years = range($currentYear + 1, 2019);
        $selectedYear = $request->input('year', $currentYear);
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $baseQuery = Proposal::with(['user', 'identitas', 'biaya'])
            ->where('skala_pelaksanaan', 'Prodi')
            ->where('tahun_pelaksanaan', $selectedYear);
        if ($search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->whereHas('identitas', fn($sub) => $sub->where('judul', 'like', "%$search%"))
                    ->orWhereHas('user', fn($sub) => $sub->where('name', 'like', "%$search%"));
            });
        }

        $data = [
            'menunggu' => (clone $baseQuery)->where('status_progress', 1)
                ->orderBy('updated_at', 'asc')
                ->paginate($perPage, ['*'], 'page_m')->withQueryString(),
            'disetujui' => (clone $baseQuery)->where('status_progress', '>=', 2)
                ->where('status_progress', '!=', 99)
                ->orderByDesc('updated_at')
                ->paginate($perPage, ['*'], 'page_d')->withQueryString(),
            'ditolak' => (clone $baseQuery)->where('status_progress', 99)
                ->orderByDesc('updated_at')
                ->paginate($perPage, ['*'], 'page_t')->withQueryString(),
        ];
        return view('dekan.dekan_monitoring', compact('data', 'years', 'selectedYear', 'perPage'));
    }
}
