<!DOCTYPE html>
<html lang="en"  class="fuelux">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<!-- for ajax post method security token in laravel -->
		<meta name="csrf-token" content="{{ csrf_token() }}" />
					
		<title>@yield('htmlheader_title') - Bu Ana Catering</title>
		
		<!-- Bootstrap CSS -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href=" {{ secure_asset('/css/z.css') }}" rel="stylesheet" type="text/css">

		{{-- <link href="{{ secure_asset('/css/animate.min.css) }}" rel="stylesheet"> --}}
		<link href="https://fonts.googleapis.com/css?family=Exo:300,400,500,600,700,800,900" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Nunito:400,400i,600,700,700i,800,900,900i" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,700,900" rel="stylesheet">

		@stack('content-header')		
		
		@stack('html-header')
	</head>
	<body class="body-nopadding">
		{{--NAVIGATION BAR--}}
		@include('nav')
		{{--NAVIGATION BAR END--}}	

		<script type="text/javascript">
			var baseURL = {!! json_encode(url('/')) !!};	//getting base url
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
		</script>		

		@yield('content')

		{{--FOOTER--}}
		@include('footer')
		{{--FOOTER END--}}	
	</body>

	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	
	@stack('content-footer')

@section('scripts')
	@yield('content-script')
@show

</html>