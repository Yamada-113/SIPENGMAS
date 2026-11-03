@extends('layouts.app')

@section('title', 'Website Customization Hub')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.dashboard') }}"
                class="btn btn-light shadow-sm btn-sm px-3 rounded-pill text-secondary fw-bold border">
                <div class="d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                    </svg>
                    <span>Kembali ke Dashboard</span>
                </div>
            </a>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 text-white shadow overflow-hidden"
                    style="background: linear-gradient(135deg, #378a75 0%, #2a6656 100%); border-radius: 1rem;">
                    <div class="card-body p-5 position-relative">
                        <div class="d-flex align-items-center position-relative z-1">
                            <div class="me-4 p-3 bg-white bg-opacity-25 rounded-circle">
                                <i class="bi bi-grid-fill fs-1 text-white"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-1">Website Customization Hub</h3>
                                <p class="mb-0 text-white-50">Pusat kendali untuk mengubah konten master data dan atribut
                                    sistem.</p>
                            </div>
                        </div>
                        <i class="bi bi-sliders position-absolute text-white opacity-10"
                            style="font-size: 10rem; right: -20px; bottom: -40px; transform: rotate(-15deg);"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center">

            <div class="col-lg-4 col-md-6">
                <a href="{{ route('admin.customization.skema') }}" class="text-decoration-none action-card-link">
                    <div class="card shadow-sm action-card h-100 border-0">

                        <div class="floating-preview">
                            <div class="preview-box">
                                <img src="{{ asset('images/preview_skema.png') }}" alt="Preview Skema">
                                <span class="preview-badge">Preview Page</span>
                            </div>
                        </div>

                        <div class="card-body p-4 d-flex flex-column align-items-center text-center">
                            <div class="icon-circle bg-teal-subtle mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#378a75"
                                    class="bi bi-list-check" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3.854 2.146a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 3.293l1.146-1.147a.5.5 0 0 1 .708 0zm0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2 7.293l1.146-1.147a.5.5 0 0 1 .708 0zm0 4a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z" />
                                </svg>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">Skala & Skema</h6>
                            <p class="text-muted small mb-0">Daftar Skema Pengabdian & Skala Pelaksanaan.</p>
                            <div class="mt-auto pt-3 text-teal fw-bold small action-text">Kelola <i
                                    class="bi bi-arrow-right"></i></div>
                        </div>
                        <div class="card-footer-line bg-teal"></div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-6">
                <a href="{{ route('admin.customization.prodi') }}" class="text-decoration-none action-card-link">
                    <div class="card shadow-sm action-card h-100 border-0">

                        <div class="floating-preview">
                            <div class="preview-box">
                                <img src="{{ asset('images/preview_prodi.png') }}" alt="Preview Prodi">
                                <span class="preview-badge">Preview Page</span>
                            </div>
                        </div>

                        <div class="card-body p-4 d-flex flex-column align-items-center text-center">
                            <div class="icon-circle bg-teal-subtle mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#378a75"
                                    class="bi bi-mortarboard" viewBox="0 0 16 16">
                                    <path
                                        d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5ZM8 8.46 1.758 5.965 8 3.052l6.242 2.913L8 8.46Z" />
                                    <path
                                        d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466 4.176 9.032Zm-.068 1.873.22-.748 3.496 1.317a.5.5 0 0 0 .352 0l3.496-1.317.22.748L8 12.46l-3.892-1.556Z" />
                                </svg>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">Prodi Anggota</h6>
                            <p class="text-muted small mb-0">Daftar Jurusan untuk Anggota Dosen & Mhs.</p>
                            <div class="mt-auto pt-3 text-teal fw-bold small action-text">Kelola <i
                                    class="bi bi-arrow-right"></i></div>
                        </div>
                        <div class="card-footer-line bg-teal"></div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-6">
                <a href="{{ route('admin.customization.profile') }}" class="text-decoration-none action-card-link">
                    <div class="card shadow-sm action-card h-100 border-0">

                        <div class="floating-preview">
                            <div class="preview-box">
                                <img src="{{ asset('images/preview_profile.png') }}" alt="Preview Profile">
                                <span class="preview-badge">Preview Page</span>
                            </div>
                        </div>

                        <div class="card-body p-4 d-flex flex-column align-items-center text-center">
                            <div class="icon-circle bg-teal-subtle mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#378a75"
                                    class="bi bi-person-vcard-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm9 1.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4a.5.5 0 0 0-.5.5ZM9 8a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 0-1h-4A.5.5 0 0 0 9 8Zm1 2.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 0-1h-3a.5.5 0 0 0-.5.5Zm-9-3a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 0-1h-5a.5.5 0 0 0-.5.5Zm0 2a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 0-1h-5a.5.5 0 0 0-.5.5Z" />
                                </svg>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">Atribut Profile</h6>
                            <p class="text-muted small mb-0">Fakultas, Prodi, dan Jabatan.</p>
                            <div class="mt-auto pt-3 text-teal fw-bold small action-text">Kelola <i
                                    class="bi bi-arrow-right"></i></div>
                        </div>
                        <div class="card-footer-line bg-teal"></div>
                    </div>
                </a>
            </div>

        </div>

        <style>
            .bg-teal {
                background-color: #378a75 !important;
            }

            .bg-teal-subtle {
                background-color: #e6fcf5 !important;
            }

            .action-card {
                transition: all 0.3s ease;
                border-radius: 1rem !important;
                background: white;
                overflow: visible !important;
                position: relative;
                z-index: 1;
                -webkit-font-smoothing: antialiased;
            }

            .action-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 30px rgba(55, 138, 117, 0.15) !important;
                z-index: 100 !important;
            }

            .floating-preview {
                position: absolute;
                top: 80%;
                left: 50%;
                transform: translateX(-50%) translateY(-10px);

                width: 320px;
                aspect-ratio: 16/9;

                opacity: 0;
                visibility: hidden;
                transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
                z-index: 999;
                pointer-events: none;

                will-change: transform, opacity;
                filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.15));
            }

            .preview-box {
                background: white;
                padding: 6px;
                border-radius: 12px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
                border: 2px solid #378a75;
                width: 100%;
                height: 100%;
                position: relative;
            }

            .preview-box img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 8px;
                display: block;

                image-rendering: -webkit-optimize-contrast;
                image-rendering: crisp-edges;
                transform: translateZ(0);
                backface-visibility: hidden;
            }

            .preview-badge {
                position: absolute;
                top: -10px;
                right: 15px;
                background: #378a75;
                color: white;
                font-size: 0.7rem;
                padding: 4px 12px;
                border-radius: 20px;
                font-weight: bold;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
                letter-spacing: 0.5px;
            }

            .preview-box::before {
                content: '';
                position: absolute;
                top: -10px;
                left: 50%;
                transform: translateX(-50%);
                border-width: 0 10px 10px 10px;
                border-style: solid;
                border-color: transparent transparent #378a75 transparent;
            }

            .action-card:hover .floating-preview {
                opacity: 1;
                visibility: visible;
                transform: translateX(-50%) translateY(15px);
            }

            .icon-circle {
                width: 64px;
                height: 64px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: transform 0.3s ease;
            }

            .action-card:hover .icon-circle {
                transform: scale(1.1);
                background-color: #378a75 !important;
            }

            .action-card:hover .icon-circle svg {
                fill: white !important;
            }

            .card-footer-line {
                height: 6px;
                width: 100%;
            }
        </style>
    @endsection
