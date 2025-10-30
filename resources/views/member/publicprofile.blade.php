@extends('z') 
@section('htmlheader_title') 
	{{ $title ?? '' }} 
@endsection 
@push('content-header')
	<!-- Important Owl stylesheet -->
	<link rel="stylesheet" href="{{ secure_asset('/css/owl.carousel.css') }}">
	<!-- Default Theme -->
	<link rel="stylesheet" href="{{ secure_asset('/css/owl.theme.css') }}">
	<style type="text/css">
		.owl-controls 
		{
			position: absolute;
			top: 20%;
			width: 100%;
		}

		.owl-prev 
		{
			position: absolute;
			left: 0px;
		}

		.owl-next 
		{
			position: absolute;
			right: 25px;
		}

		.owl-theme .owl-controls .owl-buttons div 
		{
			color: #ec008c;
			display: inline-block;
			zoom: 0;
			font-weight: 900;
			margin: 5px;
			padding: 0;
			font-size: 33px;
			-webkit-border-radius: 0;
			-moz-border-radius: 30px;
			border-radius: 0;
			background: transparent;
			filter: Alpha(Opacity=50);
			opacity: 1;
		}


		@media(max-width: 768px) 
		{
			.owl-controls 
			{
				position: absolute;
				top: 10%;
				width: 100%;
			}

			.owl-next 
			{
				position: absolute;
				right: 0;
			}
		}
	</style>
@endpush 
@section('content')
	<div class="row clearfix" style="margin-top: 3%">
		<div class="col-md-9">
			<div class="row clearfix">
				<div class="col-md-4">
					@if(isset($member))
					<div class="profile-panel">
						<a target="_blank" href="{{ url('view?'.'path='.$member->avatar_path.'&text='.$member->name.' ('.$member->user->id.')') }}">
							@if(isset($member->avatar_path))
								<img class="rounded-circle user-img-profile d-block mx-auto img-fluid" id="photoPreview" src="{{ route('image-show', [IMAGE_MEDIUM, $member->avatar_path_view ]) }}" alt="{{ $member->name }}" />
							@else
								<img class="rounded-circle user-img-profile d-block mx-auto img-fluid" id="photoPreview" src="{{ url('/img/logo-square.jpg') }}" alt="{{ $member->name }}" />
							@endif
						</a>
							<span class="profile-divider"></span>
					</div>
					@endif
				</div>
				<div class="col-md-8">
					<div class="row clearfix">
						<div class="col-md-12">
							@php 
								$memberName = strtoupper($member->name); 
								$memberNameLength = strlen($memberName);
							@endphp
							@if($memberNameLength <=15)
							<div class="profile-name" style="padding-top: 60px; padding-bottom: 50px;">
								{{ $memberName ?? '-' }}
							</div>
							@elseif($memberNameLength <=20)
							<div class="profile-name" style="padding-top: 10px; padding-bottom: 10px;">
								{{ $memberName ?? '-' }}
							</div>
							@else
							<div class="profile-name" style="padding-top: 10px; padding-bottom: 10px;font-size: 70px;">
								{{ $memberName ?? '-' }}
							</div>
							@endif
						</div>
						<div class="col-md-12">
							<div style="padding-bottom: 35px; padding-top: 15px;">

								@if($member->description != '')
									<span class="bio">BIODATA, PROFIL PENULIS
										<br>
									</span>
									<div class="bio-description">
										{{$member->description}}
									</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				@foreach($contentArticles as $article)
					<div class="row category-post-item">	
						<div class="col-sm-4">					
							<div class="img-fluid">
								<a href="{{ route('post-single', [$article->post->id, $article->post->slug]) }}">
								@if($article->post->featured_image_path!="")
									<img class="d-block mx-auto img-fluid post-image" src="{{ route('image-show', [IMAGE_MEDIUM, $article->post->featured_image_relative_path]) }}" />
								@else
									<img class="d-block mx-auto img-fluid post-image" src="{{ route('image-show', [IMAGE_MEDIUM, 'img/logo.png']) }}" />
								@endif
								</a>
							</div>

												
						</div>
						<div class="col-sm-8">
							@if(isset($article->post->postCategory))				                            
								<div class="post-category">
									<a href="{{ route('post-category', [$article->post->postCategory->id,$article->post->postCategory->slug]) }}">
										{{ $article->post->postCategory->name ?? '' }}
									</a>
								</div>
							@endif					
							<div class="post-title">
								<a href="{{ route('post-single', [$article->post->id, $article->post->slug]) }}">{{ $article->post->title ?? '' }}</a>
							</div>
							<div>
								<span class="post-date">{{ $article->post->created_at->diffforhumans() }}</span>
							</div>	
							<div class="no-padding post-body">
								@if($article->post->excerpt)
									{{ $article->post->excerpt }}
								@else
									{{ str_limit(strip_tags($article->post->body), 160, '...') }}							
								@endif
							</div>					
						</div>
					</div>
				@endforeach
				@if(isset($contentArticles) and count($contentArticles))
					<div class="row">
						<div class="col-md-12">
							<span>{!! $contentArticles->render() !!}</span>
						</div>
					</div>
					@endif
			</div>
		</div>
		@include('sidebar_top')
	</div>
@endsection 
@push('content-footer') 
@endpush