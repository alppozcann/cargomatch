@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Gemi Rotaları</h1>
            @auth
                @if(Auth::user()->isGemici())
                    <a href="{{ route('gemi_routes.create') }}" class="btn btn-primary">Yeni Rota Ekle</a>
                @endif
            @endauth
        </div>
        
        @if($routes->count() > 0)
            <div class="row">
                @foreach($routes as $gemiRoute)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0">{{ $gemiRoute->title }}</h5>
                                <span class="badge bg-primary">{{ number_format($gemiRoute->price, 2) }} TL</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <p class="mb-1"><strong>Rota:</strong> {{ $gemiRoute->start_location }} → {{ $gemiRoute->end_location }}</p>
                                    <p class="mb-1"><strong>Boş Kapasite:</strong> {{ number_format($gemiRoute->available_capacity, 2) }} kg</p>
                                    <p class="mb-0">
                                        <strong>Tarih:</strong> 
                                        {{ $gemiRoute->departure_date->format('d.m.Y') }} - 
                                        {{ $gemiRoute->arrival_date->format('d.m.Y') }}
                                    </p>
                                </div>
                                <p class="card-text">{{ \Illuminate\Support\Str::limit($gemiRoute->description, 100) }}</p>
                            </div>
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <small class="text-muted">{{ $gemiRoute->user->name }} tarafından</small>
                                <a href="{{ route('gemi_routes.show', $gemiRoute) }}" class="btn btn-sm btn-outline-primary">Detaylar</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                Henüz aktif gemi rotası bulunmamaktadır.
                @auth
                    @if(Auth::user()->isGemici())
                        İlk rotayı siz ekleyebilirsiniz!
                    @endif
                @endauth
            </div>
        @endif
    </div>
</div>
@endsection
