<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\ProposalLampiran;
use App\Exports\RekapProposalExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminRekapitulasiProposalController extends Controller
{
    public function index(Request $request)
    {
        $startYear = 2019;
        $currentYear = date('Y');
        $years = range($currentYear + 1, $startYear);
        $selectedYear = $request->input('year', $currentYear);
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $query = Proposal::with(['user', 'identitas', 'biaya'])
            ->where('tahun_pelaksanaan', $selectedYear);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('identitas', fn($sub) => $sub->where('judul', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn($sub) => $sub->where('name', 'like', "%{$search}%"));
            });
        }

        $data = [
            'proses' => (clone $query)->where('status_progress', '<', 4)
                ->where('status_progress', '!=', 99)
                ->latest()
                ->paginate($perPage, ['*'], 'page_p')
                ->withQueryString(),
            'didanai'  => (clone $query)->where('status_progress', 4)
                ->latest()
                ->paginate($perPage, ['*'], 'page_d')
                ->withQueryString(),
            'ditolak'  => (clone $query)->where('status_progress', 99)
                ->latest()
                ->paginate($perPage, ['*'], 'page_t')
                ->withQueryString(),
        ];
        return view('admin.admin_rekapitulasi_proposal', compact('data', 'years', 'selectedYear'));
    }

    public function detail($id)
    {
        $detailProposal = Proposal::with([
            'identitas',
            'biaya',
            'atribut',
            'user',
            'anggotaDosen',
            'anggotaMahasiswa'
        ])->findOrFail($id);
        $ketua = (object) [
            'nama'  => $detailProposal->user->name ?? '-',
            'peran' => 'Ketua Pengusul',
            'tipe'  => 'Ketua'
        ];
        $anggota = collect([$ketua])
            ->merge($detailProposal->anggotaDosen)
            ->merge($detailProposal->anggotaMahasiswa);
        $lampiran = ProposalLampiran::where('proposal_id', $id)->get();
        return view('admin.admin_detail_proposal', compact('detailProposal', 'anggota', 'lampiran'));
    }

    public function updateKeputusan(Request $request, $id)
    {
        $request->validate([
            'status_keputusan' => 'required|in:Didanai,Ditolak',
            'feedback'         => 'required_if:status_keputusan,Ditolak',
        ]);
        $proposal = Proposal::findOrFail($id);
        if ($request->status_keputusan == 'Didanai') {
            $proposal->update(['status_progress' => 4]);
        } else {
            $proposal->update([
                'status_progress' => 99,
                'feedback' => $request->feedback
            ]);
        }
        return redirect()->back()->with('success', 'Status proposal berhasil diperbarui.');
    }
    public function exportExcel(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $status = $request->input('status', 'proses');
        $fileName = 'Rekapitulasi_Proposal_' . ucfirst($status) . '_' . $year . '.xlsx';
        return Excel::download(new RekapProposalExport($year, $status), $fileName);
    }
}
