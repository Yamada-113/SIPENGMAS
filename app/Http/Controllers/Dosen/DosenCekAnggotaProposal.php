<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Services\IncidentReporter;
use Illuminate\Http\Request;
use LdapRecord\Container;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class DosenCekAnggotaProposal extends Controller
{
    public function checkId(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'string', 'regex:/^[0-9]{1,20}$/'],
        ], [
            'id.required' => 'NPM wajib diisi.',
            'id.regex' => 'NPM hanya boleh berisi angka dan maksimal 20 digit.',
        ]);
        $id = $validated['id'];

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
            throw new ServiceUnavailableHttpException(0, 'LDAP lookup unavailable', $e);
        }
    }
}
