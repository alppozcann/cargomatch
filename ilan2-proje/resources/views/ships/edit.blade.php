@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
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
        <h1>Gemi Bilgilerini Düzenle</h1>
    </div>

    <div class="form-card">
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
                <select name="cargoTypes[]" class="form-select" multiple required>
                    @foreach($cargoTypes as $type)
                        <option value="{{ $type }}" {{ in_array($type, $ship->cargoTypes ?? []) ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Ctrl (veya Cmd) tuşuna basılı tutarak birden fazla seçim yapabilirsiniz.</small>
            </div>

            <div class="text-end">
                <button type="submit" class="submit-btn">
                    <i class="bi bi-save"></i> Güncelle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
