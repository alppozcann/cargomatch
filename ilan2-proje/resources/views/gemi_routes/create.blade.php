@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%) !important;
        font-family: 'Poppins', sans-serif;
    }
    .form-header {
        background: linear-gradient(135deg,rgb(0, 4, 250), #000);
        color: #fff;
        border-radius: 18px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.2);
    }
    .form-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }
    .form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        padding: 2rem;
    }
    .form-label {
        font-weight: 600;
        color: #1e3a8a;
    }
    .form-control, .form-select {
        border-radius: 10px;
        padding: 0.75rem;
        border: 1px solid #cbd5e1;
    }
    .form-control:focus, .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
    }
    .submit-btn {
        background: linear-gradient(135deg,#2563eb, rgb(0, 4, 250));
        color: #fff;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 12px;
        transition: 0.3s;
    }
    .submit-btn:hover {
        background: linear-gradient(135deg,rgb(0, 4, 250), #2563eb);
    }
    .select2-container--default .select2-selection--single {
        height: 48px !important;
        border-radius: 10px !important;
        display: flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border: 1px solid #cbd5e1;
        font-size: 1rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 48px !important;
        padding-left: 0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px !important;
        right: 10px;
    }
</style>

<div class="container py-5">
    <div class="form-header mb-4">
        <h1>Yeni Gemi Rotası Ekle</h1>
    </div>
    <div class="form-card">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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
                <div class="col-md-6 mb-4">
                    <label for="departure_port" class="form-label">Başlangıç Limanı</label>
                    <select class="form-select form-control select2" id="departure_port" name="start_location" required>
                        <option value="">Bir liman seçin</option>
                        @foreach($ports as $port)
                            <option value="{{ $port->id }}">{{ $port->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-4">
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
                <div id="waypoints-container">
                    <div class="d-flex gap-2 align-items-center mb-2">
                        <select class="form-control select2 flex-grow-1" name="way_points[][port_id]">
                            <option value="">Ara durak limanı seçin</option>
                            @foreach($ports as $port)
                                <option value="{{ $port->id }}">{{ $port->name }}</option>
                            @endforeach
                        </select>
                        <input type="date" class="form-control" name="way_points[][date]" placeholder="Varış Tarihi (ETA)">
                        <button type="button" id="add-waypoint" class="btn btn-sm btn-outline-primary">+ Ara Durak Ekle</button>
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
            <div class="mb-3">
                <label for="description" class="form-label">Açıklama</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('gemi_routes.index') }}" class="btn btn-secondary">İptal</a>
                <button type="submit" class="submit-btn">Rotayı Kaydet</button>
            </div>
        </form>
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
        wrapper.className = 'd-flex gap-2 align-items-center mb-2';
        wrapper.innerHTML = `
            <select class="form-control select2 flex-grow-1" name="way_points[][port_id]">
                ${portOptions}
            </select>
            <input type="date" class="form-control" name="way_points[][date]" placeholder="Varış Tarihi (ETA)">
            <button type="button" class="btn btn-outline-danger btn-sm remove-waypoint">Kaldır</button>
        `;
        container.appendChild(wrapper);
        $(wrapper).find('.select2').select2({
            placeholder: "Liman seçin",
            allowClear: true,
            width: '100%',
            dropdownParent: $(wrapper)
        });
    });

    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-waypoint')) {
            e.target.closest('.d-flex').remove();
        }
    });

    // Boş waypoint'leri submit öncesi temizle (güncel versiyon)
    const form = document.querySelector('form[action="{{ route('gemi_routes.store') }}"]');
    form.addEventListener('submit', function(e) {
        const waypoints = document.querySelectorAll('#waypoints-container .input-group, #waypoints-container .d-flex');
        waypoints.forEach(wp => {
            const port = wp.querySelector('select')?.value;
            const date = wp.querySelector('input[type="date"]')?.value;
            if (!port || !date) {
                wp.remove();
            }
        });
    });

    // Initialize Select2 for static selects
    $('.select2').select2({
        placeholder: "Liman seçin",
        allowClear: true,
        width: '100%',
        dropdownParent: $('.form-card')
    });
});
</script>
@endsection
@endsection
