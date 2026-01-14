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
            'username' => 'siswa',
            'email' => 'siswa@smkn1jenangan.sch.id',
            'password' => Hash::make('siswa123'),
            'role' => 'peminjam',
            'phone' => '081234567892',
            'address' => 'Ponorogo',
        ]);
    }
}
