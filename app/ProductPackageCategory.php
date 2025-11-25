<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPackageCategory extends Model
{
    use HasFactory;

    protected $table = 'catering_product_package_category';

    protected $fillable = [
        'name',
    ];

    public function productPackages()
    {
        return $this->hasMany(ProductPackageDetail::class,'catering_product_package_categoy_id');
    }
}
