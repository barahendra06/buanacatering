<?php

namespace App\Services;

use App\CateringOrder;
use App\CateringOrderHistory;

class CateringOrderHistoryManager
{
    public static function createCateringOrderHistory(CateringOrder $storeOrder, $oldStoreOrder, $userCreator)
    {
        if($storeOrder->catering_order_status_id == STORE_ORDER_STATUS_COMPLETED)
        {
            $title = 'Catering Order Payment Confirmed';
            $description = "Payment Order Confirmed, order completed";
        }
        elseif($storeOrder->catering_order_status_id == STORE_ORDER_STATUS_WAITING_CONFIRMATION)
        {
            $title = 'Catering Order Payment Waiting Confirmation';
            $description = "Payment Order Waiting Confirmation, order pending";
        }
        else
        {
            $title = 'Catering Order Payment Failed';
            $description = "Payment Order Failed, order cancelled";
        }

        // create catering order history
        $dataInput = [
            'catering_order_id' => $storeOrder->id,
            'title' => $title,
            'description' => $description,
            'old_catering_order_status_id' => $oldStoreOrder['catering_order_status_id'],
            'new_catering_order_status_id' => $storeOrder->catering_order_status_id,
            'updated_by_id' => $userCreator->id
        ];

        CateringOrderHistory::create($dataInput);
    }
}
