@extends('layouts.merchant_inner')
@section('content')
<?php
use App\Models\Category;
?>
{{ HTML::script('public/js/ckeditor/ckeditor.js')}}


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
        
        $('#city_id').change(function () {
           
            var city_id = $("#city_id").val();
            //alert(city_id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if(city_id > 0){
                $.ajax({
                    type: "POST",
                    url: '<?php echo HTTP_PATH; ?>/merchant/users/get_localities',
                    data: {'city_id': city_id},
                    success: function (result) {
                        // alert(result);
                        $('#locality_id').html(result);
    
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                        $('#loaderID').hide();
                    }
                });
            } else{
                $('#locality_id').html('<option select="selected" value="">Select locality</option>');
            }
                
        });

    });
</script>
<section class="listing_deal">
    <div class="container">


        <div class="panel panel-default">
            <div class="row"> 
                <div class="col-md-3">
                    @include('elements.merchant_left_menu')
                </div>
                <div class="col-md-9">
                    <div class="panel-body">
                        <div class="tab-pane" id="2">
                            <div class="add_deal">
                                <div class="informetion_top profile-info-my-account">
                                    <div class="tatils_0t1"> My Profile<div class="add-list">
                                      <a href="{{ URL::to( 'merchant/users/editprofile')}}"><i class="fa fa-edit"></i>Edit Profile</a>
                            		   
                                    </div>
                                    </div>
                                     <div class="er_mes">
                                    <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
                                </div>
                                   {{Form::model($recordInfo, ['method' => 'post', 'class' => 'form form-signin', 'id' => 'loginform','enctype' => "multipart/form-data"]) }}    
                                    
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Business Name </label>
                                        <div class="col-sm-9">
                                            {{$recordInfo->busineess_name}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Business Category </label>
                                        <div class="col-sm-9">
                                            {{isset($recordInfo->BusinessType->category_name)?$recordInfo->BusinessType->category_name:"N/A"}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                          <label class="col-sm-3 control-label">Sub Categories(Services) </label>
                                          <div class="col-sm-9" >
                                              
                                               <?php if(isset($recordInfo->service_ids) && $recordInfo->service_ids!='' && $recordInfo->service_ids!=0 ){
                    
                                                      $convert_to_array = explode(",", $recordInfo->service_ids);
                                                      $s_nam_arr = array();
                                                     foreach($convert_to_array as $convert_to_arr){
                                                         $s_nam_arr[] = isset($allservices[$convert_to_arr])?$allservices[$convert_to_arr]:"";
                                                     }
                                                     echo implode(',',array_filter($s_nam_arr));
                                                    
                                                      }?>
                                      </div>
                                  </div>
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Address </label>
                                        <div class="col-sm-9">
                                           {{$recordInfo->address}}
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Zipcode </label>
                                        <div class="col-sm-9">
                                           {{$recordInfo->zipcode}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Country </label>
                                        <div class="col-sm-9">
											{{isset($country[$recordInfo->country_id])?$country[$recordInfo->country_id]:"N/A"}}
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-3 control-label">State </label>
                                        <div class="col-sm-9">
											{{isset($state[$recordInfo->state_id])?$state[$recordInfo->state_id]:"N/A"}}
                                        </div>
                                    </div>
                                    
									<div class="form-group">
                                        <label class="col-sm-3 control-label">City </label>
                                        <div class="col-sm-9">
											{{isset($city[$recordInfo->city_id])?$city[$recordInfo->city_id]:"N/A"}}
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Locality </label>
                                        <div class="col-sm-9">
                                            {{isset($locality[$recordInfo->locality_id])?$locality[$recordInfo->locality_id]:"N/A"}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">Contact Number </label>
                                        <div class="col-sm-9">
                                            {{$recordInfo->contact}}
                                        </div>
                                    </div>
									
									
									<?php /**/ if($week_days){?>	<div class="form-group">
                                        <label class="col-sm-3 control-label">Hours of operations </label>
                                        <div class="col-sm-9">
											@foreach($week_days as $day_key=> $day_val)
												<div class="day-box day-box-prfile">
													<div class="day-box-day">
														<?php $day_time_from= $day_key.'_time_from'; $day_time_to= $day_key.'_time_to';?>
														<label>{{ $day_val }}</label>
														@php
														if ($recordInfo->$day_time_from && $recordInfo->$day_time_to){ 
															$is_day_show= 1;
														}else{
															$is_day_show= 0;
														}
														@endphp
													</div>
													<div class="abs-select">
													    @if($is_day_show==1) {{isset($time_array[$recordInfo->$day_time_from])?$time_array[$recordInfo->$day_time_from]:'OFF'}} @else {{'OFF'}} @endif
													</div>
													<div class="abs-select">
													    @if($is_day_show==1) {{isset($time_array[$recordInfo->$day_time_to])?$time_array[$recordInfo->$day_time_to]:'OFF'}} @else {{'OFF'}} @endif
													</div>
												</div>
											@endforeach
										</div>
                                    </div><?php } /**/ ?>
									<div class="form-group">
                                        <label class="col-sm-3 control-label">About Us </label>
                                        <div class="col-sm-9">
											{{$recordInfo->about_us?$recordInfo->about_us:'N/A'}}
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Facebook Link <span class="require"></span></label>
                                        <div class="col-sm-9">
                                            {{$recordInfo->facebook_link?$recordInfo->facebook_link:'N/A'}}
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Instagram  Link <span class="require"></span></label>
                                        <div class="col-sm-9">
                                            {{$recordInfo->instagram_link?$recordInfo->instagram_link:'N/A'}}
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Linkedin Link <span class="require"></span></label>
                                        <div class="col-sm-9">
                                            {{$recordInfo->linkedin_link?$recordInfo->linkedin_link:'N/A'}}
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Twitter Link <span class="require"></span></label>
                                        <div class="col-sm-9">
                                            {{$recordInfo->twitter_link?$recordInfo->twitter_link:'N/A'}}
                                        </div>
                                    </div>
									
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Youtube Link <span class="require"></span></label>
                                        <div class="col-sm-9">
                                            {{$recordInfo->youtube_link?$recordInfo->youtube_link:'N/A'}}
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Name</label>
                                        <div class="col-sm-9">
                                            {{$recordInfo->name?$recordInfo->name:'N/A'}}
                                        </div>
                                    </div>
									
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Email Address</label>
                                        <div class="col-sm-9">
                                            {{$recordInfo->email_address?$recordInfo->email_address:'N/A'}}
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-3 control-label">Images <span class="require"></span></label>
                                        <div class="col-sm-9">
                                            <span class="help-text"> Supported File Types: jpg, jpeg, png (Max. 2MB). You can select multiple images by pressing CTRL key.</span>
                                            <div id="showimg" class="showimages_mul"></div>
                                            <div class="ImageID">
                                                <?php
                                                $image = $recordInfo['profile_image'];
                                                $images = explode(',', $image);
                                                ?>
                                                @if(array_filter($images))
                                                @foreach($images as $image)
                                                <div class="show-ad">
                                                <div class="showeditimage">{{HTML::image(MERCHANT_FULL_DISPLAY_PATH.$image, SITE_TITLE,['style'=>"height: 100px; max-width: 100px;display:inline-block;"])}}</div>
                                                <div class="ad_s ajshort">
                                                </div>
                                                </div>
                                                @endforeach
                                                @endif
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
