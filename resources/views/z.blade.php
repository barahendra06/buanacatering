<!DOCTYPE html>
<html lang="en"  class="fuelux">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<!-- for ajax post method security token in laravel -->
		<meta name="csrf-token" content="{{ csrf_token() }}" />
					
		<title>@yield('htmlheader_title') - Bu Ana Catering</title>
		
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="css/vendor.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">

		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

		@stack('content-header')		
		
		@stack('html-header')
	</head>
	<body class="body-nopadding">		

		{{--NAVIGATION BAR--}}
		{{-- @include('nav') --}}
		{{--NAVIGATION BAR END--}}	


		@yield('content')

		@yield('content-body-scripts')
	</body>

	<script src="js/jquery-1.11.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>
	
	@stack('content-footer')

@section('scripts')
	@yield('content-script')
@show

</html>