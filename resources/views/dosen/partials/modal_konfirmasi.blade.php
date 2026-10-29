{{-- Modal Terima --}}
<div class="modal fade" id="modalTerima{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white border-0" style="background-color: #378a75;">
                <h6 class="modal-title fw-bold">Konfirmasi Bergabung</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dosen.anggota.terima', $item->id) }}" method="POST">
                @csrf
                <div class="modal-body text-center p-4">
                    <div class="mb-3" style="color: #378a75;"><i class="bi bi-person-check-fill fs-1"></i></div>
                    <p class="mb-1 text-muted">Anda bersedia bergabung dalam proposal ini?</p>
                    <h6 class="fw-bold text-dark">{{ $item->proposal->identitas->judul ?? 'Proposal ini' }}</h6>
                    <div class="mt-3 p-2 bg-light rounded border">
                        <small class="text-muted d-block">Total Dana Diajukan:</small>
                        <span class="fw-bold text-success fs-5">Rp
                            {{ number_format($item->proposal->total_dana ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold">Ya, Saya Bersedia</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Tolak --}}
<div class="modal fade" id="modalTolak{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h6 class="modal-title fw-bold">Tolak Undangan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dosen.anggota.tolak', $item->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-center">
                    <div class="mb-3 text-danger"><i class="bi bi-x-circle fs-1"></i></div>
                    <p class="mb-1 text-muted">Anda yakin ingin menolak undangan ini?</p>
                    <h6 class="fw-bold text-dark mb-3">{{ $item->proposal->identitas->judul ?? 'Proposal ini' }}</h6>
                    <div class="alert alert-warning small text-start">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Jika Anda <b>menolak</b> proposal akan selesai dan pengusul harus mengajukan kembali.
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold px-4">Ya, Tolak Undangan</button>
                </div>
            </form>
        </div>
    </div>
</div>
