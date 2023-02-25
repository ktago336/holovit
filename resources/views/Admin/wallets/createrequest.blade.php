@extends('layouts.admin')
@section('content')
<script type="text/javascript">
    $(document).ready(function () {
        $.validator.addMethod("alphanumeric", function(value, element) {
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
 
<div class="content-wrapper">
    <section class="content-header">
        <h1>Create Withdraw Request @if($merchantinfo && $merchantinfo->id > 0){{'('.$merchantinfo->busineess_name.', Balance :'.CURR.$merchantinfo->wallet_balance.')'}}@endif </h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::to('admin/admins/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            @if($merchantinfo && $merchantinfo->id > 0)
            <li><a href="{{URL::to('admin/admins/merchant')}}"><i class="fa fa-users"></i> <span> Merchants</span></a></li>
            <li><a href="{{URL::to('admin/wallets/withdrawals/'.$merchantinfo->slug)}}"><i class="fa fa-money"></i> <span> Withdrawals</span></a></li>
            @else
            <li><a href="{{URL::to('admin/wallets/withdrawals')}}"><i class="fa fa-money"></i> <span> Withdrawals</span></a></li>
            @endif
            
            <li class="active"> Create Withdraw Request</li>
        </ol>
    </section>
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">&nbsp;</h3>
            </div>
            <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
            {{ Form::open(array('method' => 'post', 'id' => 'adminForm', 'enctype' => "multipart/form-data")) }}
            <div class="form-horizontal">
                <div class="box-body">
				<?php if($merchantinfo && $merchantinfo->id > 0){?>
					{{Form::hidden('merchant_id', $merchantinfo->id)}}
					<div class="form-group">
                        <label class="col-sm-2 control-label">Withdraw Amount({{CURR}})<span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::text('amount', null, ['class'=>'form-control required digits', 'placeholder'=>'Withdraw Amount', 'autocomplete' => 'off', 'min'=>1, 'max'=>$merchantinfo->wallet_balance])}}
                        </div>
                    </div>
				<?php }else{?>
					<div class="form-group">
                        <label class="col-sm-2 control-label">Select Merchant <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::select('merchant_id', $merchant,null, ['class'=>'form-control required', 'placeholder'=>'Select Merchant'])}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Withdraw Amount({{CURR}})<span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::text('amount', null, ['class'=>'form-control required digits', 'placeholder'=>'Withdraw Amount', 'autocomplete' => 'off', 'min'=>1])}}
                        </div>
                    </div>
					
				<?php }?>
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description <span class="require">*</span></label>
                        <div class="col-sm-10">
                            {{Form::textarea('description', null, ['class'=>'form-control required', 'placeholder'=>'Description', 'autocomplete' => 'off', 'rows'=>4])}}
                        </div>
                    </div>
                    <div class="box-footer">
                        <label class="col-sm-2 control-label" for="inputPassword3">&nbsp;</label>
                        {{Form::submit('Submit', ['class' => 'btn btn-info'])}}
                        @if(count($errors) > 0 || Session::has('error_message') || isset($error_message))
                        <a href="{{ URL::to( 'admin/wallets/createrequest')}}" title="Reset" class="btn btn-default canlcel_le">Reset</a>
                        @else
                        {{Form::reset('Reset', ['class' => 'btn btn-default canlcel_le'])}}
                        @endif
                    </div>
                </div>
            </div>
            {{ Form::close()}}
        </div>
    </section>
@endsection