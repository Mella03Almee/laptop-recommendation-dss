<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use App\Models\Alternatif;
use App\Models\Kriteria;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    /**
     * Menampilkan halaman index matriks penilaian
     */
    public function index()
    {
        $title = 'SPK WP | Data Penilaian';
        $userId = auth()->user()->id;
        
        $alternatifs = Alternatif::where('user_id', $userId)->get();
        // Batasi kriteria maksimal 9 saja agar tidak error C10_val
        $kriterias = Kriteria::with('SubKriteria')->take(9)->get(); 
        $penilaians = Penilaian::where('user_id', $userId)->get(); 

        return view('dashboard.penilaian.index', compact('title', 'alternatifs', 'kriterias', 'penilaians'));
    }

    /**
     * Dialihkan ke index
     */
    public function create()
    {
        return redirect()->route('data-penilaian.index');
    }

    /**
     * Menyimpan data penilaian baru ke database
     */
    public function store(Request $request)
    {
        $userId = auth()->id();
        // Ambil maksimal 9 kriteria saja
        $kriterias = Kriteria::take(9)->get();

        // 1. Buat aturan validasi dinamis (C1x - C9x)
        $rules = [
            'alternatif_id' => 'required|exists:alternatifs,id',
        ];

        foreach ($kriterias as $index => $kriteria) {
            $rules['C' . ($index + 1) . 'x'] = 'required|integer';
        }

        $request->validate($rules);

        // Cek duplikasi agar 1 laptop hanya punya 1 baris penilaian
        $cekPenilaian = Penilaian::where('alternatif_id', $request->alternatif_id)->where('user_id', $userId)->first();
        if ($cekPenilaian) {
            alert()->error('Gagal!', 'Laptop ini sudah dinilai. Sila gunakan fitur ubah nilai.');
            return redirect()->back();
        }

        // 2. Siapkan data untuk insert
        $dataInsert = [
            'user_id'       => $userId,
            'alternatif_id' => $request->alternatif_id,
            'slug'          => 'penilaian-' . $request->alternatif_id . '-' . rand(100, 999),
        ];

        // 3. LOGIKA HITUNG VEKTOR S
        $nilai_s = 1.0; 

        foreach ($kriterias as $index => $kriteria) {
            $fieldName = 'C' . ($index + 1) . 'x';
            $nilaiInput = (int) $request->$fieldName;

            $dataInsert[$fieldName] = $nilaiInput;

            $bobotKriteria = $kriteria->bobot; 
            if (strtolower($kriteria->tipe) == 'cost') {
                $bobotKriteria = -$bobotKriteria;
            }

            $nilai_s *= pow($nilaiInput, $bobotKriteria);
        }

        $dataInsert['nilai_s'] = $nilai_s;
        $dataInsert['nilai_v'] = 0;

        // 4. Eksekusi simpan ke database
        Penilaian::create($dataInsert);

        alert()->success('Buat Data Sukses!', 'Data Penilaian berhasil disimpan ke database.');
        return redirect()->route('data-penilaian.index');
    }

    /**
     * Dialihkan ke index
     */
    public function edit($id)
    {
        return redirect()->route('data-penilaian.index');
    }

    /**
     * Memperbarui data penilaian di database
     */
    public function update(Request $request, $id)
    {
        $penilaian = Penilaian::where('user_id', auth()->id())->findOrFail($id);
        // Ambil maksimal 9 kriteria saja
        $kriterias = Kriteria::take(9)->get();

        // 1. Validasi dinamis (C1x - C9x)
        $rules = [];
        foreach ($kriterias as $index => $kriteria) {
            $rules['C' . ($index + 1) . 'x'] = 'required|integer';
        }
        $request->validate($rules);

        // 2. Siapkan data update dan hitung ulang Vektor S
        $dataUpdate = [];
        $dataUpdate['slug'] = 'penilaian-' . $id . '-' . rand(100, 999);
        $nilai_s = 1.0;

        foreach ($kriterias as $index => $kriteria) {
            $fieldName = 'C' . ($index + 1) . 'x';
            $nilaiInput = (int) $request->$fieldName;

            $dataUpdate[$fieldName] = $nilaiInput;

            $bobotKriteria = $kriteria->bobot;
            if (strtolower($kriteria->tipe) == 'cost') {
                $bobotKriteria = -$bobotKriteria;
            }

            $nilai_s *= pow($nilaiInput, $bobotKriteria);
        }

        $dataUpdate['nilai_s'] = $nilai_s;

        // 3. Eksekusi update database
        $penilaian->update($dataUpdate);
        
        alert()->success('Berhasil!', 'Nilai kriteria laptop berhasil diperbarui.');
        return redirect()->route('data-penilaian.index');
    }
}