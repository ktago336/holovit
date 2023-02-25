
@extends('layouts.newhome')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $( function() {
        $("#bookeddate").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            //minDate: 'mm-dd-yyyy',
            //maxDate:'mm-dd-yyyy',
            changeYear: true,
            onClose: function(selectedDate) {
                if(selectedDate){$("#bookeddate").datepicker("option", "", selectedDate);}
            }
        });
    } );
</script>
<script type="text/javascript">


    function checkForm(){ 
        $('#captcha_msg').html("").removeClass('gcerror');
        if ($("#appointmentForm").valid()) {
            return true;
        }else{
            return false;
        }
    };

    // $('#booking_date_time').change(function(){
    //     alert($('#booking_date_time').val());
    // });
    // (function(){
    //     onSelect: function(dateText) {
    //         console.log("Selected date: " + dateText + "; input's current value: " + this.value);
    //     }
    //     console.log("heloo");
    // });
    function getdate(){
        var bookeddate=$('#bookeddate').val();
        //$('#bookeddate').val(bookeddate);
        var isFixedSlot=$('#fixedslot').val();
        var today = new Date();
        var dd = today.getDate();

        var mm = today.getMonth()+1; 
        var yyyy = today.getFullYear();
        if(dd<10) 
        {
            dd='0'+dd;
        } 

        if(mm<10) 
        {
            mm='0'+mm;
        } 
        today = yyyy+'-'+mm+'-'+dd;
        // var q= new Date();
        // var m = q.getMonth();
        // var d = q.getDay();
        // var y = q.getFullYear();
        // var today = new Date(y,m,d);
        //var today = new Date();
        var mydate = new Date(bookeddate);
        var staffid=$('#staff_id').val();
        if(staffid==''){
            staffid=0;
        }
        console.log("selected date :"+mydate);
        console.log("today date :"+today);
        if(bookeddate<today)
        {
            
            $('#bookeddate').val(null);
            alert("Please select current or future date")
            return;
        }
        console.log("date is not old");

        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var mlist = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
        var dateformatstr=days[mydate.getDay()]+", "+mydate.getDate()+" "+mlist[mydate.getMonth()]+", "+mydate.getFullYear();
        $("#click-date").html(dateformatstr);
        $("#available-date").html(dateformatstr);
        console.log(dateformatstr);
        console.log("fixed : "+isFixedSlot);
        console.log("staffid : "+staffid);
        //console.log(dateformatstr);
        // if(isFixedSlot!='0'){
            //console.log("is fixed slot yes");
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });

            $.ajax({ 
              type: 'POST',
              url: "<?php echo HTTP_PATH; ?>/getslotstartdata",
              cache: false,
              data:{'date':bookeddate,'isFixedSlot':isFixedSlot,'slug':staffid,'dayname':days[mydate.getDay()]},
              success: function (result)
              {

                var results = JSON.parse(result);
                    console.log(results);
                    var content="<option value=''>Select Time</option>";
                    if(results.length>0){
                      var i;
                      
                      for (i = 0; i < results.length; ++i) {
                         content=content+"<option value='"+results[i]+"'>"+results[i]+"</option>";

                      }
                      $('#noslotblock').css('display','none');
                      $("#slottimeid").html(content);
                    }else{
                        $('#noslotblock').css('display','block');
                      $("#slottimeid").html(content);
                    }

                    // $("#service-name").html(results['names']);
                    // $("#service-time").html(results['duration']);
                    // $("#service-price").html(results['total']);
              }
            });
        // }
        $('#slottimeblock').css('display','block');
    }
    // function changeService(){
    //     $('#bookeddate').val(null);
    //     $('#slottimeblock').css('display','none'); 
    // }
    function changeStaff(){
        $('#bookeddate').val("");
        $('#noslotblock').css('display','none');
        $('#slottimeblock').css('display','none');
    }
    // $('#service_ids').change(function(){
       
    // });
    
</script>
<script type="text/javascript">
    $(function(){


     $("#appointmentbtn").click(function(e) {
        e.preventDefault();
        var validate=checkForm();
        console.log("validate : "+validate);
        var user_id = $('#user_id').val();
        var isregister = $('#isuserregister').val();
        var email_address = $('#email_address').val();
        //var name = $('#name').val();
        var contact = $('#contact').val();
        var service_ids = $('#service_ids').val();
        var booking_date_time = $('#bookeddate').val()+" "+$('#slottimeid').val();
        console.log(booking_date_time);
        console.log("register : "+isregister);
        var staff_id = $('#staff_id').val();
        if(service_ids=='' || service_ids==null){
            service_ids=0;
        }
        if(staff_id=='' || staff_id==null){
            staff_id=1;
        }
        if(validate){
            if(isregister=='1'){
                if(email_address=='' && contact==''){
                    alert("Please Provide Phone Number Or Email");
                    return;
                }
            }else{
                if(email_address=='' && contact=='' && user_id==''){
                    alert("Please Provide Name Or Phone Number Or Email");
                    return;
                }
            }
        }
        var description = $('#description').val();
        if(validate){
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "<?php echo HTTP_PATH; ?>/savebookappointment",
                cache: false,
                type: "POST",
                data: {name:user_id,email_address:email_address,contact:contact,service_ids:service_ids,booking_date_time:booking_date_time,staff_id:staff_id,description:description,},
                success: function(result){
                 var results = JSON.parse(result);
                 $('#appoinmentformdiv').css('display','none');
                 // var date = new Date(results['bookeddateformat']);
                 // var newDate = date.toString('dd-MM-yy');
                 $('#booked-date').html(results['bookeddateformat']);
                 $('#thank-block').css("display", "block");
                 //console.log(results['booking_date_time']);
             }
         });
       }     
    });
 }); 

</script>


    <!-- <section class="about-banner">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-6" data-aos="fade-up">
                    <h1>About</h1>
                </div>
            <div class="col-sm-6 col-md-6" data-aos="zoom-in-up">
                <div class="about-img">
                    {{HTML::image('public/img/front/banner-img2.png', SITE_TITLE)}}

                </div>
            </div>
            </div>
        </div>
    </section> -->
    <section class="breadcrumb-section">
        <div class="container">
            <ol class="breadcrumb my_breadcum">
                <li><a href="{{url('/')}}">Home</a></li> 
                <li class="active"><a href="{{url('/about')}}">About</a></li> 
            </ol>
        </div>
    </section>
    <section class="our-about-section" data-aos="fade-up">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-6" data-aos="fade-right">
                    <div class="jumbotron">
                        <h2 class="site-sub-titles">About</h2>
                        <?php
                        $aboutus = DB::table('pages')->where('id','4')->first();
                        echo $aboutus->description ;
                        ?>
                            <!-- <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been 
                                the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley 
                                of type and scrambled it to make a type specimen book. It has survived not only five centuries, 
                                but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised 
                                in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently 
                            with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p> -->
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6" data-aos="fade-left">
                        <div class="our-clients-img">
                            {{HTML::image('public/img/front/img3.png', SITE_TITLE)}}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="appointment-section" data-aos="fade-updd">
            <div class="container">
                <h2 class="site-sub-titles">Make an Appointment</h2>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's 
                standard dummy text ever since the 1500s, </p>
            </div>
            <!-- <div class="ee er_msg">@include('elements.errorSuccessMessage')</div> -->
           {{ Form::open(array( 'method' => 'post', 'id' => 'appointmentForm', 'class' => 'form form-signin')) }}
    <div class="appointment-bx" id="appoinmentformdiv">
        <div class="container">
                    <input type="hidden" id="isuserregister" value="<?php echo $setting['user_registration'];?>">
                    <div class="appointment-form">
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                            <?php
                            $settings = DB::table('settings')->first();
                            if($settings->fullname_required == '1')
                            {
                            ?>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group"> 
                                    <div class="appointment-input">
                                        {{Form::text('user_id', null, ['class'=>'form-control required', 'placeholder'=>'Your name','id' => 'user_id'])}}
                                        
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                            if($settings->fullname_required == '0')
                            {
                            ?>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group"> 
                                    <div class="appointment-input">
                                        {{Form::text('user_id', null, ['class'=>'form-control', 'placeholder'=>'Your name','id' => 'user_id'])}}
                                        
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                            ?>

                            <?php 
                            $settings = DB::table('settings')->first();
                            
                            if ($settings->email_required == '1') {
                                ?>
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group"> 
                                        <div class="appointment-input">
                                            {{Form::text('email_address', null, ['class'=>'form-control required email', 'placeholder'=>'Your email','id' => 'email_address'])}}
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } 
                            ?>

                            <?php 
                            $settings = DB::table('settings')->first();
                            
                            if ($settings->email_required == '0') {
                                ?>
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group"> 
                                        <div class="appointment-input">
                                            {{Form::text('email_address', null, ['class'=>'form-control email', 'placeholder'=>'Your email','id' => 'email_address'])}}
                                        </div>
                                    </div>
                                </div>
                                <?php
                            } 
                            ?>

                        <!-- </div>
                        <div class="row"> -->
                            <?php 
                            $settings = DB::table('settings')->first();
                            
                            if ( $settings->phone_required == '1') {
                                ?>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group"> 
                                    <div class="appointment-input">
                                        {{Form::text('contact', null, ['class'=>'form-control required digits', 'placeholder'=>'Phone number','id' => 'contact'])}}
                                        
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                            <?php 
                            $settings = DB::table('settings')->first();
                            
                            if ( $settings->phone_required == '0') {
                                ?>
                                <div class="col-sm-12 col-md-12">
                                <div class="form-group"> 
                                    <div class="appointment-input">
                                        {{Form::text('contact', null, ['class'=>'form-control digits', 'placeholder'=>'Phone number','id' => 'contact'])}}
                                        
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="col-sm-6 col-md-6">    
                            <?php
                                $settings = DB::table('settings')->first();
                                $service_ids = DB::table('services')->where('status','1')->pluck('name','id');
                                if ($settings->service_selection == '1' && $settings->service_selection_mandatory=='1') {
                            ?>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group"> 
                                    <div class="appointment-input">
                                       
                                       {!!Form::select('service_ids', $service_ids, null, ['class' => 'form-control required', 'placeholder' => 'Select Service', 'id' => 'service_ids', 'name' => 'service_ids','id' => 'service_ids'])!!}
                                   </div>
                               </div>
                           </div>
                           <?php
                                }
                                if ($settings->service_selection == '1' && $settings->service_selection_mandatory=='0') {
                            ?>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group"> 
                                    <div class="appointment-input">
                                       
                                       {!!Form::select('service_ids', $service_ids, null, ['class' => 'form-control ', 'placeholder' => 'Select Service', 'id' => 'service_ids', 'name' => 'service_ids','id' => 'service_ids'])!!}
                                   </div>
                               </div>
                           </div>
                           <?php
                                }
                            ?>
                       <!-- </div> -->
                       <!-- <div class="row"> -->
                        
                        <?php 
                        $settings = DB::table('settings')->first();
                            
                        if ($settings->staff_selection == '1' && $settings->staff_selection_mandatory == '1') {
                            ?>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group"> 
                                    <div class="appointment-input">
                                     <?php
                                     $staff_id = DB::table('admins')->where('type', 'staff')->pluck('first_name','id');

                                     ?> 
                                     {!!Form::select('staff_id',$staffdropdown, null, ['class' => 'form-control required', 'placeholder' => 'Select Staff', 'id' => 'staff_id', 'name' => 'staff_id', 'onchange'=>'changeStaff()'])!!}
                                    </div>
                                </div>
                            </div>
                         <?php
                     }
                     if ($settings->staff_selection == '1' && $settings->staff_selection_mandatory == '0') {
                            ?>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group"> 
                                    <div class="appointment-input">
                                     <?php
                                     $staff_id = DB::table('admins')->where('type', 'staff')->pluck('first_name','id');

                                     ?> 
                                     {!!Form::select('staff_id',$staffdropdown, null, ['class' => 'form-control', 'placeholder' => 'Select Staff', 'id' => 'staff_id', 'name' => 'staff_id', 'onchange'=>'changeStaff()'])!!}
                                    </div>
                                </div>
                            </div>
                         <?php
                     }


                     ?>
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group"> 
                                <div class="appointment-input">
                                    <input type="text" id="bookeddate" name = "booking_date_time" placeholder="Appointment Date"  class="form-control required" onchange="getdate()" >
                                    <!-- min="<?php //echo date('Y-m-d');?>" -->
                                </div>
                                <span class="text-danger" id="noslotblock">No Slot Available Please Check For Another Date</span>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12" id="slottimeblock">
                            <div class="form-group"> 
                                <div class="appointment-input">
                                 <select name="slottime" class="form-control required" placeholder="Select Time" id="slottimeid">
                                    <option value="">Select time</option>
                                 <?php
                                 global $default_time;
                                 foreach ($default_time as $d) {
                                ?>
                                    <option value="<?php echo $d; ?>"><?php echo $d; ?></option>
                                <?php   
                                    }
                                ?>
                                 </select> 
                                 
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-sm-12 col-md-12" id="noslotblock">
                            <div class="form-group"> 
                                <div class="appointment-input">
                                
                                 
                                </div>
                            </div>
                        </div> -->
                    </div>
                 </div>
                 <!-- <div class="row"> -->
                    <div class="col-sm-12 col-md-12">
                        <div class="form-group"> 
                            <div class="appointment-input">
                                {{Form::textarea('description', null, ['class'=>'form-control required', 'placeholder'=>'Your comment','id' => 'description'])}}

                            </div>
                        </div>
                    </div>
                <!-- </div> -->
                <div class="row">
                    <div class="col-sm-3 col-md-3">
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group"> 
                            <div class="appointment-btn">
                                <!-- {{Form::submit('MAKE AN APPOINTMENT NOW', ['class' => 'btn btn-primary btn-block', 'onclick'=>'return checkForm()', 'id' => 'appointmentbtn'])}} -->
                                <button class="btn btn-primary" id="appointmentbtn" onclick="javascript:void(0)">MAKE AN APPOINTMENT NOW</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{Form::close()}}
    <div class="appointment-bx" id="thank-block" style="display: none">
        <h3>
            Thank You
        </h3>
        <p><strong>Your Appoinment has been booked for <span id="booked-date"></strong></p>
    </div>
    
   
</section>
<script type="text/javascript">
     $('#slottimeblock').css('display','none');
     $('#noslotblock').css('display','none');
</script>

@endsection