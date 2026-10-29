@extends('layouts.app')

@section('title', $pageTitle)

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <a href="{{ route('dekan.dashboard') }}" class="btn btn-secondary btn-action-control shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="d-flex gap-2">
                @include('shared.toolbar_filter')
            </div>
        </div>

        <div class="mb-4 ps-1">
            <h2 class="fw-bold text-primary">{{ $pageTitle }}</h2>
            <p class="text-muted mb-0">
                @if (in_array($kategori, ['total_usulan', 'disetujui', 'ditolak', 'menunggu']))
                    Daftar proposal skala Prodi yang masuk ke fakultas Anda.
                @else
                    Daftar luaran dari proposal skala Prodi yang telah disetujui.
                @endif
            </p>
        </div>

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 hover-table">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary" style="width: 50px;">No</th>

                                @if (in_array($kategori, ['total_usulan', 'disetujui', 'ditolak', 'menunggu']))
                                    <th class="py-3 text-secondary">Judul & Pengusul</th>
                                    <th class="py-3 text-secondary">Skema & Skala</th>
                                    <th class="text-center py-3 text-secondary">Tahun</th>
                                    <th class="text-end py-3 text-secondary">Total Dana</th>
                                    <th class="text-center py-3 text-secondary">Status</th>
                                    <th class="text-center py-3 text-secondary">Detail</th>
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
                                @if (in_array($kategori, ['total_usulan', 'disetujui', 'ditolak', 'menunggu']))
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

                                        <td class="py-3 text-muted small fw-semibold">{{ $item->skema_label }}</td>

                                        <td class="text-center py-3 fw-bold">{{ $item->tahun_pelaksanaan }}</td>

                                        <td class="text-end py-3 pe-4 fw-bold text-success font-monospace">
                                            Rp {{ number_format($item->total_dana, 0, ',', '.') }}
                                        </td>

                                        <td class="text-center py-3">
                                            <span
                                                class="badge bg-{{ $item->status_color }} bg-opacity-10 text-{{ $item->status_color }} rounded-pill px-3 border border-{{ $item->status_color }} border-opacity-25">
                                                {{ $item->status_label }}
                                            </span>
                                        </td>

                                        <td class="text-center py-3">
                                            <a href="{{ route('dekan.monitoring.detail', $item->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm fw-bold">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
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

                                        <td class="py-3 text-muted small fw-semibold">
                                            {{ $item->proposal->skema_label ?? '-' }}</td>

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
                                            <a href="{{ route('dekan.monitoring.detail', $item->proposal_id) }}"
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
                <div class="card-footer bg-white py-3 border-top-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted small">
                            Menampilkan <strong>{{ $items->firstItem() }}</strong> -
                            <strong>{{ $items->lastItem() }}</strong> dari <strong>{{ $items->total() }}</strong>
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center">
                                <input type="hidden" name="search" value="{{ $search ?? '' }}">
                                <input type="hidden" name="year" value="{{ $selectedYear }}">

                                <select name="per_page" class="form-select form-select-sm border-secondary text-secondary"
                                    style="width: auto;" onchange="this.form.submit()">
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
