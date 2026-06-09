<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvestorSupportController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('investor.support.index', compact('tickets'));
    }

    public function create()
    {
        return view('investor.support.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return redirect()->route('investor.support.index')
            ->with('success', 'Support ticket created.');
    }

    public function show(SupportTicket $ticket)
    {
        abort_unless($ticket->user_id === Auth::id(), 403);
        $ticket->load('messages.user');
        return view('investor.support.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket)
    {
        abort_unless($ticket->user_id === Auth::id(), 403);

        $request->validate(['message' => 'required|string']);

        SupportTicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Reply sent.');
    }
}
