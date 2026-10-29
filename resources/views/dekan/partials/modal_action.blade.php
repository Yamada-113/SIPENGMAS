{{-- Modal Setuju --}}
<div class="modal fade" id="modalSetuju{{ $prop->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white border-0" style="background-color: #378a75;">
                <h6 class="modal-title fw-bold">Konfirmasi Persetujuan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dekan.validasi.keputusan', $prop->id) }}" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body text-center p-4">
                    <div class="mb-3" style="color: #378a75;"><i class="bi bi-check-circle fs-1"></i></div>
                    <p class="mb-1 text-muted">Setujui proposal ini untuk lanjut ke Admin?</p>
                    <h6 class="fw-bold text-dark">{{ $prop->identitas->judul ?? 'Proposal ini' }}</h6>
                    <div class="mt-3 p-2 bg-light rounded border">
                        <small class="text-muted d-block">Total Dana Diajukan:</small>
                        <span class="fw-bold text-success fs-5">Rp
                            {{ number_format($prop->total_dana, 0, ',', '.') }}</span>
                    </div>
                    <input type="hidden" name="keputusan" value="terima">
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold">Ya, Setujui</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Tolak --}}
<div class="modal fade" id="modalTolak{{ $prop->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h6 class="modal-title fw-bold">Konfirmasi Penolakan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dekan.validasi.keputusan', $prop->id) }}" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="mb-3 text-danger"><i class="bi bi-x-circle fs-1"></i></div>
                        <p class="mb-1 text-muted">Anda yakin ingin menolak proposal ini?</p>
                        <h6 class="fw-bold text-dark">{{ $prop->identitas->judul ?? 'Proposal ini' }}</h6>
                    </div>
                    <div class="text-start">
                        <label for="feedback" class="form-label small fw-bold text-secondary">Alasan Penolakan <span
                                class="text-danger">*</span></label>
                        <textarea name="feedback" class="form-control" rows="4" placeholder="Jelaskan alasan..." required></textarea>
                    </div>
                    <input type="hidden" name="keputusan" value="tolak">
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold px-4">Kirim Penolakan</button>
                </div>
            </form>
        </div>
    </div>
</div>
