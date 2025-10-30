<?php

namespace App\Services;

use App\ApparelOrder;
use App\Court;
use App\CourtOrder;
use App\Subscription;
use App\Invoice;
use App\InvoiceItem;
use App\InvoiceHistory;
use App\Payment;
use App\User;
use App\BranchPricePlan;
use App\Student;
use App\Team;
use App\TeamRegistration;
use App\PaymentType;

use App\Events\Invoice\InvoiceCreated;
use App\Events\Invoice\InvoicePaid;
use App\Events\Invoice\InvoiceUnpaid;
use App\Events\Invoice\InvoiceCancelled;
use App\Events\Invoice\InvoiceChangedBalance;

use App\Variant;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Log;
use DB;
use Auth;

class InvoiceManager
{
    /**
     * store invoice, this service will create new invoice.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public static function storeInvoice(Request $request, $subscriptionId, ApparelOrder $apparelOrder = null)
    {        
        try
        {
            $subscription = Subscription::findOrFail($subscriptionId);

            // get price plan of current student branch
            $branchPricePlan = BranchPricePlan::where('branch_id', $subscription->student->branch_id)
                                                ->where('price_plan_id', $subscription->pricePlan->id)
                                                ->firstOrFail();

            DB::transaction(function () use($request, $subscription, $branchPricePlan, $apparelOrder)
            {
                $userCreator = Auth::user();
                $member = $userCreator->member;

                $billing_start_date = Carbon::createFromFormat('d-m-Y', $request->billing_start_date);
                $invoice_date = $request->invoice_date ? Carbon::make($request->invoice_date) : Carbon::today();
                $invoice = new Invoice();
                $invoice->creator_id = $member->id;
                $invoice->user_id = $subscription->student->user_id;
                $invoice->student_id = $subscription->student_id;
                $invoice->branch_id = $subscription->student->branch_id;
                $invoice->date = $invoice_date->toDateString();
                $invoice->due_date = $invoice_date->addDays(30)->toDateString();
                $invoice->invoice_status_id = INVOICE_STATUS_UNPAID;
                $invoice->amount = 0;
                $invoice->remark = $request->remark !== '' ? $request->remark : null;
                $invoice->save();

                //create invoice item
                $invoice_item = new InvoiceItem();
                $invoice_item->invoice_id = $invoice->id;
                $invoice_item->invoice_item_type_id = INVOICE_ITEM_TYPE_SUBSCRIPTION;
                $invoice_item->description = $subscription->pricePlan->name;

                //link the subscription to this invoice item
                $invoice_item->subscription_id = $subscription->id;

                if(isset($subscription->pricePlan->frequency_id))
                {
                    if($subscription->pricePlan->frequency_id == FREQUENCY_MONTH)
                    {
                        //new subscription start date
                        //use carbon copy function to create a copy of an existing Carbon instance (this will prevent Carbon to updates the object value)
                        $invoice_item->start_date = $billing_start_date->copy()->startOfMonth();     

                        //new subscription end date
                        $price_plan_period = $subscription->pricePlan->period-1;
                        //use carbon copy function to create a copy of an existing Carbon instance (this will prevent Carbon to updates the object value)
                        $invoice_item->end_date = $billing_start_date->copy()->startOfMonth()->addMonths($price_plan_period)->endOfMonth();  
                    }
                    else if($subscription->pricePlan->frequency_id == FREQUENCY_DAY)
                    {
                        //new subscription start date
                        //use carbon copy function to create a copy of an existing Carbon instance (this will prevent Carbon to updates the object value)
                        $invoice_item->start_date = $billing_start_date->copy()->addDay();     

                        //new subscription end date
                        //use carbon copy function to create a copy of an existing Carbon instance (this will prevent Carbon to updates the object value)
                        $invoice_item->end_date = $billing_start_date->copy()->addDay()->addDays($subscription->pricePlan->period);
                    }
                }
                $invoice_item->quantity = 1;
                // $invoice_item->unit_price = $subscription->pricePlan->amount;
                $invoice_item->unit_price = $branchPricePlan->amount;
                $invoice_item->discount = ($request->billing_discount != '')? $request->billing_discount:0;
                // calculate amount per item
                $invoiceItemAmount = $invoice_item->quantity * $invoice_item->unit_price;

                $invoice_item->amount = $invoiceItemAmount - $invoice_item->discount;
                $invoice_item->save();
                
                $invoice->amount += $invoiceItemAmount;

                if(isset($request->registration_fee))
                {
                    //create invoice item for registration fee
                    $registration_fee_item = new InvoiceItem();
                    $registration_fee_item->invoice_id = $invoice->id;
                    $registration_fee_item->invoice_item_type_id = INVOICE_ITEM_TYPE_REGISTRATION;
                    $registration_fee_item->description = $request->isActivation ? "Activation Fee" : "Registration Fee";
                    $registration_fee_item->quantity = 1;
                    $registration_fee_item->unit_price = ($request->registration_fee != '')? $request->registration_fee:0;
                    $registration_fee_item->discount = ($request->registration_discount != '')? $request->registration_discount:0;
                    // calculate amount per item
                    $registrationFeeAmount = $registration_fee_item->quantity * $registration_fee_item->unit_price;
                    
                    $registration_fee_item->amount = $registrationFeeAmount - $registration_fee_item->discount;
                    $registration_fee_item->save();
                
                    $invoice->amount += $registrationFeeAmount;
                }

                $apparel_discount = 0;
                if ($request['apparels'] && count($request['apparels']) > 0)
                {
                    $invoice = self::storeApparelOrder($request->all(), $invoice, $subscription);

                    $apparels = collect($request['apparels']);
                    $amount = $apparels->sum(function ($apparelItem) {
                        return $apparelItem['unit_price'] * $apparelItem['quantity'];
                    });
                    $discount = $apparels->sum(function ($apparelItem) {
                        return $apparelItem['discount'];
                    });

                    $apparel_discount += $discount;
                    $invoice->amount += $amount;
                }

                //finalize invoice
                $invoice->discount = (($request->billing_discount != '')? $request->billing_discount:0)
                    + (($request->registration_discount != '')? $request->registration_discount:0)
                    + $apparel_discount;
                $invoice->tax = 0;
                $invoice->final_amount = $invoice->amount - $invoice->discount + $invoice->tax;

                $invoice->description = $subscription->pricePlan->course->name. " ". $invoice_item->start_date->toDateString(). " " .$invoice_item->end_date->toDateString();

                $invoice->save();

                //fire an event when invoice is created
                $oldInvoice = null;
                event(new InvoiceCreated($invoice, $oldInvoice, $userCreator));
            });
            return true;
        }
        catch(\Exception $e)
        {
            Log::info($e->getMessage());
            return false;
        }
    }

    /**
     * store invoice trial, this service will create new invoice.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public static function storeInvoiceTrial(Request $request, $studentId)
    {        
        try
        {
            $student = Student::findOrFail($studentId);

            // get price plan of current student branch
            $branchPricePlan = BranchPricePlan::with('pricePlan')
                                                ->where('branch_id', $student->branch_id)
                                                ->where('price_plan_id', $request->student_price_plan)
                                                ->firstOrFail();
                                                
            DB::transaction(function () use($request, $student, $branchPricePlan, &$invoice) 
            {
                $userCreator = Auth::user();
                $member = $userCreator->member;
                
                $invoice = new Invoice();
                $invoice->creator_id = $member->id;
		$invoice->user_id = $student->user_id;
                $invoice->student_id = $student->id;
                $invoice->branch_id = $student->branch_id;
                $invoice->date = Carbon::today()->toDateString();
                $invoice->due_date = Carbon::today()->addDays(30)->toDateString();
                $invoice->invoice_status_id = INVOICE_STATUS_UNPAID;
                $invoice->amount = 0;
                $invoice->save();

                if(is_array($request->billing_date))
                {
                    $billingDateInfo = implode(", ", $request->billing_date);
                }
                else
                {
                    $billingDateInfo = $request->billing_date;
                }

                //create invoice item
                $invoice_item = new InvoiceItem();
                $invoice_item->invoice_id = $invoice->id;
                $invoice_item->invoice_item_type_id = INVOICE_ITEM_TYPE_TRIAL;
                $invoice_item->description = $branchPricePlan->pricePlan->name;

                $invoice_item->quantity = 1;
                $invoice_item->unit_price = $branchPricePlan->amount;
                $invoice_item->amount = $invoice_item->quantity * $invoice_item->unit_price;
                $invoice_item->save();
                
                $invoice->amount += $invoice_item->amount;

                //finalize invoice
                $invoice->discount = ($request->discount != '')? $request->discount:0;
                $invoice->tax = 0;
                $invoice->final_amount = $invoice->amount - $invoice->discount + $invoice->tax;

                $invoice->description = $branchPricePlan->pricePlan->course->name ." ". $billingDateInfo;

                $invoice->save();

                //fire an event when invoice is created
                $oldInvoice = null;
                event(new InvoiceCreated($invoice, $oldInvoice, $userCreator));
            });

            return $invoice;
        }
        catch(\Exception $e)
        {
            Log::info($e->getMessage());
            return false;
        }
    }

    public static function storeFieldRentOrder($inputs, CourtOrder $courtOrder)
    {
        try
        {
            DB::transaction(function () use($inputs, $courtOrder, &$invoice)
            {
                $userCreator = Auth::user();
                $member = $userCreator->member;

                $invoice_date = $inputs['invoice_date'] ? Carbon::make($inputs['invoice_date']) : Carbon::today();

                $invoice = new Invoice();
                $invoice->creator_id = $member->id;
                $invoice->user_id = $courtOrder->user_id;
                $invoice->student_id = null;
                $invoice->branch_id = $inputs['branch_id'];
                $invoice->date = $invoice_date->toDateString();
                $invoice->due_date = $invoice_date->addDays(30)->toDateString();
                $invoice->invoice_status_id = INVOICE_STATUS_UNPAID;
                $invoice->amount = $courtOrder->courts->sum('amount');
                $invoice->discount = $courtOrder->courts->sum(function ($courtItem) {
                    return $courtItem->pivot->discount;
                });
                $invoice->final_amount = $courtOrder->courts->sum(function ($courtItem) {
                    return $courtItem->pivot->final_amount;
                });
                // $invoice->description = $courtOrder->courts->pluck('name')->join(', ');
                $invoice->description = $courtOrder->courts()->distinct('court_order_id')->pluck('name')->join(', ').' ('.$courtOrder->courts[0]->branch->name.')';
                $invoice->save();

                // quantity for invoice court order is in hour
                foreach($courtOrder->courts as $court) {
                    $invoice->invoiceItems()->create([
                        'invoice_item_type_id' => INVOICE_ITEM_TYPE_COURT_ORDER,
                        'description' => $court->name.' (' . $court->pivot->start_at->format('Y-m-d') . ')',
                        'unit_price' => $court->amount,
                        'quantity' => $court->pivot->duration,
                        'amount' => $court->pivot->final_amount,
                        'discount' => $court->pivot->discount,
                        'start_date' => $court->pivot->start_at,
                        'end_date' => $court->pivot->end_at,
                        'subscription_id' => $courtOrder->id,
                    ]);
                }

                $userCreator = $courtOrder->user;

                //fire an event when invoice is created
                $oldInvoice = null;
                event(new InvoiceCreated($invoice, $oldInvoice, $userCreator));
            });

            return $invoice;
        } catch(\Exception $e)
        {
            Log::info($e->getMessage());
            return false;
        }
    }

    public static function storeApparelOrder($inputs, Invoice $invoice = null, Subscription $subscription = null)
    {
        try
        {
            DB::transaction(function () use($inputs, &$invoice)
            {
                $userCreator = Auth::user();
                $member = $userCreator->member;
                $isMergeInvoice = !!$invoice;

                // map apparels array to collection
                $apparels = collect($inputs['apparels']);

                if (!$isMergeInvoice) {
                    $invoice_date = $inputs['invoice_date'] ? Carbon::make($inputs['invoice_date']) : Carbon::today();
                    $student = Student::query()->findOrFail($inputs['student_id']);

                    $invoice = new Invoice();
                    $invoice->creator_id = $member->id;
                    $invoice->user_id = $student->user_id;
                    $invoice->student_id = $student->id;
                    $invoice->branch_id = $student->branch_id;
                    $invoice->date = $invoice_date->toDateString();
                    $invoice->due_date = $invoice_date->addDays(30)->toDateString();
                    $invoice->invoice_status_id = INVOICE_STATUS_UNPAID;
                    $invoice->amount = $apparels->sum(function ($apparelItem) {
                        return $apparelItem['quantity'] * $apparelItem['unit_price'];
                    });
                    $invoice->discount = $apparels->sum(function ($apparelItem) {
                        return $apparelItem['discount'];
                    });
                    $invoice->final_amount = $apparels->sum(function ($apparelItem) {
                        return $apparelItem['amount'];
                    });
                    $invoice->description = $apparels->map(function ($item) {
                        return $item['apparel_name'].' '.$item['type'];
                    })->join(', ');
                    $invoice->save();
                }

                foreach ($apparels as $item)
                {
                    $invoice->invoiceItems()->create([
                        'invoice_item_type_id' => INVOICE_ITEM_TYPE_APPAREL,
                        'description' => $item['apparel_name'].' '.$item['type'].($item['apparel_id'] === PRODUCT_JERSEY ? ', No:'.$item['jersey_number'] ?? '-' : ''),
                        'unit_price' => (int)$item['unit_price'],
                        'quantity' => (int)$item['quantity'],
                        'amount' => (int)$item['amount'],
                        'discount' => (int)$item['discount'],
                        'start_date' => null,
                        'end_date' => null,
                        // 'subscription_id' => $item['variant_id'],
                        'subscription_id' => $subscription->id,
                    ]);
                }
                if (!$isMergeInvoice) {
                    //fire an event when invoice is created
                    $oldInvoice = null;
                    event(new InvoiceCreated($invoice, $oldInvoice, $userCreator = null));
                }
            });

            return $invoice;
        } catch(\Exception $e)
        {
            Log::info($e->getMessage());
            return false;
        }
    }

    /**
     * generate invoice, this service will generate invoice, usually this function is triggered by system.
     *
     * @param  int $id
     * @return bool
     */
    public static function generateInvoice()
    {        
        try
        {
            //get all the subscriptions which expiration date is within a limit (ex: a month)
            $subscriptions = Subscription::active()->expiring()->with('student', 'pricePlan.course')->get();

            echo "There are ". $subscriptions->count() ." active and expiring subscriptions..\n";        

            //create the invoice for each subscriptions
            foreach($subscriptions as $subscription)
            {
                //check to avoid duplicate invoice
                if($subscription->invoiceItems()->unpaid()->count())
                {
                    echo "There are already open invoice, skip creating invoice for : ".$subscription->student->name. "\n";
                    continue;
                }

                // get price plan of current student branch
                $branchPricePlan = BranchPricePlan::where('branch_id', $subscription->student->branch_id)
                                                    ->where('price_plan_id', $subscription->pricePlan->id)
                                                    ->first();
                
                if(!$branchPricePlan)
                {
                    echo "There are subscription that doesn't have a ".$subscription->pricePlan->name." of ".$subscription->student->branch->name." branch, skip creating invoice for : ".$subscription->student->name. "\n";
                    continue;
                }

                //create new invoice
                $invoice = new Invoice();
                $invoice->user_id = $subscription->student->user_id;
                $invoice->student_id = $subscription->student->id;
                $invoice->branch_id = $subscription->student->branch_id;
                $invoice->date = Carbon::today()->toDateString();
                $invoice->due_date = Carbon::today()->addDays(30)->toDateString();
                $invoice->invoice_status_id = INVOICE_STATUS_UNPAID;
                $invoice->amount = 0;
                $invoice->save();

                //create invoice item
                $invoice_item = new InvoiceItem();
                $invoice_item->invoice_id = $invoice->id;
                $invoice_item->description = $subscription->pricePlan->name;

                //link the subscription to this invoice item
                $invoice_item->subscription_id = $subscription->id;
                $invoice_item->invoice_item_type_id = INVOICE_ITEM_TYPE_SUBSCRIPTION;

                if($subscription->pricePlan->frequency_id == FREQUENCY_MONTH)
                {
                    //new subscription start date
                    $invoice_item->start_date = $subscription->end_date->startOfMonth()->addMonth();     

                    //new subscription end date
                    $invoice_item->end_date = $subscription->end_date->startOfMonth()->addMonths($subscription->pricePlan->period)->endOfMonth();       
                }
                else
                if($subscription->pricePlan->frequency_id == FREQUENCY_DAY)
                {
                    //new subscription start date
                    $invoice_item->start_date = $subscription->end_date->addDay();     

                    //new subscription end date
                    $invoice_item->end_date = $subscription->end_date->addDay()->addDays($subscription->pricePlan->period);       
                }

                $invoice_item->quantity = 1;
                // $invoice_item->unit_price = $subscription->pricePlan->amount;
                $invoice_item->unit_price = $branchPricePlan->amount;
                $invoice_item->amount = $invoice_item->quantity * $invoice_item->unit_price;
                $invoice_item->save();
                
                $invoice->amount += $invoice_item->amount;

                //finalize invoice
                $invoice->discount = 0;
                $invoice->tax = 0;
                $invoice->final_amount = $invoice->amount - $invoice->discount - $invoice->tax;

                $invoice->description = $subscription->pricePlan->course->name. " ". $invoice_item->start_date->toDateString(). " " .$invoice_item->end_date->toDateString();

                $invoice->save();

                echo "Invoice ID : ".$invoice->id." generated..\n";
                
                //fire an event when invoice is created
                $oldInvoice = null;
                $userCreator = null;
                event(new InvoiceCreated($invoice, $oldInvoice, $userCreator));
            }
            return true;
        }
        catch(\Exception $e)
        {
            Log::info($e->getMessage());
            return false;
        }
    }

    /**
     * update invoice, this service will update invoice status.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public static function updateInvoiceStatus($payment, $userCreator = null)
    {        
        try
        {
            //check total amount of payment that has been confirmed
            $totalPayment = Payment::where('invoice_id', $payment->invoice_id)
                                    ->where('payment_status_id', PAYMENT_CONFIRMED)
                                    ->sum('confirmed_amount');

            //find the linked invoice
            $invoice = Invoice::findOrFail($payment->invoice_id);

            //compare between invoice amount and payment amount when invoice status is not cancelled
            if($invoice->invoice_status_id != INVOICE_STATUS_CANCELLED)
            {
                //change invoice status to paid when total amount of payment enough or exceed invoice amount
                if($totalPayment >= $invoice->final_amount)
                {
                    //add exceed payment to balance if any
                    $exceededPayment = $totalPayment - $invoice->final_amount;

                    if($exceededPayment > 0)
                    {
                        //invoice balance added
                        $invoice->balance_added = $totalPayment-$invoice->final_amount;
                        $invoice->save();

                        //update user balance
                        $invoice->student->user->balance += $exceededPayment;
                        $invoice->student->user->save();

                        //call balance increase event
                        event(new InvoiceChangedBalance($invoice, $exceededPayment));
                        
                    }

                    //change invoice status to paid
                    InvoiceManager::payInvoice($invoice, $userCreator);
                }
                else
                {
                    $userBalance = $invoice->student_id ? $invoice->student->user->balance : 0;

                    //check if the user balance enough to help pay the invoice
                    if(($totalPayment + $userBalance) >= $invoice->final_amount && $invoice->invoice_status_id !== INVOICE_STATUS_PAID)
                    {
                        //invoice balance used
                        $invoice->balance_used = $invoice->final_amount - $totalPayment;
                        $invoice->save();

                        //update user balance
                        $invoice->student->user->balance -= $invoice->balance_used;
                        $invoice->student->user->save();

                        //call balance decrease event
                        event(new InvoiceChangedBalance($invoice, -$invoice->balance_used));
                        
                        //change invoice status to paid
                        InvoiceManager::payInvoice($invoice, $userCreator);
                    }
                    else
                    {
                        //do nothing, not enough amount to pay the invoice
                    }
                }

/*
                else //if payment amount is still below invoice amount, make sure the invoice status is unpaid in case there's payment cancelled
                { 
                    InvoiceManager::unpayInvoice($invoice, $userCreator);
                }
*/
            }
            return true;
        }
        catch(\Exception $e)
        {
            Log::info($e->getMessage());
            return false;
        }
    }
   
    /**
     * create invoice history, this service will insert invoice history.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public static function payInvoice($invoice, $userCreator)
    {        
        try
        {
            $invoice = Invoice::findOrFail($invoice->id);   

            //check invoice status is not paid
            if($invoice->invoice_status_id == INVOICE_STATUS_PAID)
            {
                return;
            }

            DB::transaction(function () use($invoice, $userCreator) 
            {
                $oldInvoice = $invoice->getOriginal();

                $invoice->invoice_status_id = INVOICE_STATUS_PAID;
                $invoice->receipt_number = Carbon::now()->format('YmdHis') . '-' . $invoice->id;
                $invoice->receipt_date = Carbon::now();
                $invoice->save(); 

                //fire an event when invoice is paid
                //this event will update subscription status and date
                event(new InvoicePaid($invoice, $oldInvoice, $userCreator));
            });

            return true;
        }
        catch(\Exception $e)
        {
            Log::info($e->getMessage());
            return false;
        }
    }

    /**
     * create invoice history, this service will insert invoice history.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public static function unpayInvoice($invoice, $userCreator)
    {        
        try
        {
            $invoice = Invoice::findOrFail($invoice->id);

            //check invoice status is not unpaid
            if($invoice->invoice_status_id == INVOICE_STATUS_UNPAID && $invoice->invoice_status_id == INVOICE_STATUS_PENDING_CONFIRMATION)
            {
                return;
            }

            DB::transaction(function () use($invoice, $userCreator) 
            {
                $oldInvoice = $invoice->getOriginal();

                //check total amount of payment that has been confirmed
                $confirmedPayments = Payment::where('invoice_id', $invoice->id)
                    ->where('payment_status_id', PAYMENT_CONFIRMED)
                    ->get();

                if ($invoice->student_id && $confirmedPayments->count() === 0) {
                    $user = $invoice->student->user;
                    if($invoice->balance_used > 0) //return the balance used in the invoice to user balance
                    {
                        $balance_used = $invoice->balance_used;

                        //update user balance
                        $user->balance += $balance_used;
                        $user->save();

                        //update invoice balance used
                        $invoice->balance_used = 0;
                        $invoice->save();

                        //call event balance increase
                        event(new InvoiceChangedBalance($invoice, $balance_used));
                    }

                    if($invoice->balance_added > 0) //return the balance previously added to user balance
                    {
                        $balance_added = $invoice->balance_added;

                        //update user balance
                        $user->balance -= $balance_added;
                        $user->save();

                        //update invoice balance added
                        $invoice->balance_added = 0;
                        $invoice->save();

                        //call event balance decrease
                        event(new InvoiceChangedBalance($invoice, -$balance_added));
                    }
                }

                //check if the user balance enough to help pay the invoice
                if(($confirmedPayments->sum('confirmed_amount') + $invoice->balance_used) >= $invoice->final_amount) {
                    $invoice->invoice_status_id = INVOICE_STATUS_PAID;
                } elseif ($invoice->payments->contains('payment_status_id', PAYMENT_PENDING_CONFIRMATION)) {
                    $invoice->invoice_status_id = INVOICE_STATUS_PENDING_CONFIRMATION;
                } else {
                    $invoice->invoice_status_id = INVOICE_STATUS_UNPAID;
                }

                $invoice->receipt_number = null;
                $invoice->receipt_date = null;
                $invoice->save();

                //fire an event when invoice is unpaid
                event(new InvoiceUnpaid($invoice, $oldInvoice, $userCreator));
            });

            return true;
        }
        catch(\Exception $e)
        {
            Log::info($e->getMessage());
            return false;
        }
    }

    /**
     * create invoice history, this service will insert invoice history.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public static function cancelInvoice($invoice, $userCreator, $remark = null)
    {        
        try
        {
            $invoice = Invoice::findOrFail($invoice->id);

            //check invoice status is not cancelled
            if($invoice->invoice_status_id == INVOICE_STATUS_CANCELLED)
            {
                return;
            }

            DB::transaction(function () use($invoice, $userCreator, $remark)
            {
                $oldInvoice = $invoice->getOriginal();

                if ($invoice->student_id) {
                    // update user balance
                    $user = $invoice->student->user;

                    if ($invoice->balance_used > 0) //return the balance used in the invoice to user balance
                    {
                        $balance_used = $invoice->balance_used;

                        //update user balance
                        $user->balance += $balance_used;
                        $user->save();

                        //update invoice balance used
                        $invoice->balance_used = 0;
                        $invoice->save();

                        //call event balance increase
                        event(new InvoiceChangedBalance($invoice, $balance_used));
                    }

                    if ($invoice->balance_added > 0) //return the balance previously added to user balance
                    {
                        $balance_added = $invoice->balance_added;

                        //update user balance
                        $user->balance -= $balance_added;
                        $user->save();

                        //update invoice balance added
                        $invoice->balance_added = 0;
                        $invoice->save();

                        //call event balance decrease
                        event(new InvoiceChangedBalance($invoice, -$balance_added));
                    }
                }

                // update invoice status to cancelled
                $invoice->invoice_status_id = INVOICE_STATUS_CANCELLED;
                $invoice->remark = $remark;
                $invoice->save();

                //fire an event when invoice is canceled
                event(new InvoiceCancelled($invoice, $oldInvoice, $userCreator));

                // check total confirmed payment for this invoice
                $totalPayment = Payment::where('invoice_id', $invoice->id)
                                        ->where('payment_status_id', PAYMENT_CONFIRMED)
                                        ->sum('confirmed_amount');
                
                // add all payment to user balance if any
                if($totalPayment > 0)
                {
                    $user->balance += $totalPayment;
                    $user->save(); 

                    //fire balance increased event
                    event(new InvoiceChangedBalance($invoice, $totalPayment));
                }

            });

            return true;
        }
        catch(\Exception $e)
        {
            Log::info($e->getMessage());
            return false;
        }
    }

    /**
     * create invoice history, this service will insert invoice history.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public static function createInvoiceHistory($newInvoice, $oldInvoice, $userCreator)
    {        
        try
        {
            DB::transaction(function () use($newInvoice, $oldInvoice, $userCreator) 
            {
                $invoiceHistory = new InvoiceHistory();
                $invoiceHistory->invoice_id = $newInvoice['id'];
                $invoiceHistory->old_invoice_status_id = (isset($oldInvoice['invoice_status_id']))? $oldInvoice['invoice_status_id']: null;
                $invoiceHistory->new_invoice_status_id = $newInvoice['invoice_status_id'];
                $invoiceHistory->user_id = ($userCreator != null)? $userCreator->id: null;
                $invoiceHistory->save();
            });
            return true;
        }
        catch(\Exception $e)
        {
            Log::info($e->getMessage());
            return false;
        }
    }

    public static function teamCreated(Team $team)
    {
        if ($team->competition->isPaymentVisible())
        {
            // dd($team->creator_id);

            if($team->creator_id)
            {
                $user = User::find($team->creator_id);
            }
            else
            {
                $user = User::find($team->teamMembers->first()->user_id);
            }

            $competition = $team->competition;
            // self::createInvoiceManual($team, $leader, $paymentMethod);

            $packageFee = 0;

            if($team->event_package_id && $team->event_package_id != "")
            {
                $packageFee = $team->eventPackage->price;
            }

            $competitionFee = $competition->registration_fee;

            $fee = $competitionFee + $packageFee;

            // create invoice
            $invoice = new Invoice();
            $invoice->user_id = $user->id;
            $invoice->amount = $fee;
            $invoice->tax = 0;
            //$invoice->discount = 0;
	        // $invoice->discount = $competition->registration_fee;

            $unique_code = 0;
            if($team->competition->registration_fee > 0)
            {
               $unique_code = self::getUniqueCode();
            }

            $invoice->unique_code = $unique_code;

            $invoice->final_amount = ($invoice->amount - $invoice->discount) + $invoice->unique_code;

            if($team->event_package_id && $team->event_package_id != "")
            {
                $invoice->description = 'Registration '. $competition->name . ' ('.$team->eventPackage->name.')' ;
            }
            else
            {
                $invoice->description = 'Registration '. $competition->name;
            }

            // $invoice->description = 'Registration '. $competition->name;

            $invoice->due_date = Carbon::now()->addHours(48)->toDateTimeString();
            $invoice->date = Carbon::now()->toDateTimeString();
            $invoice->invoice_status_id = INVOICE_STATUS_UNPAID;

            // save to get team id
            $invoice->save();

            // build order number
            $randomLength =  MAX_ORDER_NUMBER_LENGTH - strlen((string)$user->id);
            $invoice->invoice_number = $user->id.self::incrementalHash($randomLength);
            $invoice->save();

            // create invoice item
            $invoiceItem = new InvoiceItem();
            $invoiceItem->invoice_id = $invoice->id;
            $invoiceItem->invoice_item_type_id = INVOICE_ITEM_TYPE_REGISTRATION_TOURNAMENT_FEE;
            if($team->event_package_id && $team->event_package_id != "")
            {
                $invoiceItem->description = 'Registration '. $competition->name . ' (' . $team->eventPackage->name.')';
            }
            else
            {
                $invoiceItem->description = 'Registration '. $competition->name;
            }

            $invoiceItem->quantity = 1;
            $invoiceItem->unit_price = $fee;
            $invoiceItem->amount = $fee;
            $invoiceItem->save();

            // create team registration
            $teamRegistration = new TeamRegistration();
            $teamRegistration->team_id = $team->id;
            $teamRegistration->invoice_id = $invoice->id;
            $teamRegistration->invitation_code_id = $team->invitation_code_id;
            $teamRegistration->save();
            
            $paymentType = PaymentType::find(PAYMENT_TYPE_TRANSFER);

            $payment = new Payment();
            $payment->creator_id = $user->id;
            $payment->invoice_id = $invoice->id;
            $payment->amount = $invoice->final_amount;
            $payment->payment_type_id = $paymentType->id;
            $payment->payment_category_id = PAYMENT_CATEGORY_REGISTRATION_TOURNAMENT;
            $payment->payment_status_id = PAYMENT_PENDING_CONFIRMATION;
            $payment->save();
        }
    }

    public static function incrementalHash($len = 5){
        $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $base = strlen($charset);
        $result = '';

        $now = explode(' ', microtime())[1];
        while ($now >= $base){
            $i = $now % $base;
            $result = $charset[$i] . $result;
            $now /= $base;
        }
        return substr($result, -5);
    }

    public static function getUniqueCode()
    {        
        $nextUniqueNumber = floatval(0.01);
        $date = Carbon::now()->toDateString();
        $from = $date . ' 00:00:00';
        $to = $date . ' 23:59:59';

        //check if there's any invoice record today
        $checkInvoice = Invoice::/* whereBetween('created_at', [$from, $to])
                                -> */where('unique_code', '!=', 0.00)
                                ->orderBy('created_at', 'desc')
                                //->max('unique_code');
                                ->first();

        //increment unique number
        if($checkInvoice)
        {
            if($checkInvoice->unique_code === floatval(1.00))
            {
                $nextUniqueNumber = floatval(0.01);        
            }
            else
            {
                $nextUniqueNumber = $checkInvoice->unique_code + floatval(0.01);   
            }
        }

        return $nextUniqueNumber;
    }

    public static function createResponseInvoice(Invoice $invoice)
    {
        $data = [];
        if($invoice)
        {

            $payment = $invoice->payments->whereNull('reference_number')->first();
            $invoiceStatus = [
                'id' => $invoice->invoiceStatus->id,
                'name' => $invoice->invoiceStatus->name,
                'description' => $invoice->invoiceStatus->description,
    
            ];
            
            $invoiceItems = $invoice->invoiceItems->map(function ($item)
            {
                $description = $item->description;
                return [
                        'id' => $item->id,
                        'description' => $description,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'amount' => $item->amount,
                ];
            });
    
            $invoiceFees = [];
            if($invoice->unique_code > 0)
            {
                $temp = [
                    'invoice_id' => $invoice->id,
                    'description' => 'Unique Code',
                    'quantity' => null,
                    'unit_price' => null,
                    'amount' => (float) $invoice->unique_code
                ];
    
                array_push($invoiceFees, $temp);
            }

            if($invoice->payment_fee > 0)
            {
    
                $temp = [
                    'invoice_id' => $invoice->id,
                    'description' => 'Payment Fee',
                    'quantity' => null,
                    'unit_price' => null,
                    'amount' =>  (float) $invoice->payment_fee
                ];
    
                array_push($invoiceFees, $temp);
            }

            if($invoice->service_fee > 0)
            {
    
                $temp = [
                    'invoice_id' => $invoice->id,
                    'description' => 'Service Fee',
                    'quantity' => null,
                    'unit_price' => null,
                    'amount' =>  (float) $invoice->service_fee
                ];
    
                array_push($invoiceFees, $temp);
            }

            if($invoice->tax > 0)
            {
    
                $temp = [
                    'invoice_id' => $invoice->id,
                    'description' => 'Tax Fee',
                    'quantity' => null,
                    'unit_price' => null,
                    'amount' =>  (float) $invoice->tax
                ];
    
                array_push($invoiceFees, $temp);
            }

            $data = [
                'id' => $invoice->id,
                'order_number' => $invoice->invoice_number,
                'description' => $invoice->description,
                'amount' => $invoice->amount,
                'discount' => $invoice->discount,
                'final_amount' => $invoice->final_amount, 
                'due_date' => $invoice->due_date ? $invoice->due_date->timestamp:null, 
                'version' => $invoice->version,
                'invoice_fees' => $invoiceFees
            ];
            
            if($data['version'] != null)
            {
                $data['order_number'] = $invoice->invoice_number . '.' . $invoice->version;
            }
            
            $data['invoice_items'] = $invoiceItems;
            $data['invoice_status'] = $invoiceStatus;
            // $data['payment'] = $payment;
        }

        return $data;
    }
}

?>