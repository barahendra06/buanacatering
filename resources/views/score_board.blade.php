{{--  check if scoreboards data not empty  --}}
@if(isset($scoreBoards) && $scoreBoards != NULL)
    <div class="container">
        <div class="row clearfix">
                <div class="scoreboard-container col-12 mt-1" style="padding-left:0px; padding-right: 0px;">

                        <!-- START horizontal accordion -->
                        <div
                            class="panel-group horizontal"
                            id="accordion"
                            role="tablist"
                            aria-multiselectable="true"
                            >
                            {{--  START looping for match category (ex: NBA, JP-PRO, etc)  --}}
                                @foreach ($scoreBoards as $key => $dt)
                                    <div class="panel panel-scoreboard" 
                                            @if ($loop->first) 
                                                style="width: 100%" 
                                            @endif>
                                        <a class="panel-heading scoreboard-panel-heading" id="heading{{$loop->index}}" role="button"
                                                        data-toggle="collapse"
                                                        data-parent="#accordion"
                                                        data-target="#collapse{{$loop->index}}"
                                                        aria-expanded="true"
                                                        aria-controls="collapse{{$loop->index}}">
                                            <h4 class="panel-title">

                                                {{--  show tournament category name  --}}
                                                <div class="scoreboard-category text-center">{{ strtoupper($key) }}</div>
                                            </h4>
                                        </a>
                                        <div
                                            id="collapse{{$loop->index}}"
                                            class="panel-collapse collapse @if ($loop->first) show @endif"
                                            aria-labelledby="heading{{$loop->index}}"
                                            data-parent="#accordion"
                                            >
                                            <div class="panel-body">
                                            
                                                <div class='row'>
                                                    <div class='col-md-12'>

                                                        {{--  START scoreboard section for big screen size(>992px)  --}}
                                                        <div class="carousel slide media-carousel d-none d-md-flex" id="{{$loop->index}}BigScreen">
                                                            <div class="carousel-inner">

                                                                    {{--  check if any match item in previous day  --}}
                                                                    @if(isset($scoreBoards[$key]['previousDay']))

                                                                            {{--  START looping for slideshow item  --}}
                                                                            @foreach($scoreBoards[$key]['previousDay'] as $keyItem => $scoreBoardsItem)
                                                                            
                                                                                {{--  if loop index is a first of each 6 item then wrap with opening slide  --}}
                                                                                @if($loop->iteration%6 == 1)

                                                                                    {{--  if it is a last slide AND there's no nextDay item, then set it to active slide  --}}
                                                                                    <div class="carousel-item 
                                                                                                    @if( $loop->remaining < 6 and !isset($scoreBoards[$key]['nextDay']))
                                                                                                        active
                                                                                                    @endif ">
                                                                                            <div class="row">
                                                                                @endif
                                                                                                {{--  START of match item  --}}
                                                                                                <div class="col-5 col-md-2 no-padding" style="max-height: 95px; font-size: 14px;">
                                                                                                    <a class="thumbnail scoreboard-thumbnail" rel="nofollow"
                                                                                                        @if($scoreBoardsItem['match_url'] != null) 
                                                                                                            href="{{$scoreBoardsItem['match_url'] ?? ''}}" target="_blank" 
                                                                                                        @else
                                                                                                            href="#"
                                                                                                        @endif >
                                                                                                        {{--  dates of each match  --}}
                                                                                                        <small class="myriad @if($loop->iteration%6 == 1) ml-3 @endif">{{ $scoreBoardsItem['dates'] }}</small> <br><br>
                                                                                                        {{--  table score of each match  --}}
                                                                                                        <table class="table-scoreboard">
                                                                                                            <tr 
                                                                                                                {{--  if team 1 is the winner then add class winner to make a font bold  --}}
                                                                                                                @if($scoreBoardsItem['score_1'] > $scoreBoardsItem['score_2']) 
                                                                                                                    class="winner" 
                                                                                                                @endif 
                                                                                                                title="{{$scoreBoardsItem['teamName_1']}}">
                                                                                                                <td>
                                                                                                                   <img src="{{asset($scoreBoardsItem['teamLogo_1'])}}" class="img img-fluid scoreboard-logo" />
                                                                                                                </td>
                                                                                                                <td>{{$scoreBoardsItem['teamAcronym_1']}}</td>
                                                                                                                <td width="15px"></td>
                                                                                                                <td class="vertical-center">
                                                                                                                    {{--  if team 1 is the winner then add a caret-left symbol  --}}
                                                                                                                    @if($scoreBoardsItem['score_1'] > $scoreBoardsItem['score_2']) 
                                                                                                                        {{$scoreBoardsItem['score_1'] ?? '-'}} <i class="fa fa-caret-left"></i>
                                                                                                                    @else
                                                                                                                        {{$scoreBoardsItem['score_1'] ?? '-'}}
                                                                                                                    @endif  
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr 
                                                                                                                {{--  if team 2 is the winner then add class winner to make a font bold  --}}
                                                                                                                @if($scoreBoardsItem['score_2'] > $scoreBoardsItem['score_1']) 
                                                                                                                    class="winner" 
                                                                                                                @endif 
                                                                                                                title="{{$scoreBoardsItem['teamName_2']}}">
                                                                                                                <td>
                                                                                                                   <img src="{{asset($scoreBoardsItem['teamLogo_2'])}}" class="img img-fluid scoreboard-logo" />
                                                                                                                </td>
                                                                                                                <td>{{$scoreBoardsItem['teamAcronym_2']}}</td>
                                                                                                                <td></td>
                                                                                                                <td class="vertical-center">
                                                                                                                    {{--  if team 2 is the winner then add a caret-left symbol  --}}
                                                                                                                    @if($scoreBoardsItem['score_2'] > $scoreBoardsItem['score_1'])
                                                                                                                        {{$scoreBoardsItem['score_2'] ?? '-'}} <i class="fa fa-caret-left"></i>
                                                                                                                    @else
                                                                                                                        {{$scoreBoardsItem['score_2'] ?? '-'}}
                                                                                                                    @endif 
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </a>
                                                                                                </div>
                                                                                                {{--  END of match item  --}}

                                                                                {{--  if loop index is a end of each 6 item OR it's the last index then wrap with end of slide  --}}
                                                                                @if($loop->iteration%6 == 0 or $loop->last)
                                                                                            </div>
                                                                                        </div>
                                                                                @endif
                                                                            @endforeach
                                                                            {{--  END looping for slideshow item  --}}
                                                                    @endif
                                                                    
                                                                    {{--  check if any match item at present or next days  --}}
                                                                    @if(isset($scoreBoards[$key]['nextDay']))
                                                                            {{--  START looping for slideshow item  --}}
                                                                            @foreach($scoreBoards[$key]['nextDay'] as $keyItem => $scoreBoardsItem)
                                                                                {{--  if loop index is a first of each 6 item then wrap with opening slide  --}}
                                                                                @if($loop->iteration%6 == 1)
                                                                                    {{--  if loop index is a first of all item, then set it to active slide  --}}
                                                                                    <div class="carousel-item 
                                                                                                    @if($loop->iteration == 1))
                                                                                                        active
                                                                                                    @endif ">
                                                                                            <div class="row">
                                                                                @endif
                                                                                                {{--  START of match item  --}}
                                                                                                <div class="col-5 col-md-2 no-padding" style="max-height: 95px; font-size: 14px;">
                                                                                                    <a class="thumbnail scoreboard-thumbnail" rel="nofollow"
                                                                                                        @if($scoreBoardsItem['match_url'] != null) 
                                                                                                            href="{{$scoreBoardsItem['match_url'] ?? ''}}" target="_blank" 
                                                                                                        @else
                                                                                                            href="#"
                                                                                                        @endif >
                                                                                                        {{--  dates of each match  --}}
                                                                                                        <small class="myriad @if($loop->iteration%6 == 1) ml-3 @endif">{{ $scoreBoardsItem['dates'] }}</small> <br><br>
                                                                                                        {{--  table score of each match  --}}
                                                                                                        <table class="table-scoreboard">
                                                                                                            <tr 
                                                                                                                {{--  if team 1 is the winner then add class winner to make a font bold  --}}
                                                                                                                @if($scoreBoardsItem['score_1'] > $scoreBoardsItem['score_2']) 
                                                                                                                    class="winner" 
                                                                                                                @endif 
                                                                                                                title="{{$scoreBoardsItem['teamName_1']}}">
                                                                                                                <td>
                                                                                                                   <img src="{{asset($scoreBoardsItem['teamLogo_1'])}}" class="img img-fluid scoreboard-logo" />
                                                                                                                </td>
                                                                                                                <td>{{$scoreBoardsItem['teamAcronym_1']}}</td>
                                                                                                                <td width="15px"></td>
                                                                                                                <td class="vertical-center">
                                                                                                                    {{--  if team 1 is the winner then add a caret-left symbol  --}}
                                                                                                                    @if($scoreBoardsItem['score_1'] > $scoreBoardsItem['score_2']) 
                                                                                                                        {{$scoreBoardsItem['score_1'] ?? '-'}} <i class="fa fa-caret-left"></i>
                                                                                                                    @else
                                                                                                                        {{$scoreBoardsItem['score_1'] ?? '-'}}
                                                                                                                    @endif  
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr 
                                                                                                                {{--  if team 2 is the winner then add class winner to make a font bold  --}}
                                                                                                                @if($scoreBoardsItem['score_2'] > $scoreBoardsItem['score_1']) 
                                                                                                                    class="winner" 
                                                                                                                @endif 
                                                                                                                title="{{$scoreBoardsItem['teamName_2']}}">
                                                                                                                <td>
                                                                                                                   <img src="{{asset($scoreBoardsItem['teamLogo_2'])}}" class="img img-fluid scoreboard-logo" />
                                                                                                                </td>
                                                                                                                <td>{{$scoreBoardsItem['teamAcronym_2']}}</td>
                                                                                                                <td></td>
                                                                                                                <td class="vertical-center">
                                                                                                                    {{--  if team 2 is the winner then add a caret-left symbol  --}}
                                                                                                                    @if($scoreBoardsItem['score_2'] > $scoreBoardsItem['score_1'])
                                                                                                                        {{$scoreBoardsItem['score_2'] ?? '-'}} <i class="fa fa-caret-left"></i>
                                                                                                                    @else
                                                                                                                        {{$scoreBoardsItem['score_2'] ?? '-'}}
                                                                                                                    @endif 
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                    </a>
                                                                                                </div>
                                                                                                {{--  END of match item  --}}

                                                                                {{--  if loop index is a end of each 6 item OR it's the last index then wrap with end of slide  --}}
                                                                                @if($loop->iteration%6 == 0 or $loop->last)
                                                                                            </div>
                                                                                        </div>
                                                                                @endif
                                                                            @endforeach
                                                                            {{--  END looping for slideshow item  --}}
                                                                    @endif
                                                            </div>
                                                            <a class="carousel-control-prev" href="#{{$loop->index}}BigScreen" role="button" data-slide="prev">
                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Previous</span>
                                                            </a>
                                                            <a class="carousel-control-next" href="#{{$loop->index}}BigScreen" role="button" data-slide="next">
                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Next</span>
                                                            </a>
                                                        </div>
                                                        {{--  END scoreboard section for big screen size(>992px)  --}}

                                                        {{--  START scoreboard section for small screen size(<992px)  --}}
                                                        <div class="carousel slide media-carousel  d-flex d-md-none" id="{{$loop->index}}SmallScreen">
                                                            <div class="carousel-inner">

                                                                    {{--  check if any match item in previous day  --}}
                                                                    @if(isset($scoreBoards[$key]['previousDay']))

                                                                            {{--  START looping for slideshow item  --}}
                                                                            @foreach($scoreBoards[$key]['previousDay'] as $keyItem => $scoreBoardsItem)
                                                                                
                                                                                {{--  if loop index is a first of each 2 item then wrap with opening slide  --}}
                                                                                @if($loop->iteration%2 == 1)
                                                                                    
                                                                                    {{--  if it is a last slide AND there's no nextDay item, then set it to active slide  --}}
                                                                                        <div class="carousel-item 
                                                                                                        @if($loop->remaining < 2 and !isset($scoreBoards[$key]['nextDay']))
                                                                                                            active
                                                                                                        @endif
                                                                                                        ">
                                                                                            <div class="row">
                                                                                @endif
                                                                                                {{--  START of match item  --}}
                                                                                                <div class="col-6 col-md-2 no-padding">
                                                                                                    <a class="thumbnail scoreboard-thumbnail" rel="nofollow"
                                                                                                        @if($scoreBoardsItem['match_url'] != null) 
                                                                                                            href="{{$scoreBoardsItem['match_url'] ?? ''}}" target="_blank" 
                                                                                                        @else
                                                                                                            href="#"
                                                                                                        @endif >
                                                                                                        
                                                                                                        {{--  dates of each match  --}}
                                                                                                        <small class="myriad @if($loop->iteration%2 == 1) ml-3 @endif">{{ $scoreBoardsItem['dates'] }}</small> <br>
                                                                                                        
                                                                                                        {{--  table score of each match  --}}
                                                                                                        <table class="table-scoreboard">
                                                                                                            <tr 
                                                                                                                {{--  if team 1 is the winner then add class winner to make a font bold  --}}
                                                                                                                @if($scoreBoardsItem['score_1'] > $scoreBoardsItem['score_2']) 
                                                                                                                    class="winner" 
                                                                                                                @endif 
                                                                                                                title="{{$scoreBoardsItem['teamName_1']}}">
                                                                                                                <td><img src="{{asset($scoreBoardsItem['teamLogo_1'])}}" class="img img-fluid scoreboard-logo" /></td>
                                                                                                                <td>{{$scoreBoardsItem['teamAcronym_1']}}</td>
                                                                                                                <td width="5px"></td>
                                                                                                                <td class="vertical-center">
                                                                                                                    
                                                                                                                    {{--  if team 1 is the winner then add a caret-left symbol  --}}
                                                                                                                    @if($scoreBoardsItem['score_1'] > $scoreBoardsItem['score_2']) 
                                                                                                                        {{$scoreBoardsItem['score_1'] ?? '-'}} <i class="fa fa-caret-left"></i>
                                                                                                                    @else
                                                                                                                        {{$scoreBoardsItem['score_1'] ?? '-'}}
                                                                                                                    @endif  
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr 
                                                                                                                {{--  if team 2 is the winner then add class winner to make a font bold  --}}
                                                                                                                @if($scoreBoardsItem['score_2'] > $scoreBoardsItem['score_1']) 
                                                                                                                    class="winner" 
                                                                                                                @endif 
                                                                                                                title="{{$scoreBoardsItem['teamName_2']}}">
                                                                                                                <td><img src="{{asset($scoreBoardsItem['teamLogo_2'])}}" class="img img-fluid scoreboard-logo" /></td>
                                                                                                                <td>{{$scoreBoardsItem['teamAcronym_2']}}</td>
                                                                                                                <td></td>
                                                                                                                <td class="vertical-center">
                                                                                                                    
                                                                                                                    {{--  if team 2 is the winner then add a caret-left symbol  --}}
                                                                                                                    @if($scoreBoardsItem['score_2'] > $scoreBoardsItem['score_1'])
                                                                                                                        {{$scoreBoardsItem['score_2'] ?? '-'}} <i class="fa fa-caret-left"></i>
                                                                                                                    @else
                                                                                                                        {{$scoreBoardsItem['score_2'] ?? '-'}}
                                                                                                                    @endif 
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                        <br>
                                                                                                    </a>
                                                                                                </div>
                                                                                                {{--  END of match item  --}}

                                                                                {{--  if loop index is a end of each 2 item OR it's the last index then wrap with end of slide  --}}
                                                                                @if($loop->iteration%2 == 0 or $loop->last)
                                                                                            </div>
                                                                                        </div>
                                                                                @endif
                                                                            @endforeach
                                                                            {{--  END looping for slideshow item  --}}
                                                                    @endif
                                                                    
                                                                    {{--  check if any match item at present or next days  --}}
                                                                    @if(isset($scoreBoards[$key]['nextDay']))
                                                                            {{--  START looping for slideshow item  --}}
                                                                            @foreach($scoreBoards[$key]['nextDay'] as $keyItem => $scoreBoardsItem)
                                                                                {{--  if loop index is a first of each 2 item then wrap with opening slide  --}}
                                                                                @if($loop->iteration%2 == 1)
                                                                                    {{--  if loop index is a first of all item, then set it to active slide  --}}
                                                                                    <div class="carousel-item 
                                                                                                    @if($loop->iteration == 1))
                                                                                                        active
                                                                                                    @endif
                                                                                                        ">
                                                                                            <div class="row">
                                                                                @endif
                                                                                                {{--  START of match item  --}}
                                                                                                <div class="col-6 col-md-2 no-padding">
                                                                                                    <a class="thumbnail scoreboard-thumbnail" rel="nofollow"
                                                                                                        @if($scoreBoardsItem['match_url'] != null) 
                                                                                                            href="{{$scoreBoardsItem['match_url'] ?? ''}}" target="_blank" 
                                                                                                        @else
                                                                                                            href="#"
                                                                                                        @endif >
                                                                                                        {{--  dates of each match  --}}
                                                                                                        <small class="myriad @if($loop->iteration%2 == 1) ml-3 @endif">{{ $scoreBoardsItem['dates'] }}</small> <br>
                                                                                                        {{--  table score of each match  --}}
                                                                                                        <table class="table-scoreboard">
                                                                                                            <tr 
                                                                                                                {{--  if team 1 is the winner then add class winner to make a font bold  --}}
                                                                                                                @if($scoreBoardsItem['score_1'] > $scoreBoardsItem['score_2']) 
                                                                                                                    class="winner" 
                                                                                                                @endif 
                                                                                                                title="{{$scoreBoardsItem['teamName_1']}}">
                                                                                                                <td><img src="{{asset($scoreBoardsItem['teamLogo_1'])}}" class="img img-fluid scoreboard-logo" /></td>
                                                                                                                <td>{{$scoreBoardsItem['teamAcronym_1']}}</td>
                                                                                                                <td width="15px"></td>
                                                                                                                <td class="vertical-center">
                                                                                                                    {{--  if team 1 is the winner then add a caret-left symbol  --}}
                                                                                                                    @if($scoreBoardsItem['score_1'] > $scoreBoardsItem['score_2']) 
                                                                                                                        {{$scoreBoardsItem['score_1'] ?? '-'}} <i class="fa fa-caret-left"></i>
                                                                                                                    @else
                                                                                                                        {{$scoreBoardsItem['score_1'] ?? '-'}}
                                                                                                                    @endif  
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                            <tr 
                                                                                                                {{--  if team 2 is the winner then add class winner to make a font bold  --}}
                                                                                                                @if($scoreBoardsItem['score_2'] > $scoreBoardsItem['score_1']) 
                                                                                                                    class="winner" 
                                                                                                                @endif 
                                                                                                                title="{{$scoreBoardsItem['teamName_2']}}">
                                                                                                                <td><img src="{{asset($scoreBoardsItem['teamLogo_2'])}}" class="img img-fluid scoreboard-logo" /></td>
                                                                                                                <td>{{$scoreBoardsItem['teamAcronym_2']}}</td>
                                                                                                                <td></td>
                                                                                                                <td class="vertical-center">
                                                                                                                    {{--  if team 2 is the winner then add a caret-left symbol  --}}
                                                                                                                    @if($scoreBoardsItem['score_2'] > $scoreBoardsItem['score_1'])
                                                                                                                        {{$scoreBoardsItem['score_2'] ?? '-'}} <i class="fa fa-caret-left"></i>
                                                                                                                    @else
                                                                                                                        {{$scoreBoardsItem['score_2'] ?? '-'}}
                                                                                                                    @endif 
                                                                                                                </td>
                                                                                                            </tr>
                                                                                                        </table>
                                                                                                        <br>
                                                                                                    </a>
                                                                                                </div>
                                                                                                {{--  END of match item  --}}

                                                                                {{--  if loop index is a end of each 2 item OR it's the last index then wrap with end of slide  --}}
                                                                                @if($loop->iteration%2 == 0 or $loop->last)
                                                                                            </div>
                                                                                        </div>
                                                                                @endif
                                                                            @endforeach
                                                                            {{--  END looping for slideshow item  --}}
                                                                    @endif
                                                            </div>
                                                            <a class="carousel-control-prev" href="#{{$loop->index}}SmallScreen" role="button" data-slide="prev">
                                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Previous</span>
                                                            </a>
                                                            <a class="carousel-control-next" href="#{{$loop->index}}SmallScreen" role="button" data-slide="next">
                                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                                <span class="sr-only">Next</span>
                                                            </a>
                                                        </div>
                                                        {{--  END scoreboard section for small screen size(<992px)  --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                @endforeach
                            {{--  END looping for category  --}}
                        </div>
                            </a>
                        <!-- END horizontal accordion -->
                </div>
        </div>
    </div>

    <script type="text/javascript">
    /**
        * set panel width and height
        * 
        * @returns {undefined}
        */
        function horizontalAccordion() {
            var panels = $('.panel-group.horizontal').find('.collapse');
            $.each(panels, function (idx, item) {
                $(item).removeClass('height');
                $(item).addClass('width');
                $(item).css({
                    'height': '',
                    'width': ''
                });
            });
            expandFullWidth();
            var panels = $('.panel-group.horizontal').find('.panel');
            panels.unbind('shown.bs.collapse', expandFullWidth);
            panels.on('shown.bs.collapse', expandFullWidth);
        }

        /**
        * Prevent self link to collapse its panel
        * 
        * @returns {undefined}
        */
        function preventSelfCollapsed() {
            var links = $('.panel-group.horizontal a[data-toggle="collapse"]');
            $(links).unbind('click', this);
            links.on('click', function (event) {
                var _this = $(this);
                var panel = $(_this[0].hash);
                if (panel.length && panel.hasClass('show')) {
                    _this.prop('disabled', true);
                    _this.addClass('disabled');
                    return false;
                } else {
                    _this.prop('disabled', false);
                    _this.removeClass('disabled');
                    return true;
                }
            });
        }

        /**
        * Forced the panel that has .collapse .in to be 100% width and remove the others
        * This needs javascript because the selector is the parent
        *
        * @returns {undefined}
        */
        function expandFullWidth(object) {
            var panels = $(this).closest('.panel-group.horizontal').find('.panel');
            $.each(panels, function (item, panel) {
                $(panel).css('width', '');
                var collapsePanel = $(panel).find('.collapse.show.width');
                if (collapsePanel) {
                    if ($(window).width() < 1170) {
                        //var screenSize = $(window).width();
                        collapsePanel.closest('.panel').width('100%');
                    }else{
                        var screenSize = $(window).width();
                        collapsePanel.closest('.panel').width(screenSize);
                    }
                }
            });
        }

    //        function preventSelfMinimized(){
    //            $(this).find(".panel").css('width': '100%');
    //        }

        $(document).ready(function () {
            horizontalAccordion();
            preventSelfCollapsed();
        });
    </script>

@endif