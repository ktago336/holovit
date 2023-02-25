
@extends('layouts.newhome')
@section('content')
 <script src='https://www.google.com/recaptcha/api.js'></script>
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
                    <li class="active"><a href="{{url('/work')}}">All Works</a></li> 
                </ol>
            </div>
        </section>
        <section class="our-about-section" data-aos="fade-up">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-md-6" data-aos="fade-right">
                        <div class="jumbotron">
                            <h2 class="site-sub-titles">All works</h2>
                            <?php
                            $aboutus = DB::table('pages')->where('id','8')->first();
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
                        <div class="">
                            {{HTML::image('public/img/front/img2.png','img')}}
                        </div>
                    </div>
                </div>
            </div>
        </section>
       
        

@endsection