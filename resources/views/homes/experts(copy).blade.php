
@extends('layouts.newhome')
@section('content')
 
    <section class="about-banner">
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
    </section>
        <section class="breadcrumb-section">
            <div class="container">
                <ol class="breadcrumb my_breadcum">
                    <li><a href="{{url('/')}}">Home</a></li> 
                    <li class="active"><a href="{{url('/experts')}}">Our Experts</a></li> 
                </ol>
            </div>
        </section>
        <section class="our-meet-section" data-aos="fade-up">
    <div class="container">
        <h2 class="site-sub-titles">Meet Our Experts</h2>
        <div class="top-moduls">
            <div class="row">
                <div id="app_slider4" class="owl-carousel">
                    <div class="thumbnail">
                        <div class="letest-img">
                            {{HTML::image('public/img/front/g1.png','img')}}
                        </div>
                        
                    </div>
                    <div class="thumbnail">
                        <div class="letest-img">
                            {{HTML::image('public/img/front/g2.png','img')}}
                        </div>
                        
                    </div>
                    <div class="thumbnail">
                        <div class="letest-img">
                            {{HTML::image('public/img/front/g3.png','img')}}
                        </div>
                        
                    </div>
                    <div class="thumbnail">
                        <div class="letest-img">
                            {{HTML::image('public/img/front/g4.png','img')}}
                        </div>
                        
                    </div>
                    <div class="thumbnail">
                        <div class="letest-img">
                            {{HTML::image('public/img/front/g5.png','img')}}
                        </div>
                        
                    </div>
                    <div class="thumbnail">
                        <div class="letest-img">
                            {{HTML::image('public/img/front/g1.png','img')}}
                        </div>
                        
                    </div>
                    <div class="thumbnail">
                        <div class="letest-img">
                            {{HTML::image('public/img/front/g2.png','img')}}
                        </div>
                        
                    </div>
                    <div class="thumbnail">
                        <div class="letest-img">
                            {{HTML::image('public/img/front/g3.png','img')}}
                        </div>
                        
                    </div>
                    <div class="thumbnail">
                        <div class="letest-img">
                            {{HTML::image('public/img/front/g4.png','img')}}
                        </div>
                        
                    </div>
                    <div class="thumbnail">
                        <div class="letest-img">
                            {{HTML::image('public/img/front/g5.png','img')}}
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