<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CateringReview extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function productPackage()
    {
        return $this->belongsTo('App\ProductPackage');
    }
}
