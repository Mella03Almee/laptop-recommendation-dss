@extends('dashboard.layouts.main')

@section('container')
<main class="py-4">
    <div class="container-fluid px-4">
        
        {{-- HEADER HALAMAN --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-dark mb-1">Data Pengunjung & Rekomendasi</h1>
                <p class="text-muted small mb-0">Riwayat mahasiswa yang telah melakukan pengisian preferensi laptop.</p>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Biodata Mahasiswa</li>
                </ol>
            </nav>
        </div>

        {{-- CARD TABEL DATA --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
            <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="m-0 fw-bold text-dark d-flex align-items-center">
                    <i class="bi bi-people-fill text-primary me-2"></i> Daftar Riwayat Pengguna Sistem
                </h6>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 fs-7">
                    Total: {{ isset($allBiodata) ? count($allBiodata) : 0 }} Pengunjung
                </span>
            </div>
            
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tableBiodata" width="100%">
                        <thead class="table-light border-bottom">
                            <tr>
                                <th class="text-center py-3" width="5%">No</th>
                                <th class="py-3">Nama Mahasiswa</th>
                                <th class="py-3">Kampus</th>
                                <th class="py-3">Fakultas</th>
                                <th class="py-3">Jurusan</th>
                                <th class="py-3">Rekomendasi (Juara 1)</th>
                                <th class="text-center py-3">Waktu Akses</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($allBiodata) && count($allBiodata) > 0)
                                @foreach($allBiodata as $index => $bio)
                                    <tr>
                                        <td class="text-center fw-semibold text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $bio->nama ?? 'Anonim (Data Lama)' }}</span>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ strtoupper($bio->kampus) }}</span></td>
                                        <td class="text-secondary small fw-medium">{{ strtoupper($bio->fakultas) }}</td>
                                        <td class="text-secondary small fw-medium">{{ strtoupper($bio->jurusan) }}</td>
                                        <td>
                                            @if($bio->laptop_terbaik)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-semibold">
                                                    <i class="bi bi-laptop me-1"></i> {{ $bio->laptop_terbaik }}
                                                </span>
                                            @else
                                                <span class="text-muted small"><em>Tidak terekam</em></span>
                                            @endif
                                        </td>
                                        <td class="text-center text-muted small">
                                            {{ \Carbon\Carbon::parse($bio->created_at)->translatedFormat('d F Y H:i') }} WIB
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection

@push('table')
{{-- KUSTOM STYLING KHUSUS PAGINATION DATATABLES AGAR TERLIHAT MODERN & TIDAK BERDEMPETAN --}}
<style>
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 15px !important;
        display: flex !important;
        justify-content: flex-end !important;
        align-items: center !important;
        gap: 4px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 6px 14px !important;
        margin: 0 2px !important;
        border-radius: 8px !important;
        border: 1px solid #E2E8F0 !important;
        background: #FFFFFF !important;
        color: #475569 !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #F1F5F9 !important;
        color: #1E293B !important;
        border-color: #CBD5E1 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #2563EB !important;
        color: #FFFFFF !important;
        border-color: #2563EB !important;
        font-weight: 600 !important;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        opacity: 0.5 !important;
        cursor: not-allowed !important;
        background: #F8FAFC !important;
    }
    .dataTables_wrapper .dataTables_length select {
        padding: 5px 10px !important;
        border-radius: 8px !important;
        border: 1px solid #E2E8F0 !important;
        margin: 0 6px !important;
    }
    .dataTables_wrapper .dataTables_filter input {
        padding: 6px 12px !important;
        border-radius: 8px !important;
        border: 1px solid #E2E8F0 !important;
        margin-left: 8px !important;
    }
</style>

<script>
    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#tableBiodata')) {
            $('#tableBiodata').DataTable().destroy();
        }

        $('#tableBiodata').DataTable({
            "responsive": true,
            "autoWidth": false,
            "pageLength": 10,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "language": {
                "search": "Search:",
                "searchPlaceholder": "Search student name or laptop...",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "Showing 0 entries",
                "zeroRecords": "No matching records found",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            }
        });
    });
</script>
@endpush