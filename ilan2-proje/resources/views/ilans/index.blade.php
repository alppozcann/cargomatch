@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>İlanlar</h1>
            @auth
                <a href="{{ route('ilans.create') }}" class="btn btn-primary">Yeni İlan Ekle</a>
            @endauth
        </div>
        
        <!-- İlanlar Listesi -->
        @if(isset($ilanlar) && $ilanlar->count() > 0)
            <div class="row">
                @foreach($ilanlar as $ilan)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0">{{ $ilan->baslik }}</h5>
                                <span class="badge bg-primary">{{ number_format($ilan->fiyat, 2) }} TL</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{ \Illuminate\Support\Str::limit($ilan->aciklama, 100) }}</p>
                            </div>
                            <div class="card-footer d-flex justify-content-between">
                                <small class="text-muted">{{ $ilan->created_at->diffForHumans() }}</small>
                                <a href="{{ route('ilans.show', $ilan) }}" class="btn btn-sm btn-outline-primary">Detaylar</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                Henüz ilan bulunmamaktadır. 
                @auth
                    İlk ilanı siz ekleyebilirsiniz!
                @else
                    İlan eklemek için lütfen <a href="{{ route('login') }}">giriş yapın</a> veya <a href="{{ route('register') }}">üye olun</a>.
                @endauth
            </div>
        @endif
    </div>
</div>
@endsection
