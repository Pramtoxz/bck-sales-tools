<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;


class CutiUpdateNotifications extends Notification
{
    use Queueable;
    private $judulnotifikasi;
    private $adminpengubah;
    private $pembuatcuti;
    private $tanggalcutiupdate;
    private $jumlah_cutibaru;
    private $infopenutup;
    Private $tanggal_sekarang;

    /**
     * Create a new notification instance.
     */
    public function __construct($adminpengubah,$namaPembuatCuti,$tanggalcutiupdate,$jumlah_cutibaru)
    {
        // return $tanggalcutiupdate;
      
        $judulnotifikasi= 'Info HR Update Cuti';
        $infopenutup = 'Terima kasih';

        $this->tanggal_sekarang= date('d-m-Y H:i:s');
        
        $this->judulnotifikasi=$judulnotifikasi;
        $this->adminpengubah=$adminpengubah;
        $this->pembuatcuti=$namaPembuatCuti;
        $this->tanggalcutiupdate=$tanggalcutiupdate;
        $this->jumlah_cutibaru=$jumlah_cutibaru;
        $this->infopenutup= $infopenutup;

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
                    ->line('Nama Karyawan : ' . $this->pembuatcuti . ',' )
                    ->line('Tanggal Cuti : ' . $this->tanggalcutiupdate . ',' )
                    ->line('Jumlah Cuti : ' . $this->jumlah_cutibaru . ',' )
                    ->line('TTD HR : ' . $this->adminpengubah . ',' )
                    ->line('Tanggal Di Update : ' .  $this->tanggal_sekarang . ',' )
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
            'message'=> $this->adminpengubah . ' , Telah Mengupdate Cuti ' . $this->pembuatcuti,
            'url'=> route('cuti.all'),
        ];
    }
}
