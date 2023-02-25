<?php $__env->startSection('content'); ?>
<section class="banner-slider">
         <?php if($banners): ?>
<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
      <?php $i = 0; ?>
      <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
       
    <div class="carousel-item <?php if($i == 0) echo "active"; ?>">
	  <?php echo e(HTML::image(BANNER_FULL_DISPLAY_PATH.$banner->banner_image,'img1')); ?>

	   <div class="banner-text">
	   <div class="container">
	   <h1><?php echo e($banner->title); ?></h1>
	   <h2><?php echo nl2br($banner->short_description);?></h2>
<a href="<?php echo $banner->banner_url;?>" class="btn btn-primary">BOOK NOW</a>
    </div>
    </div>
    </div>
    <?php $i =1;?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    
  </div>
  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-prev-icon" aria-hidden="true"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-next-icon" aria-hidden="true"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
    <span class="sr-only">Next</span>
  </a>
</div>
<?php endif; ?>
			
			</section>
<div class="hidden" style="overflow: hidden; display: none; opacity: 0">
<h1>Groupon clone script</h1>
<h2>Daily deal software</h2>
</div>
		<?php if($services): ?>	
 <section class="offer-section">
            <div class="container">
						<div class="offer-site">
						<h2>What Next is in your Mind ?</h2>
						<div class="offer-slider">
						<div id="offer-site" class="owl-carousel owl-theme">
						    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						    <?php if(file_exists(CATEGORY_FULL_UPLOAD_PATH.$service->category_image)): ?>
                            <div class="item">
                                <div class="offer-border-section">
                                    <div class="offer-img-section text-center">
                                        <a href="<?php echo e(HTTP_PATH.'/deals/search/'.$service->slug); ?>">
										<?php echo e(HTML::image(CATEGORY_FULL_DISPLAY_PATH.$service->category_image,'img1')); ?>

                                        </a>
                                    </div>
                                   <div class="offer-title"><a href="<?php echo e(HTTP_PATH.'/deals/search/'.$service->slug); ?>"><?php echo e($service->category_name); ?></a></div>
                                </div>
                            </div>
                            <?php endif; ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							 <!--  <div class="item">
                                <div class="offer-border-section">
                                    <div class="offer-img-section text-center">
                                        <a href="">
										<?php echo e(HTML::image('public/img/front/massage-img.png','img1')); ?>

                                        </a>
                                    </div>
                                   <div class="offer-title"><a href="">Ftull Body Massage</a></div>
                                </div>
                            </div>
							
							   <div class="item">
                                <div class="offer-border-section">
                                     <div class="offer-img-section text-center">
                                        <a href="">
										<?php echo e(HTML::image('public/img/front/beard-shave.png','img1')); ?>

                                        </a>
                                    </div>
                                   <div class="offer-title"><a href="">Beard Shave</a></div>
                                </div>
                            </div>
							
							   <div class="item">
                               <div class="offer-border-section">
                                    <div class="offer-img-section text-center">
                                        <a href="">
										<?php echo e(HTML::image('public/img/front/waxing-img.png','img1')); ?>

                                        </a>
                                    </div>
                                   <div class="offer-title"><a href="">WAXING</a></div>
                                </div>
                            </div>
							
							   <div class="item">
                                <div class="offer-border-section">
                                    <div class="offer-img-section text-center">
                                        <a href="">
										<?php echo e(HTML::image('public/img/front/hair-cut.png','img1')); ?>

                                        </a>
                                    </div>
                                   <div class="offer-title"><a href="">Hair Cut</a></div>
                                </div>
                            </div>-->
                           
                          
                        </div>
                        </div>
                    </div>
               
                
            </div>
            </div>

        </section>
		<?php endif; ?>
		
		
		
		
		
		<?php if($dealsliders): ?>
		<?php $__currentLoopData = $dealsliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$dealslider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		
		<?php //print_r($dealslider);?>
		<?php if(!empty($dealslider)): ?>
		<section class="<?php echo e($dealslider[0]['catslug']); ?>-deals-section">
            <div class="container">
						<div class="offer-site">
						<h2><?php echo e($dealslider[0]['category_name']); ?> Deals <a href="<?php echo e(HTTP_PATH.'/deals/search/'.$dealslider[0]['catslug']); ?>">See All</a></h2>
						<div class="offer-slider">
						<div id="<?php echo e($dealslider[0]['catslug']); ?>-deals-site" class="owl-carousel owl-theme deals-sld-all">
						    <?php $__currentLoopData = $dealslider; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dealvalue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						    <?php $merchatimgarr = explode(',',$dealvalue['profile_image']); 
							    if(file_exists(MERCHANT_FULL_UPLOAD_PATH.$merchatimgarr[0])){   ?>
                            <div class="item">
							<div class="card">
							    
  <div class="deals-sld-img"><?php echo e(HTML::image(MERCHANT_FULL_DISPLAY_PATH.$merchatimgarr[0],'img1')); ?></div>
  <div class="card-body">
    <h5 class="card-title"><a href="<?php echo e(HTTP_PATH.'/deals/detail/'.$dealvalue['slug']); ?>"><?php echo e($dealvalue['busineess_name']); ?></a></h5>
    <p class="card-text"><?php echo e($dealvalue['locality_name']); ?></p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em><?php echo e(CURR.$dealvalue['voucher_price']); ?></em><span><?php echo e(CURR.$dealvalue['final_price']); ?></span>
   </div>
   <div class="salon-deals-price-right">
   <?php echo e(CURR.$dealvalue['discount']); ?>%OFF
   </div>
   
   </div>
  </div>
</div>
                            </div>
                            
                            <?php } //$dealslider=array();?>
                           	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           	
                            </div>
                        </div>
                    </div>
               
                
            </div>

        </section>
        <script>
            $(document).ready(function () {
                $('#<?php echo $dealslider[0]['catslug']; ?>-deals-site').owlCarousel({
                    loop: true,
                    margin: 10,
                    responsiveClass: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    responsive: {
                        0: {items: 1, nav: true},
                        600: {items: 2, nav: false},
                        1000: {items: 4, nav: true, loop: true, margin: 20
                        }
                    }
                })
            })
        </script>
	<?php endif; ?>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		<?php endif; ?>
		
		
		
		
		<?php /*?><section class="salon-deals-section">
            <div class="container">
						<div class="offer-site">
						<h2>Salon Deals <a href="#">See All</a></h2>
						<div class="offer-slider">
						<div id="salon-deals-site" class="owl-carousel owl-theme">
                            <div class="item">
							<div class="card">
  {{HTML::image('public/img/front/salon-img.png','img1')}}
  <div class="card-body">
    <h5 class="card-title"><a href="#">Figaro's Unisex Salon</a></h5>
    <p class="card-text">Punjabi Bagh West</p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em>$100</em><span>$80</span>
   </div>
   <div class="salon-deals-price-right">
   20%OFF
   </div>
   
   </div>
  </div>
</div>
                            </div>
							
							 <div class="item">
							<div class="card">
  {{HTML::image('public/img/front/salon-img.png','img1')}}
  <div class="card-body">
    <h5 class="card-title"><a href="#">Figaro's Unisex Salon</a></h5>
    <p class="card-text">Punjabi Bagh West</p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em>$100</em><span>$80</span>
   </div>
   <div class="salon-deals-price-right">
   20%OFF
   </div>
   
   </div>
  </div>
</div>
                            </div>
							
							 <div class="item">
							<div class="card">
  {{HTML::image('public/img/front/salon-img.png','img1')}}
  <div class="card-body">
    <h5 class="card-title"><a href="#">Figaro's Unisex Salon</a></h5>
    <p class="card-text">Punjabi Bagh West</p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em>$100</em><span>$80</span>
   </div>
   <div class="salon-deals-price-right">
   20%OFF
   </div>
   
   </div>
  </div>
</div>
                            </div>
							
							 <div class="item">
							<div class="card">
  {{HTML::image('public/img/front/salon-img.png','img1')}}
  <div class="card-body">
    <h5 class="card-title"><a href="#">Figaro's Unisex Salon</a></h5>
    <p class="card-text">Punjabi Bagh West</p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em>$100</em><span>$80</span>
   </div>
   <div class="salon-deals-price-right">
   20%OFF
   </div>
   
   </div>
  </div>
</div>
                            </div>
							<div class="item">
							<div class="card">
 {{HTML::image('public/img/front/salon-img.png','img1')}}
  <div class="card-body">
    <h5 class="card-title"><a href="#">Figaro's Unisex Salon</a></h5>
    <p class="card-text">Punjabi Bagh West</p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em>$100</em><span>$80</span>
   </div>
   <div class="salon-deals-price-right">
   20%OFF
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
		
		
		
		
		
		
		
		
		
		
		
		
		
		<section class="restaurant-deals-section">
            <div class="container">
						<div class="offer-site">
						<h2>Restaurant Deals <a href="#">See All</a></h2>
						<div class="offer-slider">
						<div id="restaurant-deals-site" class="owl-carousel owl-theme">
                            <div class="item">
							<div class="card">
 {{HTML::image('public/img/front/salon-img.png','img1')}}
  <div class="card-body">
    <h5 class="card-title"><a href="#">Figaro's Unisex Salon</a></h5>
    <p class="card-text">Punjabi Bagh West</p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em>$100</em><span>$80</span>
   </div>
   <div class="salon-deals-price-right">
   20%OFF
   </div>
   
   </div>
  </div>
</div>
                            </div>
							
							 <div class="item">
							<div class="card">
  {{HTML::image('public/img/front/salon-img.png','img1')}}
  <div class="card-body">
    <h5 class="card-title"><a href="#">Figaro's Unisex Salon</a></h5>
    <p class="card-text">Punjabi Bagh West</p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em>$100</em><span>$80</span>
   </div>
   <div class="salon-deals-price-right">
   20%OFF
   </div>
   
   </div>
  </div>
</div>
                            </div>
							
							 <div class="item">
							<div class="card">
 {{HTML::image('public/img/front/salon-img.png','img1')}}
  <div class="card-body">
    <h5 class="card-title"><a href="#">Figaro's Unisex Salon</a></h5>
    <p class="card-text">Punjabi Bagh West</p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em>$100</em><span>$80</span>
   </div>
   <div class="salon-deals-price-right">
   20%OFF
   </div>
   
   </div>
  </div>
</div>
                            </div>
							
							 <div class="item">
							<div class="card">
  {{HTML::image('public/img/front/salon-img.png','img1')}}
  <div class="card-body">
    <h5 class="card-title"><a href="#">Figaro's Unisex Salon</a></h5>
    <p class="card-text">Punjabi Bagh West</p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em>$100</em><span>$80</span>
   </div>
   <div class="salon-deals-price-right">
   20%OFF
   </div>
   
   </div>
  </div>
</div>
                            </div>
							<div class="item">
							<div class="card">
  {{HTML::image('public/img/front/salon-img.png','img1')}}
  <div class="card-body">
    <h5 class="card-title"><a href="#">Figaro's Unisex Salon</a></h5>
    <p class="card-text">Punjabi Bagh West</p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em>$100</em><span>$80</span>
   </div>
   <div class="salon-deals-price-right">
   20%OFF
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
		
		
		
		
		
		
		
		<section class="spa-deals-section">
            <div class="container">
						<div class="offer-site">
						<h2>Spa Deals <a href="#">See All</a></h2>
						<div class="offer-slider">
						<div id="spa-deals-site" class="owl-carousel owl-theme">
                            <div class="item">
							<div class="card">
 {{HTML::image('public/img/front/salon-img.png','img1')}}
  <div class="card-body">
    <h5 class="card-title"><a href="#">Figaro's Unisex Salon</a></h5>
    <p class="card-text">Punjabi Bagh West</p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em>$100</em><span>$80</span>
   </div>
   <div class="salon-deals-price-right">
   20%OFF
   </div>
   
   </div>
  </div>
</div>
                            </div>
							
							 <div class="item">
							<div class="card">
  {{HTML::image('public/img/front/salon-img.png','img1')}}
  <div class="card-body">
    <h5 class="card-title"><a href="#">Figaro's Unisex Salon</a></h5>
    <p class="card-text">Punjabi Bagh West</p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em>$100</em><span>$80</span>
   </div>
   <div class="salon-deals-price-right">
   20%OFF
   </div>
   
   </div>
  </div>
</div>
                            </div>
							
							 <div class="item">
							<div class="card">
 {{HTML::image('public/img/front/salon-img.png','img1')}}
  <div class="card-body">
    <h5 class="card-title"><a href="#">Figaro's Unisex Salon</a></h5>
    <p class="card-text">Punjabi Bagh West</p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em>$100</em><span>$80</span>
   </div>
   <div class="salon-deals-price-right">
   20%OFF
   </div>
   
   </div>
  </div>
</div>
                            </div>
							
							 <div class="item">
							<div class="card">
 {{HTML::image('public/img/front/salon-img.png','img1')}}
  <div class="card-body">
    <h5 class="card-title"><a href="#">Figaro's Unisex Salon</a></h5>
    <p class="card-text">Punjabi Bagh West</p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em>$100</em><span>$80</span>
   </div>
   <div class="salon-deals-price-right">
   20%OFF
   </div>
   
   </div>
  </div>
</div>
                            </div>
							<div class="item">
							<div class="card">
 {{HTML::image('public/img/front/salon-img.png','img1')}}
  <div class="card-body">
    <h5 class="card-title"><a href="#">Figaro's Unisex Salon</a></h5>
    <p class="card-text">Punjabi Bagh West</p>
   <div class="salon-deals-price">
   <div class="salon-deals-price-left">
   <em>$100</em><span>$80</span>
   </div>
   <div class="salon-deals-price-right">
   20%OFF
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

        </section></php */?>
		
		
		<?php if($whyBuy): ?>
		    <section class="why-lscoupon-section">
                <div class="container">
               
    		<h2><?php echo e($whyBuy->whybuy_heading?$whyBuy->whybuy_heading:"Why Buy on LS COUPON ?"); ?></h2>
    		 <div class="why-lscoupon">
    		 <div class="row">
    		 <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
    		 <div class="why-lscoupon-bx">
    		 <i><?php echo e(HTML::image(LOGO_IMAGE_DISPLAY_PATH.$whyBuy->step1_image,'img1')); ?></i>
    		 <h3><?php echo e($whyBuy->step1_title); ?></h3>
    		 <p><?php echo e($whyBuy->step1_description); ?></p>
    		</div>
    		</div>
    		
    		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
    		 <div class="why-lscoupon-bx">
    		 <i><?php echo e(HTML::image(LOGO_IMAGE_DISPLAY_PATH.$whyBuy->step2_image,'img1')); ?></i>
    		 <h3><?php echo e($whyBuy->step2_title); ?></h3>
    		 <p><?php echo e($whyBuy->step2_description); ?></p>
    		</div>
    		</div>
    		
    		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
    		 <div class="why-lscoupon-bx">
    		 <i><?php echo e(HTML::image(LOGO_IMAGE_DISPLAY_PATH.$whyBuy->step3_image,'img1')); ?></i>
    		 <h3><?php echo e($whyBuy->step3_title); ?></h3>
    		 <p><?php echo e($whyBuy->step3_description); ?></p>
    		</div>
    		</div>
    		
    		</div>
    		</div>
		<?php endif; ?>
    		
		
		<?php if($topBrands): ?>
		<div class="topbrands-section">
          
           
		<h2>Top Brands</h2>
		 <div class="topbrands">
		<ul>
		    <?php $__currentLoopData = $topBrands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $topBrand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		    <?php $merchatimgarr = explode(',',$topBrand->profile_image); 
							    if(file_exists(MERCHANT_FULL_UPLOAD_PATH.$merchatimgarr[0]) && $topBrand->profile_image!=''){   ?>
		    <li><a href="<?php echo e(HTTP_PATH.'/deals/detail/'.$topBrand->slug); ?>"><?php echo e(HTML::image(MERCHANT_FULL_DISPLAY_PATH.$merchatimgarr[0],'img1')); ?></a></li>
		     <?php } ?>
	        <!--<li><a href="#"><?php echo e(HTML::image('public/img/front/brand-logo-1.png','img1')); ?></a></li>
    		<li><a href="#"><?php echo e(HTML::image('public/img/front/brand-logo-2.png','img1')); ?></a></li>
    		<li><a href="#"><?php echo e(HTML::image('public/img/front/brand-logo-3.png','img1')); ?></a></li>
    		<li><a href="#"><?php echo e(HTML::image('public/img/front/brand-logo-4.png','img1')); ?></a></li>
    		<li><a href="#"><?php echo e(HTML::image('public/img/front/brand-logo-5.png','img1')); ?></a></li>
    		<li><a href="#"><?php echo e(HTML::image('public/img/front/brand-logo-6.png','img1')); ?></a></li>-->
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</ul>
		</div>
	

        </div>
        <?php endif; ?>
</div>
        </section>
			
		

<!--<section class="trending-section">
    <div class="container">
        <h2 class="title_centre">Trending Right Now</h2>
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-3 col-xs-12">
                <div class="card">
                    <div class="card__inner">
                        <a  href="">
                            <div class="card__image">
                                <?php echo e(HTML::image('public/img/front/cardimg1.jpeg','Breakfast Buffets', array('class' => 'img-responsive', 'width'=>'261', 'data-lzled'=>'true'))); ?>

                                <div class="card__favourite">
                                    <label class="nb-checkbox">
                                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                                    </label>
                                </div>
                                <div class="card__rating">
                                    <span class="rating-icon nearbuy"></span>
                                    <span class="rating-score" style="color: rgb(63, 126, 0);">New</span>
                                </div>
                            </div>
                            <div class="card__description">
                                <p class="card__title">County's Kitchen</p>
                                <div class="card__location">
                                    <p class="card__location"> Big Bazaar Gift Vouchers Worth up to Rs-6000</p>
                                </div>
                                <hr class="margin-reset">
                                <div class="margin-top-s">
                                    <p class="txt-brand-secondary"><i class="fa fa-inr" aria-hidden="true"></i> 1,000</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3 col-xs-12">
                <div class="card">
                    <div class="card__inner">
                        <a  href="">
                            <div class="card__image">
                                <?php echo e(HTML::image('public/img/front/cardimg1.jpeg','Breakfast Buffets', array('class' => 'img-responsive', 'width'=>'261', 'data-lzled'=>'true'))); ?>

                                <div class="card__favourite">
                                    <label class="nb-checkbox">
                                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                                    </label>
                                </div>
                                <div class="card__rating">
                                    <span class="rating-icon nearbuy"></span>
                                    <span class="rating-score" style="color: rgb(63, 126, 0);">New</span>
                                </div>
                            </div>
                            <div class="card__description">
                                <p class="card__title">County's Kitchen</p>
                                <div class="card__location">
                                    <p class="card__location"> Big Bazaar Gift Vouchers Worth up to Rs-6000</p>
                                </div>
                                <hr class="margin-reset">
                                <div class="margin-top-s">
                                    <p class="txt-brand-secondary"><i class="fa fa-inr" aria-hidden="true"></i> 300</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3 col-xs-12">
                <div class="card">
                    <div class="card__inner">
                        <a  href="">
                            <div class="card__image">
                                <?php echo e(HTML::image('public/img/front/cardimg1.jpeg','Breakfast Buffets', array('class' => 'img-responsive', 'width'=>'261', 'data-lzled'=>'true'))); ?>

                                <div class="card__favourite">
                                    <label class="nb-checkbox">
                                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                                    </label>
                                </div>
                                <div class="card__rating">
                                    <span class="rating-icon nearbuy"></span>
                                    <span class="rating-score" style="color: rgb(63, 126, 0);">New</span>
                                </div>
                            </div>
                            <div class="card__description">
                                <p class="card__title">County's Kitchen</p>
                                <div class="card__location">
                                    <p class="card__location"> Big Bazaar Gift Vouchers Worth up to Rs-6000</p>
                                </div>
                                <hr class="margin-reset">
                                <div class="margin-top-s">
                                    <p class="txt-brand-secondary"><i class="fa fa-inr" aria-hidden="true"></i> 2,500</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3 col-xs-12">
                <div class="card">
                    <div class="card__inner">
                        <a  href="">
                            <div class="card__image">
                                <?php echo e(HTML::image('public/img/front/cardimg1.jpeg','Breakfast Buffets', array('class' => 'img-responsive', 'width'=>'261', 'data-lzled'=>'true'))); ?>

                                <div class="card__favourite">
                                    <label class="nb-checkbox">
                                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                                    </label>
                                </div>
                                <div class="card__rating">
                                    <span class="rating-icon nearbuy"></span>
                                    <span class="rating-score" style="color: rgb(63, 126, 0);">New</span>
                                </div>
                            </div>
                            <div class="card__description">
                                <p class="card__title">County's Kitchen</p>
                                <div class="card__location">
                                    <p class="card__location"> Big Bazaar Gift Vouchers Worth up to Rs-6000</p>
                                </div>
                                <hr class="margin-reset">
                                <div class="margin-top-s">
                                    <p class="txt-brand-secondary"><i class="fa fa-inr" aria-hidden="true"></i> 100</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="backgrounf_white">
    <div class="container">
        <h2>Browse By Category</h2>
        <div class="category-fild">
           
            <?php if($featCategories): ?>
            <ul>
            <?php $__currentLoopData = $featCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            
                <li>
                    <a href="#">
                        <i>
                            <?php echo e(HTML::image(CATEGORY_FULL_DISPLAY_PATH.$cate->category_image, $cate->category_name)); ?>

                        </i>
                        <span><?php echo e($cate->category_name); ?></span>
                    </a>
                </li>
            
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul> 
            <?php endif; ?>
            
        </div>
    </div>
</section>
<section class="coursal_p1">
    <div class="container">
        <h2 class="title_centre">Things To Do In Your City</h2>
        <div class="row">
            <div class="col">
                <div class="owl-carousel owl-theme">
                    <div class="item">
                        <div class="border-section">
                            <div class="img-section text-center">
                                <a href=""><?php echo e(HTML::image('public/img/front/c1.jpg','')); ?>

                                </a>
                            </div>
                            <div class="card__description">
                                <h3>Govind Marg</h3>
                                <ul class="product-details">
                                    <li>
                                        <i class="fa fa-tags"></i>
                                        <span>11 Offers</span>
                                    </li>
                                    <li>
                                        <i class="fa fa-inr" aria-hidden="true"></i> 9 - <i class="fa fa-inr" aria-hidden="true"></i> 2,000
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="border-section">
                            <div class="img-section text-center">
                                <a href=""><?php echo e(HTML::image('public/img/front/c1.jpg','')); ?>

                                </a>
                            </div>
                            <div class="card__description">
                                <h3>Govind Marg</h3>
                                <ul class="product-details">
                                    <li>
                                        <i class="fa fa-tags"></i>
                                        <span>11 Offers</span>
                                    </li>
                                    <li>
                                        <i class="fa fa-inr" aria-hidden="true"></i> 9 - <i class="fa fa-inr" aria-hidden="true"></i> 2,000
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="border-section">
                            <div class="img-section text-center">
                                <a href=""><?php echo e(HTML::image('public/img/front/c1.jpg','')); ?>

                                </a>
                            </div>
                            <div class="card__description">
                                <h3>Govind Marg</h3>
                                <ul class="product-details">
                                    <li>
                                        <i class="fa fa-tags"></i>
                                        <span>11 Offers</span>
                                    </li>
                                    <li>
                                        <i class="fa fa-inr" aria-hidden="true"></i> 9 - <i class="fa fa-inr" aria-hidden="true"></i> 2,000
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="border-section">
                            <div class="img-section text-center">
                                <a href=""><?php echo e(HTML::image('public/img/front/c1.jpg','')); ?>

                                </a>
                            </div>
                            <div class="card__description">
                                <h3>Govind Marg</h3>
                                <ul class="product-details">
                                    <li>
                                        <i class="fa fa-tags"></i>
                                        <span>11 Offers</span>
                                    </li>
                                    <li>
                                        <i class="fa fa-inr" aria-hidden="true"></i> 9 - <i class="fa fa-inr" aria-hidden="true"></i> 2,000
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<section class="coursal_p1">
    <div class="container">
        <h2 class="title_centre">Yummy Buffets</h2>
        <div class="row">
            <div class="col">
                <div class="owl-carousel owl-theme">
                    <div class="item">
                        <div class="border-section">
                            <div class="img-section text-center">
                                <a href=""><?php echo e(HTML::image('public/img/front/c1.jpg','')); ?>

                                </a>
                            </div>
                            <div class="card__description">
                                <h3>Govind Marg</h3>
                                <ul class="product-details">
                                    <li>
                                        <i class="fa fa-tags"></i>
                                        <span>11 Offers</span>
                                    </li>
                                    <li>
                                        <i class="fa fa-inr" aria-hidden="true"></i> 9 - <i class="fa fa-inr" aria-hidden="true"></i> 2,000
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="border-section">
                            <div class="img-section text-center">
                                <a href=""><?php echo e(HTML::image('public/img/front/c1.jpg','')); ?>

                                </a>
                            </div>
                            <div class="card__description">
                                <h3>Govind Marg</h3>
                                <ul class="product-details">
                                    <li>
                                        <i class="fa fa-tags"></i>
                                        <span>11 Offers</span>
                                    </li>
                                    <li>
                                        <i class="fa fa-inr" aria-hidden="true"></i> 9 - <i class="fa fa-inr" aria-hidden="true"></i> 2,000
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="border-section">
                            <div class="img-section text-center">
                                <a href=""><?php echo e(HTML::image('public/img/front/c1.jpg','')); ?>

                                </a>
                            </div>
                            <div class="card__description">
                                <h3>Govind Marg</h3>
                                <ul class="product-details">
                                    <li>
                                        <i class="fa fa-tags"></i>
                                        <span>11 Offers</span>
                                    </li>
                                    <li>
                                        <i class="fa fa-inr" aria-hidden="true"></i> 9 - <i class="fa fa-inr" aria-hidden="true"></i> 2,000
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="item">
                        <div class="border-section">
                            <div class="img-section text-center">
                                <a href=""><?php echo e(HTML::image('public/img/front/c1.jpg','')); ?>

                                </a>
                            </div>
                            <div class="card__description">
                                <h3>Govind Marg</h3>
                                <ul class="product-details">
                                    <li>
                                        <i class="fa fa-tags"></i>
                                        <span>11 Offers</span>
                                    </li>
                                    <li>
                                        <i class="fa fa-inr" aria-hidden="true"></i> 9 - <i class="fa fa-inr" aria-hidden="true"></i> 2,000
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="vill-all"><a href="#">View All Offers</a></div>
    </div>

</section>-->
 <script>
            $(document).ready(function () {
                $('#offer-site').owlCarousel({
                    loop: true,
                    margin: 10,
                    responsiveClass: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    responsive: {
                        0: {items: 1, nav: true},
                        600: {items: 2, nav: false},
                        1000: {items: 4, nav: true, loop: true, margin: 20
                        }
                    }
                })
            })
        </script>
		<!--<script>
            $(document).ready(function () {
                $('#salon-deals-site').owlCarousel({
                    loop: true,
                    margin: 10,
                    responsiveClass: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    responsive: {
                        0: {items: 1, nav: true},
                        600: {items: 2, nav: false},
                        1000: {items: 4, nav: true, loop: true, margin: 20
                        }
                    }
                })
            })
        </script>
		 <script>
            $(document).ready(function () {
                $('#restaurant-deals-site').owlCarousel({
                    loop: true,
                    margin: 10,
                    responsiveClass: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    responsive: {
                        0: {items: 1, nav: true},
                        600: {items: 2, nav: false},
                        1000: {items: 4, nav: true, loop: true, margin: 20
                        }
                    }
                })
            })
        </script>
		<script>
            $(document).ready(function () {
                $('#spa-deals-site').owlCarousel({
                    loop: true,
                    margin: 10,
                    responsiveClass: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    responsive: {
                        0: {items: 1, nav: true},
                        600: {items: 2, nav: false},
                        1000: {items: 4, nav: true, loop: true, margin: 20
                        }
                    }
                })
            })
        </script>-->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.inner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/homes/index.blade.php ENDPATH**/ ?>