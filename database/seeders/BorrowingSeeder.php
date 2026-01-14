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
        $siswa = User::where('role', 'peminjam')->first();
        
        $equipment1 = Equipment::where('name', 'like', '%Mikrotik%')->orWhere('name', 'like', '%PC%')->first();
        $equipment2 = Equipment::where('name', 'like', '%Jangka Sorong%')->orWhere('name', 'like', '%Bubut%')->first();
        $equipment3 = Equipment::where('name', 'like', '%Helm%')->orWhere('name', 'like', '%Las%')->first();
        $equipment4 = Equipment::where('name', 'like', '%Oscilloscope%')->orWhere('name', 'like', '%Power%')->first();
        $equipment5 = Equipment::where('name', 'like', '%PLC%')->orWhere('name', 'like', '%Sensor%')->first();
        
        if (!$equipment1) $equipment1 = Equipment::first();
        if (!$equipment2) $equipment2 = Equipment::skip(1)->first() ?? $equipment1;
        if (!$equipment3) $equipment3 = Equipment::skip(2)->first() ?? $equipment1;
        if (!$equipment4) $equipment4 = Equipment::skip(3)->first() ?? $equipment1;
        if (!$equipment5) $equipment5 = Equipment::skip(4)->first() ?? $equipment1;
        
        if (!$siswa || !$equipment1) {
            $this->command->warn('⚠️ Skipping BorrowingSeeder - missing siswa or equipment');
            return;
        }

        Borrowing::create([
            'user_id' => $siswa->id,
            'equipment_id' => $equipment1->id,
            'borrow_date' => Carbon::now(),
            'planned_return_date' => Carbon::now()->addDays(3),
            'actual_return_date' => null,
            'status' => 'pending',
            'purpose' => 'Praktikum Jaringan Komputer',
            'notes' => null,
            'verified_by' => null,
        ]);

        $activeBorrowing = Borrowing::create([
            'user_id' => $siswa->id,
            'equipment_id' => $equipment2->id,
            'borrow_date' => Carbon::now()->subDays(2),
            'planned_return_date' => Carbon::now()->addDays(5),
            'actual_return_date' => null,
            'status' => 'borrowed',
            'purpose' => 'Praktikum Pemesinan',
            'notes' => 'Disetujui untuk praktikum',
            'verified_by' => $admin->id ?? null,
            'verified_at' => Carbon::now()->subDays(2),
        ]);
        $equipment2->decrement('stock');

        Borrowing::create([
            'user_id' => $siswa->id,
            'equipment_id' => $equipment3->id,
            'borrow_date' => Carbon::now()->subDays(10),
            'planned_return_date' => Carbon::now()->subDays(7),
            'actual_return_date' => Carbon::now()->subDays(7),
            'status' => 'returned',
            'purpose' => 'Praktikum Pengelasan',
            'notes' => 'Dikembalikan tepat waktu',
            'verified_by' => $admin->id ?? null,
            'verified_at' => Carbon::now()->subDays(10),
        ]);

        $lateBorrowing = Borrowing::create([
            'user_id' => $siswa->id,
            'equipment_id' => $equipment4->id,
            'borrow_date' => Carbon::now()->subDays(20),
            'planned_return_date' => Carbon::now()->subDays(15),
            'actual_return_date' => Carbon::now()->subDays(10),
            'status' => 'returned',
            'purpose' => 'Praktikum Elektronika',
            'notes' => 'Terlambat 5 hari',
            'verified_by' => $admin->id ?? null,
            'verified_at' => Carbon::now()->subDays(20),
        ]);
        
        Fine::create([
            'borrowing_id' => $lateBorrowing->id,
            'amount' => 25000,
            'days_late' => 5,
            'rate_per_day' => 5000,
            'is_paid' => false,
            'paid_at' => null,
            'notes' => 'Keterlambatan pengembalian 5 hari',
        ]);

        if ($equipment5) {
            Borrowing::create([
                'user_id' => $siswa->id,
                'equipment_id' => $equipment5->id,
                'borrow_date' => Carbon::now()->subHours(2),
                'planned_return_date' => Carbon::now()->addDays(2),
                'actual_return_date' => null,
                'status' => 'pending',
                'purpose' => 'Praktikum Otomasi Industri',
                'notes' => null,
                'verified_by' => null,
            ]);
        }

        $this->command->info('✅ BorrowingSeeder: Created 5 demo scenarios!');
    }
}
