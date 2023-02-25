<?php $__env->startSection('content'); ?>
<section class="listing_deal">
  <div class="container">
   
    
  <div class="panel panel-default">
   <div class="row"> 
    <div class="col-md-3">
     <?php echo $__env->make('elements.merchant_left_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    
    <div class="col-md-9">
    <div class="panel-body">
      <div class="tab-content ">
        <div class="tab-pane active" id="1">
        <div class="informetion_top">
        <div class="tatils_0t1">
		My Orders
		<div class="add-list">
          <!--<a href="<?php echo e(URL::to( 'merchant/deals/add')); ?>"><i class="fa fa-plus"></i>Add Deals</a>-->
        </div>
		</div>
        <div class="informetion_bx">
            <div class="informetion_bxes"> 
			<div class="informetion_bxes" id="listID">
			<?php echo $__env->make('elements.merchant.users.myorders', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
			</div>
        </div>
		</div>
    </div>
        </div>
        <div class="tab-pane" id="2">
     hfgghghghghghghghghghghghgh
        </div>
        <div class="tab-pane" id="3">
         ghjghjghjgjghjghj
        </div>
      </div>
    </div>
  </div>
  </div>
</div>
</div>
  </div>
</section>







<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.merchant_inner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/merchant/user/myorders.blade.php ENDPATH**/ ?>