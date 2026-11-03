@extends('layouts.app')
@section('title', 'Manajemen Skala & Skema')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">Manajemen Skala & Skema</h4>
                <p class="text-muted small mb-0">Kelola Kategori Skala dan Skema Pengabdian Disini!!! :)</p>
            </div>
            <a href="{{ route('admin.customization') }}" class="btn btn-outline-secondary btn-sm px-3 shadow-sm rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Hub
            </a>
        </div>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100 rounded-3 position-relative overflow-hidden">
                    <div class="card-header-accent bg-gradient-primary"></div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-square bg-primary-subtle text-primary me-3">
                                <i class="bi bi-tags-fill fs-4"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Tambah Skala<br><span class="text-muted small fw-normal">Kategori
                                    Baru</span></h6>
                        </div>

                        <p class="text-muted small bg-light p-2 rounded border border-dashed mb-3">
                            <i class="bi bi-info-circle me-1"></i> Contoh: Eksternal, Fakultas, Universitas.
                        </p>

                        <form action="{{ route('admin.customization.skala.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-secondary text-uppercase">Nama Skala</label>
                                <input type="text" name="nama" class="form-control form-control-lg fs-6"
                                    placeholder="Contoh: Universitas" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2">
                                <i class="bi bi-plus-lg me-1"></i> Simpan Skala
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0 h-100 rounded-3 position-relative overflow-hidden">
                    <div class="card-header-accent bg-gradient-success"></div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-square bg-success-subtle text-success me-3">
                                <i class="bi bi-list-check fs-4"></i>
                            </div>
                            <h6 class="fw-bold mb-0">Tambah Skema<br><span class="text-muted small fw-normal">Item dalam
                                    Kategori</span></h6>
                        </div>

                        <form action="{{ route('admin.customization.skema.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-secondary text-uppercase">Pilih Skala
                                        Induk</label>
                                    <select name="skala_id" class="form-select" required>
                                        @foreach ($skalas as $skala)
                                            <option value="{{ $skala->id }}">{{ $skala->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-bold small text-secondary text-uppercase">Label
                                        Dropdown</label>
                                    <input type="text" name="label_dropdown" class="form-control"
                                        placeholder="Contoh: Teknik Informatika" required>
                                    <div class="form-text small">Nama pendek yang muncul di pilihan menu dosen.</div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-secondary text-uppercase">Nama Lengkap
                                        Skema</label>
                                    <input type="text" name="nama" class="form-control"
                                        placeholder="Contoh: Skema Program Internal Prodi Teknik Informatika" required>
                                    <div class="form-text small">Nama resmi yang akan dicetak di proposal/surat.</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-success fw-bold shadow-sm px-4 py-2">
                                    <i class="bi bi-save me-2"></i> Simpan Skema Baru
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h5 class="fw-bold mb-3 border-start border-4 border-primary ps-3 text-dark">Daftar Skema Aktif</h5>

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-0">
                        @foreach ($skalas as $index => $skala)
                            <div class="{{ $index == 0 ? '' : 'border-top' }} p-4 hover-bg-light transition-bg">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <span
                                            class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold fs-6">
                                            {{ $skala->nama }}
                                        </span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-action text-blue bg-blue-subtle"
                                            data-bs-toggle="modal" data-bs-target="#editSkalaModal{{ $skala->id }}"
                                            title="Edit Nama Kategori">
                                            <i class="bi bi-pencil-square"></i> <span
                                                class="d-none d-md-inline ms-1">Edit</span>
                                        </button>
                                        <form action="{{ route('admin.customization.skala.delete', $skala->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('PERINGATAN KERAS:\n\nMenghapus Kategori {{ $skala->nama }} akan menghapus SEMUA SKEMA di dalamnya secara permanen.\n\nLanjutkan?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-action text-danger bg-danger-subtle"
                                                title="Hapus Kategori">
                                                <i class="bi bi-trash"></i> <span
                                                    class="d-none d-md-inline ms-1">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                @if ($skala->skemas->count() > 0)
                                    <div class="table-responsive rounded border">
                                        <table class="table table-hover align-middle mb-0 bg-white">
                                            <thead class="bg-light">
                                                <tr class="text-secondary small text-uppercase">
                                                    <th class="ps-3 py-2" width="25%">Label Dropdown</th>
                                                    <th class="py-2" width="60%">Nama Lengkap Skema</th>
                                                    <th class="py-2 text-end pe-3" width="15%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($skala->skemas as $item)
                                                    <tr>
                                                        <td class="ps-3 fw-bold text-dark">{{ $item->label_dropdown }}</td>
                                                        <td class="text-secondary">{{ $item->nama }}</td>
                                                        <td class="text-end pe-3">
                                                            <div class="d-flex justify-content-end gap-1">
                                                                <button class="btn btn-blue border-0"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editSkemaModal{{ $item->id }}"
                                                                    title="Edit Item">
                                                                    <i class="bi bi-pencil-fill"></i>
                                                                </button>
                                                                <form
                                                                    action="{{ route('admin.customization.skema.delete', $item->id) }}"
                                                                    method="POST">
                                                                    @csrf @method('DELETE')
                                                                    <button
                                                                        class="btn btn-sm btn-icon btn-outline-danger border-0"
                                                                        onclick="return confirm('Hapus skema ini?')"
                                                                        title="Hapus Item">
                                                                        <i class="bi bi-trash-fill"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    <div class="modal fade" id="editSkemaModal{{ $item->id }}"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content border-0 shadow">
                                                                <div class="modal-header bg-blue text-white">
                                                                    <h6 class="modal-title fw-bold"><i
                                                                            class="bi bi-pencil-square me-2"></i>Edit Skema
                                                                    </h6>
                                                                    <button type="button"
                                                                        class="btn-close btn-close-white"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <form
                                                                    action="{{ route('admin.customization.skema.update', $item->id) }}"
                                                                    method="POST">
                                                                    @csrf @method('PUT')
                                                                    <div class="modal-body p-4">
                                                                        <div class="mb-3">
                                                                            <label
                                                                                class="form-label small fw-bold text-muted">Label
                                                                                Dropdown</label>
                                                                            <input type="text" name="label_dropdown"
                                                                                class="form-control"
                                                                                value="{{ $item->label_dropdown }}"
                                                                                required>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label
                                                                                class="form-label small fw-bold text-muted">Nama
                                                                                Lengkap Skema</label>
                                                                            <textarea name="nama" class="form-control" rows="3" required>{{ $item->nama }}</textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer bg-light">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-secondary"
                                                                            data-bs-dismiss="modal">Batal</button>
                                                                        <button type="submit"
                                                                            class="btn btn-sm btn-blue px-4 fw-bold">Update</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-3 border border-dashed rounded bg-light text-muted small">
                                        <i class="bi bi-inbox me-2"></i> Belum ada skema di kategori ini.
                                    </div>
                                @endif
                            </div>

                            <div class="modal fade" id="editSkalaModal{{ $skala->id }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-blue text-white">
                                            <h6 class="modal-title fw-bold"><i class="bi bi-tags-fill me-2"></i>Edit Nama
                                                Kategori</h6>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.customization.skala.update', $skala->id) }}"
                                            method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="mb-1">
                                                    <label class="form-label small fw-bold text-muted">Nama Skala</label>
                                                    <input type="text" name="nama" class="form-control"
                                                        value="{{ $skala->nama }}" required>
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
                        @endforeach
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
            --theme-blue-soft: #cfe2ff;
        }

        .text-primary {
            color: var(--theme-primary) !important;
        }

        .bg-primary {
            background-color: var(--theme-primary) !important;
        }

        .bg-primary-subtle {
            background-color: var(--theme-soft) !important;
            color: var(--theme-primary) !important;
        }

        .border-primary {
            border-color: var(--theme-primary) !important;
        }

        .border-primary-subtle {
            border-color: #bfe0d8 !important;
        }

        .btn-primary {
            background-color: var(--theme-primary);
            border-color: var(--theme-primary);
        }

        .btn-primary:hover {
            background-color: var(--theme-primary-hover);
            border-color: var(--theme-primary-hover);
        }

        .btn-outline-primary {
            color: var(--theme-primary);
            border-color: var(--theme-primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--theme-primary);
            color: white;
        }

        .bg-blue {
            background-color: var(--theme-blue) !important;
        }

        .text-blue {
            color: var(--theme-blue) !important;
        }

        .bg-blue-subtle {
            background-color: var(--theme-blue-soft) !important;
            color: var(--theme-blue) !important;
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

        .card-header-accent {
            height: 6px;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .bg-gradient-primary {
            background: linear-gradient(90deg, #378a75 0%, #6abfa8 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(90deg, #198754 0%, #46d389 100%);
        }

        .icon-square {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .btn-action {
            font-weight: 600;
            border: none;
            transition: all 0.2s;
        }

        .btn-action:hover {
            filter: brightness(0.9);
            transform: translateY(-1px);
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .transition-bg {
            transition: background-color 0.2s ease;
        }

        .hover-bg-light:hover {
            background-color: #fcfcfc;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--theme-primary);
            box-shadow: 0 0 0 0.25rem rgba(55, 138, 117, 0.25);
        }
    </style>
@endsection
