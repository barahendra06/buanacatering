@extends('layouts.app')

@section('htmlheader_title')
    Dashboard
@endsection

@push('content-header')
<style type="text/css">
  .small-box > .icon
  {
    z-index: -1;
  }
  .small-box
  {
    z-index: 0;
  }
</style>
@endpush

@section('main-content')
    @if($currentMember->user->isAdmin() or $currentMember->user->isManager())
     
      <div class="row">
          <div class="col-lg-3 col-xs-6">
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3>{{ $memberRegister }}</h3>

                  <p>Total Register</p>
                </div>
                <div class="icon">
                  <i class="fa fa-users"></i>
                </div>
              </div>
            </div>
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3>{{ $memberRegisterToday }}</h3>

                <p>Register Today</p>
              </div>
              <div class="icon">
                <i class="fa fa-users"></i>
              </div>
            </div>
          </div>
      </div>
    @endif

@endsection

@section('content-script')

@endsection