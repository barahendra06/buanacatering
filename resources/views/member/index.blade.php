@extends('layouts.app')

@section('htmlheader_title')
    {{ $title ?? '' }}
@endsection

@section('contentheader_title')
    {{  $title  ?? '' }}
@endsection

@section('contentheader_description')
    {{ $title_description ?? '' }}
@endsection

@section('main-content')
<div class="container-fluid">
	<div class="row">
		<div class="panel panel-default">	
			<div class="panel-heading"><strong>Pencarian Lanjut</strong></div>
			<div class="panel-body">	
				<form id="myForm" class="form-horizontal" role="form" method="POST" enctype="multipart/form-data" action="{{ route('member-list') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="table-responsive">          
						<table class="table">
						<tr>
							@if(0 && $currentMember->user->isAdmin())
								<td>Province</td>
								<td>
									@if(isset($param['province_id']))
										{!! Form::select('province_id', $provinces, $param['province_id'], ['placeholder' => 'All']); !!}
									@else
										{!! Form::select('province_id', $provinces, null, ['placeholder' => 'All']); !!}									
									@endif
								</td>
							@endif
							<td>Gender</td>
							<td>
								@if(isset($param['gender']))
									{!! Form::select('gender', $gender, $param['gender'], ['placeholder' => 'All']); !!}
								@else
									{!! Form::select('gender', $gender, null, ['placeholder' => 'All']); !!}										
								@endif					
							</td>
							{{-- <td>Education</td>
							<td>
								@if(isset($param['education_type_id']))
									{!! Form::select('education_type_id', $educations, $param['education_type_id'], ['placeholder' => 'All']); !!}
								@else
									{!! Form::select('education_type_id', $educations, null, ['placeholder' => 'All']); !!}									
								@endif
							</td> --}}
						</tr>
						<tr>	

							<td>Join Date</td>
							<td>
								@if(isset($param['join']))
									{!! Form::select('join', $join, $param['join'],['id' => 'joinDate']); !!}
								@elseif(isset($param['custom']))
									{!! Form::select('join', $join, $param['join'],['id' => 'joinDate']); !!}
								@else
									{!! Form::select('join', $join, null,['id' => 'joinDate']); !!}
								@endif
                            	<div id="startDate" class="input-group width-initial margin-top-5">
                                    <div class="input-group-addon width-initial">
                                        Start
                                    </div>
                                    <input class="form-control datepicker" placeholder="Click here..." name="startDate" required 
                                    		value="@if(isset($param['startDate'])){{ $param['startDate']->format('d-m-Y') }}@else{{\Carbon\Carbon::now()->format('d-m-Y')}}@endif" />
                                </div>
                            	<div id="endDate" class="input-group width-initial margin-top-5">
                                    <div class="input-group-addon width-initial">
                                        End&nbsp;&nbsp;
                                    </div>
                                    <input class="form-control datepicker" placeholder="Click here..." name="endDate" required 
                                    		value="@if(isset($param['endDate'])){{ $param['endDate']->format('d-m-Y') }}@else{{\Carbon\Carbon::now()->format('d-m-Y')}}@endif" />
                                </div>

							</td>
							<td>
							<button type="submit" class="btn btn-primary btn-flat"><span class="glyphicon glyphicon-search"></span> Cari</button> 
							</td>
							<td>
							<input type='hidden' value='0' name='active'>
															
							</td>								
							<td></td>						
							<td>
								
							</td>
							<td></td>						
							<td>
								
							</td>
							<td></td>
							<td>
								
							</td>							
						</tr>
						</table>
					</div>
				</form>
			</div>	
		</div>	
	</div>
	<div class="row">
		<div class="panel panel-default">	
			<div class="panel-heading"><strong>Search Result ({{ $summary['total'] ?? '0' }} Members)</strong></div>
			<div class="panel-body">							
				<div class="table-responsive">          
					<table class="table">
						<tr>
							@if($summary['total']!=0)
								<td>
								Male : {{ $summary['male'] }} ({{ number_format((float)$summary['male']/$summary['total']*100, 2, '.', '') }}%)<span id="male"></span><span id="male-percent"></span><br/>
								Female : {{ $summary['female'] }} ({{ number_format((float)$summary['female']/$summary['total']*100, 2, '.', '') }}%)<span id="female"></span><span id="female-percent"></span> 
								</td>
								<td>
								SMP : {{ $summary['SMP'] }} ({{ number_format((float)$summary['SMP']/$summary['total']*100, 2, '.', '') }}%)<span id="grade-smp"></span><span id="grade-smp-percent"></span> <br/>
								SMA : {{ $summary['SMA'] }} ({{ number_format((float)$summary['SMA']/$summary['total']*100, 2, '.', '') }}%)<span id="grade-sma"></span><span id="grade-sma-percent"></span> <br/>
								Kuliah : {{ $summary['Kuliah'] }} ({{ number_format((float)$summary['Kuliah']/$summary['total']*100, 2, '.', '') }}%)<span id="grade-kuliah"></span><span id="grade-kuliah-percent"></span> 								
								</td>
								<td>
								Age {{ MINIMUM_AGE }}-15 : {{ $summary['age1'] }} ({{ number_format((float)$summary['age1']/$summary['total']*100, 2, '.', '') }}%)<span id="age-1"></span><span id="age-1-percent"></span><br/>
								Age 16-18 : {{ $summary['age2'] }} ({{ number_format((float)$summary['age2']/$summary['total']*100, 2, '.', '') }}%)<span id="age-2"></span><span id="age-2-percent"></span> <br/> 
								Age 19-{{ (MAXIMUM_AGE-1) }} : {{ $summary['age3'] }} ({{ number_format((float)$summary['age3']/$summary['total']*100, 2, '.', '') }}%)<span id="age-3"></span><span id="age-3-percent"></span><br/>
								Age   >{{ (MAXIMUM_AGE-1) }} : {{ $summary['age4'] }} ({{ number_format((float)$summary['age4']/$summary['total']*100, 2, '.', '') }}%)<span id="age-4"></span><span id="age-4-percent"></span> 			 								
								</td>
							@else
								<td>
								Male : 0 (0%)<span id="male"></span><span id="male-percent"></span><br/>
								Female : 0 (0%)<span id="female"></span><span id="female-percent"></span> 
								</td>
								<td>
								SMP : 0 (0%)<span id="grade-smp"></span><span id="grade-smp-percent"></span> <br/>
								SMA : 0 (0%)<span id="grade-sma"></span><span id="grade-sma-percent"></span> <br/>
								Kuliah : 0 (0%)<span id="grade-kuliah"></span><span id="grade-kuliah-percent"></span> 								
								</td>
								<td>
								Age {{ MINIMUM_AGE }}-15 : 0 (0%)<span id="age-1"></span><span id="age-1-percent"></span><br/>
								Age 16-18 : 0 (0%)<span id="age-2"></span><span id="age-2-percent"></span> <br/> 
								Age 19-{{ (MAXIMUM_AGE-1) }} : 0 (0%)<span id="age-3"></span><span id="age-3-percent"></span><br>
								Age   >{{ (MAXIMUM_AGE-1) }} : 0 (0%)<span id="age-3"></span><span id="age-3-percent"></span> 								
								</td>
							@endif
						</tr>

						@if($currentMember->user->isAdmin())
						<tr>
							<td>
								<form action="{{ route('member-download') }}" method="get">
		                            {!! Form::select('month', array_combine(range(1,12,1), range(1,12,1)),null,['placeholder'=>'Month','id'=>'monthExcel','class'=>'excelDate']) !!}
		                            {!! Form::select('year', array_combine(range(2016,\Carbon\Carbon::now()->year,1), range(2016,\Carbon\Carbon::now()->year,1)),null,['placeholder'=>'Year','id'=>'yearExcel','class'=>'excelDate']) !!}
		                            <button type="submit" class="btn btn-success btn-xs btn-flat" id="excelLink">
		                            	<span class="glyphicon glyphicon-download-alt"></span> Download File Excel
	                            	</button>	
								</form>				
							</td>											
							<td>
								
							</td>
							<td>
								
							</td>
						</tr>						
						@endif
					</table>
				</div>					
			</div>	
		</div>	
	</div>		
	<div class="row">
		<div class="panel panel-default">	
			<div class="panel-heading"><strong>{{ $title ?? '' }}</strong></div>
			<div class="panel-body">
				<table id="member-table" class="display" cellspacing="0" width="100%">
			        <thead>
			            <tr>
			                <th class="text-center">ID</th>
							<th class="text-center">Name</th>
							<th class="text-center">Email</th>
			               			<th class="text-center">FB</th>
							<th class="text-center">Education</th>
							<th class="text-center">Province</th>
							<th class="text-center">City</th>
							<th class="text-center">Age</th>
							<th class="text-center">Status</th>
							<th class="text-center">Poin</th>
							<th class="text-center">Join</th>
					
			            </tr>
			        </thead>
			    </table>	
			</div>
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
			  <h4><span class="glyphicon glyphicon-user"></span> Data Member</h4>
			</div>
			<div class="modal-body load_modal" ></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

@endsection

@section('content-script')
<script>
	$(document).ready(function(){

		$.fn.dataTable.ext.errMode = 'none';	//alief.mf 2015102 remove unnecessary warning message from datatables
		$('#member-table').DataTable({
			paging:   true,
			responsive: true,			
			order: [],
		});

		if($("#joinDate").val()!='custom')
		{
			$('#startDate').hide();
			$('#endDate').hide();	
			$('#startDate').attr("disabled", true);
			$('#endDate').attr("disabled", true);
		}
	});//on ready

	$('#joinDate').change(function()
	{
		if($(this).val() == 'custom')
		{
			$('#startDate').show('slow');
			$('#endDate').show('slow');	
			$('#startDate').removeAttr("disabled");	
			$('#endDate').removeAttr("disabled");	
		}
		else
		{
			$('#startDate').hide('slow');
			$('#endDate').hide('slow');	
			$('#startDate').attr("disabled", true);
			$('#endDate').attr("disabled", true);	
		}
	});

	$('#member-table').on('xhr.dt', function ( e, settings, json, xhr ) {

		}).DataTable({
            bProcessing: true,
            bServerSide: true,
            responsive: true, 
            ajax:
            {
                url : 'list/data',    
                dataSrc: 'data',
                data: function(data){
                	@if(isset($summary["total"]))data.total = {{ $summary['total'] }};@endif
                	@if(isset($param["gender"]))data.gender = '{{ $param["gender"] }}';@endif
                	@if(isset($param["province_id"]))data.province_id = '{{ $param["province_id"] }}';@endif
                	@if(isset($param["join"]))
                		data.join = '{{ $param["join"] }}';
                		@if($param["join"]=='custom')data.startDate='{{ $param["startDate"] }}';data.endDate='{{ $param["endDate"] }}';@endif
                	@endif
                	@if(isset($param["education_type_id"]))data.education_type_id = '{{ $param["education_type_id"] }}';@endif
                }
            },
            aoColumns: 
            [
                { mData: 'id', sType: "numeric", "name": "user.id" },
                { 
                	mData: 'name', sType: "string", "name": "member.name",
                	mRender : function(data, type, full)
                	{
                		return '<a data-id='+full['member_id']+' style="cursor:pointer" data-action="show">' + data + '</a>';
                	}
                },
                { mData: 'email', sType: "string", "name": "user.email" },
                { 
                	mData: 'facebook_id', 
                	mRender: function(data, type, full) 
                	{
                		if(data)
                		{
                			return '<i class="fa fa-check" aria-hidden="true" style="color:blue"></i>';
                		}
                		else
                		{
                			return '<i class="fa fa-times" aria-hidden="true" style="color:red"></i>'
                		}
                	},
                    className: "dt-body-center" , "name": "user.facebook_id"
                },
				{ 
					mData: 'education', sType: "string",
                    className: "dt-body-center", "name": "education_type.education" },
                { 
                	mData: 'province', sType: "string",
                    className: "dt-body-center" , "name": "province.name"
                },
                { 
                	mData: 'city', sType: "string",
                    className: "dt-body-center", "name": "member.city"
                },
				{ 
					mData: 'age', sType: "date",
                    className: "dt-body-center" , "name": "age"
                },
				@if($currentMember->user->isAdmin())
                { 	
                	mData: 'status',
                	mRender: function(data, type, full) 
                	{
						
						
						var status ='<span id="status'+full["id"]+'">'+data+
									'</span>'+
									'<center>'+
										'<a data-id="'+full["id"]+'" data-action="active" class="btn btn-success btn-xs btn-flat">Active</a>'+
										'<a data-id="'+full["id"]+'" data-action="inactive" class="btn btn-danger btn-xs btn-flat">Inactive</a>'+								
									'</center>';
						return status;
                	},
                    className: "dt-body-center", "name": "status"
                },
                @endif
                { 
                	mData: 'poin', sType: "numeric",
                    className: "dt-body-center" , "name": "poin"
                },
				{ 
					mData: 'join', sType: "date",
                    className: "dt-body-center", "name": "join" 
                }
            ],
            columnDefs: [
		@if($currentMember->user->isAdmin())
                { "targets": 0,"searchable": true, "orderable": true },
                { "targets": 1,"searchable": true, "orderable": true },
                { "targets": 2,"searchable": true, "orderable": true },
                { "targets": 3,"searchable": false, "orderable": true },
                { "targets": 4,"searchable": false, "orderable": true  },
                { "targets": 5,"searchable": false, "orderable": true  },
                { "targets": 6,"searchable": true, "orderable": true  },
                { "targets": 7,"searchable": false, "orderable": true  },
                { "targets": 8,"searchable": false, "orderable": true  },
                { "targets": 9,"searchable": false, "orderable": true  },
                { "targets": 10,"searchable": false, "orderable": true  }
                @else
                { "targets": 0,"searchable": true, "orderable": true },
                { "targets": 1,"searchable": true, "orderable": true },
                { "targets": 2,"searchable": true, "orderable": true },
                { "targets": 3,"searchable": false, "orderable": true },
                { "targets": 4,"searchable": false, "orderable": true  },
                { "targets": 5,"searchable": false, "orderable": true  },
                { "targets": 6,"searchable": false, "orderable": true  },
                { "targets": 7,"searchable": false, "orderable": true  },
                { "targets": 8,"searchable": false, "orderable": true  },
                { "targets": 9,"searchable": false, "orderable": true  },
                @endif
            ],
            fnInitComplete: function (oSettings, json) {


	            this.api().columns().every(function () {
	                var column = this;
	                // console.log(column[0][0]);
	                // for ID, Name, Email, City Column
	                if(column[0][0] == 6 || column[0][0] == 0 || column[0][0] == 1 || column[0][0] == 2)
	                {
	                	// add enter(new line) to header
	                	$("<br>").appendTo($(column.header()));
		                var input = document.createElement("input");

		                if(column[0][0] == 0)
		                {
		                	$(input).css("width", "40px");
		                }
		                else
		                {
		                	$(input).css("width", "80px");
		                }
		                // append input to header
		                $(input).appendTo($(column.header()))
						/*.on('change', function (e) {
							console.log("onChange");
							// if (e.which == 13) {
			                    // var val = $.fn.dataTable.util.escapeRegex($(this).val());
			                    // console.log("keypress");
			                    // column.search(val ? val : '', true, false).draw();
                    		column.search($(this).val()).draw();
							// }
		                })*/.on('keypress', function (e) {
		                	// if enter pressed
							if (e.which == 13) 
							{
								column.search($(this).val()).draw();
							}
		                }).on('keyup', function (e) {

		                	// letter is printed after keypress, so use event keyup because 
		                	// if input is blank 
							if($(input).val() == '')
							{
								column.search($(this).val()).draw();
							}
		                }).on('click', function(e){
						  	e.stopPropagation();
						    e.preventDefault();
		                });
	                }
	            });


	            var _that = this;

				this.each(function (i) {
					$.fn.dataTableExt.iApiIndex = i;
					var $this = this;
					var anControl = $('input', _that.fnSettings().aanFeatures.f);
					anControl
						.unbind('keyup search input')
						.bind('keypress', function (e) {
							if (e.which == 13) {
								$.fn.dataTableExt.iApiIndex = i;
								_that.fnFilter(anControl.val());
							}
						});
					return this;
				});
				return this;
	        },
        });
	
	$('#member-table').on('click', 'a', function(){
		var this_id = $(this).attr('data-id');
		var this_action = $(this).data('action');
		if(this_action == 'show'){
				// alert(baseURL);
			$.get(baseURL + "/member/profile/mini/" + this_id, function( data ) {
				$('#myModal').modal({
					backdrop: false	
				});
				$('#myModal').on('shown.bs.modal', function(){
					$('#myModal .load_modal').html(data);
				});
				$('#myModal').on('hidden.bs.modal', function(){
					$('#myModal .modal-body').data('');
				});
			});
		}
		// activation confirmation
		if(this_action == 'active' || this_action == 'inactive')
		{
			
			console.log(this_action+" "+this_id);
			$('#status' + this_id).html('<img src="{{ secure_asset('/img/load.gif') }}">');
			$.get(baseURL + "/member/status?action=" + this_action + "&id=" + this_id , function( data ) 
			{
				$('#status' + this_id).html(data);
			});
		}
	});
	$('.datepicker').datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
	    changeYear: true,
    });

    $('input.datepicker').bind('keyup keydown keypress', function (evt) {
       return false;
   	});

</script>
@endsection