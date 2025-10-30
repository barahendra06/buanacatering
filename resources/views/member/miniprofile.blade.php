<div class="container-fluid">
	<div class="row">
		<div class="col-md-4 col-md-push-8">
			@if(isset($member))
			<div class="panel">				
				<a target="_blank" href="{{ url('view?'.'path='.$member->avatar_path.'&text='.$member->name.' ('.$member->user->id.')') }}">
					<img class="img-responsive"  id="photoPreview" src="{{ route('image-show', [IMAGE_MEDIUM, $member->avatar_path_view ]) }}" alt="{{ $member->name }}"/>
				</a>
			</div>								
			@endif
			@if(auth()->check() and $currentMember->user->isAdmin())
			<div style="padding: 2px;">
				<a href="{{ route('member-profile',['id'=>$member->id]) }}" class="btn btn-xs btn-primary btn-flat">
					<span class="glyphicon glyphicon-search"></span> Lihat Detail
				</a> 
				<a target="_blank" href="{{ route('member-profile',['id'=>$member->id]) }}" class="btn btn-xs btn-primary btn-flat">
					<span class="glyphicon glyphicon-new-window"></span>
				</a>
			</div>
			<div style="padding: 2px;">
				<a href="{{ route('member-public-profile',['id'=>$member->id]) }}" class="btn btn-xs bg-maroon btn-flat">
					<span class="glyphicon glyphicon-user"></span> Public Profile
				</a> 
				<a target="_blank" href="{{ route('member-public-profile',['id'=>$member->id]) }}" class="btn btn-xs bg-maroon btn-flat">
					<span class="glyphicon glyphicon-new-window"></span>
				</a>
			</div>
			<div style="padding: 2px;">
				<a href="{{ route('member-edit',['id'=>$member->id]) }}" class="btn btn-xs btn-success btn-flat">
					<i class="glyphicon glyphicon-edit"></i> Ubah Data
				</a> 
				<a target="_blank"  href="{{ route('member-edit',[ 'id'=>$member->id ]) }}" class="btn btn-xs btn-success btn-flat">
					<span class="glyphicon glyphicon-new-window"></span>
				</a>
			</div>
			<div style="padding: 2px;">
				<a href="{{ route('conversation-show',['recipient_id'=>$member->id]) }}" class="btn btn-xs btn-warning btn-flat">
					<i class="fa fa-envelope"></i>&nbsp;Send Message
				</a> 
				<a target="_blank"  href="{{ route('conversation-show',[ 'recipient_id'=>$member->id ]) }}" class="btn btn-xs btn-warning btn-flat">
					<span class="glyphicon glyphicon-new-window"></span>
				</a>
			</div>
			@endif
			@if(Auth::user()->isSuperAdmin())
				<div style="padding: 2px;">
					<a target="_blank" title="Login As" href="{{ route('member-login-as', $member->id) }}" class="btn btn-xs btn-danger">
						<i class="glyphicon glyphicon-user"></i> Login As
					</a>
				</div>
			@endif
			
		</div>		
		<div class="col-md-8 col-md-pull-4">
			<div class="panel panel-default">							
				<div class="panel-body">					
					<div class="table-responsive">          
						<table class="table borderless">
							<tr>
								<td><strong>Role</strong></td>
								<td>{{ $member->user->role->role ?? '-' }}</td>
							</tr>
							<tr>
								<td><strong>Name</strong></td>
								<td>{{ $member->name ?? '-' }}</td>
							</tr>
							<tr>
								<td><strong>Ref. ID</strong></td>
								<td>{{ $member->user->id ?? '-' }}</td>
							</tr>
							<tr>
								<td><strong>Poin</strong></td>
								<td>
									@if($member->activity)
										{{ $member->activity->sum('poin') }}
									@else
										-
									@endif
								</td>
							</tr>									
							<tr>
								<td><strong>Gender</strong></td>
								<td>{{ $member->gender ?? '-'}}</td>
							</tr>						
							<tr>
								<td><strong>School</strong></td>
								<td>{{ $member->school ?? '-'}}</td>
							</tr>
							<tr>
								<td><strong>Mobile Phone</strong></td>
								<td>{{ $member->mobile_phone ?? '-'}}</td>
							</tr>									
							<tr>
								<td><strong>Province</strong></td>
								<td>{{ $member->province->name ?? '-' }}</td>
							</tr>				
							<tr>
								<td><strong>City</strong></td>
								<td>{{ $member->city ?? '-'}}</td>
							</tr>								
							<tr>
								<td><strong>Address</strong></td>
								<td>{{ $member->address ?? '-'}}</td>
							</tr>								
							<tr>
								<td><strong>E-Mail</strong></td>
								<td>{{ $member->user->email ?? '-'}}</td>
							</tr>								
						</table>
					</div>
															
				</div>
			</div>				
		</div> 

	</div>

</div>

