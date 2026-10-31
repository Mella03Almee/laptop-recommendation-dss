<?php

namespace Database\Seeders;

use App\Models\Alternatif;
use App\Models\Penilaian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PenilaianUpdateSeeder extends Seeder
{
    public function run(): void
    {
        $path = storage_path('app/audit-skor-laptop.csv');

        if (!is_file($path)) {
            throw new RuntimeException("File audit tidak ditemukan: {$path}");
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new RuntimeException('File audit gagal dibuka.');
        }

        try {
            $header = fgetcsv($handle, 0, ',', '"', '\\');

            if (!$header) {
                throw new RuntimeException('Header CSV tidak valid.');
            }

            $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
            $header = array_map('trim', $header);

            $required = [
                'Kode', 'Harga', 'C2_CPU', 'C3_RAM', 'C4_Storage',
                'C5_GPU', 'C6_Layar', 'C7_Baterai', 'C8_Berat',
                'C9_Kamera', 'Status Audit',
            ];

            foreach ($required as $column) {
                if (!in_array($column, $header, true)) {
                    throw new RuntimeException("Kolom CSV tidak ditemukan: {$column}");
                }
            }

            $rows = [];

            while (($data = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
                if (count($data) !== count($header)) {
                    throw new RuntimeException(
                        'Jumlah kolom CSV tidak konsisten: ' . implode(',', $data)
                    );
                }

                $row = array_combine($header, $data);
                $kode = trim((string) $row['Kode']);

                if ($kode === '') {
                    continue;
                }

                if (isset($rows[$kode])) {
                    throw new RuntimeException("Kode alternatif duplikat: {$kode}");
                }

                $rows[$kode] = $row;
            }
        } finally {
            fclose($handle);
        }

        $siap = 0;
        $dikecualikan = 0;
        $tidakDitemukan = [];

        DB::transaction(function () use (
            $rows, &$siap, &$dikecualikan, &$tidakDitemukan
        ) {
            foreach ($rows as $kode => $row) {
                $alternatif = Alternatif::where('kodeAlternatif', $kode)->first();

                if (!$alternatif) {
                    $tidakDitemukan[] = $kode;
                    continue;
                }

                $penilaian = Penilaian::where(
                    'alternatif_id', $alternatif->id
                )->first();

                if (!$penilaian) {
                    $tidakDitemukan[] = $kode . ' (penilaian)';
                    continue;
                }

                $skor = [];
                $kolomCsv = [
                    2 => 'C2_CPU',
                    3 => 'C3_RAM',
                    4 => 'C4_Storage',
                    5 => 'C5_GPU',
                    6 => 'C6_Layar',
                    7 => 'C7_Baterai',
                    8 => 'C8_Berat',
                    9 => 'C9_Kamera',
                ];

                foreach ($kolomCsv as $i => $column) {
                    $value = trim((string) ($row[$column] ?? ''));

                    $valid = $value !== ''
                        && is_numeric($value)
                        && (float) $value >= 1
                        && (float) $value <= 5
                        && (float) $value === (float) (int) $value;

                    $skor["C{$i}x"] = $valid ? (int) $value : null;
                }

                $lengkap = trim((string) $row['Status Audit']) === 'SIAP DIAUDIT'
                    && !in_array(null, $skor, true);

                $harga = (float) preg_replace(
                    '/[^0-9]/',
                    '',
                    (string) $alternatif->harga
                );

                if ($harga <= 0) {
                    $lengkap = false;
                }

                if ($lengkap) {
                    $c1 = match (true) {
                        $harga <= 5_000_000 => 5,
                        $harga <= 8_000_000 => 4,
                        $harga <= 12_000_000 => 3,
                        $harga <= 20_000_000 => 2,
                        default => 1,
                    };

                    $penilaian->update([
                        'C1x' => $c1,
                        ...$skor,
                        'status_audit' => 'siap_diaudit',
                    ]);

                    $siap++;
                } else {
                    // Pertahankan skor lama yang NOT NULL.
                    // Status membuat baris ini dikecualikan dari perhitungan.
                    $penilaian->update([
                        'status_audit' => 'perlu_perbaikan',
                    ]);

                    $dikecualikan++;
                }
            }
        });

        $this->command->info('Import skor selesai.');
        $this->command->info("Siap digunakan: {$siap}");
        $this->command->info("Dikecualikan: {$dikecualikan}");

        if ($tidakDitemukan) {
            $this->command->warn(
                'Alternatif/penilaian tidak ditemukan: '
                . implode(', ', $tidakDitemukan)
            );
        }
    }
}
