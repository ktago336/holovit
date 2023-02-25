<?php $__env->startSection('content'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php 
$percentage_commission = $adminInfo->deposit_commission;
$fixed_commission = $adminInfo->deposit_fixed_commission;

?>
<script>
    $(document).ready(function () {
        $('#walletform').validate();
        $('#WalletAmount').keyup(function(){
            //calculate servicefee and total payble amount
            var amount = parseFloat($('#WalletAmount').val());
            if(amount > 0){
                var percentage_commission = parseFloat("<?php echo $percentage_commission; ?>");
                var fixed_commission = parseFloat("<?php echo $fixed_commission; ?>");
                var admin_commission = parseFloat(parseFloat(amount*percentage_commission/100+fixed_commission).toFixed(2));
                var total_amount = amount+admin_commission;
                //$('#WalletAmount').val()
                $('#WalletAdminCommission').val(admin_commission);
                $('#WalletTotalAmount').val(total_amount);
            }else{
                $('#WalletAdminCommission').val(0);
                $('#WalletTotalAmount').val(0);
            }
                
        });
    });
	
</script>
<section class="listing_deal">
    <div class="container">


        <div class="panel panel-default">
            <div class="row"> 
                <div class="col-md-3">
                    <?php echo $__env->make('elements.left_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <div class="col-md-9">
                    <div class="panel-body">
                        <div class="tab-pane" id="2">
                            <div class="add_deal">
                                <div class="informetion_top">
                                    <div class="tatils_0t1"> Add Money</div>
                                     <div class="er_mes">
                                    <div class="ersu_message"><?php echo $__env->make('elements.admin.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
                                </div>
                                    <?php echo e(Form::open(array('method' => 'post', 'id' => 'walletform', 'enctype' => "multipart/form-data"))); ?>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Enter Amount (<?php echo e(CURR); ?>)<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            <?php echo e(Form::text('amount', null, ['id'=>'WalletAmount', 'min'=>1, 'class'=>'form-control required number', 'placeholder'=>'Enter Amount', 'autocomplete' => 'off'])); ?>

                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-2 control-label">Service Fee (<?php echo e(CURR); ?>)<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            <?php echo e(Form::text('admin_commission', null, ['id'=>'WalletAdminCommission', 'class'=>'form-control required number', 'placeholder'=>'Service Fee', 'autocomplete' => 'off','readonly'])); ?>

                                        </div>
                                    </div> 
									<div class="form-group">
                                        <label class="col-sm-2 control-label">Total Amount (<?php echo e(CURR); ?>)<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            <?php echo e(Form::text('total_amount', null, ['id'=>'WalletTotalAmount', 'class'=>'form-control required number', 'placeholder'=>'Total Amount', 'autocomplete' => 'off','readonly'])); ?>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Description <span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            <?php echo e(Form::textarea('description', null, ['class'=>'form-control required', 'placeholder'=>'Enter Description', 'autocomplete' => 'off', 'rows'=>4])); ?>

                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">&nbsp;</label>
                                        <div class="col-sm-10">
											<a href="javascript:void(0)" class="btn btn-primary" onclick="paybypaypal()">Pay Now</a>
                                            <!--<?php echo e(Form::submit('Submit', ['class' => 'btn btn-info'])); ?>-->
                                            <?php echo e(Form::reset('Reset', ['class' => 'btn btn-default canlcel_le'])); ?>


                                        </div>

                                    </div>
                                    <?php echo e(Form::close()); ?>

                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function paybypaypal() {
        $('#walletform').attr("action", "<?php echo HTTP_PATH . '/users/payviapaypal' ?>");
        $('#walletform').submit();
    }
   

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.inner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/users/addmoney.blade.php ENDPATH**/ ?>