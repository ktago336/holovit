<?php 
$adminLId = Session::get('adminid');
?>
<?php echo e(HTML::script('public/js/facebox.js')); ?>

<?php echo e(HTML::style('public/css/facebox.css')); ?>

<script type="text/javascript">
    $(document).ready(function ($) {
        $('.close_image').hide();
        $('a[rel*=facebox]').facebox({
            closeImage: '<?php echo HTTP_PATH; ?>/public/img/close.png'
        });
    });
</script>
<style>
.my_text{
    font-size: 12px;
    margin-left: 55px;
    margin-top:-20px;
        }
</style>

<div class="admin_loader" id="loaderID"><?php echo e(HTML::image("public/img/website_load.svg", SITE_TITLE)); ?></div>
<?php if(!$allrecords->isEmpty()): ?>
<div class="panel-body marginzero">
    <div class="ersu_message"><?php echo $__env->make('elements.admin.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
    <?php echo e(Form::open(array('method' => 'post', 'id' => 'actionFrom'))); ?>

    <section id="no-more-tables" class="lstng-section">
        <div class="topn">
            <div class="topn_left">Deals List</div>
            <div class="topn_rightd ddpagingshorting" id="pagingLinks" align="right">
                <div class="panel-heading" style="align-items:center;">
                    <?php echo e($allrecords->appends(Input::except('_token'))->render()); ?>

                    
                </div>
            </div>                
        </div>
        <div class="tbl-resp-listing">
            <table class="table table-bordered table-striped table-condensed cf">
                <thead class="cf ddpagingshorting">
                    <tr>
                        <th style="width:5%">#</th>
                         <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('Merchant.busineess_name', 'Merchant'));?></th>
                         <!--<th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('product name', 'Product Name'));?></th>-->
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('name', 'Deal Name'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('price', 'Price'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('discount', 'Discount'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('final price', 'Final Price'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('expire date', 'Expire Date'));?></th>
                        <th class="action_dvv"> Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $allrecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allrecord): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th style="width:5%"><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="<?php echo e($allrecord->id); ?>" /></th>
                        <?php  $merchant = DB::table('users')->where(['id'=> $allrecord->merchant_id,'status'=>1])->first(); ?>
                        <td data-title="Full Name"><?php echo e(isset($allrecord->Merchant->busineess_name)?$allrecord->Merchant->busineess_name:'N/A'); ?></td>
                        <!--<td data-title="Full Name"><?php echo e($allrecord->product_id); ?></td>-->
                        <td data-title="Full Name"><?php echo e($allrecord->deal_name); ?></td>
                        <td data-title="Email Address"><?php echo e(CURR); ?><?php echo e($allrecord->voucher_price); ?></td>
                        <td data-title="Contact Number"><?php echo e($allrecord->discount.'%'); ?></td>
                        <td data-title="Email Address"><?php echo e(CURR); ?><?php echo e($allrecord->final_price); ?></td>
                        <td data-title="Last Updated"><?php echo e($allrecord->expire_date); ?></td>
                        <td data-title="Action">
                            <div id="loderstatus<?php echo e($allrecord->id); ?>" class="right_action_lo"><?php echo e(HTML::image("public/img/loading.gif", SITE_TITLE)); ?></div>
                           <span class="right_acdc" id="status<?php echo e($allrecord->id); ?>">
                                <?php if($allrecord->status == '1'): ?>
                                <a href="<?php echo e(URL::to( 'admin/deals/deactivate/'.$allrecord->slug)); ?>" title="Deactivate" class="deactivate"><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>
                                <?php else: ?>
                                <a href="<?php echo e(URL::to( 'admin/deals/activate/'.$allrecord->slug)); ?>" title="Activate" class="activate"><button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button></a>
                                <?php endif; ?>
                            </span>
                          
                             <?php /*<a href="{{ URL::to( 'admin/deals/edit/'.$allrecord->slug)}}" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>*/?>
                             <a href="<?php echo e(URL::to( 'admin/deals/delete/'.$allrecord->slug)); ?>" title="Delete" class="btn btn-danger btn-xs action-list delete-list" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fa fa-trash-o"></i></a>
                            <a href="#info<?php echo $allrecord->id; ?>" title="View" class="btn btn-primary btn-xs" rel='facebox'><i class="fa fa-eye"></i></a>
                             
                             
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div class="search_frm">
                <button type="button" name="chkRecordId" onclick="checkAll(true);"  class="btn btn-info">Select All</button>
                <button type="button" name="chkRecordId" onclick="checkAll(false);" class="btn btn-info">Unselect All</button>
                <?php global $accountStatus; ?>
                <?php 
            if($adminLId != 1){
                if(isset($checkSubRols[5])){
                if ($adminLId == 1 || in_array(2, $checkSubRols[5]) && in_array(3, $checkSubRols[5])) { global $accountStatus; }
                if ($adminLId == 1 || in_array(2, $checkSubRols[5]) && (!in_array(3, $checkSubRols[5]))) {unset($accountStatus['Delete']); }

                if ($adminLId == 1 || in_array(3, $checkSubRols[5]) && (!in_array(2, $checkSubRols[5]))) {  unset($accountStatus['Activate']); unset($accountStatus['Deactivate']);}
            
            if(!in_array(3, $checkSubRols[5]) && (!in_array(2, $checkSubRols[5]))){
                unset($accountStatus['Activate']);
                unset($accountStatus['Deactivate']);
                unset($accountStatus['Delete']);
            }}
        }
            ?>
                <div class="list_sel"><?php echo e(Form::select('action', $accountStatus,null, ['class' => 'small form-control','placeholder' => 'Action for selected record', 'id' => 'action'])); ?></div>
                <button type="submit" class="small btn btn-success btn-cons btn-info" onclick="return ajaxActionFunction();" id="submit_action">OK</button>
            </div>    
        </div>
    </section>
    <?php echo e(Form::close()); ?>

</div>         
</div> 
<?php else: ?> 
<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
<div class="admin_no_record">No record found.</div>
<?php endif; ?>

<?php if(!$allrecords->isEmpty()): ?>
<?php 
//echo '<pre>'; print_r($allrecords); exit;
?>
<?php $__currentLoopData = $allrecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allrecord): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div id="info<?php echo $allrecord->id; ?>" style="display: none;">
    <div class="nzwh-wrapper">
        <fieldset class="nzwh">
            <legend class="head_pop"><?php echo $allrecord->deal_name; ?></legend>
            <div class="drt">
                <?php  //$products = DB::table('products')->where(['status'=>1,'id'=>$allrecord->product_id])->first(); print_r($products); exit;?>
                <div class="admin_pop"><span>Deal Name: </span>  <label><?php echo $allrecord->deal_name; ?></label></div>
				<div class="admin_pop"><span>Voucher Type: </span>  <label><?php global $vouchers;?> <?php echo $allrecord->voucher_type; ?></label></div>
				<div class="admin_pop"><span>Voucher Price: </span>  <label><?php echo CURR.$allrecord->voucher_price; ?></label></div>
                <div class="admin_pop"><span>Discount: </span>  <label><?php echo $allrecord->discount.'%'; ?></label></div>
				<div class="admin_pop"><span>Final Price: </span>  <label><?php echo CURR.$allrecord->final_price; ?></label></div>
				<div class="admin_pop"><span>Most Popular Time Of the day: </span>  <label><?php global $popular_time;?> <?php echo isset($popular_time[$allrecord->popular_time])?$popular_time[$allrecord->popular_time]:$allrecord->popular_time; ?></label></div>
				<div class="admin_pop"><span>Amenities: </span>  <label><?php 
				if($allrecord->amenitie_id)
				{
				     $amenitie_id_arr = explode(',',$allrecord->amenitie_id);
    				$am_arr = array();
    				foreach($amenitie_id_arr as $val){
    				    if(isset($amenitie[$val])){
    				        $am_arr[] = $amenitie[$val];
    				    }
    					    
    				}
        				if(count($am_arr) > 0){
        				    echo implode(', ',$am_arr);
        				}
    				
				    }
				   
				
				?> </label></div>
				<div class="admin_pop"><span>Deal Start Time: </span>  <label><?php global $time_array;?> <?php echo isset($time_array[$allrecord->deal_start_time])?$time_array[$allrecord->deal_start_time]:'N/A'; ?></label></div>
				<div class="admin_pop"><span>Deal End Time: </span>  <label><?php global $time_array;?> <?php echo isset($time_array[$allrecord->deal_end_time])?$time_array[$allrecord->deal_end_time]:'N/A'; ?></label></div>
                <div class="admin_pop"><span>Description: </span>  <label><?php echo $allrecord->description; ?></label></div>
                <?php if($allrecord->images != ''): ?>
                <?php 
                $image = explode(',',$allrecord->images);
                //print_r($category_id); exit;
                //echo $category_id;
                ?>
                <div class="admin_pop popup_view_images"><span class="popimg">Profile Image</span> <div class="imgsection"><?php $__currentLoopData = $image; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $images): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e(HTML::image(DEAL_FULL_DISPLAY_PATH.$images, SITE_TITLE,['style'=>"max-width: 200px"])); ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div></div>
                <?php endif; ?>
                
                <div class="admin_pop"><span>Expiry Date: </span>  <label><?php echo $allrecord->expire_date; ?></label></div>
            </div>
        </fieldset>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/elements/admin/deals/index.blade.php ENDPATH**/ ?>