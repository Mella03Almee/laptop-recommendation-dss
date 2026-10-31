<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Rekomendasi Laptop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="text-center mb-4">
            <h2>Cari Laptop Sesuai Kebutuhan Anda</h2>
            <p class="text-muted">Silakan pilih spesifikasi kriteria laptop yang Anda inginkan.</p>
        </div>

        <div class="card shadow-sm p-4">
            <form action="{{ route('rekomendasi.hitung') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">1. Kriteria Harga (C1)</label>
                    <select name="c1" class="form-select" required>
                        <option value="">-- Pilih Range Harga --</option>
                        @foreach($sub_kriterias->where('kriteria_id', 1) as $sub)
                            <option value="{{ $sub->nilai }}">{{ $sub->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">2. Kriteria RAM (C2)</label>
                    <select name="c2" class="form-select" required>
                        <option value="">-- Pilih Kapasitas RAM --</option>
                        @foreach($sub_kriterias->where('kriteria_id', 2) as $sub)
                            <option value="{{ $sub->nilai }}">{{ $sub->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary btn-lg">Hitung Rekomendasi</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>