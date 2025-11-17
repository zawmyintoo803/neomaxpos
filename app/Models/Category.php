<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
        protected $fillable = [
        'restaurant_id',
        'category_name',
        'slug',
        'status',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function products() {
        return $this->hasMany(Product::class);
    }


}
