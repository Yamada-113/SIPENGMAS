<?php

namespace App\Http\Controllers\WakilDekan3;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationFlowService;

class WakilDekan3ValidasiController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = date('Y');
        $years = range($currentYear + 1, 2019);
        $selectedYear = $request->input('year', $currentYear);
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $baseQuery = Proposal::with(['user', 'identitas', 'biaya', 'skemaRef'])
            ->where('tahun_pelaksanaan', $selectedYear);
        if ($search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->whereHas('identitas', fn($sub) => $sub->where('judul', 'like', "%$search%"))
                    ->orWhereHas('user', fn($sub) => $sub->where('name', 'like', "%$search%"));
            });
        }
        $data = [
            'menunggu' => (clone $baseQuery)->where('status_progress', 1)
                ->orderByDesc('updated_at')->paginate($perPage, ['*'], 'page_m')->withQueryString(),

            'disetujui' => (clone $baseQuery)->where('status_progress', '>', 1)->where('status_progress', '!=', 99)
                ->orderByDesc('updated_at')->paginate($perPage, ['*'], 'page_d')->withQueryString(),

            'ditolak' => (clone $baseQuery)->where('status_progress', 99)
                ->orderByDesc('updated_at')->paginate($perPage, ['*'], 'page_t')->withQueryString(),
        ];
        return view('wakil_dekan3.wakil_dekan3_validasi', compact('data', 'years', 'selectedYear', 'perPage'));
    }

    public function updateKeputusan(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        $request->validate([
            'keputusan' => 'required|in:terima,tolak',
            'feedback'  => 'required_if:keputusan,tolak'
        ]);
        if ($proposal->status_progress != 1) {
            return back()->with('error', 'Gagal: Proposal tidak dalam antrian Anda.');
        }
        DB::beginTransaction(); // Gunakan beginTransaction manual agar lebih fleksibel
        try {
            if ($request->keputusan == 'terima') {
                $isPusat = trim($proposal->skala_pelaksanaan) == 'Pusat';
                $nextStatus = $isPusat ? 2 : 3;
                $proposal->update(['status_progress' => $nextStatus]);
                NotificationFlowService::wadekApproved($proposal);
                $pesan = 'Proposal disetujui. Notifikasi dikirim ke tahap selanjutnya.';
            } else {
                $proposal->update([
                    'status_progress' => 99,
                    'feedback' => $request->feedback
                ]);
                NotificationFlowService::proposalRejected($proposal, $request->feedback, 'Wakil Dekan 3');
                $pesan = 'Proposal telah ditolak.';
            }
            DB::commit();
            return back()->with('success', $pesan);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error Validasi Wadek 3: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function notifyDekanUploadTTD(Request $request)
    {
        $isSent = NotificationFlowService::remindDekanToUploadSignature();
        if ($isSent) {
            return back()->with('success', 'Notifikasi pengingat berhasil dikirim ke Dekan.');
        }
        return back()->with('error', 'Data akun Dekan tidak ditemukan.');
    }
}
