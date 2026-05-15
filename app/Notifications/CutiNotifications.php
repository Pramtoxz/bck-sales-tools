<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;


class CutiNotifications extends Notification
{
    use Queueable;
    private $judulnotifikasi;
    private $pembuatcuti;
    private $tanggal_cuti;
    private $jumlah_cuti;
    private $infopenutup;
    private $ambilJenisCuti;

    /**
     * Create a new notification instance.
     */
    public function __construct($save,$nama_lengkap,$ambilJenisCuti)
    {
        if($save->id_jenis_cuti=='4'){
            $judulnotifikasi = 'Info Izin Sakit';
            $infopenutup = 'Informasi Lebih Lanjut bisa Kunjungi Web Digiment';
        }else{
            $judulnotifikasi= 'Info Cuti Masuk';
            $infopenutup = 'Mohon Untuk Dapat Dilakukan Peninjauan Cuti Pada Web Digiment';
        }

        $this->judulnotifikasi=$judulnotifikasi;
        $this->pembuatcuti=$nama_lengkap;
        $this->tanggal_cuti = $save->tgl_cuti;
        $this->jumlah_cuti= $save->jumlah_cuti;
        $this->infopenutup= $infopenutup;
        $this->ambilJenisCuti=$ambilJenisCuti;

        // $jeniscuti=

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
                    ->line('Jenis Cuti : ' . $this->ambilJenisCuti . ',' )
                    ->line('Nama Karyawan : ' . $this->pembuatcuti . ',' )
                    ->line('Tanggal : ' . $this->tanggal_cuti . ',' )
                    ->line('Jumlah Hari : ' . $this->jumlah_cuti . ' Hari ,' )
                    ->line($this->infopenutup)
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
            'message'=> $this->pembuatcuti . ' Telah Mengajukan Cuti',
            'url'=> route('cuti.all'),
        ];
    }
}
