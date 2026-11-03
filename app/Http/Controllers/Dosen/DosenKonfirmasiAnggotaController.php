<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\ProposalCoreAnggotaDosen;
use App\Models\Proposal;
use App\Services\NotificationFlowService;

class DosenKonfirmasiAnggotaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $currentYear = date('Y');
        $years = range($currentYear + 1, 2019);
        $selectedYear = $request->input('year', $currentYear);
        $perPage = in_array($request->input('per_page'), [5, 10, 50]) ? $request->input('per_page') : 10;
        $search = $request->input('search');
        $baseQuery = ProposalCoreAnggotaDosen::with([
            'proposal.user',
            'proposal.identitas',
            'proposal.biaya'
        ])->where('nidn', $user->nidn);
        $baseQuery->whereHas('proposal', function ($q) use ($selectedYear, $search) {
            $q->where('tahun_pelaksanaan', $selectedYear);
            if ($search) {
                $q->whereHas('identitas', fn($sq) => $sq->where('judul', 'like', "%{$search}%"));
            }
        });
        $data = [
            'invitations' => (clone $baseQuery)
                ->where('is_approved_dosen', 0)
                ->whereHas('proposal', fn($q) => $q->where('status_progress', 0))
                ->latest()
                ->paginate($perPage, ['*'], 'page_inv')
                ->withQueryString(),
            'waiting' => (clone $baseQuery)
                ->where('is_approved_dosen', 1)
                ->whereHas('proposal', fn($q) => $q->where('status_progress', 0))
                ->latest('updated_at')
                ->paginate($perPage, ['*'], 'page_wait')
                ->withQueryString(),
            'process' => (clone $baseQuery)
                ->where('is_approved_dosen', 1)
                ->whereHas('proposal', function ($q) {
                    $q->whereBetween('status_progress', [1, 3]);
                })
                ->latest('updated_at')
                ->paginate($perPage, ['*'], 'page_proc')
                ->withQueryString(),
            'finished' => (clone $baseQuery)
                ->where('is_approved_dosen', 1) // Pastikan user sudah join
                ->whereHas('proposal', function ($q) {
                    $q->whereIn('status_progress', [4, 99]);
                })
                ->latest('updated_at')
                ->paginate($perPage, ['*'], 'page_fin')
                ->withQueryString(),
        ];
        return view('dosen.dosen_konfirmasi_anggota', compact('data', 'years', 'selectedYear', 'perPage'));
    }

    public function terima($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $anggota = ProposalCoreAnggotaDosen::findOrFail($id);
                if ($anggota->nidn !== Auth::user()->nidn) {
                    throw new Exception('Unauthorized action.');
                }
                $proposal = Proposal::findOrFail($anggota->proposal_id);
                if ($proposal->status_progress != 0) {
                    throw new Exception('Gagal: Proposal sudah diproses atau dibatalkan.');
                }
                $anggota->update(['is_approved_dosen' => 1]);
                $pendingCount = ProposalCoreAnggotaDosen::where('proposal_id', $proposal->id)
                    ->where('is_approved_dosen', 0)
                    ->count();
                if ($pendingCount == 0) {
                    $proposal->update(['status_progress' => 1]);
                    if (class_exists(NotificationFlowService::class)) {
                        NotificationFlowService::anggotaApproved($proposal);
                    }
                }
            });

            return back()->with('success', 'Berhasil bergabung dengan proposal.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function tolak($id)
    {
        try {
            $anggota = ProposalCoreAnggotaDosen::findOrFail($id);
            if ($anggota->nidn !== Auth::user()->nidn) {
                throw new Exception('Unauthorized action.');
            }
            DB::transaction(function () use ($anggota) {
                Proposal::where('id', $anggota->proposal_id)->update(['status_progress' => 99]);
                $anggota->delete();
            });
            return back()->with('success', 'Undangan ditolak. Proposal dibatalkan.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
