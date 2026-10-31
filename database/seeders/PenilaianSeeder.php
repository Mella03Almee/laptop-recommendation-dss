<?php

namespace Database\Seeders;

use App\Models\Alternatif;
use App\Models\Penilaian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenilaianSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // Kosongkan penilaian lama agar hasil seeder selalu bersih
            Penilaian::query()->delete();

            $alternatifs = Alternatif::query()
                ->orderBy('id')
                ->get();

            foreach ($alternatifs as $alternatif) {

                // Alternatif tanpa data spesifikasi lengkap tidak dinilai
                if (
                    $this->isEmpty($alternatif->harga) ||
                    $this->isEmpty($alternatif->cpu) ||
                    $this->isEmpty($alternatif->gpu) ||
                    $this->isEmpty($alternatif->ram) ||
                    $this->isEmpty($alternatif->storage) ||
                    $this->isEmpty($alternatif->layar) ||
                    $this->isEmpty($alternatif->berat) ||
                    $this->isEmpty($alternatif->kamera) ||
                    $this->isEmpty($alternatif->baterai)
                ) {
                    continue;
                }

                $penilaian = Penilaian::create([
                    'user_id'      => $alternatif->user_id,
                    'alternatif_id' => $alternatif->id,
                    'slug'         => $alternatif->slug,

                    'C1x' => $this->scoreHarga($alternatif->harga),
                    'C2x' => $this->scoreCpu($alternatif->cpu),
                    'C3x' => $this->scoreRam($alternatif->ram),
                    'C4x' => $this->scoreStorage($alternatif->storage),
                    'C5x' => $this->scoreGpu($alternatif->gpu),
                    'C6x' => $this->scoreLayar($alternatif->layar, $alternatif->id),
                    'C7x' => $this->scoreBaterai($alternatif->baterai),
                    'C8x' => $this->scoreBerat($alternatif->berat),
                    'C9x' => $this->scoreKamera($alternatif->kamera),

                    // Nilai turunan dihitung pada proses WP
                    'C1_val' => null,
                    'C2_val' => null,
                    'C3_val' => null,
                    'C4_val' => null,
                    'C5_val' => null,
                    'C6_val' => null,
                    'C7_val' => null,
                    'C8_val' => null,
                    'C9_val' => null,
                    'nilai_s' => null,
                    'nilai_v' => null,
                ]);
            }
        });
    }

    /**
     * C1 - Harga
     * Cost: semakin murah semakin baik.
     */
    private function scoreHarga($harga): int
    {
        $harga = $this->parseHarga($harga);

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
     * C2 - CPU
     * Benefit: semakin tinggi kelas CPU semakin baik.
     */
    private function scoreCpu($cpu): int
    {
        $cpu = Str::lower(trim($cpu));

        // Kelas tertinggi
        if (
            str_contains($cpu, 'core ultra 9') ||
            str_contains($cpu, 'ultra 9') ||
            str_contains($cpu, 'core i9') ||
            preg_match('/\bi9\b/', $cpu) ||
            str_contains($cpu, 'ryzen ai 9') ||
            str_contains($cpu, 'ryzen 9') ||
            str_contains($cpu, 'm3 max') ||
            str_contains($cpu, 'm2 max') ||
            str_contains($cpu, 'm4 max') ||
            str_contains($cpu, 'm3 ultra') ||
            str_contains($cpu, 'm2 ultra')
        ) {
            return 5;
        }

        // Kelas tinggi
        if (
            str_contains($cpu, 'core ultra 7') ||
            str_contains($cpu, 'ultra 7') ||
            str_contains($cpu, 'core i7') ||
            preg_match('/\bi7\b/', $cpu) ||
            str_contains($cpu, 'core 7') ||
            str_contains($cpu, 'ryzen ai 7') ||
            str_contains($cpu, 'ryzen 7') ||
            str_contains($cpu, 'm3 pro') ||
            str_contains($cpu, 'm2 pro') ||
            str_contains($cpu, 'm4 pro') ||
            str_contains($cpu, 'snapdragon x elite')
        ) {
            return 4;
        }

        // Kelas menengah
        if (
            str_contains($cpu, 'core ultra 5') ||
            str_contains($cpu, 'ultra 5') ||
            str_contains($cpu, 'core i5') ||
            preg_match('/\bi5\b/', $cpu) ||
            str_contains($cpu, 'core 5') ||
            str_contains($cpu, 'ryzen ai 5') ||
            str_contains($cpu, 'ryzen 5') ||
            str_contains($cpu, 'snapdragon x plus') ||
            str_contains($cpu, 'apple m3') ||
            str_contains($cpu, 'apple m2') ||
            str_contains($cpu, 'apple m1') ||
            str_contains($cpu, 'core i3-n305')
        ) {
            return 3;
        }

        // Kelas entry
        if (
            preg_match('/\bi3\b/', $cpu) ||
            str_contains($cpu, 'ryzen 3') ||
            str_contains($cpu, 'pentium') ||
            str_contains($cpu, 'athlon')
        ) {
            return 2;
        }

        return 1;
    }

    /**
     * C3 - RAM
     * Benefit: semakin besar RAM semakin baik.
     */
    private function scoreRam($ram): int
    {
        preg_match_all('/(\d+(?:\.\d+)?)\s*GB/i', $ram, $matches);

        if (empty($matches[1])) {
            return 1;
        }

        $gb = max(array_map('floatval', $matches[1]));

        if ($gb >= 32) {
            return 5;
        }

        if ($gb >= 16) {
            return 4;
        }

        if ($gb >= 12) {
            return 3;
        }

        if ($gb >= 8) {
            return 2;
        }

        return 1;
    }

    /**
     * C4 - Storage
     * Benefit: semakin besar storage semakin baik.
     */
    private function scoreStorage($storage): int
    {
        $gb = $this->storageToGb($storage);

        if ($gb >= 2048) {
            return 5;
        }

        if ($gb >= 1024) {
            return 4;
        }

        if ($gb >= 512) {
            return 3;
        }

        if ($gb >= 256) {
            return 2;
        }

        return 1;
    }

    /**
     * C5 - GPU
     * Benefit: semakin tinggi kelas GPU semakin baik.
     */
    private function scoreGpu($gpu): int
    {
        $gpu = Str::lower(trim($gpu));

        // GPU kelas tertinggi
        if (
            str_contains($gpu, 'rtx 5090') ||
            str_contains($gpu, 'rtx 4090') ||
            str_contains($gpu, 'rtx 6000') ||
            str_contains($gpu, 'rtx a6000') ||
            str_contains($gpu, 'rtx a5000')
        ) {
            return 5;
        }

        // GPU kelas tinggi
        if (
            str_contains($gpu, 'rtx 5080') ||
            str_contains($gpu, 'rtx 4080') ||
            str_contains($gpu, 'rtx 5070') ||
            str_contains($gpu, 'rtx 4070') ||
            str_contains($gpu, 'rtx 5060') ||
            str_contains($gpu, 'rtx 4060') ||
            str_contains($gpu, 'rtx 500 ada') ||
            str_contains($gpu, 'rtx 2000 ada') ||
            str_contains($gpu, 'rx 7900') ||
            str_contains($gpu, 'rx 7800') ||
            str_contains($gpu, 'rx 7700')
        ) {
            return 4;
        }

        // GPU kelas menengah
        if (
            str_contains($gpu, 'rtx 4050') ||
            str_contains($gpu, 'rtx 3050') ||
            str_contains($gpu, 'rtx 3060') ||
            str_contains($gpu, 'rtx 3070') ||
            str_contains($gpu, 'rtx 3080') ||
            str_contains($gpu, 'gtx 1660') ||
            str_contains($gpu, 'gtx 1650') ||
            str_contains($gpu, 'rx 6600') ||
            str_contains($gpu, 'rx 6500') ||
            str_contains($gpu, 'rx 7600') ||
            str_contains($gpu, 'intel arc')
        ) {
            return 3;
        }

        // GPU bawaan dengan performa menengah-ringan
        if (
            str_contains($gpu, 'mx') ||
            str_contains($gpu, 'radeon') ||
            str_contains($gpu, 'vega') ||
            str_contains($gpu, 'iris xe') ||
            str_contains($gpu, 'iris plus') ||
            str_contains($gpu, 'iris')
        ) {
            return 2;
        }

        // Qualcomm Adreno
        if (str_contains($gpu, 'adreno')) {
            return 2;
        }

        // Intel UHD / Intel Graphics
        if (
            str_contains($gpu, 'intel uhd') ||
            str_contains($gpu, 'intel graphics')
        ) {
            return 1;
        }

        // Apple integrated GPU
        if (
            str_contains($gpu, 'core gpu') ||
            preg_match('/\b(?:8|10|14|16|18|20|30|40)-?core gpu\b/i', $gpu)
        ) {
            return 3;
        }

        return 1;
    }

    /**
     * C6 - Ukuran Layar
     * Cost: semakin kecil layar semakin baik untuk portabilitas.
     */
    private function scoreLayar($layar, $alternatifId = null): int
    {
        $ukuran = $this->extractUkuranLayar($layar);

        /*
         * A19 memiliki kesalahan data sumber:
         * "4" WUXGA (1920x1200) IPS"
         * Seharusnya ThinkPad L14 = 14".
         */
        if (
            (int) $alternatifId === 19 &&
            str_starts_with(trim(Str::lower($layar)), '4"')
        ) {
            $ukuran = 14.0;
        }

        if ($ukuran === null) {
            return 1;
        }

        // Cost: semakin kecil ukuran layar, semakin tinggi nilainya.
        if ($ukuran <= 13.6) {
            return 5;
        }

        if ($ukuran <= 14.0) {
            return 4;
        }

        if ($ukuran <= 15.0) {
            return 3;
        }

        if ($ukuran <= 16.0) {
            return 2;
        }

        return 1;
    }

    /**
     * C7 - Baterai
     * Benefit: semakin lama daya tahan semakin baik.
     */
    private function scoreBaterai($baterai): int
    {
        $bateraiLower = Str::lower(trim($baterai));

        // Ambil angka jam, termasuk format 5-6 jam / 10-12 jam / hingga 18 jam.
        preg_match_all(
            '/(\d+(?:\.\d+)?)\s*(?:-|–|—|sampai|hingga)?\s*(\d+(?:\.\d+)?)?\s*jam/i',
            $bateraiLower,
            $matches
        );

        if (!empty($matches[1])) {
            $jam = [];

            foreach ($matches[1] as $index => $awal) {
                $jam[] = (float) $awal;

                if (!empty($matches[2][$index])) {
                    $jam[] = (float) $matches[2][$index];
                }
            }

            $maksJam = max($jam);

            if ($maksJam >= 15) {
                return 5;
            }

            if ($maksJam >= 10) {
                return 4;
            }

            if ($maksJam >= 7) {
                return 3;
            }

            if ($maksJam >= 5) {
                return 2;
            }

            return 1;
        }

        // Jika tidak ada informasi jam, gunakan kapasitas Wh.
        if (preg_match('/(\d+(?:\.\d+)?)\s*wh/i', $bateraiLower, $match)) {
            $wh = (float) $match[1];

            if ($wh >= 80) {
                return 5;
            }

            if ($wh >= 65) {
                return 4;
            }

            if ($wh >= 50) {
                return 3;
            }

            if ($wh >= 40) {
                return 2;
            }

            return 1;
        }

        // Penilaian berdasarkan deskripsi.
        if (str_contains($bateraiLower, 'sangat panjang')) {
            return 5;
        }

        if (str_contains($bateraiLower, 'panjang')) {
            return 4;
        }

        if (str_contains($bateraiLower, 'standar')) {
            return 3;
        }

        if (str_contains($bateraiLower, 'efisien')) {
            return 3;
        }

        if (str_contains($bateraiLower, 'pendek')) {
            return 2;
        }

        return 1;
    }

    /**
     * C8 - Berat
     * Cost: semakin ringan semakin baik.
     */
    private function scoreBerat($berat): int
    {
        $berat = Str::lower(trim($berat));

        if (preg_match('/<\s*(\d+(?:\.\d+)?)\s*kg/i', $berat, $match)) {
            $kg = (float) $match[1];
            return 5;
        }

        if (preg_match('/(\d+(?:\.\d+)?)\s*kg/i', $berat, $match)) {
            $kg = (float) $match[1];

            if ($kg <= 1.5) {
                return 5;
            }

            if ($kg <= 2.0) {
                return 4;
            }

            if ($kg <= 2.5) {
                return 3;
            }

            if ($kg <= 3.0) {
                return 2;
            }

            return 1;
        }

        return 1;
    }

    /**
     * C9 - Kamera
     * Benefit: semakin tinggi kualitas kamera semakin baik.
     */
    private function scoreKamera($kamera): int
    {
        $kamera = Str::lower(trim($kamera));

        // Kamera berdasarkan megapixel.
        if (preg_match('/(\d+(?:\.\d+)?)\s*mp/i', $kamera, $match)) {
            $mp = (float) $match[1];

            if ($mp >= 8) {
                return 5;
            }

            if ($mp >= 5) {
                return 4;
            }

            if ($mp >= 2) {
                return 3;
            }

            if ($mp >= 1) {
                return 2;
            }

            return 1;
        }

        // Kamera berdasarkan resolusi.
        if (
            str_contains($kamera, '1080p') ||
            str_contains($kamera, 'full hd')
        ) {
            return 3;
        }

        if (
            str_contains($kamera, '720p') ||
            str_contains($kamera, 'hd')
        ) {
            return 2;
        }

        if (str_contains($kamera, 'camera') || str_contains($kamera, 'webcam')) {
            return 2;
        }

        return 1;
    }

    /**
     * Konversi harga ke angka.
     */
    private function parseHarga($harga): float
    {
        $harga = trim((string) $harga);

        // Ambil bagian angka pertama.
        preg_match('/[\d.,]+/', $harga, $match);

        if (empty($match[0])) {
            return 0;
        }

        $value = $match[0];

        // Format Indonesia: 3.460.000
        if (substr_count($value, '.') > 1) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } elseif (substr_count($value, '.') === 1) {
            $parts = explode('.', $value);

            // Jika tiga digit setelah titik, anggap titik sebagai pemisah ribuan.
            if (isset($parts[1]) && strlen($parts[1]) === 3) {
                $value = str_replace('.', '', $value);
            } else {
                $value = str_replace(',', '.', $value);
            }
        } else {
            $value = str_replace(',', '.', $value);
        }

        return (float) $value;
    }

    /**
     * Konversi storage menjadi GB.
     */
    private function storageToGb($storage): float
    {
        $storage = Str::lower(trim($storage));
        $values = [];

        preg_match_all('/(\d+(?:\.\d+)?)\s*tb/i', $storage, $tbMatches);

        foreach ($tbMatches[1] as $tb) {
            $values[] = (float) $tb * 1024;
        }

        preg_match_all('/(\d+(?:\.\d+)?)\s*gb/i', $storage, $gbMatches);

        foreach ($gbMatches[1] as $gb) {
            $values[] = (float) $gb;
        }

        return empty($values) ? 0 : max($values);
    }

    /**
     * Ambil ukuran layar saja.
     * Menghindari angka resolusi seperti 1920x1200.
     */
    private function extractUkuranLayar($layar): ?float
    {
        $layar = Str::lower(trim($layar));

        if (
            preg_match(
                '/(\d+(?:\.\d+)?)\s*(?:"|inch|inci)/i',
                $layar,
                $match
            )
        ) {
            return (float) $match[1];
        }

        return null;
    }

    /**
     * Cek apakah data kosong.
     */
    private function isEmpty($value): bool
    {
        return is_null($value) || trim((string) $value) === '';
    }
}