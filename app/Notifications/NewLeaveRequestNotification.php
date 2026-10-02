<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewLeaveRequestNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $url;

    public function __construct($message, $url = null)
    {
        $this->message = $message;
        $this->url = $url;
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
        $url = $this->url;
        if (!$url) {
            if ($notifiable->hasRole('superadmin')) {
                $url = route('superadmin.leave.index');
            } elseif ($notifiable->hasRole('admin')) {
                $url = route('admin.leave.index');
            } else {
                $url = route('user.leave.index');
            }
        }

        return (new MailMessage)
            ->subject('ReTalent HRIS - Pengajuan Cuti Baru')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line($this->message)
            ->action('Proses Pengajuan Cuti', $url)
            ->line('Terima kasih.');
    }

    public function toArray(object $notifiable): array
    {
        $url = $this->url;
        if (!$url) {
            if ($notifiable->hasRole('superadmin')) {
                $url = route('superadmin.leave.index');
            } elseif ($notifiable->hasRole('admin')) {
                $url = route('admin.leave.index');
            } else {
                $url = route('user.leave.index');
            }
        }

        return [
            'title' => 'Pengajuan Cuti Baru',
            'message' => $this->message,
            'icon' => 'calendar',
            'url' => $url,
            'created_at' => now()->toDateTimeString()
        ];
    }
}
