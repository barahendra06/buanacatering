@extends('layouts.app')

@section('htmlheader_title')
    {{ $title ?? '' }}
@endsection

@section('contentheader_title')
    {{ $title ?? '' }}
@endsection

@section('contentheader_description')
    {{ $title_description ?? '' }}
@endsection

@push('content-header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />

<style>
    td{
        height: 50px;
        vertical-align: middle!important;
    }
</style>
@endpush

@section('main-content')
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    {{$summary['total']}}
                </h3>
                <p>
                    <span style="font-size:15px;"><b>(Rp {{ number_format($summary['total_nominal'],0,',','.') }})</b></span>
                    <br>
                    Total Order
                </p>
            </div>
            <div class="icon">
                {{-- <i class="fa fa-bell"></i> --}}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    {{$summary['total_completed']}}                   
                </h3>
                <p>
                    <span style="font-size:15px;"><b>(Rp {{ number_format($summary['total_completed_nominal'],0,',','.') }})</b></span>
                    <br>
                    Total Order Completed
                </p>
            </div>
            <div class="icon">
                {{-- <i class="fa fa-dollar"></i> --}}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-blue">
            <div class="inner">
                <h3>
                    {{$summary['total_waiting_confirmation']}}                   
                </h3>
                <p>
                    <span style="font-size:15px;"><b>(Rp {{ number_format($summary['total_waiting_confirmation_nominal'],0,',','.') }})</b></span>
                    <br>
                    Total Order Waiting Confirmation
                </p>
            </div>
            <div class="icon">
                {{-- <i class="fa fa-bar-chart"></i> --}}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3>
                    {{$summary['total_cancelled']}}                   
                </h3>
                <p>
                    <span style="font-size:15px;"><b>(Rp {{ number_format($summary['total_cancelled_nominal'],0,',','.') }})</b></span>
                    <br>
                    Total Order Cancelled
                </p>
            </div>
            <div class="icon">
                {{-- <i class="fa fa-percent"></i> --}}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="box-title">Filter</h3>
                        <form id="myForm" role="form" method="GET" enctype="multipart/form-data" action="{{ route('catering-order-list') }}">
                            <div class="row">
                                {{-- <div class="col-md-3 m-t-10">
                                    <label>Product Type</label>
                                    <select name="product_type" id="product_type" class="form-control">
                                        <option value="">All</option>
                                        <option value="product" {{ old('product_type', $request->product_type) == 'product' ? 'selected' : '' }}>Product</option>
                                        <option value="package" {{ old('product_type', $request->product_type) == 'package' ? 'selected' : '' }}>Package</option>
                                    </select>
                                </div> --}}
                                <div class="col-md-3 m-t-10">
                                    <label>Order Status</label>
                                    <select name="status_id" id="status_id" class="form-control">
                                        <option value="">All</option>
                                        @foreach($cateringOrderStatuses as $status)
                                            <option value="{{ $status->id }}" @if(old('status_id', $request->status_id) == $status->id) {{ 'selected' }} @endif>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3  m-t-10">
                                    <label for="">Date</label>
                                    @if(isset($param['date']))
                                        {!! Form::select('date', $date, $param['date'], [
                                            'id' => 'joinDate',
                                            'class' => 'form-control'
                                        ]) !!}
                                    @elseif(isset($param['custom']))
                                        {!! Form::select('date', $date, $param['date'], [
                                            'id' => 'joinDate',
                                            'class' => 'form-control'
                                        ]) !!}
                                    @else
                                        {!! Form::select('date', $date, null, [
                                            'id' => 'joinDate',
                                            'class' => 'form-control'
                                        ]) !!}
                                    @endif
                                    <div id="startDate" class="input-group width-initial margin-top-5">
                                        <div class="input-group-addon width-initial">
                                            Start
                                        </div>
                                        <input class="form-control datepicker" placeholder="Click here..." name="startDate" required 
                                                value="@if(isset($param['startDate'])){{ $param['startDate']->format('d-m-Y') }}@else{{\Carbon\Carbon::now()->format('d-m-Y')}}@endif" />
                                    </div>
                                    <div id="endDate" class="input-group width-initial margin-top-5">
                                        <div class="input-group-addon width-initial">
                                            End&nbsp;&nbsp;
                                        </div>
                                        <input class="form-control datepicker" placeholder="Click here..." name="endDate" required 
                                                value="@if(isset($param['endDate'])){{ $param['endDate']->format('d-m-Y') }}@else{{\Carbon\Carbon::now()->format('d-m-Y')}}@endif" />
                                    </div>		
                                </div>
                                <div class="col-md-12 text-center" style="margin-top: 10px;">
                                    <button type="submit" class="btn btn-primary"><span class="fa fa-search"></span> Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>	
</div>
<div class="row">
    {{-- <div class="col-md-12 text-right">
        <a class="btn btn-success btn-sm" href="{{ route('change-classroom-create') }}">Create Request Classroom Replacement</a>
    </div> --}}
    <div class="col-md-12" style="margin-top: 10px">
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="order-table" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Order Date</th>
                                    <th class="text-center">Order Number</th>
                                    <th class="text-center">Customer</th>
                                    {{-- <th class="text-center">Receipt Number</th> --}}
                                    <th class="text-center">Sub Total</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cateringOrders as $order)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($order->date)->format('d-m-Y') }}</td>
                                        <td class="text-center">{{ $order->order_number }}</td>
                                        <td class="text-center">
                                            {{-- <a href="" target="_blank"> --}}
                                                {{ $order->cateringCustomer->name ?? '-' }}
                                            {{-- </a> --}}
                                        </td>
                                        {{-- <td class="text-center">{{ $order->receipt_number ?? '-' }}</td> --}}
                                        <td class="text-center">{{ $order->subtotal_rupiah ?? '0' }}</td>
                                        <td class="text-center"> {{ $order->cateringOrderStatus->name }} </td>
                                        <td class="text-center">
                                            <a class="btn btn-primary btn-xs" href="{{ route('catering-order-detail', $order->id) }}">Detail</a>
                                            @if($order->catering_order_status_id == STORE_ORDER_STATUS_WAITING_CONFIRMATION)
                                                <br><a class="btn btn-danger btn-xs" id="cancel-order" href="{{ route('catering-cancel-order', $order->id) }}" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel Order</a>
                                                <br><a class="btn btn-success btn-xs" id="complete-order" href="{{ route('catering-complete-order', $order->id) }}" onclick="return confirm('Are you sure you want to complete this order?');">Complete Order</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
    </div>
</div>
@endsection

@section('content-script')
    <script>
        $('#order-table').DataTable();

        if($("#joinDate").val()!='custom')
        {
            $('#startDate').hide();
            $('#endDate').hide();	
            $('#startDate').attr("disabled", true);
            $('#endDate').attr("disabled", true);
        }

        $('#joinDate').change(function()
        {
            if($(this).val() == 'custom')
            {
                $('#startDate').show('slow');
                $('#endDate').show('slow');	
                $('#startDate').removeAttr("disabled");	
                $('#endDate').removeAttr("disabled");	
            }
            else
            {
                $('#startDate').hide('slow');
                $('#endDate').hide('slow');	
                $('#startDate').attr("disabled", true);
                $('#endDate').attr("disabled", true);	
            }
        });

        $('.datepicker').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
        });

        $('input.datepicker').bind('keyup keydown keypress', function (evt) {
            return false;
        });
    </script>
@endsection