<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'status',
        'priority',
        'assigned_to_user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function messages()
    {
        return $this->hasMany(SupportTicketMessage::class);
    }

    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress', 'waiting']);
    }
}
