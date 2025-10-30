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
            <form data-toggle="validator" role="form" method="POST" enctype="multipart/form-data"
                  action="{{ route('edit-profile') }}">
                <div class="col-sm-4 col-sm-push-8">
                    <div class="panel panel-default box-custom">
                        <div class="panel-body">
                            <h3 class="box-title text-center">Profile Picture</h3>
                            <div class="form-group">
                                <div class="col-md-12" id="photoButton">
                                    <center>
                                        @if(isset($member))
                                            <img class="img-responsive" id="photoPreview"
                                                 src="{{ route('image-show', ['medium', $member->avatar_path_view]) }}"/>
                                        @endif
                                        <span class="btn btn-default btn-file">
                                            Choose Photo<input type="file" name="photo">
                                        </span>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(0)
                    <div class="panel panel-default box-custom">
                        <div class="panel-body">
                            <h3 class="box-title text-center">Student Card</h3>
                            <div class="form-group">
                                <div class="col-md-12" id="idCardButton">
                                    <center>
                                        @if(isset($member) and $member->idcard_path)
                                            <img class="img-responsive" id="idCardPreview"
                                                 src="{{ secure_asset($member->idcard_path) }}"/>
                                        @else
                                            <img class="img-responsive"  id="idCardPreview"
                                                 src="{{ secure_asset('/img/no_preview.png') }}"/>
                                        @endif
                                        <span class="btn btn-default btn-file">
                                            Choose Image<input type="file" name="idcard">
                                        </span>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-sm-8 col-sm-pull-4">
                    <div class="panel panel-default box-custom">
                        <div class="panel-body">
                            <div class="col-sm-6">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <!-- name -->
                                <div class="form-group">
                                    <label>Name</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <input type="text" class="form-control" name="name"
                                               value="@if(isset($member)){{ $member->name }}@endif">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- gender -->
                                <div class="form-group">
                                    <label>Gender</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-transgender"></i>
                                        </div>
                                        <select class="form-control" name="gender">
                                            <option value="male"@if(isset($member))@if($member->gender=='male') selected="selected"
                                                    @endif @endif>Male
                                            </option>
                                            <option value="female" @if(isset($member))@if($member->gender=='female') selected="selected"
                                                    @endif @endif>Female
                                            </option>
                                        </select>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- birthdate -->
                                <div class="form-group">
                                    <label>Birth Date</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-birthday-cake"></i>
                                        </div>
                                        <input id="datepicker" class="form-control" name="birth_date" 
                                            value="@if(isset($member) and $member->birth_date){{  \Carbon\Carbon::createFromFormat('Y-m-d', $member->birth_date)->format('d-m-Y') }}@endif"  />
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- mobile phone -->
                                <div class="form-group">
                                    <label>Mobile Phone</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-mobile"></i>
                                        </div>
                                        <input type="text" class="form-control" name="mobile_phone"
                                               value="@if(isset($member)){{ $member->mobile_phone }}@endif">
                                    </div>
                                    <!-- /.input group -->
                                </div>

                                <!-- name -->
                                <div class="form-group">
                                    <label>Description</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-bookmark"></i>
                                        </div>
                                        <input type="text" class="form-control" name="description"
                                               value="@if(isset($member)){{ $member->description }}@endif">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Province</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-location-arrow"></i>
                                        </div>
                                        <select class="form-control" name="province" id="pro">
                                            @foreach($province as $prov)
                                                <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                            @endforeach
                                            <script type="text/javascript">
                                                document.getElementById('pro').value = '@if(isset($member)){{ $member->province_id }}@endif';
                                            </script>
                                        </select>
                                    </div>
                                </div>

                                <!-- city -->
                                <div class="form-group">
                                    <label>City</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-building"></i>
                                        </div>
                                        <input type="text" class="form-control" name="city"
                                               value="@if(isset($member)){{ $member->city }}@endif">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <div class="form-group">
                                    <label>Address</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-home"></i>
                                        </div>
                                        <input type="text" class="form-control" name="address"
                                               value="@if(isset($member)){{ $member->address }}@endif">
                                    </div>
                                    <!-- /.input group -->
                                </div>

                                <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input name="allow_newsletter" type="checkbox" value="1" 
                                        @if(isset($member->user->allow_newsletter) and $member->user->allow_newsletter == 1) checked @endif>
                                        Newsletter
                                    </label>
                                </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label></label>
                                <button type="submit" class="btn btn-block btn-success">Update</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('content-script')
    <script type="text/javascript">
        // <<<<< image preview
        function readURL(input, previewContainer) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#' + previewContainer).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $('#photoButton').on('change', '.btn-file :file', function () {
            readURL(this, 'photoPreview');
        });

        $('#idCardButton').on('change', '.btn-file :file', function () {
            readURL(this, 'idCardPreview');
        });
        // >>>>> image preview

        // alert(yearMax+" "+yearMin);
        $('#datepicker').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: '1970:{{\Carbon\Carbon::now()->year}}'
        });

        $('input#datepicker').bind('keyup keydown keypress', function (evt) {
           return false;
        });
    </script>
@endsection
