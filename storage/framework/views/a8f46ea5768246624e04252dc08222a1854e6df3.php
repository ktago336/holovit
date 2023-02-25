<?php $__env->startSection('content'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#adminForm").validate();
    });
</script>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Change Email</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(URL::to('admin/admins/dashboard')); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li><a href="javascript:void(0);"><i class="fa fa-cogs"></i> Configuration</a></li>
            <li class="active">Change Email</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">&nbsp;</h3>
            </div>
            <div class="ersu_message"><?php echo $__env->make('elements.admin.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
            <?php echo e(Form::open(array('method' => 'post', 'id' => 'adminForm', 'class' => 'form form-signin'))); ?>

            <div class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Current Email <span class="require"></span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('old_email', $adminInfo->email, ['class'=>'form-control required email', 'placeholder'=>'Current Email', 'autocomplete' => 'off', 'readonly'=>true])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">New Email <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('new_email', null, ['class'=>'form-control required email', 'placeholder'=>'New Email', 'autocomplete' => 'off', 'id'=>'newemail'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Confirm Email <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('confirm_email', null, ['class'=>'form-control required email', 'placeholder'=>'Confirm Email', 'autocomplete' => 'off', 'equalTo' => '#newemail'])); ?>

                        </div>
                    </div>

                    <?php if(IS_DEMO == 0): ?>
                        <div class="box-footer">
                            <label class="col-sm-2 control-label" for="inputPassword3">&nbsp;</label>
                            <?php echo e(Form::submit('Submit', ['class' => 'btn btn-info'])); ?>

                            <a href="<?php echo e(URL::to('admin/admins/dashboard')); ?>" class="btn btn-default canlcel_le">Cancel</a>
                        </div>
                    <?php else: ?>
                         <blockquote> You are not allowed to update above information, because it's a demo of this product. Once we deliver code to you, you'll be able to update this information. </blockquote>
                    <?php endif; ?>
                </div>
            </div>
            <?php echo e(Form::close()); ?>

        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/holovitr/domains/holovit.ru/public_html/resources/views/admin/admins/changeEmail.blade.php ENDPATH**/ ?>