<?php

namespace App\Src\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = "products";

    protected $fillable = [
        "category_id",
        "subcategory_id",
        "product_name",
        "product_code",
        "bar_code",
        "cost_price",
        "sale_price",
        "status",
        "description"
    ];

    protected $casts = [
        "cost_price" => "decimal:2",
        "sale_price" => "decimal:2",
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubcategoryProduct::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
