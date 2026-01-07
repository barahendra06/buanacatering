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

@push('content-header')

@endpush

@section('main-content')
{{-- <div class="row">
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
                <i class="fa fa-dollar"></i>
            </div>
        </div>
    </div>
</div> --}}
<div class="container-fluid">
    <div class="row">
        <div class="panel panel-default">	
            <div class="panel-heading"><strong>Filter</strong></div>
            <div class="panel-body">	
                <form id="myForm" class="form-horizontal" role="form" method="GET" enctype="multipart/form-data" action="{{ route('catering-sales-report') }}">
                    <div class="row">
                        <div class="col-md-3 m-t-10">
                            <label for="">Product</label>
                            <select class="form-control" name="product_id" id="product_id">
                                <option value="">All</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-type="{{ $product->type }}" @if(isset($param['product_id']) && $param['product_id'] == $product->id) selected @endif>{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="product_type" value="{{ isset($param['product_type']) ? $param['product_type'] : '' }}">
                        </div>
                        <div class="col-md-3 m-t-10">
                            <label for="">Start Date</label>
                            <input class="form-control datepicker" placeholder="Click here..." name="startDate" required 
                                value="@if(isset($filters['startDate'])){{ $filters['startDate'] }}@else{{\Carbon\Carbon::now()->startOfMonth()->format('Y-m-d')}}@endif" />
                        </div>
                        <div class="col-md-3 m-t-10">
                            <label for="">End Date</label>
                            <input class="form-control datepicker" placeholder="Click here..." name="endDate" required 
                                value="@if(isset($filters['endDate'])){{ $filters['endDate'] }}@else{{\Carbon\Carbon::now()->endOfMonth()->format('Y-m-d')}}@endif" />
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button onclick="loadChart()">Filter</button>
                        </div>
                    </div>
                </form>
            </div>	
        </div>	
    </div>
    <div class="row">
        <div class="panel panel-default">	
            <div class="panel-heading">
                
            </div>
            <div class="panel-body">
                @if(count($labels))
                <canvas id="salesChart"></canvas>
                @endif
                <br>
                @if(count($recapData))
                <h3>Sales Recap</h3>
                <div class="table-responsive">
                    <table class="table" id="table">
                        <thead>
                            <tr>
                                <th>Product Type</th>
                                <th>Product Name</th>
                                <th>Sales Total Quantity</th>
                                <th>Sales Total (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recapData as $row)
                            <tr>
                                <td>{{ $row['type'] }}</td>
                                <td>{{ $row['name'] }}</td>
                                <td>{{ $row['total'] }}</td>
                                <td>Rp. {{ number_format($row['total_nominal'],0,',','.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>			
    </div>	
</div>
@endsection

@section('content-script')
    {{-- <script src="{{ secure_asset('plugins/chartjs/Chart.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $('#table').DataTable();

        $('.datepicker').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
        });

        $('#product_id').change(function() {
            var selectedOption = $(this).find('option:selected');
            var productType = selectedOption.data('type');
            $('input[name="product_type"]').val(productType);
        });
    </script>
    <script>
        @if(count($labels))
            new Chart(document.getElementById('salesChart'), {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Total Product Sold',
                        data: @json($data),
                        tension: 0.3
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        @endif
    </script>
@endsection