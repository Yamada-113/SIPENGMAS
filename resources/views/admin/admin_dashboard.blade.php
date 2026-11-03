@extends('layouts.app')

@section('title', 'Dasbor Admin')

@section('content')
    @include('shared.alert_script')

    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card text-white shadow-sm" style="background-color: #8BC3B4;">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold">Dasbor Administrator</h5>
                        <p class="card-text mb-0">Selamat datang, Administrator. Kelola sistem, pengguna, dan rekapitulasi
                            data.</p>
                    </div>
                    <div class="p-4 pt-0">
                        <div class="row justify-content-center g-3">

                            <div class="col-lg-4 col-md-6">
                                <a href="{{ route('admin.rekapitulasi') }}" class="text-decoration-none action-card-link">
                                    <div class="card shadow-sm action-card h-100"
                                        style="border-radius: 0.5rem; border: none;">
                                        <div class="card-body text-center p-4 position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#378a75" class="bi bi-clipboard-data-fill action-icon mx-auto mb-2"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M6.5 0A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0zm3 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5-.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z" />
                                                <path
                                                    d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1A2.5 2.5 0 0 1 9.5 5h-3A2.5 2.5 0 0 1 4 2.5zM10 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m-6 4a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                                            </svg>
                                            <h6 class="card-title mb-0 mt-2 action-title" style="color: #378a75;">
                                                Rekapitulasi Proposal</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <a href="{{ route('admin.manajemen_user') }}" class="text-decoration-none action-card-link">
                                    <div class="card shadow-sm action-card h-100"
                                        style="border-radius: 0.5rem; border: none;">
                                        <div class="card-body text-center p-4 position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#378a75" class="bi bi-people-fill action-icon mx-auto mb-2"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5" />
                                            </svg>
                                            <h6 class="card-title mb-0 mt-2 action-title" style="color: #378a75;">Manajemen
                                                Akun</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <a href="{{ route('admin.customization') }}" class="text-decoration-none action-card-link">
                                    <div class="card shadow-sm action-card h-100"
                                        style="border-radius: 0.5rem; border: none;">
                                        <div class="card-body text-center p-4 position-relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                fill="#378a75" class="bi bi-gear-fill action-icon mx-auto mb-2"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.858 2.929 2.929 0 0 1 0 5.858z" />
                                            </svg>
                                            <h6 class="card-title mb-0 mt-2 action-title" style="color: #378a75;">
                                                Customization</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <h6 class="text-uppercase fw-bold text-secondary mb-3" style="letter-spacing: 1px; font-size: 0.9rem;">
                    Statistik Sistem</h6>

                <div class="row g-3">
                    <div class="col-lg-3 col-md-12">
                        <div class="card shadow-sm h-100 border-0">
                            <div
                                class="card-body p-3 d-flex flex-column justify-content-center align-items-center text-center">
                                <div class="mb-2 bg-light rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                    style="width: 60px; height: 60px;">
                                    <i class="bi bi-person-fill-gear text-secondary" style="font-size: 2rem;"></i>
                                </div>
                                <small class="text-uppercase text-secondary fw-bold mb-1"
                                    style="font-size: 0.7rem; letter-spacing: 1px;">Identitas</small>
                                <h6 class="fw-bold mb-1 text-dark text-truncate w-100" title="{{ auth()->user()->name }}">
                                    {{ auth()->user()->name }}</h6>
                                <span class="badge bg-light text-dark border px-2 py-1 mt-1 mb-2"
                                    style="font-size: 0.75rem;">
                                    {{ auth()->user()->role ?? 'Admin' }}
                                </span>
                                <div class="mt-2 text-muted small">Administrator</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5 col-md-6">
                        <div class="d-flex flex-column gap-2 h-100">
                            <a href="{{ route('admin.statistik.list', 'proposal_keseluruhan') }}"
                                class="text-decoration-none flex-grow-1">
                                <div class="card shadow-sm h-100 stat-card">
                                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="fw-bold mb-0 text-dark">{{ $stats['proposal_keseluruhan'] }}</h4>
                                            <small class="text-muted" style="font-size: 1rem;">Total Proposal Masuk</small>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="#8BC3B4"
                                            class="bi bi-inbox-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M4.98 4a.5.5 0 0 0-.39.188L1.54 8H6a.5.5 0 0 1 .5.5 1.5 1.5 0 1 0 3 0A.5.5 0 0 1 10 8h4.46l-3.05-3.812A.5.5 0 0 0 11.02 4H4.98zm-1.17-.437A1.5 1.5 0 0 1 4.98 3h6.04a1.5 1.5 0 0 1 1.17.563l3.7 4.625a.5.5 0 0 1 .106.374l-.39 3.124A1.5 1.5 0 0 1 14.117 13H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .106-.374l3.7-4.625z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('admin.statistik.list', 'proposal_didanai') }}"
                                class="text-decoration-none flex-grow-1">
                                <div class="card shadow-sm h-100 stat-card">
                                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="fw-bold mb-0 text-dark">{{ $stats['proposal_didanai'] }}</h4>
                                            <small class="text-muted" style="font-size: 1rem;">Total Didanai</small>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="#8BC3B4"
                                            class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="d-flex flex-column gap-2 h-100">
                            <a href="{{ route('admin.statistik.list', 'artikel') }}"
                                class="text-decoration-none flex-grow-1">
                                <div class="card shadow-sm h-100 stat-card">
                                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="fw-bold mb-0 text-dark">{{ $stats['artikel'] }}</h5>
                                            <small class="text-muted" style="font-size: 1rem;">Total Artikel</small>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                            fill="#8BC3B4" class="bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM4.5 12a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1h-4z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('admin.statistik.list', 'buku') }}"
                                class="text-decoration-none flex-grow-1">
                                <div class="card shadow-sm h-100 stat-card">
                                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="fw-bold mb-0 text-dark">{{ $stats['buku'] }}</h5>
                                            <small class="text-muted" style="font-size: 1rem;">Total Buku</small>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                            fill="#8BC3B4" class="bi bi-book-half" viewBox="0 0 16 16">
                                            <path
                                                d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                            <a href="{{ route('admin.statistik.list', 'hki') }}"
                                class="text-decoration-none flex-grow-1">
                                <div class="card shadow-sm h-100 stat-card">
                                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="fw-bold mb-0 text-dark">{{ $stats['hki'] }}</h5>
                                            <small class="text-muted" style="font-size: 1rem;">Total HKI</small>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                            fill="#8BC3B4" class="bi bi-patch-check-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.622-1.321-.117a2.89 2.89 0 0 0-3.133 3.133l.117 1.321-.622.622a2.89 2.89 0 0 0 0 4.134l.622.622-.117 1.321a2.89 2.89 0 0 0 3.133 3.133l1.321-.117.622.622a2.89 2.89 0 0 0 4.134 0l.622-.622 1.321.117a2.89 2.89 0 0 0 3.133-3.133l-.117-1.321.622-.622a2.89 2.89 0 0 0 0-4.134l-.622-.622.117-1.321a2.89 2.89 0 0 0-3.133-3.133l-1.321.117zM6.993 11.15l-3.116-3.116a.75.75 0 0 1 1.058-1.058l2.059 2.059 4.061-4.061a.75.75 0 1 1 1.058 1.058l-4.588 4.588a.75.75 0 0 1-1.072 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4" style="margin-top: 10px;">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-uppercase fw-bold text-secondary mb-0"
                        style="letter-spacing: 1px; font-size: 0.9rem;">KEBUTUHAN BERKAS DAN PANDUAN</h6>
                    <button class="btn btn-sm btn-success text-white fw-bold shadow-sm" data-bs-toggle="modal"
                        data-bs-target="#modalUploadBerkas">
                        <i class="bi bi-upload me-2"></i>Upload Berkas dan Panduan
                    </button>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse ($kumpulanPanduan as $panduan)
                                <div class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            fill="#8BC3B4" class="bi bi-file-earmark-pdf-fill me-3" viewBox="0 0 16 16">
                                            <path
                                                d="M5.523 12.424c.14-.082.293-.162.459-.252.166-.09.337-.187.518-.293a.5.5 0 0 1 .536.002c.17.104.34.2.51.304.17.102.34.208.514.318.173.11.352.228.536.354a.5.5 0 0 0 .58.001c.19-.13.388-.268.59-.413.201-.145.408-.3.626-.463.218-.162.44-.33.669-.507a.5.5 0 0 1 .58.002c.22.168.44.33.668.507.218.163.425.318.626.463.202.145.4.283.59.413a.5.5 0 0 0 .58-.001c.184-.126.363-.244.536-.354.174-.11.344-.216.514-.318.17-.104.34-.2.51-.304a.5.5 0 0 1 .536-.002c.18.106.352.203.518.293.166.09.318.17.459.252a1.71 1.71 0 0 1 .51.414c.142.3.121.638-.03.886-.153.248-.465.447-.82.557a.97.97 0 0 1-.144.022.97.97 0 0 1-.144-.022c-.355-.11-.667-.309-.82-.557-.15-.248-.172-.586-.03-.886a1.71 1.71 0 0 1 .51-.414zM11.11 7.91l-.11.18a.5.5 0 0 1-.847.11L9 7.414v2.07a.5.5 0 0 1-1 0V7.414l-1.153.662a.5.5 0 0 1-.847-.11l-.11-.18a.5.5 0 1 1 .847-.49l1.11-1.922a.5.5 0 0 1 .848 0l1.11 1.922a.5.5 0 1 1 .847.49z" />
                                            <path
                                                d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v10.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V2a1 1 0 0 1 1-1" />
                                        </svg>
                                        <span style="font-weight: 500;">{{ $panduan->title }}</span>
                                    </div>
                                    <div>
                                        <a href="{{ \Storage::url('panduan/' . $panduan->file) }}" target="_blank"
                                            class="btn btn-sm btn-download me-1">Download</a>
                                    </div>
                                </div>
                            @empty
                                <div class="card-body d-flex flex-column align-items-center justify-content-center p-5">
                                    <img src="{{ asset('images/empty.png') }}" alt="Belum ada panduan"
                                        style="width: 275px; opacity: 0.7;" class="mb-3">
                                    <h6 class="text-muted fw-bold">Belum ada berkas dan panduan diunggah</h6>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUploadBerkas" tabindex="-1" aria-labelledby="modalUploadBerkasLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold" id="modalUploadBerkasLabel">Upload Berkas Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.panduan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="nama_file" class="form-label fw-bold">Nama File</label>
                            <input type="text" class="form-control" id="nama_file" name="title"
                                placeholder="Masukkan nama file/dokumen" required>
                        </div>
                        <div class="mb-3">
                            <label for="file_upload" class="form-label fw-bold">Pilih File</label>
                            <input class="form-control" type="file" id="file_upload" name="file"
                                accept=".pdf,.docx,application/pdf" required>
                            <div class="form-text">Format: PDF, DOCX (Max: 5MB)</div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success fw-bold px-4">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    @include('shared.styles_dashboard')
@endpush
