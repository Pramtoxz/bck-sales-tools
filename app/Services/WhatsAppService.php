<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Helper\WhatsAppResponse;
use App\Models\Digital\WaSender;

class WhatsAppService
{
    protected string $baseUrl;
    protected string $apiToken;
    protected string $sender;

    public function __construct(string $baseUrl, string $apiToken, ?string $sender = null)
    {
        $this->baseUrl  = $baseUrl;
        $this->apiToken = $apiToken;
        $this->sender   = $sender ?? '';
    }

    public function isConfigured(): bool
    {
        return !empty($this->baseUrl) &&
            !empty($this->apiToken) &&
            !empty($this->sender);
    }

    protected function getHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Api-Token' => $this->apiToken
        ];
    }

    public static function fromDealer(string $kodeDealer): ?self
    {
        $sender = WaSender::where('kode_dealer', $kodeDealer)
            ->where('status', true)
            ->first();

        if (!$sender) {
            // Log::warning('WaSender tidak ditemukan', ['kode_dealer' => $kodeDealer]);
            return null;
        }

        if (empty($sender->base_url) || empty($sender->api_token) || empty($sender->sender_id)) {
            // Log::warning('WaSender data tidak lengkap', ['kode_dealer' => $kodeDealer]);
            return null;
        }

        return new self($sender->base_url, $sender->api_token, $sender->sender_id);
    }

    public static function fromDealerQueue(string $kodeDealer): ?self
    {
        $sender = WaSender::where('kode_dealer', $kodeDealer)
            ->where('status', true)
            ->first();

        if (!$sender) {
            // Log::warning('WaSender tidak ditemukan untuk queue', ['kode_dealer' => $kodeDealer]);
            return null;
        }

        if (empty($sender->base_url) || empty($sender->api_token) || empty($sender->no_hp)) {
            // Log::warning('WaSender data tidak lengkap untuk queue (cek base_url/api_token/no_hp)', ['kode_dealer' => $kodeDealer]);
            return null;
        }


        return new self($sender->base_url, $sender->api_token, $sender->no_hp);
    }


    public function getSenderId(): string
    {
        return $this->sender;
    }

    public function getAccount(): array
    {
        try {
            // Log::info('Getting account information');

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get("{$this->baseUrl}/api/account");

            if ($response->successful()) {
                $data = $response->json();
                // Log::info('Account information successfully', ['data' => $data]);
                return WhatsAppResponse::success($data);
            }

            return WhatsAppResponse::error(
                $response->status(),
                $response->json()
            );
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return WhatsAppResponse::connectionError($e->getMessage());
        } catch (\Exception $e) {
            return WhatsAppResponse::unexpectedError($e->getMessage());
        }
    }


    public function getSenders(int $limit = 10, int $offset = 0): array
    {
        try {
            // Log::info('Getting list of senders', [
            //     'limit' => $limit,
            //     'offset' => $offset
            // ]);

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get("{$this->baseUrl}/api/account/senders", [
                    'page[limit]' => $limit,
                    'page[offset]' => $offset
                ]);

            if ($response->successful()) {
                $data = $response->json();
                // Log::info('Senders list successfully', [
                //     'count' => count($data['data']['docs'] ?? [])
                // ]);
                return WhatsAppResponse::success($data);
            }

            return WhatsAppResponse::error(
                $response->status(),
                $response->json()
            );
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return WhatsAppResponse::connectionError($e->getMessage());
        } catch (\Exception $e) {
            return WhatsAppResponse::unexpectedError($e->getMessage());
        }
    }


    public function getTemplates(string $senderId, int $limit = 1000, int $offset = 0): array
    {
        try {
            // Log::info('Getting list of templates', [
            //     'sender_id' => $senderId,
            //     'limit' => $limit,
            //     'offset' => $offset
            // ]);

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get("{$this->baseUrl}/api/account/senders/{$senderId}/templates", [
                    'page[limit]' => $limit,
                    'page[offset]' => $offset
                ]);

            if ($response->successful()) {
                $data = $response->json();
                // Log::info('Templates list successfully', [
                //     'count' => count($data['data']['docs'] ?? [])
                // ]);
                return WhatsAppResponse::success($data);
            }

            return WhatsAppResponse::error(
                $response->status(),
                $response->json()
            );
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return WhatsAppResponse::connectionError($e->getMessage());
        } catch (\Exception $e) {
            return WhatsAppResponse::unexpectedError($e->getMessage());
        }
    }

    public function sendTemplateMessage(string $recipient, string $templateName, string $languageCode = 'id', ?array $variables = null): array
    {
        try {
            // Log::info('Sending template message', [
            //     'recipient' => $recipient,
            //     'sender' => $this->sender,
            //     'template' => $templateName,
            //     'language' => $languageCode,
            //     'variables' => $variables
            // ]);

            $payload = [
                'channel' => 'wa',
                'sender' => $this->sender,
                'recipient' => $recipient,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => [
                        'code' => $languageCode
                    ]
                ]
            ];

            // variables tidak null dan ada key 'header', 'body', atau 'buttons'
            if ($variables !== null && (
                array_key_exists('header', $variables) ||
                array_key_exists('body', $variables) ||
                array_key_exists('buttons', $variables)
            )) {
                $components = $this->buildTemplateComponents($variables);

                if (!empty($components)) {
                    $payload['template']['components'] = $components;
                }
            }

            // // Log::debug('Template payload', ['payload' => $payload]);

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(5)
                ->connectTimeout(2)
                ->post("{$this->baseUrl}/api/v1/messages", $payload);

            if ($response->successful()) {
                $data = $response->json();
                $uniqueId = $data['data']['uniqueId'] ?? null;

                // Log::info('Template message sent successfully', [
                //     'unique_id' => $uniqueId,
                //     'recipient' => $recipient,
                //     'template' => $templateName
                // ]);

                return WhatsAppResponse::success($data, $uniqueId);
            }

            return WhatsAppResponse::error(
                $response->status(),
                $response->json()
            );
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return WhatsAppResponse::connectionError($e->getMessage());
        } catch (\Exception $e) {
            return WhatsAppResponse::unexpectedError($e->getMessage());
        }
    }

    protected function buildTemplateComponents(array $variables): array
    {
        $components = [];

        // ── HEADER
        if (array_key_exists('header', $variables) && $variables['header'] !== null && !empty($variables['header'])) {
            $header = $variables['header'];

            // Case 1: HEADER TEXT
            if (isset($header['type']) && $header['type'] === 'text' && isset($header['values']) && is_array($header['values'])) {
                $parameters = [];
                foreach ($header['values'] as $value) {
                    $parameters[] = [
                        'type' => 'text',
                        'text' => (string)$value
                    ];
                }

                $components[] = [
                    'type' => 'header',
                    'parameters' => $parameters
                ];
            }
            // Case 2: HEADER MEDIA (image/video/document)
            elseif (isset($header['type']) && in_array($header['type'], ['image', 'video', 'document']) && isset($header['link'])) {

                $mediaObject = ['link' => $header['link']];

                // Tambahkan filename jika ada (khusus document/PDF)
                if ($header['type'] === 'document' && !empty($header['filename'])) {
                    $mediaObject['filename'] = $header['filename'];
                }

                $components[] = [
                    'type' => 'header',
                    'parameters' => [
                        [
                            'type' => $header['type'],
                            $header['type'] => $mediaObject
                        ]
                    ]
                ];
            }
        }

        // ── BODY
        if (array_key_exists('body', $variables) && $variables['body'] !== null && is_array($variables['body']) && !empty($variables['body'])) {
            $parameters = [];

            foreach ($variables['body'] as $value) {
                $parameters[] = [
                    'type' => 'text',
                    'text' => (string)$value
                ];
            }

            if (!empty($parameters)) {
                $components[] = [
                    'type' => 'body',
                    'parameters' => $parameters
                ];
            }
        }

        // ── BUTTONS
        if (array_key_exists('buttons', $variables) && $variables['buttons'] !== null && is_array($variables['buttons']) && !empty($variables['buttons'])) {
            foreach ($variables['buttons'] as $index => $button) {
                $buttonType = $button['sub_type'] ?? null;

                // CTA - Visit Website (URL)
                if ($buttonType === 'url' && isset($button['url'])) {
                    $components[] = [
                        'type'       => 'button',
                        'sub_type'   => 'url',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => $button['url']
                            ]
                        ]
                    ];
                }

                // CTA - Phone Number
                elseif ($buttonType === 'phone_number' && isset($button['phone_number'])) {
                    $components[] = [
                        'type'       => 'button',
                        'sub_type'   => 'phone_number',
                        'parameters' => [
                            [
                                'type'         => 'phone_number',
                                'phone_number' => $button['phone_number']
                            ]
                        ]
                    ];
                }

                // Quick Reply
                elseif ($buttonType === 'quick_reply' && isset($button['payload'])) {
                    $components[] = [
                        'type'       => 'button',
                        'sub_type'   => 'quick_reply',
                        'parameters' => [
                            [
                                'type'    => 'payload',
                                'payload' => $button['payload']
                            ]
                        ]
                    ];
                }
            }
        }

        return $components;
    }
}
