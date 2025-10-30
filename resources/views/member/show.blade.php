@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">List Pegawai</div>
				<div class="panel-body">
					<table class="table-padding">	
						<style>
							.table-padding td{
							padding: 3px 8px;
							}
						</style>				
						@foreach ($employees as $employee) 
							<tr>
							<td>{{ $employee->name }} </td>
							<td>{{ $employee->address }} </td>
							<td>{{ $employee->phone_number }} </td>
							<td><a href="{{ url('/edituser/'.$employee->user_id) }}">Edit</a></td>
							<td><a href="{{ url('/user/'.$employee->user_id) }}">View</a></td>
							</tr>
						@endforeach
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
