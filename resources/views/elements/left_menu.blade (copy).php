<div class="profile-bx">
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
<ul>
    <li>
        <a class="<?php echo isset($myaccountAct)?$myaccountAct:''?>" href="{{ URL::to( 'users/myaccount')}}">
            <strong>My Profile</strong>
            <span>Your Name, Contact, Address</span>
        </a>
    </li>
    <li>
        <a class="<?php echo isset($settingsAct)?$settingsAct:''?>" href="{{ URL::to( 'users/settings')}}">
            <strong>Update Profile</strong>
            <span>Update Your Profile Information</span>
        </a>
    </li>
    <li>
        <a class="<?php echo isset($myrequests)?$myrequests:''?>" href="{{ URL::to( 'users/myrequests')}}">
            <strong>My Request</strong>
            <span>appointment details</span>
        </a>
    </li>
    <li>
        <a class="<?php echo isset($mypayments)?$mypayments:''?>" href="{{ URL::to( 'users/mypayments')}}">
            <strong>Payment History</strong>
            <span>payment details</span>
        </a>
    </li>
    <!-- <li>
        <a class="<?php //echo isset($myaccount)?$myaccount:''?>" href="{{ URL::to( 'users/myaccount')}}">
            <strong>View Business details</strong>
            <span>business details</span>
        </a>
    </li>
    <li>
        <a class="<?php //echo isset($settings)?$settings:''?>" href="{{ URL::to( 'users/settings')}}">
            <strong>Slots availability</strong>
            <span>available slots</span>
        </a>
    </li>
    <li>
        <a class="<?php //echo isset($settings)?$settings:''?>" href="{{ URL::to( 'users/settings')}}">
            <strong>Book an appointment</strong>
            <span>book an appointment</span>
        </a>
    </li>
    <li>
        <a class="<?php //echo isset($settings)?$settings:''?>" href="{{ URL::to( 'users/settings')}}">
            <strong>Add to Calendar</strong>
            <span>calendar</span>
        </a>
    </li>
    <li>
        <a class="<?php //echo isset($settings)?$settings:''?>" href="{{ URL::to( 'users/settings')}}">
            <strong>Staff Members List</strong>
            <span>staff members</span>
        </a>
    </li>
    <li>
        <a class="<?php //echo isset($settings)?$settings:''?>" href="{{ URL::to( 'users/settings')}}">
            <strong>Booking History</strong>
            <span>booking history</span>
        </a>
    </li>
    <li>
        <a class="<?php //echo isset($settings)?$settings:''?>" href="{{ URL::to( 'users/settings')}}">
            <strong>Review</strong>
            <span>customers reviews</span>
        </a>
    </li> -->
</ul>
<script>
    $(document).ready(function(){
        $(".uplaodprofileimg_id").on('change', function (event) {
            var postData = new FormData(this);

            event.preventDefault();
            $.ajax({
                url: "{!! HTTP_PATH !!}/users/uploadprofileimage",
                type: "POST",
                data: postData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('.ploader').show();
                },
                success: function (imagename) {
                    $('.pimage_id').attr("src", "{!! PROFILE_SMALL_DISPLAY_PATH !!}" + imagename);
                   
                    $('.ploader').hide();
                }
            });
        });
    })

</script>
