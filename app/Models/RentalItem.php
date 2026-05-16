<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalItem extends Model
{
    protected $fillable = [
        'rental_id', 'item_id', 'quantity', 'daily_rate',
        'deposit_amount', 'subtotal', 'returned_quantity',
        'condition_on_return', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'daily_rate' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'quantity' => 'integer',
            'returned_quantity' => 'integer',
        ];
    }

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
