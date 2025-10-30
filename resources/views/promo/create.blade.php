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
        .add-photo-button {
            cursor: pointer;
        }

        .photo-preview {
            object-fit: cover;
            object-position: center;
            width: 100%;
            height: 175px;
        }

        .mt-2 {
            margin-top: 1rem;
        }

        .pl-0 {
            padding-left: 0;
        }
    </style>
@endpush

@section('main-content')
    <form method="post" action="{{ route('promo-store') }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default box-custom">
                    <div class="panel-heading">
                        <strong>Add Promo</strong>
                        <div class="box-tools pull-right">
                            <a href="{{ route('promo-list') }}" class="btn btn-xs btn-info">Back To List Promo</a>
                        </div>
                    </div>
                    <div class="panel-body">
                    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Promo Name" id="">
                                </div>
                            </div>

                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" class="form-control" name="description" placeholder="Promo Description" id="">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Url</label>
                                    <input type="text" class="form-control" placeholder="Url" name="url" id="">
                                </div>
                            </div>
                          
                            <div class="col-md-6">
                                <div class="form-group " id="">
                                    <label>Promo Type</label>
                                    <select class="form-control" name="promo_type" id="" required>
                                        <option value="">Choose one</option>
                                        @foreach ($promoTypes as $promoType)
                                            <option value="{{ $promoType->id }}">{{ $promoType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                                
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <div class='input-group datetime'>
                                        <input name="start_date" autocomplete="off" value="{{ old('start_date') }}" type='text'
                                            class="form-control" placeholder="Start Date" required />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
        
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <div class='input-group datetime'>
                                        <input autocomplete="off" name="end_date" value="{{ old('end_date') }}" type='text'
                                            class="form-control" placeholder="End Date" required />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
    
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="submit" name="submit" value="Create" class="btn btn-sm btn-success">
                            </div>
                        </div>
        
                    </div>
        
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <div class="form-group text-center">
                            <label>Image</label>
                            <img class="img-preview" id="img-product-preview" width=100% height=190px
                                style="object-fit: contain;display:block;" src="{{ asset('img/no_preview.jpg') }}">
                            <input class="form-control" onchange="previewImageProduct(event);"
                                value="{{ old('image') }}" type="file" accept="image/*" name="image"
                                id="image" style="border:none" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('content-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script src="{{ asset('js/collect.min.js') }}"></script>
  
    <script>

         function initiateDateTimePicker() {
            $('.datetime').datetimepicker({
                format: 'DD-MM-YYYY'
            });
         }
        $(document).ready(function() {
            initiateDateTimePicker();

        });

         function previewImageProduct(event) {
            if (event.target.files.length > 0) {
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("img-product-preview");
                preview.src = src;
                preview.style.display = "block";
            }
        }
    </script>
@endsection
