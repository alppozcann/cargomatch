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
                        <p class="mb-0">{{ optional($yuk->fromPort)->name }} → {{ optional($yuk->toPort)->name }}</p>

                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">İstenen Teslimat Tarihi</h6>
                        <p class="mb-0">{{ $yuk->desired_delivery_date?->format('d.m.Y') ?? 'Belirtilmemiş' }}</p>

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
                    
                </div>
            </div>
        </div>

        <div class="col-md-8">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">Bu Yükle Potansiyel Olarak Eşleşebilecek Diğer Rotalar</h5>
        </div>
        <div class="card-body">
            @if($matchingRoutes && count($matchingRoutes) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Rota</th>
                            <th>Kapasite</th>
                            <th>Fiyat</th>
                            <th>Tarih</th>
                            <th>Sahibi</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody id="routesTableBody">
                        @foreach($matchingRoutes as $rota)
                        <tr>
                            <td>
                            @php
    $fromId = $yuk->from_location;
    $toId = $yuk->to_location;
    $waypointIds = collect($rota->way_points ?? [])->values();

    $fromIdx = $waypointIds->search(fn($val) => (int)$val === (int)$fromId);
    $toIdx = $waypointIds->search(fn($val) => (int)$val === (int)$toId);

    if ($waypointIds->isEmpty()) {
        echo optional($rota->startPort)->name . " → " . optional($rota->endPort)->name;
    } elseif ($fromIdx !== false && $toIdx !== false && $fromIdx < $toIdx) {
        $fromName = optional(\App\Models\Port::find($fromId))->name;
        $toName = optional(\App\Models\Port::find($toId))->name;

        if ($fromIdx === 0 && $toIdx === $waypointIds->count() - 1) {
            echo "$fromName → ... → $toName";
        } elseif ($fromIdx === 0) {
            echo "$fromName → ... → $toName → ...";
        } elseif ($toIdx === $waypointIds->count() - 1) {
            echo "... → $fromName → ... → $toName";
        } else {
            echo "... → $fromName → ... → $toName → ...";
        }
    } else {
        echo optional($rota->startPort)->name . " → " . optional($rota->endPort)->name;
    }
@endphp
    
                            </td>
                            <td>{{ $rota->available_capacity ?? 'N/A' }} kg</td>
                            <td>{{ number_format($rota->price, 2) }} {{ $rota->currency_type ?? 'TL' }}</td>
                            <td>
                                Kalkış: {{ optional($rota->departure_date)->format('d.m.Y') }}<br>
                                Varış: {{ optional($rota->arrival_date)->format('d.m.Y') }}
                            </td>
                            <td>{{ optional($rota->user)->name ?? 'Bilinmiyor' }}</td>
                            <td>
                            <form action="{{ route('yukler.request_match', ['yuk' => $yuk->id, 'gemiRoute' => $rota->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-2"></i>Eşleştir
                                    </button>
                                </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">Bu yük için eşleşebilecek uygun rota bulunamadı.</div>
            @endif
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
                        <p class="mb-3">{{ optional($yuk->matchedGemiRoute)->title ?? 'Belirtilmemiş' }}</p>
                        
                        <h6 class="text-muted mb-2">Rota</h6>
                        <p class="mb-3">
                            {{ optional($yuk->matchedGemiRoute->startPort)->name ?? '?' }} →
                            {{ optional($yuk->matchedGemiRoute->endPort)->name ?? '?' }}
</p>

                        
                        <h6 class="text-muted mb-2">Kapasite</h6>
                        <p class="mb-3">{{ optional($yuk->matchedGemiRoute)->available_capacity ? number_format($yuk->matchedGemiRoute->available_capacity, 2) : 'Belirtilmemiş' }} kg</p>
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
                        <p class="mb-3">{{ optional(optional($yuk->matchedGemiRoute)->user)->name ?? 'Bilinmiyor' }}</p>

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
                            
                            @if($yuk->matchedGemiRoute)
                                <a href="{{ route('gemi_routes.show', $yuk->matchedGemiRoute) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-eye me-2"></i>Rotayı Görüntüle
                                </a>
                            @endif
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
