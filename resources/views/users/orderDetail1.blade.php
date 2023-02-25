@extends('layouts.inner')
@section('content')
<section class="oder_details">
  <div class="container">
    <div class="row">
      <div class="home_myaccount">
        <ul>
          <li><a href="#">
            Home 
          </a></li>
          <li><a href="#">
           <i class="fa fa-angle-left"></i> My Account 
          </a></li>
          <li><a href="#">
           <i class="fa fa-angle-left"></i>  My Orders 
          </a></li>
           <li><a href="#">
           <i class="fa fa-angle-left"></i> OD113456789234576
          </a></li>
        </ul>
      </div>
    </div>
    <div class="row delivery">
   
      <div class="col-md-6">
        <div class="address">
          <h3>Delivery Address</h3>
          <h6>Santosh Mittal</h6>
          <p>8, Indira Nagar, Opposite Gopalpura Turn, Tonk Road, Jaipur, Rajasthan 302018</p>
          <p class="phone_no">
            Phone no <span> 456788924</span>
          </p>
        </div>

        
      </div>
      <div class="col-md-6">
       
      </div>
        
     
   
  </div>
  <div class="your_reward">
    <div class="row">
   
      <div class="col-md-3">
           <div class="images_withtext">
      <div class="images-section">
        <img src="http://192.168.0.251:85/comp212/groupon_clone/site/public/files/product/small/e6641ab8_flower4.jpeg" alt="">
      </div>
      <div class="itemDetails columns nine">
      <div class="itemTitle">
          H&amp;R Block
      </div>
          <div>
          
           <p>seller :<span>Qudeendom </span></p>
           <p class="price">
             ₹ 509
           </p>
          </div> 
       
    </div>
    </div>
    </div>

        
      
      <div class="col-md-6">
        
    
       
           
         
           <div class="line_box">
<div class="text_circle done">
<div class="circle">
<h4>Ordered</h4>

</div>
<a href="javascript:void(0)" class="tvar"><span data-toggle="popover" title="" data-trigger="hover" data-placement="top" data-content="Buyer can login securely using Facebook" data-original-title=""><i class="fa fa-user"></i></span></a>
</div>
<div class="text_circle">
<div class="circle">
<h4>Packed</h4>

</div>
<a href="javascript:void(0)" class="tvar"><span data-toggle="popover" title="" data-trigger="hover" data-placement="top" data-content="Buyer can login securely" data-original-title=""><i class="fa fa-archive"></i></span></a>
</div>
<div class="text_circle">
<div class="circle">
<h4>Shipped</h4>

</div>
<a href="javascript:void(0)" class="tvar"><span data-toggle="popover" title="" data-trigger="hover" data-placement="top" data-content="Buyer can login" data-original-title=""><i class="fa fa-car"></i></span></a>
</div>
<div class="text_circle">
<div class="circle">
<h4>DELIVERED</h4>

</div>
<a href="javascript:void(0)" class="tvar"><span data-toggle="popover" title="" data-trigger="hover" data-placement="top" data-content="Buyer can" data-original-title=""><i class="fa fa-home"></i></span></a>
</div>
</div>
       
           
           
        
  
      </div>
      <div class="col-md-3">
        <div class="deleverd_rate">
          <h5>Delivered on march 17, 2020</h5>
          <a href="#"><i class="fa fa-star"></i> Rate & Review Product </a>
          <p><a href="#"><i class="fa fa-question-circle"></i> Need Help</a></p>
        </div>
      </div>
        </div>
     </div>
   
</div>
</section>

<script type="text/javascript">
  $(function () {
  $('[data-toggle="popover"]').popover();
});
</script>





@endsection