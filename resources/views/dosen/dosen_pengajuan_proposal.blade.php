@extends('layouts.app')

@section('title', 'Form Pengajuan Proposal')

@section('content')
    {{-- 1. CEK STATUS TANDA TANGAN (PHP) --}}
    @php
        $userHasSignature = !empty(Auth::user()->tanda_tangan);
    @endphp

    {{-- 2. CONTAINER NOTIFIKASI MELAYANG (FLOATING) --}}
    <div id="floating-alert-container"
        style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;">
    </div>

    {{-- 3. MODAL UPLOAD TANDA TANGAN (AJAX) --}}
    <div class="modal fade" id="modalUploadSignature" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning text-dark border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Tanda Tangan
                        Diperlukan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-3">
                        <i class="bi bi-pen text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <div class="alert alert-light border mb-3 small text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Anda belum memiliki tanda tangan digital. Mohon unggah sekarang agar dapat dibubuhkan pada lembar
                        pengesahan secara otomatis.
                    </div>

                    {{-- Form AJAX --}}
                    <form id="formAjaxSignature" enctype="multipart/form-data">
                        @csrf
                        <div class="card bg-light border-0 p-3 mb-3">
                            <label class="form-label fw-bold small text-muted">File Tanda Tangan</label>
                            <input type="file" class="form-control" id="ajax_tanda_tangan" name="tanda_tangan"
                                accept="image/png, image/jpeg, image/jpg" required>
                            <div class="form-text small text-muted">
                                <i class="bi bi-check-circle me-1"></i>Format: PNG/JPG (Max 2MB).<br>
                                <i class="bi bi-magic me-1"></i>Background putih akan otomatis dihapus.
                            </div>
                            <div id="ajax-error-msg" class="text-danger small mt-2 d-none fw-bold"></div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold" id="btnSaveAjaxSignature">
                                <span id="btn-text-upload">Simpan & Lanjutkan Pengajuan</span>
                                <span id="btn-loader-upload" class="d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. KONTEN UTAMA --}}
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <a href="{{ route('dosen.pengajuan.skema') }}"
                class="btn btn-secondary btn-action-control d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
                <span>Kembali</span>
            </a>
            <div style="width: 100px;"></div>
        </div>

        {{-- Notifikasi Error Global --}}
        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showFloatingAlert("{{ session('error') }}", 'danger');
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    showFloatingAlert("Terdapat kolom yang belum diisi atau salah. Mohon periksa kembali.", 'danger');
                });
            </script>
        @endif

        <h1 class="mb-4 ms-4" style="font-weight: 600">Form Pengajuan Proposal</h1>

        <form action="{{ route('dosen.tesproposal.store') }}" method="POST" enctype="multipart/form-data"
            id="formPengajuan" novalidate>
            @csrf

            {{-- DETAIL PROPOSAL --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold text-dark">Detail Proposal</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Tahun Pelaksanaan</label>
                            <input type="text" class="form-control bg-light" name="tahun_pelaksanaan"
                                value="{{ $selectedYear }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Skala Pelaksanaan</label>
                            <input type="text" class="form-control bg-light" name="skala_pelaksanaan"
                                value="{{ $selectedRole }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Skema yang Dipilih</label>
                            <input type="text" class="form-control bg-light" value="{{ $namaSkema }}" disabled>
                            <input type="hidden" name="skema" value="{{ $skemaId }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- UPLOAD PROPOSAL --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold"> Upload Proposal</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="upload_proposal" class="form-label">
                            Upload Proposal Pengabdian Anda Disini (Tanpa Lembar Pengesahan) <span
                                class="text-danger">*</span>
                        </label>
                        <input type="file" required class="form-control @error('file_proposal') is-invalid @enderror"
                            id="upload_proposal" name="file_proposal" accept=".pdf,.doc,.docx">
                        <div class="form-text text-muted">
                            Format yang diizinkan: PDF, DOC, DOCX. Maksimal ukuran file: 10MB.
                        </div>
                        <div class="invalid-feedback">File proposal wajib diunggah.</div>
                        @error('file_proposal')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 1. IDENTITAS USULAN --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">1. Identitas Usulan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
                            <small id="judul-counter" class="form-text text-muted">0/300</small>
                        </div>
                        {{-- SESUAI CONTROLLER: max 300 --}}
                        <textarea required class="form-control @error('judul') is-invalid @enderror" name="judul" id="judul"
                            rows="3" maxlength="300">{{ old('judul') }}</textarea>
                        <div class="invalid-feedback">Judul wajib diisi.</div>
                        @error('judul')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="abstrak" class="form-label">Abstrak <span class="text-danger">*</span></label>
                            <small id="abstrak-counter" class="form-text text-muted">0/500</small>
                        </div>
                        {{-- SESUAI CONTROLLER: max 500 --}}
                        <textarea required class="form-control @error('abstrak') is-invalid @enderror" name="abstrak" id="abstrak"
                            rows="4" maxlength="500">{{ old('abstrak') }}</textarea>
                        <div class="invalid-feedback">Abstrak wajib diisi.</div>
                        @error('abstrak')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label">Keyword <span class="text-danger">*</span></label>
                            <small id="keyword-counter" class="form-text text-muted">0/150</small>
                        </div>
                        {{-- SESUAI CONTROLLER: max 150 --}}
                        <textarea required class="form-control @error('keyword') is-invalid @enderror" name="keyword" id="keyword"
                            rows="2" maxlength="150">{{ old('keyword') }}</textarea>
                        <div class="invalid-feedback">Keyword wajib diisi.</div>
                        @error('keyword')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Periode Kegiatan <span class="text-danger">*</span></label>
                            <select class="form-select @error('periode_kegiatan') is-invalid @enderror"
                                name="periode_kegiatan">
                                <option value="1" {{ old('periode_kegiatan') == 1 ? 'selected' : '' }}>1 Tahun
                                </option>
                                <option value="2" {{ old('periode_kegiatan') == 2 ? 'selected' : '' }}>2 Tahun
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bidang Fokus</label>
                            {{-- SESUAI CONTROLLER: max 50 (readonly aman, tapi tetap kita set) --}}
                            <input type="text" class="form-control bg-light" name="bidang_fokus"
                                value="Teknologi Informasi" readonly maxlength="50">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Tendik</label>
                            <input type="text"
                                class="form-control input-rupiah @error('jumlah_tendik') is-invalid @enderror"
                                name="jumlah_tendik" maxlength="3" placeholder="0" value="{{ old('jumlah_tendik') }}">
                            @error('jumlah_tendik')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Mahasiswa</label>
                            <input type="text"
                                class="form-control input-rupiah @error('jumlah_mahasiswa') is-invalid @enderror"
                                name="jumlah_mahasiswa" maxlength="3" placeholder="0" value="{{ old('jumlah_mahasiswa') }}">
                            @error('jumlah_mahasiswa')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                </div>
            </div>

            {{-- 2. ATRIBUT & MITRA --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">2. Atribut Usulan & Mitra</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="rumpun_ilmu" class="form-label mb-1">Rumpun Ilmu <span
                                    class="text-danger">*</span></label>
                            <small id="rumpun_ilmu-counter" class="form-text text-muted">0/50</small>
                        </div>
                        {{-- SESUAI CONTROLLER: max 50 --}}
                        <input required type="text" class="form-control @error('rumpun_ilmu') is-invalid @enderror"
                            id="rumpun_ilmu" name="rumpun_ilmu" value="{{ old('rumpun_ilmu') }}" maxlength="50">
                        <div class="invalid-feedback">Rumpun ilmu wajib diisi.</div>
                    </div>
                    <hr>
                    <h6 class="fw-bold">MITRA</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="mitra_nama" class="form-label mb-1">Nama Institusi <span
                                        class="text-danger">*</span></label>
                                <small id="mitra_nama-counter" class="form-text text-muted">0/50</small>
                            </div>
                            {{-- SESUAI CONTROLLER: max 50 --}}
                            <input required type="text"
                                class="form-control @error('nama_institusi_mitra') is-invalid @enderror"
                                name="nama_institusi_mitra" id="mitra_nama" value="{{ old('nama_institusi_mitra') }}"
                                maxlength="50">
                            <div class="invalid-feedback">Nama mitra wajib diisi.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="mitra_penanggung_jawab" class="form-label mb-1">Penanggung Jawab </label>
                                <small id="mitra_penanggung_jawab-counter" class="form-text text-muted">0/50</small>
                            </div>
                            <input type="text"
                                class="form-control @error('penanggung_jawab_mitra') is-invalid @enderror"
                                name="penanggung_jawab_mitra" id="mitra_penanggung_jawab"
                                value="{{ old('penanggung_jawab_mitra') }}" maxlength="50">
                            <div class="invalid-feedback">Penanggung jawab mitra wajib diisi.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="mitra_alamat" class="form-label mb-1">Alamat <span
                                    class="text-danger">*</span></label>
                            <small id="mitra_alamat-counter" class="form-text text-muted">0/250</small>
                        </div>
                        {{-- SESUAI CONTROLLER: max 250 --}}
                        <textarea required class="form-control @error('alamat_mitra') is-invalid @enderror" name="alamat_mitra"
                            id="mitra_alamat" rows="3" maxlength="250">{{ old('alamat_mitra') }}</textarea>
                        <div class="invalid-feedback">Alamat mitra wajib diisi.</div>
                    </div>
                </div>
            </div>

            {{-- 3. URAIAN UMUM --}}
            <!-- <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">3. Identitas dan Uraian Umum</h6>
                </div>
                <div class="card-body">
                    {{-- Baris 1: Objek Pengabdian (Full Width) --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="objek_pengabdian" class="form-label mb-1">Objek Pengabdian <span
                                    class="text-danger">*</span></label>
                            <small id="objek_pengabdian-counter" class="form-text text-muted">0/50</small>
                        </div>
                        {{-- SESUAI CONTROLLER: max 50 --}}
                        <input required type="text"
                            class="form-control @error('objek_pengabdian') is-invalid @enderror" name="objek_pengabdian"
                            id="objek_pengabdian" value="{{ old('objek_pengabdian') }}" maxlength="50">
                        <div class="invalid-feedback">Objek pengabdian wajib diisi.</div>
                    </div>

                    <hr>
                    <h6 class="fw-bold">DETAIL URAIAN</h6>

                    {{-- Baris 2: Instansi & Temuan (2 Kolom) --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="instasi_lain" class="form-label mb-1">Instansi lain yang terlibat</label>
                                <small id="instasi_lain-counter" class="form-text text-muted">0/50</small>
                            </div>
                            {{-- SESUAI CONTROLLER: max 50 --}}
                            <input type="text" class="form-control @error('instansi_terlibat') is-invalid @enderror"
                                name="instansi_terlibat" id="instasi_lain" value="{{ old('instansi_terlibat') }}"
                                maxlength="50">
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="temuan_target" class="form-label mb-1">Temuan yang ditargetkan <span
                                        class="text-danger">*</span></label>
                                <small id="temuan_target-counter" class="form-text text-muted">0/50</small>
                            </div>
                            {{-- SESUAI CONTROLLER: max 50 --}}
                            <input required type="text"
                                class="form-control @error('temuan_ditargetkan') is-invalid @enderror"
                                name="temuan_ditargetkan" id="temuan_target" value="{{ old('temuan_ditargetkan') }}"
                                maxlength="50">
                            <div class="invalid-feedback">Temuan target wajib diisi.</div>
                        </div>
                    </div>

                    {{-- Baris 3: Lokasi Pengabdian (Textarea Full Width) --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="lokasi_pengabdian" class="form-label mb-1">Lokasi Pengabdian <span
                                    class="text-danger">*</span></label>
                            <small id="lokasi_pengabdian-counter" class="form-text text-muted">0/100</small>
                        </div>
                        {{-- SESUAI CONTROLLER: max 100 (Controller: 100, Bukan 250) --}}
                        <textarea required class="form-control @error('lokasi_pengabdian') is-invalid @enderror" name="lokasi_pengabdian"
                            id="lokasi_pengabdian" rows="3" maxlength="100">{{ old('lokasi_pengabdian') }}</textarea>
                        <div class="invalid-feedback">Lokasi pengabdian wajib diisi.</div>
                    </div>
                </div>
            </div> -->

            {{-- 4. ANGGOTA DOSEN --}}
            <div class="card shadow-sm mb-4" id="card-anggota-dosen">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">3. Penambahan Anggota Pengabdian <span class="text-danger">*</span></h6>
                </div>
                <div class="card-body">
                    <div id="error-anggota-dosen" class="alert alert-danger d-none p-2 mb-3">
                        <small><i class="bi bi-exclamation-circle me-1"></i> Wajib menambahkan minimal 1 Anggota
                            Dosen.</small>
                    </div>
                    @error('anggota_dosen')
                        <div class="alert alert-danger p-2 mb-3"><small>{{ $message }}</small></div>
                    @enderror

                    <div class="mb-3">
                        <label class="form-label">Nomor Induk Pegawai (NIP)</label>
                        <div class="input-group">
                            {{-- SESUAI CONTROLLER: max 20 --}}
                            <input type="text" class="form-control only-number" id="nidn_input" inputmode="numeric"
                                maxlength="20">
                            <button type="button" class="btn btn-success" id="btnCheckNIDN">Check</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-1">Nama</label>
                        <input type="text" class="form-control bg-light" id="nama_dosen_form" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-1">Fakultas</label>
                        <select class="form-select" id="fakultas_dosen_form">
                            <option value="" selected disabled>-- Pilih Fakultas --</option>
                            @foreach ($dataFakultas as $fakultas)
                                <option value="{{ $fakultas->nama }}" data-prodis="{{ json_encode($fakultas->prodis) }}">
                                    {{ $fakultas->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-1">Program Studi</label>
                        <select class="form-select" id="prodi_dosen_form" disabled>
                            <option value="" selected disabled>-- Pilih Fakultas Terlebih Dahulu --</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-1">Peran Personil</label>
                        <input type="text" class="form-control bg-light" id="peran_dosen_form" readonly>
                    </div>
                    <div class="d-flex justify-content-end mb-4 align-items-center">
                        <button class="btn btn-success" type="button" id="btnTambahDosen">Tambahkan Anggota</button>
                    </div>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama & NIP</th>
                                <th>Fakultas</th>
                                <th>Prodi</th>
                                <th>Peran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabelAnggotaDosen">
                            @if (old('anggota_dosen'))
                                @foreach (old('anggota_dosen') as $index => $dosen)
                                    <tr>
                                    <td class="text-center row-number">{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $dosen['nama'] }}</strong><br>
                                            <small>{{ $dosen['nidn'] }}</small>
                                            <input type="hidden" name="anggota_dosen[{{ $index }}][nidn]" value="{{ $dosen['nidn'] }}">
                                            <input type="hidden" name="anggota_dosen[{{ $index }}][nama]" value="{{ $dosen['nama'] }}">
                                            
                                            {{-- HIDDEN INPUT FAKULTAS --}}
                                            <input type="hidden" name="anggota_dosen[{{ $index }}][fakultas_dosen]" value="{{ $dosen['fakultas_dosen'] }}">
                                            
                                            <input type="hidden" name="anggota_dosen[{{ $index }}][prodi_dosen]" value="{{ $dosen['prodi_dosen'] }}">
                                            <input type="hidden" name="anggota_dosen[{{ $index }}][peran]" value="{{ $dosen['peran'] }}">
                                        </td>
                                        <td>{{ $dosen['fakultas_dosen'] }}</td>
                                        <td>{{ $dosen['prodi_dosen'] }}</td>
                                        <td>
                                            <div class="role-text">{{ $dosen['peran'] }}</div>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 5. ANGGOTA MAHASISWA --}}
            <div class="card shadow-sm mb-4" id="card-anggota-mhs">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">4. Penambahan Anggota Mahasiswa <span class="text-danger">*</span></h6>
                </div>
                <div class="card-body">
                    <div id="error-anggota-mhs" class="alert alert-danger d-none p-2 mb-3">
                        <small><i class="bi bi-exclamation-circle me-1"></i> Wajib menambahkan minimal 1 Anggota
                            Mahasiswa.</small>
                    </div>
                    @error('anggota_mhs')
                        <div class="alert alert-danger p-2 mb-3"><small>{{ $message }}</small></div>
                    @enderror

                    <div class="mb-3">
                        <label class="form-label mb-1">Nomor Pokok Mahasiswa (NPM)</label>
                        <div class="input-group">
                            {{-- SESUAI CONTROLLER: max 20 --}}
                            <input type="text" class="form-control only-number" id="npm_input" inputmode="numeric"
                                maxlength="20">
                            <button type="button" class="btn btn-success" id="btnCheckNPM">Check</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-1">Nama</label>
                        <input type="text" class="form-control bg-light" id="nama_mhs_form" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-1">Program Studi</label>
                        <select class="form-select" id="prodi_mhs_form">
                            {{-- Default Teknik Informatika --}}
                            <option value="Teknik Informatika" selected>Teknik Informatika</option>
                            @foreach ($prodis as $prodi)
                                @if ($prodi->nama !== 'Teknik Informatika')
                                    <option value="{{ $prodi->nama }}">{{ $prodi->nama }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-1">Peran Personil</label>
                        <input type="text" class="form-control bg-light" id="peran_mhs_form" readonly>
                    </div>
                    <button type="button" class="btn btn-success float-end mb-3" id="btnTambahMhs">Tambahkan
                        Mahasiswa</button>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama & NPM</th>
                                <th>Prodi</th>
                                <th>Peran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabelAnggotaMhs">
                            @if (old('anggota_mhs'))
                                @foreach (old('anggota_mhs') as $index => $mhs)
                                    <tr>
                                        <td class="text-center row-number">{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $mhs['nama'] }}</strong><br>
                                            <small>{{ $mhs['npm'] }}</small>
                                            <input type="hidden" name="anggota_mhs[{{ $index }}][npm]"
                                                value="{{ $mhs['npm'] }}">
                                            <input type="hidden" name="anggota_mhs[{{ $index }}][nama]"
                                                value="{{ $mhs['nama'] }}">
                                            <input type="hidden" name="anggota_mhs[{{ $index }}][prodi]"
                                                value="{{ $mhs['prodi'] }}">
                                            <input type="hidden" name="anggota_mhs[{{ $index }}][peran]"
                                                value="{{ $mhs['peran'] }}">
                                        </td>
                                        <td>{{ $mhs['prodi'] }}</td>
                                        <td>
                                            <div class="role-text">{{ $mhs['peran'] }}</div>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 6. BIAYA --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">5. Rencana Anggaran Biaya</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label">Honor<span class="text-danger">*</span></label>
                            <input required type="text"
                                class="form-control input-rupiah biaya-input @error('honor_output') is-invalid @enderror"
                                name="honor_output" maxlength="10" placeholder="2.000.000"
                                value="{{ old('honor_output') }}">
                            <div class="invalid-feedback">Honor output wajib diisi.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Biaya Inovasi <span class="text-danger">*</span></label>
                            <input required type="text"
                                class="form-control input-rupiah biaya-input @error('belanja_non_operasional') is-invalid @enderror"
                                name="belanja_non_operasional" maxlength="10" placeholder="2.000.000"
                                value="{{ old('belanja_non_operasional') }}">
                            <div class="invalid-feedback">Biaya Inovasi wajib diisi.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Perjalanan atau Transportasi <span class="text-danger">*</span></label>
                            <input required type="text"
                                class="form-control input-rupiah biaya-input @error('transportasi') is-invalid @enderror"
                                name="transportasi" maxlength="10" placeholder="2.000.000"
                                value="{{ old('transportasi') }}">
                            <div class="invalid-feedback">Perjalanan atau Transportasi wajib diisi.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Luaran<span class="text-danger">*</span></label>
                            <input required type="text"
                                class="form-control input-rupiah  biaya-input @error('lain_lain') is-invalid @enderror"
                                name="lain_lain" maxlength="10" placeholder="2.000.000"
                                value="{{ old('lain_lain') }}">
                            <div class="invalid-feedback">Luaran wajib diisi.</div>
                        </div>
                        <hr>

                        {{-- TOTAL CALCULATOR (READONLY) --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-success fs-5">Total Anggaran (Rp)</label>
                            <input type="text" id="total_biaya_display" class="form-control bg-light fw-bold text-end fs-4 text-success" 
                                readonly value="0" style="letter-spacing: 1px;">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 7. PENGESAHAN --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">6. Pengesahan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label">Kota <span class="text-danger">*</span></label>
                            <small id="pengesahan_kota-counter" class="form-text text-muted">0/20</small>
                        </div>
                        {{-- SESUAI CONTROLLER: max 20 --}}
                        <input required type="text" class="form-control @error('kota') is-invalid @enderror"
                            name="kota" id="pengesahan_kota" placeholder="Jakarta" maxlength="20"
                            value="{{ old('kota') }}">
                        <div class="invalid-feedback">Kota wajib diisi.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Pihak yang Mengetahui <span class="text-danger">*</span></h6>
                            <label class="form-label">NIP <span class="text-danger"></span></label>
                            {{-- SESUAI CONTROLLER: max 20 --}}
                            <input required type="text"
                                class="form-control mb-2 only-number @error('nip_mengetahui') is-invalid @enderror"
                                name="nip_mengetahui" inputmode="numeric" placeholder="NIP" maxlength="20"
                                value="{{ old('nip_mengetahui') }}">
                            <div class="invalid-feedback">NIP wajib diisi.</div>

                            <label class="form-label">Nama Lengkap <span class="text-danger"></span></label>
                            {{-- SESUAI CONTROLLER: max 30 (PERBAIKAN UTAMA) --}}
                            <input required type="text"
                                class="form-control mb-2 @error('nama_mengetahui') is-invalid @enderror"
                                name="nama_mengetahui" placeholder="Nama Lengkap" maxlength="30"
                                value="{{ old('nama_mengetahui') }}">
                            <div class="invalid-feedback">Nama wajib diisi.</div>

                            <label class="form-label">Jabatan <span class="text-danger"></span></label>
                            {{-- SESUAI CONTROLLER: max 30 (PERBAIKAN UTAMA) --}}
                            <input required type="text"
                                class="form-control mb-2 @error('jabatan_mengetahui') is-invalid @enderror"
                                name="jabatan_mengetahui" placeholder="Jabatan" maxlength="30"
                                value="{{ old('jabatan_mengetahui') }}">
                            <div class="invalid-feedback">Jabatan wajib diisi.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Pihak yang Menyetujui <span class="text-danger">*</span></h6>
                            <label class="form-label">NIP <span class="text-danger"></span></label>
                            {{-- SESUAI CONTROLLER: max 20 --}}
                            <input required type="text"
                                class="form-control mb-2 only-number @error('nip_menyetujui') is-invalid @enderror"
                                name="nip_menyetujui" inputmode="numeric" placeholder="NIP" maxlength="20"
                                value="{{ old('nip_menyetujui') }}">
                            <div class="invalid-feedback">NIP wajib diisi.</div>

                            <label class="form-label">Nama Lengkap <span class="text-danger"></span></label>
                            {{-- SESUAI CONTROLLER: max 30 (PERBAIKAN UTAMA) --}}
                            <input required type="text"
                                class="form-control mb-2 @error('nama_menyetujui') is-invalid @enderror"
                                name="nama_menyetujui" placeholder="Nama Lengkap" maxlength="50"
                                value="{{ old('nama_menyetujui') }}">
                            <div class="invalid-feedback">Nama wajib diisi.</div>

                            <label class="form-label">Jabatan <span class="text-danger"></span></label>
                            {{-- SESUAI CONTROLLER: max 30 (PERBAIKAN UTAMA) --}}
                            <input required type="text"
                                class="form-control mb-2 @error('jabatan_menyetujui') is-invalid @enderror"
                                name="jabatan_menyetujui" placeholder="Jabatan" maxlength="30"
                                value="{{ old('jabatan_menyetujui') }}">
                            <div class="invalid-feedback">Jabatan wajib diisi.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mb-5">
                <a href="{{ route('dosen.pengajuan.skema') }}"
                    class="btn btn-secondary btn-action-control d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                    </svg>
                    <span>Kembali</span>
                </a>
                <button type="submit" class="btn btn-primary btn-action-control d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-send-fill" viewBox="0 0 16 16">
                        <path
                            d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z" />
                    </svg>
                    Ajukan Proposal Pengabdian
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // 0. HELPER: FUNGSI ALERT MELAYANG
        function showFloatingAlert(message, type = 'danger') {
            const container = document.getElementById('floating-alert-container');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} shadow-lg alert-dismissible fade show`;
            alertDiv.style.marginBottom = '10px';
            alertDiv.role = 'alert';
            alertDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                    <div>${message}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            container.appendChild(alertDiv);
            setTimeout(() => {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 500);
            }, 4000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // STATUS TTD AWAL (Dari PHP)
            let hasSignature = @json($userHasSignature);

            // DATA USER LOGIN (Untuk validasi diri sendiri)
            const currentUserNidn = "{{ Auth::user()->nidn }}";

            // 1. AUTO SCROLL KE ERROR
            const firstErrorBackend = document.querySelector('.is-invalid');
            if (firstErrorBackend) {
                firstErrorBackend.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                firstErrorBackend.focus();
            }

            // HELPER: CEK DUPLIKASI DI TABEL
            function isIdAlreadyInTable(id, tableBodyId, inputNameKey) {
                let exists = false;
                const tableBody = document.getElementById(tableBodyId);
                const inputs = tableBody.querySelectorAll(`input[name*="[${inputNameKey}]"]`);
                inputs.forEach(input => {
                    if (input.value.trim() === id.trim()) {
                        exists = true;
                    }
                });
                return exists;
            }

            // 2. LOGIKA CEK LDAP
            async function fetchLdapData(id, targetInputId, btnElement) {
                if (!id) return showFloatingAlert("Masukkan ID terlebih dahulu!", "warning");
                const originalText = btnElement.innerText;
                btnElement.innerText = "Checking...";
                btnElement.disabled = true;

                try {
                    const response = await fetch(`{{ route('api.ldap_check') }}?id=${id}`);
                    const data = await response.json();
                    if (data.success) {
                        document.getElementById(targetInputId).value = data.nama;
                        showFloatingAlert("Data ditemukan: " + data.nama, "success");
                    } else {
                        showFloatingAlert(data.message || "Data tidak ditemukan di LDAP", "danger");
                        document.getElementById(targetInputId).value = "";
                    }
                } catch (error) {
                    console.error("LDAP Error:", error);
                    showFloatingAlert("Gagal terhubung ke server LDAP", "danger");
                } finally {
                    btnElement.innerText = originalText;
                    btnElement.disabled = false;
                }
            }

            // EVENT LISTENER: CEK NIDN (DOSEN)
            const btnCheckNIDN = document.getElementById('btnCheckNIDN');
            if (btnCheckNIDN) {
                btnCheckNIDN.addEventListener('click', function() {
                    const id = document.getElementById('nidn_input').value.trim();
                    if (id === currentUserNidn) {
                        showFloatingAlert("Anda tidak dapat menambahkan diri sendiri sebagai anggota.", "warning");
                        document.getElementById('nama_dosen_form').value = "";
                        return;
                    }
                    if (isIdAlreadyInTable(id, 'tabelAnggotaDosen', 'nidn')) {
                        showFloatingAlert("Anggota dengan NIP ini sudah ditambahkan.", "warning");
                        document.getElementById('nama_dosen_form').value = "";
                        return;
                    }
                    fetchLdapData(id, 'nama_dosen_form', this);
                });
            }

            // EVENT LISTENER: CEK NPM (MAHASISWA)
            const btnCheckNPM = document.getElementById('btnCheckNPM');
            if (btnCheckNPM) {
                btnCheckNPM.addEventListener('click', function() {
                    const id = document.getElementById('npm_input').value.trim();
                    if (isIdAlreadyInTable(id, 'tabelAnggotaMhs', 'npm')) {
                        showFloatingAlert("Mahasiswa dengan NPM ini sudah ditambahkan.", "warning");
                        document.getElementById('nama_mhs_form').value = "";
                        return;
                    }
                    fetchLdapData(id, 'nama_mhs_form', this);
                });
            }

            // -------------------------------------------------------------------------
            // 3. LOGIKA CASCADING DROPDOWN (FAKULTAS -> PRODI) - [REVISI BARU]
            // -------------------------------------------------------------------------
            const fakultasSelect = document.getElementById('fakultas_dosen_form');
            const prodiSelect = document.getElementById('prodi_dosen_form');

            if (fakultasSelect && prodiSelect) {
                fakultasSelect.addEventListener('change', function() {
                    // Ambil data JSON dari atribut data-prodis
                    const selectedOption = this.options[this.selectedIndex];
                    const rawData = selectedOption.getAttribute('data-prodis');
                    let prodis = [];
                    
                    try {
                        prodis = JSON.parse(rawData);
                    } catch (e) {
                        console.error("Gagal Mendapatkan data prodi", e);
                    }
                    
                    // Reset Prodi Dropdown
                    prodiSelect.innerHTML = '<option value="" selected disabled>-- Pilih Program Studi --</option>';
                    
                    // Isi Prodi Dropdown
                    if (prodis && prodis.length > 0) {
                        prodiSelect.disabled = false; // Aktifkan dropdown
                        prodis.forEach(prodi => {
                            const option = document.createElement('option');
                            option.value = prodi.nama; // Pastikan 'nama' sesuai kolom DB
                            option.textContent = prodi.nama;
                            prodiSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.textContent = "Tidak ada prodi di fakultas ini";
                        prodiSelect.appendChild(option);
                        prodiSelect.disabled = true;
                    }
                });
            }

            // -------------------------------------------------------------------------
            // 4. TOMBOL TAMBAH DOSEN - [REVISI BARU]
            // -------------------------------------------------------------------------
            const btnTambahDosen = document.getElementById('btnTambahDosen');
            if (btnTambahDosen) {
                btnTambahDosen.addEventListener('click', function() {
                    const nidn = document.getElementById('nidn_input').value.trim();
                    const nama = document.getElementById('nama_dosen_form').value;
                    const fakultas = document.getElementById('fakultas_dosen_form').value; // Ambil Fakultas
                    const prodi = document.getElementById('prodi_dosen_form').value;
                    const peran = document.getElementById('peran_dosen_form').value;
                    const tableBody = document.getElementById('tabelAnggotaDosen');
                    const index = tableBody.rows.length;

                    // Validasi
                    if (!nidn || !nama || nama === "Mencari data..." || nama === "") {
                        return showFloatingAlert("Cek NIP via LDAP dulu sebelum menambahkan!", "warning");
                    }
                    if (nidn === currentUserNidn) {
                        return showFloatingAlert("Anda tidak dapat menambahkan diri sendiri sebagai anggota.", "warning");
                    }
                    if (!fakultas) {
                        return showFloatingAlert("Silakan pilih Fakultas terlebih dahulu!", "warning");
                    }
                    if (!prodi && !document.getElementById('prodi_dosen_form').disabled) {
                        return showFloatingAlert("Silakan pilih Program Studi terlebih dahulu!", "warning");
                    }
                    if (isIdAlreadyInTable(nidn, 'tabelAnggotaDosen', 'nidn')) {
                        return showFloatingAlert("Anggota dengan NIDN ini sudah ditambahkan.", "warning");
                    }

                    // Buat Baris Tabel Baru
                    const row = `
                        <tr>
                            <td class="text-center row-number">${index + 1}</td>
                            <td>
                                <div class="fw-bold">${nama}</div>
                                <div class="text-muted small">${nidn}</div>
                                <input type="hidden" name="anggota_dosen[${index}][nidn]" value="${nidn}">
                                <input type="hidden" name="anggota_dosen[${index}][nama]" value="${nama}">
                                <input type="hidden" name="anggota_dosen[${index}][fakultas_dosen]" value="${fakultas}">
                                <input type="hidden" name="anggota_dosen[${index}][prodi_dosen]" value="${prodi}">
                                <input type="hidden" name="anggota_dosen[${index}][peran]" value="${peran}">
                            </td>
                            <td>${fakultas}</td>
                            <td>${prodi}</td>
                            <td><div class="role-text">${peran}</div></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
                            </td>
                        </tr>
                    `;

                    tableBody.insertAdjacentHTML('beforeend', row);
                    updateAllRoles();
                    
                    // Reset Form
                    document.getElementById('nidn_input').value = "";
                    document.getElementById('nama_dosen_form').value = "";
                    document.getElementById('fakultas_dosen_form').value = "";
                    
                    // Reset Prodi
                    prodiSelect.innerHTML = '<option value="" selected disabled>-- Pilih Fakultas Terlebih Dahulu --</option>';
                    prodiSelect.disabled = true;

                    // Hapus Error UI
                    document.getElementById('card-anggota-dosen').classList.remove('border', 'border-danger');
                    document.getElementById('error-anggota-dosen').classList.add('d-none');
                });
            }

            // 5. TAMBAH MAHASISWA (EXISTING)
            const btnTambahMhs = document.getElementById('btnTambahMhs');
            if (btnTambahMhs) {
                btnTambahMhs.addEventListener('click', function() {
                    const npm = document.getElementById('npm_input').value.trim();
                    const nama = document.getElementById('nama_mhs_form').value;
                    const prodi = document.getElementById('prodi_mhs_form').value;
                    const peran = document.getElementById('peran_mhs_form').value;
                    const tableBody = document.getElementById('tabelAnggotaMhs');
                    const index = tableBody.rows.length;

                    if (!npm || !nama || nama === "Mencari data...") {
                        return showFloatingAlert("Cek NPM via LDAP dulu sebelum menambahkan!", "warning");
                    }
                    if (isIdAlreadyInTable(npm, 'tabelAnggotaMhs', 'npm')) {
                        return showFloatingAlert("Mahasiswa dengan NPM ini sudah ditambahkan.", "warning");
                    }

                    const row = `<tr>
                        <td class="text-center row-number">${index + 1}</td>
                        <td><strong>${nama}</strong><br><small>${npm}</small>
                            <input type="hidden" name="anggota_mhs[${index}][npm]" value="${npm}">
                            <input type="hidden" name="anggota_mhs[${index}][nama]" value="${nama}">
                            <input type="hidden" name="anggota_mhs[${index}][prodi]" value="${prodi}">
                            <input type="hidden" name="anggota_mhs[${index}][peran]" value="${peran}">
                        </td>
                        <td>${prodi}</td>
                        <td><div class="role-text">${peran}</div></td>
                        <td class="text-center"><button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button></td>
                    </tr>`;

                    tableBody.insertAdjacentHTML('beforeend', row);
                    updateAllRoles();
                    document.getElementById('npm_input').value = "";
                    document.getElementById('nama_mhs_form').value = "";
                    document.getElementById('card-anggota-mhs').classList.remove('border', 'border-danger');
                    document.getElementById('error-anggota-mhs').classList.add('d-none');
                });
            }

            // HAPUS ROW
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    const btn = e.target.closest('.remove-row');
                    const row = btn.closest('tr');
                    const tableBody = row.closest('tbody');
                    const isDosen = tableBody.id === 'tabelAnggotaDosen';
                    const rolePrefix = isDosen ? 'Anggota Pengusul' : 'Anggota Mahasiswa';

                    row.remove();
                    tableBody.querySelectorAll('tr').forEach((tr, i) => {
                        const newNum = i + 1;
                        const newRole = `${rolePrefix} ${newNum}`;
                        tr.querySelector('.row-number').innerText = newNum;
                        if (tr.querySelector('.role-text')) tr.querySelector('.role-text').innerText = newRole;
                        tr.querySelectorAll('input[type="hidden"]').forEach(input => {
                            let name = input.getAttribute('name');
                            input.setAttribute('name', name.replace(/\[\d+\]/, `[${i}]`));
                            if (name.includes('[peran]')) input.value = newRole;
                        });
                    });
                    updateAllRoles();
                }
            });

            // UPDATE ROLE
            function updateAllRoles() {
                const dCount = document.querySelectorAll('#tabelAnggotaDosen tr').length + 1;
                const dPeranInput = document.getElementById('peran_dosen_form');
                if (dPeranInput) dPeranInput.value = `Anggota Pengusul ${dCount}`;
                const mCount = document.querySelectorAll('#tabelAnggotaMhs tr').length + 1;
                const mPeranInput = document.getElementById('peran_mhs_form');
                if (mPeranInput) mPeranInput.value = `Anggota Mahasiswa ${mCount}`;
            }

            // FORMAT RUPIAH
            document.querySelectorAll('.input-rupiah').forEach(input => {
                input.addEventListener('input', function() {
                    let value = this.value.replace(/[^0-9]/g, '');
                    this.value = value ? value.replace(/\B(?=(\d{3})+(?!\d))/g, ".") : "";
                });
            });

            // ONLY NUMBER
            const numericInputs = document.querySelectorAll('.only-number, #nidn_input, #npm_input');
            numericInputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            });

            // PENGHITUNG KARAKTER
            function setupCharacterCounter(inputId, counterId, maxLength) {
                const inputElement = document.getElementById(inputId);
                const counterElement = document.getElementById(counterId);
                if (inputElement && counterElement) {
                    const initialLength = inputElement.value.length;
                    counterElement.textContent = `${initialLength}/${maxLength}`;
                    if (initialLength >= maxLength) counterElement.classList.add('text-danger');
                    inputElement.addEventListener('input', function() {
                        const currentLength = inputElement.value.length;
                        counterElement.textContent = `${currentLength}/${maxLength}`;
                        if (currentLength >= maxLength) {
                            counterElement.classList.add('text-danger');
                        } else {
                            counterElement.classList.remove('text-danger');
                        }
                    });
                }
            }

            setupCharacterCounter('judul', 'judul-counter', 300);
            setupCharacterCounter('abstrak', 'abstrak-counter', 500);
            setupCharacterCounter('keyword', 'keyword-counter', 150);
            setupCharacterCounter('rumpun_ilmu', 'rumpun_ilmu-counter', 50);
            setupCharacterCounter('mitra_nama', 'mitra_nama-counter', 50);
            setupCharacterCounter('mitra_alamat', 'mitra_alamat-counter', 250);
            setupCharacterCounter('mitra_penanggung_jawab', 'mitra_penanggung_jawab-counter', 50);
            setupCharacterCounter('objek_pengabdian', 'objek_pengabdian-counter', 50);
            setupCharacterCounter('lokasi_pengabdian', 'lokasi_pengabdian-counter', 100);
            setupCharacterCounter('instasi_lain', 'instasi_lain-counter', 50);
            setupCharacterCounter('temuan_target', 'temuan_target-counter', 50);
            setupCharacterCounter('pengesahan_kota', 'pengesahan_kota-counter', 20);

            // 9. VALIDASI SUBMIT FORM UTAMA
            const formPengajuan = document.getElementById('formPengajuan');
            if (formPengajuan) {
                formPengajuan.addEventListener('submit', function(e) {
                    let isValid = true;
                    let firstErrorElement = null;

                    // --- CEK TANDA TANGAN ---
                    if (!hasSignature) {
                        e.preventDefault();
                        e.stopPropagation();
                        var myModal = new bootstrap.Modal(document.getElementById('modalUploadSignature'));
                        myModal.show();
                        return false;
                    }

                    // A. Required Inputs
                    const requiredInputs = formPengajuan.querySelectorAll('[required]');
                    requiredInputs.forEach(input => {
                        if (!input.value.trim()) {
                            input.classList.add('is-invalid');
                            isValid = false;
                            if (!firstErrorElement) firstErrorElement = input;
                        } else {
                            input.classList.remove('is-invalid');
                        }
                    });

                    // B. Anggota Dosen
                    const dosenCount = document.querySelectorAll('#tabelAnggotaDosen tr').length;
                    const cardDosen = document.getElementById('card-anggota-dosen');
                    const errorMsgDosen = document.getElementById('error-anggota-dosen');
                    if (dosenCount === 0) {
                        isValid = false;
                        cardDosen.classList.add('border', 'border-danger');
                        errorMsgDosen.classList.remove('d-none');
                        if (!firstErrorElement) firstErrorElement = cardDosen;
                    } else {
                        cardDosen.classList.remove('border', 'border-danger');
                        errorMsgDosen.classList.add('d-none');
                    }

                    // C. Anggota Mahasiswa
                    const mhsCount = document.querySelectorAll('#tabelAnggotaMhs tr').length;
                    const cardMhs = document.getElementById('card-anggota-mhs');
                    const errorMsgMhs = document.getElementById('error-anggota-mhs');
                    if (mhsCount === 0) {
                        isValid = false;
                        cardMhs.classList.add('border', 'border-danger');
                        errorMsgMhs.classList.remove('d-none');
                        if (!firstErrorElement) firstErrorElement = cardMhs;
                    } else {
                        cardMhs.classList.remove('border', 'border-danger');
                        errorMsgMhs.classList.add('d-none');
                    }

                    // D. Final Check
                    if (!isValid) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (firstErrorElement) {
                            firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            if (firstErrorElement.tagName === 'INPUT' || firstErrorElement.tagName === 'TEXTAREA') {
                                firstErrorElement.focus();
                            }
                        }
                        showFloatingAlert("Mohon lengkapi semua kolom wajib dan anggota!", "danger");
                    }
                });

                formPengajuan.addEventListener('input', function(e) {
                    if (e.target.hasAttribute('required') && e.target.value.trim() !== '') {
                        e.target.classList.remove('is-invalid');
                    }
                });
            }

            // --- LOGIKA AJAX UPLOAD TTD ---
            const formAjaxSignature = document.getElementById('formAjaxSignature');
            if (formAjaxSignature) {
                formAjaxSignature.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const fileInput = document.getElementById('ajax_tanda_tangan');
                    const errorMsg = document.getElementById('ajax-error-msg');
                    const btn = document.getElementById('btnSaveAjaxSignature');
                    const btnText = document.getElementById('btn-text-upload');
                    const btnLoader = document.getElementById('btn-loader-upload');

                    if (fileInput.files.length === 0) return;

                    btn.disabled = true;
                    btnText.classList.add('d-none');
                    btnLoader.classList.remove('d-none');
                    errorMsg.classList.add('d-none');

                    const formData = new FormData(this);

                    try {
                        const response = await fetch("{{ route('profile.upload_signature_ajax') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            },
                            body: formData
                        });
                        const result = await response.json();
                        if (response.ok && result.success) {
                            hasSignature = true;
                            showFloatingAlert("Tanda tangan berhasil disimpan! Silakan klik 'Ajukan' kembali.", "success");
                            const modalEl = document.getElementById('modalUploadSignature');
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            modal.hide();
                        } else {
                            throw new Error(result.message || "Gagal mengunggah gambar.");
                        }
                    } catch (error) {
                        console.error(error);
                        errorMsg.textContent = error.message;
                        errorMsg.classList.remove('d-none');
                    } finally {
                        btn.disabled = false;
                        btnText.classList.remove('d-none');
                        btnLoader.classList.add('d-none');
                    }
                });
            }
            function calculateTotalBiaya() {
                let total = 0;
                // Hanya ambil input yang punya class 'biaya-input'
                const inputs = document.querySelectorAll('.biaya-input');
                
                inputs.forEach(input => {
                    // Hapus titik, ubah ke integer
                    let rawValue = input.value.replace(/\./g, '');
                    let intValue = parseInt(rawValue) || 0;
                    total += intValue;
                });

                // Format kembali ke Rupiah untuk ditampilkan
                const formattedTotal = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                
                // Update field total
                const display = document.getElementById('total_biaya_display');
                if(display){
                    display.value = formattedTotal;
                }
            }

            // Pasang Event Listener ke semua input biaya
            const biayaInputs = document.querySelectorAll('.biaya-input');
            biayaInputs.forEach(input => {
                input.addEventListener('input', function() {
                    // Panggil fungsi hitung setiap kali mengetik
                    calculateTotalBiaya();
                });
            });

            updateAllRoles();
            calculateTotalBiaya();
        });
    </script>
@endpush

@push('styles')
    <style>
        .btn-action-control {
            font-weight: 600;
            min-width: 120px;
            transition: all 0.2s ease-in-out;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: #8BC3B4;
            border-color: #8BC3B4;
            color: white;
        }

        .btn-primary:hover {
            background-color: #6cb8a5;
            border-color: #6cb8a5;
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 4px 8px rgba(139, 195, 180, 0.4);
        }

        .form-control[disabled] {
            background-color: #e9ecef;
        }

        .form-label {
            font-weight: 500;
        }

        .btn-success {
            background-color: #198754;
            border-color: #198754;
            color: white;
        }

        .btn-success:hover {
            background-color: #157347;
            border-color: #146c43;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #bb2d3b;
            border-color: #b02a37;
        }

        .form-label.mb-1 {
            margin-bottom: 0.25rem !important;
        }
    </style>
@endpush
