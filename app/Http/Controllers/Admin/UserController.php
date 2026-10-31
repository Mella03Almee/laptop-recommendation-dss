<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Menampilkan daftar seluruh pengguna sistem (User Profile Cards Grid).
     */
    public function index()
    {
        $title = 'SPK WP | Kelola Pengguna';
        $users = User::all();

        return view('dashboard.user.index', compact('title', 'users'));
    }

    /**
     * Memproses pembaruan data dan foto profil pengguna.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'name'     => $request->name,
            'username' => $request->username,
        ];

        // Jika mengunggah foto profil baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada di dalam storage
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            // Simpan foto baru ke folder storage/app/public/user-photos
            $data['foto'] = $request->file('foto')->store('user-photos', 'public');
        }

        $user->update($data);

        return redirect()->route('data-user.index')->with('success', 'Profil pengguna berhasil diperbarui!');
    }

    /**
     * Menghapus akun pengguna dari database.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Hapus file foto dari storage jika ada
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        $user->delete();

        return redirect()->route('data-user.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}