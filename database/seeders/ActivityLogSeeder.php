<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Equipment;
use App\Models\Borrowing;
use Carbon\Carbon;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $petugas = User::where('role', 'petugas')->first();
        $siswa = User::where('role', 'peminjam')->first();

        $equipment = Equipment::first();
        $borrowings = Borrowing::with('equipment', 'user')->take(5)->get();

        $logs = [];

        // === Equipment logs (30 hari terakhir) ===
        $logs[] = [
            'user_id' => $admin->id,
            'action' => 'created',
            'model_type' => 'Equipment',
            'model_id' => $equipment->id,
            'model_label' => $equipment->code . ' - ' . $equipment->name,
            'old_values' => null,
            'new_values' => ['name' => $equipment->name, 'stock' => $equipment->stock],
            'description' => 'Equipment baru ditambahkan',
            'ip_address' => '192.168.1.100',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'created_at' => Carbon::now()->subDays(25),
        ];

        $logs[] = [
            'user_id' => $admin->id,
            'action' => 'updated',
            'model_type' => 'Equipment',
            'model_id' => $equipment->id,
            'model_label' => $equipment->code,
            'old_values' => ['stock' => 10],
            'new_values' => ['stock' => 15],
            'description' => 'Equipment diperbarui',
            'ip_address' => '192.168.1.100',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'created_at' => Carbon::now()->subDays(20),
        ];

        // === Borrowing logs ===
        foreach ($borrowings as $i => $borrowing) {
            // Request peminjaman
            $logs[] = [
                'user_id' => $borrowing->user_id,
                'action' => 'created',
                'model_type' => 'Borrowing',
                'model_id' => $borrowing->id,
                'model_label' => $borrowing->equipment->name ?? 'Equipment',
                'old_values' => null,
                'new_values' => ['status' => 'pending', 'purpose' => $borrowing->purpose],
                'description' => 'Borrowing baru ditambahkan',
                'ip_address' => '192.168.1.10' . $i,
                'user_agent' => 'Mozilla/5.0 (Linux; Android 12)',
                'created_at' => Carbon::now()->subDays(15 - $i),
            ];

            // Approval/Reject
            if ($borrowing->status === 'borrowed' || $borrowing->status === 'returned') {
                $logs[] = [
                    'user_id' => $admin->id,
                    'action' => 'approved',
                    'model_type' => 'Borrowing',
                    'model_id' => $borrowing->id,
                    'model_label' => $borrowing->equipment->name ?? 'Equipment',
                    'old_values' => ['status' => 'pending'],
                    'new_values' => ['status' => 'borrowed'],
                    'description' => 'Peminjaman disetujui',
                    'ip_address' => '192.168.1.100',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    'created_at' => Carbon::now()->subDays(14 - $i),
                ];
            }

            if ($borrowing->status === 'rejected') {
                $logs[] = [
                    'user_id' => $admin->id,
                    'action' => 'rejected',
                    'model_type' => 'Borrowing',
                    'model_id' => $borrowing->id,
                    'model_label' => $borrowing->equipment->name ?? 'Equipment',
                    'old_values' => ['status' => 'pending'],
                    'new_values' => ['status' => 'rejected'],
                    'description' => 'Peminjaman ditolak: ' . ($borrowing->rejection_reason ?? 'Tidak sesuai'),
                    'ip_address' => '192.168.1.100',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    'created_at' => Carbon::now()->subDays(14 - $i),
                ];
            }

            // Return
            if ($borrowing->status === 'returned') {
                $logs[] = [
                    'user_id' => $petugas->id ?? $admin->id,
                    'action' => 'returned',
                    'model_type' => 'Borrowing',
                    'model_id' => $borrowing->id,
                    'model_label' => $borrowing->equipment->name ?? 'Equipment',
                    'old_values' => ['status' => 'borrowed'],
                    'new_values' => ['status' => 'returned', 'return_condition' => $borrowing->return_condition],
                    'description' => 'Alat dikembalikan',
                    'ip_address' => '192.168.1.101',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    'created_at' => Carbon::now()->subDays(10 - $i),
                ];
            }
        }

        // === Login logs ===
        $logs[] = [
            'user_id' => $admin->id,
            'action' => 'login',
            'model_type' => 'User',
            'model_id' => $admin->id,
            'model_label' => $admin->name,
            'old_values' => null,
            'new_values' => null,
            'description' => 'User login ke sistem',
            'ip_address' => '192.168.1.100',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'created_at' => Carbon::now()->subHours(2),
        ];

        $logs[] = [
            'user_id' => $siswa->id,
            'action' => 'login',
            'model_type' => 'User',
            'model_id' => $siswa->id,
            'model_label' => $siswa->name,
            'old_values' => null,
            'new_values' => null,
            'description' => 'User login ke sistem',
            'ip_address' => '192.168.1.50',
            'user_agent' => 'Mozilla/5.0 (Linux; Android 12)',
            'created_at' => Carbon::now()->subHours(1),
        ];

        // Insert all logs
        foreach ($logs as $log) {
            ActivityLog::create($log);
        }

        $this->command->info('✓ Created ' . count($logs) . ' activity logs');
    }
}
