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
        <h1>Add Sub Category</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(URL::to('admin/admins/dashboard')); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li><a href="<?php echo e(URL::to('admin/categories')); ?>"><i class="fa fa-bars"></i> <span>Manage Categories</span></a></li>
            <li><a href="<?php echo e(URL::to('admin/categories/subcategories/'.$parentInfo->slug)); ?>"><i class="fa fa-bars"></i> <span><?php echo e($parentInfo->category_name); ?></span></a></li>
            <li class="active"> Add Sub Category</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">&nbsp;</h3>
            </div>
            <div class="ersu_message"><?php echo $__env->make('elements.admin.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
            <?php echo e(Form::open(array('method' => 'post', 'id' => 'adminForm', 'enctype' => "multipart/form-data"))); ?>

            <div class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Sub Category Name <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('category_name', null, ['class'=>'form-control required', 'placeholder'=>'Sub Category Name', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Sub Category Description <span class="require"></span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::textarea('category_desc', null, ['class'=>'form-control', 'placeholder'=>'Sub CategoryDescription', 'autocomplete' => 'off', 'rows'=>4])); ?>

                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Sub Category Image <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::file('category_image', ['class'=>'form-control required', 'accept'=>IMAGE_EXT])); ?>

                            <span class="help-text"> Supported File Types: jpg, jpeg, png (Max. <?php echo e(MAX_IMAGE_UPLOAD_SIZE_DISPLAY); ?>) (must be 358 x 378 pixels).</span>
                        </div>
                    </div>
                      <div class="form-group" id="staff-selection-mandatory-block">
                        <label class="col-sm-2 control-label">Is Featured Sub Category <span class="require"></span></label>
                        <div class="col-sm-10">
                             <div class="abs-radio-bx">
                                <div class="abs-check-bx">
                                    <input type="checkbox" name="is_feature" value="1">
                                </div>
                            </div>
                            
                        </div>
                    </div>
                                        
                    <div class="box-footer">
                        <label class="col-sm-2 control-label" for="inputPassword3">&nbsp;</label>
                        <?php echo e(Form::submit('Submit', ['class' => 'btn btn-info'])); ?>

                         <?php if(count($errors) > 0 || Session::has('error_message') || isset($error_message)): ?>
                        <a href="<?php echo e(URL::to( 'admin/categories/addsub/'.$parentInfo->slug)); ?>" title="Reset" class="btn btn-default canlcel_le">Reset</a>
                        <?php else: ?>
                        <?php echo e(Form::reset('Reset', ['class' => 'btn btn-default canlcel_le'])); ?>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php echo e(Form::close()); ?>

        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/admin/categories/addsub.blade.php ENDPATH**/ ?>