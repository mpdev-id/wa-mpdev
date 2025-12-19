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

            if (isset($message->conversation)) {
                $message = $message->conversation ?? null;
            } elseif (isset($message->extendedTextMessage)) {
                $message = $message->extendedTextMessage->text ?? null;
            } elseif (isset($message->buttonsResponseMessage)) {
                $message = $message->buttonsResponseMessage->selectedDisplayText ?? null;
            } elseif (isset($message->listResponseMessage)) {
                $message = $message->listResponseMessage->title ?? null;
            } else {
                $message = null;
            }
            
            \Log::info('Message extracted', ['message' => $message, 'length' => strlen($message ?? '')]);

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
                
                if ($aiResponse && $aiResponse->gemini_api_key) {
                    try {
                        // Check if message is from group
                        $isGroupMessage = strpos($request->from, '@g.us') !== false;
                        
                        \Log::info('Group check', ['isGroup' => $isGroupMessage, 'reply_to_groups' => $aiResponse->reply_to_groups]);
                        
                        // Only proceed if: 
                        // 1. Message is from private chat, OR
                        // 2. Message is from group AND reply_to_groups is enabled
                        if (!$isGroupMessage || $aiResponse->reply_to_groups) {
                            \Log::info('Calling AI generateResponse', ['sender' => $request_from]);
                            
                            // Send typing indicator
                            $this->sendTyping($device_id, $request->from);
                            
                            $aiController = new \App\Http\Controllers\User\AiResponseController();
                            $aiReply = $aiController->generateResponse($message, $device_id, $request_from);
                            
                            \Log::info('AI Reply received', ['reply' => $aiReply ? substr($aiReply, 0, 50) : 'null']);
                            
                            if ($aiReply) {
                                $logs['user_id'] = $device->user_id;
                                $logs['device_id'] = $device->id;
                                $logs['from'] = $device->phone ?? null;
                                $logs['to'] = $request_from;
                                $logs['type'] = 'ai';
                                $this->saveLog($logs);
                                
                                return response()->json([
                                    'message' => array('text' => $aiReply),
                                    'receiver' => $request->from,
                                    'session_id' => $session
                                ], 200);
                            }
                        }
                    } catch (\Exception $e) {
                        \Log::error('AI Error: ' . $e->getMessage());
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
