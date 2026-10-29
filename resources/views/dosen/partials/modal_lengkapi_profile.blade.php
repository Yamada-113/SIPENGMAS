@if ($showProfileModal)
    <div class="modal fade" id="modalLengkapiProfile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-person-exclamation me-2"></i>Lengkapi Profile
                    </h5>
                </div>

                <form id="form-lengkapi-profile" method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="modal-body p-4">
                        <div class="alert alert-warning d-flex align-items-center mb-3" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div class="small">
                                Halo <strong>{{ Auth::user()->name }}</strong>! <br>
                                Mohon lengkapi data akademik Anda sebelum melanjutkan.
                            </div>
                        </div>

                        {{-- NIDN --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">NIDN</label>
                            <input type="text" class="form-control" name="nidn"
                                value="{{ old('nidn', Auth::user()->nidn) }}" placeholder="Masukkan NIDN"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')" required readonly>
                        </div>

                        {{-- Nama Lengkap --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name"
                                value="{{ old('name', Auth::user()->name) }}" required readonly>
                        </div>

                        {{-- FAKULTAS --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Fakultas</label>
                            <select class="form-select" id="fakultas_modal" name="fakultas"
                                onchange="updateProdiModal()" required>
                                <option value="" disabled selected>Pilih Fakultas</option>
                                {{-- Loop Data --}}
                                @foreach ($fakultas as $f)
                                    <option value="{{ $f->nama }}">{{ $f->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PRODI --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Program Studi</label>
                            <select class="form-select" id="prodi_modal" name="prodi" required>
                                <option value="" disabled selected>Pilih Fakultas Terlebih Dahulu</option>
                            </select>
                        </div>

                        {{-- Jabatan Fungsional --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Jabatan Fungsional</label>
                            <select class="form-select" id="jabatan_fungsional" name="jabatan_fungsional" required>
                                <option value="" disabled selected>Pilih Jabatan</option>
                                {{-- Loop Data --}}
                                @foreach ($jabatans as $j)
                                    <option value="{{ $j->nama }}">{{ $j->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer bg-light justify-content-end">
                        <button type="submit" form="logout-form"
                            class="btn btn-link text-danger text-decoration-none btn-sm me-auto">Log Out</button>

                        <button type="submit" class="btn btn-primary fw-bold px-4">Simpan Data</button>
                    </div>
                </form>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById('modalLengkapiProfile'), {
                backdrop: 'static',
                keyboard: false
            });
            myModal.show();
        });

        const prodiDataModal = @json($fakultasData ?? []);

        function updateProdiModal() {
            const fakultasSelect = document.getElementById("fakultas_modal");
            const prodiSelect = document.getElementById("prodi_modal");
            const selectedFakultas = fakultasSelect.value;

            prodiSelect.innerHTML = '<option value="" disabled selected>Pilih Program Studi</option>';

            if (selectedFakultas && prodiDataModal[selectedFakultas]) {
                prodiDataModal[selectedFakultas].forEach(prodi => {
                    let option = document.createElement("option");
                    option.value = prodi;
                    option.textContent = prodi;
                    prodiSelect.appendChild(option);
                });
            }
        }
    </script>
@endif
