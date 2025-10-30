@extends('layouts.auth')

@section('htmlheader_title')
    Register
@endsection

@push('content-header')
<style type="text/css">
    .label-checkbox
    {
        min-height: 20px;
        padding-left: 20px !important;
        margin-bottom: 0;
        font-weight: 400;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<body class="hold-transition">

    <div class="container">

        @if (isset($error))        
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endif
        @if (isset($errors))
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif

        <!-- REGISTER FORM -->
        <form action="{{ url('/register') }}" method="post">
            <!-- Register Form page 1-->
            <div class="user_register vertical-align clear-vertical-mobile row">
                <div id="registerPromoSection" class="col-sm-6 col-xs-12">
                    <!-- /.login-logo -->
                    <img src="{{ secure_asset('img/popup-login-image.jpg') }}" class="explanation-img">
                </div>
                <div id="registerFormSection" class="col-sm-6 col-xs-12">
                    
                    <div class="register-box-body">
                        <p class="box-title">Hi, can we know more about you ?</p>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row register-column">
                            <div class="col-sm-3 direction">
                                My name is
                            </div>
                            <div class="col-sm-9 p-l-0">
                                <input type="text" class="form-control register-validate input-round" placeholder="Full name" name="name" id="register_name"/>
                            </div>
                        </div>

                        <div class="row register-column">
                            <div class="col-sm-3 direction">
                                I was born in
                            </div>
                            <div class="col-sm-9 p-l-0">
                                <input id="datepicker" class="form-control register-validate input-round" placeholder="Birth Date" name="birth_date" required/>
                                <div class="error-validation" id="error_email"></div>
                            </div>
                        </div>

                        <div class="row register-column">
                            <div class="col-sm-3 direction">
                                My gender is
                            </div>
                            <div class="col-sm-9 p-l-0">
                                <select class="form-control input-round" name="gender">
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="row register-column">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col-sm-3 direction">
                                        My phone number is
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-l-0">
                                        <input type="number" class="form-control register-validate input-round" placeholder="Phone Number" name="handphone" id="handphone"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6 col-sm-3 direction">
                                        My referral code is
                                    </div>
                                    <div class="col-md-6 col-sm-9 p-l-0">
                                        <input type="number" class="form-control input-round" placeholder="Referral Code" name="reference_id" id="handphone"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row register-column">
                            <div class="col-sm-3 direction">
                                I study at
                            </div>
                            <div class="col-sm-9 p-l-0">
                                <input type="text" class="form-control register-validate input-round" placeholder="School / Campus" name="school" id="school"/>
                            </div>
                        </div>

                        <div class="row register-column">
                            <div class="col-sm-3 direction">
                                Now I live at
                            </div>
                            <div class="col-sm-9 p-l-0">
                                <div class="col-xs-6 p-rl-0">
                                    <input type="text" class="form-control register-validate input-round" placeholder="City" name="city" id="city"/>
                                </div>

                                <div class="col-xs-6 p-rl-0">
                                    <select class="form-control input-round" name="province" id="province">
                                        @foreach($province as $prov)
                                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div id="wrap_next">
                            <button id="next_form" class="btn btn-primary btn-block btn-flat">Next</button>
                        </div><!-- /.col -->
                        <br>
                    </div>
                </div>
                <!-- /.login-box-body -->
            </div>

            <!-- Register Form page 2-->

            <div class="user_register_next_page vertical-align clear-vertical-mobile row" style="display:none">
                <div id="register2PromoSection" class="col-sm-6 col-xs-12">
                    <!-- /.login-logo -->
                    <img src="{{ secure_asset('img/popup-login-image.jpg') }}" class="explanation-img">
                </div>
                <div id="register2FormSection" class="col-sm-6 col-xs-12">
                    <div class="register-box-body">

                        <div class="row register-column">
                            <div class="col-sm-3 direction">
                                My email is
                            </div>
                            <div class="col-sm-9 p-l-0">
                                <input type="email" class="form-control register-validate2 input-round" placeholder="Email" name="email" id="register_email"/>
                                <div class="error-validation" id="error_email"></div>
                            </div>
                        </div>
                        <div class="row register-column">
                            <div class="col-sm-3 direction">
                                My password is
                            </div>
                            <div class="col-sm-9 p-l-0">
                                <input type="password" class="form-control register-validate2 input-round" placeholder="Password" name="password" id="register_password"/>
                            </div>
                        </div>
                        <div class="row register-column">
                            <div class="col-sm-3 direction">
                                Confirm password
                            </div>
                            <div class="col-sm-9 p-l-0">
                                <input type="password" class="form-control register-validate2 input-round" placeholder="Retype Password" name="password_confirmation" id="register_password_confirm"/>
                                <div class="error-validation" id="error_password_confirm"></div>
                            </div>
                        </div>

                        <div class="checkbox icheck">
                            <label class="label-checkbox">
                                <input type="checkbox" name="terms" required> I agree to the <a target="_blank" href="http://www.user.com/show/131/terms-and-condition">terms</a>
                            </label>
                        </div>
                        <div id="wrap_next">
                            <button type="submit" id="btn_register" class="btn btn-pink-round">Submit</button>
                        </div><!-- /.col -->
                        <div id="wrap_previous">
                            <a href="" id="previous_form">< Previous</a>
                        </div>
                        <!-- <a href="" id="previous_form" class="text-left">Previous</a> -->
                    </div>
                </div>
                <!-- /.login-box-body -->
            </div>
        </form>
    </div>
    <script src="{{ secure_asset('/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script type="text/javascript">
        $(function(){
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
            


            //
            var d = new Date();
            var month = d.getMonth();
            var day = d.getDate()-1;
            var yearMax = d.getFullYear()-{{ MINIMUM_AGE }};
            var yearMin = d.getFullYear()-{{ MAXIMUM_AGE }};
            // alert(yearMax+" "+yearMin);
            $('#datepicker').datepicker({
                dateFormat: 'dd-mm-yy',
                changeMonth: true,
                changeYear: true,
                minDate: new Date(yearMin, month, day),
                maxDate: new Date(yearMax, month, day)
            });
        })
    </script>
 </body>

@endsection
