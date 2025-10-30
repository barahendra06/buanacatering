<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<form data-toggle="validator" role="form" method="GET" action="{{ route('post-search-result') }}">
				<div class="form-group">
	                <div class="input-group">
	                    <div class="input-group-prepend">
							<div class="input-group-text">
								<i class="fa fa-search"></i>
							</div>
	                    </div>
	                    <input type="text" class="form-control" name="search" id="search_lup">
	                </div>
	            </div>

	            <div class="modal-footer">
	                <button type="submit" class="btn btn-primary">Search</button>
	            </div>
            </form
		</div>
	</div>
</div>
<script type="text/javascript">
$("#search_lup").focus();
</script>