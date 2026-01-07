<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Member;
use App\Branch;
use App\Student;
use App\Product;
use App\ProductVariant;
use App\ProductPackage;
use App\CateringOrder;
use App\StoreBranch;
use App\CateringOrderDetail;
use App\CateringOrderHistory;
use App\CateringOrderStatus;
use App\CateringShoppingCart;
use App\CateringCustomer;
use App\Payment;
use App\PaymentStatus;
use App\PaymentMethod;

use App\Services\CateringOrderManager;

use Auth;
use DB;
use Hash;
use Carbon\Carbon;

use App\Events\CateringOrder\CateringOrderLocked;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $cateringOrders = CateringOrder::orderBy('created_at', 'DESC');
                                
        $cateringOrderStatuses = CateringOrderStatus::whereIn('id', [STORE_ORDER_STATUS_WAITING_CONFIRMATION,STORE_ORDER_STATUS_COMPLETED,STORE_ORDER_STATUS_CANCELLED])->get();

        $param = null;

        if($request->status_id != "")
        {
            $cateringOrders->where('catering_order_status_id', $request->status_id);
            $param['catering_order_status_id'] = $request->status_id;
        }

        if($request->date)
        {          
            if($request->date == 'today')
            {
                $param['date'] = $request->date;
                $today = Carbon::today()->toDateString();
                $cateringOrders = $cateringOrders->where('date', $today);
            }
            elseif($request->date == 'all')
            {
                $param['date'] = $request->date; 
            }
            elseif($request->date == 'custom')
            {
                $param['date'] = $request->date;
                if($request->startDate == '')
                {
                    return redirect()->back()->withError('Wrong Start Date !!');
                } 
                elseif($request->endDate == '')
                {
                    return redirect()->back()->withError('Wrong End Date !!');
                } 
                $startDate = Carbon::parse($request->startDate);
                $endDate = Carbon::parse($request->endDate);
                $param['startDate'] = $startDate;
                $param['endDate'] = $endDate;

                $cateringOrders = $cateringOrders->whereIn('date', array($startDate->format('Y-m-d'), $endDate->format('Y-m-d')));
            }
        }       
        else
        {
            $param['date'] = 'all';  
        }

        $cateringOrdersTemp = clone $cateringOrders;
         // order summary
        $summary['total'] = $cateringOrdersTemp->count();
        $summary['total_nominal'] = $cateringOrdersTemp->sum('subtotal');

        $cateringOrders = clone $cateringOrdersTemp;
        $summary['total_waiting_confirmation'] = $cateringOrders->where('catering_order_status_id', STORE_ORDER_STATUS_WAITING_CONFIRMATION)->count();
        $summary['total_waiting_confirmation_nominal'] = $cateringOrders->where('catering_order_status_id', STORE_ORDER_STATUS_WAITING_CONFIRMATION)->sum('subtotal');
        
        $cateringOrders = clone $cateringOrdersTemp;
        $summary['total_completed'] = $cateringOrders->where('catering_order_status_id', STORE_ORDER_STATUS_COMPLETED)->count();
        $summary['total_completed_nominal'] = $cateringOrders->where('catering_order_status_id', STORE_ORDER_STATUS_COMPLETED)->sum('subtotal');

        $cateringOrders = clone $cateringOrdersTemp;
        $summary['total_cancelled'] = $cateringOrders->where('catering_order_status_id', STORE_ORDER_STATUS_CANCELLED)->count();
        $summary['total_cancelled_nominal'] = $cateringOrders->where('catering_order_status_id', STORE_ORDER_STATUS_CANCELLED)->sum('subtotal');

        $cateringOrders = clone $cateringOrdersTemp;

        $data['param'] = $param;
        $data['summary'] = $summary;

        $data['cateringOrders'] = $cateringOrders->orderBy('id', 'desc')->get();
        $data['cateringOrderStatuses'] = $cateringOrderStatuses;
        $data['title'] = "Order Transaction";
        $data['request'] = $request;
        $data['user'] = $user;
        $data['date'] = array('all'=>'All Time','today'=>'Today','custom'=>'Custom'); 
        
        return view('order.order_transaction.index', $data);
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        $products = Product::active()->get();

        $productsObject = collect([]);
        $productsTemp = collect([]);
        
        foreach($products as $product)
        {
            $productsTemp = (object) [
                'id' => $product->id,
                'name' => $product->name,
                'type' => 'product'
            ];

            $productsObject->push($productsTemp);
        }

        $productPackages = ProductPackage::active()->get();

        foreach($productPackages as $productPackage)
        {
            $productsTemp = (object) [
                'id' => $productPackage->id,
                'name' => $productPackage->name,
                'type' => 'package'
            ];

            $productsObject->push($productsTemp);
        }

        $data['paymentMethods'] = PaymentMethod::get();
        $data['user'] =  $user;
        $data['products'] = $productsObject;

        $data['title'] = "Create Order"; 

        return view('order.order_transaction.create', $data);
    }

    public function store(Request $request)
    {
        $dataCustomer = [
            'name' => $request->customer_name,
            'address' => $request->customer_address ?? null,
            'phone_number' => $request->customer_phone ?? null,
        ];

        // Set var user to user seller
        $user = auth()->user();

        $shoppingCart = CateringShoppingCart::where('user_id', $user->id)->get();

        $storeOrder = null;

        DB::transaction(function () use ($request, &$user, &$dataCustomer, &$storeOrder, &$shoppingCart)
        {
            $date = Carbon::parse($request->invoice_date);

            $cateringCustomer = CateringCustomer::firstOrCreate($dataCustomer);

            $storeOrder = new CateringOrder();
            $storeOrder->user_id = $user->id;
            $storeOrder->catering_customer_id = $cateringCustomer->id;
            $storeOrder->catering_order_status_id = STORE_ORDER_STATUS_WAITING_CONFIRMATION;
            $storeOrder->save();
            
            $storeOrder->order_number = 'ORDER'.$date->copy()->format('dmY').$storeOrder->id;
            $storeOrder->time_limit = $date->copy()->addHours(24);
            $storeOrder->save();

            // create order details
            $subtotal = 0;
            $subdiscount = 0;

            foreach ($shoppingCart as $key => $cart) 
            {
                $price = 0;
                $productPackage = $cart->productPackage;
                $productVariant = $cart->productVariant;
                $product = $cart->product;

                /* if($key > 1)
                {
                    dd($cart);
                } */

                if($productPackage)
                {
                    $price = $productPackage->price;
                }
                else if($productVariant)
                {
                    $price = $productVariant->price;
                }
                else if($product)
                {
                    $price = $product->price;
                }

                $totalPrice = $price * $cart->quantity;

                // setup data
                $dataOrderDetail = [
                    'catering_order_id' => $storeOrder->id,
                    'catering_product_package_id' => $cart->catering_product_package_id,
                    'catering_product_id' => $cart->catering_product_id,
                    'catering_product_variant_id' => $cart->catering_product_variant_id,
                    'unit_price' => $price,
                    'quantity' => $cart->quantity,
                    'total_price' => $totalPrice,
                ];

                $storeOrderDetail = CateringOrderDetail::create($dataOrderDetail);

                $subtotal += $storeOrderDetail->total_price;
            }

            $storeOrder->subtotal = $subtotal;
            $storeOrder->save();
            
            $storeOrder->payment_method_id = $request->payment_method;
            $storeOrder->description = $request->description != "" ? $request->description : null;
            $storeOrder->date = $date;
            $storeOrder->save();

            // create store order history
            $dataInput = [
                'catering_order_id' => $storeOrder->id,
                'title' => 'Initiate Order',
                'description' => "Create new order, initiated by ".$user->member->name." (".$user->id.").",
                'new_catering_order_status_id' => $storeOrder->catering_order_status_id,
                'updated_by_id' => $user->id
            ];

            CateringOrderHistory::create($dataInput);

            $storeOrderId = $storeOrder->id;
        });

        // trigger event store order locked
        event(new CateringOrderLocked($storeOrder));

        return redirect()->back()->withMessage('success create order');
    }

    public function detail($id)
    {
        $cateringOrder = CateringOrder::find($id);

        $data['cateringOrder'] = $cateringOrder;
        $data['title'] = 'Detail Order ' . $cateringOrder->order_number;

        return view('order.order_transaction.detail', $data);
    }

    public function getStoreProductVariantAjax(Request $request)
    {
        $user = Auth::user();

        $variants = ProductVariant::where('catering_product_id', $request->product_id)->get();

        $variantsCollect = collect([]);
        $variantsTemp = collect([]);

        foreach($variants as $variant)
        {
            $variantsTemp = (object) [
                'id' => $variant->id,
                'name' => $variant->name,
            ];

            $variantsCollect->push($variantsTemp);
        }

        if(!$variants->count())
        {
            $variantsCollect = null;
        }

        return $variantsCollect;
    }

    public function addProductToCartAjax(Request $request)
    {
        $productType = $request->type;

        if($productType == 'product')
        {
            // Find variant product
            if($request->variant_id != null)
            {
                $productVariant = ProductVariant::find($request->variant_id);
            }
            else
            {
                $product = Product::find($request->product_id);

            }
        }
        else if($productType == 'package')
        {
            $productPackage = ProductPackage::find($request->product_id);
        }

        // If the product is exist will create data on shopping cart
        if(isset($productPackage) || isset($product) || isset($productVariant))
        {
            $storeShoppingCart = CateringShoppingCart::where('user_id', $request->user_id);

            if(isset($productPackage))
            {
                $storeShoppingCart = $storeShoppingCart->where('catering_product_package_id', $productPackage->id);
            }
            else if(isset($productVariant))
            {
                $storeShoppingCart = $storeShoppingCart->where('catering_product_variant_id', $productVariant->id);
            }
            else if(isset($product))
            {
                $storeShoppingCart = $storeShoppingCart->where('catering_product_id', $product->id);
            }
            
            $storeShoppingCart = $storeShoppingCart->first();

            // If not exist will create new
            if(!$storeShoppingCart)
            {
                $storeShoppingCart = new CateringShoppingCart();
                
                if(isset($productPackage))
                {
                    $storeShoppingCart->catering_product_package_id = $productPackage->id;
                }
                else if(isset($product))
                {
                    $storeShoppingCart->catering_product_id = $product->id;
                }
                else if(isset($productVariant))
                {
                    $storeShoppingCart->catering_product_variant_id = $productVariant->id;
                }
                
                $storeShoppingCart->user_id = $request->user_id;
                $storeShoppingCart->quantity = $request->qty;
                $storeShoppingCart->save();
            }
            else // if exist will update the quantity
            {
                $storeShoppingCart->quantity += $request->qty;
                $storeShoppingCart->save();
            }

            return $storeShoppingCart;
        }
        else // If the product article not found will return null and didnt insert to cart
        {
            return $storeShoppingCart = null;
        }
    }

    // Get shopping cart for user id seller
    public function getStoreShoppingCartAjax(Request $request)
    {
        $user = User::find($request->user_id);

        // Find shopping cart based on user id seller
        $storeShoppingCart = CateringShoppingCart::with('product', 'productVariant', 'productPackage')->where('user_id', $user->id)->get();

        // Modify data purpose for needed data on frontend
        $storeShoppingCart = $storeShoppingCart->map(function ($cart) {
            $variant = null;
            $packageId = null;
            $productId = null;
            $variantId = null;

            $discountsTemp = [];

            $type = 'product';
            
            if($cart->catering_product_package_id)
            {
                $packageId = $cart->catering_product_package_id;
                $type = 'package';
                $name = $cart->productPackage->name;
                $price = $cart->productPackage->price;
            }

            if($cart->catering_product_id)
            {
                $productId = $cart->catering_product_id;
                $name = $cart->product->name;
                $price = $cart->product->price;
            }

            if($cart->catering_product_variant_id)
            {
                $variantId = $cart->catering_product_variant_id;
                $name = $cart->productVariant->product->name . ' - ' . $cart->productVariant->name;
                $price = $cart->productVariant->price;
            }

            $tempCart = [
                    'id' => $cart->id,
                    'package_id' => $packageId,
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'product_name' => $name,
                    'product_price' => $price,
                    'product_type' => $type,
                    'qty' => $cart->quantity,
                ];

            return $tempCart;
        });

        return $storeShoppingCart;
    }

    public function removeItemShoppingCartAjax(Request $request)
    {
        $itemCart = CateringShoppingCart::find($request->id);

        if($itemCart)
        {
            $itemCart->delete();

            return true;
        }

        return false;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPayments(Request $request)
    {
        //check if the user has the authority to view payment list
        $this->authorize('merchandisePayment', Payment::class);

        $payments = Payment::with('paymentStatus')
                            ->whereHas('invoice')
                            ->where('payment_category_id', PAYMENT_CATEGORY_DBL_ACADEMY_MERCHANDISE);

        $user = auth()->user();

        if($user->isAdmin() || $user->isFinance() || $user->id == 17008) 
        {
            $branches = Branch::active()->get();
        } 
        else 
        {
            $storeSeller = StoreSeller::with('storeBranches')->first();

            $storeBranchIds = $storeSeller->users()->where('user_id', $user->id)->get()->pluck('pivot.store_branch_id');

            $branches = Branch::active()->whereIn('store_branch_id', $storeBranchIds)->get();
        }

        $storeBranchesIds = $branches->pluck('store_branch_id');

        if($request->branch_id)
        {
            $param['branch_id'] = $request->branch_id;   
            
            $storeBranchId = Branch::find($request->branch_id)->store_branch_id;
            $payments = $payments->whereHas('invoice.storeOrder', function($query) use($storeBranchId){
                $query->where('store_branch_id', $storeBranchId);
            });
        }
        else
        {
            $payments = $payments->whereHas('invoice.storeOrder', function($query) use($storeBranchesIds){
                $query->whereIn('store_branch_id', $storeBranchesIds);
            });
        }

        if($request->payment_status_id)
        {
            $param['payment_status_id'] = $request->payment_status_id;          
            $payments = $payments->where('payment_status_id', $param['payment_status_id']);
        }   

        if($request->payment_type_id)
        {
            $param['payment_type_id'] = $request->payment_type_id;          
            $payments = $payments->where('payment_type_id', $param['payment_type_id']);
        }   

        if($request->date)
        {          
            if($request->date == 'today')
            {
                $param['date'] = $request->date;
                $payments = $payments->whereDate('date', '=', Carbon::today()->toDateString());    
            }
            elseif($request->date == 'all')
            {
                $param['date'] = $request->date; 
            }
            elseif($request->date == 'custom')
            {
                $param['date'] = $request->date;
                if($request->startDate == '')
                {
                    return redirect()->back()->withMessage('Wrong Start Date !!');
                } 
                elseif($request->endDate == '')
                {
                    return redirect()->back()->withMessage('Wrong End Date !!');
                } 
                $startDate = Carbon::createFromFormat('d-m-Y', $request->startDate);
                $endDate = Carbon::createFromFormat('d-m-Y', $request->endDate);
                $param['startDate'] = $startDate;
                $param['endDate'] = $endDate;

                $payments = $payments->whereBetween('date', array($startDate->toDateString(), $endDate->toDateString())); 
            }            
        }       
        else
        {
            $param['date'] = 'all';  
        }

        $paymentsTemp =clone $payments;
        // payment status summary
        $summary['total'] = $payments->count();
        $payments = clone $paymentsTemp;
        $summary['pendingConfirmation'] = $payments->pendingConfirmation()->count();
        $summary['pendingConfirmationAmount'] = $payments->sum('amount');        
        $payments = clone $paymentsTemp;
        $summary['confirmed'] = $payments->confirmed()->count();
        $summary['confirmedAmount'] = $payments->sum('confirmed_amount');        

        $payments = clone $paymentsTemp;
        $summary['failed'] = $payments->failed()->count();
        $summary['failedAmount'] = $payments->sum('amount');  

        $summary['totalAmount'] = $payments->sum('confirmed_amount'); 

        $data['param'] = $param;
        $data['summary'] = $summary;
        $data['branches'] = $branches;
        $data['payment_statuses'] = PaymentStatus::all()->pluck('name', 'id');
        $data['payment_types'] = PaymentType::all()->pluck('name', 'id');
        $data['date'] = array('all'=>'All Time','today'=>'Today','custom'=>'Custom');
        $data['payments'] = $paymentsTemp->orderBy('created_at', 'ASC')->get();
        $data['title'] = "Payment List";
        $data['user'] = $user;

        return view('merchandise.payment.index', $data);
    }

    public function viewPayment($id)
    {
        $payment = Payment::with('invoice', 'student', 'paymentHistories')->findOrFail($id);

        //compress and send the data to view
        $data['payment'] = $payment;
        
        $data['title'] = 'Payment Detail';

        return view('merchandise.payment.view',$data);
    }

    /**
     * Display a listing of the resource through ajax.
     *
     * @return \Illuminate\Http\Response
     */
    /* public function indexPaymentsAjax(Request $request)
    {
        //check if the user has the authority to view payment list
        $this->authorize('list', Payment::class);

        $payments = Payment::with('paymentType', 'paymentStatus', 'invoice')
                            ->whereHas('invoice')
                            ->where('payment_category_id', PAYMENT_CATEGORY_DBL_ACADEMY_MERCHANDISE);

        if($request->branch_id)
        {         
            $payments = $payments->whereHas('student.branch', function($query) use($request){
                $query->where('id', $request->branch_id);
            });
        }          

        if($request->payment_status_id)
        {         
            $payments = $payments->where('payment_status_id', $request->payment_status_id);
        }      

        if($request->payment_type_id)
        {         
            $payments = $payments->where('payment_type_id', $request->payment_type_id);
        }  

        if($request->date)
        {          
            if($request->date == 'today')
            {
                $payments = $payments->whereDate('date', '=', Carbon::today()->toDateString());    
            }
            elseif($request->date == 'all')
            {
                //getting all, no specific date query
            }
            elseif($request->date == 'custom')
            {
                $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->startDate)->toDateString();
                $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->endDate)->toDateString();
                $payments = $payments->whereBetween('date', array($startDate, $endDate))->orderBy('date');    
            }
            
        }       
        else
        {
           //getting all, no specific date query
        }

        return Datatables::eloquent($payments)
                        // ->setTotalRecords($request->total)
                        ->make(true) ;
    } */

    public function print($id)
    {
        \App::setLocale('id');

        $storeOrder = StoreOrder::find($id);

        $invoice = $storeOrder->invoice;

        if($invoice && ($invoice->invoice_status_id == INVOICE_STATUS_PAID || $invoice->invoice_status_id == INVOICE_STATUS_PENDING_CONFIRMATION))
        {
            //increment receipt_print_number
            $invoice->receipt_print_number = $invoice->receipt_print_number + 1;
            $invoice->save();
            
            $invoiceItems =  $invoice->invoiceItems;

            $data['student'] = $invoice->student;
            $data['invoice'] = $invoice;
            $data['payments'] = $invoice->payments;
            $data['printedBy'] = Auth::user()->member->name;
        }
        else
        {
            return redirect()->route('merchandise-transaction')->withMessage('Cannot print receipt for this invoice');
        }

        return view('merchandise.order_transaction.receipt', $data);
    }

    public function sendMail($id, Request $request)
    {
        // return true;

        $invoice = Invoice::with('storeOrder')->find($id);

        //increment receipt_print_number
        $invoice->receipt_print_number = $invoice->receipt_print_number + 1;
        $invoice->save();

        $urlInvoicePdf = self::generateInvoicePdf($invoice);

        if($urlInvoicePdf)
        {
            $invoice->file_path = $urlInvoicePdf;

            // dd($invoice->file_path);

            sleep(1);
	    
            Mail::to($request->email)->send(new InvoiceEmail($invoice));

            return true;
        }
        else
        {
            return false;
        }
    }

    public function generateInvoicePdf($invoice)
    {        
        $invoiceItems =  $invoice->invoiceItems;

        $invoice = $invoice;
        $payments = $invoice->payments;
        $printedBy = Auth::user()->member->name;

        $contxt = stream_context_create([
            'ssl' => [
            'verify_peer' => FALSE,
            'verify_peer_name' => FALSE,
            'allow_self_signed'=> TRUE,
            ]
        ]);

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'isFontSubsettingEnabled' => false, 'isJavascriptEnabled' => true]);

        $pdf->getDomPDF()->setHttpContext($contxt);
                
        $pdf->loadview('pdf.merchandise-invoice', [
                                            'invoice' => $invoice,
                                            'payments' => $payments,
                                            'printedBy' => $printedBy,
                                        ]);

        $pdf->setOption('javascript-delay', 5000);

        $pdf->setPaper('a4','portrait');

        $folderPath = 'uploads/merchandise-invoice/user-' . $invoice->user_id . '/';

        $fileName = $invoice->number . "-" . Carbon::now()->format('ymdHis') . ".pdf";

        // create the directory if its not there, this is a must since intervention did not create the directory automatically
        File::exists($folderPath) or File::makeDirectory($folderPath, 0755, true);
        $filePath =  $folderPath . $fileName;

        File::delete(public_path() . '/' . $filePath);

        $pdf->save($filePath);

        return $filePath;
    }

    public function orderCancel($id)
    {
        $userCreator = auth()->user();

        $storeOrder = CateringOrder::find($id);

        $remark = 'Cancel Order ' . $storeOrder->order_number;

        try
        {
            $cancelOrder = CateringOrderManager::updateOrderStatus($storeOrder, STORE_ORDER_STATUS_CANCELLED);

            return redirect()->back()->withMessage('Success! Order cancelled');
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors('Something went wrong, cancel order failed. Please try again later');
        }
    }

    public function orderComplete($id)
    {
        $userCreator = auth()->user();

        $storeOrder = CateringOrder::find($id);

        $remark = 'Complete Order ' . $storeOrder->order_number;

        try
        {
            $completeOrder = CateringOrderManager::updateOrderStatus($storeOrder, STORE_ORDER_STATUS_COMPLETED);
            
            return redirect()->back()->withMessage('Success! Order Completed');            
        }
        catch(\Exception $e)
        {
            return redirect()->back()->withErrors('Something went wrong, cancel order failed. Please try again later');
        }
    }

    public function orderDelete($id)
    {
        $userCreator = auth()->user();

        $storeOrder = CateringOrder::find($id);

        try
        {
            $storeOrder->cateringOrderHistories()->delete();
            $storeOrder->cateringOrderDetails()->delete();
            $storeOrder->delete();

            return redirect()->route('catering-order-list')->withMessage('Success! Order Deleted');
        }
        catch(\Exception $e)
        {
            dd($e);
            return redirect()->back()->withErrors('Something went wrong, cancel order failed. Please try again later');
        }
    }

    public function downloadExcel(Request $request)
    {
        //check if the user has the authority to store payment
        // $this->authorize('downloadExcel', Payment::class);
        $payments = Payment::with('invoice.invoiceItems', 'invoice.storeOrder', 'paymentType', 'paymentConfirmed')
                            ->where('payment_category_id', PAYMENT_CATEGORY_DBL_ACADEMY_MERCHANDISE)
                            ->orderBy('date', 'asc');

        if($request->branch_id)
        {
            $branch = Branch::find($request->branch_id);

            $payments = $payments->whereHas('invoice.storeOrder.storeBranch', function($query) use($branch){
                $query->where('id', $branch->store_branch_id);
            });
        }              
           
        if($request->payment_status_id)
        {
            $payments = $payments->where('payment_status_id', $request->payment_status_id);
        }   

        if($request->payment_type_id)
        {
            $payments = $payments->where('payment_type_id', $request->payment_type_id);
        }          
        
        //if there's custom date request, then get payment by custom date
        $datePeriod = 'all';
        if(isset($request->date))
        {
            if($request->date == 'today')
            {
                $datePeriod = 'today';
                $date = Carbon::today()->format('Y-m-d');
                $payments = $payments->where('date', $date);
            }
            
            if($request->date == 'custom')
            {
                $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->startDate)->toDateString();
                $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $request->endDate)->toDateString();
                $payments = $payments->whereBetween('date', array($startDate, $endDate));
                $datePeriod = [
                    'startDate' => $startDate,
                    'endDate' => $endDate
                ];
            }
        }

        $payments = $payments->where('amount', '>', 0)->get();

        //get payment type that exist in payment list
        $paymentTypeList = $payments->pluck('payment_type_id');

        $date = Carbon::now();
        $filename = 'export-merchandise-payments-'.$date.'.xlsx';
        $data = ['payments' => $payments, 'dateType' => $datePeriod];
        return Excel::download(new MerchandisePaymentExport($data), $filename);
    }

    public function indexSalesReport(Request $request)
    {
        $startDate = $request->startDate ? Carbon::parse($request->startDate)->format('Y-m-d') : Carbon::today()->startOfMonth()->format('Y-m-d');
        $endDate   = $request->endDate ? Carbon::parse($request->endDate)->format('Y-m-d') : Carbon::today()->endOfMonth()->format('Y-m-d');
        $productType = $request->product_type;
        $productId = $request->product_id;

        $productsObject = collect([]);
        $productsTemp = collect([]);

        $products = Product::active()->whereDoesntHave('productVariants')->get();

        foreach($products as $product)
        {
            $productsTemp = (object) [
                'id' => $product->id,
                'name' => $product->name,
                'type' => 'product'
            ];

            $productsObject->push($productsTemp);
        }
        
        $productsVariants = ProductVariant::whereHas('product', function ($query) {
                                    $query->active();
                                })
                                ->get();

        foreach($productsVariants as $productVariant)
        {
            $productsTemp = (object) [
                'id' => $productVariant->id,
                'name' => $productVariant->product->name . ' - ' . $productVariant->name,
                'type' => 'variant'
            ];

            $productsObject->push($productsTemp);
        }

        $productPackages = ProductPackage::active()->get();

        foreach($productPackages as $productPackage)
        {
            $productsTemp = (object) [
                'id' => $productPackage->id,
                'name' => $productPackage->name,
                'type' => 'package'
            ];

            $productsObject->push($productsTemp);
        }

        $sales = CateringOrderDetail::query()
            ->selectRaw('catering_orders.date as date, SUM(catering_order_details.quantity) as total')
            ->join('catering_orders', 'catering_orders.id', '=', 'catering_order_details.catering_order_id')
            ->when($startDate, fn ($q) => $q->whereDate('catering_orders.date', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('catering_orders.date', '<=', $endDate))
            ->when($productType !== 'all' && $productId, function ($q) use ($productType, $productId) {
                match ($productType) {
                    'package' => $q->where('catering_order_details.catering_product_package_id', $productId),
                    'product' => $q->where('catering_order_details.catering_product_id', $productId),
                    'variant' => $q->where('catering_order_details.catering_product_variant_id', $productId),
                };
            })
            ->groupBy('catering_orders.date')
            ->orderBy('catering_orders.date')
            ->get();

        // dd($sales);

        $recap = CateringOrderDetail::query()
            ->selectRaw('
                SUM(catering_order_details.quantity) as total,
                SUM(catering_order_details.total_price) as total_nominal,
                catering_order_details.catering_product_package_id,
                catering_order_details.catering_product_id,
                catering_order_details.catering_product_variant_id
            ')
            ->join('catering_orders', 'catering_orders.id', '=', 'catering_order_details.catering_order_id')

            // filter tanggal
            ->when($startDate, fn ($q) => $q->whereDate('catering_orders.date', '>=', $startDate))
            ->when($endDate, fn ($q) => $q->whereDate('catering_orders.date', '<=', $endDate))

            // filter product (konsisten)
            ->when($productType !== 'all' && $productId, function ($q) use ($productType, $productId) {
                match ($productType) {
                    'package' => $q->where('catering_order_details.catering_product_package_id', $productId),
                    'product' => $q->where('catering_order_details.catering_product_id', $productId),
                    'variant' => $q->where('catering_order_details.catering_product_variant_id', $productId),
                };
            })

            // group by SEMUA kolom product
            ->groupBy(
                'catering_order_details.catering_product_package_id',
                'catering_order_details.catering_product_id',
                'catering_order_details.catering_product_variant_id'
            )
            ->orderByDesc('total')
            ->get();

        $recapData = $recap->map(function ($row) {
            if ($row->catering_product_package_id) {
                return [
                    'type'  => 'Package',
                    'name'  => optional($row->productPackage)->name ?? 'Package #' . $row->catering_product_package_id,
                    'total' => $row->total,
                    'total_nominal'=> $row->total_nominal,
                ];
            }

            if ($row->catering_product_id) {
                return [
                    'type'  => 'Product',
                    'name'  => optional($row->product)->name ?? 'Product #' . $row->catering_product_id,
                    'total' => $row->total,
                    'total_nominal'=> $row->total_nominal,
                ];
            }

            $productName = optional($row->productVariant->product)->name ?? 'Product #' . $row->catering_product_id;
            $variantName = optional($row->productVariant)->name ?? 'Variant #' . $row->catering_product_variant_id;
            
            return [
                'type'  => 'Variant',
                'name'  => $productName . ' - ' . $variantName,
                'total' => $row->total,
                'total_nominal'=> $row->total_nominal,
            ];
        });

        return view('order.report.index', [
            'labels' => $sales->pluck('date'),
            'data'   => $sales->pluck('total'),
            'filters'=> $request->all(),
            'products' => $productsObject,
            'recapData' => $recapData,
            'title' => 'Sales Report',
        ]);
    }
}
