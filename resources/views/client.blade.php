
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

@if(Auth())
	Already login
@else
	Not login yet
@endif

	<script src="http://localhost:6001/socket.io/socket.io.js"></script>
	<!--<script src="{{ secure_asset('/js/laravelEchoClientDist.js') }}"></script>-->
	<script src="http://localhost/z/public_html/js/laravelEchoClientDist.js"></script>


</body>