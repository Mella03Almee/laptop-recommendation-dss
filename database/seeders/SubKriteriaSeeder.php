<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubKriteriaSeeder extends Seeder
{
    public function run()
    {
        // 1. Matikan pengaman relasi database dan kosongkan data lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('sub_kriterias')->truncate();

        // 2. Susun data sub-kriteria menggunakan nama kolom array 'harga' dan 'nHarga'
        $data = [
            // 1. Kisaran Harga (C1) - id kriteria = 1
            ['kriteria_id' => 1, 'harga' => '>= Rp 15 Juta', 'nHarga' => 1, 'slug' => Str::random(8)],
            ['kriteria_id' => 1, 'harga' => 'Rp 12 Juta - Rp 14.9 Juta', 'nHarga' => 2, 'slug' => Str::random(8)],
            ['kriteria_id' => 1, 'harga' => 'Rp 9 Juta - Rp 11.9 Juta', 'nHarga' => 3, 'slug' => Str::random(8)],
            ['kriteria_id' => 1, 'harga' => 'Rp 6 Juta - Rp 8.9 Juta', 'nHarga' => 4, 'slug' => Str::random(8)],
            ['kriteria_id' => 1, 'harga' => '< Rp 6 Juta', 'nHarga' => 5, 'slug' => Str::random(8)],

            // 2. CPU / Prosesor (C2) - id kriteria = 2
            ['kriteria_id' => 2, 'harga' => 'Intel Core i9 / Ryzen 9', 'nHarga' => 5, 'slug' => Str::random(8)],
            ['kriteria_id' => 2, 'harga' => 'Intel Core i7 / Ryzen 7', 'nHarga' => 4, 'slug' => Str::random(8)],
            ['kriteria_id' => 2, 'harga' => 'Intel Core i5 / Ryzen 5', 'nHarga' => 3, 'slug' => Str::random(8)],
            ['kriteria_id' => 2, 'harga' => 'Intel Core i3 / Ryzen 3', 'nHarga' => 2, 'slug' => Str::random(8)],
            ['kriteria_id' => 2, 'harga' => 'Celeron / Athlon', 'nHarga' => 1, 'slug' => Str::random(8)],

            // 3. RAM (C3) - id kriteria = 3
            ['kriteria_id' => 3, 'harga' => '32 GB atau lebih', 'nHarga' => 5, 'slug' => Str::random(8)],
            ['kriteria_id' => 3, 'harga' => '16 GB', 'nHarga' => 4, 'slug' => Str::random(8)],
            ['kriteria_id' => 3, 'harga' => '8 GB', 'nHarga' => 3, 'slug' => Str::random(8)],
            ['kriteria_id' => 3, 'harga' => '4 GB', 'nHarga' => 1, 'slug' => Str::random(8)],

            // 4. Penyimpanan SSD (C4) - id kriteria = 4
            ['kriteria_id' => 4, 'harga' => '1 TB SSD atau lebih', 'nHarga' => 5, 'slug' => Str::random(8)],
            ['kriteria_id' => 4, 'harga' => '512 GB SSD', 'nHarga' => 4, 'slug' => Str::random(8)],
            ['kriteria_id' => 4, 'harga' => '256 GB SSD', 'nHarga' => 3, 'slug' => Str::random(8)],
            ['kriteria_id' => 4, 'harga' => '128 GB SSD / HDD', 'nHarga' => 1, 'slug' => Str::random(8)],

            // 5. GPU / Grafis (C5) - id kriteria = 5
            ['kriteria_id' => 5, 'harga' => 'NVIDIA RTX High-End (RTX 4070/4080)', 'nHarga' => 5, 'slug' => Str::random(8)],
            ['kriteria_id' => 5, 'harga' => 'NVIDIA RTX Mid-End (RTX 3050/4050)', 'nHarga' => 4, 'slug' => Str::random(8)],
            ['kriteria_id' => 5, 'harga' => 'NVIDIA GTX / AMD Radeon Dedicated', 'nHarga' => 3, 'slug' => Str::random(8)],
            ['kriteria_id' => 5, 'harga' => 'Intel Iris Xe / AMD Radeon Integrated', 'nHarga' => 2, 'slug' => Str::random(8)],
            ['kriteria_id' => 5, 'harga' => 'Intel HD Graphics', 'nHarga' => 1, 'slug' => Str::random(8)],

            // 6. Ukuran Layar (C6) - id kriteria = 6
            ['kriteria_id' => 6, 'harga' => '11 - 13 Inch (Sangat Ringkas)', 'nHarga' => 3, 'slug' => Str::random(8)],
            ['kriteria_id' => 6, 'harga' => '15.6 - 17 Inch (Besar/Gaming)', 'nHarga' => 4, 'slug' => Str::random(8)],
            ['kriteria_id' => 6, 'harga' => '14 Inch (Standar/Ideal)', 'nHarga' => 5, 'slug' => Str::random(8)],

            // 7. Daya Tahan Baterai (C7) - id kriteria = 7
            ['kriteria_id' => 7, 'harga' => '> 8 Jam', 'nHarga' => 5, 'slug' => Str::random(8)],
            ['kriteria_id' => 7, 'harga' => '5 - 8 Jam', 'nHarga' => 4, 'slug' => Str::random(8)],
            ['kriteria_id' => 7, 'harga' => '3 - 5 Jam', 'nHarga' => 3, 'slug' => Str::random(8)],
            ['kriteria_id' => 7, 'harga' => '< 3 Jam', 'nHarga' => 1, 'slug' => Str::random(8)],

            // 8. Berat Laptop (C8) - id kriteria = 8
            ['kriteria_id' => 8, 'harga' => '< 1.3 kg (Sangat Ringan)', 'nHarga' => 5, 'slug' => Str::random(8)],
            ['kriteria_id' => 8, 'harga' => '1.3 kg - 1.8 kg (Ringan)', 'nHarga' => 4, 'slug' => Str::random(8)],
            ['kriteria_id' => 8, 'harga' => '1.81 kg - 2.3 kg (Sedang)', 'nHarga' => 3, 'slug' => Str::random(8)],
            ['kriteria_id' => 8, 'harga' => '> 2.3 kg (Berat)', 'nHarga' => 1, 'slug' => Str::random(8)],

            // 9. Kualitas Kamera (C9) - id kriteria = 9
            ['kriteria_id' => 9, 'harga' => '1080p FHD Camera dengan Privacy Shutter', 'nHarga' => 5, 'slug' => Str::random(8)],
            ['kriteria_id' => 9, 'harga' => '720p HD Camera Standar', 'nHarga' => 3, 'slug' => Str::random(8)],
            ['kriteria_id' => 9, 'harga' => 'VGA Camera', 'nHarga' => 1, 'slug' => Str::random(8)],

            // 10. Merek Laptop (C10) - id kriteria = 10
            ['kriteria_id' => 10, 'harga' => 'ASUS / Lenovo / HP (Reputasi & Service Center Sangat Banyak)', 'nHarga' => 5, 'slug' => Str::random(8)],
            ['kriteria_id' => 10, 'harga' => 'Acer / MSI (Reputasi Bagus & Fokus Segmen Jelas)', 'nHarga' => 4, 'slug' => Str::random(8)],
            ['kriteria_id' => 10, 'harga' => 'Apple MacBook (Eksklusif & Premium)', 'nHarga' => 3, 'slug' => Str::random(8)],
            ['kriteria_id' => 10, 'harga' => 'Xiaomi / Axioo / Infinix (Merek Berkembang / Lokal)', 'nHarga' => 2, 'slug' => Str::random(8)],
            ['kriteria_id' => 10, 'harga' => 'Merek Lainnya / Tanpa Merek Resmi', 'nHarga' => 1, 'slug' => Str::random(8)],    
        ];

        // 3. Suntikkan data langsung ke kolom 'harga' dan 'nHarga' sesuai spesifikasi tabel database kamu
        foreach ($data as $item) {
            DB::table('sub_kriterias')->insert([
                'user_id'     => 1, // Menyertakan user_id yang ada di struktur tabel kamu
                'kriteria_id' => $item['kriteria_id'],
                'harga'       => $item['harga'],  // Di database ini nama kolom teksnya
                'nHarga'      => $item['nHarga'], // Di database ini nama kolom skor nilainya
                'slug'        => $item['slug'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}