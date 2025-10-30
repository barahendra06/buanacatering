    {{--FOOTER--}}

<div id="footer-top" class="row">
    <div class="col-12 col-md-6 col-lg-3" id="footer-social-media">
        <div class="row">
                   
            <div class="col-2 offset-2 offset-lg-4 p-0">
                    <div class="social-media-border-bottom p-0">
                        <div class="social-media-icon py-2"><i class="fab fa-facebook-f"></i>
                        </div>
                    </div>
            </div>
            <div class="col-2 p-0">
                    <div class="social-media-border-bottom p-0">
                        <div class="social-media-icon py-2"><i class="fab fa-twitter"></i>
                        </div>
                    </div>
            </div>
            <div class="col-2 p-0">
                    <div class="social-media-border-bottom p-0">
                        <div class="social-media-icon py-2 social-media-border-right"><i class="fab fa-instagram"></i>
                        </div>
                    </div>
            </div>
            <div class="col-2 p-0">
                    <div class="social-media-border-bottom p-0">
                        <div class="social-media-icon py-2 social-media-border-right"><i class="fab fa-whatsapp"></i>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<div id="footer-bottom" class="row">
    <div class="container">
        <div class="row mt-5">
            <div class="col-12 col-lg-8 footer-menu ">
            </div>
            <div class="col-12 col-lg-2 offset-lg-2 text-lg-left text-center">
            </div>
        </div>
    </div>
</div>




@if (Session::has('messagepopup'))
<div id="messagePopup" class="all-screen">
    <div class="popupContainer popup-message">
        <section class="popupBody" id="messagePopupBody" data-section="{{ Session::get('messagepopup') }}">
        </section>
    </div>
</div>
@endif
@if (isset($messagepopup))
<div id="messagePopup" class="all-screen">
    <div class="popupContainer popup-message">
        <section class="popupBody" id="messagePopupBody" data-section="{{ $messagepopup }}">
        </section>
    </div>
</div>
@endif

@if (session('success'))
    <div class="flash-message">
        <div class="alert alert-success">
        
        </div>
    </div>
@endif

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

{{-- Display and Script for photo viewer --}}
@include('photo_viewer')

<script src="{{secure_asset("js/jquery.leanModal.min.js")}}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

<script>
    // Plugin options and our code
    $(".modal_trigger").leanModal({
        overlay: 0.6,
        closeButton: ".modal_close"
    });
    $("#modal_trigger1").leanModal({
        overlay: 0.6,
        closeButton: ".modal_close"
    });

    $(".modal-trigger-poll").leanModal({
        overlay: 0.6,
        closeButton: ".modal_close"
    });

    $("#goToRegistrationEvent").leanModal({
        overlay: 0.6,
        closeButton: ".modal_close"
    });

    $(".btn-subscribe").leanModal({
        overlay: 0.6,
        closeButton: ".modal_close"
    });



    $('#modal').on('close.leanModal', function(e) { alert('The modal is now closed.') });

    //prevent user input manually
    $('input#datepicker').bind('keyup keydown keypress', function (evt) {
       return false;
    });

    $(function () {
        // Calling Login Form
        $("#doYouHaveMember").click(function () {
            $(".user_register").hide();
            $(".user_register_next_page").hide();
            $(".user_login").show();
            $(".resend_email").hide();
            $(".forgot_password").hide();
            return false;
        });

        $("#login_form").click(function () {
            $(".user_register").hide();
            $(".user_register_next_page").hide();
            $(".user_login").show();
            $(".resend_email").hide();
            $(".forgot_password").hide();
            return false;
        });

        $(".login1").click(function () {
            $(".user_register").hide();
            $(".user_register_next_page").hide();
            $(".user_login").show();
            $(".resend_email").hide();
            $(".forgot_password").hide();
            return false;
        });
        

        // Calling Register Form
        $("#register_form").click(function () {
            $(".user_login").hide();
            $(".user_register").show();
            $(".user_register_next_page").hide();
            $(".resend_email").hide();
            $(".forgot_password").hide();
            return false;
        });
        $(".btn-subscribe").click(function () {
            $(".user_login").hide();
            $(".user_register").show();
            $(".user_register_next_page").hide();
            $(".resend_email").hide();
            $(".forgot_password").hide();
            $('html, body').animate({ scrollTop: 0 }, 'fast');
            return false;
        });

        $(".register1").click(function () {
            $(".user_login").hide();
            $(".user_register").show();
            $(".user_register_next_page").hide();
            $(".resend_email").hide();
            $(".forgot_password").hide();
            return false;
        });

        // Calling Register Form
        $("#previous_form").click(function () {
            $(".user_login").hide();
            $(".user_register").show();
            $(".user_register_next_page").hide();
            $(".resend_email").hide();
            $(".forgot_password").hide();
            return false;
        });
        
        // clear error border when input is focus
        $(".register-validate").focus(function(){
            $(this).removeClass('error-column');

            // remove error text below password input
            if($(this).attr('id') == "register_password_confirm" || $(this).attr('id') == "register_password")
            {
                $('#error_password_confirm').text('');
                $("#register_password").removeClass('error-column');
                $("#register_password_confirm").removeClass('error-column');
            }

            // remove error text below email input
            if($(this).attr('id') == "register_email")
            {
                $('#error_email').text('');
            }
            
        });

        // next button clicked
        $("#next_form").click(function () {
            isError = false;
            // check all input that has 'register-validate' class, then give error border if that input is blank
            for (var i = 0; i < $('.register-validate').length; i++) {
                if($($('.register-validate')[i]).val().trim() == '')
                {
                    $($('.register-validate')[i]).addClass('error-column');
                    isError = true;
                }
            }
            console.log(isError);
            if(isError != true)
            {
                $(".user_login").hide();
                $(".user_register").hide();
                $(".user_register_next_page").show();
                $(".resend_email").hide();
                $(".forgot_password").hide();
            }
            return false;
        });


        $("#btn_register").click(function () {
            
            var isError = false;
            var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
            if (!testEmail.test($('#register_email').val()))
            {
                $('#register_email').addClass('error-column');
                $('#error_email').text('This email not valid.');
                isError = true;
            }

            if($('#register_password').val() !=  $('#register_password_confirm').val())
            {
                $('#register_password').addClass('error-column');
                $('#register_password_confirm').addClass('error-column');
                $('#error_password_confirm').text('Password is not match.');
                isError = true;
            }

            if($('#register_password').val().trim() == "")
            {
                $('#register_password').addClass('error-column');
                isError = true;
            }

            // check all input that has 'register-validate' class, then give error border if that input is blank
            for (var i = 0; i < $('.register-validate').length; i++) {
                if($($('.register-validate')[i]).val().trim() == '')
                {
                    $($('.register-validate')[i]).addClass('error-column');
                    isError = true;
                    console.log($($('.register-validate')[i]));
                }
            };
            // console.log($('.register-validate'));
            if(isError == true)
            {
                return false;
            }
            else
            {
                return true;
            }
        });

        $("#previous_form").click(function () {
            $(".user_login").hide();
            $(".user_register").show();
            $(".user_register_next_page").hide();
            $(".resend_email").hide();
            $(".forgot_password").hide();
            return false;
        });

        $("#resend").click(function () {
            $(".user_login").hide();
            $(".user_register").hide();
            $(".resend_email").show();
            $(".user_register_next_page").hide();
            $(".forgot_password").hide();
            return false;
        }); 

        $("#password-form").click(function () {
            $(".user_login").hide();
            $(".user_register").hide();
            $(".resend_email").hide();
            $(".user_register_next_page").hide();
            $(".forgot_password").show();
            return false;
        }); 

        


        //
        var d = new Date();
        var month = d.getMonth();
        var day = d.getDate();
        var yearMax = d.getFullYear()-{{ MINIMUM_AGE }};
        var yearMin = d.getFullYear()-{{ MAXIMUM_AGE }};
        // alert(yearMax+" "+yearMin);
        $('#datepicker').datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            minDate: new Date(yearMin, month, day+1),
            maxDate: new Date(yearMax, month, day)
        });

        @if(isset($firstPopup) and $firstPopup and isset($postPopup) and $postPopup)
        // use popup for first time visit this website
            var visited = jQuery.cookie('visited');
            if (visited == 'yes') {
                 // second page load, cookie active
            } 
            else 
            {
                $.ajax({
                    type : 'get',
                    url : "{{ route('popup-show', [$postPopup->id, $postPopup->slug]) }}",
                    success : function( data ) {
                        $('#firstTimePopup').modal({
                            backdrop: false 
                        });
                        $('#firstTimePopup').on('shown.bs.modal', function(){
                            $('#bodyFirstTimePopup').html(data);
                        });
                        $('#firstTimePopup').on('hidden.bs.modal', function(){
                            $('#bodyFirstTimePopup').data('');
                        });
                    }
                });
            }
            jQuery.cookie('visited', 'yes', {
                expires: '' // the number of days cookie  will be effective
            });
        @endif

        // carousel national challenge user
        $("#user-public-challenge").owlCarousel({
            autoPlay: 3000, //Set AutoPlay to 3 seconds
            items : 4,
            lazyLoad : true,
            itemsDesktopSmall : [786,3], 
            itemsTablet: [700,2], 
            itemsMobile : [450,1] 
        }); 

    });

</script>

<!-- search button clicked -->
<script type="text/javascript">

    $('.search-lup').on('click', function()
    {
        $.get(baseURL + "/search", function( data ) {
            $('#modalSearch').modal({
                backdrop: false 
            });
            $('#modalSearch').on('shown.bs.modal', function(){
                $('#modalSearch .load_modal').html(data);
            });
            $('#modalSearch').on('hidden.bs.modal', function(){
                $('#modalSearch .modal-body').data('');
            });
        });
    });


    @if (Session::has('messagepopup'))
        // event click for hiding messagePopup
        $('#messagePopup').click(function(){
            $('#messagePopup').hide();
        });

        $(function () {
            if($('#messagePopupBody').data('section') == 'success'){
                $.get("{{ route('register-success') }}"+"?name={{ session('name') }}&email={{ session('email') }}", function( data ) {
                    $('#messagePopupBody').html(data);
                });   
            }
        });
    @endif

    $('#lean_overlay').click(function(){

        $(".user_login").show();
        $(".user_register").hide();
        $(".resend_email").hide();
        $(".user_register_next_page").hide();
        $(".forgot_password").hide();
        $('#messagePopup').hide();
        return false;
    });
</script>


    
