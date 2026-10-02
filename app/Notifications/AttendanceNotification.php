<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendanceNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $url;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $url = null)
    {
        $this->message = $message;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if (isset($notifiable->notify_attendance) && !$notifiable->notify_attendance) {
            return [];
        }

        $channels = ['database'];
        if (!empty($notifiable->notify_email)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    /**
     * Get target URL dynamically based on user role
     */
    protected function getTargetUrl(object $notifiable): string
    {
        if ($this->url) {
            return $this->url;
        }

        if ($notifiable->hasRole('superadmin')) {
            return route('superadmin.attendance.index');
        } elseif ($notifiable->hasRole('admin')) {
            return route('admin.attendance.personal');
        }

        return route('user.attendance.index');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $targetUrl = $this->getTargetUrl($notifiable);

        return (new MailMessage)
            ->subject('ReTalent HRIS - Notifikasi Kehadiran')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line($this->message)
            ->action('Lihat Presensi Saya', $targetUrl)
            ->line('Terima kasih telah menggunakan sistem ReTalent HRIS.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Status Kehadiran',
            'message' => $this->message,
            'icon' => 'clock',
            'url' => $this->getTargetUrl($notifiable)
        ];
    }
}
