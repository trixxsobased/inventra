<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@smkn1jenangan.sch.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'SMKN 1 Jenangan Ponorogo',
        ]);

        User::create([
            'name' => 'Petugas Inventaris',
            'username' => 'petugas',
            'email' => 'petugas@smkn1jenangan.sch.id',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
            'phone' => '081234567891',
            'address' => 'SMKN 1 Jenangan Ponorogo',
        ]);

        User::create([
            'name' => 'Siswa Demo RPL',
            'username' => 'siswa-rpl',
            'email' => 'rpl@smkn1jenangan.sch.id',
            'password' => Hash::make('siswa123'),
            'role' => 'peminjam',
            'phone' => '081234567892',
            'address' => 'Ponorogo',
        ]);

        User::create([
            'name' => 'Siswa Demo TKJ',
            'username' => 'siswa-tkj',
            'email' => 'tkj@smkn1jenangan.sch.id',
            'password' => Hash::make('siswa123'),
            'role' => 'peminjam',
            'phone' => '081234567893',
            'address' => 'Madiun',
        ]);

        User::create([
            'name' => 'Siswa Demo TPM',
            'username' => 'siswa-tpm',
            'email' => 'tpm@smkn1jenangan.sch.id',
            'password' => Hash::make('siswa123'),
            'role' => 'peminjam',
            'phone' => '081234567894',
            'address' => 'Magetan',
        ]);

        User::create([
            'name' => 'Pak Guru Produktif',
            'username' => 'guru',
            'email' => 'guru@smkn1jenangan.sch.id',
            'password' => Hash::make('guru123'),
            'role' => 'peminjam', // Guru juga meminjam sebagai user biasa
            'phone' => '081234567895',
            'address' => 'Ruang Guru',
        ]);
    }
}
