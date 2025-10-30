@extends('layouts.app')

@section('htmlheader_title')
	{{ $title ?? '' }}
@endsection

@section('contentheader_title')
	{{ $title ?? '' }}
@endsection

@section('contentheader_description')
	{{ $title_description ?? '' }}
@endsection


@section('main-content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-body">
					<form class="form-horizontal" role="form" method="POST" action="{{ route('change-password') }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="form-group">
							<label class="col-md-4 control-label">Old Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="old_password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">New Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label">Confirm New Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>						
						
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary btn-flat">Change Password</button>
							</div>
						</div>						
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
