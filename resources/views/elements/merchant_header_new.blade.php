
<?php

$location = DB::table('locations')->where(['status' => 1])->orderBy('location_name', 'ASC')->pluck('location_name', 'id'); ?>
<header>
    <div class="header">
        <div class="header__secondary">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-4 col-lg-6">
                        <!--<div class="city-selector">
                            <div class="texticon-group">
                                <div class="texticon-group__icon padding-right-xs show-on-tab">
                                    <i class="fa fa-map-marker"></i>
                                    <span class="font-sm txt-tertiary">Select Location</span>
                                </div>
                                <div class="select-location">
                                    <span>
                                        {{Form::select('location_id', $location,null, ['class'=>'form-control', 'id'=>'location_id'])}}
                                        
                                    </span>
                                </div>
                            </div>
                        </div>-->
                    </div>
                    <div class="col-xs-12 col-md-7 col-lg-6 desktop-show">
                        <div class="right_p0">
                            <ul class="list_inline secondary_links flt-right">
							@if(isset(Auth::guard('merchant')->user()->id))
                                <li class="show-on-tab">
								   
                                    <a href="{{ URL::to( 'merchant/user/myaccount')}}" class="utility-btn"><i class="fa fa-user-circle"> My Profile </i></a>
									
                                </li>
                                <li class="show-on-tab">
                                    <a class="utility-btn" href="{{ URL::to( 'merchant/deals')}}"><i class="fa fa-slideshare"> My deals </i></a>
                                </li>
								 @else
									<!--<li class="show-on-tab">
								   
                                    <a href="#" class="utility-btn"> How it works </a>
									
                                </li>
                                <li class="show-on-tab">
                                    <a class="utility-btn" href="{{URL::to('/merchant/register')}}"> List your Business </a>
                                </li>-->
								@endif
                                <li class="line-height-default">
                                    <div class="utility-btn">
                                        <div class="texticon-group">
                                            <div class="texticon-group__icon vertical-align-middle padding-top-zero padding-right-xs">
                                                
                                                @if(isset(Auth::guard('merchant')->user()->id))
                                                <i class="fa fa-user"></i>
                                                <span class="font-xs">
                                                    <a href="{{URL('/merchant/logout')}}">Logout</a>
                                                    /
                                                    <a href="{{URL('/merchant/user/myaccount')}}">My Account</a>
                                                </span>
                                                @else
                                                <i class="fa fa-lock"></i>
                                                <span class="font-xs">
                                                    <a href="{{URL('/merchant/login')}}">Login</a>
                                                    /
                                                    <a href="{{URL('/merchant/register')}}">Sign Up</a>
                                                </span>
                                                @endif
                                                
                                            </div>
                                        </div>
                                    </div>
                                </li>
								@if(isset(Auth::guard('merchant')->user()->id))
                                <li class="my-stuff1 dropdown dropdown-list-toggle">
  <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle  beep-warning"> 
   
My Stuff <i class="fa fa-caret-down"></i> </a>
    <div class="my-stuff dropdown-menu dropdown-list dropdown-menu-right dropdown">
     <ul>
         <li> <i class="fa fa-tags"></i> <a href="#">My Groupons</a></li>
         
             <li> <i class="fa fa-handshake-o"></i> <a href="{{URL('/merchant/deals/add')}}"> Add Deal</a></li>
               <!--<li> <i class="fa fa-heart-o"></i> <a href="#">My Wishlist</a></li>-->
     </ul>
    
     
     
    </div>
  </li>
  @endif
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header__primary">
            <div class="wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-md-7 col-lg-6">
                            <a class="navbar-brand" href="{{URL::to('/')}}">
                                {{HTML::image('public/img/front/logo.png','logo', array('class' => 'header-logo'))}}
                            </a>
                           
</div>
<div class="col-xs-12 col-md-5 col-lg-6">

</div>
</div>
</div>
</div>
</div>
<div class="menu_header">
<div class="container">
<div class="row">
<div class="col-lg-12">
<nav class="navbar navbar-expand-lg">
<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-label="Toggle navigation">
<div class="toggle position-relative">
<div class="line top position-absolute"></div>
<div class="line middle cross1 position-absolute"></div>
<div class="line middle cross2 position-absolute"></div>
<div class="line bottom position-absolute"></div>
</div>
</button>
<div class="fix_logo">
<a href="{{URL::to('/')}}">
{{HTML::image('public/img/front/logo.png','logo', array('class' => 'header-logo'))}}
</a>
</div>
<div class="collapse navbar-collapse" id="navbarSupportedContent">
<ul class="navbar-nav mr-auto">
@if(session()->has('user_id'))
<li class="nav-item mobile_sh">
<a class="nav-link" href="{{URL::to('/logout')}}">Logout </a>
</li>
<li class="nav-item mobile_sh">
<a class="nav-link" href="{{URL::to('users/dashboard')}}">My Account</a>
</li>
@else
<li class="nav-item mobile_sh">
<a class="nav-link" href="{{URL::to('login')}}">Login </a>
</li>
<li class="nav-item mobile_sh">
<a class="nav-link" href="{{URL::to('register')}}">Sign Up</a>
</li>
@endif
</ul>
</div>
</nav>
</div>
</div>
</div>
</div>
</div>
</header> 
<script type="text/javascript">
//    function searchcategory(value) {
//        var slug = value;
//       // alert(slug);
//        $.ajax({
//            type: 'POST',
//            url: "<?php echo HTTP_PATH . '/products/search'; ?>",
//            data: {'slug': slug},
//           // cache: false,
////            beforeSend: function () {
////                $("#loaderID").show();
////            },
//            success: function (data) {
//                 alert(data);
//                //NProgress.done();
//                $("#tab1").html(data);
//                $("#loaderID").hide();
//
//
//            },
//            error: function (data) {
//                console.log("error");
//                console.log(data);
//            }
//        });
//        return false;
//    }
 </script>
 