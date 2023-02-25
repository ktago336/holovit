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
            <li><a href="{{url('/experts')}}">Our Experts</a></li> 
            <?php
            if(!empty($expert)){
            ?>
            <li class="active"><a href="{{url('/expertdetail/'.$expert->slug)}}">{{ucfirst($expert->first_name)." ".ucfirst($expert->last_name)}}</a></li>
            <?php } ?> 
        </ol>
    </div>
</section>
<section class="profile-section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-4 col-lg-3 col-xl-3">
                
                <div class="select-service-menu test">
                    @include('elements.select_date_left_menu')
                </div>
            </div>
            <div class="col-xs-12 col-md-8 col-lg-9 col-xl-9">
                <div class="select-service-part">
                    <div class="ee er_msg">@include('elements.errorSuccessMessage')</div>
                    <!-- <h2><b>Your Profile Information</b></h2> -->
                    <?php
                    if(!empty($expert)){
                    ?>
                    {{ Form::open(array('url'=>'/selectservice/'.$expert->slug , 'method' => 'post', 'id' => 'changeprofile')) }}
                    <?php }else{
                    ?>    
                        {{ Form::open(array('url'=>'/selectservice/0' , 'method' => 'post', 'id' => 'changeprofile')) }}
                    <?php
                    } ?>
                    <div class="">
                        <?php
                        $i=1;
                            foreach ($services as $s) {
                        ?>
                        <div class="form-group round">
                            <!-- <label for="blog_check" class="checkbox-circle"  name ="check1" > -->

                                {{Form::checkbox('service_ids[]',$s->id, null,['class'=>'checkbox checkbox-circle required','id'=>'checkbox'.$i])}}
                                <label for="checkbox<?php echo $i; ?>"></label>
                                <span class="select-label">
                                    <strong>
                                        {{$s->name}}
                                    </strong>
                                    <p class="select-label-price">60 minutes - {{CURR.$s->price}}</p>
                                </span>
                            <!-- </label> -->
                        </div>
                        
                        
                        <?php
                        $i++;      
                            }
                        ?>
                        <div class="form-group">
                            <!-- <a href="#" class=" "></a> -->
                            {{Form::submit('CONTINUE', ['class' => 'rounded-0 btn  btn-primary'])}}
                        </div>
                        <!-- <div class="edit-info"><a href="{{ URL::to( 'users/settings')}}"><i class="fa fa-pencil"></i></a></div> -->
                        
                        <div class="profile-info">
                            <!-- <label>Contact</label>
                            <span>8569742455</span> -->
                        </div>
                    </div>
                    {{ Form::close()}}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection