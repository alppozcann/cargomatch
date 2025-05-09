@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="section-title">Yeni Yük İlanı</h1>
                <a href="{{ route('yukler.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Geri Dön
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body p-4">
                    <form action="{{ route('yukler.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="title" class="form-label">Başlık</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="yuk_type" class="form-label">Yük Türü</label>
                                <select class="form-select @error('yuk_type') is-invalid @enderror" id="yuk_type" name="yuk_type" required>
                                    <option value="">Seçiniz</option>
                                    <option value="Konteyner" {{ old('yuk_type') == 'Konteyner' ? 'selected' : '' }}>Konteyner</option>
                                    <option value="Dökme Yük" {{ old('yuk_type') == 'Dökme Yük' ? 'selected' : '' }}>Dökme Yük</option>
                                    <option value="Proje Yükü" {{ old('yuk_type') == 'Proje Yükü' ? 'selected' : '' }}>Proje Yükü</option>
                                    <option value="Tehlikeli Madde" {{ old('yuk_type') == 'Tehlikeli Madde' ? 'selected' : '' }}>Tehlikeli Madde</option>
                                </select>
                                @error('yuk_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                            <label for="weight" class="form-label">Ağırlık</label>
                                <!-- <div class="input-group">
                                    <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight') }}" required>
                                    <select name="weight_unit" id="weight_unit" class="form-select form-select-sm w-auto" style="width: 30px;">
                                        <option value="kg" {{ old('weight_unit') == 'kg' ? 'selected' : '' }}>kg</option>
                                        <option value="ton" {{ old('weight_unit') == 'ton' ? 'selected' : '' }}>ton</option>
                                    </select>
                                </div> -->
                                <div class="input-group">
    <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight') }}" required>
    <div class="input-group-text p-0">
        <select name="weight_unit" id="weight_unit" class="form-select form-select-sm border-0" style="width: 85px;">
            <option value="kg" {{ old('weight_unit') == 'kg' ? 'selected' : '' }}>kg</option>
            <option value="ton" {{ old('weight_unit') == 'ton' ? 'selected' : '' }}>ton</option>
        </select>
    </div>
</div>
                                @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                        <div class="col-md-6 mb-5">
                            <label for="departure_port" class="form-label">Başlangıç Limanı</label>
                            <select class="form-control select2" id="departure_port" name="from_location" required>
                                <option value="">Bir liman seçin</option>
                                @foreach($ports as $port)
                                    <option value="{{ $port->id }}">{{ $port->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-5">
                            <label for="arrival_port" class="form-label">Varış Limanı</label>
                            <select class="form-control select2" id="arrival_port" name="to_location" required>
                                <option value="">Bir liman seçin</option>
                                @foreach($ports as $port)
                                    <option value="{{ $port->id }}">{{ $port->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
    <label class="form-label">Ara Duraklar ve Tahmini Varış Tarihleri</label>
    <div id="waypoints-container">
        <div class="d-flex gap-2 align-items-center mb-2 waypoint-row">
            <select class="form-control select2 flex-grow-1" name="way_points[0][port_id]">
                <option value="">Ara durak limanı seçin</option>
                @foreach($ports as $port)
                    <option value="{{ $port->id }}">{{ $port->name }}</option>
                @endforeach
            </select>
            <input type="date" class="form-control" name="way_points[0][date]" placeholder="Varış Tarihi (ETA)">
            <button type="button" class="btn btn-outline-danger btn-sm remove-waypoint">Kaldır</button>
        </div>
    </div>
    <button type="button" id="add-waypoint" class="btn btn-sm btn-outline-primary mt-2">+ Ara Durak Ekle</button>
</div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="shipping_date" class="form-label">Gönderim (Yükleme) Tarihi</label>
                            <input type="date" name="shipping_date" class="form-control" value="{{ old('shipping_date', $yuk->shipping_date ?? '') }}">
                            @error('shipping_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="desired_delivery_date" class="form-label">İstenen Teslimat Tarihi</label>
                            <input type="date" class="form-control @error('desired_delivery_date') is-invalid @enderror" id="desired_delivery_date" name="desired_delivery_date" value="{{ old('desired_delivery_date') }}" required>
                            @error('desired_delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Boyutlar (opsiyonel)</label>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text">En</span>
                                            <input type="number" step="0.01" min="0" class="form-control @error('width') is-invalid @enderror" id="width" name="width" value="{{ old('width') }}">
                                            <span class="input-group-text">cm</span>
                                        </div>
                                        @error('width')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text">Boy</span>
                                            <input type="number" step="0.01" min="0" class="form-control @error('length') is-invalid @enderror" id="length" name="length" value="{{ old('length') }}">
                                            <span class="input-group-text">cm</span>
                                        </div>
                                        @error('length')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text">Yükseklik</span>
                                            <input type="number" step="0.01" min="0" class="form-control @error('height') is-invalid @enderror" id="height" name="height" value="{{ old('height') }}">
                                            <span class="input-group-text">cm</span>
                                        </div>
                                        @error('height')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-4">
                                <label for="description" class="form-label">Açıklama</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg me-2"></i>İlanı Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .section-title {
        position: relative;
        margin-bottom: 0;
        font-weight: 700;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 50px;
        height: 3px;
        background-color: var(--bs-primary);
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
    }
    
    .input-group-text {
        border-radius: 0.5rem;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let waypointIndex = 1;

        document.getElementById('add-waypoint').addEventListener('click', function () {
            const container = document.getElementById('waypoints-container');
            const div = document.createElement('div');
            div.className = 'd-flex gap-2 align-items-center mb-2 waypoint-row';
            div.innerHTML = `
                <select class="form-control select2 flex-grow-1" name="way_points[${waypointIndex}][port_id]">
                    <option value="">Ara durak limanı seçin</option>
                    @foreach($ports as $port)
                        <option value="{{ $port->id }}">{{ $port->name }}</option>
                    @endforeach
                </select>
                <input type="date" class="form-control" name="way_points[${waypointIndex}][date]" placeholder="Varış Tarihi (ETA)">
                <button type="button" class="btn btn-outline-danger btn-sm remove-waypoint">Kaldır</button>
            `;
            container.appendChild(div);
            waypointIndex++;
        });

        document.getElementById('waypoints-container').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-waypoint')) {
                e.target.parentElement.remove();
            }
        });
    });
</script>
@endsection
