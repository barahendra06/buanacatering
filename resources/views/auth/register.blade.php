@extends('z') 
@section('htmlheader_title') 
	Register 
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
			<div class="col-md-4 offset-md-4" style="margin-top: 3%;">
				<center>
					<span style="font-size:18pt;">
						<strong>Create An Account</strong>
					</span>
				</center>
				<form role="form"  action="{{ url('/register') }}" method="post" style="margin-top: 3%;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-group">
						<input type="name" class="form-control" name="name" placeholder="Full Name">
					</div>
					<div class="form-group">
						<input type="email" class="form-control" name="email" placeholder="Email Address">
					</div>
					<div class="form-group">
						<input type="password" class="form-control" name="password" placeholder="Password">
					</div>
					<div class="form-group">
						<input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
					</div>
					<button type="submit" class="btn btn-black pull-right">SIGN UP</button>
				</form>
			</div>
			<div class="col-md-4 offset-md-4" style="margin-top: 3%; margin-bottom: 10%;">
				<center>
					<span style="font-size:18pt;">
						<strong>Already Have an Account?</strong>
					</span>
				</center>
				<div class="text-center">
					<a href="{{ url('/login') }}" class="btn btn-black">LOG IN</a>
				</center>
			</div>
		</div>
	</div>	
@endsection 
@push('content-footer') 
@endpush