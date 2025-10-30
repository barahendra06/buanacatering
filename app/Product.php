<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'catering_product';

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'catering_product_category_id');
    }

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class, 'catering_product_id');
    }

    public function getProductPriceAttribute()
    {
        return 'Rp. ' . number_format($this->price,0,',','.');
    }
}
