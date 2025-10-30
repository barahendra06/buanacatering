@extends('layouts.app')

@section('htmlheader_title')
    {{ $title ?? '' }}
@endsection

@section('contentheader_title')
    {{  $title  ?? '' }}
@endsection

@section('main-content')
<div class="container-fluid">
    <div class="panel panel-default box-custom">
        @include('member.activity_poin')
    </div>
    <!-- /.box-body -->
</div>

@endsection