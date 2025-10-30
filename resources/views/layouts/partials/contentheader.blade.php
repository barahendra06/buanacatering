<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        @yield('contentheader_title')
        <small>@yield('contentheader_description')</small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
    @if(isset($message))
    <div class="flash alert-info-dashboard m-t-10">
        <p class="panel-body">
            {{ $message }}
        </p>
    </div>
    @endif	
	@if (Session::has('message'))
    <div class="flash alert-info-dashboard m-t-10">
        <p class="panel-body">
            {{ Session::get('message') }}
        </p>
    </div>
	@endif	
    @if(isset($errors) and count($errors))
    <div class="flash alert-danger-dashboard m-t-10">
        <ul style="margin-left: 10px;" class="panel-body">
            @if($errors instanceof Illuminate\Support\ViewErrorBag)
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            @else
                <li>{{ $errors }}</li>
            @endif
        </ul>
    </div>
    @endif  

    @if(session('error'))
    <div class="flash alert-danger-dashboard m-t-10">
        <ul style="margin-left: 10px;" class="panel-body">
            @foreach(session('error') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif 
</section>