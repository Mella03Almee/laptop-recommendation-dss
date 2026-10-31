<?php

namespace Database\Seeders;

use App\Models\Alternatif;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlternatifSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Alternatif::create([
            'user_id' => '1',
            'kodeAlternatif' => 'A1',
            'slug' => 'A1',
            'namaAlternatif' => 'Viewsonic Elite XG270'
        ]);

        Alternatif::create([
            'user_id' => '1',
            'kodeAlternatif' => 'A2',
            'slug' => 'A2',
            'namaAlternatif' => 'Philips 325M2CR2'
        ]);

        Alternatif::create([
            'user_id' => '1',
            'kodeAlternatif' => 'A3',
            'slug' => 'A3',
            'namaAlternatif' => 'LG 27UL500-W'
        ]);

        Alternatif::create([
            'user_id' => '1',
            'kodeAlternatif' => 'A4',
            'slug' => 'A4',
            'namaAlternatif' => 'Dell P2422H'
        ]);

        Alternatif::create([
            'user_id' => '1',
            'kodeAlternatif' => 'A5',
            'slug' => 'A5',
            'namaAlternatif' => 'MSI Optix G273'
        ]);

        Alternatif::create([
            'user_id' => '1',
            'kodeAlternatif' => 'A6',
            'slug' => 'A6',
            'namaAlternatif' => 'Samsung Odyssey G5'
        ]);
    }
}
