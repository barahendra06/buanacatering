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

@push('content-header')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

<style>
	#imagePreview {
		width: 300px;
		height: 150px;
		border: 2px solid #ddd;
		display: none;
		object-fit: cover;
	}
</style>
@endpush


@section('main-content')
	<script data-cfasync="false" type="text/javascript" src="{{ secure_asset('/plugins/tinymce-4.6.1/tinymce.min.js') }}"></script>
	<script type="text/javascript">
	    function initTiny() {
	    	tinymce.init({
		        schema: "html5",
		    	@if(Auth::user()->isAdmin() or Auth::user()->isContributor() or Auth::user()->isEditor())
				extended_valid_elements:"script[charset|async|defer|language|src|type],",		
				@endif	    
		        relative_urls : false,
		        remove_script_host : false,
		        convert_urls : true,
		        selector: "textarea.tinyMessage",
		        height: "280",
		        image_caption: true,
		        automatic_uploads: true,
		        plugins: ["textcolor advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste jbimages"],
		        toolbar: "insertfile undo redo | styleselect | forecolor backcolor bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages",
		        
		        // selector: "textarea",  // change this value according to your HTML
		        // plugins: "image",
		        // menubar: "insert",
		        // toolbar: "jbimages",
		        // image_prepend_url: "http://192.168.81.53/z/public/uploads/"
		    });
	    }
	</script>
	<style type="text/css">
		.pushNotif{
			margin-bottom: 12px;
		}
	</style>
<div class="box">
    <div class="box-body">
    	<form action="{{ route('create-notification') }}" method="post" id="form_push_notif" enctype="multipart/form-data">
    		{{ csrf_field() }}
    		<div class="row">
	    		<div class="col-xs-6 form-group">
	    			<label for="notificationType">Notification Type</label>
	    			<select id="notificationType" name="notificationType" class="form-control" required>
	    					<option value="" disabled selected>Choose one</option>
		    			@foreach ($notificationTypes as $notificationType)
		    				<option value="{{ $notificationType->id }}">{{ $notificationType->alias }}</option>
		    			@endforeach
	    			</select>
	    		</div>
	    		<div class="col-xs-6">
	    			<div class="row">
	    				<div class="col-lg-12 hide" id="listContentCol">
		    		
                		</div>
	    				{{-- <div class="col-xs-6 hide" id="imgColNews">
			    			<label for="imgPathNews">Image</label>
			    			<input type="file" name="imgPathNews" id="imgPathNews">
			    		</div> --}}
	    			</div>
					<div class="row">
						<div class="col-xs-4" id="recipientInboxCol">
	    					<label for="recipient">Send to</label>
	    					<input id="recipientInbox" type="text" name="recipientInbox" value="All" class="form-control" disabled>
	    				</div>
	    				<div class="col-xs-8" id="memberCol">
	    					
	    				</div>
					</div>
	    		</div>
	    	</div>
	    	<div class="row">
	    		<div class="form-group col-xs-6" id="titleCol">
	    			<label for="title">Title</label>
	    			<input type="text" id="title" class="form-control" name="title" value="{{ old('title') }}" placeholder="Notification Title" maxlength="100" required>
	    		</div>

	    		{{-- <div class="col-xs-6" id="imgCol">
	    			<label for="imgPath">Image</label>
	    			<input type="file" name="imgPath" id="imgPath">
	    		</div> --}}

                <div class="col-xs-6 form-group hide" id="recipientCol">
	    			<div class="row">
						<div class="col-xs-4">
							<label for="">Target</label>
							<select id="recipientTarget" name="recipientTarget" class="form-control">
		    				</select>
						</div>
		    			<div class="col-xs-3" id="recipientFilterCol">
							<label for="recipientCol">Send to</label>
							<select id="recipientFilter" name="recipientFilter" class="form-control">
	    						<option value="" disabled selected>Choose one</option>
			    				@foreach (PUSH_NOTIFICATION_TARGET_ARRAY as $value => $pushNotificationTarget)
			    					<option value="{{ $value }}" data-id="{{ $value }}">{{ $pushNotificationTarget }}</option>
			    				@endforeach
		    				</select>
						</div>
						<div class="col-xs-5" id="recipientTargetCol">
	    					
	    				</div>
						{{-- <div class="col-xs-6">
			    			<label for="imgPathVideo">Image</label>
			    			<input type="file" name="imgPathVideo" id="imgPathVideo">
			    		</div> --}}
		    		</div>
				</div>
	    	</div>
	    	<div class="row">
	    		<div class="form-group col-xs-12 hide" id="urlCol">
	    			
	    		</div>
	    	</div>
			<div class="row">
	    		<div class="form-group col-xs-4 col-md-4 col-lg-4 hide" id="inputFileCol">
					<label for="">Image</label>
					<img id="imagePreview" src="#" alt="">
					<input class="form-control" type="file" id="imageInput" name="imgPath" accept="image/*">
	    		</div>
	    	</div>
	    	<div class="row">
	    		<div class="form-group col-xs-12">
	    			<div id="pushNotif" class="hide pushNotif">
	    				<input type="checkbox" name="pushNotif" id="checkBoxPushNotification"> Push Notification <br>
	    			</div>
					<div id="inbox" class="hide inbox">
	    				<input type="checkbox" name="inbox" id="checkBoxInboxNotification"> Inbox Notification <br>
	    			</div>
	    			<div id="wrapperShortBody">
	    				
	    			</div>
	    			<label for="message">Notification Body</label>
	    			<textarea name="notificationMessage" id="message" rows="10" class="form-control tinyMessage" placeholder="Notification Message" maxlength="180">{{ old('notificationMessage') }}</textarea>
	    		</div>
	    	</div>
	    	<div class="row">
	    		<div class="form-group col-xs-12">
	    			<button class="btn btn-sm btn-success" type="submit" id="btn-publish">Send Now</button>
	    		</div>
	    	</div>
	    </form>
	</div>
</div>
@endsection

@section('content-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript">
    var baseURL = {!! json_encode(url('/')) !!};    //getting base url
</script>  
<script type="text/javascript">
	{{-- if selected type changed --}}
	$('#notificationType').change(function () {
		changeForm();
	});

	$(document).ready(function () {
		changeForm();
	});

	function changeForm() {
		var selectedType = $("#notificationType option:selected").val();

		// Inbox Selected
		if(selectedType == 1)
		{
			uncheckCheckbox();
			resetTarget();
			resetImage();

			var element = '';
			$('#wrapperShortBody').html(element);

			$('#article-choice').prop('required', 'false');
			$('#inbox').addClass('hide');
			$('#pushNotif').removeClass('hide');
			$('#inputFileCol').addClass('hide');

			// change recipient to specific member
			$('#recipientInbox').val('Specific Member');
			$('#recipientInboxCol').addClass('col-xs-6');
			$('#recipientInboxCol').removeClass('col-xs-12 hide');

			// remove element url
			var element = '';
			$('#urlCol').addClass('hide');
			$('#urlCol').html(element);
			$('#memberCol').addClass('hide');
			$('#Col').addClass('hide');
			$('#recipientCol').addClass('hide');
			$('#listContentCol').addClass('hide');
			document.getElementById("message").required = false;
			document.getElementById("recipientFilter").required = false;

			// add select2 member
			var element = '<label for="member">Student Name</label><select name="member" id="member" class="form-control" width="100%" required></select>';
			$('#memberCol').html(element);
			$('#memberCol').removeClass('hide');
			$('#imgColNews').addClass('hide');
			$('#imgCol').removeClass('hide');

			initTiny();

			$('#checkBoxPushNotification').click(function () {
				var checkbox = $('#checkBoxPushNotification');

				if(checkbox.is(":checked"))
				{
					// checkbox.val(1);

					var element = '<label for="shortBody">Push Notification Body</label><textarea name="notificationShortMessage" id="shortBody" rows="10" class="form-control" style="margin-bottom:12px;" placeholder="Push Notification Body" required>{{ old('notificationShortMessage') }}</textarea>';
					$('#wrapperShortBody').html(element);
					// tinymce.remove();
				}
				else
				{
					// checkbox.val(0);
					// initTiny();
					var element = '';
					$('#wrapperShortBody').html(element);
				}
			});

			$("#member").select2({
			    tags: false,
			    multiple: false,
			    minimumInputLength: 3,
			    minimumResultsForSearch: 10,
			    placeholder: 'Search Student',
			    ajax: {
			        url: baseURL+'/notification/create/member',
			        dataType: "json",
			        type: "GET",
			        data: function (params) {
			            return {
			            	queryParameters:params.term,
			            };
			        },
			        processResults: function (data) {
						console.log(data.member)
			            return {
			                results: $.map(data.students, function (item) {
			                    return {
			                        text: item.name+'('+item.user.email+')',
			                        id: item.user_id
			                    }
			                })
			            };
			        }
			    }
			});

			$('#article-choice').prop('required', false);
			$('#recipientId').prop('required', false);
			$('#recipientTarget').prop('required', false);
		}
		// News Selected
		else if(selectedType == 2)
		{	
			uncheckCheckbox();
			resetTarget();
			resetImage();

			// remove element url
			var element = '';
			$('#urlCol').addClass('hide');
			$('#urlCol').html(element);
			$('#wrapperShortBody').html(element);

			// change recipient to specific all
			// $('#recipient').val('All');

			$('#pushNotif').addClass('hide');
			$('#inbox').addClass('hide');
			$('#inputFileCol').addClass('hide');

			// remove select2 member
			$('#recipientInboxCol').addClass('hide');
			$('#memberCol').html(element);
			$('#memberCol').addClass('hide');
			$('#wrapperShortBody').html(element);
			$('#imgColNews').addClass('hide');
			$('#imgCol').addClass('hide');
			$('#recipientCol').removeClass('hide');
			$('#recipientFilter').removeClass('hide');
			$('#recipientTargetCol').removeClass('hide');
			document.getElementById("message").required = true;
			document.getElementById("recipientFilter").required = true;

			// add list article
			var element = '<label>Article List</label><div class="form-group">{!! Form::select('articleId', [], old('articleId'), ['placeholder' => 'Select All', 'class' => 'form-control', 'id' => 'article-choice', 'style' => 'width:89%', 'required']); !!}<a class="btn btn-sm btn-info" name="viewArticle" id="viewArticle" style="margin-left: 10px;">View</a></div>';
			$('#listContentCol').removeClass('hide');
			$('#listContentCol').html(element);
			
			$('#article-choice').select2({
	            placeholder: 'Select Article',
				multiple: false,
			    // minimumInputLength: 5,
			    // minimumResultsForSearch: 10,
			    ajax: {
			        url: baseURL+'/post/search/ajax',
			        dataType: "json",
			        type: "GET",
			        data: function (params) {
			            return {
			            	title:params.term,
			            };
			        },
			        processResults: function (data) {
			            return {
			                results: $.map(data.posts, function (item) {
			                    return {
			                        text: item.title,
			                        id: item.id
			                    }
			                })
			            };
			        }
			    }
	        });

			// element recipient area
	        // added element recipient
	        var element = '<label for="recipientTargetCol">Recipient</label><select name="recipientId" id="recipientId" class="form-control" required></select>';
			$('#recipientId').addClass('required');
	        $('#recipientTargetCol').html(element);

	        $("#recipientFilter").change(function () {
		        var recipientFilter = $('#recipientFilter').val();

		        if (recipientFilter == 1 || recipientFilter == 6) {
		        	$("#recipientId").prop("disabled", true);
					$("#recipientId").empty();
		        }
		        else
		        {
		        	$("#recipientId").prop("disabled", false);

					var uri = baseURL+'/notification/create/recipient';

					$("#recipientId").select2();

					$.ajax({
						url: uri,
						dataType: "json",
						type: "GET",
						data: { 
							recipient_data: $('#recipientFilter').val(),
						},
						success: function (data) {
							var select = $('#recipientId').empty();
							
							$.each(data.recipient, function(i, item) {
								select.append( '<option value="'
													+ item.id
													+ '">'
													+ item.name
													+ '</option>' ); 
							});
						}
					})
		        }
		    });

	        /* $("#recipientId").select2({
			    multiple: false,
			    minimumInputLength: 3,
			    minimumResultsForSearch: 10,
			    placeholder: 'Search',
			    ajax: {
			        url: baseURL+'/notification/create/recipient',
			        dataType: "json",
			        type: "GET",
			        data: function (params) {
			            return {
			            	queryParameters:params.term,
			            	recipient_data: $('#recipientFilter').val(),
			            };
			        },
			        processResults: function (data) {
			            return {
			                results: $.map(data.recipient, function (item) {
			                    return {
			                        text: item.name,
			                        id: item.id
			                    }
			                })
			            };
			        }
			    }
			}); */

			// get selected article on news type
			var articleId = 0;
	        $('#article-choice').on('select2:select', function (e) {
			    articleId = e.params.data.id;
			    $('#viewArticle').removeClass('disabled');
			});

	        if (articleId == 0) 
	        {
	        	$('#viewArticle').addClass('disabled');
	        }

	        // open selected article on news type
	        $('#viewArticle').click(function(){
	        	window.open(baseURL+"/r/"+articleId, '_blank');
	        });
			
			// remove tinymce
			tinymce.remove();

			$('#recipientTarget').prop('required', true);
		}
		// Announcement Selected
		else if(selectedType == 7)
		{
			uncheckCheckbox();
			resetTarget();
			resetImage();

			// remove element url
			var element = '';
			// $('#urlCol').addClass('hide');
			// $('#urlCol').html(element);
			$('#wrapperShortBody').html(element);
			$('#articleId').prop('required', false);
			// remove element url
			var element = '';
			$('#urlCol').addClass('hide');
			$('#urlCol').html(element);
			$('#memberCol').addClass('hide');
			$('#Col').addClass('hide');
			$('#recipientCol').addClass('hide');
			$('#listContentCol').addClass('hide');
			document.getElementById("message").required = false;
			document.getElementById("recipientFilter").required = false;

			// change recipient to specific all
			// $('#recipient').val('All');

			$('#pushNotif').addClass('hide');
			$('#inputFileCol').removeClass('hide');

			// remove select2 member
			var element = '';
			$('#recipientInboxCol').addClass('hide');
			$('#memberCol').html(element);
			$('#memberCol').addClass('hide');
			$('#wrapperShortBody').html(element);
			$('#imgColNews').addClass('hide');
			$('#imgCol').addClass('hide');
			$('#recipientCol').removeClass('hide');
			$('#recipientFilter').removeClass('hide');
			$('#recipientTargetCol').removeClass('hide');
			document.getElementById("message").required = true;
			document.getElementById("recipientFilter").required = true;

			// element recipient area
	        // added element recipient
	        var element = '<label for="recipientTargetCol">Recipient</label><select name="recipientId" id="recipientId" class="form-control" required></select>';
			$('#recipientId').addClass('required');
	        $('#recipientTargetCol').html(element);

	        $("#recipientFilter").change(function () {
		        var recipientFilter = $('#recipientFilter').val();

		        if (recipientFilter == 1 || recipientFilter == 6) {
		        	$("#recipientId").prop("disabled", true);
					$("#recipientId").empty();
		        }
		        else
		        {
		        	$("#recipientId").prop("disabled", false);

					var uri = baseURL+'/notification/create/recipient';

					$("#recipientId").select2();

					$.ajax({
						url: uri,
						dataType: "json",
						type: "GET",
						data: { 
							recipient_data: $('#recipientFilter').val(),
						},
						success: function (data) {
							var select = $('#recipientId').empty();
							
							$.each(data.recipient, function(i, item) {
								select.append( '<option value="'
													+ item.id
													+ '">'
													+ item.name
													+ '</option>' ); 
							});
						}
					})
		        }
		    });

			$('#recipientTarget').prop('required', true);
		}
		// Other Selected
		else
		{
			uncheckCheckbox();
			resetTarget();
			resetImage();

			// remove element url
			var element = '';
			$('#urlCol').addClass('hide');
			$('#urlCol').html(element);
			$('#wrapperShortBody').html(element);

			// change recipient to specific all
			$('#recipient').val('All');

			$('#pushNotif').addClass('hide');
			$('#inbox').addClass('hide');
			$('#inputFileCol').addClass('hide');

			// remove select2 member
			var element = '';
			$('#recipient').addClass('hide');
			$('#memberCol').html(element);
			$('#memberCol').addClass('hide');
			$('#imgColNews').addClass('hide');
			$('#imgCol').removeClass('hide');
			$('#listArticleCol').html(element);
			$('#listVideoCol').addClass('hide');
			$('#recipientNews').addClass('hide');
			$('#recipientVideo').addClass('hide');

			// remove tinymce
			tinymce.remove();

			$('#recipientTarget').prop('required', true);
		}

		$('#recipientFilter option').hide();

		$('#recipientTarget').on('change', function (){
			uncheckCheckbox();

			var val = $('#recipientTarget').val();

			$('#recipientFilter').val('');
			$('#recipientTargetCol').hide();
			$('#recipientId').val('');
			$('#recipientId').prop('disabled', true);

			if(val == 'user_dbl')
			{
				$('#recipientTargetCol').show();
				$('#recipientFilterCol').show();

				$('#recipientFilter option').show();

				$('#recipientFilter option').each(function() {
					if ($(this).data('id') != 1 && $(this).data('id') != 3) {
						$(this).hide();
					}
				});

				$('#recipientFilter').prop('required', false);
				$('#recipientId').prop('required', false);

				$('#inbox').addClass('hide');
			}
			else if(val == 'user')
			{
				$('#recipientTargetCol').show();
				$('#recipientFilterCol').show();

				$('#recipientFilter option').show();

				$('#recipientFilter option').each(function() {
					if ($(this).data('id') != 1 && $(this).data('id') != 5 && $(this).data('id') != 6) {
						$(this).hide();
					}
				});

				$('#recipientFilter').prop('required', true);
				$('#recipientId').prop('required', true);

				if(selectedType == 7)
				{
					$('#inbox').removeClass('hide');
				}
			}
			else
			{
				$('#recipientFilter option').hide();
				$('#recipientId').prop('required', false);

				$('#inbox').addClass('hide');
			}
		})
	}

	$('#checkBoxInboxNotification').click(function () {
		var checkbox = $('#checkBoxInboxNotification');

		if(checkbox.is(":checked"))
		{
			// checkbox.val(1);
			var element = '<label for="shortBody">Inbox Notification Body</label><textarea name="notificationShortMessage" id="shortBody" rows="10" class="form-control" style="margin-bottom:12px;" placeholder="Inbox Notification Body" required>{{ old('notificationShortMessage') }}</textarea>';
			$('#wrapperShortBody').html(element);
			// tinymce.remove();
		}
		else
		{
			// checkbox.val(0);
			// initTiny();
			var element = '';
			$('#wrapperShortBody').html(element);
		}
	});

	function uncheckCheckbox() {
		// $('#checkBoxPushNotification').val('');
		$('#checkBoxPushNotification').prop('checked', false);

		// $('#checkBoxInboxNotification').val('');
		$('#checkBoxInboxNotification').prop('checked', false);
	}

	function resetTarget() {
		$('#recipientTarget').val('');	
		$('#recipientFilter').val('');
	}

	function resetImage() {
		$('#imagePreview').removeAttr('src');
		$('#imagePreview').attr('src', '#');
		// $('#imagePreview').src('');
		$('#imageInput').val('');
	}

    $("#form_push_notif").submit( function (e) {
	    // var jns_srt = $("#i_dok").val();
	    e.preventDefault();

	    swal({
	        title: "Push Notification",
	        text: "Are you sure want to push this notification?",
	        type: "warning",
	        showCancelButton: true,
	        confirmButtonColor: "#ec6c62",
	        confirmButtonText: "Yes!",
	        cancelButtonText: "Cancel",
	        closeOnConfirm: true
	    }, 
	    function(isConfirm)
	    {
	     	if(isConfirm)
	        {
	        	document.getElementById("form_push_notif").submit();
	        } 
	        else 
	        {
	        return false;
	      	}
	    });
	});

</script>

<script>
	$('#imageInput').change(function(){
		readURL(this);
	}); 

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#imagePreview').attr('src', e.target.result).show();
			}

			reader.readAsDataURL(input.files[0]);
		}
	}
</script>
@endsection
