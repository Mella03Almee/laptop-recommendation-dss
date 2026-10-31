@extends('dashboard.layouts.main')

@section('container')
<style>
    /* Mengunci batas tabel matriks agar tidak merusak layout dashboard */
    #example {
        border: 1px solid #DEE2E6;
    }
    .table-responsive {
        margin-top: 15px;
    }
    /* Memberikan efek visual semi-bold pada kode kriteria di baris header */
    th {
        font-size: 0.9rem;
        white-space: nowrap;
    }
</style>

<div class="container-fluid px-4">
    <h1 class="mt-4">Data Penilaian (Matriks Keputusan)</h1>
    
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Penilaian</li>
    </ol>
    
    <div class="card text-bg-light mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <span class="fw-bold text-secondary">
                <i class="fas fa-table me-2 text-primary"></i> Matriks Penilaian Alternatif
            </span>
            {{-- TOMBOL TAMBAH UTAMA MENGGUNAKAN MODAL --}}
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahPenilaian">
                <i class="bi bi-plus-circle me-1"></i> Tambah Penilaian
            </button>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-hover table-bordered text-center align-middle" style="width: 100%">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>Nama Alternatif</th>
                            
                            {{-- Render Kolom Kriteria Secara Dinamis --}}
                            @foreach ($kriterias as $kcriteria)
                                <th>{{ $kcriteria->namaKriteria }} ({{ $kcriteria->kodeKriteria }})</th>
                            @endforeach
                            
                            <th style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alternatifs as $alternatif)
                            @php
                                // Mengambil data penilaian yang cocok dengan alternatif_id saat ini
                                $penilaian = $penilaians->where('alternatif_id', $alternatif->id)->first();
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start fw-semibold text-secondary">{{ $alternatif->namaAlternatif }}</td>
                                
                                {{-- Render Nilai C1x, C2x, dst secara berurutan --}}
                                @foreach ($kriterias as $kriteria)
                                    @php
                                        $fieldName = 'C' . $loop->iteration . 'x';
                                    @endphp
                                    <td>
                                        @if($penilaian && $penilaian->$fieldName !== null)
                                            <span class="badge bg-light text-dark border px-2 py-1.5">{{ $penilaian->$fieldName }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($penilaian)
                                            {{-- TOMBOL UBAH MENGGUNAKAN MODAL DATA ATRIBUT --}}
                                            <button type="button" 
                                                    class="btn btn-warning btn-sm fw-medium btn-edit-penilaian"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEditPenilaian"
                                                    data-id="{{ $penilaian->id }}"
                                                    data-laptop="{{ $alternatif->namaAlternatif }}"
                                                    @foreach($kriterias as $kriteria)
                                                        @php $fName = 'C' . $loop->iteration . 'x'; @endphp
                                                        data-c{{ $loop->iteration }}="{{ $penilaian->$fName }}"
                                                    @endforeach>
                                                <i class="bi bi-pen me-1"></i> Ubah Nilai
                                            </button>
                                        @else
                                            {{-- TOMBOL INPUT JIKA DATA MASIH KOSONG --}}
                                            <button type="button" 
                                                    class="btn btn-info btn-sm text-white fw-medium btn-tambah-langsung"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalTambahPenilaian"
                                                    data-id="{{ $alternatif->id }}">
                                                <i class="bi bi-plus-lg me-1"></i> Input Nilai
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> 
        </div>
    </div>
</div>

{{-- ==================== MODAL TAMBAH PENILAIAN ==================== --}}
<div class="modal fade" id="modalTambahPenilaian" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTambahLabel"><i class="fas fa-star me-2"></i>Tambah Penilaian Karakteristik Laptop</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('data-penilaian.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    
                    <div class="mb-4">
                        <label for="alternatif_id" class="form-label fw-bold text-secondary">Pilih Laptop (Alternatif)</label>
                        <select name="alternatif_id" id="alternatif_id" class="form-select" required>
                            <option value="">-- Pilih Laptop --</option>
                            @foreach($alternatifs as $alternatif)
                                <option value="{{ $alternatif->id }}">{{ $alternatif->namaAlternatif }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="my-3">
                    <h6 class="mb-3 text-primary fw-bold"><i class="fas fa-sliders-h me-2"></i>Sesuaikan Nilai Berdasarkan Sub Kriteria</h6>

                    @foreach($kriterias as $kriteria)
                    <div class="mb-3">
                        <label for="kriteria_{{ $loop->iteration }}" class="form-label fw-semibold text-dark">
                            {{ $kriteria->kodeKriteria }} - {{ $kriteria->namaKriteria }}
                        </label>
                        {{-- Name dikondisikan sesuai kolom database C1x, C2x, dst --}}
                        <select name="C{{ $loop->iteration }}x" id="kriteria_{{ $loop->iteration }}" class="form-select" required>
                            <option value="">-- Pilih Opsi Spesifikasi --</option>
                            @foreach($kriteria->subKriteria as $sub)
                                <option value="{{ $sub->nHarga ?? $sub->bobot }}">
                                    {{ $sub->harga ?? $sub->namaSubKriteria }} (Nilai: {{ $sub->nHarga ?? $sub->bobot }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endforeach

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ==================== MODAL EDIT PENILAIAN ==================== --}}
<div class="modal fade" id="modalEditPenilaian" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalEditLabel"><i class="fas fa-edit me-2"></i>Ubah Penilaian: <span id="text-laptop-target" class="fw-bold"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-edit-penilaian" method="POST">
                @method('put')
                @csrf
                <div class="modal-body">
                    
                    <h6 class="mb-3 text-secondary fw-bold"><i class="fas fa-sliders-h me-2"></i>Perbarui Nilai Spesifikasi</h6>

                    @foreach($kriterias as $kriteria)
                    <div class="mb-3">
                        <label for="edit_kriteria_{{ $loop->iteration }}" class="form-label fw-semibold text-dark">
                            {{ $kriteria->kodeKriteria }} - {{ $kriteria->namaKriteria }}
                        </label>
                        <select name="C{{ $loop->iteration }}x" id="edit_kriteria_{{ $loop->iteration }}" class="form-select" required>
                            <option value="">-- Pilih Opsi Spesifikasi --</option>
                            @foreach($kriteria->subKriteria as $sub)
                                <option value="{{ $sub->nHarga ?? $sub->bobot }}">
                                    {{ $sub->harga ?? $sub->namaSubKriteria }} (Nilai: {{ $sub->nHarga ?? $sub->bobot }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endforeach

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ==================== JAVASCRIPT MODAL INTERACTION ==================== --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Logika Auto-Select Laptop jika klik "Input Nilai" langsung di baris tabel
        const tombolTambahLangsung = document.querySelectorAll('.btn-tambah-langsung');
        tombolTambahLangsung.forEach(btn => {
            btn.addEventListener('click', function() {
                const altId = this.getAttribute('data-id');
                document.querySelector('#alternatif_id').value = altId;
            });
        });

        // Logika Melempar Data ke Form Modal Edit
        const tombolEdit = document.querySelectorAll('.btn-edit-sub, .btn-edit-penilaian');
        const formEdit = document.querySelector('#form-edit-penilaian');
        const textLaptop = document.querySelector('#text-laptop-target');

        tombolEdit.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const namaLaptop = this.getAttribute('data-laptop');
                
                textLaptop.innerText = namaLaptop;
                formEdit.action = `/dashboard/data-penilaian/${id}`;

                // Loop mengisi select-option berdasarkan data-atribut elemen table
                for (let i = 1; i <= {{ $kriterias->count() }}; i++) {
                    const nilaiC = this.getAttribute(`data-c${i}`);
                    const selectElement = document.querySelector(`#edit_kriteria_${i}`);
                    if(selectElement) {
                        selectElement.value = nilaiC;
                    }
                }
            });
        });
    });
</script>
@endsection