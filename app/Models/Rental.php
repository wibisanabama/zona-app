<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'customer_id', 'cashier_id', 'rental_date', 'due_date',
        'actual_return_date', 'days', 'subtotal', 'discount', 'total_deposit',
        'total_amount', 'paid_amount', 'late_fee', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'rental_date' => 'date',
            'due_date' => 'date',
            'actual_return_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'total_deposit' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'late_fee' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($rental) {
            if (empty($rental->code)) {
                $today = now()->format('Ymd');
                $lastRental = static::where('code', 'like', "SW-{$today}-%")
                    ->orderBy('code', 'desc')
                    ->first();

                $sequence = 1;
                if ($lastRental) {
                    $lastSeq = (int) substr($lastRental->code, -4);
                    $sequence = $lastSeq + 1;
                }

                $rental->code = sprintf('SW-%s-%04d', $today, $sequence);
            }
        });
    }

    /**
     * Dynamically check if rental is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'aktif' && Carbon::today()->gt($this->due_date);
    }

    /**
     * Get effective status (marks as terlambat dynamically).
     */
    public function getEffectiveStatusAttribute(): string
    {
        if ($this->is_overdue) {
            return 'terlambat';
        }

        return $this->status;
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp '.number_format($this->total_amount, 0, ',', '.');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function rentalItems(): HasMany
    {
        return $this->hasMany(RentalItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
