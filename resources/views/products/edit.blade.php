@extends('layouts.inner')
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
        if (cat == '') {
            $('.subcategory_id').hide();
        }



        $('#category_id').change(function () {
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
        if (subcat == '') {
            $('.subsubcategory_id').hide();
        }



        $('#subcategory_id').change(function () {
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
        else {
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
                    $("#expire_date").datepicker("option", "", selectedDate);
                }
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
                    @include('elements.left_menu')
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
                                    {{ Form::model($recordInfo, array('method' => 'post', 'id' => 'serviceForm', 'enctype' => "multipart/form-data")) }}

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Product Name <span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::text('name', null, ['class'=>'form-control required', 'placeholder'=>'Product Name', 'autocomplete' => 'off'])}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">No Of Units<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::text('no_of_units', null, ['class'=>'form-control number required', 'placeholder'=>'Add No Of Units', 'autocomplete' => 'off'])}}
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
                                            {{Form::select('subcategory_id', $subcategory,null, ['class'=>'form-control', 'placeholder'=>'Sub Category','id'=>'subcategory_id'])}}
                                        </div>
                                    </div>
                                    <div class="form-group subsubcategory_id">
                                        <label class="col-sm-2 control-label">Sub Sub Category <span class="require"></span></label>
                                        <div class="col-sm-10">
                                            {{Form::select('subsubcategory_id', $subsubcategory,null, ['class'=>'form-control ', 'placeholder'=>'Sub Sub Category','id'=>'subsubcategory_id'])}}
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
                                            {{Form::text('discount',null,['class'=>'form-control required discount', 'min'=>'0','max'=>'99', 'placeholder'=>'Discount', 'id'=>'discount', 'autocomplete' => 'off','onkeyup' => 'getprice()'])}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Final Price ({{CURR }})<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::text('final_price',null,['class'=>'form-control required final_price', 'min'=>'0', 'placeholder'=>'Final Price', 'id'=>'discount_price', 'autocomplete' => 'off','readonly'=>'readonly'])}}
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
                                            {{Form::file('images[]', ['class'=>'form-control', 'accept'=>IMAGE_EXT,'multiple'=>true,'id' => 'add_images'])}}
                                            <span class="help-text"> Supported File Types: jpg, jpeg, png (Max. {{ MAX_IMAGE_UPLOAD_SIZE_DISPLAY }}).</span>
                                            <div id="showimg" class="showimages_mul"></div>
                                            <div class="ImageID">
                                                <?php
                                                $image = $recordInfo['images'];
                                                $images = explode(',', $image);
                                                ?>
                                                @if(array_filter($images))
                                                @foreach($images as $image)
                                                <div class="show-ad">
                                                <div class="showeditimage">{{HTML::image(PRODUCT_SMALL_DISPLAY_PATH.$image, SITE_TITLE,['style'=>"height: 100px; max-width: 100px;display:inline-block;"])}}</div>

                                                <div class="ad_s ajshort"><a href="{{ URL::to('admin/products/deleteimageedit/'.$recordInfo['slug'].'/'.$image)}}" title="Cancel" class="canlcel_le"  onclick="return confirm('Are you sure you want to delete?')">X</a>
                                                </div></div>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Videos <span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::file('videos[]', ['class'=>'form-control', 'accept'=>VIDEO_EXT,'multiple'=>true,'id' => 'add_videos'])}}
                                            <span class="help-text"> Supported File Types: mp4, 3gp, mov, avi, ogg (Max. {{ MAX_VIDEO_UPLOAD_SIZE_DISPLAY }}).</span>
                                            <div class="ImageID">
                                                <?php
                                                $video = $recordInfo['videos'];
                                                $videos = explode(',', $video);
                                                ?>
                                                @if(array_filter($videos))
                                                @foreach($videos as $video)
                                                <div class="show-ad show-ad-vdo">
                                                    <div class="showeditimage"><video class="video-box" controls>  <source src="<?php echo PRODUCTVIDEO_FULL_DISPLAY_PATH.$video;?>" type="video/mp4"></video></div>
                                                    <div class="ad_s ajshort"><a href="{{ URL::to('admin/deals/deletevideoedit/'.$recordInfo['slug'].'/'.$video)}}" title="Cancel" class="canlcel_le"  onclick="return confirm('Are you sure you want to delete?')"> X </a></div>

                                                </div>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Expiry Date <span class="require"></span></label>
                                        <div class="col-sm-10">
                                            {{Form::text('expire_date', null, ['class'=>'form-control expire_date', 'placeholder'=>'Expiry Date', 'autocomplete' => 'off','id'=>'expire_date'])}}
                                            <span class="help-text"> By default it will active for unlimited time.</span>
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
