@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Gemi Rotasını Düzenle</h2>

    <form action="{{ route('gemi_routes.update', $gemiRoute->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Başlık</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $gemiRoute->title) }}" required>
            </div>

            <div class="col-md-3 mb-3">
    <label class="form-label">Başlangıç Limanı</label>
    <select class="form-select" disabled>
        @foreach($ports as $port)
            <option value="{{ $port->id }}" {{ $gemiRoute->start_location == $port->id ? 'selected' : '' }}>
                {{ $port->name }}
            </option>
        @endforeach
    </select>
</div>

            <div class="col-md-3 mb-3">
                <label class="form-label    ">Varış Limanı</label>
                <select class="form-select" disabled>
                    @foreach($ports as $port)
                        <option value="{{ $port->id }}" {{ $gemiRoute->end_location == $port->id ? 'selected' : '' }}>
                            {{ $port->name }}
                        </option>
                    @endforeach
                </select>
</div>



<div class="mb-3">
    <label class="form-label">Ara Duraklar</label>
    <div id="waypoints-container">
        @foreach($gemiRoute->way_points as $waypointId)
            <div class="input-group mb-2 align-items-center">
                <select name="way_points[]" class="form-control rounded border border-primary"
                        style="width: 90%;">
                    <option value=""></option>
                    @foreach($ports as $port)
                        <option value="{{ $port->id }}" {{ $port->id == $waypointId ? 'selected' : '' }}>
                            {{ $port->name }}
                        </option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-danger btn-sm ms-2 remove-waypoint">Sil</button>
            </div>
        @endforeach
    </div>
    <button type="button" id="add-waypoint" class="btn btn-primary btn-sm mt-2">+ Ara Durak Ekle</button>
</div>


            <div class="col-md-6">
                <label class="form-label">Boş Kapasite</label>
                <div class="input-group">
                    <input type="number" name="available_capacity" step="0.01" class="form-control" value="{{ old('available_capacity', $gemiRoute->available_capacity) }}" required>
                    <select name="weight_type" class="form-select" style="max-width:100px;">
                        <option value="kg" {{ old('weight_type', $gemiRoute->weight_type) == 'kg' ? 'selected' : '' }}>kg</option>
                        <option value="ton" {{ old('weight_type', $gemiRoute->weight_type) == 'ton' ? 'selected' : '' }}>ton</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Fiyat</label>
                <div class="input-group">
                    <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price', $gemiRoute->price) }}" required>
                    <select name="currency_type" class="form-select" style="max-width:100px;">
                        <option value="TRY" {{ old('currency_type', $gemiRoute->currency_type) == 'TRY' ? 'selected' : '' }}>₺</option>
                        <option value="USD" {{ old('currency_type', $gemiRoute->currency_type) == 'USD' ? 'selected' : '' }}>$</option>
                        <option value="EUR" {{ old('currency_type', $gemiRoute->currency_type) == 'EUR' ? 'selected' : '' }}>€</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label">Kalkış Tarihi</label>
                <input type="date" name="departure_date" class="form-control" value="{{ old('departure_date', \Illuminate\Support\Carbon::parse($gemiRoute->departure_date)->format('Y-m-d')) }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Varış Tarihi</label>
                <input type="date" name="arrival_date" class="form-control" value="{{ old('arrival_date', \Illuminate\Support\Carbon::parse($gemiRoute->arrival_date)->format('Y-m-d')) }}" required>
            </div>

            <div class="col-md-12">
                <label class="form-label">Açıklama</label>
                <textarea name="description" rows="3" class="form-control">{{ old('description', $gemiRoute->description) }}</textarea>
            </div>

            <div class="col-12 mt-4 text-end">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Güncelle
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const addWaypointBtn = document.getElementById('add-waypoint');
    const waypointsContainer = document.getElementById('waypoints-container');

    // Mevcut olan select'lere select2 uygula
    $('#waypoints-container select').select2({
        placeholder: "Bir ara durak seçin",
        allowClear: true
    });

    addWaypointBtn.addEventListener('click', function () {
        const waypointDiv = document.createElement('div');
        waypointDiv.classList.add('input-group', 'mb-2', 'align-items-center');

        waypointDiv.innerHTML = `
            <select name="way_points[]" class="form-control">
                <option value=""></option> <!-- Placeholder için boş option -->
                @foreach($ports as $port)
                    <option value="{{ $port->id }}">{{ $port->name }}</option>
                @endforeach
            </select>
            <button type="button" class="btn btn-danger btn-sm ms-2 remove-waypoint">Sil</button>
        `;

        waypointsContainer.appendChild(waypointDiv);

        // Yeni eklenen select'e select2 uygula
        $(waypointDiv).find('select').select2({
            placeholder: "Bir ara durak seçin",
            allowClear: true
        });

        // Dinamik sil butonu
        waypointDiv.querySelector('.remove-waypoint').addEventListener('click', function () {
            waypointDiv.remove();
        });
    });

    // Sayfa yüklendiğinde var olan sil butonlarına click ekle
    document.querySelectorAll('.remove-waypoint').forEach(btn => {
        btn.addEventListener('click', function () {
            btn.parentElement.remove();
        });
    });
});
</script>


