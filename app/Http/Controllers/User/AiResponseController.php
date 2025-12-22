<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AiResponse;
use App\Models\Device;
use Auth;
use Http;

class AiResponseController extends Controller
{
    public function index()
    {
        $devices = Device::where('user_id', Auth::id())->where('status', 1)->get();
        $aiSettings = AiResponse::where('user_id', Auth::id())->get()->keyBy('device_id');
        
        return view('user.ai-response.index', compact('devices', 'aiSettings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|exists:devices,id',
            'ai_type' => 'required|in:default,custom',
            'gemini_api_key' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'reply_to_groups' => 'nullable|boolean',
            'model' => 'nullable|string',
            'system_prompt' => 'nullable|string|max:1000',
            'max_tokens' => 'integer|min:100|max:2000',
            'temperature' => 'numeric|min:0|max:1',
        ]);

        // Validate API key for custom AI
        if ($request->ai_type === 'custom') {
            $request->validate([
                'gemini_api_key' => 'required|string'
            ]);

            // Test API key with selected model
            $testResult = $this->testGeminiApi($request->gemini_api_key, $request->model ?? 'gemini-2.5-flash');
            
            if (!$testResult['success']) {
                return response()->json([
                    'message' => $testResult['message']
                ], 400);
            }
        }

        AiResponse::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'device_id' => $request->device_id
            ],
            [
                'ai_type' => $request->ai_type,
                'gemini_api_key' => $request->ai_type === 'custom' ? $request->gemini_api_key : null,
                'reply_to_groups' => $request->reply_to_groups ?? false,
                'model' => $request->model ?? 'gemini-2.5-flash',
                'system_prompt' => $request->system_prompt,
                'max_tokens' => $request->max_tokens ?? 500,
                'temperature' => $request->temperature ?? 0.7,
            ]
        );

        return response()->json([
            'message' => __('AI Response settings saved successfully')
        ], 200);
    }

    public function toggle(Request $request, $id)
    {
        $aiResponse = AiResponse::where('user_id', Auth::id())->findOrFail($id);
        $aiResponse->is_active = !$aiResponse->is_active;
        $aiResponse->save();

        return response()->json([
            'message' => $aiResponse->is_active ? __('AI Response activated') : __('AI Response deactivated'),
            'is_active' => $aiResponse->is_active
        ], 200);
    }

    public function destroy($id)
    {
        $aiResponse = AiResponse::where('user_id', Auth::id())->findOrFail($id);
        $aiResponse->delete();

        return response()->json([
            'message' => __('AI Response settings deleted successfully')
        ], 200);
    }

    private function testGeminiApi($apiKey, $model = 'gemini-2.5-flash')
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => 'Say "OK" if you receive this']
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'API key is valid'];
            }

            $errorBody = $response->json();
            $errorMessage = $errorBody['error']['message'] ?? 'Invalid API key';
            
            return ['success' => false, 'message' => $errorMessage];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to validate API key: ' . $e->getMessage()];
        }
    }

    public function generateResponse($message, $deviceId, $sender)
    {
        $aiResponse = AiResponse::where('device_id', $deviceId)
            ->where('is_active', true)
            ->first();

        if (!$aiResponse) {
            \Log::warning('AI Response not found for device', ['device_id' => $deviceId]);
            return null;
        }

        try {
            // Use Default AI (Free Gemini)
            if ($aiResponse->ai_type === 'default') {
                return $this->generateDefaultAiResponse($message, $aiResponse, $deviceId, $sender);
            }
            
            // Use Custom AI (API Key)
            return $this->generateCustomAiResponse($message, $aiResponse, $deviceId, $sender);
            
        } catch (\Exception $e) {
            \Log::error('AI Response Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return "🤖 Maaf, terjadi kesalahan. Silakan hubungi administrator.";
        }
    }

    private function generateDefaultAiResponse($message, $aiResponse, $deviceId, $sender)
    {
        try {
            $geminiClient = new \App\Services\GeminiClient();
            
            // Build context with system prompt
            $systemPrompt = $aiResponse->system_prompt ?? 'You are a helpful WhatsApp assistant. Keep responses concise and friendly.';
            $fullMessage = $systemPrompt . "\n\nUser: " . $message;
            
            $result = $geminiClient->ask($fullMessage);
            
            if ($result && isset($result['text'])) {
                $reply = $result['text'];
                
                // Save to history
                \App\Models\AiConversationHistory::create([
                    'device_id' => $deviceId,
                    'sender' => $sender,
                    'role' => 'user',
                    'message' => $message,
                ]);

                \App\Models\AiConversationHistory::create([
                    'device_id' => $deviceId,
                    'sender' => $sender,
                    'role' => 'assistant',
                    'message' => $reply,
                ]);

                $this->cleanupOldMessages($deviceId, $sender);
                
                return $reply;
            }
            
            return "🤖 AI sedang tidak tersedia. Silakan coba lagi nanti.";
            
        } catch (\Exception $e) {
            \Log::error('Default AI Error: ' . $e->getMessage());
            return "🤖 AI sedang tidak tersedia. Silakan coba lagi nanti.";
        }
    }

    private function generateCustomAiResponse($message, $aiResponse, $deviceId, $sender)
    {
        // Get conversation history (last 10 messages)
        $history = \App\Models\AiConversationHistory::where('device_id', $deviceId)
            ->where('sender', $sender)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->reverse(); // Oldest first

        // Build conversation context
        $contents = [];
        
        // Add system prompt as first message
        $systemPrompt = $aiResponse->system_prompt ?? 'You are a helpful WhatsApp assistant. Keep responses concise and friendly.';
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $systemPrompt]]
        ];
        $contents[] = [
            'role' => 'model',
            'parts' => [['text' => 'Understood. I will be a helpful WhatsApp assistant.']]
        ];

        // Add conversation history
        foreach ($history as $msg) {
            $contents[] = [
                'role' => $msg->role === 'user' ? 'user' : 'model',
                'parts' => [['text' => $msg->message]]
            ];
        }

        // Add current message
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $message]]
        ];

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$aiResponse->model}:generateContent?key={$aiResponse->gemini_api_key}";
        
        $payload = [
            'contents' => $contents,
            'generationConfig' => [
                'maxOutputTokens' => $aiResponse->max_tokens,
                'temperature' => $aiResponse->temperature,
            ]
        ];
        
        \Log::info('Sending to Gemini with history', [
            'model' => $aiResponse->model, 
            'history_count' => count($history),
            'sender' => $sender
        ]);
        
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, $payload);

        \Log::info('Gemini response', ['status' => $response->status()]);
        
        if ($response->successful()) {
            $data = $response->json();
            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            
            if ($reply) {
                // Save user message to history
                \App\Models\AiConversationHistory::create([
                    'device_id' => $deviceId,
                    'sender' => $sender,
                    'role' => 'user',
                    'message' => $message,
                ]);

                // Save AI response to history
                \App\Models\AiConversationHistory::create([
                    'device_id' => $deviceId,
                    'sender' => $sender,
                    'role' => 'assistant',
                    'message' => $reply,
                ]);

                // Cleanup old messages (keep last 50 per sender)
                $this->cleanupOldMessages($deviceId, $sender);
            }
            
            return $reply;
        } else {
            \Log::error('Gemini API failed', ['status' => $response->status(), 'body' => $response->body()]);
            
            $errorBody = $response->json();
            if (isset($errorBody['error']['message'])) {
                return "🤖 AI: " . $errorBody['error']['message'] . "\n\nPlease try again or contact support.";
            }
            return "🤖 AI sedang tidak tersedia. Silakan coba lagi nanti.";
        }
    }

    private function cleanupOldMessages($deviceId, $sender)
    {
        // Keep only last 50 messages per sender
        $messagesToDelete = \App\Models\AiConversationHistory::where('device_id', $deviceId)
            ->where('sender', $sender)
            ->orderBy('created_at', 'desc')
            ->skip(50)
            ->pluck('id');

        if ($messagesToDelete->count() > 0) {
            \App\Models\AiConversationHistory::whereIn('id', $messagesToDelete)->delete();
            \Log::info('Cleaned up old messages', ['count' => $messagesToDelete->count()]);
        }
    }
}
