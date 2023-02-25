@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $( function() {
    //$("#fromdate" ).datepicker();
    //$("#todate" ).datepicker();
    $("#fromdate1").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            //minDate: 'mm-dd-yyyy',
            //maxDate:'mm-dd-yyyy',
            changeYear: true,
            onClose: function(selectedDate) {
                if(selectedDate){$("#fromdate1").datepicker("option", "", selectedDate);}
            }
        });
    $("#todate1").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            //minDate: 'mm-dd-yyyy',
            //maxDate:'mm-dd-yyyy',
            changeYear: true,
            onClose: function(selectedDate) {
                if(selectedDate){$("#todate1").datepicker("option", "", selectedDate);}
            }
        });
    
} );
</script>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Booking Requests</h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::to('admin/admins/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li class="active"> Booking Requests</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-info">
            <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
            <div class="admin_search">
                {{ Form::open(array('method' => 'post', 'id' => 'adminSearch')) }}
                <div class="form-group align_box dtpickr_inputs">
                    <span class="hints">Search by Service/Booking Number/Staff </span>
                    <span class="hint">{{Form::text('service', null, ['class'=>'form-control', 'placeholder'=>'Service/Booking Number', 'autocomplete' => 'off'])}}</span>

                    <span class="hint">{{Form::text('staff', null, ['class'=>'form-control', 'placeholder'=>'Search by Staff', 'autocomplete' => 'off'])}}</span>

                    <span class="hint"><input type="text" id="fromdate1" name = "fromdate1" placeholder="From Date" class="form-control"></span> 

                    <span class="hint"><input type="text" id ="todate1" name ="todate1" placeholder="To Date"class="form-control"></span>

                    <div class="admin_asearch">
                        <div class="ad_s ajshort">{{Form::button('Submit', ['class' => 'btn btn-info admin_ajax_search'])}}</div>
                        <div class="ad_cancel"><a href="{{URL::to('admin/requests')}}" class="btn btn-default canlcel_le">Clear Search</a></div>
                    </div>

                </div>
                {{ Form::close()}}
            </div>            
            <div class="m_content" id="listID">
                @include('elements.admin.requests.index')
            </div>
        </div>
    </section>
</div>
@endsection