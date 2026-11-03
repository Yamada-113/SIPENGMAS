@extends('layouts.app')

@section('title', $pageTitle)

@section('content')
    <div class="container pb-5">

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fw-bold text-primary mb-1">{{ $pageTitle }}</h2>
                <p class="text-muted mb-0">
                    Menampilkan daftar data untuk kategori <strong>{{ ucfirst(str_replace('_', ' ', $kategori)) }}</strong>.
                </p>
            </div>

            <div class="d-flex gap-2">
                <form action="{{ url()->current() }}" method="GET" class="d-flex" style="max-width: 300px;">
                    <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" name="search"
                            placeholder="Cari judul/nama..." value="{{ $search ?? '' }}">
                        <button class="btn btn-success text-white fw-bold" type="submit">Cari</button>
                    </div>
                </form>
                <a href="{{ route('admin.dashboard') }}"
                    class="btn btn-secondary shadow-sm d-flex align-items-center fw-bold px-3">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary" style="width: 50px;">No</th>

                                @if (in_array($kategori, ['proposal_keseluruhan', 'proposal_didanai']))
                                    <th class="py-3 text-secondary" style="width: 30%;">Identitas Usulan</th>
                                    <th class="py-3 text-secondary" style="width: 20%;">Skema & Skala</th>
                                    <th class="text-center py-3 text-secondary">Tahun</th>
                                    <th class="text-end py-3 text-secondary">Total Dana</th>
                                    <th class="text-center py-3 text-secondary">Status</th>
                                    <th class="text-center py-3 text-secondary">Aksi</th>
                                @else
                                    <th class="py-3 text-secondary" style="width: 25%;">Judul Proposal & Pengusul</th>
                                    <th class="py-3 text-secondary">Jenis Skema</th>
                                    <th class="text-center py-3 text-secondary">Tahun</th>
                                    <th class="py-3 text-secondary" style="width: 25%;">
                                        @if ($kategori == 'sertifikat')
                                            Judul Sertifikat
                                        @elseif($kategori == 'hki')
                                            Judul HKI
                                        @else
                                            Judul Artikel
                                        @endif
                                    </th>
                                    <th class="text-center py-3 text-secondary">Aksi</th>
                                    <th class="text-center py-3 text-secondary">Detail</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $index => $item)
                                <tr class="border-bottom">
                                    <td class="ps-4 py-3 text-muted">{{ $items->firstItem() + $index }}</td>

                                    @if (in_array($kategori, ['proposal_keseluruhan', 'proposal_didanai']))
                                        <td class="py-3">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark mb-1 text-wrap" style="line-height: 1.4;">
                                                    {{ $item->identitas->judul ?? '-' }}
                                                </span>
                                                <div class="d-flex align-items-center text-muted small mt-1">
                                                    <i class="bi bi-person-circle me-2 text-secondary"></i>
                                                    <span>{{ $item->user->name ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="py-3 text-dark small fw-bold">
                                            {{ $item->skema_label }}
                                        </td>

                                        <td class="text-center py-3 fw-bold text-secondary">{{ $item->tahun_pelaksanaan }}
                                        </td>

                                        <td class="text-end py-3 pe-4">
                                            <span class="fw-bold text-success"
                                                style="font-family: monospace; font-size: 0.95rem;">
                                                Rp {{ number_format($item->total_dana, 0, ',', '.') }}
                                            </span>
                                        </td>

                                        <td class="text-center py-3">
                                            <span
                                                class="badge bg-{{ $item->status_color }} bg-opacity-10 text-{{ $item->status_color }} rounded-pill px-3 border border-{{ $item->status_color }} border-opacity-25">
                                                {{ $item->status_label }}
                                            </span>
                                        </td>

                                        <td class="text-center py-3">
                                            <a href="{{ route('admin.rekapitulasi.detail', $item->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm fw-bold">
                                                Detail
                                            </a>
                                        </td>
                                    @else
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

                                        <td class="text-center py-3 fw-bold">
                                            {{ $item->proposal->tahun_pelaksanaan ?? '-' }}</td>

                                        <td class="py-3 fw-bold text-primary" style="color: #378a75 !important;">
                                            {{ $item->judul }}
                                        </td>

                                        <td class="text-center py-3">
                                            <a href="{{ isset($item->file_path) ? \Storage::url($item->file_path) : '#' }}"
                                                target="_blank"
                                                class="btn btn-sm btn-outline-success fw-bold rounded-pill px-3">
                                                <i class="bi bi-download me-1"></i> Unduh
                                            </a>
                                        </td>

                                        <td class="text-center py-3">
                                            <a href="{{ route('admin.rekapitulasi.detail', $item->proposal_id) }}"
                                                class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm text-white"
                                                style="background-color: #378a75; border-color: #378a75;">
                                                Detail
                                            </a>
                                        </td>
                                    @endif
                                </tr>
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
                                <select name="per_page" class="form-select form-select-sm border-secondary text-secondary"
                                    style="width: 70px;" onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                </select>
                            </form>
                            <div class="pagination-clean">
                                {{ $items->appends(['per_page' => request('per_page'), 'search' => $search])->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .input-group-text {
            background-color: #fff;
            border-right: none;
        }

        .input-group .form-control {
            border-left: none;
        }

        .input-group .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }

        .btn-success {
            background-color: #378a75;
            border-color: #378a75;
        }

        .btn-success:hover {
            background-color: #2e7060;
            border-color: #2e7060;
        }

        .text-primary {
            color: #378a75 !important;
        }

        .pagination-clean nav>div:first-child,
        .pagination-clean nav>div:last-child>div:first-child {
            display: none !important;
        }

        .pagination-clean nav>div:last-child {
            justify-content: flex-end !important;
        }

        .pagination {
            margin-bottom: 0;
        }
    </style>
@endpush
