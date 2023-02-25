@extends('layouts.newhome')
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
<!-- <section class="about-banner">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-6" data-aos="fade-left">
                <h1>Contact us</h1>
            </div>
            <div class="col-sm-6 col-md-6" data-aos="fade-right">
                <div class="about-img">{{HTML::image('public/img/front/banner-img5.png', SITE_TITLE)}}</div>
            </div>
        </div>
    </div>
</section> -->
<section class="breadcrumb-section">
    <div class="container">
        <ol class="breadcrumb my_breadcum">
            <li><a href="{{url('/')}}">Home</a></li> 
            <li class="active"><a href="{{url('/contact')}}">Contact us</a></li> 
        </ol>
    </div>
</section>
<section class="contact-section" data-aos="fade-up">
    <div class="container">
        <div class="contact-bar">
            <div class="row">
                <div class="col-xs-12 col-sm-7 col-md-8">
                    <div class="book-posts" data-aos="fade-up">
                        <div class="my-contact">
                            <h2 class="site-sub-titles">Contact us</h2>
                            <div class="well well-sm">
                                <h3>You can contact us any way that is convenient for you. We are available 24/7 via fax or email.<br>
                                    You can also use a quick contact form below or visit our salon personally.</h3>
                                <div class="ee er_msg">@include('elements.errorSuccessMessage')</div>
                                {{ Form::open(array('url' => '/contactus', 'method' => 'post', 'id' => 'loginform', 'class' => 'form form-signin')) }}
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{Form::text('first_name', null, ['class'=>'form-control required', 'placeholder'=>'First Name'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{Form::text('last_name', null, ['class'=>'form-control required', 'placeholder'=>'Last Name'])}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{Form::text('phone', null, ['class'=>'form-control required numeric', 'placeholder'=>'Phone'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{Form::text('email_address', null, ['class'=>'form-control required email', 'placeholder'=>'Email'])}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{Form::textarea('message', null, ['class'=>'form-control required', 'placeholder'=>'Message'])}}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                            {{Form::submit('Submit', ['class' => 'btn btn-primary btn-block', 'onclick'=>'return checkForm()'])}}
                                        </div>
                                           <!--  <button type="submit" class="btn btn-primary" id="btnContactUs">Submit</button> -->
                                        </div>
                                    </div>
                               {{ Form::close()}}
                            </div>
                        </div>   
                    </div>
                </div>
                <div class="col-xs-12 col-sm-5 col-md-4">
                    <div class="contact-details">
                        <div class="address-details addre-info">
                            <h3>Address</h3>
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <span>254 East 40 New Street , Suite 404 Brooklyn, CA 123456</span>
                        </div>
                        <div class="address-details email-details">
                            <h3>E-mail</h3>
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <span>info@myway.com</span>
                        </div>
                        <div class="address-details phone-details">
                            <h3>Phones</h3>
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <span>+61 2132 5642 12</span>
                        </div>
                        <div class="address-details phone-details">
                            <h3>Opening Hours</h3>
                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                            <span>
                                Mon-Fri: 9 am – 6 pm<br>
                                Saturday: 9 am – 4 pm<br>
                                Sunday: Closed
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="map-section" data-aos="fade-updd">
    <div class="country-map">
        <div style="width: 100%; margin-top: 0px;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d2965.0824050173574!2d-93.63905729999999!3d41.998507000000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sWebFilings%2C+University+Boulevard%2C+Ames%2C+IA!5e0!3m2!1sen!2sus!4v1390839289319" width="100%" height="300" frameborder="0" style="border:0"></iframe>
        </div>
    </div>
</section>
@endsection