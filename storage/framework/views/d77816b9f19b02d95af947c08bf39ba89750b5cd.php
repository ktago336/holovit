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
        $("#registerform").validate();
    });
    function checkForm() {
		if($("#is_verfied").val()== '1'){
			
		}else{
			alert("Please verify voucher ID before redeem!");
			return false;
		}
    }
  
</script>
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
            $.ajax({
                type: "POST",
                url: '<?php echo HTTP_PATH; ?>/merchant/users/add_states',
                data: {'country_id': country_id},
                success: function (result) {
                    // alert(result);
                    $('#state_id').html(result);
                   // $('.subcategory_id').show();

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    $('#loaderID').hide();
                }
            });
        });
		
		
		$('#state_id').change(function () {
            //alert('sdfsd');
            var state_id = $("#state_id").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
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
        });
		});
		</script>
		<script type="text/javascript">
		$(document).ready(function () {
		$('#verify_number').click(function() { //alert();
		var number = $("#verify_contact").val();
			if(number){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});
				$.ajax({
					type: "POST",
					url: '<?php echo HTTP_PATH; ?>/merchant/verify-voucher',
					data: {'voucher_number': number},
					success: function (result) {
						if(result == 1){
							$("#is_verfied").val("1");
							$('#results').html("<div class='success-msg'>Voucher is available!</div>");
						}else{
							$('#results').html("<div class='error-msg'>"+result+"</div>");
							$("#is_verfied").val("0");
						}
						//$('#verify').css("display","block"); 
					},
					error: function (jqXHR, textStatus, errorThrown) {
						console.log(textStatus, errorThrown);
						$('#loaderID').hide();
					}
				});
			}else{
				alert("Please enter voucher ID!");
			}
		});
		});
		</script>
<section class="login-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-xl-12">
                <div class="merchant-login">
                    <div class="login-part">
                        <h1>Redeem Voucher</h1>
                        <div class="ee er_msg"><?php echo $__env->make('elements.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
                        <div class="login-form">
                            <?php echo e(Form::open(array('method' => 'post', 'id' => 'registerform', 'class' => 'form form-signin'))); ?>

                                
								<div class="form-group"> 
                                    <div class="Groupon_clone">
                                       <i class="fa fa-gift" aria-hidden="true"></i>
                                        <?php echo e(Form::text('voucher_number', null, ['id'=>'verify_contact', 'class'=>'form-control required', 'placeholder'=>'Enter voucher ID eg: LSGOV1111', 'autocomplete'=>'OFF'])); ?>

										<div class="verify_number">
										<a href="javascript:void(0);" id="verify_number" >Verify</a>
										</div>
										<div class="results-otp">
										<div id="results">
											
										</div>
										<!--<a href="javascript:void(0);" id="verify" class="verify-phone" >Verify</a>-->
                                    </div></div>
                                </div>
								
                                <input type="hidden" name="is_verfied" id="is_verfied" value="0" />
                                <div class="form-group">
                                    <div class="Groupon_clone_button">
                                        <?php echo e(Form::submit('Redeem', ['class' => 'btn btn-primary', 'onclick'=>'return checkForm()'])); ?>

                                        <div class="sin-txt">Go to the Orders <a href="<?php echo e(URL('/merchant/myorders')); ?>">Cancel</a></div>
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
<?php echo $__env->make('layouts.merchant_inner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/lscouponslogicsp/public_html/resources/views/merchant/user/redeem_voucher.blade.php ENDPATH**/ ?>