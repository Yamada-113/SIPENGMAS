<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JenisKegiatan;
use Illuminate\Http\JsonResponse;

class JenisKegiatanController extends Controller
{
    public function index(): JsonResponse
    {
        $jenisKegiatans = JenisKegiatan::active()
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return response()->json([
            'data' => $jenisKegiatans,
        ]);
    }
}
