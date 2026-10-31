<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterAkademikSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ISI DATA KATEGORI BOBOT WP (Cost 10 : Benefit 10)
        DB::table('kategori_bobot')->insert([
            [
                'id' => 1,
                'nama_kategori' => 'Komputasi & IT',
                'c1_harga' => 5.0, 'c6_ukuran_layar' => 2.0, 'c8_berat' => 3.0, // Cost = 10
                'c2_cpu' => 3.5, 'c3_ram' => 2.5, 'c4_storage' => 2.0, 'c5_gpu' => 1.0, 'c7_baterai' => 0.5, 'c9_kamera' => 0.5 // Benefit = 10
            ],
            [
                'id' => 2,
                'nama_kategori' => 'Teknik, Arsitektur & Desain',
                'c1_harga' => 5.0, 'c6_ukuran_layar' => 2.0, 'c8_berat' => 3.0, // Cost = 10
                'c2_cpu' => 3.0, 'c3_ram' => 2.0, 'c4_storage' => 1.5, 'c5_gpu' => 3.0, 'c7_baterai' => 0.25, 'c9_kamera' => 0.25 // Benefit = 10
            ],
            [
                'id' => 3,
                'nama_kategori' => 'Sains, Pertanian & Kesehatan',
                'c1_harga' => 5.0, 'c6_ukuran_layar' => 2.0, 'c8_berat' => 3.0, // Cost = 10
                'c2_cpu' => 3.0, 'c3_ram' => 2.5, 'c4_storage' => 2.0, 'c5_gpu' => 0.5, 'c7_baterai' => 1.5, 'c9_kamera' => 0.5 // Benefit = 10
            ],
            [
                'id' => 4,
                'nama_kategori' => 'Ekonomi, Bisnis & Manajemen',
                'c1_harga' => 5.0, 'c6_ukuran_layar' => 2.0, 'c8_berat' => 3.0, // Cost = 10
                'c2_cpu' => 2.5, 'c3_ram' => 2.5, 'c4_storage' => 2.0, 'c5_gpu' => 0.5, 'c7_baterai' => 2.0, 'c9_kamera' => 0.5 // Benefit = 10
            ],
            [
                'id' => 5,
                'nama_kategori' => 'Sosial, Hukum, Pendidikan & Agama',
                'c1_harga' => 5.0, 'c6_ukuran_layar' => 2.0, 'c8_berat' => 3.0, // Cost = 10
                'c2_cpu' => 2.0, 'c3_ram' => 2.0, 'c4_storage' => 1.5, 'c5_gpu' => 0.5, 'c7_baterai' => 3.0, 'c9_kamera' => 1.0 // Benefit = 10
            ],
        ]);

        // 2. ISI DATA UNIVERSITAS
        DB::table('universitas')->insert([
            ['id' => 1, 'nama_universitas' => 'UIN Raden Intan Lampung', 'singkatan' => 'UIN'],
            ['id' => 2, 'nama_universitas' => 'Universitas Lampung', 'singkatan' => 'UNILA'],
            ['id' => 3, 'nama_universitas' => 'Institut Teknologi Sumatera', 'singkatan' => 'ITERA'],
        ]);

        // 3. ISI DATA FAKULTAS
        DB::table('fakultas')->insert([
            // UIN (id 1-8)
            ['id' => 1, 'universitas_id' => 1, 'nama_fakultas' => 'Fakultas Tarbiyah dan Keguruan'],
            ['id' => 2, 'universitas_id' => 1, 'nama_fakultas' => 'Fakultas Ushuluddin dan Studi Agama'],
            ['id' => 3, 'universitas_id' => 1, 'nama_fakultas' => 'Fakultas Syariah'],
            ['id' => 4, 'universitas_id' => 1, 'nama_fakultas' => 'Fakultas Dakwah dan Ilmu Komunikasi'],
            ['id' => 5, 'universitas_id' => 1, 'nama_fakultas' => 'Fakultas Ekonomi dan Bisnis Islam'],
            ['id' => 6, 'universitas_id' => 1, 'nama_fakultas' => 'Fakultas Adab'],
            ['id' => 7, 'universitas_id' => 1, 'nama_fakultas' => 'Fakultas Psikologi Islam'],
            ['id' => 8, 'universitas_id' => 1, 'nama_fakultas' => 'Fakultas Sains dan Teknologi'],

            // UNILA (id 9-16)
            ['id' => 9, 'universitas_id' => 2, 'nama_fakultas' => 'Fakultas Ekonomi dan Bisnis'],
            ['id' => 10, 'universitas_id' => 2, 'nama_fakultas' => 'Fakultas Hukum'],
            ['id' => 11, 'universitas_id' => 2, 'nama_fakultas' => 'Fakultas Keguruan dan Ilmu Pendidikan'],
            ['id' => 12, 'universitas_id' => 2, 'nama_fakultas' => 'Fakultas Pertanian'],
            ['id' => 13, 'universitas_id' => 2, 'nama_fakultas' => 'Fakultas Teknik'],
            ['id' => 14, 'universitas_id' => 2, 'nama_fakultas' => 'Fakultas Ilmu Sosial dan Ilmu Politik'],
            ['id' => 15, 'universitas_id' => 2, 'nama_fakultas' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam'],
            ['id' => 16, 'universitas_id' => 2, 'nama_fakultas' => 'Fakultas Kedokteran'],

            // ITERA (id 17-19)
            ['id' => 17, 'universitas_id' => 3, 'nama_fakultas' => 'Jurusan Teknologi Produksi dan Industri'],
            ['id' => 18, 'universitas_id' => 3, 'nama_fakultas' => 'Jurusan Sains'],
            ['id' => 19, 'universitas_id' => 3, 'nama_fakultas' => 'Jurusan Infrastruktur dan Kewilayahan'],
        ]);

        // 4. ISI DATA JURUSAN & PEMETAAN KATEGORI BOBOT
        $jurusanData = [
            // UIN - Tarbiyah
            ['fakultas_id' => 1, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Pendidikan Agama Islam', 'jenjang' => 'S1'],
            ['fakultas_id' => 1, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Pendidikan Bahasa Arab', 'jenjang' => 'S1'],
            ['fakultas_id' => 1, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Manajemen Pendidikan Islam', 'jenjang' => 'S1'],
            ['fakultas_id' => 1, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Pendidikan Bahasa Inggris', 'jenjang' => 'S1'],
            ['fakultas_id' => 1, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Pendidikan Biologi', 'jenjang' => 'S1'],
            ['fakultas_id' => 1, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Pendidikan Fisika', 'jenjang' => 'S1'],
            ['fakultas_id' => 1, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Bimbingan dan Konseling', 'jenjang' => 'S1'],
            ['fakultas_id' => 1, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Pendidikan Matematika', 'jenjang' => 'S1'],
            ['fakultas_id' => 1, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Pendidikan Anak Usia Dini', 'jenjang' => 'S1'],
            ['fakultas_id' => 1, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Pendidikan Guru Madrasah Ibtidaiyah', 'jenjang' => 'S1'],

            // UIN - Ushuluddin
            ['fakultas_id' => 2, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Aqidah dan Filsafat Islam', 'jenjang' => 'S1'],
            ['fakultas_id' => 2, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Ilmu Alquran dan Tafsir', 'jenjang' => 'S1'],
            ['fakultas_id' => 2, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Pemikiran Politik Islam', 'jenjang' => 'S1'],
            ['fakultas_id' => 2, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Sosiologi Agama', 'jenjang' => 'S1'],
            ['fakultas_id' => 2, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Studi Agama-Agama', 'jenjang' => 'S1'],
            ['fakultas_id' => 2, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Ilmu Hadist', 'jenjang' => 'S1'],

            // UIN - Syariah
            ['fakultas_id' => 3, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Hukum Keluarga (Ahwal Syakhshiyah)', 'jenjang' => 'S1'],
            ['fakultas_id' => 3, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Hukum Tata Negara', 'jenjang' => 'S1'],
            ['fakultas_id' => 3, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Hukum Ekonomi Syariah', 'jenjang' => 'S1'],

            // UIN - Dakwah
            ['fakultas_id' => 4, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Komunikasi dan Penyiaran Islam', 'jenjang' => 'S1'],
            ['fakultas_id' => 4, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Pengembangan Masyarakat Islam', 'jenjang' => 'S1'],
            ['fakultas_id' => 4, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Manajemen Dakwah', 'jenjang' => 'S1'],

            // UIN - FEBI
            ['fakultas_id' => 5, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Akuntansi Syariah', 'jenjang' => 'S1'],
            ['fakultas_id' => 5, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Perbankan Syariah', 'jenjang' => 'S1'],
            ['fakultas_id' => 5, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Ekonomi Syariah', 'jenjang' => 'S1'],
            ['fakultas_id' => 5, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Manajemen Bisnis Syariah', 'jenjang' => 'S1'],

            // UIN - Adab & Psikologi
            ['fakultas_id' => 6, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Ilmu Perpustakaan dan Informasi Islam', 'jenjang' => 'S1'],
            ['fakultas_id' => 6, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Sejarah Peradaban Islam', 'jenjang' => 'S1'],
            ['fakultas_id' => 7, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Psikologi Islam', 'jenjang' => 'S1'],
            ['fakultas_id' => 7, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Tasawuf dan Psikoterapi', 'jenjang' => 'S1'],
            ['fakultas_id' => 7, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Bimbingan dan Konseling Islam', 'jenjang' => 'S1'],

            // UIN - Saintek
            ['fakultas_id' => 8, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Biologi', 'jenjang' => 'S1'],
            ['fakultas_id' => 8, 'kategori_bobot_id' => 1, 'nama_jurusan' => 'Sistem Informasi', 'jenjang' => 'S1'],
            ['fakultas_id' => 8, 'kategori_bobot_id' => 1, 'nama_jurusan' => 'Sains Data', 'jenjang' => 'S1'],
            ['fakultas_id' => 8, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Kimia', 'jenjang' => 'S1'],

            // UNILA - FEB
            ['fakultas_id' => 9, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Akuntansi', 'jenjang' => 'D3'],
            ['fakultas_id' => 9, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Keuangan, Perbankan & Asuransi', 'jenjang' => 'D3'],
            ['fakultas_id' => 9, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Pemasaran', 'jenjang' => 'D3'],
            ['fakultas_id' => 9, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Perpajakan', 'jenjang' => 'D3'],
            ['fakultas_id' => 9, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Akuntansi', 'jenjang' => 'S1'],
            ['fakultas_id' => 9, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Ekonomi Pembangunan', 'jenjang' => 'S1'],
            ['fakultas_id' => 9, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Manajemen', 'jenjang' => 'S1'],

            // UNILA - Hukum & FKIP
            ['fakultas_id' => 10, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Ilmu Hukum', 'jenjang' => 'S1'],
            ['fakultas_id' => 11, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Bimbingan Konseling', 'jenjang' => 'S1'],
            ['fakultas_id' => 11, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Pendidikan Bahasa Dan Sastra Indonesia', 'jenjang' => 'S1'],
            ['fakultas_id' => 11, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Pendidikan Bahasa Inggris', 'jenjang' => 'S1'],
            ['fakultas_id' => 11, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Pendidikan Biologi', 'jenjang' => 'S1'],
            ['fakultas_id' => 11, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Pendidikan Ekonomi', 'jenjang' => 'S1'],
            ['fakultas_id' => 11, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Pendidikan Fisika', 'jenjang' => 'S1'],
            ['fakultas_id' => 11, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Pendidikan Kimia', 'jenjang' => 'S1'],
            ['fakultas_id' => 11, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Pendidikan Matematika', 'jenjang' => 'S1'],

            // UNILA - Pertanian
            ['fakultas_id' => 12, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Agribisnis', 'jenjang' => 'S1'],
            ['fakultas_id' => 12, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Agroteknologi', 'jenjang' => 'S1'],
            ['fakultas_id' => 12, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Kehutanan', 'jenjang' => 'S1'],
            ['fakultas_id' => 12, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Teknologi Hasil Pertanian', 'jenjang' => 'S1'],
            ['fakultas_id' => 12, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Teknik Pertanian', 'jenjang' => 'S1'],

            // UNILA - Teknik
            ['fakultas_id' => 13, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Elektro', 'jenjang' => 'S1'],
            ['fakultas_id' => 13, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Geofisika', 'jenjang' => 'S1'],
            ['fakultas_id' => 13, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Kimia', 'jenjang' => 'S1'],
            ['fakultas_id' => 13, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Mesin', 'jenjang' => 'S1'],
            ['fakultas_id' => 13, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Sipil', 'jenjang' => 'S1'],
            ['fakultas_id' => 13, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Arsitektur', 'jenjang' => 'S1'],
            ['fakultas_id' => 13, 'kategori_bobot_id' => 1, 'nama_jurusan' => 'Teknik Informatika', 'jenjang' => 'S1'],

            // UNILA - FISIP & FMIPA & Kedokteran
            ['fakultas_id' => 14, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Ilmu Komunikasi', 'jenjang' => 'S1'],
            ['fakultas_id' => 14, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Sosiologi', 'jenjang' => 'S1'],
            ['fakultas_id' => 14, 'kategori_bobot_id' => 5, 'nama_jurusan' => 'Administrasi Publik', 'jenjang' => 'S1'],
            ['fakultas_id' => 14, 'kategori_bobot_id' => 4, 'nama_jurusan' => 'Administrasi Bisnis', 'jenjang' => 'S1'],
            ['fakultas_id' => 15, 'kategori_bobot_id' => 1, 'nama_jurusan' => 'Manajemen Informatika', 'jenjang' => 'D3'],
            ['fakultas_id' => 15, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Fisika', 'jenjang' => 'S1'],
            ['fakultas_id' => 15, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Kimia', 'jenjang' => 'S1'],
            ['fakultas_id' => 15, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Matematika', 'jenjang' => 'S1'],
            ['fakultas_id' => 15, 'kategori_bobot_id' => 1, 'nama_jurusan' => 'Ilmu Komputer', 'jenjang' => 'S1'],
            ['fakultas_id' => 15, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Biologi', 'jenjang' => 'S1'],
            ['fakultas_id' => 16, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Pendidikan Dokter', 'jenjang' => 'S1'],

            // ITERA - Industri
            ['fakultas_id' => 17, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Elektro', 'jenjang' => 'S1'],
            ['fakultas_id' => 17, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Geofisika', 'jenjang' => 'S1'],
            ['fakultas_id' => 17, 'kategori_bobot_id' => 1, 'nama_jurusan' => 'Teknik Informatika', 'jenjang' => 'S1'],
            ['fakultas_id' => 17, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Geologi', 'jenjang' => 'S1'],
            ['fakultas_id' => 17, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Mesin', 'jenjang' => 'S1'],
            ['fakultas_id' => 17, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Industri', 'jenjang' => 'S1'],
            ['fakultas_id' => 17, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Kimia', 'jenjang' => 'S1'],
            ['fakultas_id' => 17, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Pertambangan', 'jenjang' => 'S1'],
            ['fakultas_id' => 17, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Telekomunikasi', 'jenjang' => 'S1'],
            ['fakultas_id' => 17, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Biomedik', 'jenjang' => 'S1'],

            // ITERA - Sains
            ['fakultas_id' => 18, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Fisika', 'jenjang' => 'S1'],
            ['fakultas_id' => 18, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Matematika', 'jenjang' => 'S1'],
            ['fakultas_id' => 18, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Biologi', 'jenjang' => 'S1'],
            ['fakultas_id' => 18, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Kimia', 'jenjang' => 'S1'],
            ['fakultas_id' => 18, 'kategori_bobot_id' => 3, 'nama_jurusan' => 'Farmasi', 'jenjang' => 'S1'],
            ['fakultas_id' => 18, 'kategori_bobot_id' => 1, 'nama_jurusan' => 'Sains Data', 'jenjang' => 'S1'],

            // ITERA - Infrastruktur
            ['fakultas_id' => 19, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Geomatika', 'jenjang' => 'S1'],
            ['fakultas_id' => 19, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Perencanaan Wilayah dan Kota', 'jenjang' => 'S1'],
            ['fakultas_id' => 19, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Sipil', 'jenjang' => 'S1'],
            ['fakultas_id' => 19, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Arsitektur', 'jenjang' => 'S1'],
            ['fakultas_id' => 19, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Lingkungan', 'jenjang' => 'S1'],
            ['fakultas_id' => 19, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Kelautan', 'jenjang' => 'S1'],
            ['fakultas_id' => 19, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Desain Komunikasi Visual', 'jenjang' => 'S1'],
            ['fakultas_id' => 19, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Arsitektur Lanskap', 'jenjang' => 'S1'],
            ['fakultas_id' => 19, 'kategori_bobot_id' => 2, 'nama_jurusan' => 'Teknik Perkeretaapian', 'jenjang' => 'S1'],
        ];

        DB::table('jurusan')->insert($jurusanData);
    }
}