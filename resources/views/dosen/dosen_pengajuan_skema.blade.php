@extends('layouts.app')

@section('title', 'Pilih Skema Pengabdian')

@section('content')
    <div class="container">

        {{-- HEADER & TOMBOL KEMBALI --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            {{-- [FIX] Arrow di Kiri --}}
            <a href="{{ route('dosen.dashboard') }}"
                class="btn btn-secondary btn-action-control d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                </svg>
                <span>Kembali</span>
            </a>

            <div style="width: 100px;"></div>
        </div>

        {{-- CARD 1: TAHUN & SKALA --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0" style="color: #343a40;">Pilih Tahun dan Skala Pelaksanaan</h5>
            </div>
            <div class="card-body">
                <form id="formSetting">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="year_pelaksanaan" class="form-label fw-bold">Tahun Pelaksanaan</label>
                            <select class="form-select form-select-sm" id="year_pelaksanaan">
                                @php
                                    $currentYear = (int) date('Y');
                                    $startYear = 2019;
                                @endphp
                                @for ($i = $startYear; $i <= $currentYear + 1; $i++)
                                    <option value="{{ $i }}" @if ($i == $currentYear) selected @endif>
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="skala_pelaksanaan" class="form-label fw-bold">Skala Pelaksanaan</label>
                            <select class="form-select form-select-sm" id="skala_pelaksanaan">
                                @foreach ($skalaOptions as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- CARD 2: PILIH SKEMA --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form id="formSkema">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="skema_terpilih" class="form-label fw-bold">Pilih Skema Pengabdian</label>
                            <select class="form-select form-select-sm" id="skema_terpilih"> {{-- ID HARUS 'skema_terpilih' --}}
                                <option value="" disabled selected>--- Pilih Skala Terlebih Dahulu ---</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- FOOTER: TOMBOL LANJUT --}}
        <div class="d-flex justify-content-end mt-4">
            {{-- [FIX] Arrow di Kanan --}}
            <button id="btnLanjut" class="btn btn-primary btn-action-control d-flex align-items-center gap-2" disabled>
                <span>Lanjut</span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                </svg>
            </button>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. DATA
            const dataSkema = @json($skemaList);

            const yearSelect = document.getElementById('year_pelaksanaan');
            const skalaSelect = document.getElementById('skala_pelaksanaan');
            const skemaSelect = document.getElementById('skema_terpilih');
            const btnLanjut = document.getElementById('btnLanjut');

            // 2. LOGIC ENABLE BUTTON
            function checkAndEnableLanjut() {
                if (skemaSelect.value && skemaSelect.value !== "") {
                    btnLanjut.disabled = false;
                    btnLanjut.classList.add('pop-up-ready');
                } else {
                    btnLanjut.disabled = true;
                    btnLanjut.classList.remove('pop-up-ready');
                }
            }

            // 3. LOGIC UPDATE DROPDOWN
            function updateSkemaOptions() {
                const skala = skalaSelect.value;
                const options = dataSkema[skala];

                skemaSelect.innerHTML = '<option value="" disabled selected>--- Pilih Skema ---</option>';

                if (options) {
                    options.forEach(opt => {
                        const newOption = document.createElement('option');
                        newOption.value = opt.id;
                        newOption.text = opt.text;
                        newOption.setAttribute('data-nama', opt.nama);
                        skemaSelect.appendChild(newOption);
                    });
                }

                checkAndEnableLanjut();
            }

            // 4. LISTENERS
            if (skalaSelect) skalaSelect.addEventListener('change', updateSkemaOptions);
            if (yearSelect) yearSelect.addEventListener('change', checkAndEnableLanjut);
            if (skemaSelect) skemaSelect.addEventListener('change', checkAndEnableLanjut);

            if (btnLanjut) {
                btnLanjut.addEventListener('click', function() {
                    const selectedSkemaId = skemaSelect.value;
                    const selectedYear = yearSelect.value;
                    const selectedRole = skalaSelect.value;

                    if (selectedSkemaId) {
                        const redirectTemplate =
                            '{{ route('dosen.pengajuan.form', ['skemaId' => 999, 'year' => ':year', 'role' => ':role']) }}';
                        const finalUrl = redirectTemplate
                            .replace('999', selectedSkemaId)
                            .replace(':year', selectedYear)
                            .replace(':role', encodeURIComponent(selectedRole));

                        window.location.href = finalUrl;
                    }
                });
            }

            // 5. INIT
            updateSkemaOptions();
        });
    </script>
@endpush

@push('styles')
    <style>
        .btn-action-control {
            font-weight: 600;
            min-width: 120px;
            /* Tambahan agar isi tombol rata tengah */
            justify-content: center;
            transition: all 0.2s ease-in-out;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: #8BC3B4;
            border-color: #8BC3B4;
            color: white;
            transition: all 0.2s ease-in-out, transform 0.2s, box-shadow 0.3s;
        }

        .btn-primary:hover:not(:disabled),
        .btn-primary.pop-up-ready:hover {
            background-color: #6cb8a5;
            border-color: #6cb8a5;
            transform: translateY(-3px);
            color: white;
            box-shadow: 0 5px 10px rgba(139, 195, 180, 0.5);
        }

        .btn-primary:disabled {
            background-color: #ced4da;
            border-color: #ced4da;
            color: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
    </style>
@endpush
