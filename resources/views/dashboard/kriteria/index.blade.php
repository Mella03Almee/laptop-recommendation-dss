@extends('dashboard.layouts.main')

@section('container')
<main class="py-4">
    <div class="container-fluid px-4">
        
        {{-- HEADER HALAMAN --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Data Kriteria</h1>
                <p class="text-muted small mb-0">Kelola bobot dan jenis kriteria penentu rekomendasi laptop (C1 - C9).</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kriteria</li>
                </ol>
            </nav>
        </div>

        {{-- ALERT INFORMASI STABILITAS --}}
        <div class="alert alert-info border-0 shadow-sm rounded-3 d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-info-circle-fill fs-4 me-3 text-info"></i>
            <div>
                <strong>Informasi Sistem:</strong> Jumlah 9 kriteria utama telah dikunci untuk menjaga kestabilan algoritma <em>Weighted Product (WP)</em>. Anda tetap dapat mengedit nama, bobot kepentingan, dan jenis kriteria.
            </div>
        </div>

        {{-- CARD TABEL DATA --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="m-0 fw-bold text-dark d-flex align-items-center">
                    <i class="bi bi-sliders text-primary me-2"></i> Daftar Kriteria Penilaian
                </h6>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fs-7">Total: {{ $kriterias->count() }} Kriteria</span>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="tableKriteriaClean" class="table table-hover align-middle mb-0">
                        <thead class="table-light border-bottom">
                            <tr>
                                <th class="text-center py-3" width="5%">No</th>
                                <th class="text-center py-3" width="15%">Kode</th>
                                <th class="py-3">Nama Kriteria</th>
                                <th class="text-center py-3" width="15%">Bobot</th>
                                <th class="text-center py-3" width="15%">Jenis</th>
                                <th class="text-center py-3" width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriterias as $kcriteria)
                            <tr>
                                <td class="text-center fw-semibold text-muted">{{ $loop->iteration }}</td>
                                <td class="text-center">
                                    <span class="badge bg-dark text-white rounded-2 px-2.5 py-1.5 fw-bold">{{ $kcriteria->kodeKriteria }}</span>
                                </td>
                                <td class="fw-semibold text-dark">{{ $kcriteria->namaKriteria }}</td>
                                <td class="text-center">
                                    <span class="fw-bold text-primary fs-6">{{ $kcriteria->bobot }}</span>
                                </td>
                                <td class="text-center">
                                    @if($kcriteria->jenis == 'benefit')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-semibold">
                                            <i class="bi bi-arrow-up-circle-fill me-1"></i> Benefit
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-semibold">
                                            <i class="bi bi-arrow-down-circle-fill me-1"></i> Cost
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-warning btn-sm rounded-3 px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#modalEditKriteria{{ $kcriteria->id }}">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </button>
                                </td>
                            </tr>

                            {{-- MODAL EDIT KRITERIA --}}
                            <div class="modal fade" id="modalEditKriteria{{ $kcriteria->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header bg-dark text-white py-3 rounded-top-4">
                                            <h5 class="modal-title fs-6 fw-bold">
                                                <i class="bi bi-pencil-square me-2 text-warning"></i> Ubah Kriteria: {{ $kcriteria->kodeKriteria }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('data-kriteria.update', $kcriteria->id) }}" method="POST">
                                            @csrf
                                            @method('put')
                                            <div class="modal-body p-4 text-dark text-start">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Kode Kriteria</label>
                                                    <input type="text" class="form-control rounded-3" name="kodeKriteria" value="{{ $kcriteria->kodeKriteria }}" required>
                                                </div>
                                                
                                                <input type="hidden" name="slug" value="{{ $kcriteria->slug }}">

                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Nama Kriteria</label>
                                                    <input type="text" class="form-control rounded-3" name="namaKriteria" value="{{ $kcriteria->namaKriteria }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Bobot Kepentingan</label>
                                                    <input type="number" step="any" class="form-control rounded-3" name="bobot" value="{{ $kcriteria->bobot }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Jenis Kriteria</label>
                                                    <select class="form-select rounded-3" name="jenis" required>
                                                        <option value="benefit" {{ $kcriteria->jenis == 'benefit' ? 'selected' : '' }}>Benefit (Makin tinggi makin baik)</option>
                                                        <option value="cost" {{ $kcriteria->jenis == 'cost' ? 'selected' : '' }}>Cost (Makin murah/kecil makin baik)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light py-2.5 rounded-bottom-4">
                                                <button type="button" class="btn btn-secondary rounded-3 btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary rounded-3 btn-sm px-4 fw-semibold">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection

@push('table')
<script>
    $(document).ready(function () {
        // Menonaktifkan Paging, Search, dan Info agar tombol Previous/Next kaku hilang total
        $('#tableKriteriaClean').DataTable({
            "paging": false,
            "searching": false,
            "info": false,
            "autoWidth": false
        });
    });
</script>
@endpush