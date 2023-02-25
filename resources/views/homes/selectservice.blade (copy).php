@extends('layouts.newhome')
@section('content')
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
<section class="breadcrumb-section">
    <div class="container">
        <!-- <ol class="breadcrumb my_breadcum">
            <li><a href="{{url('/')}}">Home</a></li> 
            <li><a href="{{url('/experts')}}">Our Experts</a></li> 
            <?php
            //if(!empty($expert)){
            ?>
            <li class="active"><a href="{{url('/expertdetail/'.$expert->slug)}}">{{ucfirst($expert->first_name)." ".ucfirst($expert->last_name)}}</a></li>
            <?php //} ?> 
        </ol> -->
    </div>
</section>
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
                          <p><span id="service-time">0</span> Min</p>
                          <p>{{CURR}}<span id="service-price">0</span></p>
                          <div class="mt-2">
                            <a href="javascript:void(0)" id="edit-selected-link">Edit Your Selections</a>
                          </div>
                        </div>
                      </div>
                      <div class="form-group disabled">
                          <a href="#" class="menu-link not-active" id="select-date-menu" disabled>Select Date & Time</a>
                          <div class="step-content" id="step2-content">
                              <p id="appoinment-date">Friday,12 July,2019</p>
                              <p id="appoinment-time">10.30 AM-11.30AM</p>
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
                        {{ Form::open(array('url'=>'/selectservice/'.$expert->slug , 'method' => 'post', 'id' => 'changeprofile')) }}
                        <?php }else{
                        ?>    
                            {{ Form::open(array('url'=>'/selectservice/0' , 'method' => 'post', 'id' => 'changeprofile')) }}
                        <?php
                        } ?>
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
                                    <p class="select-label-price">0 minutes - {{CURR.$s->price}}</p>
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
                        <div class="step2 mx-2" id="select-date-div" style="border: 1px solid green">
                            step 2
                            <div class="form-group">
                                <button type="button" class="rounded-0 btn  btn-primary" id="book-appoinment-btn">book appoinment</button>
                            </div>
                        </div>
                        <div class="step3 mx-2" id="your-info-div" style="border: 1px solid green">
                            step 3
                            <div class="form-group">
                                <button type="button" class="rounded-0 btn  btn-primary" id="proceed-to-pay-btn">Procced to pay</button>
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
                $("#service-time").html(results['duration']);
                $("#service-price").html(results['total']);
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
    

</script>
@endsection