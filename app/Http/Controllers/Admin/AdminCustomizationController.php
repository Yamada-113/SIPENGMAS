<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\FakultasProdi;
use App\Models\Jabatan;
use App\Models\Prodi;
use App\Models\Setting;
use App\Models\Skala;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminCustomizationController extends Controller
{
    public function index()
    {
        return view('admin.customization.index');
    }

    public function general()
    {
        $settings = Setting::all();

        return view('admin.customization.general', compact('settings'));
    }

    public function skema()
    {
        $skalas = Skala::with('skemas')->orderBy('nama')->get();
        $activeSkalas = Skala::active()->orderBy('nama')->get();

        return view('admin.customization.skema', compact('skalas', 'activeSkalas'));
    }

    public function storeSkala(Request $request)
    {
        $request->merge(['nama' => trim((string) $request->input('nama'))]);
        $validated = $request->validateWithBag('storeSkala', [
            'nama' => ['required', 'string', 'max:255', 'unique:skalas,nama'],
        ], [
            'nama.required' => 'Nama skala wajib diisi.',
            'nama.unique' => 'Nama skala ini sudah ada.',
        ]);

        Skala::create([
            'nama' => trim($validated['nama']),
            'is_active' => true,
        ]);

        return back()->with('success', 'Skala Baru Berhasil Ditambahkan');
    }

    public function storeSkema(Request $request)
    {
        $request->merge(['nama' => trim((string) $request->input('nama'))]);
        $validated = $request->validateWithBag('storeSkema', [
            'skala_id' => [
                'required',
                Rule::exists('skalas', 'id')->where('is_active', true),
            ],
            'label_dropdown' => ['required', 'string', 'max:255'],
            'nama' => ['required', 'string', 'max:255', Rule::unique('skemas', 'nama')],
        ], [
            'nama.required' => 'Nama skema wajib diisi.',
            'nama.unique' => 'Nama skema ini sudah ada.',
        ]);
        Skema::create([
            'skala_id' => $validated['skala_id'],
            'label_dropdown' => trim($validated['label_dropdown']),
            'nama' => $validated['nama'],
            'is_active' => true,
        ]);

        return back()->with('success', 'Skema Berhasil Ditambahkan');
    }

    public function updateSkala(Request $request, $id)
    {
        $request->merge(['nama' => trim((string) $request->input('nama'))]);
        $validated = $request->validateWithBag('updateSkala', [
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('skalas', 'nama')->ignore($id),
            ],
            'is_active' => ['sometimes', 'boolean'],
        ], [
            'nama.required' => 'Nama skala wajib diisi.',
            'nama.unique' => 'Nama skala ini sudah ada.',
        ]);

        $skala = Skala::findOrFail($id);
        $skala->update([
            'nama' => trim($validated['nama']),
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Nama Skala berhasil diperbarui.');
    }

    public function destroySkala($id)
    {
        Skala::destroy($id);

        return back()->with('success', 'Skala dan seluruh isinya berhasil dihapus.');
    }

    public function updateSkema(Request $request, $id)
    {
        $request->merge(['nama' => trim((string) $request->input('nama'))]);
        $validated = $request->validateWithBag('updateSkema', [
            'label_dropdown' => ['required', 'string', 'max:255'],
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('skemas', 'nama')->ignore($id),
            ],
            'is_active' => ['sometimes', 'boolean'],
        ], [
            'nama.required' => 'Nama skema wajib diisi.',
            'nama.unique' => 'Nama skema ini sudah ada.',
        ]);
        $skema = Skema::findOrFail($id);
        $skema->update([
            'label_dropdown' => trim($validated['label_dropdown']),
            'nama' => $validated['nama'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Data Skema berhasil diperbarui.');
    }

    public function destroySkema($id)
    {
        Skema::destroy($id);

        return back()->with('success', 'Skema berhasil dihapus');
    }

    public function prodi()
    {
        $prodis = Prodi::all();

        return view('admin.customization.prodi', compact('prodis'));
    }

    public function storeProdi(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255']);
        Prodi::create([
            'nama' => $request->nama,
        ]);

        return back()->with('success', 'Program Studi berhasil ditambahkan.');
    }

    public function updateProdi(Request $request, $id)
    {
        $request->validate(['nama' => 'required|string|max:255']);
        $prodi = Prodi::findOrFail($id);
        $prodi->update([
            'nama' => $request->nama,
        ]);

        return back()->with('success', 'Program Studi berhasil diperbarui.');
    }

    public function destroyProdi($id)
    {
        Prodi::destroy($id);

        return back()->with('success', 'Program Studi berhasil dihapus.');
    }

    public function profileAttributes()
    {
        $fakultas = Fakultas::with('prodis')->get();
        $jabatans = Jabatan::all();

        return view('admin.customization.profile_attributes', compact('fakultas', 'jabatans'));
    }

    public function storeFakultas(Request $request)
    {
        $request->validate(['nama' => 'required|string']);
        Fakultas::create(['nama' => $request->nama]);

        return back()->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function destroyFakultas($id)
    {
        Fakultas::destroy($id);

        return back()->with('success', 'Fakultas beserta prodinya berhasil dihapus.');
    }

    public function storeFakultasProdi(Request $request)
    {
        $request->validate([
            'fakultas_id' => 'required|exists:fakultas,id',
            'nama' => 'required|string',
        ]);
        FakultasProdi::create([
            'fakultas_id' => $request->fakultas_id,
            'nama' => $request->nama,
        ]);

        return back()->with('success', 'Prodi berhasil ditambahkan ke Fakultas tersebut.');
    }

    public function destroyFakultasProdi($id)
    {
        FakultasProdi::destroy($id);

        return back()->with('success', 'Prodi berhasil dihapus dari Fakultas.');
    }

    public function storeJabatan(Request $request)
    {
        $request->validate(['nama' => 'required|string']);
        Jabatan::create(['nama' => $request->nama]);

        return back()->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function destroyJabatan($id)
    {
        Jabatan::destroy($id);

        return back()->with('success', 'Jabatan berhasil dihapus.');
    }
}
