<?php

namespace App\Services;

use App\ApparelOrder;
use App\Events\Invoice\InvoiceCreated;
use App\Invoice;
use App\CourtOrder;
use App\Member;
use App\Student;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OrderManager
{
    public static function createFieldRent($inputs)
    {
        // check if create new user or select last user
        if (!isset($inputs['user_id']) || $inputs['user_id'] === '')
        {
            $user = new User();
            $user->email = str_replace( ' ', '_', strtolower($inputs['renter'])).'@rentercourt';
            $user->confirmed = 1;
            $user->password = Hash::make('Renter2022Password');
            $user->role_id = ROLE_RENTER;
            $user->allow_newsletter = 0;
            $user->save();

            $member = new Member();
            $member->user_id = $user->id;
            $member->name = $inputs['renter'];
            $member->mobile_phone = $inputs['phone'];
            $member->save();

            $inputs['user_id'] = $user->id;
        }

        $rentOrder = new CourtOrder();
        $rentOrder->fill($inputs);
        $rentOrder->order_status_id = ORDER_STATUS_UNPAID;
        $rentOrder->save();

        $rentOrder->courts()->sync($inputs['courts']);

        InvoiceManager::storeFieldRentOrder($inputs, $rentOrder->load('user'));

        return $rentOrder;
    }

    public static function createApparelOrder($inputs, $withInvoice = true)
    {
        if (count($inputs['apparels']) > 0) {
//            $apparelOrder = new ApparelOrder();
//            $apparelOrder->fill($inputs);
//            $apparelOrder->save();
//
//            $apparelPrices = collect($inputs['apparels'])->map(function ($apparel) {
//                unset($apparel['apparel_id']);
//                unset($apparel['apparel_name']);
//                unset($apparel['apparel_price_name']);
//                unset($apparel['type']);
//                unset($apparel['size']);
//                return $apparel;
//            });
//
//            $apparelOrder->apparelItems()->sync($apparelPrices);

            if ($withInvoice) {
                InvoiceManager::storeApparelOrder($inputs);
            }
        }
    }

    public static function updateApparelOrder(Invoice $invoice, $status)
    {
        $invoiceItems = $invoice->invoiceItems->unique(['subscription_id']);
        foreach ($invoiceItems as $invoiceItem)
        {
            if (isset($invoiceItem->subscription_id) && $invoiceItem->invoice_item_type_id === INVOICE_ITEM_TYPE_APPAREL)
            {
                $apparelOrder = ApparelOrder::query()->with('student.apparels')->find($invoiceItem->subscription_id);
                $student = $apparelOrder->student;

                if ($apparelOrder)
                {
                    foreach ($apparelOrder->apparelItems as $item)
                    {
                        if ($student->apparels->contains($item->apparel_id))
                        {
                            if ($status !== ORDER_STATUS_PAID)
                            {
                                $student->apparels()->detach($item->apparel_id);
                            }
                        } elseif ($status === ORDER_STATUS_PAID) {
                            $student->apparels()->attach($item->apparel_id);
                        }
                    }
                    $apparelOrder->order_status_id = $status;
                    $apparelOrder->save();
                }
            }
        }
    }

    public static function updateRentOrder(Invoice $invoice, $status)
    {
        $invoiceItems = $invoice->invoiceItems->unique(['subscription_id']);
        foreach ($invoiceItems as $invoiceItem)
        {
            if (isset($invoiceItem->subscription_id) && $invoiceItem->invoice_item_type_id === INVOICE_ITEM_TYPE_COURT_ORDER)
            {
                $rentOrder = CourtOrder::query()->find($invoiceItem->subscription_id);

                if ($rentOrder)
                {
                    $rentOrder->order_status_id = $status;
                    $rentOrder->save();
                }
            }
        }
    }
}