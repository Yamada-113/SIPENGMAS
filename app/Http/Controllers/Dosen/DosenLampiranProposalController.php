<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DosenLampiranProposalController extends Controller
{
    public function store(Request $request, $proposal_id)
    {
        ini_set('memory_limit', '256M');
        $request->validate([
            'kategori'    => 'required|in:dokumen,artikel,sertifikat,hki',
            'judul'       => 'required|string|max:255',
            'file_upload' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        try {
            if ($request->hasFile('file_upload')) {
                $file = $request->file('file_upload');
                $fileName = $proposal_id . '_' . time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('lampiran', $fileName, 'public');
                DB::table('proposal_lampiran')->insert([
                    'proposal_id' => $proposal_id,
                    'kategori'    => $request->kategori,
                    'judul'       => $request->judul,
                    'file_path'   => $path,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
                return redirect()->back()->with('success', ucfirst($request->kategori) . ' berhasil disimpan!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        return redirect()->back()->with('error', 'Gagal mengunggah file.');
    }
}
