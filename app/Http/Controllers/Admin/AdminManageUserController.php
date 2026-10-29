<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManageUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::oldest();
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('nidn', 'like', "%{$search}%");
            });
        }
        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, [5, 10, 50, 100])) {
            $perPage = 10;
        }
        $users = $query->paginate($perPage)->appends($request->query());
        return view('admin.admin_manage_user', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nidn' => 'nullable|numeric|unique:users,nidn',
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:3',
            'role' => 'required',
        ]);
        User::create([
            'nidn' => $request->nidn,
            'name' => $request->name,
            'fakultas' => $request->fakultas,
            'prodi' => $request->prodi,
            'username' => $request->username,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
        return redirect()->back()->with('success', 'Akun berhasil ditambahkan!');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'nidn' => 'nullable|numeric|unique:users,nidn,' . $user->id,
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:3',
        ]);
        $data = [
            'nidn' => $request->nidn,
            'name' => $request->name,
            'fakultas' => $request->fakultas,
            'prodi' => $request->prodi,
            'username' => $request->username,
        ];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return redirect()->back()->with('success', 'Data akun berhasil diperbarui!');
    }
}
