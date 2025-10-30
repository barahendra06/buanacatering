@extends('layouts.app')

@section('htmlheader_title')
    {{ $title ?? '' }}
@endsection

@section('contentheader_title')
    {{  $title  ?? '' }}
@endsection

@section('contentheader_description')
    {{ $title_description ?? '' }}
@endsection

@section('main-content')
    <div class="container-fluid">
        <div class="panel panel-default box-custom">
            <div  id="notificationMenuAll" class="panel-body">
                @if(isset($allNotifications) and $allNotifications->count())
                    @foreach($allNotifications as $notif)
                        <a data-href="{{ $notif->link ?? '' }}" data-id="{{ $notif->id }}" data-type="{{ $notif->activity_type_id }}" style="color:#1e2125" class="icon-pointer">
                            <div id="NotifItemWrapperAll{{ $notif->id }}" class="row no-margin item-notification vertical-align @if(!$notif->is_read()) menu-notification-not-read @endif">
                                <div class="col-xs-2 col-md-1 no-padding text-center ">
                                    @if($notif->activity_type_id == ACTIVITY_PREMIUM_COMMENT)
                                        <i class="fa fa-star fa-2x"></i>
                                    {{--
                                    @elseif($notif->activity_type_id == ACTIVITY_PREMIUM)
                                        <i class="fa fa-star fa-2x star-silver"></i>
                                    @elseif($notif->activity_type_id == ACTIVITY_WINNER)
                                        <i class="fa fa-star fa-2x star-gold"></i>
                                    --}}
                                    @elseif($notif->activity_type_id == ACTIVITY_REDEMPTION_APPROVED or $notif->activity_type_id == ACTIVITY_REDEMPTION_REJECTED)
                                        <i class="fa fa-gift fa-3x"></i>
                                    @else
                                        <img src="{{ secure_asset($notif->sender->avatar_path) }}" class="img-responsive center-block image-sender-notification-all">
                                    @endif
                                </div>
                                <div class="col-xs-10 col-md-11 no-padding">
                                    <ul class="custom-menu-notification">
                                        <li><!-- start notification -->
                                                {{--
                                                @if($notif->activity_type_id != ACTIVITY_PREMIUM_COMMENT
                                                      and $notif->activity_type_id != ACTIVITY_PREMIUM
                                                      and $notif->activity_type_id != ACTIVITY_WINNER)
                                                --}}
                                                @if($notif->activity_type_id != ACTIVITY_PREMIUM_COMMENT)
                                                    <b>{{ $notif->sender->name ?? '' }}</b> 
                                                @endif
                                                {!! $notif->text ?? '' !!}
                                                <div class="notif-date">{{ $notif->created_at->diffForHumans() }}</div>
                                        </li><!-- end notification -->
                                    </ul>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
            <span style="padding-left:10px">{{ $allNotifications->links() }}</span>
        </div>
    </div>
@endsection

@section('content-script')
<script type="text/javascript">
    $("#notificationMenuAll").on('click', 'a', function(e){
        
        e.preventDefault();
        var type = $(this).data('type');
        var id = $(this).data('id');
        var href = $(this).data('href');
        
        $.get(baseURL + "/notification/{{ $currentMember->id }}/read/" + $(this).data('id'), function(data){

            if(type != {{ ACTIVITY_PREMIUM_COMMENT }} && 
                type != {{ ACTIVITY_PHOTO_APPROVED }} && 
                type != {{ ACTIVITY_GRAPHIC_APPROVED }} && 
                type != {{ ACTIVITY_POST_APPROVED }} && 
                type != {{ ACTIVITY_PREMIUM }} && 
                type != {{ ACTIVITY_WINNER }})   
            {
                window.location.href = href;
            }
            $('#NotifItemWrapperAll'+id).removeClass('menu-notification-not-read')
        })

    });
</script>
@endsection
