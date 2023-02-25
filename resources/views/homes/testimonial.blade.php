@extends('layouts.newhome')
@section('content')
<!-- <section class="about-banner">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-md-6" data-aos="fade-up">
                <h1>Testimonial</h1>
            </div>
            <div class="col-sm-6 col-md-6" data-aos="fade-down">
                <div class="about-img">{{HTML::image('public/img/front/banner-img1.png', SITE_TITLE)}}</div>
            </div>
        </div>
    </div>
</section> -->
<section class="breadcrumb-section">
    <div class="container">
        <ol class="breadcrumb my_breadcum">
            <li><a href="{{url('/')}}">Home</a></li> 
            <li class="active"><a href="{{url('/testimonial')}}">Testimonial</a></li> 
        </ol>
    </div>
</section>
@if(!$testimonils->isEmpty())    
<section class="inner-testmonial-section" data-aos="fade-up">
    <div class="container">
        <h2 class="site-sub-titles">Testimonials</h2>
        <div class="clients-testimonials">
            <div class="row">
                <div id="app_slider5" class="owl-carousel">
                    @foreach($testimonils as $allrecord)
                        <div class="col-md-12 col-sm-12">
                            <div class="thumbnail">
                                <div class="testimonials-slider-img">
                                    {{HTML::image(TESTIMONIAL_SMALL_DISPLAY_PATH.$allrecord->image, SITE_TITLE)}}
                                </div>
                                <div class="caption">
                                    <h3>{{$allrecord->client_name}}</h3>
                                    <h4>{{$allrecord->country}}</h4>
                                </div>
                                <p>{!! str_limit($allrecord->description, $limit = 250, $end = '...') !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif
<!--
<section class="inner-testi-section" data-aos="fade-up">
    <div class="container">
        <h2 class="site-sub-titles">What Clients Say</h2>
        <div class="clients-say">
            <div class="row">
                <div id="app_slider6" class="owl-carousel">
                    <div class="col-md-12 col-sm-12">
                        <div class="thumbnail">
                            <div class="quote-img-bx">{{HTML::image('public/img/front/two-quotes.png', SITE_TITLE)}}</div>
                            <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of 
                                classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a 
                                Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,</p>

                            <div class="caption">
                                <div class="say-slider-img">
                                    {{HTML::image('public/img/front/g1.png', SITE_TITLE)}}
                                </div>
                                <h3>Jenifer Lope</h3>
                                <h4>Client</h4>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="thumbnail">
                            <div class="quote-img-bx">{{HTML::image('public/img/front/two-quotes.png', SITE_TITLE)}}</div>
                            <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of 
                                classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a 
                                Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,</p>

                            <div class="caption">
                                <div class="say-slider-img">
                                    {{HTML::image('public/img/front/g2.png', SITE_TITLE)}}
                                </div>
                                <h3>Jenifer Lope</h3>
                                <h4>Client</h4>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="thumbnail">
                            <div class="quote-img-bx"><img src="img/two-quotes.png" alt=""></div>
                            <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of 
                                classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a 
                                Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,</p>

                            <div class="caption">
                                <div class="say-slider-img">
                                    {{HTML::image('public/img/front/g3.png', SITE_TITLE)}}
                                </div>
                                <h3>Jenifer Lope</h3>
                                <h4>Client</h4>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
-->
<!-- old -->
<!-- <section class="inner-testmonial-section" data-aos="fade-up">
    <div class="container">
        <h2 class="site-sub-titles">Testimonials</h2>
        <div class="clients-testimonials">
            <div class="row">
                 <div id="">
                    <div class="col-md-12 col-sm-12">
                        <div class="thumbnail">
                            <div class="testimonials-slider-img">
                                {{HTML::image('public/img/front/clints-img.png', SITE_TITLE)}}
                            </div>
                            <div class="caption">
                                <h3>Jenifer Lope</h3>
                                <h4>Actor / Model</h4>
                            </div>
                            <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of 
                                classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a 
                                Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,</p>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="thumbnail">
                            <div class="testimonials-slider-img">
                                {{HTML::image('public/img/front/g1.png', SITE_TITLE)}}
                            </div>
                            <div class="caption">
                                <h3>Jenifer Lope</h3>
                                <h4>Actor / Model</h4>
                            </div>
                            <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of 
                                classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a 
                                Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,</p>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="thumbnail">
                            <div class="testimonials-slider-img">
                                {{HTML::image('public/img/front/g1.png', SITE_TITLE)}}
                            </div>
                            <div class="caption">
                                <h3>Jenifer Lope</h3>
                                <h4>Actor / Model</h4>
                            </div>
                            <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of 
                                classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a 
                                Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,</p>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
        <div class="testimonial-slider">
                        <div id="app_slider3" class="owl-carousel">
                            <div class="thumbnail">
                                <div class="testimonials-slider-img">
                                     {{HTML::image('public/img/front/g1.png', SITE_TITLE)}}
                                </div>
                                <div class="caption">
                                    <h3>Jenifer Lope</h3>
                                    <h4>Actor / Model</h4>
                                </div>
                                <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of 
                                    classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a 
                                    Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,</p>
                            </div>
                            <div class="thumbnail">
                                <div class="testimonials-slider-img">
                                    {{HTML::image('public/img/front/g1.png', SITE_TITLE)}}
                                </div>
                                <div class="caption">
                                    <h3>Jenifer Lope</h3>
                                    <h4>Actor / Model</h4>
                                </div>
                                <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of 
                                    classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a 
                                    Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,</p>
                            </div>
                        </div>
                    </div>
    </div>
</section>

<section class="inner-testi-section" data-aos="fade-up">
    <div class="container">
        <h2 class="site-sub-titles">What Clients Say</h2>
        <div class="clients-say">
            <div class="row">
                 <div id="" class="owl-carousel">
                    <div class="col-md-12 col-sm-12">
                        <div class="thumbnail">
                            <div class="quote-img-bx">{{HTML::image('public/img/front/two-quotes.png', SITE_TITLE)}}</div>
                            <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of 
                                classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a 
                                Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,</p>

                            <div class="caption">
                                <div class="say-slider-img">
                                    {{HTML::image('public/img/front/g1.png', SITE_TITLE)}}
                                </div>
                                <h3>Jenifer Lope</h3>
                                <h4>Client</h4>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="thumbnail">
                            <div class="quote-img-bx">{{HTML::image('public/img/front/two-quotes.png', SITE_TITLE)}}</div>
                            <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of 
                                classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a 
                                Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,</p>

                            <div class="caption">
                                <div class="say-slider-img">
                                    {{HTML::image('public/img/front/g2.png', SITE_TITLE)}}
                                </div>
                                <h3>Jenifer Lope</h3>
                                <h4>Client</h4>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="thumbnail">
                            <div class="quote-img-bx">{{HTML::image('public/img/front/two-quotes.png', SITE_TITLE)}}</div>
                            <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of 
                                classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a 
                                Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words,</p>

                            <div class="caption">
                                <div class="say-slider-img">
                                    {{HTML::image('public/img/front/g3.png', SITE_TITLE)}}
                                </div>
                                <h3>Jenifer Lope</h3>
                                <h4>Client</h4>
                            </div>

                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</section> -->

@endsection
        