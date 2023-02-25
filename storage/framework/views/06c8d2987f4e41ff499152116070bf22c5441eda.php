<?php $__env->startSection('content'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#adminForm").validate();
    });
  
    
</script>
<div class="content-wrapper">
    <section class="content-header">
        <h1> Why Buy Section</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(URL::to('admin/admins/dashboard')); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li><a href="javascript:void(0);"><i class="fa fa-cogs"></i> Configuration</a></li>
            <li class="active"> Why Buy Section</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Why Buy Section</h3>
            </div>
            <div class="ersu_message"><?php echo $__env->make('elements.admin.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
            <?php echo e(Form::model($recordInfo, ['method' => 'post', 'id' => 'adminForm', 'enctype' => "multipart/form-data"])); ?>  
            <div class="form-horizontal">
                <div class="box-body">
                                  
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Why Buy Heading <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('whybuy_heading', null, ['class'=>'form-control required', 'placeholder'=>'Why Buy Heading', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-2 control-label">Step 1 Title <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('step1_title', null, ['class'=>'form-control required', 'placeholder'=>'Step 1 Title', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-2 control-label">Step 1 Image <span class="require"></span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::file('step1_image', ['class'=>'form-control', 'accept'=>'image/png'])); ?>

                            <span class="help-text"> Supported File Types: png (Max. 2MB) (Best view:72 x 69px)</span>
                            <?php if($recordInfo->step1_image != ''): ?>
                               <div><?php echo e(HTML::image(LOGO_IMAGE_DISPLAY_PATH.$recordInfo->step1_image, SITE_TITLE,['style'=>"max-width: 200px"])); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-2 control-label">Step 1 Description <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('step1_description', null, ['class'=>'form-control required', 'placeholder'=>'Step 1 Description', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-2 control-label">Step 2 Title <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('step2_title', null, ['class'=>'form-control required', 'placeholder'=>'Step 2 Title', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-2 control-label">Step 2 Image <span class="require"></span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::file('step2_image', ['class'=>'form-control', 'accept'=>'image/png'])); ?>

                            <span class="help-text"> Supported File Types: png (Max. 2MB) (Best view:72 x 69px)</span>
                            <?php if($recordInfo->step1_image != ''): ?>
                               <div><?php echo e(HTML::image(LOGO_IMAGE_DISPLAY_PATH.$recordInfo->step2_image, SITE_TITLE,['style'=>"max-width: 200px"])); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-2 control-label">Step 2 Description <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('step2_description', null, ['class'=>'form-control required', 'placeholder'=>'Step 2 Description', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-2 control-label">Step 3 Title <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('step3_title', null, ['class'=>'form-control required', 'placeholder'=>'Step 3 Title', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-2 control-label">Step 3 Image <span class="require"></span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::file('step3_image', ['class'=>'form-control', 'accept'=>'image/png'])); ?>

                            <span class="help-text"> Supported File Types: png (Max. 2MB) (Best view:72 x 69px)</span>
                            <?php if($recordInfo->step1_image != ''): ?>
                               <div><?php echo e(HTML::image(LOGO_IMAGE_DISPLAY_PATH.$recordInfo->step3_image, SITE_TITLE,['style'=>"max-width: 200px"])); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
					<div class="form-group">
                        <label class="col-sm-2 control-label">Step 3 Description <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('step3_description', null, ['class'=>'form-control required', 'placeholder'=>'Step 3 Description', 'autocomplete' => 'off'])); ?>

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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/admin/admins/whyBuy.blade.php ENDPATH**/ ?>