@extends('layouts.app')

@section('htmlheader_title')
    {{ $title ?? '' }}
@endsection

@section('contentheader_title')
    {{  $title  ?? '' }}
@endsection

@section('main-content')
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12">
			<ul class="list-group border-flat">
                @foreach($publishers as $key=>$publisher)
                    <a target="_blank" href="{{ route('member-public-profile', $publisher->id) }}">
                        <li class="list-group-item border-flat">    
                            <img class="img-subscriber img-circle" src="{{ route('image-show', [IMAGE_TINY, secure_asset($publisher->avatar_path_view) ]) }}" alt="{{ $publisher->name }}"> 
                            {{ $publisher->name }}
                        </li>
                    </a>
                @endforeach
            </ul>
		</div>
	</div>
</div>
@endsection

@push('content-footer')
@endpush

