<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;


class CutiPotongNotifications extends Notification
{
    use Queueable;
    private $judulnotifikasi;
    private $nama_karyawan;
    private $jumlah_cuti_dikurangi;
    private $alasan_pengurangan;
    private $tanggalpengurangan;

    /**
     * Create a new notification instance.
     */
    public function __construct($save,$nama_lengkap)
    {
        
        $judulnotifikasi= 'Info Pengurangan Jatah Cuti';

        $this->judulnotifikasi=$judulnotifikasi;
        $this->nama_karyawan = $nama_lengkap;
        $this->jumlah_cuti_dikurangi= $save->jumlah_cuti;
        $this->alasan_pengurangan= $save->alasan;
        $this->tanggalpengurangan=$save->tgl_cuti;

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
                    ->line('Jenis Cuti : Cuti Tahunan' . ',' )
                    ->line('Nama Karyawan : ' . $this->nama_karyawan . ',' )
                    ->line('Jumlah Hari Dikurangi : ' . $this->jumlah_cuti_dikurangi . ' Hari ,' )
                    ->line('Alasan Pengurangan : ' . $this->alasan_pengurangan . ',' )
                    ->line('Tanggal Pengurangan : ' . $this->tanggalpengurangan . ',' )
                    ->line('Terima Kasih')
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
            'title'=> $this->judulnotifikasi,
            'message'=>'Pemotongan Cuti ' . $this->nama_karyawan,
            'url'=> route('cuti.all'),
        ];
    }
}
