@extends('layouts.app')

@section('htmlheader_title')
    Home
@endsection


@section('main-content')
	<?php //dd($activities[1]->content->post);?>
    <div class="row">
        <div class="col-sm-12 ">
            <ul class="timeline">
            	@foreach($activities as $activity)
                <!-- timeline time label -->
                <li class="time-label">
			        <span class="bg-red">
			           	{{$activity->created_at->format('d M Y')}}
			        </span>
                </li>
                <!-- /.timeline-label -->

                <!-- timeline item -->
                <li>
                    <!-- timeline icon -->
                    @if($activity->activity_type_id==1)
            			<i class="fa fa-share-alt bg-blue"></i>
                	@elseif($activity->activity_type_id==2)
            			<i class="fa fa-star bg-blue"></i>
                	@elseif($activity->activity_type_id==3)
            			<i class="fa fa-comments bg-blue"></i>
                	@elseif($activity->activity_type_id==4)
            			<i class="fa  fa-file-o bg-blue"></i>
                	@elseif($activity->activity_type_id==5)
            			<i class="fa fa-camera bg-blue"></i>
                	@elseif($activity->activity_type_id==6)
            			<i class="fa fa-file-image-o bg-blue"></i>
                	@elseif($activity->activity_type_id==7)
            			<i class="fa fa-book bg-blue"></i>
                	@elseif($activity->activity_type_id==8)
            			<i class="fa fa-user-plus bg-blue"></i>
                	@endif
                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i>{{$activity->created_at->format('H:i')}}</span>

                        <h3 class="timeline-header">You 
                        	@if($activity->activity_type_id==1)
                        		shared <a href="#">Content</a>
                        	@elseif($activity->activity_type_id==2)
                        		join <a href="user.com">user.com</a>
                        	@elseif($activity->activity_type_id==3)
                        		comment in <a href="user.com">post</a>
                        	@elseif($activity->activity_type_id==4)
                        		submit <a href="user.com">post</a>
                        	@elseif($activity->activity_type_id==5)
                        		submit <a href="user.com">photo</a>
                        	@elseif($activity->activity_type_id==6)
                        		submit <a href="user.com">infografis</a>
                        	@elseif($activity->activity_type_id==7)
                        		fill <a href="user.com">poll</a>
                        	@elseif($activity->activity_type_id==8)
                        		invite new member
                        	@endif
                        </h3>
                        
                        <div class="timeline-body">
                            @if($activity->activity_type_id==1)
                        		shared <a href="#">Content</a>
                        	@elseif($activity->activity_type_id==2)
                        		join <a href="user.com">user.com</a>
                        	@elseif($activity->activity_type_id==3)
                        		comment in <a href="user.com">post</a>
                        	@elseif($activity->activity_type_id==4)
                        		@if(isset($activity->content->post))
                        			{{ str_limit(strip_tags($post->body), 50, '...') }}
                        		@else
                        			Post was deleted
                        		@endif
                        	@elseif($activity->activity_type_id==5)
                        		submit <a href="user.com">photo</a>
                        	@elseif($activity->activity_type_id==6)
                        		submit <a href="user.com">infografis</a>
                        	@elseif($activity->activity_type_id==7)
                        		fill <a href="user.com">poll</a>
                        	@elseif($activity->activity_type_id==8)
                        		invite new member
                        	@endif
                        </div>

                        <div class="timeline-footer">
                            <a class="btn btn-primary btn-xs">...</a>
                        </div>
                    </div>
                </li>
                <!-- END timeline item -->
                @endforeach
            </ul>
    </div>
    </div>
@endsection
