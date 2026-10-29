{{-- 1. MODAL VIEW ARTIKEL --}}
<div class="modal fade" id="modalListArtikel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-journal-text me-2"></i>Daftar Artikel Ilmiah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    @forelse($lampiran->where('kategori', 'artikel') as $item)
                        <a href="{{ \Storage::url($item->file_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold small">{{ $item->judul }}</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</small>
                            </div>
                            <i class="bi bi-download text-success"></i>
                        </a>
                    @empty
                        <div class="text-center py-4 bg-light rounded border border-dashed">
                            <i class="bi bi-folder2-open text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted small mb-0 mt-2">Belum ada Artikel yang diunggah.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 2. MODAL VIEW SERTIFIKAT --}}
<div class="modal fade" id="modalListBuku" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-award me-2"></i>Daftar Sertifikat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    @forelse($lampiran->where('kategori', 'sertifikat') as $item)
                        <a href="{{ \Storage::url($item->file_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold small">{{ $item->judul }}</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</small>
                            </div>
                            <i class="bi bi-download text-success"></i>
                        </a>
                    @empty
                        <div class="text-center py-4 bg-light rounded border border-dashed">
                            <i class="bi bi-folder2-open text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted small mb-0 mt-2">Belum ada Sertifikat yang diunggah.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 3. MODAL VIEW HKI --}}
<div class="modal fade" id="modalListHKI" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-patch-check me-2"></i>Daftar HKI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    @forelse($lampiran->where('kategori', 'hki') as $item)
                        <a href="{{ \Storage::url($item->file_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold small">{{ $item->judul }}</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</small>
                            </div>
                            <i class="bi bi-download text-success"></i>
                        </a>
                    @empty
                        <div class="text-center py-4 bg-light rounded border border-dashed">
                            <i class="bi bi-folder2-open text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted small mb-0 mt-2">Belum ada HKI yang diunggah.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>