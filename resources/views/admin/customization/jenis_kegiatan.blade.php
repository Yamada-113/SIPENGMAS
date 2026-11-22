@extends('layouts.app')

@section('title', 'Master Jenis Kegiatan')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Master Jenis Kegiatan</h4>
                <p class="text-muted small mb-0">Kelola pilihan jenis kegiatan pada form pengajuan proposal.</p>
            </div>
            <a href="{{ route('admin.customization') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Tambah Jenis Kegiatan</h6>
                        <form method="POST" action="{{ route('admin.customization.jenis_kegiatan.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label fw-bold small">Nama Jenis Kegiatan</label>
                                <input id="nama" type="text" name="nama" value="{{ old('nama') }}"
                                    maxlength="255" required
                                    class="form-control @error('nama') is-invalid @enderror"
                                    placeholder="Contoh: Internal">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-lg me-1"></i> Simpan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jenisKegiatans as $jenis)
                                    <tr>
                                        <td>{{ $jenis->nama }}</td>
                                        <td>
                                            @if ($jenis->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form method="POST"
                                                action="{{ route('admin.customization.jenis_kegiatan.update', $jenis) }}"
                                                class="d-flex justify-content-end gap-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="nama" value="{{ $jenis->nama }}"
                                                    maxlength="255" required class="form-control form-control-sm"
                                                    style="max-width: 220px;">
                                                <input type="hidden" name="is_active" value="0">
                                                <div class="form-check form-check-inline mt-2">
                                                    <input type="checkbox" name="is_active" value="1"
                                                        id="aktif-{{ $jenis->id }}"
                                                        class="form-check-input" @checked($jenis->is_active)>
                                                    <label for="aktif-{{ $jenis->id }}" class="form-check-label">Aktif</label>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-outline-primary">Simpan</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada data jenis kegiatan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
