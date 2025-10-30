<!-- Main Header -->
<header class="main-header main-header-custom">

    <!-- Logo -->
    <a href="{{ route('home') }}" class="logo custom-navbar  hidden-xs">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>Z</b></span>
        <!-- logo for regular state and mobile devices -->
        <span><img class="logo-lg" src="{{secure_asset('img/logo.png')}}" class="left-block" style="width: 50px; " ></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top custom-navbar" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle visible-xs" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <a href="{{ route('home') }}" class="logo-dashboard visible-xs">
            <picture>
                <source srcset="{{ secure_asset('img/small-logo.png') }}" media="(max-width: 400px)">
                <source srcset="{{ secure_asset('img/logo.png') }}">
                <img class="img-responsive logo-dashboard-xs" src="{{ secure_asset('img/logo.png') }}">
            </picture>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Notifications Menu -->
                <li class="dropdown notifications-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-info"></i>
                        @if(isset($notificationCount) and $notificationCount>0)
                        <span class="label label-danger">{{ $notificationCount }}</span>
                        @endif
                    </a>
                    <ul class=" dropdown-menu">
                        @if(isset($notifications) and $notifications->count())
                            <li>
                                <ul id="notificationMenu"  class="menu-notification">
                                    @foreach($notifications as $notif)
                                        <li>
                                            <!-- Inner Menu: contains the notifications -->
                                            <div id="NotifItemWrapper{{ $notif->id }}" class="row no-margin item-notification vertical-align @if(!$notif->is_read()) menu-notification-not-read @endif">
                                                <div class="col-xs-3 no-padding text-center ">
                                                    @if($notif->activity_type_id == ACTIVITY_PREMIUM_COMMENT)
                                                        <i class="fa fa-star fa-2x"></i>
                                                    {{--
                                                    @elseif($notif->activity_type_id == ACTIVITY_PREMIUM)
                                                        <i class="fa fa-star fa-2x star-silver"></i>
                                                    @elseif($notif->activity_type_id == ACTIVITY_WINNER)
                                                        <i class="fa fa-star fa-2x star-gold"></i>
                                                    --}}
                                                    @elseif($notif->activity_type_id == ACTIVITY_NEW_BADGE)
                                                        <i class="fa fa-trophy fa-2x"></i>
                                                    @elseif($notif->activity_type_id == ACTIVITY_REDEMPTION_APPROVED or $notif->activity_type_id == ACTIVITY_REDEMPTION_REJECTED)
                                                        <i class="fa fa-gift fa-3x"></i>
                                                    @else
                                                        <img src="{{ route('image-show', [IMAGE_TINY, $notif->sender->avatar_path_view ]) }}" class="img-responsive center-block image-sender-notification">
                                                    @endif
                                                </div>
                                                <div class="col-xs-9 no-padding">
                                                    <ul class="menu custom-menu">
                                                        <li><!-- start notification -->
                                                            <a data-href="{{ $notif->link ?? '' }}" data-id="{{ $notif->id }}" data-type="{{ $notif->activity_type_id }}" class="notification-link no-border icon-pointer" style="white-space:normal">
                                                                {{--
                                                                @if($notif->activity_type_id != ACTIVITY_PREMIUM_COMMENT
                                                                      and $notif->activity_type_id != ACTIVITY_PREMIUM
                                                                      and $notif->activity_type_id != ACTIVITY_WINNER)
                                                                --}}
                                                                @if(!in_array($notif->activity_type_id, NOTIFICATION_WITHOUT_SUBJECT) )
                                                                    <b>{{ $notif->sender->name ?? '' }}</b> 
                                                                @endif
                                                                {!! $notif->text ?? '' !!}
                                                                <div class="notif-date">{{ $notif->created_at->diffForHumans() }}</div>
                                                            </a>
                                                        </li><!-- end notification -->
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li id="loadNotifMoreWrapper">
                                <!-- Inner Menu: contains the notifications -->
                                <ul class="menu custom-menu">
                                    <li><!-- start notification -->
                                        <a id="seeAllNotifications" href="{{ route('get-notification-all', $currentMember->id) }}" class="icon-pointer no-border text-center" style="white-space:normal">
                                           See All
                                        </a>
                                    </li><!-- end notification -->
                                </ul>
                            </li>
                        @else
                            <li class="header">No Notification</li>
                        @endif
                        <!-- <li class="footer"><a href="#">View all</a></li> -->
                    </ul>
                </li>
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @else
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="{{ route('image-show', ['tiny', $currentMember->avatar_path_view]) }}" class="user-image" alt="User Image"/>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ $currentMember->name }}</span>
                        </a>
                        <ul class="dropdown-menu custom-drop">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="{{ route('image-show', ['medium', $currentMember->avatar_path_view]) }}" class="img-circle" alt="User Image" />
                                <p>
                                    {{ $currentMember->name }}
                                    <small>Member since {{ $currentMember->created_at->diffForHumans() }}</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ route('edit-profile') }}" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ url('/logout') }}"  class="btn btn-default btn-flat"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- Control Sidebar Toggle Button -->
                <!-- <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li> -->
            </ul>
        </div>
    </nav>
</header>
