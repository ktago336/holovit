@extends('layouts.newhome')
@section('content')
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
                    <h2><b>Your Profile Information</b></h2>
                    <div class="edit-info-sec">
                        <div class="edit-info"><a href="{{ URL::to( 'users/settings')}}"><i class="fa fa-pencil"></i></a></div>
                        <div class="profile-info">
                            <label>Name</label>
                            <span>@if(isset($recordInfo->first_name))
                            {!! $recordInfo->first_name.' '.$recordInfo->last_name !!}
                            @else
                            {{'N/A'}}
                            @endif</span>
                        </div>
                        <div class="profile-info">
                            <label>Email</label>
                            <span>{!! $recordInfo->email_address !!}</span>
                        </div>
                        <div class="profile-info">
                            <label>Contact</label>
                            <span>{!! $recordInfo->contact !!}</span>
                        </div>
<!--                        <div class="profile-info">
                            <label>Gender</label>
                            <span>Male</span>
                        </div>-->
                        <div class="profile-info">
                            <label>Profile Picture</label>
                            <span>
                                <div class="profile-img">
                                @if(!empty($recordInfo->profile_image) && file_exists(PROFILE_SMALL_UPLOAD_PATH.$recordInfo->profile_image))
                                {{HTML::image(PROFILE_SMALL_DISPLAY_PATH.$recordInfo->profile_image, SITE_TITLE, ['id'=> 'pimage', 'class'=> 'pimage_id'])}}
                                @else
                                {{HTML::image('public/img/front/no_profile.png', SITE_TITLE, ['id'=> 'pimage', 'class'=> 'pimage_id'])}}
                                @endif
                            </div>
                            </span>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection