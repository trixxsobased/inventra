<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\User;
use App\Models\Equipment;
use Carbon\Carbon;

class BorrowingSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $petugas = User::where('role', 'petugas')->first();
        
        $students = User::where('role', 'peminjam')->get();
        if ($students->isEmpty()) {
            $this->command->warn('⚠️ No students found. Run UserSeeder first.');
            return;
        }

        $equipments = Equipment::where('stock', '>', 0)->get();
        if ($equipments->count() < 15) {
            $this->command->warn('⚠️ Not enough equipment. Run EquipmentSeeder first.');
            return;
        }

        $count = 0;

        // ============================================
        // SKENARIO 1: PEMINJAMAN AKTIF (Sedang Dipinjam)
        // ============================================
        
        // Siswa RPL pinjam PC
        $this->createBorrowing($students[0], $equipments[0], 'borrowed', -2, 5, 'Praktikum Android Studio');
        $count++;
        
        // Siswa TKJ pinjam Mikrotik
        $this->createBorrowing($students[4] ?? $students[1], $equipments[1], 'borrowed', -1, 7, 'Project tugas akhir jaringan');
        $count++;
        
        // Guru pinjam alat untuk demo
        $guru = $students->firstWhere('username', 'guru-rpl') ?? $students[0];
        $this->createBorrowing($guru, $equipments[5], 'borrowed', 0, 14, 'Demo pembelajaran kelas XII');
        $count++;

        // ============================================
        // SKENARIO 2: TERLAMBAT (Masih Dipinjam, Deadline Lewat)
        // ============================================
        
        // Terlambat 3 hari
        $this->createBorrowing($students[2] ?? $students[0], $equipments[2], 'borrowed', -10, -3, 'Praktikum jaringan');
        $count++;
        
        // Terlambat 7 hari (parah)
        $this->createBorrowing($students[5] ?? $students[1], $equipments[10], 'borrowed', -14, -7, 'Project semester');
        $count++;

        // ============================================
        // SKENARIO 3: MENUNGGU PERSETUJUAN (Pending)
        // ============================================
        
        $this->createBorrowing($students[0], $equipments[3], 'pending', 0, 3, 'Praktikum web programming');
        $count++;
        
        $this->createBorrowing($students[1], $equipments[4], 'pending', 0, 5, 'Tugas kelompok jaringan');
        $count++;
        
        $this->createBorrowing($students[6] ?? $students[2], $equipments[8], 'pending', 0, 2, 'Demo presentation');
        $count++;

        // ============================================
        // SKENARIO 4: SUDAH DIKEMBALIKAN (Tepat Waktu)
        // ============================================
        
        // Kembali tepat waktu - kondisi baik
        $this->createBorrowing($students[0], $equipments[6], 'returned', -20, -15, 'Praktikum dasar', -15, 'baik');
        $count++;
        
        $this->createBorrowing($students[1], $equipments[7], 'returned', -18, -14, 'Tugas mandiri', -14, 'baik');
        $count++;
        
        // Kembali - kondisi rusak ringan
        $this->createBorrowing($students[3] ?? $students[0], $equipments[9], 'returned', -25, -20, 'Project IOT', -20, 'rusak ringan');
        $count++;

        // ============================================
        // SKENARIO 5: DENDA - LUNAS
        // ============================================
        
        $latePaid = $this->createBorrowing($students[0], $equipments[11], 'returned', -30, -25, 'Project akhir semester', -20, 'baik');
        Fine::create([
            'borrowing_id' => $latePaid->id,
            'amount' => 25000,
            'days_late' => 5,
            'rate_per_day' => 5000,
            'is_paid' => true,
            'paid_at' => now()->subDays(18),
            'received_by' => $admin->id,
        ]);
        $count++;

        // ============================================
        // SKENARIO 6: DENDA - BELUM LUNAS
        // ============================================
        
        $lateUnpaid1 = $this->createBorrowing($students[2] ?? $students[0], $equipments[12], 'returned', -15, -10, 'Lupa mengembalikan', -6, 'baik');
        Fine::create([
            'borrowing_id' => $lateUnpaid1->id,
            'amount' => 20000,
            'days_late' => 4,
            'rate_per_day' => 5000,
            'is_paid' => false,
        ]);
        $count++;

        $lateUnpaid2 = $this->createBorrowing($students[7] ?? $students[1], $equipments[13], 'returned', -12, -8, 'Tidak bisa datang', -5, 'baik');
        Fine::create([
            'borrowing_id' => $lateUnpaid2->id,
            'amount' => 15000,
            'days_late' => 3,
            'rate_per_day' => 5000,
            'is_paid' => false,
        ]);
        $count++;

        // ============================================
        // SKENARIO 7: BARANG RUSAK BERAT
        // ============================================
        
        $this->createBorrowing($students[4] ?? $students[0], $equipments[14], 'returned', -8, -5, 'Jatuh saat praktikum', -5, 'rusak berat');
        $count++;

        // ============================================
        // SKENARIO 8: PENGAJUAN DITOLAK
        // ============================================
        
        Borrowing::create([
            'user_id' => $students[0]->id,
            'equipment_id' => $equipments[0]->id,
            'borrow_date' => Carbon::now()->subDays(5),
            'planned_return_date' => Carbon::now()->addDays(2),
            'status' => 'rejected',
            'purpose' => 'Main game',
            'rejection_reason' => 'Tidak sesuai peruntukan alat sekolah.',
            'verified_by' => $admin->id,
            'verified_at' => Carbon::now()->subDays(5),
        ]);
        $count++;

        Borrowing::create([
            'user_id' => $students[3]->id ?? $students[1]->id,
            'equipment_id' => $equipments[5]->id,
            'borrow_date' => Carbon::now()->subDays(3),
            'planned_return_date' => Carbon::now()->addDays(10),
            'status' => 'rejected',
            'purpose' => 'Pinjam untuk dibawa pulang',
            'rejection_reason' => 'Alat tidak boleh dibawa keluar area sekolah.',
            'verified_by' => $petugas->id,
            'verified_at' => Carbon::now()->subDays(3),
        ]);
        $count++;

        // ============================================
        // SKENARIO 9: RIWAYAT LAMA (1-2 bulan lalu)
        // ============================================
        
        $this->createBorrowing($students[8] ?? $students[0], $equipments[15] ?? $equipments[0], 'returned', -45, -40, 'Praktikum awal semester', -40, 'baik');
        $count++;

        $this->createBorrowing($students[9] ?? $students[1], $equipments[16] ?? $equipments[1], 'returned', -60, -55, 'Ujian praktik', -55, 'baik');
        $count++;

        $this->command->info("✓ Created {$count} borrowing scenarios");
    }

    private function createBorrowing($user, $equipment, $status, $startOffset, $planOffset, $purpose = 'Praktikum', $returnOffset = null, $condition = 'baik')
    {
        $borrowDate = Carbon::now()->addDays($startOffset);
        $planDate = Carbon::now()->addDays($planOffset);
        
        $actualReturnDate = $returnOffset !== null ? Carbon::now()->addDays($returnOffset) : null;
        
        $verifiedBy = ($status !== 'pending') ? User::where('role', 'admin')->first()->id : null;
        $verifiedAt = ($status !== 'pending') ? $borrowDate : null;

        $borrowing = Borrowing::create([
            'user_id' => $user->id,
            'equipment_id' => $equipment->id,
            'borrow_date' => $borrowDate,
            'planned_return_date' => $planDate,
            'actual_return_date' => $actualReturnDate,
            'status' => $status,
            'purpose' => $purpose,
            'notes' => null,
            'verified_by' => $verifiedBy,
            'verified_at' => $verifiedAt,
            'return_condition' => ($status === 'returned') ? $condition : null,
        ]);

        return $borrowing;
    }
}
