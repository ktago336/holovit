<?php $__env->startSection('content'); ?>
<?php
use App\Models\Category;
?>
<script type="text/javascript">
  $(document).ready(function () {
      $.validator.addMethod("alphanumeric", function(value, element) {
          return this.optional(element) || /^[\w. ]+$/i.test(value);
      }, "Only letters, numbers and underscore allowed.");
      $.validator.addMethod("validname", function(value, element) {
            return this.optional(element) || /^[\w. ]+$/i.test(value);
        }, "Only letters, numbers, space and underscore allowed.");
      $.validator.addMethod("passworreq", function (input) {
          var reg = /[0-9]/; //at least one number
          var reg2 = /[a-z]/; //at least one small character
          var reg3 = /[A-Z]/; //at least one capital character
          //var reg4 = /[\W_]/; //at least one special character
          return reg.test(input) && reg2.test(input) && reg3.test(input);
      }, "Password must be a combination of Numbers, Uppercase & Lowercase Letters.");
      $("#adminForm").validate();
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

  </style>
  
  
  <script type="text/javascript">
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
            if(country_id > 0){
                $.ajax({
                    type: "POST",
                    url: '<?php echo HTTP_PATH; ?>/merchant/users/add_states',
                    data: {'country_id': country_id},
                    success: function (result) {
                        // alert(result);
                        $('#state_id').html(result);
                        $('#city_id').html("<option value=''>Select City</option>");
                        $('#locality_id').html("<option value=''>Select Locality</option>");
                       // $('.subcategory_id').show();
    
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                        $('#loaderID').hide();
                    }
                });
            }else{
                $('#state_id').html("<option value=''>Select State</option>");
                $('#city_id').html("<option value=''>Select City</option>");
                $('#locality_id').html("<option value=''>Select Locality</option>");
            }
                
        });
		
		
		$('#state_id').change(function () {
            //alert('sdfsd');
            var state_id = $("#state_id").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if(state_id > 0){
                $.ajax({
                    type: "POST",
                    url: '<?php echo HTTP_PATH; ?>/merchant/users/add_cities',
                    data: {'state_id': state_id},
                    success: function (result) {
                        // alert(result);
                        $('#city_id').html(result);
                        $('#locality_id').html("<option value=''>Select Locality</option>");
    
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                        $('#loaderID').hide();
                    }
                });
            }else{
                $('#city_id').html("<option value=''>Select City</option>");
                $('#locality_id').html("<option value=''>Select Locality</option>");
            }
                
        });
        
        $('#city_id').change(function () {
            //alert('sdfsd');
            var city_id = $("#city_id").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            if(city_id > 0){
                $.ajax({
                    type: "POST",
                    url: '<?php echo HTTP_PATH; ?>/merchant/users/add_localities',
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
            }else{
                $('#locality_id').html("<option value=''>Select Locality</option>");
            }
                
        });
        
        
		});
		</script>
<?php
$future_ids=array();
?>

<div class="content-wrapper">
  <section class="content-header">
      <h1>Edit Merchant</h1>
      <ol class="breadcrumb">
          <li><a href="<?php echo e(URL::to('admin/admins/dashboard')); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
          <li><a href="<?php echo e(URL::to('admin/admins/merchant')); ?>"><i class="fa fa-users"></i> <span> Merchants</span></a></li>
          <li class="active"> Edit Merchant</li>
      </ol>
  </section>
  <section class="content">
      <div class="box box-info">
          <div class="box-header with-border">
              <h3 class="box-title">&nbsp;</h3>
          </div>
          <div class="ersu_message"><?php echo $__env->make('elements.admin.errorSuccessMessage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?></div>
          <?php echo e(Form::model($recordInfo, ['method' => 'post', 'id' => 'adminForm', 'enctype' => "multipart/form-data"])); ?>            
          <div class="form-horizontal">
              <div class="box-body">
                   
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('name', null, ['class'=>'form-control required validname', 'placeholder'=>'Name', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Address <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('address', null, ['class'=>'form-control required', 'placeholder'=>'Address', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Contact Number <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('contact', null, ['class'=>'form-control required digits', 'placeholder'=>'Contact Number', 'autocomplete' => 'off', 'minlength' => 8, 'maxlength' => 16])); ?>

                        </div>
                    </div>
                        
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Zip code <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('zipcode', null, ['class'=>'form-control required', 'maxlength'=>10, 'placeholder'=>'Zip Code', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Country <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::select('country_id', $country, null,['id'=>'country_id', 'class'=>'form-control required', 'placeholder'=>'Select Country', 'autocomplete'=>'OFF'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">State <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::select('state_id',$state, null, ['id'=>'state_id', 'class'=>'form-control required', 'placeholder'=>'Select State', 'autocomplete'=>'OFF'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">City <span class="require">*</span></label>
                        <div class="col-sm-10">
							<?php echo e(Form::select('city_id',$city, null, ['id'=>'city_id', 'class'=>'form-control required', 'placeholder'=>'Select City', 'autocomplete'=>'OFF'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Locality <span class="require">*</span></label>
                        <div class="col-sm-10">
							<?php echo e(Form::select('locality_id',$locality, null, ['id'=>'locality_id', 'class'=>'form-control required', 'placeholder'=>'Select Locality', 'autocomplete'=>'OFF'])); ?>

                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Select Service <span class="require">*</span></label>
                        <div class="col-sm-10">
							<?php
                              $vall = '';
                              $cnt=0;
                              $oldAssets = array();
                              $convert_to_array=array();
                              // print_r($appointment->service_ids);
                              if(isset($recordInfo->service_ids) && $recordInfo->service_ids!='' && $recordInfo->service_ids!=0 ){

                                  $convert_to_array = explode(",", $recordInfo->service_ids);
                                 
                                  //$selectedservicesbystaf=Category::whereIn('id', $convert_to_array)->where(['is_deleted'=>0, 'parent_id'=>$recordInfo->business_type])->get();
                                  $selectedservicesbystaf=Category::whereIn('id', $convert_to_array)->where(['parent_id'=>$recordInfo->business_type])->get();
                                   //$cnt=count( $convert_to_array);
                                  //$vall = count( $convert_to_array).' service selected';
                                   $cnt=count($selectedservicesbystaf);
                                   $vall = count($selectedservicesbystaf).' service selected';
                                  $oldAssets = $recordInfo->service_ids;
                              }
                              ?>
                                <div id="size-filter" class="showatr" style="float:left;">
                                  <input type="hidden" id="isSelect" value="0">
                                  <input type="text" id="ProductTotalSize" placeholder="Select Service" readonly="readonly" autocomplete="off" onclick="$('#sizes-dropdown').toggle();" class="form-control required" name="service_ids[]" value="<?php echo $vall;?>" data-count="<?php echo $cnt; ?>">
                                  <div class="sizes-drop" style="display: none;" id="sizes-dropdown">
                                      <div class="crooss">
                                          <span class="astclose" onclick="$('#sizes-dropdown').toggle();" >X</span>
                                      </div>
                                      <div class="sizemorescroll">
                                          <div class="cloth_size"> 
                                              <?php if($allservices){
                                                  $assetArray = array();
                                                  foreach($allservices as $service){
                                                      $aid = $service->id;
                                                      $aname = $service->category_name;
                                                      $assetArray[$aid] = $aname;
                                                      $aname = str_replace('"', '', $aname);
                                                      $checked = '';
                                                      if(in_array($aid, $convert_to_array)){
                                                          $checked = 'checked';
                                                      }
                                                      $maxq = 40;//$catquestion[$key];
                                                      // if($service->is_deleted){
                                                      //   $checked=''; 
                                                      // }
                                                      $disableclass='';
                                                      if($checked!='' && in_array($aid, $future_ids)){
                                                        $disableclass='disabled';
                                                      }
                                                      $class='';
                                                      if($service->is_deleted==1 && $checked==''){
                                                          continue;
                                                        }else{
                                                          if($service->is_deleted){
                                                            $class='servicenotavailable';
                                                          }
                                                        }
                                                      ?>
                                                      <div class="des_box_cont test-size connect-cat newstyle <?php echo $class; ?>"><input onclick="changeCount(<?php echo $aid;?>,'<?php echo $aname;?>', '<?php echo $maxq;?>')" <?php echo $checked;?> type="checkbox" id="StrategyAsset<?php echo $aid;?>" value="<?php echo $aid;?>" name="service_ids[]" <?php echo $disableclass;?> >
                                                        <?php
                                                          if($disableclass!=''){
                                                        ?>
                                                          <input  <?php echo $checked;?> type="checkbox" value="<?php echo $aid;?>" name="service_ids[]" style="display:none">
                                                        <?php
                                                          }  
                                                        ?>
                                                        <label class=" "><?php echo $assetArray[$aid];?></label><?php if($class!=''){?><i class="fa fa-info-circle" aria-hidden="true" title="This service is deleted."></i><?php }else{ if($disableclass!=''){?><i class="fa fa-info-circle" aria-hidden="true" title="This service is already booked for future date so you can't remove this service."></i><?php } } ?></div>
                                                  <?php }
                                              } ?>
                                          </div>
    
                                  </div>
                              </div>
                          </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Hours of operations <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php $__currentLoopData = $week_days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day_key=> $day_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<div class="day-box">
									<div class="day-box-day">
										<?php $day_time_from= $day_key.'_time_from'; $day_time_to= $day_key.'_time_to';?>
										<input type="checkbox" name="<?php echo e($day_key); ?>" value="<?php echo e($day_val); ?>" onchange="showhidetime('<?php echo e($day_key); ?>')" 
										<?php if($recordInfo->$day_time_from && $recordInfo->$day_time_to): ?> 
											<?php echo e('checked'); ?>

										<?php endif; ?>
										><label><?php echo e($day_val); ?></label>
										<?php
										if ($recordInfo->$day_time_from && $recordInfo->$day_time_to){ 
											$is_day_show= "display:block";
										}else{
											$is_day_show= "display:none";
										}
										?>
									</div>
									<div class="abs-select">
									<?php echo e(Form::select($day_key.'_time_from', $time_array,null, ['class' => 'form-control required','placeholder' => 'Time From','id' => $day_key.'_time_from','style'=>$is_day_show])); ?>

									</div>
									<div class="abs-select">
									<?php echo e(Form::select($day_key.'_time_to', $time_array,null, ['class' => 'form-control required','placeholder' => 'Time To','id' => $day_key.'_time_to','style'=>$is_day_show])); ?>

									</div>
								</div>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">About Us <span class="require">*</span></label>
                        <div class="col-sm-10">
							<?php echo e(Form::textarea('about_us', null, ['class'=>'form-control required', 'placeholder'=>'About Us', 'autocomplete' => 'off', 'rows'=>4])); ?>

                        </div>
                    </div>
                    


                    <!--                    <div class="form-group">
                                            <label class="col-sm-2 control-label">Profile Image <span class="require">*</span></label>
                                            <div class="col-sm-10">
                                                <?php echo e(Form::file('profile_image', ['class'=>'form-control required', 'accept'=>IMAGE_EXT])); ?>

                                                <span class="help-text"> Supported File Types: jpg, jpeg, png (Max. <?php echo e(MAX_IMAGE_UPLOAD_SIZE_DISPLAY); ?>).</span>
                                            </div>
                                        </div>-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Business Name <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('busineess_name', null, ['class'=>'form-control required', 'placeholder'=>'Business Name', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Facebook Link <span class="require"></span></label>
                        <div class="col-sm-10">
							<?php echo e(Form::text('facebook_link', null, ['class'=>'form-control url', 'placeholder'=>'Facebook link', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Instagram Link <span class="require"></span></label>
                        <div class="col-sm-10">
							<?php echo e(Form::text('instagram_link', null, ['class'=>'form-control url', 'placeholder'=>'Instagram link', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Linkedin Link <span class="require"></span></label>
                        <div class="col-sm-10">
							<?php echo e(Form::text('linkedin_link', null, ['class'=>'form-control url', 'placeholder'=>'Linkedin link', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Twitter Link <span class="require"></span></label>
                        <div class="col-sm-10">
							<?php echo e(Form::text('twitter_link', null, ['class'=>'form-control url', 'placeholder'=>'Twitter link', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Youtube Link <span class="require"></span></label>
                        <div class="col-sm-10">
							<?php echo e(Form::text('youtube_link', null, ['class'=>'form-control url', 'placeholder'=>'Youtube link', 'autocomplete' => 'off'])); ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Email Address <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::text('email_address', null, ['class'=>'form-control required email', 'placeholder'=>'Email Address', 'autocomplete' => 'off', 'readonly'=>'readonly'])); ?>

                        </div>
                    </div>
                    
                  <div class="form-group">
                      <label class="col-sm-2 control-label">Password <span class="require"></span></label>
                      <div class="col-sm-10">
                          <?php echo e(Form::password('password', ['class'=>'form-control', 'placeholder' => 'Password', 'minlength' => 8, 'id'=>'password'])); ?>

                          <span class="help-text"> * Note: If You want to change Merchant's password, only then fill password below otherwise leave it blank. </span>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-2 control-label">Confirm Password <span class="require"></span></label>
                      <div class="col-sm-10">
                          <?php echo e(Form::password('confirm_password', ['class'=>'form-control', 'placeholder' => 'Confirm Password', 'equalTo' => '#password'])); ?>

                      </div>
                  </div>
                 <div class="form-group">
                        <label class="col-sm-2 control-label">Images <span class="require"></span></label>
                        <div class="col-sm-10">
                            <?php echo e(Form::file('profile_image[]', ['class'=>'form-control', 'accept'=>IMAGE_EXT,'multiple'=>true,'id' => 'add_images'])); ?>

                            <span class="help-text"> Supported File Types: jpg, jpeg, png (Max. 2MB).</span>
                            <div id="showimg" class="showimages_mul"></div>
                            <div class="ImageID">
                                <?php
                                $image = $recordInfo['profile_image'];
                                $images = explode(',', $image);
                                ?>
                                <?php if(array_filter($images)): ?>
                                <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="show-ad">
                                <div class="showeditimage"><?php echo e(HTML::image(MERCHANT_FULL_DISPLAY_PATH.$image, SITE_TITLE,['style'=>"height: 100px; max-width: 100px;display:inline-block;"])); ?></div>

                                <div class="ad_s ajshort"><a href="<?php echo e(URL::to('admin/admins/deleteimagemerchant/'.$recordInfo['slug'].'/'.$image)); ?>" title="Cancel" class="canlcel_le"  onclick="return confirm('Are you sure you want to delete?')">X</a>
                                </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                  <div class="box-footer">
                      <label class="col-sm-2 control-label" for="inputPassword3">&nbsp;</label>
                      <?php echo e(Form::submit('Submit', ['class' => 'btn btn-info'])); ?>

                      <a href="<?php echo e(URL::to( 'admin/admins/merchant')); ?>" title="Cancel" class="btn btn-default canlcel_le">Cancel</a>
                  </div>
              </div>
          </div>
          <?php echo e(Form::close()); ?>

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
  <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/holovitr/domains/holovit.ru/public_html/resources/views/admin/users/editmerchant.blade.php ENDPATH**/ ?>