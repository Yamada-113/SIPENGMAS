@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <div class="container">
        @php
            $role = auth()->user()->role ?? '';
            $backRoute = match (true) {
                str_contains($role, 'Admin') => 'admin.dashboard',
                str_contains($role, 'Dosen') => 'dosen.dashboard',
                str_contains($role, 'Wakil Dekan') => 'wakil_dekan3.dashboard',
                str_contains($role, 'Dekan') => 'dekan.dashboard',
                str_contains($role, 'Kepala Pusat') => 'kepala_pusat.dashboard',
                str_contains($role, 'Wakil Rektor') => 'wakil_rektor.dashboard',
                default => 'login',
            };
        @endphp

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1 text-primary">Profil Saya</h4>
                <p class="text-muted mb-0">Kelola informasi akun dan identitas akademik Anda.</p>
            </div>
            <a href="{{ route($backRoute) }}" class="btn btn-secondary d-flex align-items-center">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
            </a>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> Profil berhasil diperbarui.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-person-badge me-2"></i>Informasi Akun</h6>
            </div>
            <div class="card-body p-4">
                <form id="form-update-profile" method="post" action="{{ route('profile.update') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    <div class="row">
                        <div class="col-md-6 border-end pe-md-4">
                            <h6 class="fw-bold text-secondary mb-3 small text-uppercase">Data Akun</h6>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Username</label>
                                <input type="text" name="username" class="form-control bg-light"
                                    value="{{ old('username', $user->username ?? '') }}" readonly style="cursor: not-allowed;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                                <input type="text" class="form-control bg-light" name="name"
                                    value="{{ $user->name ?? '' }}" readonly style="pointer-events: none; cursor: not-allowed;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted d-block">Hak Akses</label>
                                @php
                                    $badgeColor = match (true) {
                                        str_contains($role, 'Admin') => 'danger',
                                        str_contains($role, 'Dosen') => 'primary',
                                        str_contains($role, 'Dekan') || str_contains($role, 'Wakil') => 'warning',
                                        str_contains($role, 'Kepala') => 'info',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeColor }} fs-6 px-4 py-2 d-inline-block text-center"
                                    style="min-width: 120px; border-radius: 8px;">
                                    {{ ucfirst($role) }}
                                </span>
                            </div>

                            @php
                                $allowedKeywords = [
                                    'Dosen',
                                    'Dekan',
                                    'Kepala Pusat 1',
                                    'Kepala Pusat 2',
                                    'Kepala Pusat 3',
                                    'Kepala Pusat 4',
                                    'Kepala Pusat 5',
                                    'Wakil Rektor 3',
                                ];

                                $canUploadSignature = false;

                                if (\Illuminate\Support\Str::contains($role, 'Dosen')) {
                                    $canUploadSignature = true;
                                } elseif (in_array($role, $allowedKeywords)) {
                                    $canUploadSignature = true;
                                }
                            @endphp

                            @if ($canUploadSignature)
                                <div class="mb-3 mt-4">
                                    <label class="form-label fw-bold small text-muted">Tanda Tangan Digital</label>
                                    <div class="card bg-light border-0 p-3">
                                    @if (!empty($user->tanda_tangan))
                                        <div class="mb-3 text-center bg-white border rounded p-2" style="border-style: dashed !important;">
                                            <p class="small text-muted mb-1">Tanda Tangan Saat Ini:</p>
                                            
                                            @php
                                                $filename = basename($user->tanda_tangan);
                                            @endphp

                                            <img src="{{ route('storage.view', ['filename' => $filename]) }}" 
                                                alt="Tanda Tangan"
                                                class="img-fluid" 
                                                style="max-height: 100px; object-fit: contain;">
                                        </div>
                                    @endif

                                        <input type="file"
                                            class="form-control @error('tanda_tangan') is-invalid @enderror"
                                            name="tanda_tangan" accept="image/png, image/jpeg, image/jpg">
                                        <div class="form-text small text-muted">
                                            <i class="bi bi-info-circle me-1"></i>Format: PNG/JPG. Max: 2MB.<br>
                                        </div>

                                        @error('tanda_tangan')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                        </div>

                        <div class="col-md-6 ps-md-4 mt-4 mt-md-0">
                            <h6 class="fw-bold text-secondary mb-3 small text-uppercase">Data Akademik</h6>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">NIDN</label>
                                <input type="text" name="nidn" class="form-control bg-light"
                                    value="{{ old('nidn', $user->nidn ?? '') }}" readonly style="cursor: not-allowed;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Fakultas</label>
                                <select class="form-select @error('fakultas') is-invalid @enderror" id="fakultas_edit"
                                    name="fakultas" onchange="updateProdiEdit()" required>
                                    <option value="" disabled selected>Pilih Fakultas</option>

                                    @foreach ($fakultas as $f)
                                        <option value="{{ $f->nama }}"
                                            {{ old('fakultas', $user->fakultas ?? '') == $f->nama ? 'selected' : '' }}>
                                            {{ $f->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('fakultas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Program Studi</label>
                                <select class="form-select @error('prodi') is-invalid @enderror" id="prodi_edit"
                                    name="prodi" required>
                                    <option value="" disabled selected>Pilih Fakultas Terlebih Dahulu</option>
                                </select>
                                @error('prodi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Jabatan Fungsional</label>
                                <select class="form-select @error('jabatan_fungsional') is-invalid @enderror"
                                    id="jabatan_fungsional" name="jabatan_fungsional" required>
                                    <option value="" disabled {{ empty($user->jabatan_fungsional) ? 'selected' : '' }}>
                                        Pilih Jabatan</option>

                                    @foreach ($jabatans as $j)
                                        <option value="{{ $j->nama }}"
                                            {{ old('jabatan_fungsional', $user->jabatan_fungsional ?? '') == $j->nama ? 'selected' : '' }}>
                                            {{ $j->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jabatan_fungsional')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kolom Google Scholar ID yang sudah ada di desain awal, kini tombol link-nya difungsikan -->
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Google Scholar ID</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('google_scholar_id') is-invalid @enderror"
                                        id="google_scholar_id" name="google_scholar_id"
                                        value="{{ old('google_scholar_id', $user->google_scholar_id ?? '') }}"
                                        placeholder="AbCdEf1234Gh">
                                    
                                    <button type="button" class="btn btn-outline-secondary" id="btn-open-scholar" title="Buka Profil Google Scholar">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </button>
                                </div>
                                <div class="form-text small text-muted">
                                    Opsional. ID profil Google Scholar Anda.
                                </div>
                                @error('google_scholar_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="https://www.yarsi.ac.id/ganti-password-akun-yarsi" target="_blank"
                            class="btn btn-outline-primary fw-bold px-4">
                            <i class="bi bi-key me-2"></i>Ubah Password
                        </a>

                        <button type="button" class="btn btn-primary fw-bold px-4" data-bs-toggle="modal"
                            data-bs-target="#modalKonfirmasiSimpan">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalKonfirmasiSimpan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold">Konfirmasi Simpan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <h6 class="fw-bold text-dark mt-3">Simpan perubahan profil?</h6>
                    <p class="text-muted small">Data dan tanda tangan Anda akan diperbarui di sistem.</p>
                </div>
                <div class="modal-footer justify-content-center bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="form-update-profile" class="btn btn-primary fw-bold px-4">Ya,
                        Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Menggunakan atribut data-* untuk passing data JSON agar lolos dari pengecekan syntax HTML/VS Code --}}
    <div id="prodi-json-data" data-prodi="{{ json_encode($fakultasData ?? []) }}" style="display: none;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil data prodi dari atribut data-* agar editor bersih dari garis merah
            const jsonElement = document.getElementById('prodi-json-data');
            const prodiDataEdit = jsonElement ? JSON.parse(jsonElement.getAttribute('data-prodi')) : {};

            window.updateProdiEdit = function() {
                const fakultasSelect = document.getElementById("fakultas_edit");
                const prodiSelect = document.getElementById("prodi_edit");
                const selectedFakultas = fakultasSelect ? fakultasSelect.value : "";
                const currentProdi = "{{ old('prodi', $user->prodi ?? '') }}";

                if (!prodiSelect) return;

                prodiSelect.innerHTML = '<option value="" disabled selected>Pilih Program Studi</option>';

                if (selectedFakultas && prodiDataEdit[selectedFakultas]) {
                    prodiDataEdit[selectedFakultas].forEach(prodi => {
                        let option = document.createElement("option");
                        option.value = prodi;
                        option.textContent = prodi;

                        if (prodi === currentProdi) {
                            option.selected = true;
                        }

                        prodiSelect.appendChild(option);
                    });
                }
            };

            updateProdiEdit();

            // Fungsi interaktif tombol Google Scholar agar bisa diklik membuka tab baru
            const btnScholar = document.getElementById('btn-open-scholar');
            const inputScholar = document.getElementById('google_scholar_id');

            if (btnScholar && inputScholar) {
                btnScholar.addEventListener('click', function() {
                    const scholarId = inputScholar.value.trim();
                    if (!scholarId) {
                        alert('Silakan isi Google Scholar ID terlebih dahulu!');
                        inputScholar.focus();
                        return;
                    }
                    const url = `https://scholar.google.com/citations?user=${encodeURIComponent(scholarId)}`;
                    window.open(url, '_blank');
                });
            }
        });
    </script>
@endsection