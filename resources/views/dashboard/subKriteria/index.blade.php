@extends('dashboard.layouts.main')

@section('container')
<div class="container-fluid px-4">
    <h1 class="mt-4">Data Sub Kriteria Laptop</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Sub Kriteria</li>
    </ol>

    @if(session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- TOMBOL UTAMA --}}
    <div class="mb-4">
        <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahSub">
            <i class="fas fa-plus me-1"></i> Tambah Sub Kriteria Baru
        </button>
    </div>

    {{-- LOOPING KARTU KRITERIA --}}
    @foreach($kriterias as $kriteria)
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-dark text-white fw-bold py-3">
            <i class="fas fa-list-ul me-2 text-info"></i> 
            {{ $kriteria->kodeKriteria }} - {{ $kriteria->namaKriteria }}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover text-center align-middle mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th style="width: 10%">No</th>
                            <th class="text-start">Opsi / Range Sub Kriteria</th>
                            <th style="width: 25%">Nilai (Bobot 1-5)</th>
                            <th style="width: 20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kriteria->subKriteria as $sub)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start text-secondary fw-medium">{{ $sub->harga ?? $sub->namaSubKriteria }}</td>
                            <td>
                                <span class="badge bg-info text-dark border border-info px-3 py-2 fw-bold">
                                    {{ $sub->nHarga ?? $sub->bobot }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- TOMBOL EDIT --}}
                                    <button type="button" 
                                            class="btn btn-warning btn-sm fw-medium btn-edit-sub" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditSub"
                                            data-id="{{ $sub->id }}"
                                            data-kriteria="{{ $kriteria->namaKriteria }}"
                                            data-harga="{{ $sub->harga ?? $sub->namaSubKriteria }}"
                                            data-nharga="{{ $sub->nHarga ?? $sub->bobot }}">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </button>
                                    
                                    {{-- FORM HAPUS --}}
                                    <form action="{{ route('data-sub-kriteria.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus sub kriteria ini?')">
                                        @method('delete')
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-muted py-3 text-center">Belum ada opsi sub kriteria untuk kriteria ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- MODAL TAMBAH DATA --}}
<div class="modal fade" id="modalTambahSub" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTambahLabel"><i class="fas fa-plus-circle me-2"></i>Tambah Sub Kriteria</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('data-sub-kriteria.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kriteria_id" class="form-label fw-semibold text-secondary">Pilih Kriteria Induk</label>
                        <select name="kriteria_id" id="kriteria_id" class="form-select" required>
                            <option value="">-- Pilih Kriteria --</option>
                            @foreach($kriterias as $kriteria)
                                <option value="{{ $kriteria->id }}">{{ $kriteria->kodeKriteria }} - {{ $kriteria->namaKriteria }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label fw-semibold text-secondary">Nama / Range Sub Kriteria</label>
                        <input type="text" name="harga" id="harga" class="form-control" placeholder="Contoh: Intel Core i5 atau 8 GB" required>
                    </div>
                    <div class="mb-3">
                        <label for="nHarga" class="form-label fw-semibold text-secondary">Nilai Bobot (1-5)</label>
                        <input type="number" name="nHarga" id="nHarga" class="form-control" min="1" max="5" placeholder="Contoh: 5" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT DATA --}}
<div class="modal fade" id="modalEditSub" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalEditLabel"><i class="fas fa-edit me-2"></i>Ubah Sub Kriteria: <span id="text-kriteria-induk" class="fw-bold"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-edit-sub" method="POST">
                @method('put')
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_harga" class="form-label fw-semibold text-secondary">Nama / Range Sub Kriteria</label>
                        <input type="text" name="harga" id="edit_harga" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nharga" class="form-label fw-semibold text-secondary">Nilai Bobot (1-5)</label>
                        <input type="number" name="nHarga" id="edit_nharga" class="form-control" min="1" max="5" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const buttonsEdit = document.querySelectorAll('.btn-edit-sub');
        const formEdit = document.querySelector('#form-edit-sub');
        const textKriteria = document.querySelector('#text-kriteria-induk');
        const inputHarga = document.querySelector('#edit_harga');
        const inputNHarga = document.querySelector('#edit_nharga');

        buttonsEdit.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const harga = this.getAttribute('data-harga');
                const nharga = this.getAttribute('data-nharga');
                const kriteria = this.getAttribute('data-kriteria');

                textKriteria.innerText = kriteria;
                inputHarga.value = harga;
                inputNHarga.value = nharga;

                // URL form edit disesuaikan dengan prefix /dashboard/ dan rute manual web.php
                formEdit.action = `/dashboard/data-sub-kriteria/${id}`;
            });
        });
    });
</script>
@endsection