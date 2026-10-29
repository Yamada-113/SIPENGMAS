@extends('layouts.app')

@section('title', 'Rekapitulasi Proposal')

@section('content')
    <div class="container">
        @include('shared.alert_script')

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-action-control shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.rekap.export', ['year' => request('year', date('Y')), 'status' => request('tab', 'proses')]) }}"
                    class="btn btn-success text-white fw-bold shadow-sm d-flex align-items-center"
                    title="Download Rekap Excel">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i> Excel
                </a>

                @include('shared.toolbar_filter')
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0 fw-bold text-primary">Rekapitulasi Proposal</h1>
                <p class="text-muted">Daftar rekapitulasi seluruh proposal yang masuk dalam sistem.</p>
            </div>
        </div>

        <ul class="nav nav-tabs" id="rekapTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="proses-tab" data-bs-toggle="tab" data-bs-target="#proses-pane"
                    type="button" onclick="setActiveTab('proses')">
                    Proses <span class="badge bg-primary text-white ms-1">{{ $data['proses']->total() }}</span>
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="didanai-tab" data-bs-toggle="tab" data-bs-target="#didanai-pane" type="button"
                    onclick="setActiveTab('didanai')">
                    Didanai <span class="badge bg-success ms-1">{{ $data['didanai']->total() }}</span>
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
                <div class="tab-content" id="rekapTabContent">
                    <div class="tab-pane fade show active" id="proses-pane">
                        @include('admin.partials.table_rekap', [
                            'items' => $data['proses'],
                            'type' => 'proses',
                        ])
                    </div>

                    <div class="tab-pane fade" id="didanai-pane">
                        @include('admin.partials.table_rekap', [
                            'items' => $data['didanai'],
                            'type' => 'didanai',
                        ])
                    </div>

                    <div class="tab-pane fade" id="ditolak-pane">
                        @include('admin.partials.table_rekap', [
                            'items' => $data['ditolak'],
                            'type' => 'ditolak',
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function changeYear(year) {
            const activeTabElement = document.querySelector('#rekapTab .nav-link.active');
            let currentTab = 'proses';
            if (activeTabElement) {
                currentTab = activeTabElement.id.replace('-tab', '');
            }

            const url = new URL(window.location.href);
            url.searchParams.set('year', year);
            url.searchParams.delete('page_p');
            url.searchParams.delete('page_d');
            url.searchParams.delete('page_t');
            url.searchParams.set('tab', currentTab);

            window.location.href = url.toString();
        }

        function setActiveTab(tabName) {
            const url = new URL(window.location);
            url.searchParams.set('tab', tabName);

            const excelBtn = document.querySelector('a[title="Download Rekap Excel"]');
            if (excelBtn) {
                const excelUrl = new URL(excelBtn.href);
                excelUrl.searchParams.set('status', tabName);
                excelBtn.href = excelUrl.toString();
            }

            window.history.pushState({}, '', url);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab') || 'proses';

            const triggerEl = document.querySelector(`#${activeTab}-tab`);
            if (triggerEl) {
                new bootstrap.Tab(triggerEl).show();
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        #rekapTab .nav-item .nav-link {
            color: #555 !important;
            background-color: #f8f9fa;
            border-bottom: none;
            margin-right: 2px;
        }

        #rekapTab .nav-item .nav-link.active {
            color: #378a75 !important;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
            font-weight: 600;
        }

        .text-primary {
            color: #378a75 !important;
        }

        .btn-success {
            background-color: #378a75;
            border-color: #378a75;
        }

        .btn-success:hover {
            background-color: #2e7361;
            border-color: #2e7361;
        }
    </style>
@endpush
