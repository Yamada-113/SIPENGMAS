@if ($items->isEmpty())
    <div class="d-flex flex-column align-items-center justify-content-center py-5">
        <img src="{{ asset('images/empty.png') }}" alt="Kosong" style="width: 150px; opacity: 0.5">
        <h6 class="text-muted mt-3">Tidak ada proposal pada tab ini.</h6>
    </div>
@else
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    {{-- KOLOM 1: NO --}}
                    <th class="ps-4 py-3 text-secondary" style="width: 50px;">No</th>

                    {{-- KOLOM 2: JUDUL & PENGUSUL (Digabung) --}}
                    <th class="py-3 text-secondary">Judul & Pengusul</th>

                    {{-- KOLOM 3: SKEMA & TAHUN (Digabung) --}}
                    <th class="py-3 text-secondary">Skema & Tahun</th>

                    {{-- KOLOM 4: DANA --}}
                    <th class="text-end py-3 text-secondary pe-4">Pengajuan Dana</th>

                    {{-- KOLOM 5: AKSI / STATUS --}}
                    @if ($type == 'menunggu')
                        <th class="text-center py-3 text-secondary" style="width: 200px;">Aksi</th>
                    @else
                        <th class="text-center py-3 text-secondary">Status Proposal</th>
                    @endif

                    {{-- KOLOM 6: DETAIL --}}
                    <th class="text-center py-3 text-secondary" style="width: 100px;">Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index => $prop)
                    <tr class="border-bottom">
                        {{-- NO --}}
                        <td class="ps-4 py-3 text-muted">{{ $items->firstItem() + $index }}</td>

                        {{-- JUDUL & PENGUSUL --}}
                        <td class="py-3" style="max-width: 350px;">
                            <span class="fw-bold text-dark d-block text-truncate"
                                title="{{ $prop->identitas->judul ?? '' }}">
                                {{ $prop->identitas->judul ?? 'Tanpa Judul' }}
                            </span>
                            <div class="small text-muted mt-1 d-flex align-items-center">
                                <i class="bi bi-person-circle me-1"></i>
                                <span class="ms-1">{{ $prop->user->name ?? 'Pengusul' }}</span>
                            </div>
                        </td>

                        {{-- SKEMA & TAHUN --}}
                        <td class="py-3">
                            <span class="d-block text-dark fw-bold small">
                                {{ $prop->skemaRef->nama ?? '-' }}
                            </span>
                            <span class="text-muted small">Tahun: {{ $prop->tahun_pelaksanaan }}</span>
                        </td>

                        {{-- PENGAJUAN DANA --}}
                        <td class="text-end py-3 pe-4 fw-bold text-success font-monospace">
                            Rp {{ number_format($prop->total_dana, 0, ',', '.') }}
                        </td>

                        {{-- AKSI (MENUNGGU) --}}
                        @if ($type == 'menunggu')
                            <td class="text-center py-3">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Tombol Terima --}}
                                    {{-- ID Modal HARUS #modalApprove agar sesuai dengan modal_action.blade.php --}}
                                    <button class="btn btn-sm btn-success rounded-pill px-3 shadow-sm fw-bold"
                                        data-bs-toggle="modal" data-bs-target="#modalApprove{{ $prop->id }}">
                                        Terima
                                    </button>

                                    {{-- Tombol Tolak --}}
                                    {{-- ID Modal HARUS #modalReject agar sesuai dengan modal_action.blade.php --}}
                                    <button class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm fw-bold"
                                        data-bs-toggle="modal" data-bs-target="#modalReject{{ $prop->id }}">
                                        Tolak
                                    </button>
                                </div>
                            </td>
                            {{-- STATUS (SELESAI/DITOLAK) --}}
                        @else
                            <td class="text-center py-3">
                                <span
                                    class="badge bg-{{ $prop->status_color }} rounded-pill px-3 border border-{{ $prop->status_color }} bg-opacity-10 text-{{ $prop->status_color }}">
                                    {{ $prop->status_label }}
                                </span>
                            </td>
                        @endif

                        {{-- TOMBOL DETAIL --}}
                        <td class="text-center py-3">
                            {{-- Route diarahkan ke Wakil Rektor --}}
                            <a href="{{ route('wakil_rektor.validasi.detail', $prop->id) }}"
                                class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm fw-bold">
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
