<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;


class CutiApprovalNotifications extends Notification
{
    use Queueable;
    private $judulnotifikasi;
    private $pembuatcuti;
    private $jumlah_cuti;
    private $status_approval;
    private $pengaprove;
    private $tanggal_approval;
    private $infopenutup;
    private $jenis_cuti;

    private $alasan_reject;

    /**
     * Create a new notification instance.
     */
    public function __construct($nama_lengkap,$jumlah_cuti,$approveOrReject,$kd_karyawan,$tanggal,$alasan_reject,$jenis_cuti)
    {
      
        $judulnotifikasi= 'Info Pengajuan Cuti';
        $infopenutup = 'Terima kasih';
        
        $this->judulnotifikasi=$judulnotifikasi;
        $this->pembuatcuti=$nama_lengkap;
        $this->jumlah_cuti=$jumlah_cuti;
        $this->status_approval=$approveOrReject;
        $this->pengaprove=$kd_karyawan;
        $this->tanggal_approval=$tanggal;
        $this->infopenutup= $infopenutup;
        $this->jenis_cuti=$jenis_cuti;

        $this->alasan_reject=$alasan_reject;

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
                    ->line('Jenis Cuti : ' . $this->jenis_cuti . ',' )
                    ->line('Jumlah Cuti : ' . $this->jumlah_cuti . ',' )
                    ->line('Status Approval : ' . $this->status_approval . ',' )
                    ->line('TTD Approval : ' . ($this->status_approval == 'Dibatalkan' ? 'Tim HR' : $this->pengaprove) . ',')

                    // ->line('TTD Approval : ' . $this->status_approval=='Dibatalkan' ? 'Tim HR' : $this->pengaprove . ',' )
                    // ->line('TTD Approval : ' . $this->pengaprove . ',' )
                    ->line('Tanggal Approval : ' . $this->tanggal_approval . ',' )
                    ->line($this->alasan_reject ? ($this->status_approval == 'Dibatalkan' ? 'Alasan Batal: ' . $this->alasan_reject : 'Alasan Reject: ' . $this->alasan_reject) : '')
                    // ->line($this->alasan_reject ? ($this->status_approval=='Dibatalkan' ? 'Alasan Batal' : 'Alasan Reject : '). $this->alasan_reject: '' )
                    ->line($this->infopenutup)
                    ->salutation('Best Regards, Admin Digiment MA');
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
            'message'=> $this->pembuatcuti . $this->alasan_reject ? ' , Cuti Anda Di Tolak' :' , Cuti Anda Di terima',
            'url'=> route('cuti.all'),
        ];
    }
}
