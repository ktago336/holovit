 @extends('layouts.newhome')
@section('content')       
        <section class="about-banner">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-md-6" data-aos="fade-down">
                        <h1>Services</h1>
                    </div>
                    <div class="col-sm-6 col-md-6" data-aos="fade-up">
                        <div class="about-img">
                           
                            {{HTML::image('public/img/front/banner-img4.png', SITE_TITLE)}}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="breadcrumb-section">
            <div class="container">
                <ol class="breadcrumb my_breadcum">
                   <li><a href="{{url('/')}}">Home</a></li>  
                    <li class="active"><a href="{{url('/services')}}">Services</a></li> 
                </ol>
            </div>
        </section>
       
        <!-- <section class="our-services-section" data-aos="fade-up">
            <div class="container">
                <h2 class="site-sub-titles">Our Services</h2>
                <div class="our-services-bx">
                    <ul>
                        <li class="bor-right bor-bottom">
                            <a href="#">
                                <div class="our-services">
                                    <div class="our-services-img">
                                        
                                        {{HTML::image('public/img/front/icon1.png', SITE_TITLE)}}
                                    </div>
                                    <h3>Manicure</h3>
                                </div>
                            </a>
                        </li>
                        <li class="bor-right bor-bottom">
                            <a href="#">
                                <div class="our-services">
                                    <div class="our-services-img">
                                       
                                        {{HTML::image('public/img/front/icon2.png', SITE_TITLE)}}
                                    </div>
                                    <h3>Pedicure</h3>
                                </div>
                            </a>
                        </li>
                        <li class="bor-right bor-bottom">
                            <a href="#">
                                <div class="our-services">
                                    <div class="our-services-img">
                                        
                                        {{HTML::image('public/img/front/icon3.png', SITE_TITLE)}}
                                    </div>
                                    <h3>Nail Art</h3>
                                </div>
                            </a>
                        </li>
                        <li class="bor-bottom">
                            <a href="#">
                                <div class="our-services">
                                    <div class="our-services-img">
                                       
                                        {{HTML::image('public/img/front/icon4.png', SITE_TITLE)}}
                                    </div>
                                    <h3>Paraffin Wax</h3>
                                </div>
                            </a>
                        </li>
                        <li class="bor-right">
                            <a href="#">
                                <div class="our-services">
                                    <div class="our-services-img">
                                        
                                        {{HTML::image('public/img/front/icon5.png', SITE_TITLE)}}
                                    </div>
                                    <h3>Haircut & Styling</h3>
                                </div>
                            </a>
                        </li>
                        <li class="bor-right">
                            <a href="#">
                                <div class="our-services">
                                    <div class="our-services-img">
                                       
                                        {{HTML::image('public/img/front/icon6.png', SITE_TITLE)}}
                                    </div>
                                    <h3>Skin Care</h3>
                                </div>
                            </a>
                        </li>
                        <li class="bor-right">
                            <a href="#">
                                <div class="our-services">
                                    <div class="our-services-img">
                                        
                                        {{HTML::image('public/img/front/icon7.png', SITE_TITLE)}}
                                    </div>
                                    <h3>Body Treatment</h3>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <div class="our-services">
                                    <div class="our-services-img">
                                        <img src="img/icon8.png" alt="icon">
                                        {{HTML::image('public/img/front/icon8.png', SITE_TITLE)}}
                                    </div>
                                    <h3>Massage</h3>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
         -->

          <section class="our-services-section" data-aos="fade-up">
            <div class="container">
                <h2 class="site-sub-title-service">Our Services</h2>
                <div class="our-services-bx">
                    <div class="row">
                        <div class="col-lg-2 col-xs-6"></div>
                        <div class="col-lg-4 col-xs-6">
                        <div class="small-box bg-gray">
                            <div class="inner col-lg-8 col-xs-8">
                                <h4 style="padding-top: 25px; font-family: 'playfair_displaybold';">
                                    <b class = "">Regular Facial</b></h4>
                                 <h5>60 Minutes = $89</h5>
                            </div>
                            <div class="inner" style="padding-top: 40px;">
                                <button class = "btn-primary btn-round" >Book Now </button>
                            </div>
                        </div>
                        </div>
                        <div class="col-lg-4 col-xs-6">
                        <div class="small-box bg-gray">
                            <div class="inner col-lg-8 col-xs-8">
                                <h4 style="padding-top: 25px; font-family: 'playfair_displaybold';">
                                    <b class = "">Pedicure Signature</b></h4>
                                 <h5>60 Minutes = $89</h5>
                            </div>
                            <div class="inner" style="padding-top: 40px;">
                                <button class = "btn-primary btn-round" >Book Now </button>
                            </div>
                        </div>
                        </div>
                   </div>
                   <div class="row">
                        <div class="col-lg-2 col-xs-6"></div>
                        <div class="col-lg-4 col-xs-6">
                        <div class="small-box bg-gray">
                            <div class="inner col-lg-8 col-xs-8">
                                <h4 style="padding-top: 25px; font-family: 'playfair_displaybold';">
                                    <b class = "">Regular Manicure</b></h4>
                                 <h5>60 Minutes = $89</h5>
                            </div>
                            <div class="inner" style="padding-top: 40px;">
                                <button class = "btn-primary btn-round" >Book Now </button>
                            </div>
                        </div>
                        </div>
                        <div class="col-lg-4 col-xs-6">
                        <div class="small-box bg-gray">
                            <div class="inner col-lg-8 col-xs-8">
                                <h4 style="padding-top: 25px; font-family: 'playfair_displaybold';">
                                    <b class = "">Bridal 2 3 Month Package</b></h4>
                                 <h5>60 Minutes = $89</h5>
                            </div>
                            <div class="inner" style="padding-top: 40px;">
                                <button class = "btn-primary btn-round" >Book Now </button>
                            </div>
                        </div>
                        </div>
                   </div>
                   <div class="row">
                        <div class="col-lg-2 col-xs-6"></div>
                        <div class="col-lg-4 col-xs-6">
                        <div class="small-box bg-gray">
                            <div class="inner col-lg-8 col-xs-8">
                                <h4 style="padding-top: 25px; font-family: 'playfair_displaybold';">
                                    <b class = "">Regular Facial</b></h4>
                                 <h5>60 Minutes = $89</h5>
                            </div>
                            <div class="inner" style="padding-top: 40px;">
                                <button class = "btn-primary btn-round" >Book Now </button>
                            </div>
                        </div>
                        </div>
                        <div class="col-lg-4 col-xs-6">
                        <div class="small-box bg-gray">
                            <div class="inner col-lg-8 col-xs-8">
                                <h4 style="padding-top: 25px; font-family: 'playfair_displaybold';">
                                    <b class = "">Regular Facial</b></h4>
                                 <h5>60 Minutes = $89</h5>
                            </div>
                            <div class="inner" style="padding-top: 40px;">
                                <button class = "btn-primary btn-round" >Book Now </button>
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