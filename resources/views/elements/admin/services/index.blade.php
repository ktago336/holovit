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
<style>
.my_text{
    font-size: 12px;
    margin-left: 55px;
    margin-top:-20px;
        }
</style>
<?php
$parent_id = Session::get('parent_id');
$adminLId = Session::get('adminid');
$adminRols = App\Http\Controllers\Admin\AdminsController::getAdminRoles(Session::get('adminid'));
$checkSubRols = App\Http\Controllers\Admin\AdminsController::getAdminRolesSub(Session::get('adminid'));
//print_r($adminRols);
?>
<div class="admin_loader" id="loaderID">{{HTML::image("public/img/website_load.svg", SITE_TITLE)}}</div>
@if(!$allrecords->isEmpty())
<div class="panel-body marginzero">
    <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
    {{ Form::open(array('method' => 'post', 'id' => 'actionFrom')) }}
    <section id="no-more-tables" class="lstng-section">
        <div class="topn">
            <div class="topn_left">Services List</div>
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
                        <th style="width:5%">#</th>
                        <th class="sorting_paging">@sortablelink('name', 'Service Name')</th>
                        <th class="sorting_paging">@sortablelink('price', 'Price')</th>
                        <th class="sorting_paging">@sortablelink('description', 'Description')</th>
                        <th class="sorting_paging">@sortablelink('minutes', 'Time to Complete')</th>
                        <th class="sorting_paging">@sortablelink('updated_at', 'Last Updated')</th>
                        <th class="action_dvv"> Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allrecords as $allrecord)
                    <tr>
                        <th style="width:5%"><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="{{$allrecord->id}}" /></th>
                        <td data-title="Full Name">
                        @if($allrecord->service_image != '')
                        <label>{{HTML::image(SERVICE_SMALL_DISPLAY_PATH.$allrecord->service_image, SITE_TITLE,['style'=>"max-width: 50px"])}}</label>
                        @else
                        {{HTML::image('public/img/noimage.png','our-clients',['style'=>"max-width: 50px"])}}
                        @endif

                        {{$allrecord->name}}
                        <div class="my_text">
                            <?php
                            $description = substr($allrecord->description, 0, 20);
                            if(strlen($description) >= 20){
                            $description .= "....";}
                            ?>
                        {{$description}}
                        </div>
                    </td>
                        <td data-title="Email Address">{{CURR }}{{$allrecord->price}}</td>
                        <td data-title="Contact Number">{{$allrecord->description}}</td>
                        <td data-title="Contact Number">{{$allrecord->minutes}} Mins</td>
                        <td data-title="Last Updated">{{$allrecord->updated_at->format('M d, Y')}}</td>
                        <td data-title="Action">
                            <div id="loderstatus{{$allrecord->id}}" class="right_action_lo">{{HTML::image("public/img/loading.gif", SITE_TITLE)}}</div>
                            <?php $role = 2; if(isset($checkSubRols[5])){
                            if ($adminLId == 1 || in_array($role, $checkSubRols[5])) { ?>
                            <span class="right_acdc" id="status{{$allrecord->id}}">
                                @if($allrecord->status == '1')
                                <a href="{{ URL::to( 'admin/services/deactivate/'.$allrecord->slug)}}" title="Deactivate" class="deactivate"><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>
                                @else
                                <a href="{{ URL::to( 'admin/services/activate/'.$allrecord->slug)}}" title="Activate" class="activate"><button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button></a>
                                @endif
                            </span>
                        <?php } }else{?><span class="right_acdc" id="status{{$allrecord->id}}">
                                @if($allrecord->status == '1')
                                <a href="{{ URL::to( 'admin/services/deactivate/'.$allrecord->slug)}}" title="Deactivate" class="deactivate"><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>
                                @else
                                <a href="{{ URL::to( 'admin/services/activate/'.$allrecord->slug)}}" title="Activate" class="activate"><button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button></a>
                                @endif
                            </span>
                        <?php } ?>
                            <?php $role = 2; if(isset($checkSubRols[5])){if ($adminLId == 1 || in_array($role, $checkSubRols[5])) { ?>
                            <a href="{{ URL::to( 'admin/services/edit/'.$allrecord->slug)}}" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                        <?php } }else{?><a href="{{ URL::to( 'admin/services/edit/'.$allrecord->slug)}}" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                        <?php }?>
                         <?php $role = 3; if(isset($checkSubRols[5])){if ($adminLId == 1 || in_array($role, $checkSubRols[5])) { ?>
                            <a href="{{ URL::to( 'admin/services/delete/'.$allrecord->slug)}}" title="Delete" class="btn btn-danger btn-xs action-list delete-list" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fa fa-trash-o"></i></a>
                             <?php } } else{?><a href="{{ URL::to( 'admin/services/delete/'.$allrecord->slug)}}" title="Delete" class="btn btn-danger btn-xs action-list delete-list" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fa fa-trash-o"></i></a>
                             <?php } ?>
                             
                            <a href="#info{!! $allrecord->id !!}" title="View" class="btn btn-primary btn-xs" rel='facebox'><i class="fa fa-eye"></i></a>
                             
                             
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="search_frm">
                <button type="button" name="chkRecordId" onclick="checkAll(true);"  class="btn btn-info">Select All</button>
                <button type="button" name="chkRecordId" onclick="checkAll(false);" class="btn btn-info">Unselect All</button>
                <?php global $accountStatus; ?>
                <?php 
            if($adminLId != 1){
                if(isset($checkSubRols[5])){
                if ($adminLId == 1 || in_array(2, $checkSubRols[5]) && in_array(3, $checkSubRols[5])) { global $accountStatus; }
                if ($adminLId == 1 || in_array(2, $checkSubRols[5]) && (!in_array(3, $checkSubRols[5]))) {unset($accountStatus['Delete']); }

                if ($adminLId == 1 || in_array(3, $checkSubRols[5]) && (!in_array(2, $checkSubRols[5]))) {  unset($accountStatus['Activate']); unset($accountStatus['Deactivate']);}
            
            if(!in_array(3, $checkSubRols[5]) && (!in_array(2, $checkSubRols[5]))){
                unset($accountStatus['Activate']);
                unset($accountStatus['Deactivate']);
                unset($accountStatus['Delete']);
            }}
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
            <legend class="head_pop">{!! $allrecord->name!!}</legend>
            <div class="drt">
                <div class="admin_pop"><span>Name: </span>  <label>{!! $allrecord->name !!}</label></div>
                <div class="admin_pop"><span>Price: </span>  <label>{!! $allrecord->price !!}</label></div>
<!--                <div class="admin_pop"><span>Gender: </span>  <label>{!! $allrecord->gender !!}</label></div>-->
                <div class="admin_pop"><span>Description: </span>  <label>{!! $allrecord->description !!}</label></div>
                @if($allrecord->service_image != '')
                    <div class="admin_pop"><span>Profile Image</span> <label>{{HTML::image(SERVICE_SMALL_DISPLAY_PATH.$allrecord->service_image, SITE_TITLE,['style'=>"max-width: 200px"])}}</label></div>
                @endif
        </fieldset>
    </div>
</div>
@endforeach
@endif