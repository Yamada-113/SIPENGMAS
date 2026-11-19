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
                        
                        {{-- SKALA PELAKSANAAN (READONLY SESUAI PILIHAN SEBELUMNYA + HIDDEN INPUT) --}}
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Skala Pelaksanaan</label>
                            <input type="text" class="form-control bg-light" value="{{ $selectedRole }}" readonly>
                            <input type="hidden" name="skala_pelaksanaan" value="{{ $selectedRole }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Skema yang Dipilih</label>
                            <input type="text" class="form-control bg-light" value="{{ $namaSkema }}" disabled>
                            <input type="hidden" name="skema" value="{{ $skemaId }}">
                        </div>

                        {{-- TAMBAHAN: PILIHAN JENIS KEGIATAN (AMAN DARI ERROR) --}}
                        <div class="col-md-12">
                            <label for="jenis_kegiatan_id" class="form-label fw-bold">
                                Jenis Kegiatan <span class="text-danger">*</span>
                            </label>
                            <select required class="form-select @error('jenis_kegiatan_id') is-invalid @enderror" 
                                name="jenis_kegiatan_id" id="jenis_kegiatan_id">
                                <option value="" selected disabled>-- Pilih Jenis Kegiatan --</option>
                                
                                @if(isset($jenisKegiatan) && count($jenisKegiatan) > 0)
                                    @foreach ($jenisKegiatan as $jenis)
                                        <option value="{{ $jenis->id }}" {{ old('jenis_kegiatan_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama }}
                                        </option>
                                    @endforeach
                                @else
                                    {{-- Opsi cadangan sementara agar tidak error saat backend belum siap --}}
                                    <option value="1">Penelitian Dosen Pemula (Dummy)</option>
                                    <option value="2">Pengabdian Kepada Masyarakat (Dummy)</option>
                                @endif
                            </select>
                            <div class="invalid-feedback">Jenis kegiatan wajib dipilih.</div>
                            @error('jenis_kegiatan_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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
                                <option value="1" {{ old('periode_kegiatan') == 1 ? 'selected' : '' }}>1 Tahun</option>
                                <option value="2" {{ old('periode_kegiatan') == 2 ? 'selected' : '' }}>2 Tahun</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bidang Fokus</label>
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
                            <label for="rumpun_ilmu" class="form-label mb-1">Rumpun Ilmu <span class="text-danger">*</span></label>
                            <small id="rumpun_ilmu-counter" class="form-text text-muted">0/50</small>
                        </div>
                        <input required type="text" class="form-control @error('rumpun_ilmu') is-invalid @enderror"
                            id="rumpun_ilmu" name="rumpun_ilmu" value="{{ old('rumpun_ilmu') }}" maxlength="50">
                        <div class="invalid-feedback">Rumpun ilmu wajib diisi.</div>
                    </div>
                    <hr>
                    <h6 class="fw-bold">MITRA</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="mitra_nama" class="form-label mb-1">Nama Institusi <span class="text-danger">*</span></label>
                                <small id="mitra_nama-counter" class="form-text text-muted">0/50</small>
                            </div>
                            <input required type="text"
                                class="form-control @error('nama_institusi_mitra') is-invalid @enderror"
                                name="nama_institusi_mitra" id="mitra_nama" value="{{ old('nama_institusi_mitra') }}"
                                maxlength="50">
                            <div class="invalid-feedback">Nama mitra wajib diisi.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="mitra_penanggung_jawab" class="form-label mb-1">Penanggung Jawab</label>
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
                            <label for="mitra_alamat" class="form-label mb-1">Alamat <span class="text-danger">*</span></label>
                            <small id="mitra_alamat-counter" class="form-text text-muted">0/250</small>
                        </div>
                        <textarea required class="form-control @error('alamat_mitra') is-invalid @enderror" name="alamat_mitra"
                            id="mitra_alamat" rows="3" maxlength="250">{{ old('alamat_mitra') }}</textarea>
                        <div class="invalid-feedback">Alamat mitra wajib diisi.</div>
                    </div>
                </div>
            </div>

            {{-- 4. ANGGOTA DOSEN --}}
            <div class="card shadow-sm mb-4" id="card-anggota-dosen">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">3. Penambahan Anggota Pengabdian <span class="text-danger">*</span></h6>
                </div>
                <div class="card-body">
                    <div id="error-anggota-dosen" class="alert alert-danger d-none p-2 mb-3">
                        <small><i class="bi bi-exclamation-circle me-1"></i> Wajib menambahkan minimal 1 Anggota Dosen.</small>
                    </div>
                    @error('anggota_dosen')
                        <div class="alert alert-danger p-2 mb-3"><small>{{ $message }}</small></div>
                    @enderror

                    <div class="mb-3">
                        <label class="form-label">Nomor Induk Pegawai (NIP)</label>
                        <div class="input-group">
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
                                            <input type="hidden" name="anggota_dosen[{{ $index }}][fakultas_dosen]" value="{{ $dosen['fakultas_dosen'] }}">
                                            <input type="hidden" name="anggota_dosen[{{ $index }}][prodi_dosen]" value="{{ $dosen['prodi_dosen'] }}">
                                            <input type="hidden" name="anggota_dosen[{{ $index }}][peran]" value="{{ $dosen['peran'] }}">
                                        </td>
                                        <td>{{ $dosen['fakultas_dosen'] }}</td>
                                        <td>{{ $dosen['prodi_dosen'] }}</td>
                                        <td><div class="role-text">{{ $dosen['peran'] }}</div></td>
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
                        <small><i class="bi bi-exclamation-circle me-1"></i> Wajib menambahkan minimal 1 Anggota Mahasiswa.</small>
                    </div>
                    @error('anggota_mhs')
                        <div class="alert alert-danger p-2 mb-3"><small>{{ $message }}</small></div>
                    @enderror

                    <div class="mb-3">
                        <label class="form-label mb-1">Nomor Pokok Mahasiswa (NPM)</label>
                        <div class="input-group">
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
                    <button type="button" class="btn btn-success float-end mb-3" id="btnTambahMhs">Tambahkan Mahasiswa</button>
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
                                            <input type="hidden" name="anggota_mhs[{{ $index }}][npm]" value="{{ $mhs['npm'] }}">
                                            <input type="hidden" name="anggota_mhs[{{ $index }}][nama]" value="{{ $mhs['nama'] }}">
                                            <input type="hidden" name="anggota_mhs[{{ $index }}][prodi]" value="{{ $mhs['prodi'] }}">
                                            <input type="hidden" name="anggota_mhs[{{ $index }}][peran]" value="{{ $mhs['peran'] }}">
                                        </td>
                                        <td>{{ $mhs['prodi'] }}</td>
                                        <td><div class="role-text">{{ $mhs['peran'] }}</div></td>
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

            <div class="d-grid gap-2 mb-5">
                <button type="submit" class="btn btn-primary btn-lg fw-bold">Ajukan Proposal Sekarang</button>
            </div>
        </form>
    </div>
@endsection