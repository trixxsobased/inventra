<?php

namespace App\Notifications;

use App\Models\Borrowing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BorrowingRejected extends Notification implements ShouldQueue
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
        $mail = (new MailMessage)
            ->subject('❌ Peminjaman Ditolak - ' . $this->borrowing->equipment->name)
            ->greeting('Halo, ' . $notifiable->name)
            ->line('Maaf, peminjaman alat Anda **ditolak**.')
            ->line('**Detail Peminjaman:**')
            ->line('• Alat: ' . $this->borrowing->equipment->name)
            ->line('• Kode: ' . $this->borrowing->equipment->code)
            ->line('• Tanggal Pengajuan: ' . $this->borrowing->created_at->format('d M Y'));

        if ($this->borrowing->rejection_reason) {
            $mail->line('**Alasan:** ' . $this->borrowing->rejection_reason);
        }

        return $mail
            ->line('Silakan ajukan peminjaman untuk alat lain atau hubungi petugas inventaris.')
            ->salutation('Terima kasih, Inventra SMKN 1 Jenangan');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'borrowing_id' => $this->borrowing->id,
            'equipment_name' => $this->borrowing->equipment->name,
            'message' => 'Peminjaman ' . $this->borrowing->equipment->name . ' ditolak',
            'reason' => $this->borrowing->rejection_reason,
            'type' => 'rejected',
        ];
    }
}
