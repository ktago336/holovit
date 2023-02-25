@extends('layouts.merchant_inner')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{ HTML::script('public/js/ckeditor/ckeditor.js')}}
<script type="text/javascript">
    $(document).ready(function() {
        CKEDITOR.replace( 'description', {
            toolbar :
                [
                    ['ajaxsave'],
                    ['Styles','Bold', 'Italic', 'Underline', '-', 'NumberedList', 'BulletedList', '-'],
                    ['Cut','Copy','Paste','PasteText'],
                    ['Undo','Redo','-','RemoveFormat'],
                    ['TextColor','BGColor'],
                    ['Maximize', 'Image', 'Table','Link', 'Unlink']
            ],
            filebrowserUploadUrl : '<?php echo HTTP_PATH;?>/admin/pages/pageimages',
            language: '',
            height: 300,
            //uiColor: '#884EA1'
        });
    });
</script>
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


    function getprice() {
        var price = $('#price').val();
        var discount = $('#discount').val();
        if (discount != '') {
            var disPrice = price - ((price * discount) / 100);
            $('#discount_price').val(disPrice);
        } else {
            $('#discount_price').val(price);
        }
    }

//limit for image upload
    $(document).ready(function () {
        $("#add_images").on('change', function () {
            //Get count of selected files
            var countFiles = $(this)[0].files.length;
            if (countFiles > 10) {
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
                                <div class="informetion_top">
                                    <div class="tatils_0t1"> Edit Deal</div>
                                     <div class="er_mes">
                                    <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
                                </div>
								{{ Form::model($recordInfo,array('method' => 'post', 'id' => 'serviceForm', 'enctype' => "multipart/form-data")) }}
                                    
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Deal Name <span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::text('deal_name', null, ['class'=>'form-control required', 'placeholder'=>'Deal Name', 'autocomplete' => 'off'])}}
                                        </div>
                                    </div>
									@if(isset(Auth::guard('merchant')->user()->id))
									<?php $user_id = Auth::guard('merchant')->user()->id; ?>
								@endif
									<input type = "hidden" name="merchant_id" value="<?php echo $user_id; ?>">
									
									<?php global $vouchers; ?>
									<div class="form-group">
                                        <label class="col-sm-2 control-label">Voucher Type <span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::select('voucher_type',$vouchers, null, ['class'=>'form-control required', 'placeholder'=>'Voucher Type', 'autocomplete' => 'off'])}}
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-2 control-label">Voucher Price({{CURR}})<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::text('voucher_price', null, ['class'=>'form-control required number', 'placeholder'=>'Voucher Price('.CURR.')', 'autocomplete' => 'off', 'min' => '0', 'max' => '999999'])}}
                                        </div>
                                    </div>
									<!--<div class="form-group">
                                        <label class="col-sm-2 control-label">Phone Number<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::text('contact', null, ['class'=>'form-control required number', 'placeholder'=>'Phone Number', 'autocomplete' => 'off'])}}
                                        </div>
                                    </div>-->
									<?php global $popular_time; ?>
									<div class="form-group">
                                        <label class="col-sm-2 control-label">Most Popular Time Of the day<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::select('popular_time',$popular_time, null, ['class'=>'form-control required ', 'placeholder'=>'Most popular time of the day', 'autocomplete' => 'off'])}}
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-2 control-label">Add Amenities<span class="require"></span></label>
                                        <div class="col-sm-10">
										<?php $amenitie_id_arr = explode(',',$recordInfo->amenitie_id);?>

                                            {{Form::select('amenitie_id[]',$amenitie, $amenitie_id_arr, ['class'=>'form-control ','multiple'=>'multiple', 'autocomplete' => 'off'])}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Valid For(Per Person)<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::text('valid_for', null, ['class'=>'form-control required digits', 'placeholder'=>'Valid For(Per Person)', 'autocomplete' => 'off', 'min' => '1', 'max' => '100'])}}
                                        </div>
                                    </div>
                                    <?php /*?><div class="form-group">
                                        <label class="col-sm-2 control-label">Valid On<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::text('voucher_price', null, ['class'=>'form-control required number', 'placeholder'=>'Voucher Price('.CURR.')', 'autocomplete' => 'off', 'min' => '0', 'max' => '999999'])}}
                                        </div>
                                    </div><?php */?>
									<?php global $time_array; ?>
									<div class="form-group">
                                        <label class="col-sm-2 control-label">Deal Start Time<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::select('deal_start_time',$time_array, null, ['class'=>'form-control required ', 'placeholder'=>'Start Time', 'autocomplete' => 'off'])}}
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-2 control-label">Deal End Time<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::select('deal_end_time',$time_array, null, ['class'=>'form-control required ', 'placeholder'=>'End Time', 'autocomplete' => 'off'])}}
                                        </div>
                                    </div>
                                   <!-- <div class="form-group">
                                        <label class="col-sm-2 control-label">Select Products to Add Deal <span class="require">*</span></label>
                                        <div class="col-sm-10">-->
                                            <?php
                                            $check1 = 'checked';
                                            $check2 = '';
                                            $class = 'display:none;';
                                            ?>
                                           <!--  <div class="rdo-sprt">
                                                <input id="all" type="radio" name="type" value="1" class="required" <?php //echo $check1; ?> onchange="get_product(this.value);"><label>Select All Products to add in deal</label>
                                            </div>
                                            <div class="rdo-sprt">
                                                <input id="sendTo" type="radio" name="type" value="0" class="required" <?php //echo $check2; ?> onchange="get_product(this.value);"><label>Select Products to add in deal</label>
                                            </div>
                                            <div class="rdo-sprt-type0">
                                                {{Form::select('product_id[]', $product_name,null, ['class'=>'form-control','style' => $class,'id'=>'product_ids','multiple'=>'multiple', 'placeholder'=>'Select Product'])}}
                                            </div>
                                        </div>
                                    </div>-->
									<div class="form-group">
                                        <label class="col-sm-2 control-label">Discount (%)<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::text('discount', null, ['class'=>'form-control required number', 'min'=>'1','max'=>'99', 'placeholder'=>'Discount', 'id'=>'discount', 'autocomplete' => 'off','onkeyup' => 'getprice()'])}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Deal Description <span class="require"></span></label>
                                        <div class="col-sm-10">
                                            {{Form::textarea('description', null, ['class'=>'form-control', 'placeholder'=>'Enter Description', 'autocomplete' => 'off', 'rows'=>4])}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Images <span class="require"></span></label>
                                        <div class="col-sm-10">
                                            {{Form::file('images[]', ['class'=>'form-control', 'accept'=>IMAGE_EXT,'multiple'=>true,'id' => 'add_images'])}}
                                            <span class="help-text"> Supported File Types: jpg, jpeg, png (Max. 2MB).</span>
                                            <div id="showimg" class="showimages_mul"></div>
                                            <div class="ImageID">
                                                <?php
                                                $image = $recordInfo['images'];
                                                $images = explode(',', $image);
                                                ?>
                                                @if(array_filter($images))
                                                @foreach($images as $image)
                                                <div class="show-ad">
                                                <div class="showeditimage">{{HTML::image(DEAL_FULL_DISPLAY_PATH.$image, SITE_TITLE,['style'=>"height: 100px; max-width: 100px;display:inline-block;"])}}</div>

                                                <div class="ad_s ajshort"><a href="{{ URL::to('merchant/deals/deleteimageedit/'.$recordInfo['slug'].'/'.$image)}}" title="Cancel" class="canlcel_le"  onclick="return confirm('Are you sure you want to delete?')">X</a>
                                                </div>
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Expiry Date <span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::text('expire_date', null, ['class'=>'form-control expire_date required', 'placeholder'=>'Expiry Date', 'readonly' => 'readonly', 'autocomplete' => 'off','id'=>'expire_date'])}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">&nbsp;</label>
                                        <div class="col-sm-10">
                                            {{Form::submit('Submit', ['class' => 'btn btn-info'])}}
                                            {{Form::reset('Reset', ['class' => 'btn btn-default canlcel_le'])}}

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
@endsection
