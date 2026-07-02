<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Device;
use App\Models\Template;
use App\Models\Smstransaction;
use Http;
trait Whatsapp
{
    private function messageSend($data,$from, $reciver,$type,$filter=false,$delay = 0)
    {
        $delay = $delay == 0 ? env('DELAY_TIME',1000) : $delay;

        if ($delay < 500) {
            $delay = 1;
        }
        else{
           $delay =  $delay/1000;
           $delay = round($delay);
        }

        sleep($delay);

        $device=Device::where('status',1)->where('id',$from)->first();
        if (empty($device)) {
            return false;
        }

        //creating session id
        $session_id='device_'.$from;

        //formating message
        $message=$this->formatBody($data['message'] ?? '',$device->user_id);

        //formating array context
        $formatedBody= $filter == false ? $this->formatArray($data,$message,$type) : $data;

        //get server url
        $whatsServer=config('services.whatsapp.server_url');
        
        // Validate WA server URL
        if (empty($whatsServer)) {
            \Log::error('WA_SERVER_URL not configured', ['device_id' => $from]);
            $responseData['status'] = 500;
            $responseData['message'] = 'WhatsApp server URL is not configured';
            return $responseData;
        }

        //formating array before sending data to server
        $body['receiver']=$reciver;
        $body['delay']=0;
        $body['message']=$formatedBody;
        
        $markAsAi = false;
        if ($device->aiResponse && $device->aiResponse->use_ai_label) {
            $markAsAi = true;
        }
        $body['markAsAi']=$markAsAi;

        //sending data to whatsapp server
        try {
            $response=Http::timeout(30)->post($whatsServer.'/chats/send?id='.$session_id,$body);
            $status=$response->status();

            if ($status != 200) {
                $responseBody=json_decode($response->body());
                $responseData['message']=$responseBody->message ?? 'Failed to send message';
                $responseData['status']=$status;
            }
            else{
                $responseData['status'] = 200;
            }

            return $responseData;
       } catch (\Exception $e) {
           \Log::error('Message send error', ['error' => $e->getMessage()]);
           $responseData['status'] = 500;
           $responseData['message'] = 'Failed to send message';
           return $responseData;
       }

    }

    private function getChats($device_id)
    {
        $session_id='device_'.$device_id;
        $whatsServer=config('services.whatsapp.server_url');

        $response=Http::get($whatsServer.'/chats?id='.$session_id);
        $status=$response->status();
        
        \Log::info('getChats response', ['device_id' => $device_id, 'status' => $status, 'body' => $response->body()]);

        if ($status != 200) {
            $responseBody=json_decode($response->body());
            $responseData['message']=$responseBody->message ?? 'Unknown error';
            $responseData['status']=$status;
        }
        else{
            $responseBody  =json_decode($response->body());
            $colllections  = collect($responseBody->data);
            $contacts = $colllections->map(function($item) {
                             $phone = explode('@', $item->id);
                             $data['number'] = $phone[0] ?? null;
                             $data['jid'] = $item->id;
                             $data['name'] = $item->name ?? null;
                             $data['pushName'] = $item->pushName ?? null;
                             $data['unread'] = $item->unreadCount ?? 0;
                             $data['timestamp'] = $item->conversationTimestamp ?? 0;
                             $data['last_message'] = $item->lastMessage ?? null; // Attempt to get last message if available
                             return $data;
                        });

            $responseData['status'] = 200;
            $responseData['data'] = $contacts;
        }

        return $responseData;

    }

    private function getChatMessages($device_id, $jid)
    {
        $session_id = 'device_' . $device_id;
        $whatsServer = config('services.whatsapp.server_url');

        try {
            $response = Http::get($whatsServer . '/chats/' . $jid . '?id=' . $session_id . '&limit=50');
            $status = $response->status();

            if ($status != 200) {
                $responseBody = json_decode($response->body());
                $responseData['message'] = $responseBody->message ?? 'Unknown error';
                $responseData['status'] = $status;
            } else {
                $responseBody = json_decode($response->body());
                $responseData['status'] = 200;
                $responseData['data'] = $responseBody->data ?? [];
            }
            return $responseData;
        } catch (\Exception $e) {
            $responseData['status'] = 500;
            $responseData['message'] = $e->getMessage();
            return $responseData;
        }
    }

    public function getGroupList($device_id)
    {
        $session_id='device_'.$device_id;
        $whatsServer=config('services.whatsapp.server_url');

        $response=Http::get($whatsServer.'/groups?id='.$session_id);
        $status=$response->status();

        if ($status != 200) {
            $responseBody=json_decode($response->body());
            $responseData['message']=$responseBody->message;
            $responseData['status']=$status;
        }
        else{
            $responseBody  =json_decode($response->body());
            $colllections  = collect($responseBody->data);

            $contacts = $colllections->map(function($item) {

                             $data['name'] = $item->name;
                             $data['id'] = $item->id;
                             return $data;
                        });

            $responseData['status'] = 200;
            $responseData['data'] = $contacts;
        }

        return $responseData;
    }

    private function sendMessageToGroup($data,$from, $reciver,$type,$filter=false,$delay = 0)
    {
       $delay = $delay == 0 ? env('DELAY_TIME',1000) : $delay;

        if ($delay < 500) {
            $delay = 1;
        }
        else{
           $delay =  $delay/1000;
           $delay = round($delay);
        }

        sleep($delay);

        $device=Device::where('status',1)->where('id',$from)->first();
        if (empty($device)) {
            return false;
        }

        //creating session id
        $session_id='device_'.$from;

        //formating message
        $message=$this->formatBody($data['message'] ?? '',$device->user_id);

        //formating array context
        $formatedBody= $filter == false ? $this->formatArray($data,$message,$type) : $data;

        //get server url
        $whatsServer=config('services.whatsapp.server_url');

        
        // Validate WA server URL
        if (empty($whatsServer)) {
            \Log::error('WA_SERVER_URL not configured', ['device_id' => $from]);
            $responseData['status'] = 500;
            $responseData['message'] = 'WhatsApp server URL is not configured';
            return $responseData;
        }

        //formating array before sending data to server
        $body['receiver']=$reciver;
        $body['delay']=0;
        $body['message']=$formatedBody;
        
        $markAsAi = false;
        if ($device->aiResponse && $device->aiResponse->use_ai_label) {
            $markAsAi = true;
        }
        $body['markAsAi']=$markAsAi;

        //sending data to whatsapp server
        try {
            $response=Http::timeout(30)->post($whatsServer.'/groups/send?id='.$session_id,$body);
            $status=$response->status();

            if ($status != 200) {
                $responseBody=json_decode($response->body());
                $responseData['message']=$responseBody->message ?? 'Failed to send message';
                $responseData['status']=$status;
            }
            else{
                $responseData['status'] = 200;
            }

            return $responseData;
       } catch (\Illuminate\Http\Client\ConnectionException $e) {
           \Log::error('WhatsApp Server Connection Error (Group)', [
               'url' => $whatsServer,
               'error' => $e->getMessage()
           ]);
           $responseData['status'] = 503;
           $responseData['message'] = 'Cannot connect to WhatsApp server';
           return $responseData;
       } catch (\Exception $e) {
           \Log::error('WhatsApp Group Message Send Error', [
               'error' => $e->getMessage()
           ]);
           $responseData['status'] = 500;
           $responseData['message'] = 'Failed to send group message';
           return $responseData;
       }

    }

    private function formatArray($data,$message,$type)
    {

        if ($type == 'plain-text') {
            $content['text']=$message;
        }
        elseif ($type == 'text-with-media') {
            $content['caption']=$message;
            $file_path = parse_url($data['attachment'], PHP_URL_PATH);
            $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
            $extentions=[
                'jpg'=>'image',
                'jpeg'=>'image',
                'png'=>'image',
                'webp'=>'image',
                'gif'=>'image',
                'mp4'=>'video',
                'mov'=>'video',
                'avi'=>'video',
                'pdf'=>'document',
                'docx'=>'document',
                'xlsx'=>'document',
                'csv'=>'document',
                'txt'=>'document',
                'mp3'=>'audio',
                'wav'=>'audio',
                'ogg'=>'audio',
                'm4a'=>'audio'
            ];

            $media_type = $extentions[$file_type] ?? 'document';
            $content[$media_type]=['url' => asset($data['attachment'])];
            if ($media_type === 'audio') {
                $content['ptt'] = true; // Send as Voice Note
            }

        }
        elseif ($type == 'text-with-button') {
            $buttons=[];
            foreach ($data['buttons'] as $key => $button) {
                $button_content['buttonId']='id'.$key;
                $button_content['buttonText']= array('displayText' => $button);
                $button_content['type']=1;

                array_push($buttons, $button_content);
            }


           $content['text']=$message;
           $content['footer']=$data['footer_text'];
           $content['buttons']=$buttons;
           $content['headerType']=1;
        }

        elseif ($type == 'text-with-template') {
             $templateButtons=[];
            foreach ($data['buttons'] as $key => $button) {
                $button_type='';
                $button_action_content='';

                if ($button['type'] == 'urlButton') {
                    $button_type='url';
                    $button_action_content=$button['action'];
                }
                elseif ($button['type'] == 'callButton') {
                    $button_type='phoneNumber';
                    $button_action_content=$button['action'];
                }
                elseif ($button['type'] == 'copyCodeButton') {
                    $button_type='copyCode';
                    $button_action_content=$button['action'];
                }
                else{
                    $button_type='id';
                    $button_action_content='action-id-'.$key;
                }

                $button_actions=[];
                $displayText = isset($button['icon']) ? $button['icon'] . ' ' . $button['displaytext'] : $button['displaytext'];
                $button_actions['displayText']=$displayText;
                $button_actions[$button_type]=$button_action_content;
                if (isset($button['icon'])) {
                    $button_actions['icon'] = $button['icon'];
                }



                $button_context['index']=$key;
                $button_context[$button['type']]= $button_actions;

                array_push($templateButtons, $button_context);
                $button_context=null;

            }


           $content['text']=$message;
           $content['footer']=$data['footer_text'];
           $content['templateButtons']=$templateButtons;
           
           if (isset($data['attachment'])) {
               $content['attachment'] = $data['attachment'];
           }

        }
        elseif ($type == 'text-with-poll') {
            $content = [
                'poll' => [
                    'name' => $message,
                    'values' => $data['options'],
                    'selectableCount' => isset($data['selectable_count']) ? (int)$data['selectable_count'] : 1
                ]
            ];
        }
        elseif ($type == 'text-with-location') {
            $content['location']=array(
                'degreesLatitude'=>$data['degreesLatitude'],
                'degreesLongitude'=>$data['degreesLongitude']
            );
        }
        elseif ($type == 'text-with-vcard') {
            $wa_id = preg_replace('/\D+/', '', $data['wa_number'] ?? '');
            $vcard = "BEGIN:VCARD\n"
                . "VERSION:3.0\n"
                . "FN:" . ($data['full_name'] ?? '') . "\n"
                . "ORG:" . ($data['org_name'] ?? '') . ";\n"
                . "TEL;type=CELL;type=VOICE;waid=" . $wa_id . ":" . ($data['contact_number'] ?? '') . "\n"
                . "END:VCARD";

            $content = [
             "contacts" => [
               "displayName" => $data['display_name'] ?? 'Contact',
               "contacts" => [
                   [
                       "vcard" => $vcard
                   ]
               ]
             ]
            ];
        }
        elseif ($type == 'text-with-list') {

            $templateButtons=[];

            foreach ($data['section'] as $section_key => $sections) {

               $rows=[];

               foreach ($sections['value'] as $value_key => $value) {

                   $rowArr['title']=$value['title'];
                   $rowArr['rowId']='option-'.$section_key.'-'.$value_key;

                   if ($value['description'] != null) {
                       $rowArr['description']=$value['description'];
                   }
                   array_push($rows, $rowArr);
                   $rowArr=[];
               }

               $row['title']=$sections['title'];
               $row['rows']=$rows;


              array_push($templateButtons, $row);
              $row=[];
            }

             $content = [
               "text" => $message,
               "footer" =>  $data['footer_text'],
               "title" => $data['header_title'],
               "buttonText" =>$data['button_text'],
               "sections" => $templateButtons
            ];
        }
        elseif ($type == 'text-with-reaction') {
            $receiver = preg_replace('/\D+/', '', $data['phone'] ?? '');
            if (substr($receiver, 0, 1) === '0') {
                $receiver = '62' . substr($receiver, 1);
            }
            $remoteJid = $receiver . '@s.whatsapp.net';
            
            $content = [
                'react' => [
                    'text' => $data['reaction_emoji'] ?? '',
                    'key' => [
                        'id' => $data['target_message_id'] ?? '',
                        'remoteJid' => $remoteJid,
                        'fromMe' => isset($data['from_me']) && $data['from_me'] ? true : false
                    ]
                ]
            ];
        }

        // --- Global Toggles for Messages (Mentions, Quote, ViewOnce) ---

        // Extract Mentions
        if (isset($data['extract_mentions']) && $data['extract_mentions'] && isset($content['text'])) {
            preg_match_all('/@(\d+)/', $content['text'], $matches);
            if (!empty($matches[1])) {
                $mentions = [];
                foreach ($matches[1] as $number) {
                    $mentions[] = $number . '@s.whatsapp.net';
                }
                $content['mentions'] = $mentions;
            }
        }

        // Quote Message (ContextInfo)
        if (!empty($data['quote_message_id'])) {
            $content['contextInfo'] = [
                'stanzaId' => $data['quote_message_id'],
                // participant is required by Baileys, we can default to the receiver
                'participant' => isset($remoteJid) ? $remoteJid : (preg_replace('/\D+/', '', $data['phone'] ?? '') . '@s.whatsapp.net')
            ];
        }

        // View Once
        if (isset($data['is_view_once']) && $data['is_view_once']) {
            $content['viewOnce'] = true;
        }

        // GIF Playback
        if (isset($data['is_gif']) && $data['is_gif']) {
            $content['gifPlayback'] = true;
        }

        // Link Preview (Only applies to plain-text mostly, handled automatically by Baileys, but can be disabled)
        if (isset($content['text']) && (!isset($data['enable_link_preview']) || !$data['enable_link_preview'])) {
            // By default Baileys enables it. We can disable it if toggle is off.
            // But wait, the user wants a toggle to ENABLE it. 
            // So if it's NOT checked, we disable it:
            $content['linkPreview'] = false;
        }

        return $content;
    }

    private function saveTemplate($data,$message,$type,$user_id,$template_id=null)
    {
       if ($template_id == null) {
          $template= new Template;
       }
       else{
          $template=  Template::findorFail($template_id);
          $template->status=isset($data['status']) ? 1 : 0;
       }

       $template->title=$data['template_name'];
       $template->user_id=$user_id;
       $template->body=$this->formatArray($data,$message,$type);
       $template->type=$type;
       $template->save();

       return true;
    }

    private function saveFile(Request $request,$input)
    {
        $file = $request->file($input);
        $ext = $file->extension();
        $filename = now()->timestamp.'.'.$ext;

        $path = 'uploads/message/' . \Auth::id() . date('/y') . '/' . date('m') . '/';
        $filePath = $path.$filename;


        Storage::put($filePath, file_get_contents($file));

        return Storage::url($filePath);
    }

    private function formatBody($context='', $user_id)
    {
        if ($context == '') {
            return $context;
        }

        $user=User::where('id',$user_id)->first();

        if (empty($user)) {
           return $context;
        }
        else{
           return $context;
        }
    }


    private function groupMetaData($group_id, $device_id)
    {
        $whatsServer=config('services.whatsapp.server_url');
        $device_id='device_'.$device_id;
        $url = $whatsServer.'/groups/meta/'.$group_id.'?id='.$device_id;

         try {

            $response=Http::get($url);
            $status=$response->status();

            if ($status != 200) {
                $responseBody=json_decode($response->body());
                $responseData['message']=$responseBody->message;
                $responseData['status']=$status;
            }
            else{
                $responseData['status'] = 200;
                $responseData['data']=json_decode($response->body());

            }

            return $responseData;
       } catch (Exception $e) {
           $responseData['status'] = 403;
           return $responseData;
       }
    }

    private function formatText($context='', $contact_data = null,$senderdata = null)
    {
       if ($context == '') {
            return $context;
       }
       if ($contact_data != null) {
           $name=$contact_data['name'] ?? '';
           $phone=$contact_data['phone'] ?? '';

           $context=str_replace('{name}',$name,$context);
           $context=str_replace('{phone_number}',$phone,$context);

       }

       if ($senderdata != null) {
           $sender_name=$senderdata['name'] ?? '';
           $sender_phone=$senderdata['phone'] ?? '';
           $sender_email=$senderdata['email'] ?? '';

           $context=str_replace('{my_name}',$sender_name,$context);
           $context=str_replace('{my_contact_number}',$sender_phone,$context);
           $context=str_replace('{my_email}',$sender_email,$context);
       }

       return $context;


    }

    private function formatCustomText($context='', $replaceableData = [])
    {
        $filteredContent = $context;

        foreach ($replaceableData ?? [] as $key => $value) {
           $filteredContent = str_replace($key, $value, $filteredContent);
        }

        return $filteredContent;

    }

    private function saveLog($data)
    {
        $log= new Smstransaction;
        $log->user_id = $data['user_id'] ?? null;
        $log->device_id = $data['device_id'] ?? null;
        $log->app_id = $data['app_id'] ?? null;
        $log->from = $data['from'] ?? null;
        $log->to = $data['to'] ?? null;
        $log->template_id = $data['template_id'] ?? null;
        $log->type = $data['type'] ?? null;
        $log->save();
    }

    private function sendTyping($deviceId, $recipient)
    {
        try {
            $device = Device::where('status', 1)->where('id', $deviceId)->first();
            if (empty($device)) {
                return false;
            }

            $session_id = 'device_' . $deviceId;
            $whatsServer = config('services.whatsapp.server_url');

            if (empty($whatsServer)) {
                return false;
            }

            // Send typing indicator (non-blocking with short timeout)
            Http::timeout(2)->post($whatsServer . '/chats/typing?id=' . $session_id, [
                'receiver' => $recipient,
                'typing' => true
            ]);

            // Don't wait for response, just fire and forget
            return true;
        } catch (\Exception $e) {
            // Silently fail - typing is not critical
            \Log::debug('Typing indicator failed (non-critical)', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Send long message in chunks like a human typing
     * Splits message by newlines and sends each part with delay
     */
    private function sendMessageInChunks($deviceId, $recipient, $fullMessage, $session)
    {
        try {
            // Split message by double newlines (paragraphs)
            $chunks = preg_split('/\n\n+/', trim($fullMessage));
            
            // If no double newlines, split by single newlines
            if (count($chunks) === 1) {
                $chunks = explode("\n", trim($fullMessage));
            }
            
            // Filter empty chunks
            $chunks = array_filter($chunks, function($chunk) {
                return !empty(trim($chunk));
            });
            
            // If still one chunk or very short, send as is
            if (count($chunks) <= 1 || strlen($fullMessage) < 100) {
                return [
                    'message' => array('text' => $fullMessage),
                    'receiver' => $recipient,
                    'session_id' => $session
                ];
            }
            
            \Log::info('Sending message in chunks', [
                'total_chunks' => count($chunks),
                'total_length' => strlen($fullMessage)
            ]);
            
            $device = Device::where('status', 1)->where('id', $deviceId)->first();
            if (empty($device)) {
                return null;
            }
            
            $whatsServer = config('services.whatsapp.server_url');
            if (empty($whatsServer)) {
                return null;
            }
            
            // Send ALL chunks in background to ensure correct order
            dispatch(function() use ($chunks, $deviceId, $recipient, $session, $whatsServer) {
                foreach ($chunks as $index => $chunk) {
                    // Send typing indicator before each chunk
                    try {
                        Http::timeout(2)->post($whatsServer . '/chats/typing?id=' . $session, [
                            'receiver' => $recipient,
                            'typing' => true
                        ]);
                    } catch (\Exception $e) {
                        // Ignore typing errors
                    }
                    
                    // Small delay after typing
                    usleep(1000000); // 1 second
                    
                    // Send chunk
                    try {
                        Http::timeout(5)->post($whatsServer . '/chats/send?id=' . $session, [
                            'receiver' => $recipient,
                            'delay' => 0,
                            'message' => ['text' => $chunk]
                        ]);
                        
                        \Log::info('Chunk sent', [
                            'chunk_number' => $index + 1,
                            'length' => strlen($chunk)
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to send chunk', [
                            'chunk_number' => $index + 1,
                            'error' => $e->getMessage()
                        ]);
                    }
                    
                    // Wait 2 seconds before next chunk (if not last chunk)
                    if ($index < count($chunks) - 1) {
                        sleep(2);
                    }
                }
            })->afterResponse();
            
            // Return null so webhook doesn't send anything
            return [
                'message' => array('text' => null),
                'receiver' => $recipient,
                'session_id' => $session
            ];
            
        } catch (\Exception $e) {
            \Log::error('sendMessageInChunks error', ['error' => $e->getMessage()]);
            // Fallback to single message
            return [
                'message' => array('text' => $fullMessage),
                'receiver' => $recipient,
                'session_id' => $session
            ];
        }
    }

}
