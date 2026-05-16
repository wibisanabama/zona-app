<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'description',
        'daily_rate',
        'deposit_amount',
        'stock_total',
        'stock_available',
        'condition',
        'photo_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'daily_rate' => 'decimal:2',
            'deposit_amount' => 'decimal:2',
            'stock_total' => 'integer',
            'stock_available' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope: only available items (active + stock > 0).
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)->where('stock_available', '>', 0);
    }

    /**
     * Get the photo URL.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo_path) {
            return Storage::url($this->photo_path);
        }

        return null;
    }

    /**
     * Get formatted daily rate in Rupiah.
     */
    public function getFormattedDailyRateAttribute(): string
    {
        return 'Rp ' . number_format($this->daily_rate, 0, ',', '.');
    }

    /**
     * Get formatted deposit amount in Rupiah.
     */
    public function getFormattedDepositAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->deposit_amount, 0, ',', '.');
    }

    /**
     * Item belongs to a category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Item has many rental items.
     */
    public function rentalItems(): HasMany
    {
        return $this->hasMany(RentalItem::class);
    }
}
