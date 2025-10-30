@extends('layouts.auth')

@section('htmlheader_title')
    Register
@endsection

@section('content')
    <body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ route('home') }}"><img class="logo" src="{{secure_asset('img/logo.png')}}" class="left-block"></a>
        </div>

    @if (isset($errors))		
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
	@endif
	@if (Session::has('message'))		
		<div class="alert alert-info">
			<strong>Success!</strong> {{ Session::get('message') }}
		</div>
	@endif
        <div class="register-box-body">
            <p class="login-box-msg">Register a new membership</p>
            <p class="box-title-second">Age requirement : {{ MINIMUM_AGE }}-{{ (MAXIMUM_AGE-1) }} years old</p>
            <form action="{{ route('facebook-register') }}" method="post" enctype="multipart/form-data" id="myRegisterForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="facebook_id" value="{{ $facebookUser->id ?? '' }}">
                <div class="form-group">
                    <center>
                        <img class="img-responsive"
                             src="{{ $facebookUser->avatar }}"/>
                        <input type="hidden" name="avatar" value="{{ $facebookUser->avatar }}">
                    </center>
                </div>
                @if(isset($facebookUser->user['email']))
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" name="email" 
                        value="{{ $facebookUser->user['email'] ?? '' }}" readonly="" />
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                @endif
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Full name" name="name" 
                        value="{{ $facebookUser->user['first_name'] ?? '' }} {{ $facebookUser->user['middle_name'] ?? '' }} {{ $facebookUser->user['last_name'] ?? '' }}" readonly="" />
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <select class="form-control" name="gender" readonly="">
                        @if(isset($facebookUser->user['gender']))
                            @if($facebookUser->user['gender']=='male') 
                                <option selected="selected"> Male</option>
                            @endif 
                        @endif
                        @if(isset($facebookUser->user['gender']))
                            @if($facebookUser->user['gender']=='female') 
                                <option selected="selected"> Female</option>
                            @endif 
                        @endif
                    </select>
                </div>
                <div class="form-group has-feedback">
                    <input @if(!isset($facebookUser->user['birthday'])) id="datepicker" @endif class="form-control" placeholder="Birth Date" name="birth_date" 
                        @if(isset($facebookUser->user['birthday'])) value="{{  \Carbon\Carbon::createFromFormat('m/d/Y', $facebookUser->user['birthday'])->format('d-m-Y') }}" readonly="" @endif required />
                    <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                </div>
                <hr>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Reference id" name="reference_id" value="{{ old('reference_id') }}"/>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
				<div class="form-group has-feedback">
					<select class="form-control" name="education" >
						@foreach($education as $edu)
							<option value="{{ $edu->id }}">{{ $edu->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="School" name="school" required/>
                    <span class="glyphicon glyphicon-briefcase form-control-feedback"></span>
                </div>
				<div class="form-group has-feedback">
                    <input type="number" class="form-control" placeholder="Handphone" name="handphone" required/>
                    <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                </div>
				<div class="form-group has-feedback">
					<select class="form-control" name="province" value="{{ old('province') }}">
                        @foreach($province as $prov)
							<option value="{{ $prov->id }}">{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="City" name="city" value="{{ old('city') }}" required/>                    
                </div>				
				<div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Address" name="address" required/>
                    <span class="glyphicon glyphicon-home form-control-feedback"></span>
                </div>				
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox"> I agree to the <a href="http://www.user.com/show/131/terms-and-condition">terms</a>
                            </label>
                        </div>
                    </div><!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                    </div><!-- /.col -->
                </div>
            </form>


            <a href="{{ url('login') }}" class="text-center">I already have a membership</a>
        </div><!-- /.form-box -->
    </div><!-- /.register-box -->

    @include('layouts.partials.scripts_auth')

    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
			
			
			var d = new Date();
			var month = d.getMonth();
			var day = d.getDate()-1;
			var yearMax = d.getFullYear()-MINIMUM_AGE;
			var yearMin = d.getFullYear()-MAXIMUM_AGE;
			// alert(yearMax+" "+yearMin);
			$('#datepicker').datepicker({
				dateFormat: 'dd-mm-yy',
				changeMonth: true,
				changeYear: true,
				minDate: new Date(yearMin, month, day),
				maxDate: new Date(yearMax, month, day),
			});
        });
        $('input#datepicker').bind('keyup keydown keypress', function (evt) {
           return false;
        });
        //prevent user submit twice
        $("#myRegisterForm").submit(function() {
            $(this).submit(function() {
                return false;
            });
            return true;
        });
    </script>
	<style>
		input[type="date"]:before {
			content: attr(placeholder) !important;
			color: #aaa;
			margin-right: 0.5em;
		}
		input[type="date"]:focus:before,
		input[type="date"]:valid:before {
			content: "";
		}
	</style>
</body>

@endsection
