@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="section-title">Yüklerim</h1>
                <a href="{{ route('yukler.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Yeni Yük Ekle
                </a>
            </div>
        </div>
    </div>

    @if($yukler->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-white py-3">
                        <ul class="nav nav-tabs card-header-tabs" id="yuklerTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">
                                    <i class="bi bi-box-seam me-2"></i>Aktif Yükler
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="matched-tab" data-bs-toggle="tab" data-bs-target="#matched" type="button" role="tab" aria-controls="matched" aria-selected="false">
                                    <i class="bi bi-link-45deg me-2"></i>Eşleşmiş Yükler
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">
                                    <i class="bi bi-check-circle me-2"></i>Tamamlanmış Yükler
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content" id="yuklerTabsContent">
                            <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Başlık</th>
                                                <th>Yük Türü</th>
                                                <th>Ağırlık</th>
                                                <th>Nereden</th>
                                                <th>Nereye</th>
                                                <th>Fiyat</th>
                                                <th>Teslimat Tarihi</th>
                                                <th>Eşleşme Durumu</th>
                                                <th class="text-end">İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($yukler->where('status', 'active') as $yuk)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="ms-3">
                                                                <h6 class="mb-0">{{ $yuk->title }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-info">{{ $yuk->yuk_type }}</span></td>
                                                    <td>{{ number_format($yuk->weight, 2) }} kg</td>
                                                    <td>{{ $yuk->from_location }}</td>
                                                    <td>{{ $yuk->to_location }}</td>
                                                    <td><strong>{{ number_format($yuk->proposed_price, 2) }} TL</strong></td>
                                                    <td>{{ $yuk->desired_delivery_date->format('d.m.Y') }}</td>
                                                    <td>
                                                        @if($yuk->match_status)
                                                            <span class="badge 
                                                                @if($yuk->match_status === 'pending') bg-warning
                                                                @elseif($yuk->match_status === 'confirmed') bg-info
                                                                @elseif($yuk->match_status === 'delivering') bg-primary
                                                                @elseif($yuk->match_status === 'delivered') bg-success
                                                                @else bg-secondary
                                                                @endif">
                                                                @switch($yuk->match_status)
                                                                    @case('pending')
                                                                        Onay Bekliyor
                                                                        @break
                                                                    @case('confirmed')
                                                                        Onaylandı
                                                                        @break
                                                                    @case('delivering')
                                                                        Taşınıyor
                                                                        @break
                                                                    @case('delivered')
                                                                        Teslim Edildi
                                                                        @break
                                                                    @default
                                                                        {{ $yuk->match_status }}
                                                                @endswitch
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">Eşleşme Yok</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-end">
                                                            <a href="{{ route('yukler.show', $yuk) }}" class="btn btn-sm btn-outline-info me-2">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <a href="{{ route('yukler.edit', $yuk) }}" class="btn btn-sm btn-outline-primary me-2">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <form action="{{ route('yukler.destroy', $yuk) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu yükü silmek istediğinize emin misiniz?')">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="matched" role="tabpanel" aria-labelledby="matched-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Başlık</th>
                                                <th>Yük Türü</th>
                                                <th>Ağırlık</th>
                                                <th>Nereden</th>
                                                <th>Nereye</th>
                                                <th>Fiyat</th>
                                                <th>Teslimat Tarihi</th>
                                                <th>Eşleşme Durumu</th>
                                                <th class="text-end">İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($yukler->where('status', 'matched') as $yuk)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="ms-3">
                                                                <h6 class="mb-0">{{ $yuk->title }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-info">{{ $yuk->yuk_type }}</span></td>
                                                    <td>{{ number_format($yuk->weight, 2) }} kg</td>
                                                    <td>{{ $yuk->from_location }}</td>
                                                    <td>{{ $yuk->to_location }}</td>
                                                    <td><strong>{{ number_format($yuk->proposed_price, 2) }} TL</strong></td>
                                                    <td>{{ $yuk->desired_delivery_date->format('d.m.Y') }}</td>
                                                    <td>
                                                        @if($yuk->match_status)
                                                            <span class="badge 
                                                                @if($yuk->match_status === 'pending') bg-warning
                                                                @elseif($yuk->match_status === 'confirmed') bg-info
                                                                @elseif($yuk->match_status === 'delivering') bg-primary
                                                                @elseif($yuk->match_status === 'delivered') bg-success
                                                                @else bg-secondary
                                                                @endif">
                                                                @switch($yuk->match_status)
                                                                    @case('pending')
                                                                        Onay Bekliyor
                                                                        @break
                                                                    @case('confirmed')
                                                                        Onaylandı
                                                                        @break
                                                                    @case('delivering')
                                                                        Taşınıyor
                                                                        @break
                                                                    @case('delivered')
                                                                        Teslim Edildi
                                                                        @break
                                                                    @default
                                                                        {{ $yuk->match_status }}
                                                                @endswitch
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">Eşleşme Yok</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-end">
                                                            <a href="{{ route('yukler.show', $yuk) }}" class="btn btn-sm btn-outline-info me-2">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <form action="{{ route('yukler.complete_delivery', $yuk) }}" method="POST" class="d-inline me-2">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                                    <i class="bi bi-check-lg"></i>
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('yukler.cancel_match', $yuk) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Eşleşmeyi iptal etmek istediğinize emin misiniz?')">
                                                                    <i class="bi bi-x-lg"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Başlık</th>
                                                <th>Yük Türü</th>
                                                <th>Ağırlık</th>
                                                <th>Nereden</th>
                                                <th>Nereye</th>
                                                <th>Fiyat</th>
                                                <th>Teslimat Tarihi</th>
                                                <th>Eşleşme Durumu</th>
                                                <th class="text-end">İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($yukler->where('status', 'completed') as $yuk)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="ms-3">
                                                                <h6 class="mb-0">{{ $yuk->title }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-info">{{ $yuk->yuk_type }}</span></td>
                                                    <td>{{ number_format($yuk->weight, 2) }} kg</td>
                                                    <td>{{ $yuk->from_location }}</td>
                                                    <td>{{ $yuk->to_location }}</td>
                                                    <td><strong>{{ number_format($yuk->proposed_price, 2) }} TL</strong></td>
                                                    <td>{{ $yuk->desired_delivery_date->format('d.m.Y') }}</td>
                                                    <td>
                                                        @if($yuk->match_status)
                                                            <span class="badge 
                                                                @if($yuk->match_status === 'pending') bg-warning
                                                                @elseif($yuk->match_status === 'confirmed') bg-info
                                                                @elseif($yuk->match_status === 'delivering') bg-primary
                                                                @elseif($yuk->match_status === 'delivered') bg-success
                                                                @else bg-secondary
                                                                @endif">
                                                                @switch($yuk->match_status)
                                                                    @case('pending')
                                                                        Onay Bekliyor
                                                                        @break
                                                                    @case('confirmed')
                                                                        Onaylandı
                                                                        @break
                                                                    @case('delivering')
                                                                        Taşınıyor
                                                                        @break
                                                                    @case('delivered')
                                                                        Teslim Edildi
                                                                        @break
                                                                    @default
                                                                        {{ $yuk->match_status }}
                                                                @endswitch
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">Eşleşme Yok</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-end">
                                                            <a href="{{ route('yukler.show', $yuk) }}" class="btn btn-sm btn-outline-info">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-box-seam text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h3 class="mb-3">Henüz hiç yük ilanınız bulunmamaktadır</h3>
                        <p class="text-muted mb-4">Yeni bir yük ilanı ekleyerek taşımacılık hizmetlerinden faydalanmaya başlayabilirsiniz.</p>
                        <a href="{{ route('yukler.create') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle me-2"></i>Yeni Yük Ekle
                        </a>
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
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        font-weight: 500;
        padding: 1rem 1.5rem;
        transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link:hover {
        color: var(--bs-primary);
    }
    
    .nav-tabs .nav-link.active {
        color: var(--bs-primary);
        border-bottom: 3px solid var(--bs-primary);
        background-color: transparent;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endsection 