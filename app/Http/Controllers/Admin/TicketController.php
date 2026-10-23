<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\TicketMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('user')->orderBy('id', 'desc')->paginate(20);
        return view('admin.ticket.index', compact('tickets'));
    }

    public function view($id)
    {
        $ticket = Ticket::where('id', $id)->first();
        $ticket_messages = TicketMessage::with('user')->where('ticket_id', $id)->get();
        return view('admin.ticket.view', compact('ticket', 'ticket_messages'));
    }

    public function ticketMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $message = new TicketMessage();
        $message->ticket_id = $id;
        $message->user_id = '0';
        $message->message = $request->message;
        if($request->status == '0'){
            $ticket         = Ticket::where('id', $id)->first();
            $ticket->status = '1';
            $ticket->save();
        }
        $message->save();
        return redirect()->back()->with('message', 'Message Sent Successfully');
    }

    public function ticketStatusUpdate(Request $request, $id){
        $ticket         = Ticket::where('id', $id)->first();
        $ticket->status = $request->status;
        $ticket->save();
        return redirect()->route('admin.ticket.index')->with('message', 'Status Updated Successfully');
    }
}
