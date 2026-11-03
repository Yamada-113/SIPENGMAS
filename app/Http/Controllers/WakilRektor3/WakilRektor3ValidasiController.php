<?php

namespace App\Http\Controllers\WakilRektor3;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationFlowService;

class WakilRektor3ValidasiController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = date('Y');
        $years = range($currentYear + 1, 2019);
        $selectedYear = $request->input('year', $currentYear);
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $baseQuery = Proposal::with(['user', 'identitas', 'biaya'])
            ->where('tahun_pelaksanaan', $selectedYear);
        if ($search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->whereHas('identitas', fn($sub) => $sub->where('judul', 'like', "%$search%"))
                    ->orWhereHas('user', fn($sub) => $sub->where('name', 'like', "%$search%"));
            });
        }
        $data = [
            'menunggu' => (clone $baseQuery)->where('status_progress', 3)
                ->orderBy('updated_at', 'asc') // FIFO
                ->paginate($perPage, ['*'], 'page_m')->withQueryString(),
            'disetujui' => (clone $baseQuery)->where('status_progress', 4)
                ->orderByDesc('updated_at')
                ->paginate($perPage, ['*'], 'page_d')->withQueryString(),
            'ditolak' => (clone $baseQuery)->where('status_progress', 99)
                ->orderByDesc('updated_at')
                ->paginate($perPage, ['*'], 'page_t')->withQueryString(),
        ];
        return view('wakil_rektor3.wakil_rektor3_validasi', compact('data', 'years', 'selectedYear', 'perPage'));
    }

    public function updateKeputusan(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        if ($proposal->status_progress != 3) {
            return back()->with('error', 'Gagal: Proposal tidak dalam antrian validasi Anda.');
        }
        $request->validate([
            'keputusan' => 'required|in:terima,tolak',
            'feedback' => 'required_if:keputusan,tolak'
        ]);
        try {
            DB::transaction(function () use ($request, $proposal) {
                if ($request->keputusan == 'terima') {
                    $proposal->update(['status_progress' => 4]);
                    NotificationFlowService::warekApproved($proposal);
                } else {
                    $proposal->update([
                        'status_progress' => 99,
                        'feedback' => $request->feedback
                    ]);
                    NotificationFlowService::proposalRejected($proposal, $request->feedback, 'Wakil Rektor 3');
                }
            });

            $pesan = $request->keputusan == 'terima' ? 'Proposal berhasil disetujui dan didanai.' : 'Proposal telah ditolak.';
            return redirect()->route('wakil_rektor.validasi')->with('success', $pesan);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
