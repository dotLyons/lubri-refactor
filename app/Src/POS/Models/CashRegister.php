<?php

namespace App\Src\POS\Models;

use App\Models\User;
use App\Src\POS\Enums\CashRegisterStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashRegister extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'opened_at',
        'closed_at',
        'opening_amount',
        'closing_expected_amount',
        'closing_actual_amount',
        'difference',
        'status',
        'closed_automatically',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'opening_amount' => 'decimal:2',
            'closing_expected_amount' => 'decimal:2',
            'closing_actual_amount' => 'decimal:2',
            'difference' => 'decimal:2',
            'status' => CashRegisterStatus::class,
            'closed_automatically' => 'boolean',
        ];
    }

    /**
     * Get the user who opened/managed the register.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the movements in this cash register session.
     */
    public function movements(): HasMany
    {
        return $this->hasMany(CashRegisterMovement::class);
    }
}
