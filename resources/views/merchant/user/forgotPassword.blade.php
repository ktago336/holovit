@extends('layouts.merchant_inner')
@section('content')
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">    
    function checkForm(){
        $('#captcha_msg').html("").removeClass('gcerror');
        if ($("#loginform").valid()) {
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

<section class="login-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-xl-12">
                <div class="container-login">
                    <div class="login-part">
                        <h1>Forgot Password</h1>
                        <div class="ee er_msg">@include('elements.errorSuccessMessage')</div>
                        <div class="login-form">
                            {{ Form::open(array('url' => '/merchant/forgot-password', 'method' => 'post', 'id' => 'loginform', 'class' => 'form form-signin')) }}
                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <span><i class="fa fa-envelope-open-o" aria-hidden="true"></i></span>
                                        {{Form::text('email_address', null, ['class'=>'form-control required email', 'placeholder'=>'Email Address'])}}
                                    </div>
                                </div>
                               
                               <div class="form-group">
                                    <div class="Groupon_clone_button">
                                        {{Form::submit('SUBMIT', ['class' => 'btn btn-primary btn-block btn-flat', 'onclick'=>'return checkForm()'])}}
                                        <div class="create-acc">Already Have an Account? <a href="{{url('/merchant/login')}}">Login</a></div>
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