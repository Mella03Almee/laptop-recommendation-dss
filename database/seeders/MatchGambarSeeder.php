<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alternatif;
use Illuminate\Support\Facades\Storage;

class MatchGambarSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua file gambar dari folder storage/app/public/laptops
        $files = Storage::disk('public')->files('laptops');
        
        // Urutkan file agar sesuai abjad/urutan
        sort($files);

        // Ambil semua data alternatif diurutkan berdasarkan ID
        $alternatifs = Alternatif::orderBy('id', 'asc')->get();

        foreach ($alternatifs as $index => $item) {
            // Jika file gambar tersedia pada urutan index tersebut
            if (isset($files[$index])) {
                $item->update([
                    'gambar' => $files[$index]
                ]);
            }
        }

        $this->command->info('Berhasil mengupdate ' . count($files) . ' gambar alternatif!');
    }
}