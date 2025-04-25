@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="section-title">Yük İlanları</h1>
                @auth
                    @if(Auth::user()->isYukVeren())
                        <a href="{{ route('yukler.create') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle me-2"></i>Yeni Yük Ekle
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
    
    @if($yukler->count() > 0)
        <div class="row">
            @foreach($yukler as $yuk)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 rounded-lg hover-card">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 text-truncate" style="max-width: 70%;">{{ $yuk->title }}</h5>
                            <span class="badge bg-primary rounded-pill px-3 py-2">{{ number_format($yuk->proposed_price, 2) }} TL</span>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-box-seam text-primary me-2"></i>
                                    <span class="fw-medium">{{ $yuk->yuk_type }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-weight text-primary me-2"></i>
                                    <span class="fw-medium">{{ number_format($yuk->weight, 2) }} kg</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-geo-alt text-primary me-2"></i>
                                    <span class="fw-medium">{{ $yuk->from_location }} → {{ $yuk->to_location }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-calendar-event text-primary me-2"></i>
                                    <span class="fw-medium">{{ $yuk->desired_delivery_date->format('d.m.Y') }}</span>
                                </div>
                            </div>
                            @if($yuk->description)
                                <div class="border-top pt-3">
                                    <p class="card-text text-muted">{{ \Illuminate\Support\Str::limit($yuk->description, 100) }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-white border-top-0 d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-2">
                                    {{ strtoupper(substr($yuk->user->name, 0, 1)) }}
                                </div>
                                <small class="text-muted">{{ $yuk->user->name }}</small>
                            </div>
                            <a href="{{ route('yukler.show', $yuk) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>Detaylar
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-box-seam text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h3 class="mb-3">Henüz aktif yük ilanı bulunmamaktadır</h3>
                        <p class="text-muted mb-4">
                            @auth
                                @if(Auth::user()->isYukVeren())
                                    İlk yük ilanını siz ekleyerek taşımacılık hizmetlerinden faydalanmaya başlayabilirsiniz.
                                @else
                                    Henüz hiç yük ilanı eklenmemiş. Lütfen daha sonra tekrar kontrol edin.
                                @endif
                            @else
                                Henüz hiç yük ilanı eklenmemiş. Lütfen daha sonra tekrar kontrol edin.
                            @endauth
                        </p>
                        @auth
                            @if(Auth::user()->isYukVeren())
                                <a href="{{ route('yukler.create') }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-plus-circle me-2"></i>Yeni Yük Ekle
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .section-title {
        position: relative;
        margin-bottom: 0;
        font-weight: 700;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 50px;
        height: 3px;
        background-color: var(--bs-primary);
    }
    
    .hover-card {
        transition: all 0.3s ease;
    }
    
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .avatar-circle {
        width: 30px;
        height: 30px;
        background-color: var(--bs-primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }
    
    .fw-medium {
        font-weight: 500;
    }
    
    .card {
        border-radius: 0.75rem;
        overflow: hidden;
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.05);
    }
    
    .badge {
        font-weight: 500;
    }
    
    .btn-sm {
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
    }
</style>
@endsection
