@extends('z')

@section('htmlheader_title')
{{ $title ?? '' }}
@endsection

@section('content')
<div align="center"><h3><b>Top Members</b></h3></div>
<div align="center"><h5>1<sup>st</sup> May 2017 - 31<sup>st</sup> December 2017 </h5></div>
<div class="featured-categories-container row">
	@for($i=0; $i<34; $i++)

		@if($i%2 == 0)
		<div class="clearfix visible-sm-block"></div>
		@endif
		@if($i%3 == 0)
		<div class="clearfix visible-md-block"></div>
		<div class="clearfix visible-lg-block"></div>
		@endif

		<div class="col-xs-12 col-sm-6 col-md-4">
			@if($arrayProvinceMembers[$i]->first())
			<div><b> {{ $arrayProvinceMembers[$i]->first()->province }} </b></div>
			@endif
			<ul class="list-group border-flat">
			@foreach($arrayProvinceMembers[$i] as $provinceMember)

					<li class="list-group-item border-flat">
						<table>
							<tr>
								<td rowspan="2"><img  class="img-top-member img-circle" src="{{ route('image-show', [IMAGE_SMALL, $provinceMember->avatar_path_view ]) }}" alt="{{ $provinceMember->name }}"></td>
								<td style="padding-left: 10px"> 
								<a target="_blank" href="{{ route('member-public-profile', $provinceMember->id) }}">
								{{ $provinceMember->name }}</a>, {{ $provinceMember->last_point }} points 
								</td>
							</tr>
							<tr>
								<td style="padding-left: 10px"> {{ $provinceMember->age }}, {{ $provinceMember->school }}</td>
							</tr>
						</table>
					</li>				
			@endforeach		
			</ul>
		</div>

	@endfor
</div>
@endsection