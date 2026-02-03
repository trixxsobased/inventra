<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Superadmin - ID 1 (satu-satunya admin)
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@smkn1jenangan.sch.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'SMKN 1 Jenangan, Jl. Raya Jenangan No.1, Ponorogo',
        ]);

        // Petugas Lab/Inventaris
        User::create([
            'name' => 'Budi Santoso',
            'username' => 'petugas',
            'email' => 'petugas@smkn1jenangan.sch.id',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
            'phone' => '081234567891',
            'address' => 'Ruang Inventaris, SMKN 1 Jenangan',
        ]);

        // Create students for each major
        $majors = ['RPL', 'TPM', 'TOI', 'TEI', 'DPIB', 'TLAS', 'TPTU', 'TKP', 'TSM'];
        
        foreach ($majors as $index => $major) {
            User::create([
                'name' => "Siswa {$major}",
                'username' => strtolower("siswa-{$major}"),
                'email' => strtolower("{$major}@smkn1jenangan.sch.id"),
                'password' => Hash::make('siswa123'),
                'role' => 'peminjam',
                'phone' => '0812345678' . str_pad((string)$index, 2, '0', STR_PAD_LEFT),
                'address' => "Jl. Raya {$major} No. " . ($index + 1),
            ]);
        }

        // Peminjam - Guru
        User::create([
            'name' => 'Pak Wahyu Hidayat, S.Kom',
            'username' => 'guru',
            'email' => 'guru@smkn1jenangan.sch.id',
            'password' => Hash::make('guru123'),
            'role' => 'peminjam',
            'phone' => '081234567895',
            'address' => 'Ruang Guru SMKN 1 Jenangan, Ponorogo',
        ]);
    }
}
