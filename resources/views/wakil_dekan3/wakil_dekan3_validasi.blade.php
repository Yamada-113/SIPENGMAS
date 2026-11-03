@extends('layouts.app')

@section('title', 'Validasi Proposal (Wadek 3)')

@section('content')
    <div class="container">
        @include('shared.alert_script')

        {{-- Toolbar --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <a href="{{ route('wakil_dekan3.dashboard') }}" class="btn btn-secondary btn-action-control shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Dashboard
                </a>
            </div>
            <div class="d-flex gap-2">
                @include('shared.toolbar_filter')
            </div>
        </div>

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0 fw-bold text-primary">Validasi Proposal Fakultas</h1>
                <p class="text-muted">Kelola validasi usulan proposal dari dosen di lingkungan fakultas.</p>
            </div>
        </div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs" id="validasiTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="menunggu-tab" data-bs-toggle="tab" data-bs-target="#menunggu-pane"
                    type="button" onclick="setActiveTab('menunggu')">
                    Menunggu Validasi <span class="badge bg-warning text-dark ms-1">{{ $data['menunggu']->total() }}</span>
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

        {{-- Content Table --}}
        <div class="card shadow-sm border-0 mb-5" style="border-top-left-radius: 0; border-top-right-radius: 0;">
            <div class="card-body p-0">
                <div class="tab-content" id="validasiTabContent">
                    <div class="tab-pane fade show active" id="menunggu-pane">
                        @include('wakil_dekan3.partials.table_validasi', [
                            'items' => $data['menunggu'],
                            'type' => 'menunggu',
                        ])
                    </div>
                    <div class="tab-pane fade" id="disetujui-pane">
                        @include('wakil_dekan3.partials.table_validasi', [
                            'items' => $data['disetujui'],
                            'type' => 'disetujui',
                        ])
                    </div>
                    <div class="tab-pane fade" id="ditolak-pane">
                        @include('wakil_dekan3.partials.table_validasi', [
                            'items' => $data['ditolak'],
                            'type' => 'ditolak',
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ACTION LOOP --}}
    @foreach ($data['menunggu'] as $item)
        {{-- 
            PERBAIKAN DISINI: 
            Ubah 'proposal' => $item MENJADI 'prop' => $item 
            agar sesuai dengan variabel $prop di dalam modal_action.blade.php
        --}}
        @include('wakil_dekan3.partials.modal_action', ['prop' => $item])
    @endforeach

@endsection

@push('scripts')
    <script>
        function changeYear(year) {
            const url = new URL(window.location.href);
            url.searchParams.set('year', year);
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
    </style>
@endpush
