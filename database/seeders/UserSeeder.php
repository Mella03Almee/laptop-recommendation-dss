<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. Matikan pengaman relasi database dan kosongkan data lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();

        // 2. Suntik data akun admin baru sesuai struktur kolom asli kamu
        DB::table('users')->insert([
            'id'                => 1,
            'name'              => 'Mella Admin',
            'username'          => 'admin', // Menggunakan kolom username sesuai hasil Tinker
            'password'          => Hash::make('admin123'), // Password dienkripsi dengan aman
            'is_admin'          => 1, // Menggunakan kolom is_admin (1 = true / admin)
            'remember_token'    => null,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}