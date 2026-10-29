@extends('layouts.app')
@section('title', 'Manajemen Program Studi')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Manajemen Program Studi</h4>
                <p class="text-muted small mb-0">Kelola daftar program studi anggota pengabdian.</p>
            </div>
            <a href="{{ route('admin.customization') }}" class="btn btn-outline-secondary btn-sm px-3 shadow-sm rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Hub
            </a>
        </div>

        <div class="row g-4">
            {{-- KOLOM TAMBAH --}}
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100 rounded-3 position-relative overflow-hidden">
                    <div class="card-header-accent bg-gradient-primary"></div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-square bg-primary-subtle text-primary me-3">
                                <i class="bi bi-mortarboard-fill fs-4"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Tambah Prodi<br><span class="text-muted small fw-normal">Input
                                    Baru</span></h6>
                        </div>
                        <form action="{{ route('admin.customization.prodi.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-secondary text-uppercase">Nama Program
                                    Studi</label>
                                <input type="text" name="nama" class="form-control"
                                    placeholder="Contoh: Teknik Informatika" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2">
                                <i class="bi bi-plus-lg me-1"></i> Simpan Prodi
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- KOLOM TABEL --}}
            <div class="col-md-8">
                <div class="card shadow-sm border-0 h-100 rounded-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr class="text-secondary small text-uppercase">
                                        <th class="ps-4 py-3">Nama Program Studi</th>
                                        <th class="text-end pe-4 py-3" width="25%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($prodis as $prodi)
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark">{{ $prodi->nama }}</td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-1">
                                                    <button class="btn btn-sm btn-blue" data-bs-toggle="modal"
                                                        data-bs-target="#editProdiModal{{ $prodi->id }}">
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </button>

                                                    <form
                                                        action="{{ route('admin.customization.prodi.delete', $prodi->id) }}"
                                                        method="POST">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-icon btn-outline-danger border-0"
                                                            onclick="return confirm('Hapus Prodi ini?')" title="Hapus">
                                                            <i class="bi bi-trash-fill"></i>
                                                        </button>
                                                    </form>
                                                </div>

                                                {{-- MODAL DI DALAM TD AGAR STRUKTUR TABEL VALID --}}
                                                <div class="modal fade text-start" id="editProdiModal{{ $prodi->id }}"
                                                    tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content border-0 shadow">
                                                            <div class="modal-header bg-blue text-white">
                                                                <h6 class="modal-title fw-bold"><i
                                                                        class="bi bi-pencil-square me-2"></i>Edit Prodi</h6>
                                                                <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form
                                                                action="{{ route('admin.customization.prodi.update', $prodi->id) }}"
                                                                method="POST">
                                                                @csrf @method('PUT')
                                                                <div class="modal-body p-4">
                                                                    <div class="mb-3">
                                                                        <label
                                                                            class="form-label small fw-bold text-muted">Nama
                                                                            Program Studi</label>
                                                                        <input type="text" name="nama"
                                                                            class="form-control" value="{{ $prodi->nama }}"
                                                                            required>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-sm btn-secondary"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-blue px-4 fw-bold">Update</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-5 text-muted">
                                                <img src="{{ asset('images/empty.png') }}" alt="Kosong"
                                                    style="width: 80px; opacity: 0.5;" class="mb-2">
                                                <p class="small mb-0">Belum ada data Program Studi.</p>
                                            </td>
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
        :root {
            --theme-primary: #378a75;
            --theme-primary-hover: #2c6e5d;
            --theme-soft: #ebf5f3;
            --theme-blue: #0d6efd;
            --theme-blue-hover: #0b5ed7;
        }

        .bg-gradient-primary {
            background: linear-gradient(90deg, #378a75 0%, #378a75 100%);
        }

        .bg-primary-subtle {
            background-color: var(--theme-soft) !important;
            color: var(--theme-primary) !important;
        }

        .text-primary {
            color: var(--theme-primary) !important;
        }

        .btn-primary {
            background-color: var(--theme-primary);
            border-color: var(--theme-primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--theme-primary-hover);
            border-color: var(--theme-primary-hover);
            color: white;
        }

        .btn-blue {
            background-color: var(--theme-blue);
            border-color: var(--theme-blue);
            color: white;
        }

        .btn-blue:hover {
            background-color: var(--theme-blue-hover);
            border-color: var(--theme-blue-hover);
            color: white;
        }

        .bg-blue {
            background-color: var(--theme-blue) !important;
        }

        .card-header-accent {
            height: 6px;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .icon-square {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }
    </style>
@endsection
