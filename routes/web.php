<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

// KELOMPOK FRONT / PUBLIK
use App\Http\Controllers\Front\RekomendasiController;
use App\Http\Controllers\Front\ChatbotController;

// MODEL UNTUK ROUTE AJAX DROPDOWN
use App\Models\Fakultas;
use App\Models\Jurusan;

// KELOMPOK ADMIN / DASHBOARD
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\KriteriaController;
use App\Http\Controllers\Admin\SubKriteriaController;
use App\Http\Controllers\Admin\AlternatifController;
use App\Http\Controllers\Admin\PenilaianController;
use App\Http\Controllers\Admin\DataHasilAkhirController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UniversitasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama memanggil form kriteria untuk user publik
Route::get('/', [RekomendasiController::class, 'index'])->name('rekomendasi.index');

// =========================================================================
// ROUTE AJAX DROPDOWN BERTINGKAT (Kampus -> Fakultas -> Jurusan)
// =========================================================================
Route::get('/get-fakultas/{universitas_id}', function ($universitas_id) {
    return response()->json(Fakultas::where('universitas_id', $universitas_id)->get());
});

Route::get('/get-jurusan/{fakultas_id}', function ($fakultas_id) {
    return response()->json(Jurusan::with('kategoriBobot')->where('fakultas_id', $fakultas_id)->get());
});

// Rute darurat agar jika sistem mencari rute cover, langsung dilempar ke dashboard
Route::get('/cover', function () {
    return redirect('/dashboard');
})->name('cover');

// Halaman untuk memproses hitungan WP setelah user menekan tombol "Cari"
Route::post('/hitung-rekomendasi', [RekomendasiController::class, 'hitung'])->name('rekomendasi.hitung');

// Rute darurat jika sistem mencari rute register secara manual
Route::get('/register', function () {
    return redirect('/login');
})->name('register');

// Proses untuk menampung data saran dari halaman depan publik
Route::post('/kirim-saran', [RekomendasiController::class, 'simpanSaran'])->name('saran.simpan');

// =========================================================================
// RUTE CHATBOT PUBLIK (Bisa diakses SIAPA SAJA tanpa login)
// =========================================================================
Route::post('/chatbot/tanya', [ChatbotController::class, 'tanya'])->name('chatbot.tanya');

// Menggunakan Route::match agar mendukung GET maupun POST saat logout
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');


// AREA OTENTIKASI GUEST (BELUM LOGIN)
Route::middleware('guest')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'index')->name('login');
        Route::post('/login', 'authenticate');
    });
});


// AREA DASHBOARD (WAJIB LOGIN)
Route::group(['middleware' => 'auth', 'prefix' => 'dashboard'], function () {
    Route::resource('universitas', UniversitasController::class);

    // 2. Data Fakultas (Bertingkat di dalam Universitas)
    Route::post('universitas/{universitas}/fakultas', [UniversitasController::class, 'storeFakultas'])->name('fakultas.store');
    Route::put('fakultas/{id}', [UniversitasController::class, 'updateFakultas'])->name('fakultas.update');
    Route::delete('fakultas/{id}', [UniversitasController::class, 'destroyFakultas'])->name('fakultas.destroy');

    // 3. Data Jurusan (Bertingkat di dalam Fakultas)
    Route::post('fakultas/{fakultas}/jurusan', [UniversitasController::class, 'storeJurusan'])->name('jurusan.store');
    Route::put('jurusan/{id}', [UniversitasController::class, 'updateJurusan'])->name('jurusan.update');
    Route::delete('jurusan/{id}', [UniversitasController::class, 'destroyJurusan'])->name('jurusan.destroy');

    // Rute utama Dashboard
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/main', [HomeController::class, 'index'])->name('Dashboard');

    // Rute halaman khusus terpisah untuk manajemen interaksi publik
    Route::get('/saran', [HomeController::class, 'halamanSaran'])->name('saran.index');
    Route::get('/biodata', [HomeController::class, 'halamanBiodata'])->name('biodata.index');

    // Mengarahkan rute memproses klik baca saran ke HomeController
    Route::get('/saran/baca/{id}', [HomeController::class, 'bacaSaran'])->name('saran.baca');

    // PERBAIKAN: Manajemen Kriteria dikunci hanya index, edit, dan update (Tidak bisa tambah/hapus kriteria)
    Route::resource('data-kriteria', KriteriaController::class)->only(['index', 'edit', 'update']);
    
    // Manajemen Sub Kriteria
    Route::resource('data-sub-kriteria', SubKriteriaController::class);
    
    // Manajemen Alternatif dan Penilaian Laptop
    Route::resource('data-alternatif', AlternatifController::class)->except(['show']);
    Route::resource('data-penilaian', PenilaianController::class)->except(['show', 'destroy']);

    // Cetak Perhitungan Akhir WP
    Route::controller(DataHasilAkhirController::class)->group(function () {
        Route::get('/data-hasil-akhir', 'index')->name('data-hasil-akhir.index');
        Route::get('/data-hasil-akhir/print', 'printDataHasil')->name('data-hasil-akhir.result');
    });

    // Manajemen User Pengelola (Hanya untuk Admin) - Ditambahkan 'update'
    Route::resource('data-user', UserController::class)->only(['index', 'update'])->middleware('admin');
});