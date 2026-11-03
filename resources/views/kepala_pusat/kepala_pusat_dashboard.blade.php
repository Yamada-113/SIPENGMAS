@extends('layouts.app')

@section('title', 'Dasbor Kepala Pusat')

@section('content')
    @include('shared.alert_script')

    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card text-white shadow-sm" style="background-color: #8BC3B4; border: none;">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold">Dasbor Kepala Pusat</h5>
                        <p class="card-text mb-0">Selamat datang. Kelola validasi proposal skema pusat yang masuk.</p>
                    </div>

                    <div class="p-4 pt-0">
                        <div class="row justify-content-center g-3">
                            <div class="col-lg-4 col-md-5 col-sm-6">
                                <a href="{{ route('kepala_pusat.validasi') }}"
                                    class="text-decoration-none action-card-link">
                                    <div class="card shadow-sm action-card h-100"
                                        style="border-radius: 0.5rem; border: none;">
                                        <div class="card-body text-center p-4 position-relative">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#378a75" class="bi bi-clipboard-check-fill action-icon mx-auto mb-2"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0zm3 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5-.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z" />
                                                <path
                                                    d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5zm6.854 7.354-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708" />
                                            </svg>

                                            <h6 class="card-title mb-0 mt-2 action-title" style="color: #378a75;">Validasi
                                                Proposal Pusat</h6>

                                            @if ($stats['pending_count'] > 0)
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                                                    {{ $stats['pending_count'] }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom-0 pt-3 pb-2">
                        <h6 class="fw-bold mb-0 text-secondary text-uppercase" style="letter-spacing: 0.5px;">
                            <i class="bi bi-clock-history me-2"></i>Menunggu Persetujuan Anda
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @if ($pendingProposals->count() > 0)
                            @php $lastPending = $pendingProposals->first(); @endphp

                            <div class="list-group list-group-flush">
                                <div
                                    class="list-group-item d-flex justify-content-between align-items-center flex-wrap px-4 py-3 gap-3">
                                    <div>
                                        <h5 class="mb-2 fw-bold text-dark">
                                            {{ $lastPending->identitas->judul ?? 'Tanpa Judul' }}
                                        </h5>

                                        <div class="d-flex align-items-center flex-wrap gap-2 text-muted small">
                                            <span>
                                                Tanggal Masuk: <span
                                                    class="fw-bold text-dark">{{ $lastPending->created_at->format('d-m-Y') }}</span>
                                            </span>

                                            <span class="d-none d-md-inline">•</span>

                                            <span>
                                                Skema: <span
                                                    class="fw-bold text-dark">{{ $lastPending->skemaRef->nama ?? '-' }}</span>
                                            </span>

                                            <span class="d-none d-md-inline">•</span>

                                            <span class="d-flex align-items-center">
                                                Status:
                                                <span
                                                    class="badge bg-{{ $lastPending->status_color }} bg-opacity-10 text-{{ $lastPending->status_color }} border border-{{ $lastPending->status_color }} border-opacity-25 rounded-pill px-2 ms-1">
                                                    {{ $lastPending->status_label }}
                                                </span>
                                            </span>
                                        </div>
                                    </div>

                                    <a href="{{ route('kepala_pusat.validasi.detail', $lastPending->id) }}"
                                        class="btn btn-outline-primary btn-sm fw-bold px-3 shadow-sm text-nowrap">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="card-body d-flex flex-column align-items-center justify-content-center p-5">
                                <img src="{{ asset('images/empty.png') }}" alt="Belum ada usulan"
                                    style="width: 275px; opacity: 0.7;" class="mb-2">
                                <h6 class="text-muted fw-semibold">Belum ada proposal yang perlu persetujuan</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <h6 class="text-uppercase fw-bold text-secondary mb-3">Informasi Pusat</h6>
                <div class="row g-3">
                    <div class="col-lg-3 col-md-12">
                        <div class="card shadow-sm h-100 border-0">
                            <div
                                class="card-body p-3 d-flex flex-column justify-content-center align-items-center text-center">
                                <div class="mb-2 bg-light rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                    style="width: 60px; height: 60px;">
                                    <i class="bi bi-person-fill text-secondary" style="font-size: 2rem;"></i>
                                </div>
                                <small class="text-uppercase text-secondary fw-bold mb-1"
                                    style="font-size: 0.7rem; letter-spacing: 1px;">Identitas</small>
                                <h6 class="fw-bold mb-1 text-dark text-truncate w-100" title="{{ auth()->user()->name }}">
                                    {{ auth()->user()->name }}</h6>
                                <span class="badge bg-light text-dark border px-2 py-1 mt-1 mb-2"
                                    style="font-size: 0.75rem;">NIP: {{ auth()->user()->nidn ?? '-' }}</span>
                                <div class="mt-2 text-muted small">Kepala Pusat</div>
                            </div>
                        </div>
                    </div>

                    @include('kepala_pusat.partials.dashboard_stats_cards')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @include('shared.styles_dashboard')
@endpush
