<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Smstransaction;
use App\Models\Smstesttransactions;
use App\Http\Requests\Bulkrequest;
use App\Models\User;
use App\Models\App;
use App\Models\Device;
use App\Models\Contact;
use App\Models\Template;
use App\Models\Reply;
use App\Models\Webhook;
use Carbon\Carbon;
use App\Traits\Whatsapp;
use Http;
use Auth;
use Str;
use DB;
use Session;
use Exception;

class BulkController extends Controller
{
    use Whatsapp;

    private function convertPhoneNumber($number) {
        // Remove any non-digit characters
        $number = preg_replace('/\D+/', '', $number);
        // If number starts with '0', replace with '62'
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }
        return $number;
    }

    /**
     * sent message
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submitRequest(Bulkrequest $request)
    {
        try {
            // Convert the phone number
            $request->to = $this->convertPhoneNumber($request->to);

            // dd($request->all());
            $user = User::where('status', 1)->where('will_expire', '>', now())->where('authkey', $request->authkey)->first();
            $app = App::where('key', $request->appkey)->whereHas('device')->with('device')->where('status', 1)->first();

            if ($user == null || $app == null) {
                return response()->json(['error' => 'Invalid Auth and AppKey'], 401);
            }

            if (getUserPlanData('messages_limit', $user->id) == false) {
                return response()->json([
                    'message' => __('Maximum Monthly Messages Limit Exceeded')
                ], 401);
            }

            if (!empty($request->template_id)) {
                $template = Template::where('user_id', $user->id)->where('uuid', $request->template_id)->where('status', 1)->first();
                if (empty($template)) {
                    return response()->json(['error' => 'Template Not Found'], 401);
                }

                if (isset($template->body['text'])) {
                    $body = $template->body;
                    $text = $this->formatText($template->body['text'], [], $user);
                    $text = $this->formatCustomText($text, $request->variables ?? []);
                    $body['text'] = $text;
                } else {
                    $body = $template->body;
                }
                $type = $template->type;
            } else {
                $text = $this->formatText($request->message);
                if (!empty($request->file)) {
                    $explode = explode('.', $request->file);
                    $file_type = strtolower(end($explode));
                    $extentions = [
                        'jpg' => 'image',
                        'jpeg' => 'image',
                        'png' => 'image',
                        'webp' => 'image',
                        'pdf' => 'document',
                        'docx' => 'document',
                        'xlsx' => 'document',
                        'csv' => 'document',
                        'txt' => 'document'
                    ];

                    if (!isset($extentions[$file_type])) {
                        $validators['error'] = 'file type should be jpg,jpeg,png,webp,pdf,docx,xlsx,csv,txt';
                        return response()->json($validators, 403);
                    }

                    $body[$extentions[$file_type]] = ['url' => $request->file];
                    $body['caption'] = $text;
                    $type = 'text-with-media';
                } else {
                    $body['text'] = $text;
                    $type = 'plain-text';
                }
            }

            if (!isset($body)) {
                return response()->json(['error' => 'Request Failed'], 401);
            }

            $response = $this->messageSend($body, $app->device_id, $request->to, $type, true);
            
            if ($response['status'] == 200) {
                $logs['user_id'] = $user->id;
                $logs['device_id'] = $app->device_id;
                $logs['app_id'] = $app->id;
                $logs['from'] = $app->device->phone ?? null;
                $logs['to'] = $request->to;
                $logs['template_id'] = $template->id ?? null;
                $logs['type'] = 'from_api';

                $this->saveLog($logs);

                return response()->json(['message_status' => 'Success', 'data' => [
                    'from' => $app->device->phone ?? null,
                    'to' => $request->to,
                    'status_code' => 200,
                ]], 200);
            } else {
                return response()->json(['error' => 'Request Failed'], 401);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Request Failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * set status device
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setStatus($device_id, $status)
    {
        try {
            $device_id = str_replace('device_', '', $device_id);
            $device = Device::where('id', $device_id)->first();
            if (!empty($device)) {
                $device->status = $status;
                $device->save();
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to set device status: ' . $e->getMessage()], 500);
        }
    }

    /**
     * receive webhook response
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function webHook(Request $request, $device_id)
    {
        try {
            \Log::info('=== WEBHOOK RECEIVED ===', [
                'raw_device_id' => $device_id,
                'from' => $request->from,
                'message_data' => $request->message,
                'all_request' => $request->all()
            ]);
            
            $session = $device_id;
            $device_id = str_replace('device_', '', $device_id);

            $device = Device::with('user')->whereHas('user', function ($query) {
                return $query->where('will_expire', '>', now());
            })->where('id', $device_id)->first();
            
            \Log::info('Device found', ['device' => $device ? 'yes' : 'no', 'device_id' => $device_id]);

            $request_from = explode('@', $request->from);
            $request_from = $request_from[0];

            $message_id = $request->message_id ?? null;
            $message = json_encode($request->message ?? '');
            $message = json_decode($message);
            
            // Extract quoted/replied message for context
            $quotedMessage = null;
            if (isset($message->extendedTextMessage->contextInfo->quotedMessage)) {
                $quoted = $message->extendedTextMessage->contextInfo->quotedMessage;
                if (isset($quoted->conversation)) {
                    $quotedMessage = $quoted->conversation;
                } elseif (isset($quoted->extendedTextMessage->text)) {
                    $quotedMessage = $quoted->extendedTextMessage->text;
                }
            }

            // Extract main message
            $buttonId = null;
            if (isset($message->conversation)) {
                $message = $message->conversation ?? null;
            } elseif (isset($message->extendedTextMessage)) {
                $message = $message->extendedTextMessage->text ?? null;
            } elseif (isset($message->buttonsResponseMessage)) {
                $buttonId = $message->buttonsResponseMessage->selectedButtonId ?? null;
                $message = $message->buttonsResponseMessage->selectedDisplayText ?? null;
            } elseif (isset($message->templateButtonReplyMessage)) {
                // Handle quick reply button response
                $buttonId = $message->templateButtonReplyMessage->selectedId ?? null;
                $message = $message->templateButtonReplyMessage->selectedDisplayText ?? null;
            } elseif (isset($message->listResponseMessage)) {
                $message = $message->listResponseMessage->title ?? null;
            } else {
                $message = null;
            }
            
            \Log::info('Message extracted', [
                'message' => $message, 
                'length' => strlen($message ?? ''),
                'has_quoted' => !empty($quotedMessage),
                'quoted_message' => $quotedMessage ? substr($quotedMessage, 0, 50) : null
            ]);


            if ($device->hook_url) {
                $hook = new Webhook;
                $hook->device_id = $device->id;
                $hook->user_id = $device->user_id;
                $hook->payload = json_encode([
                    'payload' => $request->message ?? '',
                    'sender' => $request_from ?? '',
                    'receiver' => $device->phone ?? '',
                ]);
                $hook->hook = $device->hook_url;
                $hook->save();
            }

            if ($device != null && $message != null) {
                
                // ===== CHECK AI RESPONSE FIRST =====
                $aiResponse = \App\Models\AiResponse::where('device_id', $device_id)
                    ->where('is_active', true)
                    ->first();
                
                \Log::info('AI check', ['found' => $aiResponse ? 'yes' : 'no', 'device_id' => $device_id, 'message' => $message]);
                
                // Check for reset command
                if ($aiResponse && (strtolower(trim($message)) === '/reset' || strtolower(trim($message)) === 'reset')) {
                    \App\Models\AiConversationHistory::where('device_id', $device_id)
                        ->where('sender', $request_from)
                        ->delete();
                    
                    \Log::info('Conversation reset', ['sender' => $request_from]);
                    
                    return response()->json([
                        'message' => array('text' => '🔄 Conversation history has been reset. Let\'s start fresh!'),
                        'receiver' => $request->from,
                        'session_id' => $session
                    ], 200);
                }
                
                // Handle button responses (ai_on / ai_off)
                if ($aiResponse && $buttonId) {
                    if ($buttonId === 'ai_off') {
                        \DB::table('ai_user_preferences')->updateOrInsert(
                            ['device_id' => $device_id, 'sender' => $request_from],
                            ['ai_enabled' => false, 'updated_at' => now()]
                        );
                        
                        \Log::info('AI disabled via button', ['sender' => $request_from]);
                        
                        return response()->json([
                            'message' => array('text' => 'Chat bot telah dinonaktifkan. Ketik #on untuk mengaktifkan kembali.'),
                            'receiver' => $request->from,
                            'session_id' => $session
                        ], 200);
                    } elseif ($buttonId === 'ai_on') {
                        \DB::table('ai_user_preferences')->updateOrInsert(
                            ['device_id' => $device_id, 'sender' => $request_from],
                            ['ai_enabled' => true, 'updated_at' => now()]
                        );
                        
                        \Log::info('AI enabled via button', ['sender' => $request_from]);
                        
                        return response()->json([
                            'message' => array('text' => 'Chat bot telah diaktifkan! Ketik #off untuk menonaktifkan fitur ini.'),
                            'receiver' => $request->from,
                            'session_id' => $session
                        ], 200);
                    }
                }
                
                // Check for #off command (disable AI for this user)
                if ($aiResponse && strtolower(trim($message)) === '#off') {
                    // Store user preference to disable AI
                    \DB::table('ai_user_preferences')->updateOrInsert(
                        [
                            'device_id' => $device_id,
                            'sender' => $request_from
                        ],
                        [
                            'ai_enabled' => false,
                            'updated_at' => now()
                        ]
                    );
                    
                    \Log::info('AI disabled for user', ['sender' => $request_from]);
                    
                    return response()->json([
                        'message' => array('text' => '🔕 AI auto-reply has been disabled for you. Send #on to enable it again.'),
                        'receiver' => $request->from,
                        'session_id' => $session
                    ], 200);
                }
                
                // Check for #on command (enable AI for this user)
                if ($aiResponse && strtolower(trim($message)) === '#on') {
                    // Store user preference to enable AI
                    \DB::table('ai_user_preferences')->updateOrInsert(
                        [
                            'device_id' => $device_id,
                            'sender' => $request_from
                        ],
                        [
                            'ai_enabled' => true,
                            'updated_at' => now()
                        ]
                    );
                    
                    \Log::info('AI enabled for user', ['sender' => $request_from]);
                    
                    return response()->json([
                        'message' => array('text' => '🔔 AI auto-reply has been enabled for you. I\'m ready to help!'),
                        'receiver' => $request->from,
                        'session_id' => $session
                    ], 200);
                }
                
                // Check if user has disabled AI
                $userPreference = \DB::table('ai_user_preferences')
                    ->where('device_id', $device_id)
                    ->where('sender', $request_from)
                    ->first();
                
                // Check if this is first-time user (no preference record yet)
                $isFirstTime = !$userPreference;
                
                if ($userPreference && !$userPreference->ai_enabled) {
                    \Log::info('AI disabled by user preference', ['sender' => $request_from]);
                    return response()->json([
                        'message' => array('text' => null),
                        'receiver' => $request->from,
                        'session_id' => $session
                    ], 200);
                }
                
                // Send welcome message with buttons for first-time users
                if ($isFirstTime && $aiResponse) {
                    // Create user preference record (enabled by default)
                    \DB::table('ai_user_preferences')->insert([
                        'device_id' => $device_id,
                        'sender' => $request_from,
                        'ai_enabled' => true,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                    \Log::info('First-time user, sending welcome message', ['sender' => $request_from]);
                    
                    // Send welcome message with quick reply buttons
                    return response()->json([
                        'message' => array(
                            'text' => "👋 Halo! Saya adalah AI Assistant yang siap membantu Anda.\n\n💡 *Tips:*\nAnda bisa mengontrol AI auto-reply dengan quick reply di bawah ini:",
                            'footer' => 'Pilih salah satu opsi',
                            'templateButtons' => [
                                [
                                    'index' => 0,
                                    'quickReplyButton' => [
                                        'displayText' => '#on',
                                        'id' => '#on'
                                    ]
                                ],
                                [
                                    'index' => 1,
                                    'quickReplyButton' => [
                                        'displayText' => '#off',
                                        'id' => '#off'
                                    ]
                                ]
                            ]
                        ),
                        'receiver' => $request->from,
                        'session_id' => $session
                    ], 200);
                }
                
                // Process AI if enabled (both Default AI and Custom AI)
                if ($aiResponse) {
                    try {
                        // Check contact filter
                        $contactFilterMode = $aiResponse->contact_filter_mode ?? 'all';
                        $allowedNumbers = [];
                        
                        if ($contactFilterMode !== 'all' && !empty($aiResponse->contact_numbers)) {
                            // Parse contact numbers (comma or newline separated)
                            $allowedNumbers = preg_split('/[\r\n,]+/', $aiResponse->contact_numbers);
                            $allowedNumbers = array_map('trim', $allowedNumbers);
                            $allowedNumbers = array_filter($allowedNumbers);
                        }
                        
                        $senderNumber = $request_from;
                        $isAllowed = true;
                        
                        if ($contactFilterMode === 'whitelist' && !empty($allowedNumbers)) {
                            // Only allow numbers in the list
                            $isAllowed = in_array($senderNumber, $allowedNumbers);
                            \Log::info('Whitelist check', [
                                'sender' => $senderNumber,
                                'allowed' => $isAllowed,
                                'whitelist_count' => count($allowedNumbers)
                            ]);
                        } elseif ($contactFilterMode === 'blacklist' && !empty($allowedNumbers)) {
                            // Block numbers in the list
                            $isAllowed = !in_array($senderNumber, $allowedNumbers);
                            \Log::info('Blacklist check', [
                                'sender' => $senderNumber,
                                'allowed' => $isAllowed,
                                'blacklist_count' => count($allowedNumbers)
                            ]);
                        }
                        
                        if (!$isAllowed) {
                            \Log::info('Contact filtered out', [
                                'mode' => $contactFilterMode,
                                'sender' => $senderNumber
                            ]);
                            // Skip AI processing for this contact
                            return response()->json([
                                'message' => array('text' => null),
                                'receiver' => $request->from,
                                'session_id' => $session
                            ], 403);
                        }
                        
                        // Check if message is from group
                        $isGroupMessage = strpos($request->from, '@g.us') !== false;
                        
                        \Log::info('Group check', ['isGroup' => $isGroupMessage, 'reply_to_groups' => $aiResponse->reply_to_groups]);
                        
                        // Only proceed if: 
                        // 1. Message is from private chat, OR
                        // 2. Message is from group AND reply_to_groups is enabled
                        if (!$isGroupMessage || $aiResponse->reply_to_groups) {
                            \Log::info('Calling AI generateResponse', [
                                'ai_type' => $aiResponse->ai_type,
                                'sender' => $request_from,
                                'has_quoted' => !empty($quotedMessage)
                            ]);
                            
                            // Send typing indicator
                            $this->sendTyping($device_id, $request->from);
                            
                            $aiController = new \App\Http\Controllers\User\AiResponseController();
                            $aiReply = $aiController->generateResponse($message, $device_id, $request_from, $quotedMessage);
                            
                            \Log::info('AI Reply received', ['reply' => $aiReply ? substr($aiReply, 0, 50) : 'null']);
                            
                            if ($aiReply) {
                                $logs['user_id'] = $device->user_id;
                                $logs['device_id'] = $device->id;
                                $logs['from'] = $device->phone ?? null;
                                $logs['to'] = $request_from;
                                $logs['type'] = 'ai';
                                $this->saveLog($logs);
                                
                                // Send message in chunks for natural human-like conversation
                                $response = $this->sendMessageInChunks($device_id, $request->from, $aiReply, $session);
                                
                                // If response has null text (chunks sent in background), return empty success
                                if (!isset($response['message']['text']) || $response['message']['text'] === null) {
                                    return response()->json([
                                        'status' => 'success',
                                        'message' => 'Message sent in chunks'
                                    ], 200);
                                }
                                
                                return response()->json($response, 200);
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error('AI Error in webhook', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                }
                // ===== END AI RESPONSE =====
                
                $reply = Reply::where('device_id', $device_id)->with('template')->where('keyword', $message)->where('match_type', 'equal')->latest()->first();

                if (empty($reply)) {
                    $responses = Reply::where('device_id', $device_id)->where('match_type', '!=', 'equal')->with('template')->latest()->get();

                    $maxMatches = 0;
                    $bestResponse = null;

                    foreach ($responses as $response) {
                        $matches = 0;
                        $keywords = explode(',', $response->keyword);

                        foreach ($keywords as $keyword) {
                            if (stripos($message, $keyword) !== false) {
                                $matches++;
                            }
                        }

                        if ($matches > $maxMatches) {
                            $maxMatches = $matches;
                            $bestResponse = $response;
                        }
                    }

                    $reply = $bestResponse;
                }

                if ($reply != null) {
                    // Send typing indicator for auto response
                    $this->sendTyping($device_id, $request->from);
                    
                    if ($reply->reply_type == 'text') {
                        $logs['user_id'] = $device->user_id;
                        $logs['device_id'] = $device->id;
                        $logs['from'] = $device->phone ?? null;
                        $logs['to'] = $request_from;
                        $logs['type'] = 'chatbot';
                        $this->saveLog($logs);

                        return response()->json([
                            'message' => array('text' => $reply->reply),
                            'receiver' => $request->from,
                            'session_id' => $session
                        ], 200);
                    } else {
                        if (!empty($reply->template)) {
                            $template = $reply->template;

                            if (isset($template->body['text'])) {
                                $body = $template->body;
                                $text = $this->formatText($template->body['text'], [], $device->user);
                                $body['text'] = $text;
                            } else {
                                $body = $template->body;
                            }

                            $logs['user_id'] = $device->user_id;
                            $logs['device_id'] = $device->id;
                            $logs['from'] = $device->phone ?? null;
                            $logs['to'] = $request_from;
                            $logs['type'] = 'chatbot';
                            $logs['template_id'] = $template->id ?? null;
                            $this->saveLog($logs);

                            return response()->json([
                                'message' => $body,
                                'receiver' => $request->from,
                                'session_id' => $session
                            ], 200);
                        }
                    }
                }
            }

            return response()->json([
                'message' => array('text' => null),
                'receiver' => $request->from,
                'session_id' => $session
            ], 403);
        } catch (Exception $e) {
            return response()->json(['error' => 'Webhook processing failed: ' . $e->getMessage()], 500);
        }
    }
}
