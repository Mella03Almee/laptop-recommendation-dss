<?php

namespace App\Services;

use App\Models\Jurusan;
use App\Models\Kriteria;
use App\Models\Penilaian;
use InvalidArgumentException;

class WeightedProductService
{
    /**
     * Menghitung Weighted Product berdasarkan profil jurusan.
     *
     * Catatan:
     * - Tidak menyimpan nilai_s dan nilai_v ke database.
     * - C1 menggunakan skor kontekstual berdasarkan harga jurusan.
     * - C2-C9 menggunakan skor dasar dari penilaian.
     * - Seluruh kriteria dan bobot divalidasi sebelum perhitungan.
     *
     * @param int|null   $jurusanId
     * @param float|null $maxHarga
     */
    public function calculate($jurusanId = null, $maxHarga = null): array
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Ambil sembilan kriteria dengan ID yang eksplisit
        |--------------------------------------------------------------------------
        */

        $kriterias = Kriteria::whereIn('id', range(1, 9))
            ->orderBy('id')
            ->get()
            ->keyBy('id');

        for ($i = 1; $i <= 9; $i++) {
            if (!$kriterias->has($i)) {
                throw new InvalidArgumentException(
                    "Kriteria dengan ID {$i} tidak ditemukan. "
                    . 'Pastikan ID kriteria 1-9 sesuai dengan C1-C9.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Ambil profil jurusan dan bobot
        |--------------------------------------------------------------------------
        */

        $jurusan = null;
        $hargaMinSaran = null;
        $hargaMaxSaran = null;
        $bobot = [];

        if ($jurusanId !== null) {
            $jurusan = Jurusan::with('kategoriBobot')
                ->find($jurusanId);

            if (!$jurusan) {
                throw new InvalidArgumentException(
                    "Jurusan dengan ID {$jurusanId} tidak ditemukan."
                );
            }

            if (!$jurusan->kategoriBobot) {
                throw new InvalidArgumentException(
                    'Kategori bobot untuk jurusan '
                    . $jurusan->nama_jurusan
                    . ' belum tersedia.'
                );
            }

            $hargaMinSaran = (float) $jurusan->harga_min_saran;
            $hargaMaxSaran = (float) $jurusan->harga_max_saran;

            if (
                $hargaMinSaran <= 0
                || $hargaMaxSaran <= $hargaMinSaran
            ) {
                throw new InvalidArgumentException(
                    'Rentang harga jurusan '
                    . $jurusan->nama_jurusan
                    . ' tidak valid.'
                );
            }

            $kategori = $jurusan->kategoriBobot;

            $bobot = [
                1 => (float) $kategori->c1_harga,
                2 => (float) $kategori->c2_cpu,
                3 => (float) $kategori->c3_ram,
                4 => (float) $kategori->c4_storage,
                5 => (float) $kategori->c5_gpu,
                6 => (float) $kategori->c6_ukuran_layar,
                7 => (float) $kategori->c7_baterai,
                8 => (float) $kategori->c8_berat,
                9 => (float) $kategori->c9_kamera,
            ];
        } else {
            /*
             * Mode umum tanpa jurusan:
             * gunakan bobot dasar pada tabel kriterias.
             */
            foreach (range(1, 9) as $id) {
                $bobot[$id] = (float) $kriterias[$id]->bobot;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Validasi dan normalisasi bobot
        |--------------------------------------------------------------------------
        */

        if (count($bobot) !== 9) {
            throw new InvalidArgumentException(
                'Jumlah bobot kriteria harus tepat sembilan.'
            );
        }

        foreach (range(1, 9) as $id) {
            if (
                !isset($bobot[$id])
                || !is_finite($bobot[$id])
                || $bobot[$id] <= 0
            ) {
                throw new InvalidArgumentException(
                    "Bobot C{$id} tidak valid. "
                    . 'Setiap bobot harus berupa angka positif.'
                );
            }
        }

        $totalBobot = array_sum($bobot);

        if (!is_finite($totalBobot) || $totalBobot <= 0) {
            throw new InvalidArgumentException(
                'Total bobot kriteria harus lebih besar dari nol.'
            );
        }

        $wNormalisasi = [];

        foreach ($bobot as $id => $nilai) {
            $wNormalisasi[$id] = $nilai / $totalBobot;
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Ambil penilaian beserta alternatif laptop
        |--------------------------------------------------------------------------
        */

       
        $penilaians = Penilaian::with('alternatif')
            ->where('status_audit', 'siap_diaudit')
            ->whereNotNull('C1x')
            ->whereNotNull('C2x')
            ->whereNotNull('C3x')
            ->whereNotNull('C4x')
            ->whereNotNull('C5x')
            ->whereNotNull('C6x')
            ->whereNotNull('C7x')
            ->whereNotNull('C8x')
            ->whereNotNull('C9x')
            ->get()
            ->filter(function ($penilaian) {
                if (!$penilaian->alternatif) {
                    return false;
                }

                for ($i = 1; $i <= 9; $i++) {
                    $kolom = "C{$i}x";
                    $nilai = $penilaian->{$kolom};

                    if (
                        !is_numeric($nilai)
                        || !is_finite((float) $nilai)
                        || (float) $nilai < 1
                        || (float) $nilai > 5
                    ) {
                        return false;
                    }
                }

                return true;
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | 5. Filter harga opsional
        |--------------------------------------------------------------------------
        */

        if ($maxHarga !== null) {
            $batasHarga = (float) $maxHarga;

            if (!is_finite($batasHarga) || $batasHarga <= 0) {
                throw new InvalidArgumentException(
                    'Batas harga harus berupa angka positif.'
                );
            }

            $penilaians = $penilaians
                ->filter(function ($penilaian) use ($batasHarga) {
                    $harga = $this->parseHarga(
                        $penilaian->alternatif->harga ?? 0
                    );

                    return $harga > 0 && $harga <= $batasHarga;
                })
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Hitung nilai S
        |--------------------------------------------------------------------------
        */

        foreach ($penilaians as $penilaian) {
            $nilaiS = 1.0;

            for ($i = 1; $i <= 9; $i++) {
                if ($i === 1 && $jurusan !== null) {
                    /*
                     * C1 kontekstual.
                     * Nilai C1x asli di database tidak diubah.
                     */
                    $hargaLaptop = $this->parseHarga(
                        $penilaian->alternatif->harga ?? 0
                    );

                    if ($hargaLaptop <= 0) {
                        $nilaiS = 0;
                        break;
                    }

                    $nilaiKriteria = $this->scoreHargaBerdasarkanJurusan(
                        $hargaLaptop,
                        $hargaMinSaran,
                        $hargaMaxSaran
                    );
                } else {
                    $kolom = "C{$i}x";
                    $nilaiKriteria = (float) $penilaian->{$kolom};
                }

                $w = $wNormalisasi[$i];

                $nilaiS *= pow($nilaiKriteria, $w);
            }

            /*
             * Nilai disimpan hanya pada objek di memori,
             * bukan ke database.
             */
            $penilaian->nilai_s = $nilaiS;
        }

        /*
        |--------------------------------------------------------------------------
        | 7. Hitung total nilai S dan nilai V
        |--------------------------------------------------------------------------
        */

        $totalNilaiS = (float) $penilaians->sum('nilai_s');

        foreach ($penilaians as $penilaian) {
            $penilaian->nilai_v = $totalNilaiS > 0
                ? (float) $penilaian->nilai_s / $totalNilaiS
                : 0.0;
        }

        /*
        |--------------------------------------------------------------------------
        | 8. Urutkan hasil dan tambahkan ranking
        |--------------------------------------------------------------------------
        */

        $penilaians = $penilaians
            ->sortByDesc('nilai_v')
            ->values();

        foreach ($penilaians as $index => $penilaian) {
            $penilaian->ranking = $index + 1;
        }

        /*
        |--------------------------------------------------------------------------
        | 9. Kembalikan hasil
        |--------------------------------------------------------------------------
        */

        return [
            'penilaians' => $penilaians,
            'kriterias' => $kriterias->values(),
            'bobot' => $bobot,
            'w_normalisasi' => $wNormalisasi,
            'total_nilai_s' => $totalNilaiS,
            'jurusan' => $jurusan,
            'harga_min_saran' => $hargaMinSaran,
            'harga_max_saran' => $hargaMaxSaran,
        ];
    }

    /**
     * Menentukan skor C1 berdasarkan rentang harga jurusan.
     *
     * 1 = jauh di bawah rentang
     * 2 = sedikit di bawah rentang
     * 5 = berada dalam rentang
     * 3 = sedikit di atas rentang
     * 1 = jauh di atas rentang
     */
    private function scoreHargaBerdasarkanJurusan(
        float $harga,
        ?float $hargaMin,
        ?float $hargaMax
    ): int {
        if (
            $hargaMin === null
            || $hargaMax === null
            || $hargaMin <= 0
            || $hargaMax <= $hargaMin
        ) {
            return $this->scoreHargaDasar($harga);
        }

        $lebarRentang = $hargaMax - $hargaMin;
        $margin = $lebarRentang * 0.5;

        // Zona 1: jauh di bawah rentang.
        if ($harga < ($hargaMin - $margin)) {
            return 1;
        }

        // Zona 2: sedikit di bawah rentang.
        if ($harga < $hargaMin) {
            return 2;
        }

        // Zona 3: berada di dalam rentang.
        if ($harga <= $hargaMax) {
            return 5;
        }

        // Zona 4: sedikit di atas rentang.
        if ($harga <= ($hargaMax + $margin)) {
            return 3;
        }

        // Zona 5: jauh di atas rentang.
        return 1;
    }

    /**
     * Skor harga dasar jika calculate() dipanggil tanpa jurusan.
     */
    private function scoreHargaDasar(float $harga): int
    {
        if ($harga <= 5_000_000) {
            return 5;
        }

        if ($harga <= 8_000_000) {
            return 4;
        }

        if ($harga <= 12_000_000) {
            return 3;
        }

        if ($harga <= 20_000_000) {
            return 2;
        }

        return 1;
    }

    /**
     * Mengubah format harga menjadi angka.
     */
    private function parseHarga($harga): float
    {
        if (is_numeric($harga)) {
            return (float) $harga;
        }

        if ($harga === null || $harga === '') {
            return 0.0;
        }

        $harga = preg_replace('/[^0-9]/', '', (string) $harga);

        return $harga !== ''
            ? (float) $harga
            : 0.0;
    }

    /**
     * Mengambil seluruh penilaian beserta alternatifnya.
     */
    public function getAllAlternatifs()
    {
        return Penilaian::with('alternatif')->get();
    }
}
