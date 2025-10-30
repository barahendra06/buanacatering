<div class="line-separator visible-sm visible-xs"></div>
<div class="row">
	{{-- <div class="col-xs-6  col-sm-6 col-md-12 col-lg-12">
		<!-- ANNOUNCEMENT -->
		@if(isset($siteSettings[SETTING_SIDEBAR_ANNOUNCEMENT_LINK]) and !empty($siteSettings[SETTING_SIDEBAR_ANNOUNCEMENT_LINK]->value))
			<div class="sidebar-item">
				<a href="{{ $siteSettings[SETTING_SIDEBAR_ANNOUNCEMENT_LINK]->value ?? '' }}" >
					@if(isset($siteSettings[SETTING_SIDEBAR_ANNOUNCEMENT_IMAGE]) and !empty($siteSettings[SETTING_SIDEBAR_ANNOUNCEMENT_IMAGE]->value))
						<img class="center-block img-responsive" src="{{ secure_asset($siteSettings[SETTING_SIDEBAR_ANNOUNCEMENT_IMAGE]->value) }}" />
					@endif
				</a>
			</div>
		@endif

		<!-- WEEKLY CHALLENGE -->
		@if(isset($siteSettings[SETTING_CHALLENGE_LINK]) and !empty($siteSettings[SETTING_CHALLENGE_LINK]->value))
			<div class="sidebar-item">
				<a href="{{ $siteSettings[SETTING_CHALLENGE_LINK]->value }}" >
						@if(isset($siteSettings[SETTING_CHALLENGE_IMAGE]) and !empty($siteSettings[SETTING_CHALLENGE_IMAGE]->value))
							<img class="center-block img-responsive" src="{{ secure_asset($siteSettings[SETTING_CHALLENGE_IMAGE]->value) }}" />
						@endif
				</a>	
			</div>
		@endif
	</div> --}}

	{{-- <div class="col-xs-6  col-sm-6 col-md-12 col-lg-12">	 --}}
		<!-- POLLS -->
		{{-- @if(isset($siteSettings[SETTING_POLLS]) and !empty($siteSettings[SETTING_POLLS]->value))
			<div class="sidebar-item">
				<a href="{{ route('polls') }}" ><img class="center-block img-responsive" src="{{ secure_asset($siteSettings[SETTING_POLLS]->value) }}" /></a>
			</div>
		@endif --}}
		
		<!-- INSTAGRAM -->
		{{-- <div class="sidebar-item">		
			<h2>Instagram</h1>
			<!-- LightWidget WIDGET -->
			<script src="//lightwidget.com/widgets/lightwidget.js"></script>
			<iframe src="//lightwidget.com/widgets/7389b3bbd8ae5af6affca34748014d51.html" id="lightwidget_7389b3bbd8" name="lightwidget_7389b3bbd8"  scrolling="no" allowtransparency="true" class="lightwidget-widget" style="width: 100%; border: 0; overflow: hidden;"></iframe>
		</div>	 --}}
		
		<!--
		<div class="sidebar-item">
			<img class="center-block img-responsive" src="{{ url('img/sidebar/ads-1.jpg') }}" />		
		</div>
		-->
		
		<!-- CARTOON -->
		{{-- @if(isset($siteSettings[SETTING_CARTOON]) and !empty($siteSettings[SETTING_CARTOON]->value))
			<div class="sidebar-item">

				<div class="my-gallery" itemscope itemtype="http://schema.org/ImageGallery">
                    <figure class="" itemprop="associatedMedia" itemscope
                            itemtype="http://schema.org/ImageObject">
                        <a href="{{ secure_asset($siteSettings[SETTING_CARTOON]->value) }}" itemprop="contentUrl"
                           data-size="{{ $siteSettings[SETTING_CARTOON]->getImageSizeString($siteSettings[SETTING_CARTOON]->value) }}">
                            <img id="image_result" src="{{ route('image-show', [IMAGE_LARGE, $siteSettings[SETTING_CARTOON]->value ]) }}"
                                 itemprop="thumbnail" class="center-block img-responsive no-caption"
                                 alt="Image description"/>
                        </a>
                        <figcaption itemprop="caption description" hidden>
                        </figcaption>
                    </figure>                           
                </div>
			</div>	
		@endif --}}
	{{-- </div> --}}

	{{-- START Related Post --}}
    @if(isset($relatedPosts))
    <div class="col-md-12">
        <div class="row m-t-10 sidebar">
            <div class="col-12 col-md-12 related-post">
                <div class="row">
                    <div class="col-lg-5 title lh-1">Related</div>
                    <div class="col-lg-7 pl-0"><hr class="w-100 mt-3"></div>
                </div>
                <ul>
                @foreach($relatedPosts as $relatedPost)
                    <li>
                        <a href="{{ route('post-single', [$relatedPost->id, $relatedPost->slug]) }}">
                            @if(isset($relatedPost->title_top))
                                <span class="title-top">{{ $relatedPost->title_top ?? '' }}</span><br>
                            @endif
                            <span>{{ $relatedPost->title ?? '' }}</span>
                        </a>
                    </li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
    {{-- END Related Post --}}
    
    {{-- START Latest Post --}}
    @if(isset($latestPosts))
    <div class="col-md-12">
        <div class="row m-t-20 sidebar">
            <div class="col-12 col-md-12 related-post">
                <div class="row">
                    <div class="col-lg-5 title lh-1">Latest</div>
                    <div class="col-lg-7 pl-0"><hr class="w-100 mt-3"></div>
                </div>
                <ul>
                @foreach($latestPosts->take(5) as $latestPost)
                    <li>
                        <a href="{{ route('post-single', [$latestPost->id, $latestPost->slug]) }}">
                            @if(isset($latestPost->title_top))
                                <span class="title-top">{{ $latestPost->title_top ?? '' }}</span><br>
                            @endif
                            <span>{{ $latestPost->title ?? '' }}</span>
                        </a>
                    </li>
                @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
    {{-- END Latest Post --}}

</div>

