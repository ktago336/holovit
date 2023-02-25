<div class="panel-heading">
    <div class="panel-title">
        <ul class="nav nav-tabs">
            <li class="<?php echo isset($myaccountAct) ? $myaccountAct : '' ?>">
                <a href="<?php echo e(URL::to( 'users/myaccount')); ?>"><i class="fa fa-user"></i> My Profile</a>
            </li>
            <li class="<?php echo isset($myorders) ? $myorders : '' ?>">
                <a href="<?php echo e(URL::to( 'users/myorders')); ?>"><i class="fa fa-shopping-bag"></i> My Orders</a>
            </li>
			<li class="<?php echo isset($mywallet) ? $mywallet: '' ?>">
                <a href="<?php echo e(URL::to( 'users/mywallet')); ?>"><i class="fa fa-google-wallet"></i> My Wallet</a>
            </li>
			<li class="<?php echo isset($mypayments) ? $mypayments : '' ?>">
                <a href="<?php echo e(URL::to( 'users/mypayments')); ?>"><i class="fa fa-history"></i> Payment History</a>
            </li>
			<li class="">
                <a href="<?php echo e(URL::to( 'users/logout')); ?>"><i class="fa fa-sign-out"></i>  Logout</a>
            </li>
        </ul>
    </div>
</div>
<?php /**PATH /home/lscouponslogicsp/public_html/resources/views/elements/left_menu.blade.php ENDPATH**/ ?>