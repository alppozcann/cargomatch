@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="section-title">Yük Detayları</h1>
                <div>
                    <a href="{{ route('yukler.edit', $yuk) }}" class="btn btn-primary me-2">
                        <i class="bi bi-pencil me-2"></i>Düzenle
                    </a>
                    <form action="{{ route('yukler.destroy', $yuk) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bu yükü silmek istediğinize emin misiniz?')">
                            <i class="bi bi-trash me-2"></i>Sil
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-lg mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Yük Bilgileri</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Başlık</h6>
                        <p class="mb-0">{{ $yuk->title }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Yük Türü</h6>
                        <p class="mb-0">{{ $yuk->yuk_type }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Ağırlık</h6>
                        <p class="mb-0">{{ number_format($yuk->weight, 2) }} kg</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Boyutlar</h6>
                        <p class="mb-0">
                            @if($yuk->dimensions)
                                {{ $yuk->dimensions['length'] ?? '?' }} x 
                                {{ $yuk->dimensions['width'] ?? '?' }} x 
                                {{ $yuk->dimensions['height'] ?? '?' }} cm
                            @else
                                Belirtilmemiş
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Rota</h6>
                        <p class="mb-0">{{ $yuk->from_location }} → {{ $yuk->to_location }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Teklif Edilen Fiyat</h6>
                        <p class="mb-0">{{ number_format($yuk->proposed_price, 2) }} TL</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">İstenen Teslimat Tarihi</h6>
                        <p class="mb-0">{{ optional($yuk->desired_delivery_date)->format('d.m.Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Durum</h6>
                        <p class="mb-0">
                            @switch($yuk->status)
                                @case('active')
                                    <span class="badge bg-success">Aktif</span>
                                    @break
                                @case('matched')
                                    <span class="badge bg-primary">Eşleşti</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-info">Tamamlandı</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ $yuk->status }}</span>
                            @endswitch
                        </p>
                    </div>
                    
                    <!-- Eşleşme Durumu Bilgileri -->
                    @if($yuk->match_status)
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Eşleşme Durumu</h6>
                        <p class="mb-0">
                            @switch($yuk->match_status)
                                @case('pending')
                                    <span class="badge bg-warning">Onay Bekliyor</span>
                                    @break
                                @case('confirmed')
                                    <span class="badge bg-info">Onaylandı</span>
                                    @break
                                @case('delivering')
                                    <span class="badge bg-primary">Taşınıyor</span>
                                    @break
                                @case('delivered')
                                    <span class="badge bg-success">Teslim Edildi</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ $yuk->match_status }}</span>
                            @endswitch
                        </p>
                    </div>
                    @endif
                    
                    @if($yuk->matched_at)
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Eşleşme Tarihi</h6>
                        <p class="mb-0">{{ $yuk->matched_at->format('d.m.Y H:i') }}</p>
                    </div>
                    @endif
                    
                    @if($yuk->match_notes)
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Eşleşme Notları</h6>
                        <p class="mb-0">{{ $yuk->match_notes }}</p>
                    </div>
                    @endif
                    
                    @if($yuk->description)
                        <div class="mb-3">
                            <h6 class="text-muted mb-2">Açıklama</h6>
                            <p class="mb-0">{{ $yuk->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Eşleşebilecek Rotalar</h5>
                        <div class="d-flex align-items-center">
                            <label class="me-2">Sırala:</label>
                            <select class="form-select" id="sortRoutes" style="width: auto;">
                                <option value="score">Eşleşme Puanı</option>
                                <option value="price">Fiyat</option>
                                <option value="date">Tarih</option>
                                <option value="capacity">Kapasite</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($matchingRoutes->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Rota</th>
                                        <th>Kapasite</th>
                                        <th>Fiyat</th>
                                        <th>Tarih</th>
                                        <th>Eşleşme</th>
                                        <th class="text-end">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody id="routesTableBody">
                                    @foreach($matchingRoutes as $route)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <h6 class="mb-0">{{ $route->title }}</h6>
                                                        <small class="text-muted">{{ $route->start_location }} → {{ $route->end_location }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ number_format($route->available_capacity, 2) }} kg</td>
                                            <td>{{ number_format($route->price, 2) }} TL</td>
                                            <td>
                                                <div>Kalkış: {{ optional($route->departure_date)->format('d.m.Y') }}</div>
                                                <div>Varış: {{ optional($route->arrival_date)->format('d.m.Y') }}</div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1" style="height: 6px;">
                                                        <div class="progress-bar bg-success" role="progressbar" 
                                                             style="width: {{ $route->match_score * 100 }}%"
                                                             aria-valuenow="{{ $route->match_score * 100 }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100"></div>
                                                    </div>
                                                    <span class="ms-2">{{ number_format($route->match_score * 100, 0) }}%</span>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('gemi_routes.show', $route) }}" class="btn btn-sm btn-outline-primary me-2">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @if($yuk->status === 'active')
                                                    <form action="{{ route('yukler.request_match', ['yuk' => $yuk, 'gemiRoute' => $route]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Bu rotaya eşleşme talebi göndermek istediğinize emin misiniz?')">
                                                            <i class="bi bi-link-45deg"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            Bu yük için eşleşebilecek rota bulunamadı.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($yuk->matched_gemi_route_id)
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Eşleşen Rota</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Rota Başlığı</h6>
                        <p class="mb-3">{{ $yuk->matchedGemiRoute->title }}</p>
                        
                        <h6 class="text-muted mb-2">Rota</h6>
                        <p class="mb-3">{{ $yuk->matchedGemiRoute->start_location }} → {{ $yuk->matchedGemiRoute->end_location }}</p>
                        
                        <h6 class="text-muted mb-2">Kapasite</h6>
                        <p class="mb-3">{{ number_format($yuk->matchedGemiRoute->available_capacity, 2) }} kg</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Fiyat</h6>
                        <p class="mb-3">{{ number_format($yuk->matchedGemiRoute->price, 2) }} TL</p>
                        
                        <h6 class="text-muted mb-2">Tarih</h6>
                        <p class="mb-3">
                            Kalkış: {{ optional($yuk->matchedGemiRoute->departure_date)->format('d.m.Y') }}<br>
                            Varış: {{ optional($yuk->matchedGemiRoute->arrival_date)->format('d.m.Y') }}
                        </p>
                        
                        <h6 class="text-muted mb-2">Gemi Sahibi</h6>
                        <p class="mb-3">{{ $yuk->matchedGemiRoute->user->name }}</p>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h6 class="text-muted mb-2">Eşleşme İşlemleri</h6>
                        <div class="d-flex gap-2">
                            @if($yuk->match_status === 'pending')
                                <form action="{{ route('yukler.cancel_match', $yuk) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Eşleşme talebini iptal etmek istediğinize emin misiniz?')">
                                        <i class="bi bi-x-circle me-2"></i>Eşleşme Talebini İptal Et
                                    </button>
                                </form>
                            @elseif($yuk->match_status === 'confirmed')
                                <form action="{{ route('yukler.mark_as_delivering', $yuk) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-truck me-2"></i>Yükü Teslim Et
                                    </button>
                                </form>
                            @elseif($yuk->match_status === 'delivering')
                                <form action="{{ route('yukler.complete_delivery', $yuk) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle me-2"></i>Teslimatı Tamamla
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('gemi_routes.show', $yuk->matchedGemiRoute) }}" class="btn btn-outline-primary">
                                <i class="bi bi-eye me-2"></i>Rotayı Görüntüle
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sortSelect = document.getElementById('sortRoutes');
        const tableBody = document.getElementById('routesTableBody');
        
        if (sortSelect && tableBody) {
            sortSelect.addEventListener('change', function() {
                const rows = Array.from(tableBody.querySelectorAll('tr'));
                const sortBy = this.value;
                
                rows.sort((a, b) => {
                    let aValue, bValue;
                    
                    if (sortBy === 'score') {
                        aValue = parseFloat(a.querySelector('.progress-bar').style.width);
                        bValue = parseFloat(b.querySelector('.progress-bar').style.width);
                    } else if (sortBy === 'price') {
                        aValue = parseFloat(a.querySelector('td:nth-child(3)').textContent.replace(/[^\d.-]/g, ''));
                        bValue = parseFloat(b.querySelector('td:nth-child(3)').textContent.replace(/[^\d.-]/g, ''));
                    } else if (sortBy === 'date') {
                        aValue = new Date(a.querySelector('td:nth-child(4)').textContent.split('Varış: ')[1].trim());
                        bValue = new Date(b.querySelector('td:nth-child(4)').textContent.split('Varış: ')[1].trim());
                    } else if (sortBy === 'capacity') {
                        aValue = parseFloat(a.querySelector('td:nth-child(2)').textContent.replace(/[^\d.-]/g, ''));
                        bValue = parseFloat(b.querySelector('td:nth-child(2)').textContent.replace(/[^\d.-]/g, ''));
                    }
                    
                    return bValue - aValue;
                });
                
                tableBody.innerHTML = '';
                rows.forEach(row => tableBody.appendChild(row));
            });
        }
    });
</script>
@endsection
