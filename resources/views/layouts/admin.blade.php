<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{$title.TITLE_FOR_LAYOUT}}</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{!! FAVICON_PATH !!}" type="image/x-icon"/>
  <link rel="icon" href="{!! FAVICON_PATH !!}" type="image/x-icon"/>


  {{ HTML::style('public/css/bootstrap3.min.css')}}
  {{ HTML::style('public/css/AdminLTE.min.css')}}
  {{ HTML::style('public/css/all-skins.min.css')}}
  {{ HTML::style('public/css/admin.css')}}
  {{ HTML::style('public/css/font-awesome.css')}}



  {{ HTML::script('public/js/jquery-2.1.0.min.js')}}
  {{ HTML::script('public/js/bootstrap3.min.js')}}
  {{ HTML::script('public/js/jquery.validate.js')}}
  {{ HTML::script('public/js/app.min.js')}}
  {{ HTML::script('public/js/ajaxsoringpagging.js')}}
  {{ HTML::script('public/js/listing.js')}}


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      <style type="text/css">
        .fc-past{pointer-events: none;opacity: 0.5;cursor: not-allowed;}
        .fc-offday{pointer-events: none;cursor: not-allowed;background:#ecdddb;}

        .fc-past:hover {cursor: not-allowed!important;}
        #space-div{display:none;height:155px;}
        .fc-unthemed th, .fc-unthemed td, .fc-unthemed thead, .fc-unthemed tbody, .fc-unthemed .fc-divider, .fc-unthemed .fc-row, .fc-unthemed .fc-content, .fc-unthemed .fc-popover, .fc-unthemed .fc-list-view, .fc-unthemed .fc-list-heading td{border-color: #cec9c9!important;}
      </style>
      
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
      <div class="wrapper">
        @include('elements.admin.header')
        @include('elements.admin.left_menu')
        @yield('content')
      </div>

    </body>

    </html>