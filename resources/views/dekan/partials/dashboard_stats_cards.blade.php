<div class="col-lg-5 col-md-6">
    <div class="d-flex flex-column gap-2 h-100">
        <a href="{{ route('dekan.statistik.list', 'total_usulan') }}" class="text-decoration-none flex-grow-1">
            <div class="card shadow-sm h-100 stat-card">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['total_usulan'] ?? 0 }}</h4>
                        <small class="text-muted" style="font-size: 1rem;">Total Usulan Masuk</small>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="#8BC3B4"
                        class="bi bi-inbox-fill" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('dekan.statistik.list', 'disetujui') }}" class="text-decoration-none flex-grow-1">
            <div class="card shadow-sm h-100 stat-card">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $stats['disetujui'] ?? 0 }}</h4>
                        <small class="text-muted" style="font-size: 1rem;">Proposal Disetujui</small>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="#8BC3B4"
                        class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                        <path
                            d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm0 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3z" />
                    </svg>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="col-lg-4 col-md-6">
    <div class="d-flex flex-column gap-2 h-100">
        <a href="{{ route('dekan.statistik.list', 'artikel') }}" class="text-decoration-none flex-grow-1">
            <div class="card shadow-sm h-100 stat-card">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">{{ $stats['total_artikel'] ?? 0 }}</h5>
                        <small class="text-muted" style="font-size: 1rem;">Total Artikel</small>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#8BC3B4"
                        class="bi bi-file-earmark-text-fill" viewBox="0 0 16 16">
                        <path
                            d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM4.5 12a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1h-4z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('dekan.statistik.list', 'sertifikat') }}" class="text-decoration-none flex-grow-1">
            <div class="card shadow-sm h-100 stat-card">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">{{ $stats['total_sertifikat'] ?? 0 }}</h5>
                        <small class="text-muted" style="font-size: 1rem;">Total Sertifikat</small>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#8BC3B4"
                        class="bi bi-award-fill" viewBox="0 0 16 16">
                        <path
                            d="m8 0 1.669.864 1.858.282.842 1.68 1.337 1.32L13.4 6l.306 1.854-1.337 1.32-.842 1.68-1.858.282L8 12l-1.669-.864-1.858-.282-.842-1.68-1.337-1.32L2.6 6l-.306-1.854 1.337-1.32.842-1.68 1.858-.282L8 0z" />
                        <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ route('dekan.statistik.list', 'hki') }}" class="text-decoration-none flex-grow-1">
            <div class="card shadow-sm h-100 stat-card">
                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">{{ $stats['total_hki'] ?? 0 }}</h5>
                        <small class="text-muted" style="font-size: 1rem;">Total HKI</small>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#8BC3B4"
                        class="bi bi-patch-check-fill" viewBox="0 0 16 16">
                        <path
                            d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.622-1.321-.117a2.89 2.89 0 0 0-3.133 3.133l.117 1.321-.622.622a2.89 2.89 0 0 0 0 4.134l.622.622-.117 1.321a2.89 2.89 0 0 0 3.133 3.133l1.321-.117.622.622a2.89 2.89 0 0 0 4.134 0l.622-.622 1.321.117a2.89 2.89 0 0 0-3.133-3.133l-1.321.117zM6.993 11.15l-3.116-3.116a.75.75 0 0 1 1.058-1.058l2.059 2.059 4.061-4.061a.75.75 0 1 1 1.058 1.058l-4.588 4.588a.75.75 0 0 1-1.072 0z" />
                    </svg>
                </div>
            </div>
        </a>
    </div>
</div>
