@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Üye Ol</div>
            <div class="card-body">
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
                                <div class="card mb-3">
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
                                <div class="card mb-3">
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
                        <a href="{{ route('login') }}" class="text-decoration-none">Zaten üye misiniz? Giriş yapın</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
