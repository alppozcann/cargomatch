<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CargoOptima</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%) !important;
            min-height: 100vh;
        }
        .card-body {
    overflow-x: auto;
}

/* Genişlik taşıyıcıya uymazsa sınırla */
.card .list-group-item {
    min-width: 0;
    word-wrap: break-word;
}

/* Büyük badge ve butonlar satır taşırsa kırılabilsin */
.card .badge, .card .btn {
    white-space: normal;
}
        .sidebar {
            background: linear-gradient(180deg, rgb(0, 4, 250) 0%, rgb(0, 0, 0) 100%);
            color: #fff;
            width: 280px;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 1.5rem;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            padding: 1rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar-header .logo {
            width: 180px;
            height: auto;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .sidebar-header .logo:hover {
            transform: scale(1.05);
        }
        .sidebar-header h5 {
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
            color: #fff;
        }
        .nav-item {
            margin-bottom: 0.5rem;
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.8rem 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }
        .nav-link.active {
            background: linear-gradient(135deg, rgb(0, 162, 255), #3b82f6);
            color: #fff;
        }
        .nav-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }
        .main-content {
            margin-left: 280px;
            padding: 0;
            min-height: 100vh;
        }
        .top-header {
            background: transparent !important;
            box-shadow: none !important;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            min-height: 72px;
            padding: 1rem 2rem 0.5rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .top-header .btn {
            color: #000;
            border: 1px solid rgba(0, 0, 0, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            position: relative;
        }
        .top-header .btn:hover {
            background: #f8f9fa;
            border-color: rgba(0, 0, 0, 0.2);
            color: #000;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .content-wrapper {
            padding: 2rem;
        }
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1001;
        }
        .logout-btn {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            width: 100%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .logout-btn:hover {
            background: rgba(185, 0, 0, 0.44);
            color: #fff;
        }
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .alert-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
        }
        .alert-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
        }
        footer {
            background: transparent;
            color: #64748b;
            padding: 1.5rem 0;
            margin-top: 2rem;
        }
        .dropdown-menu .btn-sm {
    font-size: 0.75rem;
    padding: 0.3rem 0.6rem;
    border-radius: 6px;
}

.dropdown-menu .btn-outline-primary {
    color: #2563eb;
    border-color: #2563eb;
    background-color: white;
}
.dropdown-menu .btn-outline-primary:hover {
    background-color: #2563eb;
    color: white;
}

.dropdown-menu .btn-outline-secondary {
    color: #334155;
    border-color: #94a3b8;
    background-color: white;
}
.dropdown-menu .btn-outline-secondary:hover {
    background-color: #94a3b8;
    color: white;
}

.dropdown-menu .btn-outline-danger {
    color: #dc2626;
    border-color: #dc2626;
    background-color: white;
}
.dropdown-menu .btn-outline-danger:hover {
    background-color: #dc2626;
    color: white;
}
.notification-wrapper {
    z-index: 1100;
    max-width: 100vw;
    overflow-x: hidden;
}
.dropdown-menu {
    z-index: 1002;
}
.dropdown {
    margin-left: auto;
}

    </style>
</head>
<body>
@php
    $hideNavbar = in_array(Route::currentRouteName(), ['login', 'register']);
@endphp

@if (!$hideNavbar)
<div class="d-flex">
    {{-- Sidebar Navigation --}}
    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="d-block text-center mb-2">
                <img src="{{ asset('logo2.png') }}" alt="CargoOptima Logo" style="max-height: 100px; width: auto;" class="img-fluid mx-auto d-block">
            </a>
        </div>
        <div class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <span>Profilim</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ships.index') }}" class="nav-link {{ request()->routeIs('ships.index') ? 'active' : '' }}">
                        <i class="fas fa-ship"></i>
                        <span>Gemilerim</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ships.create') }}" class="nav-link {{ request()->routeIs('ships.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle"></i>
                        <span>Gemi Ekle</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('gemi_routes.index') }}" class="nav-link {{ request()->routeIs('gemi_routes.index') ? 'active' : '' }}">
                        <i class="fas fa-route"></i>
                        <span>Rotalarım</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('gemi_routes.create') }}" class="nav-link {{ request()->routeIs('gemi_routes.create') ? 'active' : '' }}">
                        <i class="fas fa-plus"></i>
                        <span>Rota Ekle</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-envelope"></i>
                        <span>Mesajlar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('matched.products') }}" class="nav-link {{ request()->routeIs('matched.products') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <span>Eşleşmiş Yükler</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-map-marked-alt"></i>
                        <span>Haritalar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span>Ayarlar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-headset"></i>
                        <span>Temsilci ile İletişime Geç</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                </button>
            </form>
        </div>
    </nav>

    <main class="main-content w-100" style="overflow-x: hidden;">
    <div class="top-header d-flex justify-content-end align-items-center">
        @include('partials.notification')
    </div>
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>
@endif

<footer>
    <div class="container text-center">
        <p class="mb-0">&copy; {{ date('Y') }} CargoOptima. Tüm hakları saklıdır.</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@yield('scripts')
</body>
</html>

