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
@endpush

@section('main-content')
{{-- <div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Filter</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="myForm" role="form" method="POST" enctype="multipart/form-data" action="{{ route('team-list') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
    
                        
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>	
</div> --}}
<div class="row">
    <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Promo</h3>
                    {{-- <div class="box-tools pull-right">
                        <a href="{{ route('promo-create') }}" class="btn btn-xs btn-success">Create</a>
                    </div> --}}
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="table" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Description</th>
                                    <th class="text-center">Promo Type</th>
                                    <th class="text-center">Start Date</th>
                                    <th class="text-center">End Date</th>
                                    {{-- <th class="text-center">Push Notification</th> --}}
                                    <th class="text-center">Image</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listPromos as $listPromo)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $listPromo->name }}</td>
                                    <td class="text-center">{{ $listPromo->description }}</td>
                                    <td class="text-center">{{ $listPromo->promoType->name ?? '-' }}</td>
                                    <td class="text-center">{{ date_format($listPromo->start_date,'d-m-Y') }}</td>
                                    <td class="text-center">{{ date_format($listPromo->end_date, 'd-m-Y')}}</td>
                                     {{-- <td class="text-center">
                                        @if ($listPromo->is_push_notification)
                                            <span><i class="fa fa-check" aria-hidden="true" style="color: green"></i></span>
                                        @else
                                            <span><i class="fa fa-times" aria-hidden="true" style="color: red"></i></span>
                                        @endif

                                    </td> --}}
                                    <td class="text-center">
                                        @if (isset($listPromo->image_path))
                                            <img style="height: 110px!important" class="img img-fit img-fluid"
                                                src="{{ secure_asset($listPromo->image_path) }}" alt="">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('promo-edit', ['id' => $listPromo->id]) }}"
                                            class="mt-1 btn btn-xs btn-warning"><i class="fa fa-edit"></i> Edit </a>
                                        {{-- <br> --}}
                                        <a data-id="{{ $listPromo->id }}" id="deletePromo"
                                            class="mt-1 btn btn-danger btn-xs deletePromo "><i class="fa fa-trash"></i>
                                            delete</a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
   
    <script>
        $(document).ready(function () {
            $('.table').DataTable();
        });
    </script>

    <script>
          $('.deletePromo').on('click', function() {
            var promoId = $(this).attr('data-id');
            swal({
                    title: "Delete Promo",
                    text: "Are you sure you want to delete this Promo?",
                    type: "warning",
                    showCancelButton: true,
                    closeOnConfirm: true,
                    confirmButtonText: "Yes",
                    confirmButtonColor: "#ec6c62"
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: 'POST',
                            url: baseURL + "/promo/deletePromo/" + promoId,
                            data: {
                                '_method': 'delete'
                            },
                            success: function(data) {
                                // location.reload();
                                // var responseText=JSON.parse(data.responseText);
                                swal({
                                        title: "Success!",
                                        text: "Deleting promo successfully",
                                        type: "success",
                                        html: true
                                    },
                                    function() {
                                        location.reload();
                                    }
                                );
                            },
                            error: function(errors) {
                                // console.log(errors)
                                // console.log(JSON.parse(errors.responseText))
                                var responseText = JSON.parse(errors.responseText);
                                swal({
                                        title: "Error!",
                                        text: responseText.message,
                                        type: "error",
                                        html: true
                                    },
                                    function() {
                                        // location.reload();
                                    });
                            }
                        });
                    }
                });
        });
    </script>


@endsection