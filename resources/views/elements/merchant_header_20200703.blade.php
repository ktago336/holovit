<header>
    <div class="header">
        <div class="header__secondary">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-md-4 col-lg-6">
                        
                    </div>
                    <div class="col-xs-12 col-md-7 col-lg-6 desktop-show">
                        <div class="right_p0">
                            <ul class="list_inline secondary_links flt-right">
							@if(isset(Auth::guard('merchant')->user()->id))
                                <!--<li class="show-on-tab">
								   
                                    <a href="{{ URL::to( 'merchant/user/myaccount')}}" class="utility-btn">My Profile </i></a>
									
                                </li>-->
								<li class="show-on-tab">
								   
                                    <a href="{{ URL::to( 'merchant/redeem-voucher')}}" class="utility-btn">Redeem Voucher </i></a>
									
                                </li>
                                <!--<li class="show-on-tab">
                                    <a class="utility-btn" href="{{ URL::to( 'merchant/deals')}}"><i class="fa fa-slideshare"> My deals </i></a>
                                </li>-->
								 @else
									<li class="show-on-tab">
								   
                                    <a href="#" class="utility-btn"> How it works </a>
									
                                </li>
                                <li class="show-on-tab">
                                    <a class="utility-btn" href="{{URL::to('/merchant/register')}}"> List your Business </a>
                                </li>
								@endif
                                <li class="line-height-default">
                                    <div class="utility-btn">
                                        <div class="texticon-group">
                                            <div class="texticon-group__icon vertical-align-middle padding-top-zero padding-right-xs">
                                                
                                                @if(isset(Auth::guard('merchant')->user()->id))
                                                <?php /*<i class="fa fa-user"></i>
                                                <span class="font-xs">
                                                    <a href="{{URL('/merchant/logout')}}">Logout</a>
                                                    <!--/
                                                    <a href="{{URL('/merchant/user/myaccount')}}">My Account</a>-->
                                                </span>*/?>
												<span class="font-xs">
												<a href="{{ URL::to( 'merchant/user/myaccount')}}" class="utility-btn">My Profile </i></a>
                                                </span>
												@else
                                                <i class="fa fa-lock"></i>
                                                <span class="font-xs">
                                                    <a href="{{URL('/merchant/login')}}">Login</a>
                                                    <!--/
                                                    <a href="{{URL('/merchant/register')}}">Sign Up</a>-->
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
        <li> <i class="fa fa-dashboard"></i> <a href="{{URL('/merchant/dashboard')}}">My Groupons</a></li>
		<li> <i class="fa fa-gift"></i> <a href="{{URL('/merchant/redeem-voucher')}}">Redeem Code</a></li> 
        <li> <i class="fa fa-handshake-o"></i> <a href="{{URL('/merchant/deals')}}">My Deals</a></li> 
		<li> <i class="fa fa-shopping-bag"></i> <a href="{{URL('/merchant/myorders')}}">My Orders</a></li> 
		<li> <i class="fa fa-google-wallet"></i> <a href="{{URL('/merchant/mywallet')}}">My Wallet</a></li> 
		<!--<li> <i class="fa fa-history"></i> <a href="{{URL('/merchant/mypayments')}}">Payment History</a></li>-->
		<li> <i class="fa fa-sign-out"></i> <a href="{{URL('/merchant/logout')}}">Logout</a></li> 
		<!--<li> <i class="fa fa-tags"></i> <a href="/merchant/redemptions">My Redemptions</a></li> -->
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
                            <div class="selest-around">
                                <div class="dropdown categories_navarea show_sub">
                                    <button type="button" class="btn btn-primary dropdown-toggle show_sub_section " data-toggle="dropdown">
                                    Categories
                                    </button>
                                    
                                    
                                    <div class="new_categoriesarea_new">
                                        <?php 
                                        $categories = DB::table('categories')->where(['parent_id' => 0, 'status' => 1])->orderBy('category_name', 'ASC')->get();
                                        $subcatename = '';
                                        $i = 0; 
                                        //print_r($categories); exit;
                                        ?>
                                         @foreach($categories as  $allrecord)
                                      <?php  //print_r($allrecord); exit; ?>
                                        <ul class="<?php if($i == 0){ echo 'sub_section';}else{ } ?>">
                                           <?php  $subcategories = DB::table('categories')->where(['parent_id' => $allrecord->id, 'status' => 1])->orderBy('category_name', 'ASC')->get(); 
//                                           print_r($subcategories); 
//                                           exit;
                                           
                                           ?>
                                            
                                            <li>
                                              
                                                <a href="{{URL::to('deals/search/'.$allrecord->slug)}}" class="category_local">{{$allrecord->category_name}}</a> <i class="fa fa-angle-right"></i>
                                                
                                                       @if($subcategories)
                                                  @foreach($subcategories as $subcatename) 
                                                <div class="level-one-top">
                                                    <div class="level-one">
                                                        <div class="level-one-title"><a href="">{{$allrecord->category_name}}</a>
                                                    </div>
                                                       
                                                  
                                                    <ul class="">
                                                       
                                                        <li><a href="{{URL::to('deals/search/'.$subcatename->slug)}}" >{{$subcatename->category_name}}</a>  </li>
                                                      
                                                         
                                                    </ul>
                                                     
                                                    
                                                </div>
                                                             
                                                <div class="level-one">
                                                    <div class="level-one-title"><a href="">{{$subcatename->category_name}}</a>
                                                </div>
                                                    <?php $subsubcategories = DB::table('categories')->where(['parent_id' => $subcatename->id, 'status' => 1])->orderBy('category_name', 'ASC')->get(); ?>
                                                              @foreach($subsubcategories as  $subsubcatename)
                                                <ul class="">
                                                    <li><a href="">{{$subsubcatename->category_name}}</a>
                                                </li>
                                                </ul>
                                                            @endforeach  
                                 
                            </div>
                           
                        </div>
                           @endforeach
                           
                                                     @endif               
                    </li>
                   
                 
                </ul>
                                         <?php $i++; ?>
@endforeach

<!--                <ul>
                    <li>
                        <a href="" class="category_local">Goods</a> <i class="fa fa-angle-right"></i>
                        
                        <div class="level-one-top">
                            <div class="level-one">
                                <div class="level-one-title"><a href="">Automotive</a>
                            </div>
                            <ul class="">
                                <li><a href="">Banners,s</a> </li>
                                <li><a href="">Banners,s</a> </li>
                                
                                
                                
                                
                                
                                
                            </ul>
                            
                            
                        </div>
                        <div class="level-one">
                            <div class="level-one-title"><a href="">Online Learning</a>
                        </div>
                        <ul class="">
                            <li><a href="">Banners, Signs And Nameplates</a>
                        </li>
                        <li><a href="">Banners, Signs And Nameplates</a>
                    </li>
                    
                    
                    <li><a href="">Banners, Signs And Nameplates</a>
                </li>
                
                
                
            </ul>
        </div>
    </div>
</li>
</ul>



           <ul>
                                            <li>
                                                <a href="" class="category_local">Hotels & Travel</a> <i class="fa fa-angle-right"></i>
                                                
                                                <div class="level-one-top">
                                                    <div class="level-one">
                                                        <div class="level-one-title"><a href="">Automotive</a>
                                                    </div>
                                                    <ul class="">
                                                        <li><a href="">Banners,s</a>  </li>
                                                        <li><a href="">Banners,s</a>  </li>
                                                        
                                                        <li><a href="">Banners,s</a>  </li>
                                                        
                                                        
                                                        
                                                        
                                                        
                                                    </ul>
                                                    
                                                    
                                                </div>
                                                <div class="level-one">
                                                    <div class="level-one-title"><a href="">Online Learning</a>
                                                </div>
                                                <ul class="">
                                                    <li><a href="">Banners, Signs And Nameplates</a>
                                                </li>
                                              
                                            
                                        <li><a href="">Banners, Signs And Nameplates</a>
                                    </li>
                                    
                                    
                                    
                                </ul>
                                
                                
                            </div>
                            
                        </div>
                    </li>
                    
                    
                </ul>




                
           <ul>
                                            <li>
                                                <a href="" class="category_local">Coupons</a> <i class="fa fa-angle-right"></i>
                                                
                                                <div class="level-one-top">
                                                    <div class="level-one">
                                                        <div class="level-one-title"><a href="">Automotive</a>
                                                    </div>
                                                    <ul class="">
                                                        <li><a href="">Banners,s</a>  </li>
                                                        <li><a href="">Banners,s</a>  </li>
                                                        
                                                        <li><a href="">Banners,s</a>  </li>
                                                        
                                                        
                                                        
                                                        
                                                        
                                                    </ul>
                                                    
                                                    
                                                </div>
                                                <div class="level-one">
                                                    <div class="level-one-title"><a href="">Online Learning</a>
                                                </div>
                                                <ul class="">
                                                    <li><a href="">Banners, Signs And Nameplates</a>
                                                </li>
                                              
                                            
                                        <li><a href="">Banners, Signs And Nameplates</a>
                                    </li>
                                    
                                    
                                    
                                </ul>
                                
                                
                            </div>
                            
                        </div>
                    </li>
                    
                    
                </ul>-->
</div>
</div>
</div>
</div>
<div class="col-xs-12 col-md-5 col-lg-6">
<div class="restaurants-search">
{{ Form::open(array('url'=>'deals/search','method' => 'post', 'id' => 'searchForm')) }}

<div class="input-group">
{{Form::text('keyword', null, ['class'=>'form-control', 'placeholder'=>'Search restaurants, spa, events', 'autocomplete' => 'off'])}}
<!--<input type="text" class="form-control" placeholder="Search restaurants, spa, events" aria-label="Recipient's username" aria-describedby="basic-addon2">-->
<i class="fa fa-search" aria-hidden="true"></i>
<div class="input-group-append">
    
<!--<span class="input-group-text" id="basic-addon2">-->
{{Form::submit('Search', ['class' => 'input-group-text','id'=>'basic-addon2'])}}
<!--</span>-->
    
</div>
</div>
    {{ Form::close()}}
</div>
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
<!--<li class="nav-item active">
<a href="{{ URL::to( 'merchant/user/myaccount')}}" class="nav-link">My Profile</a>
</li>
<li class="nav-item">
<a class="nav-link" href="{{ URL::to( 'merchant/deals')}}">My deals</a>
</li>-->
<!-- @if($categories)
@foreach ($categories as $allrecord)
<li class="nav-item">
<a class="nav-link" href="{{URL::to('products/search/'.$allrecord->slug)}}">{{ $allrecord->category_name }}</a>
</li>
@endforeach
@endif -->
<!--                                    <li class="nav-item">
<a class="nav-link" href="#">Things To Do</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#">Beauty & Spas</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#">Local</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#">Goods</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#">Getaways</a>
</li>-->

<!--<li class="nav-item mobile_sh">
<a class="nav-link" href="#">How it Works</a>
</li>
<li class="nav-item mobile_sh">
<a class="nav-link" href="{{URL::to('/merchant/register')}}">List your Business</a>
</li>-->
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
 