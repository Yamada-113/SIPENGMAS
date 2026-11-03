{{-- 
    FILE: resources/views/wakil_rektor3/partials/modal_action.blade.php
--}}

@php
    // Cek Tanda Tangan User Login (Wakil Rektor 3)
    $hasSignature = !empty(auth()->user()->tanda_tangan);
@endphp

{{-- MODAL APPROVE --}}
<div class="modal fade" id="modalApprove{{ $proposal->id }}" tabindex="-1" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            {{-- KONDISI A: SUDAH ADA TTD --}}
            @if ($hasSignature)
                <div class="modal-header bg-success text-white border-0">
                    <h6 class="modal-title fw-bold">Konfirmasi Pendanaan</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('wakil_rektor.validasi.keputusan', $proposal->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="keputusan" value="terima">

                    <div class="modal-body text-center p-4">
                        <div class="mb-3 text-success"><i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Setujui & Danai?</h5>
                        <p class="small text-muted mb-0">Judul:
                            <strong>{{ \Illuminate\Support\Str::limit($proposal->identitas->judul ?? '', 50) }}</strong>
                        </p>
                        <div
                            class="alert alert-success small mt-3 mb-0 py-2 border-0 bg-success bg-opacity-10 text-success">
                            <i class="bi bi-pen-fill me-1"></i> Tanda tangan Anda siap dibubuhkan.
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center border-0">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success fw-bold px-4">Ya, Setujui</button>
                    </div>
                </form>

                {{-- KONDISI B: BELUM ADA TTD (STYLE DISESUAIKAN DENGAN DOSEN) --}}
            @else
                <div class="modal-header bg-warning text-dark border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Tanda Tangan
                        Diperlukan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-3">
                        <i class="bi bi-pen text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <div class="alert alert-light border mb-3 small text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Anda belum memiliki tanda tangan digital. Mohon unggah sekarang agar dapat dibubuhkan pada
                        lembar pengesahan secara otomatis.
                    </div>

                    {{-- Form Upload (Menggunakan Class agar bisa banyak modal dalam satu halaman) --}}
                    <form class="formAjaxSignatureWR" enctype="multipart/form-data">
                        @csrf
                        <div class="card bg-light border-0 p-3 mb-3">
                            <label class="form-label fw-bold small text-muted">File Tanda Tangan</label>
                            <input type="file" class="form-control" name="tanda_tangan"
                                accept="image/png, image/jpeg, image/jpg" required>
                            <div class="form-text small text-muted">
                                <i class="bi bi-check-circle me-1"></i>Format: PNG/JPG (Max 2MB).<br>
                                <i class="bi bi-magic me-1"></i>Background putih akan otomatis dihapus.
                            </div>
                            <div class="ajax-error-msg text-danger small mt-2 d-none fw-bold"></div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold btn-save-upload">
                                <span class="btn-text">Simpan & Lanjutkan</span>
                                <span class="btn-loader d-none">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span> Memproses...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL REJECT --}}
<div class="modal fade" id="modalReject{{ $proposal->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h6 class="modal-title fw-bold">Tolak Proposal</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('wakil_rektor.validasi.keputusan', $proposal->id) }}" method="POST">
                @csrf @method('PATCH')
                <input type="hidden" name="keputusan" value="tolak">
                <div class="modal-body p-4">
                    <p class="fw-bold text-dark mb-2">
                        Judul: {{ \Illuminate\Support\Str::limit($proposal->identitas->judul ?? '', 50) }}
                    </p>
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-bold">Alasan Penolakan <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control bg-light" name="feedback" rows="4" required
                            placeholder="Tuliskan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger fw-bold px-4">Kirim Penolakan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT AJAX UPLOAD TTD (FIX CSRF & LOGIC) --}}
@push('scripts')
    @once
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const forms = document.querySelectorAll('.formAjaxSignatureWR');

                forms.forEach(form => {
                    form.addEventListener('submit', async function(e) {
                        e.preventDefault();

                        const fileInput = this.querySelector('input[type="file"]');
                        const errorMsg = this.querySelector('.ajax-error-msg');
                        const btn = this.querySelector('.btn-save-upload');
                        const btnText = this.querySelector('.btn-text');
                        const btnLoader = this.querySelector('.btn-loader');

                        // 1. Ambil CSRF Token DARI FORM (Lebih Aman)
                        // Kita ambil input hidden bernama _token yang digenerate oleh @csrf di dalam form ini
                        const csrfInput = this.querySelector('input[name="_token"]');
                        const csrfToken = csrfInput ? csrfInput.value : null;

                        if (!csrfToken) {
                            errorMsg.textContent =
                                "Error: CSRF Token tidak ditemukan dalam form. Refresh halaman.";
                            errorMsg.classList.remove('d-none');
                            return;
                        }

                        // 2. Validasi File
                        if (fileInput.files.length === 0) {
                            errorMsg.textContent = "Silakan pilih file tanda tangan.";
                            errorMsg.classList.remove('d-none');
                            return;
                        }

                        // 3. UI Loading State
                        btn.disabled = true;
                        btnText.classList.add('d-none');
                        btnLoader.classList.remove('d-none');
                        errorMsg.classList.add('d-none');

                        const formData = new FormData(this);

                        try {
                            const response = await fetch(
                                "{{ route('profile.upload_signature_ajax') }}", {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': csrfToken
                                    },
                                    body: formData
                                });

                            const result = await response.json();

                            if (response.ok && result.success) {
                                // Sukses -> Reload untuk memperbarui status TTD di tampilan
                                window.location.reload();
                            } else {
                                throw new Error(result.message || "Gagal mengunggah tanda tangan.");
                            }
                        } catch (error) {
                            console.error(error);
                            errorMsg.textContent = error.message;
                            errorMsg.classList.remove('d-none');

                            // Reset UI jika error
                            btn.disabled = false;
                            btnText.classList.remove('d-none');
                            btnLoader.classList.add('d-none');
                        }
                    });
                });
            });
        </script>
    @endonce
@endpush
