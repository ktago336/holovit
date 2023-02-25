<style>
.my_text{
    font-size: 12px;
    margin-left: 55px;
    margin-top:-20px;
        }
</style>
<script type="text/javascript">
   
    function changestatus(status, id, current_status) {
   // alert(value+' '+id);
        if(confirm("Are you sure you want to change status?")){
            $("#loaderIDAct" + id).show();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
            $.ajax({
                type: "POST",
                url: "<?php echo HTTP_PATH; ?>/admin/wallets/changestatus/" + id + "/" + status,
                cache: false,
                success: function(responseText) {
                    $("#loaderIDAct" + id).hide();
                    $("#status" + id).html(responseText);
                }
            });
        }else{
            $("#WithdrawalStatus" + id).val(current_status);
        }
        
    }
</script>
<div class="admin_loader" id="loaderID"><?php echo e(HTML::image("public/img/website_load.svg", SITE_TITLE)); ?></div>
<?php if(!$allrecords->isEmpty()): ?>
<div class="panel-body marginzero">
    <div class="ersu_message"><?php echo $__env->make('elements.admin.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
    <?php echo e(Form::open(array('method' => 'post', 'id' => 'actionFrom'))); ?>

    <section id="no-more-tables" class="lstng-section">
        <div class="topn">
            <div class="topn_left">Withdrawals List</div>
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
                         
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('Merchant.busineess_name', 'Business Name'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('amount', 'Requested Amount'));?></th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('description', 'Description'));?></th>
						<th class="sorting_paging">Status</th>
                        <th class="sorting_paging"><?php echo \Kyslik\ColumnSortable\SortableLink::render(array ('created_at', 'Created'));?></th>
                    </tr>
                </thead>
                <tbody>
				<?php global $withdrawal_status;; ?>
                    <?php $__currentLoopData = $allrecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allrecord): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <!--<th style="width:5%"><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="<?php echo e($allrecord->id); ?>" /></th>-->
						<td data-title="Business Name"><?php echo e(isset($allrecord->Merchant->busineess_name)?$allrecord->Merchant->busineess_name:'N/A'); ?></td>
                        <td data-title="Requested Amount"><?php echo e(CURR.$allrecord->amount); ?></td>
                        <td data-title="Description"><?php echo e($allrecord->description); ?></td>
                        <td data-title="Status">
							<div id="loderstatus<?php echo e($allrecord->id); ?>" class="right_action_lo"><?php echo e(HTML::image("public/img/loading.gif", SITE_TITLE)); ?></div>
							<span id="status<?php echo e($allrecord->id); ?>">
									<?php if($allrecord->status == 0 || $allrecord->status == 1): ?>
                                        <?php echo e(Form::select('status',$withdrawal_status, $allrecord->status, ['class'=>'form-control required ', 'empty'=>false, 'autocomplete' => 'off', 'id' => 'WithdrawalStatus'.$allrecord->id, 'onchange' => "changestatus(this.value,'" . $allrecord->id . "','" . $allrecord->status . "')"])); ?>

									<?php else: ?>
										<?php echo e($withdrawal_status[$allrecord->status]); ?>

									<?php endif; ?>
							</span>
						</td>
                        
                        <td data-title="Payment Date"><?php echo e($allrecord->created_at); ?></td>
						
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            
        </div>
    </section>
    <?php echo e(Form::close()); ?>

</div>         
</div> 
<?php else: ?> 
<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
<div class="admin_no_record">No record found.</div>
<?php endif; ?>
<?php /**PATH /home/lscouponslogicsp/public_html/resources/views/elements/admin/wallets/withdrawals.blade.php ENDPATH**/ ?>