<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'rental_id', 'cashier_id', 'amount', 'method', 'type', 'paid_at', 'reference_no', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp '.number_format($this->amount, 0, ',', '.');
    }

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function scopeIncome($query)
    {
        return $query->whereIn('type', ['dp', 'pelunasan', 'denda']);
    }

    public function scopeOutflow($query)
    {
        return $query->whereIn('type', ['refund_deposit']);
    }
}
