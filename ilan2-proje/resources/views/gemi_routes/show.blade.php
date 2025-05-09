@extends('layouts.app')

@section('content')
<div class="container-fluid px-3">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 fw-bold">{{ $gemiRoute->title }}</h4>
                    <span class="badge bg-primary fs-5 px-3 py-2">{{ number_format($gemiRoute->price, 2) }} {{$gemiRoute->currency_type}}</span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3">Rota Bilgileri</h5>
                            <div class="mb-3">
                                <p class="mb-2"><strong>Gemi Adı:</strong> 
                                    @if($gemiRoute->ship)
                                        <span class="text-primary">{{ $gemiRoute->ship->ship_name }}</span>
                                    @else
                                        <span class="text-muted">Belirtilmemiş</span>
                                    @endif
                                </p>
                                <p class="mb-2"><strong>Başlangıç:</strong> <span class="text-primary">{{ $startPort->name ?? 'Bilinmiyor' }}</span></p>
                                <p class="mb-2"><strong>Bitiş:</strong> <span class="text-primary">{{ $endPort->name ?? 'Bilinmiyor' }}</span></p>
                            </div>
                            @if(count($gemiRoute->way_points) > 0)
                                <div class="mb-3">
                                    <p class="mb-2"><strong>Ara Duraklar ve Tahmini Varış Tarihleri:</strong></p>
                                    <div class="list-group list-group-flush">
                                        @foreach($gemiRoute->way_points as $point)
                                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 border-0 bg-transparent">
                                                <span class="text-primary">{{ \App\Models\Port::find($point['port_id'])?->name ?? 'Bilinmiyor' }}</span>
                                                <span class="badge bg-light text-dark">{{ isset($point['date']) ? \Carbon\Carbon::parse($point['date'])->format('d.m.Y') : 'Tarih yok' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="mb-3">
                                <p class="mb-2">
                                    <strong>Hareket Tarihi:</strong> 
                                    <span class="text-primary">{{ optional($gemiRoute->departure_date)->format('d.m.Y') }}</span>
                                </p>
                                <p class="mb-2">
                                    <strong>Varış Tarihi:</strong> 
                                    <span class="text-primary">{{ optional($gemiRoute->arrival_date)->format('d.m.Y') }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-3">Kapasite ve Fiyat</h5>
                            <div class="mb-3">
                                <p class="mb-2"><strong>Boş Kapasite:</strong> <span class="text-primary">{{ number_format($gemiRoute->available_capacity, 2) }} {{$gemiRoute->weight_type}}</span></p>
                                <p class="mb-2"><strong>Fiyat:</strong> <span class="text-primary">{{ number_format($gemiRoute->price, 2) }} {{$gemiRoute->currency_type}}</span></p>
                                <p class="mb-2">
                                    <strong>Durum:</strong>
                                    @if($gemiRoute->status === 'active')
                                        <span class="badge bg-success px-3 py-2">Aktif</span>
                                    @elseif($gemiRoute->status === 'completed')
                                        <span class="badge bg-secondary px-3 py-2">Tamamlandı</span>
                                    @else
                                        <span class="badge bg-warning px-3 py-2">{{ $gemiRoute->status }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    @if($gemiRoute->description)
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Açıklama</h5>
                            <p class="text-muted">{{ $gemiRoute->description }}</p>
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Gemici Bilgileri</h5>
                        <p class="mb-2"><strong>Ad Soyad:</strong> <span class="text-primary">{{ $gemiRoute->user->name }}</span></p>
                        @if($gemiRoute->user->description)
                            <p class="mb-2"><strong>Hakkında:</strong> <span class="text-muted">{{ $gemiRoute->user->description }}</span></p>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('gemi_routes.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i>Geri Dön
                        </a>
                        
                        @auth
                            @if(Auth::id() === $gemiRoute->user_id)
                                <div>
                                    <a href="{{ route('gemi_routes.edit', $gemiRoute) }}" class="btn btn-primary">
                                        <i class="fas fa-edit me-2"></i>Düzenle
                                    </a>
                                    <form action="{{ route('gemi_routes.destroy', $gemiRoute) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bu rotayı silmek istediğinize emin misiniz?')">
                                            <i class="fas fa-trash me-2"></i>Sil
                                        </button>
                                    </form>
                                </div>
                            @elseif(Auth::user()->isYukVeren())
                                <!-- Burada yük veren kullanıcı için işlem butonları olabilir -->
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
            
            @if(Auth::id() === $gemiRoute->user_id && isset($matchingYukler) && $matchingYukler->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h4 class="mb-0 fw-bold">Bu Rotaya Uygun Yükler</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Yük</th>
                                        <th>Ağırlık</th>
                                        <th>Teklif</th>
                                        <th>Yük Veren</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($matchingYukler as $yuk)
                                        <tr>
                                            <td>
                                                <a href="{{ route('yukler.show', $yuk) }}" class="text-decoration-none fw-semibold">{{ $yuk->title }}</a>
                                                <small class="d-block text-muted">{{ $yuk->yuk_type }}</small>
                                            </td>
                                            <td>{{ number_format($yuk->weight, 2) }} kg</td>
                                            <td>{{ number_format($yuk->proposed_price, 2) }} TL</td>
                                            <td>{{ $yuk->user->name }}</td>
                                            <td>
                                                @if($yuk->match_status)
                                                    <span class="badge 
                                                        @if($yuk->match_status === 'pending') bg-warning
                                                        @elseif($yuk->match_status === 'confirmed') bg-info
                                                        @elseif($yuk->match_status === 'delivering') bg-primary
                                                        @elseif($yuk->match_status === 'delivered') bg-success
                                                        @else bg-secondary
                                                        @endif px-3 py-2">
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
                                                    <form action="{{ route('gemi_routes.match_yuk', ['gemiRoute' => $gemiRoute, 'yuk' => $yuk]) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Bu yükü rotanıza eklemek istediğinize emin misiniz?')">
                                                            <i class="fas fa-link me-1"></i>Eşleştir
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Eşleşen Yükler</h5>
                </div>
                <div class="card-body">
                    @if($gemiRoute->matchedYukler()->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($gemiRoute->matchedYukler as $yuk)
                                <div class="list-group-item border-0 mb-3 bg-light rounded">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <a href="{{ route('yukler.show', $yuk) }}" class="text-decoration-none fw-semibold">{{ $yuk->title }}</a>
                                            <small class="d-block text-muted">{{ number_format($yuk->weight, 2) }} kg</small>
                                            <small class="d-block text-muted">{{ $yuk->fromPort->name ?? $yuk->from_location }} → {{$yuk->toPort->name ?? $yuk->to_location}}</small>
                                            <small class="d-block mt-2">
                                                <span class="badge 
                                                    @if($yuk->match_status === 'pending') bg-warning
                                                    @elseif($yuk->match_status === 'confirmed') bg-info
                                                    @elseif($yuk->match_status === 'delivering') bg-primary
                                                    @elseif($yuk->match_status === 'delivered') bg-success
                                                    @else bg-secondary
                                                    @endif px-3 py-2">
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
                                            </small>
                                            @if($yuk->match_status === 'pending')
                                                <div class="mt-2">
                                                    <form action="{{ route('matched-cargos.approve', $yuk->pivot->id ?? $yuk->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            <i class="fas fa-check me-1"></i>Onayla
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('matched-cargos.reject', $yuk->pivot->id ?? $yuk->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-times me-1"></i>Reddet
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                        <span class="badge bg-primary px-3 py-2">{{ number_format($yuk->proposed_price, 2) }} TL</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="d-grid mt-3">
                            <a href="#" class="btn btn-outline-primary">
                                <i class="fas fa-list me-2"></i>Eşleşen Tüm Yükleri Görüntüle
                            </a>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Henüz bu rotaya eşleşen yük bulunmamaktadır.
                        </div>
                    @endif
                </div>
            </div>
            
            @if(Auth::check() && Auth::user()->isYukVeren() && Auth::id() !== $gemiRoute->user_id)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold">Yükünüz için mi arıyorsunuz?</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Taşınacak bir yükünüz varsa, bu rotaya ekleyebilirsiniz.</p>
                        <div class="d-grid">
                            <a href="{{ route('yukler.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Yeni Yük Ekle
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
