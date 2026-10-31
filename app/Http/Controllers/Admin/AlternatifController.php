<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alternatif;
use App\Http\Requests\UpdateAlternatifRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AlternatifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'SPK WP | Data Alternatif';
        // Mengambil alternatif milik user yang sedang login saja
        $alternatifs = Alternatif::where('user_id', auth()->user()->id)->get();

        return view('dashboard.alternatif.index', compact('title', 'alternatifs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kodeAlternatif' => 'required|unique:alternatifs,kodeAlternatif',
            'namaAlternatif' => 'required',
            'slug'           => 'required|unique:alternatifs,slug'
        ]);

        $validatedData['user_id'] = auth()->user()->id; 

        Alternatif::create($validatedData);

        alert()->success('Buat Data Sukses!', 'Data Alternatif telah ditambahkan.');

        return redirect()->route('data-alternatif.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $title = 'SPK WP | Ubah Data Alternatif';
        
        // Cari data alternatif berdasarkan ID secara manual agar tidak 404
        $alternatif = Alternatif::findOrFail($id);

        return view('dashboard.alternatif.edit', compact('title', 'alternatif'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlternatifRequest $request, $id)
    {
        // Cari data yang mau diubah berdasarkan ID
        $alternatif = Alternatif::findOrFail($id);

        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->user()->id;

        // Jalankan update
        $alternatif->update($validatedData);

        alert()->success('Ubah Data Sukses!', 'Data Alternatif telah diubah.');

        return redirect()->route('data-alternatif.index');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alternatif $alternatif)
    {
        $alternatif->delete();

        alert()->success('Hapus Data Sukses!', 'Data Alternatif telah dihapus.');

        return redirect()->route('data-alternatif.index');
    }
}