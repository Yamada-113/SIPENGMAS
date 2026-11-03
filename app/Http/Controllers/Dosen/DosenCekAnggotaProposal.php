<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LdapRecord\Container;

class DosenCekAnggotaProposal extends Controller
{
    public function checkId(Request $request)
    {
        $id = trim($request->query('id'));
        try {
            $connection = Container::getConnection('default');
            $user = $connection->query()
                ->where('description', '=', $id)
                ->first();
            if ($user) {
                $nama = $user['displayname'][0] ?? $user['cn'][0] ?? 'Nama Tidak Ditemukan';
                return response()->json([
                    'success' => true,
                    'nama' => $nama,
                ]);
            }
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan']);
        } catch (\Exception $e) {
            Log::error("Detail Error LDAP: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
