<?php

namespace App\Src\Customers\Models;

use App\Src\Customers\Enums\PickupCabinType;
use App\Src\Customers\Enums\VehicleType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'type',
        'brand',
        'model',
        'year',
        'license_plate',
        'version',
        'color',
        'pickup_cabin_type',
        'engine_displacement',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => VehicleType::class,
            'pickup_cabin_type' => PickupCabinType::class,
            'year' => 'integer',
        ];
    }

    /**
     * Get the customer that owns the vehicle.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
