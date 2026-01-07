<?php

namespace App\Services;

use App\CateringOrder;
use App\CateringShoppingCart;

class CateringShoppingCartManager
{
    public static function removeCart(CateringOrder $cateringOrder)
    {
        $cateringOrderDetails = $cateringOrder->cateringOrderDetails;
        CateringShoppingCart::where('user_id', $cateringOrder->user_id)
                            ->get()
                            ->each(function ($cart)
                            {
                                $cart->delete();
                            });
        }
}
