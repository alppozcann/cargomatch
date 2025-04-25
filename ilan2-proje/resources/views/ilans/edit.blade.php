@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">İlan Düzenle</div>
            <div class="card-body">
                <form method="POST" action="{{ route('ilans.update', $ilan) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="baslik" class="form-label">İlan Başlığı</label>
                        <input type="text" class="form-control @error('baslik') is-invalid @enderror" id="baslik" name="baslik" value="{{ old('baslik', $ilan->baslik) }}" required>
                        @error('baslik')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="aciklama" class="form-label">İlan Açıklaması</label>
                        <textarea class="form-control @error('aciklama') is-invalid @enderror" id="aciklama" name="aciklama" rows="5" required>{{ old('aciklama', $ilan->aciklama) }}</textarea>
                        @error('aciklama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="fiyat" class="form-label">Fiyat (TL)</label>
                        <input type="number" step="0.01" class="form-control @error('fiyat') is-invalid @enderror" id="fiyat" name="fiyat" value="{{ old('fiyat', $ilan->fiyat) }}" required>
                        @error('fiyat')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('ilans.show', $ilan) }}" class="btn btn-secondary">İptal</a>
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
