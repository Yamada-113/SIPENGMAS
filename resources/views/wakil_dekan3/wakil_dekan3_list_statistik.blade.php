@extends('layouts.app')

@section('title', $pageTitle)

@section('content')
    <div class="container">
        {{-- 1. TOOLBAR --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <a href="{{ route('wakil_dekan3.dashboard') }}" class="btn btn-secondary btn-action-control shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="d-flex gap-2">
                @include('shared.toolbar_filter')
            </div>
        </div>

        {{-- 2. HEADER --}}
        <div class="mb-4 ps-1">
            <h2 class="fw-bold text-primary">{{ $pageTitle }}</h2>
            <p class="text-muted mb-0">
                @if (in_array($kategori, ['total_usulan', 'proposal_disetujui']))
                    Daftar proposal yang masuk ke fakultas Anda.
                @else
                    Daftar luaran dari proposal yang telah disetujui.
                @endif
            </p>
        </div>

        {{-- 3. TABEL DATA --}}
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary" style="width: 50px;">No</th>

                                {{-- HEADER PROPOSAL --}}
                                @if (in_array($kategori, ['total_usulan', 'proposal_disetujui']))
                                    <th class="py-3 text-secondary">Judul & Pengusul</th>
                                    <th class="py-3 text-secondary">Skema & Skala</th>
                                    <th class="text-center py-3 text-secondary">Tahun</th>
                                    <th class="text-end py-3 text-secondary">Pengajuan Dana</th>
                                    <th class="text-center py-3 text-secondary">Status</th>
                                    <th class="text-center py-3 text-secondary">Detail</th>

                                    {{-- HEADER LUARAN --}}
                                @else
                                    <th class="py-3 text-secondary">Judul Proposal</th>
                                    <th class="py-3 text-secondary">Jenis Skema</th>
                                    <th class="text-center py-3 text-secondary">Tahun</th>
                                    <th class="py-3 text-secondary">Judul Luaran</th>
                                    <th class="text-center py-3 text-secondary">File</th>
                                    <th class="text-center py-3 text-secondary">Detail</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $index => $item)
                                {{-- ISI PROPOSAL --}}
                                @if (in_array($kategori, ['total_usulan', 'proposal_disetujui']))
                                    <tr class="border-bottom">
                                        <td class="ps-4 py-3 text-muted">{{ $items->firstItem() + $index }}</td>

                                        <td class="py-3" style="max-width: 350px;">
                                            <span class="fw-bold text-dark d-block text-truncate"
                                                title="{{ $item->identitas->judul ?? '' }}">
                                                {{ $item->identitas->judul ?? 'Tanpa Judul' }}
                                            </span>
                                            <div class="small text-muted mt-1">
                                                <i class="bi bi-person-circle me-1"></i> {{ $item->user->name ?? '-' }}
                                            </div>
                                        </td>

                                        {{-- Accessor Skema --}}
                                        <td class="py-3 text-dark small fw-bold">{{ $item->skemaRef->nama ?? '-' }}</td>

                                        <td class="text-center py-3 fw-bold">{{ $item->tahun_pelaksanaan }}</td>

                                        {{-- Accessor Dana --}}
                                        <td class="text-end py-3 pe-4 fw-bold text-success font-monospace">
                                            Rp {{ number_format($item->total_dana, 0, ',', '.') }}
                                        </td>

                                        {{-- Accessor Status --}}
                                        <td class="text-center py-3">
                                            <span
                                                class="badge bg-{{ $item->status_color }} bg-opacity-10 text-{{ $item->status_color }} rounded-pill px-3 border border-{{ $item->status_color }}">
                                                {{ $item->status_label }}
                                            </span>
                                        </td>

                                        <td class="text-center py-3">
                                            <a href="{{ route('wakil_dekan3.validasi.detail', $item->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm fw-bold">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>

                                    {{-- ISI LUARAN --}}
                                @else
                                    <tr class="border-bottom">
                                        <td class="ps-4 py-3 text-muted">{{ $items->firstItem() + $index }}</td>

                                        <td class="py-3" style="max-width: 300px;">
                                            <span class="fw-bold text-dark d-block text-truncate"
                                                title="{{ $item->proposal->identitas->judul ?? '' }}">
                                                {{ $item->proposal->identitas->judul ?? '-' }}
                                            </span>
                                            <div class="small text-muted mt-1">
                                                <i class="bi bi-person-circle me-1"></i>
                                                {{ $item->proposal->user->name ?? '-' }}
                                            </div>
                                        </td>

                                        <td class="py-3 text-dark small fw-bold">
                                            {{ $item->proposal->skemaRef->nama ?? '-' }}</td>

                                        <td class="text-center py-3 fw-bold">
                                            {{ $item->proposal->tahun_pelaksanaan ?? '-' }}</td>

                                        <td class="py-3 text-primary fw-semibold" style="color: #378a75 !important;">
                                            {{ $item->judul }}
                                        </td>

                                        <td class="text-center py-3">
                                            <a href="{{ \Storage::url($item->file_path) }}" target="_blank"
                                                class="btn btn-sm btn-outline-success fw-bold">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </td>

                                        <td class="text-center py-3">
                                            <a href="{{ route('wakil_dekan3.validasi.detail', $item->proposal_id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endif

                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <i class="bi bi-folder-x display-4 text-muted mb-3 opacity-50"></i>
                                            <h6 class="text-muted fw-bold">Tidak ditemukan data</h6>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($items->total() > 0)
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted small">
                            Menampilkan <strong>{{ $items->firstItem() }}</strong> -
                            <strong>{{ $items->lastItem() }}</strong> dari <strong>{{ $items->total() }}</strong>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center me-2">
                                <input type="hidden" name="search" value="{{ $search ?? '' }}">
                                <input type="hidden" name="year" value="{{ $selectedYear }}">
                                <select name="per_page" class="form-select form-select-sm border-secondary text-secondary"
                                    style="width: 70px;" onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                </select>
                            </form>
                            <div>{{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function changeYear(year) {
            const url = new URL(window.location.href);
            url.searchParams.set('year', year);
            window.location.href = url.toString();
        }
    </script>
@endsection
