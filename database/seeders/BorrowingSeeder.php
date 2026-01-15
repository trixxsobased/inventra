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
        if ($equipments->count() < 10) {
            $this->command->warn('⚠️ Not enough equipment. Run EquipmentSeeder first.');
            return;
        }

        // 1. Data Peminjaman Aktif
        $this->createBorrowing($students[0], $equipments[0], 'borrowed', -2, 3);
        $this->createBorrowing($students[1] ?? $students[0], $equipments[1], 'borrowed', -1, 5);

        // Guru meminjam alat
        $teacher = $students->firstWhere('username', 'guru') ?? $students[0];
        $this->createBorrowing($teacher, $equipments[5], 'borrowed', 0, 7, 'Keperluan mengajar kelas X TPL');

        // 2. Data Peminjaman Terlambat (Overdue)
        $this->createBorrowing($students[2] ?? $students[0], $equipments[2], 'borrowed', -10, -3); 

        // 3. Menunggu Verifikasi (Pending)
        $this->createBorrowing($students[0], $equipments[3], 'pending', 0, 3, 'Mohon segera di acc pak');
        $this->createBorrowing($students[1] ?? $students[0], $equipments[4], 'pending', 0, 2);

        // 4. Riwayat Peminjaman (Sudah Kembali)
        $this->createBorrowing($students[0], $equipments[6], 'returned', -20, -18, 'Praktikum dasar', -18);
        $this->createBorrowing($students[1] ?? $students[0], $equipments[7], 'returned', -15, -14, 'Praktikum lanjut', -14);

        // 5. Skenario Denda
        // Denda lunas
        $latePaid = $this->createBorrowing($students[0], $equipments[8], 'returned', -30, -25, 'Project akhir', -20);
        Fine::create([
            'borrowing_id' => $latePaid->id,
            'amount' => 25000,
            'days_late' => 5,
            'rate_per_day' => 5000,
            'is_paid' => true,
            'paid_at' => now()->subDays(20),
        ]);

        // Denda belum lunas
        $lateUnpaid = $this->createBorrowing($students[2] ?? $students[0], $equipments[9], 'returned', -10, -5, 'Lupa mengembalikan', -1);
        Fine::create([
            'borrowing_id' => $lateUnpaid->id,
            'amount' => 20000,
            'days_late' => 4,
            'rate_per_day' => 5000,
            'is_paid' => false,
        ]);

        // 6. Skenario Barang Rusak
        $damagedBorrowing = $this->createBorrowing($students[0], $equipments[10] ?? $equipments[0], 'returned', -5, -2, 'Jatuh saat praktikum', -2, 'rusak berat');

        // 7. Pengajuan Ditolak
        $rejected = Borrowing::create([
            'user_id' => $students[0]->id,
            'equipment_id' => $equipments[0]->id,
            'borrow_date' => Carbon::now()->subDays(5),
            'planned_return_date' => Carbon::now()->addDays(2),
            'status' => 'rejected',
            'purpose' => 'Main game',
            'notes' => null,
            'rejection_reason' => 'Tidak sesuai peruntukan penggunaan alat sekolah.',
            'verified_by' => $admin->id,
            'verified_at' => Carbon::now()->subDays(5),
        ]);
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
        
        if ($status === 'borrowed' || ($status === 'returned' && $condition !== 'rusak berat')) {
            $equipment->decrement('stock');
        }
        
        // Kembalikan stok jika barang kembali baik
        if ($status === 'returned' && $condition === 'baik') {
            $equipment->increment('stock');
        }

        return $borrowing;
    }
}
