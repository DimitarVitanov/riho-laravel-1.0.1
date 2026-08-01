<?php

namespace App\Models\Est8ads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use SoftDeletes;

    protected $table = 'est8ads_invoices';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2', 'tax_amount' => 'decimal:2', 'total' => 'decimal:2', 'amount_due' => 'decimal:2',
            'line_items' => 'array', 'billing_details' => 'array', 'issued_on' => 'date', 'due_on' => 'date', 'paid_at' => 'datetime',
        ];
    }

    public function profile(): BelongsTo { return $this->belongsTo(Profile::class); }
    public function agency(): BelongsTo { return $this->belongsTo(Agency::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
}
