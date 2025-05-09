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
</style>

<div class="container py-5">
    <div class="form-header mb-4">
        <h1>Yeni Gemi Kaydı</h1>
    </div>

    <div class="form-card">
        <form action="{{ route('ships.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="registration_number" class="form-label">Kayıt Numarası (Plaka Kodu)</label>
                <input type="text" class="form-control" id="registration_number" name="registration_number">
            </div>

            <div class="mb-3">
                <label for="ship_name" class="form-label">Gemi Adı</label>
                <input type="text" class="form-control" id="ship_name" name="ship_name">
            </div>

            <div class="mb-3">
                <label for="ship_type" class="form-label">Gemi Türü</label>
                <input type="text" class="form-control" id="ship_type" name="ship_type">
            </div>

            <div class="mb-3">
                <label for="capacity" class="form-label">Taşıma Kapasitesi (ton)</label>
                <input type="number" class="form-control" id="capacity" name="capacity">
            </div>

            <div class="mb-3">
            <label class="form-label">Taşınabilir Yük Türleri</label>
            <select name="load_types[]" class="form-select " multiple required>
                @foreach ($cargoTypes as $type)
                    <option value="{{ $type }}" {{ (collect(old('load_types'))->contains($type)) ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                @endforeach
            </select>
                <small class="text-muted">CTRL veya CMD ile birden fazla yük türü seçebilirsiniz.</small>
            </div>


            <div class="mb-3">
            <label class="form-label">Sahip Olduğu Sertifikalar</label>
            <select name="certificates[]" class="form-select " multiple>
                <option value="IMO" {{ (collect(old('certificates'))->contains('IMO')) ? 'selected' : '' }}>IMO Sertifikası</option>
                <option value="SOLAS" {{ (collect(old('certificates'))->contains('SOLAS')) ? 'selected' : '' }}>SOLAS Sertifikası</option>
                <option value="ISPS" {{ (collect(old('certificates'))->contains('ISPS')) ? 'selected' : '' }}>ISPS Sertifikası</option>
                <option value="Diğer" {{ (collect(old('certificates'))->contains('Diğer')) ? 'selected' : '' }}>Diğer</option>
            </select>
            <small class="text-muted">CTRL veya CMD ile birden fazla sertifika seçebilirsiniz.</small>
        </div>

            <button type="submit" class="submit-btn">Kaydet</button>
        </form>
    </div>
</div>


@endsection
