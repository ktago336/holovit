
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
        <ol class="breadcrumb my_breadcum">
            <li><a href="{{url('/')}}">Home</a></li> 
            <li class="active"><a href="{{url('/experts/'.$slug)}}">Our Experts</a></li> 
        </ol>
    </div>
</section>
<section class="our-experts-section">
    <div class="container">
        
        <div class="content-our-experts">
                <!-- <div class="box box-info"> -->
                    <div class="ersu_message">@include('elements.admin.errorSuccessMessage')
                    </div>
                 <div class="our-experts-box">
                <h2 class="site-sub-titles-experts">Meet Our Experts</h2>
                    <div class="our-experts-search">
                        {{ Form::open(array('method' => 'post', 'id' => 'adminSearch')) }}
                        <div class="form-group">
                            <span class="dtpickr_inputs">{{Form::text('keyword1', null, ['class'=>'form-control', 'placeholder'=>'Search by Service Name', 'autocomplete' => 'off'])}}</span>
                            
                        </div>
                         <div class="form-group">
                            <span class="dtpickr_inputs">{{Form::text('keyword', null, ['class'=>'form-control', 'placeholder'=>'Search by staff', 'autocomplete' => 'off'])}}</span>
                            
                        </div>
                        <div class="experts_asearch">
                                <div class="ad_s ajshort">{{Form::button('Submit', ['class' => 'btn btn-info admin_ajax_search'])}}</div>
<!--                                <div class="ad_cancel"><a href="{{URL::to('/experts/'.$slug)}}" class="btn btn-default canlcel_le">Clear Search</a></div>-->
                            </div>
                        {{ Form::close()}}
                        
                    </div>  
                 </div>
                    <div class="m_content" id="listID">
                        @include('homes.expertlist')
                    </div>
                <!-- </div> -->
           
        </div>
    </div>
</section>
        

@endsection