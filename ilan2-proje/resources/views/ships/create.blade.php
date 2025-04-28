@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Yeni Gemi Kaydı</h2>

    <form action="{{ route('ships.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Kayıt Numarası (Plaka Kodu)</label>
            <input type="text" name="plate_code" class="form-control" value="{{ old('plate_code') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Gemi Adı</label>
            <input type="text" name="ship_name" class="form-control" value="{{ old('ship_name') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Gemi Türü</label>
            <input type="text" name="ship_type" class="form-control" value="{{ old('ship_type') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Taşıma Kapasitesi (ton)</label>
            <input type="number" name="carrying_capacity" step="0.01" min="0" class="form-control" value="{{ old('carrying_capacity') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Taşınabilir Yük Türleri</label>
            <select name="load_types[]" class="form-select" multiple>
                @foreach ($cargoTypes as $type)
                    <option value="{{ $type }}" {{ (collect(old('load_types'))->contains($type)) ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Birden fazla yük türü seçebilirsiniz.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Sahip Olduğu Sertifikalar</label>
            <select name="certificates[]" class="form-select" multiple>
                <option value="IMO" {{ (collect(old('certificates'))->contains('IMO')) ? 'selected' : '' }}>IMO Sertifikası</option>
                <option value="SOLAS" {{ (collect(old('certificates'))->contains('SOLAS')) ? 'selected' : '' }}>SOLAS Sertifikası</option>
                <option value="ISPS" {{ (collect(old('certificates'))->contains('ISPS')) ? 'selected' : '' }}>ISPS Sertifikası</option>
                <option value="Diğer" {{ (collect(old('certificates'))->contains('Diğer')) ? 'selected' : '' }}>Diğer</option>
            </select>
            <small class="text-muted">Birden fazla sertifika seçebilirsiniz.</small>
        </div>

        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Kaydet
            </button>
        </div>
    </form>
</div>
@endsection
