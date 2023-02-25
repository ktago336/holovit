<?php 
$merchants_citie_ids = DB::table('merchants')->where(['status' => 1])->groupBy('city_id')->orderBy('city_id', 'ASC')->pluck('city_id', 'city_id');

$cityids = array_filter(json_decode(json_encode($merchants_citie_ids),true));
$cities_obj = DB::table('cities')->where(['status' => 1])->whereIn('id',$cityids)->orderBy('name', 'ASC')->pluck('name', 'id');
$cities = array_filter(json_decode(json_encode($cities_obj),true)); 
if(!Session::get('session_city_id')){
	Session::put('session_city_id', array_keys($cities)[0]);
	$is_location = Session::get('session_city_id');
	//echo $is_location;exit;
}else{
	$is_location = Session::get('session_city_id');
}
 //exit;?>
<script>
$(document).ready(function(){
	$("#city_id").on('change',function(){
		$("#setlocation").submit();
		//alert($(this).val());
	   //var name = $("#nameInput").val(),
	   //city_id = $(this).val();
	   //$.cookie('back_to_url_onPage_referesh', 1);
	   //$.cookie('name',name);
	   //$.cookie('city_id',city_id);
	   //alert($.cookie('city_id'));
	   /*$.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
	   $.ajax({
            type: 'POST',
            url: "<?php echo HTTP_PATH . '/deals/setlocation'; ?>",
			data: {'city_id': city_id},
            success: function (data) {
                 //alert(data);
            }
        });*/
	   
	   
	   
	});
	var location ='<?php echo $is_location;?>';
	if(location){
		//alert(location);
		$("#city_id").val(location); 
	}else{
		//$.cookie('city_id')
	}
});
</script>
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
 
<?php

//echo '<pre>';print_r($categories); exit;
//$location = DB::table('locations')->where(['status' => 1])->orderBy('location_name', 'ASC')->pluck('location_name', 'id'); 



?>
<header>
    <div class="header">
        <div class="header__primary">
            <div class="wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-md-5 col-lg-4">
                            <a class="navbar-brand" href="{{URL::to('/')}}">
                                {{HTML::image('public/img/front/logo.png','logo', array('class' => 'header-logo'))}}
                            </a>
							 <div class="city-selector">
					{{ Form::open(array('url'=>'deals/setlocation','method' => 'post', 'id' => 'setlocation')) }}

                       
                            <div class="texticon-group">
                                <div class="texticon-group__icon padding-right-xs show-on-tab">
                                    <i class="fa fa-map-marker"></i>
                                </div>
                                <div class="select-location">
                                    <span>
                                        {{Form::select('city_id', $cities,null, ['placeholder'=>'Select Location', 'class'=>'form-control', 'id'=>'city_id'])}}
                                        
                                    </span>
                                </div>
                            </div>
                       
						{{ Form::close() }}

                    </div>
							
                           
</div>
<div class="col-xs-12 col-md-6 col-lg-4">
<div class="restaurants-search">
{{ Form::open(array('url'=>'deals/search','method' => 'post', 'id' => 'searchForm','onsubmit' => "return searchform()")) }}

<div class="input-group">
{{Form::text('keyword', isset($search_keyword)?$search_keyword:null, ['class'=>'form-control', 'placeholder'=>'Search restaurants, spa, events', 'autocomplete' => 'off'])}}
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


<div class="col-xs-12 col-md-4 col-lg-4 desktop-show">
                        <div class="right_p0">
                            <ul class="list_inline secondary_links flt-right">
                                <li class="show-on-tab">
                                    <a href="{{URL::to('/how-it-works')}}" class="utility-btn">How it Works</a>
                                </li>
                                
                                @if(session()->has('user_id'))
                                 <li class="show-on-tab"><a href="{{URL::to('users/dashboard')}}">My Account</a></li>
                                     @else
                                <li class="show-on-tab">
                                    <a class="utility-btn" href="{{URL::to('/merchant/register')}}">List your Business</a>
                                </li>
                                <li class="show-on-tab"> <a href="{{URL::to('login')}}">Login</a></li>
                                 <li class="show-on-tab"><a href="{{URL::to('register')}}">Sign Up</a></li>
                                 @endif
                                
								@if(session()->has('user_id'))
                                <li class="my-stuff1 dropdown dropdown-list-toggle">
  <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle  beep-warning"> 
   
My Stuff <i class="fa fa-caret-down"></i> </a>
    <div class="my-stuff dropdown-menu dropdown-list dropdown-menu-right dropdown">
     <ul>
		<li> <i class="fa fa-user"></i> <a href="{{URL('/users/myaccount')}}">My Profile</a></li> 
        <li> <i class="fa fa-shopping-bag"></i> <a href="{{URL('/users/myorders')}}">My Orders</a></li>
		<li> <i class="fa fa-google-wallet"></i> <a href="{{URL('/users/mywallet')}}">My Wallet</a></li> 
		<li> <i class="fa fa-history"></i> <a href="{{URL('/users/mypayments')}}">Payment History</a></li> 
        <li> <i class="fa fa-sign-out"></i> <a href="{{URL('/logout')}}">Logout</a></li> 
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
<ul class="navbar-nav mr-auto ml-auto">
<!--<li class="nav-item <?php echo (!isset($slug) && isset($page_name) && $page_name=='search')?'active':''?>">
<a class="nav-link" href="<?php echo HTTP_PATH.'/deals/search';?>">Featured</a>
<a class="nav-link" href="<?php echo HTTP_PATH.'/deals/search';?>"><i>{{HTML::image('public/img/front/food-icon.png','icon')}}</i>All</a>
</li>-->
<?php $categories = DB::table('categories')->where(['parent_id' => 0, 'status' => 1, 'is_feature' => 1])->orderBy('category_name', 'ASC')->take(8)->get(); ?>
@if($categories)
@foreach ($categories as $allrecord)
<li class="nav-item <?php echo (isset($slug) && $slug==$allrecord->slug)?'active':''?>">
<a class="nav-link" href="{{URL::to('deals/search/'.$allrecord->slug)}}"><i>{{HTML::image(CATEGORY_FULL_DISPLAY_PATH.$allrecord->category_image,'icon')}}</i>{{ $allrecord->category_name }}</a>
</li>
@endforeach
@endif
<!--
<li class="nav-item">
<a class="nav-link" href="#">Deals of the Day</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#">Coupons</a>
</li>
<li class="nav-item">
<a class="nav-link" href="#">Sale</a>
</li>
<li class="nav-item">
<a class="nav-link" href="{{URL::to('products/search')}}">Category</a>
</li>

<li class="nav-item">
<a class="nav-link" href="{{URL::to('merchants/dashboard')}}">merchant dashboard</a>
</li>-->
<li class="nav-item mobile_sh">
<a class="nav-link" href="#">How it Works</a>
</li>
<li class="nav-item mobile_sh">
<a class="nav-link" href="{{URL::to('/merchant/register')}}">List your Business</a>
</li>
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
 </script> <script>$(document).ready(function() {  $('.message-toggle').click(function() {    $('.my-stuff').slideToggle('slow');  });}); </script>
 