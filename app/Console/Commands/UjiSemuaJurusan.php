<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Models\Jurusan;
use App\Http\Controllers\Front\RekomendasiController;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UjiSemuaJurusan extends Command
{
    protected $signature = 'uji:semua-jurusan 
                            {--limit=0 : Batas jumlah jurusan, 0 untuk semua}
                            {--export : Simpan hasil pengujian ke file CSV}';

    protected $description = 'Menguji seluruh jurusan dan 5 jenis anggaran serta menampilkan detail rekomendasi laptopnya';

    public function handle(RekomendasiController $controller)
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $limit = (int) $this->option('limit');
        $export = $this->option('export');

        $query = Jurusan::query();
        if ($limit > 0) {
            $query->take($limit);
        }

        $jurusans = $query->get();
        $totalJurusan = $jurusans->count();

        if ($totalJurusan === 0) {
            $this->error('Tidak ada data jurusan yang ditemukan!');
            return;
        }

        $this->info("Memulai pengujian untuk {$totalJurusan} jurusan (Total " . ($totalJurusan * 5) . " kombinasi anggaran)...\n");

        $errorList = [];
        $berhasil = 0;
        $exportData = [];

        // Header CSV jika flag export aktif
        if ($export) {
            $exportData[] = [
                'ID Jurusan',
                'Nama Jurusan',
                'Rentang Saran Jurusan',
                'Kategori Anggaran User',
                'Nama Laptop Utama',
                'Harga Raw',
                'Harga Formatted',
                'Status Peringatan / Catatan'
            ];
        }

        foreach ($jurusans as $j) {
            $rentangJurusan = 'Rp ' . number_format($j->harga_min_saran ?? 0, 0, ',', '.') . ' - Rp ' . number_format($j->harga_max_saran ?? 0, 0, ',', '.');
            
            $this->line("==========================================================================================");
            $this->info("JURUSAN: {$j->nama_jurusan} (Saran: {$rentangJurusan})");
            $this->line("==========================================================================================");

            for ($b = 1; $b <= 5; $b++) {
                $req = new Request([
                    'nama'           => 'Automated Test All',
                    'universitas_id' => 1,
                    'fakultas_id'    => $j->fakultas_id ?? 1,
                    'jurusan_id'     => $j->id,
                    'c1_harga'       => $b,
                ]);

                try {
                    $res = $controller->hitung($req);

                    if ($res instanceof RedirectResponse) {
                        $msg = "Redirect (Bobot/Saran belum lengkap)";
                        $this->warn("  [Anggaran {$b}] {$msg}");
                        $errorList[] = "Jurusan ID {$j->id} ({$j->nama_jurusan}) - Budget {$b}: {$msg}";
                        continue;
                    }

                    if ($res instanceof View) {
                        $data = $res->getData();
                        $utama = $data['utama'] ?? null;
                        $budgetInfo = $data['budgetInfo']['label'] ?? "Kategori {$b}";

                        if ($utama) {
                            $berhasil++;
                            $namaLaptop = $utama['nama_laptop'] ?? 'N/A';
                            $hargaRaw = $utama['harga_raw'] ?? 0;
                            $hargaFormatted = 'Rp ' . number_format($hargaRaw, 0, ',', '.');
                            $peringatan = $utama['peringatan'] ?? 'Sesuai Target';

                            // Tampilkan langsung di terminal
                            $this->line("  [Anggaran {$b} ({$budgetInfo})]");
                            $this->line("  -> Rekomendasi Utama : {$namaLaptop}");
                            $this->line("  -> Harga Laptop      : {$hargaFormatted}");
                            if (!empty($utama['peringatan'])) {
                                $this->comment("  -> Catatan/Peringatan: {$peringatan}");
                            }
                            $this->line("  --------------------------------------------------");

                            // Tampung data untuk CSV
                            if ($export) {
                                $exportData[] = [
                                    $j->id,
                                    $j->nama_jurusan,
                                    $rentangJurusan,
                                    $budgetInfo,
                                    $namaLaptop,
                                    $hargaRaw,
                                    $hargaFormatted,
                                    $peringatan
                                ];
                            }
                        } else {
                            $this->error("  [Anggaran {$b}] Rekomendasi utama kosong");
                            $errorList[] = "Jurusan ID {$j->id} ({$j->nama_jurusan}) - Budget {$b}: Rekomendasi utama kosong";
                        }
                    }

                } catch (\Exception $e) {
                    $this->error("  [Anggaran {$b}] ERROR: " . $e->getMessage());
                    $errorList[] = "Jurusan ID {$j->id} ({$j->nama_jurusan}) - Budget {$b}: " . $e->getMessage();
                }
            }

            $this->line("");
        }

        $this->info("=== RINGKASAN PENGUJIANS ===");
        $this->info("Total kombinasi berhasil diproses : {$berhasil} / " . ($totalJurusan * 5));

        // Simpan ke file CSV jika flag export digunakan
        if ($export) {
            $filename = 'hasil_pengujian_950_kombinasi_' . date('Ymd_His') . '.csv';
            $filePath = storage_path('app/' . $filename);
            
            $file = fopen($filePath, 'w');
            foreach ($exportData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);

            $this->info("\nHasil pengujian berhasil diekspor ke file CSV:");
            $this->comment("Lokasi file: " . $filePath);
        }

        if (count($errorList) > 0) {
            $this->warn("\nDitemukan " . count($errorList) . " kendala/error:");
            foreach ($errorList as $err) {
                $this->error(" - " . $err);
            }
        }
    }
}