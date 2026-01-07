<?php

namespace App\Services;

use Carbon\Carbon;
use App\CateringOrder;
use App\Services\CateringOrderHistoryManager;

class CateringOrderManager
{
    public static function updateOrderStatus(CateringOrder $storeOrder, $status)
    {
        $oldStoreOrder = $storeOrder->getOriginal();
        
        $storeOrder->catering_order_status_id = $status;

        if($status == STORE_ORDER_STATUS_COMPLETED)
        {
            $storeOrder->completed_at = Carbon::now();
        }
        
        $storeOrder->save();

        $userCreator = auth()->user();

        CateringOrderHistoryManager::createCateringOrderHistory($storeOrder, $oldStoreOrder, $userCreator);
    }
}
