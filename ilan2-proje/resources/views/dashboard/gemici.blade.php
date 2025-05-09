@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    body {
        background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%) !important;
        font-family: 'Poppins', sans-serif;
    }
    .dashboard-welcome-card {
        background: linear-gradient(120deg, #6366f1 0%, #60a5fa 100%);
        color: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.13);
        border: none;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
    }
    .dashboard-welcome-card h2 {
        font-weight: 700;
        font-size: 2.2rem;
    }
    .dashboard-welcome-card p {
        font-size: 1.1rem;
        color: #e0e7ff;
    }
    .dashboard-stats .card {
        border-radius: 16px;
        box-shadow: 0 4px 18px 0 rgba(31, 38, 135, 0.09);
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
    }
    .dashboard-stats .card:hover {
        transform: translateY(-4px) scale(1.03);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
    }
    .dashboard-stats .card-title {
        font-size: 1.1rem;
        color: #6366f1;
    }
    .dashboard-stats .fw-bold {
        font-size: 2.1rem;
        color: #18181b;
    }
    .dashboard-action-btn {
        font-size: 1.1rem;
        padding: 0.7rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        color: #fff;
        border: none;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.12);
        transition: background 0.2s, color 0.2s;
    }
    .dashboard-action-btn:hover {
        background: linear-gradient(90deg, #60a5fa 0%, #6366f1 100%);
        color: #fff;
    }
    .dashboard-rotalar .card {
        border-radius: 14px;
        border: none;
        box-shadow: 0 2px 10px 0 rgba(99, 102, 241, 0.07);
        margin-bottom: 1.2rem;
        transition: box-shadow 0.2s;
    }
    .dashboard-rotalar .card:hover {
        box-shadow: 0 8px 32px 0 rgba(99, 102, 241, 0.13);
    }
    .dashboard-rotalar .badge {
        font-size: 0.95rem;
        padding: 0.5em 1em;
        border-radius: 8px;
    }
</style>

<div class="container py-5">
    <div class="dashboard-welcome-card d-flex justify-content-between align-items-center flex-wrap mb-5">
        <div>
            <h2>Hoşgeldin, {{ $user->name }}</h2>
            <p>Profiline ve gemi rotalarına buradan erişebilirsin.</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="dashboard-action-btn mt-3 mt-md-0">
            <i class="bi bi-pencil"></i> Profili Düzenle
        </a>
    </div>

    {{-- Rota İstatistikleri --}}
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5 dashboard-stats">
        <div class="col">
            <a href="{{ route('gemi_routes.index') }}" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-2"><i class="bi bi-signpost-split-fill text-primary" style="font-size: 2.2rem;"></i></div>
                        <div class="card-title fw-semibold mb-2">Toplam Rota</div>
                        <div class="fw-bold">{{ $rotalar->count() }}</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('matched.products') }}" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-2"><i class="bi bi-check-circle-fill text-success" style="font-size: 2.2rem;"></i></div>
                        <div class="card-title fw-semibold mb-2">Eşleşen Yük Sayısı</div>
                        <div class="fw-bold">{{ $matchedYukCount }}</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('ships.index') }}" class="text-decoration-none">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="mb-2"><i class="fa-solid fa-ship text-primary" style="font-size: 2.2rem;"></i></div>
                        <div class="card-title fw-semibold mb-2">Gemilerim</div>
                        <div class="fw-bold">{{ $gemiCount }}</div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Rotalar Listesi --}}
    <div class="dashboard-rotalar">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-semibold mb-0">Rotaların</h4>
            <a href="{{ route('gemi_routes.create') }}" class="dashboard-action-btn">
                <i class="bi bi-plus-circle me-1"></i> Yeni Rota Oluştur
            </a>
        </div>
        @forelse($rotalar as $rota)
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h5 class="fw-bold mb-1">{{ $rota->start_port_name }} → {{ $rota->end_port_name }}</h5>
                        <p class="text-muted mb-0">
                            Kapasite: {{ $rota->available_capacity }} {{$rota->weight_type}} |
                            Kalkış Zamanı: {{ $rota->departure_date }} |
                            Durum: 
                            @if($rota->durum == 'tamamlandı')
                                <span class="badge bg-success">Tamamlandı</span>
                            @elseif($rota->durum == 'onaylandı')
                                <span class="badge bg-warning text-dark">Onaylandı</span>
                            @else
                                <span class="badge bg-secondary">Bekliyor</span>
                            @endif
                        </p>
                    </div>
                    <div class="text-end mt-3 mt-md-0">
                        <a href="{{ route('gemi_routes.show', $rota->id) }}" class="btn btn-outline-secondary btn-sm">
                            Detaylar
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">Henüz bir rotan yok.</div>
        @endforelse
    </div>
</div>
@endsection
