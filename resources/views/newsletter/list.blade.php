@extends('layouts.app')

@section('htmlheader_title')
    {{ $title ?? '' }}
@endsection

@section('contentheader_title')
    {{ $title ?? '' }}
@endsection

@section('contentheader_description')
    {{ $title_description ?? '' }}
@endsection

@section('main-content')
        <div class="">
            <div class="box table-responsive">
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Title</th>
                        <th class="text-center">Author</th>
                        <th class="text-center">Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $number = 1;?>                    
                    <?php $i = $number;?>
                    @foreach( $newsletters as $newsletter )
                    <tr>
                        <td class="text-center">{{$i++}}</td>
                        <td class="text-center"><a href="{{ route('newsletter-edit', [$newsletter->id]) }}">{{ $newsletter->title }}</a></td>
                        <td class="text-center">{{ $newsletter->author->name }}</td>
                        <td class="text-center">{{ $newsletter->created_at->format('d M Y - H:i:s') }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            <span style="padding-left:10px">{!! $newsletters->render() !!}</span>
            </div>
        </div>
@endsection

@section('content-script')

@endsection

