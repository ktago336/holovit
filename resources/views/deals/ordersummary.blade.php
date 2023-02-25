@extends('layouts.inner')
@section('content')
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
 <section class="payment">
           <div class="container">
               <div class="row">
                   <div class="col-md-4">
				    {{ Form::open(array('url'=>'deals/generateorder/'.$recordInfo->slug.'/'.$offerinfo,'method' => 'post', 'id' => 'submitorder')) }}

                   <div class="block block--secondary block-collapsible">
    <div class="block__inner">
        <h5 class="padding-bottom-m card-head">Order summary</h5>
        <div class="venue">
            <div class="margin-bottom-m">
                <div class="row">
                <div class="col-sm-4 col-md-4">
                    <div class="venue__image">
					<?php
					$image = $recordInfo->profile_image;
					$images = explode(',', $image);
					$imgcnt = 0;
					?>
					@if(array_filter($images))
					@foreach($images as $image)
					<?php $imgcnt = $imgcnt+1; ?>
						{{HTML::image(MERCHANT_SMALL_DISPLAY_PATH.$image, SITE_TITLE,['style'=>"",'class'=>"img-responsive",'alt'=>"",'width'=>"55px",'data-lzled'=>"true"])}}
					@if($imgcnt == 1)
					<?php break;?>
					@endif
					@endforeach
					@endif

                    </div>
                </div>
                <div class="col-sm-8 col-md-8">
                    <div class="venue__name">
                    <!----><p class="font-weight-semibold line-height-xs margin-bottom-xs txt-primary">{{$recordInfo->busineess_name}}</p>
                    <!----><p class="font-weight-semibold line-height-xs margin-bottom-xs txt-primary">{{$recordInfo->address.', '.$recordInfo->City->name}}</p>
                    </div>
                </div>
                </div>
            </div>
            <!---->
			
            <div class="venue__tickets">
                <!---->
				<?php $subtotal = 0;?>
				@if($recordInfo->allDeal)
				@foreach($recordInfo->allDeal as $dealinfo)	
				@if(array_key_exists($dealinfo->id, $orders))
				<?php $subtotal = $subtotal+ $dealinfo->final_price*$orders[$dealinfo->id];?>	
				<div>
                    <hr class="thick-border--width-sm">
                    <p class="line-height-xs margin-bottom-xs">{{$dealinfo->deal_name}}</p>
                    <div class="row">
                    <div class="col-sm-5 col-md-5">
                        <p>Qty. {{$orders[$dealinfo->id]}}</p>
                    </div>
                    <div class="col-sm-7 col-md-7">
                        <div class="flt-right">
						
                        <!----><p class="txt-primary font-xxl">
						<span class="txt-strike-through font-sm txt-tertiary">
                       <i class="nb-icon nb-icon_rupee nb-icon--xs"></i>{{CURR.$dealinfo->voucher_price*$orders[$dealinfo->id]}}</span>
                            <!---->
                             {{(CURR.$dealinfo->final_price*$orders[$dealinfo->id])}}</p>
                        </div>
                    </div>
                    </div>
                </div>
				@endif
				@endforeach
				@endif
            </div>
        </div>

     
    </div>
  </div>
  <div class="block block--secondary">
    <div class="block__inner">
        <div class="row">
            <div class="col-sm-6 col-md-6">
                <p class="txt-tertiary">Subtotal
                    <b> (Qty. {{array_sum($orders)}})</b>
                </p>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="txt-right">
                    <p class="txt-primary font-xxl font-weight-semibold">  {{CURR.$subtotal}}</p>
                </div>
            </div>
        </div>
		<div class="row">
            <div class="col-sm-6 col-md-6">
                <p class="txt-tertiary">Convenience Fees</p>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="txt-right">
                    <p class="txt-primary font-xxl font-weight-semibold">  {{CURR.$recordInfo->convenience_fees}}</p>
                </div>
            </div>
        </div>
        <hr class="thick-border thick-border--width-sm">

        <!---->
        <!---->

        <!----<div class="">
            <div>
                <div class="margin-bottom-s show">
                    <a class="txt-brand-primary txt-decoration-underline  font-md">View &amp; Apply promo code</a>
                </div>
               
             
               
            </div>
            
        </div>

        <hr class="thick-border thick-border--width-sm">--->

        <div class="row">
            <div class="col-sm-4 col-md-4">
                <p class="h5 txt-primary font-weight-bold">Total</p>
            </div>
            <div class="col-sm-8 col-md-8">
                <div class="txt-right">
                    <p class="h5 txt-primary margin-bottom-xs font-weight-bold">
                    {{CURR.($subtotal+$recordInfo->convenience_fees)}}
                    </p>
                    <!---->
                </div>
            </div>
        </div>
        
      

    </div>
  </div>
					<input type="hidden" name="sub_total" value="{{$subtotal}}" />
					<input type="hidden" name="convenience_fees" value="{{$recordInfo->convenience_fees}}" />
					<input type="hidden" name="total_price" value="{{($subtotal+$recordInfo->convenience_fees)}}" />
					<input type="hidden" name="payment_type" value="" id="payment_type" />
					{{ Form::close()}}

                   </div>
                   <div class="col-md-8">
              
              <h5 class="card-heading">Payment options</h5>

               <ul class="nav nav-pills mb-3 payment-proceed__navigation list-block" id="pills-tab" role="tablist">
  <li class="">
    <a class="active" id="pills-wallet-tab" data-toggle="pill" href="#pills-wallet" role="tab" aria-controls="pills-wallet" aria-selected="true"> Wallet <span class="sol"><i class="fa fa-angle-right"></i></span></a> 
  </li>
  <li class="">
    <a class="" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="false"> Paypal <span class="sol"><i class="fa fa-angle-right"></i></span></a> 
  </li>
  <!--<li class="">
    <a class="" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">   Credit Cards <span class="sol"><i class="fa fa-angle-right"></i></span> </a>
  </li>
  <li class="">
    <a class="" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">   Debit Cards <span class="sol"><i class="fa fa-angle-right"></i></span></a>
  </li>-->
 
</ul>
 <div class="payment-proceed__content-wrapper">
   <div class="payment-proceed__content"> 
<div class="tab-content" id="pills-tabContent">
<div class="tab-pane fade show active" id="pills-wallet" role="tabpanel" aria-labelledby="pills-wallet-tab">
<div class="form-group">
     <label class="margin-bottom-s">Wallet Balance : {{CURR.$userInfo->wallet_balance}}</label>
 
    
    </div>
    <div class="form-group">
      <label class="nb-checkbox full-width bornb">
        <input formcontrolname="saveCardReference" type="checkbox" class="ng-untouched ng-pristine ng-valid" checked="checked" disabled="disabled">
        <div class="nb-checkbox__bg">
          <div class="nb-checkbox__icon"></div>
        </div>
        <span class="in">
         <img class="img-responsive" height="28" src="<?php echo HTTP_PATH;?>/public/img/front/logo.png" width="90" alt="Wallet" data-lzled="true">
     </span>
      </label>
    </div>
	
    <div class="form-group">
      <button class="btn btn--primary" onclick="paynow(2);" <?php if($userInfo->wallet_balance < ($subtotal+$recordInfo->convenience_fees)) echo "disabled";?>>Pay Now</button>
    </div>
</div>
  <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
<!--<div class="form-group">
     <label class="margin-bottom-s">Select a wallet</label>
 
    
    </div>-->
    <div class="form-group">
      <label class="nb-checkbox full-width bornb">
        <input formcontrolname="saveCardReference" type="checkbox" class="ng-untouched ng-pristine ng-valid" checked="checked" disabled="disabled">
        <div class="nb-checkbox__bg">
          <div class="nb-checkbox__icon"></div>
        </div>
        <span class="in">
         <img class="img-responsive" height="28" src="<?php echo HTTP_PATH;?>/public/img/paytm-wallet.svg" width="90" alt="Paytm Wallet" data-lzled="true">
     </span>
      </label>
    </div>
    <div class="form-group">
      <button class="btn btn--primary" onclick="paynow(1);">Pay Now</button>
    </div>
</div>
<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
    <div class="form-group">
     <label class="margin-bottom-s">Credit card number</label>
     <input autocomplete="off" class="form-control ng-pristine ng-valid ng-touched credit-card-input" formcontrolname="number" placeholder="Enter card number" type="text">
    
    </div>
    <div class="form-group">
      <label class="margin-bottom-s">Name on card</label>
      <input class="form-control ng-pristine ng-valid ng-touched" formcontrolname="name" placeholder="Enter your name" type="text">
     
    </div>
<div class="form-group">
      <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8">
          <label class="margin-bottom-s">Expiry date (MM / YY)</label>
          <div class="row">
            <div class="col-lg-6 col-md-7 col-sm-7">
              <input name="fake1" style="display:none" type="text">
              <input class="form-control ng-untouched ng-pristine ng-valid" formcontrolname="expiry" placeholder="MM / YY" type="text">
              <!---->
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm4">
          <label class="margin-bottom-s">CVV</label>
          <input name="fake2" style="display:none" type="password">
          <input class="card-cvv-input form-control ng-untouched ng-pristine ng-valid" formcontrolname="cvv" type="password">
          <!---->
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="nb-checkbox full-width">
        <input formcontrolname="saveCardReference" type="checkbox" class="ng-untouched ng-pristine ng-valid">
        <div class="nb-checkbox__bg">
          <div class="nb-checkbox__icon"></div>
        </div>
        <span class="txt-primary padding-left-xs">Save this card for faster checkout</span>
      </label>
    </div>
    <div class="form-group">
      <button class="btn btn--primary">Pay Now</button>
    </div>
</div>
  <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
          <div class="form-group">
     <label class="margin-bottom-s">Debit card number</label>
     <input autocomplete="off" class="form-control ng-pristine ng-valid ng-touched debit-card-input" formcontrolname="number" placeholder="Enter card number" type="text">
    
    </div>
    <div class="form-group">
      <label class="margin-bottom-s">Name on card</label>
      <input class="form-control ng-pristine ng-valid ng-touched" formcontrolname="name" placeholder="Enter your name" type="text">
     
    </div>
<div class="form-group">
      <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-8">
          <label class="margin-bottom-s">Expiry date (MM / YY)</label>
          <div class="row">
            <div class="col-lg-6 col-md-7 col-sm-7">
              <input name="fake1" style="display:none" type="text">
              <input class="form-control ng-untouched ng-pristine ng-valid" formcontrolname="expiry" placeholder="MM / YY" type="text">
              <!---->
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm4">
          <label class="margin-bottom-s">CVV</label>
          <input name="fake2" style="display:none" type="password">
          <input class="card-cvv-input form-control ng-untouched ng-pristine ng-valid" formcontrolname="cvv" type="password">
          <!---->
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="nb-checkbox full-width">
        <input formcontrolname="saveCardReference" type="checkbox" class="ng-untouched ng-pristine ng-valid">
        <div class="nb-checkbox__bg">
          <div class="nb-checkbox__icon"></div>
        </div>
        <span class="txt-primary padding-left-xs">Save this card for faster checkout</span>
      </label>
    </div>
    <div class="form-group">
      <button class="btn btn--primary">Pay Now</button>
    </div>
  </div>

</div>
</div>
</div>


             
            </div>

                      
               </div>
               </div>
               </section>   
          
        


@endsection
<script type="text/javascript">
	function paynow(paymenttype){
		
		if(paymenttype == 1 || paymenttype == 2){
			//alert(paymenttype);
			$("#payment_type").val(paymenttype);
			$('#submitorder').trigger('submit');
			//$("#submitorder").submit();
		}
		
	}

	function trigger_deal_list(){
		//alert();
		$('#home-tab').trigger('click');
	}
	
</script>
