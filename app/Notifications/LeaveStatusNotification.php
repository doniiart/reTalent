<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaveStatusNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $url;

    public function __construct($message, $url = null)
    {
        $this->message = $message;
        $this->url = $url ?? route('user.leave.index');
    }

    public function via(object $notifiable): array
    {
        if (isset($notifiable->notify_leave) && !$notifiable->notify_leave) {
            return [];
        }

        $channels = ['database'];
        if (!empty($notifiable->notify_email)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('ReTalent HRIS - Status Pengajuan Cuti')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line($this->message)
            ->action('Lihat Detail Cuti', $this->url)
            ->line('Terima kasih telah menggunakan sistem ReTalent HRIS.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Persetujuan Pengajuan Cuti',
            'message' => $this->message,
            'icon' => 'calendar',
            'url' => $this->url,
            'created_at' => now()->toDateTimeString()
        ];
    }
}
