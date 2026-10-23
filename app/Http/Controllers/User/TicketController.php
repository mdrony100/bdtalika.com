<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\TicketMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(20);
        return view('user.ticket.index', compact('tickets'));
    }

    public function create()
    {
        return view('user.ticket.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject'   => 'required',
            'priority'  => 'required',
            'message'   => 'required',
        ]);
        try {
            $data               = new Ticket;
            $data->subject      = $request->subject;
            $data->priority     = $request->priority;
            $data->user_id      = Auth::user()->id;
            $data->status       = 1;
            $data->created_at   = Carbon::now();
            $data->save();

            $message                = new TicketMessage();
            $message->ticket_id     = $data->id;
            $message->user_id       = Auth::user()->id;
            $message->message       = $request->message;
            $message->created_at    = Carbon::now();
            $message->save();
            return redirect()->route('user.ticket.index')->with('message', 'Ticket Created Successfully');
        } catch (\Exception $e) { 
            return back()->with('error', 'Something wrong please try again.');
        }
    }

    public function view($id)
    {
        $ticket = Ticket::where('id', $id)->first();
        $ticket_messages = TicketMessage::with('user')->where('ticket_id', $id)->get();
        return view('user.ticket.view', compact('ticket', 'ticket_messages'));
    }

    public function ticketMessage(Request $request, $id) {
 
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);
    
        $message = new TicketMessage();
        $message->ticket_id = $id;
        $message->user_id = Auth::user()->id;
        $message->message = $request->message;
        $message->save();
        return redirect()->back()->with('message', 'Message Sent Successfully');
    }
    
}
