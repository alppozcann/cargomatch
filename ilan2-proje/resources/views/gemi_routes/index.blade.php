@extends('layouts.app')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    body {
        background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%) !important;
        font-family: 'Poppins', sans-serif;
    }
    .page-header {
        background: linear-gradient(120deg,rgb(0, 4, 250) 0%, rgb(0, 0, 0) 100%);
        color: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.13);
        border: none;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
    }
    .page-header h1 {
        font-weight: 700;
        font-size: 2.2rem;
        margin: 0;
    }
    .page-header p {
        font-size: 1.1rem;
        color: #e0e7ff;
        margin: 0.5rem 0 0;
    }
    .route-card {
        border-radius: 16px;
        box-shadow: 0 4px 18px 0 rgba(31, 38, 135, 0.09);
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        height: 100%;
    }
    .route-card:hover {
        transform: translateY(-4px) scale(1.03);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
    }
    .route-card .card-header {
        background: linear-gradient(120deg,rgb(0, 4, 250) 0%, rgb(0, 0, 0) 100%);
        color: #fff;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem;
        border: none;
    }
    .route-card .card-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0;
    }
    .route-card .badge {
        font-size: 1rem;
        font-weight: 600;
        padding: 0.5em 1em;
        background: linear-gradient(90deg, #1e3a8a, #2563eb);
        color: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(30, 58, 138, 0.3);
    }
    .route-card .card-body {
        padding: 1.5rem;
    }
    .route-card .info-item {
        margin-bottom: 0.8rem;
    }
    .route-card .info-label {
        color: rgb(0, 4, 250);
        font-weight: 600;
        font-size: 0.9rem;
    }
    .route-card .info-value {
        color: #18181b;
        font-size: 1rem;
    }
    .route-card .card-footer {
        background: transparent;
        border-top: 1px solid rgba(99, 102, 241, 0.1);
        padding: 1rem 1.5rem;
    }
    .action-btn {
        font-size: 1.1rem;
        padding: 0.7rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        background: linear-gradient(135deg,rgb(0, 162, 255), #3b82f6);
        color: #fff;
        border: none;
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.12);
        transition: background 0.2s, color 0.2s;
    }
    .action-btn:hover {
        background: linear-gradient(135deg, #3b82f6,rgb(0, 162, 255));
        color: #fff;
    }
    .detail-btn {
        padding: 0.5rem 1.2rem;
        border-radius: 8px;
        font-weight: 500;
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
        border: 1px solid rgba(99, 102, 241, 0.2);
        transition: all 0.2s;
    }
    .detail-btn:hover {
        background: rgba(99, 102, 241, 0.2);
        color:rgb(0, 4, 250);
    }
</style>

<div class="container py-5">
    <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1>Gemi Rotaları</h1>
            <p>Tüm aktif gemi rotalarını buradan görüntüleyebilirsiniz.</p>
        </div>
        @auth
            @if(Auth::user()->isGemici())
                <a href="{{ route('gemi_routes.create') }}" class="action-btn mt-3 mt-md-0">
                    <i class="bi bi-plus-circle me-1"></i> Yeni Rota Ekle
                </a>
            @endif
        @endauth
    </div>

    @if(isset($routes) && $routes->count() > 0)
        <div class="row row-cols-1 row-cols-md-2 g-4">
            @foreach($routes as $gemiRoute)
                <div class="col">
                    <div class="route-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">{{ $gemiRoute->title }}</h5>
                            <span class="badge">{{ number_format($gemiRoute->price, 2)}} {{ $gemiRoute->currency_type }}</span>
                        </div>
                        <div class="card-body">
                            <div class="info-item">
                                <div class="info-label">Gemi Adı</div>
                                <div class="info-value">
                                    @if($gemiRoute->ship)
                                        {{ $gemiRoute->ship->ship_name }}
                                    @else
                                        <span class="text-muted">Belirtilmemiş</span>
                                    @endif
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Rota</div>
                                <div class="info-value">{{ $gemiRoute->start_port_name }} → {{ $gemiRoute->end_port_name }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Boş Kapasite</div>
                                <div class="info-value">{{ number_format($gemiRoute->available_capacity, 2) }} {{ $gemiRoute->weight_type }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Tarih</div>
                                <div class="info-value">
                                    {{ optional($gemiRoute->departure_date)->format('d.m.Y') }} - 
                                    {{ optional($gemiRoute->arrival_date)->format('d.m.Y') }}
                                </div>
                            </div>
                            @if($gemiRoute->description)
                                <div class="info-item">
                                    <div class="info-label">Açıklama</div>
                                    <div class="info-value">{{ \Illuminate\Support\Str::limit($gemiRoute->description, 100) }}</div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <a href="{{ route('gemi_routes.show', $gemiRoute) }}" class="detail-btn">
                                <i class="bi bi-arrow-right me-1"></i> Detaylar
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info" style="border-radius: 12px; padding: 1.5rem;">
            <i class="bi bi-info-circle me-2"></i>
            Henüz aktif gemi rotası bulunmamaktadır.
            @auth
                @if(Auth::user()->isGemici())
                    İlk rotayı siz ekleyebilirsiniz!
                @endif
            @endauth
        </div>
    @endif
</div>
@endsection
