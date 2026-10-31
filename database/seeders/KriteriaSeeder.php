<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use Illuminate\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['kodeKriteria' => 'C1', 'namaKriteria' => 'Kisaran Harga', 'bobot' => 5, 'jenis' => 'cost'],
            ['kodeKriteria' => 'C2', 'namaKriteria' => 'CPU / Prosesor', 'bobot' => 5, 'jenis' => 'benefit'],
            ['kodeKriteria' => 'C3', 'namaKriteria' => 'RAM', 'bobot' => 4, 'jenis' => 'benefit'],
            ['kodeKriteria' => 'C4', 'namaKriteria' => 'Penyimpanan (SSD/HDD)', 'bobot' => 4, 'jenis' => 'benefit'],
            ['kodeKriteria' => 'C5', 'namaKriteria' => 'GPU (Grafis)', 'bobot' => 3, 'jenis' => 'benefit'],
            ['kodeKriteria' => 'C6', 'namaKriteria' => 'Ukuran Layar', 'bobot' => 3, 'jenis' => 'cost'],
            ['kodeKriteria' => 'C7', 'namaKriteria' => 'Daya Tahan Baterai', 'bobot' => 3, 'jenis' => 'benefit'],
            ['kodeKriteria' => 'C8', 'namaKriteria' => 'Berat', 'bobot' => 3, 'jenis' => 'cost'],
            ['kodeKriteria' => 'C9', 'namaKriteria' => 'Kualitas Kamera', 'bobot' => 2, 'jenis' => 'benefit'],
            ['kodeKriteria' => 'C10', 'namaKriteria' => 'Merek Laptop', 'bobot' => 2, 'jenis' => 'benefit'],
        ];

        foreach ($data as $item) {
            Kriteria::create($item);
        }
    }
}