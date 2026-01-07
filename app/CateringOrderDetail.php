<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringOrderDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'catering_order_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'catering_order_id',
        'catering_product_package_id',
        'catering_product_id',
        'catering_product_variant_id',
        'unit_price',
        'quantity',
        'total_price',
    ];

    protected $appends = ['unit_price_rupiah','total_price_rupiah'];

    public function cateringOrder()
    {
        return $this->belongsTo('App\CateringOrder');
    }

    public function productPackage()
    {
        return $this->belongsTo('App\ProductPackage', 'catering_product_package_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'catering_product_id');
    }

    public function productVariant()
    {
        return $this->belongsTo('App\ProductVariant', 'catering_product_variant_id');
    }
    
    /**
     * Get the orderedProductName
     *
     * @return string
     */
    public function getOrderedProductNameAttribute()
    {
        $name = null;

        $packageName = $this->productPackage->name ?? null;
        $productName = $this->product->name ?? null;
        $variantName = $this->productVariant->name ?? null;

        if($packageName)
        {
            $name = $packageName;
        }

        if($productName)
        {
            $name = $productName;
        }

        if($variantName)
        {
            $name .= $this->productVariant->product->name . " - " . $variantName;
        }

        return $name;
    }

    /**
     * Get the cart_detail
     *
     * @param  string  $value
     * @return string
     */

    public function getUnitPriceRupiahAttribute()
    {
        return "Rp " . number_format($this->unit_price,0,',','.');
    }

    public function getTotalPriceRupiahAttribute()
    {
        return "Rp " . number_format($this->total_price,0,',','.');
    }

    public function getBrutoPriceRupiahAttribute()
    {
        $unitPrice = $this->unit_price;
        $qty = $this->quantity;

        $brutoPrice = $unitPrice * $qty;

        return "Rp " . number_format($brutoPrice,0,',','.');
    }
}
