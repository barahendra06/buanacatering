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
                @foreach($subscribers as $key=>$subscriber)
                    <a target="_blank" href="{{ route('member-public-profile', $subscriber->id) }}">
                        <li class="list-group-item border-flat">    
                            <img class="img-subscriber img-circle" src="{{ route('image-show', [IMAGE_TINY, $subscriber->avatar_path_view ]) }}" alt="{{ $subscriber->name }}"> 
                            {{ $subscriber->name }}
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

