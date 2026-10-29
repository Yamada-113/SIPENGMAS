<?php

namespace App\Http\Controllers\KepalaPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Skema;

class KepalaPusatValidasiController extends Controller
{
    public function index(Request $request)
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
        if (!$centerId) {
            abort(403, 'Gagal mendeteksi Skema Pusat. Pastikan nama akun Kepala Pusat mengandung nama Pusat Studinya.');
        }
        $currentYear = date('Y');
        $years = range($currentYear + 1, 2019);
        $selectedYear = $request->input('year', $currentYear);
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $baseQuery = Proposal::with(['user', 'identitas', 'biaya'])
            ->where('tahun_pelaksanaan', $selectedYear)
            ->where('skala_pelaksanaan', 'Pusat')
            ->where('skema', $centerId);
        if ($search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->whereHas('identitas', fn($sub) => $sub->where('judul', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn($sub) => $sub->where('name', 'like', "%{$search}%"));
            });
        }
        $data = [
            'menunggu' => (clone $baseQuery)->where('status_progress', 2)
                ->orderBy('updated_at', 'asc')
                ->paginate($perPage, ['*'], 'page_m')
                ->withQueryString(),
            'disetujui' => (clone $baseQuery)->where('status_progress', '>', 2)
                ->where('status_progress', '!=', 99)
                ->orderByDesc('updated_at')
                ->paginate($perPage, ['*'], 'page_d')
                ->withQueryString(),
            'ditolak' => (clone $baseQuery)->where('status_progress', 99)
                ->orderByDesc('updated_at')
                ->paginate($perPage, ['*'], 'page_t')
                ->withQueryString(),
        ];

        return view('kepala_pusat.kepala_pusat_validasi', compact('data', 'years', 'selectedYear', 'perPage'));
    }

    public function updateKeputusan(Request $request, $id)
    {
        $proposal = Proposal::with('skemaRef')->findOrFail($id);
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
        if ($proposal->skala_pelaksanaan != 'Pusat' || $proposal->skema != $centerId) {
            return back()->with('error', 'Anda tidak memiliki akses validasi proposal ini.');
        }
        if ($proposal->status_progress != 2) {
            return back()->with('error', 'Gagal: Status proposal sudah berubah.');
        }
        $request->validate([
            'keputusan' => 'required|in:terima,tolak',
            'feedback'  => 'required_if:keputusan,tolak'
        ]);
        try {
            DB::transaction(function () use ($request, $proposal) {
                if ($request->keputusan == 'terima') {
                    $proposal->update(['status_progress' => 3]);
                } else {
                    $proposal->update([
                        'status_progress' => 99,
                        'feedback' => $request->feedback
                    ]);
                }
            });
            $pesan = $request->keputusan == 'terima' ? 'Proposal berhasil disetujui.' : 'Proposal telah ditolak.';
            return redirect()->route('kepala_pusat.validasi')->with('success', $pesan);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
