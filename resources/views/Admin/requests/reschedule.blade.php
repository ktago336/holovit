@extends('layouts.admin')
@section('content')
<script type="text/javascript">
    $(document).ready(function () {
        $("#adminForm").validate();
    });
    function showhidetime(value) {
        
        $.each($("input[name='" + value + "']:checked"), function () {
            $("#"+value+"_time_from").show();
            $("#"+value+"_time_to").show();
        });
        $.each($("input[name='" + value + "']:unchecked"), function () {
            $("#"+value+"_time_from").val('');
            $("#"+value+"_time_to").val('');
            $("#"+value+"_time_from").hide();
            $("#"+value+"_time_to").hide();
        });
    }
    
</script>
<div class="content-wrapper">
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog">
   <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
      {{ Form::open(array('method' => 'post', 'id' => 'appointmentForm', 'enctype' => "multipart/form-data")) }}
      <?php
      //if($isfixedslot=='1'){
      ?>
      <h5>Availble appointments on <span id="click-date">Friday, 16 November, 2019</span></h5><br>
      <section id="slots-section">
        <div class="row slot-block" >
          <div class="col-md-12 col-md-8 col-md-8 col-xl-8">
            <h5><i class ="fa fa-clock-o"></i> 09:00-10:00</h5>
            <h6>3 spaces available</h6>
          </div>
          <div class="col-md-12 col-md-4 col-lg-4 col-xl-4">
            <!-- <button type="button" class="btn btn-appointment">Book Appointment</button> -->
            <input type="button" class="btn btn-appointment" value="Book Appointment" onclick = "javascript:void(0)" id = "book-appoinment-btn">
          </div>
        </div>
      </section>
      <br>
      
      <?php
      //}else{
      ?>
       <!-- <h5>Availble appointments on <span id="click-date">Friday, 16 November, 2019</span></h5><br>
        <div class="col-sm-6 col-md-6">
            <div class="form-group"> 
                <div class="appointment-input">
                    <input type="datetime-local" id="booking_date_time" name = "booking_date_time" placeholder="Select date/time" class="form-control required">
                </div>
            </div>
        </div>
        <div id="space-div">
          &nbsp;
        </div> -->
        
      <?php  
      //}
      ?>
      {{ Form::close()}}
    </div>
  </div>      
</div>
</div>
    <section class="content-header">
        <h1>Reschedule Booking Request</h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::to('admin/admins/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li ><a href="{{URL::to('admin/requests')}}">Booking Requests</a></li>
            <li class="active"> Reschedule Booking Request</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-info">
            <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
           
            {{ Form::open(array('url'=>'/saverescheduledata/'.$appoinmentdata->slug , 'method' => 'post', 'id' => 'bookappoinment')) }}
              {{Form::hidden('staff_slug', $appoinmentdata->Admin->slug, ['id'=>'staff_slug' ])}}
              {{Form::hidden('request_slug', $appoinmentdata->slug, ['id'=>'request_slug' ])}}
              {{Form::hidden('selected_date', null, ['id'=>'selected_date' ])}}
              <!-- {{Form::hidden('start_time',null, ['id'=>'start_time'])}} -->
              <!-- {{Form::hidden('end_time',null, ['id'=>'end_time'])}} -->
              <!-- {{Form::hidden('total_price',0, ['id'=>'total_price' ])}} -->
              {{Form::hidden('is_fixed_slot',$settings->fixed_time_slot
              , ['id'=>'is_fixed_slot'])}}
              <input type="hidden"  id="staffsoffday" value="<?php echo $offdays; ?>">
            <div class="form-horizontal">
                <div class="box-body">
                    <div id="calendar" style="width:'100%;'">
                    </div>
                </div>
            </div>
            {{ Form::close()}}
        </div>
    </section>
</div>
<script type="text/javascript">
  $(document.body).on('click', '.fc-day,.fc-day-top,.fc-future', function(event){
    var requestDate=event.target.dataset.date;
    console.log("data on click : "+requestDate);
    if(requestDate<today){
      return;
    }
    var mydate = new Date(event.target.dataset.date);
    // console.log("mydate : "+mydate);
    $('#selected_date').val(requestDate);
    $("#slots-section").html("<div class='spinner-border' role='status'><span class='sr-only'>Loading...</span></div>");
      var staffslug=$('#staff_slug').val();
    var isFixedSlot=$('#is_fixed_slot').val();
    //console.log("slot: "+isFixedSlot);
    var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    var mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
    var dateformatstr=days[mydate.getDay()]+", "+mydate.getDate()+" "+mlist[mydate.getMonth()]+", "+mydate.getFullYear();
    $("#click-date").html(dateformatstr);
    $("#available-date").html(dateformatstr);
    console.log(dateformatstr);
    //if(isFixedSlot!='0'){
        console.log("is fixed slot yes");
    $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({ 
          type: 'POST',
          url: "<?php echo HTTP_PATH; ?>/getslotdata/"+requestDate,
          cache: false,
          data:{'date':requestDate,'isFixedSlot':isFixedSlot,'slug':staffslug,'dayname':days[mydate.getDay()]},
          success: function (result)
          {

            var results = JSON.parse(result);
                console.log(results);
                
                if(results.length>0){
                  var i;
                  var content="";
                  for (i = 0; i < results.length; ++i) {
                     content=content+"<div class='row slot-block'><div class='col-md-12 col-md-8 col-md-8 col-xl-8'><h5><i class ='fa fa-clock-o'></i> "+results[i]+"</h5></div><div class='col-md-12 col-md-4 col-lg-4 col-xl-4'><!-- <button type='button' class='btn btn-appointment'>Book Appointment</button> --><input type='button' class='btn btn-appointment' value='Book Appointment' data-slot='"+results[i]+"' data-slotdate='"+dateformatstr+"' onclick = 'bookAppoinment(this)' id = 'book-"+results[i]+"'></div></div><br>";

                  }
                  $("#slots-section").html(content);
                }else{
                  $("#slots-section").html("<h4 class='text-danger'>No slot available, Please check for another date</h4>");
                }

                // $("#service-name").html(results['names']);
                // $("#service-time").html(results['duration']);
                // $("#service-price").html(results['total']);
          }
        });
       $("#myModal").show();
      $(".modal-backdrop.show").show();
    //}    
  $("#myModal").modal();

    });
  function bookAppoinment(slot) {
      var bookSlotTime=(slot.dataset.slot).split("-",2);
      var bookSlotdate=slot.dataset.slotdate;
      console.log("start : "+bookSlotTime[0]);
      console.log("end : "+bookSlotTime[1]);
      var a=bookSlotTime[0].trim();
      var b=bookSlotTime[1].trim();
      console.log(a);
      console.log(b);
      var selectedDateTime=$('#selected_date').val()+" "+a;
      var requestSlug=$('#request_slug').val();
      console.log(bookSlotdate);
      $('#start_time').val(a);
      $('#end_time').val(b);
      $("#myModal").modal('hide');
      $(".modal-backdrop.show").hide();
      $("body").css("overflow-x","hidden");
      $("body").css("overflow-y","auto");

      $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({ 
          type: 'POST',
          url: "<?php echo HTTP_PATH; ?>/admin/saverescheduledata",
          cache: false,
          data:{'rescheduleDate':selectedDateTime,'formatdate':bookSlotdate,'slug':requestSlug},
          success: function (result)
          {
             window.location.href = "<?php echo HTTP_PATH; ?>/admin/requests";
            // location.reload();
            // alert(result);
          }
        });
    }
</script>
@endsection