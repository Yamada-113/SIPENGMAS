<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\Fakultas;
use App\Models\Jabatan;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $fakultasDB = Fakultas::with('prodis')->get();
        $jabatans = Jabatan::all();
        $fakultasData = [];
        foreach ($fakultasDB as $f) {
            $fakultasData[$f->nama] = $f->prodis->pluck('nama');
        }
        return view('profile.edit', [
            'user' => $request->user(),
            'fakultas' => $fakultasDB,
            'jabatans' => $jabatans,
            'fakultasData' => $fakultasData,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fakultas'           => ['required', 'string', 'max:100'],
            'prodi'              => ['required', 'string', 'max:100'],
            'jabatan_fungsional' => ['required', 'string', 'max:100'],
            'tanda_tangan'       => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'], // Max 2MB
        ]);

        $user = $request->user();
        $userData = collect($validated)->except(['tanda_tangan'])->toArray();
        $user->fill($userData);
        if ($request->hasFile('tanda_tangan')) {
            $file = $request->file('tanda_tangan');
            if ($user->tanda_tangan && Storage::exists('public/' . $user->tanda_tangan)) {
                Storage::delete('public/' . $user->tanda_tangan);
            }
            $filename = 'tanda_tangan/' . uniqid() . '.png'; // Force PNG for transparency
            $absolutePath = storage_path('app/public/' . $filename);
            if (!file_exists(dirname($absolutePath))) {
                mkdir(dirname($absolutePath), 0777, true);
            }
            $processed = $this->convertWhiteToTransparent($file->getPathname(), $absolutePath);
            if ($processed) {
                $user->tanda_tangan = $filename;
            } else {
                $path = $file->store('tanda_tangan', 'public');
                $user->tanda_tangan = $path;
            }
        }
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();
        return back()->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);
        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::to('/');
    }

    private function convertWhiteToTransparent($sourcePath, $destinationPath)
    {
        if (!extension_loaded('gd')) {
            return false;
        }
        $info = getimagesize($sourcePath);
        $mime = $info['mime'];
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $img = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $img = imagecreatefrompng($sourcePath);
                break;
            default:
                return false;
        }
        $originalWidth = imagesx($img);
        $originalHeight = imagesy($img);
        $targetWidth = 400;
        if ($originalWidth > $targetWidth) {
            $ratio = $targetWidth / $originalWidth;
            $targetHeight = (int) ($originalHeight * $ratio);
        } else {
            $targetWidth = $originalWidth;
            $targetHeight = $originalHeight;
        }
        $newImg = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($newImg, false);
        imagesavealpha($newImg, true);
        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        imagefill($newImg, 0, 0, $transparent);
        $tempImg = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopyresampled($tempImg, $img, 0, 0, 0, 0, $targetWidth, $targetHeight, $originalWidth, $originalHeight);
        imagedestroy($img);
        $threshold = 200;

        for ($x = 0; $x < $targetWidth; $x++) {
            for ($y = 0; $y < $targetHeight; $y++) {
                $rgb = imagecolorat($tempImg, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $brightness = ($r * 0.299) + ($g * 0.587) + ($b * 0.114);
                if ($brightness > $threshold) {
                    imagesetpixel($newImg, $x, $y, $transparent);
                } else {
                    $color = imagecolorallocatealpha($newImg, $r, $g, $b, 0);
                    imagesetpixel($newImg, $x, $y, $color);
                }
            }
        }
        $result = imagepng($newImg, $destinationPath);
        imagedestroy($tempImg);
        imagedestroy($newImg);
        return $result;
    }

    public function uploadSignatureAjax(Request $request)
    {
        $request->validate([
            'tanda_tangan' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);
        $user = $request->user();
        try {
            if ($request->hasFile('tanda_tangan')) {
                $file = $request->file('tanda_tangan');
                if ($user->tanda_tangan && Storage::exists('public/' . $user->tanda_tangan)) {
                    Storage::delete('public/' . $user->tanda_tangan);
                }
                $filename = 'tanda_tangan/' . uniqid() . '.png';
                $absolutePath = storage_path('app/public/' . $filename);
                if (!file_exists(dirname($absolutePath))) {
                    mkdir(dirname($absolutePath), 0777, true);
                }
                $processed = $this->convertWhiteToTransparent($file->getPathname(), $absolutePath);
                if ($processed) {
                    $user->tanda_tangan = $filename;
                } else {
                    $path = $file->store('tanda_tangan', 'public');
                    $user->tanda_tangan = $path;
                }
                $user->save();
                return response()->json(['success' => true, 'message' => 'Tanda tangan berhasil disimpan.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
        }

        return response()->json(['success' => false, 'message' => 'File tidak ditemukan.'], 400);
    }
}
