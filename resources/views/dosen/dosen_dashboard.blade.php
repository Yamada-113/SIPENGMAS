@extends('layouts.app')

@section('title', 'Dasbor Dosen')

@section('content')
    {{-- ALERT SUKSES --}}
    @if (session('success'))
        <div id="floating-alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 1050;"></div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const container = document.getElementById('floating-alert-container');
                if (container) {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = `alert alert-success shadow-lg alert-dismissible fade show`;
                    alertDiv.role = 'alert';
                    alertDiv.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    `;
                    container.appendChild(alertDiv);
                    setTimeout(() => {
                        alertDiv.classList.remove('show');
                        setTimeout(() => alertDiv.remove(), 300);
                    }, 3000);
                }
            });
        </script>
    @endif

    <div class="container">
        {{-- 1. Banner Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card text-white shadow-sm" style="background-color: #8BC3B4;">
                    <div class="card-body p-4">
                        <h5 class="card-title">Dasbor Dosen</h5>
                        <p class="card-text mb-0">Anda dapat mengajukan usulan terkait dengan layanan berikut:</p>
                    </div>
                    <div class="p-4 pt-0">
                        <div class="row justify-content-center g-3">
                            {{-- KARTU 1: USULAN BARU --}}
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="{{ route('dosen.pengajuan.skema') }}"
                                    class="text-decoration-none action-card-link">
                                    <div class="card shadow-sm action-card h-100"
                                        style="border-radius: 0.5rem; border: none;">
                                        <div class="card-body text-center p-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#378a75" class="bi bi-clipboard-check-fill action-icon mx-auto mb-2"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0zm3 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z" />
                                                <path
                                                    d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5zm6.854 7.354-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708" />
                                            </svg>
                                            <h6 class="card-title mb-0 mt-2 action-title" style="color: #378a75;">Usulan
                                                Pengabdian Baru</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            {{-- KARTU 2: ANGGOTA REQUEST (POSISI COUNT DIPERBAIKI SEPERTI WADEK) --}}
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <a href="{{ route('dosen.anggota_request') }}"
                                    class="text-decoration-none action-card-link">
                                    <div class="card shadow-sm action-card h-100"
                                        style="border-radius: 0.5rem; border: none;">

                                        {{-- Position Relative ada di card-body --}}
                                        <div class="card-body text-center p-4 position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#378a75" class="bi bi-people-fill action-icon mx-auto mb-2"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                                            </svg>
                                            <h6 class="card-title mb-0 mt-2 action-title" style="color: #378a75;">Anggota
                                                Pengabdian Request</h6>

                                            {{-- Badge Hitung Request --}}
                                            @php
                                                $reqCount = \Illuminate\Support\Facades\DB::table(
                                                    'proposal_core_anggota_dosen',
                                                )
                                                    ->where('nidn', Auth::user()->nidn)
                                                    ->where('is_approved_dosen', 0)
                                                    ->count();
                                            @endphp

                                            {{-- POSISI BADGE: Top Right Corner (Floating like Wadek) --}}
                                            <span
                                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light {{ $reqCount == 0 ? 'd-none' : '' }}">
                                                {{ $reqCount }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Status Usulan Terakhir --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom-0 pt-3 pb-2">
                        <h6 class="fw-bold mb-0 text-secondary text-uppercase" style="letter-spacing: 0.5px;">
                            <i class="bi bi-clock-history me-2"></i>Status Usulan Terakhir
                        </h6>
                    </div>

                    <div class="card-body p-0">
                        @if (isset($lastProposal) && $lastProposal)
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                    <div>
                                        {{-- Judul (Via Relasi Identitas) --}}
                                        <h5 class="mb-2 fw-bold text-dark">
                                            {{ $lastProposal->identitas->judul ?? 'Tanpa Judul' }}
                                        </h5>

                                        <div class="d-flex align-items-center flex-wrap gap-2">

                                            {{-- 1. Tanggal (Format Langsung di Blade) --}}
                                            <span class="text-secondary" style="font-size: 1rem;">
                                                Tanggal Ajuan: <span
                                                    class="fw-bold text-dark">{{ $lastProposal->created_at->format('d-m-Y') }}</span>
                                            </span>

                                            <span class="text-secondary mx-1 d-none d-md-inline">•</span>

                                            {{-- 2. Skema (GUNAKAN ACCESSOR MODEL) --}}
                                            <span class="text-secondary" style="font-size: 1rem;">
                                                Skema: <span
                                                    class="fw-bold text-dark">{{ $lastProposal->skemaRef->nama ?? '-' }}</span>
                                            </span>

                                            <span class="text-secondary mx-1 d-none d-md-inline">•</span>

                                            {{-- 3. Status (GUNAKAN ACCESSOR MODEL) --}}
                                            <span class="text-secondary" style="font-size: 1rem;">
                                                Status:
                                                <span
                                                    class="badge bg-{{ $lastProposal->status_color }} bg-opacity-10 text-{{ $lastProposal->status_color }} fs-6 px-3 py-1 ms-1">
                                                    {{ $lastProposal->status_label }}
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ route('dosen.detail_proposal', $lastProposal->id) }}"
                                        class="btn btn-outline-primary fw-bold px-4 py-2 ms-3">Lihat Detail</a>
                                </div>
                            </div>
                        @else
                            <div class="card-body d-flex flex-column align-items-center justify-content-center p-5">
                                <img src="{{ asset('images/empty.png') }}" alt="Kosong"
                                    style="width: 270px; opacity: 0.7;" class="mb-3">
                                <h6 class="text-muted fw-bold">Belum ada proposal.</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Profil & Statistik --}}
        <div class="row mb-4">
            <div class="col-12">
                <h6 class="text-uppercase fw-bold text-secondary mb-3" style="letter-spacing: 1px; font-size: 1rem;">Profil
                    Anda</h6>
                <div class="row g-3">
                    {{-- Identitas --}}
                    <div class="col-lg-3 col-md-12">
                        <div class="card shadow-sm h-100 border-0">
                            <div
                                class="card-body p-3 d-flex flex-column justify-content-center align-items-center text-center">
                                <div class="mb-2 bg-light rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                    style="width: 60px; height: 60px;">
                                    <i class="bi bi-person-fill text-secondary" style="font-size: 2rem;"></i>
                                </div>
                                <small class="text-uppercase text-secondary fw-bold mb-1"
                                    style="font-size: 0.8rem; letter-spacing: 1px;">Identitas</small>
                                <h6 class="fw-bold mb-1 text-dark text-truncate w-100" title="{{ auth()->user()->name }}">
                                    {{ auth()->user()->name }}</h6>
                                <span class="badge bg-light text-dark border px-2 py-1 mt-1 mb-2"
                                    style="font-size: 0.75rem;">
                                    NIP: {{ auth()->user()->nidn ?? '-' }}
                                </span>
                                <a href="{{ route('profile.edit') }}"
                                    class="btn btn-outline-primary rounded-pill px-3 py-1 btn-sm fw-bold"
                                    style="font-size: 0.75rem;">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Partial Kartu Statistik --}}
                    @include('dosen.partials.dashboard_stats_cards')
                </div>
            </div>
        </div>

        {{-- 4. Panduan --}}
        <div class="row mb-4" style="margin-top: 10px;">
            <div class="col-12">
                <h6 class="text-uppercase fw-bold text-secondary mb-3" style="letter-spacing: 1px; font-size: 1rem;">Panduan
                </h6>
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse ($kumpulanPanduan as $panduan)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="#8BC3B4" class="bi bi-file-earmark-pdf-fill me-3" viewBox="0 0 16 16">
                                            <path
                                                d="M5.523 12.424c.14-.082.293-.162.459-.252.166-.09.337-.187.518-.293a.5.5 0 0 1 .536.002c.17.104.34.2.51.304.17.102.34.208.514.318.173.11.352.228.536.354a.5.5 0 0 0 .58.001c.19-.13.388-.268.59-.413.201-.145.408-.3.626-.463.218-.162.44-.33.669-.507a.5.5 0 0 1 .58.002c.22.168.44.33.668.507.218.163.425.318.626.463.202.145.4.283.59.413a.5.5 0 0 0 .58-.001c.184-.126.363-.244.536-.354.174-.11.344-.216.514-.318.17-.104.34-.2.51-.304a.5.5 0 0 1 .536-.002c.18.106.352.203.518.293.166.09.318.17.459.252a1.71 1.71 0 0 1 .51.414c.142.3.121.638-.03.886-.153.248-.465.447-.82.557a.97.97 0 0 1-.144.022.97.97 0 0 1-.144-.022c-.355-.11-.667-.309-.82-.557-.15-.248-.172-.586-.03-.886a1.71 1.71 0 0 1 .51-.414zM11.11 7.91l-.11.18a.5.5 0 0 1-.847.11L9 7.414v2.07a.5.5 0 0 1-1 0V7.414l-1.153.662a.5.5 0 0 1-.847-.11l-.11-.18a.5.5 0 1 1 .847-.49l1.11-1.922a.5.5 0 0 1 .848 0l1.11 1.922a.5.5 0 1 1 .847.49z" />
                                            <path
                                                d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v10.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V2a1 1 0 0 1 1-1" />
                                        </svg>
                                        <span style="font-weight: 500;">{{ $panduan->title }}</span>
                                    </div>
                                    <a href="{{ \Storage::url('panduan/' . $panduan->file) }}" target="_blank"
                                        class="btn btn-sm btn-download">Download</a>
                                </div>
                            @empty
                                <div class="card-body d-flex flex-column align-items-center justify-content-center p-5">
                                    <img src="{{ asset('images/empty.png') }}" alt="Belum ada panduan"
                                        style="width: 275px; opacity: 0.7;" class="mb-3">
                                    <h6 class="text-muted fw-semibold">Belum ada panduan tersedia</h6>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Lengkapi Profile (Dari Partial) --}}
        @include('dosen.partials.modal_lengkapi_profile')

    </div>
@endsection

@push('styles')
    @include('shared.styles_dashboard')
@endpush
