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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" />

    <style type="text/css">
        td {
            vertical-align : middle!important;
            /* text-align:center!important; */
        }

        .items{
            width: 70px!important;
        }

        .table-bordered td, .table-bordered th{
            border-color: black !important;
        }
    </style>
    @endpush

    @section('main-content')
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <a class="btn btn-warning btn-sm" href="{{ route('product-category-index') }}">Back to list</a>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="myForm" role="form" method="POST" enctype="multipart/form-data" action="{{ route('product-category-update', $productCategories->id) }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                
                                                <div class="form-group">
                                                    <label>Product Category Name <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-file"></i>
                                                        </div>
                                                        <input class="form-control" type="text" name="product_category_name" placeholder="ex: Main Course" value="{{ $productCategories->name }}" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Description <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-file-excel-o"></i>
                                                        </div>
                                                        <textarea class="form-control" rows="5" type="text" name="product_category_description" placeholder="ex: Menu utama makanan berat ...." required>{{ $productCategories->description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                          
                                        </div>
                                    </div>
                                   
                                    <div class="form-group float-right">
                                        <button class="btn btn-sm btn-success" id="submitBtn" type="submit">Update Product Category</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4 text-right">
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>	
        </div>
    @endsection

    @section('content-script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('#myForm').submit(function(){
            $('#submitBtn').prop('disabled', true);

            Swal.fire({
                title: 'Wait a second, Processing File..',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            })
        })
    </script>

  
    
@endsection