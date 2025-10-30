@extends('z')

@section('htmlheader_title')
{{ 'Maintenance' }}
@endsection

@push('content-header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
@endpush


@section('content')
<div class="container-fluid" style="background-image: url('../img/bg-footer.jpg'); height:500px">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="form-group text-center">
                        <br><br><br><br><br>
                        <i class="fas fa-gears" style="font-size:170px; color:white;"></i>
                        <br><br>
						<h3 class="text-center" style="color: white">Sedang Maintenance, Tunggu bentar yaaa </h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
