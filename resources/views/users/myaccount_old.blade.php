@extends('layouts.home')
@section('content')
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
        $("#loginform").validate();
    });
 
</script>
<section class="profile-section">
    <div class="container">
        <div class="heading1">  My Stuff</div>
        <div class="row">
            @include('elements.profile_navbar')

            <div class="tab-content" id="nav-tabContent">
               
                <div class="tab-pane fade active show" id="nav-account " role="tabpanel" aria-labelledby="nav-contact-tab">
                    <nav class="nav_groupn">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active show" id="nav-home-tab" data-toggle="tab" href="#nav-account1" role="tab" aria-controls="nav-home" aria-selected="true">ACCOUNT</a>
                            <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-bucks" role="tab" aria-controls="nav-home" aria-selected="true">Groupon Bucks</a>

                        </div>
                    </nav>
                    <div class="tab-content from_groupn" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-account1" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="ee er_msg">@include('elements.errorSuccessMessage')</div>
                            {{Form::model($recordInfo, ['method' => 'post', 'class' => 'form form-signin', 'id' => 'loginform']) }}            
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">First Name</label>
                                            {{Form::text('first_name', null, ['id'=>'first_name', 'class'=>'form-control required', 'placeholder'=>'First Name', 'autocomplete'=>'OFF'])}}

                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Last Name </label>
                                            {{Form::text('last_name', null, ['id'=>'last_name', 'class'=>'form-control required', 'placeholder'=>'Last Name', 'autocomplete'=>'OFF'])}}
                                        </div>
                                          <div class="form-group">
                                            <label for="exampleInputPassword1">Email Address </label>
                                            {{Form::text('email_address', null, ['id'=>'last_name', 'class'=>'form-control required', 'placeholder'=>'Email Address', 'autocomplete'=>'OFF'])}}
                                        </div>


                                        <h3 class="change">Change Your Password</h3>
                                        <div class="form-group">

                                            <label class="current" for="password">Current Password</label>
                                            <!--<span class="reset">Can't remember your password? <a href="">Reset it now</a></span>-->

                                            {{Form::password('old_password', ['class'=>'form-control ', 'placeholder' => 'Password', 'minlength' => 8])}}
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">New Password</label>
                                            {{Form::password('password', ['class'=>'form-control ', 'placeholder' => 'Password', 'minlength' => 8, 'id'=>'password'])}}
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Retype New Password </label>
                                           {{Form::password('confirm_password', ['class'=>'form-control ', 'placeholder' => 'Confirm Password', 'equalTo' => '#password'])}}
                                        </div>
                                        <div class="submit-row">
                                            <input type="submit" class="btn-cta" value="Save Changes">
                                            {{Form::submit('Create Account', ['class' => 'btn-cta'])}}
                                        </div>
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
@endsection