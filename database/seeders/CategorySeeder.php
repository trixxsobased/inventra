<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // 9 Jurusan SMKN 1 Jenangan
        Category::create(['name' => 'Rekayasa Perangkat Lunak (RPL)', 'description' => 'Jurusan pengembangan software dan aplikasi']);
        Category::create(['name' => 'Desain Pemodelan dan Informasi Bangunan (DPIB)', 'description' => 'Jurusan desain arsitektur dan teknik sipil']);
        Category::create(['name' => 'Teknik Konstruksi dan Perumahan (TKP)', 'description' => 'Jurusan konstruksi bangunan dan perumahan']);
        Category::create(['name' => 'Teknik Otomasi Industri (TOI)', 'description' => 'Jurusan otomasi dan kontrol industri']);
        Category::create(['name' => 'Teknik Pemesinan (TPM)', 'description' => 'Jurusan teknik mesin dan manufaktur']);
        Category::create(['name' => 'Teknik Pengelasan (TLAS)', 'description' => 'Jurusan pengelasan dan fabrikasi logam']);
        Category::create(['name' => 'Teknik dan Bisnis Sepeda Motor (TBSM)', 'description' => 'Jurusan otomotif sepeda motor']);
        Category::create(['name' => 'Teknik Elektronika Industri (TEI)', 'description' => 'Jurusan elektronika dan instrumentasi']);
        Category::create(['name' => 'Teknik Pendinginan dan Tata Udara (TPTU)', 'description' => 'Jurusan refrigerasi dan AC']);
        
        $this->command->info('9 Categories created successfully');
    }
}
