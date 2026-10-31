@extends('dashboard.layouts.main')

@section('container')
<style>
    #example {
        border: 2px #9DB2BF;
    }
</style>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Data Hasil Akhir</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Hasil Akhir</li>
        </ol>

        @if($rankingWP->isEmpty())
            <div class="card text-center text-bg-light mb-4">
                <div class="card-body mt-3 mb-3">
                    <h5 class="card-title fw-bold text-danger">Data Hasil Akhir Kosong</h5>
                    <p class="card-text text-secondary">Isi terlebih dahulu Data Alternatif & berikan penilaian di Matriks Keputusan!</p>
                    <a href="{{ route('data-penilaian.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="bi bi-arrow-right-circle"></i> Menuju Halaman Penilaian
                    </a>
                </div>
            </div>
        @else
            <div class="card text-bg-light mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Hasil Akhir Perankingan WP
                </div>
                <div class="d-flex justify-content-start">
                    <div class="ms-3 mt-3 mb-3">
                        <a href="{{ route('data-hasil-akhir.result') }}" target="_blank" class="btn btn-primary btn-sm">
                            <i class="bi bi-printer"></i> Cetak Data
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover table-bordered align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Alternatif</th>
                                    <th>Nilai V</th>
                                    <th>Rank</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach ($rankingWP as $rWP)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start fw-semibold">{{ $rWP->alternatif->namaAlternatif }}</td>
                                    <td class="text-info fw-bold">{{ round($rWP->nilai_v, 4) }}</td>
                                    <td>
                                        @if($loop->iteration == 1)
                                            <span class="badge bg-success"><i class="bi bi-trophy-fill"></i> Juara 1</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $loop->iteration }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</main>
@endsection