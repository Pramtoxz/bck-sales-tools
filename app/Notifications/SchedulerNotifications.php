<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;


class SchedulerNotifications extends Notification
{
    use Queueable;
    private $judulnotifikasi;
    private $nama_karyawan;
    private $nama_training;
    private $sisa_hari_penyelesaian;
    private $kd_training;
    private $event_training;

    /**
     * Create a new notification instance.
     */
    public function __construct($pesertatraining)
    {        
        $judulnotifikasi= 'Info Batas Waktu Penyelesaian Training';

        $this->kd_training=$pesertatraining->kd_training;
        $this->event_training=$pesertatraining->kd_event_training;

        $this->judulnotifikasi=$judulnotifikasi;
        $this->nama_karyawan = $pesertatraining->nama_lengkap;
        $this->nama_training= $pesertatraining->nama_training;
        $this->sisa_hari_penyelesaian= $pesertatraining->sisa_hari_menyelesaikan;
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
                    ->subject($this->judulnotifikasi)
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Salam Satu Hati')
                    ->line($this->judulnotifikasi)
                    ->line('Nama Karyawan : ' . $this->nama_karyawan . ',' )
                    ->line('Nama Training : ' . $this->nama_training . ',' )
                    ->line('Sisa Hari Penyelesaian : ' . $this->sisa_hari_penyelesaian . ' Hari ,' )
                    ->line('Silahkan Login Ke LMS-MA Dan lihat Pemberitahuan untuk detail Training!')
                    ->line('Harap Segera Menyelesaikan Training, Terima Kasih')
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
            'title'=> 'Info Batas Waktu Penyelesaian Training ' . $this->nama_training,
            'message'=> 'Click Untuk Melihat Detail Training', 
            'url'=> route('detail.show',[$this->kd_training,$this->event_training]),
        ];
    }
}
