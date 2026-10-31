@extends('dashboard.layouts.main')

@section('container')
<div class="container-fluid px-4">
    <h1 class="mt-4">Data Akademik Kampus</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Universitas</li>
    </ol>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <span class="fw-bold text-primary"><i class="bi bi-building me-2"></i>Daftar Universitas</span>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUniv">
                <i class="bi bi-plus-lg me-1"></i> Tambah Universitas
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th class="text-start">Nama Universitas</th>
                            <th width="20%">Jumlah Fakultas</th>
                            <th width="25%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($universitas as $index => $univ)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="text-start fw-bold">{{ $univ->nama_universitas }}</td>
                                <td><span class="badge bg-info text-dark fs-6">{{ $univ->fakultas_count }} Fakultas</span></td>
                                <td>
                                    <a href="{{ route('universitas.show', $univ->id) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-diagram-3 me-1"></i> Kelola Fakultas & Jurusan
                                    </a>
                                    <form action="{{ route('universitas.destroy', $univ->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus universitas ini beserta seluruh fakultas dan jurusannya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted py-3">Belum ada data universitas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH UNIV --}}
<div class="modal fade" id="modalTambahUniv" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('universitas.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-building me-2"></i>Tambah Universitas Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Universitas</label>
                    <input type="text" name="nama_universitas" class="form-control" placeholder="Contoh: Universitas Lampung" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Singkatan / Inisial Kampus</label>
                    <input type="text" name="singkatan" class="form-control" placeholder="Contoh: UNMAL" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Kampus</button>
            </div>
        </form>
    </div>
</div>
@endsection