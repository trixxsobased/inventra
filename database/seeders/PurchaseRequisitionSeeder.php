<?php

namespace Database\Seeders;

use App\Models\PurchaseRequisition;
use App\Models\User;
use App\Models\Category;
use App\Models\Equipment;
use Illuminate\Database\Seeder;

class PurchaseRequisitionSeeder extends Seeder
{
    public function run(): void
    {
        // Use admin since we no longer have petugas user by default
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->command->warn('⚠️ No admin user found. Run UserSeeder first.');
            return;
        }
        
        $categories = Category::all();
        $equipment = Equipment::where('condition', 'rusak berat')->first();
        
        // Requisition 1: URGENT - Pengganti laptop rusak berat
        PurchaseRequisition::create([
            'category_id' => $categories->where('name', 'Rekayasa Perangkat Lunak (RPL)')->first()->id ?? 1,
            'equipment_id' => $equipment?->id,
            'requested_by' => $admin->id,
            'item_name' => 'Laptop ASUS ROG Strix G15',
            'quantity' => 2,
            'estimated_price' => 15000000,
            'reason' => 'replacement',
            'justification' => 'Mengganti 2 unit laptop yang rusak berat akibat kerusakan motherboard pada peminjaman terakhir. Laptop diperlukan untuk praktik programming dan UKK RPL tahun 2026.',
            'priority' => 'urgent',
            'status' => 'pending',
        ]);

        // Requisition 2: High Priority - Penambahan stok
        PurchaseRequisition::create([
            'category_id' => $categories->where('name', 'Teknik Komputer dan Jaringan (TKJ)')->first()->id ?? 2,
            'requested_by' => $admin->id,
            'item_name' => 'Toolkit Jaringan Professional',
            'quantity' => 5,
            'estimated_price' => 1500000,
            'reason' => 'new_stock',
            'justification' => 'Menambah stok toolkit untuk praktik instalasi kabel jaringan. Toolkit yang ada sudah tidak layak dan beberapa tools hilang.',
            'priority' => 'high',
            'status' => 'pending',
        ]);

        // Requisition 3: Approved - Ekspansi lab
        PurchaseRequisition::create([
            'category_id' => $categories->where('name', 'Multimedia')->first()->id ?? 3,
            'requested_by' => $admin->id,
            'item_name' => 'Kamera DSLR Canon EOS 90D',
            'quantity' => 1,
            'estimated_price' => 18000000,
            'reason' => 'expansion',
            'justification' => 'Pengembangan studio multimedia untuk praktik videografi siswa. Kamera diperlukan untuk meningkatkan kualitas hasil karya siswa.',
            'priority' => 'medium',
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'review_notes' => 'Disetujui untuk pengadaan tahun anggaran 2026. Koordinasi dengan bendahara untuk pencairan dana.',
        ]);

        // Requisition 4: Rejected
        PurchaseRequisition::create([
            'category_id' => $categories->where('name', 'Teknik Otomotif')->first()->id ?? 4,
            'requested_by' => $admin->id,
            'item_name' => 'Mesin Bubut CNC Mini',
            'quantity' => 1,
            'estimated_price' => 35000000,
            'reason' => 'expansion',
            'justification' => 'Untuk ekspansi praktik pemesinan siswa otomotif.',
            'priority' => 'low',
            'status' => 'rejected',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now()->subDays(2),
            'review_notes' => 'Anggaran tidak mencukupi untuk tahun ini. Diusulkan untuk diajukan kembali tahun depan dengan justifikasi yang lebih detail terkait kurikulum.',
        ]);

        // Requisition 5: Medium - New stock untuk praktik
        PurchaseRequisition::create([
            'category_id' => $categories->where('name', 'Broadcasting')->first()->id ?? 5,
            'requested_by' => $admin->id,
            'item_name' => 'Microphone Condenser Audio-Technica AT2020',
            'quantity' => 3,
            'estimated_price' => 2500000,
            'reason' => 'new_stock',
            'justification' => 'Menambah microphone untuk praktik recording dan podcasting siswa broadcasting. Microphone yang ada sudah mengalami penurunan kualitas audio.',
            'priority' => 'medium',
            'status' => 'pending',
        ]);

        // Requisition 6: Replacement - Proyektor rusak
        PurchaseRequisition::create([
            'category_id' => $categories->where('name', 'Rekayasa Perangkat Lunak (RPL)')->first()->id ?? 1,
            'requested_by' => $admin->id,
            'item_name' => 'Proyektor Epson EB-X06',
            'quantity' => 1,
            'estimated_price' => 5500000,
            'reason' => 'replacement',
            'justification' => 'Mengganti proyektor Lab RPL 1 yang lampu proyektornya sudah padam dan tidak bisa diganti (spare part discontinued).',
            'priority' => 'high',
            'status' => 'approved',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now()->subHours(5),
            'review_notes' => 'Approved. Segera lakukan pencairan dana karena diperlukan untuk KBM.',
        ]);

        $this->command->info('✓ Created 6 Purchase Requisitions (2 Approved, 1 Rejected, 3 Pending)');
    }
}
