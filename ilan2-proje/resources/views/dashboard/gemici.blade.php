@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Hoşgeldin, {{ $user->name }}</h2>
                <p class="text-muted">Profiline ve gemi rotalarına buradan erişebilirsin.</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Profili Düzenle
            </a>
        </div>
    </div>

    {{-- Rota İstatistikleri --}}
<div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
<div class="col">
        <a href="{{ route('gemi_routes.index') }}" class="text-decoration-none text-dark">
            <div class="card shadow border-0 h-100 hover-grow">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3 d-flex align-items-center">
                        <i class="bi bi-signpost-split-fill text-primary me-2" style="font-size: 24px;"></i>
                        <span>Toplam Rota</span>
                    </h5>
                    <h3 class="fw-bold">{{ $rotalar->count() }}</h3>
                </div>
            </div>
        </a>
    </div>
    <div class="col">
        <a href="{{ route('matched.products') }}" class="text-decoration-none text-dark">
            <div class="card shadow border-0 h-100 hover-grow">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3 d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success me-2" style="font-size: 24px;"></i> 
                        <span>Eşleşen Yük Sayısı</span>
                    </h5>
                    <h3 class="fw-bold">{{ $matchedYukCount }}</h3>
                </div>
            </div>
        </a>
    </div>
    <div class="col">
        <a href="{{ route('ships.index') }}" class="text-decoration-none text-dark">
            <div class="card shadow border-0 h-100 hover-grow">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3 d-flex align-items-center">
                        <i class="fa-solid fa-ship text-primary me-2" style="font-size: 24px;"></i> 
                        <span>Gemilerim</span>
                    </h5>
                    <h3 class="fw-bold">{{ $gemiCount }}</h3>
                </div>
            </div>
        </a>
    </div>
</div>

{{-- Rotalar Listesi --}}
<div class="row">
    <div class="col-md-12">
        <div class="mb-4 text-end">
            <a href="{{ route('gemi_routes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Yeni Rota Oluştur
            </a>
        </div>
        <h4 class="fw-semibold mb-3">Rotaların</h4>
        @forelse($rotalar as $rota)
            <div class="card mb-3 shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
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
                    <div class="text-end">
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
</div>
<style>
    .hover-grow {
        transition: transform 0.3s ease-in-out;
    }
    .hover-grow:hover {
        transform: scale(1.03);
    }
</style>
@endsection
