<?php

namespace App\Http\Controllers\User;

use App\Classes\GeniusMailer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Auth;
use App\Models\AdminUserConversation;
use App\Models\AdminUserMessage;
use App\Models\Generalsetting;
use App\Models\Notification;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data['user'] = auth()->user();
        $data['tickets'] = AdminUserConversation::whereUserId(auth()->id())->orderBy('id','desc')->get();
        $data['ticket'] = AdminUserConversation::findOrFail($request->ticket);
        $data['messages'] = AdminUserMessage::when($request->ticket,function($query) use ($request){
                                                    return $query->whereConversationId($request->ticket);
                                                })
                                                ->get();
        return view('user.message.index',$data);
    }

    public function create(){
        return view('user.message.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required',
            'attachment' => 'mimes:png,jpeg,gif',
        ]);

        $msg = new AdminUserConversation();
        $input = $request->all();

        $input['ticket_number'] = '#TKT'.random_int(100000, 999999);
        $input['user_id'] = auth()->id();

        if ($file = $request->file('attachment'))
        {
           $name = Str::random(8).time().'.'.$file->getClientOriginalExtension();
           $file->move('assets/images',$name);
           $input['attachment'] = $name;
        }
        $msg->fill($input)->save();

        $conversation = new AdminUserMessage();
        $conversation->conversation_id = $msg->id;
        $conversation->user_id = auth()->id();
        $conversation->message = $request->message;
        $conversation->photo = $msg->attachment;
        $conversation->save();

        return redirect()->route('user.message.index',['ticket'=>$msg->id])->with('message','Message Send Successfully.');
    }

    public function show($id){
        $data = AdminUserConversation::whereId($id)->first();
        return view('user.message.show',compact('data'));
    }


    public function conversation(Request $request, $id){
        $data = new AdminUserMessage();
        $data->conversation_id = $id;
        $data->user_id = auth()->id();
        $data->message = $request->message;

        if ($file = $request->file('photo'))
        {
           $name = Str::random(8).time().'.'.$file->getClientOriginalExtension();
           $file->move('assets/images',$name);
           $data->photo = $name;
        }
        $data->save();

        return redirect()->back()->with('message','Message Send Successfully.');
    }


    public function adminmessagedelete($id)
    {
        $conv = AdminUserConversation::findOrfail($id);
        if($conv->messages->count() > 0)
        {
            foreach ($conv->messages as $key) {
                $key->delete();
            }
        }
        $conv->delete();
        return redirect()->back()->with('success','Message Deleted Successfully');
    }



}
