@extends('layouts.admin')
@section('content')
<script type="text/javascript">
    $(document).ready(function () {
        $.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[\w.]+$/i.test(value);
        }, "Only letters, numbers and underscore allowed.");
        $("#adminForm").validate();
    });
 </script>
 
<div class="content-wrapper">
    <section class="content-header">
        <h1>Edit Category</h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::to('admin/admins/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li><a href="{{URL::to('admin/categories')}}"><i class="fa fa-bars"></i> <span>Manage Categories</span></a></li>
            <li class="active"> Edit Category</li>
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
                        <label class="col-sm-2 control-label">Category Name <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::text('category_name', null, ['class'=>'form-control required', 'placeholder'=>'Category Name', 'autocomplete' => 'off'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Category Description <span class="require"></span></label>
                        <div class="col-sm-10">
                            {{Form::textarea('category_desc', null, ['class'=>'form-control', 'placeholder'=>'Description', 'autocomplete' => 'off', 'rows'=>4])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Category Image <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::file('category_image', ['class'=>'form-control', 'accept'=>IMAGE_EXT])}}
                              <span class="help-text"> Supported File Types: jpg, jpeg, png (Max. {{ MAX_IMAGE_UPLOAD_SIZE_DISPLAY }}) (must be 39 x 39 pixels).</span>
                             @if($recordInfo->category_image != '')
                                <br><div class="showeditimage">{{HTML::image(CATEGORY_SMALL_DISPLAY_PATH.$recordInfo->category_image, SITE_TITLE,['style'=>"max-width: 100px"])}}</div>
                                <?php /*?><div class="help-text">
                                    <a href="{{ URL::to('admin/categories/deleteimage/'.$recordInfo->slug.'?return='.Request::url())}}" title="Cancel" class="canlcel_le"  onclick="return confirm('Are you sure you want to delete?')">Delete Image</a>
                                   &nbsp; </div><?php */ ?>
                             @endif
                        </div>
                    </div>
                    <div class="form-group" id="staff-selection-mandatory-block">
                        <label class="col-sm-2 control-label">Is Featured Category <span class="require"></span></label>
                        <div class="col-sm-10">
                             <div class="abs-radio-bx">
                                <div class="abs-check-bx">
                                    <input type="checkbox" name="is_feature" value="1" <?php
                                    if ($recordInfo->is_feature == '1') {
                                        echo 'checked';
                                    }
                                ?>>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="box-footer">
                        <label class="col-sm-2 control-label" for="inputPassword3">&nbsp;</label>
                        {{Form::submit('Submit', ['class' => 'btn btn-info'])}}
                        <a href="{{ URL::to( 'admin/categories')}}" title="Cancel" class="btn btn-default canlcel_le">Cancel</a>
                    </div>
                </div>
            </div>
            {{ Form::close()}}
        </div>
    </section>
@endsection