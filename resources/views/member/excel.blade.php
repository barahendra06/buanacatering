	

	<table id="employeeTable" class="display" cellspacing="0">
		<thead>
			<tr>
				<th class="text-center">No</th>
				<th class="text-center">ID</th>
				<th class="text-center">Name</th>
				<th class="text-center">Email</th>
				<th class="text-center">Cellphone</th>
				<th class="text-center">Education</th>
				<th class="text-center">School</th>
				<th class="text-center">Province</th>
				<th class="text-center">City</th>
				<th class="text-center">Age</th>
				<th class="text-center">Status</th>
				<th class="text-center">Poin</th>
				<th class="text-center">Join</th>
			</tr>
		</thead>
		{{ $i=1 }}
		@foreach ($members as $member) 
			<tr>
				<td>{{ $i++ }} </td>
				<td class="text-center p-a-4">{{ $member->user->id ?? '-' }} </td>
				<td>{{ $member->name ?? '-' }} </td>
				<td>{{ $member->user->email ?? '-' }} </td>
				<td>{{ $member->mobile_phone ?? '-' }} </td>
				<td>{{ $member->educationType->name ?? '-' }} </td>
				<td>{{ $member->school ?? '-' }} </td>
				<td>{{ $member->province->name ?? '-' }} </td>
				<td>{{ $member->city ?? '-' }} </td>
				<td class="text-center">{{ $member->age ?? '-' }} </td>
				<td class="text-center">
					@if(isset($member->user->confirmed))
						@if($member->user->confirmed == 0)
							-
						@else
							Active
						@endif
					@endif
				</td>
				<td class="text-center">{{ $member->activity->sum('poin') }} </td>
				<td>{{ $member->created_at->format('d M Y - H:i:s') }} </td>
			</tr>
		@endforeach					
	</table>					