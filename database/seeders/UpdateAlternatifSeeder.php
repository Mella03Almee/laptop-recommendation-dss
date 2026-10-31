<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateAlternatifSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('seeders/alternatif.csv');

        if (!file_exists($filePath)) {
            $this->command->error("File CSV tidak ditemukan di: {$filePath}");
            return;
        }

        $file = fopen($filePath, 'r');

        // Deteksi pemisah (delimiter) otomatis: koma (,) atau titik koma (;)
        $firstLine = fgets($file);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
        rewind($file); // Kembalikan kursor pembacaan ke awal file

        // Baca header dan hapus karakter BOM/spasi tersembunyi dari Excel
        $headers = fgetcsv($file, 0, $delimiter);
        if (!$headers) {
            $this->command->error("Header CSV tidak dapat dibaca.");
            fclose($file);
            return;
        }

        // Hapus UTF-8 BOM jika ada
        $headers[0] = preg_replace('/\x{EF}\x{BB}\x{BF}/', '', $headers[0]);
        $headers = array_map('trim', $headers);

        $headerCount = count($headers);
        $i = 1;

        while (($data = fgetcsv($file, 0, $delimiter)) !== FALSE) {
            if (empty($data) || (count($data) === 1 && is_null($data[0]))) {
                continue;
            }

            $dataCount = count($data);

            if ($dataCount < $headerCount) {
                $data = array_pad($data, $headerCount, null);
            } elseif ($dataCount > $headerCount) {
                $data = array_slice($data, 0, $headerCount);
            }

            $row = array_combine($headers, $data);

            // Ambil Merek dan Model (dukungan huruf besar/kecil)
            $merek = $row['Merek'] ?? $row['merek'] ?? '';
            $model = $row['Model'] ?? $row['model'] ?? '';
            $namaLengkap = trim($merek . ' ' . $model);

            // Jika namaLengkap kosong, coba ambil kolom nama/namaAlternatif jika ada
            if (empty($namaLengkap)) {
                $namaLengkap = $row['namaAlternatif'] ?? $row['Nama Alternatif'] ?? $row['nama'] ?? '';
            }

            if (empty($namaLengkap)) {
                continue;
            }

            $kode = 'A' . $i;
            $slug = Str::slug($namaLengkap) . '-' . $kode;

            DB::table('alternatifs')->updateOrInsert(
                ['kodeAlternatif' => $kode],
                [
                    'user_id'        => 1,
                    'slug'           => $slug,
                    'namaAlternatif' => $namaLengkap,
                    'gambar'         => $row['gambar'] ?? $row['Gambar'] ?? null,
                    'harga'          => $row['Kisaran Harga (IDR)'] ?? $row['Harga'] ?? $row['harga'] ?? null,
                    'cpu'            => $row['CPU / Prosesor'] ?? $row['CPU'] ?? $row['cpu'] ?? null,
                    'gpu'            => $row['GPU (Grafis)'] ?? $row['GPU'] ?? $row['gpu'] ?? null,
                    'ram'            => $row['RAM'] ?? $row['ram'] ?? null,
                    'storage'        => $row['Penyimpanan'] ?? $row['storage'] ?? null,
                    'layar'          => $row['Ukuran Layar'] ?? $row['layar'] ?? null,
                    'berat'          => $row['Berat'] ?? $row['berat'] ?? null,
                    'kamera'         => $row['Kualitas Kamera'] ?? $row['kamera'] ?? null,
                    'baterai'        => $row['Daya Tahan Baterai'] ?? $row['baterai'] ?? null,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]
            );

            $i++;
        }

        fclose($file);
        $this->command->info("Seeding berhasil! Total data diproses: " . ($i - 1));
    }
}