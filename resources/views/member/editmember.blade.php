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
            <div class="col-sm-8">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <h3 class="box-title">Change Member Password</h3>
                        <form data-toggle="validator" role="form" method="POST" enctype="multipart/form-data"
                            action="{{ route('member-password') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{ $member->user->id }}">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-lock"></i>
                                    </div>
                                    <input type="text" class="form-control" name="newPassword" placeholder="New Password">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-info btn-flat">Change</button>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <form data-toggle="validator" role="form" method="POST" enctype="multipart/form-data"
                  action="{{ route('member-edit',['id'=>$member->id]) }}">
                <div class="col-sm-4 col-sm-push-8">
                    <div class="panel panel-default box-custom">
                        <div class="panel-body">
                            <h3 class="box-title">Photo Profile</h3>
                            <div class="form-group">
                                <div class="col-md-12" id="photoButton">
                                    <center>
                                        @if(isset($member) and $member->avatar_path)
                                            <a target="_blank"
                                               href="{{ url('view?'.'path='.$member->avatar_path.'&text='.$member->name.' ('.$member->id.')') }}">
                                                <img class="img-responsive" id="photoPreview"
                                                     src="{{ secure_asset($member->avatar_path) }}"/>
                                            </a>
                                        @else
                                            @if($member->gender == 'male')
                                                <img class="img-user img-responsive img-circle" src="{{ secure_asset('img/boys.jpg') }}" alt="{{ $member->name }}">     
                                            @else
                                                <img class="img-user img-responsive img-circle" src="{{ secure_asset('img/girls.jpg') }}" alt="{{ $member->name }}">                                
                                            @endif
                                        @endif
                                        <span class="btn btn-default btn-file">
                                            Pilih Foto<input type="file" name="photo">
                                        </span>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8 col-sm-pull-4">
                    <div class="panel panel-default box-custom">
                        <div class="panel-body">
                            <h3 class="box-title">Edit Profile</h3>
                            <div class="col-sm-6">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <!-- name -->

                                <div class="form-group">
                                    <label>Email</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-envelope"></i>
                                        </div>
                                        
                                        <input class="form-control" value="{{ $member->user->email ?? '' }}" readonly>
                                    </div>
                                </div>
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
                                            <option @if(isset($member))@if($member->gender=='male') selected="selected"
                                                    @endif @endif>Male
                                            </option>
                                            <option @if(isset($member))@if($member->gender=='female') selected="selected"
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
                                        <input type="date" class="form-control" name="birth_date"
                                               value="@if(isset($member)){{ $member->birth_date }}@endif"/>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                                <!-- address -->
                                <!-- phone -->
                                <!-- <div class="form-group">
                                    <label>Phone</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-phone"></i>
                                        </div>
                                        <input type="text" class="form-control" name="phone"
                                               value="@if(isset($member)){{ $member->phone }}@endif">
                                    </div> -->
                                    <!-- /.input group -->
                                <!-- </div> -->
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


                                <!-- Education -->
                                <div class="form-group">
                                    <label>Education</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-mortar-board"></i>
                                        </div>
                                        <select class="form-control" name="education" id="edu">
                                            @foreach($education as $edu)
                                                <option value="{{ $edu->id }}">{{ $edu->name }}</option>
                                            @endforeach
                                            <script type="text/javascript">
                                                document.getElementById('edu').value = '@if(isset($member)){{ $member->education_type_id }}@endif';
                                            </script>
                                        </select>
                                    </div>
                                </div>

                                <!-- school -->
                                <div class="form-group">
                                    <label>School</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-mortar-board"></i>
                                        </div>
                                        <input type="text" class="form-control" name="school"
                                               value="@if(isset($member)){{ $member->school }}@endif">
                                    </div>
                                    <!-- /.input group -->
                                </div>
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

        $('#documentButton').on('change', '.btn-file :file', function () {
            var this_index = $(this).attr('data-index');
            readURL(this, 'docPreview' + this_index);
        });

        $('#documentButton').on('click', 'a', function () {
            var this_action = $(this).attr('data-action');
            var this_index = $(this).attr('data-index');
            if (this_action == 'delete') {
                $(this).hide();
                var document_id = $(this).attr('document-id');
                deleteDocument(this_index, document_id);
            }
        });
        // >>>>> image preview
    </script>
@endsection
