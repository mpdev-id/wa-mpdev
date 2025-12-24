<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UnlimitedAIClient
{
    private $baseUrl = 'https://unlimitedai.org';
    private $chatUrl = 'https://unlimitedai.org/chat/';
    private $ajaxUrl = 'https://unlimitedai.org/wp-admin/admin-ajax.php';
    
    private $botId = 'defaultBotId';
    private $chatbotIdentity = 'defaultChatbotIdentity';
    private $clientId = 'defaultClientId';
    private $postId = 'defaultPostId';
    
    private $sessionId;
    private $nonce = null;
    private $nonceTimestamp = 0;
    private $chatHistory = [];
    private $cookies = [];

    public function __construct()
    {
        $this->sessionId = Str::uuid()->toString();
    }

    public function ask($message, $retryCount = 0)
    {
        try {
            Log::info('UnlimitedAI: Sending message', ['message' => substr($message, 0, 50)]);
            
            $this->chatHistory[] = ['role' => 'user', 'content' => $message];
            
            $currentNonce = $this->getCachedNonceOrFetch();
            $response = $this->sendToOptimizedChatGptApi($message, $currentNonce);
            
            $this->chatHistory[] = ['role' => 'assistant', 'content' => $response];
            
            Log::info('UnlimitedAI: Response received', ['length' => strlen($response)]);
            
            return ['text' => $response];
        } catch (\Exception $e) {
            Log::error('UnlimitedAI: Error', ['error' => $e->getMessage()]);
            
            if ($retryCount < 2) {
                Log::info('UnlimitedAI: Retrying...', ['attempt' => $retryCount + 1]);
                return $this->ask($message, $retryCount + 1);
            }
            
            throw $e;
        }
    }

    private function getCachedNonceOrFetch()
    {
        $now = time();
        $fiveMinutes = 5 * 60;
        
        if ($this->nonce && ($now - $this->nonceTimestamp) < $fiveMinutes) {
            Log::info('UnlimitedAI: Using cached nonce');
            return $this->nonce;
        }
        
        return $this->getNonceViaHttp();
    }

    private function getNonceViaHttp()
    {
        try {
            Log::info('UnlimitedAI: Getting fresh nonce');
            
            $response = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.9',
                ])
                ->get($this->chatUrl);
            
            if ($response->successful()) {
                $html = $response->body();
                
                // Try multiple patterns to find nonce
                $patterns = [
                    '/wpaicgParams.*?"search_nonce":"([^"]+)"/i',
                    '/"search_nonce":"([^"]+)"/i',
                    '/"_ajax_nonce":"([^"]+)"/i',
                    "/'_ajax_nonce':'([^']+)'/i",
                    '/_ajax_nonce["\']?\s*:\s*["\']([^"\']+)/i',
                    '/nonce["\']?\s*:\s*["\']([a-f0-9]{10})/i',
                    '/ajax_nonce["\']?\s*:\s*["\']([^"\']+)/i'
                ];
                
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $html, $matches)) {
                        $this->nonce = $matches[1];
                        $this->nonceTimestamp = time();
                        Log::info('UnlimitedAI: Found and cached nonce', ['nonce' => $this->nonce]);
                        return $this->nonce;
                    }
                }
                
                // Fallback nonce
                $this->nonce = '3c4b7dd456';
                $this->nonceTimestamp = time();
                Log::warning('UnlimitedAI: No nonce found, using fallback', ['nonce' => $this->nonce]);
                return $this->nonce;
            }
            
            throw new \Exception('Failed to load chat page: ' . $response->status());
        } catch (\Exception $e) {
            Log::error('UnlimitedAI: Error getting nonce', ['error' => $e->getMessage()]);
            
            // Use fallback nonce
            $this->nonce = '3c4b7dd456';
            $this->nonceTimestamp = time();
            Log::info('UnlimitedAI: Using error fallback nonce', ['nonce' => $this->nonce]);
            return $this->nonce;
        }
    }

    private function sendToOptimizedChatGptApi($message, $currentNonce)
    {
        try {
            $formData = [
                'action' => 'wpaicg_chat_shortcode_message',
                'message' => $message,
                '_wpnonce' => $currentNonce,
                'post_id' => $this->postId,
                'bot_id' => $this->botId,
                'chatbot_identity' => $this->chatbotIdentity,
                'wpaicg_chat_client_id' => $this->clientId,
            ];
            
            Log::info('UnlimitedAI: Sending to API');
            
            $response = Http::timeout(120)
                ->asForm()
                ->withHeaders([
                    'X-Requested-With' => 'XMLHttpRequest',
                    'Origin' => $this->baseUrl,
                    'Referer' => $this->chatUrl,
                    'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36',
                ])
                ->post($this->ajaxUrl, $formData);
            
            if ($response->successful()) {
                return $this->parseStreamResponse($response->body());
            }
            
            throw new \Exception('API request failed: ' . $response->status());
        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function parseStreamResponse($streamText)
    {
        $result = '';
        $lines = explode("\n", $streamText);
        
        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            
            if (strpos($trimmedLine, 'data: ') === 0) {
                $data = substr($trimmedLine, 6);
                
                if ($data === '[DONE]') {
                    break;
                }
                
                try {
                    $json = json_decode($data, true);
                    
                    if (isset($json['choices']) && count($json['choices']) > 0) {
                        $choice = $json['choices'][0];
                        
                        if (isset($choice['delta']['content'])) {
                            $result .= $choice['delta']['content'];
                        }
                    }
                } catch (\Exception $e) {
                    // Skip invalid JSON
                }
            }
        }
        
        return trim($result);
    }

    public function resetSession()
    {
        $this->sessionId = Str::uuid()->toString();
        $this->nonce = null;
        $this->nonceTimestamp = 0;
        $this->chatHistory = [];
        $this->cookies = [];
    }
}
