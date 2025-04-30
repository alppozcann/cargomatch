@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $gemiRoute->title }}</h4>
                <span class="badge bg-primary fs-5">{{ number_format($gemiRoute->price, 2) }} {{$gemiRoute->currency_type}}</span>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Rota Bilgileri</h5>
                        <p class="mb-1"><strong>Gemi Adı:</strong> 
                                        @if($gemiRoute->ship)
                                            {{ $gemiRoute->ship->ship_name }}
                                        @else
                                            <span class="text-muted">Belirtilmemiş</span>
                                        @endif
                                    </p>
                        <p class="mb-1"><strong>Başlangıç:</strong> {{ $startPort->name ?? 'Bilinmiyor' }}</p>
                        <p class="mb-1"><strong>Bitiş:</strong> {{ $endPort->name ?? 'Bilinmiyor' }}</p>
                        @if(count($gemiRoute->way_points) > 0)
                            <p class="mb-1">
                                <strong>Ara Duraklar:</strong>
                                {{ implode(' → ', $waypoints) }}
                            </p>
                        @endif
                        <p class="mb-1">
                            <strong>Hareket Tarihi:</strong> 
                            {{ optional($gemiRoute->departure_date)->format('d.m.Y') }}
                            </p>
                        <p class="mb-1">
                            <strong>Varış Tarihi:</strong> 
                            {{ optional($gemiRoute->arrival_date)->format('d.m.Y') }}
                            </p>
                    </div>
                    <div class="col-md-6">
                        <h5>Kapasite ve Fiyat</h5>
                        <p class="mb-1"><strong>Boş Kapasite:</strong> {{ number_format($gemiRoute->available_capacity, 2) }} {{$gemiRoute->weight_type}}</p>
                        <p class="mb-1"><strong>Fiyat:</strong> {{ number_format($gemiRoute->price, 2) }} {{$gemiRoute->currency_type}}</p>
                        <p class="mb-1">
                            <strong>Durum:</strong>
                            @if($gemiRoute->status === 'active')
                                <span class="badge bg-success">Aktif</span>
                            @elseif($gemiRoute->status === 'completed')
                                <span class="badge bg-secondary">Tamamlandı</span>
                            @else
                                <span class="badge bg-warning">{{ $gemiRoute->status }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                @if($gemiRoute->description)
                    <div class="mb-4">
                        <h5>Açıklama</h5>
                        <p>{{ $gemiRoute->description }}</p>
                    </div>
                @endif
                
                <div class="mb-3">
                    <h5>Gemici Bilgileri</h5>
                    <p class="mb-1"><strong>Ad Soyad:</strong> {{ $gemiRoute->user->name }}</p>
                    @if($gemiRoute->user->description)
                        <p class="mb-1"><strong>Hakkında:</strong> {{ $gemiRoute->user->description }}</p>
                    @endif
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('gemi_routes.index') }}" class="btn btn-secondary">Geri Dön</a>
                    
                    @auth
                        @if(Auth::id() === $gemiRoute->user_id)
                            <div>
                                <a href="{{ route('gemi_routes.edit', $gemiRoute) }}" class="btn btn-primary">Düzenle</a>
                                <form action="{{ route('gemi_routes.destroy', $gemiRoute) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bu rotayı silmek istediğinize emin misiniz?')">Sil</button>
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
            <div class="card">
                <div class="card-header">
                    <h4>Bu Rotaya Uygun Yükler</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
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
                                            <a href="{{ route('yukler.show', $yuk) }}">{{ $yuk->title }}</a>
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
                                                <form action="{{ route('gemi_routes.match_yuk', ['gemiRoute' => $gemiRoute, 'yuk' => $yuk]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Bu yükü rotanıza eklemek istediğinize emin misiniz?')">Eşleştir</button>
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
        <div class="card mb-4">
            <div class="card-header">
                <h5>Eşleşen Yükler</h5>
            </div>
            <div class="card-body">
                @if($gemiRoute->matchedYukler()->count() > 0)
                    <ul class="list-group mb-3">
                        @foreach($gemiRoute->matchedYukler as $yuk)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('yukler.show', $yuk) }}" class="text-decoration-none">{{ $yuk->title }}</a>
                                    <small class="d-block text-muted">{{ number_format($yuk->weight, 2) }} kg</small>
                                    <small class="d-block text-muted">{{ $yuk->fromPort->name ?? $yuk->from_location }} -> {{$yuk->toPort->name ?? $yuk->to_location}}</small>
                                    <small class="d-block">
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
                                    </small>
                                    @if($yuk->match_status === 'pending')
                                        <div class="mt-2">
                                            <form action="{{ route('matched-cargos.approve', $yuk->pivot->id ?? $yuk->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Onayla</button>
                                            </form>
                                            <form action="{{ route('matched-cargos.reject', $yuk->pivot->id ?? $yuk->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">Reddet</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ number_format($yuk->proposed_price, 2) }} TL</span>
                            </li>
                        @endforeach
                    </ul>
                    
                    <div class="d-grid">
                        <a href="#" class="btn btn-outline-primary">Eşleşen Tüm Yükleri Görüntüle</a>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        Henüz bu rotaya eşleşen yük bulunmamaktadır.
                    </div>
                @endif
            </div>
        </div>
        
        @if(Auth::check() && Auth::user()->isYukVeren() && Auth::id() !== $gemiRoute->user_id)
            <div class="card">
                <div class="card-header">
                    <h5>Yükünüz için mi arıyorsunuz?</h5>
                </div>
                <div class="card-body">
                    <p>Taşınacak bir yükünüz varsa, bu rotaya ekleyebilirsiniz.</p>
                    <div class="d-grid">
                        <a href="{{ route('yukler.create') }}" class="btn btn-success">Yeni Yük Ekle</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
