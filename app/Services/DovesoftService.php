<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DovesoftService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected string $wabaNumber;

    public function __construct()
    {
        $this->apiUrl     = (string) config('services.dovesoft.api_url');
        $this->apiKey     = (string) config('services.dovesoft.key');
        $this->wabaNumber = (string) config('services.dovesoft.waba_number');
    }

    public function sendTemplate(
        string $to,
        string $templateName,
        string $language,
        array $bodyParams = [],
        array $document = [],
        array $extra = []
    ): array {
        $components = [];

        // HEADER → DOCUMENT
        if (!empty($document['link'])) {
            $components[] = [
                'type' => 'header',
                'parameters' => [
                    [
                        'type' => 'document',
                        'document' => [
                            'link'     => $document['link'],
                            'filename' => $document['filename'] ?? 'document.pdf',
                        ],
                    ],
                ],
            ];
        }

        // BODY → {{1}}, {{2}}
        if (!empty($bodyParams)) {
            $components[] = [
                'type' => 'body',
                'parameters' => array_map(
                    fn ($value) => [
                        'type' => 'text',
                        'text' => (string) $value,
                    ],
                    $bodyParams
                ),
            ];
        }

        $template = [
            'name'     => $templateName,
            'language' => [
                'code'   => $language,
                'policy' => 'deterministic',
            ],
        ];

        if (!empty($components)) {
            $template['components'] = $components;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to'   => $to,
            'type' => 'template',
            'template' => $template,
        ];

        Log::info('WhatsApp TEMPLATE payload', [
            'payload' => json_encode($payload, JSON_UNESCAPED_SLASHES),
        ]);

        return $this->sendRequest($payload, $extra);
    }

    protected function sendRequest(array $payload, array $extra = []): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Key'          => $this->apiKey,
            'wabaNumber'   => $this->wabaNumber,
        ];

        $response = Http::withHeaders($headers)
            ->post($this->apiUrl, $payload);

        Log::info('WhatsApp API response', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return [
            'status'     => $response->status(),
            'successful' => $response->successful(),
            'raw'        => $response->body(),
            'decoded'    => json_decode($response->body(), true),
            'sent_body'  => json_encode($payload, JSON_UNESCAPED_SLASHES),
        ];
    }
}
