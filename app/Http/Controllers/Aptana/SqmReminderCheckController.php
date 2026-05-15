<?php

namespace App\Http\Controllers\Aptana;

use App\Http\Controllers\Controller;
use App\Models\Digital\WaMsgTmp;
use App\Models\sqm\Sqm;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SqmReminderCheckController extends Controller
{
    private const SURVEY_EXPIRY_DAYS = 7;

    public function checkTransactionDateDifference()
    {
        Log::info('Scheduler jalan: ' . Carbon::now());

        // pengecekkan tgl_transaksi sudah >= 72 jam dari sekarang
        $sqmList = Sqm::where('status', 'Delivered')
            ->whereNotNull('tgl_transaksi')
            ->whereNull('reminder_sent_at')
            ->whereRaw('tgl_transaksi <= ?', [
                Carbon::now()->subHours(72)->toDateTimeString()
            ])
            ->get();
        Log::info('Jumlah transaksi eligible: ' . $sqmList->count());
        if ($sqmList->isEmpty()) {
            Log::info('Tidak ada transaksi yang perlu dikirim reminder.');
            return;
        }
        foreach ($sqmList as $sqm) {
            try {
                $this->sendReminderLink($sqm);
                Log::info('Reminder terkirim untuk SQM ID: ' . $sqm->id_unique);
            } catch (\Exception $e) {
                Log::error('Gagal kirim reminder SQM ID: ' . $sqm->id_unique . ' | Error: ' . $e->getMessage());
            }
        }
        Log::info('Selesai: ' . Carbon::now());
    }
    public function sendReminderLink($sqm)
    {
        $waExists = WaMsgTmp::where('sqm_id', $sqm->id_unique)
            ->whereIn('keterangan', ['INVOICEH1', 'INVOICEH23'])
            ->whereNull('status_resend')
            ->exists();

        if (!$waExists) {
            Log::warning('Data WaMsgTmp tidak ditemukan, SQM ID: ' . $sqm->id_unique . ' tidak diupdate.');
            return;
        }

        $invoiceH23Records = WaMsgTmp::where('sqm_id', $sqm->id_unique)
            ->where('keterangan', 'INVOICEH23')
            ->whereNull('status_resend')
            ->get();

        foreach ($invoiceH23Records as $record) {
            if (!empty($record->template_variables)) {


                $templateVars = is_array($record->template_variables)
                    ? $record->template_variables
                    : json_decode($record->template_variables, true);

                if (is_array($templateVars) && isset($templateVars['header'])) {
                    // Hapus key "header"
                    unset($templateVars['header']);
                    $record->template_variables = $templateVars;
                    $record->save();

                    Log::info('Header dihapus dari template_variables, WaMsgTmp ID: ' . $record->id . ', SQM ID: ' . $sqm->id_unique);
                }
            }
        }

        // Update template_name khusus INVOICEH23

        WaMsgTmp::where('sqm_id', $sqm->id_unique)
            ->where('keterangan', 'INVOICEH23')
            ->whereNull('status_resend')
            ->update([
                'template_name' => 'invoice_h23_reminder_1',
            ]);

        Log::info('template_name diupdate ke invoice_h23_reminder_1, SQM ID: ' . $sqm->id_unique);

        $sqm->status           = null;
        $sqm->reminder_sent_at = Carbon::now();
        $sqm->save();

        // Update WaMsgTmp untuk trigger kirim ulang WA
        WaMsgTmp::where('sqm_id', $sqm->id_unique)
            ->whereIn('keterangan', ['INVOICEH1', 'INVOICEH23'])
            ->whereNull('status_resend')
            ->update([
                'status_wa'             => 'pending',
                'process_time'          => null,
                'status'                => null,
                'unique_id'             => null,
                'source_id'             => null,
                'delivered_at'          => null,
                'read_at'               => null,
                'replied_at'            => null,
                'sent_to_aptana_at'     => null,
                'failed_to_aptana_at'   => null,
                'sent_to_customer_at'   => null,
                'message_replied'       => null,
                'status_resend'         => 't',
                'failed_to_customer_at' => null,
            ]);

        Log::info('sendReminderLink selesai diproses, SQM ID: ' . $sqm->id_unique);
    }

    public function updateExpired(): int
    {
        return Sqm::where('status', 'Delivered')
            ->where('tgl_transaksi', '<', now()->subDays(self::SURVEY_EXPIRY_DAYS))
            ->update(['status' => 'Expired']);
    }
}
