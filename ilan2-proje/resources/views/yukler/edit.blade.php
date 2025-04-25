@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="section-title">Yük Düzenle</h1>
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
                    <form action="{{ route('yukler.update', $yuk) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="title" class="form-label">Başlık</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $yuk->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="yuk_type" class="form-label">Yük Türü</label>
                                <select class="form-select @error('yuk_type') is-invalid @enderror" id="yuk_type" name="yuk_type" required>
                                    <option value="">Seçiniz</option>
                                    <option value="Konteyner" {{ old('yuk_type', $yuk->yuk_type) == 'Konteyner' ? 'selected' : '' }}>Konteyner</option>
                                    <option value="Dökme Yük" {{ old('yuk_type', $yuk->yuk_type) == 'Dökme Yük' ? 'selected' : '' }}>Dökme Yük</option>
                                    <option value="Proje Yükü" {{ old('yuk_type', $yuk->yuk_type) == 'Proje Yükü' ? 'selected' : '' }}>Proje Yükü</option>
                                    <option value="Tehlikeli Madde" {{ old('yuk_type', $yuk->yuk_type) == 'Tehlikeli Madde' ? 'selected' : '' }}>Tehlikeli Madde</option>
                                </select>
                                @error('yuk_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="weight" class="form-label">Ağırlık (kg)</label>
                                <input type="number" step="0.01" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $yuk->weight) }}" required>
                                @error('weight')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="proposed_price" class="form-label">Teklif Edilen Fiyat (TL)</label>
                                <input type="number" step="0.01" class="form-control @error('proposed_price') is-invalid @enderror" id="proposed_price" name="proposed_price" value="{{ old('proposed_price', $yuk->proposed_price) }}" required>
                                @error('proposed_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="from_location" class="form-label">Nereden</label>
                                <input type="text" class="form-control @error('from_location') is-invalid @enderror" id="from_location" name="from_location" value="{{ old('from_location', $yuk->from_location) }}" required>
                                @error('from_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="to_location" class="form-label">Nereye</label>
                                <input type="text" class="form-control @error('to_location') is-invalid @enderror" id="to_location" name="to_location" value="{{ old('to_location', $yuk->to_location) }}" required>
                                @error('to_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="desired_delivery_date" class="form-label">İstenen Teslimat Tarihi</label>
                                <input type="date" class="form-control @error('desired_delivery_date') is-invalid @enderror" id="desired_delivery_date" name="desired_delivery_date" value="{{ old('desired_delivery_date', $yuk->desired_delivery_date->format('Y-m-d')) }}" required>
                                @error('desired_delivery_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-4">
                                <label for="description" class="form-label">Açıklama</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $yuk->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg me-2"></i>Değişiklikleri Kaydet
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
</style>
@endsection 