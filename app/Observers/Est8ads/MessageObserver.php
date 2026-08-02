<?php

namespace App\Observers\Est8ads;

use App\Models\Est8ads\Message;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Support\Str;

class MessageObserver
{
    /**
     * Handle the Message "created" event.
     * Automatically create or update a VillaBit AI support ticket.
     */
    public function created(Message $message): void
    {
        // Only sync messages that are sent to EST8ADS support/admin
        if (!$this->shouldSyncToTicket($message)) {
            return;
        }

        $conversation = $message->conversation;
        if (!$conversation) {
            return;
        }

        // Find or create support ticket for this conversation
        $ticket = SupportTicket::firstOrCreate(
            [
                'user_id' => $message->sender_user_id,
                'subject' => $this->generateTicketSubject($message, $conversation),
            ],
            [
                'message' => $this->generateTicketDescription($message, $conversation),
                'status' => 'open',
                'priority' => $this->determinePriority($message),
            ]
        );

        // Add message to ticket
        SupportTicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'user_id' => $message->sender_user_id,
            'message' => $this->formatMessageForTicket($message),
            'is_internal_note' => false,
        ]);

        // Update ticket status if it was closed
        if (in_array($ticket->status, ['closed', 'resolved'])) {
            $ticket->update(['status' => 'open']);
        }
    }

    /**
     * Determine if this message should be synced to the ticketing system.
     */
    private function shouldSyncToTicket(Message $message): bool
    {
        // Sync messages of type 'support', 'inquiry', or 'help'
        if (in_array($message->type, ['support', 'inquiry', 'help', 'question'])) {
            return true;
        }

        // Sync messages containing support keywords
        $supportKeywords = ['help', 'support', 'issue', 'problem', 'question', 'assist'];
        $body = strtolower($message->body ?? '');
        
        foreach ($supportKeywords as $keyword) {
            if (str_contains($body, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate ticket subject from message and conversation.
     */
    private function generateTicketSubject(Message $message, $conversation): string
    {
        $subject = $conversation->subject ?? 'EST8ADS Support Request';
        
        // Add property reference if available
        if (isset($message->metadata['property_id'])) {
            $subject .= ' - Property #' . $message->metadata['property_id'];
        }

        return Str::limit($subject, 190);
    }

    /**
     * Generate ticket description from message and conversation.
     */
    private function generateTicketDescription(Message $message, $conversation): string
    {
        $description = "EST8ADS Message:\n\n";
        $description .= $message->body ?? '';
        
        if ($conversation->subject) {
            $description .= "\n\nConversation: " . $conversation->subject;
        }

        return $description;
    }

    /**
     * Determine ticket priority based on message content.
     */
    private function determinePriority(Message $message): string
    {
        $body = strtolower($message->body ?? '');
        
        // High priority keywords
        if (str_contains($body, 'urgent') || str_contains($body, 'asap') || str_contains($body, 'emergency')) {
            return 'high';
        }

        // Medium priority keywords
        if (str_contains($body, 'important') || str_contains($body, 'soon')) {
            return 'medium';
        }

        return 'normal';
    }

    /**
     * Format message for ticket system.
     */
    private function formatMessageForTicket(Message $message): string
    {
        $formatted = $message->body ?? '';

        // Add attachments info if present
        if (!empty($message->attachments)) {
            $formatted .= "\n\n[Attachments: " . count($message->attachments) . " file(s)]";
        }

        // Add metadata context if relevant
        if (!empty($message->metadata)) {
            if (isset($message->metadata['property_id'])) {
                $formatted .= "\n\nProperty ID: " . $message->metadata['property_id'];
            }
            if (isset($message->metadata['chain_id'])) {
                $formatted .= "\nChain ID: " . $message->metadata['chain_id'];
            }
        }

        return $formatted;
    }
}
