<?php

namespace App\Src\Inventory\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $table = "stock_movements";

    protected $fillable = [
        "product_id",
        "user_id",
        "type",
        "quantity",
        "reason",
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
