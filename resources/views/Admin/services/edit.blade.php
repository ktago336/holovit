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
 
<div class="content-wrapper">
    <section class="content-header">
        <h1>Edit Service</h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::to('admin/admins/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li><a href="{{URL::to('admin/services')}}"><i class="fa fa-list"></i> <span> Service</span></a></li>
            <li class="active"> Edit Service</li>
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
                        <label class="col-sm-2 control-label">Service Name <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::text('name', null, ['class'=>'form-control required', 'placeholder'=>'Service Name', 'autocomplete' => 'off'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Price ({{CURR }})<span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::text('price', null, ['class'=>'form-control required number', 'min'=>'0', 'placeholder'=>'Price', 'autocomplete' => 'off'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Time to complete Service <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::text('minutes', null, ['class'=>'form-control required number', 'min'=>'0', 'placeholder'=>'Minutes', 'autocomplete' => 'off'])}}
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Service Image <span class="require"></span></label>
                        <div class="col-sm-10">
                            {{Form::file('service_image', ['class'=>'form-control', 'accept'=>IMAGE_EXT])}}
                            <span class="help-text"> Supported File Types: jpg, jpeg, png (Max. {{ MAX_IMAGE_UPLOAD_SIZE_DISPLAY }}).</span>
                             @if($recordInfo->service_image != '')
                                <div class="showeditimage">{{HTML::image(SERVICE_SMALL_DISPLAY_PATH.$recordInfo->service_image, SITE_TITLE,['style'=>"max-width: 200px"])}}</div>
                                <div class="help-text"><a href="{{ URL::to('admin/services/deleteimage/'.$recordInfo->slug)}}" title="Cancel" class="canlcel_le"  onclick="return confirm('Are you sure you want to delete?')">Delete Image</a></div>
                             @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::textarea('description', null, ['class'=>'form-control required', 'placeholder'=>'Enter Description', 'autocomplete' => 'off', 'rows'=>4])}}
                        </div>
                    </div>
                    <div class="box-footer">
                        <label class="col-sm-2 control-label" for="inputPassword3">&nbsp;</label>
                        {{Form::submit('Submit', ['class' => 'btn btn-info'])}}
                        <a href="{{ URL::to( 'admin/services')}}" title="Cancel" class="btn btn-default canlcel_le">Cancel</a>
                    </div>
                </div>
            </div>
            {{ Form::close()}}
        </div>
    </section>
@endsection