<?php

namespace App\Http\Controllers\Investor;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
            'subject'  => 'required|string|max:255',
            'message'  => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $ticket = SupportTicket::create([
            'user_id'  => Auth::id(),
            'subject'  => $request->subject,
            'message'  => $request->message,
            'priority' => $request->priority,
        ]);

        $user = Auth::user();
        $adminEmail = config('mail.admin_address', 'inbox@villabit.ai');

        $ticketUrl = route('admin.villabit.support.show', $ticket->id);

        // Notify admin
        Mail::raw(
            "New support ticket #{$ticket->id}\n"
            . "From: {$user->first_name} {$user->last_name} ({$user->email})\n"
            . "Priority: {$ticket->priority}\n"
            . "Subject: {$ticket->subject}\n\n"
            . $ticket->message . "\n\n"
            . "View ticket: {$ticketUrl}",
            fn ($m) => $m
                ->from('inbox@villabit.ai', 'Villa Bit AI')
                ->to($adminEmail)
                ->subject("[VillaBit Support] #{$ticket->id}: {$ticket->subject}")
        );

        // Confirm to user
        Mail::raw(
            "Hi {$user->first_name},\n\nYour support ticket has been received.\n"
            . "Ticket #: {$ticket->id}\nSubject: {$ticket->subject}\n\n"
            . "We'll get back to you as soon as possible.\n\n— Villa Bit AI Team",
            fn ($m) => $m
                ->from('inbox@villabit.ai', 'Villa Bit AI')
                ->to($user->email)
                ->subject("Your support ticket #{$ticket->id} received")
        );

        return redirect()->route('investor.support.index')
            ->with('success', 'Support ticket created. Confirmation sent to your email.');
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
