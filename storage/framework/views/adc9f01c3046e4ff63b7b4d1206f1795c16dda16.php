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

<div class="admin_loader" id="loaderID"><?php echo e(HTML::image("public/img/website_load.svg", SITE_TITLE)); ?></div>
<?php if(!$allrecords->isEmpty()): ?>
<div class="panel-body marginzero">
    <div class="ersu_message"><?php echo $__env->make('elements.admin.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
    <?php echo e(Form::open(array('method' => 'post', 'id' => 'actionFrom'))); ?>

    <section id="no-more-tables" class="lstng-section">
        <div class="topn">
            <div class="topn_left">Merchants List</div>
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
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('busineess_name', 'Business Name'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('email_address', 'Email Address'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('contact', 'Contact Number'));?></th>
						<th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('wallet_balance', 'Wallet Balance'));?></th>
						<th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('total_earned_amount', 'Total Earned'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('City.name', 'City'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('zipcode', 'Zip code'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('created_at', 'Date'));?></th>
                        <th class="action_dvv"> Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $allrecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allrecord): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th style="width:5%"><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="<?php echo e($allrecord->id); ?>" /></th>
                        <td data-title="Business Name"> <?php echo e($allrecord->busineess_name); ?></td>
                        <td data-title="Email Address"><?php echo e($allrecord->email_address); ?></td>
                        <td data-title="Contact Number"><?php echo e($allrecord->contact?$allrecord->contact:"N/A"); ?></td>
						<td data-title="Wallet Balance"><?php echo e(CURR.$allrecord->wallet_balance); ?></td>
						<td data-title="Total Earned"><?php echo e(CURR.$allrecord->total_earned_amount); ?></td>                        
						<td data-title="City"><?php echo e(isset($allrecord->City->name)?$allrecord->City->name:"N/A"); ?></td>
                        <td data-title="Zip code"><?php echo e($allrecord->zipcode?$allrecord->zipcode:"N/A"); ?></td>
                        <td data-title="Date"><?php echo e($allrecord->created_at->format('M d, Y')); ?></td>
                        <td data-title="Action">
                            <div id="loderstatus<?php echo e($allrecord->id); ?>" class="right_action_lo"><?php echo e(HTML::image("public/img/loading.gif", SITE_TITLE)); ?></div>
                            <span class="right_acdc" id="status<?php echo e($allrecord->id); ?>">
                                <?php if($allrecord->status == '1'): ?>
                                <a href="<?php echo e(URL::to( 'admin/admins/deactivatemerchant/'.$allrecord->slug)); ?>" title="Deactivate" class="deactivate"><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>
                                <?php else: ?>
                                <a href="<?php echo e(URL::to( 'admin/admins/activatemerchant/'.$allrecord->slug)); ?>" title="Activate" class="activate"><button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button></a>
                                <?php endif; ?>
                            </span>
                            <a href="<?php echo e(URL::to( 'admin/admins/editmerchant/'.$allrecord->slug)); ?>" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                            <a href="<?php echo e(URL::to( 'admin/admins/deletemerchant/'.$allrecord->slug)); ?>" title="Delete" class="btn btn-danger btn-xs action-list delete-list" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fa fa-trash-o"></i></a>
							<?php if($allrecord->wallet_balance>=1){ ?>
                            <a href="<?php echo e(URL::to( 'admin/wallets/createrequest/'.$allrecord->slug)); ?>" title="Create Withdraw Request" class="btn btn-danger btn-xs action-list delete-list" ><i class="fa fa-credit-card"></i></a>
							<?php } ?>
                            <a href="<?php echo e(URL::to( 'admin/wallets/withdrawals/'.$allrecord->slug)); ?>" title="Withdrawals" class="btn btn-danger btn-xs action-list delete-list" ><i class="fa fa-money"></i></a>
                            <a href="#info<?php echo $allrecord->id; ?>" title="View" class="btn btn-primary btn-xs" rel='facebox'><i class="fa fa-eye"></i></a>
                        </td>
                        </td>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div class="search_frm">
                <button type="button" name="chkRecordId" onclick="checkAll(true);"  class="btn btn-info">Select All</button>
                <button type="button" name="chkRecordId" onclick="checkAll(false);" class="btn btn-info">Unselect All</button>
                <?php global $accountStatus; ?>

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
<?php $__currentLoopData = $allrecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allrecord): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div id="info<?php echo $allrecord->id; ?>" style="display: none;">
    <div class="nzwh-wrapper">
        <fieldset class="nzwh">
            <legend class="head_pop"><?php echo $allrecord->first_name.' '.$allrecord->last_name; ?></legend>
            <div class="drt">
                <div class="admin_pop"><span>Business Name: </span>  <label><?php echo $allrecord->busineess_name; ?></label></div>
                <div class="admin_pop"><span>Merchant Name: </span>  <label><?php echo $allrecord->name; ?></label></div>
                <div class="admin_pop"><span>Email Address: </span>  <label><?php echo $allrecord->email_address; ?></label></div>
                <div class="admin_pop"><span>Contact Number: </span>  <label><?php echo $allrecord->contact; ?></label></div>
                <div class="admin_pop"><span>Business Type: </span>  <label><?php echo isset($allrecord->BusinessType->name)?$allrecord->BusinessType->name:'N/A'; ?></label></div>
                <div class="admin_pop"><span>Country: </span>  <label><?php echo isset($allrecord->Country->name)?$allrecord->Country->name:"N/A"; ?></label></div>
                <div class="admin_pop"><span>State: </span>  <label><?php echo isset($allrecord->State->name)?$allrecord->State->name:"N/A"; ?></label></div>
                <div class="admin_pop"><span>City: </span>  <label><?php echo isset($allrecord->City->name)?$allrecord->City->name:"N/A"; ?></label></div>
                <div class="admin_pop"><span>Locality: </span>  <label><?php echo isset($allrecord->Locality->locality_name)?$allrecord->Locality->locality_name:"N/A"; ?></label></div>
                <div class="admin_pop"><span>Address: </span>  <label><?php echo $allrecord->address; ?></label></div>
                <div class="admin_pop"><span>Zip code: </span>  <label><?php echo $allrecord->zipcode; ?></label></div>


                <div class="admin_pop"><span>Created Date: </span>  <label><?php echo $allrecord->created_at; ?></label></div>

        </fieldset>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/elements/admin/users/merchant.blade.php ENDPATH**/ ?>