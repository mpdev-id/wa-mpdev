<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Template;
use App\Models\Device;
use App\Models\User;
use App\Rules\Phone;
use App\Traits\Whatsapp;
use App\Models\Group;
use App\Models\Groupcontact;
use Auth;
use DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class ContactController extends Controller
{
    use Whatsapp;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $total_contacts=Contact::where('user_id',Auth::id())->count();

        $query = Contact::where('user_id',Auth::id())->with('groupcontact');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                  ->orWhere('phone', 'LIKE', '%' . $search . '%');
            });
        }

        $contacts = $query->latest()->paginate(20);

        if (!empty($search)) {
            $contacts->appends(['search' => $search]);
        }

        $templates=Template::where('user_id',Auth::id())->where('status',1)->latest()->get();
        $devices=Device::where('user_id',Auth::id())->where('status',1)->latest()->get();
        $limit  = json_decode(Auth::user()->plan);
        $limit = $limit->contact_limit ?? 0;

        if ($limit == '-1') {
            $limit = number_format($total_contacts);
        }
        else{
            $limit = $total_contacts.' / '.$limit;
        }
        
        $groups = Group::where('user_id',Auth::id())->latest()->get();

        return view('user.contact.index',compact('contacts','total_contacts','templates','devices','limit','groups', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group::where('user_id',Auth::id())->latest()->get();
        return view('user.contact.create',compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (getUserPlanData('contact_limit') == false) {
            return response()->json([
                'message'=>__('Maximum Contacts Limit Exceeded')
            ],401);  
        }
        

        $validated = $request->validate([
            'phone'   => ['required', new Phone],
            'name'=>['required','max:20'], 
        ]);

        $is_exist=Contact::where('user_id',Auth::id())->where('phone',$request->phone)->first();
        if (!empty($is_exist)) {
           return response()->json([
                'message'  => __('Contact already exist..!!'),
                'redirect' =>  route('user.contact.index')
            ], 401);
        }

        if ($request->group) {
            $group = Group::where('user_id',Auth::id())->findorFail($request->group);
        }

        $phone = preg_replace('/\D+/', '', $request->phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        $contact= new Contact;
        $contact->name = $request->name;
        $contact->phone = $phone;
        $contact->user_id = Auth::id();
        $contact->save();

        if ($request->group) {
        $contact->groupcontacts()->insert(['group_id'=>$request->group,'contact_id'=>$contact->id]);
        }

        return response()->json([
                    'message' => __('New Contact Created Successfully'),
               ], 200);
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info=Contact::where('user_id',Auth::id())->findorFail($id);
        return view('user.contact.edit',compact('info'));
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
            'phone'   => ['required', new Phone],
            'name'=>['required','max:20'], 
        ]);

        $is_exist=Contact::where('user_id',Auth::id())->where('phone',$request->phone)->where('id','!=',$id)->first();
        if (!empty($is_exist)) {
           return response()->json([
                'message'  => __('Opps this contact number you have already added')
            ], 401);
        }

        $phone = preg_replace('/\D+/', '', $request->phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        $contact=  Contact::where('user_id',Auth::id())->findorFail($id);
        $contact->name = $request->name;
        $contact->phone = $phone;
        $contact->user_id = Auth::id();
        $contact->save();

        if ($request->group) {
            $group = Group::where('user_id',Auth::id())->findorFail($request->group);
            $contact->groupcontact()->sync([$request->group]);
        }


        return response()->json([
                    'message' => __('Contact update Successfully'),
                    'redirect' =>  route('user.contact.index')
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
        $contact=Contact::where('user_id',Auth::id())->findorFail($id);
        $contact->delete();

        return response()->json([
            'message'  => __('Contact deleted successfully..!!'),
            'redirect' =>  route('user.contact.index')
        ], 200);
    }

    public function sendtemplateBulk(Request $request)
    {
       
        if (getUserPlanData('messages_limit') == false) {
            return response()->json([
                'message'=>__('Maximum Monthly Messages Limit Exceeded')
            ],401);  
        }

            $validated = $request->validate([
                'template'=>['required'], 
                'group'   => ['required'],
            ]);

            $group = Group::where('user_id',Auth::id())->whereHas('groupcontacts')->findorFail($request->group);
            $device=Device::where('user_id',Auth::id())->where('status',1)->findorFail($request->device);

            return response()->json([
                'message'  => __('Redirecting to bulk sending page'),
                'redirect' =>  url('user/sent-bulk-with-template/'.$request->template.'/'.$group->id.'/'.$device->uuid)
            ], 200);

            
           // $validated = $request->validate([
           //  'group'   => ['required'],
           //  'template'   => ['required'], 
           // ]);

            // $template=Template::where('user_id',Auth::id())->where('status',1)->findorFail($request->template);
            // $device=Device::where('user_id',Auth::id())->where('status',1)->findorFail($request->device);
            // $contacts=Contact::where('user_id',Auth::id())->whereHas('groupcontacts',function($q) use ($request){
            //     return $q->where('group_id',$request->group);
            // })->get();

            // $user=User::where('id',Auth::id())->first();

            // $logs['template_id']=$template->id;

            // foreach ($contacts as $key => $contact) {
            //   if (isset($template->body['text'])) {

            //     $formatText=$this->formatText($template->body['text'],$contact,$user);
            //     $body=$template->body;
            //     $body['text']=$formatText;
            //     $logs['template_id']=$template->id;

            //   }
            //   else{
            //     $body=$template->body;
            //   }


              
            //   $response = $this->messageSend($body,$device->id,$contact->phone,$template->type,true);

            //   if ($response['status'] == 200) {
            //      $logs['user_id']=$user->id;
            //      $logs['device_id']=$device->id;
            //      $logs['from']=$device->phone ?? null;
            //      $logs['to']=$contact->phone;
            //      $logs['type']='bulk-message';

            //      $this->saveLog($logs);
            //   }
            // }
            
            // return response()->json([
            //     'message'  => __('Message Send Successfully'),
            // ], 200);
        
    }


    public function import(Request $request)
    {

        $validated = $request->validate([
            'file'   => 'required|mimes:csv,txt|max:10',
            
        ]);

        if (getUserPlanData('contact_limit') == false) {
            return response()->json([
                'message'=>__('Maximum Contacts Limit Exceeded')
            ],401);  
        }
        else{
            $contact_limit=json_decode(Auth::user()->plan);
            $contact_limit=$contact_limit->contact_limit;
        }


        if ($request->group) {
            $group = Group::where('user_id',Auth::id())->findorFail($request->group);
        }
        

        $file = $request->file('file');

        $insertable=[];

        // Open the CSV file
        if (($handle = fopen($file, 'r')) !== false) {
        // Read the header row
            $header = fgetcsv($handle);

        // Loop through the remaining rows
            while (($data = fgetcsv($handle)) !== false) {
            // Process the row data
            // ...

            // Example: Create a new record in the database
                $row=array(
                    'name'=>$data[0],
                    'phone'=>$data[1]
                ); 
                array_push($insertable, $row);
                
            }

        // Close the CSV file
            fclose($handle);
           

        }

        $count_contacts=count($insertable);

        if ($contact_limit != -1) {
           $old_rows = Contact::where('user_id',Auth::id())->count();

           $available_rows = $contact_limit-$old_rows;
           


           if ($count_contacts > $available_rows) {
                return response()->json([
                    'message'=>__('Maximum '.$available_rows.' records are available only for create contact')
                ],401);  
           }
        }

        DB::beginTransaction();
        try {
            
            $insertableGroups=[];

              foreach ($insertable as $key => $row) {
                 $phone = preg_replace('/\D+/', '', $row['phone']);
                 if (substr($phone, 0, 1) === '0') {
                     $phone = '62' . substr($phone, 1);
                 }

                 $contact= new Contact;
                 $contact->name=$row['name'];
                 $contact->phone=$phone;
                 $contact->user_id=Auth::id();
                 $contact->save();

                 $contactRow=array(
                    'contact_id'=>$contact->id,
                    'group_id'=>$request->group ?? null
                ); 
                 array_push($insertableGroups, $contactRow);

             }


             if ($request->group) {
                Groupcontact::insert($insertableGroups);
            }

            DB::commit();

        } catch (Throwable $th) {
            DB::rollback();

            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }

       
        
        return response()->json([
                'message'  => __('Contact list imported successfully'),
            ], 200);


    }

    public function sync(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|exists:devices,id',
        ]);

        $device = Device::where('user_id', Auth::id())
            ->where('status', 1)
            ->findOrFail($request->device_id);

        try {
            // Call WhatsApp API to get contacts (using chats endpoint)
            $whatsServer = config('services.whatsapp.server_url');
            // Use device_{id} format if uuid is not set or different
            $sessionId = 'device_' . $device->id; 
            
            // Use device_{id} format
            $sessionId = 'device_' . $device->id; 
            
            // We use /chats endpoint because /contacts endpoint is not available on this server
            // This will fetch all active conversations
            $url = $whatsServer . '/chats?id=' . $sessionId;
            
            Log::info('Syncing active contacts from: ' . $url);
            
            $response = Http::timeout(30)->get($url);

            Log::info('Sync response status: ' . $response->status());
            
            $contacts = [];
            
            if ($response->successful()) {
                $jsonResponse = $response->json();
                $data = $jsonResponse['data'] ?? [];
                
                if (is_array($data)) {
                    $contacts = $data;
                    Log::info('Active chats found: ' . count($contacts));
                } else {
                    // Fallback if data is directly in the root
                    if (is_array($jsonResponse)) {
                        $contacts = $jsonResponse;
                    }
                }
            } else {
                Log::warning('Failed to fetch chats. Status: ' . $response->status());
            }
            
            Log::info('Total contacts to process: ' . count($contacts));
                
                $syncedCount = 0;
                $skippedCount = 0;

                DB::beginTransaction();
                
                foreach ($contacts as $contact) {
                    // Normalize contact data structure
                    // /chats endpoint might return different structure than /contacts
                    $contactId = $contact['id'] ?? null;
                    $contactName = $contact['name'] ?? $contact['notify'] ?? null;
                    
                    // Skip if no ID or if it's a group (usually ends with @g.us)
                    if (empty($contactId) || strpos($contactId, '@g.us') !== false) {
                        continue;
                    }

                    // Extract phone number (remove @s.whatsapp.net)
                    $phone = str_replace('@s.whatsapp.net', '', $contactId);
                    
                    // Use phone as name if name is empty
                    $name = $contactName ?? $phone;
                    
                    // Check if contact already exists
                    $existingContact = Contact::where('user_id', Auth::id())
                        ->where('phone', $phone)
                        ->first();

                    if (!$existingContact) {
                        // Check contact limit
                        if (getUserPlanData('contact_limit') == false) {
                            Log::warning('Contact limit reached for user: ' . Auth::id());
                            break;
                        }

                        // Create new contact
                        Contact::create([
                            'name' => $name,
                            'phone' => $phone,
                            'user_id' => Auth::id()
                        ]);
                        
                        $syncedCount++;
                    } else {
                        $skippedCount++;
                    }
                }

                DB::commit();
                
                Log::info("Sync completed. Synced: $syncedCount, Skipped: $skippedCount");

                return response()->json([
                    'message' => __('Active contacts synced successfully (from chat history)'),
                    'synced' => $syncedCount,
                    'skipped' => $skippedCount,
                    'total' => count($contacts)
                ], 200);

            // Removed else block as we handle errors individually above

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Sync Exception: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'message' => __('Error syncing contacts: ') . $e->getMessage()
            ], 500);
        }
    }

    
}
