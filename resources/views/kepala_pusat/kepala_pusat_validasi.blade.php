@extends('layouts.app')

@section('title', 'Validasi Kepala Pusat')

@section('content')
    <div class="container">
        @include('shared.alert_script')

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <a href="{{ route('kepala_pusat.dashboard') }}" class="btn btn-secondary btn-action-control shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
            </div>
            <div class="d-flex gap-2">
                @include('shared.toolbar_filter')
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0 fw-bold text-primary">Validasi Proposal Pusat</h1>
                <p class="text-muted">Validasi proposal yang masuk ke Pusat Studi Anda.</p>
            </div>
        </div>

        <ul class="nav nav-tabs" id="validasiTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="menunggu-tab" data-bs-toggle="tab" data-bs-target="#menunggu-pane"
                    type="button" onclick="setActiveTab('menunggu')">
                    Menunggu Persetujuan <span
                        class="badge bg-warning text-dark ms-1">{{ $data['menunggu']->total() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="disetujui-tab" data-bs-toggle="tab" data-bs-target="#disetujui-pane"
                    type="button" onclick="setActiveTab('disetujui')">
                    Disetujui <span class="badge bg-success ms-1">{{ $data['disetujui']->total() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="ditolak-tab" data-bs-toggle="tab" data-bs-target="#ditolak-pane" type="button"
                    onclick="setActiveTab('ditolak')">
                    Ditolak <span class="badge bg-danger ms-1">{{ $data['ditolak']->total() }}</span>
                </button>
            </li>
        </ul>

        <div class="card shadow-sm border-0 mb-5" style="border-top-left-radius: 0; border-top-right-radius: 0;">
            <div class="card-body p-0">
                <div class="tab-content" id="validasiTabContent">

                    <div class="tab-pane fade show active" id="menunggu-pane">
                        @include('kepala_pusat.partials.table_validasi', [
                            'items' => $data['menunggu'],
                            'type' => 'menunggu',
                        ])
                    </div>

                    <div class="tab-pane fade" id="disetujui-pane">
                        @include('kepala_pusat.partials.table_validasi', [
                            'items' => $data['disetujui'],
                            'type' => 'disetujui',
                        ])
                    </div>

                    <div class="tab-pane fade" id="ditolak-pane">
                        @include('kepala_pusat.partials.table_validasi', [
                            'items' => $data['ditolak'],
                            'type' => 'ditolak',
                        ])
                    </div>

                </div>
            </div>
        </div>
    </div>

    @foreach ($data['menunggu'] as $proposal)
        @include('kepala_pusat.partials.modal_action', ['proposal' => $proposal])
    @endforeach

@endsection

@push('scripts')
    <script>
        function changeYear(year) {
            const url = new URL(window.location.href);
            url.searchParams.set('year', year);

            url.searchParams.delete('page_m');
            url.searchParams.delete('page_d');
            url.searchParams.delete('page_t');

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
            if (triggerEl) {
                new bootstrap.Tab(triggerEl).show();
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        #validasiTab .nav-item .nav-link {
            color: #555 !important;
            background-color: #f8f9fa;
            border-bottom: none;
            margin-right: 2px;
        }

        #validasiTab .nav-item .nav-link.active {
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
            border: 1px solid #badbcc;
            padding: 0.5em 0.75em;
            font-size: 0.75rem;
        }
    </style>
@endpush
