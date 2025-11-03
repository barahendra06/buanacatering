<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPackage extends Model
{
    use HasFactory;

    protected $table = 'catering_product_package';

    public function items()
    {
        return $this->hasMany(ProductPackageDetail::class,'catering_product_package_id');
    }

    public function getPackagePriceAttribute()
    {
        return 'Rp. ' . number_format($this->price, 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
