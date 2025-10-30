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
    <div class="row">
        
        <div class="col-sm-4">
            <form data-toggle="validator" role="form" method="POST" id="form_cartoon" enctype="multipart/form-data"
                          action="{{ route('sidebar-update') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <h3 class="box-title">Sidebar Cartoon Corner</h3>
                        <div class="form-group">
                            <div class="col-md-12" id="photoButtonCartoon">
                                <center>
                                    @if(isset($settings[SETTING_CARTOON]) and $settings[SETTING_CARTOON]->value)
                                        <img class="img-responsive" id="photoPreviewCartoon"
                                             src="{{ secure_asset($settings[SETTING_CARTOON]->value) }}"/>
                                    @else
                                        <img class="img-responsive" id="photoPreviewCartoon"
                                             src="{{ secure_asset('/img/no_preview.png') }}"/>
                                    @endif
                                    <span class="btn btn-default btn-file m-t-10">
                                        Choose Image<input type="file" name="photoCartoon" class="input-fit" required>
                                    </span>
                                    <button type="submit" name="submit_cartoon" value="submit" class="btn btn-primary m-t-10">Save</button>
                                    <a data-toggle="modal" data-target="#deleteSettingModal" data-delete="cartoon" class="btn delete-setting btn-danger m-t-10">Delete</a>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-4">
            <form data-toggle="validator" role="form" method="POST" id="form_challenge"  enctype="multipart/form-data"
                          action="{{ route('sidebar-update') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <h3 class="box-title">Sidebar Challenge</h3>
                        <div class="form-group">
                            <div class="col-md-12" id="photoButtonChallenge">
                                <center>
                                    @if(isset($settings[SETTING_CHALLENGE_IMAGE]) and $settings[SETTING_CHALLENGE_IMAGE]->value)
                                        <img class="img-responsive" id="photoPreviewChallenge"
                                             src="{{ secure_asset($settings[SETTING_CHALLENGE_IMAGE]->value) }}"/>
                                    @else
                                        <img class="img-responsive" id="photoPreviewChallenge"
                                             src="{{ secure_asset('/img/no_preview.png') }}"/>
                                    @endif
                                    <span class="btn btn-default btn-file">
                                        Choose Image<input type="file" name="photoChallenge" class="input-fit">
                                    </span>
                                    <div class="input-group m-t-10">
                                        <label>Challenge</label>
                                        <select class="form-control" name="challenge_id">
                                            @foreach($challenges as $challenge)
                                                @if($settings[SETTING_CHALLENGE_LINK]->value == route('post-create',$challenge->id))
                                                    <option value="{{ $challenge->id }}" selected="">
                                                        {{ $challenge->name }}
                                                    </option>
                                                @elseif($settings[SETTING_CHALLENGE_LINK]->value == route('photo-create',$challenge->id))
                                                    <option value="{{ $challenge->id }}" selected="">
                                                        {{ $challenge->name }}
                                                    </option>
                                                @elseif($settings[SETTING_CHALLENGE_LINK]->value == route('infographic-create',$challenge->id))
                                                    <option value="{{ $challenge->id }}" selected="">
                                                        {{ $challenge->name }}
                                                    </option>
                                                @elseif($settings[SETTING_CHALLENGE_LINK]->value == $challenge->landing_page_url)
                                                    <option value="{{ $challenge->id }}" selected="">
                                                        {{ $challenge->name }}
                                                    </option>
                                                @else
                                                    <option value="{{ $challenge->id }}">
                                                        {{ $challenge->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" name="submit_challenge" value="submit" class="btn btn-primary m-t-10">Save</button>
                                    <a data-toggle="modal" data-target="#deleteSettingModal" data-delete="challenge" class="btn btn-danger delete-setting m-t-10" >Delete</a>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-4">
            <form data-toggle="validator" role="form" method="POST" id="form_poll" enctype="multipart/form-data"
                          action="{{ route('sidebar-update') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <h3 class="box-title">Sidebar Polls</h3>
                        <div class="form-group">
                            <div class="col-md-12" id="photoButtonPolls">
                                <center>
                                    @if(isset($settings[SETTING_POLLS]) and $settings[SETTING_POLLS]->value)
                                        <img class="img-responsive" id="photoPreviewPolls"
                                             src="{{ secure_asset($settings[SETTING_POLLS]->value) }}"/>
                                    @else
                                        <img class="img-responsive" id="photoPreviewPolls"
                                             src="{{ secure_asset('/img/no_preview.png') }}"/>
                                    @endif
                                    <span class="btn btn-default btn-file m-t-10">
                                        Choose Image<input type="file" name="photoPolls" class="input-fit" required>
                                    </span>
                                    <button type="submit" name="submit_poll" value="submit" class="btn btn-primary m-t-10">Save</button>
                                    <a data-toggle="modal" data-target="#deleteSettingModal" data-delete="poll" class="btn btn-danger delete-setting m-t-10">Delete</a>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <form data-toggle="validator" role="form" method="POST" id="form_banner_announcement" enctype="multipart/form-data"
                          action="{{ route('sidebar-update') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <h3 class="box-title">Banner Annoucement</h3>
                        <div class="form-group">
                            <div class="col-md-12" id="photoButtonBannerAnnoucement">
                                <center>
                                    @if(isset($settings[SETTING_BANNER_ANNOUNCEMENT_IMAGE]->value) and $settings[SETTING_BANNER_ANNOUNCEMENT_IMAGE]->value)
                                        <img class="img-responsive" id="photoPreviewBannerAnnoucement"
                                             src="{{ secure_asset($settings[SETTING_BANNER_ANNOUNCEMENT_IMAGE]->value) }}"/>
                                        <span class="btn btn-default btn-file m-t-10">
                                            Choose Image<input type="file" name="photoBannerAnnoucement" class="input-fit">
                                        </span>
                                    @else
                                        <img class="img-responsive" id="photoPreviewBannerAnnoucement"
                                             src="{{ secure_asset('/img/no_preview.png') }}" style="height:200px" />
                                        <span class="btn btn-default btn-file m-t-10">
                                            Choose Image<input type="file" name="photoBannerAnnoucement" class="input-fit" required>
                                        </span>
                                    @endif

                                    <div class="form-group">
                                        <label>Link</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <input type="text" class="form-control" name="linkBannerAnnouncement"
                                                   value="{{ $settings[SETTING_BANNER_ANNOUNCEMENT_LINK]->value ?? '' }}" required>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <button type="submit" name="submit_banner_announcement" value="submit" class="btn btn-primary m-t-10">Save</button>
                                    <a data-toggle="modal" data-target="#deleteSettingModal" data-delete="banner_announcement" class="btn btn-danger delete-setting m-t-10">Delete</a>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <form data-toggle="validator" role="form" method="POST" id="form_banner_announcement_mobile" enctype="multipart/form-data"
                          action="{{ route('sidebar-update') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <h3 class="box-title">Banner Annoucement Mobile</h3>
                        <div class="form-group">
                            <div class="col-md-12" id="photoButtonBannerAnnoucementMobile">
                                <center>
                                    @if(isset($settings[SETTING_BANNER_ANNOUNCEMENT_MOBILE]->value) and $settings[SETTING_BANNER_ANNOUNCEMENT_MOBILE]->value)
                                        <img class="img-responsive" id="photoPreviewBannerAnnoucementMobile"
                                             src="{{ secure_asset($settings[SETTING_BANNER_ANNOUNCEMENT_MOBILE]->value) }}" style="height:200px"/>
                                    @else
                                        <img class="img-responsive" id="photoPreviewBannerAnnoucementMobile"
                                             src="{{ secure_asset('/img/no_preview.png') }}" style="height:200px" />
                                    @endif
                                    <span class="btn btn-default btn-file m-t-10">
                                        Choose Image<input type="file" name="photoBannerAnnoucementMobile" class="input-fit" required>
                                    </span>

                                    <button type="submit" name="submit_banner_announcement_mobile" value="submit" class="btn btn-primary m-t-10">Save</button>
                                    <a data-toggle="modal" data-target="#deleteSettingModal" data-delete="banner_announcement_mobile" class="btn btn-danger delete-setting m-t-10">Delete</a>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <form data-toggle="validator" role="form" method="POST" id="form_banner_announcement2" enctype="multipart/form-data"
                          action="{{ route('sidebar-update') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <h3 class="box-title">Banner Annoucement 2</h3>
                        <div class="form-group">
                            <div class="col-md-12" id="photoButtonBannerAnnoucement2">
                                <center>
                                    @if(isset($settings[SETTING_BANNER_ANNOUNCEMENT_IMAGE2]->value) and $settings[SETTING_BANNER_ANNOUNCEMENT_IMAGE2]->value)
                                        <img class="img-responsive" id="photoPreviewBannerAnnoucement2"
                                             src="{{ secure_asset($settings[SETTING_BANNER_ANNOUNCEMENT_IMAGE2]->value) }}" style="height:200px" />
                                        <span class="btn btn-default btn-file m-t-10">
                                            Choose Image<input type="file" name="photoBannerAnnoucement2" class="input-fit">
                                        </span>
                                    @else
                                        <img class="img-responsive" id="photoPreviewBannerAnnoucement2"
                                             src="{{ secure_asset('/img/no_preview.png') }}" style="height:200px" />
                                        <span class="btn btn-default btn-file m-t-10">
                                            Choose Image<input type="file" name="photoBannerAnnoucement2" class="input-fit" required>
                                        </span>
                                    @endif

                                    <div class="form-group">
                                        <label>Link</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <input type="text" class="form-control" name="linkBannerAnnouncement2"
                                                   value="{{ $settings[SETTING_BANNER_ANNOUNCEMENT_LINK2]->value ?? '' }}" required>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <button type="submit" name="submit_banner_announcement2" value="submit" class="btn btn-primary m-t-10">Save</button>
                                    <a data-toggle="modal" data-target="#deleteSettingModal" data-delete="banner_announcement2" class="btn btn-danger delete-setting m-t-10">Delete</a>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <form data-toggle="validator" role="form" method="POST" id="form_banner_announcement_mobile2" enctype="multipart/form-data"
                          action="{{ route('sidebar-update') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <h3 class="box-title">Banner Annoucement 2 Mobile</h3>
                        <div class="form-group">
                            <div class="col-md-12" id="photoButtonBannerAnnoucementMobile2">
                                <center>
                                    @if(isset($settings[SETTING_BANNER_ANNOUNCEMENT_MOBILE2]->value) and $settings[SETTING_BANNER_ANNOUNCEMENT_MOBILE2]->value)
                                        <img class="img-responsive" id="photoPreviewBannerAnnoucementMobile2"
                                             src="{{ secure_asset($settings[SETTING_BANNER_ANNOUNCEMENT_MOBILE2]->value) }}" style="height:200px"/>
                                    @else
                                        <img class="img-responsive" id="photoPreviewBannerAnnoucementMobile2"
                                             src="{{ secure_asset('/img/no_preview.png') }}" style="height:200px" />
                                    @endif
                                    <span class="btn btn-default btn-file m-t-10">
                                        Choose Image<input type="file" name="photoBannerAnnoucementMobile2" class="input-fit" required>
                                    </span>

                                    <button type="submit" name="submit_banner_announcement_mobile2" value="submit" class="btn btn-primary m-t-10">Save</button>
                                    <a data-toggle="modal" data-target="#deleteSettingModal" data-delete="banner_announcement_mobile2" class="btn btn-danger delete-setting m-t-10">Delete</a>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <form data-toggle="validator" role="form" method="POST" id="form_banner_announcement3" enctype="multipart/form-data"
                          action="{{ route('sidebar-update') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <h3 class="box-title">Banner Annoucement 3</h3>
                        <div class="form-group">
                            <div class="col-md-12" id="photoButtonBannerAnnoucement3">
                                <center>
                                    @if(isset($settings[SETTING_BANNER_ANNOUNCEMENT_IMAGE3]->value) and $settings[SETTING_BANNER_ANNOUNCEMENT_IMAGE3]->value)
                                        <img class="img-responsive" id="photoPreviewBannerAnnoucement3"
                                             src="{{ secure_asset($settings[SETTING_BANNER_ANNOUNCEMENT_IMAGE3]->value) }}" style="height:200px" />
                                        <span class="btn btn-default btn-file m-t-10">
                                            Choose Image<input type="file" name="photoBannerAnnoucement3" class="input-fit">
                                        </span>
                                    @else
                                        <img class="img-responsive" id="photoPreviewBannerAnnoucement3"
                                             src="{{ secure_asset('/img/no_preview.png') }}" style="height:200px" />
                                        <span class="btn btn-default btn-file m-t-10">
                                            Choose Image<input type="file" name="photoBannerAnnoucement3" class="input-fit" required>
                                        </span>
                                    @endif

                                    <div class="form-group">
                                        <label>Link</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <input type="text" class="form-control" name="linkBannerAnnouncement3"
                                                   value="{{ $settings[SETTING_BANNER_ANNOUNCEMENT_LINK3]->value ?? '' }}" required>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <button type="submit" name="submit_banner_announcement3" value="submit" class="btn btn-primary m-t-10">Save</button>
                                    <a data-toggle="modal" data-target="#deleteSettingModal" data-delete="banner_announcement3" class="btn btn-danger delete-setting m-t-10">Delete</a>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <form data-toggle="validator" role="form" method="POST" id="form_banner_announcement_mobile3" enctype="multipart/form-data"
                          action="{{ route('sidebar-update') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <h3 class="box-title">Banner Annoucement 3 Mobile</h3>
                        <div class="form-group">
                            <div class="col-md-12" id="photoButtonBannerAnnoucementMobile3">
                                <center>
                                    @if(isset($settings[SETTING_BANNER_ANNOUNCEMENT_MOBILE3]->value) and $settings[SETTING_BANNER_ANNOUNCEMENT_MOBILE3]->value)
                                        <img class="img-responsive" id="photoPreviewBannerAnnoucementMobile3"
                                             src="{{ secure_asset($settings[SETTING_BANNER_ANNOUNCEMENT_MOBILE3]->value) }}" style="height:200px"/>
                                    @else
                                        <img class="img-responsive" id="photoPreviewBannerAnnoucementMobile3"
                                             src="{{ secure_asset('/img/no_preview.png') }}" style="height:200px" />
                                    @endif
                                    <span class="btn btn-default btn-file m-t-10">
                                        Choose Image<input type="file" name="photoBannerAnnoucementMobile3" class="input-fit" required>
                                    </span>

                                    <button type="submit" name="submit_banner_announcement_mobile3" value="submit" class="btn btn-primary m-t-10">Save</button>
                                    <a data-toggle="modal" data-target="#deleteSettingModal" data-delete="banner_announcement_mobile3" class="btn btn-danger delete-setting m-t-10">Delete</a>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <form data-toggle="validator" role="form" method="POST" id="form_sidebar_announcement"  enctype="multipart/form-data"
                          action="{{ route('sidebar-update') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-default box-custom">
                    <div class="panel-body">
                        <h3 class="box-title">Sidebar Annoucement</h3>
                        <div class="form-group">
                            <div class="col-md-12" id="photoButtonSidebarAnnoucement">
                                <center>
                                    @if(isset($settings[SETTING_SIDEBAR_ANNOUNCEMENT_IMAGE]->value) and $settings[SETTING_SIDEBAR_ANNOUNCEMENT_IMAGE]->value)
                                        <img class="img-responsive" id="photoPreviewSidebarAnnoucement"
                                             src="{{ secure_asset($settings[SETTING_SIDEBAR_ANNOUNCEMENT_IMAGE]->value) }}"/>
                                         <span class="btn btn-default btn-file m-t-10">
                                            Choose Image<input type="file" name="photoSidebarAnnoucement" class="input-fit">
                                        </span>
                                    @else
                                        <img class="img-responsive" id="photoPreviewSidebarAnnoucement"
                                             src="{{ secure_asset('/img/no_preview.png') }}" style="height:200px" />
                                         <span class="btn btn-default btn-file m-t-10">
                                            Choose Image<input type="file" name="photoSidebarAnnoucement" class="input-fit" required>
                                        </span>
                                    @endif
                                    

                                    <div class="form-group">
                                        <label>Link</label>

                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <input type="text" class="form-control" name="linkSidebarAnnouncement"
                                                   value="{{ $settings[SETTING_SIDEBAR_ANNOUNCEMENT_LINK]->value ?? '' }}" required>
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <button type="submit" name="submit_sidebar_announcement" value="submit" class="btn btn-primary m-t-10">Save</button>
                                    <a data-toggle="modal" data-target="#deleteSettingModal" data-delete="sidebar_announcement" class="btn btn-danger delete-setting m-t-10">Delete</a>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <form role="form" method="POST" action="{{ route('youtube-update') }}">
    <div class="row ">
        <div class="col-sm-12">
            <div class="panel panel-default box-custom">
                <div class="panel-body">
                    <div class="row">
                    <div class="col-sm-6">
                        <h3 class="box-title">Youtube 1</h3>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <!-- title -->
                        <div class="form-group">
                            <label>Title</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="value[1]"
                                       value="@if(isset($settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[0])){{ $settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[0]->value }}@endif">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- link embedded -->
                        <div class="form-group">
                            <label>Link Embedded</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="value[2]"
                                       value="@if(isset($settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[1])){{ $settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[1]->value }}@endif">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h3 class="box-title">Youtube 2</h3>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <!-- title -->
                        <div class="form-group">
                            <label>Title</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="value[3]"
                                       value="@if(isset($settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[2])){{ $settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[2]->value }}@endif">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- link embedded -->
                        <div class="form-group">
                            <label>Link Embedded</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="value[4]"
                                       value="@if(isset($settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[3])){{ $settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[3]->value }}@endif">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-6">
                        <h3 class="box-title">Youtube 3</h3>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <!-- title -->
                        <div class="form-group">
                            <label>Title</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="value[5]"
                                       value="@if(isset($settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[4])){{ $settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[4]->value }}@endif">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- link embedded -->
                        <div class="form-group">
                            <label>Link Embedded</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="value[6]"
                                       value="@if(isset($settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[5])){{ $settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[5]->value }}@endif">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h3 class="box-title">Youtube 4</h3>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <!-- title -->
                        <div class="form-group">
                            <label>Title</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="value[7]"
                                       value="@if(isset($settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[6])){{ $settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[6]->value }}@endif">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- link embedded -->
                        <div class="form-group">
                            <label>Link Embedded</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="value[8]"
                                       value="@if(isset($settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[7])){{ $settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[7]->value }}@endif">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-6">
                        <h3 class="box-title">Youtube 5</h3>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <!-- title -->
                        <div class="form-group">
                            <label>Title</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="value[9]"
                                       value="@if(isset($settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[8])){{ $settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[8]->value }}@endif">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- link embedded -->
                        <div class="form-group">
                            <label>Link Embedded</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="value[10]"
                                       value="@if(isset($settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[9])){{ $settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[9]->value }}@endif">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h3 class="box-title">Youtube 6</h3>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <!-- title -->
                        <div class="form-group">
                            <label>Title</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="value[11]"
                                       value="@if(isset($settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[10])){{ $settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[10]->value }}@endif">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- link embedded -->
                        <div class="form-group">
                            <label>Link Embedded</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="value[12]"
                                       value="@if(isset($settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[11])){{ $settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[11]->value }}@endif">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>                    
                    </div>
                    <div class="row">
                    <div class="col-sm-6">
                        <h3 class="box-title">Youtube 7</h3>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <!-- title -->
                        <div class="form-group">
                            <label>Title</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="value[13]"
                                       value="@if(isset($settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[12])){{ $settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[12]->value }}@endif">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- link embedded -->
                        <div class="form-group">
                            <label>Link Embedded</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <input type="text" class="form-control" name="value[14]"
                                       value="@if(isset($settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[13])){{ $settings->whereIn('id',SETTING_YOUTUBE_ARRAY)[13]->value }}@endif">
                            </div>
                            <!-- /.input group -->
                        </div>
                    </div>                  
                    </div>                    
                    <div class="row">
                        <div class="col-sm-6 col-sm-push-3">
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-success">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </form>

    <div class="row ">
        <div class="col-sm-12">
            <div class="panel panel-default box-custom">
                <div class="panel-body">
                    <form role="form" method="POST" action="{{ route('sidebar-update') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label>Post Popup ID</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-link"></i>
                                </div>
                                <input type="number" class="form-control" name="linkPopup"
                                       value="@if(isset($popupID)){{ $popupID->value }}@endif">
                            </div>
                            <!-- /.input group -->
                        </div>
                        <button type="submit" name="submit_popup" value="submit" class="btn btn-primary m-t-10">Save</button>
                        <a data-toggle="modal" data-target="#deleteSettingModal" data-delete="popup" class="btn btn-danger delete-setting m-t-10">Delete</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="deleteSettingModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Caution</h4>
          </div>
          <div class="modal-body">
            <p>Are you sure to delete this Sidebar ?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-flat float-left" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-success btn-flat float-left" data-dismiss="modal" id="yes">Yes</button>
          </div>
        </div>

      </div>
    </div>
@endsection

@section('content-script')
    <script type="text/javascript">
        // <<<<< image preview
        function readURL(input, previewContainer) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#' + previewContainer).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $('#photoButtonCartoon').on('change', '.btn-file :file', function () {
            readURL(this, 'photoPreviewCartoon');
        });
        $('#photoButtonChallenge').on('change', '.btn-file :file', function () {
            readURL(this, 'photoPreviewChallenge');
        });
        $('#photoButtonPolls').on('change', '.btn-file :file', function () {
            readURL(this, 'photoPreviewPolls');
        });
        $('#photoButtonBannerAnnoucement').on('change', '.btn-file :file', function () {
            readURL(this, 'photoPreviewBannerAnnoucement');
        });
        $('#photoButtonBannerAnnoucement2').on('change', '.btn-file :file', function () {
            readURL(this, 'photoPreviewBannerAnnoucement2');
        });
        $('#photoButtonBannerAnnoucement3').on('change', '.btn-file :file', function () {
            readURL(this, 'photoPreviewBannerAnnoucement3');
        });
        $('#photoButtonSidebarAnnoucement').on('change', '.btn-file :file', function () {
            readURL(this, 'photoPreviewSidebarAnnoucement');
        });
        $('#photoButtonBannerAnnoucementMobile').on('change', '.btn-file :file', function () {
            readURL(this, 'photoPreviewBannerAnnoucementMobile');
        });
        $('#photoButtonBannerAnnoucementMobile2').on('change', '.btn-file :file', function () {
            readURL(this, 'photoPreviewBannerAnnoucementMobile2');
        });
        $('#photoButtonBannerAnnoucementMobile3').on('change', '.btn-file :file', function () {
            readURL(this, 'photoPreviewBannerAnnoucementMobile3');
        });


        var objectDelete="";
        $('#yes').on('click', function(){
            var input = $("<input>")
               .attr("type", "hidden")
               .attr("name", "delete_"+objectDelete).val("delete");
            $("#form_"+objectDelete).append($(input));
            $( "#form_"+objectDelete ).submit();
            
        });
        $( ".delete-setting" ).click(function() {
            console.log($(this).data("delete"));
            $(".input-fit").removeAttr('required');
            objectDelete = $(this).data("delete");
        });
        $('#deleteSettingModal').on('hidden.bs.modal', function () {
            console.log("cancel delete");
            $(".input-fit").attr('required',"true");
        })
    </script>
@endsection
