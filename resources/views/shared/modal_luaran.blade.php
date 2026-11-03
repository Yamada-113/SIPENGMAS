{{-- 1. MODAL LIST ARTIKEL --}}
<div class="modal fade" id="modalListArtikel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-journal-text me-2 text-danger"></i>Artikel Ilmiah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="list-group">
                    @forelse($lampiran->where('kategori', 'artikel') as $item)
                        <a href="{{ \Storage::url($item->file_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 fw-bold text-dark small">{{ $item->judul ?? 'Tanpa Judul' }}</h6>
                                <small class="text-muted">Diunggah: {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</small>
                            </div>
                            <i class="bi bi-download text-primary"></i>
                        </a>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-folder-x text-muted display-4"></i>
                            <p class="text-muted small mt-2">Belum ada artikel.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 2. MODAL LIST SERTIFIKAT (ID disamakan dengan button trigger: modalListBuku) --}}
<div class="modal fade" id="modalListBuku" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-book me-2 text-success"></i>Sertifikat Seminar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="list-group">
                    @forelse($lampiran->where('kategori', 'sertifikat') as $item)
                        <a href="{{ \Storage::url($item->file_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 fw-bold text-dark small">{{ $item->judul ?? 'Tanpa Judul' }}</h6>
                                <small class="text-muted">Diunggah: {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</small>
                            </div>
                            <i class="bi bi-download text-primary"></i>
                        </a>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-folder-x text-muted display-4"></i>
                            <p class="text-muted small mt-2">Belum ada sertifikat.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3. MODAL LIST HKI --}}
<div class="modal fade" id="modalListHKI" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-award me-2 text-warning"></i>Hak Kekayaan Intelektual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light">
                <div class="list-group">
                    @forelse($lampiran->where('kategori', 'hki') as $item)
                        <a href="{{ \Storage::url($item->file_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 fw-bold text-dark small">{{ $item->judul ?? 'Tanpa Judul' }}</h6>
                                <small class="text-muted">Diunggah: {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</small>
                            </div>
                            <i class="bi bi-download text-primary"></i>
                        </a>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-folder-x text-muted display-4"></i>
                            <p class="text-muted small mt-2">Belum ada HKI.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>