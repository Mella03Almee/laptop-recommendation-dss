
@extends('Front.layouts.main')

@section('title', 'Hasil Rekomendasi Laptop - SPK WP')

@push('styles')
<style>
    .header-top-banner {
        background: linear-gradient(135deg, #0056b3 0%, #0080ff 100%);
        color: #fff;
        padding: 20px 25px;
        border-radius: 12px;
    }

    .card-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .06);
    }

    .rank-badge {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .img-box-utama,
    .img-box-alt {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 10px;
        padding: 10px;
    }

    .img-box-utama { height: 200px; }
    .img-box-alt { height: 140px; }

    .img-laptop {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }

    .spec-item {
        font-size: .8rem;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 6px 10px;
        display: flex;
        align-items: center;
        min-width: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .spec-item i {
        width: 18px;
        text-align: center;
        flex-shrink: 0;
    }

    .recommendation-label {
        font-size: .85rem;
        font-weight: 700;
        letter-spacing: .1px;
    }

    .recommendation-note {
        font-size: .8rem;
        color: #6c757d;
        line-height: 1.6;
    }

    .budget-badge {
        font-size: .72rem;
        font-weight: 600;
        white-space: normal;
    }

    .alternative-card {
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .alternative-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, .08) !important;
    }

    .price-help {
        width: 17px;
        height: 17px;
        padding: 0;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 700;
        vertical-align: middle;
        cursor: pointer;
    }

    .popover { max-width: 320px; }

    .popover-body {
        font-size: 11px;
        line-height: 1.5;
        color: #495057;
    }
</style>
@endpush

@section('content')
@php
    /*
     * Menangani beberapa format path gambar yang umum:
     * - URL eksternal
     * - storage/nama-file.jpg
     * - public/storage/nama-file.jpg
     * - img/nama-file.jpg atau images/nama-file.jpg
     */
    $resolveLaptopImage = function ($path, $fallback) {
        if (empty($path)) {
            return $fallback;
        }

        $path = trim(str_replace('\\', '/', $path));

        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        if (
            str_starts_with($path, 'img/') ||
            str_starts_with($path, 'images/')
        ) {
            return asset($path);
        }

        return asset('storage/' . $path);
    };

    $formatHarga = function ($harga) {
        if (is_numeric($harga)) {
            return (float) $harga;
        }

        return (float) preg_replace('/[^0-9]/', '', (string) $harga);
    };

    $utama = $utama ?? null;
    $alternatif1 = $alternatif1 ?? null;
    $alternatif2 = $alternatif2 ?? null;
    $hasilRanking = $hasilRanking ?? [];
    $profil = $profil ?? [];

    $fallbackUtama = 'https://via.placeholder.com/300x200?text=Foto+Laptop';
    $fallbackAlt = 'https://via.placeholder.com/200x140?text=Foto+Laptop';
@endphp

<div class="container my-4">

    {{-- HEADER --}}
    <div class="header-top-banner shadow-sm mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-1">
                    <i class="bi bi-trophy-fill text-warning me-2"></i>
                    Hasil Rekomendasi Laptop
                </h3>
                <p class="mb-0 text-white-50 small">
                    Rekomendasi perangkat berdasarkan perhitungan metode
                    Weighted Product (WP).
                </p>
            </div>

            <a href="{{ url('/') }}"
               class="btn btn-light text-primary fw-bold rounded-pill px-4 btn-sm">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- PROFIL PENGGUNA --}}
        <div class="col-lg-3 col-md-4">
            <div class="card card-custom p-3 text-dark h-100">

                <h6 class="fw-bold border-bottom pb-2 mb-3 text-primary">
                    <i class="bi bi-person-badge-fill me-2"></i>
                    Profil Pengguna
                </h6>

                <div class="mb-2">
                    <small class="text-muted d-block" style="font-size:11px">
                        Nama Lengkap
                    </small>
                    <span class="fw-bold text-dark fs-6">
                        {{ $profil['nama'] ?? 'Mahasiswa' }}
                    </span>
                </div>

                <div class="mb-2">
                    <small class="text-muted d-block" style="font-size:11px">
                        Kampus / Universitas
                    </small>
                    <span class="fw-semibold text-secondary small text-uppercase">
                        {{ $profil['kampus'] ?? '-' }}
                    </span>
                </div>

                <div class="mb-2">
                    <small class="text-muted d-block" style="font-size:11px">
                        Fakultas
                    </small>
                    <span class="fw-semibold text-secondary small text-uppercase">
                        {{ $profil['fakultas'] ?? '-' }}
                    </span>
                </div>

                <div class="mb-2">
                    <small class="text-muted d-block" style="font-size:11px">
                        Jurusan / Program Studi
                    </small>
                    <span class="fw-bold text-primary small text-uppercase">
                        {{ $profil['jurusan'] ?? '-' }}
                    </span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block" style="font-size:11px">
                        Kluster Bobot Kebutuhan
                    </small>
                    <span class="badge bg-info text-dark text-wrap">
                        {{ $profil['kategori_kluster'] ?? $profil['kategori'] ?? 'Kebutuhan Standard' }}
                    </span>
                </div>

                <div class="mt-3">
                    <div class="small text-muted mb-1">Kebutuhan Perangkat</div>
                    <div class="fw-semibold">
                        {{ $profil['tingkat_kebutuhan'] ?? '-' }}
                    </div>
                </div>

                {{-- RENTANG HARGA --}}
                <div class="mt-3">
                    <div class="small text-muted mb-1 d-flex align-items-center">
                        <span>Rentang Harga yang Disarankan</span>

                        <button
                            type="button"
                            class="btn btn-outline-secondary price-help ms-1"
                            data-bs-toggle="popover"
                            data-bs-placement="right"
                            data-bs-trigger="click"
                            data-bs-html="true"
                            title="Mengapa rentangnya berbeda?"
                            data-bs-content="
                                <div class='mb-2'>
                                    Rentang harga ditentukan berdasarkan kebutuhan
                                    perangkat untuk jurusan
                                    <strong>{{ $profil['jurusan'] ?? 'ini' }}</strong>.
                                </div>
                                <div class='mb-2'>
                                    Anggaran Anda <strong>{{ $profil['budget_label'] ?? '-' }}</strong>
                                    adalah batas anggaran yang dipilih, sedangkan
                                    <strong>{{ $profil['rentang_saran'] ?? '-' }}</strong>
                                    adalah rentang harga yang disarankan untuk jurusan.
                                </div>
                                <div>
                                    {{ $profil['pesan_anggaran'] ?? 'Sistem mempertimbangkan kebutuhan jurusan, bobot kriteria, dan anggaran yang dipilih.' }}
                                </div>
                            "
                            aria-label="Informasi rentang harga"
                        >?</button>
                    </div>

                    <div class="fw-semibold">
                        {{ $profil['rentang_saran'] ?? '-' }}
                    </div>
                </div>

                <div class="mt-3">
                    <div class="small text-muted mb-1">Software Utama</div>
                    <div class="fw-semibold small">
                        {{ $profil['software_utama'] ?? '-' }}
                    </div>
                </div>

                <div class="mt-3">
                    <div class="small text-muted mb-1">Profil Perangkat</div>
                    <div class="fw-semibold small">
                        {{ $profil['profil_perangkat'] ?? '-' }}
                    </div>
                </div>

                <div class="alert alert-info p-2 mt-3 mb-0" style="font-size:11px">
                    <i class="bi bi-info-circle me-1"></i>
                    Bobot kriteria disesuaikan berdasarkan kebutuhan jurusan
                    <strong>{{ $profil['jurusan'] ?? 'Anda' }}</strong>.
                </div>
            </div>
        </div>

        {{-- AREA HASIL --}}
        <div class="col-lg-9 col-md-8">

            @if($utama || $alternatif1 || $alternatif2)

                {{-- REKOMENDASI UTAMA --}}
                @if($utama)
                    @php
                        $hargaUtama = $formatHarga($utama['harga'] ?? 0);
                        $urlImgUtama = $resolveLaptopImage(
                            $utama['gambar'] ?? '',
                            $fallbackUtama
                        );
                    @endphp

                    <div class="card border-success bg-light rounded-4 p-4 shadow-sm mb-4">

                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <span class="badge bg-success recommendation-label px-3 py-2">
                                <i class="bi bi-check-circle-fill me-1"></i>
                                Rekomendasi Utama
                            </span>

                            <span class="fw-bold text-primary fs-5">
                                Skor WP (V): {{ number_format((float) ($utama['nilai_v'] ?? 0), 4) }}
                            </span>
                        </div>

                        <div class="row align-items-start g-3">

                            <div class="col-auto">
                                <span class="rank-badge bg-warning text-dark shadow">1</span>
                            </div>

                            <div class="col-md-3 col-12 text-center">
                                <div class="img-box-utama border rounded">
                                    <img
                                        src="{{ $urlImgUtama }}"
                                        class="img-laptop"
                                        alt="{{ $utama['nama_laptop'] ?? 'Laptop' }}"
                                        onerror="this.onerror=null;this.src='{{ $fallbackUtama }}';"
                                    >
                                </div>
                            </div>

                            <div class="col-md-8 col-12">
                                <h4 class="fw-bold text-dark mb-1">
                                    {{ $utama['nama_laptop'] ?? 'Laptop' }}
                                </h4>

                                <div class="text-success fw-bold fs-5 mb-3">
                                    Rp {{ number_format($hargaUtama, 0, ',', '.') }}
                                </div>

                                {{-- SPESIFIKASI --}}
                                <div class="row g-2 mb-3">
                                    @foreach([
                                        ['bi-cpu', 'PROC', 'cpu'],
                                        ['bi-gpu-card', 'GPU', 'gpu'],
                                        ['bi-memory', 'RAM', 'ram'],
                                        ['bi-hdd-rack', 'Storage', 'storage'],
                                        ['bi-display', 'Layar', 'layar'],
                                        ['bi-camera-video', 'Kamera', 'kamera'],
                                        ['bi-box-seam', 'Berat', 'berat'],
                                        ['bi-battery-charging', 'Baterai', 'baterai']
                                    ] as $spec)
                                        <div class="col-sm-6">
                                            <div class="spec-item">
                                                <i class="bi {{ $spec[0] }} text-primary me-1"></i>
                                                <strong>{{ $spec[1] }}:</strong>
                                                &nbsp;{{ $utama[$spec[2]] ?? '-' }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- ALASAN REKOMENDASI --}}
                                <div class="text-secondary small mb-2">
                                    <strong>Alasan Rekomendasi:</strong>
                                    <p class="mb-0">
                                        {{ $utama['alasan_rekomendasi'] ?? 'Laptop ini dipilih berdasarkan perhitungan nilai preferensi Weighted Product (WP) dan aturan pemilihan rekomendasi untuk jurusan yang dipilih.' }}
                                    </p>
                                </div>

                                {{-- PERINGATAN --}}
                                @if(!empty($utama['peringatan']))
                                    <div class="alert alert-warning py-2 px-3 small mt-3 mb-2">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        {{ $utama['peringatan'] }}
                                    </div>
                                @endif

                                {{-- STATUS ANGGARAN --}}
                                <div class="mt-3">
                                    @if(!($utama['sesuai_budget'] ?? false))
                                        <span class="badge bg-warning text-dark budget-badge">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Di luar anggaran pilihan Anda
                                        </span>
                                    @else
                                        <span class="badge bg-success budget-badge">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Sesuai anggaran pilihan Anda
                                        </span>
                                    @endif
                                </div>

                                <div class="text-muted mt-2" style="font-size:11px">
                                    Vektor S: {{ number_format((float) ($utama['nilai_s'] ?? 0), 4) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- PILIHAN ALTERNATIF --}}
                <div class="row g-3">

                    {{-- PILIHAN PERFORMA --}}
                    @if($alternatif1)
                        @php
                            $alt1 = $alternatif1;
                            $hargaAlt1 = $formatHarga($alt1['harga'] ?? 0);
                            $urlImgAlt1 = $resolveLaptopImage(
                                $alt1['gambar'] ?? '',
                                $fallbackAlt
                            );
                        @endphp

                        <div class="col-md-6">
                            <div class="card alternative-card h-100 border rounded-4 p-3 shadow-sm d-flex flex-column">

                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                    <span class="badge bg-primary recommendation-label">
                                        <i class="bi bi-rocket-takeoff me-1"></i>
                                        Pilihan Performa
                                    </span>

                                    <small class="fw-bold text-primary">
                                        V: {{ number_format((float) ($alt1['nilai_v'] ?? 0), 4) }}
                                    </small>
                                </div>

                                <div class="text-center my-2">
                                    <div class="img-box-alt border rounded mx-auto mb-2">
                                        <img
                                            src="{{ $urlImgAlt1 }}"
                                            class="img-laptop"
                                            alt="{{ $alt1['nama_laptop'] ?? 'Laptop' }}"
                                            onerror="this.onerror=null;this.src='{{ $fallbackAlt }}';"
                                        >
                                    </div>

                                    <h6 class="fw-bold text-dark mb-1">
                                        {{ $alt1['nama_laptop'] ?? 'Laptop' }}
                                    </h6>

                                    <div class="text-success fw-bold small mb-2">
                                        Rp {{ number_format($hargaAlt1, 0, ',', '.') }}
                                    </div>
                                </div>

                                <div class="spec-list mb-2">
                                    <div class="row g-1 mb-1">
                                        <div class="col-12">
                                            <div class="spec-item">
                                                <i class="bi bi-cpu text-primary me-1"></i>
                                                <strong>Proc:</strong>&nbsp;
                                                <span class="text-secondary">{{ $alt1['cpu'] ?? '-' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="spec-item">
                                                <i class="bi bi-gpu-card text-primary me-1"></i>
                                                <strong>GPU:</strong>&nbsp;
                                                <span class="text-secondary">{{ $alt1['gpu'] ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-1 mb-2">
                                        <div class="col-6">
                                            <div class="spec-item">
                                                <i class="bi bi-memory text-primary me-1"></i>
                                                {{ $alt1['ram'] ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="spec-item">
                                                <i class="bi bi-hdd-rack text-primary me-1"></i>
                                                {{ $alt1['storage'] ?? '-' }}
                                            </div>
                                        </div>
                                    </div>

                                    <p class="recommendation-note mb-2">
                                        <strong>Alasan pilihan:</strong><br>
                                        {{ $alt1['alasan_rekomendasi'] ?? 'Pilihan ini dipertimbangkan sebagai alternatif dengan harga lebih tinggi dan peningkatan spesifikasi yang relevan terhadap kebutuhan jurusan.' }}
                                    </p>

                                    @if(!empty($alt1['peningkatan_spesifikasi']) && is_array($alt1['peningkatan_spesifikasi']))
                                        <div class="small mb-2">
                                            <strong>Peningkatan spesifikasi:</strong>
                                            <ul class="ps-3 mb-0">
                                                @foreach($alt1['peningkatan_spesifikasi'] as $peningkatan)
                                                    <li>{{ $peningkatan }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if(!empty($alt1['peringatan']))
                                        <div class="alert alert-warning py-2 px-3 small mb-2">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                            {{ $alt1['peringatan'] }}
                                        </div>
                                    @endif

                                    @if(!($alt1['sesuai_budget'] ?? false))
                                        <span class="badge bg-light text-secondary border budget-badge">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Di luar anggaran pilihan
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border budget-badge">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Sesuai anggaran pilihan
                                        </span>
                                    @endif

                                    <div class="text-muted mt-2" style="font-size:11px">
                                        Vektor S: {{ number_format((float) ($alt1['nilai_s'] ?? 0), 4) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- PILIHAN LEBIH TERJANGKAU --}}
                    @if($alternatif2)
                        @php
                            $alt2 = $alternatif2;
                            $hargaAlt2 = $formatHarga($alt2['harga'] ?? 0);
                            $urlImgAlt2 = $resolveLaptopImage(
                                $alt2['gambar'] ?? '',
                                $fallbackAlt
                            );
                        @endphp

                        <div class="col-md-6">
                            <div class="card alternative-card h-100 border rounded-4 p-3 shadow-sm d-flex flex-column">

                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                                    <span class="badge bg-success recommendation-label">
                                        <i class="bi bi-wallet2 me-1"></i>
                                        Pilihan Lebih Terjangkau
                                    </span>

                                    <small class="fw-bold text-primary">
                                        V: {{ number_format((float) ($alt2['nilai_v'] ?? 0), 4) }}
                                    </small>
                                </div>

                                <div class="text-center my-2">
                                    <div class="img-box-alt border rounded mx-auto mb-2">
                                        <img
                                            src="{{ $urlImgAlt2 }}"
                                            class="img-laptop"
                                            alt="{{ $alt2['nama_laptop'] ?? 'Laptop' }}"
                                            onerror="this.onerror=null;this.src='{{ $fallbackAlt }}';"
                                        >
                                    </div>

                                    <h6 class="fw-bold text-dark mb-1">
                                        {{ $alt2['nama_laptop'] ?? 'Laptop' }}
                                    </h6>

                                    <div class="text-success fw-bold small mb-2">
                                        Rp {{ number_format($hargaAlt2, 0, ',', '.') }}
                                    </div>
                                </div>

                                <div class="spec-list mb-2">
                                    <div class="row g-1 mb-1">
                                        <div class="col-12">
                                            <div class="spec-item">
                                                <i class="bi bi-cpu text-primary me-1"></i>
                                                <strong>Proc:</strong>&nbsp;
                                                <span class="text-secondary">{{ $alt2['cpu'] ?? '-' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="spec-item">
                                                <i class="bi bi-gpu-card text-primary me-1"></i>
                                                <strong>GPU:</strong>&nbsp;
                                                <span class="text-secondary">{{ $alt2['gpu'] ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-1 mb-2">
                                        <div class="col-6">
                                            <div class="spec-item">
                                                <i class="bi bi-memory text-primary me-1"></i>
                                                {{ $alt2['ram'] ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="spec-item">
                                                <i class="bi bi-hdd-rack text-primary me-1"></i>
                                                {{ $alt2['storage'] ?? '-' }}
                                            </div>
                                        </div>
                                    </div>

                                    <p class="recommendation-note mb-2">
                                        <strong>Alasan pilihan:</strong><br>
                                        {{ $alt2['alasan_rekomendasi'] ?? 'Pilihan ini merupakan alternatif berbeda yang memperoleh nilai WP tertinggi di antara kandidat yang memenuhi batas anggaran Anda.' }}
                                    </p>

                                    @if(!empty($alt2['peringatan']))
                                        <div class="alert alert-warning py-2 px-3 small mb-2">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                            {{ $alt2['peringatan'] }}
                                        </div>
                                    @endif

                                    @if(!($alt2['sesuai_budget'] ?? false))
                                        <span class="badge bg-light text-secondary border budget-badge">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Di luar anggaran pilihan
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border budget-badge">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Sesuai anggaran pilihan
                                        </span>
                                    @endif

                                    <div class="text-muted mt-2" style="font-size:11px">
                                        Vektor S: {{ number_format((float) ($alt2['nilai_s'] ?? 0), 4) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- TIDAK ADA PILIHAN PERFORMA --}}
                    @if(!$alternatif1)
                        <div class="col-md-6">
                            <div class="card h-100 border rounded-4 p-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="bi bi-rocket-takeoff me-1"></i>
                                    Pilihan Performa
                                </h6>
                                <p class="recommendation-note mb-0">
                                    Belum ditemukan alternatif berbeda yang memenuhi
                                    kriteria peningkatan spesifikasi secara bermakna.
                                    Sistem tidak menampilkan laptop hanya karena
                                    harganya lebih mahal.
                                </p>
                            </div>
                        </div>
                    @endif

                    {{-- TIDAK ADA PILIHAN LEBIH TERJANGKAU --}}
                    @if(!$alternatif2)
                        <div class="col-md-6">
                            <div class="card h-100 border rounded-4 p-3">
                                <h6 class="fw-bold text-success">
                                    <i class="bi bi-wallet2 me-1"></i>
                                    Pilihan Lebih Terjangkau
                                </h6>
                                <p class="recommendation-note mb-0">
                                    Belum ditemukan laptop berbeda yang memenuhi
                                    batas anggaran pilihan Anda. Sistem tidak
                                    menampilkan laptop di luar anggaran sebagai
                                    pengganti.
                                </p>
                            </div>
                        </div>
                    @endif

                </div>

            @else
                <div class="alert alert-warning p-4 text-center rounded-4 shadow-sm">
                    <h5>
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        Data Rekomendasi Belum Tersedia
                    </h5>
                    <p class="mb-0 small">
                        Belum ada laptop dengan data spesifikasi dan penilaian
                        yang dapat digunakan untuk menghasilkan rekomendasi.
                        Silakan periksa data alternatif dan penilaiannya.
                    </p>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof bootstrap === 'undefined' || !bootstrap.Popover) {
            return;
        }

        document.querySelectorAll('[data-bs-toggle="popover"]').forEach(function (element) {
            new bootstrap.Popover(element, {
                container: 'body',
                html: true,
                trigger: 'click'
            });
        });
    });
</script>
@endpush
