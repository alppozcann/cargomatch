@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $ilan->baslik }}</h4>
                <span class="badge bg-primary fs-5">{{ number_format($ilan->fiyat, 2) }} TL</span>
            </div>
            <div class="card-body">
                <p class="lead">{{ $ilan->aciklama }}</p>
                
                <hr>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">İlan Tarihi: {{ $ilan->created_at->format('d.m.Y H:i') }}</small>
                        @if($ilan->user)
                            <small class="text-muted d-block">İlan Sahibi: {{ $ilan->user->name }}</small>
                        @endif
                    </div>
                    
                    @auth
                        @if(Auth::id() === $ilan->user_id || Auth::user()->is_admin)
                            <div class="btn-group">
                                <a href="{{ route('ilans.edit', $ilan) }}" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                <form action="{{ route('ilans.destroy', $ilan) }}" method="POST" onsubmit="return confirm('Bu ilanı silmek istediğinize emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger ms-2">Sil</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('ilans.index') }}" class="btn btn-secondary">İlanlara Dön</a>
            </div>
        </div>
    </div>
</div>
@endsection
