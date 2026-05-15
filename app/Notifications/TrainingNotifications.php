<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;


class TrainingNotifications extends Notification
{
    use Queueable;
    private $kd_training;
    private $event_training;
    private $namatraining;
    private $tanggal_mulai;
    private $tanggal_akhir;

    /**
     * Create a new notification instance.
     */
    public function __construct($namatraining)
    {
        // return $namatraining;
        $tanggal_mulai = Carbon::parse($namatraining->tanggal_mulai);
        $tanggalmulai = $tanggal_mulai->format('d F Y');

        $tanggal_akhir= Carbon::parse($namatraining->tanggal_akhir);
        $tanggalakhir = $tanggal_akhir->format('d F Y');

        $this->kd_training=$namatraining->kd_training;
        $this->event_training=$namatraining->kd_event_training;
        $this->namatraining = $namatraining->nama_training;
        $this->tanggal_mulai= $tanggalmulai;
        $this->tanggal_akhir= $tanggalakhir;
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
                    ->subject('Kamu Telah Didaftarkan Pada Event Training')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Kamu telah terdaftar untuk Mengikuti Training ' . $this->namatraining . ',')
                    ->line('training di mulai pada tanggal ' . $this->tanggal_mulai . ' s/d ' . $this->tanggal_akhir . ',' )
                    ->line('Silahkan Login Ke LMS-MA Dan lihat Pemberitahuan untuk detail Training!')
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
            'title'=> 'Anda Terdaftar Pada Training ' . $this->namatraining,
            'message'=> 'Click Untuk Melihat Detail Training', 
            'url'=> route('detail.show',[$this->kd_training,$this->event_training]),
        ];
    }
}
