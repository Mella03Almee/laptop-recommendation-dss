@extends('dashboard.layouts.main')

@section('container')
<div class="container-fluid px-4">
    <h1 class="mt-4">Kelola Akademik: {{ $universitas->nama_universitas }} ({{ $universitas->singkatan }})</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('universitas.index') }}">Universitas</a></li>
        <li class="breadcrumb-item active">Fakultas & Jurusan</li>
    </ol>

    <div class="mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahFakultas">
            <i class="bi bi-plus-circle me-1"></i> Tambah Fakultas
        </button>
    </div>

    {{-- ACCORDION FAKULTAS --}}
    <div class="accordion" id="accordionFakultas">
        @forelse($universitas->fakultas as $index => $fakultas)
            <div class="accordion-item shadow-sm mb-3">
                <h2 class="accordion-header" id="heading{{ $fakultas->id }}">
                    <button class="accordion-button {{ $index != 0 ? 'collapsed' : '' }} fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $fakultas->id }}">
                        <i class="bi bi-journal-bookmark text-primary me-2"></i> {{ $fakultas->nama_fakultas }}
                    </button>
                </h2>
                <div id="collapse{{ $fakultas->id }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#accordionFakultas">
                    <div class="accordion-body bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold text-secondary">Daftar Jurusan di Fakultas Ini:</span>
                            <div>
                                <button class="btn btn-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalTambahJurusan{{ $fakultas->id }}">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Jurusan
                                </button>
                                <form action="{{ route('fakultas.destroy', $fakultas->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus fakultas ini beserta seluruh jurusannya?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Hapus Fakultas</button>
                                </form>
                            </div>
                        </div>

                        {{-- TABEL JURUSAN --}}
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered bg-white align-middle">
                                <thead class="table-secondary text-center">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Nama Jurusan</th>
                                        <th width="15%">Jenjang</th>
                                        <th>Preset Kategori Bobot</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fakultas->jurusan as $jIndex => $jurusan)
                                        <tr>
                                            <td class="text-center">{{ $jIndex + 1 }}</td>
                                            <td class="fw-semibold">{{ $jurusan->nama_jurusan }}</td>
                                            <td class="text-center"><span class="badge bg-secondary">{{ $jurusan->jenjang ?? 'S1' }}</span></td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark">{{ $jurusan->kategoriBobot->nama_kategori ?? 'Standard' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('jurusan.destroy', $jurusan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jurusan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-2">Belum ada jurusan di fakultas ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL TAMBAH JURUSAN --}}
            <div class="modal fade" id="modalTambahJurusan{{ $fakultas->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <form action="{{ route('jurusan.store', $fakultas->id) }}" method="POST" class="modal-content">
                        @csrf
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">Tambah Jurusan - {{ $fakultas->nama_fakultas }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Jurusan</label>
                                <textarea name="nama_jurusan" class="form-control" rows="4" placeholder="Masukan nama jurusan&#10;Contoh:&#10;Informatika&#10;Sistem Informasi" required></textarea>
                                <small class="text-muted">*Bisa isi 1 nama jurusan, atau pisahkan dengan Enter/koma jika lebih dari satu.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Jenjang</label>
                                <select name="jenjang" class="form-select">
                                    <option value="S1">S1</option>
                                    <option value="D3">D3</option>
                                    <option value="D4">D4</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Kategori Bobot Kriteria</label>
                                <select name="kategori_bobot_id" class="form-select" required>
                                    @foreach($kategoriBobots as $kb)
                                        <option value="{{ $kb->id }}">{{ $kb->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Simpan Jurusan</button>
                        </div>
                    </form>
                </div>
            </div>
        @empty
            <div class="alert alert-info">Belum ada fakultas yang ditambahkan untuk universitas ini.</div>
        @endforelse
    </div>
</div>

{{-- MODAL TAMBAH FAKULTAS --}}
<div class="modal fade" id="modalTambahFakultas" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('fakultas.store', $universitas->id) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Fakultas Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Fakultas</label>
                    <textarea name="nama_fakultas" class="form-control" rows="4" placeholder="Masukan nama fakultas&#10;Contoh:&#10;Fakultas Ilmu Komputer&#10;Fakultas Ekonomi" required></textarea>
                    <small class="text-muted">*Bisa isi 1 nama fakultas, atau pisahkan dengan Enter/koma jika lebih dari satu.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Fakultas</button>
            </div>
        </form>
    </div>
</div>
@endsection