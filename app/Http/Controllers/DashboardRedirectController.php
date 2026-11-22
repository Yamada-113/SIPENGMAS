<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class DashboardRedirectController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $role = auth()->user()?->role;

        $routes = [
            'Admin' => 'admin.dashboard',
            'Dosen' => 'dosen.dashboard',
            'Dosen Pengusul' => 'dosen.dashboard',
            'Dosen Penanggung Jawab' => 'dosen.dashboard',
            'Wakil Dekan 3' => 'wakil_dekan3.dashboard',
            'Dekan' => 'dekan.dashboard',
            'Wakil Rektor 3' => 'wakil_rektor.dashboard',
            'Kepala Pusat 1' => 'kepala_pusat.dashboard',
            'Kepala Pusat 2' => 'kepala_pusat.dashboard',
            'Kepala Pusat 3' => 'kepala_pusat.dashboard',
            'Kepala Pusat 4' => 'kepala_pusat.dashboard',
            'Kepala Pusat 5' => 'kepala_pusat.dashboard',
        ];

        if (! isset($routes[$role])) {
            abort(403);
        }

        return redirect()->route($routes[$role]);
    }
}
