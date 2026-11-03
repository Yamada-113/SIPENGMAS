{{-- 1. MODAL UPLOAD DOKUMEN UMUM --}}
<div class="modal fade" id="modaldokumen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Unggah Dokumen Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dosen.lampiran.store', $detailProposal->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kategori" value="dokumen">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Dokumen</label>
                        <input type="text" class="form-control" name="judul" placeholder="Masukkan judul dokumen" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">File Dokumen (PDF/JPG)</label>
                        <input class="form-control" type="file" name="file_upload" accept=".pdf,.jpg,.jpeg,.png" required>
                        <div class="form-text">Maksimal ukuran file: 5MB</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success text-white">Simpan Dokumen</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 2. MODAL LIST + UPLOAD ARTIKEL --}}
<div class="modal fade" id="modalListArtikel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-journal-text me-2"></i>Daftar Artikel Ilmiah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group mb-3">
                    @forelse($lampiran->where('kategori', 'artikel') as $item)
                        <a href="{{ \Storage::url($item->file_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold small">{{ $item->judul }}</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</small>
                            </div>
                            <i class="bi bi-box-arrow-up-right text-primary"></i>
                        </a>
                    @empty
                        <div class="text-center py-3 border rounded bg-light">
                            <small class="text-muted">Belum ada Artikel.</small>
                        </div>
                    @endforelse
                </div>
                <div class="d-grid">
                    <button class="btn btn-outline-primary dashed-border" data-bs-toggle="modal" data-bs-target="#modalArtikel">
                        <i class="bi bi-plus-circle me-2"></i> Tambah Artikel Baru
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FORM UPLOAD ARTIKEL --}}
<div class="modal fade" id="modalArtikel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Unggah Artikel Baru</h5>
                <button type="button" class="btn-close" data-bs-target="#modalListArtikel" data-bs-toggle="modal"></button>
            </div>
            <form action="{{ route('dosen.lampiran.store', $detailProposal->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kategori" value="artikel">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Artikel</label>
                        <input type="text" class="form-control" name="judul" placeholder="Masukkan judul artikel" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">File Artikel (PDF)</label>
                        <input class="form-control" type="file" name="file_upload" accept=".pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-target="#modalListArtikel" data-bs-toggle="modal">Batal</button>
                    <button type="submit" class="btn btn-success text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 3. MODAL LIST + UPLOAD SERTIFIKAT --}}
<div class="modal fade" id="modalListBuku" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-award me-2"></i>Daftar Sertifikat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group mb-3">
                    @forelse($lampiran->where('kategori', 'sertifikat') as $item)
                        <a href="{{ \Storage::url($item->file_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold small">{{ $item->judul }}</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</small>
                            </div>
                            <i class="bi bi-box-arrow-up-right text-primary"></i>
                        </a>
                    @empty
                        <div class="text-center py-3 border rounded bg-light">
                            <small class="text-muted">Belum ada Sertifikat.</small>
                        </div>
                    @endforelse
                </div>
                <div class="d-grid">
                    <button class="btn btn-outline-primary dashed-border" data-bs-toggle="modal" data-bs-target="#modalBuku">
                        <i class="bi bi-plus-circle me-2"></i> Tambah Sertifikat
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FORM UPLOAD SERTIFIKAT --}}
<div class="modal fade" id="modalBuku" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Unggah Sertifikat</h5>
                <button type="button" class="btn-close" data-bs-target="#modalListBuku" data-bs-toggle="modal"></button>
            </div>
            <form action="{{ route('dosen.lampiran.store', $detailProposal->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kategori" value="sertifikat">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Sertifikat</label>
                        <input type="text" class="form-control" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">File Sertifikat (PDF)</label>
                        <input class="form-control" type="file" name="file_upload" accept=".pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-target="#modalListBuku" data-bs-toggle="modal">Batal</button>
                    <button type="submit" class="btn btn-success text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 4. MODAL LIST + UPLOAD HKI --}}
<div class="modal fade" id="modalListHKI" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-patch-check me-2"></i>Daftar HKI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group mb-3">
                    @forelse($lampiran->where('kategori', 'hki') as $item)
                        <a href="{{ \Storage::url($item->file_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-bold small">{{ $item->judul }}</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</small>
                            </div>
                            <i class="bi bi-box-arrow-up-right text-primary"></i>
                        </a>
                    @empty
                        <div class="text-center py-3 border rounded bg-light">
                            <small class="text-muted">Belum ada HKI.</small>
                        </div>
                    @endforelse
                </div>
                <div class="d-grid">
                    <button class="btn btn-outline-primary dashed-border" data-bs-toggle="modal" data-bs-target="#modalHKI">
                        <i class="bi bi-plus-circle me-2"></i> Tambah HKI
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- FORM UPLOAD HKI --}}
<div class="modal fade" id="modalHKI" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Unggah HKI Baru</h5>
                <button type="button" class="btn-close" data-bs-target="#modalListHKI" data-bs-toggle="modal"></button>
            </div>
            <form action="{{ route('dosen.lampiran.store', $detailProposal->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kategori" value="hki">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Ciptaan</label>
                        <input type="text" class="form-control" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">File HKI (PDF/JPG)</label>
                        <input class="form-control" type="file" name="file_upload" accept=".pdf, .png, image/png, application/pdf" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-target="#modalListHKI" data-bs-toggle="modal">Batal</button>
                    <button type="submit" class="btn btn-success text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>