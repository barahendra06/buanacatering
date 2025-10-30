@extends('z')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12 mt-4">
			<div class="error-page">
				<a href="{{ url('/') }}">
					<img class="img-fluid d-block d-sm-none mx-auto" src="{{ secure_asset('/img/404.png') }}">
					<img class="img-fluid d-none d-sm-block mx-auto" src="{{ secure_asset('/img/404.png') }}">
				</a>
			</div><!-- /.error-page -->
		</div>
	</div>
</div>
@endsection