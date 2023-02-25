<?php $__env->startSection('content'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $.validator.addMethod("alphanumeric", function (value, element) {
            return this.optional(element) || /^[\a-zA-Z._ ]+$/i.test(value);
        }, "Only letters and underscore are allowed.");
        $.validator.addMethod("passworreq", function (input) {
            var reg = /[0-9]/; //at least one number
            var reg2 = /[a-z]/; //at least one small character
            var reg3 = /[A-Z]/; //at least one capital character
            //var reg4 = /[\W_]/; //at least one special character
            return reg.test(input) && reg2.test(input) && reg3.test(input);
        }, "Password must be a combination of Numbers, Uppercase & Lowercase Letters.");
        $("#loginform").validate();
    });
 
</script>
<section class="slider details">
            <div class="container">
                <div class="card-main">
                    <div class="row">
                        <div class="col-xs-12 col-md-5 col-sm-12 col-lg-5">
                            <p><?php echo $recordInfo->busineess_name;?></p>

                            <div class="m-address">
                                <div>
                                    <span class="card-main__details font-weight-semibold font-lg"><?php echo isset($recordInfo->City->city_name)?$recordInfo->City->city_name:'';?></span>
                                </div>
                                
                            </div>
                            <div class="phone_no">
                                <div>
                                    <i class="fa fa-phone"></i>
                                    <span class="">Phone no. - <?php echo $recordInfo->contact?$recordInfo->contact:'';?></span>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-7 col-sm-12 col-lg-7">
                            <div class="row">
								<?php
								$image = $recordInfo->profile_image;
								$images = explode(',', $image);
								$imgcnt = 0;
								?>
								<?php if(array_filter($images)): ?>
								<?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<?php $imgcnt = $imgcnt+1; ?>
								<div class="col-md-6">
									<?php echo e(HTML::image(MERCHANT_FULL_DISPLAY_PATH.$image, SITE_TITLE,['style'=>""])); ?>

                                </div>
                                <?php if($imgcnt == 2): ?>
                                <?php break;?>
                                <?php endif; ?>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="container">
                <div class="card-main_1">
                    <tabs>
                    <ul class="nav nav-tabs  tab tab-delight" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active tab__item font-medium tab-delight--9 tab__active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Gift cards</a>
                        </li>
                        <li class="nav-item tab__item font-medium">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">About</a>
                        </li>
                        <li class="nav-item tab__item font-medium">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Photos</a>
                        </li>
                    </ul>
                    </tabs>
                    <div class="row">
                        
                        <div class="tab-content" id="myTabContent">
                              
                              
                                  
                                 
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                  <div class="container">
                                    <div class="row">
                                        <div class="col-lg-8">
                                <div class="card-main__content--lg margin-top-m"> 
                                    <div class="card-list card-list--border card-list--comfortable">
                                       
                                        <?php if($recordInfo->allDeal): ?>
										<?php $__currentLoopData = $recordInfo->allDeal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dealinfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>	
                                        <div class="card-main">
                                            <option-card>
                                                <div>
                                                   
                                                    <div class="margin-bottom-m">
                                                        <div class="row">
                                                            <div class="col-sm-8 col-md-8">
                                                                <h3 class="font-xxxl font-weight-bold txt-primary line-height-sm"><?php echo e($dealinfo->deal_name); ?> (30min)</h3>
                                                                <div class="margin-top-s">
                                                                    <span class="txt-delight-8 font-lg line-height-xs">Free Cancellation</span>
                                                                    
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4 col-md-4 txt-right">
                                                                <p>
                                                                    <span class="txt-strike-through font-sm txt-tertiary">
                                                                    <i class="nb-icon nb-icon_rupee nb-icon--xs"></i><?php echo e(CURR.$dealinfo->voucher_price); ?></span>
                                                                    <b class="font-xxxl font-weight-bold txt-primary margin-left-xs">
                                                                    <i class="nb-icon nb-icon_rupee nb-icon--md" content="USD" itemprop="priceCurrency"></i><?php echo e(CURR.$dealinfo->final_price); ?></b>
                                                                </p>
                                                                <span class="font-md txt-tertiary">Inc. of all taxes</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="margin-bottom-m">
                                                        <div class="row">
                                                            <div class="col-sm-7 col-md-8">
                                                                <div class="op-timings">
                                                                    <p class="op-timings__content">
                                                                        <span class="txt-tertiary font-weight-semibold font-sm">Valid for :</span>
                                                                        <span class="txt-primary font-weight-semibold font-sm"><?php echo e($dealinfo->valid_for); ?> Person</span>
                                                                    </p>
                                                                    <!--<p class="op-timings__content">
                                                                        <span class="txt-tertiary font-weight-semibold font-sm">Valid on :</span>
                                                                        <span class="txt-primary font-weight-semibold font-sm">All Days</span>
                                                                        
                                                                    </p>-->
                                                                    <div class="op-timings__content">
                                                                        <span class="txt-tertiary font-weight-semibold font-sm"> Timings :</span>
                                                                        <span class="txt-primary font-weight-semibold font-sm">
																		<?php 
																		if($dealinfo->deal_start_time){
																			$startdatetime = strtotime(date('Y-m-d').' '.$dealinfo->deal_start_time);
																			if(date('i',$startdatetime) > 0){
																				$start_timing = date('h:i A',$startdatetime);
																			}else{
																				$start_timing = date('h A',$startdatetime);
																			}
																			$enddatetime = strtotime(date('Y-m-d').' '.$dealinfo->deal_end_time);
																			if(date('i',$enddatetime) > 0){
																				$end_timing = date('h:i A',$enddatetime);
																			}else{
																				$end_timing = date('h A',$enddatetime);
																			}
																		}
																		?>
																		<?php echo e($start_timing); ?> - <?php echo e($end_timing); ?></span>
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-7 col-md-8">
															      <button type="button" class="btn btn--xs-small btn--border txt-link font-sm font-weight-semibold txt-capitalize" data-toggle="modal" data-target="#detailModal">Details</button>
                                                        </div>
                                                        <div class="col-sm-5 col-md-4 txt-right">
                                                            <span class="qty-counter">
                                                                <button id="blank-<?php echo e($dealinfo->id); ?>" class="btn--brand qty-counter__btn-activate" onclick="addtobook(<?php echo e($dealinfo->id); ?>,<?php echo e($dealinfo->final_price); ?>)">
                                                                <span class="full-width txt-center txt-white font-bold font-xxl">Add</span>
                                                                <span class="txt-white font-xxxl line-height-xs">+</span>
                                                                </button>
                                                                <button "add-<?php echo e($dealinfo->id); ?>" class="qty-counter__btn-increment" onclick="removetobook(<?php echo e($dealinfo->id); ?>,<?php echo e($dealinfo->final_price); ?>)">–</button>
                                                                <input class="deal_qty" data-id="<?php echo e($dealinfo->id); ?>" id="deal_qty_<?php echo e($dealinfo->id); ?>" name="deal_qty" readonly="" type="text" value="0">
                                                                <button "remove-<?php echo e($dealinfo->id); ?>" class="qty-counter__btn-decrement" onclick="addtobook(<?php echo e($dealinfo->id); ?>,<?php echo e($dealinfo->final_price); ?>)">+</button>
                                                            </span>
                                                            <span style="display:none;" id="deal-name-<?php echo e($dealinfo->id); ?>"><?php echo e(\Illuminate\Support\Str::limit($dealinfo->deal_name,20, $end='...')); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </option-card>
                                        </div>
										<input type="hidden" id="discount-price-<?php echo e($dealinfo->id); ?>" name="discount_price" value="<?php echo e(($dealinfo->voucher_price-$dealinfo->final_price)); ?>">
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										<?php endif; ?>
                                  </div>
                                   </div>
                                   </div>
                                   <div class="col-lg-4">

                            
                                   <div class="card-main__content--lg margin-top-m">
                                    <div class="card-main c5">
                                     <div class="heading">
                                         <h3 class="title text-center">Your Order</h3>
                                     </div>
                                     
									 
									 
									 
									 
									 <div class="row-order-dtl" id="order-list">
										
									 
                                     </div>
									 <div class='no-order-list'>
										 <p class="ad-cart-option"> Please add an option</p>
										 <p class="txt-tertiary saving-cart"> Your order is empty</p>
									 </div>
                                     <div class="row pricecard1">
                                        
                                     <div class="col-md-6">
                                        <p class="">Total</p> 
                                     </div>
                                    
                                     <div class="col-md-6">
                                         <div class="txt-right">
                            <span class=" total_prices" id="total-amount">
                               <i class="fa fa-usd"></i> <?php echo e(CURR); ?>0
                            </span>
                        </div>
                                     </div>

                                     </div>
                                     <div class="margin-top-m margin-bottom-m">
                    <!---->
					<input type="hidden" id="quantity" name="quantity" value="0">
						<input type="hidden" id="total" value="0" name="total">
						<input type="hidden" id="dis_price" value="0" name="dis_price">
                    <button class="btn btn--primary btn-block font-weight-bold loaders" onclick="submitorder();">
                    <div class="spin-loader-wrapper hide">
                        <div class="spin-loader"></div>
                    </div>
                    Buy now
                </button>
            </div>
            <p class="saving-cart" id="saving-cart"> you are saving   <i class="fa fa-usd"></i> 0</p>
                                   </div>
                                   </div>
                                   </div>
                                   </div>
                                    </div>
                                  </div>
                                       
                                    
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                               <div class="container">
                                <div class="tab_details2">
                                   <div class="row">
                                    <div class="col-md-8">
                                      <div class="margin-bottom-xl">
                <h1 class="font-lg txt-tertiary font-weight-bold line-height-xs margin-bottom-l txt-uppercase"><?php echo $recordInfo->busineess_name.(isset($recordInfo->City->city_name)?', '.$recordInfo->City->city_name:'');?></h1>
                <!----><!---->
                    
                    <!----><div>
					<?php if(!empty( $recordInfo->currentDeal)){ ?>
                        <div style="padding: 18px; border-radius: 6px;" class="gradient-delight-1">
                            <!----><h2 class="tag tag--border font-weight-semibold txt-uppercase line-height-default txt-delight-1">DEALS</h2>
                            <div class="row">
                                <div class="col-sm-8 col-md-7 col-lg-9">
                                    <div class="margin-bottom-s">
                                        <h3>
                                            <!----><p class="font-xxxl font-weight-bold txt-primary margin-bottom-xs"><?php echo e($recordInfo->currentDeal->deal_name); ?></p>
                                            <!---->
											<?php if(count($recordInfo->allDeal) > 1): ?>
												<p class="font-md font-weight-semibold line-height-default txt-tertiary">+ <?php echo e((count($recordInfo->allDeal)-1)); ?> more Deal</p>
											<?php endif; ?>
                                        </h3>
                                    </div>
                                    <!----><p class="font-lg line-height-xs">
                                        <span class="margin-right-xs">
                                            <span class="font-lg txt-secondary">Starting From :</span>
                                            <span class="font-lg txt-primary font-weight-bold"><?php echo e(CURR.$recordInfo->currentDeal->final_price); ?></span>
                                        </span>
                                        
                                        
                                    </p>
                                    <!---->
                                </div>
                                <!----><div class="col-sm-4 col-md-5 col-lg-3">
                                    <button class="btn-delight font-xl txt-uppercase margin-bottom-s font-weight-semibold bg-delight-1" onclick="trigger_deal_list();">View</button>
                                </div>
                            </div>
                        </div>
					<?php } ?>
                    </div>
                
            </div>
            <div class="margin-top-xl margin-bottom-l">
                <div class="margin-bottom-l margin-top-m">
                    <!----><h2 class="line-height-default font-weight-semibold margin-bottom-s txt-capitalize font-xxxl">About <?php echo e($recordInfo->busineess_name); ?></h2>
                    <!----><p class="card-main__desc txt-secondary margin-bottom-m font-xl"><?php echo e($recordInfo->about_us); ?></p>
                    <!---->
                </div>
            </div>
           <h2 class="line-height-default font-weight-semibold margin-bottom-s txt-capitalize font-xxxl"> Address</h2>
               <p class="card-main__desc txt-secondary margin-bottom-m font-xl"><?php echo e($recordInfo->address.', '.(isset($recordInfo->City->city_name)?$recordInfo->City->city_name.', ':'').$recordInfo->zipcode); ?></p>
                <h2 class="line-height-default font-weight-semibold margin-bottom-s txt-capitalize font-xxxl"> Phone no.</h2>
               <p class="card-main__desc txt-secondary margin-bottom-m font-xl"><?php echo e($recordInfo->contact); ?></p>
               <h2 class="line-height-default font-weight-semibold margin-bottom-s txt-capitalize font-xxxl"> Average cost</h2>
               <p class="card-main__desc txt-secondary margin-bottom-m font-xl">Cost for two - 2,600</p>
                                   
                                    <h2 class="line-height-default font-weight-semibold margin-bottom-s txt-capitalize font-xxxl">Follow <?php echo e($recordInfo->busineess_name); ?></h2>
               <ul class="social_icon details">
					<?php $is_social_links = 0;?>
					<?php if(trim($recordInfo->facebook_link)): ?>
						<?php $is_social_links = 1;?>
					    <li class="facebook"><a href="<?php echo $recordInfo->facebook_link; ?>" target="_blank"><i class="social-media fa fa-facebook" aria-hidden="true"></i></a></li>
					<?php endif; ?>
					<?php if(trim($recordInfo->instagram_link)): ?>
						<?php $is_social_links = 1;?>
                        <li class="instagram"><a href="<?php echo $recordInfo->instagram_link; ?>" target="_blank"><i class="social-media fa fa-instagram" aria-hidden="true"></i></a></li>
					<?php endif; ?>
					<?php if(trim($recordInfo->linkedin_link)): ?>
						<?php $is_social_links = 1;?>
                        <li class="linkedin"><a href="<?php echo $recordInfo->linkedin_link; ?>" target="_blank"><i class="social-media fa fa-linkedin" aria-hidden="true"></i></a></li>
					<?php endif; ?>
					<?php if(trim($recordInfo->twitter_link)): ?>
						<?php $is_social_links = 1;?>
                        <li class="twitter"><a href="<?php echo $recordInfo->twitter_link; ?>" target="_blank"><i class="social-media fa fa-twitter" aria-hidden="true"></i></a></li>
					<?php endif; ?>
					<?php if(trim($recordInfo->google_link)): ?>
						<?php $is_social_links = 1;?>
                        <li class="google"><a href="<?php echo $recordInfo->google_link; ?>" target="_blank"><i class="social-media fa fa-google" aria-hidden="true"></i></a></li>
					<?php endif; ?>
					<?php if(trim($recordInfo->youtube_link)): ?>
						<?php $is_social_links = 1;?>
                        <li class="youtube"><a href="<?php echo $recordInfo->youtube_link; ?>" target="_blank"><i class="social-media fa fa-youtube" aria-hidden="true"></i></a></li>
					<?php endif; ?>
					<?php if($is_social_links == 0): ?>
						<p class="card-main__desc txt-secondary margin-bottom-m font-xl">No social links found</p>
					<?php endif; ?>
                   
                    
                     
                      
                       
                        
               </ul> 
                <h2 class="line-height-default font-weight-semibold margin-bottom-s txt-capitalize font-xxxl"><?php echo e($recordInfo->busineess_name); ?> Timings</h2>
                
				
				<?php global $week_days;
                    $working_days_arr = explode(',',$recordInfo->working_days);
                    $start_time_arr = explode(',',$recordInfo->start_time);
                    $end_time_arr = explode(',',$recordInfo->end_time);
                ?>
				<div class="row day_date">
                    <div class="col-md-6">
                        <div class="day">
						<?php $__currentLoopData = $week_days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wd_key=>$wd_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><?php echo e($wd_val); ?></p>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                     <div class="col-md-6">
                         <div class="date">
						 <?php $__currentLoopData = $week_days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wd_key=>$wd_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php if(in_array($wd_key, $working_days_arr)): ?>
                            <p><?php echo e(date("h:i A",strtotime($start_time_arr[array_search ($wd_key,$working_days_arr)]))); ?> - <?php echo e(date("h:i A",strtotime($end_time_arr[array_search ($wd_key,$working_days_arr)]))); ?></p>
							<?php else: ?>
                            <p>Closed</p>
							<?php endif; ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                         </div>
                     </div>
                </div>
                                    </div> 
                                      <div class="col-md-4">
                                        
                                    </div>
                                     </div>
                                   </div>
                               </div>
                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                               <div class="container">
                                <div class="images_details">
									<?php $i = 0; ?>
									<?php if(array_filter($images)): ?>
										<?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<?php $i = $i+1; $is_open = 0;?>
											<?php if($i%3 == 1): ?>
												<?php $is_open = 1;?>
												<div class="row">
											<?php endif; ?>	
										<div class="col-md-4">
											<?php echo e(HTML::image(MERCHANT_SMALL_DISPLAY_PATH.$image, SITE_TITLE,['style'=>""])); ?>

										</div>
											<?php if($i%3 == 0): ?>
												<?php $is_open = 0;?>
												</div>
											<?php endif; ?>	
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									<?php endif; ?>
									<?php if(isset($is_open) && $is_open == 1): ?>
										</div>
									<?php endif; ?>	
                                   </div>
                               </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
</section>

<div class="modal modal-detals fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="pro-details">
       <h3 class="">Free Cancellation</h3>
       <p class="">One click cancellation available within 90 days of purchase</p>
       </div>
	   
	   <div class="pro-details">
       <h3 class="">Applicable on</h3>
       <p class="">Valid For: Dine-in</p>
	   <p class="">Not Valid for: Takeaway, Home Delivery</p>
       </div>
	   
	   <div class="pro-details">
       <h3 class="">Use this within</h3>
       <p class="">90 days of purchase</p>
       </div>
	   
	   <div class="pro-details">
       <h3 class="">How to use offer</h3>
       <ul class="section-details">
       <li>For a seamless experience, at the time of arrival, please inform the outlet staff that you will be using nearbuy Cash Voucher.</li>
	   <li>Open your Cash Voucher Code on the nearbuy app and show it to the staff.</li>
	   <li>The Staff will redeem the voucher and deduct the Cash Voucher Value from the Bill Amount.</li>
       
	   </ul>
       </div>
	   
	   <div class="pro-details">
       <h3 class="">Things to remember</h3>
       <ul class="section-details">
       <li>Valid on the Total Bill amount inclusive of all taxes and service charges</li>
	   <li>Merchant has the right to retain the service charge on the Bill amount</li>
	   <li>Cannot be used or clubbed with other offers and discounts.</li> 
	   <li>Not valid on Days of Special Events, please refer to Black out Dates for this offer</li> 
	   <li>Only 1 Cash voucher can be redeemed per visit</li> 
	   <li>The entire amount of Cash Voucher must be used in a single visit</li> 
	   <li>If the order value exceeds the value of the cash voucher, the balance must be paid through payment options available with the merchant.</li> 
	   <li>No credit note / refund for the unused/expired amount of the Cash Voucher shall be given</li> 
	
       
	   </ul>
       </div>
	
    </div>
  </div>
</div>
</div>        
        

<script type="text/javascript">
    $(document).ready(function () {
$('#search_location').keyup(function(){ 
		var thisHref = $(location).attr('href'); 
		var key = $(this).val();
		//alert(txt);
		if(key !=''){
			 
			$.ajaxSetup({
        				headers: {
        					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        				}
        			});
			$.ajax({
				url:thisHref,
				type: "POST",
                data: {search:key},
				dataType:"text",
                //beforeSend: function () { $("#searchloader").show();},
                //complete: function () {$("#searchloader").hide();},
                success: function (data) { 
					//alert(data);
               $('.page-list-content').html(data);
			}
		});
		}else{
			
	}
	});		
         $(".deltimesub").on('change', function (event) { 
             event.preventDefault();
            updateresult ();
        });
        
          $(document).on('click', '.ajaxpagee a', function () {
            var npage = $(this).html();
            if ($(this).html() == '»') {
                npage = $('.ajaxpagee .active').html() * 1 + 1;
            } else if ($(this).html() == '«') {
                npage = $('.ajaxpagee .active').html() * 1 - 1;
            }
            $('#pageidd').val(npage);
            updateresult ();
            return false;
        });
         $("img.lazy").lazyload();
    <?php if(isset($isajax)): ?>
    $('html, body').animate({
        scrollTop: $('#backtotop').offset().top - 1
    }, 'slow');
    <?php endif; ?>
    });
    
      function updateresult(){ 
	  //alert('hello');
        var thisHref = $(location).attr('href'); 
          $.ajaxSetup({
        				headers: {
        					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        				}
        			});
        $.ajax({
            url: thisHref,
            type: "POST",
            data: $('#searchform').serialize(),
            beforeSend: function () { $("#searchloader").show();},
            complete: function () {$("#searchloader").hide();},
            success: function (result) {
               $('.page-list-content').html(result);
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>
<script type="text/javascript">
	function addtobook(deal_id,amt){
		//increament category wise quantity
		//qty-counter__btn-activate
		quantvar="#deal_qty_"+deal_id;
		quantity=parseFloat($(quantvar).val());
		$(quantvar).val(quantity+1);
		// console.log(deal_id.charAt(0));
		/////$("#"+deal_id.charAt(0)+"quant").html(quantity+1);
		$("#deal_qty_"+deal_id).html(quantity+1);
		
		
		//increament total quantity
		total_deal_quantity=quantity+1;
		total_deal_price=total_deal_quantity*amt;

		//increament total quantity
		totalquantity=parseFloat($('#quantity').val())+1;
		$('#quantity').val(totalquantity);
		
		var deal_discount_price = $('#discount-price-'+deal_id).val();
		var total_discount_price =parseFloat($('#dis_price').val())+parseFloat(deal_discount_price);
		$('#dis_price').val(total_discount_price);
		$("#saving-cart").html(' you are saving   <i class="fa fa-usd"></i> '+total_discount_price);
		//increament total amount
		totalamt=parseFloat($('#total').val())+amt;
		$('#total').val(totalamt);
		
		if(quantity == 0){
			$(".no-order-list").hide();
			//make order div and append
			var deal_name = $("#deal-name-"+deal_id).text();
			var generate_order = '<div class="row pricecard borderp" id="row-order-dtl-'+deal_id+'">'+
                                     '<div class="col-md-4">'+
                                        '<p class="txt-primary font-sm ellipsis ellipsis--lg">'+deal_name+'</p>'+ 
                                     '</div>'+
                                      '<div class="col-md-4">'+
                                         '<div class="txt-right">'+
                            '<span class="margin-right-m txt-primary font-regular" id="row-order-dtl-qty-'+deal_id+'">x 1</span>'+
                        '</div>'+
                                     '</div>'+
                                     '<div class="col-md-4">'+
                                         '<div class="txt-right">'+
                            '<span class="txt-secondary font-weight-bold" id="row-order-dtl-price-'+deal_id+'">'+
                                 '<i class="fa fa-usd"></i> '+amt+
                            '</span>'+
                        '</div>'+
                                     '</div>'+
									 '</div>';
			
				//alert(generate_order);
			$("#order-list").append(generate_order);
			
		}else{
			$("#row-order-dtl-qty-"+deal_id).html('x '+total_deal_quantity);
			$("#row-order-dtl-price-"+deal_id).html('<i class="fa fa-usd"></i>'+total_deal_price);
		}
		
		
		
		
		

		//change label data for ticket and amount
		//$("#total-ticket").html(totalquantity);
		$("#total-amount").html('<i class="fa fa-usd"></i>'+totalamt);
		$("#blank-"+deal_id).hide();
		$("#add-"+deal_id).show();
		$("#remove-"+deal_id).show();
		$("#deal_qty_"+deal_id).show();
		
		//$("#"+deal_id.charAt(0)+"quant").show();
		showproceed();
	}

	function removetobook(deal_id,amt){
		//increament category wise quantity
		quantvar="#deal_qty_"+deal_id;
		quantity=parseFloat($(quantvar).val());
		if(quantity>0){
			$(quantvar).val(quantity-1);
			if(quantity-1<=0){
				
				//$("#"+deal_id+"-remove").hide();
				//$("#"+deal_id.charAt(0)+"quant").hide();
				
				$("#blank-"+deal_id).show();
				$("#add-"+deal_id).hide();
				$("#remove-"+deal_id).hide();
				$("#deal_qty_"+deal_id).hide();
				
				$("#row-order-dtl-"+deal_id).remove();
			}else{
				
				total_deal_quantity=quantity-1;
				total_deal_price=total_deal_quantity*amt;

				
				$("#row-order-dtl-qty-"+deal_id).html('x '+total_deal_quantity);
				$("#row-order-dtl-price-"+deal_id).html('<i class="fa fa-usd"></i>'+total_deal_price);				
			}
			
			var deal_discount_price = $('#discount-price-'+deal_id).val();
			var total_discount_price =parseFloat($('#dis_price').val())-parseFloat(deal_discount_price);
			$('#dis_price').val(total_discount_price);
			$("#saving-cart").html(' you are saving   <i class="fa fa-usd"></i> '+total_discount_price);
			
			/////$("#"+deal_id.charAt(0)+"quant").html(quantity-1);
			$("#deal_qty_"+deal_id).html(quantity-1);
			//increament total quantity
			totalquantity=parseFloat($('#quantity').val())-1;
			$('#quantity').val(totalquantity);
			if(totalquantity <= 0){
				$(".no-order-list").show();
			}	
			//increament total amount
			totalamt=parseFloat($('#total').val())-amt;
			$('#total').val(totalamt);

			//change label data for ticket and amount
			$("#total-amount").html('<i class="fa fa-usd"></i>'+totalamt);

		}
		showproceed();
	}

	function showproceed(){
		if(parseFloat($('#quantity').val())>=1){
			$('#proceed-btn').show();
		}else{
			$('#proceed-btn').hide();
		}
	}
	function trigger_deal_list(){
		//alert();
		$('#home-tab').trigger('click');
	}
	
	function submitorder(){
		//alert();
		var order_url_param = '';
		$('.deal_qty').each(function(){
			if($(this).val() >0){
				//alert($(this).attr('data-id'));
				if(order_url_param != ""){
					order_url_param = order_url_param+"-"+$(this).attr('data-id')+'_'+$(this).val();
				}else{
					order_url_param = $(this).attr('data-id')+'_'+$(this).val();
				}
				if(order_url_param != ''){
					location.href = "<?php echo HTTP_PATH.'/deals/ordersummary/'.$recordInfo->slug.'/';?>"+order_url_param;
				}
			}
            //alert($(this).val()); //alert each input fields value
        });
		if(order_url_param == ''){
			alert('Please select atleast one deal!');
		}
		
		//alert(order_url_param);
		//orders = 
	}
	
	// window.onbeforeunload  = function (){
 //  		return "hello";
 // 	}
 	//$(window).unload(function () {
	//    $.ajax({
	//      type: 'GET',
	//      async: false,
	//      url: 'SomeUrl.com?id=123'
	//    });
	// });
</script>

<?php echo $__env->make('layouts.inner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/deals/detail.blade.php ENDPATH**/ ?>