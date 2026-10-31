<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubKriteria;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SubKriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager loading relasi SubKriteria yang ada di model Kriteria
        $kriterias = Kriteria::with('SubKriteria')->get(); 

        return view('dashboard.subKriteria.index', [
            'kriterias' => $kriterias,
            'title'     => 'Data Sub Kriteria'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi disesuaikan dengan atribut 'name' di file Blade (harga & nHarga)
        $request->validate([
            'kriteria_id' => 'required|exists:kriterias,id',
            'harga'       => 'required|string|max:255',
            'nHarga'      => 'required|integer|between:1,5',
        ]);

        // 2. Simpan menggunakan DB Insert atau Model Eloquent secara langsung demi keamanan relasi
        DB::table('sub_kriterias')->insert([
            'kriteria_id' => $request->kriteria_id,
            'harga'       => $request->harga, // Menampung range deskripsi sub (misal: 8 GB)
            'nHarga'      => $request->nHarga, // Menampung nilai bobot angka (1-5)
            'slug'        => Str::slug($request->harga) . '-' . rand(10, 99),
            'user_id'     => auth()->id(),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // 3. Notifikasi Sukses
        alert()->success('Tambah Data Sukses!', 'Data Sub Kriteria baru telah ditambahkan.');
        
        return redirect()->route('data-sub-kriteria.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi data update dari form modal edit
        $request->validate([
            'harga'  => 'required|string|max:255',
            'nHarga' => 'required|integer|between:1,5',
        ]);

        // 2. Lakukan update langsung berbasis ID target
        DB::table('sub_kriterias')->where('id', $id)->update([
            'harga'      => $request->harga,
            'nHarga'     => $request->nHarga,
            'slug'       => Str::slug($request->harga) . '-' . rand(10, 99),
            'user_id'    => auth()->id(),
            'updated_at' => now(),
        ]);

        alert()->success('Ubah Data Sukses!', 'Data Sub Kriteria telah diubah.');

        return redirect()->route('data-sub-kriteria.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // MENAMBAHKAN FUNGSI HAPUS YANG SEBELUMNYA HILANG
        DB::table('sub_kriterias')->where('id', $id)->delete();

        alert()->success('Hapus Data Sukses!', 'Data Sub Kriteria telah dihapus dari sistem.');

        return redirect()->route('data-sub-kriteria.index');
    }
}