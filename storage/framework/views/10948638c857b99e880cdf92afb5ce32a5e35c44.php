<?php $__env->startSection('content'); ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">
    $(document).ready(function () {
        $.validator.addMethod("alphanumeric", function (value, element) {
            return this.optional(element) || /^[\a-zA-Z._ ]+$/i.test(value);
        }, "Only letters and underscore are allowed.");
        $.validator.addMethod("passworreq", function (input) {
            var reg = /[0-9]/; //at least one number
            var reg2 = /[a-z]/; //at least one small character
            var reg3 = /[A-Z]/; //at least one capital character
            //var reg4 = /[\W_]/; //at least one special character
            return reg.test(input) && reg2.test(input) && reg3.test(input);
        }, "Password must be a combination of Numbers, Uppercase & Lowercase Letters.");
        $.validator.addMethod("validname", function(value, element) {
            return this.optional(element) || /^[\w. ]+$/i.test(value);
        }, "Only letters, numbers, space and underscore allowed.");
        $("#loginform").validate();
    });
    function checkForm() {
        $('#captcha_msg').html("").removeClass('gcerror');
        if ($("#loginform").valid()) {
            var captchaTick = grecaptcha.getResponse();
            if (captchaTick == "" || captchaTick == undefined || captchaTick.length == 0) {
                $('#captcha_msg').html("Please confirm captcha to proceed").addClass('gcerror');
                $('#captcha_msg').addClass('gcerror');
                return false;
            }
        } else {
            var captchaTick = grecaptcha.getResponse();
            if (captchaTick == "" || captchaTick == undefined || captchaTick.length == 0) {
                $('#captcha_msg').html("Please confirm captcha to proceed").addClass('gcerror');
                return false;
            }
        }
    }
    ;
</script>
<section class="login-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-xl-12">
                <div class="container-login">
                    <div class="login-part">
                        <h1>Create Account</h1>
                        <div class="ee er_msg"><?php echo $__env->make('elements.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
                        <div class="login-form">
                            <?php echo e(Form::open(array('method' => 'post', 'id' => 'loginform', 'class' => 'form form-signin'))); ?>

                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <span><i class="fa fa-user-circle-o" aria-hidden="true"></i></span>
                                        <?php echo e(Form::text('first_name', null, ['id'=>'first_name', 'class'=>'form-control required validname', 'placeholder'=>'First Name', 'autocomplete'=>'OFF'])); ?>

                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <span><i class="fa fa-user-circle-o" aria-hidden="true"></i></span>
                                        <?php echo e(Form::text('last_name', null, ['id'=>'last_name', 'class'=>'form-control required validname', 'placeholder'=>'Last Name', 'autocomplete'=>'OFF'])); ?>

                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <span><i class="fa fa-envelope-open-o" aria-hidden="true"></i></span>
                                        <?php echo e(Form::text('email_address', Cookie::get('user_email_address'), ['class'=>'form-control required email', 'placeholder'=>'E-mail', 'autocomplete'=>'OFF'])); ?>

                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <span><i class="fa fa-key fa-rotate-90" aria-hidden="true"></i></span>
                                        <?php echo e(Form::password('password', ['class'=>'form-control required', 'placeholder' => 'Password', 'minlength' => 8, 'id'=>'password'])); ?>

                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <span><i class="fa fa-key fa-rotate-90" aria-hidden="true"></i></span>
                                        <?php echo e(Form::password('confirm_password', ['class'=>'form-control required', 'placeholder' => 'Confirm Password', 'equalTo' => '#password'])); ?>

                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <div class="groupon-input gcpaatcha">
                                            <div id="recaptchaQ" class="g-recaptcha" data-sitekey="<?php echo e(CAPTCHA_KEY); ?>" style="transform:scale(0.2);-webkit-transform:scale(1);transform-origin:0 0;-webkit-transform-origin:0 0;" ></div>
                                            <div class="gcpc" id="captcha_msg"></div>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <div class="rebrs remember_secsd register-check">
                                            <?php echo e(Form::checkbox('terms', '1', Cookie::get('terms'), array('class'=>'css-checkbox in-checkbox required','id'=>'checkboxG1'))); ?>

                                            <label class="in-label" for="checkboxG1">I read and agree to <a href="<?php echo e(URL::to( 'terms-and-condition')); ?>" target="blank">Terms & Conditions</a></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="Groupon_clone_button">
                                        <?php echo e(Form::submit('Create Account', ['class' => 'btn btn-primary', 'onclick'=>'return checkForm()'])); ?>

                                        <div class="sin-txt">Already have an account? <a href="<?php echo e(URL::to('login')); ?>">Sign In</a></div>
                                    </div>
                                </div>
                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/users/register.blade.php ENDPATH**/ ?>