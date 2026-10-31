@extends('login.layouts.main')

@section('container')

<style>
/* Background Gradient Modern khas Slate & Electric Blue */
.login-wrapper {
    width: 100%;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: radial-gradient(circle at top left, #1E293B 0%, #0F172A 100%);
    padding: 1.5rem;
}

/* Card Login SaaS Modern */
.card-login-modern {
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(16px);
    border-radius: 1.25rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
}

.btn-gradient-primary {
    background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
    border: none;
    color: #FFFFFF;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    border-radius: 50rem;
    transition: all 0.25s ease;
}

.btn-gradient-primary:hover {
    background: linear-gradient(135deg, #1D4ED8 0%, #1E40AF 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
    color: #FFFFFF;
}

.form-floating > label {
    color: #64748B;
}

.form-control:focus {
    border-color: #2563EB;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
}
</style>

<div class="login-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-5 col-md-7 col-sm-10">
                
                {{-- KARTU LOGIN SAAS --}}
                <div class="card card-login-modern overflow-hidden">
                    
                    {{-- Header Card & Branding --}}
                    <div class="card-header bg-transparent text-center border-0 pt-4 pb-2">
                        <div class="bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                            <i class="bi bi-laptop fs-3"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Masuk Pengelola</h4>
                        <p class="text-muted small mb-0">Sistem Pemilihan Laptop Berbasis WP</p>
                    </div>

                    <div class="card-body px-4 py-3">

                        {{-- ALERT ERROR --}}
                        @if(session()->has('error'))
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 alert-dismissible fade show small mb-3" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- ALERT SUCCESS --}}
                        @if(session()->has('success'))
                            <div class="alert alert-success border-0 shadow-sm rounded-3 alert-dismissible fade show small mb-3" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- FORM LOGIN --}}
                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            
                            {{-- Input Username --}}
                            <div class="form-floating mb-3">
                                <input class="form-control rounded-3 @error('username') is-invalid @enderror" 
                                       id="username" 
                                       name="username" 
                                       type="text" 
                                       placeholder="Username"
                                       autofocus 
                                       required 
                                       value="{{ old('username') }}" />
                                <label for="username"><i class="bi bi-person me-1"></i> Username</label>
                                @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Input Password --}}
                            <div class="form-floating mb-4">
                                <input class="form-control rounded-3" 
                                       id="password" 
                                       name="password" 
                                       type="password"
                                       placeholder="Password" 
                                       required />
                                <label for="password"><i class="bi bi-lock me-1"></i> Password</label>
                            </div>

                            {{-- Tombol Submit --}}
                            <div class="d-grid mb-2">
                                <button type="submit" class="btn btn-gradient-primary btn-lg fs-6">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Footer Card --}}
                    <div class="card-footer bg-light border-0 text-center py-3">
                        <a href="{{ route('rekomendasi.index') }}" class="text-decoration-none small text-secondary fw-semibold">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Utama
                        </a>
                    </div>
                </div>

                {{-- Copyright Bawah --}}
                <div class="text-center mt-4">
                    <p class="text-slate-400 small mb-0" style="color: #94A3B8;">&copy; 2026 Sistem Pemilihan Laptop - Mella</p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection