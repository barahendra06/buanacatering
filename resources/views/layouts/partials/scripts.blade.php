<!-- REQUIRED JS SCRIPTS -->

<!-- sweet alert -->
<script src="{{ secure_asset('plugins/sweetalert/dist/sweetalert.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{ secure_asset('plugins/sweetalert/dist/sweetalert.css') }}">

<!-- jQuery 2.1.4 -->
<script src="{{ secure_asset('/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="{{ secure_asset('js/tag-it.min.js') }}" type="text/javascript" charset="utf-8"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="{{ secure_asset('/js/bootstrap.min.js') }}" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="{{ secure_asset('/js/app.min.js') }}" type="text/javascript"></script>

<!-- Datatables -->
<script src="{{ secure_asset('/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ secure_asset('/js/dataTables.responsive.min.js') }}"></script>	
<!-- jquery form -->
<script src="{{ secure_asset('/js/jquery.form.min.js') }}"></script>
<!-- bootstrap toggle -->
<script src="{{ secure_asset('/js/bootstrap-toggle.min.js') }}"></script>
<!-- <script src="{{ secure_asset('/js/bootstrap2-toggle.min.js') }}"></script> -->
<script type="text/javascript" src="{{ secure_asset('js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ secure_asset('js/bootstrap-datetimepicker.min.js') }}"></script>

<!-- Include js plugin -->
<script src="{{ secure_asset('/js/owl.carousel.js') }}"></script>

<!-- Multiselect -->		
<script src="{{ secure_asset('/js/jquery.multi-select.js') }}"></script>		
<script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>  

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-73652975-7"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-73652975-7');
</script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience. Slimscroll is required when using the
      fixed layout. -->