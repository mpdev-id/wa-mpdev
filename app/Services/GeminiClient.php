<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiClient
{
    private $session = null;
    private $requestId = 1;

    public function __construct()
    {
        $this->session = null;
        $this->requestId = 1;
    }

    public function init()
    {
        try {
            $response = Http::withHeaders([
                'user-agent' => 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36'
            ])->get('https://gemini.google.com/');

            $html = $response->body();

            preg_match('/"SNlM0e":"(.*?)"/', $html, $matchA);
            preg_match('/"cfb2h":"(.*?)"/', $html, $matchB);
            preg_match('/"FdrFJe":"(.*?)"/', $html, $matchC);

            $this->session = [
                'a' => $matchA[1] ?? '',
                'b' => $matchB[1] ?? '',
                'c' => $matchC[1] ?? ''
            ];

            return $this->session;
        } catch (\Exception $e) {
            Log::error('Gemini init failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function ask($message)
    {
        try {
            if (!$this->session) {
                $this->init();
            }

            $payload = [null, json_encode([[$message, 0, null, null, null, null, 0]])];

            $queryParams = http_build_query([
                'bl' => $this->session['b'],
                'f.sid' => $this->session['c'],
                'hl' => 'id',
                '_reqid' => $this->requestId++,
                'rt' => 'c'
            ]);

            $response = Http::withHeaders([
                'content-type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                'user-agent' => 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Mobile Safari/537.36',
                'x-same-domain' => '1'
            ])->asForm()->post(
                "https://gemini.google.com/_/BardChatUi/data/assistant.lamda.BardFrontendService/StreamGenerate?{$queryParams}",
                [
                    'f.req' => json_encode($payload),
                    'at' => $this->session['a']
                ]
            );

            return $this->parse($response->body());
        } catch (\Exception $e) {
            Log::error('Gemini ask failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function parse($text)
    {
        $lines = explode("\n", $text);
        $lastResult = null;

        foreach ($lines as $line) {
            if (strpos($line, '["wrb.fr"') === 0) {
                try {
                    $decoded = json_decode($line, true);
                    if (isset($decoded[0][2])) {
                        $data = json_decode($decoded[0][2], true);
                        if (isset($data[4][0][1])) {
                            $responseText = is_array($data[4][0][1]) 
                                ? $data[4][0][1][0] 
                                : $data[4][0][1];
                            
                            $lastResult = ['text' => $responseText];
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }

        return $lastResult;
    }
}
