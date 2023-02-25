@extends('layouts.newhome')
@section('content')
<style type="text/css">
  .modal-content{
    width:650px !important;
    margin-top: 80px !important;

  }
  .slot-block{
    border-bottom: 1px solid #D3D3D3;
  }

</style>
<script type="text/javascript">
    function checkForm(){ 
        $('#captcha_msg').html("").removeClass('gcerror');
        if ($("#bookappoinment").valid()) {
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
<div class="modal fade selectservice" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
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
      <section id="slots-section" class="selectservice-section">
        <div class="servis-book slot-block" >
        <div class="row" >
          <div class="col-md-12 col-md-8 col-md-8 col-xl-8">
            <h5><i class ="fa fa-clock-o"></i> 09:00-10:00</h5>
            <h6>3 spaces available</h6>
          </div>
          <div class="col-md-12 col-md-4 col-lg-4 col-xl-4">
            <!-- <button type="button" class="btn btn-appointment">Book Appointment</button> -->
            <input type="button" class="btn btn-appointment" value="Book Appointment" onclick = "javascript:void(0)" id = "book-appoinment-btn">
          </div>
        </div>
        </div>
      </section>
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
<!-- <section class="about-banner">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-6" data-aos="fade-up">
                <h1>Our Experts</h1>
            </div>
        <div class="col-sm-6 col-md-6" data-aos="zoom-in-up">
            <div class="about-img">
                {{HTML::image('public/img/front/banner-img2.png', SITE_TITLE)}}

            </div>
        </div>
        </div>
    </div>
  </section> -->

      <section class="profile-section">
        <div class="container">
          <div class="row">
            <div class="col-xs-12 col-md-4 col-lg-3 col-xl-3 " >
              <div class="mx-auto" >
                <div class="step1 mx-2" >
                  <div class="form-group">
                    <a href="javascript:void(0)" class="menu-link active-link">Select Service</a>
                    <div class="step-content" id="step1-content" >
                      <p id="service-name">Service</p>
                      <p><span id="service-time"></span></p>
                      <p>{{CURR}}<span id="service-price">0</span></p>
                      <div class="mt-2">
                        <a href="javascript:void(0)" id="edit-selected-link">Edit Your Selections</a>
                      </div>
                    </div>
                  </div>
                  <div class="form-group disabled">
                    <a href="javascript:void(0)" class="menu-link not-active" id="select-date-menu" disabled>Select Date & Time</a>
                    <div class="step-content" id="step2-content">
                      <p id="appoinment-date">Day,00 Month,0000</p>
                      <p id="appoinment-time">00.00 -11.00</p>
                    </div>
                  </div>
                  <div class="form-group">
                    <a href="#" class="menu-link not-active" id="your-info-menu">Your Info</a>
                  </div>  
                </div>
              </div>
                <!-- <div class="mx-auto" style="border: 1px solid green">
                    <div class="step2 mx-2">
                      <div class="form-group">
                          <a href="#" class="menu-link active-link">Select Service</a>
                          <div class="step1-content" id="step1-content">
                              <p id="service-name">Regular Facial</p>
                              <p id="service-time">60 Min</p>
                              <p id="service-price">$89</p>
                              <div>&nbsp;</div>
                              <a href="#">Edit Your Selections</a>
                          </div>
                      </div>
                      <div class="form-group disabled">
                          <a href="#" class="menu-link active-link" disabled>Select Date & Time</a>
                          <div class="step2-content" id="step2-content">
                              <p id="appoinment-date">Friday,12 July,2019</p>
                              <p id="appoinment-time">10.30 AM-11.30AM</p>
                          </div>
                      </div>
                      <div class="form-group">
                          <a href="#" class="menu-link not-active">Your Info</a>
                      </div>  
                    </div>
                  </div> -->
                </div>
                <div class="col-xs-12 col-md-8 col-lg-9 col-xl-9" >
                  <div class="mx-auto">
                    <div id="error"></div>
                    <!-- <form> -->
                      <?php
                      if(!empty($expert)){
                        ?>
                        {{ Form::open(array('url'=>'/selectservice/'.$expert->slug , 'method' => 'post', 'id' => 'bookappoinment')) }}
                      <?php }else{
                        ?>   
                        {{ Form::open(array('url'=>'/selectservice/' , 'method' => 'post', 'id' => 'bookappoinment')) }} 
                        <?php
                      } ?>
                      {{Form::hidden('staff_slug', $staff_slug, ['id'=>'staff_slug' ])}}
                      {{Form::hidden('selected_date', null, ['id'=>'selected_date' ])}}
                      {{Form::hidden('start_time',null, ['id'=>'start_time'])}}
                      {{Form::hidden('end_time',null, ['id'=>'end_time'])}}
                      {{Form::hidden('total_price',0, ['id'=>'total_price' ])}}
                      {{Form::hidden('is_fixed_slot',$isfixedslot, ['id'=>'is_fixed_slot'])}}
                     
                      <input type="hidden"  id="staffsoffday" value="<?php echo $offdays; ?>">

                      <!-- {{Form::hidden('uslug', null, ['id'=>'udate' ])}} -->
                      <div class="step1 mx-2" id="select-service-div">
                        <?php
                        $i=1;
                        foreach ($services as $s) {
                          ?>
                          <div class="form-group round">
                            <input type="checkbox" name="service_ids[]" id="checkbox{{$i}}" value="{{$s->id}}">
                            <label for="checkbox{{$i}}"></label>
                            <span class="select-label">
                              <span class="select-service-name">
                                {{$s->name}}
                              </span>
                              <p class="select-label-price">{{($s->minutes==0 || $s->minutes=='')?'':$s->minutes.' minutes - '}} {{CURR.$s->price}}</p>
                            </span>
                          </div>
                          <?php
                          $i++;      
                        }
                        ?>
                            <!-- <div class="form-group round">
                                <input type="checkbox" name="checkbox1" id="checkbox2" value="2">
                                <label for="checkbox2"></label>
                                <span class="select-label">
                                    <span class="select-service-name">
                                        Manicure
                                    </span>
                                    <p class="select-label-price">60 minutes - $100</p>
                                </span>
                            </div>
                            <div class="form-group round">
                                <input type="checkbox" name="checkbox1" id="checkbox3" value="3">
                                <label for="checkbox3"></label>
                                <span class="select-label">
                                    <span class="select-service-name">
                                        Wax
                                    </span>
                                    <p class="select-label-price">60 minutes - $90</p>
                                </span>
                              </div> -->
                              <div class="form-group">
                                <button type="button" class="rounded-0 btn  btn-primary" id="step1-continue-btn">CONTINUE</button>
                              </div>
                            </div>
                            <div class="step2 mx-2" id="select-date-div" style="border: 1px solid grey">
                              <div id="calendar" style="width:'100%;'">
                              </div><br>
                              <!-- <h4 style="padding-left: 20px;">Available on <span id="available-date"><?php //echo date("l").", ".date('d F Y');; ?></span></h4><br> -->
                              <!-- <div class="row"> -->
                                <!-- <div class="col-md-4"> -->
                                   <!-- <select class="form-control required" id="slot-options"> -->
                                    <!-- <option>MORNING</option> -->
                                    <!-- <option value="">Select Slot</option> 
                                     <option value=0>10:00 AM</option> -->
                                    
                                  <!-- </select> -->
                                <!-- </div> -->
                                <!-- <div class="col-md-4"> -->
                                 <!-- <button class="btn btn-primary" onclick="return false;">Book Appoinment</button> -->
                                <!-- </div> -->
                                  <!-- <div class="col-md-4">
                                   <select class="form-control">
                                    <option>EVENING</option>
                                    <option value=0>05:00 PM</option>
                                    <option value=1>05:30 PM</option>
                                    <option value=2>06:00 PM</option>
                                    <option value=3>06:30 PM</option>
                                    <option value=4>07:00 PM</option>
                                    <option value=5>07:30 PM</option>
                                  </select>
                                </div> -->      
                              <!-- </div> -->
                        <div class="form-group">
                          <!-- <button type="button" class="rounded-0 btn  btn-primary" id="book-appoinment-btn">book appoinment</button> -->
                        </div>
                      </div>
                      <div class="step3 mx-2" id="your-info-div" style="border: 1px solid grey">
                        <h4 style="padding-left: 20px">Enter Your Information</h4>
                        <h6 style="padding-left: 20px; padding-right: 20px;">Your name, email, phone number will be used to send you appointment confirmation and remainders. We'll also be able to call or text you if anything changes.</h6>
                        <div class="form-horizontal">
                          <div class="box-body">
                            <div class="form-group">
                              <div class="input-group">
                                  @php
                                  if(isset($userInfo) && $userInfo!=''){
                                        $first_name = $userInfo->first_name;
                                        $last_name = $userInfo->last_name;
                                        $email = $userInfo->email_address;
                                        $contact = $userInfo->contact;
                                      }
                                  else{
                                        $first_name = null;
                                        $last_name = null;
                                        $email = null;
                                        $contact = null;
                                       }
                                  @endphp
                                <div class="col-sm-6">
                                  {{Form::text('first_name', $first_name, ['class'=>'form-control required alphanumeric', 'placeholder'=>'First Name*', 'autocomplete' => 'off'])}}
                                </div>

                                <div class="col-sm-6">
                                  {{Form::text('last_name', $last_name, ['class'=>'form-control required alphanumeric', 'placeholder'=>'Last Name*', 'autocomplete' => 'off'])}}
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="input-group">
                                <div class="col-sm-6">
                                  {{Form::text('email', $email, ['class'=>'form-control required email', 'placeholder'=>'Email*', 'autocomplete' => 'off'])}}
                                </div>
                                <div class="col-sm-6">
                                  {{Form::text('contact', $contact, ['class'=>'form-control required digits', 'placeholder'=>'Phone Number*', 'autocomplete' => 'off', 'minlength' => 8, 'maxlength' => 16])}}
                                </div>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-sm-12">
                                {{Form::textarea('description', null, ['class'=>'form-control required', 'placeholder'=>'Appointment Notes*', 'autocomplete' => 'off','rows' => '5'])}}
                              </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="col-sm-3">
                                      Select Payment Method*
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="abs-radio">
                                            <input type="radio" name="payment_method" value="cash" id="cash"><label>Cash</label>
                                        </div>
                                        <div class="abs-radio">
                                            <input type="radio" name="payment_method" value="paypal" id="paypal"><label>Pay via PayPal</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="">
                              <label class="col-sm-5 control-label" for="inputPassword3">&nbsp;</label>
                              <!-- {{ html_entity_decode(link_to('user/planproceed/', 'Procced to pay ', array('escape' => false,'class'=>"btn btn-primary"))) }} -->
                              {{Form::submit('Book Now', ['class' => 'btn btn-appointment', 'onclick'=>'return checkForm()'])}}
                            </div>
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <!-- <button type="button" class="rounded-0 btn  btn-primary" id="proceed-to-pay-btn">Procced to pay</button> -->
                        </div>
                      </div>
                      {{ Form::close()}}
                      <!-- </form> -->
                    </div>
                  </div>
                </div>
              </div>
            </section>


            <script type="text/javascript">
             $(document).ready(function(){
              //var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
// var d = new Date('2019-12-21');
// var dayName = days[d.getDay()];
// console.log("name of day"+dayName);
    // to hide error after selection of service 
    $('input[type="checkbox"]').click(function(){
      if($(this).prop("checked") == true){
        $("#error").hide();
      }
        // else if($(this).prop("checked") == false){
        //     alert("Checkbox is unchecked.");
        // }
      });

    //go to second step 
    $("#step1-continue-btn").click(function(){
        //get selected checkbox's value    
        if($('input[type="checkbox"]:checked').length>0){
          var val = [];
          $(':checkbox:checked').each(function(i){
            val[i] = $(this).val();
          });
        //console.log(JSON.stringify(val));
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({ 
          type: 'POST',
          url: "<?php echo HTTP_PATH; ?>/getservicesdata/"+val,
          cache: false,
          success: function (result)
          {
            var results = JSON.parse(result);
                //console.log(results);
                $("#service-name").html(results['names']);
                if(results['duration']>0){
                  $("#service-time").html(results['duration']+" Min");
                }
                $("#service-price").html(results['total']);
                $("#total_price").val(results['total']);
              }
            })
        $("#select-date-div").show();
        $("#select-service-div").hide();
        $("#step1-content").show();
        $("#select-date-menu").removeClass("not-active");
        $("#select-date-menu").addClass("active-link");
      }else{
        $("#error").html("Please select at least one service");
        $("#error").show();
        setTimeout(function() { $("#error").hide(); }, 5000);
      }
    });

    $("#book-appoinment-btn").click(function(){
    // alert("edit")
    $("#your-info-div").show();
    $("#select-date-div").hide();
    $("#select-service-div").hide();
    $("#myModal").hide();
    $(".modal-backdrop.show").hide();
      
    $("#step2-content").show();
    $("#your-info-menu").removeClass("not-active");
    $("#your-info-menu").addClass("active-link");
  });

    $("#edit-selected-link").click(function(){
    // alert("edit");
    $("#select-service-div").show();
    $("#select-date-div").hide();
    $("#your-info-div").hide();
    $("#select-date-menu").removeClass("active-link");
    $("#select-date-menu").addClass("not-active");
    $("#your-info-menu").removeClass("active-link");
    $("#your-info-menu").addClass("not-active");
    $("#step1-content").hide();
    $("#step2-content").hide();
  });
  });

// $("#booking_date_time").click(function(){
//   $("#space-div").toggle();
// });
</script>
@endsection