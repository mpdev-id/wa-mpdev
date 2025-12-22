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

            // Find device by session ID
            $device = Device::where('uuid', $deviceId)->first();
            
            if (!$device) {
                return response()->json(['reply' => null], 200);
            }

            // Check if AI is enabled for this device
            $aiResponse = AiResponse::where('device_id', $device->id)
                ->where('is_active', true)
                ->first();

            if (!$aiResponse) {
                return response()->json(['reply' => null], 200);
            }

            // Check if should reply to groups
            $isGroup = strpos($sender, '@g.us') !== false;
            if ($isGroup && !$aiResponse->reply_to_groups) {
                return response()->json(['reply' => null], 200);
            }

            // Generate AI response
            $userAiController = new UserAiResponseController();
            $reply = $userAiController->generateResponse($message, $device->id, $sender);

            return response()->json([
                'reply' => $reply
            ], 200);

        } catch (\Exception $e) {
            \Log::error('AI API Error: ' . $e->getMessage());
            return response()->json(['reply' => null], 200);
        }
    }
}
