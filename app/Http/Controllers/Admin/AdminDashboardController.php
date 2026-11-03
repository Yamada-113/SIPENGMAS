<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Proposal;
use App\Models\Panduan;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'proposal_keseluruhan' => Proposal::count(),
            'proposal_didanai'     => Proposal::where('status_progress', 4)->count(),
            'artikel'              => DB::table('proposal_lampiran')
                ->where('kategori', 'artikel')
                ->count(),
            'buku'                 => DB::table('proposal_lampiran')
                ->where('kategori', 'sertifikat')
                ->count(),
            'hki'                  => DB::table('proposal_lampiran')
                ->where('kategori', 'hki')
                ->count(),
        ];
        $kumpulanPanduan = Panduan::orderByDesc('created_at')->get();
        return view('admin.admin_dashboard', compact('stats', 'kumpulanPanduan'));
    }
}
