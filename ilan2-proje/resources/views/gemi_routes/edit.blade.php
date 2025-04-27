@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Gemi Rotasını Düzenle</h2>

    <form action="{{ route('gemi_routes.update', $gemiRoute->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Başlık --}}
        <div class="mb-3">
            <label for="title" class="form-label">Başlık</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $gemiRoute->title) }}" required>
        </div>

        {{-- Başlangıç Limanı (readonly) --}}
        <div class="mb-3">
            <label for="start_location" class="form-label">Başlangıç Limanı</label>
            <input type="text" id="start_location" name="start_location" class="form-control" value="{{ $gemiRoute->start_port_name }}" readonly>
        </div>

        {{-- Varış Limanı (readonly) --}}
        <div class="mb-3">
            <label for="end_location" class="form-label">Varış Limanı</label>
            <input type="text" id="end_location" name="end_location" class="form-control" value="{{ $gemiRoute->end_port_name }}" readonly>
        </div>

        {{-- Ara Duraklar --}}
        <div class="mb-3">
            <label for="way_points" class="form-label">Ara Duraklar (virgülle ayır)</label>
            <input type="text" id="way_points" name="way_points" class="form-control" value="{{ old('way_points', implode(',', $gemiRoute->way_points ?? [])) }}">
        </div>

        {{-- Boş Kapasite --}}
        <div class="mb-3">
            <label for="available_capacity" class="form-label">Boş Kapasite</label>
            <div class="input-group">
                <input type="number" id="available_capacity" name="available_capacity" class="form-control" value="{{ old('available_capacity', $gemiRoute->available_capacity) }}" required>
                <select name="weight_type" class="form-select" style="max-width: 100px;">
                    <option value="kg" {{ $gemiRoute->weight_type == 'kg' ? 'selected' : '' }}>kg</option>
                    <option value="ton" {{ $gemiRoute->weight_type == 'ton' ? 'selected' : '' }}>ton</option>
                </select>
            </div>
        </div>

        {{-- Fiyat --}}
        <div class="mb-3">
            <label for="price" class="form-label">Fiyat</label>
            <div class="input-group">
                <input type="number" id="price" name="price" class="form-control" value="{{ old('price', $gemiRoute->price) }}" required>
                <select name="currency_type" class="form-select" style="max-width: 100px;">
                    <option value="TRY" {{ $gemiRoute->currency_type == 'TRY' ? 'selected' : '' }}>₺</option>
                    <option value="USD" {{ $gemiRoute->currency_type == 'USD' ? 'selected' : '' }}>$</option>
                    <option value="EUR" {{ $gemiRoute->currency_type == 'EUR' ? 'selected' : '' }}>€</option>
                </select>
            </div>
        </div>

        {{-- Kalkış ve Varış Tarihleri --}}
        <div class="row mb-3">
            <div class="col">
                <label for="departure_date" class="form-label">Kalkış Tarihi</label>
                <input type="date" id="departure_date" name="departure_date" class="form-control" value="{{ old('departure_date', \Carbon\Carbon::parse($gemiRoute->departure_date)->format('Y-m-d')) }}" required>
            </div>
            <div class="col">
                <label for="arrival_date" class="form-label">Varış Tarihi</label>
                <input type="date" id="arrival_date" name="arrival_date" class="form-control" value="{{ old('arrival_date', \Carbon\Carbon::parse($gemiRoute->arrival_date)->format('Y-m-d')) }}" required>
            </div>
        </div>

        {{-- Açıklama --}}
        <div class="mb-3">
            <label for="description" class="form-label">Açıklama</label>
            <textarea id="description" name="description" rows="4" class="form-control">{{ old('description', $gemiRoute->description) }}</textarea>
        </div>

        {{-- Kaydet Butonu --}}
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Güncelle</button>
        </div>
    </form>
</div>
@endsection
