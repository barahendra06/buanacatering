<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringShoppingCart extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'catering_shopping_cart';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'product_variant_id',
        'product_package_id',
        'user_id',
        'quantity'
    ];


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'catering_product_id');
    }

    public function productVariant()
    {
        return $this->belongsTo('App\ProductVariant', 'catering_product_variant_id');
    }

    public function productPackage()
    {
        return $this->belongsTo('App\ProductPackage', 'catering_product_package_id');
    }

    /**
     * Get the cart_detail
     *
     * @param  string  $value
     * @return string
     */
    public function getCartDetailAttribute()
    {
        $variant = $this->store_product_variant;
        $size = $this->store_product_size;

        if($variant && $size)
        {
            return "Size $size->name / Color $variant->name";
        }
        else if($variant && is_null($size))
        {
            return "Color $variant->name";
        }
        else if(is_null($variant) && $size)
        {
            return "Size $size->name";
        }
        else
        {
            return null;
        }
    }
}
