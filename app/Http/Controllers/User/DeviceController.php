<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Reply;
use App\Models\Smstransaction;
use App\Models\Template;
use App\Models\User;
use DB;
use Auth;
use Http;
use Session;
use Carbon\Carbon;
use App\Traits\Whatsapp;
class DeviceController extends Controller
{
    use Whatsapp;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $devices=Device::where('user_id',Auth::id())->withCount('smstransaction')->latest()->paginate(20);
        return view('user.device.index',compact('devices'));
    }

    /**
     * return device statics informations
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deviceStatics()
    {
       $data['total']=Device::where('user_id',Auth::id())->count();
       $data['active']=Device::where('user_id',Auth::id())->where('status',1)->count();
       $data['inActive']=Device::where('user_id',Auth::id())->where('status',0)->count();
       $limit  = json_decode(Auth::user()->plan);
       $limit = $limit->device_limit ?? 0;

       if ($limit == '-1') {
           $data['total']= $data['total'];
       }
       else{
         $data['total']= $data['total'].' / '. $limit;
       }
       
       
       return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.device.create');
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        if (getUserPlanData('device_limit') == false) {
            return response()->json([
                'message'=>__('Maximum Device Limit Exceeded')
            ],401);  
        }

        $validated = $request->validate([
            'name' => 'required|max:100',
            'webhook_url' => 'nullable|url|max:100',
        ]);

        $device=new Device;
        $device->user_id=Auth::id();
        $device->name=$request->name;
        $device->hook_url=$request->webhook_url;
        $device->save();

        return response()->json([
            'redirect'=>url('user/device/'.$device->uuid.'/qr'),
            'message'=>__('Device Created Successfully')
        ],200);
    }

    public function scanQr($id)
    {
        $device=Device::where('user_id',Auth::id())->where('uuid',$id)->first();
        abort_if(empty($device),404);

        return view('user.device.qr',compact('device'));

    }

    public function getQr(Request $request, $id)
    {
        try {
            $device=Device::where('user_id',Auth::id())->where('uuid',$id)->first();
            abort_if(empty($device),404);

            // Check if WA_SERVER_URL is configured
            $waServerUrl = config('services.whatsapp.server_url');
            if (empty($waServerUrl)) {
                \Log::error('WA_SERVER_URL not configured in .env');
                return response()->json([
                    'success' => false,
                    'message' => 'WhatsApp server URL is not configured. Please contact administrator.'
                ], 500);
            }

            $deviceId = 'device_'.$device->id;
            $phoneNumber = $request->input('phoneNumber');
            
            // Debug logging
            \Log::info('getQr called', [
                'device_id' => $deviceId,
                'phoneNumber' => $phoneNumber,
                'has_phoneNumber' => !empty($phoneNumber),
                'wa_server_url' => $waServerUrl
            ]);
            
            // Build request payload
            $payload = [
                'id' => $deviceId,
                'isLegacy' => false
            ];
            
            // Add phoneNumber if provided (for phone pairing)
            if ($phoneNumber) {
                $payload['phoneNumber'] = $phoneNumber;
                \Log::info('Adding phoneNumber to payload', ['phoneNumber' => $phoneNumber]);
            }

            \Log::info('Sending to WA server', ['payload' => $payload]);

            $response = Http::timeout(10)->post($waServerUrl.'/sessions/add', $payload);

            \Log::info('WA server response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->status() == 200) {
                 $body = json_decode($response->body());
                 
                 // Check if response has pairing code (phone pairing)
                 if (isset($body->data->pairingCode)) {
                     return response()->json([
                         'success' => true,
                         'data' => [
                             'pairingCode' => $body->data->pairingCode,
                             'phoneNumber' => $phoneNumber
                         ],
                         'message' => $body->message ?? __('Pairing code generated successfully')
                     ], 200);
                 }
                 
                 // Otherwise return QR code (QR pairing)
                 if (isset($body->data->qr)) {
                     $device->qr = $body->data->qr;
                     $device->save();
                     
                     return response()->json([
                         'success' => true,
                         'data' => [
                             'qr' => $body->data->qr
                         ],
                         'message' => $body->message ?? __('QR code received, please scan the QR code')
                     ], 200);
                 }
                 
                 // No QR or pairing code in response
                 return response()->json([
                     'success' => false,
                     'message' => 'Unexpected response from WhatsApp server'
                 ], 500);
            }
            elseif($response->status() == 409){
            return response()->json([
                'success' => true,
                'data' => [
                    'qr' => $device->qr
                ],
                'message' => __('QR code received, please scan the QR code')
            ], 200);
        }
        
        return response()->json([
            'success' => false,
            'message' => __('Failed to connect device')
        ], 500);
        
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            \Log::error('WhatsApp Server Connection Error', [
                'error' => $e->getMessage(),
                'url' => config('services.whatsapp.server_url')
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Cannot connect to WhatsApp server. Please make sure the server is running.'
            ], 500);
        } catch (\Exception $e) {
            \Log::error('getQr Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkSession($id)
    {
       $device=Device::where('user_id',Auth::id())->where('uuid',$id)->first();
       abort_if(empty($device),404);

       $id=$device->id;
       $response=Http::get(config('services.whatsapp.server_url').'/sessions/status/device_'.$id);

       $device->status= $response->status() == 200 ? 1 : 0; 
       if ($response->status() == 200) {
           $res=json_decode($response->body());
           if (isset($res->data->userinfo)) {
             $device->user_name=$res->data->userinfo->name ?? '';
             $phone=str_replace('@s.whatsapp.net', '', $res->data->userinfo->id);
             $phone=explode(':', $phone);
             $phone=$phone[0] ?? null;

             $device->phone=$phone;
             $device->qr=null;
             
           }
           

       }         
       $device->save();

       $message= $response->status() == 200 ? __('Device Connected Successfully') : null;

       return response()->json([
           'success' => $response->status() == 200,
           'session' => $response->status() == 200,
           'message' => $message,
           'connected' => $response->status() == 200
       ]);

    }

    public function setStatus($device_id,$status)
    {

       $device_id=str_replace('device_','',$device_id);

       $device=Device::where('id',$device_id)->first();
       if (!empty($device)) {
          $device->status=$status;
          $device->save();
       }


    }

    public function webHook(Request $request,$device_id)
    {
       
       $session=$device_id;
       $device_id=str_replace('device_','',$device_id);

       $device=Device::with('user')->whereHas('user')->where('id',$device_id)->first();
       if (empty($device)) {
        return response()->json([
            'message'  => array('text' => 'this is reply'),
            'receiver' => $request->from,
            'session_id' => $session
          ],403);
       }
       
       if (getUserPlanData('chatbot',$device->user_id) == false) {
            return response()->json([
             'message'  => array('text' => 'this is reply'),
             'receiver' => $request->from,
             'session_id' => $session
            ],401);  
        }

       $request_from=explode('@',$request->from);
       $request_from=$request_from[0];

       $message_id=$request->message_id ?? null;
        $message=$request->message ?? null;
        $device_id=$device_id;

        \Log::info('Webhook received', ['device_id' => $device_id, 'message' => $message, 'from' => $request->from]);
        
        if ($device != null && $message != null) {
            
            // Check AI Response first
            $aiResponse = \App\Models\AiResponse::where('device_id', $device_id)
                ->where('is_active', true)
                ->first();
            
            \Log::info('AI Response check', ['found' => $aiResponse ? 'yes' : 'no', 'is_active' => $aiResponse->is_active ?? 'N/A']);
            
            if ($aiResponse && $aiResponse->gemini_api_key) {
                try {
                    // Check if message is from group
                    $isGroupMessage = strpos($request->from, '@g.us') !== false;
                    
                    \Log::info('Group check', ['isGroup' => $isGroupMessage, 'reply_to_groups' => $aiResponse->reply_to_groups]);
                    
                    // Only proceed if: 
                    // 1. Message is from private chat, OR
                    // 2. Message is from group AND reply_to_groups is enabled
                    if (!$isGroupMessage || $aiResponse->reply_to_groups) {
                        \Log::info('Calling AI generateResponse');
                        $aiController = new \App\Http\Controllers\User\AiResponseController();
                        $aiReply = $aiController->generateResponse($message, $device_id);
                        
                        \Log::info('AI Reply', ['reply' => $aiReply ? substr($aiReply, 0, 50) : 'null']);
                        
                        if ($aiReply) {
                            return response()->json([
                                'message'  => array('text' => $aiReply),
                                'receiver' => $request->from,
                                'session_id' => $session
                            ], 200);
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('AI Response Error: ' . $e->getMessage());
                }
            }
            
            // Fall back to chatbot (only for short messages)
            if (strlen($message) < 50) {
                $replies=Reply::where('device_id',$device_id)->with('template')->where('keyword','LIKE','%'.$message.'%')->latest()->get();

                foreach ($replies as $key => $reply) {
            if ($reply->match_type == 'equal') {

                if ($reply->reply_type == 'text') {
                 
                 return response()->json([
                    'message'  => array('text' => $reply->reply),
                    'receiver' => $request->from,
                    'session_id' => $session
                  ],200);

                 
                }
                elseif ($reply->reply_type == 'image') {
                    $content = json_decode($reply->reply, true);
                    $imagePath = $content['image'] ?? null;
                    $caption = $content['caption'] ?? '';
                    
                    if ($imagePath) {
                        return response()->json([
                            'message'  => [
                                'image' => ['url' => asset('storage/'.$imagePath)],
                                'caption' => $caption
                            ],
                            'receiver' => $request->from,
                            'session_id' => $session
                        ], 200);
                    }
                }
                elseif ($reply->reply_type == 'audio') {
                    $content = json_decode($reply->reply, true);
                    $audioPath = $content['audio'] ?? null;
                    
                    if ($audioPath) {
                        return response()->json([
                            'message'  => [
                                'audio' => ['url' => asset('storage/'.$audioPath)],
                                'ptt' => true // Send as voice note
                            ],
                            'receiver' => $request->from,
                            'session_id' => $session
                        ], 200);
                    }
                }
                elseif ($reply->reply_type == 'video') {
                    $content = json_decode($reply->reply, true);
                    $videoPath = $content['video'] ?? null;
                    $caption = $content['caption'] ?? '';
                    
                    if ($videoPath) {
                        return response()->json([
                            'message'  => [
                                'video' => ['url' => asset('storage/'.$videoPath)],
                                'caption' => $caption
                            ],
                            'receiver' => $request->from,
                            'session_id' => $session
                        ], 200);
                    }
                }
                elseif ($reply->reply_type == 'document') {
                    $content = json_decode($reply->reply, true);
                    $docPath = $content['document'] ?? null;
                    
                    if ($docPath) {
                        $fileName = basename($docPath);
                        return response()->json([
                            'message'  => [
                                'document' => ['url' => asset('storage/'.$docPath)],
                                'fileName' => $fileName
                            ],
                            'receiver' => $request->from,
                            'session_id' => $session
                        ], 200);
                    }
                }
                elseif ($reply->reply_type == 'location') {
                    $content = json_decode($reply->reply, true);
                    $lat = $content['latitude'] ?? null;
                    $long = $content['longitude'] ?? null;
                    
                    if ($lat && $long) {
                        return response()->json([
                            'message'  => [
                                'location' => [
                                    'degreesLatitude' => (float)$lat,
                                    'degreesLongitude' => (float)$long
                                ]
                            ],
                            'receiver' => $request->from,
                            'session_id' => $session
                        ], 200);
                    }
                }
                elseif ($reply->reply_type == 'button') {
                    $content = json_decode($reply->reply, true);
                    $message = $content['message'] ?? '';
                    $footer = $content['footer'] ?? '';
                    $buttons = $content['buttons'] ?? [];
                    $imagePath = $content['image'] ?? null;
                    
                    $messagePayload = [
                        'templateButtons' => [
                            'text' => $message,
                            'footer' => $footer,
                            'buttons' => $buttons
                        ]
                    ];
                    
                    if ($imagePath) {
                        $messagePayload['templateButtons']['image'] = ['url' => asset('storage/'.$imagePath)];
                    }
                    
                    return response()->json([
                        'message'  => $messagePayload,
                        'receiver' => $request->from,
                        'session_id' => $session
                    ], 200);
                }
                else{
                    if (!empty($reply->template)) {
                        $template = $reply->template;

                        if (isset($template->body['text'])) {
                            $body = $template->body;
                            $text=$this->formatText($template->body['text'],[],$device->user);
                            $body['text'] = $text;
                            
                        }
                        else{
                            $body=$template->body;
                        }

                        return response()->json([
                            'message'  => $body,
                            'receiver' => $request->from,
                            'session_id' => $session
                        ],200);
                    }
                    
                }

                break;
                
            }

          }


       }
            }
        

        return response()->json([
            'message'  => array('text' => 'this is reply'),
            'receiver' => $request->from,
            'session_id' => $session
          ],403);
       
    }

    public function logoutSession($id)
    {
       $device=Device::where('user_id',Auth::id())->where('uuid',$id)->first();
       abort_if(empty($device),404);

       $device->status=0;
       $device->qr=null;
       $device->save();

       $id=$device->id;
       $response=Http::delete(config('services.whatsapp.server_url').'/sessions/delete/device_'.$id);

      return response()->json(['message'=>__('Congratulations! Your Device Successfully Logout')]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $device=Device::where('user_id',Auth::id())->where('uuid',$id)->first();
        abort_if(empty($device),404);

        $posts=Smstransaction::where('user_id',Auth::id())->where('device_id',$device->id)->latest()->paginate();
        $totalUsed=Smstransaction::where('user_id',Auth::id())->where('device_id',$device->id)->count();
        $todaysMessage=Smstransaction::where('user_id',Auth::id())->where('device_id',$device->id)->whereDate('created_at',Carbon::today())->count();
        $monthlyMessages=Smstransaction::where('user_id',Auth::id())
                        ->where('device_id',$device->id)
                        ->where('created_at', '>', now()->subDays(30)->endOfDay())
                        ->count();


        return view('user.device.show',compact('device','posts','totalUsed','todaysMessage','monthlyMessages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $device=Device::where('user_id',Auth::id())->where('uuid',$id)->first();
        abort_if(empty($device),404);
        return view('user.device.edit',compact('device'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'webhook_url' => 'nullable|url|max:100',
        ]);

        $device=Device::where('user_id',Auth::id())->where('uuid',$id)->first();
        abort_if(empty($device),404);

        $device->name=$request->name;
        $device->hook_url=$request->webhook_url;
        $device->save();

        return response()->json([
            'redirect'=>url('/user/device'),
            'message'=>__('Device Updated Successfully')
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $device=Device::where('user_id',Auth::id())->where('uuid',$id)->first();
        abort_if(empty($device),404);
        try {
           if ($device->status == 1) {
            Http::delete(config('services.whatsapp.server_url').'/sessions/delete/device_'.$device->id);
         }
        } catch (Exception $e) {
            
        }
        $device->delete();

        return response()->json([
            'message' => __('Congratulations! Your Device Successfully Removed'),
            'redirect' => route('user.device.index')
        ]);
       
    }
}
