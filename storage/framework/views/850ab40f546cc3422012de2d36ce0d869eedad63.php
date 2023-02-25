<?php $__env->startSection('content'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        $.validator.addMethod("alphanumeric", function (value, element) {
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
<style>
    .showatr {
        width: 100%;
    }
    .sizes-drop {
        background: #ffffff;
        border: 1px solid #e9e8e8;
        position: absolute;
        width: 96.5%;
        z-index: 1;
    }
    .crooss span {
        display: inline-block;
        padding: 2px;
        line-height: 10px;
        color: #fff;
        cursor: pointer;
        position: absolute;
        right: -8px;
        top: -7px;
        background: #f00;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        text-align: center;
        line-height: 17px;
        font-weight: bold;
    }
    .sizemorescroll {
        background: #f6f6f6;
        max-height: 200px;
        overflow-x: hidden;
        overflow-y: auto;
        padding: 0;
        width: 100%;
        z-index: 1;
    }
    .cloth_size {
        float: left;
        width: 100%;
    }
    .des_box_cont.test-size {
        border-bottom: 1px solid #ddd;
        margin-bottom: 0;
        padding: 4px 10px;
    }
    .des_box_cont.test-size input[type="checkbox"] {
        vertical-align: middle;
        margin: -3px 5px 0 0;
    }
    .des_box_cont.test-size label {
        margin: 0;
        font-weight: normal;
        vertical-align: top; color: #797979
    }
    .newstyle
    {
        display: block!important;
        width:100%!important;
    }

</style>
<script type="text/javascript">
$(document).ready(function () {
	  $('#country_id').change(function () {
            //alert('sdfsd');
            var country_id = $("#country_id").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if(country_id > 0){
                $.ajax({
                    type: "POST",
                    url: '<?php echo HTTP_PATH; ?>/merchant/users/add_states',
                    data: {'country_id': country_id},
                    success: function (result) {
                        // alert(result);
                        $('#state_id').html(result);
                        $('#city_id').html("<option value=''>Select City</option>");
                       // $('.subcategory_id').show();
    
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                        $('#loaderID').hide();
                    }
                });
            }else{
                $('#state_id').html("<option value=''>Select State</option>");
            }
                
        });
		
		
		$('#state_id').change(function () {
            //alert('sdfsd');
            var state_id = $("#state_id").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if(state_id > 0){
                $.ajax({
                    type: "POST",
                    url: '<?php echo HTTP_PATH; ?>/merchant/users/add_cities',
                    data: {'state_id': state_id},
                    success: function (result) {
                        // alert(result);
                        $('#city_id').html(result);
    
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                        $('#loaderID').hide();
                    }
                });
            }else{
                $('#city_id').html("<option value=''>Select City</option>");
            }
                
        });
		});
		</script>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Add Merchant</h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(URL::to('admin/admins/dashboard')); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li><a href="<?php echo e(URL::to('admin/admins/merchant')); ?>"><i class="fa fa-users"></i> <span> Merchants</span></a></li>
            <li class="active"> Add Merchant</li>
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
                        <label class="col-sm-2 control-label">Business Name <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('busineess_name', null, ['class'=>'form-control required', 'placeholder'=>'Business Name', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Country <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::select('country_id', $country, null,['id'=>'country_id', 'class'=>'form-control required', 'placeholder'=>'Select Country', 'autocomplete'=>'OFF'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">State <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::select('state_id',array(), null, ['id'=>'state_id', 'class'=>'form-control required', 'placeholder'=>'Select State', 'autocomplete'=>'OFF'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">City <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::select('city_id',array(), null, ['id'=>'city_id', 'class'=>'form-control required', 'placeholder'=>'Select City', 'autocomplete'=>'OFF'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Business Category <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::select('business_type', $business_category, null, ['id'=>'business_type', 'class'=>'form-control required', 'placeholder'=>'Select Business Category', 'autocomplete'=>'OFF'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('name', null, ['class'=>'form-control required validname', 'placeholder'=>'Name', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Contact Number <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('contact', null, ['class'=>'form-control required digits', 'placeholder'=>'Contact Number', 'autocomplete' => 'off', 'minlength' => 8, 'maxlength' => 16])); ?>

                        </div>
                    </div>


                    <!--                    <div class="form-group">
                                            <label class="col-sm-2 control-label">Profile Image <span class="require">*</span></label>
                                            <div class="col-sm-10">
                                                <?php echo e(Form::file('profile_image', ['class'=>'form-control required', 'accept'=>IMAGE_EXT])); ?>

                                                <span class="help-text"> Supported File Types: jpg, jpeg, png (Max. <?php echo e(MAX_IMAGE_UPLOAD_SIZE_DISPLAY); ?>).</span>
                                            </div>
                                        </div>-->

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email Address <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('email_address', null, ['class'=>'form-control required email', 'placeholder'=>'Email Address', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    
                   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Address <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('address', null, ['class'=>'form-control required', 'placeholder'=>'Address', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Zip code <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('zipcode', null, ['class'=>'form-control required', 'maxlength'=>10, 'placeholder'=>'Zip Code', 'autocomplete' => 'off'])); ?>

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
                        <a href="<?php echo e(URL::to( 'admin/admins/addmerchant')); ?>" title="Reset" class="btn btn-default canlcel_le">Reset</a>
                        <?php else: ?>
                        <?php echo e(Form::reset('Reset', ['class' => 'btn btn-default canlcel_le'])); ?>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php echo e(Form::close()); ?>

        </div>
    </section>
    <script>

        function changeCount(aid, aname) {
            // console.log("id: "+aid);
            if ($("#StrategyAsset" + aid).is(':checked')) {
                var count = $("#ProductTotalSize").data("count");
                console.log("checked count : " + count);
                count++;
                $("#ProductTotalSize").data("count", count);
                $("#ProductTotalSize").val(count + ' services selected');
            } else {

                var count = $("#ProductTotalSize").data("count");
                console.log("unchecked count : " + count);

                count--;
                $("#ProductTotalSize").data("count", count);
                $("#ProductTotalSize").val(count + ' services selected');
            }
        }
    </script>
    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/admin/users/addmerchant.blade.php ENDPATH**/ ?>