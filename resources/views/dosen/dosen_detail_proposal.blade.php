@extends('layouts.app')

@section('title', 'Detail Proposal')

@section('content')
    @php
        // Cek apakah user yang login adalah ketua pengusul
        $isPengusul = Auth::id() == $detailProposal->user_id;
    @endphp

    {{-- Alert Script & SweetAlert --}}
    @include('shared.alert_script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container pb-5">
        {{-- Header & Tombol Kembali --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Detail Proposal Usulan</h4>
                <p class="text-muted mb-0">Tinjau kembali data proposal.</p>
            </div>
            <a href="{{ route('dosen.dashboard') }}" class="btn btn-secondary d-flex align-items-center shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
            </a>
        </div>

        <div class="row">
            {{-- KOLOM KIRI --}}
            <div class="col-lg-8">

                {{-- 1. IDENTITAS USULAN --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-info-circle me-2"></i>Identitas Usulan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold">Judul Kegiatan</label>
                                <p class="fs-5 fw-bold text-dark mb-0">{{ $detailProposal->identitas->judul ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold d-block mb-1">Status Saat Ini</label>
                                <div>
                                    <span class="badge bg-{{ $detailProposal->status_color }} fs-6 px-3 py-2 rounded-pill">
                                        {{ $detailProposal->status_label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold">Skema Pengabdian</label>
                                <p class="mb-0 fw-semibold text-primary">
                                    {{ $detailProposal->skemaRef->nama ?? 'Skema Tidak Ditemukan' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold">Tahun Pelaksanaan</label>
                                <p class="mb-0">{{ $detailProposal->tahun_pelaksanaan }}
                                    <span class="text-muted">({{ $detailProposal->identitas->periode_kegiatan ?? 1 }} Tahun)</span>
                                </p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted fw-bold">Bidang Fokus</label>
                            <p class="mb-0 text-dark">{{ $detailProposal->identitas->bidang_fokus ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- 2. ABSTRAK & MITRA --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-file-text me-2"></i>Substansi Kegiatan</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="small text-muted fw-bold mb-2">Abstrak / Ringkasan</label>
                            <div class="p-3 bg-light rounded text-secondary border"
                                style="font-style: italic; line-height: 1.6;">
                                "{{ $detailProposal->identitas->abstrak ?? '-' }}"
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted fw-bold">Mitra Sasaran</label>
                                <p class="mb-0 fw-bold">{{ $detailProposal->atribut->nama_institusi_mitra ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted fw-bold">Lokasi Kegiatan</label>
                                <p class="mb-0"><i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                    {{ $detailProposal->atribut->alamat_mitra ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. RENCANA ANGGARAN (RAB) --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-cash-stack me-2"></i>Rencana Anggaran Biaya</h6>
                        <span class="badge bg-success fs-6 font-monospace px-3 py-2">
                            Total: Rp {{ number_format($detailProposal->total_dana, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Item Pengeluaran</th>
                                    <th class="text-end pe-4">Biaya (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4">Honor</td>
                                    <td class="text-end pe-4">
                                        {{ number_format($detailProposal->biaya->honor_output ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Biaya Inovasi</td>
                                    <td class="text-end pe-4">
                                        {{ number_format($detailProposal->biaya->belanja_non_operasional ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <td class="ps-4">Bahan Pelatihan</td>
                                    <td class="text-end pe-4">
                                        {{ number_format($detailProposal->biaya->bahan_habis_pakai ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr> -->
                                <tr>
                                    <td class="ps-4">Perjalanan atau Transportasi</td>
                                    <td class="text-end pe-4">
                                        {{ number_format($detailProposal->biaya->transportasi ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="ps-4">Luaran</td>
                                    <td class="text-end pe-4">
                                        {{ number_format($detailProposal->biaya->lain_lain ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN --}}
            <div class="col-lg-4">

                {{-- PANEL KEPUTUSAN (INVITATION) --}}
                @if (isset($statusUndanganSaya) && $statusUndanganSaya === 0 && isset($idUndanganSaya))
                    <div class="card shadow border-0 mb-4 bg-primary bg-opacity-10 border-primary">
                        <div class="card-body">
                            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-shield-check me-2"></i>Keputusan Bergabung</h6>
                            <p class="small text-muted mb-3">Anda diundang dalam proposal ini. Tentukan pilihan:</p>

                            <div class="d-grid gap-2">
                                <button class="btn btn-success fw-bold text-white shadow-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalTerimaAnggota">
                                    <i class="bi bi-check-circle-fill me-2"></i> Terima
                                </button>
                                <button class="btn btn-danger fw-bold text-white shadow-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalTolakAnggota">
                                    <i class="bi bi-x-circle-fill me-2"></i> Tolak
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- 1. TIM PELAKSANA --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-people me-2"></i>Tim Pelaksana</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach ($anggota as $item)
                            @php
                                $isMahasiswa = str_contains($item->peran ?? '', 'Mahasiswa') || ($item->tipe ?? '') == 'Mahasiswa';
                                $inisial = $isMahasiswa ? 'M' : 'D';
                                $isKetua = ($item->tipe ?? '') === 'Ketua';
                                $statusApproved = $isKetua ? 1 : $item->is_approved_dosen ?? 0;
                            @endphp

                            <div class="list-group-item px-3 py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold"
                                            style="width: 40px; height: 40px;">
                                            {{ $inisial }}
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">
                                                {{ $item->nama }}</h6>
                                            <small class="text-muted">{{ $item->peran }}</small>
                                        </div>
                                    </div>

                                    @if (!$isMahasiswa)
                                        <div class="ms-2">
                                            @if ($statusApproved == 1)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                                    <i class="bi bi-check-circle-fill me-1"></i>
                                                    {{ $isKetua ? 'Pengusul' : 'Setuju' }}
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill">
                                                    <i class="bi bi-clock-history me-1"></i> Menunggu
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- 2. FILE PROPOSAL --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="d-grid">
                            <a href="{{ \Storage::url($detailProposal->file_proposal) }}" target="_blank"
                                class="btn btn-outline-primary fw-bold py-2 border-2">
                                <i class="bi bi-file-earmark-pdf-fill me-2"></i> Lihat File Proposal
                            </a>
                        </div>
                    </div>
                </div>

                {{-- 3. DOKUMEN LAMPIRAN --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-paperclip me-2"></i>Dokumen Lampiran</h6>
                        @if ($isPengusul)
                            <button class="btn btn-sm btn-outline-primary fw-bold" data-bs-toggle="modal"
                                data-bs-target="#modaldokumen">
                                <i class="bi bi-upload me-2"></i> Upload
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @forelse($lampiran->where('kategori', 'dokumen') as $item)
                            <div class="d-grid gap-2 mb-2">
                                <a href="{{ \Storage::url($item->file_path) }}" target="_blank"
                                    class="btn btn-outline-secondary btn-sm text-start text-break border-0 bg-light">
                                    <i class="bi bi-file-pdf me-2 text-danger"></i> {{ $item->judul }}
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-4 bg-light rounded border border-dashed">
                                <p class="text-muted small mb-0">Belum ada Dokumen.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- 4. LUARAN KEGIATAN --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-archive me-2"></i>Luaran Kegiatan</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-2">Daftar luaran yang telah diunggah:</p>

                        @php
                            $jmlArtikel = $lampiran->where('kategori', 'artikel')->count();
                            $jmlSertifikat = $lampiran->where('kategori', 'sertifikat')->count();
                            $jmlHKI = $lampiran->where('kategori', 'hki')->count();
                        @endphp

                        <div class="d-flex flex-column gap-2">
                            
                            {{-- ITEM 1: ARTIKEL ILMIAH --}}
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-success border text-start d-flex justify-content-between align-items-center hover-shadow flex-grow-1"
                                    data-bs-toggle="modal" data-bs-target="#modalListArtikel">
                                    <span class="fw-semibold text-dark"><i class="bi bi-journal-text text-danger me-2"></i> Artikel Ilmiah</span>
                                    <div class="d-flex align-items-center">
                                        @if ($jmlArtikel > 0)
                                            <span class="badge bg-success text-white rounded-pill me-2">{{ $jmlArtikel }}</span>
                                        @endif
                                        <i class="bi bi-chevron-right text-muted"></i>
                                    </div>
                                </button>
                                {{-- Tombol Upload Khusus Pengusul --}}
                                @if($isPengusul)
                                    <button class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#modalUploadArtikel" title="Upload Artikel">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                @endif
                            </div>

                            {{-- ITEM 2: SERTIFIKAT --}}
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-success border text-start d-flex justify-content-between align-items-center hover-shadow flex-grow-1"
                                    data-bs-toggle="modal" data-bs-target="#modalListBuku">
                                    <span class="fw-semibold text-dark"><i class="bi bi-book text-success me-2"></i> Sertifikat Seminar</span>
                                    <div class="d-flex align-items-center">
                                        @if ($jmlSertifikat > 0)
                                            <span class="badge bg-success text-white rounded-pill me-2">{{ $jmlSertifikat }}</span>
                                        @endif
                                        <i class="bi bi-chevron-right text-muted"></i>
                                    </div>
                                </button>
                                @if($isPengusul)
                                    <button class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#modalUploadSertifikat" title="Upload Sertifikat">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                @endif
                            </div>

                            {{-- ITEM 3: HKI --}}
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-success border text-start d-flex justify-content-between align-items-center hover-shadow flex-grow-1"
                                    data-bs-toggle="modal" data-bs-target="#modalListHKI">
                                    <span class="fw-semibold text-dark"><i class="bi bi-award text-warning me-2"></i> HKI</span>
                                    <div class="d-flex align-items-center">
                                        @if ($jmlHKI > 0)
                                            <span class="badge bg-success text-white rounded-pill me-2">{{ $jmlHKI }}</span>
                                        @endif
                                        <i class="bi bi-chevron-right text-muted"></i>
                                    </div>
                                </button>
                                @if($isPengusul)
                                    <button class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#modalUploadHKI" title="Upload HKI">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>

                {{-- DOWNLOAD PENGESAHAN --}}
                <div class="d-flex justify-content-end mt-1 mb-5">
                    @php $isApprovedWR3 = $detailProposal->status_progress >= 4; @endphp
                    <a href="{{ $isApprovedWR3 ? route('dosen.proposal.export_pdf', $detailProposal->id) : '#' }}"
                        class="btn {{ $isApprovedWR3 ? 'btn-success' : 'btn-secondary' }} btn-lg px-3 w-100 fw-bold shadow-sm"
                        onclick="{{ !$isApprovedWR3 ? 'event.preventDefault(); showDownloadAlert();' : '' }}">
                        <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download Lembar Pengesahan
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- MODAL SECTION --}}
    {{-- ========================================================== --}}

    {{-- MODAL LIST LUARAN (SHARED) --}}
    @include('shared.modal_luaran', ['lampiran' => $lampiran])

    {{-- MODAL UNDANGAN (TERIMA/TOLAK) --}}
    @if (isset($idUndanganSaya) && $statusUndanganSaya === 0)
        <div class="modal fade" id="modalTerimaAnggota" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title fw-bold">Konfirmasi Bergabung</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('dosen.anggota.terima', $idUndanganSaya) }}" method="POST">
                        @csrf
                        <div class="modal-body text-center p-4">
                            <div class="mb-3 text-success"><i class="bi bi-check-circle" style="font-size: 3rem;"></i></div>
                            <h6 class="fw-bold">Anda bersedia bergabung dalam proposal ini?</h6>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success fw-bold px-4">Ya, Saya Bersedia</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalTolakAnggota" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title fw-bold">Tolak Undangan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('dosen.anggota.tolak', $idUndanganSaya) }}" method="POST">
                        @csrf
                        <div class="modal-body p-4 text-center">
                            <div class="mb-3 text-danger"><i class="bi bi-x-circle" style="font-size: 3rem;"></i></div>
                            <h6 class="fw-bold">Anda yakin menolak undangan ini?</h6>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger fw-bold px-4">Ya, Tolak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL UPLOAD DOKUMEN --}}
    <div class="modal fade" id="modaldokumen" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Unggah Dokumen Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dosen.lampiran.store', $detailProposal->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="kategori" value="dokumen">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Dokumen</label>
                            <input type="text" class="form-control" name="judul" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">File (PDF/JPG)</label>
                            <input class="form-control" type="file" name="file_upload" accept=".pdf,.jpg,.jpeg,.png"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL UPLOAD ARTIKEL --}}
    <div class="modal fade" id="modalUploadArtikel" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Unggah Artikel Ilmiah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dosen.lampiran.store', $detailProposal->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="kategori" value="artikel">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Artikel</label>
                            <input type="text" class="form-control" name="judul" required placeholder="Contoh: Publikasi Jurnal X...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">File (PDF)</label>
                            <input class="form-control" type="file" name="file_upload" accept=".pdf" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL UPLOAD SERTIFIKAT --}}
    <div class="modal fade" id="modalUploadSertifikat" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Unggah Sertifikat Seminar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dosen.lampiran.store', $detailProposal->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="kategori" value="sertifikat">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Kegiatan / Seminar</label>
                            <input type="text" class="form-control" name="judul" required placeholder="Contoh: Seminar Nasional TI...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">File (PDF/JPG)</label>
                            <input class="form-control" type="file" name="file_upload" accept=".pdf,.jpg,.jpeg,.png" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL UPLOAD HKI --}}
    <div class="modal fade" id="modalUploadHKI" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Unggah Bukti HKI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dosen.lampiran.store', $detailProposal->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="kategori" value="hki">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul HKI</label>
                            <input type="text" class="form-control" name="judul" required placeholder="Contoh: Hak Cipta Aplikasi...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">File (PDF)</label>
                            <input class="form-control" type="file" name="file_upload" accept=".pdf" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success text-white">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function showDownloadAlert() {
            Swal.fire({
                icon: 'warning',
                title: 'Belum Tersedia',
                text: 'Maaf, lembar pengesahan baru dapat diunduh setelah proposal divalidasi dan disetujui oleh Wakil Rektor 3.',
                confirmButtonColor: '#378a75',
                confirmButtonText: 'Mengerti'
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        .hover-shadow:hover {
            background-color: #f8f9fa;
            border-color: #8BC3B4 !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .btn-success {
            background-color: #378a75;
            border-color: #378a75;
        }

        .btn-success:hover {
            background-color: #2e7060;
            border-color: #2e7060;
        }

        .bg-success-subtle {
            background-color: #d1e7dd !important;
        }
    </style>
@endpush