<head>
    <meta charset="UTF-8">
    <title>@yield('htmlheader_title') - Bu Ana Catering</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    
    <!-- for ajax post method security token in laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ secure_asset('/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="{{ secure_asset('/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- Ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ secure_asset('/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ secure_asset('/css/userbackend-v2.css') }}" rel="stylesheet">
    <link href="{{ secure_asset('/css/buanacateringbackend.css?v4') }}" rel="stylesheet">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link href="{{ secure_asset('/css/skins/skin-blue.css') }}" rel="stylesheet" type="text/css" />

    <!-- Datatables -->
	<link href="{{ secure_asset('/css/jquery.dataTables.min.css') }}" rel="stylesheet">
	<link href="{{ secure_asset('/css/responsive.dataTables.min.css') }}" rel="stylesheet">


    <!-- Important Owl stylesheet -->
    <link rel="stylesheet" href="{{ secure_asset('/css/owl.carousel.css') }}">
     
    <!-- Default Theme -->
    <link rel="stylesheet" href="{{ secure_asset('/css/owl.theme.css') }}">
     
	
	<!-- Multiselect -->
	<link href="{{ secure_asset('/css/multi-select.css') }}" rel="stylesheet">

    <!-- Datetimepicker -->
   <link href="{{ secure_asset('/css/bootstrap-datetimepicker.css') }}" rel="stylesheet">
	
    <!-- iCheck -->
    <link href="{{ secure_asset('/plugins/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ secure_asset('plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/photoswipe.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/default-skin/default-skin.css') }}">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <link href="{{ secure_asset('css/jquery.tagit.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ secure_asset('css/tagit.ui-zendesk.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ secure_asset('/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
    <!-- <link href="{{ secure_asset('/css/bootstrap2-toggle.min.css') }}" rel="stylesheet"> -->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    @stack('content-header')
    
</head>
