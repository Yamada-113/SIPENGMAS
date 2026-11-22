<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\JenisKegiatan;
use App\Models\Prodi;
use App\Models\Skala;
use App\Models\Skema;

class DosenPengajuanSkemaDanForm extends Controller
{
    public function showSkema()
    {
        $allData = Skala::active()
            ->with(['skemas' => function ($q) {
                $q->where('is_active', 1);
            }])
            ->orderBy('nama')
            ->get();
        $skemaList = [];
        foreach ($allData as $s) {
            $skemaList[$s->nama] = $s->skemas->map(function ($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->label_dropdown,
                    'nama' => $item->nama,
                ];
            });
        }
        $skalaOptions = $allData->pluck('nama');

        return view('dosen.dosen_pengajuan_skema', compact('skemaList', 'skalaOptions'));
    }

    public function showForm($year, $skemaId, $role)
    {
        $dataFakultas = Fakultas::with('prodis')->get();
        $skema = Skema::find($skemaId);
        $namaSkema = $skema ? $skema->nama : 'Skema Tidak Ditemukan / Non-Aktif';
        $prodis = Prodi::all();
        $skalas = Skala::active()->orderBy('nama')->get();
        $jenisKegiatan = JenisKegiatan::active()->orderBy('nama')->get();

        return view('dosen.dosen_pengajuan_proposal', [
            'skemaId' => $skemaId,
            'selectedYear' => $year,
            'selectedRole' => $role,
            'namaSkema' => $namaSkema,
            'prodis' => $prodis,
            'dataFakultas' => $dataFakultas,
            'skalas' => $skalas,
            'jenisKegiatan' => $jenisKegiatan,
        ]);
    }
}
