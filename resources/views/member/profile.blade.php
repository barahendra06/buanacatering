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
		<div style="padding: 4px;">
			<a href="{{ route('member-edit',[ 'id'=>$member->id ]) }}" class="btn btn-xs btn-success btn-flat"><i class="glyphicon glyphicon-edit"></i> Edit Data</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-default">							
				<div class="panel-heading"><strong>Photo Profile</strong></div>
				<div class="panel-body">	
					@if(isset($member) and $member->avatar_path)
						<a target="_blank" href="{{ url('view?'.'path='.$member->avatar_path.'&text='.$member->name.' ('.$member->user->id.')') }}"><img class="img-responsive"  id="photoPreview" src="{{ secure_asset($member->avatar_path) }}"/></a>
					@else
						@if($member->gender == 'male')
							<img class="img-user img-responsive img-circle" src="{{ secure_asset('img/boys.jpg') }}" alt="{{ $member->name }}">		
						@else
							<img class="img-user img-responsive img-circle" src="{{ secure_asset('img/girls.jpg') }}" alt="{{ $member->name }}">								
						@endif
					@endif				
				</div>
				<div class="panel-heading"><strong>Data Personal</strong></div>
				<div class="panel-body">	
					<div class="table-responsive">          
						<table class="table borderless">	
							<tr>
								<td><strong>Ref. ID</td>
								<td>{{ $member->user_id ?? '-' }}</td>
							</tr>
							<tr>
								<td><strong>Nama</td>
								<td>{{ $member->name ?? '-' }}</td>
							</tr>						
							<tr>
								<td><strong>Join</td>
								<td>{{ $member->created_at->diffForHumans() }}</td>
							</tr>		
							<tr>
								<td><strong>E-Mail</td>
								<td>{{ $member->user->email ?? '-' }}</td>
							</tr>		
							<tr>
								<td><strong>Tanggal Lahir</td>
								<td>{{ $member->birth_date ?? '-' }}</td>
							</tr>						
							<tr>
								<td><strong>Age</td>
								<td>{{ $member->age ?? '-' }} years old</td>
							</tr>					
							<tr>
								<td><strong>Jenis Kelamin</td>
								<td>{{ $member->gender  ?? '-' }}</td>
							</tr>	
							<tr>
								<td><strong>Education</strong></td>
								<td>{{ $member->educationType->name ?? '-' }}</td>
							</tr>		
							<tr>
								<td><strong>School</strong></td>
								<td>{{ $member->school ?? '-' }}</td>
							</tr>		
							<tr>
								<td><strong>Mobile Phone</td>
								<td>{{ $member->mobile_phone  ?? '-' }}</td>
							</tr>	
							<tr>
								<td><strong>Province</strong></td>
								<td>{{ $member->province->name ?? '-' }}</td>
							</tr>	
							<tr>
								<td><strong>City</strong></td>
								<td>{{ $member->city ?? '-' }}</td>
							</tr>
							<tr>
								<td><strong>Address</td>
								<td>{{ $member->address  ?? '-' }}</td>
							</tr>		
							<tr>
								<td><strong>Total Point</td>
								<td>{{ $member->total_point ?? '-' }}</td>
							</tr>															
						</table>
					</div>		
				</div>				
			</div>
		</div>
		
		
        <div class="col-md-8">
            <!-- Profile Image -->
            <div class="panel panel-default box-custom">
            	<br>
				<div class="text-center">
					<a id="addPoint" style="cursor:pointer" class="btn btn-xs btn-warning btn-flat">
						<i class="fa fa-plus-square"></i> Tambah Poin
					</a> 
				</div>
				
		        <div id="panelPoint" class="panel panel-default box-custom" style="display:none">
		            <div class="panel-body">
		            	
		            	<form id="pointForm" data-toggle="validator" role="form" method="POST" action="{{ route('member-add-point') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="member_id" value="{{ $member->id ?? '' }}">
                            <div class="text-center error-validation" style="margin: 0px" id="text_validation"></div>
			            	<div class="col-sm-12">
			            		<div class="form-group">
			                        <label>Remark</label>
			                        <div class="input-group">  
			                            <div class="input-group-addon">
			                                <i class="fa fa-file-o"></i>
			                            </div>                          
			                            <input id="remarkInput" type="text" class="form-control" name="remark" required>
			                        </div>
			                    </div>
			            		<div class="form-group">
			                        <label>Point</label>
			                        <div class="input-group">    
			                            <div class="input-group-addon">
			                                <i class="fa fa-star"></i>
			                            </div>                        
			                            <input id="pointInput" type="number" class="form-control" name="poin" required>
			                        </div>
			                    </div>
			            	</div>
			            	<div class="text-center">
				            	<a data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-primary btn-flat">
									Submit
								</a> 
			            	</div>
		            	</form>
		            </div>
		        </div>
                @include('member.activity_poin')
            </div>
            <!-- /.box -->
        </div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
	<div class="modal-dialog">    
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header" style="padding:15px 50px;">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4></span>Caution</h4>
			</div>
			<div class="modal-body load_modal" >
				Are you sure to add point to this member ?
			</div>
			<div class="modal-footer">
	            <button type="button" class="btn btn-danger btn-flat float-left" data-dismiss="modal">Cancel</button>
	            <button type="button" class="btn btn-success btn-flat float-left" data-dismiss="modal" id="yes">Yes</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('content-script')
<script type="text/javascript">
	$(function(){
	    $(".panel").on('click', '#addPoint', function(){
	        $("#panelPoint").slideToggle("slow");
	    });
	});

 	$('#yes').on('click', function(){
 		$('#pointForm').submit()
 	});

 	$('#pointForm').submit(function(){
 			if($('#pointInput').val() == '' || $('#remarkInput').val() == '')
 			{
 				console.log("gagal");
 				$('#text_validation').text("Remark and Point are required");
 				return false;
 			}
 				console.log("masuk");
 			return true;
 		});
</script>
@endsection
