<?php

namespace App\Notifications;

use App\Models\Borrowing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReturnReminder extends Notification implements ShouldQueue
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
        $daysLeft = now()->diffInDays($this->borrowing->planned_return_date, false);
        
        $urgency = $daysLeft <= 0 ? '🚨 TERLAMBAT' : '⏰ Reminder';
        $subject = $urgency . ' - Pengembalian ' . $this->borrowing->equipment->name;

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Halo, ' . $notifiable->name . '!');

        if ($daysLeft <= 0) {
            $mail->line('**Peminjaman Anda sudah melewati batas waktu!**')
                 ->line('Segera kembalikan untuk menghindari denda.')
                 ->line('Denda keterlambatan: Rp 5.000/hari');
        } elseif ($daysLeft == 1) {
            $mail->line('**Batas pengembalian besok!**')
                 ->line('Jangan lupa kembalikan alat tepat waktu.');
        } else {
            $mail->line('Batas pengembalian dalam **' . $daysLeft . ' hari** lagi.');
        }

        return $mail
            ->line('**Detail Peminjaman:**')
            ->line('• Alat: ' . $this->borrowing->equipment->name)
            ->line('• Kode: ' . $this->borrowing->equipment->code)
            ->line('• Batas Kembali: ' . $this->borrowing->planned_return_date->format('d M Y'))
            ->action('Lihat Peminjaman', url('/borrowings/' . $this->borrowing->id))
            ->salutation('Terima kasih, Inventra SMKN 1 Jenangan');
    }

    public function toArray(object $notifiable): array
    {
        $daysLeft = now()->diffInDays($this->borrowing->planned_return_date, false);
        
        return [
            'borrowing_id' => $this->borrowing->id,
            'equipment_name' => $this->borrowing->equipment->name,
            'planned_return_date' => $this->borrowing->planned_return_date->toDateString(),
            'days_left' => $daysLeft,
            'message' => $daysLeft <= 0 
                ? 'Peminjaman ' . $this->borrowing->equipment->name . ' sudah terlambat!'
                : 'Reminder: Kembalikan ' . $this->borrowing->equipment->name . ' dalam ' . $daysLeft . ' hari',
            'type' => 'reminder',
        ];
    }
}
