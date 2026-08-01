<?php

namespace App\Models\Est8ads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'est8ads_payments';
    protected $guarded = ['id'];
    protected $hidden = ['provider_payload', 'idempotency_key'];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2', 'refunded_amount' => 'decimal:2', 'provider_payload' => 'encrypted:array', 'metadata' => 'array',
            'processed_at' => 'datetime', 'failed_at' => 'datetime',
        ];
    }

    public function invoice(): BelongsTo { return $this->belongsTo(Invoice::class); }
    public function profile(): BelongsTo { return $this->belongsTo(Profile::class); }
}
