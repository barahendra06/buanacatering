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
	<div class="row">
        <div class="col-sm-4 col-xs-12 ">
            <div class="panel panel-default box-custom">
                <div class="panel-body">
                	<form action="{{ route('short-url-store') }}" method="post">
				{{ csrf_field() }}
                		<br>
                		<input type="text" name="id" class="form-control" style="display:none" />
			            <div class="form-group">
			            	<label>Parameter</label>
			                <input required="required" placeholder="Parameter" type="text" name="parameter" class="form-control"/>
			            </div>

			            <div class="form-group">
			            	<label>Target URL</label>
			                <input required="required" placeholder="Target URL" type="text" name="url_target" class="form-control"/>
			            </div>
			            <input value="Submit" type="submit" class="btn btn-primary"/>
                	</form>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-8">
    		<div class="box table-responsive">
                <table id="shortUrlTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="text-center">Parameter</th>
                        <th class="text-center">URL Target</th>
                        <th class="text-center">Total Click</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
					@foreach( $shortUrls as $shortUrl )
                    <tr>
						<td>{{ $shortUrl->parameter ?? '' }}</td>
						<td>{{ $shortUrl->url_target ?? '' }}</td>
						<td class="text-center">{{ $shortUrl->click ?? '' }}</td>
						<td class="text-center">
							<a class="btn btn-xs btn-warning edit" data-parameter="{{ $shortUrl->parameter ?? '' }}" data-id="{{ $shortUrl->id ?? '' }}" data-urltarget="{{ $shortUrl->url_target ?? '' }}" ><i class="fa fa-pencil-square-o"></i></a>
							<a class="btn btn-xs btn-danger" href="{{ route('short-url-delete',$shortUrl->id) }}"><i class="fa fa-trash-o"></i></a>
						</td>
                    </tr>
					@endforeach
                    </tbody>
                </table>
            <span style="padding-left:10px">{!! $shortUrls->links() !!}</span>
            </div>
    	</div>
    </div>
    <div id="editShortUrlModal" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Caution</h4>
				</div>
				<div class="modal-body">
					<form id="shortUrlForm" action="{{ route('short-url-update') }}" method="post">
                		<input id="idField" type="text" name="id" class="form-control" style="display:none" />
			            <div class="form-group">
			            	<label>Parameter</label>
			                <input required="required" id="parameterField" placeholder="Parameter" type="text" name="parameter" class="form-control"/>
			            </div>

			            <div class="form-group">
			            	<label>Target URL</label>
			                <input required="required" id="targetUrlField" placeholder="Target URL" type="text" name="url_target" class="form-control"/>
			            </div>
			            <input value="Submit" type="submit" class="btn btn-primary"/>
                	</form>
				</div>
			</div>
		</div>
    </div>
@endsection

@section('content-script')
<script type="text/javascript">
	$("#shortUrlTable").on('click','a.edit',function(){
		$("#parameterField").val($(this).data('parameter'));
		$("#targetUrlField").val($(this).data('urltarget'));
		$("#idField").val($(this).data('id'));
		$('#editShortUrlModal').modal('show'); 
		$("#parameterField").focus();
	});
</script>
@endsection
