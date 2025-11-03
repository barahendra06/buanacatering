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
              <a href="{{ route('product-category-index') }}">
                <div class="small-box bg-aqua">
                  <div class="inner">
                    <h3>{{ $totalProductCategories }}</h3>

                    <p>Total Product Category</p>
                  </div>
                  <div class="icon">
                    <i class="fa fa-users"></i>
                  </div>
                </div>
              </a>
          </div>
          <div class="col-lg-3 col-xs-6">
            <a href="{{ route('product-index') }}">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3>{{ $totalProducts }}</h3>

                  <p>Total Product</p>
                </div>
                <div class="icon">
                  <i class="fa fa-users"></i>
                </div>
              </div>
            </a>
          </div>
          <div class="col-lg-3 col-xs-6">
            <a href="{{ route('package-index') }}">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>{{ $totalProductCategories }}</h3>

                  <p>Total Product Package</p>
                </div>
                <div class="icon">
                  <i class="fa fa-users"></i>
                </div>
              </div>
            </a>
          </div>
      </div>
    @endif

@endsection

@section('content-script')

@endsection