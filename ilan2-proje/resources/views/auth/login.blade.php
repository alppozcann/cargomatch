@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    body {
        background: #111 url('{{ asset('loginpage.jpg') }}') no-repeat center center fixed !important;
        background-size: cover !important;
    }
    .login-card {
        background: rgba(24, 24, 27, 0.92);
        color: #fff;
        border-radius: 18px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        border: none;
        font-family: 'Poppins', sans-serif;
        backdrop-filter: blur(12px);
    }
    .login-card .form-control,
    .login-card .form-control:focus {
        background: #23232a;
        color: #fff;
        border: 1px solid #333;
        border-radius: 8px;
        box-shadow: none;
    }
    .login-card .btn-primary {
        background: #fff;
        color: #18181b;
        border: none;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 4px 14px rgba(255, 255, 255, 0.1);
        transition: all 0.2s ease-in-out;
    }
    .login-card .btn-primary:hover {
        background: #e5e5e5;
        color: #18181b;
        transform: scale(1.02);
    }
    .login-card .btn-outline-light {
        border-radius: 8px;
    }
    .login-card .form-label {
        color: #bbb;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
    }
    .login-card .divider {
        border-top: 1px solid #333;
        margin: 1.5rem 0;
    }
    .login-card .social-btn {
        background: #23232a;
        color: #fff;
        border: 1px solid #333;
        border-radius: 8px;
        font-weight: 500;
        margin-bottom: 10px;
        transition: background 0.2s;
    }
    .login-card .social-btn:hover {
        background: #333;
        color: #fff;
    }
    .login-card .signup-link {
        color: #a5b4fc;
        text-decoration: none;
    }
    .login-card .signup-link:hover {
        text-decoration: underline;
    }
    .login-card img {
        filter: drop-shadow(0 0 6px rgba(0, 102, 255, 0.3));
    }
</style>
<div class="d-flex align-items-center justify-content-center" style="min-height: 90vh;">
    <div class="col-12 col-md-5 col-lg-4">
        <div class="card login-card p-4">
            <div class="text-center mb-4">
                <img src="{{ asset('logo2.png') }}" alt="Logo" style="height: 120px;">
                <h2 class="fw-bold mt-3 mb-1" style="color: #fff;">Login</h2>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif
            <form method="POST" action="{{ route('login.submit') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Your email address">
                    @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Your password">
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember me
                    </label>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 mb-2">Continue</button>
            </form>
            <div class="divider"></div>
            <div class="mb-2">
                <a href="{{ url('/auth/google') }}" class="btn social-btn w-100 mb-2">
                    <i class="fab fa-google me-2"></i> Continue with Google
                </a>
            </div>
            <div class="text-center mt-3">
                <span style="color: #bbb;">Don't have an account?</span>
                <a href="{{ route('register') }}" class="signup-link">Sign up</a>
            </div>
        </div>
    </div>
</div>
@endsection
