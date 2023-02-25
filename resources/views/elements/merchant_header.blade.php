<header>
    <div class="header">
        <div class="header__primary">
            <div class="wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-md-4 col-lg-4">
                            <a class="navbar-brand" href="{{URL::to('/')}}">
                                {{HTML::image('public/img/front/logo.png','logo', array('class' => 'header-logo'))}}
                            </a>
						
							
                           
</div>
<div class="col-xs-12 col-md-4 col-lg-4">

</div>


<div class="col-xs-12 col-md-4 col-lg-4 desktop-show">
                        <div class="right_p0">
                            <ul class="list_inline secondary_links flt-right">
                                
                                @if(isset(Auth::guard('merchant')->user()->id))
                                <li class="show-on-tab">
								   
                                    <a href="{{ URL::to( 'merchant/redeem-voucher')}}" class="utility-btn">Redeem Voucher </i></a>
									
                                </li>
                                 <li class="show-on-tab"><a href="{{URL::to('merchant/users/myaccount')}}">My Account</a></li>
                                     @else
                                 <li class="show-on-tab"> <a href="{{URL::to('merchant/login')}}">Login</a></li>
                                 <li class="show-on-tab"><a href="{{URL::to('merchant/register')}}">Sign Up</a></li>
                                 @endif
                                
								@if(isset(Auth::guard('merchant')->user()->id))
                                <li class="my-stuff1 dropdown dropdown-list-toggle">
  <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg message-toggle  beep-warning"> 
   
My Stuff <i class="fa fa-caret-down"></i> </a>
    <div class="my-stuff dropdown-menu dropdown-list dropdown-menu-right dropdown">
     <ul>
        <li> <i class="fa fa-dashboard"></i> <a href="{{URL('/merchant/dashboard')}}">My {{SITE_TITLE}}</a></li>
		<li> <i class="fa fa-gift"></i> <a href="{{URL('/merchant/redeem-voucher')}}">Redeem Voucher</a></li> 
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

@if(isset(Auth::guard('merchant')->user()->id))
<li class="nav-item mobile_sh">
<a class="nav-link" href="{{URL::to('merchant/logout')}}">Logout </a>
</li>
<li class="nav-item mobile_sh">
<a class="nav-link" href="{{URL::to('merchant/users/myaccount')}}">My Account</a>
</li>
@else
<li class="nav-item mobile_sh">
<a class="nav-link" href="{{URL::to('merchant/login')}}">Login </a>
</li>
<li class="nav-item mobile_sh">
<a class="nav-link" href="{{URL::to('merchant/register')}}">Sign Up</a>
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



 <script>
$(document).ready(function() {
  $('.message-toggle').click(function() {
    $('.my-stuff').slideToggle('slow');
  });
});
 </script>



















