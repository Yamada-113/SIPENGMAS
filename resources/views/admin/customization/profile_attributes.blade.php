@extends('layouts.app')
@section('title', 'Manajemen Atribut Profil')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Manajemen Atribut Profil</h4>
                <p class="text-muted small mb-0">Kelola Fakultas, Prodi, dan Jabatan Fungsional.</p>
            </div>
            <a href="{{ route('admin.customization') }}" class="btn btn-outline-secondary btn-sm px-3 shadow-sm rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Hub
            </a>
        </div>

        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-teal text-white fw-bold d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-building me-2"></i> Fakultas & Program Studi</span>
                    </div>
                    <div class="card-body p-4 bg-light-subtle">

                        <form action="{{ route('admin.customization.fakultas.store') }}" method="POST" class="mb-4">
                            @csrf
                            <label class="small fw-bold text-muted mb-1">Tambah Fakultas Baru</label>
                            <div class="input-group">
                                <input type="text" name="nama" class="form-control"
                                    placeholder="Contoh: Fakultas Teknik..." required>
                                <button class="btn btn-teal text-white" type="submit"><i class="bi bi-plus-lg"></i>
                                    Simpan</button>
                            </div>
                        </form>

                        <hr class="text-muted opacity-25">

                        <div class="accordion" id="accordionFakultas">
                            @forelse($fakultas as $f)
                                <div class="accordion-item border-0 shadow-sm mb-3 rounded overflow-hidden">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold text-dark bg-white" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $f->id }}">
                                            {{ $f->nama }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $f->id }}" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionFakultas">
                                        <div class="accordion-body bg-light border-top">

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="badge bg-secondary">Daftar Prodi di {{ $f->nama }}</span>
                                                <form action="{{ route('admin.customization.fakultas.delete', $f->id) }}"
                                                    method="POST">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-xs btn-outline-danger py-0"
                                                        onclick="return confirm('Hapus Fakultas {{ $f->nama }} beserta seluruh prodi di dalamnya?')">Hapus
                                                        Fakultas</button>
                                                </form>
                                            </div>

                                            <ul class="list-group list-group-flush mb-3 rounded">
                                                @foreach ($f->prodis as $p)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center bg-white px-3 py-2">
                                                        <span class="small"><i class="bi bi-dot me-1"></i>
                                                            {{ $p->nama }}</span>
                                                        <form
                                                            action="{{ route('admin.customization.fakultas_prodi.delete', $p->id) }}"
                                                            method="POST">
                                                            @csrf @method('DELETE')
                                                            <button class="btn btn-link text-danger p-0"
                                                                onclick="return confirm('Hapus prodi ini?')"><i
                                                                    class="bi bi-x-circle-fill"></i></button>
                                                        </form>
                                                    </li>
                                                @endforeach
                                            </ul>

                                            <form action="{{ route('admin.customization.fakultas_prodi.store') }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="fakultas_id" value="{{ $f->id }}">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" name="nama" class="form-control"
                                                        placeholder="Tambah Prodi di sini..." required>
                                                    <button class="btn btn-outline-secondary" type="submit">Add</button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4 small">Belum ada Fakultas.</div>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-teal text-white fw-bold">
                        <i class="bi bi-award me-2"></i> Jabatan Fungsional
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.customization.jabatan.store') }}" method="POST" class="mb-4">
                            @csrf
                            <label class="small fw-bold text-muted mb-1">Tambah Jabatan</label>
                            <div class="input-group">
                                <input type="text" name="nama" class="form-control" placeholder="Contoh: Lektor"
                                    required>
                                <button class="btn btn-teal text-white" type="submit"><i
                                        class="bi bi-plus-lg"></i></button>
                            </div>
                        </form>

                        <div class="table-responsive border rounded">
                            <table class="table table-hover mb-0 align-middle table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-3 py-2">Nama Jabatan</th>
                                        <th class="text-end pe-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jabatans as $j)
                                        <tr>
                                            <td class="ps-3">{{ $j->nama }}</td>
                                            <td class="text-end pe-3">
                                                <form action="{{ route('admin.customization.jabatan.delete', $j->id) }}"
                                                    method="POST">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm text-danger border-0"
                                                        onclick="return confirm('Hapus Jabatan ini?')">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-3 small">Kosong.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .bg-teal {
            background-color: #378a75 !important;
        }

        .btn-teal {
            background-color: #378a75 !important;
            border-color: #378a75 !important;
        }

        .btn-teal:hover {
            background-color: #2c6e5d !important;
        }

        .accordion-button:not(.collapsed) {
            color: #378a75;
            background-color: #e6fcf5;
        }
    </style>
@endsection
