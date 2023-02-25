{{ HTML::script('public/js/facebox.js')}}
{{ HTML::style('public/css/facebox.css')}}
<script type="text/javascript">
$(document).ready(function ($) {
$('.close_image').hide();
$('a[rel*=facebox]').facebox({
closeImage: '{!! HTTP_PATH !!}/public/img/close.png'
});
});
</script>
<?php 
$parent_id = Session::get('parent_id');
$adminLId = Session::get('adminid');
$adminRols = App\Http\Controllers\Admin\AdminsController::getAdminRoles(Session::get('adminid'));
$checkSubRols = App\Http\Controllers\Admin\AdminsController::getAdminRolesSub(Session::get('adminid'));
//print_r($checkSubRols);
?>
<div class="admin_loader" id="loaderID">{{HTML::image("public/img/website_load.svg", SITE_TITLE)}}</div>
@if(!$allrecords->isEmpty())
<div class="panel-body marginzero">
<div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
{{ Form::open(array('method' => 'post', 'id' => 'actionFrom')) }}
<section id="no-more-tables" class="lstng-section">
<div class="topn">
<div class="topn_left">Merchant List</div>
<div class="topn_rightd ddpagingshorting" id="pagingLinks" align="right">
<div class="panel-heading" style="align-items:center;">
{{$allrecords->appends(Input::except('_token'))->render()}}
</div>
</div>                
</div>
<div class="tbl-resp-listing">
<table class="table table-bordered table-striped table-condensed cf">
<thead class="cf ddpagingshorting">
<tr>
<?php if($adminLId != 1){if(isset($checkSubRols[2])){
if($adminLId == 1 || in_array(2, $checkSubRols[2]) || in_array(3, $checkSubRols[2])|| in_array(4, $checkSubRols[2])) { ?>
<th style="width:5%">#</th>
<?php } } } else{?>
<th style="width:5%">#</th>
<?php } ?>
<th class="sorting_paging">@sortablelink('user_name', 'Username')</th>
<th class="sorting_paging">@sortablelink('email', 'Email Address')</th>
<!--<th class="sorting_paging">@sortablelink('contact', 'Contact Number')</th>-->
<th class="sorting_paging">@sortablelink('created_at', 'Date')</th>
<th class="action_dvv"> Action</th>
</tr>
</thead>
<tbody>
@foreach($allrecords as $allrecord)
<tr>
<?php if($adminLId != 1){ if(isset($checkSubRols[2])){
if($adminLId == 1 || (in_array(2, $checkSubRols[2]) || in_array(3, $checkSubRols[2])|| in_array(4, $checkSubRols[2])) && $allrecord->id != $adminLId) { ?>
<th style="width:5%"><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="{{$allrecord->id}}" /></th>
<?php }else{ ?><th style="width:5%">&nbsp;</th> <?php } } }else { ?> <th style="width:5%"><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="{{$allrecord->id}}" /></th>
<?php } ?>
<td data-title="Full Name">
@if($allrecord->profile_image != '')
{{HTML::image(PROFILE_SMALL_DISPLAY_PATH.$allrecord->profile_image, SITE_TITLE,['style'=>"max-width: 50px"])}}</label>
@else
{{HTML::image('public/img/noimage.png','our-clients',['style'=>"max-width: 50px"])}}
@endif
{{$allrecord->username}}</td>
<td data-title="Email Address">{{$allrecord->email}}</td>
<!--<td data-title="Contact Number">{{$allrecord->contact}}</td>-->
<td data-title="Date">{{$allrecord->created_at->format('M d, Y')}}</td>
<td data-title="Action">
    <div id="loderstatus{{$allrecord->id}}" class="right_action_lo">{{HTML::image("public/img/loading.gif", SITE_TITLE)}}</div>
    <?php if(isset($checkSubRols[2])){if($adminLId == 1  || (in_array(2, $checkSubRols[2])) && $allrecord->id != $adminLId){  ?>
        <span class="right_acdc" id="status{{$allrecord->id}}">
            @if($allrecord->status == '1')
            <a href="{{ URL::to( 'admin/admins/deactivatemerchant/'.$allrecord->slug)}}" title="Deactivate" class="deactivate"><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>
            @else
            <a href="{{ URL::to( 'admin/admins/activatemerchant/'.$allrecord->slug)}}" title="Activate" class="activate"><button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button></a>
            @endif
        </span>
    <?php } } else if(!isset($checkSubRols[2])){if($adminLId == 1  && $allrecord->id != $adminLId){  ?>
        <span class="right_acdc" id="status{{$allrecord->id}}">
            @if($allrecord->status == '1')
            <a href="{{ URL::to( 'admin/admins/deactivatemerchant/'.$allrecord->slug)}}" title="Deactivate" class="deactivate"><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>
            @else
            <a href="{{ URL::to( 'admin/admins/activatemerchant/'.$allrecord->slug)}}" title="Activate" class="activate"><button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button></a>
            @endif
        </span>
    <?php } } else {?>
        <span class="right_acdc" id="status{{$allrecord->id}}">
            @if($allrecord->status == '1')
            <a href="{{ URL::to( 'admin/admins/deactivatemerchant/'.$allrecord->slug)}}" title="Deactivate" class="deactivate"><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>
            @else
            <a href="{{ URL::to( 'admin/admins/activatemerchant/'.$allrecord->slug)}}" title="Activate" class="activate"><button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button></a>
            @endif
        </span>
    <?php } ?>
    <?php if(isset($checkSubRols[2])){ 
        if($allrecord->id == $adminLId || $adminLId == 1  || (in_array(2, $checkSubRols[2]))){  ?>
            <a href="{{ URL::to( 'admin/admins/editmerchant/'.$allrecord->slug)}}" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
        <?php } } else{?>
            <a href="{{ URL::to( 'admin/admins/editmerchant/'.$allrecord->slug)}}" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i>
            <?php }?>
            <?php $role = 3; if(isset($checkSubRols[2])){
                if($adminLId == 1 || in_array($role, $checkSubRols[2]) && $allrecord->id != $adminLId) { ?>
                    <a href="{{ URL::to( 'admin/admins/deletemerchant/'.$allrecord->slug)}}" title="Delete" class="btn btn-danger btn-xs action-list delete-list" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fa fa-trash-o"></i></a>
                <?php } } ?>
              
                        <?php //$role = 4; if ($adminLId == 1 || in_array($role, $checkSubRols[2])) { ?>
                            <a href="#info{!! $allrecord->id !!}" title="View" class="btn btn-primary btn-xs" rel='facebox'><i class="fa fa-eye"></i></a>
                            <?php //} ?>
                        </td>
                    </td>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="search_frm">
        <button type="button" name="chkRecordId" onclick="checkAll(true);"  class="btn btn-info">Select All</button>
        <button type="button" name="chkRecordId" onclick="checkAll(false);" class="btn btn-info">Unselect All</button>
        <?php global $accountStatus; ?>
        <?php $role = 3; if($adminLId != 1){
            if(isset($checkSubRols[2])){
                if ($adminLId == 1 || in_array($role, $checkSubRols[2]) && (!in_array(2, $checkSubRols[2]))) { 
                    unset($accountStatus['Activate']);
                    unset($accountStatus['Deactivate']);
                }
                else if($adminLId == 1 || in_array(2, $checkSubRols[2]) && (!in_array(3, $checkSubRols[2]))) { 
                    unset($accountStatus['Delete']);
                }
                else if($adminLId == 1 || in_array(4, $checkSubRols[2]) && (!in_array(2, $checkSubRols[2])) && (!in_array(3, $checkSubRols[2]))) { 
                    unset($accountStatus['Activate']);
                    unset($accountStatus['Deactivate']);
                    unset($accountStatus['Delete']);
                }
                else if($adminLId == 1 || in_array(2, $checkSubRols[2]) && in_array(3, $checkSubRols[2])) { 
                    $accountStatus = $accountStatus;
                }
            }
            if(!isset($checkSubRols[2])){
                unset($accountStatus['Activate']);
                unset($accountStatus['Deactivate']);
                unset($accountStatus['Delete']);}
            }
            ?>
            <div class="list_sel">{{Form::select('action', $accountStatus,null, ['class' => 'small form-control','placeholder' => 'Action for selected record', 'id' => 'action'])}}</div>
            <button type="submit" class="small btn btn-success btn-cons btn-info" onclick="return ajaxActionFunction();" id="submit_action">OK</button>
        </div>    
    </div>
</section>
{{ Form::close()}}
</div>         
</div> 
@else 
<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
<div class="admin_no_record">No record found.</div>
@endif

@if(!$allrecords->isEmpty())
@foreach($allrecords as $allrecord)
<div id="info{!! $allrecord->id !!}" style="display: none;">
<div class="nzwh-wrapper">
<fieldset class="nzwh">
    <legend class="head_pop">{!! $allrecord->first_name.' '.$allrecord->last_name !!}</legend>
    <div class="drt">
        <div class="admin_pop"><span>Username: </span>  <label>{!! $allrecord->username !!}</label></div>
        <div class="admin_pop"><span>Name: </span>  <label>{!! $allrecord->first_name.' '.$allrecord->last_name !!}</label></div>
        <div class="admin_pop"><span>Email Address: </span>  <label>{!! $allrecord->email !!}</label></div>
        <div class="admin_pop"><span>Services: </span>  <label>
            <?php 
                        if($allrecord->service_ids!='' && $allrecord->service_ids!=0){
                         $allrecord->service_ids = explode(",",$allrecord->service_ids);
                         $a = '';
                         foreach($allrecord->service_ids as $allrecord->service_ids){
                             $services = DB::table('services')->where('id',$allrecord->service_ids)->first();
                                   //if()
                             $a.= $services->name.", ";

                         }
                     }else{
                      $a='N/A';
                  }
                  ?>
              {!! $a !!}</label></div>
           
        <div class="admin_pop"><span>Created Date: </span>  <label>{!! $allrecord->created_at !!}</label></div>
        <!--<div class="admin_pop"><span>Address: </span>  <label>{!! nl2br($allrecord->address) !!}</label></div>-->
        @if($allrecord->profile_image != '')
        <div class="admin_pop"><span>Profile Image</span> <label>{{HTML::image(PROFILE_SMALL_DISPLAY_PATH.$allrecord->profile_image, SITE_TITLE,['style'=>"max-width: 200px"])}}</label></div>
        @endif
    </fieldset>
</div>
</div>
@endforeach
@endif