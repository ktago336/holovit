@extends('layouts.admin')
@section('content')
<?php 
$parent_id = Session::get('parent_id');
$adminLId = Session::get('adminid');
$adminRols = App\Http\Controllers\Admin\AdminsController::getAdminRoles(Session::get('adminid'));
$checkSubRols = App\Http\Controllers\Admin\AdminsController::getAdminRolesSub(Session::get('adminid'));
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1> Merchant</h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::to('admin/admins/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li class="active">  Merchant</li>
        </ol>
    </section>

    <section class="content">
        <div class="box box-info">
            <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
            <div class="admin_search">
                {{ Form::open(array('method' => 'post', 'id' => 'adminSearch')) }}
                <div class="form-group align_box dtpickr_inputs">
                    <span class="hints">Search by Username or Email Address</span>
                    <span class="hint">{{Form::text('keyword', null, ['class'=>'form-control', 'placeholder'=>'Search by keyword', 'autocomplete' => 'off'])}}</span>
                    <div class="admin_asearch">
                        <div class="ad_s ajshort">{{Form::button('Submit', ['class' => 'btn btn-info admin_ajax_search'])}}</div>
                        <div class="ad_cancel"><a href="{{URL::to('admin/admins/merchant')}}" class="btn btn-default canlcel_le">Clear Search</a></div>
                    </div>
                </div>
                {{ Form::close()}}
                <?php $role = 2; if(isset($checkSubRols[2])){
                    if(in_array($role, $checkSubRols[2])) { ?>
                <div class="add_new_record"><a href="{{URL::to('admin/admins/addmerchant')}}" class="btn btn-default"><i class="fa fa-plus"></i> Add Merchant</a></div>
            <?php } } ?>
            <?php if($adminLId == 1){ ?>
                <div class="add_new_record"><a href="{{URL::to('admin/admins/addmerchant')}}" class="btn btn-default"><i class="fa fa-plus"></i> Add Merchant</a></div>
            <?php }  ?>
            </div>            
            <div class="m_content" id="listID">
                @include('elements.admin.admins.staff')
            </div>
        </div>
    </section>
</div>
@endsection