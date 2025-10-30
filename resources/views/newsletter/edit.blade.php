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
@endpush


@section('main-content')
    <div class="">
        <script type="text/javascript" src="{{ secure_asset('/plugins/tinymce-4.6.1/tinymce.min.js') }}"></script>
        <script type="text/javascript">
            tinymce.init({
                schema: "html5",
                extended_valid_elements:"script[charset|async|defer|language|src|type],",       
                relative_urls : false,
                remove_script_host : false,
                convert_urls : true,
                selector: "textarea.postbody",
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
        </script>

        <form action="{{ route('newsletter-update') }}" enctype="multipart/form-data" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $newsletter->id }}">
            <div class="form-group">
                <input required="required" value="{{ $newsletter->title ?? '' }}" placeholder="Title" type="text"
                       name="title" class="form-control"/>
            </div>
            <div class="form-group">
                <textarea name='body' class="form-control postbody">{{ $newsletter->body ?? '' }}</textarea>
            </div>

	    @if(0)
            <div>
                <label>
                    <input type="checkbox" id="checkboxPublish"> Publish Later         
                </label>
            </div>
            <div id="picker">
                <input id="datepickerPublish" placeholder="Choose Date" name="date_publish" disabled="true"/>  

                <span style="margin-left:20px"><b>Time</b><span>  
                <input id="hourPickerPublish" name="hour_publish" class="numbersOnly" maxlength="2" disabled="true"/> :  
                <input id="minutePickerPublish" name="minute_publish" class="numbersOnly" maxlength="2" disabled="true"/>    
            </div>
	    @endif
            <br>
            <input type="submit" name='save' class="btn btn-default" value="Save Draft"/>
            <input type="submit" name='test' class="btn btn-success" value="Email Test"/>
            <input type="submit" name='blast' class="btn btn-success" value="Email Blast"/>
        </form>
    </div>
@endsection

@section('content-script')
<script type="text/javascript">

    $(function () {
        
        $('#datepickerPublish').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
        });
        $('#picker').hide();
    });
    $('input#datepickerPublish').bind('keyup keydown keypress', function (evt) {
       if (evt.which != 9) {
            return false;
        }
    });
    $('#checkboxPublish').change(function(){
        if($(this).prop('checked'))
        {
            $('#picker').show('slow');
            $('#datepickerPublish').removeAttr('disabled');
            $('#hourPickerPublish').removeAttr('disabled');
            $('#minutePickerPublish').removeAttr('disabled');

            $('#datepickerPublish').attr('required',true);
            $('#hourPickerPublish').attr('required',true);
            $('#minutePickerPublish').attr('required',true);
        }
        else
        {
            $('#picker').hide('slow');
            $('#datepickerPublish').attr('disabled',true);
            $('#hourPickerPublish').attr('disabled',true);
            $('#minutePickerPublish').attr('disabled',true);
            
            $('#datepickerPublish').removeAttr('required');
            $('#hourPickerPublish').removeAttr('required');
            $('#minutePickerPublish').removeAttr('required');
        }
        
    });
    $('.numbersOnly').bind('keyup keydown keypress', function (e) { 
        if (e.which != 8 && e.which != 0 && e.which != 9 && (e.which < 48 || e.which > 57)) {
            return false;
        }
        if($(this).attr('id') == 'hourPickerPublish' && $(this).val()>23)
        {
            $(this).val('00');   
            return false;
        }
        else if($(this).attr('id') == 'minutePickerPublish' && $(this).val()>59)
        {
            $(this).val('00');   
            return false;
        }
        // console.log($(this).val());
    });
</script>
@endsection
