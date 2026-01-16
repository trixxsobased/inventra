<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // === ADMIN ===
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@smkn1jenangan.sch.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'SMKN 1 Jenangan Ponorogo',
        ]);

        // === PETUGAS ===
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
            'name' => 'Bu Sari Laboran',
            'username' => 'laboran',
            'email' => 'laboran@smkn1jenangan.sch.id',
            'password' => Hash::make('petugas123'),
            'role' => 'petugas',
            'phone' => '081234567896',
            'address' => 'Lab Komputer Lantai 2',
        ]);

        // === GURU (Peminjam) ===
        User::create([
            'name' => 'Pak Budi Produktif RPL',
            'username' => 'guru-rpl',
            'email' => 'budi.rpl@smkn1jenangan.sch.id',
            'password' => Hash::make('guru123'),
            'role' => 'peminjam',
            'phone' => '081234567895',
            'address' => 'Ruang Guru',
        ]);

        User::create([
            'name' => 'Bu Ani Produktif TKJ',
            'username' => 'guru-tkj',
            'email' => 'ani.tkj@smkn1jenangan.sch.id',
            'password' => Hash::make('guru123'),
            'role' => 'peminjam',
            'phone' => '081234567897',
            'address' => 'Ruang Guru',
        ]);

        // === SISWA RPL ===
        $rplStudents = [
            ['Andi Prasetyo', 'andi.rpl', 'andi@siswa.smkn1jenangan.sch.id', 'Ponorogo'],
            ['Bima Saputra', 'bima.rpl', 'bima@siswa.smkn1jenangan.sch.id', 'Madiun'],
            ['Citra Dewi', 'citra.rpl', 'citra@siswa.smkn1jenangan.sch.id', 'Ngawi'],
            ['Dimas Kurniawan', 'dimas.rpl', 'dimas@siswa.smkn1jenangan.sch.id', 'Ponorogo'],
        ];

        foreach ($rplStudents as $i => $student) {
            User::create([
                'name' => $student[0],
                'username' => $student[1],
                'email' => $student[2],
                'password' => Hash::make('siswa123'),
                'role' => 'peminjam',
                'phone' => '08521000010' . $i,
                'address' => $student[3],
            ]);
        }

        // === SISWA TKJ ===
        $tkjStudents = [
            ['Eka Putri', 'eka.tkj', 'eka@siswa.smkn1jenangan.sch.id', 'Trenggalek'],
            ['Fajar Rahman', 'fajar.tkj', 'fajar@siswa.smkn1jenangan.sch.id', 'Ponorogo'],
            ['Galang Permana', 'galang.tkj', 'galang@siswa.smkn1jenangan.sch.id', 'Magetan'],
        ];

        foreach ($tkjStudents as $i => $student) {
            User::create([
                'name' => $student[0],
                'username' => $student[1],
                'email' => $student[2],
                'password' => Hash::make('siswa123'),
                'role' => 'peminjam',
                'phone' => '08521000020' . $i,
                'address' => $student[3],
            ]);
        }

        // === SISWA TEKNIK MESIN ===
        $tpStudents = [
            ['Hendra Wijaya', 'hendra.tp', 'hendra@siswa.smkn1jenangan.sch.id', 'Madiun'],
            ['Irfan Maulana', 'irfan.tp', 'irfan@siswa.smkn1jenangan.sch.id', 'Ponorogo'],
        ];

        foreach ($tpStudents as $i => $student) {
            User::create([
                'name' => $student[0],
                'username' => $student[1],
                'email' => $student[2],
                'password' => Hash::make('siswa123'),
                'role' => 'peminjam',
                'phone' => '08521000030' . $i,
                'address' => $student[3],
            ]);
        }

        // === SISWA LAINNYA ===
        $otherStudents = [
            ['Joko Susanto', 'joko.tei', 'joko@siswa.smkn1jenangan.sch.id', 'Pacitan'],
            ['Kirana Sari', 'kirana.dpib', 'kirana@siswa.smkn1jenangan.sch.id', 'Ponorogo'],
            ['Lukman Hakim', 'lukman.tbsm', 'lukman@siswa.smkn1jenangan.sch.id', 'Nganjuk'],
        ];

        foreach ($otherStudents as $i => $student) {
            User::create([
                'name' => $student[0],
                'username' => $student[1],
                'email' => $student[2],
                'password' => Hash::make('siswa123'),
                'role' => 'peminjam',
                'phone' => '08521000040' . $i,
                'address' => $student[3],
            ]);
        }

        $this->command->info('✓ Created 16 users (1 admin, 2 petugas, 2 guru, 11 siswa)');
    }
}
