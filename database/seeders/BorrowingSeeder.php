<?php

declare(strict_types=1);

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
        $peminjam = User::where('role', 'peminjam')->get();

        if ($peminjam->isEmpty()) {
            $this->command->warn('⚠️ No peminjam found. Run UserSeeder first.');
            return;
        }

        $equipment = Equipment::where('stock', '>', 0)->get();
        if ($equipment->count() < 10) {
            $this->command->warn('⚠️ Not enough equipment. Run EquipmentSeeder first.');
            return;
        }

        $verifier = $admin ?? $petugas;
        if (!$verifier) {
            $this->command->warn('⚠️ No admin or petugas found for verification.');
            return;
        }

        // 1. Peminjaman Aktif
        $this->createBorrowing($peminjam[0], $equipment[0], 'borrowed', -2, 3, 'Praktikum Pemrograman Web', $verifier);
        $this->createBorrowing($peminjam[1] ?? $peminjam[0], $equipment[1], 'borrowed', -1, 5, 'Praktikum Jaringan Komputer', $verifier);

        // Guru meminjam alat
        $guru = $peminjam->firstWhere('username', 'guru') ?? $peminjam->last();
        $this->createBorrowing($guru, $equipment[5], 'borrowed', 0, 7, 'Keperluan mengajar kelas X RPL', $verifier);

        // 2. Peminjaman Terlambat (Overdue)
        $this->createBorrowing($peminjam[2] ?? $peminjam[0], $equipment[2], 'borrowed', -10, -3, 'Praktikum Database', $verifier);

        // 3. Menunggu Verifikasi (Pending)
        $this->createBorrowing($peminjam[0], $equipment[3], 'pending', 0, 3, 'Project Akhir Semester');
        $this->createBorrowing($peminjam[1] ?? $peminjam[0], $equipment[4], 'pending', 0, 2, 'Tugas Praktik RPL');

        // 4. Riwayat Peminjaman (Sudah Kembali)
        $this->createBorrowing($peminjam[0], $equipment[6], 'returned', -20, -18, 'Praktikum Dasar Komputer', $verifier, -18);
        $this->createBorrowing($peminjam[1] ?? $peminjam[0], $equipment[7], 'returned', -15, -14, 'Praktikum Multimedia', $verifier, -14);

        // 5. Skenario Denda - Lunas
        $latePaid = $this->createBorrowing($peminjam[0], $equipment[8], 'returned', -30, -25, 'Proyek Akhir RPL', $verifier, -20);
        Fine::create([
            'borrowing_id' => $latePaid->id,
            'amount' => 25000,
            'days_late' => 5,
            'rate_per_day' => 5000,
            'is_paid' => true,
            'paid_at' => now()->subDays(20),
        ]);

        // 6. Skenario Denda - Belum Lunas
        $lateUnpaid = $this->createBorrowing($peminjam[2] ?? $peminjam[0], $equipment[9], 'returned', -10, -5, 'Lupa mengembalikan', $verifier, -1);
        Fine::create([
            'borrowing_id' => $lateUnpaid->id,
            'amount' => 20000,
            'days_late' => 4,
            'rate_per_day' => 5000,
            'is_paid' => false,
        ]);

        // 7. Barang Rusak
        $this->createBorrowing(
            $peminjam[0],
            $equipment[10] ?? $equipment[0],
            'returned',
            -5,
            -2,
            'Jatuh saat praktikum',
            $verifier,
            -2,
            'rusak berat'
        );

        // 8. Pengajuan Ditolak
        Borrowing::create([
            'user_id' => $peminjam[0]->id,
            'equipment_id' => $equipment[0]->id,
            'borrow_date' => Carbon::now()->subDays(5),
            'planned_return_date' => Carbon::now()->addDays(2),
            'status' => 'rejected',
            'purpose' => 'Keperluan pribadi di rumah',
            'notes' => null,
            'rejection_reason' => 'Tidak sesuai peruntukan alat sekolah. Alat hanya untuk kegiatan belajar mengajar.',
            'verified_by' => $verifier->id,
            'verified_at' => Carbon::now()->subDays(5),
        ]);

        // 9. Skenario Smart Queue (Stok 1, Peminjam 3)
        // Ambil alat dengah stok rendah (misal equipment ke-11 atau create baru)
        $limitedItem = Equipment::create([
            'code' => 'LMT-Q-001',
            'name' => 'Kamera DSLR Canon EOS (Limited)',
            'category_id' => $equipment[0]->category_id,
            'stock' => 1, // STOK HANYA 1
            'location' => 'Lemari Khusus',
            'condition' => 'baik',
            'description' => 'Unit terbatas untuk tes antrian sistem',
        ]);

        // Request #1 (Paling Awal - Harusnya Prioritas)
        Borrowing::create([
            'user_id' => $peminjam[0]->id,
            'equipment_id' => $limitedItem->id,
            'status' => 'pending',
            'borrow_date' => Carbon::tomorrow(),
            'planned_return_date' => Carbon::tomorrow()->addDays(1),
            'purpose' => 'Antrian #1 (Prioritas)',
            'created_at' => Carbon::now()->subHours(5), // 5 jam lalu
        ]);

        // Request #2 (Pertengahan)
        Borrowing::create([
            'user_id' => $peminjam[1]->id ?? $peminjam[0]->id,
            'equipment_id' => $limitedItem->id,
            'status' => 'pending',
            'borrow_date' => Carbon::tomorrow(),
            'planned_return_date' => Carbon::tomorrow()->addDays(1),
            'purpose' => 'Antrian #2 (Warning jika di-acc)',
            'created_at' => Carbon::now()->subHours(3), // 3 jam lalu
        ]);

        // Request #3 (Terbaru)
        if (isset($peminjam[2])) {
            Borrowing::create([
                'user_id' => $peminjam[2]->id,
                'equipment_id' => $limitedItem->id,
                'status' => 'pending',
                'borrow_date' => Carbon::tomorrow(),
                'planned_return_date' => Carbon::tomorrow()->addDays(1),
                'purpose' => 'Antrian #3 (Warning sangat keras)',
                'created_at' => Carbon::now()->subHours(1), // 1 jam lalu
            ]);
        }
    }

    private function createBorrowing(
        User $user,
        Equipment $equipment,
        string $status,
        int $startOffset,
        int $planOffset,
        string $purpose = 'Praktikum',
        ?User $verifier = null,
        ?int $returnOffset = null,
        string $condition = 'baik'
    ): Borrowing {
        $borrowDate = Carbon::now()->addDays($startOffset);
        $planDate = Carbon::now()->addDays($planOffset);
        $actualReturnDate = $returnOffset !== null ? Carbon::now()->addDays($returnOffset) : null;

        $borrowing = Borrowing::create([
            'user_id' => $user->id,
            'equipment_id' => $equipment->id,
            'borrow_date' => $borrowDate,
            'planned_return_date' => $planDate,
            'actual_return_date' => $actualReturnDate,
            'status' => $status,
            'purpose' => $purpose,
            'notes' => null,
            'verified_by' => ($status !== 'pending' && $verifier) ? $verifier->id : null,
            'verified_at' => ($status !== 'pending' && $verifier) ? $borrowDate : null,
            'return_condition' => ($status === 'returned') ? $condition : null,
        ]);

        if ($status === 'borrowed' || ($status === 'returned' && $condition !== 'rusak berat')) {
            $equipment->decrement('stock');
        }

        if ($status === 'returned' && $condition === 'baik') {
            $equipment->increment('stock');
        }

        return $borrowing;
    }
}
