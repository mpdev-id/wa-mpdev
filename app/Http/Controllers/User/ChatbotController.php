<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reply;
use App\Models\Template;
use App\Models\Device;
use Auth;
use DB;
class ChatbotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates=Template::where('user_id',Auth::id())->where('status',1)->latest()->get();
        $replies=Reply::where('user_id',Auth::id())->with('device')->latest()->paginate(20);
        $total_replies=Reply::where('user_id',Auth::id())->count();
        $template_replies=Reply::where('user_id',Auth::id())->where('template_id','!=',null)->count();
        $text_replies=Reply::where('user_id',Auth::id())->where('template_id',null)->count();
        $devices = Device::where('user_id',Auth::id())->where('status',1)->latest()->get();

        return view('user.chatbot.index',compact('templates','replies','total_replies','template_replies','text_replies','devices'));
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (getUserPlanData('chatbot') == false) {
            return response()->json([
                'message'=>__('Chatbot features is not available with your subscription')
            ],401);  
        }

        $validated = $request->validate([
            'keyword' => 'required|max:100',
            'match_type' => 'required',
            'reply_type' => 'required',
            'device' => 'required'
        ]);

        $device = Device::where('user_id',Auth::id())->findorFail($request->device);

        if ($request->reply_type == 'template') {
             $validated = $request->validate([
                'template' => 'required',
            ]);
            $template=Template::where('user_id',Auth::id())->where('status',1)->findorFail($request->template);
        }
        elseif ($request->reply_type == 'button') {
            $validated = $request->validate([
                'button_message' => 'required|max:1000',
                'footer_text' => 'required|max:50',
                'buttons' => 'required|array|min:1|max:3',
                'buttons.*.displaytext' => 'required|max:50',
                'buttons.*.type' => 'required',
                'button_image' => 'nullable|image|max:2048',
            ]);
        }
        elseif ($request->reply_type == 'image') {
            $validated = $request->validate([
                'image' => 'required|image|max:2048',
                'caption' => 'nullable|max:1000',
            ]);
        }
        elseif ($request->reply_type == 'audio') {
            $validated = $request->validate([
                'audio' => 'required|mimes:mp3,ogg,wav|max:5120', // 5MB
            ]);
        }
        elseif ($request->reply_type == 'video') {
            $validated = $request->validate([
                'video' => 'required|mimes:mp4|max:10240', // 10MB
                'video_caption' => 'nullable|max:1000',
            ]);
        }
        elseif ($request->reply_type == 'document') {
            $validated = $request->validate([
                'document' => 'required|mimes:pdf,doc,docx,xls,xlsx,csv,txt|max:10240', // 10MB
            ]);
        }
        elseif ($request->reply_type == 'location') {
            $validated = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);
        }
        else{
            $validated = $request->validate([
                'reply' => 'required|max:1000',
            ]);
        }

        $reply = new Reply;
        $reply->user_id = Auth::id();
        $reply->device_id = $request->device;
        $reply->template_id = $request->reply_type == 'template' ? $template->id : null;
        $reply->keyword = $request->keyword;
        
        if ($request->reply_type == 'button') {
            $data = [
                'message' => $request->button_message,
                'footer' => $request->footer_text,
                'buttons' => $request->buttons
            ];
            
            if ($request->hasFile('button_image')) {
                $path = $request->file('button_image')->store('uploads/replies', 'public');
                $data['image'] = $path;
            }
            
            $reply->reply = json_encode($data);
        }
        elseif ($request->reply_type == 'image') {
            $path = $request->file('image')->store('uploads/replies', 'public');
            $reply->reply = json_encode(['image' => $path, 'caption' => $request->caption]);
        } 
        elseif ($request->reply_type == 'audio') {
            $path = $request->file('audio')->store('uploads/replies', 'public');
            $reply->reply = json_encode(['audio' => $path]);
        }
        elseif ($request->reply_type == 'video') {
            $path = $request->file('video')->store('uploads/replies', 'public');
            $reply->reply = json_encode(['video' => $path, 'caption' => $request->video_caption]);
        }
        elseif ($request->reply_type == 'document') {
            $path = $request->file('document')->store('uploads/replies', 'public');
            $reply->reply = json_encode(['document' => $path]);
        }
        elseif ($request->reply_type == 'location') {
            $reply->reply = json_encode(['latitude' => $request->latitude, 'longitude' => $request->longitude]);
        }
        else {
            $reply->reply = $request->reply_type != 'template' ? $request->reply : null;
        }
        
        $reply->match_type= $request->match_type == 'equal' ? 'equal' : 'like';
        $reply->reply_type= $request->reply_type;
        $reply->save();

        return response()->json([
                'message'  => __('Reply Created Successfully'),
                'redirect' => route('user.chatbot.index')
            ], 200);
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
            'keyword' => 'required|max:100',
            'match_type' => 'required',
            'reply_type' => 'required',
            'device' => 'required'
        ]);

        $device = Device::where('user_id',Auth::id())->findorFail($request->device);

        if ($request->reply_type == 'template') {
             $validated = $request->validate([
                'template' => 'required',
            ]);
            $template=Template::where('user_id',Auth::id())->where('status',1)->findorFail($request->template);
        }
        elseif ($request->reply_type == 'button') {
            $validated = $request->validate([
                'button_message' => 'required|max:1000',
                'footer_text' => 'required|max:50',
                'buttons' => 'required|array|min:1|max:3',
                'buttons.*.displaytext' => 'required|max:50',
                'buttons.*.type' => 'required',
                'button_image' => 'nullable|image|max:2048',
            ]);
        }
        elseif ($request->reply_type == 'image') {
            $validated = $request->validate([
                'image' => 'nullable|image|max:2048',
                'caption' => 'nullable|max:1000',
            ]);
        }
        elseif ($request->reply_type == 'audio') {
            $validated = $request->validate([
                'audio' => 'nullable|mimes:mp3,ogg,wav|max:5120',
            ]);
        }
        elseif ($request->reply_type == 'video') {
            $validated = $request->validate([
                'video' => 'nullable|mimes:mp4|max:10240',
                'video_caption' => 'nullable|max:1000',
            ]);
        }
        elseif ($request->reply_type == 'document') {
            $validated = $request->validate([
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx,csv,txt|max:10240',
            ]);
        }
        elseif ($request->reply_type == 'location') {
            $validated = $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);
        }
        else{
            $validated = $request->validate([
                'reply' => 'required|max:1000',
            ]);
        }

        $reply =  Reply::where('user_id',Auth::id())->findorFail($id);
        $reply->user_id = Auth::id();
        $reply->device_id = $request->device;
        $reply->template_id = $request->reply_type == 'template' ? $template->id : null;
        $reply->keyword = $request->keyword;
        
        $data = [];
        if (in_array($reply->reply_type, ['image', 'audio', 'video', 'document', 'location', 'button']) && !empty($reply->reply)) {
             $data = json_decode($reply->reply, true) ?? [];
        }
        
        // Reset data if reply type changed
        if ($reply->reply_type != $request->reply_type) {
            $data = [];
        }

        if ($request->reply_type == 'button') {
            $data['message'] = $request->button_message;
            $data['footer'] = $request->footer_text;
            $data['buttons'] = $request->buttons;
            
            if ($request->hasFile('button_image')) {
                $path = $request->file('button_image')->store('uploads/replies', 'public');
                $data['image'] = $path;
            }
            
            $reply->reply = json_encode($data);
        }
        elseif ($request->reply_type == 'image') {
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('uploads/replies', 'public');
                $data['image'] = $path;
            }
            $data['caption'] = $request->caption;
            $reply->reply = json_encode($data);
        } 
        elseif ($request->reply_type == 'audio') {
            if ($request->hasFile('audio')) {
                $path = $request->file('audio')->store('uploads/replies', 'public');
                $data['audio'] = $path;
            }
            $reply->reply = json_encode($data);
        }
        elseif ($request->reply_type == 'video') {
            if ($request->hasFile('video')) {
                $path = $request->file('video')->store('uploads/replies', 'public');
                $data['video'] = $path;
            }
            $data['caption'] = $request->video_caption;
            $reply->reply = json_encode($data);
        }
        elseif ($request->reply_type == 'document') {
            if ($request->hasFile('document')) {
                $path = $request->file('document')->store('uploads/replies', 'public');
                $data['document'] = $path;
            }
            $reply->reply = json_encode($data);
        }
        elseif ($request->reply_type == 'location') {
            $data['latitude'] = $request->latitude;
            $data['longitude'] = $request->longitude;
            $reply->reply = json_encode($data);
        }
        else {
            $reply->reply = $request->reply_type != 'template' ? $request->reply : null;
        }

        $reply->match_type= $request->match_type == 'equal' ? 'equal' : 'like';
        $reply->reply_type= $request->reply_type;
        $reply->save();

        return response()->json([
                'message'  => __('Reply Created Successfully'),
                'redirect' => route('user.chatbot.index')
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reply =  Reply::where('user_id',Auth::id())->findorFail($id);
        $reply->delete();

        return response()->json([
            'message' => __('Congratulations! Your Reply Successfully Removed'),
            'redirect' => route('user.chatbot.index')
        ]);
    }
}
