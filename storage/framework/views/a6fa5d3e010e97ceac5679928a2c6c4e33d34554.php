<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Withdrawals <?php if($merchantinfo && $merchantinfo->id > 0): ?><?php echo e('('.$merchantinfo->busineess_name.', Balance :'.CURR.$merchantinfo->wallet_balance.')'); ?><?php endif; ?> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(URL::to('admin/admins/dashboard')); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            
            <?php if($merchantinfo && $merchantinfo->id > 0): ?>
            <li><a href="<?php echo e(URL::to('admin/admins/merchant')); ?>"><i class="fa fa-users"></i> <span> Merchants</span></a></li>
            <li class="active">Withdrawals</li>
            <?php else: ?>
            <li class="active">Withdrawals</li>
            <?php endif; ?>
        </ol>
    </section>

    <section class="content">
        <div class="box box-info">
            <div class="ersu_message"><?php echo $__env->make('elements.admin.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
            <div class="admin_search">
                <?php echo e(Form::open(array('method' => 'post', 'id' => 'adminSearch'))); ?>

                <div class="form-group align_box dtpickr_inputs">
                    <span class="hints">Search by Business Name</span>
                    <span class="hint"><?php echo e(Form::text('keyword', null, ['class'=>'form-control', 'placeholder'=>'Search by keyword', 'autocomplete' => 'off'])); ?></span>
                    <div class="admin_asearch">
                        <div class="ad_s ajshort"><?php echo e(Form::button('Submit', ['class' => 'btn btn-info admin_ajax_search'])); ?></div>
                        <div class="ad_cancel"><a href="<?php echo e(URL::to('admin/wallets/withdrawals')); ?>" class="btn btn-default canlcel_le">Clear Search</a></div>
                    </div>
                </div>
                <?php echo e(Form::close()); ?>

				
				<?php if($merchantinfo && $merchantinfo->id > 0): ?>
					<?php if($merchantinfo->wallet_balance>=1): ?>
					    <div class="add_new_record"><a href="<?php echo e(URL::to('admin/wallets/createrequest/'.$merchantinfo->slug)); ?>" class="btn btn-default"><i class="fa fa-plus"></i> Create Withdraw Request</a></div>
				    <?php endif; ?>
				<?php else: ?>
					<div class="add_new_record"><a href="<?php echo e(URL::to('admin/wallets/createrequest')); ?>" class="btn btn-default"><i class="fa fa-plus"></i> Create Withdraw Request</a></div>
				<?php endif; ?>
            </div>            
            <div class="m_content" id="listID">
                <?php echo $__env->make('elements.admin.wallets.withdrawals', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/admin/wallets/withdrawals.blade.php ENDPATH**/ ?>