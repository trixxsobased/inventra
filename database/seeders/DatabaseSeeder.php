<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,                    // Admin, Petugas, Sample Student
            CategorySeeder::class,                // 9 Jurusan SMKN 1 Jenangan
            EquipmentSeeder::class,               // Professional Equipment per Department
            BorrowingSeeder::class,               // Demo borrowing scenarios (CRITICAL FOR DEMO!)
            DamagedEquipmentSeeder::class,        // Damaged items tracking
            PurchaseRequisitionSeeder::class,     // Purchase requisitions for replacement
            ActivityLogSeeder::class,             // Activity log demo data
        ]);
    }
}
