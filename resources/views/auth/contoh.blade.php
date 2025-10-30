@extends('app')

@section('title')
{{$title}}
@endsection

@section('content')		
<div class="container-fluid">
@if(isset($employee))
@if(Auth::user()->isAdmin() or Auth::user()->isHRD())					
	<div class="row">
		<div style="padding: 4px;">
			<a title="Lihat data lengkap pelamar" href="{{ url('employee/profile').'/'.$employee->user_id }}" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-search"></span> Lihat Detail Pelamar</a>
			<a title="Ubah data pelamar" href="{{ url('employee/edit').'/'.$employee->user_id }}" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-edit"></i> Ubah Data Pelamar</a>
		</div>
	</div>
	<form id="myForm" role="form" data-toggle="validator" class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="{{ url('/employee/edit/'.$user->id) }}">
@else
	<form id="myForm" role="form" data-toggle="validator" class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="{{ url('/my/edit') }}">	
@endif
@else
	<form id="myForm" role="form" data-toggle="validator" class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="{{ url('/employee/create') }}">	
@endif
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<div class="row">
			<div class="col-md-8">
				<div class="panel panel-default">
@if(Auth::user()->isAdmin() or Auth::user()->isHRD())				
					<div class="panel-heading"><strong>Data Login</strong></div>
					<div class="panel-body">
							@if(isset($employee))								
								<input type="hidden" name="user_id" value="{{ $employee->user_id }}">
							@endif
							<div class="form-group">
								<label class="col-md-4 control-label">Username</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="username" value="{{ $user->username ?? old( 'username') }}">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-4 control-label">E-Mail</label>
								<div class="col-md-6">
									<input type="email" class="form-control"  name="email" value="{{ $user->email ?? old( 'username')}}">
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
									<input  type="password" class="form-control" name="password">
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
@endif					
					<div class="panel-heading"><strong>Data Personal</strong></div>
					<div class="panel-body">
							<div class="form-group">
								<label class="col-md-4 control-label">* Nama</label>
								<div class="col-md-6">
									<input required data-error="Kolom ini harus diisi." maxlength=50 type="text" class="form-control" name="name" value="{{ $employee->name ?? old( 'name') }}">
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">Nama Panggilan</label>
								<div class="col-md-6">
									<input type="text" class="form-control" maxlength=20 name="nickname" value="{{ $employee->nickname ?? old( 'nickname') }}">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-4 control-label">* Jenis Kelamin</label>
								<div class="col-md-6">								
									{!! Form::select('sex', $choice['sex'], isset($employee->sex)?$employee->sex:null); !!}
								</div>
							</div>	
							
							<div class="form-group">
								<label class="col-md-4 control-label">* Tempat Lahir</label>
								<div class="col-md-6">
									<input required data-error="Kolom ini harus diisi." type="text" maxlength=255 class="form-control" name="birth_place" value="{{ $employee->birth_place ?? old( 'birth_place') }}">
									<div class="help-block with-errors"></div>
								</div>
							</div>					
							
							<div class="form-group">
								<label class="col-md-4 control-label">* Tanggal Lahir</label>
								<div class="col-md-6">
									<div data-format="d-m-y" class="bfh-datepicker"  data-name="birth_date" data-date="{{ isset($employee->birth_date)?$employee->birth_date_view:'01-01-1991' }}"></div>								
								</div>
							</div>	

							<div class="form-group">
								<label class="col-md-4 control-label">* Agama</label>
								<div class="col-md-6">
									<input required data-error="Kolom ini harus diisi." type="text" maxlength=255 class="form-control" name="religion" value="{{ $employee->religion ?? old( 'religion')}}">
									<div class="help-block with-errors"></div>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-4 control-label">Golongan Darah</label>
								<div class="col-md-6">
									<input type="text" maxlength=3 class="form-control" name="blood_type" value="{{ $employee->blood_type ?? old( 'religion')}}">
								</div>
							</div>	

							<div class="form-group">
								<label class="col-md-4 control-label">* Alamat Tinggal</label>
								<div class="col-md-6">
									<input required data-error="Kolom ini harus diisi." type="text" maxlength=255 class="form-control" name="current_address" value="{{ $employee->current_address ?? old( 'current_address')}}">
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">* Alamat KTP</label>
								<div class="col-md-6">
									<input required data-error="Kolom ini harus diisi." type="text" maxlength=255 class="form-control" name="address" value="{{ $employee->address ?? old( 'address')}}">
									<div class="help-block with-errors"></div>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-4 control-label">* Status Pernikahan</label>
								<div class="col-md-6">
									{!! Form::select('marital_status', $choice['marital_status'], isset($employee->marital_status)?$employee->marital_status:null); !!}
								</div>
							</div>							
					
							<div class="form-group">
								<label class="col-md-4 control-label">* Warga Negara</label>
								<div class="col-md-6">
									<input required data-error="Kolom ini harus diisi." type="text"  maxlength=255 class="form-control" name="nationality" value="{{ $employee->nationality ?? old( 'nationality')}}">
									<div class="help-block with-errors"></div>
								</div>
							</div>			

							<div class="form-group">
								<label class="col-md-4 control-label">Telepon Rumah</label>
								<div class="col-md-6">
									<input type="number" class="form-control" maxlength=255 name="phone" value="{{ $employee->phone ?? old( 'phone')}}">
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Telepon Genggam</label>
								<div class="col-md-6">
									<input type="number" class="form-control" maxlength=255 name="mobile_phone" value="{{ $employee->mobile_phone ?? old( 'mobile_phone')}}">
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-4 control-label">Telepon Genggam 2</label>
								<div class="col-md-6">
									<input type="number" class="form-control" maxlength=255 name="mobile_phone2" value="{{ $employee->mobile_phone2 ?? old( 'mobile_phone2')}}">
									<div class="help-block with-errors"></div>
								</div>
							</div>		
							<div class="form-group">
								<label class="col-md-4 control-label">* Tipe Identitas</label>
								<div class="col-md-6">
									{!! Form::select('id_card_type', $choice['id_type'], null); !!}
								</div>
							</div>								
							<div class="form-group">
								<label class="col-md-4 control-label">* Nomer Identitas</label>
								<div class="col-md-6">
									<input required data-error="Kolom ini harus diisi." type="text" maxlength=255 class="form-control" name="id_card_number" value="{{ $employee->id_card_number ?? old( 'id_card_number')}}">
									<div class="help-block with-errors"></div>
								</div>
							</div>							
					</div>
					<div class="panel-heading"><strong>Pengalaman Organisasi</strong></div>
					<div class="panel-body">
						<div class="form-group">
							<div class="col-md-12">
								<textarea class="form-control" rows="4" maxlength="2000" name="organization_experience">{{ $employee->organization_experience ?? '' }}</textarea>
							</div>
						</div>			
					</div>
					<div class="panel-heading"><strong>Penempatan Kerja</strong></div>
					<div class="panel-body">
						<div class="form-group">
							<label class="col-md-4 control-label">* Preferensi Penempatan</label>
							<div class="col-md-6">
								{!! Form::select('placement_id', $choice['placement'], isset($employee->placement_id)?$employee->placement_id:null); !!}
							</div>
						</div>			
					</div>		
					<div class="panel-heading"><strong>Pendidikan Formal</strong></div>
					<div class="panel-body">
							<div class="form-group">
								<label class="col-md-4 control-label">* Pendidikan Terakhir</label>
								<div class="col-md-6">
									{!! Form::select('education_degree_id', $choice['education_degree'], isset($employee->education_degree_id)?$employee->education_degree_id:null, ['placeholder' => 'Pilih', 'id' => 'educationId']); !!}
								</div>
							</div>			
							<div id="high_school" style="display:none">
								<div class="form-group">
									<label class="col-md-4 control-label">* Nama Sekolah</label>
									<div class="col-md-6">
										<input  type="text" maxlength=200 class="form-control" name="high_school_name" value="{{ $employee->high_school_name ?? old( 'high_school_name')}}">
									</div>
								</div>
							</div>
							
							<div id="college" style="display:none">
								<div class="form-group" >
									<label class="col-md-4 control-label">* Nama Kota</label>
									<div class="col-md-6">
										{!! Form::select('city_id', $choice['city'], isset($employee->college->city_id)?$employee->college->city_id:null, ['placeholder' => 'Pilih', 'id' => 'cityId']); !!}
									</div>
								</div>							
								<div class="form-group" id="collegeChoice">
									<label class="col-md-4 control-label">* Perguruan Tinggi</label>
									<div class="col-md-6">
										{!! Form::select('college_id', $choice['college'], isset($employee->college_id)?$employee->college_id:null, ['placeholder' => 'Pilih', 'id' => 'collegeId']); !!}
									</div>
								</div>
								<div class="form-group" id="collegeInput">
									<label class="col-md-4 control-label">* Perguruan Tinggi</label>
									<div class="col-md-6">
										<input  type="text" maxlength=200 class="form-control" name="college_name" value="{{ $employee->college_name ?? old( 'college_name')}}">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">* Jurusan</label>
									<div class="col-md-6">
										{!! Form::select('education_major_id', $choice['education_major'], isset($employee->education_major_id)?$employee->education_major_id:null, ['placeholder' => 'Pilih']); !!}
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-4 control-label">* IPK (0-4)</label>
									<div class="col-md-6">
										<input type="number" step=0.01 min=0 max=4 class="form-control" name="gpa" value="{{ $employee->gpa ?? old( 'gpa')}}">
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>
							
							<div id="school_detail" style="display:none">
							<div class="form-group">
								<label class="col-md-4 control-label">* Tanggal Masuk</label>
								<div class="col-md-6">
									<div data-format="d-m-y" class="bfh-datepicker"  data-name="enroll_date" data-date="{{ isset($employee->enroll_date)?$employee->enroll_date_view:old('enroll_date') }}"></div>								
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-4 control-label">* Tanggal Lulus</label>
								<div class="col-md-6">
									<div data-format="d-m-y" class="bfh-datepicker"  data-name="graduation_date" data-date="{{ isset($employee->graduation_date)?$employee->graduation_date_view:old('graduation_date') }}"></div>								
								</div>
							</div>
							</div>		
							<div class="form-group">
								<label class="col-md-4 control-label">TOEFL (bila ada)</label>
								<div class="col-md-6">
									<input type="number" min=0 max=1000  class="form-control" name="toefl_score" value="{{ $employee->toefl_score ?? old( 'toefl_score')}}">
									<div class="help-block with-errors"></div>
								</div>
							</div>							
							<div class="form-group">
								<div class="col-md-12">
									* Wajib diisi
								</div>
							</div>							
					</div>
				<div class="panel panel-default">				
					<div class="panel-heading"><strong>Pengalaman Kerja</strong></div>
					<div class="panel-body">
						<div id="workExperience">
							@for ($i = 1; $i <= 5; $i++)						
							<div class="row"
								@if($i<=$employeeWorks->count() or $i<=1)
									id="work{{ $i }}"
								@else
									id="work{{ $i }}"
									style="display:none;"								
								@endif
							>
								@if(isset($employeeWorks[$i-1]))
									<input type="hidden" id="workId{{ $i }}"  name="workId{{ $i }}" value="{{ $employeeWorks[$i-1]->id }}">
								@endif							
								<div class="col-md-12">
									<p><strong>Pengalaman Kerja ({{ $i }})</strong></p>
								</div>
								<div class="col-md-3">
									<input type="text" class="form-control" maxlength=100 name="workCompany{{ $i }}" value="{{ isset($employeeWorks[$i-1])?$employeeWorks[$i-1]->company:'' }}" placeholder="Perusahaan">
									<div class="help-block with-errors">Perusahaan</div>
								</div>
								<div class="col-md-3">
									<input type="text" class="form-control" maxlength=50 name="workPosition{{ $i }}" value="{{ isset($employeeWorks[$i-1])?$employeeWorks[$i-1]->position:'' }}" placeholder="Posisi">
									<div class="help-block with-errors">Posisi</div>
								</div>
								<div class="col-md-3">
									<div data-format="d-m-y" class="bfh-datepicker"  data-name="workStartDate{{ $i }}" data-date="{{ isset($employeeWorks[$i-1])?$employeeWorks[$i-1]->start_date_view:'today' }}"></div>								
									<div class="help-block">Tanggal Masuk</div>
								</div>
								<div class="col-md-3">
									<div data-format="d-m-y" class="bfh-datepicker"  data-name="workEndDate{{ $i }}" data-date="{{ isset($employeeWorks[$i-1])?$employeeWorks[$i-1]->end_date_view:'today' }}"></div>								
									<div class="help-block">Tanggal Keluar</div>
								</div>						
							</div>	
							@endfor							
						</div>
						<div class="row" id="workExperienceButton">
							<div class="col-md-12 text-right">
								<a class="btn btn-warning btn-xs"> + Tambah Kolom</a>
							</div>
						</div>		
						<div class="form-group">
							<div class="col-md-3 ">
								<button type="submit" class="btn btn-primary">
									Simpan Data
								</button>
							</div>
						</div>	
						
					</div>						
				</div>					
				</div>				
			</div>
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading"><strong>* Foto</strong></div>
					<div class="panel-body">					
						<div class="form-group">
							<div class="col-md-12" id="photoButton">
								@if(isset($employee) and $employee->photo_path)
									<a target="_blank" href="{{ url('view?'.'path='.$employee->photo_path.'&text='.$employee->name) }}"><img class="img-responsive"  id="photoPreview" src="{{ secure_asset($employee->photo_path) }}"/></a>
								@else
									<img class="img-responsive"  id="photoPreview" src="{{ secure_asset('/img/no-photo.png') }}"/>
								@endif
								<span class="btn btn-default btn-file">
									Upload Foto<input type="file" name="photo">
								</span>
							</div>
						</div>		
					</div>
				</div>	
@if(Auth::user()->isAdmin() or Auth::user()->isHRD())					
				<div class="panel panel-default">
					<div class="panel-heading"><strong>Catatan Tambahan</strong></div>
					<div class="panel-body">					
						<div class="form-group">
							<textarea class="form-control" rows="3" name="remark" >{{ $employee->remark ?? '' }}</textarea>
						</div>		
					</div>
				</div>			
@endif				
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
											Upload Scan Dokumen {{ $i }}<input type="file" data-index="{{ $i }}" name="document{{ $i }}">
										</span> 
										
										@if(isset($employeeDocuments[$i-1]))
										<a href="#" class="btn btn-danger" data-action="delete" data-index="{{ $i }}" document-id="{{ $employeeDocuments[$i-1]->id }}" onClick="return confirm('Anda yakin menghapus dokumen?')"><span class="glyphicon glyphicon-trash" ></span> Hapus</a>
										@endif									
									</div>
									<div class="col-md-12">
										@if($i==1)									
										<input type="text" class="form-control" id="docDescription{{ $i }}" name="docDescription{{ $i }}" value="{{ isset($employeeDocuments[$i-1])?$employeeDocuments[$i-1]->description:'' }}" placeholder="KTP/SIM/Paspor">
										@elseif($i==2)
										<input type="text" class="form-control" id="docDescription{{ $i }}" name="docDescription{{ $i }}" value="{{ isset($employeeDocuments[$i-1])?$employeeDocuments[$i-1]->description:'' }}" placeholder="Ijazah">										
										@else
										<input type="text" class="form-control" id="docDescription{{ $i }}" name="docDescription{{ $i }}" value="{{ isset($employeeDocuments[$i-1])?$employeeDocuments[$i-1]->description:'' }}" placeholder="Dokumen Pelengkap">											
										@endif
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

<script>	
	var work = {{ $employeeWorks->count()>0?$employeeWorks->count():1 }} + 1;
	$(document).ready(function(){
		var educationId = $("#educationId").val();
		if(educationId == 1)
		{
			$("#high_school").show();			
			$("#school_detail").show();	
			$("#college").hide();
		}
		else if(educationId == "")
		{
			$("#high_school").hide();			
			$("#college").hide();	
			$("#school_detail").hide();	
		}		
		else
		{
			$("#high_school").hide();			
			$("#college").show();
			$("#school_detail").show();				
			$("#collegeInput").hide();				
		}
	});//on ready
	
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

	(function($, window) {
	  $.fn.replaceOptions = function(options) {
		var self, $option;

		this.empty();
		self = this;

		$.each(options, function(index, option) {
			$option = $("<option></option>")
			.attr("value", option.id)
			.text(option.name);
			self.append($option);
		});
	  };
	})(jQuery, window);	

	$("#cityId").change(function () 
	{
		var cityId = this.value;
		if(cityId == 246)
		{
			$("#collegeInput").show();			
			$("#collegeChoice").hide();			
		}
		else
		{
			$("#collegeInput").hide();			
			$("#collegeChoice").show();			
			$.ajax({
			  url: baseURL + "/api/college/choice/" + cityId,
			  success: function(data) {
					$("#collegeId").replaceOptions(data);
			  },
			  error: function(xhr) {
					console.log(xhr);
			  }
			});
		}		
    });
	
	$("#educationId").on('change', function (e) 
	{
		$("#educationId").removeAttr('placeholder');
		
		if(this.value == 1)
		{
			$("#high_school").show();			
			$("#school_detail").show();	
			$("#college").hide();
		}
		else if(this.value == "")
		{
			$("#high_school").hide();			
			$("#college").hide();	
			$("#school_detail").hide();	
		}		
		else
		{
			$("#high_school").hide();			
			$("#college").show();
			$("#school_detail").show();				
		}
    });	
	
	$('#workExperienceButton').on('click', 'a', function(){
		$('#work' + work).show();
		work++;
	});	
</script>
							
@endsection
