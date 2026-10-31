<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        // Jika admin ternyata sudah login, langsung lempar ke dashboard, jangan kasih lihat halaman login lagi
        if (Auth::check()) {
            return redirect()->intended('/dashboard');
        }

        $title = 'SPK WP | Login';
        return view('login.index', compact('title'));
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // PERBAIKAN LOGIKA: Jika kamu menggunakan kolom 'username' (bukan email) di database,
        // Auth::attempt akan otomatis mencocokkannya dengan kolom 'username' di tabel users Anda.
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/dashboard');
        }

        // Jika gagal login, kembalikan dengan pesan error
        return back()->with('error', 'Username atau password salah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Setelah logout, lempar ke halaman welcome (akar/root) agar session bersih total
        return redirect('/');
    }
}