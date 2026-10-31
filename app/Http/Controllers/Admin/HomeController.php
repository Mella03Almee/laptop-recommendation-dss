<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Saran; 
use App\Models\SubKriteria; 
use App\Services\WeightedProductService;
use Illuminate\Support\Facades\DB; 

class HomeController extends Controller
{
    public function index(WeightedProductService $weightedProduct)
    {
        $title = 'SPK WP | Dashboard';
        list($penilaians, $kriterias) = $weightedProduct->calculate();

        $dataPenilaians = $penilaians->sortBy('nilai_v');
        $formattedUpdatedAt = $dataPenilaians->isEmpty() ? 'Data Kosong' : $dataPenilaians->first()->getFormattedUpdatedAt();

        $dataKriterias = $kriterias;
        $formattedUpdatedAtKriteria = $dataKriterias->first()->getFormattedUpdatedAt();
        $dataKriteriaNama = $dataKriterias->pluck('namaKriteria');
        $dataKriteriaBobot = $dataKriterias->pluck('bobot');
        $dataNilaiV = $dataPenilaians->pluck('nilai_v');
        $dataNamaAlternatif = $dataPenilaians->pluck('alternatif.namaAlternatif');

        $jumlahSaranMasuk = Saran::where('is_read', 0)->count();
        $saranMasukDetail = Saran::where('is_read', 0)->orderBy('created_at', 'desc')->get();

        $sub_kriterias = SubKriteria::all();

        $allBiodata = DB::table('biodatas')->orderBy('created_at', 'desc')->get();
        $allSaran = DB::table('sarans')->orderBy('created_at', 'desc')->get();

        return view('dashboard.index', compact(
            'title', 'dataKriteriaNama', 'dataKriteriaBobot', 'formattedUpdatedAtKriteria',
            'dataNamaAlternatif', 'dataNilaiV', 'formattedUpdatedAt', 'jumlahSaranMasuk', 'saranMasukDetail',
            'sub_kriterias',
            'allBiodata', 
            'allSaran'    
        ));
    }

    public function halamanSaran()
    {
        $title = 'SPK WP | Kelola Saran';
        
        // PERBAIKAN: Mengubah paginate(10) menjadi get() agar seluruh saran masuk ke DataTables
        $allSaran = Saran::orderBy('created_at', 'desc')->get(); 
        
        $jumlahSaranMasuk = Saran::where('is_read', 0)->count();
        $saranMasukDetail = Saran::where('is_read', 0)->orderBy('created_at', 'desc')->get();

        return view('dashboard.saran.index', compact('title', 'allSaran', 'jumlahSaranMasuk', 'saranMasukDetail'));
    }

    // =========================================================================
    // METHOD: Ditentukan oleh rute 'saran.baca'
    // =========================================================================
    public function bacaSaran($id)
    {
        // Cari data saran berdasarkan ID
        $saran = Saran::findOrFail($id);

        // Tandai status sebagai sudah dibaca jika nilainya masih 0
        if ($saran->is_read == 0) {
            $saran->update(['is_read' => 1]);
        }

        // Arahkan kembali ke halaman daftar saran
        return redirect()->route('saran.index')->with('success', 'Saran telah ditandai sebagai dibaca.');
    }

    public function halamanBiodata()
    {
        $title = 'SPK WP | Data Biodata Pengunjung';
        
        // PERBAIKAN: Mengubah paginate(10) menjadi get() agar seluruh data pengunjung ditarik penuh
        $allBiodata = DB::table('biodatas')->orderBy('created_at', 'desc')->get();
        
        $jumlahSaranMasuk = Saran::where('is_read', 0)->count();
        $saranMasukDetail = Saran::where('is_read', 0)->orderBy('created_at', 'desc')->get();

        return view('dashboard.biodata.index', compact('title', 'allBiodata', 'jumlahSaranMasuk', 'saranMasukDetail'));
    }
}