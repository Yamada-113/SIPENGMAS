<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJenisKegiatanRequest;
use App\Http\Requests\Admin\UpdateJenisKegiatanRequest;
use App\Models\JenisKegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JenisKegiatanController extends Controller
{
    public function index(): View
    {
        $jenisKegiatans = JenisKegiatan::orderBy('nama')->get();

        return view('admin.customization.jenis_kegiatan', compact('jenisKegiatans'));
    }

    public function store(StoreJenisKegiatanRequest $request): RedirectResponse
    {
        JenisKegiatan::create([
            'nama' => trim($request->validated('nama')),
            'is_active' => true,
        ]);

        return back()->with('success', 'Jenis kegiatan berhasil ditambahkan.');
    }

    public function update(UpdateJenisKegiatanRequest $request, JenisKegiatan $jenisKegiatan): RedirectResponse
    {
        $jenisKegiatan->update([
            'nama' => trim($request->validated('nama')),
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Jenis kegiatan berhasil diperbarui.');
    }
}
