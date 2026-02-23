<?php

namespace App\Src\POS\Models;

use App\Models\User;
use App\Src\POS\Enums\MovementType;
use App\Src\POS\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashRegisterMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'cash_register_id',
        'user_id',
        'type',
        'amount',
        'payment_method',
        'card_plan_id',
        'description',
        'is_manual',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => MovementType::class,
            'amount' => 'decimal:2',
            'payment_method' => PaymentMethod::class,
            'is_manual' => 'boolean',
        ];
    }

    /**
     * Get the cash register session this movement belongs to.
     */
    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    /**
     * Get the user who made the movement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the card plan used for this movement (if any).
     */
    public function cardPlan(): BelongsTo
    {
        return $this->belongsTo(CardPlan::class);
    }
}
