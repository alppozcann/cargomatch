<!-- resources/views/dashboard/yukveren.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">Hoşgeldin, {{ $user->name }}</h2>
                <p class="text-muted">Profiline ve yük ilanlarına buradan erişebilirsin.</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Profili Düzenle
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">
                        <i class="bi bi-box-seam me-2 text-primary"></i> Toplam Yük İlanı
                    </h5>
                    <h3 class="fw-bold">{{ $yukler->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-3">
                        <i class="bi bi-check-circle me-2 text-success"></i> Eşleşen İlanlar
                    </h5>
                    <h3 class="fw-bold">{{ $yukler->where('matched_gemi_route_id', '!=', null)->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h4 class="fw-semibold mb-3">Yük İlanların</h4>
            @forelse($yukler as $yuk)
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">{{ $yuk->title }}</h5>
                            <p class="text-muted mb-0">
                                {{ $yuk->from_location }} → {{ $yuk->to_location }} |
                                {{ number_format($yuk->weight, 0) }} {{ $yuk->weight_unit }} | {{ $yuk->status }}
                            </p>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('yukler.show', $yuk) }}" class="btn btn-outline-secondary btn-sm">
                                Detaylar
                            </a>
                            @if($yuk->matched_gemi_route_id)
                                <a href="{{ route('gemi_routes.show', $yuk->matched_gemi_route_id) }}" class="btn btn-success btn-sm ms-2">
                                    Eşleşme Detayı
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Henüz bir yük ilanın yok.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection