<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternatif;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Penilaian;

class DataHasilAkhirController extends Controller
{
    public function index()
    {
        // Tambahkan with('alternatif') agar teks spek laptop ikut ditarik
        $penilaians = Penilaian::with('alternatif')
            ->where('user_id', auth()->user()->id)
            ->get();
            
        $alternatifs = Alternatif::where('user_id', auth()->user()->id)->get();
        $title = 'SPK WP | Data Hasil akhir';

        // Urutkan berdasarkan nilai_v tertinggi
        $rankingWP = $penilaians->sortByDesc('nilai_v');

        return view('dashboard.hasilAkhir.index', compact('title', 'rankingWP'));
    }

    public function printDataHasil()
    {
        // Tambahkan with('alternatif') juga di sini untuk cetak PDF
        $rangkingWP = Penilaian::with('alternatif')
            ->where('user_id', auth()->user()->id)
            ->get()
            ->sortByDesc('nilai_v');

        $pdf = PDF::loadview('dashboard.hasilAkhir.printDataHasil', ['penilaians' => $rangkingWP])
            ->setPaper('a4', 'landscape');

        return $pdf->stream();
    }
}