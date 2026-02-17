<?php

namespace App\Src\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = "stocks";

    protected $fillable = [
        "product_id",
        "quantity",
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
