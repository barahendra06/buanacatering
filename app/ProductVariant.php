<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'catering_product_variant';

    public function product()
    {
        return $this->belongsTo(Product::class, 'catering_product_id');
    }

    public function getProductPriceAttribute()
    {
        return 'Rp. ' . number_format($this->price,0,',','.');
    }
}
