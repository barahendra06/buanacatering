<div class="panel-body">
    <div class="box-header">
        @if($currentMember->user->role_id == ROLE_ADMIN)
            <h3><b>Total Point :</b> {{ $member->activity->sum('poin') }} </h3>
        @endif
    </div>
    <div class="">
        @foreach($activities as $activity)
            @if($activity->activity_type_id==ACTIVITY_SHARE)
                <h5>You <b>shared</b> a <a href="#">Article</a> @if(isset($activity->remark)) to <b>{{ ucfirst($activity->remark) }}</b> @endif</h5>
            @elseif($activity->activity_type_id==ACTIVITY_REGISTER)
                <h5>You <b>joined <a href="user.com">user.com</a></b></h5>
            @elseif($activity->activity_type_id==ACTIVITY_COMMENT)
                @if($activity->poin)
                  <h5><b>Congratulation</b>, you got points from your <b>first comment</b> in 
                      "<a href="{{ route('post-single', [$activity->content->post->id, $activity->content->post->slug]) }}">{{ $activity->content->post->title }}</a>"   
                  </h5>
                @else
                  <h5>You <b>commented in a post</b>: 
                      <a href="{{ route('post-single', [$activity->content->post->id, $activity->content->post->slug]) }}">{{ $activity->content->post->title }}</a> 
                  </h5>
                @endif
            @elseif($activity->activity_type_id==ACTIVITY_POST)
                @if($activity->content->post)
                    <h5>You <b>submitted a post</b> in "{{ $activity->content->challenge->name ?? '' }}" : 
		                    "<a href="{{ route('post-single',['id'=>$activity->content->post->id, $activity->content->post->slug]) }}">{{ $activity->content->post->title }}</a>"
                    </h5>
                @else
                    <h5>Post was deleted</h5>
                @endif
            @elseif($activity->activity_type_id==ACTIVITY_PHOTO)
                <h5>You <b>uploaded a photo in "{{ $activity->content->challenge->name ?? '' }}" </b>: 
		                "<a href="@if(isset($activity->content->photo->path)){{ secure_asset($activity->content->photo->path) }}@endif">
                      {{ $activity->content->photo->description ?? '' }}
                    </a>"
                </h5>
            @elseif($activity->activity_type_id==ACTIVITY_INFOGRAFIS)
                <h5>You <b>uploaded a graphic in "{{ $activity->content->challenge->name ?? '' }}" </b>: 
		                "<a href="{{ secure_asset($activity->content->infografis->path) }}">{{ $activity->content->infografis->description }}</a>"
                </h5>
            @elseif($activity->activity_type_id==ACTIVITY_POLL)
                <h5>You <b>filled a poll</b>: "{{ $activity->content->poll->name }}"</h5>
            @elseif($activity->activity_type_id==ACTIVITY_INVITE_MEMBER)
                <h5>You <b>invited a new member</b></h5>
            @elseif($activity->activity_type_id==ACTIVITY_REGISTER_FROM_INVITATION)
                <h5>You <b>registered using other member reference ID</b> </h5>
            @elseif($activity->activity_type_id==ACTIVITY_REFERENCE_POLLING)
                <h5>Your friend <b>filled a poll</b></h5>
            @elseif($activity->activity_type_id==ACTIVITY_PREMIUM)
                <h5><b>Congratulation</b>, your 
                    @if($activity->content->content_type_id == CONTENT_POST)
                        <a href="{{ route('post-single',['id'=>$activity->content->post->id, $activity->content->post->slug]) }}">post</a> picked as <b>Premium Content</b> : "{{ $activity->content->post->title }}"
                    @elseif($activity->content->content_type_id == CONTENT_GRAPHIC)
                        <a href="{{ secure_asset($activity->content->infografis->path) }}">graphic</a> picked as <b>Premium Content</b> : "{{ $activity->content->infografis->description }}"
                    @elseif($activity->content->content_type_id == CONTENT_PHOTO)
                        <a href="{{ secure_asset($activity->content->photo->path) }}">photo</a> picked as <b>Premium Content</b> : "{{ $activity->content->photo->description }}"
                    @endif
                </h5>
            @elseif($activity->activity_type_id==ACTIVITY_WINNER)
                <h5><b>Congratulation</b>, your 
                    @if($activity->content->content_type_id == CONTENT_POST)
                        <a href="{{ route('post-single',['id'=>$activity->content->post->id, $activity->content->post->slug]) }}">post</a> picked as <b>Challenge Winner</b> : "{{ $activity->content->post->title }}"
                    @elseif($activity->content->content_type_id == CONTENT_GRAPHIC)
                        <a href="{{ secure_asset($activity->content->infografis->path) }}">graphic</a> picked as <b>Challenge Winner</b> : "{{ $activity->content->infografis->description }}"
                    @elseif($activity->content->content_type_id == CONTENT_PHOTO)
                        <a href="{{ secure_asset($activity->content->photo->path) }}">photo</a> picked as <b>Challenge Winner</b> : "{{ $activity->content->photo->description }}"
                    @endif
                </h5>
            @elseif($activity->activity_type_id == ACTIVITY_PHOTO_SUBMITED)
                <h5>You <b>submited photo</b> in "{{ $activity->content->challenge->name }}".</h5>
            @elseif($activity->activity_type_id == ACTIVITY_PHOTO_APPROVED)
                <h5>Your photo in "{{ $activity->content->challenge->name }}" is <b>approved</b>.</h5>
            @elseif($activity->activity_type_id == ACTIVITY_PHOTO_REJECTED)
                <h5>Your photo in "{{ $activity->content->challenge->name }}" is <b>rejected</b>. <b>Note:</b> "{{$activity->content->challengeNote()->first()->description}}"</h5>
            @elseif($activity->activity_type_id == ACTIVITY_GRAPHIC_SUBMITED)
                <h5>You <b>submited graphic</b> in "{{ $activity->content->challenge->name }}".</h5>
            @elseif($activity->activity_type_id == ACTIVITY_GRAPHIC_APPROVED)
                <h5>Your graphic in "{{ $activity->content->challenge->name }}" is <b>approved</b>.</h5>
            @elseif($activity->activity_type_id == ACTIVITY_GRAPHIC_REJECTED)
                <h5>Your graphic in "{{ $activity->content->challenge->name }}" is <b>rejected</b>. <b>Note:</b> "{{$activity->content->challengeNote()->first()->description}}"</h5>
            @elseif($activity->activity_type_id == ACTIVITY_POST_SUBMITED)
                <h5>You <b>submited post</b> in "{{ $activity->content->challenge->name }}".</h5>
            @elseif($activity->activity_type_id == ACTIVITY_POST_APPROVED)
                <h5>Your post in "{{ $activity->content->challenge->name }}" is <b>approved</b>.</h5>
            @elseif($activity->activity_type_id == ACTIVITY_POST_REJECTED)
                <h5>Your post in "{{ $activity->content->challenge->name }}" is <b>rejected</b>. <b>Note:</b> "{{$activity->content->challengeNote()->first()->description}}"</h5>
            @elseif($activity->activity_type_id == ACTIVITY_PREMIUM_COMMENT)
                <h5>Your comment's liked <b>reach 100</b>, it's now a premium comment.</h5>
            @elseif($activity->activity_type_id == ACTIVITY_OTHER)
                <h5>You got point from <b>{{ $activity->remark ?? '' }}</b></h5>
            @elseif($activity->activity_type_id == ACTIVITY_ZETCON_VOTING)
                <h5>You got point from your vote in <b>{{ $activity->remark ?? '' }}</b></h5>
            @elseif($activity->activity_type_id == ACTIVITY_ARTICLE_SUBMITED)
                <h5>You <b>submited article</b> "<a href="{{ route('post-single',['id'=>$activity->content->post->id, $activity->content->post->slug]) }}">{{ $activity->content->post->title }}</a>".</h5>
            @elseif($activity->activity_type_id == ACTIVITY_ARTICLE_APPROVED)
                <h5>Your article is <b>approved by admin</b> "<a href="{{ route('post-single',['id'=>$activity->content->post->id, $activity->content->post->slug]) }}">{{ $activity->content->post->title }}</a>".</h5>
            @elseif($activity->activity_type_id == ACTIVITY_ARTICLE_REJECTED)
                <h5>Your article is <b>rejected by admin</b> "<a href="{{ route('post-single',['id'=>$activity->content->post->id, $activity->content->post->slug]) }}">{{ $activity->content->post->title }}</a>".</h5>
            @elseif($activity->activity_type_id == ACTIVITY_ARTICLE_SPAMMED)
                <h5>Your article is detected as <b>spam by admin</b> "<a href="{{ route('post-single',['id'=>$activity->content->post->id, $activity->content->post->slug]) }}">{{ $activity->content->post->title }}</a>".</h5>
            @elseif($activity->activity_type_id == ACTIVITY_NEW_BADGE)
                <h5>You got new <b>badge</b> @if($activity->remark){{ ": ".$activity->remark }} @endif</h5>
            @elseif($activity->activity_type_id == ACTIVITY_REDEEM_POINT)
                <h5>You exchanged your point for <b>{{$activity->redemptionTransaction->redemptionPrize->name}}</b></h5>
            @elseif($activity->activity_type_id == ACTIVITY_REDEMPTION_APPROVED)
                <h5>Your redemption in <b>{{ $activity->remark ?? '' }}</b> was chosen by user.com.</b></h5>
                
            @elseif($activity->activity_type_id == ACTIVITY_REDEMPTION_REJECTED)
                <h5>Your redemption in <b>{{ $activity->remark ?? '' }}</b> is not lucky for this month.</b></h5>
            @endif

            @if($activity->poin)
                (@if(in_array($activity->activity_type_id,[ACTIVITY_REDEEM_POINT,ACTIVITY_ARTICLE_SPAMMED]))- @else + @endif {{ $activity->poin }} points) - @if(isset($activity->created_at)) {{ $activity->created_at->format('d M Y - H:i:s') }} @endif
            @else
                @if(isset($activity->created_at)) {{ $activity->created_at->format('d M Y - H:i:s') }} @endif
            @endif
            <hr>
        @endforeach
        </div>
    </div>
    <span style="padding-left:10px">{!! $activities->render() !!}</span>
    <!-- /.box-body -->
</div>