<div class="col-xs-12 no-padding">
	<h3>
		Thank you <b>{{$name ?? ''}}</b>, for signing up with buanacatering.com
	</h3>
	<p>We already sent confirmation email to <b>{{ $email ?? '' }}</b>. Please check your email and verifiy your address or resend the verification. Check your spam folder to make sure it didn't end up there</p>
</div>
<br>
<br>
<div class="row">
	<div class="col-xs-12 text-center no-padding">
		if you doesn't receive any email click this button
	</div>
	<div class="col-xs-12 no-padding text-center" id="resend_button">
		<a class="btn btn-black modal-resend" href="#modal">RESEND CONFIRMATION E-MAIL</a>
	</div>
</div>

<script type="text/javascript">
	
        $(".modal-resend").leanModal({
        //        top: 100,
            overlay: 0.6,
            closeButton: ".modal_close"
        });
        $(".modal-resend").click(function () {
            $(".user_login").hide();
            $(".user_register").hide();
            $(".resend_email").show();
            $(".user_register_next_page").hide();
            $(".forgot_password").hide();
            $('#messagePopup').hide();
            return false;
        }); 
</script>