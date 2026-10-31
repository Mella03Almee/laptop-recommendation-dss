<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaptopSeeder extends Seeder
{
    public function run()
    {
        // 1. Matikan pengaman relasi agar database mengizinkan pengisian data tanpa cek tabel users
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // 2. Kosongkan data lama agar tidak duplikat
        DB::table('penilaians')->truncate();
        DB::table('alternatifs')->truncate();

        // Seluruh 74 data laptop asli hasil konversi dari Excel Mella (Sudah dibersihkan dari typo)
        $laptops = [
            ['nama' => 'ASUS Vivobook Go 14 E1404FA', 'c1'=>5, 'c2'=>2, 'c3'=>3, 'c4'=>3, 'c5'=>2, 'c6'=>3, 'c7'=>4, 'c8'=>4, 'c9'=>3, 'c10'=>5],
            ['nama' => 'Lenovo IdeaPad Slim 1 14AMN7', 'c1'=>5, 'c2'=>2, 'c3'=>3, 'c4'=>2, 'c5'=>2, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>2, 'c10'=>4],
            ['nama' => 'Axioo MyBook Hype 5 AMD', 'c1'=>5, 'c2'=>3, 'c3'=>3, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>2, 'c10'=>3],
            ['nama' => 'HP Laptop 14s-fq1036AU', 'c1'=>5, 'c2'=>2, 'c3'=>3, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>2, 'c10'=>4],
            ['nama' => 'Acer Aspire 3 Slim A314-23M', 'c1'=>5, 'c2'=>2, 'c3'=>3, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>2, 'c10'=>4],
            ['nama' => 'Advan Workplus AMD Ryzen 5', 'c1'=>4, 'c2'=>4, 'c3'=>4, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>4, 'c8'=>4, 'c9'=>2, 'c10'=>3],
            ['nama' => 'MSI Modern 14 C11M', 'c1'=>5, 'c2'=>2, 'c3'=>3, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>2, 'c10'=>4],
            ['nama' => 'Infinix InBook X2 Gen 11', 'c1'=>5, 'c2'=>2, 'c3'=>3, 'c4'=>3, 'c5'=>2, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>2, 'c10'=>3],
            ['nama' => 'ASUS Vivobook 14 A1404ZA', 'c1'=>4, 'c2'=>3, 'c3'=>3, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>3, 'c10'=>5],
            ['nama' => 'Lenovo IdeaPad Slim 3 14IAN8', 'c1'=>5, 'c2'=>2, 'c3'=>3, 'c4'=>2, 'c5'=>2, 'c6'=>3, 'c7'=>4, 'c8'=>4, 'c9'=>3, 'c10'=>4],
            ['nama' => 'HP Laptop 14s-dq5115TU', 'c1'=>4, 'c2'=>3, 'c3'=>3, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>2, 'c10'=>4],
            ['nama' => 'Acer Aspire 5 Slim A514-55', 'c1'=>4, 'c2'=>3, 'c3'=>3, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>3, 'c10'=>4],
            ['nama' => 'Axioo Pongo 725 Gaming', 'c1'=>3, 'c2'=>4, 'c3'=>4, 'c4'=>4, 'c5'=>4, 'c6'=>4, 'c7'=>2, 'c8'=>2, 'c9'=>2, 'c10'=>3],
            ['nama' => 'MSI Cyborg 15 A12UCX', 'c1'=>3, 'c2'=>4, 'c3'=>3, 'c4'=>4, 'c5'=>4, 'c6'=>5, 'c7'=>2, 'c8'=>2, 'c9'=>2, 'c10'=>4],
            ['nama' => 'ASUS TUF Gaming F15 FX506HF', 'c1'=>3, 'c2'=>4, 'c3'=>3, 'c4'=>4, 'c5'=>4, 'c6'=>5, 'c7'=>2, 'c8'=>1, 'c9'=>2, 'c10'=>5],
            ['nama' => 'Gigabyte G5 GE-51WN263SH', 'c1'=>3, 'c2'=>4, 'c3'=>3, 'c4'=>4, 'c5'=>4, 'c6'=>5, 'c7'=>2, 'c8'=>1, 'c9'=>2, 'c10'=>3],
            ['nama' => 'Lenovo IdeaPad Gaming 3 15ACH6', 'c1'=>3, 'c2'=>4, 'c3'=>3, 'c4'=>4, 'c5'=>4, 'c6'=>5, 'c7'=>2, 'c8'=>1, 'c9'=>2, 'c10'=>4],
            ['nama' => 'HP Victus Gaming 15-fb0009AX', 'c1'=>3, 'c2'=>4, 'c3'=>3, 'c4'=>4, 'c5'=>4, 'c6'=>5, 'c7'=>3, 'c8'=>1, 'c9'=>2, 'c10'=>4],
            ['nama' => 'Acer Nitro V 15 ANV15-51', 'c1'=>3, 'c2'=>4, 'c3'=>3, 'c4'=>4, 'c5'=>4, 'c6'=>5, 'c7'=>3, 'c8'=>2, 'c9'=>3, 'c10'=>4],
            ['nama' => 'ASUS Zenbook 14 OLED UX3402ZA', 'c1'=>2, 'c2'=>4, 'c3'=>4, 'c4'=>4, 'c5'=>2, 'c6'=>2, 'c7'=>5, 'c8'=>5, 'c9'=>3, 'c10'=>5],
            ['nama' => 'Lenovo Yoga Slim 7 Carbon 14ACCN6', 'c1'=>2, 'c2'=>4, 'c3'=>4, 'c4'=>5, 'c5'=>3, 'c6'=>3, 'c7'=>4, 'c8'=>5, 'c9'=>3, 'c10'=>4],
            ['nama' => 'HP Pavilion Aero 13-be2005AU', 'c1'=>3, 'c2'=>4, 'c3'=>4, 'c4'=>4, 'c5'=>2, 'c6'=>2, 'c7'=>4, 'c8'=>5, 'c9'=>2, 'c10'=>4],
            ['nama' => 'Acer Swift Go 14 OLED SFG14-71', 'c1'=>3, 'c2'=>4, 'c3'=>4, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>4, 'c8'=>5, 'c9'=>4, 'c10'=>4],
            ['nama' => 'Apple MacBook Air M1 (2020)', 'c1'=>3, 'c2'=>4, 'c3'=>3, 'c4'=>3, 'c5'=>3, 'c6'=>2, 'c7'=>5, 'c8'=>5, 'c9'=>3, 'c10'=>5],
            ['nama' => 'Apple MacBook Air M2 (2022)', 'c1'=>2, 'c2'=>4, 'c3'=>3, 'c4'=>3, 'c5'=>3, 'c6'=>2, 'c7'=>5, 'c8'=>5, 'c9'=>4, 'c10'=>5],
            ['nama' => 'MSI Stealth 15 A13VF', 'c1'=>1, 'c2'=>5, 'c3'=>4, 'c4'=>5, 'c5'=>5, 'c6'=>5, 'c7'=>2, 'c8'=>2, 'c9'=>2, 'c10'=>4],
            ['nama' => 'ASUS ROG Zephyrus G14 GA402RJ', 'c1'=>1, 'c2'=>5, 'c3'=>4, 'c4'=>5, 'c5'=>5, 'c6'=>3, 'c7'=>4, 'c8'=>4, 'c9'=>3, 'c10'=>5],
            ['nama' => 'Lenovo Legion 5 Pro 16IAH7H', 'c1'=>1, 'c2'=>5, 'c3'=>5, 'c4'=>5, 'c5'=>5, 'c6'=>5, 'c7'=>3, 'c8'=>1, 'c9'=>3, 'c10'=>4],
            ['nama' => 'HP Omen Gaming 16-wf0030TX', 'c1'=>1, 'c2'=>5, 'c3'=>5, 'c4'=>5, 'c5'=>5, 'c6'=>5, 'c7'=>3, 'c8'=>1, 'c9'=>3, 'c10'=>4],
            ['nama' => 'Acer Predator Helios Neo 16 PHN16-71', 'c1'=>1, 'c2'=>5, 'c3'=>4, 'c4'=>5, 'c5'=>5, 'c6'=>5, 'c7'=>3, 'c8'=>1, 'c9'=>3, 'c10'=>4],
            ['nama' => 'Axioo MyBook Hype 3 Intel', 'c1'=>5, 'c2'=>2, 'c3'=>3, 'c4'=>3, 'c5'=>2, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>2, 'c10'=>3],
            ['nama' => 'Advan Soulmate Intel Celeron', 'c1'=>5, 'c2'=>1, 'c3'=>2, 'c4'=>2, 'c5'=>1, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>1, 'c10'=>3],
            ['nama' => 'Chuwi HeroBook Pro Intel', 'c1'=>5, 'c2'=>1, 'c3'=>3, 'c4'=>2, 'c5'=>1, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>1, 'c10'=>3],
            ['nama' => 'Itel Able 1 Intel Celeron', 'c1'=>5, 'c2'=>1, 'c3'=>2, 'c4'=>2, 'c5'=>1, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>1, 'c10'=>3],
            ['nama' => 'Zyrex Kenshin Intel Celeron', 'c1'=>5, 'c2'=>1, 'c3'=>2, 'c4'=>2, 'c5'=>1, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>1, 'c10'=>3],
            ['nama' => 'Huawei MateBook D14 i3', 'c1'=>4, 'c2'=>2, 'c3'=>3, 'c4'=>3, 'c5'=>2, 'c6'=>3, 'c7'=>4, 'c8'=>4, 'c9'=>2, 'c10'=>4],
            ['nama' => 'Xiaomi RedmiBook 15 Core i3', 'c1'=>4, 'c2'=>2, 'c3'=>3, 'c4'=>3, 'c5'=>2, 'c6'=>4, 'c7'=>3, 'c8'=>4, 'c9'=>2, 'c10'=>4],
            ['nama' => 'Realme Book Prime Core i5', 'c1'=>4, 'c2'=>3, 'c3'=>3, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>4, 'c8'=>4, 'c9'=>3, 'c10'=>3],
            ['nama' => 'Samsung Galaxy Book2 Go', 'c1'=>4, 'c2'=>2, 'c3'=>2, 'c4'=>2, 'c5'=>2, 'c6'=>3, 'c7'=>5, 'c8'=>5, 'c9'=>2, 'c10'=>4],
            ['nama' => 'Microsoft Surface Laptop Go 2', 'c1'=>3, 'c2'=>3, 'c3'=>3, 'c4'=>2, 'c5'=>2, 'c6'=>1, 'c7'=>4, 'c8'=>5, 'c9'=>3, 'c10'=>4],
            ['nama' => 'ASUS ExpertBook B1 B1400CEAE', 'c1'=>4, 'c2'=>2, 'c3'=>3, 'c4'=>3, 'c5'=>2, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>2, 'c10'=>5],
            ['nama' => 'Lenovo ThinkPad E14 Gen 4', 'c1'=>3, 'c2'=>3, 'c3'=>3, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>4, 'c8'=>4, 'c9'=>3, 'c10'=>4],
            ['nama' => 'HP ProBook 440 G9 Core i5', 'c1'=>3, 'c2'=>3, 'c3'=>4, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>4, 'c8'=>4, 'c9'=>3, 'c10'=>4],
            ['nama' => 'Dell Vostro 3420 Core i3', 'c1'=>4, 'c2'=>2, 'c3'=>3, 'c4'=>2, 'c5'=>2, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>2, 'c10'=>4],
            ['nama' => 'Fujitsu Lifebook CH-X Intel', 'c1'=>2, 'c2'=>4, 'c3'=>3, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>3, 'c8'=>5, 'c9'=>2, 'c10'=>3],
            ['nama' => 'ASUS ROG Strix G15 G513RC', 'c1'=>2, 'c2'=>4, 'c3'=>3, 'c4'=>4, 'c5'=>4, 'c6'=>5, 'c7'=>3, 'c8'=>1, 'c9'=>1, 'c10'=>5],
            ['nama' => 'Lenovo Legion Slim 7 16ARHA7', 'c1'=>1, 'c2'=>5, 'c3'=>4, 'c4'=>5, 'c5'=>5, 'c6'=>5, 'c7'=>4, 'c8'=>2, 'c9'=>3, 'c10'=>4],
            ['nama' => 'HP Transmute Gaming Pavilion', 'c1'=>3, 'c2'=>3, 'c3'=>3, 'c4'=>4, 'c5'=>4, 'c6'=>5, 'c7'=>2, 'c8'=>1, 'c9'=>2, 'c10'=>4],
            ['nama' => 'Acer Aspire 7 Gaming A715-76G', 'c1'=>3, 'c2'=>4, 'c3'=>3, 'c4'=>4, 'c5'=>4, 'c6'=>5, 'c7'=>3, 'c8'=>2, 'c9'=>2, 'c10'=>4],
            ['nama' => 'MSI Thin GF63 12UCX', 'c1'=>3, 'c2'=>4, 'c3'=>3, 'c4'=>4, 'c5'=>4, 'c6'=>5, 'c7'=>2, 'c8'=>2, 'c9'=>2, 'c10'=>4],
            ['nama' => 'Dell G15 Gaming 5520', 'c1'=>3, 'c2'=>4, 'c3'=>3, 'c4'=>4, 'c5'=>4, 'c6'=>5, 'c7'=>2, 'c8'=>1, 'c9'=>2, 'c10'=>4],
            ['nama' => 'Gigabyte Aorus 15 BKF', 'c1'=>1, 'c2'=>5, 'c3'=>4, 'c4'=>5, 'c5'=>5, 'c6'=>5, 'c7'=>3, 'c8'=>1, 'c9'=>3, 'c10'=>3],
            ['nama' => 'Razer Blade 14 AMD Ryzen 9', 'c1'=>1, 'c2'=>5, 'c3'=>4, 'c4'=>5, 'c5'=>5, 'c6'=>3, 'c7'=>4, 'c8'=>4, 'c9'=>3, 'c10'=>3],
            ['nama' => 'Alienware m15 R7 Core i7', 'c1'=>1, 'c2'=>5, 'c3'=>5, 'c4'=>5, 'c5'=>5, 'c6'=>5, 'c7'=>2, 'c8'=>1, 'c9'=>3, 'c10'=>4],
            ['nama' => 'Apple MacBook Pro 14 M2 Pro', 'c1'=>1, 'c2'=>5, 'c3'=>4, 'c4'=>5, 'c5'=>4, 'c6'=>3, 'c7'=>5, 'c8'=>4, 'c9'=>4, 'c10'=>5],
            ['nama' => 'ASUS Vivobook Pro 15 OLED K6502VU', 'c1'=>2, 'c2'=>4, 'c3'=>4, 'c4'=>4, 'c5'=>5, 'c6'=>5, 'c7'=>3, 'c8'=>2, 'c9'=>3, 'c10'=>5],
            ['nama' => 'Lenovo ThinkBook 14 G4+ IAP', 'c1'=>3, 'c2'=>4, 'c3'=>4, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>4, 'c8'=>4, 'c9'=>3, 'c10'=>4],
            ['nama' => 'HP Envy x360 2-in-1 13-bf0033TU', 'c1'=>2, 'c2'=>3, 'c3'=>4, 'c4'=>4, 'c5'=>2, 'c6'=>1, 'c7'=>4, 'c8'=>5, 'c9'=>3, 'c10'=>4],
            ['nama' => 'Acer Swift Edge 16 SFA16-41', 'c1'=>2, 'c2'=>4, 'c3'=>4, 'c4'=>5, 'c5'=>2, 'c6'=>5, 'c7'=>3, 'c8'=>5, 'c9'=>3, 'c10'=>4],
            ['nama' => 'Huawei MateBook X Pro (2022)', 'c1'=>1, 'c2'=>4, 'c3'=>4, 'c4'=>5, 'c5'=>2, 'c6'=>3, 'c7'=>4, 'c8'=>5, 'c9'=>3, 'c10'=>4],
            ['nama' => 'ASUS Zenbook S 13 OLED UM5302TA', 'c1'=>2, 'c2'=>4, 'c3'=>4, 'c4'=>4, 'c5'=>2, 'c6'=>1, 'c7'=>4, 'c8'=>5, 'c9'=>3, 'c10'=>5],
            ['nama' => 'Lenovo Yoga 7i 2-in-1 Intel', 'c1'=>2, 'c2'=>4, 'c3'=>4, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>4, 'c8'=>4, 'c9'=>3, 'c10'=>4],
            ['nama' => 'Dell XPS 13 Plus 9320', 'c1'=>1, 'c2'=>4, 'c3'=>4, 'c4'=>4, 'c5'=>2, 'c6'=>1, 'c7'=>3, 'c8'=>5, 'c9'=>3, 'c10'=>4],
            ['nama' => 'LG Gram 14Z90Q Ultra Lightweight', 'c1'=>2, 'c2'=>4, 'c3'=>4, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>5, 'c8'=>5, 'c9'=>2, 'c10'=>3],
            ['nama' => 'Dynabook Portege X30W-J', 'c1'=>2, 'c2'=>4, 'c3'=>3, 'c4'=>4, 'c5'=>2, 'c6'=>1, 'c7'=>4, 'c8'=>5, 'c9'=>2, 'c10'=>3],
            ['nama' => 'ASUS Vivobook 14X M1403QA', 'c1'=>4, 'c2'=>4, 'c3'=>3, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>2, 'c10'=>5],
            ['nama' => 'Lenovo IdeaPad Slim 5 14IAL7', 'c1'=>3, 'c2'=>4, 'c3'=>3, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>4, 'c8'=>4, 'c9'=>3, 'c10'=>4],
            ['nama' => 'HP Pavilion 14-dv2003TX', 'c1'=>3, 'c2'=>4, 'c3'=>3, 'c4'=>4, 'c5'=>3, 'c6'=>3, 'c7'=>3, 'c8'=>4, 'c9'=>2, 'c10'=>4],
            ['nama' => 'Acer Aspire Vero AV14-51', 'c1'=>3, 'c2'=>3, 'c3'=>3, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>4, 'c8'=>4, 'c9'=>3, 'c10'=>4],
            ['nama' => 'Infinix Zero Book Ultra', 'c1'=>3, 'c2'=>5, 'c3'=>4, 'c4'=>5, 'c5'=>3, 'c6'=>4, 'c7'=>4, 'c8'=>3, 'c9'=>2, 'c10'=>3],
            ['nama' => 'MSI Prestige 14 Evo B13M', 'c1'=>2, 'c2'=>4, 'c3'=>4, 'c4'=>4, 'c5'=>2, 'c6'=>3, 'c7'=>4, 'c8'=>4, 'c9'=>3, 'c10'=>4],
            ['nama' => 'ASUS ROG Strix Scar 16 G634JZ', 'c1'=>1, 'c2'=>5, 'c3'=>5, 'c4'=>5, 'c5'=>5, 'c6'=>5, 'c7'=>3, 'c8'=>1, 'c9'=>3, 'c10'=>5],
            ['nama' => 'MSI Titan GT77 HX 13VI', 'c1'=>1, 'c2'=>5, 'c3'=>5, 'c4'=>5, 'c5'=>5, 'c6'=>5, 'c7'=>2, 'c8'=>1, 'c9'=>3, 'c10'=>4],
            ['nama' => 'Lenovo Legion Pro 5 16IRX8', 'c1'=>1, 'c2'=>5, 'c3'=>5, 'c4'=>5, 'c5'=>5, 'c6'=>5, 'c7'=>3, 'c8'=>1, 'c9'=>3, 'c10'=>4],
        ];

        // Variabel pembantu untuk membuat kodeAlternatif (A1, A2, dst)
        $nomor = 1;

        foreach ($laptops as $laptop) {
            $userId = 1; 
            $kode = 'A' . $nomor++;
            $slug = \Illuminate\Support\Str::slug($laptop['nama']) . '-' . $kode;

            // 3. Suntik ke tabel alternatifs
            $alternatifId = DB::table('alternatifs')->insertGetId([
                'user_id'        => $userId,
                'kodeAlternatif' => $kode,
                'namaAlternatif' => $laptop['nama'],
                'slug'           => $slug,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // 4. Suntik ke tabel penilaians
            DB::table('penilaians')->insert([
                'user_id'       => $userId,
                'alternatif_id' => $alternatifId,
                'slug'          => $slug . '-penilaian',
                'C1x'           => $laptop['c1'],
                'C2x'           => $laptop['c2'],
                'C3x'           => $laptop['c3'],
                'C4x'           => $laptop['c4'],
                'C5x'           => $laptop['c5'],
                'C6x'           => $laptop['c6'],
                'C7x'           => $laptop['c7'],
                'C8x'           => $laptop['c8'],
                'C9x'           => $laptop['c9'],
                'C10x'          => $laptop['c10'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        // 5. Aktifkan kembali pengaman relasi database setelah selesai menyuntik data
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}