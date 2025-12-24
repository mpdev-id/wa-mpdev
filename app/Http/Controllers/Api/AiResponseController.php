<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AiResponse;
use App\Models\Device;
use App\Http\Controllers\User\AiResponseController as UserAiResponseController;

class AiResponseController extends Controller
{
    public function generate(Request $request)
    {
        try {
            $deviceId = $request->device_id;
            $sender = $request->sender;
            $message = $request->message;

            \Log::info('AI API Request', [
                'device_id' => $deviceId,
                'sender' => $sender,
                'message_length' => strlen($message)
            ]);

            // Find device by session ID
            $device = Device::where('uuid', $deviceId)->first();
            
            if (!$device) {
                \Log::warning('Device not found', ['device_id' => $deviceId]);
                return response()->json(['reply' => null, 'error' => 'Device not found'], 404);
            }

            // Check if AI is enabled for this device
            $aiResponse = AiResponse::where('device_id', $device->id)
                ->where('is_active', true)
                ->first();

            if (!$aiResponse) {
                \Log::info('AI not enabled for device', ['device_id' => $device->id]);
                return response()->json(['reply' => null], 200);
            }

            // Check if should reply to groups
            $isGroup = strpos($sender, '@g.us') !== false;
            if ($isGroup && !$aiResponse->reply_to_groups) {
                \Log::info('Group messages disabled', ['sender' => $sender]);
                return response()->json(['reply' => null], 200);
            }

            \Log::info('Generating AI response', [
                'ai_type' => $aiResponse->ai_type,
                'model' => $aiResponse->model
            ]);

            // Generate AI response
            $userAiController = new UserAiResponseController();
            $reply = $userAiController->generateResponse($message, $device->id, $sender);

            if ($reply) {
                \Log::info('AI response generated successfully', ['reply_length' => strlen($reply)]);
            } else {
                \Log::warning('AI response is null');
            }

            return response()->json([
                'reply' => $reply
            ], 200);

        } catch (\Exception $e) {
            \Log::error('AI API Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['reply' => null, 'error' => $e->getMessage()], 500);
        }
    }
}
