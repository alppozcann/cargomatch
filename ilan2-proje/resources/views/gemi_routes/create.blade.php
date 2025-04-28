@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Yeni Gemi Rotası Ekle</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('gemi_routes.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Başlık</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="ship_id" class="form-label">Gemi Seçin</label>
                        <select name="ship_id" id="ship_id" class="form-select" required>
                            <option value="">Gemi seçin...</option>
                            @foreach($ships as $ship)
                                <option value="{{ $ship->id }}">{{ $ship->ship_name }} ({{ $ship->plate_code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-5">
                            <label for="departure_port" class="form-label">Başlangıç Limanı</label>
                            <select class="form-control select2" id="departure_port" name="start_location" required>
                                <option value="">Bir liman seçin</option>
                                @foreach($ports as $port)
                                    <option value="{{ $port->id }}">{{ $port->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-5">
                            <label for="arrival_port" class="form-label">Varış Limanı</label>
                            <select class="form-control select2" id="arrival_port" name="end_location" required>
                                <option value="">Bir liman seçin</option>
                                @foreach($ports as $port)
                                    <option value="{{ $port->id }}">{{ $port->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="waypoint_port" class="form-label">Ara Duraklar</label>
                        <div class="input-group mb-2 align-items-center">
                            <select class="form-control select2" id="waypoint_port" name="way_points[]" style="width: 90%;">
                                <option value="">Ara durak limanı seçin</option>
                                @foreach($ports as $port)
                                    <option value="{{ $port->id }}">{{ $port->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="add-waypoint" class="btn btn-sm btn-outline-primary">+ Ara Durak Ekle</button>
                        </div>
                        <div id="waypoints-container"></div> 
                    </div>
                </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="available_capacity" class="form-label">Boş Kapasite</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control" id="available_capacity" name="available_capacity" value="{{ old('available_capacity') }}" required>
                                <select class="form-select" id="weight_type" name="weight_type" style="max-width: 100px;">
                                    <option value="kg" selected>kg</option>
                                    <option value="ton">ton</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Fiyat</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
                                <select class="form-select" id="currency_type" name="currency_type" style="max-width: 100px;">
                                    <option value="TRY" selected>₺</option>
                                    <option value="USD">$</option>
                                    <option value="EUR">€</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="departure_date" class="form-label">Hareket Tarihi</label>
                            <input type="datetime-local" class="form-control @error('departure_date') is-invalid @enderror" id="departure_date" name="departure_date" value="{{ old('departure_date') }}" required>
                            @error('departure_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="arrival_date" class="form-label">Varış Tarihi</label>
                            <input type="datetime-local" class="form-control @error('arrival_date') is-invalid @enderror" id="arrival_date" name="arrival_date" value="{{ old('arrival_date') }}" required>
                            @error('arrival_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Açıklama</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('gemi_routes.index') }}" class="btn btn-secondary">İptal</a>
                        <button type="submit" class="btn btn-primary">Rotayı Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addBtn = document.getElementById('add-waypoint');
    const container = document.getElementById('waypoints-container');
    const portOptions = `
        <option value="">Ara durak limanı seçin</option>
        @foreach($ports as $port)
            <option value="{{ $port->id }}">{{ $port->name }}</option>
        @endforeach
    `;

    addBtn.addEventListener('click', function () {
        const wrapper = document.createElement('div');
        wrapper.className = 'd-flex gap-2 align-items-center mb-2'; // ✅ responsive yapı
        wrapper.innerHTML = `
            <select class="form-control select2 flex-grow-1" name="way_points[]">
                ${portOptions}
            </select>
            <button type="button" class="btn btn-outline-danger btn-sm remove-waypoint">Kaldır</button>
        `;
        container.appendChild(wrapper);

        // Yeni gelen select'e Select2 uygula
        $(wrapper).find('.select2').select2({
            placeholder: "Liman seçin",
            allowClear: true,
            width: '100%'
        });
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-waypoint')) {
            e.target.closest('.d-flex').remove();
        }
    });
});

</script>
@endsection
@endsection
