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
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<style>
    .ml-2{
        margin-left: 0.5rem;
    }
</style>
@endpush

@section('main-content')
<div class="row">
    <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    @can('create', App\Product::class)
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a class="btn btn-sm btn-success" href="{{ route('product-create') }}">Create Product</a>
                        </div>
                    </div>
                    @endcan
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="myTable" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center" width=40%>Description</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $product->productCategory->name }}</td>
                                        <td class="" data-id="{{ $product->id }}">{{ $product->name }}</td>
                                        <td class="">{{ $product->description }}</td>
                                        <td class="text-center">
                                            @if(!$product->productVariants->count())
                                                {{ $product->product_price }}
                                            @else
                                                {{ $product->productVariants->sortBy('price')->first()->product_price }} - {{ $product->productVariants->sortByDesc('price')->first()->product_price }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-warning btn-sm" href="{{ route('product-edit', $product->id) }}">Manage</a>
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
<div class="modal fade" tabindex="-1" role="dialog" id="spinnerModal">
    <div class="modal-dialog modal-dialog-centered text-center" style="top: 50%;" role="document">
        <span class="fa fa-spinner fa-spin fa-5x"></span>
    </div>
</div>
@endsection

@section('content-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

    <script>
        $('#myTable').DataTable();
    </script>
@endsection