@extends('z')

@section('htmlheader_title')
{{ $title ?? 'Not Authorized' }}
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="form-group">
						<img class="img-responsive"  src="{{ secure_asset('/img/403.jpg')}} ">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
