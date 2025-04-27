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

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">CargoOptima</a>

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
                            <a class="nav-link" href="{{ route('gemi_routes.index') }}">Rotalarım</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link" href="{{ route('gemi_routes.create') }}">Rota Ekle</a>
                        </li>
                    @endif

                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
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
            <p class="mb-0">&copy; {{ date('Y') }} CargoMatch. Tüm hakları saklıdır.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
