@extends('layouts.app')

@section('title', 'Form Pengajuan Proposal')

@section('content')
    @php
        $userHasSignature = !empty(Auth::user()->tanda_tangan);
    @endphp

    <div id="floating-alert-container"
        style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;">
    </div>

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

    <div class="container">
        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof showFloatingAlert === 'function') {
                        showFloatingAlert(@json(session('error')), 'danger');
                    }
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof showFloatingAlert === 'function') {
                        showFloatingAlert("Terdapat kolom yang belum diisi atau salah. Mohon periksa kembali.", 'danger');
                    }
                });
            </script>
        @endif

        <h1 class="mb-4 ms-4" style="font-weight: 600">Form Pengajuan Proposal</h1>

        <form action="{{ route('dosen.tesproposal.store') }}" method="POST" enctype="multipart/form-data"
            id="formPengajuan" novalidate>
            @csrf

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
                            <input type="text" class="form-control bg-light" value="{{ $selectedRole }}" readonly>
                            <input type="hidden" name="skala_pelaksanaan" value="{{ $selectedRole }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Skema yang Dipilih</label>
                            <input type="text" class="form-control bg-light" value="{{ $namaSkema }}" disabled>
                            <input type="hidden" name="skema" value="{{ $skemaId }}">
                        </div>

                        <div class="col-md-12">
                            <label for="jenis_kegiatan_id" class="form-label fw-bold">
                                Jenis Kegiatan <span class="text-danger">*</span>
                            </label>
                            <select required class="form-select @error('jenis_kegiatan_id') is-invalid @enderror"
                                name="jenis_kegiatan_id" id="jenis_kegiatan_id">
                                <option value="" selected disabled>-- Pilih Jenis Kegiatan --</option>
                                @if (isset($jenisKegiatan) && count($jenisKegiatan) > 0)
                                    @foreach ($jenisKegiatan as $jenis)
                                        <option value="{{ $jenis->id }}" {{ old('jenis_kegiatan_id') == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>Tidak ada jenis kegiatan aktif</option>
                                @endif
                            </select>
                            <div class="invalid-feedback">
                                @error('jenis_kegiatan_id') {{ $message }} @else Jenis kegiatan wajib dipilih. @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">Upload Proposal</h6>
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
                        <div class="invalid-feedback">
                            @error('file_proposal') {{ $message }} @else File proposal wajib diunggah. @enderror
                        </div>
                    </div>
                </div>
            </div>

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
                        <div class="invalid-feedback">
                            @error('judul') {{ $message }} @else Judul wajib diisi. @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="abstrak" class="form-label">Abstrak <span class="text-danger">*</span></label>
                            <small id="abstrak-counter" class="form-text text-muted">0/500</small>
                        </div>
                        <textarea required class="form-control @error('abstrak') is-invalid @enderror" name="abstrak" id="abstrak"
                            rows="4" maxlength="500">{{ old('abstrak') }}</textarea>
                        <div class="invalid-feedback">
                            @error('abstrak') {{ $message }} @else Abstrak wajib diisi. @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="keyword" class="form-label">Keyword <span class="text-danger">*</span></label>
                            <small id="keyword-counter" class="form-text text-muted">0/150</small>
                        </div>
                        <textarea required class="form-control @error('keyword') is-invalid @enderror" name="keyword" id="keyword"
                            rows="2" maxlength="150">{{ old('keyword') }}</textarea>
                        <div class="invalid-feedback">
                            @error('keyword') {{ $message }} @else Keyword wajib diisi. @enderror
                        </div>
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
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Mahasiswa</label>
                            <input type="text"
                                class="form-control input-rupiah @error('jumlah_mahasiswa') is-invalid @enderror"
                                name="jumlah_mahasiswa" maxlength="3" placeholder="0" value="{{ old('jumlah_mahasiswa') }}">
                        </div>
                    </div>
                </div>
            </div>

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
                        <div class="invalid-feedback">
                            @error('rumpun_ilmu') {{ $message }} @else Rumpun ilmu wajib diisi. @enderror
                        </div>
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
                            <div class="invalid-feedback">
                                @error('nama_institusi_mitra') {{ $message }} @else Nama mitra wajib diisi. @enderror
                            </div>
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
                            <div class="invalid-feedback">
                                @error('penanggung_jawab_mitra') {{ $message }} @else Penanggung jawab mitra wajib diisi. @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="mitra_alamat" class="form-label mb-1">Alamat <span class="text-danger">*</span></label>
                            <small id="mitra_alamat-counter" class="form-text text-muted">0/250</small>
                        </div>
                        <textarea required class="form-control @error('alamat_mitra') is-invalid @enderror" name="alamat_mitra"
                            id="mitra_alamat" rows="3" maxlength="250">{{ old('alamat_mitra') }}</textarea>
                        <div class="invalid-feedback">
                            @error('alamat_mitra') {{ $message }} @else Alamat mitra wajib diisi. @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4" id="card-anggota-dosen">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">3. Penambahan Anggota Pengabdian <span class="text-danger">*</span></h6>
                </div>
                <div class="card-body">
                    <div id="error-anggota-dosen" class="alert alert-danger {{ $errors->has('anggota_dosen') ? '' : 'd-none' }} p-2 mb-3">
                        <small><i class="bi bi-exclamation-circle me-1"></i> Wajib menambahkan minimal 1 Anggota Dosen.</small>
                    </div>

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

            <div class="card shadow-sm mb-4" id="card-anggota-mhs">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">4. Penambahan Anggota Mahasiswa <span class="text-danger">*</span></h6>
                </div>
                <div class="card-body">
                    <div id="error-anggota-mhs" class="alert alert-danger {{ $errors->has('anggota_mhs') ? '' : 'd-none' }} p-2 mb-3">
                        <small><i class="bi bi-exclamation-circle me-1"></i> Wajib menambahkan minimal 1 Anggota Mahasiswa.</small>
                    </div>

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

            <div class="card shadow-sm mb-4" id="card-rab">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">5. Rencana Anggaran Biaya</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="honor" class="form-label">Honor <span class="text-danger">*</span></label>
                            <input type="text" required
                                class="form-control input-rupiah rab-item @error('honor') is-invalid @enderror"
                                name="honor" id="honor" placeholder="2.000.000" value="{{ old('honor') }}">
                            <div class="invalid-feedback">
                                @error('honor') {{ $message }} @else Honor wajib diisi. @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="biaya_inovasi" class="form-label">Biaya Inovasi <span class="text-danger">*</span></label>
                            <input type="text" required
                                class="form-control input-rupiah rab-item @error('biaya_inovasi') is-invalid @enderror"
                                name="biaya_inovasi" id="biaya_inovasi" placeholder="2.000.000" value="{{ old('biaya_inovasi') }}">
                            <div class="invalid-feedback">
                                @error('biaya_inovasi') {{ $message }} @else Biaya inovasi wajib diisi. @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="perjalanan_transportasi" class="form-label">Perjalanan atau Transportasi <span class="text-danger">*</span></label>
                            <input type="text" required
                                class="form-control input-rupiah rab-item @error('perjalanan_transportasi') is-invalid @enderror"
                                name="perjalanan_transportasi" id="perjalanan_transportasi" placeholder="2.000.000" value="{{ old('perjalanan_transportasi') }}">
                            <div class="invalid-feedback">
                                @error('perjalanan_transportasi') {{ $message }} @else Biaya perjalanan atau transportasi wajib diisi. @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="luaran" class="form-label">Luaran <span class="text-danger">*</span></label>
                            <input type="text" required
                                class="form-control input-rupiah rab-item @error('luaran') is-invalid @enderror"
                                name="luaran" id="luaran" placeholder="2.000.000" value="{{ old('luaran') }}">
                            <div class="invalid-feedback">
                                @error('luaran') {{ $message }} @else Biaya luaran wajib diisi. @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <span class="fw-bold text-success fs-6">Total Anggaran (Rp)</span>
                        </div>
                        <div class="col-md-6 d-flex justify-content-md-end">
                            <input type="text" class="form-control text-end fw-bold bg-light" style="max-width: 260px;"
                                id="total_anggaran_display" name="total_anggaran" value="{{ old('total_anggaran', '0') }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4" id="card-pengesahan">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold">6. Pengesahan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="kota_pengesahan" class="form-label">Kota <span class="text-danger">*</span></label>
                        <input type="text" required
                            class="form-control @error('kota_pengesahan') is-invalid @enderror"
                            name="kota_pengesahan" id="kota_pengesahan" maxlength="20" placeholder="Jakarta"
                            value="{{ old('kota_pengesahan') }}">
                        <div class="d-flex justify-content-between">
                            <div class="invalid-feedback">
                                @error('kota_pengesahan') {{ $message }} @else Kota wajib diisi. @enderror
                            </div>
                            <small id="kota_pengesahan-counter" class="form-text text-muted ms-auto">0/20</small>
                        </div>
                    </div>

                    <div class="row g-4 mt-1">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Pihak yang Mengetahui <span class="text-danger">*</span></h6>

                            <div class="mb-3">
                                <label for="mengetahui_nip" class="form-label">NIP</label>
                                <input type="text"
                                    class="form-control only-number @error('mengetahui_nip') is-invalid @enderror"
                                    name="mengetahui_nip" id="mengetahui_nip" value="{{ old('mengetahui_nip') }}">
                            </div>

                            <div class="mb-3">
                                <label for="mengetahui_nama" class="form-label">Nama Lengkap</label>
                                <input type="text"
                                    class="form-control @error('mengetahui_nama') is-invalid @enderror"
                                    name="mengetahui_nama" id="mengetahui_nama" value="{{ old('mengetahui_nama') }}">
                            </div>

                            <div class="mb-3">
                                <label for="mengetahui_jabatan" class="form-label">Jabatan</label>
                                <input type="text"
                                    class="form-control @error('mengetahui_jabatan') is-invalid @enderror"
                                    name="mengetahui_jabatan" id="mengetahui_jabatan" value="{{ old('mengetahui_jabatan') }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Pihak yang Menyetujui <span class="text-danger">*</span></h6>

                            <div class="mb-3">
                                <label for="menyetujui_nip" class="form-label">NIP</label>
                                <input type="text"
                                    class="form-control only-number @error('menyetujui_nip') is-invalid @enderror"
                                    name="menyetujui_nip" id="menyetujui_nip" value="{{ old('menyetujui_nip') }}">
                            </div>

                            <div class="mb-3">
                                <label for="menyetujui_nama" class="form-label">Nama Lengkap</label>
                                <input type="text"
                                    class="form-control @error('menyetujui_nama') is-invalid @enderror"
                                    name="menyetujui_nama" id="menyetujui_nama" value="{{ old('menyetujui_nama') }}">
                            </div>

                            <div class="mb-3">
                                <label for="menyetujui_jabatan" class="form-label">Jabatan</label>
                                <input type="text"
                                    class="form-control @error('menyetujui_jabatan') is-invalid @enderror"
                                    name="menyetujui_jabatan" id="menyetujui_jabatan" value="{{ old('menyetujui_jabatan') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2 mb-5">
                <button type="submit" class="btn btn-primary btn-lg fw-bold">Ajukan Proposal Sekarang</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rabInputs = document.querySelectorAll('.rab-item');
            const totalDisplay = document.getElementById('total_anggaran_display');

            function parseAngka(val) {
                if (!val) return 0;
                const bersih = val.toString().replace(/[^0-9]/g, '');
                return parseInt(bersih, 10) || 0;
            }

            function formatRibuan(angka) {
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function hitungTotalRab() {
                let total = 0;
                rabInputs.forEach(input => {
                    total += parseAngka(input.value);
                });
                if (totalDisplay) {
                    totalDisplay.value = formatRibuan(total);
                }
            }

            rabInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const angka = parseAngka(this.value);
                    this.value = angka > 0 ? formatRibuan(angka) : '';
                    hitungTotalRab();
                });
            });
            hitungTotalRab();

            const kotaInput = document.getElementById('kota_pengesahan');
            const kotaCounter = document.getElementById('kota_pengesahan-counter');
            if (kotaInput && kotaCounter) {
                const updateKotaCounter = () => {
                    kotaCounter.textContent = `${kotaInput.value.length}/20`;
                };
                kotaInput.addEventListener('input', updateKotaCounter);
                updateKotaCounter();
            }

            async function bindCheckButton(btnId, inputId, namaId, endpoint, paramKey, msgKosong, msgGagal) {
                const btn = document.getElementById(btnId);
                const input = document.getElementById(inputId);
                const namaTarget = document.getElementById(namaId);
                if (!btn || !input || !namaTarget) return;

                btn.addEventListener('click', async function() {
                    const nilai = input.value.trim();
                    if (!nilai) {
                        alert(msgKosong);
                        input.focus();
                        return;
                    }

                    const labelAwal = btn.textContent;
                    btn.disabled = true;
                    btn.textContent = 'Mengecek...';

                    try {
                        const res = await fetch(`${endpoint}?${encodeURIComponent(paramKey)}=${encodeURIComponent(nilai)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            credentials: 'same-origin'
                        });

                        let data = {};
                        try {
                            data = await res.json();
                        } catch (e) {
                            data = {};
                        }

                        if (res.ok && data.nama) {
                            namaTarget.value = data.nama;
                        } else {
                            namaTarget.value = '';
                            alert(data.message || `${msgGagal} (status ${res.status})`);
                        }
                    } catch (err) {
                        namaTarget.value = '';
                        alert('Gagal menghubungi server saat mengecek data.');
                    } finally {
                        btn.disabled = false;
                        btn.textContent = labelAwal;
                    }
                });
            }

            bindCheckButton(
                'btnCheckNIDN',
                'nidn_input',
                'nama_dosen_form',
                '/dosen/pengajuan/check-dosen',
                'nip',
                'Masukkan NIP terlebih dahulu.',
                'Data dosen tidak ditemukan.'
            );

            bindCheckButton(
                'btnCheckNPM',
                'npm_input',
                'nama_mhs_form',
                '/dosen/pengajuan/check-mahasiswa',
                'npm',
                'Masukkan NPM terlebih dahulu.',
                'Data mahasiswa tidak ditemukan.'
            );
        });
    </script>
@endsection