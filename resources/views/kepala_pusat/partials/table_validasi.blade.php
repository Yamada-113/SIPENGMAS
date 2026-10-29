@if ($items->isEmpty())
    <div class="d-flex flex-column align-items-center justify-content-center py-5">
        <img src="{{ asset('images/empty.png') }}" alt="Kosong" style="width: 150px; opacity: 0.5">
        <h6 class="text-muted mt-3">Tidak ada proposal dalam kategori ini.</h6>
    </div>
@else
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 py-3 text-secondary" style="width: 50px;">No</th>
                    <th class="py-3 text-secondary">Judul & Pengusul</th>
                    <th class="text-center py-3 text-secondary">Tahun</th>
                    @if ($type == 'ditolak')
                        <th class="py-3 text-secondary">Alasan Penolakan</th>
                    @else
                        <th class="text-center py-3 text-secondary">Dana</th>
                        <th class="text-center py-3 text-secondary">Status</th>
                        @if ($type == 'menunggu')
                            <th class="text-center py-3 text-secondary" style="width: 180px;">Aksi</th>
                        @endif
                    @endif
                    <th class="text-center py-3 text-secondary" style="width: 100px;">Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index => $prop)
                    <tr class="border-bottom">
                        <td class="ps-4 py-3 text-muted">{{ $items->firstItem() + $index }}</td>
                        <td class="py-3" style="max-width: 350px;">
                            <span class="fw-bold text-dark d-block text-truncate"
                                title="{{ $prop->identitas->judul ?? '' }}">
                                {{ $prop->identitas->judul ?? 'Tanpa Judul' }}
                            </span>
                            <div class="small text-muted mt-1 d-flex align-items-center">
                                <i class="bi bi-person-circle me-1"></i>
                                <span class="ms-1">{{ $prop->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="text-center py-3 fw-bold">{{ $prop->tahun_pelaksanaan }}</td>
                        @if ($type == 'ditolak')
                            <td class="py-3 small text-muted text-truncate" style="max-width: 250px;">
                                {{ $prop->feedback ?? '-' }}
                            </td>
                        @else
                            <td class="text-center py-3 fw-bold text-success font-monospace">
                                Rp {{ number_format($prop->total_dana ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="text-center py-3">
                                @if ($type == 'menunggu')
                                    <span class="badge status-menunggu rounded-pill px-3">Perlu Validasi</span>
                                @elseif($type == 'disetujui')
                                    <span class="badge status-selesai rounded-pill px-3">Disetujui</span>
                                @endif
                            </td>
                            @if ($type == 'menunggu')
                                <td class="text-center py-3">
                                    <div class="d-flex justify-content-center gap-1">
                                        {{-- Tombol Setuju (Modal di file utama) --}}
                                        <button class="btn btn-sm btn-success rounded-pill px-3 shadow-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalApprove{{ $prop->id }}">Setuju</button>

                                        {{-- Tombol Tolak (Modal di file utama) --}}
                                        <button class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalReject{{ $prop->id }}">Tolak</button>
                                    </div>
                                </td>
                            @endif
                        @endif
                        <td class="text-center py-3">
                            <a href="{{ route('kepala_pusat.validasi.detail', $prop->id) }}"
                                class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                Detail
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3 px-3">
        {{ $items->links('pagination::bootstrap-5') }}
    </div>
@endif
