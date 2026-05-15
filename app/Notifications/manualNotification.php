<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class manualNotification extends Notification
{
    use Queueable;
    private $title;
    private $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($title,$message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Training Notification')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line($this->title.',')
                    ->line('Click Login Untuk Melihat Detail Notification')
                    ->action('Login LMS', url('/'))
                    ->salutation('Best Regards, Admin LMS');
                    
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'=> $this->title,
            'message'=> $this->message,
            'url'=> route('moreNotifications'),
        ];
    }
}
