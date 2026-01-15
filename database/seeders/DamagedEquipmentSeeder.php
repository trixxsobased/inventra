<?php

namespace Database\Seeders;

use App\Models\DamagedEquipment;
use App\Models\Equipment;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Database\Seeder;

class DamagedEquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = User::where('role', 'petugas')->first();
        $admin = User::where('role', 'admin')->first();
        
        // Get some borrowings that were returned
        $returnedBorrowings = Borrowing::where('status', 'returned')
            ->whereNotNull('return_condition')
            ->limit(3)
            ->get();
        
        // Create damaged equipment records for rusak berat items
        foreach ($returnedBorrowings as $borrowing) {
            if ($borrowing->return_condition === 'rusak berat') {
                DamagedEquipment::create([
                    'equipment_id' => $borrowing->equipment_id,
                    'borrowing_id' => $borrowing->id,
                    'reported_by' => $petugas->id,
                    'reported_at' => $borrowing->actual_return_date,
                    'damage_description' => $this->getDamageDescription($borrowing->equipment->category->name),
                    'resolution_status' => 'pending',
                    'resolution_notes' => null,
                ]);
            }
        }
        
        // Add some older damaged equipment without borrowing reference
        $damagedEquipments = Equipment::where('condition', 'rusak berat')
            ->whereNotIn('id', DamagedEquipment::pluck('equipment_id'))
            ->limit(2)
            ->get();
        
        foreach ($damagedEquipments as $equipment) {
            DamagedEquipment::create([
                'equipment_id' => $equipment->id,
                'borrowing_id' => null,
                'reported_by' => $admin->id,
                'reported_at' => now()->subDays(rand(7, 30)),
                'damage_description' => $this->getManualDamageDescription($equipment->name),
                'resolution_status' => rand(0, 1) ? 'pending' : 'replaced',
                'resolution_notes' => rand(0, 1) ? null : 'Sudah diajukan pengadaan pengganti',
            ]);
        }
        
        $count = DamagedEquipment::count();
        $this->command->info("✓ Created {$count} Damaged Equipment records");
    }
    
    private function getDamageDescription(string $categoryName): string
    {
        $descriptions = [
            'Rekayasa Perangkat Lunak (RPL)' => 'Motherboard mati total, tidak bisa boot. LCD retak. Keyboard beberapa tombol tidak berfungsi.',
            'Teknik Komputer dan Jaringan (TKJ)' => 'Port RJ45 rusak, crimping tool patah pada bagian pisau pemotong kabel.',
            'Multimedia' => 'Lensa kamera retak, body tergores parah, shutter button macet.',
            'Broadcasting' => 'Diafragma microphone rusak, kabel input putus, stand holder patah.',
            'Teknik Otomotif' => 'Tang ampere tidak akurat lagi, digital display mati.',
        ];
        
        return $descriptions[$categoryName] ?? 'Kerusakan parah pada komponen utama, tidak dapat diperbaiki.';
    }
    
    private function getManualDamageDescription(string $equipmentName): string
    {
        $generic = [
            'Ditemukan rusak saat stock opname tahunan.',
            'Rusak akibat pemakaian berkepanjangan, sudah melewati masa pakai.',
            'Kerusakan tidak diketahui penyebabnya, ditemukan saat pengecekan rutin.',
            'Komponen internal rusak, biaya perbaikan melebihi harga barang baru.',
        ];
        
        return $generic[array_rand($generic)];
    }
}
