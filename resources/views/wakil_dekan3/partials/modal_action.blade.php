{{-- 
    FILE: resources/views/wakil_dekan3/partials/modal_action.blade.php
    TERIMA: $prop (Object)
    OPSIONAL: $dekanHasSignature (Boolean)
--}}

@php
    use App\Models\User;

    // --- LOGIKA PENENTUAN BLOCKING TANDA TANGAN ---

    // Default: Boleh Setuju (Aman)
    $isSignatureAvailable = true;
    $targetRoleLabel = '';
    $nextValidator = null;

    // 1. JIKA SKEMA PRODI -> WAJIB CEK TTD DEKAN
    if ($prop->skala_pelaksanaan == 'Prodi') {
        $targetRoleLabel = 'Dekan';
        $nextValidator = User::where('role', 'Dekan')->first();

        // Jika Dekan belum punya TTD, maka BLOKIR (False)
        if (!$nextValidator || empty($nextValidator->tanda_tangan)) {
            $isSignatureAvailable = false;
        }
    }
    // 2. JIKA SKEMA PUSAT -> LANGSUNG LOLOS (BYPASS)
    elseif ($prop->skala_pelaksanaan == 'Pusat') {
        $targetRoleLabel = 'Kepala Pusat';
        $isSignatureAvailable = true;
    }
@endphp

{{-- 1. MODAL TERIMA (APPROVE) --}}
<div class="modal fade" id="modalSetuju{{ $prop->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header border-0 bg-success text-white">
                <h6 class="modal-title fw-bold">Konfirmasi Persetujuan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center p-4">

                {{-- KONDISI 1: DEKAN SUDAH PUNYA TANDA TANGAN --}}
                @if ($isSignatureAvailable)
                    <div class="mb-3 text-success">
                        <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Setujui Proposal?</h5>
                    <p class="text-muted small">Proposal akan diteruskan ke tahap selanjutnya ({{ $targetRoleLabel }}).
                    </p>

                    {{-- Alert Info jika Prodi --}}
                    @if ($prop->skala_pelaksanaan == 'Prodi')
                        <div class="alert alert-success bg-success bg-opacity-10 border-0 small text-start">
                            <i class="bi bi-pen-fill me-1"></i>
                            <strong>Tanda Tangan Dekan Tersedia.</strong><br>
                            Lembar pengesahan akan otomatis digenerate.
                        </div>
                    @endif

                    {{-- Form Approval --}}
                    <form action="{{ route('wakil_dekan3.validasi.keputusan', $prop->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="keputusan" value="terima">
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-success fw-bold rounded-pill">
                                Ya, Validasi Sekarang
                            </button>
                        </div>
                    </form>
                    {{-- KONDISI 2: DEKAN BELUM PUNYA TANDA TANGAN --}}
                @else
                    <div class="mb-3 text-warning">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Tidak Dapat Memvalidasi</h5>
                    <p class="text-muted small mb-3">
                        Anda tidak dapat menyetujui proposal ini karena <strong>Dekan belum mengunggah tanda tangan
                            digital</strong>.
                    </p>

                    <div class="alert alert-danger bg-danger bg-opacity-10 border-0 small text-start mb-4">
                        <i class="bi bi-x-circle-fill me-1"></i>
                        Mohon ingatkan Dekan untuk melengkapi profilnya.
                    </div>

                    {{-- Tombol Lonceng (Kirim Notifikasi) --}}
                    <form action="{{ route('wakil_dekan3.notify.dekan') }}" method="POST">
                        @csrf
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-outline-danger fw-bold rounded-pill">
                                <i class="bi bi-bell-fill me-2"></i> Ingatkan Dekan (Notifikasi)
                            </button>
                            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">
                                Tutup
                            </button>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</div>

{{-- 2. MODAL TOLAK (REJECT) --}}
<div class="modal fade" id="modalTolak{{ $prop->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 bg-danger text-white">
                <h6 class="modal-title fw-bold">Konfirmasi Penolakan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('wakil_dekan3.validasi.keputusan', $prop->id) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="keputusan" value="tolak">

                <div class="modal-body p-4">
                    <div class="text-center mb-3">
                        <div class="text-danger mb-2">
                            <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="fw-bold">Tolak Proposal?</h5>
                        <p class="text-muted small">Proposal akan dikembalikan ke dosen untuk direvisi.</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alasan Penolakan <span
                                class="text-danger">*</span></label>
                        <textarea name="feedback" class="form-control bg-light" rows="4"
                            placeholder="Contoh: RAB tidak rasional, Metode tidak jelas..." required></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-danger fw-bold rounded-pill">
                            Kirim Penolakan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
