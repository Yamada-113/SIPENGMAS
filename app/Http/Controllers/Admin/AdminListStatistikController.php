<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\ProposalLampiran;

class AdminListStatistikController extends Controller
{
    public function list(Request $request, $kategori)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        switch ($kategori) {
            case 'proposal_keseluruhan':
                $pageTitle = 'Daftar Keseluruhan Proposal';
                $query = Proposal::with(['user', 'identitas', 'biaya'])->orderByDesc('created_at');
                break;
            case 'proposal_didanai':
                $pageTitle = 'Daftar Proposal Didanai';
                $query = Proposal::with(['user', 'identitas', 'biaya'])->where('status_progress', 4)->orderByDesc('created_at');
                break;
            case 'artikel':
                $pageTitle = 'Daftar Artikel Ilmiah';
                $query = ProposalLampiran::with(['proposal.user', 'proposal.identitas'])->where('kategori', 'artikel')->orderByDesc('created_at');
                break;
            case 'buku':
            case 'sertifikat':
                $pageTitle = 'Daftar Sertifikat Seminar';
                $kategori = 'sertifikat';
                $query = ProposalLampiran::with(['proposal.user', 'proposal.identitas'])->where('kategori', 'sertifikat')->orderByDesc('created_at');
                break;
            case 'hki':
                $pageTitle = 'Daftar Hak Kekayaan Intelektual (HKI)';
                $query = ProposalLampiran::with(['proposal.user', 'proposal.identitas'])->where('kategori', 'hki')->orderByDesc('created_at');
                break;
            default:
                abort(404);
        }

        if ($search) {
            if (in_array($kategori, ['proposal_keseluruhan', 'proposal_didanai'])) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('identitas', fn($sub) => $sub->where('judul', 'like', "%{$search}%"))
                        ->orWhereHas('user', fn($sub) => $sub->where('name', 'like', "%{$search}%"));
                });
            } else {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhereHas('proposal.identitas', fn($sub) => $sub->where('judul', 'like', "%{$search}%"))
                        ->orWhereHas('proposal.user', fn($sub) => $sub->where('name', 'like', "%{$search}%"));
                });
            }
        }

        $items = $query->paginate($perPage)->withQueryString();
        return view('admin.admin_list_statistik', compact('items', 'pageTitle', 'kategori', 'search'));
    }
}
