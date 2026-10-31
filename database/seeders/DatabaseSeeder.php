<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Jalankan Seeder Kriteria Utama
        $this->call(KriteriaSeeder::class);

        // 2. Jalankan Seeder Sub Kriteria yang Baru Kita Buat
        $this->call(SubKriteriaSeeder::class);
 
    $this->call([
        // Seeder kriteria atau user lainnya (jika ada)...
        LaptopSeeder::class,
        MasterAkademikSeeder::class, ]);
    }
}