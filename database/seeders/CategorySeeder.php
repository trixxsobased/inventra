<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Rekayasa Perangkat Lunak (RPL)',
                'description' => 'Jurusan pengembangan software dan aplikasi'
            ],
            [
                'name' => 'Teknik Pemesinan (TP)',
                'description' => 'Jurusan teknik mesin dan manufaktur'
            ],
            [
                'name' => 'Teknik Pengelasan (TPL)',
                'description' => 'Jurusan teknik pengelasan logam'
            ],
            [
                'name' => 'Teknik Otomasi Industri (TOI)',
                'description' => 'Jurusan otomasi dan kontrol industri'
            ],
            [
                'name' => 'Teknik Pendingin & Tata Udara (TPTU)',
                'description' => 'Jurusan sistem refrigerasi dan AC'
            ],
            [
                'name' => 'Desain Pemodelan & Informasi Bangunan (DPIB)',
                'description' => 'Jurusan desain arsitektur dan sipil'
            ],
            [
                'name' => 'Bisnis Konstruksi & Properti (BKP)',
                'description' => 'Jurusan manajemen konstruksi'
            ],
            [
                'name' => 'Teknik Elektronika Industri (TEI)',
                'description' => 'Jurusan elektronika dan instrumentasi'
            ],
            [
                'name' => 'Teknik & Bisnis Sepeda Motor (TBSM)',
                'description' => 'Jurusan otomotif sepeda motor'
            ],
        ];

        foreach ($departments as $dept) {
            Category::create($dept);
        }
    }
}
