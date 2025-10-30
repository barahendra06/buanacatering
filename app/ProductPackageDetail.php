<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPackageDetail extends Model
{
    use HasFactory;

    protected $table = 'catering_product_Package_detail';

    public function package()
    {
        return $this->belongsTo(ProductPackage::class, 'catering_product_package_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'catering_product_id');
    }
}
