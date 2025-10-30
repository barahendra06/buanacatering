@extends('app')

@section('title')
{{$title}}
@endsection

@section('content')		
<!-- Scripts -->
<div class="container-fluid">
@if(isset($employee))
	<div class="row">
		<div style="padding: 4px;">
			<a title="Lihat data lengkap karyawan" href="{{ url('employee/profile').'/'.$employee->user_id }}" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-search"></span> Lihat Detail</a>
			<a title="Ubah data karyawan" href="{{ url('employee/edit').'/'.$employee->user_id }}" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-edit"></i> Ubah Data</a>
			<a title="Ubah data mutasi karyawan" href="{{ url('employee/history').'/'.$employee->user_id }}" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-random"></i> Mutasi Karyawan</a> 
		</div>
	</div>
	<form id="myForm" class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="{{ url('/employee/edit/'.$user->id) }}">
@else
	<form id="myForm" class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="{{ url('/employee/create') }}">	
@endif
		<div class="row">
			<div class="col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading"><strong>Data Login</strong></div>
					<div class="panel-body">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							@if(isset($employee))								
								<input type="hidden" name="user_id" value="{{ $employee->user_id }}">
							@endif
							<div class="form-group">
								<label class="col-md-4 control-label">Username</label>
								<div class="col-md-6">
									<input required type="text" class="form-control" name="username" value="{{ $user->username ?? old( 'username') }}">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-4 control-label">E-Mail</label>
								<div class="col-md-6">
									<input type="email" class="form-control"  name="email" value="{{ $user->email ?? old( 'username')}}">
								</div>
							</div>					
							<div class="form-group">
								<label class="col-md-4 control-label">E-Mail Password</label>
								<div class="col-md-6">
									<input type="text" class="form-control"  name="email_password" value="{{ $user->email_password ?? old( 'email_password')}}">
								</div>
							</div>					
@if(Auth::user()->isAdmin())							
							<div class="form-group">
								<label class="col-md-4 control-label">Role</label>
								<div class="col-md-6">
									{!! Form::select('role', $choice['role'], isset($user->role)?$user->role:0); !!}
								</div>
							</div>					
@endif							
							<div class="form-group">
								<label class="col-md-4 control-label">Password</label>
								<div class="col-md-6">
								@if(isset($employee))								
									<input type="password" class="form-control" name="password">
								@else
									<input required type="password" class="form-control" name="password">
								@endif
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Confirm Password</label>
								<div class="col-md-6">
									<input type="password" class="form-control" name="password_confirmation">
								</div>
							</div>							
					</div>
					<div class="panel-heading"><strong>Data Personal</strong></div>
					<div class="panel-body">
							<div class="form-group">
								<label class="col-md-4 control-label">Nama</label>
								<div class="col-md-6">
									<input required type="text" class="form-control" name="name" value="{{ $employee->name ?? old( 'name') }}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">Nickname</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="nickname" value="{{ $employee->nickname ?? old( 'nickname') }}">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-4 control-label">Jenis Kelamin</label>
								<div class="col-md-6">								
									{!! Form::select('sex', $choice['sex'], isset($employee->sex)?$employee->sex:old('sex')); !!}
								</div>
							</div>	
							
							<div class="form-group">
								<label class="col-md-4 control-label">Tempat Lahir</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="birth_place" value="{{ $employee->birth_place ?? old( 'birth_place') }}">
								</div>
							</div>					
							
							<div class="form-group">
								<label class="col-md-4 control-label">Tanggal Lahir</label>
								<div class="col-md-6">
									<div data-format="d-m-y" class="bfh-datepicker"  data-name="birth_date" data-date="{{ isset($employee->birth_date)?$employee->birth_date_view:old('birth_date') }}"></div>								
								</div>
							</div>	

							<div class="form-group">
								<label class="col-md-4 control-label">Agama</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="religion" value="{{ $employee->religion ?? old( 'religion')}}">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-4 control-label">Golongan Darah</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="blood_type" value="{{ $employee->blood_type ?? old( 'religion')}}">
								</div>
							</div>	

							<div class="form-group">
								<label class="col-md-4 control-label">Alamat Tinggal</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="current_address" value="{{ $employee->current_address ?? old( 'current_address')}}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Alamat KTP</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="address" value="{{ $employee->address ?? old( 'address')}}">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-4 control-label">Status Pernikahan</label>
								<div class="col-md-6">
									{!! Form::select('marital_status', $choice['marital_status'], isset($employee->marital_status)?$employee->marital_status:0); !!}
								</div>
							</div>							
					
							<div class="form-group">
								<label class="col-md-4 control-label">Warga Negara</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="nationality" value="{{ $employee->nationality ?? old( 'nationality')}}">
								</div>
							</div>			

							<div class="form-group">
								<label class="col-md-4 control-label">Telepon Rumah</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="phone" value="{{ $employee->phone ?? old( 'phone')}}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Telepon Genggam</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="mobile_phone" value="{{ $employee->mobile_phone ?? old( 'mobile_phone')}}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Telepon Genggam 2</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="mobile_phone2" value="{{ $employee->mobile_phone2 ?? old( 'mobile_phone2')}}">
								</div>
							</div>							
					</div>
					<div class="panel-heading"><strong>Data Karyawan</strong></div>
					<div class="panel-body">
							
							<div class="form-group">
								<label class="col-md-4 control-label">Nomor Karyawan</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="employee_number" value="{{ $employee->employee_number ?? old( 'employee_number')}}">
								</div>
							</div>
														
							<div class="form-group">
								<label class="col-md-4 control-label">Status Karyawan</label>
								<div class="col-md-6">
									{!! Form::select('employee_status_id', $choice['employee_status'], isset($employee->employee_status_id)?$employee->employee_status_id:0); !!}
								</div>
							</div>	
							
							<div class="form-group">
								<label class="col-md-4 control-label">Tanggal Masuk</label>
								<div class="col-md-6">
									<div data-format="d-m-y" class="bfh-datepicker"  data-name="join_date" data-date="{{ isset($employee->join_date)?$employee->join_date_view:old('join_date') }}"></div>								
								</div>
							</div>		

							<div class="form-group" id="contractEndDate">
								<label class="col-md-4 control-label">Tanggal Akhir Kontrak</label>
								<div class="col-md-6">
									<div data-format="d-m-y" class="bfh-datepicker"  data-name="end_date" data-date="{{ isset($employee->end_date)?$employee->end_date_view:old('end_date') }}"></div>								
								</div>
							</div>		

							<div class="form-group">
								<label class="col-md-4 control-label">Tanggal Permanen</label>
								<div class="col-md-6">
									<div data-format="d-m-y" class="bfh-datepicker"  data-name="permanent_date" data-date="{{ isset($employee->permanent_date)?$employee->permanent_date_view:old('permanent_date') }}"></div>								
								</div>
							</div>		
							
							<div class="form-group">
								<label class="col-md-4 control-label">Perusahaan</label>
								<div class="col-md-6" id="companyChoice">
									@if(isset($organization['company']->id))
										{!! Form::select('company_id', $choice['company'], $organization['company']->id); !!}
									@else
										{!! Form::select('company_id', $choice['company'], null, ['placeholder' => 'Pilih']); !!}										
									@endif
								</div>
							</div>	

							<div class="form-group">
								<label class="col-md-4 control-label">Divisi</label>
								<div class="col-md-6" id="divChoice">
									@if(isset($choice['division']))
										@if(isset($organization['division']->id))
											{!! Form::select('division_id', $choice['division'], $organization['division']->id); !!}
										@elseif(!$choice['division']->isEmpty())
											{!! Form::select('division_id', $choice['division'], null, ['placeholder' => 'Pilih']); !!}										
										@else
											-
										@endif
									@else
										-
									@endif
								</div>
							</div>	

							<div class="form-group">
								<label class="col-md-4 control-label">Department</label>
								<div class="col-md-6" id="deptChoice">
									@if(isset($choice['department']))
										@if(isset($organization['department']->id))
											{!! Form::select('department_id', $choice['department'], $organization['department']->id); !!}
										@elseif(!$choice['department']->isEmpty())
											{!! Form::select('department_id', $choice['department'], null, ['placeholder' => 'Pilih']); !!}										
										@else
											-
										@endif
									@else
										-
									@endif
								</div>
							</div>	
							
							<div class="form-group">
								<label class="col-md-4 control-label">Sub Department</label>
								<div class="col-md-6" id="subDeptChoice">
									@if(isset($choice['subDepartment']))
										@if(isset($organization['subDepartment']->id))
											{!! Form::select('sub_department_id', $choice['subDepartment'], $organization['subDepartment']->id); !!}
										@elseif(!$choice['subDepartment']->isEmpty())
											{!! Form::select('sub_department_id', $choice['subDepartment'], null, ['placeholder' => 'Pilih']); !!}										
										@else
											-
										@endif
									@else
										-
									@endif
								</div>
							</div>	

							<div class="form-group">
								<label class="col-md-4 control-label">Posisi</label>
								<div class="col-md-6">
									{!! Form::select('job_id', $choice['job'], isset($employee->job_id)?$employee->job_id:null); !!}
								</div>
							</div>									

							<div class="form-group">
								<label class="col-md-4 control-label">Penempatan</label>
								<div class="col-md-6">
									{!! Form::select('location', $choice['location'], isset($employee->location)?$employee->location:null); !!}
								</div>
							</div>									

							<div class="form-group">
								<label class="col-md-4 control-label">Jenis Uang Makan</label>
								<div class="col-md-6">
									{!! Form::select('food_allowance_id', $choice['food_allowance'], isset($employee->food_allowance_id)?$employee->food_allowance_id:null); !!}
								</div>
							</div>									

							<div class="form-group">
								<label class="col-md-4 control-label">Deskripsi Pekerjaan</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="job_description" value="{{ $employee->job_description ?? old( 'job_description') }}">
								</div>
							</div>							

							<div class="form-group">
								<label class="col-md-4 control-label">Telepon Kantor</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="office_phone" value="{{ $employee->office_phone ?? old( 'office_phone')}}">
								</div>
							</div>									
							
							<div class="form-group">
								<label class="col-md-4 control-label">Nomer NPWP</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="tax_number" value="{{ $employee->tax_number ?? old( 'tax_number')}}">
								</div>
							</div>				

							<div class="form-group">
								<label class="col-md-4 control-label">Golongan Pajak</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="tax_status" value="{{ $employee->tax_status ?? old( 'tax_status')}}">
								</div>
							</div>								

							<div class="form-group">
								<label class="col-md-4 control-label">Nomer KTP</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="id_card_number" value="{{ $employee->id_card_number ?? old( 'id_card_number')}}">
								</div>
							</div>	

							<div class="form-group">
								<label class="col-md-4 control-label">Nomer Passport</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="passport_number" value="{{ $employee->passport_number ?? old( 'passport_number')}}">
								</div>
							</div>							

							<div class="form-group">
								<label class="col-md-4 control-label">Nomer BPJS Tenaga Kerja</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="bpjs_number" value="{{ $employee->bpjs_number ?? old( 'bpjs_number')}}">
								</div>
							</div>	

							<div class="form-group">
								<label class="col-md-4 control-label">Nomer BPJS Kesehatan</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="bpjs_health_number" value="{{ $employee->bpjs_health_number ?? old( 'bpjs_health_number')}}">
								</div>
							</div>	
							
							
							<div class="form-group">
								<label class="col-md-4 control-label">Fingerprint ID</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="fingerprint_id" value="{{ $employee->fingerprint_id ?? old( 'fingerprint_id')}}">
								</div>
							</div>		

							<div class="form-group">
								<div class="col-md-6 col-md-offset-4">
									<button type="submit" class="btn btn-primary">
										Update
									</button>
								</div>
							</div>								
					</div>
				</div>				
			</div>
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading"><strong>Foto Pegawai</strong></div>
					<div class="panel-body">					
						<div class="form-group">
							<div class="col-md-12" id="photoButton">
								@if(isset($employee) and $employee->photo_path)
									<a target="_blank" href="{{ url('view?'.'path='.$employee->photo_path.'&text='.$employee->name.' ('.$employee->employee_number.')') }}"><img class="img-responsive"  id="photoPreview" src="{{ secure_asset($employee->photo_path) }}"/></a>
								@else
									<img class="img-responsive"  id="photoPreview" src="{{ secure_asset('/img/no-photo.png') }}"/>
								@endif
								<span class="btn btn-default btn-file">
									Pilih Foto<input type="file" name="photo">
								</span>
							</div>
						</div>		
					</div>
				</div>			
				<div class="panel panel-default">
					<div class="panel-heading"><strong>Catatan Tambahan</strong></div>
					<div class="panel-body">					
						<div class="form-group">
							<textarea class="form-control" rows="3" name="remark" >{{ $employee->remark ?? '' }}</textarea>
						</div>		
					</div>
				</div>			
				@if(isset($employee))
				<div class="panel panel-default">
					<div class="panel-heading"><strong>Dokumen</strong></div>
					<div class="panel-body">					
						<div id="documentButton">
							@for ($i = 1; $i <= 10; $i++)
								<div class="form-group">
									<div class="col-md-12">
										@if(isset($employeeDocuments[$i-1]))
											<input type="hidden" id="docId{{ $i }}"  name="docId{{ $i }}" value="{{ $employeeDocuments[$i-1]->id }}">
											<a target="_blank" href="{{ url('view?'.'path='.$employeeDocuments[$i-1]->path.'&text='.$employeeDocuments[$i-1]->description) }}"><img class="img-responsive"  id="docPreview{{ $i }}" src="{{ secure_asset($employeeDocuments[$i-1]->path) }}"/></a>
										@else
											<img class="img-responsive"  id="docPreview{{ $i }}" src=""/>
										@endif
										<span class="btn btn-default btn-file">
											Pilih Dokumen {{ $i }}<input type="file" data-index="{{ $i }}" name="document{{ $i }}">
										</span> 
										
										@if(isset($employeeDocuments[$i-1]))
										<a href="#" class="btn btn-danger" data-action="delete" data-index="{{ $i }}" document-id="{{ $employeeDocuments[$i-1]->id }}" onClick="return confirm('Anda yakin menghapus dokumen?')"><span class="glyphicon glyphicon-trash" ></span> Hapus</a>
										@endif									
									</div>
									<div class="col-md-12">
										<input type="text" class="form-control" id="docDescription{{ $i }}" name="docDescription{{ $i }}" value="{{ isset($employeeDocuments[$i-1])?$employeeDocuments[$i-1]->description:'' }}" placeholder="Keterangan dokumen {{ $i }}">
									</div>
								</div>										
							@endfor
						</div>		
					</div>
				</div>				
				@endif
			</div>
			
			
		</div>
	</form>
</div>
<div class="panel-body">					
	<div class="form-group">
		<div class="col-md-12" id="photoButton">
			@if(isset($employee) and $employee->photo_path)
				<a target="_blank" href="{{ url('view?'.'path='.$employee->photo_path.'&text='.$employee->name.' ('.$employee->employee_number.')') }}"><img class="img-responsive"  id="photoPreview" src="{{ secure_asset($employee->photo_path) }}"/></a>
			@else
				<img class="img-responsive"  id="photoPreview" src="{{ secure_asset('/img/no-photo.png') }}"/>
			@endif
			<span class="btn btn-default btn-file">
				Pilih Foto<input type="file" name="photo">
			</span>
		</div>
	</div>		
</div>

<script>	
// <<<<< image preview
    function readURL(input, previewContainer) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#' + previewContainer).attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
	$('#photoButton').on('change', '.btn-file :file', function() {
		readURL(this, 'photoPreview');
	});

	$('#documentButton').on('change', '.btn-file :file', function() {
		var this_index = $(this).attr('data-index');
		readURL(this, 'docPreview' + this_index);
	});	

	$('#documentButton').on('click', 'a', function(){
		var this_action = $(this).attr('data-action');
		var this_index = $(this).attr('data-index');
		if(this_action == 'delete'){
			$(this).hide();
			var document_id = $(this).attr('document-id');
			deleteDocument(this_index, document_id);
		}
	});
// >>>>> image preview	
	
	function deleteDocument(index, docId)
	{
		$.ajax({
		  url: baseURL + "/api/employee/document/delete/" + docId,
		  type: "get", //send method (post or test)
		  data:{},
		  success: function(data) {
			$('#docPreview' + index).attr('src', '');
			$('#docDescription' + index).attr('value', '');	
			$('#docId' + index).attr('value', '');	
		  },
		  
		  error: function(xhr) {
			console.log(xhr);
		  }
		});			
	}
	function getDepartmentChoice(selectId, deptId)
	{	
		if(selectId === 'division_id')
		{			
			if(deptId)
			{
				$('#divChoice').html('<img src="'+ baseURL +'/img/load.gif">');
			}
			else
			{
				$('#divChoice').html('-');
			}
			$('#deptChoice').html('-');			
			$('#subDeptChoice').html('-');			
		}
		else if(selectId === 'department_id')
		{		
			if(deptId)
			{	
				$('#deptChoice').html('<img src="'+ baseURL +'/img/load.gif">');			
			}
			else
			{
				$('#deptChoice').html('-');							
			}
			$('#subDeptChoice').html('-');	
		}
		else if(selectId === 'sub_department_id')
		{		
			if(deptId)
			{	
				$('#subDeptChoice').html('<img src="'+ baseURL +'/img/load.gif">');			
			}
			else
			{
				$('#subDeptChoice').html('-');							
			}
		}
		
		$.ajax({
		  url: baseURL + "/api/department/choice/" + selectId + "/" + deptId,
		  type: "get", //send method (post or test)
		  data:{},
		  success: function(data) {
			  //console.log(data);
			  if(selectId === 'division_id')
			  {
				  if(data)
				  {
					$('#divChoice').html(data);
				  }
				  else
				  {
					$('#divChoice').html('-');					 
				  }
				  $('#deptChoice').html('-');
				  $('#subDeptChoice').html('-');						  
			  }
			  else if(selectId === 'department_id')
			  {
				if(data)
				{
				  $('#deptChoice').html(data);				  					
				}
				else
				{
				  $('#deptChoice').html('-');				  										
				}
				$('#subDeptChoice').html('-');
			  }
			  else if(selectId === 'sub_department_id')
			  {
				if(data)
				{
				  $('#subDeptChoice').html(data);				  					
				}
				else
				{
				  $('#subDeptChoice').html('-');				  										
				}				  
			  }
			
		  },
		  error: function(xhr) {
			console.log(xhr);
		  }
		});	
	}

	$('#deptChoice').on('change', 'select', function (e) {
		getDepartmentChoice('sub_department_id', this.value);
	});	
	
	$('#divChoice').on('change', 'select', function (e) {
		getDepartmentChoice('department_id', this.value);
	});	
	
	$('#companyChoice').on('change', 'select', function (e) {
		getDepartmentChoice('division_id', this.value);
	});	

</script>
							
@endsection
