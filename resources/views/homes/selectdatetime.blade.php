@extends('layouts.newhome')
@section('content')
<script type="text/javascript">    
    function checkForm(){ 
        $('#captcha_msg').html("").removeClass('gcerror');
        if ($("#appointmentForm").valid()) {
            var captchaTick = grecaptcha.getResponse(); 
            if (captchaTick == "" || captchaTick == undefined || captchaTick.length == 0) {
                $('#captcha_msg').html("Please confirm captcha to proceed").addClass('gcerror');
                $('#captcha_msg').addClass('gcerror');
                return false;
            }
        }else{
            var captchaTick = grecaptcha.getResponse(); 
            if (captchaTick == "" || captchaTick == undefined || captchaTick.length == 0) {
                $('#captcha_msg').html("Please confirm captcha to proceed").addClass('gcerror');
                return false;
            }
        }        
    };
</script>
<style type="text/css">
  .modal-content{
    width:650px !important;
    margin-top: 80px !important;

  }
</style>
<script type="text/javascript">
  dayClick: function (start, allDay, jsEvent, view) {
    alert('You clicked me!');
}
</script>

<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 1050!important;">
  <div class="modal-dialog">
   <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
      {{ Form::open(array('method' => 'post', 'id' => 'appointmentForm', 'enctype' => "multipart/form-data")) }}
      <h5>Availble appointments on Friday, 16 November, 2019</h5><br>
      <div class="row" style="border-bottom: 1px solid #D3D3D3;">
        <div class="col-md-12 col-md-8 col-md-8 col-xl-8">
          <h5><i class ="fa fa-clock-o"></i> 09:00-10:00</h5>
          <h6>3 spaces available</h6>
        </div>
        <div class="col-md-12 col-md-4 col-lg-4 col-xl-4">
          <!-- <button type="button" class="btn btn-appointment">Book Appointment</button> -->
          <input type="submit" class="btn btn-appointment" value="Book Appointment" id = "step2-continue-btn">
        </div>
      </div>
      <br>
      <div class="row" style="border-bottom: 1px solid #D3D3D3;">
        <div class="col-md-12 col-md-8 col-md-8 col-xl-8">
          <h5><i class ="fa fa-clock-o"></i> 10:00-11:00</h5>
          <h6>2 spaces available</h6>
        </div>
        <div class="col-md-12 col-md-4 col-lg-4 col-xl-4">
          <!-- <button type="button" class="btn btn-appointment">Book Appointment</button> -->
          <input type="submit" class="btn btn-appointment" value="Book Appointment" id = "step2-continue-btn">
        </div>
      </div>
      <br>
      <div class="row" style="border-bottom: 1px solid #D3D3D3;">
        <div class="col-md-12 col-md-8 col-md-8 col-xl-8">
          <h5><i class ="fa fa-clock-o"></i> 12:00-13:00</h5>
          <h6>4 spaces available</h6>
        </div>
        <div class="col-md-12 col-md-4 col-lg-4 col-xl-4">
          <button type="button" class="btn btn-appointment">Book Appointment</button>
        </div>
      </div>
      {{ Form::close()}}
    </div>
  </div>      
</div>
</div>
<section class="breadcrumb-section">
  <div class="container">
    <ol class="breadcrumb my_breadcum">
      <li><a href="{{url('/')}}">Home</a></li> 
      <li><a href="{{url('/experts')}}">Our Experts</a></li> 

    </ol>
  </div>
</section>


<section class="profile-section">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-md-4 col-lg-3 col-xl-3 " style="border: 1px solid red">

      </div>
      <div class="col-xs-12 col-md-8 col-lg-9 col-xl-9" style="border: 1px solid red">
        <div class="mx-auto" >
          <form action=#>

            <div class="step2 mx-2" id="calender_div">
              <div id="calendar" style="width:'100%;'">
            </div><br>
              <h4 style="padding-left: 20px;">Available on Monday, 18 November 2019</h4><br>
              <div class="row">
                <div class="col-md-4">
                   <select class="form-control">
                    <option>MORNING</option>
                    <option value=0>10:00 AM</option>
                    <option value=1>10:30 AM</option>
                    <option value=2>11:00 AM</option>
                    <option value=3>11:30 AM</option>
                    <option value=4>12:00 AM</option>
                  </select>
                </div>
                 <div class="col-md-4">
                   <select class="form-control">
                    <option>AFTERNOON</option>
                    <option value=0>12:30 PM</option>
                    <option value=1>01:00 PM</option>
                    <option value=2>01:30 PM</option>
                    <option value=3>02:00 PM</option>
                    <option value=4>02:30 PM</option>
                    <option value=5>03:00 PM</option>
                    <option value=6>03:30 PM</option>
                    <option value=7>04:00 PM</option>
                    <option value=8>04:30 PM</option>
                  </select>
                </div>
                  <div class="col-md-4">
                   <select class="form-control">
                    <option>EVENING</option>
                    <option value=0>05:00 PM</option>
                    <option value=1>05:30 PM</option>
                    <option value=2>06:00 PM</option>
                    <option value=3>06:30 PM</option>
                    <option value=4>07:00 PM</option>
                    <option value=5>07:30 PM</option>
                  </select>
                </div>      
                </div>
          </div>
          <div id = "hiddenForm" style = "visibility:hidden">
          <div class="step3 mx-2">
            <h4 style="padding-left: 20px">Enter Your Information</h4>
              <h6 style="padding-left: 20px; padding-right: 20px;">Your name, email, phone number will be used to send you appointment confirmation and remainders. We'll also be able to call or text you if anything changes.</h6>
          <div class="ersu_message">@include('elements.errorSuccessMessage')</div>
            {{ Form::open(array('method' => 'post', 'id' => 'appointmentForm', 'enctype' => "multipart/form-data")) }}
            <div class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
                  <div class="input-group">
                    <div class="col-sm-6">
                      {{Form::text('first_name', null, ['class'=>'form-control required alphanumeric', 'placeholder'=>'First Name*', 'autocomplete' => 'off'])}}
                    </div>
                    
                    <div class="col-sm-6">
                      {{Form::text('last_name', null, ['class'=>'form-control required alphanumeric', 'placeholder'=>'Last Name*', 'autocomplete' => 'off'])}}
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group">
                  <div class="col-sm-6">
                    {{Form::text('email', null, ['class'=>'form-control required email', 'placeholder'=>'Email*', 'autocomplete' => 'off'])}}
                  </div>
                  <div class="col-sm-6">
                    {{Form::text('contact', null, ['class'=>'form-control required digits', 'placeholder'=>'Phone Number*', 'autocomplete' => 'off', 'minlength' => 8, 'maxlength' => 16])}}
                  </div>
                </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    {{Form::textarea('contact', null, ['class'=>'form-control required digits', 'placeholder'=>'Appointment Notes', 'autocomplete' => 'off','rows' => '5'])}}
                  </div>
                </div>
                <div class="">
                  <label class="col-sm-2 control-label" for="inputPassword3">&nbsp;</label>
                  {{Form::submit('PROCEED TO PAY', ['class' => 'btn btn-appointment', 'onclick'=>'return checkForm()'])}}
                 </div>
              </div>
            </div>
            {{ Form::close()}}
          </div>
          </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</section>

<style type="text/css">
  .show {
  display: block !important;
}
.hidden {
  display: none !important;
  visibility: hidden !important;
}
</style>
<script>
 $("#step2-continue-btn").click(function(){
  $("#hiddenForm").show();
 });
</script>
@endsection