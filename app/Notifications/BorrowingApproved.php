<?php

namespace App\Notifications;

use App\Models\Borrowing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BorrowingApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Borrowing $borrowing
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('✅ Peminjaman Disetujui - ' . $this->borrowing->equipment->name)
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Peminjaman alat Anda telah **disetujui**.')
            ->line('**Detail Peminjaman:**')
            ->line('• Alat: ' . $this->borrowing->equipment->name)
            ->line('• Kode: ' . $this->borrowing->equipment->code)
            ->line('• Tanggal Pinjam: ' . $this->borrowing->borrow_date->format('d M Y'))
            ->line('• Batas Kembali: ' . $this->borrowing->planned_return_date->format('d M Y'))
            ->action('Lihat Detail', url('/borrowings/' . $this->borrowing->id))
            ->line('Silakan ambil alat di ruang inventaris.')
            ->salutation('Terima kasih, Inventra SMKN 1 Jenangan');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'borrowing_id' => $this->borrowing->id,
            'equipment_name' => $this->borrowing->equipment->name,
            'message' => 'Peminjaman ' . $this->borrowing->equipment->name . ' telah disetujui',
            'type' => 'approved',
        ];
    }
}
