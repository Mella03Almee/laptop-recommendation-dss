<?php

namespace App\Console\Commands;

use App\Models\Alternatif;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AuditSkorLaptop extends Command
{
    protected $signature = 'spk:audit-skor-laptop';

    protected $description =
        'Audit skor laptop berdasarkan spesifikasi tanpa mengubah database';

    public function handle(): int
    {
        $rows = [];
        $alternatifs = Alternatif::orderBy('id')->get();

        foreach ($alternatifs as $a) {
            $issues = [];

            $cpu = $this->scoreCpu($a->cpu, $issues);
            $ram = $this->scoreRam($a->ram, $issues);
            $storage = $this->scoreStorage($a->storage, $issues);
            $gpu = $this->scoreGpu($a->gpu, $issues);
            $screen = $this->scoreScreen($a->layar, $issues);
            $battery = $this->scoreBattery($a->baterai, $issues);
            $weight = $this->scoreWeight($a->berat, $issues);
            $camera = $this->scoreCamera($a->kamera, $issues);

            $scores = [
                $cpu, $ram, $storage, $gpu,
                $screen, $battery, $weight, $camera,
            ];

            $sourceSpecs = [
                $a->cpu, $a->ram, $a->storage, $a->gpu,
                $a->layar, $a->baterai, $a->berat, $a->kamera,
            ];

            if (in_array(null, $sourceSpecs, true)) {
                $issues[] = 'Ada spesifikasi kosong';
            }

            $ready = !in_array(null, $scores, true)
                && !in_array(null, $sourceSpecs, true);

            $rows[] = [
                $a->kodeAlternatif,
                $a->namaAlternatif,
                $a->harga,
                $a->cpu,
                $a->ram,
                $a->storage,
                $a->gpu,
                $a->layar,
                $a->baterai,
                $a->berat,
                $a->kamera,
                $cpu,
                $ram,
                $storage,
                $gpu,
                $screen,
                $battery,
                $weight,
                $camera,
                $ready ? 'SIAP DIAUDIT' : 'PERLU PERBAIKAN',
                implode('; ', array_unique($issues)),
            ];
        }

        $stream = fopen('php://temp', 'r+');

        if ($stream === false) {
            $this->error('Gagal membuat laporan sementara.');
            return self::FAILURE;
        }

        fputcsv($stream, [
            'Kode',
            'Nama Laptop',
            'Harga',
            'CPU_Asli',
            'RAM_Asli',
            'Storage_Asli',
            'GPU_Asli',
            'Layar_Asli',
            'Baterai_Asli',
            'Berat_Asli',
            'Kamera_Asli',
            'C2_CPU',
            'C3_RAM',
            'C4_Storage',
            'C5_GPU',
            'C6_Layar',
            'C7_Baterai',
            'C8_Berat',
            'C9_Kamera',
            'Status Audit',
            'Catatan Audit',
        ]);

        foreach ($rows as $row) {
            fputcsv($stream, $row);
        }

        rewind($stream);
        $csv = stream_get_contents($stream);
        fclose($stream);

        if ($csv === false) {
            $this->error('Gagal membaca hasil audit.');
            return self::FAILURE;
        }

        Storage::disk('local')->put('audit-skor-laptop.csv', $csv);

        $readyCount = collect($rows)
            ->filter(fn ($row) => $row[19] === 'SIAP DIAUDIT')
            ->count();

        $this->info('Audit selesai. Database tidak diubah.');
        $this->info('Jumlah alternatif: ' . count($rows));
        $this->info('Siap diaudit: ' . $readyCount);
        $this->info('Perlu perbaikan: ' . (count($rows) - $readyCount));
        $this->info('Laporan: storage/app/audit-skor-laptop.csv');

        return self::SUCCESS;
    }

    private function scoreCpu($value, array &$issues): ?int
    {
        if (blank($value)) {
            $issues[] = 'CPU kosong';
            return null;
        }

        $v = strtolower(trim($value));

        // Keluarga prosesor kelas atas harus diperiksa lebih dahulu.
        if (preg_match(
            '/ryzen ai 9|core ultra 9|core i9|ryzen 9|m[1-4]\s*max|x elite/',
            $v
        )) {
            return 5;
        }

        if (preg_match(
            '/celeron|pentium|athlon|snapdragon 7c/',
            $v
        )) {
            return 1;
        }

        if (preg_match(
            '/core ultra 7|core i7|ryzen ai 7|ryzen 7|m[1-4]\s*pro/',
            $v
        )) {
            return 4;
        }

        if (preg_match(
            '/core ultra 5|core 5\b|core i5|ryzen ai 5|ryzen 5|m[1-4]\b|x plus/',
            $v
        )) {
            return 3;
        }

        if (preg_match(
            '/core i3|ryzen 3|snapdragon 8cx/',
            $v
        )) {
            return 2;
        }

        $issues[] = "CPU belum terpetakan: {$value}";
        return null;
    }

    private function scoreRam($value, array &$issues): ?int
    {
        if (blank($value)) {
            $issues[] = 'RAM kosong';
            return null;
        }

        $v = strtolower(trim($value));

        if (
            preg_match('/\d+(?:\.\d+)?\s*(?:gb)?\s*\/\s*\d+/i', $v) ||
            preg_match('/hingga|up\s*to|maksimal|maximum/i', $v)
        ) {
            $issues[] = "RAM memiliki beberapa kemungkinan konfigurasi: {$value}";
            return null;
        }

        if (!preg_match('/(\d+(?:\.\d+)?)\s*gb\b/i', $v, $m)) {
            $issues[] = "RAM tidak terbaca: {$value}";
            return null;
        }

        $gb = (float) $m[1];

        if ($gb <= 0) {
            $issues[] = "Kapasitas RAM tidak wajar: {$value}";
            return null;
        }

        return match (true) {
            $gb >= 32 => 5,
            $gb >= 16 => 4,
            $gb >= 8  => 3,
            $gb >= 6  => 2,
            default   => 1,
        };
    }

    private function scoreStorage($value, array &$issues): ?int
    {
        if (blank($value)) {
            $issues[] = 'Penyimpanan kosong';
            return null;
        }

        $v = strtolower(trim($value));

        if (
            preg_match('/\d+(?:\.\d+)?\s*(?:gb|tb)?\s*\/\s*\d+/i', $v) ||
            preg_match('/hingga|up\s*to|maksimal|maximum/i', $v) ||
            preg_match('/\bemmc\s*\+\s*sd\b/i', $v)
        ) {
            $issues[] = "Kapasitas penyimpanan ambigu: {$value}";
            return null;
        }

        if (!preg_match('/(\d+(?:\.\d+)?)\s*(tb|gb)\b/i', $v, $m)) {
            $issues[] = "Penyimpanan tidak terbaca: {$value}";
            return null;
        }

        $capacity = (float) $m[1];
        $unit = strtolower($m[2]);
        $gb = $unit === 'tb' ? $capacity * 1024 : $capacity;

        if ($gb <= 0) {
            $issues[] = "Kapasitas penyimpanan tidak wajar: {$value}";
            return null;
        }

        if (preg_match('/\bemmc\b/i', $v)) {
            return $gb >= 128 ? 2 : 1;
        }

        if (preg_match('/\bssd\b|\bnvme\b/i', $v)) {
            return match (true) {
                $gb >= 1024 => 5,
                $gb >= 512  => 4,
                $gb >= 256  => 3,
                $gb >= 128  => 2,
                default     => 1,
            };
        }

        $issues[] = "Jenis penyimpanan belum jelas: {$value}";
        return null;
    }

    private function scoreGpu($value, array &$issues): ?int
    {
        if (blank($value)) {
            $issues[] = 'GPU kosong';
            return null;
        }

        $v = strtolower(trim($value));

        // GPU Apple dengan jumlah core eksplisit.
        if (preg_match(
            '/(\d+)\s*(?:\/\s*(\d+)\s*)?-\s*core\s*gpu\b/i',
            $v,
            $m
        )) {
            $cores = (int) $m[1];

            if (isset($m[2]) && $m[2] !== '') {
                $cores = max($cores, (int) $m[2]);
            }

            return match (true) {
                $cores >= 30 => 5,
                $cores >= 14 => 4,
                $cores >= 8  => 3,
                default      => 2,
            };
        }

        // GPU diskret kelas atas.
        if (preg_match(
            '/\brtx\s*(4080|4090|5080|5090)\b|\brtx\s*5000(?:\s*ada)?\b/',
            $v
        )) {
            return 5;
        }

        // GPU diskret menengah-atas.
        if (preg_match(
            '/\brtx\s*(4050|4060|4070|5070)\b|\brtx\s*(2000|3000)\s*ada\b|\brx\s*7\d{3}[a-z]*\b/',
            $v
        )) {
            return 4;
        }

        // GPU diskret kelas menengah/profesional dasar.
        if (preg_match(
            '/\brtx\s*(500|1000)\s*ada\b|\brtx\s*(2050|3050)\b|\bgtx\s*(16\d{2}|10\d{2})\b/',
            $v
        )) {
            return 3;
        }

        // GPU terintegrasi relatif mumpuni.
        if (preg_match(
            '/intel arc|iris xe|iris plus|radeon 660m|radeon 680m|radeon 760m|radeon 780m|radeon graphics|radeon vega|apple|adreno|snapdragon/',
            $v
        )) {
            return 3;
        }

        if (preg_match('/intel graphics|radeon 610m/', $v)) {
            return 2;
        }

        if (preg_match('/intel uhd|intel hd/', $v)) {
            return 1;
        }

        $issues[] = "GPU belum terpetakan: {$value}";
        return null;
    }

    private function scoreScreen($value, array &$issues): ?int
    {
        if (blank($value)) {
            $issues[] = 'Ukuran layar kosong';
            return null;
        }

        if (!preg_match('/(\d+(?:\.\d+)?)\s*"/', $value, $m)) {
            $issues[] = "Ukuran layar tidak terbaca: {$value}";
            return null;
        }

        $inch = (float) $m[1];

        if ($inch < 10 || $inch > 20) {
            $issues[] = "Ukuran layar tidak wajar, perlu verifikasi: {$value}";
            return null;
        }

        // Preferensi portabilitas: layar lebih kecil memperoleh skor lebih tinggi.
        return match (true) {
            $inch <= 13.3 => 5,
            $inch <= 14.2 => 4,
            $inch <= 15.6 => 3,
            $inch <= 17   => 2,
            default       => 1,
        };
    }

    private function scoreBattery($value, array &$issues): ?int
    {
        if (blank($value)) {
            $issues[] = 'Baterai kosong';
            return null;
        }

        $v = strtolower(trim($value));

        if (preg_match('/\bwh\b/i', $v)) {
            $issues[] = "Kapasitas baterai bukan durasi: {$value}";
            return null;
        }

        // Rentang memakai batas bawah, misalnya 5-7 jam menjadi 5 jam.
        if (preg_match(
            '/(\d+(?:\.\d+)?)\s*[-\x{2012}\x{2013}\x{2014}]\s*(\d+(?:\.\d+)?)\s*(?:jam|hours?|h)\b/iu',
            $v,
            $m
        )) {
            $hours = (float) $m[1];
        } elseif (preg_match(
            '/(?:hingga|sampai|up\s*to)\s*(\d+(?:\.\d+)?)\s*(?:jam|hours?|h)\b/iu',
            $v,
            $m
        )) {
            $hours = (float) $m[1];
            $issues[] = 'Durasi berdasarkan klaim maksimum produsen';
        } elseif (preg_match(
            '/(\d+(?:\.\d+)?)\s*(?:jam|hours?|h)\b/iu',
            $v,
            $m
        )) {
            $hours = (float) $m[1];
        } else {
            $issues[] = "Durasi baterai tidak tersedia: {$value}";
            return null;
        }

        if ($hours <= 0 || $hours > 48) {
            $issues[] = "Durasi baterai perlu diverifikasi: {$value}";
            return null;
        }

        return match (true) {
            $hours >= 12 => 5,
            $hours >= 9  => 4,
            $hours >= 6  => 3,
            $hours >= 3  => 2,
            default      => 1,
        };
    }

    private function scoreWeight($value, array &$issues): ?int
    {
        if (blank($value)) {
            $issues[] = 'Berat kosong';
            return null;
        }

        $v = strtolower(trim($value));

        if (preg_match('/<\s*(\d+(?:\.\d+)?)\s*kg\b/i', $v, $m)) {
            $kg = (float) $m[1];

            if ($kg <= 0 || $kg > 10) {
                $issues[] = "Berat perlu diverifikasi: {$value}";
                return null;
            }

            return match (true) {
                $kg <= 1.2 => 5,
                $kg <= 1.5 => 4,
                $kg <= 1.8 => 3,
                $kg <= 2.3 => 2,
                default    => 1,
            };
        }

        if (!preg_match('/(\d+(?:\.\d+)?)\s*kg\b/i', $v, $m)) {
            $issues[] = "Berat tidak terbaca: {$value}";
            return null;
        }

        $kg = (float) $m[1];

        if ($kg <= 0 || $kg > 10) {
            $issues[] = "Berat perlu diverifikasi: {$value}";
            return null;
        }

        return match (true) {
            $kg < 1.2  => 5,
            $kg <= 1.5 => 4,
            $kg <= 1.8 => 3,
            $kg <= 2.3 => 2,
            default    => 1,
        };
    }

    private function scoreCamera($value, array &$issues): ?int
    {
        if (blank($value)) {
            $issues[] = 'Kamera kosong';
            return null;
        }

        $v = strtolower(trim($value));

        if (preg_match('/\b(?:hd\s*\/\s*fhd|fhd\s*\/\s*hd)\b/i', $v)) {
            $issues[] = "Resolusi kamera ambigu: {$value}";
            return null;
        }

        // Megapiksel dinilai langsung berdasarkan angka yang tercantum.
        if (preg_match('/\b(\d+(?:\.\d+)?)\s*mp\b/i', $v, $m)) {
            $mp = (float) $m[1];

            return match (true) {
                $mp >= 8 => 5,
                $mp >= 5 => 4,
                $mp > 0  => 2,
                default  => null,
            };
        }

        if (preg_match('/\b4k\b/i', $v)) {
            return 5;
        }

        if (preg_match('/\b1440p\b|\bqhd\b/i', $v)) {
            return 4;
        }

        if (preg_match('/1080p|\bfhd\b|full hd/i', $v)) {
            return 3;
        }

        if (preg_match('/720p|\bhd\b|web camera|webcam/i', $v)) {
            return 2;
        }

        if (preg_match('/\bvga\b/i', $v)) {
            return 1;
        }

        $issues[] = "Kamera belum terpetakan: {$value}";
        return null;
    }
}
