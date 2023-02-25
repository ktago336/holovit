<?php $__env->startSection('content'); ?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">
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
                        <h1>Sign In</h1>
                        <div class="ee er_msg"><?php echo $__env->make('elements.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
                        <div class="login-form">
                            <?php echo e(Form::open(array('url' => '/login', 'method' => 'post', 'id' => 'loginform', 'class' => 'form form-signin'))); ?>

                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <span><i class="fa fa-envelope-open-o" aria-hidden="true"></i></span>
                                        <?php echo e(Form::text('username', Cookie::get('user_username'), ['class'=>'form-control required email', 'placeholder'=>'E-mail'])); ?>

                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <span><i class="fa fa-key fa-rotate-90" aria-hidden="true"></i></span>
                                        <?php echo e(Form::input('password', 'password', Cookie::get('user_password'), array('class' => "required form-control", 'placeholder' => 'Password', 'id' => 'txtPassword'))); ?>

                                        <button type="button" id="btnToggle" class="toggle showpassword"><i id="eyeIcon" class="fa fa-eye"></i></button>
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="Groupon_clone has-feedback">
                                        <div id="recaptchaQ" class="g-recaptcha" data-sitekey="<?php echo e(CAPTCHA_KEY); ?>" style="transform:scale(0.8);-webkit-transform:scale(1.05);transform-origin:0 0;-webkit-transform-origin:0 0;" ></div>
                                        <div class="gcpc" id="captcha_msg"></div>
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                        <div class="rebrs remember_secsd">
                                            <?php echo e(Form::checkbox('user_remember', '1', Cookie::get('user_remember'), array('id'=>'checkboxG1', 'class'=>'css-checkbox in-checkbox'))); ?>

                                            <label class="in-label" for="checkboxG1"> Remember Me
                                            </label>
                                        </div>
                                        
                                        <div class="forgot">
                                            <a href="<?php echo e(URL::to('/forgot-password')); ?>">Forgot Password?</a>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="Groupon_clone_button">
                                        <?php echo e(Form::submit('Login', ['class' => 'btn btn-primary btn-block btn-flat', 'onclick'=>'return checkForm()'])); ?>

                                        <div class="create-acc">Don't have an account? <a href="<?php echo e(URL::to('/register')); ?>">Create Account</a></div>
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
<?php echo $__env->make('layouts.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/users/login.blade.php ENDPATH**/ ?>