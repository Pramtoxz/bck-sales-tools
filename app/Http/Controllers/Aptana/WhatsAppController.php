<?php

namespace App\Http\Controllers\Aptana;

use App\Http\Controllers\Controller;
use App\Models\Digital\WaMsgTmp;
use App\Services\WhatsAppService;
use App\Helper\ApiResponse;
use App\Models\Digital\WaSender;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Digital\WaWebhook;
use App\Models\sqm\Sqm;
use Illuminate\Support\Carbon;

class WhatsAppController extends Controller
{
    private function handleErrorResponse(array $result): JsonResponse
    {
        $status = $result['status'] ?? 500;
        $message = $result['message'] ?? 'Unknown error';
        $error = $result['error'] ?? null;

        return match ($status) {
            400 => ApiResponse::badRequest($message, $error),
            401 => ApiResponse::unauthorized($message, $error),
            404 => ApiResponse::notFound($message, $error),
            422 => ApiResponse::validationError($message, $error),
            503 => ApiResponse::serviceUnavailable($message, $error),
            default => ApiResponse::serverError($message, $error)
        };
    }


    public function getAccount(Request $request): JsonResponse
    {
        $kodeDealer = $request->query('kode_dealer');

        if (empty($kodeDealer)) {
            return ApiResponse::validationError('kode_dealer is required');
        }

        $waSender = WaSender::where('status', true)
            ->where('kode_dealer', $kodeDealer)
            ->first();

        if (!$waSender) {
            return ApiResponse::serverError("Tidak ada sender aktif untuk dealer $kodeDealer");
        }

        if (empty($waSender->base_url) || empty($waSender->api_token)) {
            return ApiResponse::serverError("Data sender tidak lengkap untuk dealer $kodeDealer (base_url/api_token kosong)");
        }

        $service = new WhatsAppService(
            $waSender->base_url,
            $waSender->api_token,
            $waSender->sender_id
        );

        $result = $service->getAccount();

        if ($result['success']) {
            return ApiResponse::success($result['data'], 'Account information successfully');
        }

        return $this->handleErrorResponse($result);
    }


    public function getSenders(Request $request): JsonResponse
    {
        $kodeDealer = $request->query('kode_dealer');

        if (empty($kodeDealer)) {
            return ApiResponse::validationError('kode_dealer is required');
        }

        $waSender = WaSender::where('status', true)
            ->where('kode_dealer', $kodeDealer)
            ->first();

        if (!$waSender) {
            return ApiResponse::serverError("Tidak ada sender aktif untuk dealer $kodeDealer");
        }
        if (empty($waSender->base_url) || empty($waSender->api_token)) {
            return ApiResponse::serverError("Data sender tidak lengkap untuk dealer $kodeDealer (base_url/api_token kosong)");
        }

        $service = new WhatsAppService(
            $waSender->base_url,
            $waSender->api_token,
            $waSender->sender_id
        );

        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        if (!is_numeric($limit) || $limit < 1 || $limit > 100) {
            return ApiResponse::validationError('Limit must be between 1 and 100');
        }

        if (!is_numeric($offset) || $offset < 0) {
            return ApiResponse::validationError('Offset must be 0 or greater');
        }

        $result = $service->getSenders((int)$limit, (int)$offset);

        if ($result['success']) {
            return ApiResponse::success($result['data'], 'Senders list successfully');
        }

        return $this->handleErrorResponse($result);
    }


    public function getTemplates(Request $request): JsonResponse
    {
        $kodeDealer = $request->query('kode_dealer');
        $senderId   = $request->query('sender_id');

        if (empty($kodeDealer)) {
            return ApiResponse::validationError('kode_dealer is required');
        }

        if (empty($senderId)) {
            return ApiResponse::validationError('sender_id is required');
        }

        $waSender = WaSender::where('status', true)
            ->where('kode_dealer', $kodeDealer)
            ->first();

        if (!$waSender) {
            return ApiResponse::serverError("Tidak ada sender aktif untuk dealer $kodeDealer");
        }

        if (empty($waSender->base_url) || empty($waSender->api_token)) {
            return ApiResponse::serverError("Data sender tidak lengkap untuk dealer $kodeDealer (base_url/api_token kosong)");
        }

        $service = new WhatsAppService(
            $waSender->base_url,
            $waSender->api_token,
            $waSender->sender_id
        );

        $limit  = $request->query('limit', 1000);
        $offset = $request->query('offset', 0);

        if (!is_numeric($limit) || $limit < 1 || $limit > 1000) {
            return ApiResponse::validationError('Limit must be between 1 and 1000');
        }

        if (!is_numeric($offset) || $offset < 0) {
            return ApiResponse::validationError('Offset must be 0 or greater');
        }

        $result = $service->getTemplates($senderId, (int)$limit, (int)$offset);

        if ($result['success']) {
            return ApiResponse::success($result['data'], 'Templates list successfully');
        }

        return $this->handleErrorResponse($result);
    }


    public function processQueue(): JsonResponse
    {
        $startTime = microtime(true);
        $maxExecutionTime = 1.5;
        $limit = 25;
        $delay = 0.1;
        $successCount = 0;
        $failedCount = 0;
        $templateCount = 0;

        Log::info('Process queue started');



        $stuckCount = WaMsgTmp::stuck(5)
            ->whereNotIn('keterangan', ['Ulang Tahun'])
            ->count();

        if ($stuckCount > 0) {
            WaMsgTmp::stuck(5)
                ->whereNotIn('keterangan', ['Ulang Tahun'])
                ->update([
                    'status_wa'    => 'pending',
                    'process_time' => null
                ]);
            Log::warning("Reset {$stuckCount} stuck messages to pending");
        }

        $validDealerCodes = WaSender::where('status', true)
            ->whereNotNull('base_url')
            ->whereNotNull('api_token')
            ->whereNotNull('no_hp')
            ->pluck('kode_dealer')
            ->toArray();

        $resetInvalidCount = WaMsgTmp::pending()
            ->whereNotNull('kode_dealer')
            ->whereNotIn('kode_dealer', $validDealerCodes)
            ->count();

        if ($resetInvalidCount > 0) {
            WaMsgTmp::pending()
                ->whereNotNull('kode_dealer')
                ->whereNotIn('kode_dealer', $validDealerCodes)
                ->update(['status_wa' => null]);

            Log::warning("Reset {$resetInvalidCount} pesan dengan kode_dealer tidak terdaftar di WaSender, status_wa -> null");
        }

        // Ambil pending
        $messages = WaMsgTmp::pending()
            ->today()
            ->orderBy('created_at', 'asc')
            ->whereIn('keterangan', [
                'Ulang Tahun',
                'Terima Kasih',
                'Reminder KPB',
                'Booking Servis',
                'STNK',
                'BPKB',
                'PLAT',
                'Buku Servis',
                'Stnk Plat',
                'SPK',
                'PKB',
                'INVOICEH1',
                'INVOICEH23'
            ])
            ->whereNotNull('kode_dealer')
            ->whereNotNull('template_name')
            ->whereIn('kode_dealer', $validDealerCodes)
            ->limit($limit)
            ->get();

        if ($messages->isEmpty()) {
            return ApiResponse::success([
                'processed'            => 0,
                'success'              => 0,
                'failed'               => 0,
                'stuck_reset'          => $stuckCount,
                'invalid_dealer_reset' => $resetInvalidCount
            ], 'No pending messages');
        }


        $groupedByDealer = $messages->groupBy('kode_dealer');

        foreach ($groupedByDealer as $kodeDealer => $pesanGroup) {

            $service = WhatsAppService::fromDealerQueue($kodeDealer);

            if (!$service) {
                // Log::warning("WhatsAppService tidak tersedia untuk dealer {$kodeDealer}, pesan di-skip.", [
                //     'kode_dealer' => $kodeDealer
                // ]);

                foreach ($pesanGroup as $pesan) {
                    $pesan->update([
                        'status_wa'           => 'failed_sending_to_aptana',
                        'process_time'        => now(),
                        'failed_to_aptana_at' => now(),
                        'status'              => '2',
                        'flag_kirim'          => 't'
                    ]);
                    $failedCount++;
                }
                continue;
            }

            foreach ($pesanGroup as $pesan) {


                if ((microtime(true) - $startTime) >= $maxExecutionTime) {
                    Log::warning('Max execution time reached, stopping', [
                        'processed' => $successCount + $failedCount,
                    ]);
                    break 2;
                }

                // Set to PROCESSING
                $pesan->update([
                    'status_wa'    => 'processing',
                    'process_time' => now()
                ]);


                $validationError = $this->validateMessageData($pesan);
                if ($validationError) {
                    $pesan->update([
                        'status_wa'           => 'failed_sending_to_aptana',
                        'process_time'        => now(),
                        'failed_to_aptana_at' => now(),
                        'status'              => '2',
                        'flag_kirim'          => 't'
                    ]);
                    $failedCount++;

                    // Log::error('Message validation failed', [
                    //     'id'          => $pesan->id,
                    //     'kode_dealer' => $kodeDealer,
                    //     'error'       => $validationError
                    // ]);
                    continue;
                }

                // Kirim template message
                $result = $this->sendTemplateFromQueue($pesan, $service);
                $templateCount++;

                if ($result['success']) {
                    $pesan->update([
                        'status_wa'         => 'sent_to_aptana',
                        'process_time'      => now(),
                        'sent_to_aptana_at' => now(),
                        'unique_id'         => $result['unique_id'] ?? null,
                        'status'            => '1',
                        'flag_kirim'        => 't'
                    ]);
                    $successCount++;

                    // Log::info('Template message sent to Aptana successfully', [
                    //     'id'          => $pesan->id,
                    //     'kode_dealer' => $kodeDealer,
                    //     'template'    => $pesan->template_name,
                    //     'recipient'   => $pesan->no_hp,
                    //     'unique_id'   => $result['unique_id'] ?? null
                    // ]);
                } else {
                    $pesan->update([
                        'status_wa'           => 'failed_sending_to_aptana',
                        'process_time'        => now(),
                        'failed_to_aptana_at' => now(),
                        'status'              => '2',
                        'flag_kirim'          => 't'
                    ]);
                    $failedCount++;

                    // Log::error('Failed sending template message to Aptana', [
                    //     'id'          => $pesan->id,
                    //     'kode_dealer' => $kodeDealer,
                    //     'template'    => $pesan->template_name,
                    //     'error'       => $result['error'] ?? 'Unknown error'
                    // ]);
                }

                if ($delay > 0 && !$pesanGroup->last()->is($pesan)) {
                    usleep($delay * 1000000);
                }
            }
        }

        return ApiResponse::success([
            'processed'            => $successCount + $failedCount,
            'success'              => $successCount,
            'failed'               => $failedCount,
            'template_messages'    => $templateCount,
            'stuck_reset'          => $stuckCount,
            'invalid_dealer_reset' => $resetInvalidCount,
            'execution_time'       => round(microtime(true) - $startTime, 2) . 's'
        ], 'Messages processed successfully');
    }


    protected function sendTemplateFromQueue(WaMsgTmp $pesan, WhatsAppService $service): array
    {
        if (empty($pesan->template_name)) {
            Log::error('Template name is empty', ['message_id' => $pesan->id]);
            return ['success' => false, 'error' => 'Template name is required'];
        }

        return $service->sendTemplateMessage(
            $pesan->formatted_phone,
            $pesan->template_name,
            $pesan->template_language ?? 'id',
            $pesan->template_variables ?? []
        );
    }


    public function saveAndSendReport(): void
    {
        $today = now()->format('Y-m-d');
        $todayDisplay = now()->format('d/m/Y');

        $keteranganFilter = ['Ulang Tahun', 'Terima Kasih', 'Reminder KPB', 'Booking Servis', 'STNK', 'BPKB', 'PLAT', 'Buku Servis', 'Stnk Plat', 'SPK', 'PKB', 'INVOICEH1', 'INVOICEH23'];

        $tenMinutesAgo = now()->subMinutes(10);
        $hasRecentProcess = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->whereIn('kode_dealer', ['06732', '06750', '08199'])
            ->where('process_time', '>=', $tenMinutesAgo)
            ->exists();

        if (!$hasRecentProcess) {
            Log::info('No recent process in last 10 minutes, skipping report');
            return;
        }


        $lastProcessTime = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->where('process_time', '>=', $tenMinutesAgo)
            ->max('process_time');

        $lastProcessTimeDisplay = $lastProcessTime ? \Carbon\Carbon::parse($lastProcessTime)->format('H:i:s') : '-';

        // Hitung proses 10 menit terakhir
        $recentTotalDiproses = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->where('process_time', '>=', $tenMinutesAgo)
            ->count();

        $recentSentToAptana = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->where('process_time', '>=', $tenMinutesAgo)
            ->whereNotNull('sent_to_aptana_at')
            ->count();

        $recentFailedToAptana = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->where('process_time', '>=', $tenMinutesAgo)
            ->whereNotNull('failed_to_aptana_at')
            ->count();

        // Pending:
        $pendingCount = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->whereNull('process_time')
            ->where('status_wa', 'pending')
            ->count();

        // Sent to Aptana:
        $sentToAptanaCount = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->whereNotNull('sent_to_aptana_at')
            ->count();

        // Failed to Aptana:
        $failedToAptanaCount = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->whereNotNull('failed_to_aptana_at')
            ->count();

        // Status Webhook (Balikan WhatsApp)
        $sentToCustomerCount = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->whereNotNull('sent_to_customer_at')
            ->whereNull('failed_to_customer_at')
            ->count();

        $deliveredCount = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->whereNotNull('delivered_at')
            ->count();

        $readCount = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->whereNotNull('read_at')
            ->count();

        $repliedCount = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->whereNotNull('replied_at')
            ->count();

        $failedToCustomerCount = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->whereNotNull('failed_to_customer_at')
            ->where('status_wa', 'failed_sent_to_customer')
            ->count();

        // Hitung Ringkasan Total Hari Ini
        $totalPesanHariIni = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->count();

        $belumDiprosesCount = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->whereNull('process_time')
            ->where('status_wa', 'pending')
            ->count();

        $totalDiprosesCount = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->whereNotNull('process_time')
            ->count();

        $totalBerhasilCount = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->whereIn('status_wa', ['sent_to_customer', 'delivered', 'read', 'replied'])
            ->count();

        $totalGagalCount = WaMsgTmp::whereDate('created_at', $today)
            ->whereIn('keterangan', $keteranganFilter)
            ->whereIn('status_wa', ['failed_sending_to_aptana', 'failed_sent_to_customer'])
            ->count();

        // FORMAT REPORT MESSAGE
        $reportMessage = "📊 *LAPORAN PENGIRIMAN WA APTANA*\n";
        $reportMessage .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

        $reportMessage .= "⏰ *Waktu Proses Terakhir: {$lastProcessTimeDisplay}*\n\n";

        $reportMessage .= "📤 *Proses Terakhir:*\n";
        $reportMessage .= "• Total Diproses: {$recentTotalDiproses}\n";
        $reportMessage .= "• Sent to Aptana: {$recentSentToAptana}\n";
        $reportMessage .= "• Failed to Aptana: {$recentFailedToAptana}\n\n";

        // REKAP HARI INI
        $reportMessage .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $reportMessage .= "📈 *REKAP HARI INI ({$todayDisplay})*\n";
        $reportMessage .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $reportMessage .= "🔄 *STATUS INTERNAL (Kirim ke Aptana):*\n";
        $reportMessage .= "• Pending: {$pendingCount}\n";
        $reportMessage .= "• Sent to Aptana: {$sentToAptanaCount}\n";
        $reportMessage .= "• Failed to Aptana: {$failedToAptanaCount}\n\n";

        $reportMessage .= "✅ *STATUS WEBHOOK (Balikan WhatsApp):*\n";
        $reportMessage .= "• Sent to Customer: {$sentToCustomerCount}\n";
        $reportMessage .= "• Delivered: {$deliveredCount}\n";
        $reportMessage .= "• Read: {$readCount}\n";
        $reportMessage .= "• Replied: {$repliedCount}\n";
        $reportMessage .= "• Failed to Customer: {$failedToCustomerCount}\n\n";

        // RINGKASAN TOTAL HARI INI
        $reportMessage .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        $reportMessage .= "📊 *RINGKASAN TOTAL HARI INI:*\n";
        $reportMessage .= "• Total Pesan: {$totalPesanHariIni}\n";
        $reportMessage .= "  (Semua pesan created_at hari ini)\n";
        $reportMessage .= "• Total Diproses: {$totalDiprosesCount}\n";
        $reportMessage .= "• Belum Diproses (Pending): {$belumDiprosesCount}\n";
        $reportMessage .= "• Total Berhasil: {$totalBerhasilCount}\n";
        $reportMessage .= "• Total Gagal: {$totalGagalCount}\n";

        $adminPhones = [
            '085376393555',
            '082289220225',
        ];

        foreach ($adminPhones as $phone) {
            try {
                WaMsgTmp::create([
                    'no_hp' => $phone,
                    'jenis_msg' => 'Text',
                    'status' => '9',
                    'status_wa' => null,
                    'kode_dealer' => 'C10',
                    'module' => null,
                    'message' => $reportMessage,
                    'is_proses' => true,
                    'keterangan' => 'Notifikasi Report Aptana',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                Log::info('Report sent to admin', [
                    'phone' => $phone,
                    'recent_process_time' => $lastProcessTimeDisplay,
                    'recent_stats' => [
                        'total_diproses' => $recentTotalDiproses,
                        'sent_to_aptana' => $recentSentToAptana,
                        'failed_to_aptana' => $recentFailedToAptana,
                    ],
                    'stats' => [
                        'today_pending' => $pendingCount,
                        'today_sent' => $sentToAptanaCount,
                        'today_failed' => $failedToAptanaCount,
                        'today_sent_customer' => $sentToCustomerCount,
                        'today_delivered' => $deliveredCount,
                        'today_read' => $readCount,
                        'today_replied' => $repliedCount,
                        'today_failed_customer' => $failedToCustomerCount,
                        'total_pesan' => $totalPesanHariIni,
                        'belum_diproses' => $belumDiprosesCount,
                        'total_diproses' => $totalDiprosesCount,
                        'total_berhasil' => $totalBerhasilCount,
                        'total_gagal' => $totalGagalCount
                    ]
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send report to admin', [
                    'phone' => $phone,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    protected function validateMessageData(WaMsgTmp $pesan): ?string
    {
        if (empty($pesan->no_hp)) {
            return 'Phone number is required';
        }

        //  text message
        if ($pesan->isText()) {
            if (empty($pesan->message)) {
                return 'Message text is required for text type';
            }
        }

        //  template message
        if ($pesan->isTemplate()) {
            if (empty($pesan->template_name)) {
                return 'Template name is required for template type';
            }
        }

        return null;
    }


    public function webhook(Request $request): JsonResponse
    {
        if (function_exists('fastcgi_finish_request')) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['status' => 200, 'message' => 'OK']);
            if (ob_get_level() > 0) ob_end_flush();
            flush();
            fastcgi_finish_request();
        }

        try {
            $data = $request->all();
            Log::info('Webhook received', ['ip' => $request->ip()]);
            $this->processWebhook($data);
        } catch (\Throwable $e) {
            Log::error('Webhook error', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => basename($e->getFile()),
            ]);
        }

        return response()->json(['status' => 200, 'message' => 'OK']);
    }


    protected function processWebhook(array $data): void
    {
        if (isset($data['wabaId'], $data['payload'])) {
            $payload = is_string($data['payload']) ? json_decode($data['payload'], true) : $data['payload'];

            if (!isset($payload['entry'])) {
                Log::warning('Meta webhook: invalid payload');
                return;
            }

            $timestamp = isset($data['receivedAt'])
                ? Carbon::parse($data['receivedAt'], 'UTC')->timezone(config('app.timezone', 'Asia/Jakarta'))
                : now();

            foreach ($payload['entry'] as $entry) {
                foreach ($entry['changes'] ?? [] as $change) {
                    $value = $change['value'] ?? [];


                    if (!empty($value['messages'])) {
                        $contactMap = collect($value['contacts'] ?? [])
                            ->keyBy('wa_id')
                            ->map(fn($c) => $c['profile']['name'] ?? null)
                            ->all();

                        foreach ($value['messages'] as $msg) {
                            $from = $msg['from'] ?? null;
                            $type = $msg['type'] ?? 'text';

                            if (!$from) continue;

                            $content   = $this->extractMessageContent($msg, $type);
                            $contextId = $msg['context']['id'] ?? null;

                            $record  = null;
                            $matchBy = 'none';

                            //  Prioritas 1: Match by context.id → source_id
                            if ($contextId) {
                                $record = WaMsgTmp::where('source_id', $contextId)
                                    ->where(function ($q) {
                                        $q->whereNull('replied_at')
                                            ->orWhereRaw("jsonb_array_length(message_replied::jsonb) < 5");
                                    })
                                    ->first();

                                if ($record) {
                                    $matchBy = 'context_id';
                                }
                            }

                            //  Prioritas 2: Fallback by no_hp
                            if (!$record) {
                                $record = WaMsgTmp::where('no_hp', 'LIKE', '%' . substr($from, -10))
                                    ->where(function ($q) {
                                        $q->where(function ($sq) {

                                            $sq->where('status_wa', 'replied')
                                                ->whereNotNull('replied_at')
                                                ->whereRaw("jsonb_array_length(message_replied::jsonb) < 5");
                                        })
                                            ->orWhere(function ($sq) {

                                                $sq->whereIn('status_wa', ['sent_to_customer', 'delivered', 'read'])
                                                    ->whereNull('replied_at');
                                            });
                                    })

                                    ->orderByRaw("CASE WHEN status_wa = 'replied' AND replied_at IS NOT NULL THEN 0 ELSE 1 END ASC")
                                    ->orderBy('sent_to_aptana_at', 'desc')
                                    ->first();

                                if ($record) {
                                    $matchBy = 'no_hp_fallback';
                                }
                            }

                            Log::info('Incoming: search result', [
                                'from'       => $from,
                                'context_id' => $contextId,
                                'match_by'   => $matchBy,
                                'record_id'  => $record?->id,
                                'status_wa'  => $record?->status_wa,
                            ]);

                            if ($record) {
                                $existing = [];
                                if (!empty($record->message_replied)) {
                                    $existing = is_array($record->message_replied)
                                        ? $record->message_replied
                                        : [['message' => $record->message_replied, 'received_at' => optional($record->replied_at)->toDateTimeString()]];
                                }

                                $existing[] = [
                                    'message'     => $content,
                                    'received_at' => $timestamp->toDateTimeString(),
                                ];

                                // Batasi maksimal 5
                                $existing = array_slice($existing, -5);

                                $record->update([
                                    'status_wa'       => 'replied',
                                    'replied_at'      => $record->replied_at ?? $timestamp,
                                    'message_replied' => $existing,
                                ]);

                                Log::info('Incoming: customer replied', [
                                    'from'          => $from,
                                    'type'          => $type,
                                    'match_by'      => $matchBy,
                                    'record_id'     => $record->id,
                                    'total_replies' => count($existing),
                                ]);
                            } else {
                                Log::warning('Incoming: no matching record found', [
                                    'from'       => $from,
                                    'type'       => $type,
                                    'context_id' => $contextId,
                                ]);
                            }
                        }
                    }

                    // ── STATUS UPDATE (sent / delivered / read / failed) ──

                    foreach ($value['statuses'] ?? [] as $status) {
                        $metaId    = $status['id'] ?? null;
                        $statusVal = strtolower($status['status'] ?? 'delivered');
                        $recipient = $status['recipient_id'] ?? null;

                        if (!$metaId) continue;


                        $message = WaMsgTmp::where('source_id', $metaId)
                            ->orderBy('sent_to_aptana_at', 'desc')
                            ->first();


                        if (!$message && $recipient) {
                            $message = WaMsgTmp::where('no_hp', 'LIKE', '%' . substr($recipient, -10))
                                ->where('status_wa', 'sent_to_aptana')
                                ->whereNull('source_id')
                                ->orderBy('sent_to_aptana_at', 'desc')
                                ->first();
                        }

                        if (!$message) {
                            Log::info('Meta webhook: status for non-tracked message (skipped)', [
                                'meta_id' => $metaId,
                                'status'  => $statusVal,
                            ]);
                            continue;
                        }

                        $prev           = $message->status_wa;
                        $internalStatus = match ($statusVal) {
                            'sent'      => 'sent_to_customer',
                            'delivered' => 'delivered',
                            'read'      => 'read',
                            'failed'    => 'failed_sent_to_customer',
                            default     => 'delivered',
                        };

                        $update    = ['status_wa' => $internalStatus, 'source_id' => $metaId];
                        $notFailed = !in_array($prev, ['failed_sent_to_customer', 'failed_sending_to_aptana']);

                        if ($internalStatus === 'failed_sent_to_customer') {
                            if (!$message->failed_to_customer_at) {
                                $update['failed_to_customer_at'] = $timestamp;
                            }
                        } else {
                            if (!$message->sent_to_customer_at && $notFailed) {
                                $update['sent_to_customer_at'] = $timestamp;
                            }
                            if (in_array($internalStatus, ['delivered', 'read']) && !$message->delivered_at) {
                                $update['delivered_at'] = $timestamp;
                            }
                            if ($internalStatus === 'read' && !$message->read_at) {
                                $update['read_at'] = $timestamp;
                            }
                        }

                        // ── Update WaMsgTmp ──
                        $message->update($update);

                        Log::info('Meta webhook: updated', [
                            'id'   => $message->id,
                            'prev' => $prev,
                            'new'  => $internalStatus,
                        ]);

                        // ── Hapus file attachment ──
                        if (in_array($internalStatus, ['delivered', 'read', 'failed_sent_to_customer'])) {
                            $fileRecord = WaMsgTmp::where('source_id', $metaId)
                                ->whereIn('keterangan', ['SPK', 'PKB', 'INVOICEH23'])
                                ->whereNotNull('attachment_path')
                                ->first();

                            if ($fileRecord && !empty($fileRecord->attachment_path)) {
                                $filePath = $fileRecord->attachment_path;

                                if (file_exists($filePath)) {
                                    $deleted = unlink($filePath);

                                    if ($deleted) {
                                        $fileRecord->update(['attachment_path' => null]);
                                        Log::info('Attachment berhasil dihapus', [
                                            'message_id' => $fileRecord->id,
                                            'keterangan' => $fileRecord->keterangan,
                                            'status'     => $internalStatus,
                                            'file'       => $filePath,
                                        ]);
                                    } else {
                                        Log::error('Attachment GAGAL dihapus', [
                                            'message_id' => $fileRecord->id,
                                            'file'       => $filePath,
                                        ]);
                                    }
                                } else {
                                    Log::warning('Attachment tidak ditemukan di disk', [
                                        'message_id' => $fileRecord->id,
                                        'file'       => $filePath,
                                    ]);
                                }
                            }
                        }

                        // status ke tbl_sqm ──
                        if (
                            !empty($message->sqm_id) &&
                            in_array($internalStatus, ['delivered', 'read', 'sent_to_customer', 'failed_sent_to_customer'])
                        ) {
                            $sqm = Sqm::where('id_unique', $message->sqm_id)->first();

                            if ($sqm) {
                                if (is_null($sqm->status)) {
                                    $newStatus = $internalStatus === 'failed_sent_to_customer' ? 'Failed' : 'Delivered';
                                    $sqm->update(['status' => $newStatus]);

                                    Log::info('SQM status synced', [
                                        'id_unique'       => $sqm->id_unique,
                                        'message_id'      => $message->id,
                                        'internal_status' => $internalStatus,
                                        'status'          => $newStatus,
                                    ]);
                                }
                            } else {
                                Log::warning('SQM record not found', [
                                    'sqm_id'     => $message->sqm_id,
                                    'message_id' => $message->id,
                                ]);
                            }
                        }
                    }
                }
            }
            return;
        }

        Log::warning('Unknown webhook format', ['keys' => array_keys($data)]);
    }


    public function webhookScan(Request $request): JsonResponse
    {
        if (function_exists('fastcgi_finish_request')) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['status' => 200, 'message' => 'OK']);
            if (ob_get_level() > 0) ob_end_flush();
            flush();
            fastcgi_finish_request();
        }

        try {
            $data = $request->all();
            Log::info('Webhook Menara Agung received', ['ip' => $request->ip()]);

            $payload = is_string($data['payload'] ?? null)
                ? json_decode($data['payload'], true)
                : ($data['payload'] ?? null);

            if (!isset($payload['entry'])) {
                Log::warning('Webhook Menara Agung: invalid payload');
                return response()->json(['status' => 200, 'message' => 'OK']);
            }

            foreach ($payload['entry'] as $entry) {
                foreach ($entry['changes'] ?? [] as $change) {
                    $value = $change['value'] ?? [];

                    if (empty($value['messages'])) continue;

                    $noHpAptana = $value['metadata']['display_phone_number'] ?? null;
                    $contactMap = collect($value['contacts'] ?? [])
                        ->keyBy('wa_id')
                        ->map(fn($c) => $c['profile']['name'] ?? null)
                        ->all();

                    foreach ($value['messages'] as $msg) {
                        $from       = $msg['from'] ?? null;
                        $type       = $msg['type'] ?? 'text';
                        $uniqueId   = $msg['id'] ?? null;
                        $incomingAt = isset($msg['timestamp'])
                            ? Carbon::createFromTimestamp($msg['timestamp'])->timezone(config('app.timezone', 'Asia/Jakarta'))
                            : now();

                        if (!$from) continue;


                        $content = $this->extractMessageContent($msg, $type);


                        $dealerCodes = ['06732', '06750', '08199', '00399', '09164'];
                        $fkDealer    = null;
                        foreach ($dealerCodes as $code) {
                            if (str_contains($content, $code)) {
                                $fkDealer = $code;
                                break;
                            }
                        }

                        // Extract type berdasarkan isi pesan
                        $type_msg = null;
                        if (str_contains($content, 'Salam Satu Hati, saya pembeli motor di Dealer')) {
                            $type_msg = 'H1';
                        } elseif (str_contains($content, 'Salam Satu Hati, saya konsumen service motor di Dealer')) {
                            $type_msg = 'H23';
                        }

                        try {
                            WaWebhook::create([
                                'no_hp'        => $from,
                                'incoming_at'  => $incomingAt,
                                'fk_dealer'    => $fkDealer,
                                'type'         => $type_msg,
                                'nama'         => $contactMap[$from] ?? null,
                                'message'      => $content,
                                'unique_id'    => $uniqueId,
                                'no_hp_aptana' => $noHpAptana,
                            ]);
                            Log::info('Webhook Menara Agung: saved', [
                                'from'     => $from,
                                'type'     => $type,
                                'type_msg' => $type_msg,
                                'dealer'   => $fkDealer,
                            ]);
                        } catch (\Throwable $e) {
                            Log::error('Webhook Menara Agung: save failed', [
                                'error' => $e->getMessage(),
                                'no_hp' => $from,
                            ]);
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('Webhook Menara Agung error', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => basename($e->getFile()),
            ]);
        }

        return response()->json(['status' => 200, 'message' => 'OK']);
    }


    protected function extractMessageContent(array $message, string $type): string
    {
        return match ($type) {
            'text'        => $message['text']['body'] ?? '',
            'image'       => '[Image]'    . (($c = $message['image']['caption']    ?? '') ? " $c" : ''),
            'video'       => '[Video]'    . (($c = $message['video']['caption']    ?? '') ? " $c" : ''),
            'audio'       => '[Audio Message]',
            'sticker'     => '[Sticker]',
            'document'    => '[Document: ' . ($message['document']['filename'] ?? 'document') . ']',
            'contacts'    => '[Contact Card - ' . count($message['contacts'] ?? []) . ' contact(s)]',
            'reaction'    => '[Reaction: ' . ($message['reaction']['emoji'] ?? '') . ']',
            'button'      => '[Button Reply: ' . ($message['button']['text'] ?? '') . ']',
            'location'    => implode(' ', array_filter([
                '[Location]',
                $message['location']['name']    ?? null,
                ($message['location']['address'] ?? null) ? '- ' . $message['location']['address'] : null,
                (isset($message['location']['latitude'], $message['location']['longitude']))
                    ? '(' . $message['location']['latitude'] . ', ' . $message['location']['longitude'] . ')'
                    : null,
            ])),
            'interactive' => match ($message['interactive']['type'] ?? '') {
                'button_reply' => '[Button Reply: ' . ($message['interactive']['button_reply']['title'] ?? '') . ']',
                'list_reply'   => '[List Reply: '   . ($message['interactive']['list_reply']['title']   ?? '') . ']',
                default        => '[Interactive Message]',
            },
            default       => "[Unsupported Message Type: $type]",
        };
    }
}
