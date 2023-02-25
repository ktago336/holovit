@extends('layouts.inner')
@section('content')
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.min.css">
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js"></script>
<script>
    $(function () {
        $("#skill_nameid").chosen({disable_search_threshold: 10});
    })
</script>
{{ HTML::script('public/js/facebox.js')}}
{{ HTML::style('public/css/facebox.css')}}
<script type="text/javascript">
    $(document).ready(function ($) {
        $('.close_image').hide();
        $('a[rel*=facebox]').facebox({
            closeImage: '{!! HTTP_PATH !!}/public/img/close.png'
        });
    });
</script>
<div class="main_dashboard">
    <section class="dashboard-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-5 col-md-4">
                    <div class="profile-bx">
                        {{ Form::open(array('method' => 'post', 'id' => 'uplaodprofileimg', 'enctype' => "multipart/form-data")) }}
                        <div class="profile-img">
                            @if(isset($recordInfo->profile_image))
                            {{HTML::image(PROFILE_SMALL_DISPLAY_PATH.$recordInfo->profile_image, SITE_TITLE, ['id'=> 'pimage'])}}
                            @else
                            {{HTML::image('public/img/front/user-img.png', SITE_TITLE, ['id'=> 'pimage'])}}
                            @endif
                            <div class="new-image-add">
                                {{Form::file('profile_image', ['class'=>'form-control', 'accept'=>IMAGE_EXT, 'id'=>'profile_image'])}}
                                <a href="#"><i class="fa fa-camera" aria-hidden="true"></i></a>
                            </div>
                            <span class="ploader" id="ploader">{{HTML::image('public/img/loading.gif', SITE_TITLE)}}</span>
                        </div>
                        {{ Form::close()}}
                        {{ Form::open(array('method' => 'post', 'id' => 'updatecontact')) }}
                        <h2>@if(isset($recordInfo->first_name))
                            {!! $recordInfo->first_name.' '.$recordInfo->last_name !!}
                            @else
                            {{'N/A'}}
                            @endif
                        </h2>
                        @if(isset($recordInfo->contact))
                        <p id="contactn" class="showall"> <span id="showcontact"> {!! $recordInfo->contact !!} </span> <span class="curpointer" onclick="showeditdiv('contactn');"><i class="fa fa-pencil" aria-hidden="true"></i></span></p>
                        <div class=" displaynone" id="div_contactn">
                            {{Form::text('contact', $recordInfo->contact, ['class'=>'form-control required digits', 'placeholder'=>'Contact Number', 'autocomplete' => 'off', 'minlength' => 8, 'maxlength' => 16, 'id'=>'contactid'])}}
                            <div class="upbtn"><button type="button" class="cancelbtn" id="cntsuccbtn" onclick="hideeditdiv('contactn');">Cancel</button> <button type="button" class="succbtn" id="succbtnbtn">Update</button></div>
                        </div>
                        @endif
                        <div class="preview-btn"><a href="{{ URL::to( 'public-profile/'.$recordInfo->slug)}}" class="btn btn-default">Preview Public Mode</a></div>

                        <div class="user-details">
                            <ul>
                                <li><label><i class="fa fa-user" aria-hidden="true"></i>Member since</label><span>{!! $recordInfo->created_at->format('M Y')!!}</span></li>

                            </ul>
                        </div>
                        {{ Form::close()}}
                    </div>
                    {{ Form::open(array('method' => 'post', 'id' => 'updateother')) }}
                    <div class="profile-txtbx">
                        <div class="user-txt-bx">
                            <div id="descriptioncnt" class="showall">
                                <h3>About Yourself</h3>
                                <span class="curpointer editright" onclick="showeditdiv('descriptioncnt');"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                                <div class="text-section" >
                                    <p id="desctext">{!! nl2br($recordInfo->description) !!}</p>
                                </div>
                            </div>
                            <div class="editprofile displaynone" id="div_descriptioncnt">
                                <h3>About Yourself</h3>
                                <div class="text-section" >
                                    {{Form::textarea('description', $recordInfo->description, ['class'=>'form-control required', 'placeholder'=>'Tell us about yourself', 'autocomplete' => 'off', 'rows'=>4, 'id'=>'descriptionid'])}}
                                </div>
                                <div class="upbtn"><button type="button" class="cancelbtn" id="cntsuccbtn" onclick="hideeditdiv('descriptioncnt');">Cancel</button> <button type="button" class="succbtn" id="descriptionbtn">Update</button></div>
                            </div>
                        </div>
                        
                        

                        


                        
                    </div>
                    {{ Form::close()}}
                </div>
                <?php /*<div class="col-sm-7 col-md-8">
                    <div class="dashboard-rights-section">
                        <div class="row">
                            @if(!$mygigs->isEmpty())
                                <div class="col-sm-6 col-md-4">
                                    <div class="thumbnail">
                                        <div class="creat-new">
                                            <a href="{{ URL::to( 'gigs/create')}}">
                                                <i> {{HTML::image('public/img/front/plus.png', SITE_TITLE)}}</i>
                                                <span>Create a new gig</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @foreach($mygigs as $allrecord)
                                    <div class="col-sm-6 col-md-4">
                                        <div class="thumbnail">
                                            <div class="project-img">
                                                <?php 
                                                $gigimgname = '';
                                                if ($allrecord->Image) {
                                                    foreach ($allrecord->Image as $gigimage) {
                                                        if (isset($gigimage->name) && !empty($gigimage->name)) {
                                                            $path = GIG_FULL_UPLOAD_PATH . $gigimage->name;
                                                            if (file_exists($path) && !empty($gigimage->name)) {
                                                                $gigimgname = GIG_FULL_DISPLAY_PATH . $gigimage['name'];
                                                                break;
                                                            }
                                                        }
                                                    }
                                                }
                                                if ($gigimgname == '' && $allrecord->youtube_image) {
                                                    if (file_exists(GIG_FULL_UPLOAD_PATH.$allrecord->youtube_image)) {
                                                        $gigimgname = GIG_FULL_DISPLAY_PATH . $allrecord->youtube_image;
                                                    }
                                                }
                                                if ($gigimgname == '') {
                                                    $gigimgname = 'public/img/front/dummy.png';
                                                }
                                                ?>
                                                <a href="{{ URL::to( 'gig-details/'.$allrecord->slug)}}" class="">{{HTML::image($gigimgname, SITE_TITLE,['title'=>$allrecord->title])}}</a>
                                            </div>
                                            <div class="caption">
                                                <h3><a href="{{ URL::to( 'gig-details/'.$allrecord->slug)}}" class="">{{$allrecord->title}}</a></h3>
                                                <div class="pro-bottm">
                                                    <div class="pro-bottm-left"><a id="maraction-{{$allrecord->id}}" class="maraction" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Gig Actions">{{HTML::image('public/img/front/ellipsis.png', SITE_TITLE)}}</a></div>
                                                    <div class="pro-bottm-right">
                                                        <a href="{{ URL::to( 'gig-details/'.$allrecord->slug)}}"><small>Starting at</small>{{CURR.$allrecord->basic_price}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="offer-show-{{$allrecord->id}}" class="show-adwanv">
                                                <ul>
                                                    <li>
                                                        <a href="{{ URL::to( 'gigs/edit/'.$allrecord->slug)}}" class=""><i class="fa fa-pencil" aria-hidden="true"></i><span>Edit</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::to( 'gig-details/'.$allrecord->slug)}}" class=""><i class="fa fa-eye" aria-hidden="true"></i><span>View Detail</span></a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ URL::to( 'gigs/delete/'.$allrecord->slug)}}" class="" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fa fa-trash" aria-hidden="true"></i><span>Delete</span></a>
                                                    </li>
                                                    <li class="advanced-setting">
                                                        <a href="javascript:void(0);" class="clsstng" id="close-{{$allrecord->id}}"><i class="fa fa-close" aria-hidden="true"></i><span>Close</span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @else 
                                <div class="col-sm-6 col-md-12">
                                    <div class="thumbnail">
                                        <div class="creat-new creat-new-full">
                                            <a href="{{ URL::to( 'gigs/create')}}">
                                                <i> {{HTML::image('public/img/front/plus.png', SITE_TITLE)}}</i>
                                                <span>Create a new gig</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        @if(!$myorders->isEmpty())
                            <div class="dashborad-tt">
                            <div class="buyer-top-title"><h2>Latest Selling Orders</h2></div>
                            <div class="management-bx">
                                <div class="management-bx-over">
                                    <div class="management-table">
                                        <div class="management-table-tr">
                                            <div class="management-table-th dashdate">Date</div>
                                            <div class="management-table-th dashbuyer">Buyer Name</div>
                                            <div class="management-table-th dashtitle">Gig Title</div>
                                            <div class="management-table-th">Package</div>
                                            <div class="management-table-th">Amount</div>
                                            <div class="management-table-th">Action</div>
                                        </div>
                                        <?php global $gigOrderStatus; ?>
                                        @foreach($myorders as $allrecord)
                                        <div class="management-table-tr">
                                            <div class="management-table-td">{{$allrecord->created_at->format('d M, Y')}}</div>
                                            <div class="management-table-td">{{$allrecord->Buyer->first_name.' '.$allrecord->Buyer->last_name}}</div>
                                            <div class="management-table-td">{{$allrecord->Gig->title or ''}}</div>
                                            <div class="management-table-td">{{$allrecord->package}}</div>
                                            <div class="management-table-td">{{CURR.$allrecord->revenue}}</div>
                                            <div class="management-table-td">
                                                <a href="#info{!! $allrecord->id !!}" title="View Offer Details" class="btn btn-primary btn-xs" rel='facebox'><i class="fa fa-eye"></i></a>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @foreach($myorders as $allrecord)
                                <div id="info{!! $allrecord->id !!}" style="display: none;" class="frnt_div">
                                    <div class="nzwh-wrapper">
                                        <fieldset class="nzwh">
                                            <legend class="head_pop">
                                                Order #
                                                @if($allrecord->pay_type === 'Wallet')
                                                    {{$allrecord->wallet_trn_id}}
                                                @else 
                                                    {{$allrecord->paypal_trn_id}}
                                                @endif
                                            </legend>
                                            <div class="drt">
                                                <div class="admin_pop"><span>Buyer Name: </span>  <label>{!! $allrecord->Buyer->first_name.' '.$allrecord->Buyer->last_name !!}</label></div>
                                                <div class="admin_pop"><span>Gig Title: </span>  <label>{{$allrecord->Gig->title or ''}}</label></div>
                                                <div class="admin_pop"><span>Order Date: </span>  <label>{{$allrecord->created_at->format('d M, Y')}}</label></div>
                                                <div class="admin_pop"><span>Order ID: </span>  <label>
                                                    @if($allrecord->pay_type === 'Wallet')
                                                        {{$allrecord->wallet_trn_id}}
                                                    @else 
                                                        {{$allrecord->paypal_trn_id}}
                                                    @endif
                                                    </label>
                                                </div>
                                                <div class="admin_pop"><span>Package: </span>  <label>{{$allrecord->package}}</label></div>
                                                <div class="admin_pop"><span>Amount: </span>  <label>{{$allrecord->revenue}}</label></div>
                                                <div class="admin_pop"><span>Status: </span>  <label>{{$gigOrderStatus[$allrecord->status]}}</label></div>
                                        </fieldset>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @endif
                        
                        @if(!$latestservices->isEmpty())
                        <div class="dashborad-tt">
                            <div class="buyer-top-title"><h2>Latest Buyer Requests</h2></div>
                            <div class="management-bx">
                                <div class="management-table management-table-left">
                                    <div class="management-table-tr">
                                        <div class="management-table-th">Date</div>
                                        <div class="management-table-th">Buyer</div>
                                        <div class="management-table-th">Request</div>
                                        <div class="management-table-th">Budget</div>
                                    </div>
                                    @foreach($latestservices as $allrecord)
                                    @if(isset($allrecord->User->slug))
                                        <div class="management-table-tr">
                                            <div class="management-table-td">{{$allrecord->created_at->format('d M, Y')}}</div>
                                            <div class="management-table-td">
                                                <div class="buyer-img">
                                                    @if(isset($allrecord->User->profile_image))
                                                    <a href="{{ URL::to('public-profile/'.$allrecord->User->slug)}}" class="">{{HTML::image(PROFILE_SMALL_DISPLAY_PATH.$allrecord->User->profile_image, SITE_TITLE,['style'=>"max-width: 60px"])}}</a>
                                                    @else
                                                    <a href="{{ URL::to('public-profile/'.$allrecord->User->slug)}}" class="">{{HTML::image('public/img/front/user-img.png', SITE_TITLE,['style'=>"max-width: 60px"])}}</a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="management-table-td management-table-td-fixed dashlink">
                                                <a href="{{ URL::to( 'buyer-requests/'.$allrecord->slug)}}" class="">{{$allrecord->title}}</a>
                                                <p>{{ str_limit($allrecord->description, $limit = 150, $end = '...') }}</p>
                                            </div>
                                            <div class="management-table-td">{{CURR.number_format($allrecord->price, 2)}}</div>
                                        </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        


                    </div>
                </div>*/ ?>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        $(".dropdown").hover(
                function () {
                    $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideDown("400");
                    $(this).toggleClass('open');
                },
                function () {
                    $('.dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).slideUp("400");
                    $(this).toggleClass('open');
                }
        );
    });</script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    function showeditdiv(did, add = 0){  
        $('.curpointer').show();
        $('#pl_'+did).hide();
        $('.displaynone').hide();
        $('.showall').show();
        $('#language_nameid').val('');
        $('#language_levelid').val('');
        $('#language_old').val('');
        $('#skill_nameid').val('');

        $('#status_nameid').val('');
        $('#country_nameid').val('');
        $('#university_nameid').val('');
        $('#qual_nameid').val('');
        $('#stream_nameid').val('');
        $('#yearid').val('');
        $('#edu_old').val('');

        $('#certificate_nameid').val('');
        $('#certificate_fromid').val('');
        $('#yearcertid').val('');
        $('#cert_old').val('');

        if (add == 1) {
            $('.succbtn').html('Add');
        }
        $('#' + did).hide();
        $('#div_' + did).show();

    }
    function hideeditdiv(did) {
        $('#pl_'+did).show();
        $('#' + did).show();
        $('#div_' + did).hide();
    }

    function editLang(key, lname, llevel) {
        $('#div_languagesctn').show();
        $('#language_nameid').val(lname);
        $('#language_levelid').val(llevel);
        $('#language_old').val(key);
    }

    function deleteLang(key) {
        $('#' + key).remove();
        $.ajax({
            url: "{!! HTTP_PATH !!}/users/updatedata",
            type: "POST",
            data: {"deletekey": key, _token: '{{csrf_token()}}'},
        });
    }
    function deleteSkill(key) {
        $('#s' + key).remove();
        $.ajax({
            url: "{!! HTTP_PATH !!}/users/updatedata",
            type: "POST",
            data: {"deleteskill": key, _token: '{{csrf_token()}}'},
        });
    }

    function editEdu(key, country_name, university_name, qual_name, stream_name, year) {
        $('#div_educationctn').show();
        $('#country_nameid').val(country_name);
        $('#university_nameid').val(university_name);
        $('#qual_nameid').val(qual_name);
        $('#stream_nameid').val(stream_name);
        $('#yearid').val(year);
        $('#edu_old').val(key);
        $('#edubtn').html('Update');
    }

    function deleteEdu(key) {
        $('#' + key).remove();
        $.ajax({
            url: "{!! HTTP_PATH !!}/users/updatedata",
            type: "POST",
            data: {"deleteedu": key, _token: '{{csrf_token()}}'},
        });
    }

    function editCert(key, certificate_name, certificate_from, year) {
        $('#div_certificationctn').show();
        $('#certificate_nameid').val(certificate_name);
        $('#certificate_fromid').val(certificate_from);
        $('#yearcertid').val(year);
        $('#cert_old').val(key);
        $('#certbtn').html('Update');
    }
    function deleteCert(key) {
        $('#' + key).remove();
        $.ajax({
            url: "{!! HTTP_PATH !!}/users/updatedata",
            type: "POST",
            data: {"deletecert": key, _token: '{{csrf_token()}}'},
        });
    }

    $(document).ready(function () {
        $("#uplaodprofileimg").on('change', function (event) {
            var postData = new FormData(this);
            event.preventDefault();
            $.ajax({
                url: "{!! HTTP_PATH !!}/users/uploadprofileimage",
                type: "POST",
                data: postData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $('#ploader').show();
                },
                success: function (imagename) {
                    $('#pimage').attr("src", "{!! PROFILE_SMALL_DISPLAY_PATH !!}" + imagename);
                    $('#ploader').hide();
                }
            });
        });

        $("#succbtnbtn").click(function () {
            if ($("#updatecontact").valid()) {
                $('#showcontact').html($("#contactid").val());
                $('#contactn').show();
                $('#div_contactn').hide();
                $.ajax({
                    url: "{!! HTTP_PATH !!}/users/updatedata",
                    type: "POST",
                    data: $('#updatecontact').serialize(),
                });
            }
        });

        $("#countrybtn").click(function () {
            if ($("#updatecontact").valid()) {
                var countryname = $('#city_id').val() + ', ' + $("#countrynameid option:selected").text() + ', ' + $('#zipcode_id').val();
                $('#countryctn').show();
                $('#div_countryctn').hide();
                $('#cname').html(countryname);
                $.ajax({
                    url: "{!! HTTP_PATH !!}/users/updatedata",
                    type: "POST",
                    dataType: 'json',
                    data: {"countrynameid": $('#countrynameid').val(), "city_id": $('#city_id').val(), "zipcode_id": $('#zipcode_id').val(), _token: '{{csrf_token()}}'},
                });
            }
        });
        $("#statusbtn").click(function () {
            if ($("#updatecontact").valid()) {
                var statusname = $("#statusnameid option:selected").text();
                $('#statusctn').show();
                $('#div_statusctn').hide();
                $('#statusname').html(statusname);
                $.ajax({
                    url: "{!! HTTP_PATH !!}/users/updatedata",
                    type: "POST",
                    dataType: 'json',
                    data: {"statusnameid": $('#statusnameid').val(), _token: '{{csrf_token()}}'},
                });
            }
        });

        $("#descriptionbtn").click(function () {
            if ($("#updateother").valid()) {
                $('#desctext').html($("#descriptionid").val().replace(/\n/g, "<br>"));
                $('#descriptioncnt').show();
                $('#div_descriptioncnt').hide();
                $.ajax({
                    url: "{!! HTTP_PATH !!}/users/updatedata",
                    type: "POST",
                    dataType: 'json',
                    data: {"description": $('#descriptionid').val(), _token: '{{csrf_token()}}'},
                });
            }
        });

        $("#langbtn").click(function () {
            if ($("#updateother").valid()) {
                $('#div_languagesctn').hide();
                if ($('#language_old').val()) {
                    var sl = $('#language_old').val();
                    $('#' + $('#language_old').val()).html('<span class="title">' + $('#language_nameid').val() + '</span><span class="sub-title">' + $('#language_levelid').val() + '</span><span class="hint--top"><a href="javascript:void();" onclick="editLang(\'' + sl + '\',\'' + $('#language_nameid').val() + '\',\'' + $('#language_levelid').val() + '\')"><i class="fa fa-pencil" aria-hidden="true"></i></a> </span> <span class="hint--top"><a href="javascript:void();" onclick="deleteLang(\'' + sl + '\')"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </span>');
                } else {
                    var sl = $('#language_nameid').val().replace(" ", "_").toLowerCase();
                    $('#addlangdiv').append('<li id="' + sl + '"><span class="title">' + $('#language_nameid').val() + '</span><span class="sub-title">' + $('#language_levelid').val() + '</span><span class="hint--top"><a href="javascript:void();" onclick="editLang(\'' + sl + '\',\'' + $('#language_nameid').val() + '\',\'' + $('#language_levelid').val() + '\')"><i class="fa fa-pencil" aria-hidden="true"></i></a> </span> <span class="hint--top"><a href="javascript:void();" onclick="deleteLang(\'' + sl + '\')"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </span></li>');
                }
                $('#pl_languagesctn').show();
                $.ajax({
                    url: "{!! HTTP_PATH !!}/users/updatedata",
                    type: "POST",
                    dataType: 'json',
                    data: {"lang_name": $('#language_nameid').val(), "lang_level": $('#language_levelid').val(), "lang_old": $('#language_old').val(), _token: '{{csrf_token()}}'},
                });
            }
        });

        $("#addskilbtn").click(function () {
            if ($("#updateother").valid()) {
                $('#div_skillsctn').hide();
                var skillId = $('#skill_nameid').val();
                var skilltext = $("#skill_nameid option:selected").text();
                $('#addskilldiv').append('<li id="s' + skillId + '"> <span> ' + skilltext + '</span> <div class="animate-show"><a href="javascript:void();" onclick="deleteSkill(' + skillId + ')" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a> </div></li>');
                $('#pl_skillsctn').show();
                $.ajax({
                    url: "{!! HTTP_PATH !!}/users/updatedata",
                    type: "POST",
                    dataType: 'json',
                    data: {"skill_ids": $('#skill_nameid').val(), _token: '{{csrf_token()}}'},
                });
            }
        });
        $("#edubtn").click(function () {
            if ($("#updateother").valid()) {
                $('#div_educationctn').hide();
                var countryName = $("#country_nameid").val();
                var qualName = $("#qual_nameid").val();

                if ($('#edu_old').val()) {
                    var sl = $('#edu_old').val();
                    $('#' + $('#edu_old').val()).html('<div class="edutop"><span class="title">' + qualName + '</span><span class="sub-title">' + $('#stream_nameid').val() + '</span> <span class="hint--top"><a href="javascript:void();" onclick="editEdu(\'' + sl + '\',\'' + countryName + '\',\'' + $('#university_nameid').val() + '\',\'' + qualName + '\',\'' + $('#stream_nameid').val() + '\',\'' + $('#yearid').val() + '\')" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a> </span> <span class="hint--top"><a href="javascript:void();" onclick="deleteEdu(\'' + sl + '\')" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a></span></div><div class="edu_btm"><span class="title"> ' + $('#university_nameid').val() + ', ' + countryName + ', Pass in ' + $('#yearid').val() + '</span></div>');
                } else {
                    var sl = $('#stream_nameid').val().replace(" ", "_").toLowerCase();
                    $('#addedudiv').append('<li id="' + sl + '"><div class="edutop"><span class="title">' + qualName + '</span><span class="sub-title">' + $('#stream_nameid').val() + '</span> <span class="hint--top"><a href="javascript:void();" onclick="editEdu(\'' + sl + '\',\'' + countryName + '\',\'' + $('#university_nameid').val() + '\',\'' + qualName + '\',\'' + $('#stream_nameid').val() + '\',\'' + $('#yearid').val() + '\')" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a> </span> <span class="hint--top"><a href="javascript:void();" onclick="deleteEdu(\'' + sl + '\')" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a></span></div><div class="edu_btm"><span class="title"> ' + $('#university_nameid').val() + ', ' + countryName + ', Pass in ' + $('#yearid').val() + '</span></div></li>');
                }
                $('#pl_educationctn').show();
                $.ajax({
                    url: "{!! HTTP_PATH !!}/users/updatedata",
                    type: "POST",
                    dataType: 'json',
                    data: {"country_name": countryName, "university_name": $('#university_nameid').val(), "qual_name": qualName, "stream_name": $('#stream_nameid').val(), "year": $('#yearid').val(), "edu_old": $('#edu_old').val(), _token: '{{csrf_token()}}'},
                });
            }
        });
        $("#certbtn").click(function () {
            if ($("#updateother").valid()) {
                $('#div_certificationctn').hide();
                if ($('#cert_old').val()) {
                    var sl = $('#cert_old').val();
                    $('#' + $('#cert_old').val()).html('<div class="edutop"><span class="title">' + $('#certificate_nameid').val() + '</span> <span class="hint--top"><a href="javascript:void();" onclick="editCert(\'' + sl + '\',\'' + $('#certificate_nameid').val() + '\',\'' + $('#certificate_fromid').val() + '\',\'' + $('#yearcertid').val() + '\')" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a> </span> <span class="hint--top"><a href="javascript:void();" onclick="deleteCert(\'' + sl + '\')" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a></span></div><div class="edu_btm"><span class="title"> ' + $('#certificate_fromid').val() + ', Pass in ' + $('#yearcertid').val() + '</span></div>');
                } else {
                    var sl = $('#certificate_nameid').val().replace(" ", "_").toLowerCase();
                    $('#addcertdiv').append('<li id="' + sl + '"><div class="edutop"><span class="title">' + $('#certificate_nameid').val() + '</span> <span class="hint--top"><a href="javascript:void();" onclick="editCert(\'' + sl + '\',\'' + $('#certificate_nameid').val() + '\',\'' + $('#certificate_fromid').val() + '\',\'' + $('#yearcertid').val() + '\')" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a> </span> <span class="hint--top"><a href="javascript:void();" onclick="deleteCert(\'' + sl + '\')" title="Delete"><i class="fa fa-trash-o" aria-hidden="true"></i></a></span></div><div class="edu_btm"><span class="title"> ' + $('#certificate_fromid').val() + ', Pass in ' + $('#yearcertid').val() + '</span></div></li>');
                }
                $('#pl_certificationctn').show();
                $.ajax({
                    url: "{!! HTTP_PATH !!}/users/updatedata",
                    type: "POST",
                    dataType: 'json',
                    data: {"certificate_name": $('#certificate_nameid').val(), "certificate_from": $('#certificate_fromid').val(), "year": $('#yearcertid').val(), "cert_old": $('#cert_old').val(), _token: '{{csrf_token()}}'},
                });
            }
        });

        $("#maraction").click(function () {
            $("#offer-show").addClass("offer-div");
            $(".dashboard-rights-section").removeClass("offer-div");
        });
    });
</script>

<script>
    $(document).ready(function () {
        $(".maraction").click(function () {
            thisid = this.id;
            var id = thisid.split('-');
            $("#offer-show-"+id[1]).addClass("offer-div");
//            $(".dashboard-rights-section").removeClass("offer-div");
        });
        $(".clsstng").click(function () {
            thisid = this.id;
            var id = thisid.split('-');
            $("#offer-show-"+id[1]).removeClass("offer-div");
        });
    });
</script>
@endsection