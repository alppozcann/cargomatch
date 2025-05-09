<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CargoOptima</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 0rem;
        }
    </style>
    <!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .dropdown-item.bg-warning-subtle {
            background-color: #fff8e1 !important;
        }
        .dropdown-item + .dropdown-item {
            margin-top: 4px;
        }
        .dropdown-menu .dropdown-item {
            border-radius: 6px;
            transition: background-color 0.2s ease;
        }
        .dropdown-menu .dropdown-item:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
@php
    $hideNavbar = in_array(Route::currentRouteName(), ['login', 'register']);
@endphp

@if (!$hideNavbar)
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center overflow-hidden" href="{{ route('dashboard') }}" style="height: 50px;">
    <img src="{{ asset('logo2.png') }}" alt="Logo" style="height: 80px; width: auto; object-fit: cover; margin-right: 10px;">
    CargoOptima
</a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto d-flex align-items-center">
                @auth
                    @if(auth()->user()->isYukVeren())
                        <li class="nav-item me-3">
                            <a class="nav-link" href="{{ route('yukler.index') }}">Yüklerim</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link" href="{{ route('yukler.create') }}">Yük Ekle</a>
                        </li>
                    @elseif(auth()->user()->isGemici())
                        <li class="nav-item me-3">
                            <a class="nav-link" href="{{ route('ships.index') }}">Gemilerim</a>
                        <li class="nav-item me-3">
                            <a class="nav-link" href="{{ route('gemi_routes.index') }}">Rotalarım</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link" href="{{ route('gemi_routes.create') }}">Rota Ekle</a>
                        </li>
                    @endif

                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    @php
                        $unreadCount = auth()->user()->unreadNotifications()->count();
                        $allCount = auth()->user()->notifications()->count();
                    @endphp
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-bell fa-lg"></i>
                            @if($unreadCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 p-0" aria-labelledby="notificationDropdown" style="width: 370px; max-height: 420px; overflow-y: auto;">
                            <li class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center bg-light">
                                <span class="fw-bold">Bildirimler</span>
                                @if($unreadCount > 0)
                                    <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                                        @csrf
                                        <button class="btn btn-link btn-sm text-decoration-none text-primary p-0">Tümünü Okundu Yap</button>
                                    </form>
                                @endif
                                @if($allCount > 0)
                                    <form method="POST" action="{{ route('notifications.deleteAll') }}" onsubmit="return confirm('Tüm bildirimleri silmek istediğinize emin misiniz?');" class="d-inline-block mb-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger px-2 py-1 d-inline-flex align-items-center">
                                            <i class="bi bi-trash me-1"></i> Sil
                                        </button>
                                    </form>
                                @endif
                            </li>
                            @forelse(auth()->user()->notifications()->latest()->take(10)->get() as $notification)
                                <li class="dropdown-item px-3 py-2 {{ $notification->read_at ? '' : 'bg-warning-subtle' }} border-bottom">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                        <div class="fw-semibold mb-1 text-wrap" style="word-break: break-word; white-space: normal;">
    {{ $notification->data['message'] ?? 'Yeni bildirim' }}
</div>
                                            @if(isset($notification->data['yuk_id']))
                                            <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-outline-primary mt-1">
    Ayrıntılara Git
</a>
                                            @endif
                                            <div class="text-muted small mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                                        </div>
                                        @if(!$notification->read_at)
                                            <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-secondary ms-2">Okundu</button>
                                            </form>
                                        @endif
                                    </div>
                                </li>
                            @empty
                                <li class="dropdown-item text-center text-muted py-4">Bildirim yok</li>
                            @endforelse
                        </ul>
                    </li>

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-light">Çıkış</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
@endif

    <main class="container py-4">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-light text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} CargoOptima. Tüm hakları saklıdır.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
