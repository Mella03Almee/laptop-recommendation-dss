@extends('dashboard.layouts.main')

@section('container')
<div class="container-fluid px-4">
    <h1 class="mt-4">Saran Masuk</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Saran</li>
    </ol>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white fw-bold d-flex align-items-center">
            <i class="bi bi-chat-right-text-fill me-2 text-success"></i> Daftar Umpan Balik & Saran Pengunjung
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="example" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Email</th>
                            <th>Isi Saran</th>
                            <th>Tanggal Masuk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($allSaran) && $allSaran->count() > 0)
                            @foreach($allSaran as $index => $saran)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $saran->email ?? '-' }}</td>
                                    <td>{{ $saran->isi_saran }}</td>
                                    <td>{{ \Carbon\Carbon::parse($saran->created_at)->translatedFormat('d F Y H:i') }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Tidak ada data saran masuk.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection