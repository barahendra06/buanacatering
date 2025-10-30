@extends('app')

@section('title')
{{ $user->name }}
@endsection

@section('content')
<div>
	<ul class="list-group">
		<img src="{{ secure_asset($user->photo_path) }}" alt="{{ $user->name }}" style="width:304px;height:228px;">
		<li class="list-group-item">
			Data registered on {{$user->created_at->format('M d,Y \a\t h:i a') }}
		</li>
		<li class="list-group-item panel-body">
			<table class="table-padding">
				<style>
					.table-padding td{
						padding: 3px 8px;
					}
				</style>
				<tr>
					<td>Nama</td>
					<td> {{ $user->username }}</td>
				</tr>				
				<tr>
					<td>Email</td>
					<td> {{ $user->email }}</td>
				</tr>				

			</table>
		</li>
	</ul>
</div>

<div class="panel panel-default">
	<div class="panel-heading"><h3>Latest Job History</h3></div>
	<div class="panel-body">

	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading"><h3>Latest Achievement</h3></div>
	<div class="list-group">

	</div>
</div>
@endsection
