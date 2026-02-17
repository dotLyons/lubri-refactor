<?php

namespace App\Src\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubcategoryProduct extends Model
{
    use SoftDeletes;

    protected $table = "products_subcategories";

    protected $fillable = [
        "subcategory_name",
        "description",
        "status"
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'subcategory_id');
    }

}
