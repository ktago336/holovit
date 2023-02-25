 @extends('layouts.newhome')
@section('content') 
<style>
   .our-services-section{
    background-color: #ffffff;
   }
</style>      
        <!-- <section class="about-banner">
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
        </section> -->
        <section class="breadcrumb-section">
            <div class="container">
                <ol class="breadcrumb my_breadcum">
                   <li><a href="{{url('/')}}">Home</a></li>  
                    <li class="active"><a href="{{url('/services')}}">Services</a></li> 
                </ol>
            </div>
        </section> 
        
       @if(!$services->isEmpty())
        <section class="our-services-section" data-aos="fade-up">
            <div class="container">
                <h2 class="site-sub-titles">Our Services</h2>
                <div class="our-services-bx">
                    <ul>
                        @foreach($services as $service)
                        <li class="bor-right bor-bottom">
                            <a href="{{url('/experts/'.$service->id)}}">
                                <div class="our-services">
                                    <div class="our-services-img">
                                        {{HTML::image(SERVICE_SMALL_DISPLAY_PATH.$service->service_image)}}
                                    </div>
                                    <h3>{{$service->name}}</h3>
                                </div>
                            </a>
                        </li>
                        @endforeach

                    </ul>
                </div>
            </div>
        </section>
        @endif
        
@endsection