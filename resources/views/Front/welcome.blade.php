@extends('Front.layouts.main')

@section('title', 'Sistem Rekomendasi Laptop')

@push('styles')
<style>
    .bg {
        width: 100%;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-size: 200% 200%;
        background-image: linear-gradient(-135deg, #004080 0%, #0080ff 50%, #004080 100%);
        animation: AnimateBG 15s ease infinite;
        padding: 60px 0;
    }

    @keyframes AnimateBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .justified-text {
        text-align: justify;
        font-size: 18px; 
        line-height: 1.7; 
        color: rgba(255, 255, 255, 0.9); 
    }

    .btn-custom {
        background-color: #2A2F4F; 
        color: #ffffff;
        border: 0px;
        transition: transform 0.5s ease;
    }

    .btn-custom:hover {
        background-color: #004080;
        color: #ffffff; 
        border: 0px;
        transform: scale(1.05);
    }
</style>
@endpush

@section('content')
<div class="bg">
    <div class="container col-xxl-8 px-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 text-center text-lg-start">
                
                @auth
                    <h1 class="display-4 fw-bold lh-sm mb-4 text-white">Hi! {{ auth()->user()->name }}</h1>
                    <p class="justified-text">
                        Sistem Pendukung Keputusan berbasis metode Weighted Product (WP) ini dirancang untuk mempermudah Anda menemukan rekomendasi laptop ideal sesuai dengan profil, program studi, dan anggaran Anda. Perhitungan sistem menganalisis dan mengukur berbagai parameter penting berdasarkan bobot spesifik jurusan Anda sehingga menghasilkan rekomendasi yang tepat dan objektif.                
                    </p>
                    <br>
                    <div class="d-grid gap-3 d-md-flex justify-content-md-start justify-content-center align-items-center">
                        <button class="btn btn-custom btn-lg px-5 py-3 fw-bold rounded-pill shadow" data-bs-toggle="modal" data-bs-target="#wizardModal">
                            <i class="bi bi-rocket-takeoff-fill me-2"></i> Mulai Perhitungan
                        </button>

                        <a href="{{ route('dashboard') }}" class="btn btn-warning btn-lg px-4 rounded-pill fw-bold text-dark">Dashboard</a>
                        <a href="{{ route('logout') }}" class="btn btn-danger btn-lg px-4 rounded-pill fw-bold">Logout</a>
                    </div>
                @else
                    <h1 class="display-4 fw-bold mb-4 text-white">Pemilihan Laptop Mahasiswa</h1>
                    <p class="justified-text">
                        Sistem Pendukung Keputusan berbasis metode Weighted Product (WP) ini dirancang untuk mempermudah Anda menemukan rekomendasi laptop ideal sesuai dengan profil, program studi, dan anggaran Anda. Perhitungan sistem menganalisis dan mengukur berbagai parameter penting berdasarkan bobot spesifik jurusan Anda sehingga menghasilkan rekomendasi yang tepat dan personal.                  
                    </p>
                    <br>
                    <div class="d-grid gap-3 d-md-flex justify-content-md-start justify-content-center align-items-center">
                        <button class="btn btn-custom btn-lg px-5 py-3 fw-bold rounded-pill shadow" data-bs-toggle="modal" data-bs-target="#wizardModal">
                            <i class="bi bi-rocket-takeoff-fill me-2"></i> Mulai Perhitungan
                        </button>

                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill">Login Admin</a>
                    </div>
                @endauth

            </div>
            <div class="col-lg-6 text-center">
                <img src="{{ asset('img/buatcoverdepan1.png') }}" class="img-fluid" alt="Cover Laptop" width="600" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1531297484001-80022131f5a1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'">
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="wizardModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="wizardModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="wizardModalLabel">
                    <i class="bi bi-sliders me-2 text-info"></i> Form Profil & Anggaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('rekomendasi.hitung') }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-dark">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control form-control-lg rounded-3" placeholder="Masukkan nama lengkap Anda" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kampus / Universitas</label>
                        <select name="universitas_id" id="universitas_select" class="form-select form-select-lg rounded-3" required>
                            <option value="">-- Pilih Universitas --</option>
                            @if(isset($universitas) && count($universitas) > 0)
                                @foreach($universitas as $univ)
                                    <option value="{{ $univ->id }}">{{ $univ->nama_universitas }} ({{$univ->singkatan }})</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fakultas</label>
                        <select name="fakultas_id" id="fakultas_select" class="form-select form-select-lg rounded-3" required disabled>
                            <option value="">-- Pilih Kampus Terlebih Dahulu --</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jurusan / Program Studi</label>
                        <select name="jurusan_id" id="jurusan_select" class="form-select form-select-lg rounded-3" required disabled>
                            <option value="">-- Pilih Fakultas Terlebih Dahulu --</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bi bi-wallet2 me-1"></i> Batas Anggaran / Budget Laptop</label>
                        <select name="c1_harga" id="harga_select" class="form-select form-select-lg rounded-3" required>
                            <option value="">-- Pilih Range Harga Laptop --</option>
                            <option value="1">Sangat Terjangkau (&lt; Rp 6.000.000)</option>
                            <option value="2">Terjangkau (Rp 6.000.000 - Rp 8.999.000)</option>
                            <option value="3">Sedang (Rp 9.000.000 - Rp 11.999.000)</option>
                            <option value="4">Tinggi (Rp 12.000.000 - Rp 14.999.000)</option>
                            <option value="5">Sangat Tinggi (&gt;= Rp 15.000.000)</option>
                        </select>
                    </div>

                    <div id="info-kategori" class="alert alert-info d-none mt-3" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Kategori Bobot Kluster: <strong id="nama-kategori">-</strong>. <br>
                        <small class="text-muted">Sistem SPK akan secara otomatis menerapkan bobot kriteria yang paling presisi untuk jurusan Anda.</small>
                    </div>

                </div>
                
                <div class="modal-footer bg-light rounded-bottom-4 justify-content-end">
                    <button type="submit" class="btn btn-success btn-lg px-4 py-2 rounded-3 fw-bold">
                        <i class="bi bi-calculator me-1"></i> Mulai Perhitungan WP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const univSelect = document.getElementById('universitas_select');
    const fakSelect = document.getElementById('fakultas_select');
    const jurSelect = document.getElementById('jurusan_select');
    const infoKategori = document.getElementById('info-kategori');
    const namaKategori = document.getElementById('nama-kategori');

    univSelect.addEventListener('change', function() {
        const univId = this.value;
        fakSelect.innerHTML = '<option value="">-- Loading Fakultas... --</option>';
        fakSelect.disabled = true;
        jurSelect.innerHTML = '<option value="">-- Pilih Fakultas Terlebih Dahulu --</option>';
        jurSelect.disabled = true;
        infoKategori.classList.add('d-none');

        if (univId) {
            fetch(`/get-fakultas/${univId}`)
                .then(res => res.json())
                .then(data => {
                    fakSelect.innerHTML = '<option value="">-- Pilih Fakultas --</option>';
                    data.forEach(item => {
                        fakSelect.innerHTML += `<option value="${item.id}">${item.nama_fakultas}</option>`;
                    });
                    fakSelect.disabled = false;
                })
                .catch(err => console.error(err));
        } else {
            fakSelect.innerHTML = '<option value="">-- Pilih Kampus Terlebih Dahulu --</option>';
        }
    });

    fakSelect.addEventListener('change', function() {
        const fakId = this.value;
        jurSelect.innerHTML = '<option value="">-- Loading Jurusan... --</option>';
        jurSelect.disabled = true;
        infoKategori.classList.add('d-none');

        if (fakId) {
            fetch(`/get-jurusan/${fakId}`)
                .then(res => res.json())
                .then(data => {
                    jurSelect.innerHTML = '<option value="">-- Pilih Jurusan --</option>';
                    data.forEach(item => {
                        jurSelect.innerHTML += `<option value="${item.id}" data-kategori="${item.kategori_bobot ? item.kategori_bobot.nama_kategori : ''}">${item.nama_jurusan} (${item.jenjang || 'S1'})</option>`;
                    });
                    jurSelect.disabled = false;
                })
                .catch(err => console.error(err));
        } else {
            jurSelect.innerHTML = '<option value="">-- Pilih Fakultas Terlebih Dahulu --</option>';
        }
    });

    jurSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const katNama = selectedOption.getAttribute('data-kategori');
        if (katNama) {
            namaKategori.innerText = katNama;
            infoKategori.classList.remove('d-none');
        } else {
            infoKategori.classList.add('d-none');
        }
    });
</script>
@endpush