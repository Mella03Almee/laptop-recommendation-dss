@extends('dashboard.layouts.main')

@section('container')
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Data Alternatif (Laptop)</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Alternatif</li>
        </ol>

        <div class="mb-3 d-flex justify-content-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahAlternatif">
                <i class="fas fa-plus me-1"></i> Tambah Alternatif
            </button>
        </div>

        <div class="card text-bg-light mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i> Daftar Alternatif
            </div>
            <div class="card-body">
                <table id="example" class="table table-hover text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Alternatif</th>
                            <th>Nama Alternatif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alternatifs as $alternatif)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $alternatif->kodeAlternatif }}</td>
                            <td>{{ $alternatif->namaAlternatif }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditAlternatif{{ $alternatif->id }}">
                                        <i class="bi bi-pen"></i> Ubah
                                    </button>
                                    
                                    <form action="{{ route('data-alternatif.destroy', $alternatif->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus alternatif {{ $alternatif->namaAlternatif }}?')">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEditAlternatif{{ $alternatif->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content text-start">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ubah Alternatif</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('data-alternatif.update', $alternatif->id) }}" method="POST">
                                        @csrf
                                        @method('put')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Kode Alternatif</label>
                                                <input type="text" class="form-control" name="kodeAlternatif" value="{{ $alternatif->kodeAlternatif }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Slug</label>
                                                <input type="text" class="form-control" name="slug" value="{{ $alternatif->slug }}" readonly required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama Alternatif (Spesifikasi Laptop)</label>
                                                <input type="text" class="form-control" name="namaAlternatif" value="{{ $alternatif->namaAlternatif }}" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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

    <div class="modal fade" id="modalTambahAlternatif" tabindex="-1" aria-labelledby="modalTambahAlternatifLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahAlternatifLabel">Tambah Alternatif Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('data-alternatif.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kodeAlternatifTambah" class="form-label">Kode Alternatif</label>
                            <input type="text" class="form-control" id="kodeAlternatifTambah" name="kodeAlternatif" placeholder="Contoh: A1" required>
                        </div>
                        <input type="hidden" id="slugTambah" name="slug" required>
                        <div class="mb-3">
                            <label for="namaAlternatifTambah" class="form-label">Nama Alternatif (Spesifikasi Laptop)</label>
                            <input type="text" class="form-control" name="namaAlternatif" placeholder="Contoh: ASUS Vivobook 14" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@push('slug')
<script>
    const kodeAlternatifTambah = document.querySelector("#kodeAlternatifTambah");
    const slugTambah = document.querySelector('#slugTambah');
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    function generateString(length) {
        let result = '';
        const charactersLength = characters.length;
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

    kodeAlternatifTambah.addEventListener("keyup", () => {
        let preslug = generateString(8);
        slugTambah.value = preslug.toLowerCase();
    });
</script>
@endpush