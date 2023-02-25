<div class="panel-heading">
    <div class="panel-title">
        <ul class="nav nav-tabs">
            <li class="<?php echo isset($myaccountAct) ? $myaccountAct : '' ?>">
                <a href="<?php echo e(URL::to( 'merchant/user/myaccount')); ?>"><i class="fa fa-user"></i> My Profile</a>
            </li>
			<li class="">
                <a href="<?php echo e(URL::to( 'merchant/redeem-voucher')); ?>"><i class="fa fa-gift"></i> Redeem Voucher</a>
            </li>
		
            <li class="<?php echo isset($mydeals) ? $mydeals : '' ?>">
                <a href="<?php echo e(URL::to( 'merchant/deals')); ?>"><i class="fa fa-handshake-o"></i> My Deals</a>
            </li>
            <!--<li class="<?php //echo isset($myproducts) ? $myproducts : '' ?>">
                <a href="<?php echo e(URL::to( 'products/listing')); ?>"><i class="fa fa-cart-plus"></i> My Products</a>
            </li>-->
            <li class="<?php echo isset($myorders) ? $myorders : '' ?>">
                <a href="<?php echo e(URL::to( 'merchant/myorders')); ?>"><i class="fa fa-shopping-bag"></i> My Orders</a>
            </li>
			<li class="<?php echo isset($mywallet) ? $mywallet : '' ?>">
                <a href="<?php echo e(URL::to( 'merchant/mywallet')); ?>"><i class="fa fa-google-wallet"></i> My Wallet</a>
            </li>
            <!--<li class="<?php echo isset($mypayments) ? $mypayments : '' ?>">
                <a href="<?php echo e(URL::to( 'merchant/mypayments')); ?>"><i class="fa fa-history"></i>  Payment History</a>
            </li>-->
			<li class="">
                <a href="<?php echo e(URL::to( 'merchant/logout')); ?>"><i class="fa fa-sign-out"></i>  Logout</a>
            </li>
        </ul>
    </div>
</div>
<?php /**PATH /home/holovitr/domains/holovit.ru/public_html/resources/views/elements/merchant_left_menu.blade.php ENDPATH**/ ?>