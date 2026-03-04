<?php

namespace App\Src\Invoices\Models;

use App\Models\User;
use App\Src\POS\Enums\PaymentMethod;
use App\Src\POS\Models\CardPlan;
use App\Src\POS\Models\CashRegisterMovement;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicePayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'user_id',
        'cash_register_movement_id',
        'amount',
        'payment_method',
        'card_plan_id',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_method' => PaymentMethod::class,
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cashRegisterMovement(): BelongsTo
    {
        return $this->belongsTo(CashRegisterMovement::class);
    }

    public function cardPlan(): BelongsTo
    {
        return $this->belongsTo(CardPlan::class);
    }
}
