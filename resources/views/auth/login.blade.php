@extends('z') 
@section('htmlheader_title') 
	Login
@endsection 
@push('content-header')
	<!-- Important Owl stylesheet -->
	<link rel="stylesheet" href="{{ secure_asset('/css/owl.carousel.css') }}">
	<!-- Default Theme -->
	<link rel="stylesheet" href="{{ secure_asset('/css/owl.theme.css') }}">
	<style type="text/css">
		.owl-controls 
		{
			position: absolute;
			top: 20%;
			width: 100%;
		}

		.owl-prev 
		{
			position: absolute;
			left: 0px;
		}

		.owl-next 
		{
			position: absolute;
			right: 25px;
		}

		.owl-theme .owl-controls .owl-buttons div 
		{
			color: #ec008c;
			display: inline-block;
			zoom: 0;
			font-weight: 900;
			margin: 5px;
			padding: 0;
			font-size: 33px;
			-webkit-border-radius: 0;
			-moz-border-radius: 30px;
			border-radius: 0;
			background: transparent;
			filter: Alpha(Opacity=50);
			opacity: 1;
		}


		@media(max-width: 768px) 
		{
			.owl-controls 
			{
				position: absolute;
				top: 10%;
				width: 100%;
			}

			.owl-next 
			{
				position: absolute;
				right: 0;
			}
		}
	</style>
@endpush 
@section('content')
	<div class="container-fluid">
		@if (isset($error))        
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endif
        @if(session()->get('error'))        
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        @if (isset($errors))
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif
		<div class="row clearfix">
			<div class="col-md-4 offset-md-4 mb-5" style="margin-top: 3%; background-color: #a8282c;">
				<center>
					<img src="{{ asset('img/logo.png') }}" width="150px" alt="" style="margin-left:-20px"><br>
					<span style="font-size:18pt; color:#fff">
						<strong>Log Into Your Account</strong>
					</span>
				</center>
				<form role="form" method="POST" action="{{ url('/login') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="intended" id="intended">
					<div class="form-group mb-2">
						<input type="email" class="form-control" name="email" placeholder="Email Address">
					</div>
					<div class="form-group mt-2">
						<input type="password" class="form-control" name="password" placeholder="Password">
					</div>
					<div class="row col-12 m-b-5">

						<div class="checkbox">
							<label class="text-white">
								<input type="checkbox" name="remember"> Remember Me
							</label>
						</div>
					</div>
					{{-- <a href="{{ url('/password/reset') }}" style="color:#000">Forgot Password?</a> --}}
					<button type="submit" class="btn btn-warning pull-right mt-3 mb-5">LOG IN</button>
				</form>
			</div>
			{{-- <div class="col-md-4 offset-md-4 d-none" style="margin-top: 3%; margin-bottom: 10%;">
				<center>
					<span style="font-size:18pt;">
						<strong>Don't Already Have an Account?</strong>
					</span>
				</center>
				<div class="text-center">
					<a href="{{ url('/register') }}" class="btn btn-black">SIGN UP</a>
				</center>
			</div> --}}
		</div>
	</div>	
@endsection 
@push('content-footer') 
@endpush