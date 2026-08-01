<?php

namespace App\Models\Est8ads;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use SoftDeletes;

    protected $table = 'est8ads_messages';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['attachments' => 'array', 'metadata' => 'array', 'sent_at' => 'datetime', 'edited_at' => 'datetime'];
    }

    public function conversation(): BelongsTo { return $this->belongsTo(Conversation::class); }
    public function senderProfile(): BelongsTo { return $this->belongsTo(Profile::class, 'sender_profile_id'); }
    public function senderUser(): BelongsTo { return $this->belongsTo(User::class, 'sender_user_id'); }
    public function replyTo(): BelongsTo { return $this->belongsTo(self::class, 'reply_to_id'); }
}
