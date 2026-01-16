<?php

namespace App\Console\Commands;

use App\Models\Borrowing;
use App\Notifications\ReturnReminder;
use Illuminate\Console\Command;

class SendReturnReminders extends Command
{
    protected $signature = 'borrowings:send-reminders';

    protected $description = 'Kirim reminder email ke peminjam yang deadline-nya H-1 atau sudah terlambat';

    public function handle(): int
    {
        // Ambil peminjaman yang:
        // 1. Status masih borrowed (belum dikembalikan)
        // 2. Deadline besok (H-1) atau sudah lewat (terlambat)
        $borrowings = Borrowing::with(['user', 'equipment'])
            ->where('status', 'borrowed')
            ->where('planned_return_date', '<=', now()->addDay()->toDateString())
            ->get();

        $count = 0;
        foreach ($borrowings as $borrowing) {
            // Skip jika user tidak punya email
            if (!$borrowing->user->email) {
                continue;
            }

            $borrowing->user->notify(new ReturnReminder($borrowing));
            $count++;

            $this->info("Reminder sent to: {$borrowing->user->email} for {$borrowing->equipment->name}");
        }

        $this->info("Total {$count} reminder(s) sent.");

        return Command::SUCCESS;
    }
}
