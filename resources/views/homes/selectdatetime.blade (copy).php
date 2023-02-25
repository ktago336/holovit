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
  html {
    font-family: sans-serif;
    font-size: 15px;
    line-height: 1.4;
    color: #444;
  }

  body {
    margin: 0;
    background: #504f4f;
    font-size: 1em;
  }

  .wrapper {
    margin: 15px auto;
    max-width: 1100px;
  }

  .container-calendar {
    background: #ffffff;
    padding: 13px;
    max-width: 700px;
    height: 600px;
    margin: 0 auto;
    overflow: auto;
  }

  .button-container-calendar button {
    cursor: pointer;
    display: inline-block;
    zoom: 1;
    background: #0084ff;
    color: #fff;
    border: 1px solid #0aa2b5;
    border-radius: 4px;
    padding: 5px 5px 5px 5px;
    width :40px;
  }

  .table-calendar {
    border-collapse: collapse;
    width: 100%;
  }

  .table-calendar td, .table-calendar th {
    padding: 15px;
    border: 1px solid #e2e2e2;
    text-align: center;
    vertical-align: top;
  }

  .date-picker.selected {
    font-weight: bold;
    background-color: #E59866;
  }

  .date-picker.selected span {
    border-bottom: 2px solid currentColor;
  }

  /* sunday */
  .date-picker:nth-child(1) {
    color: red;
  }


  #monthAndYear {
    text-align: center;
    margin-top: 0;
  }

  .button-container-calendar {
    position: relative;
    margin-bottom: 1em;
    overflow: hidden;
    clear: both;
  }

  #previous {
    float: left;
  }

  #next {
    float: right;
  }

  .footer-container-calendar {
    margin-top: 1em;
    padding: 10px 0;
  }

  .footer-container-calendar select {
    cursor: pointer;
    display: inline-block;
    zoom: 1;
    background: #ffffff;
    color: #585858;
    border: 1px solid #bfc5c5;
    border-radius: 3px;
    padding: 5px 1em;
  }
  .row-calender
  {
    background: #E59866;
  }
  .modal {
    position: fixed;
    top: 150;
    left: 80;
    background: rgba(0, 0, 0, 0);
  }
</style>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 1050!important;">
  <div class="modal-dialog">
   <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
      {{ Form::open(array('method' => 'post', 'id' => 'appointmentForm', 'enctype' => "multipart/form-data")) }}
      <h5>Availble appointments on Friday, 16 November, 2019</h5><br>
      <div class="row" style="border-bottom: 1px solid #D3D3D3;">
        <div class="col-md-12 col-md-7 col-md-7 col-xl-7">
          <h5><i class ="fa fa-clock-o"></i> 09:00-10:00</h5>
          <h6>3 spaces available</h6>
        </div>
        <div class="col-md-12 col-md-5 col-lg-5 col-xl-5">
          <button type="button" class="btn btn-appointment">Book Appointment</button>
        </div>
      </div>
      <br>
      <div class="row" style="border-bottom: 1px solid #D3D3D3;">
        <div class="col-xs-12 col-md-7 col-lg-7 col-xl-7">
          <h5><i class ="fa fa-clock-o"></i> 10:00-11:00</h5>
          <h6>2 spaces available</h6>
        </div>
        <div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">
          <!-- <button type="button" class="btn btn-appointment">Book Appointment</button> -->
          <input type="submit" class="btn btn-appointment" value="Book Appointment" id = "step2-continue-btn">
        </div>
      </div>
      <br>
      <div class="row" style="border-bottom: 1px solid #D3D3D3;">
        <div class="col-xs-12 col-md-7 col-lg-7 col-xl-7">
          <h5><i class ="fa fa-clock-o"></i> 12:00-13:00</h5>
          <h6>4 spaces available</h6>
        </div>
        <div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">
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

            <div class="step2 mx-2" id = "">
              <div class="wrapper">

                <div class="container-calendar">
                  <h3 id="monthAndYear"></h3>

                  <div class="button-container-calendar">
                    <button id="previous" onclick="previous()"><b>&#8249;</b></button>
                    <button id="next" onclick="next()"><b>&#8250;</b></button>
                  </div>

                  <table class="table-calendar" id="calendar" data-lang="en">
                    <thead id="thead-month"></thead>
                    <tbody id="calendar-body"></tbody>
                  </table>
                  <br>
                  <h4 style="padding-left: 7px;">Available on Friday, 16 November, 2019</h4>

                  <div class="footer-container-calendar">
                   <select>
                    <option>MORNING</option>
                    <option value=0>10:00 AM</option>
                    <option value=1>10:30 AM</option>
                    <option value=2>11:00 AM</option>
                    <option value=3>11:30 AM</option>
                    <option value=4>12:00 AM</option>
                  </select>
                  <select>
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
                  <select>
                    <option>EVENING</option>
                    <option value=0>05:00 PM</option>
                    <option value=1>05:30 PM</option>
                    <option value=2>06:00 PM</option>
                    <option value=3>06:30 PM</option>
                    <option value=4>07:00 PM</option>
                    <option value=5>07:30 PM</option>
                  </select>
                  <select id = "month" hidden></select>
                  <select id="year" onchange="jump()" hidden></select>       
                </div>

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

<script>

</script>
@endsection