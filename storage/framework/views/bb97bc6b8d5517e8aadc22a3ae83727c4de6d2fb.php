<?php $__env->startSection('content'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[\w.]+$/i.test(value);
        }, "Only letters, numbers and underscore allowed.");
        $.validator.addMethod("validname", function(value, element) {
            return this.optional(element) || /^[\w. ]+$/i.test(value);
        }, "Only letters, numbers, space and underscore allowed.");
        $.validator.addMethod("passworreq", function (input) {
            var reg = /[0-9]/; //at least one number
            var reg2 = /[a-z]/; //at least one small character
            var reg3 = /[A-Z]/; //at least one capital character
            //var reg4 = /[\W_]/; //at least one special character
            return reg.test(input) && reg2.test(input) && reg3.test(input);
        }, "Password must be a combination of Numbers, Uppercase & Lowercase Letters.");
        
        $("#adminForm").validate();
    });
 </script>
 
<div class="content-wrapper">
    <section class="content-header">
        <h1>Add Customer</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(URL::to('admin/admins/dashboard')); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li><a href="<?php echo e(URL::to('admin/users')); ?>"><i class="fa fa-users"></i> <span> Customers</span></a></li>
            <li class="active"> Add Customer</li>
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
                        <label class="col-sm-2 control-label">First Name <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('first_name', null, ['class'=>'form-control required validname', 'placeholder'=>'First Name', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Last Name <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('last_name', null, ['class'=>'form-control required validname', 'placeholder'=>'Last Name', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Contact Number <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('contact', null, ['class'=>'form-control required digits', 'placeholder'=>'Contact Number', 'autocomplete' => 'off', 'minlength' => 8, 'maxlength' => 16])); ?>

                        </div>
                    </div>
                    <?php /*<div class="form-group">
                        <label class="col-sm-2 control-label">Gender <span class="require"></span></label>
                        <div class="col-sm-10">
                            <div class="radd"> {{ Form::radio('gender', 'Male', true) }} <span>Male</span> </div>
                            <div class="radd"> {{ Form::radio('gender', 'Female', false) }} <span>Female</span> </div>
                        </div>
                    </div> */?>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Full Address <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::textarea('address', null, ['class'=>'form-control required', 'placeholder'=>'Enter full address', 'autocomplete' => 'off', 'rows'=>4])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Profile Image <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::file('profile_image', ['class'=>'form-control required', 'accept'=>IMAGE_EXT])); ?>

                            <span class="help-text"> Supported File Types: jpg, jpeg, png (Max. <?php echo e(MAX_IMAGE_UPLOAD_SIZE_DISPLAY); ?>).</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email Address <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('email_address', null, ['class'=>'form-control required email', 'placeholder'=>'Email Address', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Password <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::password('password', ['class'=>'form-control required', 'placeholder' => 'Password', 'minlength' => 8, 'id'=>'password'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Confirm Password <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::password('confirm_password', ['class'=>'form-control required', 'placeholder' => 'Confirm Password', 'equalTo' => '#password'])); ?>

                        </div>
                    </div>
                    
                    <div class="box-footer">
                        <label class="col-sm-2 control-label" for="inputPassword3">&nbsp;</label>
                        <?php echo e(Form::submit('Submit', ['class' => 'btn btn-info'])); ?>

                        <?php if(count($errors) > 0 || Session::has('error_message') || isset($error_message)): ?>
                        <a href="<?php echo e(URL::to( 'admin/users/add')); ?>" title="Reset" class="btn btn-default canlcel_le">Reset</a>
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/admin/users/add.blade.php ENDPATH**/ ?>