@extends('layouts.admin')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $( function() {
        $("#fromsearch").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            //minDate: 'mm-dd-yyyy',
            //maxDate:'mm-dd-yyyy',
            changeYear: true,
            onClose: function(selectedDate) {
                if(selectedDate){$("#fromsearch").datepicker("option", "", selectedDate);}
            }
        });
        $("#tosearch").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            //minDate: 'mm-dd-yyyy',
            //maxDate:'mm-dd-yyyy',
            changeYear: true,
            onClose: function(selectedDate) {
                if(selectedDate){$("#tosearch").datepicker("option", "", selectedDate);}
            }
        });
    } );
</script>
<script type="text/javascript">
   function dailyfunction(){
        $('#searchingby').val("daily");
        // $('#adminSearch').submit()
        //console.log("daily");
        // $('#monthly').removeClass('btnactive');
        // $('#daily').addClass('btnactive');
    }
    function monthlyfunction(){
        $('#searchingby').val("monthly");
        // $('#adminSearch').submit()
        // $('#monthly').addClass('btnactive');
        // $('#daily').removeClass('btnactive');

        //console.log("monthly");
    }
    function customfunction(){
        $('#searchingby').val("");

    }
</script>
<style type="text/css">
    .day-box-custom ul li button{
    display: inline-block;
    color: #8b8b8b;
    font-size: 18px;
    font-family: 'robotoregular';
    background: #d6d6d6;
    border-radius: 30px;
    padding: 5px 20px;
    text-align: center;
    width: 100%;
}
.day-box-custom ul li button {
    display: inline-block;
    width: 100%;
}
.activee{
    color: #fff!important;
    background: #0084ff!important;
}

.day-box-custom ul li button,
.day-box-custom ul li button:active,
.day-box-custom ul li button:focus {
  background: #d6d6d6;
  border-style: solid;
}

</style>
<!-- <script>
    $(function(){
        $("#reportsbtn").click(function(e) {
            var todate = $('#tosearch').val();
            var fromdate = $('#fromsearch').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "<?php echo HTTP_PATH; ?>/admin/reports/custom",
                cache: false,
                type: "POST",
                data: {todate:todate, fromdate:fromdate},
                success: function(result){
                    //Console.log("Hello");
                }
            });
        });
    });
</script> -->
<script>
    $( document ).ready(function() {
            $("#reportsbtn").click(function(e) {
            $("#custom").show();
            //alert();
        });
    });
</script>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Account Reports</h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::to('admin/admins/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li class="active"> Account Reports</li>
        </ol>
    </section>

    <section class="content">
         {{ Form::open(array('url' => 'admin/reports','method' => 'post', 'id' => 'adminSearch')) }}
        <div class="box box-info">
            <div class="admin_search">
                <div class="day-box-custom" id ="days" style="padding-left: 10px">
                    <ul>
                        <li>
                            <button class="btnactive admin_ajax_search" id="daily"  onclick="dailyfunction()" >
                                <span class="day-name">Daily</span>
                                <span></span>
                            </button>
                        </li>
                        <li>
                            <button class="btnactive admin_ajax_search" id="monthly"  onclick="monthlyfunction()" >
                                <span class="day-name" >Monthly</span>
                                <span></span>
                            </button>
                        </li>
                        <li>
                            <a href="javascript:void(0)" onclick="customfunction()">
                                <span class="day-name" id="custombtn">Custom</span>
                                <span class="day-date"></span>
                            </a>
                        </li>
                    </ul>
                </div> 
            </div>

            <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
            <div class="admin_search">
            <!-- {{ Form::open(array('url' => 'admin/reports','method' => 'post', 'id' => 'adminSearch')) }} -->
            <div class="day-custom days" id ="custom" style="padding-left: 15px;">
              <div class="form-group" id="calendar1">
                <a href="javascript:void(0)" ><i class="fa fa-calendar fromcalendar" aria-hidden="true" id ="fromcalendar"></i></a>
                <input type="text" id="fromsearch" name = "fromsearch" placeholder="From" class="form-control">
            </div>
            <div class="form-group">
                <a><i class="fa fa-calendar" aria-hidden="true" id="tocalendar"></i></a>
                <input type="text" id ="tosearch" name ="tosearch" placeholder="To" class="form-control ">
            </div>
             <input type="hidden" id ="searchingby" name ="searchingby" >
            
            <div class="form-group">
                <button class="btn btn-primary admin_ajax_search" id="reportsbtn" name= "reportsbtn" type = "submit">Go</button>
            </div>
        </div> 
        <!-- {{Form::close()}} -->
        </div>

        <div class="m_content" id="listID">
            @include('elements.admin.reports.index')
        </div>
    </div>
    {{Form::close()}}
</section>
</div>
<script>
    $(document).ready(function(){
      $("#custombtn").click(function(){
        $(".day-box-custom").addClass("intro");
        $(".day-custom").addClass("day-custom-show");
    });
      $(".btnactive").click(function () {
            $(".btnactive").removeClass("activee");
            $(this).addClass("activee");
        });
  });

</script>

@endsection