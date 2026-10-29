<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="user-id" content="{{ Auth::id() }}">

    <title>@yield('title', 'SIPENGMAS')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/cropped_circle_sipengmas.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex-grow: 1;
        }

        .navbar {
            background-color: #8BC3B4;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .navbar-brand {
            padding: 0;
            margin-right: 0.5rem;
        }

        .navbar-logo-img {
            height: 75px;
            width: 200px;
            vertical-align: middle;
        }

        .navbar-brand,
        .nav-link,
        .user-name {
            color: #FFFFFF !important;
            font-weight: 600;
        }

        .dropdown-menu .dropdown-item {
            color: #212529 !important;
        }

        .footer {
            background-color: #8BC3B4;
            color: #FFFFFF;
            padding: 1.5rem 0;
            font-size: 0.9rem;
            margin-top: auto;
        }

        .footer-bottom {
            text-align: center;
        }
    </style>
    @stack('styles')

    @php
        $dashboardRoute = route('dosen.dashboard');
        if (auth()->check()) {
            if (auth()->user()->role === 'Admin') {
                $dashboardRoute = route('admin.dashboard');
            } elseif (auth()->user()->role === 'Wakil Dekan 3') {
                $dashboardRoute = route('wakil_dekan3.dashboard');
            } elseif (auth()->user()->role === 'Dekan') {
                $dashboardRoute = route('dekan.dashboard');
            } elseif (
                auth()->user()->role === 'Kepala Pusat 1' ||
                auth()->user()->role === 'Kepala Pusat 2' ||
                auth()->user()->role === 'Kepala Pusat 3' ||
                auth()->user()->role === 'Kepala Pusat 4' ||
                auth()->user()->role === 'Kepala Pusat 5'
            ) {
                $dashboardRoute = route('kepala_pusat.dashboard');
            } elseif (auth()->user()->role === 'Wakil Rektor 3') {
                $dashboardRoute = route('wakil_rektor.dashboard');
            }
        }
    @endphp
</head>

<body>
    <div id="floating-alert-container"
        style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;"></div>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #8BC3B4;">
        <div class="container">
            <a class="navbar-brand" href="{{ $dashboardRoute ?? '#' }}">
                <img src="{{ asset('images/white_sipengmaswordCROP-removebg-preview.png') }}" alt="SIPENGMAS"
                    style="height: 50px; width: auto;">
            </a>

            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center d-flex flex-row">

                <li class="nav-item dropdown me-3">
                    <a class="nav-link position-relative" href="#" id="navbarDropdownNotif" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell-fill fs-5"></i>

                        <span id="notif-badge"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ auth()->user()->unreadNotifications->count() == 0 ? 'd-none' : '' }}"
                            style="font-size: 0.6rem;">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    </a>

                    <ul id="notification-list" class="dropdown-menu dropdown-menu-end shadow border-0"
                        aria-labelledby="navbarDropdownNotif"
                        style="width: 320px; max-height: 400px; overflow-y: auto;">
                        <li
                            class="dropdown-header fw-bold d-flex justify-content-between align-items-center bg-white sticky-top pt-2 pb-2 border-bottom">
                            <span>Notifikasi</span>
                            @if (auth()->user()->unreadNotifications->count() > 0)
                                <a href="{{ route('mark_all_read') }}" class="text-decoration-none small text-primary"
                                    style="font-size: 0.75rem;">Tandai semua</a>
                            @endif
                        </li>

                        @forelse(auth()->user()->notifications->take(15) as $notification)
                            @php
                                $isUnread = is_null($notification->read_at);
                                $bgColor = $isUnread ? '#eef7fd' : '#ffffff';
                            @endphp

                            <li class="notif-item border-bottom" style="background-color: {{ $bgColor }};">
                                <a href="{{ route('notification.read', $notification->id) }}"
                                    class="dropdown-item d-flex align-items-start gap-2 py-3"
                                    onclick="decrementBadge(this)">

                                    <div
                                        class="{{ $isUnread ? 'bg-primary text-primary' : 'bg-secondary text-secondary' }} bg-opacity-10 p-2 rounded-circle flex-shrink-0">
                                        <i class="bi {{ $isUnread ? 'bi-envelope-fill' : 'bi-envelope-open' }}"></i>
                                    </div>

                                    <div style="width: 100%; white-space: normal;">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <small
                                                class="fw-bold d-block {{ $isUnread ? 'text-dark' : 'text-muted' }}">
                                                {{ $notification->data['title'] ?? 'Info' }}
                                            </small>

                                            @if ($isUnread)
                                                <span class="badge bg-danger rounded-circle p-1"
                                                    style="width: 8px; height: 8px;"> </span>
                                            @endif
                                        </div>

                                        <small class="{{ $isUnread ? 'text-dark' : 'text-muted' }}"
                                            style="font-size: 0.8rem; display: block; margin-top: 2px; line-height: 1.2;">
                                            {{ \Illuminate\Support\Str::limit($notification->data['message'] ?? ($notification->data['pesan'] ?? '-'), 80) }}
                                        </small>

                                        <small class="text-secondary d-block mt-2" style="font-size: 0.65rem;">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li id="no-notif-msg" class="p-4 text-center text-muted">
                                <i class="bi bi-bell-slash display-6 d-block mb-2 opacity-50"></i>
                                <small>Belum ada notifikasi</small>
                            </li>
                        @endforelse
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-person-fill me-2" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                        </svg>
                        <span class="user-name">
                            {{ Auth::user()->name }}
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Keluar
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <span>© Sipengmas {{ date('Y') }} | Last Dance Team</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script type="module">
        document.addEventListener("DOMContentLoaded", function() {
            const userIdMeta = document.querySelector('meta[name="user-id"]');

            if (userIdMeta && window.Echo) {
                const userId = userIdMeta.getAttribute('content');

                window.Echo.leave(`App.Models.User.${userId}`);

                window.Echo.private(`App.Models.User.${userId}`)
                    .notification((notification) => {
                        console.log("Realtime Notif:", notification);

                        const notifBadge = document.getElementById('notif-badge');
                        if (notifBadge) {
                            notifBadge.classList.remove('d-none');
                            let currentCount = parseInt(notifBadge.innerText);
                            if (isNaN(currentCount)) currentCount = 0;
                            notifBadge.innerText = currentCount + 1;
                        }

                        const notifList = document.getElementById('notification-list');
                        const noMsg = document.getElementById('no-notif-msg');
                        if (noMsg) noMsg.style.display = 'none';

                        const newHtml = `
                            <li class="bg-light">
                                <a href="${notification.url}?read_id=${notification.id}" class="dropdown-item d-flex align-items-start gap-2 py-2">
                                    <div class="bg-success bg-opacity-10 p-2 rounded-circle">
                                        <i class="bi bi-bell-fill text-success"></i>
                                    </div>
                                    <div style="white-space: normal;">
                                        <small class="fw-bold d-block">${notification.title}</small>
                                        <small class="text-dark" style="font-size: 0.75rem;">${notification.message}</small>
                                        <small class="text-primary d-block mt-1 fw-bold" style="font-size: 0.65rem;">Baru Saja</small>
                                    </div>
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        `;

                        if (notifList) {
                            const header = notifList.querySelector('.dropdown-header');
                            if (header) header.insertAdjacentHTML('afterend', newHtml);
                        }

                        if (notification.title.toLowerCase().includes('undangan') || notification.url.includes(
                                'anggotarequest')) {
                            const dashboardBadge = document.getElementById('dashboard-req-badge');
                            if (dashboardBadge) {
                                dashboardBadge.classList.remove('d-none');
                                let reqCount = parseInt(dashboardBadge.innerText);
                                if (isNaN(reqCount)) reqCount = 0;
                                dashboardBadge.innerText = reqCount + 1;
                            }
                        }

                        showRealtimeToast(notification.title, notification.message);
                    });
            }
        });

        function showRealtimeToast(title, message) {
            const container = document.getElementById('floating-alert-container');
            if (!container) return;

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-info shadow-lg alert-dismissible fade show border-0`;
            alertDiv.style.backgroundColor = "#e3f2fd";
            alertDiv.style.borderLeft = "5px solid #2196f3";
            alertDiv.innerHTML = `
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-bell-fill mt-1 text-primary"></i>
                    <div>
                        <strong class="text-primary">${title}</strong><br>
                        <small class="text-dark">${message}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            container.appendChild(alertDiv);

            setTimeout(() => {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 300);
            }, 5000);
        }
    </script>

    @stack('scripts')
</body>

</html>
