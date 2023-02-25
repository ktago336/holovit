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
            <div class="topn_left">Orders List</div>
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
                        <!--<th style="width:5%">#</th>-->
                         
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('order_number', 'Order Number'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('User.first_name', 'Customer Name'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('Merchant.busineess_name', 'Merchant'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('total_amount', 'Total Paid Amount'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('is_voucher_redeemed', 'Voucher Status'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('created_at', 'Order Date'));?></th>
                        <th class="action_dvv"> Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $allrecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allrecord): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <!--<th style="width:5%"><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="<?php echo e($allrecord->id); ?>" /></th>-->
                        <td data-title="Merchant"><?php echo e('#'.$allrecord->order_number); ?></td>
                        <td data-title="Customer Name"><?php echo e($allrecord->User->first_name.' '.$allrecord->User->last_name); ?></td>
                        <td data-title="Merchant"><?php echo e(isset($allrecord->Merchant->busineess_name)?$allrecord->Merchant->busineess_name:'N/A'); ?></td>
                        <td data-title="Total Paid Amount"><?php echo e(CURR); ?><?php echo e($allrecord->total_price); ?></td>
                        <td data-title="Voucher Status"><?php echo e($allrecord->is_voucher_redeemed?"Redeemed":"Pending"); ?></td>
                        <td data-title="Order Date"><?php echo e($allrecord->created_at); ?></td>
                        <td data-title="Action">
                            <div id="loderstatus<?php echo e($allrecord->id); ?>" class="right_action_lo"><?php echo e(HTML::image("public/img/loading.gif", SITE_TITLE)); ?></div>
                            <a href="<?php echo e(URL::to( 'admin/orders/'.$allrecord->slug)); ?>" title="View Detail" class="btn btn-danger btn-xs"><i class="fa fa-eye"></i></a>
							<!--<a href="#info<?php echo $allrecord->id; ?>" title="View" class="btn btn-primary btn-xs" rel='facebox'><i class="fa fa-eye"></i></a>-->
                             
                             
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php /* ?><div class="search_frm">
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
                <div class="list_sel">{{Form::select('action', $accountStatus,null, ['class' => 'small form-control','placeholder' => 'Action for selected record', 'id' => 'action'])}}</div>
                <button type="submit" class="small btn btn-success btn-cons btn-info" onclick="return ajaxActionFunction();" id="submit_action">OK</button>
            </div>   <?php */?> 
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
<?php /* ?>@foreach($allrecords as $allrecord)
<div id="info{!! $allrecord->id !!}" style="display: none;">
    <div class="nzwh-wrapper">
        <fieldset class="nzwh">
            <legend class="head_pop">{!! '#'.$allrecord->order_number!!}</legend>
            <div class="drt">
                <div class="admin_pop"><span>Product Name: </span>  <label>{!! $allrecord->name !!}</label></div>
                <div class="admin_pop"><span>Price: </span>  <label>{!! $allrecord->final_price !!}</label></div>
                <div class="admin_pop"><span>Description: </span>  <label>{!! $allrecord->description !!}</label></div>
                @if($allrecord->images != '')
                <?php 
                //$image = explode(',',$allrecord->images);
                //print_r($category_id); exit;
                //echo $category_id;
                ?>
                <div class="admin_pop popup_view_images"><span class="popimg">Profile Image</span> <div class="imgsection">@foreach($image as $images){{HTML::image(PRODUCT_SMALL_DISPLAY_PATH.$images, SITE_TITLE,['style'=>"max-width: 200px"])}}@endforeach</div></div>
                @endif
                <div class="admin_pop"><span>Category: </span>  <label>{!! $allrecord->category_id !!}</label></div>
                <div class="admin_pop"><span>Sub Category: </span>  <label>{!! $allrecord->category_id !!}</label></div>
                <div class="admin_pop"><span>Sub Sub Category: </span>  <label>{!! $allrecord->subsubcategory_id !!}</label></div>
                <div class="admin_pop"><span>Brand: </span>  <label>{!! $allrecord->Brand->brand_name !!}</label></div>
                <div class="admin_pop"><span>Short Description: </span>  <label>{!! $allrecord->short_description !!}</label></div>
                <div class="admin_pop"><span>More Description: </span>  <label>{!! $allrecord->more_description !!}</label></div>
                <div class="admin_pop"><span>Discount: </span>  <label>{!! $allrecord->discount !!}</label></div>
                <div class="admin_pop"><span>Address: </span>  <label>{!! $allrecord->address !!}</label></div>
                <div class="admin_pop"><span>Total Views: </span>  <label>{!! $allrecord->total_views !!}</label></div>
                <div class="admin_pop"><span>Expiry Date: </span>  <label>{!! $allrecord->expire_date !!}</label></div>
            </div>
        </fieldset>
    </div>
</div>
@endforeach<?php */ ?>
<?php endif; ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/elements/admin/orders/index.blade.php ENDPATH**/ ?>