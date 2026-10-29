<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Skema;
use App\Models\Skala;
use App\Models\Prodi;
use App\Models\Fakultas;
use App\Models\FakultasProdi;
use App\Models\Jabatan;

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
        $skalas = Skala::with('skemas')->get();
        return view('admin.customization.skema', compact('skalas'));
    }

    public function storeSkala(Request $request)
    {
        $request->validate(['nama' => 'required|unique:skalas,nama']);
        Skala::create($request->all());
        return back()->with('success', 'Skala Baru Berhasil Ditambahkan');
    }

    public function storeSkema(Request $request)
    {
        $request->validate([
            'skala_id' => 'required|exists:skalas,id',
            'label_dropdown' => 'required',
            'nama' => 'required'
        ]);
        Skema::create($request->all());
        return back()->with('success', 'Skema Berhasil Ditambahkan');
    }

    public function updateSkala(Request $request, $id)
    {
        $request->validate(['nama' => 'required|unique:skalas,nama,' . $id]);
        $skala = Skala::findOrFail($id);
        $skala->update(['nama' => $request->nama]);
        return back()->with('success', 'Nama Skala berhasil diperbarui.');
    }

    public function destroySkala($id)
    {
        Skala::destroy($id);
        return back()->with('success', 'Skala dan seluruh isinya berhasil dihapus.');
    }

    public function updateSkema(Request $request, $id)
    {
        $request->validate([
            'label_dropdown' => 'required',
            'nama' => 'required'
        ]);
        $skema = Skema::findOrFail($id);
        $skema->update([
            'label_dropdown' => $request->label_dropdown,
            'nama' => $request->nama
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
            'nama' => $request->nama
        ]);
        return back()->with('success', 'Program Studi berhasil ditambahkan.');
    }

    public function updateProdi(Request $request, $id)
    {
        $request->validate(['nama' => 'required|string|max:255']);
        $prodi = Prodi::findOrFail($id);
        $prodi->update([
            'nama' => $request->nama
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
            'nama' => 'required|string'
        ]);
        FakultasProdi::create([
            'fakultas_id' => $request->fakultas_id,
            'nama' => $request->nama
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
