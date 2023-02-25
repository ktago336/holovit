@extends('layouts.merchant_inner')
@section('content')
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">
    $(document).ready(function () {
        $.validator.addMethod("alphanumeric", function (value, element) {
            return this.optional(element) || /^[\a-zA-Z._ ]+$/i.test(value);
        }, "Only letters and underscore are allowed.");
        $.validator.addMethod("passworreq", function (input) {
            var reg = /[0-9]/; //at least one number
            var reg2 = /[a-z]/; //at least one small character
            var reg3 = /[A-Z]/; //at least one capital character
            //var reg4 = /[\W_]/; //at least one special character
            return reg.test(input) && reg2.test(input) && reg3.test(input);
        }, "Password must be a combination of Numbers, Uppercase & Lowercase Letters.");
        $.validator.addMethod("validname", function(value, element) {
            return this.optional(element) || /^[\w. ]+$/i.test(value);
        }, "Only letters, numbers, space and underscore allowed.");
        $("#registerform").validate();
    });
    function checkForm() {
        $('#captcha_msg').html("").removeClass('gcerror');
        if ($("#registerform").valid()) {
            var captchaTick = grecaptcha.getResponse();
            if (captchaTick == "" || captchaTick == undefined || captchaTick.length == 0) {
                $('#captcha_msg').html("Please confirm captcha to proceed").addClass('gcerror');
                $('#captcha_msg').addClass('gcerror');
                return false;
            }
        } else {
            var captchaTick = grecaptcha.getResponse();
            if (captchaTick == "" || captchaTick == undefined || captchaTick.length == 0) {
                $('#captcha_msg').html("Please confirm captcha to proceed").addClass('gcerror');
                return false;
            }
        }
    }
    ;
</script>
<script type="text/javascript">
$(document).ready(function () {
	  $('#country_id').change(function () {
            //alert('sdfsd');
            var country_id = $("#country_id").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '<?php echo HTTP_PATH; ?>/merchant/users/add_states',
                data: {'country_id': country_id},
                success: function (result) {
                    // alert(result);
                    $('#state_id').html(result);
                   // $('.subcategory_id').show();

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    $('#loaderID').hide();
                }
            });
        });
		
		
		$('#state_id').change(function () {
            //alert('sdfsd');
            var state_id = $("#state_id").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '<?php echo HTTP_PATH; ?>/merchant/users/add_cities',
                data: {'state_id': state_id},
                success: function (result) {
                    // alert(result);
                    $('#city_id').html(result);

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    $('#loaderID').hide();
                }
            });
        });
		});
		</script>
		<script type="text/javascript">
		$(document).ready(function () {
		$('#verify_number').click(function() { 
		var number = $("#verify_contact").val();
		var otp = 111;

		 $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
			 $.ajax({
                type: "POST",
                url: '<?php echo HTTP_PATH; ?>/merchant/users/verify_number',
                data: {'number': number,'otp':otp},
                success: function (result) {
                    $('#results').html(result);
					$('#verify_number').css("display","none");
					$('#verify').css("display","block"); 
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    $('#loaderID').hide();
                }
            });
		});
		});
		</script>
		<script type="text/javascript">
		$(document).ready(function () {
		$('#verify').click(function(){
        var match_number = $("#verify_contact").val();			
		var check_otp = $("#check_number").val(); 
		 $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
			$.ajax({
			    type: "POST",
                url: '<?php echo HTTP_PATH; ?>/merchant/users/otp_check',
                data: {'check_otp': check_otp,'match_number': match_number},
                success: function (result) {
                    $('#results').html(result);
					$('#verify').css("display","none");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    $('#loaderID').hide();
                }
            });
		});
});

</script>
<section class="login-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-xl-12">
                <div class="merchant-login">
                    <div class="login-part">
                        <h1>Get listed on <?php echo SITE_TITLE;?> for free</h1>
                        <div class="ee er_msg">@include('elements.errorSuccessMessage')</div>
                        <div class="login-form">
                            {{ Form::open(array('method' => 'post', 'id' => 'registerform', 'class' => 'form form-signin')) }}
                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <i class='fa fa-credit-card' aria-hidden="true"></i>
                                        {{Form::text('busineess_name', null, ['id'=>'busineess_name', 'class'=>'form-control required validname', 'placeholder'=>'Enter business name', 'autocomplete'=>'OFF'])}}
                                    </div>
                                </div>
								
                                <div class="form-group"> 
                                    <div class="Groupon_clone">
									 <div class="Groupon_left">
                                        <i class="fa fa-area-chart" aria-hidden="true"></i>
										<div class="merchant-select">
										<span>
                                        {{Form::select('country_id', $country, null,['id'=>'country_id', 'class'=>'form-control required', 'placeholder'=>'Select country', 'autocomplete'=>'OFF'])}}
                                    </span>
									</div>
									</div>
									 <div class="Groupon_left Groupon_right">
                                        <i class="fa fa-area-chart" aria-hidden="true"></i>
											<div class="merchant-select">
										<span>
                                        {{Form::select('state_id',array(), null, ['id'=>'state_id', 'class'=>'form-control required', 'placeholder'=>'Select state', 'autocomplete'=>'OFF'])}}
                                     </span>
									</div>
									</div>
									</div>
                                </div>
							
								
								<div class="form-group"> 
                                    <div class="Groupon_clone">
									 <div class="Groupon_left">
                                        <i class="fa fa-area-chart" aria-hidden="true"></i>
											<div class="merchant-select">
										<span>
                                        {{Form::select('city_id',array(), null, ['id'=>'city_id', 'class'=>'form-control required', 'placeholder'=>'Select city', 'autocomplete'=>'OFF'])}}
                                     </span>
									</div>
									</div>
									 <div class="Groupon_left Groupon_right">
                                        <i class="fa fa-shopping-bag" aria-hidden="true"></i>
											<div class="merchant-select">
										<span>
                                        {{Form::select('business_type', $business_category, null, ['id'=>'business_type', 'class'=>'form-control required', 'placeholder'=>'Select business category', 'autocomplete'=>'OFF'])}}
                                     </span>
									</div>
									</div>
									</div>
                                </div>
								
								<div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                                        {{Form::text('name', null, ['id'=>'name', 'class'=>'form-control required validname', 'placeholder'=>'Enter your name', 'autocomplete'=>'OFF'])}}
                                    </div>
                                </div>
								<div class="form-group"> 
                                    <div class="Groupon_clone">
                                       <i class="fa fa-phone" aria-hidden="true"></i>
                                        {{Form::text('contact', null, ['id'=>'verify_contact', 'class'=>'form-control required digits', 'placeholder'=>'Enter your mobile number', 'autocomplete'=>'OFF'])}}
										<!--<div class="verify_number">
										<a href="javascript:void(0);" id="verify_number" >Verify Number</a>
										</div>
										<div class="results-otp">
    										<div id="results">
    										
    										</div>
    										<a href="javascript:void(0);" id="verify" style="display:none" class="verify-phone" >Verify</a>
                                        </div>-->
                                    </div>
                                </div>
								
                                
								  <div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <i class="fa fa-envelope-open-o" aria-hidden="true"></i>
                                        {{Form::text('email_address', Cookie::get('user_email_address'), ['class'=>'form-control required email', 'placeholder'=>'Enter your Email ID', 'autocomplete'=>'OFF'])}}
                                    </div>
                                </div>
								<div class="form-group"> 
                                    <div class="Groupon_clone">
                                       <i class="fa fa-key fa-rotate-90" aria-hidden="true"></i>
                                        {{Form::password('password', ['class'=>'form-control required', 'placeholder' => 'Password', 'minlength' => 8, 'id'=>'password'])}}
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                      <i class="fa fa-key fa-rotate-90" aria-hidden="true"></i>
                                        {{Form::password('confirm_password', ['class'=>'form-control required', 'placeholder' => 'Confirm Password', 'equalTo' => '#password'])}}
                                    </div>
                                </div>
								<?php
								global $how_you_hear_about_us;
								?>
                               <div class="form-group"> 
                                    <div class="Groupon_clone">
                                       <i class="fa fa-heart" aria-hidden="true"></i>
									   <div class="merchant-select">
										<span>
                                        {{Form::select('source_of_info_about_us', $how_you_hear_about_us, null, ['id'=>'last_name', 'class'=>'form-control required', 'placeholder'=>'How did you hear about '.SITE_TITLE.'?', 'autocomplete'=>'OFF'])}}
                                    </span>
									</div>
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <div class="groupon-input gcpaatcha">
                                            <div id="recaptchaQ" class="g-recaptcha" data-sitekey="{{ CAPTCHA_KEY }}" style="transform:scale(0.2);-webkit-transform:scale(1);transform-origin:0 0;-webkit-transform-origin:0 0;" ></div>
                                            <div class="gcpc" id="captcha_msg"></div>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <div class="rebrs remember_secsd register-check">
                                            {{Form::checkbox('terms', '1', Cookie::get('terms'), array('class'=>'css-checkbox in-checkbox required','id'=>'checkboxG1'))}}
                                            <label class="in-label" for="checkboxG1">I read and agree to <a href="{{ URL::to( 'terms-and-condition')}}" target="blank">Terms & Conditions</a></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="Groupon_clone_button">
                                        {{Form::submit('Create Account', ['class' => 'btn btn-primary', 'onclick'=>'return checkForm()'])}}
                                        <div class="sin-txt">Already have an account? <a href="{{URL('/merchant/login')}}">Sign In</a></div>
                                    </div>
                                </div>
                            {{ Form::close()}}
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
</section>
@endsection