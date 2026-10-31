<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Universitas;
use App\Models\Fakultas;
use App\Models\Jurusan;
use App\Models\KategoriBobot;
use Illuminate\Http\Request;

class UniversitasController extends Controller
{
    /**
     * Tampilan Utama: Menampilkan Semua Universitas
     */
    public function index()
    {
        $title = 'SPK WP | Data Akademik Kampus';
        $universitas = Universitas::withCount('fakultas')->get();

        return view('dashboard.akademik.index', compact('title', 'universitas'));
    }

    /**
     * Simpan Universitas Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_universitas' => 'required|string|max:255|unique:universitas,nama_universitas',
            'singkatan'        => 'required|string|max:50',
        ]);

        Universitas::create([
            'nama_universitas' => $request->nama_universitas,
            'singkatan'        => $request->singkatan,
        ]);

        alert()->success('Berhasil!', 'Data Universitas berhasil ditambahkan.');
        return redirect()->back();
    }

    /**
     * Detail Universitas: Kelola Fakultas & Jurusan
     */
    public function show($id)
    {
        $title = 'SPK WP | Kelola Fakultas & Jurusan';
        $universitas = Universitas::with(['fakultas.jurusan.kategoriBobot'])->findOrFail($id);
        $kategoriBobots = KategoriBobot::all();

        return view('dashboard.akademik.show', compact('title', 'universitas', 'kategoriBobots'));
    }

    /**
     * Update Data Universitas
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_universitas' => 'required|string|max:255',
            'singkatan'        => 'required|string|max:50',
        ]);

        $univ = Universitas::findOrFail($id);
        $univ->update([
            'nama_universitas' => $request->nama_universitas,
            'singkatan'        => $request->singkatan,
        ]);

        alert()->success('Berhasil!', 'Nama Universitas berhasil diperbarui.');
        return redirect()->back();
    }

    /**
     * Hapus Universitas
     */
    public function destroy($id)
    {
        $univ = Universitas::findOrFail($id);
        $univ->delete();

        alert()->success('Berhasil!', 'Data Universitas beserta fakultasnya telah dihapus.');
        return redirect()->route('universitas.index');
    }

    // ==========================================
    // LOGIKA KELOLA FAKULTAS (BULK INSERT)
    // ==========================================

    public function storeFakultas(Request $request, $universitas_id)
    {
        $request->validate([
            'nama_fakultas' => 'required|string',
        ]);

        $daftarFakultas = preg_split('/[\n,]+/', $request->nama_fakultas);

        $dataInsert = [];
        foreach ($daftarFakultas as $nama) {
            $namaClean = trim($nama);
            if (!empty($namaClean)) {
                $dataInsert[] = [
                    'universitas_id' => $universitas_id,
                    'nama_fakultas'  => $namaClean,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }
        }

        if (count($dataInsert) > 0) {
            Fakultas::insert($dataInsert);
            alert()->success('Berhasil!', count($dataInsert) . ' Fakultas sekaligus berhasil ditambahkan.');
        }

        return redirect()->back();
    }

    public function updateFakultas(Request $request, $id)
    {
        $request->validate([
            'nama_fakultas' => 'required|string|max:255',
        ]);

        $fakultas = Fakultas::findOrFail($id);
        $fakultas->update(['nama_fakultas' => $request->nama_fakultas]);

        alert()->success('Berhasil!', 'Nama Fakultas berhasil diperbarui.');
        return redirect()->back();
    }

    public function destroyFakultas($id)
    {
        $fakultas = Fakultas::findOrFail($id);
        $fakultas->delete();

        alert()->success('Berhasil!', 'Fakultas berhasil dihapus.');
        return redirect()->back();
    }

    // ==========================================
    // LOGIKA KELOLA JURUSAN (BULK INSERT)
    // ==========================================

    public function storeJurusan(Request $request, $fakultas_id)
    {
        // PERBAIKAN: Mengubah exists:kategori_bobots,id menjadi exists:kategori_bobot,id
        $request->validate([
            'nama_jurusan'      => 'required|string',
            'kategori_bobot_id' => 'required|exists:kategori_bobot,id',
            'jenjang'           => 'nullable|string|max:10',
        ]);

        $daftarJurusan = preg_split('/[\n,]+/', $request->nama_jurusan);

        $dataInsert = [];
        foreach ($daftarJurusan as $nama) {
            $namaClean = trim($nama);
            if (!empty($namaClean)) {
                $dataInsert[] = [
                    'fakultas_id'       => $fakultas_id,
                    'nama_jurusan'      => $namaClean,
                    'kategori_bobot_id' => $request->kategori_bobot_id,
                    'jenjang'           => $request->jenjang ?? 'S1',
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];
            }
        }

        if (count($dataInsert) > 0) {
            Jurusan::insert($dataInsert);
            alert()->success('Berhasil!', count($dataInsert) . ' Jurusan sekaligus berhasil ditambahkan.');
        }

        return redirect()->back();
    }

    public function updateJurusan(Request $request, $id)
    {
        // PERBAIKAN: Mengubah exists:kategori_bobots,id menjadi exists:kategori_bobot,id
        $request->validate([
            'nama_jurusan'      => 'required|string|max:255',
            'kategori_bobot_id' => 'required|exists:kategori_bobot,id',
            'jenjang'           => 'nullable|string|max:10',
        ]);

        $jurusan = Jurusan::findOrFail($id);
        $jurusan->update([
            'nama_jurusan'      => $request->nama_jurusan,
            'kategori_bobot_id' => $request->kategori_bobot_id,
            'jenjang'           => $request->jenjang ?? 'S1',
        ]);

        alert()->success('Berhasil!', 'Data Jurusan berhasil diperbarui.');
        return redirect()->back();
    }

    public function destroyJurusan($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        $jurusan->delete();

        alert()->success('Berhasil!', 'Jurusan berhasil dihapus.');
        return redirect()->back();
    }
}