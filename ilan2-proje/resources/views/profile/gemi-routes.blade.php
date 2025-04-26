@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="section-title">Gemi Rotalarım</h1>
                <a href="{{ route('gemi-routes.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Yeni Rota Ekle
                </a>
            </div>
        </div>
    </div>

    @if($routes->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-white py-3">
                        <ul class="nav nav-tabs card-header-tabs" id="routesTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">
                                    <i class="bi bi-box-seam me-2"></i>Aktif Rotalar
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="matched-tab" data-bs-toggle="tab" data-bs-target="#matched" type="button" role="tab" aria-controls="matched" aria-selected="false">
                                    <i class="bi bi-link-45deg me-2"></i>Eşleşmiş Rotalar
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">
                                    <i class="bi bi-check-circle me-2"></i>Tamamlanmış Rotalar
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content" id="routesTabsContent">
                            <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Başlık</th>
                                                <th>Gemi Türü</th>
                                                <th>Kapasite</th>
                                                <th>Nereden</th>
                                                <th>Nereye</th>
                                                <th>Fiyat</th>
                                                <th>Kalkış Tarihi</th>
                                                <th class="text-end">İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($routes->where('status', 'active') as $route)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="ms-3">
                                                                <h6 class="mb-0">{{ $route->title }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-info">{{ $route->ship_type }}</span></td>
                                                    <td>{{ number_format($route->capacity, 2) }} ton</td>
                                                    <td>{{ $route->from_location }}</td>
                                                    <td>{{ $route->to_location }}</td>
                                                    <td><strong>{{ number_format($route->proposed_price, 2) }} TL</strong></td>
                                                    <td>{{ optional($route->departure_date)->format('d.m.Y') }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-end">
                                                            <a href="{{ route('gemi-routes.show', $route) }}" class="btn btn-sm btn-outline-info me-2">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <a href="{{ route('gemi-routes.edit', $route) }}" class="btn btn-sm btn-outline-primary me-2">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <form action="{{ route('gemi-routes.destroy', $route) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu rotayı silmek istediğinize emin misiniz?')">
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
                                                <th>Gemi Türü</th>
                                                <th>Kapasite</th>
                                                <th>Nereden</th>
                                                <th>Nereye</th>
                                                <th>Fiyat</th>
                                                <th>Kalkış Tarihi</th>
                                                <th class="text-end">İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($routes->where('status', 'matched') as $route)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="ms-3">
                                                                <h6 class="mb-0">{{ $route->title }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-info">{{ $route->ship_type }}</span></td>
                                                    <td>{{ number_format($route->capacity, 2) }} ton</td>
                                                    <td>{{ $route->from_location }}</td>
                                                    <td>{{ $route->to_location }}</td>
                                                    <td><strong>{{ number_format($route->proposed_price, 2) }} TL</strong></td>
                                                    <td>{{ optional($route->departure_date)->format('d.m.Y') }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-end">
                                                            <a href="{{ route('gemi-routes.show', $route) }}" class="btn btn-sm btn-outline-info me-2">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <form action="{{ route('gemi-routes.complete_delivery', $route) }}" method="POST" class="d-inline me-2">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                                    <i class="bi bi-check-lg"></i>
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('gemi-routes.cancel_match', $route) }}" method="POST" class="d-inline">
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
                                                <th>Gemi Türü</th>
                                                <th>Kapasite</th>
                                                <th>Nereden</th>
                                                <th>Nereye</th>
                                                <th>Fiyat</th>
                                                <th>Kalkış Tarihi</th>
                                                <th class="text-end">İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($routes->where('status', 'completed') as $route)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="ms-3">
                                                                <h6 class="mb-0">{{ $route->title }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-info">{{ $route->ship_type }}</span></td>
                                                    <td>{{ number_format($route->capacity, 2) }} ton</td>
                                                    <td>{{ $route->from_location }}</td>
                                                    <td>{{ $route->to_location }}</td>
                                                    <td><strong>{{ number_format($route->proposed_price, 2) }} TL</strong></td>
                                                    <td>{{ optional($route->departure_date)->format('d.m.Y') }}</td>
                                                    <td>
                                                        <div class="d-flex justify-content-end">
                                                            <a href="{{ route('gemi-routes.show', $route) }}" class="btn btn-sm btn-outline-info">
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
                            <i class="bi bi-geo-alt text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h3 class="mb-3">Henüz hiç gemi rotanız bulunmamaktadır</h3>
                        <p class="text-muted mb-4">Yeni bir gemi rotası ekleyerek taşımacılık hizmetlerinden faydalanmaya başlayabilirsiniz.</p>
                        <a href="{{ route('gemi-routes.create') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle me-2"></i>Yeni Rota Ekle
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