<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Proposal;
use App\Models\ProposalLampiran;

class DosenListStatistikController extends Controller
{
    public function showList(Request $request, $kategori)
    {
        $user = Auth::user();
        $userId = $user->id;
        $userNidn = $user->nidn;
        $perPage = in_array($request->input('per_page'), [5, 10, 25, 50]) ? $request->input('per_page') : 10;
        $search = $request->input('search');
        $currentYear = date('Y');
        $selectedYear = $request->input('year', $currentYear);
        $years = range($currentYear + 1, 2019);
        if (in_array($kategori, ['pengabdian', 'anggota', 'semua_usulan'])) {
            $pageTitle = match ($kategori) {
                'pengabdian' => 'Riwayat Pengabdian Didanai',
                'anggota'    => 'Riwayat Sebagai Anggota',
                default      => 'Semua Riwayat Usulan',
            };
            $query = Proposal::with(['identitas', 'user', 'biaya'])
                ->where('tahun_pelaksanaan', $selectedYear);
            if ($kategori == 'pengabdian') {
                $query->where('status_progress', 4);
                $query->where(function ($q) use ($userId, $userNidn) {
                    $q->where('user_id', $userId)
                        ->orWhereHas('anggotaDosen', function ($sub) use ($userNidn) {
                            $sub->where('nidn', $userNidn)
                                ->where('is_approved_dosen', 1);
                        });
                });
            } elseif ($kategori == 'anggota') {
                $query->where('user_id', '!=', $userId)
                    ->whereHas('anggotaDosen', function ($sub) use ($userNidn) {
                        $sub->where('nidn', $userNidn)
                            ->where('is_approved_dosen', 1);
                    });
            } elseif ($kategori == 'semua_usulan') {
                $query->where(function ($q) use ($userId, $userNidn) {
                    $q->where('user_id', $userId)
                        ->orWhereHas('anggotaDosen', function ($sub) use ($userNidn) {
                            $sub->where('nidn', $userNidn)
                                ->where('is_approved_dosen', 1);
                        });
                });
            }

            if ($search) {
                $query->whereHas('identitas', function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%");
                });
            }
            $items = $query->orderBy('created_at', 'desc')
                ->paginate($perPage)
                ->withQueryString();
        } else {
            $titles = [
                'artikel'    => 'Daftar Artikel Ilmiah',
                'sertifikat' => 'Daftar Sertifikat',
                'hki'        => 'Daftar HKI',
                'dokumen'    => 'Daftar Dokumen Lampiran'
            ];
            $pageTitle = $titles[$kategori] ?? ucfirst($kategori);
            $query = ProposalLampiran::with(['proposal.identitas', 'proposal.user'])
                ->where('kategori', $kategori);
            $query->whereHas('proposal', function ($q) use ($selectedYear) {
                $q->where('tahun_pelaksanaan', $selectedYear);
            });
            $query->whereHas('proposal', function ($q) use ($userId, $userNidn) {
                $q->where('user_id', $userId)
                    ->orWhereHas('anggotaDosen', function ($sub) use ($userNidn) {
                        $sub->where('nidn', $userNidn)
                            ->where('is_approved_dosen', 1);
                    });
            });
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhereHas('proposal.identitas', function ($sub) use ($search) {
                            $sub->where('judul', 'like', "%{$search}%");
                        });
                });
            }
            $items = $query->orderBy('created_at', 'desc')
                ->paginate($perPage)
                ->withQueryString();
        }
        return view('dosen.dosen_list_statistik', compact(
            'items',
            'pageTitle',
            'kategori',
            'search',
            'years',
            'selectedYear',
            'perPage'
        ));
    }
}
