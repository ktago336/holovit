<!-- <div class="profile-bx">
{{ Form::open(array('method' => 'post', 'id' => 'uplaodprofileimg', 'class' => 'uplaodprofileimg_id', 'enctype' => "multipart/form-data")) }} -->
<!-- <div class="profile-img">
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
</div> -->
<!-- {{ Form::close()}}
</div> -->
<?php
$sslug=isset($expert->slug)?$expert->slug:'0';
?>
<ul>
    <li>
        <a class="<?php echo isset($myaccountAct)?$myaccountAct:''?>" href="{{ URL::to('/selectservice/'.$sslug)}}">
            <h5>Select Service</h5>
             
        </a>
        <a class="<?php echo isset($myaccountAct)?$myaccountAct:''?>" href="{{ URL::to('/updateselectservice/'.$record->slug)}}">
            <h5>edit selected</h5>
             
        </a>
    </li>
    <li class="disabled" aria-pressed="false">
        <a class=" disabled {{ Request::is('selectdatetime/'.$sslug) ? 'active' : '' }}" href="{{ URL::to('/selectdatetime/'.$sslug)}}" >
            <h5>Select Date & Time</h5>
            <!-- <span>Update Your Profile Information</span> -->
        </a>
    </li>
    <li>
        <a class="<?php echo isset($myaccount)?$myaccount:''?>" href="{{ URL::to( 'users/myaccount')}}">
            <h5>Your Info</h5>
            <!-- <span>business details</span> -->
        </a>
    </li>
    
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
