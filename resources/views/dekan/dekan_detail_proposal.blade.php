@extends('layouts.app')

@section('title', 'Detail Monitoring Proposal - Dekan')

@section('content')
    @include('shared.alert_script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Detail Proposal Prodi</h4>
                <p class="text-muted mb-0">Detail informasi proposal pengabdian skala Prodi.</p>
            </div>
            <a href="{{ route('dekan.monitoring') }}" class="btn btn-secondary d-flex align-items-center shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">

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
                                <p class="mb-0 fw-semibold text-primary">{{ $detailProposal->skema_label }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted fw-bold">Tahun Pelaksanaan</label>
                                <p class="mb-0">{{ $detailProposal->tahun_pelaksanaan }}
                                    <span class="text-muted">({{ $detailProposal->identitas->periode_kegiatan ?? 1 }}
                                        Tahun)</span>
                                </p>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="small text-muted fw-bold">Bidang Fokus</label>
                            <p class="mb-0 text-dark">{{ $detailProposal->identitas->bidang_fokus ?? '-' }}</p>
                        </div>
                    </div>
                </div>

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

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-cash-stack me-2"></i>Rencana Anggaran Biaya
                        </h6>
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

            <div class="col-lg-4">

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-people me-2"></i>Tim Pelaksana</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @foreach ($anggota as $item)
                            @php
                                $isMahasiswa =
                                    str_contains($item->peran ?? '', 'Mahasiswa') || ($item->tipe ?? '') == 'Mahasiswa';
                                $inisial = $isMahasiswa ? 'M' : 'D';
                                $isKetua = ($item->tipe ?? '') === 'Ketua';
                                $statusApproved = $isKetua ? 1 : $item->is_approved_dosen ?? 0;
                            @endphp

                            <div class="list-group-item px-3 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold"
                                        style="width: 40px; height: 40px;">
                                        {{ $inisial }}
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 fw-bold text-dark small">{{ $item->nama }}</h6>
                                        <small class="text-muted d-block">{{ $item->peran }}</small>
                                    </div>
                                    @if ($isKetua)
                                        <span class="badge bg-primary rounded-pill" style="font-size: 0.6rem;">Ketua</span>
                                    @elseif($statusApproved == 1 && !$isMahasiswa)
                                        <span
                                            class="badge bg-success-subtle text-success rounded-pill border border-success-subtle"
                                            style="font-size: 0.6rem;">Confirmed</span>
                                    @elseif(!$isMahasiswa)
                                        <span
                                            class="badge bg-warning-subtle text-warning rounded-pill border border-warning-subtle"
                                            style="font-size: 0.6rem;">Waiting</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

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

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="fw-bold text-primary mb-0"><i class="bi bi-paperclip me-2"></i>Dokumen Lampiran</h6>
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
                                <p class="text-muted small mb-0">Tidak ada dokumen.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

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

                        <div class="d-grid gap-2">
                            <button
                                class="btn btn-outline-success border text-start d-flex justify-content-between align-items-center hover-shadow"
                                data-bs-toggle="modal" data-bs-target="#modalListArtikel">
                                <span class="fw-semibold text-dark"><i class="bi bi-journal-text text-danger me-2"></i>
                                    Artikel Ilmiah</span>
                                <div class="d-flex align-items-center">
                                    @if ($jmlArtikel > 0)
                                        <span class="badge bg-success text-white rounded-pill me-2">{{ $jmlArtikel }}
                                            File</span>
                                    @endif
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </div>
                            </button>

                            <button
                                class="btn btn-outline-success border text-start d-flex justify-content-between align-items-center hover-shadow"
                                data-bs-toggle="modal" data-bs-target="#modalListBuku">
                                <span class="fw-semibold text-dark"><i class="bi bi-book text-success me-2"></i>
                                    Sertifikat Seminar</span>
                                <div class="d-flex align-items-center">
                                    @if ($jmlSertifikat > 0)
                                        <span class="badge bg-success text-white rounded-pill me-2">{{ $jmlSertifikat }}
                                            File</span>
                                    @endif
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </div>
                            </button>

                            <button
                                class="btn btn-outline-success border text-start d-flex justify-content-between align-items-center hover-shadow"
                                data-bs-toggle="modal" data-bs-target="#modalListHKI">
                                <span class="fw-semibold text-dark"><i class="bi bi-award text-warning me-2"></i>
                                    HKI</span>
                                <div class="d-flex align-items-center">
                                    @if ($jmlHKI > 0)
                                        <span class="badge bg-success text-white rounded-pill me-2">{{ $jmlHKI }}
                                            File</span>
                                    @endif
                                    <i class="bi bi-chevron-right text-muted"></i>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-1 mb-5">
                    @php $isReady = $detailProposal->status_progress >= 4; @endphp
                    <a href="{{ $isReady ? route('dosen.proposal.export_pdf', $detailProposal->id) : '#' }}"
                        class="btn {{ $isReady ? 'btn-success' : 'btn-secondary' }} btn-lg px-3 w-100 fw-bold shadow-sm"
                        onclick="{{ !$isReady ? 'event.preventDefault(); showDownloadAlert();' : '' }}">
                        <i class="bi bi-file-earmark-pdf-fill me-2"></i>Download Lembar Pengesahan
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('shared.modal_luaran', ['lampiran' => $lampiran])

@endsection

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
    </style>
@endpush

@push('scripts')
    <script>
        function showDownloadAlert() {
            Swal.fire({
                icon: 'warning',
                title: 'Belum Tersedia',
                text: 'Lembar pengesahan hanya dapat diunduh setelah proposal selesai divalidasi dan didanai.',
                confirmButtonColor: '#378a75'
            });
        }
    </script>
@endpush
