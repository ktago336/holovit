<div class="admin_loader" id="loaderID"><?php echo e(HTML::image("public/img/website_load.svg", SITE_TITLE)); ?></div>
			<?php if(!$merchant->isEmpty()): ?>
<div class="hp">
		  <div class="ersu_message"><?php echo $__env->make('elements.admin.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
    <?php echo e(Form::open(array('method' => 'post', 'id' => 'actionFrom'))); ?>

    <div class="topn_rightd ddpagingshorting" id="pagingLinks" align="right">
        <div class="panel-heading" style="align-items:center;">
            <?php echo e($merchant->appends(Input::except('_token'))->render()); ?>


        </div>
    </div>
		<div class="table_responsive">
                <div class="table_dcf">
                    <div class="tr_tables">
<!--<div class="td_tables"></div>-->
                        <div class="td_tables"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('deal_name', 'Deal Name'));?></div>
                        <div class="td_tables"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('discount', 'Discount'));?></div>

                        <div class="td_tables"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('expire_date', 'Expiry Date'));?></div>
                        <div class="td_tables"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('created_on', 'Created On'));?></div>
                        <div class="td_tables">Action</div>

                    </div>
					<?php //print_r($merchant); exit;?>
					<?php foreach($merchant as $merchants){ ?>
                    <div class="tr_tables2">
<!--<div data-title="Select Deals" class="td_tables2">
<input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="<?php echo e($merchants->id); ?>" />
</div>-->
                                            <div data-title="Address Title" class="td_tables2">
                                               <?php echo $merchants->deal_name; ?> 
                                            </div>
                                           
                                            <div data-title="Total Amount" class="td_tables2">
                                               <?php echo $merchants->discount; ?>% 
                                            </div>
                                           
                                            <div data-title="Address Title" class="td_tables2">
                                                                                              <?php echo $merchants->expire_date; ?> 
                                            </div>
                                            <div data-title="Created" class="td_tables2">
                                                <?php echo $merchants->created_at; ?>
                                            </div>
                                        
                                            <div data-title="Action" class="td_tables2">
                                         
                                                 <div class="actions">
												 <?php // if($merchants->status == '1'){ ?>
												            <!--<a href="<?php echo e(URL('/merchant/deals/deactivate/'.$merchants->slug)); ?>" title="Deactivate" class="deactivate"><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>-->
												 <?php //}else{ ?>
												 <!--<a href="<?php echo e(URL('/merchant/deals/activate/'.$merchants->slug)); ?>" title="Activate" class="activate"><button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button></a>-->
												 <?php //} ?>
                                                            <a href="<?php echo e(URL('/merchant/deals/edit/'.$merchants->slug)); ?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>  
                                                             <a href="<?php echo e(URL('/merchant/deals/delete/'.$merchants->slug)); ?>" class="btn btn-primary btn-xs" onclick="return confirm('Are you sure you want to delete?')"><i class="fa fa-trash-o"></i></a>
                                                             <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>-->                          

                                                          </div>
                                                       
                                                </div>
                                 
                                            </div>
											<?php } ?>
                                    </div>
									
									</div>
								 <?php /*?>	<div class="search_frm">
        <button type="button" name="chkRecordId" onclick="checkAll(true);"  class="btn btn-info">Select All</button>
        <button type="button" name="chkRecordId" onclick="checkAll(false);" class="btn btn-info">Unselect All</button>
        <?php global $accountStatus; ?>
       
        <div class="list_sel">{{Form::select('action', $accountStatus,null, ['class' => 'small form-control','placeholder' => 'Action for selected record', 'id' => 'action'])}}</div>
        <button type="submit" class="small btn btn-success btn-cons btn-info" onclick="return ajaxActionFunction();" id="submit_action">OK</button>
    </div>  <?php */?>
    <?php echo e(Form::close()); ?>

            </div>
			
<?php else: ?> 
<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
<div class="admin_no_record">No record found.</div>
<?php endif; ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/elements/merchant/deals/index.blade.php ENDPATH**/ ?>