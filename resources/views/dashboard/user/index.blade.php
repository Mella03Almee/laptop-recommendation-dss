@extends('dashboard.layouts.main')

@section('container')
<main class="py-4">
    <div class="container-fluid px-4">
        
        {{-- HEADER HALAMAN --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Daftar Pengguna Sistem</h1>
                <p class="text-muted small mb-0">Informasi dan hak akses akun pengelola Sistem Pendukung Keputusan.</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pengguna</li>
                </ol>
            </nav>
        </div>

        {{-- GRID KARTU PROFIL USER (READ-ONLY PROFILE CARDS) --}}
        <div class="row g-4">
            @foreach ($users as $u)
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white">
                    
                    {{-- Cover Banner Top --}}
                    <div class="card-cover-bg" style="height: 95px; background: linear-gradient(135deg, #0F172A 0%, #2563EB 100%);"></div>
                    
                    {{-- Avatar & Profil Utama --}}
                    <div class="card-body px-4 pt-0 pb-4 text-center">
                        <div class="avatar-wrapper mb-3" style="margin-top: -48px;">
                            <div class="rounded-circle border border-4 border-white shadow-sm bg-primary text-white d-flex align-items-center justify-content-center mx-auto fs-3 fw-bold" style="width: 88px; height: 88px;">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                        </div>

                        <h5 class="fw-bold text-dark mb-1">{{ $u->name }}</h5>
                        <p class="text-muted small mb-3">@<span>{{ $u->username }}</span></p>

                        <div class="mb-3">
                            @if($u->is_admin ?? false)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-semibold">
                                    <i class="bi bi-shield-lock-fill me-1"></i> Administrator
                                </span>
                            @else
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-semibold">
                                    <i class="bi bi-person-fill me-1"></i> Pengguna Sistem
                                </span>
                            @endif
                        </div>

                        {{-- Rincian Informasi Pengguna --}}
                        <div class="bg-light rounded-3 p-3 text-start small border border-light-subtle mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">ID Pengguna:</span>
                                <span class="fw-bold text-dark">#USR-0{{ $u->id }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Username:</span>
                                <span class="fw-semibold text-dark">{{ $u->username }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Status Akun:</span>
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</main>
@endsection