@extends('layouts.admin')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script type="text/javascript">
    $(document).ready(function () {
        $.validator.addMethod("discount", function (value, element) {
            return  this.optional(element) || (/^[0-9-]+$/.test(value));
        }, "Discount is not valid.");
        $.validator.addMethod("alphanumeric", function (value, element) {
            return this.optional(element) || /^[\w.]+$/i.test(value);
        }, "Only letters, numbers and underscore allowed.");
        $.validator.addMethod("passworreq", function (input) {
            var reg = /[0-9]/; //at least one number
            var reg2 = /[a-z]/; //at least one small character
            var reg3 = /[A-Z]/; //at least one capital character
            //var reg4 = /[\W_]/; //at least one special character
            return reg.test(input) && reg2.test(input) && reg3.test(input);
        }, "Password must be a combination of Numbers, Uppercase & Lowercase Letters.");

        $("#serviceForm").validate();
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
       var cat = $("#category_id").val();
  if(cat == ''){
      $('.subcategory_id').hide();
  }
   
  
  
   $('#category_id').change(function() {
       //alert('sdfsd');
   var cats = $("#category_id").val();
   $.ajaxSetup({
        				headers: {
        					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        				}
        			});
       $.ajax({
                type: "POST",
                url: '<?php echo HTTP_PATH; ?>/admin/deals/add_subcategory', 
                data: {'cats': cats},
                success: function (result) {
                // alert(result);
                    $('#subcategory_id').html(result);
                    $('.subcategory_id').show();
                
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    $('#loaderID').hide();
                }
            });
  });
   });

//for sub sub category
    $(document).ready(function () {
       var subcat = $("#subcategory_id").val();
  if(subcat == ''){
      $('.subsubcategory_id').hide();
  }
   
  
  
   $('#subcategory_id').change(function() {
       //alert('sdfsd');
   var subcats = $("#subcategory_id").val();
   $.ajaxSetup({
        				headers: {
        					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        				}
        			});
       $.ajax({
                type: "POST",
                url: '<?php echo HTTP_PATH; ?>/admin/deals/add_sub_subcategory', 
                data: {'subcats': subcats},
                success: function (result) {
                // alert(result);
                    $('#subsubcategory_id').html(result);
                    $('.subsubcategory_id').show();
                
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    $('#loaderID').hide();
                }
            });
  });
   });
   
   function getprice() {
        var price = $('#price').val();
        var discount = $('#discount').val();
        if (discount != '') {
             var disPrice = price - ((price * discount) / 100);
            $('#discount_price').val(disPrice);
        }
        else{
            $('#discount_price').val(price);
        }
   }
   
//limit for image upload
    $(document).ready(function () {
        $("#add_images").on('change', function () {
            //Get count of selected files
            var countFiles = $(this)[0].files.length;
            if (countFiles > 10) {
                alert('You can upload maximum 10 images.');
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
        
         $("#add_videos").on('change', function () {
           var countFiles = $(this)[0].files.length;
            if (countFiles > 2) {
                alert('You can upload maximum 2 videos.');
                $("#add_videos").val('');
                return;
            }
            
            var imgPath = $(this)[0].value;
            var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
            var image_holder = $("#showvideo");
            image_holder.empty();
            if (extn == "mp4" || extn == "3gp" || extn == "mov" || extn == "avi" || extn == "ogg") {
                if (typeof (FileReader) != "undefined") {

                    //loop for each file selected for uploaded.
                    for (var i = 0; i < countFiles; i++) {

                        if ($(this)[0].files[i].size > 5242880) {
                            alert($(this)[0].files[i].name + " is more than 5MB.");
                            $("#add_videos").val('');
                            //$(".showvideos_mul").html('');
                            break;
                            continue;
                        } 
                    }

                } else {
                    alert("This browser does not support FileReader.");
                }
            } else {
                alert("Please select only videos");
                $("#add_videos").val('');
            }
         });
    });
    
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	$( function() {
		$("#expire_date").datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 1,
            //minDate: 'mm-dd-yyyy',
            //maxDate:'mm-dd-yyyy',
            changeYear: true,
            onClose: function(selectedDate) {
            	if(selectedDate){$("#expire_date").datepicker("option", "", selectedDate);}
            }
        });

	} );
</script>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Add Deal</h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::to('admin/admins/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li><a href="{{URL::to('admin/deals')}}"><i class="fa fa-list"></i> <span> Deal</span></a></li>
            <li class="active"> Add Deal</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">&nbsp;</h3>
            </div>
            <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
            {{ Form::open(array('method' => 'post', 'id' => 'serviceForm', 'enctype' => "multipart/form-data")) }}
            <div class="form-horizontal">
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Select Merchant <span class="require">*</span></label>
                        <div class="col-sm-10">
                            <?php
//                            $merchants = DB::table('users')->where('status', '1','user_type', 'merchant')->pluck("CONCAT(first_name,' ', last_name) AS full_name", 'id');
//                            print_r($merchants);
                            ?>
                            {{Form::select('merchant_id', $merchant,null, ['class'=>'form-control required', 'placeholder'=>'Select Merchant'])}}
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Select Product <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::select('product_id[]', $product_name,null, ['class'=>'form-control input1','style' => 'width: 100% !important;height:130px;'.$class.'','id'=>'Newsletter','multiple'=>'multiple', 'placeholder'=>'Select Product'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Deal Name <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::text('name', null, ['class'=>'form-control required', 'placeholder'=>'Deal Name', 'autocomplete' => 'off'])}}
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Category <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::select('category_id', $category,null, ['class'=>'form-control required', 'placeholder'=>'Category','id'=>'category_id'])}}
                        </div>
                    </div>
                    <div class="form-group subcategory_id">
                        <label class="col-sm-2 control-label">Sub Category <span class="require"></span></label>
                        <div class="col-sm-10">
                            {{Form::select('subcategory_id', array(),null, ['class'=>'form-control', 'placeholder'=>'Sub Category','id'=>'subcategory_id'])}}
                        </div>
                    </div>
                     <div class="form-group subsubcategory_id">
                        <label class="col-sm-2 control-label">Sub Sub Category <span class="require"></span></label>
                        <div class="col-sm-10">
                            {{Form::select('subsubcategory_id', array(),null, ['class'=>'form-control ', 'placeholder'=>'Sub Sub Category','id'=>'subsubcategory_id'])}}
                        </div>
                    </div>
                     
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Brand <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::select('brand_id', $brand,null, ['class'=>'form-control required', 'placeholder'=>'Brand'])}}
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Location <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::select('location_id', $location,null, ['class'=>'form-control required', 'placeholder'=>'Location'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Address <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::text('address', null, ['class'=>'form-control required', 'placeholder'=>'Address', 'autocomplete' => 'off'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Price ({{CURR }})<span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::text('price', null, ['class'=>'form-control required number', 'min'=>'0', 'placeholder'=>'Price', 'id'=>'price', 'autocomplete' => 'off','onkeyup' => 'getprice()'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Discount<span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::text('discount', 0, ['class'=>'form-control required discount', 'min'=>'0','max'=>'99', 'placeholder'=>'Discount', 'id'=>'discount', 'autocomplete' => 'off','onkeyup' => 'getprice()'])}}
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Final Price ({{CURR }})<span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::text('final_price', 0, ['class'=>'form-control required final_price', 'min'=>'0', 'placeholder'=>'Final Price', 'id'=>'discount_price', 'autocomplete' => 'off','readonly'=>'readonly'])}}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::textarea('description', null, ['class'=>'form-control required', 'placeholder'=>'Enter Description', 'autocomplete' => 'off', 'rows'=>4])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Highlight <span class="require"></span></label>
                        <div class="col-sm-10">
                            {{Form::textarea('highlight', null, ['class'=>'form-control', 'placeholder'=>'Highlight', 'autocomplete' => 'off', 'rows'=>4])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Short Description <span class="require"></span></label>
                        <div class="col-sm-10">
                            {{Form::textarea('short_description', null, ['class'=>'form-control ', 'placeholder'=>'Short Description', 'autocomplete' => 'off', 'rows'=>4])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">More Description <span class="require"></span></label>
                        <div class="col-sm-10">
                            {{Form::textarea('more_description', null, ['class'=>'form-control', 'placeholder'=>'More Description', 'autocomplete' => 'off', 'rows'=>4])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Images <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::file('images[]', ['class'=>'form-control required', 'accept'=>IMAGE_EXT,'multiple'=>true,'id' => 'add_images'])}}
                            <span class="help-text"> Supported File Types: jpg, jpeg, png (Max. {{ MAX_IMAGE_UPLOAD_SIZE_DISPLAY }}).</span>
                            <div id="showimg" class="showimages_mul"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Videos <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::file('videos[]', ['class'=>'form-control', 'accept'=>VIDEO_EXT,'multiple'=>true,'id' => 'add_videos'])}}
                            <span class="help-text"> Supported File Types: mp4, 3gp, mov, avi, ogg (Max. {{ MAX_VIDEO_UPLOAD_SIZE_DISPLAY }}).</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Expiry Date <span class="require"></span></label>
                        <div class="col-sm-10">
                            {{Form::text('expire_date', null, ['class'=>'form-control expire_date', 'placeholder'=>'Expiry Date', 'autocomplete' => 'off','id'=>'expire_date'])}}
                            <span class="help-text"> By default it will active for unlimited time.</span>
                        </div>
                    </div>
<!--                    <div class="form-group" id="staff-selection-mandatory-block">
                        <label class="col-sm-2 control-label">Is Featured Deal <span class="require"></span></label>
                        <div class="col-sm-10">
                             <div class="abs-radio-bx">
                                <div class="abs-check-bx">
                                    <input type="checkbox" name="is_feature" value="1">
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="form-group" id="staff-selection-mandatory-block">
                        <label class="col-sm-2 control-label">Is Sale Deal <span class="require"></span></label>
                        <div class="col-sm-10">
                             <div class="abs-radio-bx">
                                <div class="abs-check-bx">
                                    <input type="checkbox" name="is_sale" value="1">
                                </div>
                            </div>
                            
                        </div>
                    </div>-->
                    <div class="box-footer">
                        <label class="col-sm-2 control-label" for="inputPassword3">&nbsp;</label>
                        {{Form::submit('Submit', ['class' => 'btn btn-info'])}}
                        {{Form::reset('Reset', ['class' => 'btn btn-default canlcel_le'])}}
                    </div>
                </div>
            </div>
            {{ Form::close()}}
        </div>
    </section>
    @endsection