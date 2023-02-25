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

        $("#adminForm").validate();
    });
</script>
<script type="text/javascript">

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
        
         
    });
    
     function  generatepromo() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        for (var i = 0; i < 10; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }

        $('#promocode').val(text);
    }
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
<script>
	$( function() {
		$("#start_date").datepicker({
			defaultDate: "+1w",
			changeMonth: true,
			dateFormat: 'yy-mm-dd',
			numberOfMonths: 1,
            //minDate: 'mm-dd-yyyy',
            //maxDate:'mm-dd-yyyy',
            changeYear: true,
            onClose: function(selectedDate) {
            	if(selectedDate){$("#start_date").datepicker("option", "", selectedDate);}
            }
        });

	} );
</script>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Edit Coupon</h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::to('admin/admins/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li><a href="{{URL::to('admin/coupons')}}"><i class="fa fa-list"></i> <span> Coupon</span></a></li>
            <li class="active"> Edit Coupon</li>
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

                    <?php  $merchant = DB::table('users')->where(['status'=>1,'user_type'=>'merchant'])->orderBy('store_name', 'ASC')->pluck('store_name','id'); ?>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Merchant <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::select('user_id', $merchant,null, ['class'=>'form-control required', 'placeholder'=>'Select Merchant'])}}
                            
                        </div>
                    </div>
                    <?php 
                 
                    $keys = array();
                    if(isset($product_name)){
                        foreach($product_name as $key => $product_names){
                          $productkeys = $key; 
                          $keys[] = $productkeys;
                        }
                    }
                    $productid = explode(',',$recordInfo->id);
                    ?>
               
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Select Product <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::select('product_id[]', $product_name,in_array($key,$productid)?"selected" :"" ,['class'=>'form-control input1','style' => 'width: 100% !important;height:130px;','multiple'=>'multiple', 'placeholder'=>'Select Product'])}}
                        </div>
                    </div>
                  
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Promotional code<span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::text('coupon_code', null, ['class'=>'form-control required', 'placeholder'=>'Coupon Code', 'id' => 'promocode', 'autocomplete' => 'off','onkeyup' => 'getprice()'])}}
                             <div onclick="generatepromo()" style="cursor: pointer;background:#ffba00; cursor: pointer;
                                     margin: 0px 0 0 0;
                                     padding: 2px;
                                     width: 143px; color: #fff;  text-align: center;">Generate Code
                                </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Discription<span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::textarea('description',null,['class'=>'form-control ', 'placeholder'=>'Discription'])}}
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Discount Offer<span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::text('discount_offer',null,['class'=>'form-control required discount', 'min'=>'0','max'=>'100', 'placeholder'=>'Discount Offer', 'id'=>'discount_offer'])}}
                        </div>
                    </div>
                  
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Start Date <span class="require"></span></label>
                        <div class="col-sm-10">
                            {{Form::text('start_date', null, ['class'=>'form-control start_date required', 'placeholder'=>'Start Date', 'autocomplete' => 'off','id'=>'start_date'])}}
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Expiry Date <span class="require"></span></label>
                        <div class="col-sm-10">
                            {{Form::text('end_date', null, ['class'=>'form-control expire_date required', 'placeholder'=>'Expiry Date', 'autocomplete' => 'off','id'=>'expire_date'])}}
                        </div>
                    </div>

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