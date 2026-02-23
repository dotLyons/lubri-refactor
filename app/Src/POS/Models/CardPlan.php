<?php

namespace App\Src\POS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'card_id',
        'name',
        'installments',
        'surcharge_percentage',
        'is_active',
        'is_promotion',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'installments' => 'integer',
            'surcharge_percentage' => 'decimal:2',
            'is_active' => 'boolean',
            'is_promotion' => 'boolean',
        ];
    }

    /**
     * Get the card that owns this plan.
     */
    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
