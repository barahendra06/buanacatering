@extends('layouts.app')

@section('htmlheader_title')
    {{ $title ?? '' }}
@endsection

@section('contentheader_title')
    {{  $title  ?? '' }}
@endsection

@section('contentheader_description')
    {{ $title_description ?? '' }}
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered">
                                    <tbody>
                                        <tr>
                                            <td width="15%">Customer Name</td>
                                            <td>:</td>
                                            <td width="35%">{{ $cateringOrder->cateringCustomer->name }}</td>
                                            <td width="15%">Date</td>
                                            <td>:</td>
                                            <td width="35%">{{ $cateringOrder->created_at }}</td>
                                        </tr>
                                        <tr>
                                            <td width="15%">Customer Phone</td>
                                            <td>:</td>
                                            <td width="35%">{{ $cateringOrder->cateringCustomer->phone_number }}</td>
                                            
                                        </tr>
                                        <tr>
                                            <td width="15%">Customer Address</td>
                                            <td>:</td>
                                            <td width="35%">{{ $cateringOrder->cateringCustomer->address }}</td>
                                            <td width="15%">Order Number</td>
                                            <td>:</td>
                                            <td width="35%">{{ $cateringOrder->order_number }}</td>
                                        </tr>
                                        <tr>
                                            <td width="15%">Final Amount</td>
                                            <td>:</td>
                                            <td width="35%">{{ $cateringOrder->subtotal_rupiah }}</td>
                                            <td width="15%">
                                                <a class="btn btn-danger btn-xs" id="delete-order" href="{{ route('catering-delete-order', $cateringOrder->id) }}" onclick="return confirm('Are you sure you want to delete this order?');">Delete Order</a>
                                            </td>
                                            <td></td>
                                            <td width="35%">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="15%">Status</td>
                                            <td>:</td>
                                            <td width="35%">{{ $cateringOrder->cateringOrderStatus->name }}</td>
                                        </tr>
                                        
                                        <tr></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12 m-t-5">
                                {{-- @if(0 && $invoice->isNotPaid() && !$invoice->isCancelled()) 
                                    @can('pay', $invoice)
                                        <a href="{{ route('invoice-pay', $invoice->id) }}" class="btn btn-xs btn-success btn-flat">
                                            <i class="fa fa-dollar"></i> Mark as Paid
                                        </a> 
                                    @endcan
                                @endif
                                @if($invoice->isNotCancelled()) 
                                    @can('cancel', $invoice)                                            
                                    <a href="#" class="btn btn-xs btn-danger btn-flat" data-toggle="modal" data-target="#cancelModal" id="btn-cancel-payment">
                                        <i class="fa fa-times"></i> Cancel Invoice
                                    </a> 
                                    @endcan
                                @endif
                                @if(0 && $invoice->isCancelled()) <!-- only can open when cancelled -->
                                    @can('open', $invoice)
                                        <a href="{{ route('invoice-open', $invoice->id) }}" class="btn btn-primary btn-success btn-flat">
                                            <i class="fa fa-dollar"></i> Re-Open Invoice
                                        </a> 
                                    @endcan
                                @endif --}}

                               {{--  @if($invoice->isPaid() || $invoice->isPending())
                                    <a href="{{ route('invoice-receipt', ['id' => $invoice->id]) }}" class="btn btn-success btn-xs btn-flat"><i class="fa fa-print"></i> Print Receipt</a>
                                @endif --}}
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Order Details</h3>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center" width=30%>Product</th>
                                            <th class="text-center">Total Weight</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Unit Price</th>
                                            <th class="text-center">Total Price <br> (Unit Price x Quantity)</th>
                                            <th class="text-center">Discount</th>
                                            <th class="text-center">Final Price <br> (Total Price - Discount)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cateringOrder->cateringOrderDetails as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ $item->ordered_product_name }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->total_weight }} gr
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->quantity }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->unit_price_rupiah }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->bruto_price_rupiah }}
                                                </td>
                                                <td class="text-center">
                                                    {{ $item->discount_rupiah }}
                                                </td>
                                                <td class="text-right">{{ $item->total_price_rupiah }}</td>
                                            </tr>                                    
                                        @endforeach                                 
                                            <tr>
                                                <td class="text-right" colspan="7"><strong>Final Amount</strong></td>
                                                <td class="text-right"><strong>{{ $cateringOrder->subtotal_rupiah ?? '-' }}</strong></td>
                                            </tr>                                    
                                    </tbody>
                                </table>                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Transaction Order History</h3>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered" id="payment-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Description</th>
                                            <th class="text-center">Old Status</th>
                                            <th class="text-center">New Status</th>
                                            <th class="text-center">Creator</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cateringOrder->cateringOrderHistories as $cateringOrderHistory)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $cateringOrderHistory->created_at ?? '-' }}</td>
                                                <td class="text-center">{{ $cateringOrderHistory->description ?? '-' }}</td>
                                                <td class="text-center">{{ $cateringOrderHistory->oldcateringOrderStatus->name ?? '-' }}</td>
                                                <td class="text-center">{{ $cateringOrderHistory->newcateringOrderStatus->name ?? '-' }}</td>
                                                <td class="text-center">{{ $cateringOrderHistory->updatedBy->member->name ?? 'System' }}</td>
                                            </tr>                                    
                                        @endforeach
                                    </tbody>
                                </table>                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content-script')

@endsection
