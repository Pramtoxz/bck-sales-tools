<?php

namespace App\Models\Digital;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaMsgTmp extends Model
{
    use HasFactory;

    protected $connection = 'pgsql_nms';
    protected $table = 'Master_Schema.wa_msg_tmp';

    protected $fillable = [
        'no_hp',
        'message',
        'status_wa',
        'process_time',
        'unique_id',
        'source_id',
        'conversation_id',
        'message_type',
        'template_name',
        'template_language',
        'template_variables',
        'jenis_msg',
        'status',
        'kode_dealer',
        'module',
        'is_proses',
        'keterangan',
        'flag_kirim',
        //  STATUS KIRIM KE APTANA
        'sent_to_aptana_at',
        'failed_to_aptana_at',
        // STATUS BALIKAN WEBHOOK
        'sent_to_customer_at',
        'failed_to_customer_at',
        'delivered_at',
        'read_at',
        'replied_at',
        'message_replied',
        'status_resend'
    ];

    protected $casts = [
        'process_time' => 'datetime',
        'template_variables' => 'array',
        // STATUS KIRIM KE APTANA
        'sent_to_aptana_at' => 'datetime',
        'failed_to_aptana_at' => 'datetime',
        // STATUS BALIKAN WEBHOOK
        'sent_to_customer_at' => 'datetime',
        'failed_to_customer_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
        'message_replied' => 'array',
    ];

    protected $attributes = [
        'message_type' => 'text',
        'template_language' => 'id',
        'status_wa' => 'pending'
    ];


    /**
     * Scope pending - Pesan menunggu diproses
     */
    public function scopePending($query)
    {
        return $query->where('status_wa', 'pending');
    }

    /**
     * Scope processing - Sedang diproses/dikirim ke Aptana
     */
    public function scopeProcessing($query)
    {
        return $query->where('status_wa', 'processing');
    }

    /**
     * Scope sent to Aptana - Berhasil dikirim ke Aptana API
     */
    public function scopeSentToAptana($query)
    {
        return $query->where('status_wa', 'sent_to_aptana');
    }

    /**
     * Scope failed to Aptana - Gagal dikirim ke Aptana API
     */
    public function scopeFailedToAptana($query)
    {
        return $query->where('status_wa', 'failed_sending_to_aptana');
    }

    /**
     * Scope sent to customer - Berhasil terkirim ke customer via WhatsApp
     */
    public function scopeSentToCustomer($query)
    {
        return $query->where('status_wa', 'sent_to_customer');
    }

    /**
     * Scope failed to customer - Gagal terkirim ke customer
     */
    public function scopeFailedToCustomer($query)
    {
        return $query->where('status_wa', 'failed_sent_to_customer');
    }

    /**
     * Scope delivered - Pesan delivered ke device customer
     */
    public function scopeDelivered($query)
    {
        return $query->where('status_wa', 'delivered');
    }

    /**
     * Scope read - Pesan dibaca oleh customer
     */
    public function scopeRead($query)
    {
        return $query->where('status_wa', 'read');
    }

    /**
     * Scope replied - Customer membalas pesan
     */
    public function scopeReplied($query)
    {
        return $query->where('status_wa', 'replied');
    }


    public function scopeSent($query)
    {
        return $query->where('status_wa', 'sent_to_aptana');
    }


    public function scopeFailed($query)
    {
        return $query->where('status_wa', 'failed_sending_to_aptana');
    }


    public function scopeStuck($query)
    {
        return $query->where('status_wa', 'processing')
            ->where('process_time', '<=', now()->subHours(24));
    }

    public function scopeFailedcustomer($query)
    {
        return $query->where('status_wa', 'failed_sent_to_customer')
            ->whereNull('status_resend')
            ->where('failed_to_customer_at', '<=', now()->subHours(24));
    }

    public function scopeSuccess($query)
    {
        return $query->whereIn('status_wa', [
            'sent_to_customer',
            'delivered',
            'read',
            'replied'
        ]);
    }

    /**
     * Scope all failed - Semua status gagal
     */
    public function scopeAllFailed($query)
    {
        return $query->whereIn('status_wa', [
            'failed_sending_to_aptana',
            'failed_sent_to_customer'
        ]);
    }

    /**
     * Scope text type messages
     */
    public function scopeTextType($query)
    {
        return $query->where(function ($q) {
            $q->where('message_type', 'text')
                ->orWhereNull('message_type');
        });
    }

    /**
     * Scope template type messages
     */
    public function scopeTemplateType($query)
    {
        return $query->where('message_type', 'template');
    }


    public function scopeByKeterangan($query, array $keterangan)
    {
        return $query->whereIn('keterangan', $keterangan);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }


    public function isPending(): bool
    {
        return $this->status_wa === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status_wa === 'processing';
    }


    public function isSentToAptana(): bool
    {
        return $this->status_wa === 'sent_to_aptana';
    }


    public function isSentToCustomer(): bool
    {
        return $this->status_wa === 'sent_to_customer';
    }


    public function isDelivered(): bool
    {
        return $this->status_wa === 'delivered';
    }


    public function isRead(): bool
    {
        return $this->status_wa === 'read';
    }


    public function isReplied(): bool
    {
        return $this->status_wa === 'replied';
    }


    public function isFailed(): bool
    {
        return in_array($this->status_wa, [
            'failed_sending_to_aptana',
            'failed_sent_to_customer'
        ]);
    }


    public function isSuccess(): bool
    {
        return in_array($this->status_wa, [
            'sent_to_customer',
            'delivered',
            'read',
            'replied'
        ]);
    }



    public function isTemplate(): bool
    {
        return $this->message_type === 'template';
    }


    public function isText(): bool
    {
        return $this->message_type === 'text' || is_null($this->message_type);
    }



    public function getFormattedPhoneAttribute(): string
    {
        $phone = $this->no_hp;
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 1) === '8') {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    public function getStatusDisplayAttribute(): string
    {
        return match ($this->status_wa) {
            'pending' => 'Menunggu Diproses',
            'processing' => 'Sedang Diproses',
            'sent_to_aptana' => 'Terkirim ke Aptana',
            'failed_sending_to_aptana' => 'Gagal ke Aptana',
            'sent_to_customer' => 'Terkirim ke Customer',
            'failed_sent_to_customer' => 'Gagal ke Customer',
            'delivered' => 'Delivered',
            'read' => 'Dibaca',
            'replied' => 'Dibalas',
            default => 'Unknown'
        };
    }


    public function getStatusCategoryAttribute(): string
    {
        return match ($this->status_wa) {
            'pending', 'processing', 'sent_to_aptana', 'failed_sending_to_aptana' => 'internal',
            'sent_to_customer', 'failed_sent_to_customer', 'delivered', 'read', 'replied' => 'webhook',
            default => 'unknown'
        };
    }


    public function getProcessingDurationAttribute(): ?int
    {
        if (!$this->process_time) {
            return null;
        }

        $endTime = $this->sent_to_aptana_at
            ?? $this->failed_to_aptana_at
            ?? now();

        return $this->process_time->diffInSeconds($endTime);
    }


    public function getDeliveryDurationAttribute(): ?int
    {
        if (!$this->sent_to_customer_at || !$this->delivered_at) {
            return null;
        }

        return $this->sent_to_customer_at->diffInSeconds($this->delivered_at);
    }


    public function getReadDurationAttribute(): ?int
    {
        if (!$this->delivered_at || !$this->read_at) {
            return null;
        }

        return $this->delivered_at->diffInSeconds($this->read_at);
    }


    public function getReplyDurationAttribute(): ?int
    {
        if (!$this->sent_to_customer_at || !$this->replied_at) {
            return null;
        }

        return $this->sent_to_customer_at->diffInSeconds($this->replied_at);
    }


    public function hasRepliedMessage(): bool
    {
        return !empty($this->message_replied);
    }
}
