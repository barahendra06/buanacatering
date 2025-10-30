
<!-- ANNOUNCEMENT -->
<div class="row m-t-20">
    <div class="col-md-12">
        <div class="post-title-big">
            {{ $post->title ?? '' }}
		</div>
        @if (Auth::check() and Auth::user()->isAdmin())
        <div class="row m-t-10">
			<div class="col-xs-12">
				<a class="btn btn-xs btn-primary" href="{{ route('post-edit', $post->id) }}">Edit Post</a>
			</div>
        </div>			
        @endif
		
		<div class="post-body">
			{!! $post->body !!}
		</div>

    </div>
</div>

