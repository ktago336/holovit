@extends('layouts.admin')
@section('content')
<script type="text/javascript">
  $(document).ready(function () {
      $.validator.addMethod("alphanumeric", function(value, element) {
          return this.optional(element) || /^[\w. ]+$/i.test(value);
      }, "Only letters, numbers and underscore allowed.");
      $.validator.addMethod("passworreq", function (input) {
          var reg = /[0-9]/; //at least one number
          var reg2 = /[a-z]/; //at least one small character
          var reg3 = /[A-Z]/; //at least one capital character
          //var reg4 = /[\W_]/; //at least one special character
          return reg.test(input) && reg2.test(input) && reg3.test(input);
      }, "Password must be a combination of Numbers, Uppercase & Lowercase Letters.");
      $("#adminForm").validate();
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

<div class="content-wrapper">
  <section class="content-header">
      <h1>Edit Merchant</h1>
      <ol class="breadcrumb">
          <li><a href="{{URL::to('admin/admins/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
          <li><a href="{{URL::to('admin/admins/merchant')}}"><i class="fa fa-users"></i> <span> Merchant</span></a></li>
          <li class="active"> Edit Merchant</li>
      </ol>
  </section>
  <section class="content">
      <div class="box box-info">
          <div class="box-header with-border">
              <h3 class="box-title">&nbsp;</h3>
          </div>
          <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
          {{Form::model($recordInfo, ['method' => 'post', 'id' => 'adminForm', 'enctype' => "multipart/form-data"]) }}            
          <div class="form-horizontal">
              <div class="box-body">
                  <div class="form-group">
                      <label class="col-sm-2 control-label">First Name <span class="require">*</span></label>
                      <div class="col-sm-10">
                          {{Form::text('first_name', null, ['class'=>'form-control required', 'placeholder'=>'First Name', 'autocomplete' => 'off'])}}
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-2 control-label">Last Name <span class="require">*</span></label>
                      <div class="col-sm-10">
                          {{Form::text('last_name', null, ['class'=>'form-control required', 'placeholder'=>'Last Name', 'autocomplete' => 'off'])}}
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-sm-2 control-label">Contact Number <span class="require">*</span></label>
                      <div class="col-sm-10">
                          {{Form::text('contact', null, ['class'=>'form-control required', 'placeholder'=>'Contact Number', 'autocomplete' => 'off'])}}
                      </div>
                  </div>
                  
                  <div class="form-group">
                      <label class="col-sm-2 control-label">Profile Image <span class="require"></span></label>
                      <div class="col-sm-10">
                          {{Form::file('profile_image', ['class'=>'form-control required', 'accept'=>IMAGE_EXT])}}
                          <span class="help-text"> Supported File Types: jpg, jpeg, png (Max. {{ MAX_IMAGE_UPLOAD_SIZE_DISPLAY }}).</span>
                          @if($recordInfo->profile_image != '')
                          <div class="showeditimage">{{HTML::image(PROFILE_SMALL_DISPLAY_PATH.$recordInfo->profile_image, SITE_TITLE,['style'=>"max-width: 200px"])}}</div>
                          <div class="help-text"><a href="{{ URL::to('admin/admins/deleteimagemerchant/'.$recordInfo->slug)}}" title="Cancel" class="canlcel_le"  onclick="return confirm('Are you sure you want to delete?')">Delete Image</a></div>
                          @endif
                      </div>
                  </div>

                  <div class="form-group">
                      <label class="col-sm-2 control-label">Select Service <span class="require">*</span></label>
                      <div class="col-sm-10" >
                          <?php
                              $vall = '';
                              $cnt=0;
                              $oldAssets = array();
                              $convert_to_array=array();
                              // print_r($appointment->service_ids);
                              if(isset($recordInfo->service_ids) && $recordInfo->service_ids!='' && $recordInfo->service_ids!=0 ){

                                  $convert_to_array = explode(",", $recordInfo->service_ids);
                                  $cnt=count( $convert_to_array);
                                  $vall = count( $convert_to_array).' service selected';
                                  $oldAssets = $recordInfo->service_ids;
                              }
                          ?>
                          <div id="size-filter" class="showatr" style="float:left;">
                              <input type="hidden" id="isSelect" value="0">
                              <input type="text" id="ProductTotalSize" placeholder="Select Service" readonly="readonly" autocomplete="off" onclick="$('#sizes-dropdown').toggle();" class="form-control" name="service_ids[]" value="<?php echo $vall;?>" data-count="<?php echo $cnt; ?>">
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
                                                  $aname = $service->name;
                                                  $assetArray[$aid] = $aname;
                                                  $aname = str_replace('"', '', $aname);
                                                  $checked = '';
                                                  if(in_array($aid, $convert_to_array)){
                                                      $checked = 'checked';
                                                  }
                                                  $maxq = 40;//$catquestion[$key];
                                                  ?>
                                                  <div class="des_box_cont test-size connect-cat newstyle"><input onclick="changeCount(<?php echo $aid;?>,'<?php echo $aname;?>', '<?php echo $maxq;?>')" <?php echo $checked;?> type="checkbox" id="StrategyAsset<?php echo $aid;?>" value="<?php echo $aid;?>" name="service_ids[]"><label class=" "><?php echo $assetArray[$aid];?></label></div>
                                              <?php }
                                          } ?>
                                      </div>

                              </div>
                          </div>
                      </div>
                  </div>
              </div>                    

                  <div class="form-group">
                      <label class="col-sm-2 control-label">Password <span class="require"></span></label>
                      <div class="col-sm-10">
                          {{Form::password('password', ['class'=>'form-control', 'placeholder' => 'Password', 'minlength' => 8, 'id'=>'password'])}}
                          <span class="help-text"> * Note: If You want to change Merchant's password, only then fill password below otherwise leave it blank. </span>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-sm-2 control-label">Confirm Password <span class="require"></span></label>
                      <div class="col-sm-10">
                          {{Form::password('confirm_password', ['class'=>'form-control', 'placeholder' => 'Confirm Password', 'equalTo' => '#password'])}}
                      </div>
                  </div>
                 
                  <div class="box-footer">
                      <label class="col-sm-2 control-label" for="inputPassword3">&nbsp;</label>
                      {{Form::submit('Submit', ['class' => 'btn btn-info'])}}
                      <a href="{{ URL::to( 'admin/admins/merchant')}}" title="Cancel" class="btn btn-default canlcel_le">Cancel</a>
                  </div>
              </div>
          </div>
          {{ Form::close()}}
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