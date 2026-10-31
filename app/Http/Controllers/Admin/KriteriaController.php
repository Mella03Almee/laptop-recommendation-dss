<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use Illuminate\Http\Request; 
use Illuminate\Support\Str;   

class KriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'SPK WP | Data Kriteria';
        
        // Mengambil semua data tanpa filter user_id agar muncul semua
        $kriterias = Kriteria::all(); 

        return view('dashboard.kriteria.index', compact('title', 'kriterias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kodeKriteria' => 'required|unique:kriterias,kodeKriteria',
            'namaKriteria' => 'required',
            'bobot'        => 'required|numeric',
            'jenis'        => 'required',
        ]);

        // Membuat slug otomatis dari nama kriteria
        $validatedData['slug'] = Str::slug($request->namaKriteria);
        
        // Set null karena kriteria sekarang bersifat global (Admin yang buat)
        $validatedData['user_id'] = null; 

        Kriteria::create($validatedData);

        return redirect()->back()->with('success', 'Kriteria baru berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kriteria  $data_kriterium
     * @return \Illuminate\Http\Response
     */
    public function edit(Kriteria $data_kriterium)
    {
        $title = 'SPK WP | Ubah Data Kriteria';
        $kriteria = $data_kriterium;

        return view('dashboard.kriteria.edit', compact('title', 'kriteria'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kriteria  $data_kriterium
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kriteria $data_kriterium)
    {
        $validatedData = $request->validate([
            'kodeKriteria' => 'required|unique:kriterias,kodeKriteria,' . $data_kriterium->id,
            'namaKriteria' => 'required',
            'bobot'        => 'required|numeric',
            'jenis'        => 'required',
        ]);

        // Ambil nilai slug dari input form (yang digenerate oleh JS)
        $validatedData['slug'] = $request->slug;
        $validatedData['user_id'] = null; // Tetap global untuk admin

        // Eksekusi update data ke database
        $data_kriterium->update($validatedData);

        return redirect('/dashboard/data-kriteria')->with('success', 'Data Kriteria telah berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kriteria  $data_kriterium
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kriteria $data_kriterium)
    {
        $data_kriterium->delete();
        return redirect()->back()->with('success', 'Kriteria berhasil dihapus!');
    }
}