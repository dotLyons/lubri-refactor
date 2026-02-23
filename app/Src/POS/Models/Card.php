<?php

namespace App\Src\POS\Models;

use App\Src\POS\Enums\CardType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => CardType::class,
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the plans associated with the card.
     */
    public function plans(): HasMany
    {
        return $this->hasMany(CardPlan::class);
    }
}
