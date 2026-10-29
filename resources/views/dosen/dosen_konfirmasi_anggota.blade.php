@extends('layouts.app')

@section('title', 'Anggota Pengabdian')

@section('content')
    <div class="container">
        @include('shared.alert_script')

        {{-- TOOLBAR --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <a href="{{ route('dosen.dashboard') }}" class="btn btn-secondary btn-action-control shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="d-flex gap-2">
                @include('shared.toolbar_filter')
            </div>
        </div>

        {{-- HEADER --}}
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0 fw-bold text-primary">Anggota Pengabdian</h1>
                <p class="text-muted">Daftar proposal pengabdian di mana Dosen lain telah mengundang Anda.</p>
            </div>
        </div>

        {{-- TABS --}}
        <ul class="nav nav-tabs" id="anggotaTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="menunggu-tab" data-bs-toggle="tab" data-bs-target="#menunggu-pane"
                    type="button" onclick="setActiveTab('menunggu')">
                    Undangan Masuk <span class="badge bg-danger ms-1">{{ $data['invitations']->total() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="tunggu-tab" data-bs-toggle="tab" data-bs-target="#tunggu-pane" type="button"
                    onclick="setActiveTab('tunggu')">
                    Menunggu Tim <span class="badge bg-warning text-dark ms-1">{{ $data['waiting']->total() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="proses-tab" data-bs-toggle="tab" data-bs-target="#proses-pane" type="button"
                    onclick="setActiveTab('proses')">
                    Proses Review <span class="badge bg-info text-dark ms-1">{{ $data['process']->total() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="selesai-tab" data-bs-toggle="tab" data-bs-target="#selesai-pane" type="button"
                    onclick="setActiveTab('selesai')">
                    Selesai / Didanai <span class="badge bg-success ms-1">{{ $data['finished']->total() }}</span>
                </button>
            </li>
        </ul>

        {{-- CONTENT TABS --}}
        <div class="card shadow-sm border-0 mb-5" style="border-top-left-radius: 0; border-top-right-radius: 0;">
            <div class="card-body p-0">
                <div class="tab-content" id="anggotaTabContent">

                    {{-- TAB 1: UNDANGAN MASUK --}}
                    <div class="tab-pane fade show active" id="menunggu-pane">
                        {{-- Mengirim 'invitations' ke Partial --}}
                        @include('dosen.partials.table_anggota', [
                            'items' => $data['invitations'],
                            'type' => 'invitation',
                        ])
                    </div>

                    {{-- TAB 2: MENUNGGU TIM --}}
                    <div class="tab-pane fade" id="tunggu-pane">
                        @include('dosen.partials.table_anggota', [
                            'items' => $data['waiting'],
                            'type' => 'waiting',
                        ])
                    </div>

                    {{-- TAB 3: PROSES REVIEW --}}
                    <div class="tab-pane fade" id="proses-pane">
                        @include('dosen.partials.table_anggota', [
                            'items' => $data['process'],
                            'type' => 'process',
                        ])
                    </div>

                    {{-- TAB 4: SELESAI --}}
                    <div class="tab-pane fade" id="selesai-pane">
                        @include('dosen.partials.table_anggota', [
                            'items' => $data['finished'],
                            'type' => 'finished',
                        ])
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- MODALS KONFIRMASI (Hanya untuk Undangan Masuk) --}}
    @foreach ($data['invitations'] as $item)
        @include('dosen.partials.modal_konfirmasi', ['item' => $item])
    @endforeach

@endsection

@push('scripts')
    <script>
        function changeYear(year) {
            const activeTabElement = document.querySelector('#anggotaTab .nav-link.active');
            let currentTab = activeTabElement ? activeTabElement.id.replace('-tab', '') : 'menunggu';
            const url = new URL(window.location.href);
            url.searchParams.set('year', year);
            url.searchParams.set('tab', currentTab);
            window.location.href = url.toString();
        }

        function setActiveTab(tabName) {
            const url = new URL(window.location);
            url.searchParams.set('tab', tabName);
            window.history.pushState({}, '', url);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab') || 'menunggu';
            const triggerEl = document.querySelector(`#${activeTab}-tab`);
            if (triggerEl) new bootstrap.Tab(triggerEl).show();
        });
    </script>
@endpush

@push('styles')
    <style>
        #anggotaTab .nav-item .nav-link {
            color: #555 !important;
            background-color: #f8f9fa;
            border-bottom: none;
            margin-right: 2px;
        }

        #anggotaTab .nav-item .nav-link.active {
            color: #378a75 !important;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
            font-weight: 600;
        }

        .status-menunggu {
            color: #664d03;
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 0.5em 0.75em;
            font-size: 0.75rem;
        }

        .status-selesai {
            color: #0f5132;
            background-color: #d1e7dd;
            border: 1px solid #198754;
            padding: 0.5em 0.75em;
            font-size: 0.75rem;
        }
    </style>
@endpush
