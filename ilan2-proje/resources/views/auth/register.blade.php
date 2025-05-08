@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    body {
        background: #111 url('{{ asset('loginpage.jpg') }}') no-repeat center center fixed !important;
        background-size: cover !important;
    }
    .register-card {
        background: rgba(24, 24, 27, 0.92);
        color: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        border: none;
        font-family: 'Poppins', sans-serif;
        backdrop-filter: blur(12px);
    }
    .register-card .form-control,
    .register-card .form-control:focus {
        background: #23232a;
        color: #fff;
        border: 1px solid #333;
        border-radius: 8px;
        box-shadow: none;
    }
    .register-card .btn-primary {
        background: #fff;
        color: #18181b;
        border: none;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 4px 14px rgba(255, 255, 255, 0.1);
        transition: all 0.2s ease-in-out;
    }
    .register-card .btn-primary:hover {
        background: #e5e5e5;
        color: #18181b;
        transform: scale(1.02);
    }
    .register-card .form-label {
        color: #bbb;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }
    .register-card .signup-link {
        color: #a5b4fc;
        text-decoration: none;
    }
    .register-card .signup-link:hover {
        text-decoration: underline;
    }
    .register-card img {
        filter: drop-shadow(0 0 6px rgba(0, 102, 255, 0.3));
    }
</style>
<div class="d-flex align-items-center justify-content-center" style="min-height: 90vh;">
    <div class="col-12 col-md-7 col-lg-6">
        <div class="card register-card p-4">
            <div class="text-center mb-4">
                <img src="{{ asset('logo2.png') }}" alt="Logo" style="height: 90px;">
                <h2 class="fw-bold mt-3 mb-1" style="color: #fff;">Sign up</h2>
            </div>
            <form method="POST" action="{{ route('register.submit') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Ad Soyad</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Telefon Numarası</label>
                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" inputmode="numeric" pattern="[0-9]*" value="{{ old('phone_number') }}" required>
                    @error('phone_number')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="form-label">Kullanıcı Tipi</label>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3 bg-dark border-0">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="user_type" id="gemiciType" value="gemici" {{ old('user_type') === 'gemici' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="gemiciType">
                                            <h5>Gemici</h5>
                                            <p class="text-muted">Kargo taşımacılığı yapıyorum, yük taşımak istiyorum.</p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3 bg-dark border-0">
                                <div class="card-body">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="user_type" id="yukVerenType" value="yukveren" {{ old('user_type') === 'yukveren' ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="yukVerenType">
                                            <h5>Yük Veren</h5>
                                            <p class="text-muted">Yüküm var, taşınmasını istiyorum.</p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @error('user_type')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="company_name" class="form-label">Şirket İsmi</label>
                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                    @error('company_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="company_address" class="form-label">Şirket Adresi</label>
                    <input type="text" class="form-control @error('company_address') is-invalid @enderror" id="company_address" name="company_address" value="{{ old('company_address') }}" required>
                    @error('company_address')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-posta Adresi</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Şifre</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Şifre Tekrar</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-primary">Üye Ol</button>
                    <a href="{{ route('login') }}" class="signup-link">Zaten üye misiniz? Giriş yapın</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
