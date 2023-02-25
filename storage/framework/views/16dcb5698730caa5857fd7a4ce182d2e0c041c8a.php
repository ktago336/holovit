<?php $__env->startSection('content'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[\w.]+$/i.test(value);
        }, "Only letters, numbers and underscore allowed.");
        $("#adminForm").validate();
    });
 </script>
 
<div class="content-wrapper">
    <section class="content-header">
        <h1>Edit Banner</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(URL::to('admin/admins/dashboard')); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li><a href="<?php echo e(URL::to('admin/banners')); ?>"><i class="fa fa-bars"></i> <span>Manage Banners</span></a></li>
            <li class="active"> Edit Banner</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">&nbsp;</h3>
            </div>
            <div class="ersu_message"><?php echo $__env->make('elements.admin.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
            <?php echo e(Form::model($recordInfo, ['method' => 'post', 'id' => 'adminForm', 'enctype' => "multipart/form-data"])); ?>            
            <div class="form-horizontal">
                <div class="box-body">
                
                   <div class="form-group">
                        <label class="col-sm-2 control-label">Banner Name <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('title', null, ['class'=>'form-control required', 'placeholder'=>'Banner Name', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Short Description <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::textarea('short_description', null, ['class'=>'form-control required', 'placeholder'=>'Short Description', 'autocomplete' => 'off', 'rows'=>4])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Banner Link <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('banner_url', null, ['class'=>'form-control required url', 'placeholder'=>'Banner Link', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                  
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Banner Image <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::file('banner_image', ['class'=>'form-control', 'accept'=>IMAGE_EXT])); ?>

                              <span class="help-text"> Supported File Types: jpg, jpeg, png (Max. <?php echo e(MAX_IMAGE_UPLOAD_SIZE_DISPLAY); ?>) (must be 1920 x 400 pixels).</span>
                             <?php if($recordInfo->banner_image != ''): ?>
                                <br><div class="showeditimage"><?php echo e(HTML::image(BANNER_SMALL_DISPLAY_PATH.$recordInfo->banner_image, SITE_TITLE,['style'=>"max-width: 100px"])); ?></div>
                                <!--<div class="help-text"><a href="<?php echo e(URL::to('admin/banners/deleteimage/'.$recordInfo->slug.'?return='.Request::url())); ?>" title="Cancel" class="canlcel_le"  onclick="return confirm('Are you sure you want to delete?')">Delete Image</a></div>-->
                             <?php endif; ?>
                        </div>
                    </div>
                    
                    
                    <div class="box-footer">
                        <label class="col-sm-2 control-label" for="inputPassword3">&nbsp;</label>
                        <?php echo e(Form::submit('Submit', ['class' => 'btn btn-info'])); ?>

                        <a href="<?php echo e(URL::to( 'admin/banners')); ?>" title="Cancel" class="btn btn-default canlcel_le">Cancel</a>
                    </div>
                </div>
            </div>
            <?php echo e(Form::close()); ?>

        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/admin/banners/edit.blade.php ENDPATH**/ ?>