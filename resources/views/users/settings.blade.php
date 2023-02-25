@extends('layouts.newhome')
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
        $("#changepassword").validate();
        $("#changeprofile").validate();
    });
</script>
<section class="profile-section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-4 col-lg-3 col-xl-3">

                <div class="account-menu test">
                    @include('elements.left_menu')
                </div>
            </div>
            <div class="col-xs-12 col-md-8 col-lg-9 col-xl-9">
                <div class="my-profile-part">
                    <h2>Your Profile Information</h2>
                    <div class="edit-info-sec">
                        <div class="passmsg" id="passmsg1"></div>
                        {{ Form::open(array('method' => 'post', 'id' => 'changeprofile')) }}
                        <div class="row">
                            <div class="col-xs-12 col-md-12 col-lg-6 col-xl-6">
                                <div class="setting-input">
                                    <div class="form-group">
                                        {{Form::text('first_name', $recordInfo->first_name, ['id'=>'first_name', 'class'=>'form-control required alphanumeric', 'placeholder'=>'First Name', 'autocomplete' => 'off'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-12 col-lg-6 col-xl-6">
                                <div class="setting-input">
                                    <div class="form-group">
                                        {{Form::text('last_name', $recordInfo->last_name, ['id'=>'last_name', 'class'=>'form-control required alphanumeric', 'placeholder'=>'Last Name', 'autocomplete' => 'off'])}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-12 col-lg-6 col-xl-6">
                                <div class="setting-input">
                                    <div class="form-group">
                                        {{Form::text('contact', $recordInfo->contact, ['id'=>'contact', 'class'=>'form-control required digits', 'placeholder'=>'Contact Number', 'autocomplete' => 'off', 'minlength' => '8', 'maxlength' => '15'])}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-12 col-lg-6 col-xl-6">
                                <div class="setting-input">
                                    <div class="form-group">
                                        {{Form::text('address', $recordInfo->address, ['id'=>'address', 'class'=>'form-control required', 'placeholder'=>'Address', 'autocomplete' => 'off'])}}
                                    </div>
                                </div>
                            </div>
                        </div>

<!--                        <div class="row">
                            <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="setting-input">
                                    <div class="form-group">
                                        <label class="genders-lable">Male
                                            <input type="radio" checked="checked" name="radio">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="genders-lable">Female
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                        </label>
                                        <label class="genders-lable">Transgender
                                            <input type="radio" name="radio">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>-->

                        <div class="form-group">
                            <div class="setting-btn">
                                <button type="button" class="succbtn btn btn-primary round" id="passbtn1">Save Changes</button>
                               <!--  <div class="passloader" id="passloader1">{{HTML::image("public/img/loading.gif", SITE_TITLE)}}</div> -->
                            </div>
                        </div>
                        {{ Form::close()}}  
                    </div>
                </div>
                <div class="my-profile-part">
                    <h2>Your Profile Image</h2>
                    <div class="edit-info-sec">
                        <div class="passmsg" id="passmsg1"></div>
                        {{ Form::open(array('method' => 'post', 'id' => 'uplaodprofileimg', 'class' => 'uplaodprofileimg_id', 'enctype' => "multipart/form-data")) }}
                        <div class="profile-img">
                            @if(!empty($recordInfo->profile_image) && file_exists(PROFILE_SMALL_UPLOAD_PATH.$recordInfo->profile_image))
                            {{HTML::image(PROFILE_SMALL_DISPLAY_PATH.$recordInfo->profile_image, SITE_TITLE, ['id'=> 'pimage', 'class'=> 'pimage_id'])}}
                            @else
                            {{HTML::image('public/img/front/no_profile.png', SITE_TITLE, ['id'=> 'pimage', 'class'=> 'pimage_id'])}}
                            @endif
                            <div class="new-image-add">
                                {{Form::file('profile_image', ['class'=>'form-control', 'accept'=>IMAGE_EXT, 'id'=>'profile_image'])}}
                                <a href="#"><i class="fa fa-camera" aria-hidden="true"></i></a>
                            </div>
                            <span class="ploader" id="ploader">{{HTML::image('public/img/loading.gif', SITE_TITLE)}}</span>
                        </div>
                        {{ Form::close()}}
                    </div>
                </div>


                <div class="my-profile-part">
                    <h2>Change Your Password</h2>
                    <div class="edit-info-sec">
                        <div class="passmsg" id="passmsg"></div>
                        {{ Form::open(array('method' => 'post', 'id' => 'changepassword')) }}
                        <div class="row">
                            <div class="col-xs-12 col-md-12 col-lg-6 col-xl-6">
                        <div class="setting-input">
                            <div class="form-group">
                                {{Form::password('old_password', ['class'=>'form-control required', 'placeholder'=>'Current password', 'id'=>'old_password'])}}
                            </div>
                        </div>
                                </div>
                            <div class="col-xs-12 col-md-12 col-lg-6 col-xl-6">
                                  <div class="setting-input">
                            <div class="form-group">
                                {{Form::password('new_password', ['class'=>'form-control required passworreq', 'placeholder'=>'New password', 'id'=>'newpassword', 'minlength'=>8])}}
                                <span class="help-text">8 characters or longer and combination of upper, lowercase letters and numbers.</span>
                            </div>
                        </div>
                            </div>
                        </div>
                       <div class="row">
                            <div class="col-xs-12 col-md-12 col-lg-6 col-xl-6">
                        <div class="setting-input">
                            <div class="form-group">
                                {{Form::password('confirm_password', ['class'=>'form-control required', 'placeholder'=>'Confirm password', 'equalTo' => '#newpassword', 'id'=>'confirm_password'])}}
                            </div>
                        </div>
                            </div>
                       </div>
                        <div class="form-group">
                            <div class="setting-btn">
                                <button type="button" class="succbtn btn btn-primary round" id="passbtn">Save Changes</button>
                                <!-- <div class="passloader" id="passloader">{{HTML::image("public/img/loading.gif", SITE_TITLE)}}</div> -->
                            </div>
                        </div>
                        {{ Form::close()}}  
                    </div>
                </div>
            </div>


        </div>


    </div>
</section>
<style type="text/css">
    .round{
    border-radius: 30px;padding: 5px 10px;}
</style>
<script type="text/javascript">
    $("#passbtn").click(function () {
        if ($("#changepassword").valid()) {
            $.ajax({
                url: "{!! HTTP_PATH !!}/users/updatesettings",
                type: "POST",
                data: {"old_password": $('#old_password').val(), "newpassword": $('#newpassword').val(), "confirm_password": $('#confirm_password').val(), _token: '{{csrf_token()}}'},
                beforeSend: function () {
                    $("#passloader").show();
                },
                complete: function () {
                    $("#passloader").hide();
                },
                success: function (result) {
                    if (result == 1) {
                        $('#passmsg').html('<div class="alert alert-success"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button> You have successfully changed your account password.</div>');
                    } else {
                        $('#passmsg').html('<div class="alert alert-block alert-danger"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>' + result + '</div>');
                    }
                }
            });
        }
    });
    $("#passbtn1").click(function () {
    
        if ($("#changeprofile").valid()) {
            $.ajax({
                url: "{!! HTTP_PATH !!}/users/updatesettings",
                type: "POST",
                data: {"first_name": $('#first_name').val(), "last_name": $('#last_name').val(), "contact": $('#contact').val(), "address": $('#address').val(), _token: '{{csrf_token()}}'},
                beforeSend: function () {
                    $("#passloader1").show();
                },
                complete: function () {
                    $("#passloader1").hide();
                },
                success: function (result) {
                    if (result == 1) {
                        $('#passmsg1').html('<div class="alert alert-success"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button> Your profile has been updated successfully.</div>');
                    } else {
                        $('#passmsg1').html('<div class="alert alert-block alert-danger"><button data-dismiss="alert" class="close close-sm" type="button"><i class="fa fa-times"></i></button>' + result + '</div>');
                    }
                }
            });
        }
    });
    
    
    
</script>
@endsection