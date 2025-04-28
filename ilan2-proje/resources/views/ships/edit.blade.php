@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Gemi Bilgilerini Düzenle</h2>

    <form action="{{ route('ships.update', $ship->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Gemi Adı</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $ship->ship_name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kayıt Numarası</label>
            <input type="text" class="form-control" value="{{ $ship->plate_code }}" disabled>
            <small class="text-muted">Kayıt numarası düzenlenemez.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Taşıyabildiği Yük Türleri</label>
            <select name="cargoTypes" class="form-select" multiple required>
            @foreach($cargoTypes as $type)
    <option value="{{ $type }}" {{ in_array($type, $ship->cargoTypes ?? []) ? 'selected' : '' }}>
        {{ $type }}
    </option>
@endforeach

            </select>
            <small class="text-muted">Ctrl (veya Cmd) tuşuna basılı tutarak birden fazla seçim yapabilirsiniz.</small>
        </div>

        

        <div class="text-end">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Güncelle
            </button>
        </div>
    </form>
</div>
@endsection
