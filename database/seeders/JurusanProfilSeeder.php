<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanProfilSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | PROFIL KEBUTUHAN LAPTOP
        |--------------------------------------------------------------------------
        |
        | harga_min_saran dan harga_max_saran bukan filter harga pengguna.
        | Keduanya menunjukkan rentang harga laptop yang wajar untuk
        | kebutuhan umum masing-masing profil jurusan.
        |
        */

        $profil = [

            'P1' => [
                'harga_min_saran' => 5000000,
                'harga_max_saran' => 9000000,
                'tingkat_kebutuhan' => 'Ringan',
                'software_utama' => 'Microsoft Office, browser, PDF, Zoom/Google Meet',
                'profil_perangkat' => 'Laptop untuk aktivitas akademik ringan, pengolahan dokumen, presentasi, browsing, dan pembelajaran daring. RAM 8 GB cukup, tetapi 16 GB lebih disarankan. SSD menjadi pilihan utama.',
            ],

            'P2' => [
                'harga_min_saran' => 6000000,
                'harga_max_saran' => 10000000,
                'tingkat_kebutuhan' => 'Menengah Ringan',
                'software_utama' => 'Microsoft Office, Excel, SPSS, JASP, browser',
                'profil_perangkat' => 'Laptop untuk pekerjaan akademik dengan pengolahan data ringan hingga menengah. RAM 16 GB lebih disarankan agar nyaman menjalankan aplikasi statistik dan multitasking.',
            ],

            'P3' => [
                'harga_min_saran' => 7000000,
                'harga_max_saran' => 12000000,
                'tingkat_kebutuhan' => 'Menengah',
                'software_utama' => 'Microsoft Office, Excel, SPSS, aplikasi akuntansi, Power BI, browser',
                'profil_perangkat' => 'Laptop untuk pengolahan data bisnis, akuntansi, statistik, laporan, dan multitasking. RAM 16 GB disarankan dengan SSD minimal 512 GB.',
            ],

            'P4' => [
                'harga_min_saran' => 8000000,
                'harga_max_saran' => 18000000,
                'tingkat_kebutuhan' => 'Berat',
                'software_utama' => 'Visual Studio, VS Code, Git, PHP, Laravel, Java, Python, MySQL, XAMPP/Laragon, Android Studio',
                'profil_perangkat' => 'Laptop untuk pemrograman, pengembangan aplikasi, basis data, virtualisasi ringan, dan multitasking pengembangan perangkat lunak. RAM 16 GB disarankan dengan SSD 512 GB hingga 1 TB.',
            ],

            'P5' => [
                'harga_min_saran' => 10000000,
                'harga_max_saran' => 22000000,
                'tingkat_kebutuhan' => 'Berat',
                'software_utama' => 'Python, R, MATLAB, Jupyter Notebook, SQL, aplikasi komputasi',
                'profil_perangkat' => 'Laptop untuk pengolahan data, pemrograman ilmiah, komputasi numerik, dan analisis data. RAM 16 GB disarankan dan 32 GB lebih ideal untuk beban kerja yang lebih besar.',
            ],

            'P6' => [
                'harga_min_saran' => 12000000,
                'harga_max_saran' => 25000000,
                'tingkat_kebutuhan' => 'Berat',
                'software_utama' => 'AutoCAD, MATLAB, SolidWorks, ETABS, SAP2000',
                'profil_perangkat' => 'Laptop untuk pekerjaan teknik, pemodelan, simulasi, perhitungan, dan desain teknis. RAM 16–32 GB disarankan serta GPU yang lebih kuat untuk pekerjaan grafis dan pemodelan.',
            ],

            'P7' => [
                'harga_min_saran' => 15000000,
                'harga_max_saran' => 35000000,
                'tingkat_kebutuhan' => 'Sangat Berat',
                'software_utama' => 'AutoCAD, Revit, SketchUp, Blender, 3ds Max, Lumion, Enscape, Photoshop',
                'profil_perangkat' => 'Laptop untuk CAD, arsitektur, desain 3D, rendering, dan visualisasi. RAM 16–32 GB disarankan, SSD 1 TB ideal, serta GPU dedicated sangat disarankan untuk pekerjaan 3D dan rendering.',
            ],

            'P8' => [
                'harga_min_saran' => 10000000,
                'harga_max_saran' => 25000000,
                'tingkat_kebutuhan' => 'Berat',
                'software_utama' => 'Adobe Premiere Pro, After Effects, Photoshop, Illustrator, DaVinci Resolve, Canva',
                'profil_perangkat' => 'Laptop untuk produksi konten, pengeditan foto dan video, motion graphics, dan desain. RAM 16 GB disarankan dan 32 GB lebih ideal untuk video resolusi tinggi. GPU dedicated sangat membantu.',
            ],

            'P9' => [
                'harga_min_saran' => 8000000,
                'harga_max_saran' => 16000000,
                'tingkat_kebutuhan' => 'Menengah-Berat',
                'software_utama' => 'MATLAB, SPSS, R, aplikasi statistik, aplikasi ilmiah dan kesehatan',
                'profil_perangkat' => 'Laptop untuk analisis statistik, pengolahan data ilmiah, simulasi, dan aktivitas akademik bidang sains dan kesehatan. RAM 16 GB disarankan dengan SSD.',
            ],

            'P10' => [
                'harga_min_saran' => 8000000,
                'harga_max_saran' => 18000000,
                'tingkat_kebutuhan' => 'Menengah-Berat',
                'software_utama' => 'ArcGIS, QGIS, AutoCAD, Excel, aplikasi statistik dan pemetaan',
                'profil_perangkat' => 'Laptop untuk pengolahan data spasial, pemetaan, GIS, statistik, dan pekerjaan lapangan. RAM 16 GB disarankan dengan SSD 512 GB hingga 1 TB.',
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | MAPPING JURUSAN -> PROFIL
        |--------------------------------------------------------------------------
        */

        $mapping = [

            // P1 - Akademik ringan
            'Pendidikan Agama Islam' => 'P1',
            'Pendidikan Bahasa Arab' => 'P1',
            'Manajemen Pendidikan Islam' => 'P1',
            'Pendidikan Bahasa Inggris' => 'P1',
            'Pendidikan Anak Usia Dini' => 'P1',
            'PGMI' => 'P1',
            'Aqidah dan Filsafat Islam' => 'P1',
            'Ilmu Alquran dan Tafsir' => 'P1',
            'Pemikiran Politik Islam' => 'P1',
            'Studi Agama-Agama' => 'P1',
            'Ilmu Hadist' => 'P1',
            'Hukum Keluarga' => 'P1',
            'Hukum Tata Negara' => 'P1',
            'Ilmu Hukum' => 'P1',
            'Pengembangan Masyarakat Islam' => 'P1',
            'Ilmu Perpustakaan dan Informasi Islam' => 'P1',
            'Sejarah Peradaban Islam' => 'P1',
            'Sastra Inggris' => 'P1',
            'Hukum Bisnis' => 'P1',
            'Pendidikan Guru Madrasah Ibtidaiyah' => 'P1',
            'Sosiologi Agama' => 'P1',
            'Hukum Keluarga (Ahwal Syakhshiyah)' => 'P1',

            // P2 - Produktivitas & statistik ringan
            'Bimbingan dan Konseling' => 'P2',
            'Bimbingan Konseling' => 'P2',
            'Bimbingan dan Konseling Islam' => 'P2',
            'Pendidikan Bahasa dan Sastra Indonesia' => 'P2',
            'Pendidikan Olahraga' => 'P2',
            'Sosiologi' => 'P2',
            'Psikologi' => 'P2',
            'Psikologi Islam' => 'P2',

            // P3 - Bisnis & analitik
            'Manajemen Dakwah' => 'P3',
            'Akuntansi Syariah' => 'P3',
            'Perbankan Syariah' => 'P3',
            'Ekonomi Syariah' => 'P3',
            'Manajemen Bisnis Syariah' => 'P3',
            'Akuntansi D3' => 'P3',
            'Keuangan Perbankan & Asuransi D3' => 'P3',
            'Pemasaran D3' => 'P3',
            'Perpajakan D3' => 'P3',
            'Akuntansi S1' => 'P3',
            'Akuntansi' => 'P3',
            'Ekonomi Pembangunan' => 'P3',
            'Manajemen' => 'P3',
            'Administrasi Bisnis' => 'P3',
            'Administrasi Publik' => 'P3',
            'Agribisnis' => 'P3',
            'Agribisnis Peternakan D4' => 'P3',
            'Agribisnis Pangan D4' => 'P3',
            'Pengelolaan Agribisnis D4' => 'P3',
            'Akuntansi Perpajakan D4' => 'P3',
            'Akuntansi Bisnis Digital D4' => 'P3',
            'Bisnis Digital' => 'P3',
            'Pengelolaan Perhotelan D4' => 'P3',
            'Pengelolaan Konvensi dan Acara' => 'P3',
            'Pendidikan Ekonomi' => 'P3',
            'Bahasa Inggris untuk Komunikasi Bisnis dan Profesional' => 'P3',
            'Perjalanan Wisata' => 'P3',
            'Pariwisata' => 'P3',
            'Hukum Ekonomi Syariah' => 'P3',
            'Komunikasi dan Penyiaran Islam' => 'P3',
            'Keuangan, Perbankan & Asuransi' => 'P3',
            'Pemasaran' => 'P3',
            'Perpajakan' => 'P3',
            'Agribisnis Peternakan' => 'P3',
            'Agribisnis Pangan' => 'P3',
            'Pengelolaan Agribisnis' => 'P3',
            'Akuntansi Perpajakan' => 'P3',
            'Akuntansi Bisnis Digital' => 'P3',
            'Pengelolaan Perhotelan' => 'P3',

            // P4 - Pemrograman & aplikasi
            'Sistem Informasi' => 'P4',
            'Informatika' => 'P4',
            'Teknik Informatika' => 'P4',
            'Ilmu Komputer' => 'P4',
            'Manajemen Informatika' => 'P4',
            'Manajemen Informatika D3' => 'P4',
            'Sistem Informasi Akuntansi' => 'P4',
            'Sistem Informasi Akuntansi D3' => 'P4',
            'Sistem Komputer' => 'P4',
            'Pendidikan Teknologi Informasi' => 'P4',
            'Pendidikan Teknologi Informasi (PTI)' => 'P4',
            'Teknologi Informasi' => 'P4',
            'Teknologi Rekayasa Internet' => 'P4',
            'Teknologi Rekayasa Internet D4' => 'P4',
            'Teknologi Rekayasa Perangkat Lunak' => 'P4',
            'Teknologi Rekayasa Perangkat Lunak D4' => 'P4',
            'Teknik Komputer' => 'P4',

            // P5 - Data & komputasi
            'Sains Data' => 'P5',
            'Sains Data Terapan' => 'P5',
            'Sains Data Terapan D4' => 'P5',
            'Matematika' => 'P5',
            'Fisika' => 'P5',
            'Teknik Geomatika' => 'P5',
            'Teknik Geofisika' => 'P5',
            'Teknik Telekomunikasi' => 'P5',

            // P6 - Engineering & teknis
            'Teknik Elektro' => 'P6',
            'Teknik Mesin' => 'P6',
            'Teknik Sipil' => 'P6',
            'Teknik Geologi' => 'P6',
            'Teknik Industri' => 'P6',
            'Teknik Kimia' => 'P6',
            'Teknik Lingkungan' => 'P6',
            'Teknik Kelautan' => 'P6',
            'Teknik Perkeretaapian' => 'P6',
            'Teknik Pertambangan' => 'P6',
            'Teknologi Rekayasa Kimia Industri' => 'P6',
            'Teknologi Rekayasa Kimia Industri D4' => 'P6',
            'Teknologi Rekayasa Otomotif' => 'P6',
            'Teknologi Rekayasa Otomotif D4' => 'P6',
            'Teknologi Rekayasa Elektronika' => 'P6',
            'Teknologi Rekayasa Elektronika D4' => 'P6',
            'Teknologi Rekayasa Konstruksi Jalan dan Jembatan' => 'P6',
            'Teknologi Rekayasa Kontruksi Jalan dan Jembatan' => 'P6',
            'Teknologi Rekayasa Konstruksi Jalan dan Jembatan D4' => 'P6',
            'Mekanisasi Pertanian' => 'P6',
            'Mekanisasi Pertanian D3' => 'P6',
            'Teknologi Pangan D3' => 'P6',
            'Teknologi Pangan Halal D4' => 'P6',
            'Teknologi Pangan' => 'P6',
            'Teknologi Produksi Tanaman Pangan' => 'P6',
            'Perencanaan Wilayah dan Kota' => 'P6',
            'Teknologi Pangan Halal' => 'P6',

            // P7 - CAD, arsitektur & 3D
            'Teknik Arsitektur' => 'P7',
            'Arsitektur' => 'P7',
            'Arsitektur Lanskap' => 'P7',
            'Desain Komunikasi Visual' => 'P7',
            'Desain Komunikasi Visual (DKV)' => 'P7',
            'Desain Interior' => 'P7',

            // P8 - Kreatif & media
            'Produksi Media' => 'P8',
            'Produksi Media D4' => 'P8',
            'Ilmu Komunikasi' => 'P8',

            // P9 - Sains & kesehatan
            'Biologi' => 'P9',
            'Kimia' => 'P9',
            'Kimia Terapan' => 'P9',
            'Pendidikan Biologi' => 'P9',
            'Pendidikan Fisika' => 'P9',
            'Pendidikan Kimia' => 'P9',
            'Pendidikan Matematika' => 'P9',
            'Pendidikan Dokter' => 'P9',
            'Keperawatan' => 'P9',
            'Farmasi' => 'P9',
            'Kebidanan' => 'P9',
            'Gizi Klinis' => 'P9',
            'Gizi Klinis D4' => 'P9',
            'Teknik Biomedik' => 'P9',
            'Tasawuf dan Psikoterapi' => 'P9',

            // P10 - Agro, GIS, kelautan & lapangan
            'Agroteknologi' => 'P10',
            'Kehutanan' => 'P10',
            'Teknologi Hasil Pertanian' => 'P10',
            'Teknik Pertanian' => 'P10',
            'Hortikultura' => 'P10',
            'Hortikultura D3' => 'P10',
            'Teknologi Perbenihan' => 'P10',
            'Teknologi Produksi Tanaman Hortikultura' => 'P10',
            'Produksi Tanaman Perkebunan' => 'P10',
            'Produksi Tanaman Perkebunan D3' => 'P10',
            'Produksi dan Manajemen Industri Perkebunan' => 'P10',
            'Pengelolaan Perkebunan Kopi' => 'P10',
            'Teknologi Produksi Tanaman Perkebunan' => 'P10',
            'Pengembangan Produk Agroindustri' => 'P10',
            'Pengembangan Produk Agroindustri D4' => 'P10',
            'Teknik Sumberdaya Lahan dan Lingkungan' => 'P10',
            'Teknik Sumberdaya Lahan dan Lingkungan D3' => 'P10',
            'Budidaya Perikanan' => 'P10',
            'Budidaya Perikanan D3' => 'P10',
            'Perikanan Tangkap' => 'P10',
            'Perikanan Tangkap D3' => 'P10',
            'Teknologi Pembenihan Ikan' => 'P10',
            'Teknologi Pembenihan Ikan D4' => 'P10',
            'Teknologi Akuakultur' => 'P10',
            'Teknologi Akuakultur D4' => 'P10',
            'Teknologi Cerdas Penangkapan Ikan' => 'P10',
            'Teknologi Pakan Ternak' => 'P10',
            'Teknologi Pakan Ternak D4' => 'P10',
            'Teknologi Produksi Ternak' => 'P10',
            'Teknologi Produksi Ternak D4' => 'P10',
        ];


        /*
        |--------------------------------------------------------------------------
        | NORMALISASI NAMA
        |--------------------------------------------------------------------------
        */

        $normalizeName = function ($name) {
            $name = trim($name);

            // Hilangkan karakter bullet/list jika ada
            $name = preg_replace('/^[\-\•\–\—\s]+/u', '', $name);

            // Rapikan spasi
            $name = preg_replace('/\s+/u', ' ', $name);

            return trim($name);
        };


        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA JURUSAN
        |--------------------------------------------------------------------------
        */

        $unmatched = [];
        $updated = 0;

        foreach (Jurusan::all() as $jurusan) {

            $namaAsli = trim($jurusan->nama_jurusan);
            $namaNormal = $normalizeName($namaAsli);

            $profileCode = $mapping[$namaNormal] ?? null;

            // Pencarian case-insensitive sebagai fallback
            if (!$profileCode) {
                foreach ($mapping as $namaMapping => $kode) {
                    if (mb_strtolower($normalizeName($namaMapping))
                        === mb_strtolower($namaNormal)) {
                        $profileCode = $kode;
                        break;
                    }
                }
            }

            if (!$profileCode || !isset($profil[$profileCode])) {
                $unmatched[] = [
                    'id' => $jurusan->id,
                    'nama_jurusan' => $namaAsli,
                ];

                continue;
            }

            $data = $profil[$profileCode];

            $jurusan->harga_min_saran = $data['harga_min_saran'];
            $jurusan->harga_max_saran = $data['harga_max_saran'];
            $jurusan->tingkat_kebutuhan = $data['tingkat_kebutuhan'];
            $jurusan->software_utama = $data['software_utama'];
            $jurusan->profil_perangkat = $data['profil_perangkat'];

            $jurusan->save();

            $updated++;
        }


        /*
        |--------------------------------------------------------------------------
        | HASIL SEEDER
        |--------------------------------------------------------------------------
        */

        $this->command->info("Jumlah jurusan berhasil diperbarui: {$updated}");

        if (!empty($unmatched)) {
            $this->command->warn('Jurusan yang belum memiliki mapping:');

            foreach ($unmatched as $item) {
                $this->command->line(
                    "- ID {$item['id']}: {$item['nama_jurusan']}"
                );
            }
        } else {
            $this->command->info(
                'Semua jurusan berhasil mendapatkan profil kebutuhan laptop.'
            );
        }
    }
}