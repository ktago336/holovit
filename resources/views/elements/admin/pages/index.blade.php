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
//print_r($adminRols);
?>
<div class="admin_loader" id="loaderID">{{HTML::image("public/img/website_load.svg", SITE_TITLE)}}</div>
@if(!$allrecords->isEmpty())
    <div class="panel-body marginzero">
        <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
        {{ Form::open(array('method' => 'post', 'id' => 'actionFrom')) }}
            <section id="no-more-tables" class="lstng-section">
                <div class="topn">
                    <div class="topn_left">Pages List</div>
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
                            <th class="sorting_paging">@sortablelink('title', 'Page Title')</th>
                           <th class="sorting_paging">@sortablelink('updated_at', 'Last Updated')</th>
                           <th class="action_dvv"> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allrecords as $allrecord)
                            <tr>
                                <td data-title="Full Name">{{$allrecord->title}}</td>
                                <td data-title="Last Updated">{{$allrecord->updated_at->format('M d, Y')}}</td>
                                <td data-title="Action">
                                    <?php $role = 2; if(isset($checkSubRols[4])){
                                    if ($adminLId == 1 || in_array($role, $checkSubRols[4])) { ?>
                                    <a href="{{ URL::to('admin/pages/edit/'.$allrecord->slug)}}" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                                <?php } } else{?><a href="{{ URL::to('admin/pages/edit/'.$allrecord->slug)}}" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                                <?php } ?>
                                <?php $role = 4; if(isset($checkSubRols[4])){
                                if ($adminLId == 1 || in_array($role, $checkSubRols[4])) { ?>
                                    <a href="#info{!! $allrecord->id !!}" title="View" class="btn btn-primary btn-xs" rel='facebox'><i class="fa fa-eye"></i></a>
                                <?php } } else{?><a href="#info{!! $allrecord->id !!}" title="View" class="btn btn-primary btn-xs" rel='facebox'><i class="fa fa-eye"></i></a>
                                <?php } ?>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
                     <legend class="head_pop">{!! $allrecord->title !!}</legend>
                    <div class="drt">
                        <div class="admin_pop">{!! $allrecord->description !!}</div>  
                    </div>
                </fieldset>
            </div>
        </div>
    @endforeach
@endif