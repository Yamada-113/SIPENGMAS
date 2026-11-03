@extends('layouts.app')

@section('title', $pageTitle)

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <div>
                <a href="{{ route('kepala_pusat.dashboard') }}" class="btn btn-secondary btn-action-control shadow-sm">
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
                @if (in_array($kategori, ['total_usulan', 'proposal_disetujui']))
                    Daftar seluruh proposal yang masuk ke pusat Anda.
                @else
                    Daftar luaran dari proposal final (Didanai).
                @endif
            </p>
        </div>

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary" style="width: 50px;">No</th>

                                @if (in_array($kategori, ['total_usulan', 'proposal_disetujui']))
                                    <th class="py-3 text-secondary" style="width: 25%;">Identitas Proposal</th>
                                    <th class="text-center py-3 text-secondary">Tahun</th>
                                    <th class="py-3 text-secondary" style="width: 20%;">Skema & Skala</th>
                                    <th class="text-center py-3 text-secondary">Status</th>
                                    <th class="text-end py-3 text-secondary">Total Dana</th>
                                    <th class="text-center py-3 text-secondary">Aksi</th>
                                @else
                                    <th class="py-3 text-secondary" style="width: 25%;">Judul Proposal</th>
                                    <th class="py-3 text-secondary">Jenis Skema</th>
                                    <th class="text-center py-3 text-secondary">Tahun</th>
                                    <th class="py-3 text-secondary" style="width: 20%;">Judul Luaran</th>
                                    <th class="text-center py-3 text-secondary">Aksi</th>
                                    <th class="text-center py-3 text-secondary">Detail</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $index => $item)
                                @if (in_array($kategori, ['total_usulan', 'proposal_disetujui']))
                                    <tr class="border-bottom">
                                        <td class="ps-4 py-3 text-muted">{{ $items->firstItem() + $index }}</td>

                                        <td class="py-3">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark mb-1 text-wrap" style="line-height: 1.4;">
                                                    {{ $item->identitas->judul ?? '-' }}
                                                </span>
                                                <small class="text-muted">
                                                    <i class="bi bi-person-circle me-1"></i>
                                                    {{ $item->user->name ?? 'Pengusul' }}
                                                </small>
                                            </div>
                                        </td>

                                        <td class="text-center py-3 fw-bold text-secondary">
                                            {{ $item->tahun_pelaksanaan }}
                                        </td>

                                        <td class="py-3">
                                            <span class="fw-bold text-dark small">{{ $item->skemaRef->nama ?? '-' }}</span>
                                        </td>

                                        <td class="py-3 text-center">
                                            <span
                                                class="badge bg-{{ $item->status_color }} bg-opacity-10 text-{{ $item->status_color }} border border-{{ $item->status_color }} rounded-pill px-3">
                                                {{ $item->status_label }}
                                            </span>
                                        </td>

                                        <td class="text-end py-3 pe-4">
                                            <span class="fw-bold text-success" style="font-family: monospace;">
                                                Rp {{ number_format($item->total_dana, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        <td class="text-center py-3">
                                            <a href="{{ route('kepala_pusat.validasi.detail', $item->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold shadow-sm">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @else
                                    <tr class="border-bottom">
                                        <td class="ps-4 py-3 text-muted">{{ $items->firstItem() + $index }}</td>

                                        <td class="py-3">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark mb-1 text-truncate" style="max-width: 250px;"
                                                    title="{{ $item->proposal->identitas->judul ?? '' }}">
                                                    {{ \Illuminate\Support\Str::limit($item->proposal->identitas->judul ?? '-', 50, '...') }}
                                                </span>
                                                <small class="text-muted">
                                                    <i class="bi bi-person-circle me-1"></i>
                                                    {{ $item->proposal->user->name ?? '-' }}
                                                </small>
                                            </div>
                                        </td>

                                        <td class="py-3 text-dark small fw-bold">
                                            {{ $item->proposal->skema_label ?? '-' }}
                                        </td>

                                        <td class="text-center py-3 fw-bold text-secondary">
                                            {{ $item->proposal->tahun_pelaksanaan ?? '-' }}
                                        </td>

                                        <td class="py-3 fw-bold text-primary" style="color: #378a75 !important;">
                                            {{ $item->judul }}
                                        </td>

                                        <td class="text-center py-3">
                                            <a href="{{ \Storage::url($item->file_path ?? '#') }}" target="_blank"
                                                class="btn btn-sm btn-outline-success fw-bold rounded-pill px-3">
                                                <i class="bi bi-download me-1"></i> Unduh
                                            </a>
                                        </td>

                                        <td class="text-center py-3">
                                            <a href="{{ route('kepala_pusat.validasi.detail', $item->proposal_id) }}"
                                                class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm text-white"
                                                style="background-color: #378a75; border-color: #378a75;">
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
                            <strong>{{ $items->lastItem() }}</strong> dari <strong>{{ $items->total() }}</strong> data
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
                            <div>
                                {{ $items->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
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
