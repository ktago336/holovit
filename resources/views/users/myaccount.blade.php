@extends('layouts.inner')
@section('content')
<?php
use App\Models\Service;
?>
<meta name="csrf-token" content="{{ csrf_token() }}">
{{ HTML::script('public/js/ckeditor/ckeditor.js')}}
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
 
 $(document).ready(function () {
        $("#add_images").on('change', function () {
            //Get count of selected files
            var countFiles = $(this)[0].files.length;
            if (countFiles > 3) {
                alert('You can upload maximum 3 images.');
                $("#add_images").val('');
                $(".showimages_mul").html('');
                return;
            }

            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $("#showimg");
            image_holder.empty();
            if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
                if (typeof (FileReader) != "undefined") {

                    //loop for each file selected for uploaded.
                    for (var i = 0; i < countFiles; i++) {

                        if ($(this)[0].files[i].size > 2097152) {
                            alert($(this)[0].files[i].name + " is more than 2MB.");
                            $("#add_images").val('');
                            $(".showimages_mul").html('');
                            break;
                            continue;
                        } else {
                            var reader = new FileReader();
                            reader.onload = function (e) {
                                $("<img />", {
                                    "src": e.target.result,
                                    "class": "thumb-image"
                                }).appendTo(image_holder);
                            }

                            image_holder.show();
                            reader.readAsDataURL($(this)[0].files[i]);
                        }
                    }

                } else {
                    alert("This browser does not support FileReader.");
                }
            } else {
                alert("Please select only images");
            }
        });

    });
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(function () {
        $("#expire_date").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            dateFormat: 'yy-mm-dd',
            numberOfMonths: 1,
            minDate: 'mm-dd-yyyy',
            //maxDate:'mm-dd-yyyy',
            changeYear: true,
            onClose: function (selectedDate) {
                if (selectedDate) {
                    $("#expire_date").datepicker("option", "", selectedDate);}
            }
        });

    });

</script>
<script type="text/javascript">
    function get_product(value) {
        if (value == '0') {
            document.getElementById('product_ids').style.display = "block";
        } else {
            document.getElementById('product_ids').style.display = "none";
        }
    }
	function showhidetime(value) {
        
        $.each($("input[name='" + value + "']:checked"), function () {
            $("#"+value+"_time_from").show();
            $("#"+value+"_time_to").show();
        });
        $.each($("input[name='" + value + "']:unchecked"), function () {
            $("#"+value+"_time_from").val('');
            $("#"+value+"_time_to").val('');
            $("#"+value+"_time_from").hide();
            $("#"+value+"_time_to").hide();
        });
    }
</script>
<style>
  .showatr {
width: 100%;
}
.sizes-drop {
background: #ffffff;
border: 1px solid #e9e8e8;
position: absolute;
width: 96.5%;
z-index: 1;
}
.crooss span {
display: inline-block;
padding: 2px;
line-height: 10px;
color: #fff;
cursor: pointer;
position: absolute;
right: -8px;
top: -7px;
background: #f00;
border-radius: 50%;
width: 20px;
height: 20px;
text-align: center;
line-height: 17px;
font-weight: bold;
}
.sizemorescroll {
background: #f6f6f6;
max-height: 200px;
overflow-x: hidden;
overflow-y: auto;
padding: 0;
width: 100%;
z-index: 1;
}
.cloth_size {
float: left;
width: 100%;
}
.des_box_cont.test-size {
border-bottom: 1px solid #ddd;
margin-bottom: 0;
padding: 4px 10px;
}
.des_box_cont.test-size input[type="checkbox"] {
vertical-align: middle;
margin: -3px 5px 0 0;
}
.des_box_cont.test-size label {
margin: 0;
font-weight: normal;
vertical-align: top; color: #797979
}
.newstyle
{
display: block!important;
width:100%!important;
}
.servicenotavailable label{
  color:#9e9b9b!important;
}

  </style>
<?php
$future_ids=array();
?>
<section class="listing_deal">
    <div class="container">


        <div class="panel panel-default">
            <div class="row"> 
                <div class="col-md-3">
                    @include('elements.left_menu')
                </div>
                <div class="col-md-9">
                    <div class="panel-body">
                        <div class="tab-pane" id="2">
                            <div class="add_deal add_deal_my">
                                <div class="informetion_top">
                                    <div class="tatils_0t1"> My Profile<div class="add-list">
                                      <a href="{{ URL::to( 'users/editprofile')}}"><i class="fa fa-edit"></i>Edit Profile</a>
                            		   
                                    </div></div>
                                     <div class="er_mes">
                                    <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
                                </div>
                                   {{Form::model($recordInfo, ['method' => 'post', 'class' => 'form form-signin', 'id' => 'loginform','enctype' => "multipart/form-data"]) }}    
                                    
									
									<div class="form-group">
                                        <label class="col-sm-3 control-label">First Name<span class="require"></span></label>
                                        <div class="col-sm-9">
                                        <div class="my-ad">
                                            {{$recordInfo->first_name}}
                                        </div>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Last Name<span class="require"></span></label>
                                        <div class="col-sm-9">
                                            <div class="my-ad">
                                            {{$recordInfo->last_name}}
                                        </div>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Email Address<span class="require"></span></label>
                                        <div class="col-sm-9">
                                            <div class="my-ad">
                                            {{$recordInfo->email_address}}
                                        </div>
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Contact Number <span class="require"></span></label>
                                        <div class="col-sm-9">
                                            <div class="my-ad">
                                            {{$recordInfo->contact}}
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
        </div>
    </div>
</section>
<script>

  function changeCount(aid, aname){
    // console.log("id: "+aid);
    if($("#StrategyAsset"+aid).is(':checked')){
        var count=$("#ProductTotalSize").data("count");
        count++;
        $("#ProductTotalSize").data("count",count);
        $("#ProductTotalSize").val(count+' services selected');
    }else{
      
        var count=$("#ProductTotalSize").data("count");
        count--;
        $("#ProductTotalSize").data("count",count);
        $("#ProductTotalSize").val(count+' services selected');
    }
}   
</script>
@endsection
