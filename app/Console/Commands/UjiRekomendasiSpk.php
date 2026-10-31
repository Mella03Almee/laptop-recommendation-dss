<?php

namespace App\Console\Commands;

use App\Http\Controllers\Front\RekomendasiController;
use App\Models\Jurusan;
use App\Services\WeightedProductService;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class UjiRekomendasiSpk extends Command
{
    protected $signature = 'spk:uji-rekomendasi
                            {--output=spk_test_results.csv : Nama file laporan CSV}';

    protected $description =
        'Menguji rekomendasi laptop untuk seluruh jurusan dan lima pilihan anggaran.';

    private array $anggaran = [
        1 => ['label' => '< Rp6 juta', 'min' => null, 'max' => 5999999],
        2 => ['label' => 'Rp6–8,99 juta', 'min' => 6000000, 'max' => 8999999],
        3 => ['label' => 'Rp9–11,99 juta', 'min' => 9000000, 'max' => 11999999],
        4 => ['label' => 'Rp12–14,99 juta', 'min' => 12000000, 'max' => 14999999],
        5 => ['label' => 'Rp15 juta ke atas', 'min' => 15000000, 'max' => null],
    ];

    public function handle(): int
    {
        $jurusans = Jurusan::with([
            'fakultas.universitas',
            'kategoriBobot',
        ])->orderBy('id')->get();

        if ($jurusans->isEmpty()) {
            $this->error('Tidak ada data jurusan di database.');
            return self::FAILURE;
        }

        $namaFile = basename((string) $this->option('output'));

        if ($namaFile === '' || $namaFile === '.') {
            $namaFile = 'spk_test_results.csv';
        }

        $path = storage_path('app/' . $namaFile);
        $handle = fopen($path, 'w');

        if ($handle === false) {
            $this->error('Gagal membuat file laporan: ' . $path);
            return self::FAILURE;
        }

        // BOM agar karakter Indonesia terbaca baik di Excel.
        fwrite($handle, "\xEF\xBB\xBF");

        fputcsv($handle, [
            'jurusan_id',
            'jurusan',
            'anggaran_id',
            'anggaran',
            'jumlah_kandidat_valid',
            'utama_diharapkan',
            'utama_aktual',
            'performa_diharapkan',
            'performa_aktual',
            'anggaran_diharapkan',
            'anggaran_aktual',
            'status',
            'detail',
        ], ';');

        $total = 0;
        $lulus = 0;
        $gagal = 0;

        $bar = $this->output->createProgressBar(
            $jurusans->count() * count($this->anggaran)
        );

        $bar->start();

        foreach ($jurusans as $jurusan) {
            foreach ($this->anggaran as $budgetId => $budgetInfo) {
                $total++;

                $errors = [];
                $jumlahKandidat = 0;

                $expectedMainId = null;
                $actualMainId = null;
                $expectedPerformanceId = null;
                $actualPerformanceId = null;
                $expectedBudgetId = null;
                $actualBudgetId = null;

                DB::beginTransaction();

                try {
                    /*
                     * Hitung skor WP untuk jurusan ini.
                     * Transaksi akan dibatalkan setelah skenario selesai.
                     */
                    $wp = app(WeightedProductService::class)
                        ->calculate($jurusan->id);

                    $kandidat = $this->buildCandidates(
                        $wp['penilaians'] ?? []
                    );

                    $jumlahKandidat = count($kandidat);

                    if ($jumlahKandidat === 0) {
                        throw new \RuntimeException(
                            'Tidak ada laptop dengan harga, penilaian, '
                            . 'dan spesifikasi lengkap.'
                        );
                    }

                    $min = (float) $jurusan->harga_min_saran;
                    $max = (float) $jurusan->harga_max_saran;

                    if ($min <= 0 || $max <= $min) {
                        throw new \RuntimeException(
                            'Rentang harga jurusan tidak valid.'
                        );
                    }

                    if (!$jurusan->kategoriBobot) {
                        throw new \RuntimeException(
                            'Kategori bobot jurusan belum tersedia.'
                        );
                    }

                    /*
                     * A. Hitung rekomendasi utama yang seharusnya terpilih.
                     */
                    $expectedMain = $this->expectedMain(
                        $kandidat,
                        $min,
                        $max
                    );

                    $expectedMainId = $expectedMain['id_laptop'];

                    /*
                     * B. Hitung pilihan performa yang seharusnya terpilih.
                     */
                    $bobot = [
                        2 => (float) ($jurusan->kategoriBobot->c2_cpu ?? 5),
                        3 => (float) ($jurusan->kategoriBobot->c3_ram ?? 5),
                        4 => (float) ($jurusan->kategoriBobot->c4_storage ?? 4),
                        5 => (float) ($jurusan->kategoriBobot->c5_gpu ?? 3),
                        6 => (float) ($jurusan->kategoriBobot->c6_ukuran_layar ?? 3),
                        7 => (float) ($jurusan->kategoriBobot->c7_baterai ?? 4),
                        8 => (float) ($jurusan->kategoriBobot->c8_berat ?? 3),
                        9 => (float) ($jurusan->kategoriBobot->c9_kamera ?? 1),
                    ];

                    $expectedPerformance = $this->expectedPerformance(
                        $kandidat,
                        $expectedMain,
                        $bobot
                    );

                    $expectedPerformanceId =
                        $expectedPerformance['id_laptop'] ?? null;

                    /*
                     * C. Panggil controller sesungguhnya.
                     */
                    $request = Request::create('/uji-rekomendasi', 'POST', [
                        'nama' => 'Pengujian Otomatis',
                        'universitas_id' =>
                            $jurusan->fakultas?->universitas?->id,
                        'fakultas_id' => $jurusan->fakultas_id,
                        'jurusan_id' => $jurusan->id,
                        'c1_harga' => $budgetId,
                    ]);

                    $controller = app(RekomendasiController::class);
                    $response = $controller->hitung($request);

                    if (!$response instanceof View) {
                        $tujuan = method_exists($response, 'getTargetUrl')
                            ? $response->getTargetUrl()
                            : get_debug_type($response);

                        throw new \RuntimeException(
                            'Controller tidak menghasilkan halaman hasil. '
                            . 'Respons: ' . $tujuan
                        );
                    }

                    if ($response->name() !== 'Front.hasil') {
                        throw new \RuntimeException(
                            'Controller menghasilkan view yang tidak sesuai: '
                            . $response->name()
                        );
                    }

                    $data = $response->getData();

                    $utama = $data['utama'] ?? null;
                    $performa = $data['alternatif1'] ?? null;
                    $pilihanBudget = $data['alternatif2'] ?? null;

                    if (!is_array($utama) || empty($utama['id_laptop'])) {
                        throw new \RuntimeException(
                            'Rekomendasi utama tidak tersedia.'
                        );
                    }

                    $actualMainId = (int) $utama['id_laptop'];

                    if ($actualMainId !== $expectedMainId) {
                        $errors[] = 'Rekomendasi utama tidak sesuai aturan.';
                    }

                    $expectedFallback =
                        $expectedMain['harga_raw'] < $min
                        || $expectedMain['harga_raw'] > $max;

                    if (
                        (bool) ($utama['fallback_rentang'] ?? false)
                        !== $expectedFallback
                    ) {
                        $errors[] = 'Status fallback rentang tidak sesuai.';
                    }

                    if (
                        $expectedFallback
                        && empty($utama['peringatan'])
                    ) {
                        $errors[] = 'Peringatan fallback tidak tersedia.';
                    }

                    /*
                     * D. Validasi pilihan performa.
                     */
                    $actualPerformanceId = is_array($performa)
                        ? (int) ($performa['id_laptop'] ?? 0)
                        : null;

                    if ($actualPerformanceId === 0) {
                        $actualPerformanceId = null;
                    }

                    if ($actualPerformanceId !== $expectedPerformanceId) {
                        $errors[] = 'Pilihan performa tidak sesuai aturan.';
                    }

                    if ($performa !== null) {
                        if (
                            (int) $performa['id_laptop'] === $actualMainId
                        ) {
                            $errors[] =
                                'Pilihan performa menduplikasi rekomendasi utama.';
                        }

                        $hargaPerforma = $this->price(
                            $performa['harga_raw'] ?? 0
                        );

                        $hargaUtama = $this->price(
                            $utama['harga_raw'] ?? 0
                        );

                        if ($hargaPerforma <= $hargaUtama) {
                            $errors[] =
                                'Harga pilihan performa tidak lebih tinggi.';
                        }

                        if (
                            empty($performa['detail_peningkatan'])
                            || empty($performa['peringatan'])
                        ) {
                            $errors[] =
                                'Detail peningkatan atau peringatan performa kosong.';
                        }
                    }

                    /*
                     * E. Tentukan pilihan anggaran yang diharapkan.
                     */
                    $expectedBudget = $this->expectedBudget(
                        $kandidat,
                        $budgetInfo,
                        $expectedMainId,
                        $expectedPerformanceId
                    );

                    $expectedBudgetId =
                        $expectedBudget['id_laptop'] ?? null;

                    $actualBudgetId = is_array($pilihanBudget)
                        ? (int) ($pilihanBudget['id_laptop'] ?? 0)
                        : null;

                    if ($actualBudgetId === 0) {
                        $actualBudgetId = null;
                    }

                    if ($actualBudgetId !== $expectedBudgetId) {
                        $errors[] =
                            'Pilihan anggaran tidak sesuai aturan.';
                    }

                    if ($pilihanBudget !== null) {
                        $hargaBudget = $this->price(
                            $pilihanBudget['harga_raw'] ?? 0
                        );

                        if (!$this->withinBudget($hargaBudget, $budgetInfo)) {
                            $errors[] =
                                'Pilihan anggaran berada di luar batas harga.';
                        }

                        if (
                            $actualBudgetId === $actualMainId
                            || (
                                $actualPerformanceId !== null
                                && $actualBudgetId === $actualPerformanceId
                            )
                        ) {
                            $errors[] =
                                'Pilihan anggaran menduplikasi kartu lain.';
                        }

                        if (empty($pilihanBudget['peringatan'])) {
                            $errors[] =
                                'Peringatan pilihan anggaran tidak tersedia.';
                        }
                    }
                } catch (Throwable $e) {
                    $errors[] = get_class($e) . ': ' . $e->getMessage();
                } finally {
                    /*
                     * Penting: membatalkan perubahan database setiap skenario.
                     * Termasuk penyimpanan nilai WP dan biodata oleh controller.
                     */
                    if (DB::transactionLevel() > 0) {
                        DB::rollBack();
                    }
                }

                $status = empty($errors) ? 'LULUS' : 'GAGAL';

                if ($status === 'LULUS') {
                    $lulus++;
                } else {
                    $gagal++;
                }

                fputcsv($handle, [
                    $jurusan->id,
                    $jurusan->nama_jurusan,
                    $budgetId,
                    $budgetInfo['label'],
                    $jumlahKandidat,
                    $expectedMainId ?? '',
                    $actualMainId ?? '',
                    $expectedPerformanceId ?? '',
                    $actualPerformanceId ?? '',
                    $expectedBudgetId ?? '',
                    $actualBudgetId ?? '',
                    $status,
                    implode(' | ', $errors),
                ], ';');

                $bar->advance();
            }
        }

        $bar->finish();
        fclose($handle);

        $this->newLine(2);
        $this->info('Pengujian selesai.');
        $this->line('Jumlah jurusan       : ' . $jurusans->count());
        $this->line('Pilihan anggaran     : ' . count($this->anggaran));
        $this->line('Total skenario       : ' . $total);
        $this->line('Lulus                : ' . $lulus);
        $this->line('Gagal                : ' . $gagal);
        $this->line('Laporan              : ' . $path);

        if ($gagal > 0) {
            $this->warn(
                'Periksa kolom detail pada laporan CSV untuk menemukan masalah.'
            );

            return self::FAILURE;
        }

        $this->info('Semua skenario lulus pemeriksaan aturan program.');
        return self::SUCCESS;
    }

    private function buildCandidates(iterable $penilaians): array
    {
        $result = [];

        foreach ($penilaians as $penilaian) {
            $laptop = $penilaian->alternatif;

            if (!$laptop) {
                continue;
            }

            $harga = $this->price($laptop->harga ?? 0);

            if ($harga <= 0) {
                continue;
            }

            foreach ([
                'cpu', 'gpu', 'ram', 'storage',
                'layar', 'berat', 'kamera', 'baterai',
            ] as $field) {
                if (trim((string) ($laptop->{$field} ?? '')) === '') {
                    continue 2;
                }
            }

            $skor = [];

            for ($i = 2; $i <= 9; $i++) {
                $skor[$i] = (float) ($penilaian->{'C' . $i . 'x'} ?? 0);
            }

            $result[] = [
                'id_laptop' => (int) $laptop->id,
                'nama_laptop' => $laptop->namaAlternatif ?? 'Laptop',
                'harga_raw' => $harga,
                'nilai_v' => (float) ($penilaian->nilai_v ?? 0),
                'skor_kriteria' => $skor,
            ];
        }

        return $result;
    }

    private function expectedMain(
        array $candidates,
        float $min,
        float $max
    ): array {
        $inRange = array_values(array_filter(
            $candidates,
            fn ($l) => $l['harga_raw'] >= $min
                && $l['harga_raw'] <= $max
        ));

        if ($inRange) {
            usort($inRange, fn ($a, $b) =>
                $b['nilai_v'] <=> $a['nilai_v']
            );

            return $inRange[0];
        }

        foreach ($candidates as &$candidate) {
            $candidate['distance'] = $candidate['harga_raw'] < $min
                ? $min - $candidate['harga_raw']
                : $candidate['harga_raw'] - $max;
        }
        unset($candidate);

        usort($candidates, function ($a, $b) {
            $distance = $a['distance'] <=> $b['distance'];

            return $distance !== 0
                ? $distance
                : ($b['nilai_v'] <=> $a['nilai_v']);
        });

        return $candidates[0];
    }

    private function expectedPerformance(
        array $candidates,
        array $main,
        array $weights
    ): ?array {
        $maxWeight = max($weights);
        $qualified = [];

        foreach ($candidates as $candidate) {
            if ($candidate['id_laptop'] === $main['id_laptop']) {
                continue;
            }

            if ($candidate['harga_raw'] <= $main['harga_raw']) {
                continue;
            }

            $totalWeight = 0;
            $totalChange = 0;
            $important = false;

            foreach ($weights as $criterion => $weight) {
                if ($weight <= 0) {
                    continue;
                }

                $base = (float) ($main['skor_kriteria'][$criterion] ?? 0);
                $next = (float) ($candidate['skor_kriteria'][$criterion] ?? 0);

                if ($base <= 0 || $next <= 0) {
                    continue;
                }

                $change = $next - $base;
                $totalWeight += $weight;
                $totalChange += $change * $weight;

                if ($change >= 1 && $weight >= $maxWeight * 0.75) {
                    $important = true;
                }
            }

            $score = $totalWeight > 0
                ? $totalChange / $totalWeight
                : 0;

            if ($score > 0 && $important) {
                $candidate['performance_score'] = $score;
                $qualified[] = $candidate;
            }
        }

        usort($qualified, function ($a, $b) {
            $score = $b['performance_score']
                <=> $a['performance_score'];

            if ($score !== 0) {
                return $score;
            }

            $wp = $b['nilai_v'] <=> $a['nilai_v'];

            return $wp !== 0
                ? $wp
                : ($a['harga_raw'] <=> $b['harga_raw']);
        });

        return $qualified[0] ?? null;
    }

    private function expectedBudget(
        array $candidates,
        array $budget,
        int $mainId,
        ?int $performanceId
    ): ?array {
        $eligible = array_values(array_filter(
            $candidates,
            function ($candidate) use (
                $budget,
                $mainId,
                $performanceId
            ) {
                $id = $candidate['id_laptop'];

                return $id !== $mainId
                    && $id !== $performanceId
                    && $this->withinBudget(
                        $candidate['harga_raw'],
                        $budget
                    );
            }
        ));

        usort($eligible, fn ($a, $b) =>
            $b['nilai_v'] <=> $a['nilai_v']
        );

        return $eligible[0] ?? null;
    }

    private function withinBudget(float $price, array $budget): bool
    {
        if ($budget['min'] === null) {
            return $price <= $budget['max'];
        }

        if ($budget['max'] === null) {
            return $price >= $budget['min'];
        }

        return $price >= $budget['min']
            && $price <= $budget['max'];
    }

    private function price($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        return (float) preg_replace('/[^0-9]/', '', (string) $value);
    }
}
