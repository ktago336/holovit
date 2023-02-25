<?php $__env->startSection('content'); ?>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<script>
    $(document).ready(function () {
        $('#walletform').validate();
    });
	
</script>
<section class="listing_deal">
    <div class="container">


        <div class="panel panel-default">
            <div class="row"> 
                <div class="col-md-3">
                    <?php echo $__env->make('elements.merchant_left_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <div class="col-md-9">
                    <div class="panel-body">
                        <div class="tab-pane" id="2">
                            <div class="add_deal">
                                <div class="informetion_top">
                                    <div class="tatils_0t1"> Send Withdraw Request (Balance : <?php echo e(CURR.$userInfo->wallet_balance); ?>)</div>
                                     <div class="er_mes">
                                    <div class="ersu_message"><?php echo $__env->make('elements.admin.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
                                </div>
                                    <?php echo e(Form::open(array('method' => 'post', 'id' => 'walletform', 'enctype' => "multipart/form-data"))); ?>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Enter Amount (<?php echo e(CURR); ?>)<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            <?php echo e(Form::text('amount', null, ['max'=>$userInfo->wallet_balance, 'min'=>1, 'class'=>'form-control required number', 'placeholder'=>'Enter Amount', 'autocomplete' => 'off'])); ?>

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
											<!--<a href="javascript:void(0)" class="btn btn-primary" onclick="paybypaypal()">Send Request</a>-->
                                            <?php echo e(Form::submit('Send Request', ['class' => 'btn btn-info'])); ?>

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.merchant_inner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/merchant/user/sendwithdrawrequest.blade.php ENDPATH**/ ?>