<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Universitas;
use App\Models\Jurusan;
use App\Services\WeightedProductService;
use Illuminate\Support\Facades\DB;

class RekomendasiController extends Controller
{
    protected $weightedProductService;

    public function __construct(WeightedProductService $weightedProductService)
    {
        $this->weightedProductService = $weightedProductService;
    }

    public function index()
    {
        $universitas = Universitas::all();
        $jurusan = Jurusan::with('fakultas.universitas')->get();

        return view('Front.welcome', compact('universitas', 'jurusan'));
    }

    public function hitung(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string|max:255',
            'universitas_id' => 'required|integer|exists:universitas,id',
            'fakultas_id'    => 'required|integer|exists:fakultas,id',
            'jurusan_id'     => 'required|integer|exists:jurusan,id',
            'c1_harga'       => 'required|integer|in:1,2,3,4,5',
        ]);

        $jurusan = Jurusan::with([
            'fakultas.universitas',
            'kategoriBobot',
        ])->findOrFail($request->jurusan_id);

        $kategoriBobot = $jurusan->kategoriBobot;

        if (!$kategoriBobot) {
            return back()->withInput()->with(
                'error',
                'Bobot kriteria untuk jurusan tersebut belum tersedia.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 1. Anggaran pengguna
        |--------------------------------------------------------------------------
        */

        $budgetPilihan = (int) $request->c1_harga;

        $budgetInfo = match ($budgetPilihan) {
            1 => [
                'label' => '< Rp 6.000.000',
                'min'   => null,
                'max'   => 5999999,
            ],
            2 => [
                'label' => 'Rp 6.000.000 – Rp 8.999.999',
                'min'   => 6000000,
                'max'   => 8999999,
            ],
            3 => [
                'label' => 'Rp 9.000.000 – Rp 11.999.999',
                'min'   => 9000000,
                'max'   => 11999999,
            ],
            4 => [
                'label' => 'Rp 12.000.000 – Rp 14.999.999',
                'min'   => 12000000,
                'max'   => 14999999,
            ],
            5 => [
                'label' => '≥ Rp 15.000.000',
                'min'   => 15000000,
                'max'   => null,
            ],
        };

        /*
        |--------------------------------------------------------------------------
        | 2. Rentang harga jurusan
        |--------------------------------------------------------------------------
        */

        $minSaran = (float) $jurusan->harga_min_saran;
        $maxSaran = (float) $jurusan->harga_max_saran;

        if ($minSaran <= 0 || $maxSaran < $minSaran) {
            return back()->withInput()->with(
                'error',
                'Rentang harga jurusan belum valid. Periksa profil jurusan.'
            );
        }

        $rentangSaran = 'Rp '
            . number_format($minSaran, 0, ',', '.')
            . ' – Rp '
            . number_format($maxSaran, 0, ',', '.');

        /*
        |--------------------------------------------------------------------------
        | 3. Status anggaran
        |--------------------------------------------------------------------------
        */

        if (
            $budgetInfo['max'] !== null &&
            $budgetInfo['max'] < $minSaran
        ) {
            $statusAnggaran = 'Di bawah rentang rekomendasi';
            $pesanAnggaran =
                'Anggaran berada di bawah rentang yang disarankan untuk kebutuhan jurusan ini.';
        } elseif (
            $budgetInfo['min'] !== null &&
            $budgetInfo['min'] > $maxSaran
        ) {
            $statusAnggaran = 'Di atas rentang rekomendasi';
            $pesanAnggaran =
                'Anggaran berada di atas rentang yang umumnya diperlukan untuk kebutuhan jurusan ini.';
        } else {
            $statusAnggaran = 'Sesuai rentang rekomendasi';
            $pesanAnggaran =
                'Anggaran berada dalam atau beririsan dengan rentang harga yang disarankan.';
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Perhitungan WP
        |--------------------------------------------------------------------------
        */

        $hasilWP = $this->weightedProductService->calculate($jurusan->id);
        $penilaians = collect($hasilWP['penilaians'] ?? []);

        /*
        |--------------------------------------------------------------------------
        | 5. Susun kandidat laptop
        |--------------------------------------------------------------------------
        */

        $semuaLaptop = [];

        foreach ($penilaians as $penilaian) {
            $laptop = $penilaian->alternatif;

            if (!$laptop) {
                continue;
            }

            $hargaRaw = $this->parseHarga($laptop->harga ?? 0);

            if ($hargaRaw <= 0) {
                continue;
            }

            $kolomWajib = [
                'cpu',
                'gpu',
                'ram',
                'storage',
                'layar',
                'berat',
                'kamera',
                'baterai',
            ];

            $spesifikasiLengkap = true;

            foreach ($kolomWajib as $kolom) {
                if (trim((string) ($laptop->{$kolom} ?? '')) === '') {
                    $spesifikasiLengkap = false;
                    break;
                }
            }

            if (!$spesifikasiLengkap) {
                continue;
            }

            $gambarPath = $laptop->gambar ?? null;

            if (
                $gambarPath &&
                !str_starts_with($gambarPath, 'http://') &&
                !str_starts_with($gambarPath, 'https://') &&
                !str_starts_with($gambarPath, 'storage/') &&
                !str_starts_with($gambarPath, 'img/')
            ) {
                $gambarPath = 'storage/' . ltrim($gambarPath, '/');
            }

            $skorKriteria = [];

            foreach (range(2, 9) as $nomor) {
                $kolomSkor = 'C' . $nomor . 'x';
                $skorKriteria[$nomor] =
                    (float) ($penilaian->{$kolomSkor} ?? 0);
            }

            $semuaLaptop[] = [
                'id_laptop' => $laptop->id,
                'nama_laptop' => $laptop->namaAlternatif ?? 'Laptop',
                'gambar' => $gambarPath,
                'harga' => $laptop->harga ?? '-',
                'harga_raw' => $hargaRaw,
                'cpu' => $laptop->cpu ?? '-',
                'gpu' => $laptop->gpu ?? '-',
                'ram' => $laptop->ram ?? '-',
                'storage' => $laptop->storage ?? '-',
                'layar' => $laptop->layar ?? '-',
                'berat' => $laptop->berat ?? '-',
                'kamera' => $laptop->kamera ?? '-',
                'baterai' => $laptop->baterai ?? '-',
                'nilai_s' => (float) ($penilaian->nilai_s ?? 0),
                'nilai_v' => (float) ($penilaian->nilai_v ?? 0),
                'skor_kriteria' => $skorKriteria,
                'sesuai_budget' => $this->masukAnggaran(
                    $hargaRaw,
                    $budgetPilihan,
                    $budgetInfo
                ),
                'sesuai_rentang_jurusan' =>
                    $hargaRaw >= $minSaran &&
                    $hargaRaw <= $maxSaran,
            ];
        }

        $semuaLaptop = collect($semuaLaptop)
            ->sortByDesc('nilai_v')
            ->values();

        if ($semuaLaptop->isEmpty()) {
            return back()->withInput()->with(
                'error',
                'Belum ada data laptop dengan penilaian dan spesifikasi lengkap.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Rekomendasi utama
        |--------------------------------------------------------------------------
        */

        $fallbackRentang = false;
        $utama = null;
        $dasarPemilihanUtama = '';

        if ($budgetPilihan === 1) {
            // Skenario 1: User pilih < 6 Juta
            if ($minSaran > 6000000) {
                // Jika jurusan butuh min > 6 juta, ambil rentang terdekat di batas bawah jurusan (min s/d min + 3 juta)
                $targetMin = $minSaran;
                $targetMax = $minSaran + 3000000;
                $dasarPemilihanUtama =
                    'Anggaran pengguna di bawah 6 juta. Rekomendasi disesuaikan dengan laptop yang paling mendekati batas minimal kebutuhan jurusan.';
            } else {
                $targetMin = $minSaran;
                $targetMax = $maxSaran;
                $dasarPemilihanUtama =
                    'Anggaran pengguna di bawah 6 juta, rekomendasi disesuaikan dengan rentang harga yang disarankan jurusan.';
            }

        } elseif ($budgetPilihan === 5) {
            // Skenario 2: User pilih >= 15 Juta -> Mendahulukan permintaan user
            $batasPremium = 15000000;
            $bufferMax = 10000000;

            $targetMin = $batasPremium;
            $targetMax = max($maxSaran, $batasPremium + $bufferMax);
            $dasarPemilihanUtama =
                'Mendahulukan anggaran pengguna (>= Rp15.000.000) dengan spesifikasi terbaik.';

        } else {
            // Skenario 3: Anggaran User Kategori 2, 3, dan 4
            $userMin = (float) $budgetInfo['min'];
            $userMax = (float) $budgetInfo['max'];

            if ($userMax < $minSaran) {
                // Skenario 3A: Anggaran User LEBIH RENDAH dari syarat min Jurusan
                $targetMin = $minSaran;
                $targetMax = $maxSaran;
                $dasarPemilihanUtama =
                    'Anggaran pengguna di bawah batas minimal jurusan. Rekomendasi dialihkan ke rentang standar jurusan.';

            } elseif ($userMin >= $maxSaran) {
                // Skenario 3B: Anggaran User SAMPAI / MELEBIHI batas max Jurusan
                $targetMin = $userMin;
                $targetMax = $userMax;
                $dasarPemilihanUtama =
                    'Anggaran pengguna melebihi batas kebutuhan jurusan. Rekomendasi disesuaikan dengan anggaran pilihan pengguna.';

            } else {
                // Skenario 3C: Beririsan Murni
                $targetMin = max($minSaran, $userMin);
                $targetMax = min($maxSaran, $userMax);
                $dasarPemilihanUtama =
                    'Dipilih dari irisan rentang harga jurusan dan anggaran pengguna dengan nilai WP tertinggi.';
            }
        }

        // Cari kandidat laptop tepat pada rentang target
        $kandidatUtama = $semuaLaptop
            ->filter(function ($laptop) use ($targetMin, $targetMax) {
                return $laptop['harga_raw'] >= $targetMin
                    && ($targetMax === null || $laptop['harga_raw'] <= $targetMax);
            })
            ->values();

        if ($kandidatUtama->isNotEmpty()) {
            $utama = $kandidatUtama
                ->sortByDesc('nilai_v')
                ->first();
        } else {
            // Jika tidak ada laptop tepat di rentang target, cari harga paling mendekati
            $utama = $semuaLaptop
                ->map(function ($laptop) use ($targetMin, $targetMax) {
                    if ($laptop['harga_raw'] < $targetMin) {
                        $laptop['jarak_sasaran'] = $targetMin - $laptop['harga_raw'];
                    } elseif ($targetMax !== null && $laptop['harga_raw'] > $targetMax) {
                        $laptop['jarak_sasaran'] = $laptop['harga_raw'] - $targetMax;
                    } else {
                        $laptop['jarak_sasaran'] = 0;
                    }

                    return $laptop;
                })
                ->sort(function ($a, $b) {
                    $bandingJarak = $a['jarak_sasaran'] <=> $b['jarak_sasaran'];

                    if ($bandingJarak !== 0) {
                        return $bandingJarak;
                    }

                    return $b['nilai_v'] <=> $a['nilai_v'];
                })
                ->first();

            $fallbackRentang = true;
            $dasarPemilihanUtama .=
                ' (Tidak ada unit laptop tepat di rentang target, dipilih unit dengan jarak harga terdekat dan WP tertinggi).';
        }

        if (!$utama) {
            return back()->withInput()->with(
                'error',
                'Sistem belum dapat menentukan rekomendasi utama.'
            );
        }

        $utama['fallback_rentang'] = $fallbackRentang;
        $utama['peringatan'] = $fallbackRentang
            ? $dasarPemilihanUtama . ' Periksa kembali ketersediaan unit.'
            : null;

        /*
        |--------------------------------------------------------------------------
        | 7. Alternatif performa
        |--------------------------------------------------------------------------
        |
        | Harga alternatif harus Rp5–10 juta lebih mahal daripada utama.
        | Kandidat harus memberikan peningkatan spesifikasi yang relevan.
        |--------------------------------------------------------------------------
        */

        $bobotKriteria = [
            2 => (float) ($kategoriBobot->c2_cpu ?? 5),
            3 => (float) ($kategoriBobot->c3_ram ?? 5),
            4 => (float) ($kategoriBobot->c4_storage ?? 4),
            5 => (float) ($kategoriBobot->c5_gpu ?? 3),
            6 => (float) ($kategoriBobot->c6_ukuran_layar ?? 3),
            7 => (float) ($kategoriBobot->c7_baterai ?? 4),
            8 => (float) ($kategoriBobot->c8_berat ?? 3),
            9 => (float) ($kategoriBobot->c9_kamera ?? 1),
        ];

        $batasMinPerforma = $utama['harga_raw'] + 5000000;
        $batasMaxPerforma = $utama['harga_raw'] + 10000000;

        $alternatif1 = $semuaLaptop
            ->filter(function ($laptop) use (
                $utama,
                $batasMinPerforma,
                $batasMaxPerforma
            ) {
                return $laptop['id_laptop'] !== $utama['id_laptop']
                    && $laptop['harga_raw'] >= $batasMinPerforma
                    && $laptop['harga_raw'] <= $batasMaxPerforma;
            })
            ->map(function ($laptop) use ($utama, $bobotKriteria) {
                $hasilPerforma = $this->hitungPeningkatanPerforma(
                    $utama,
                    $laptop,
                    $bobotKriteria
                );

                $laptop['skor_peningkatan'] = $hasilPerforma['skor'];
                $laptop['peningkatan_penting'] =
                    $hasilPerforma['peningkatan_penting'];
                $laptop['detail_peningkatan'] =
                    $hasilPerforma['detail'];

                return $laptop;
            })
            ->filter(fn ($l) =>
                $l['skor_peningkatan'] > 0 &&
                $l['peningkatan_penting']
            )
            ->sort(function ($a, $b) {
                if ($a['skor_peningkatan'] != $b['skor_peningkatan']) {
                    return $b['skor_peningkatan']
                        <=> $a['skor_peningkatan'];
                }

                if ($a['nilai_v'] != $b['nilai_v']) {
                    return $b['nilai_v'] <=> $a['nilai_v'];
                }

                return $a['harga_raw'] <=> $b['harga_raw'];
            })
            ->first();

        if ($alternatif1) {
            $alternatif1['peringatan'] =
                'Harga Rp5–10 juta lebih tinggi daripada rekomendasi utama, dengan peningkatan spesifikasi yang relevan. Pastikan sesuai anggaran dan kebutuhan Anda.';
        }

        /*
        |--------------------------------------------------------------------------
        | 8. Alternatif lebih terjangkau
        |--------------------------------------------------------------------------
        */

        $idTerpakai = [$utama['id_laptop']];

        if ($alternatif1) {
            $idTerpakai[] = $alternatif1['id_laptop'];
        }

        $alternatif2 = $semuaLaptop
            ->filter(function ($laptop) use (
                $budgetPilihan,
                $budgetInfo,
                $idTerpakai
            ) {
                return !in_array(
                    $laptop['id_laptop'],
                    $idTerpakai,
                    true
                ) && $this->masukAnggaran(
                    $laptop['harga_raw'],
                    $budgetPilihan,
                    $budgetInfo
                );
            })
            ->sortByDesc('nilai_v')
            ->first();

        if ($alternatif2) {
            if ($alternatif2['harga_raw'] < $minSaran) {
                $alternatif2['peringatan'] =
                    'Laptop ini masuk kategori anggaran Anda, tetapi harganya di bawah rentang yang disarankan untuk jurusan ini.';
            } elseif ($alternatif2['harga_raw'] > $maxSaran) {
                $alternatif2['peringatan'] =
                    'Laptop ini masuk kategori anggaran Anda, tetapi harganya di atas rentang yang disarankan untuk jurusan ini.';
            } else {
                $alternatif2['peringatan'] =
                    'Laptop ini memiliki nilai WP tertinggi di antara kandidat lain dalam kategori anggaran Anda.';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 9. Ranking
        |--------------------------------------------------------------------------
        */

        $hasilRanking = $semuaLaptop->take(5)->values()->all();

        /*
        |--------------------------------------------------------------------------
        | 10. Simpan biodata
        |--------------------------------------------------------------------------
        */

        $namaUniversitas =
            $jurusan->fakultas?->universitas?->nama_universitas
            ?? 'Tidak diketahui';

        $namaFakultas = $jurusan->fakultas?->nama_fakultas ?? '-';

        $namaJurusanLengkap = $jurusan->nama_jurusan
            . ' (' . ($jurusan->jenjang ?? 'S1') . ')';

        DB::table('biodatas')->insert([
            'nama'           => $request->nama,
            'kampus'         => $namaUniversitas,
            'fakultas'       => $namaFakultas,
            'jurusan'        => $namaJurusanLengkap,
            'laptop_terbaik' => $utama['nama_laptop'],
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | 11. Profil hasil
        |--------------------------------------------------------------------------
        */

        $profil = [
            'nama'              => $request->nama,
            'kampus'            => $namaUniversitas,
            'fakultas'          => $namaFakultas,
            'jurusan'           => $jurusan->nama_jurusan,
            'kategori_kluster'  => $kategoriBobot->nama_kategori ?? 'Umum',
            'harga_min_saran'   => $minSaran,
            'harga_max_saran'   => $maxSaran,
            'rentang_saran'     => $rentangSaran,
            'tingkat_kebutuhan' => $jurusan->tingkat_kebutuhan ?? '-',
            'software_utama'    => $jurusan->software_utama ?? '-',
            'profil_perangkat'  => $jurusan->profil_perangkat ?? '-',
            'budget_label'      => $budgetInfo['label'],
            'status_anggaran'   => $statusAnggaran,
            'pesan_anggaran'    => $pesanAnggaran,
        ];

        return view('Front.hasil', [
            'hasilRanking'  => $hasilRanking,
            'utama'         => $utama,
            'alternatif1'   => $alternatif1,
            'alternatif2'   => $alternatif2,
            'profil'        => $profil,
            'budgetInfo'    => $budgetInfo,
            'budgetPilihan' => $budgetPilihan,
        ]);
    }

    /**
     * Mengubah harga menjadi angka.
     */
    private function parseHarga($harga): float
    {
        if (is_numeric($harga)) {
            return (float) $harga;
        }

        return (float) preg_replace('/[^0-9]/', '', (string) $harga);
    }

    /**
     * Memeriksa apakah harga masuk kategori anggaran.
     */
    private function masukAnggaran(
        float $harga,
        int $budgetPilihan,
        array $budgetInfo
    ): bool {
        if ($budgetPilihan === 1) {
            return $harga < 6000000;
        }

        if ($budgetPilihan === 5) {
            return $harga >= 15000000;
        }

        return $harga >= (float) $budgetInfo['min']
            && $harga <= (float) $budgetInfo['max'];
    }

    /**
     * Menghitung peningkatan spesifikasi berbobot.
     */
    private function hitungPeningkatanPerforma(
        array $utama,
        array $kandidat,
        array $bobotKriteria
    ): array {
        $totalBobot = 0;
        $totalPerubahanBerbobot = 0;
        $bobotMaksimum = max($bobotKriteria);
        $detail = [];
        $peningkatanPenting = false;

        foreach ($bobotKriteria as $nomor => $bobot) {
            if ($bobot <= 0) {
                continue;
            }

            $skorUtama =
                (float) ($utama['skor_kriteria'][$nomor] ?? 0);

            $skorKandidat =
                (float) ($kandidat['skor_kriteria'][$nomor] ?? 0);

            if ($skorUtama <= 0 || $skorKandidat <= 0) {
                continue;
            }

            $selisih = $skorKandidat - $skorUtama;

            $totalBobot += $bobot;
            $totalPerubahanBerbobot += $selisih * $bobot;

            if ($selisih > 0) {
                $detail[$nomor] = [
                    'skor_utama'    => $skorUtama,
                    'skor_kandidat' => $skorKandidat,
                    'selisih'       => $selisih,
                    'bobot'         => $bobot,
                ];
            }

            if (
                $selisih >= 1 &&
                $bobot >= ($bobotMaksimum * 0.75)
            ) {
                $peningkatanPenting = true;
            }
        }

        $skor = $totalBobot > 0
            ? $totalPerubahanBerbobot / $totalBobot
            : 0;

        return [
            'skor'                => $skor,
            'peningkatan_penting' => $peningkatanPenting,
            'detail'              => $detail,
        ];
    }

    public function simpanSaran(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'saran' => 'required',
        ]);

        DB::table('sarans')->insert([
            'email'      => $request->email,
            'isi_saran'  => $request->saran,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Saran berhasil dikirim!');
    }
}