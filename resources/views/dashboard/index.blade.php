@extends('dashboard.layouts.main')

@section('container')
<main class="py-4">
    <div class="container-fluid px-4">
        
        {{-- HEADER DASHBOARD --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Dashboard Utama</h1>
                <p class="text-muted small mb-0">Ringkasan statistik dan pemetaan data perhitungan Weighted Product (WP).</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>

        {{-- BARIS 1: WIDGET STATISTIK RINGKASAN --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block mb-1">Total Kriteria</span>
                            <h4 class="fw-bold text-dark mb-0">{{ count($dataKriteriaNama) }}</h4>
                        </div>
                        <div class="bg-primary-subtle text-primary rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-sliders fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block mb-1">Data Laptop</span>
                            <h4 class="fw-bold text-dark mb-0">{{ count($dataNamaAlternatif) }}</h4>
                        </div>
                        <div class="bg-success-subtle text-success rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-laptop fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block mb-1">Riwayat Akses</span>
                            <h4 class="fw-bold text-dark mb-0">{{ isset($allBiodata) ? count($allBiodata) : 0 }}</h4>
                        </div>
                        <div class="bg-info-subtle text-info rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted small fw-semibold d-block mb-1">Saran Masuk</span>
                            <h4 class="fw-bold text-dark mb-0">{{ isset($allSaran) ? count($allSaran) : 0 }}</h4>
                        </div>
                        <div class="bg-warning-subtle text-warning rounded-3 p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="bi bi-chat-left-dots fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BARIS 2: PENJELASAN METODE & PIE CHART --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 border-bottom">
                        <h6 class="m-0 fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-cpu-fill text-primary me-2"></i> Metode Weighted Product (WP)
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-secondary mb-3" style="text-align: justify; font-size: 0.95rem; line-height: 1.8;">
                            Sistem Pendukung Keputusan pemilihan laptop ini dibangun memanfaatkan algoritma <strong>Weighted Product (WP)</strong> untuk mempermudah pengguna menemukan laptop ideal sesuai kebutuhan dan anggaran.
                        </p>
                        <p class="text-secondary mb-0" style="text-align: justify; font-size: 0.95rem; line-height: 1.8;">
                            Analisis dilakukan dengan mengalikan nilai variabel kriteria (Harga, CPU, RAM, SSD, GPU, Layar, Baterai, Berat, dan Webcam) yang telah dipangkatkan sesuai dengan bobot kepentingannya masing-masing.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                        <h6 class="m-0 fw-bold text-dark d-flex align-items-center">
                            <i class="bi bi-pie-chart-fill text-info me-2"></i> Proporsi Bobot Kriteria
                        </h6>
                    </div>
                    <div class="card-body p-4 d-flex align-items-center justify-content-center">
                        <canvas id="myPieChart" style="max-height: 230px;"></canvas>
                    </div>
                    <div class="card-footer bg-light py-2 px-4 border-top text-muted small rounded-bottom-4">
                        <i class="bi bi-clock-history me-1"></i> Update Kriteria: {{ $formattedUpdatedAtKriteria }}
                    </div>
                </div>
            </div>
        </div>

        {{-- BARIS 3: BAR CHART PERBANDINGAN LAPTOP --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="m-0 fw-bold text-dark d-flex align-items-center">
                    <i class="bi bi-bar-chart-line-fill text-success me-2"></i> Perbandingan Nilai Akhir (V) Setiap Laptop
                </h6>
            </div>
            <div class="card-body p-4">
                <canvas id="myBarChart" width="100%" height="32"></canvas>
            </div>
            <div class="card-footer bg-light py-2 px-4 border-top text-muted small rounded-bottom-4">
                <i class="bi bi-arrow-repeat me-1"></i> Update Data Laptop: {{ $formattedUpdatedAt }}
            </div>
        </div>

    </div>
</main>

{{-- SINKRONISASI DATA CHART JAVASCRIPT --}}
<script>
    let dataKriteriaNama = @json($dataKriteriaNama);
    let dataKriteriaBobot = @json($dataKriteriaBobot);
    let dataNilaiV = @json($dataNilaiV);
    let dataNamaAlternatif = @json($dataNamaAlternatif); 
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="js/chart-bar-demo.js"></script>
<script src="js/chart-pie-demo.js"></script>
@endsection