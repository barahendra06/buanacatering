<?php

namespace App\Listeners;

use App\Events\CateringOrder\CateringOrderLocked;
use App\Services\CateringShoppingCartManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CateringShoppingCartListener
{
    public function onCateringOrderLocked($event)
    {
        $cateringOrder = $event->cateringOrder;
        CateringShoppingCartManager::removeCart($cateringOrder);
    }
}
