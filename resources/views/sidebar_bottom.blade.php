	<div class="row clearfix">
		<div class="col-md-12">
			<!-- SIDEBAR BOTTOM BANNER IMAGE 1 -->
			@if(isset($siteSettings[SETTING_SIDEBAR_BOTTOM_IMAGE1]) and !empty($siteSettings[SETTING_SIDEBAR_BOTTOM_IMAGE1]->value))
				@if($siteSettings[SETTING_SIDEBAR_BOTTOM_LINK1]->value != NULL)
					<a target="_blank" rel="nofollow" href="{{$siteSettings[SETTING_SIDEBAR_BOTTOM_LINK1]->value}}">
						<img class="img img-fluid d-block mx-auto" src="{{ secure_asset($siteSettings[SETTING_SIDEBAR_BOTTOM_IMAGE1]->value) }}" style="margin-top: 20px">
					</a>
				@else
					<img class="img img-fluid d-block mx-auto" src="{{ secure_asset($siteSettings[SETTING_SIDEBAR_BOTTOM_IMAGE1]->value) }}" style="margin-top: 20px">
				@endif
			@endif
		</div>
		<div class="col-md-12">
			<!-- SIDEBAR BOTTOM BANNER IMAGE 2 -->
			@if(isset($siteSettings[SETTING_SIDEBAR_BOTTOM_IMAGE2]) and !empty($siteSettings[SETTING_SIDEBAR_BOTTOM_IMAGE2]->value))
				@if($siteSettings[SETTING_SIDEBAR_BOTTOM_LINK2]->value != NULL)
					<a target="_blank" rel="nofollow" href="{{$siteSettings[SETTING_SIDEBAR_BOTTOM_LINK2]->value}}">
						<img class="img img-fluid d-block mx-auto" src="{{ secure_asset($siteSettings[SETTING_SIDEBAR_BOTTOM_IMAGE2]->value) }}" style="margin-top: 20px">
					</a>
				@else
					<img class="img img-fluid d-block mx-auto" src="{{ secure_asset($siteSettings[SETTING_SIDEBAR_BOTTOM_IMAGE2]->value) }}" style="margin-top: 20px">
				@endif
			@endif
		</div>
		<div class="col-md-12">
			<!-- SIDEBAR BOTTOM BANNER IMAGE 3 -->
			@if(isset($siteSettings[SETTING_SIDEBAR_BOTTOM_IMAGE3]) and !empty($siteSettings[SETTING_SIDEBAR_BOTTOM_IMAGE3]->value))
				@if($siteSettings[SETTING_SIDEBAR_BOTTOM_LINK3]->value != NULL)
					<a target="_blank" rel="nofollow" href="{{$siteSettings[SETTING_SIDEBAR_BOTTOM_LINK3]->value}}">
						<img class="img img-fluid d-block mx-auto" src="{{ secure_asset($siteSettings[SETTING_SIDEBAR_BOTTOM_IMAGE3]->value) }}" style="margin-top: 20px">
					</a>
				@else
					<img class="img img-fluid d-block mx-auto" src="{{ secure_asset($siteSettings[SETTING_SIDEBAR_BOTTOM_IMAGE3]->value) }}" style="margin-top: 20px">
				@endif
			@endif
		</div>
	</div>
