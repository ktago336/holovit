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
            <li class="active"><a href="{{url('/expertdetail/'.$expert->slug)}}">{{ucfirst($expert->first_name)." ".ucfirst($expert->last_name)}}</a></li> 
        </ol>
    </div>
</section>

<section class="blog-section" data-aos="fade-up">

    <div class="container">
    	<h1>{{ucfirst($expert->first_name)." ".ucfirst($expert->last_name)}}</h1>
    	<hr>
    	<div>&nbsp;</div>
    	<div class="blog-bx">
            <div class="row">
                <div class="col-sm-8 col-md-8">
                    <div class="blog-post">
                        <div class="thumbnail">
                            <div class="blog-img expertdetail-img-div">
                                <?php $eximg=($expert->profile_image!=null && $expert->profile_image!='') ? PROFILE_FULL_DISPLAY_PATH.$expert->profile_image:PROFILE_FULL_DISPLAY_PATH.'no_user_img.png'?>
                                        {{HTML::image($eximg,'our-clients',['class'=>'expertdetail-img'])}}
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                            	<h3 class="site-title expert-name">{{ucfirst($expert->first_name)." ".ucfirst($expert->last_name)}}<br>
                    			<span></span>
                    			</h3>
                    			<!-- <span class="social-icon pull-right" >
                                    <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                    <a href="#"><i class="fa fa-google" aria-hidden="true"></i></a>
                                    <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                    <a href="#"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
                                    <a href="#"><i class="fa fa-pinterest-p" aria-hidden="true"></i></a>
                                    <a href="#"><i class="fa fa-vimeo" aria-hidden="true"></i></a>
                                </span> -->
                            </div>
                            </div>
                            <!-- <div class="caption"> -->
                                <!-- <h3><a href="#">Five Easy Steps for Creating Gala Smoky Eyes</a></h3> -->
                                <!-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet bibendum nisl. Etiam a quam pellentesque interdum vel at risus. Curabitur tempor porttitor egestas. </p>
                                <div class="date-by">
                                    <span>Jun 16, 2018</span>
                                    <div class="by-post">by <a href="#">Mary Lucas</a></div>
                                </div> -->
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4">
                    <div class="blog-right expert-right">
                        <div class="blog-right-bx categories-right-bx" >
                            <h2 >Quick Profile</h2>
                            <div class="categories-bx">
                                <div class="expert-right-border">
                                	<span>Name : </span><span class="pull-right">{{ucfirst($expert->first_name)." ".ucfirst($expert->last_name)}}</span>
                                </div>
                                <!-- <div class="expert-right-border">
                                	<span>Address : </span><span class="pull-right">Shivangi</span>
                               	</div> -->
                                <!-- <div class="expert-right-border">
                                	<span>Phone : </span><span class="pull-right">Shivangi</span>
                                </div> -->
                                <div class="expert-right-border">
                                	<span>Email : </span><span class="pull-right">{{$expert->email}}</span>
                                </div>
                                <!-- <div class="expert-right-border">
                                	<span>Speciality : </span><span class="pull-right">Shivangi</span>
                                </div> -->
                                <!-- <div class="expert-right-border">
                                	<span>Degree : </span><span class="pull-right">Shivangi</span>
                                </div> -->
                                <div class="expert-right-border">
                                	<span>Services : </span><span class="pull-right">{{$serviceNames}}</span>
                                </div>
                                <div>&nbsp;</div>
                                <div class="col-md-12">
                                    <div class="mx-auto">
                                	<div class="form-group"><a href="{{url('/selectservice/'.$expert->slug)}}" class="rounded-0 btn  btn-primary btn-block ">REQUEST APPOINMENT</a></div>
                                	<div>
                                            <a href="#" class="rounded-0 btn btn-primary btn-block" data-toggle="modal" data-target="#expertdModal">VIEW TIMETABLE</a>
                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="expertdModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Time Table</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="view-timetbal">
              <div class="time-table">
                  <div class="time-table-tr">
                      <div class="time-table-th">Day</div>
                      <div class="time-table-th">Opening Time</div>
                      <div class="time-table-th">Closing Time</div>
                  </div>
                @php global $week_days;
                    $working_days_arr = explode(',',$expert->working_days);
                    $start_time_arr = explode(',',$expert->start_time);
                    $end_time_arr = explode(',',$expert->end_time);
                @endphp
                @foreach($week_days as $wd_key=>$wd_val)
              
                    @if(in_array($wd_key, $working_days_arr))
                        <div class="time-table-tr">
                            <div class="time-table-td">{{ $wd_val }}</div>
                            <div class="time-table-td">{{ date("h:i A",strtotime($start_time_arr[array_search ($wd_key,$working_days_arr)])) }}</div>
                            <div class="time-table-td">{{ date("h:i A",strtotime($end_time_arr[array_search ($wd_key,$working_days_arr)])) }}</div>
                        </div>
                    @else
                        <div class="time-table-tr">
                            <div class="time-table-td">{{ $wd_val }}</div>
                            <div class="time-table-td">Closed</div>
                            <div class="time-table-td">Closed</div>
                        </div>
                    @endif
                @endforeach
                  
              </div>
          </div>
      </div>
      
    </div>
  </div>
</div>
@endsection