<?php

namespace App\Src\Customers\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'dni',
        'first_name',
        'last_name',
        'primary_phone',
        'secondary_phone',
        'birth_date',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    /**
     * Get the vehicles corresponding to this customer.
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * Get the current account movements for this customer.
     */
    public function accountMovements(): HasMany
    {
        return $this->hasMany(CustomerAccountMovement::class);
    }

    /**
     * Get the current account balance (negative = owes money).
     */
    public function getAccountBalanceAttribute(): float
    {
        $credits = $this->accountMovements()->where('type', 'credit')->sum('amount');
        $debits = $this->accountMovements()->where('type', 'debit')->sum('amount');

        return $credits - $debits;
    }
}
