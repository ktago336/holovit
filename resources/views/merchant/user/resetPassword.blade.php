@extends('layouts.merchant_inner')
@section('content')
<script type="text/javascript">
	$(document).ready(function () {
		$("#loginform").validate();
	});
</script>

<section class="login-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-xl-12">
                <div class="container-user-login">
                    <div class="login-part">
                        <h1>Reset Password</h1>
                        <div class="ee er_msg">@include('elements.errorSuccessMessage')</div>
                        <div class="login-form"> 
						    {{ Form::open(array('method' => 'post', 'id' => 'loginform', 'class' => 'form form-signin')) }}
                                <div class="form-group"> 
                                    <div class="Groupon_clone">
                                       <i class="fa fa-key fa-rotate-90" aria-hidden="true"></i>
										{{Form::password('password', ['class'=>'form-control required', 'placeholder' => 'New Password', 'minlength' => 8, 'id'=>'password'])}}
                                    </div>
                                </div>
								<div class="form-group"> 
                                    <div class="Groupon_clone">
                                       <i class="fa fa-key fa-rotate-90" aria-hidden="true"></i>
										{{Form::password('confirm_password', ['class'=>'form-control required', 'placeholder' => 'Confirm Password', 'equalTo' => '#password'])}}
                                       
                                    </div>
                                </div>
                                <div class="form-group"> 
                                    <div class="Groupon_clone_button">
										{{Form::submit('Submit', ['class' => 'btn btn-primary loginbtn'])}}
                                    </div>
                                </div>
                            {{ Form::close()}}
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>
</section>
<script>

	let passwordInput = document.getElementById('password'),
	toggle = document.getElementById('btnToggle'),
	icon =  document.getElementById('eyeIcon');

	function togglePassword() {
		if (passwordInput.type === 'password') {
			passwordInput.type = 'text';
			icon.classList.add("fa-eye-slash");
    //toggle.innerHTML = 'hide';
} else {
	passwordInput.type = 'password';
	icon.classList.remove("fa-eye-slash");
    //toggle.innerHTML = 'show';
}
}

function checkInput() {
  //if (passwordInput.value === '') {
  //toggle.style.display = 'none';
  //toggle.innerHTML = 'show';
  //  passwordInput.type = 'password';
  //} else {
  //  toggle.style.display = 'block';
  //}
}

toggle.addEventListener('click', togglePassword, false);
passwordInput.addEventListener('keyup', checkInput, false);
</script>
@endsection