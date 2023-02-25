<?php 
$adminLId = Session::get('adminid');
?>
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

<div class="admin_loader" id="loaderID">{{HTML::image("public/img/website_load.svg", SITE_TITLE)}}</div>
@if(!$allrecords->isEmpty())
<div class="panel-body marginzero">
    <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
    {{ Form::open(array('method' => 'post', 'id' => 'actionFrom')) }}
    <section id="no-more-tables" class="lstng-section">
        <div class="topn">
            <div class="topn_left">Deals List</div>
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
                         
                        <th class="sorting_paging">@sortablelink('name', 'Product Name')</th>
                         <th class="sorting_paging">@sortablelink('no_of_units', 'No Of Units')</th>
                        <th class="sorting_paging">@sortablelink('price', 'Price')</th>
                        <th class="sorting_paging">@sortablelink('discount', 'Discount')</th>
                        <th class="sorting_paging">@sortablelink('final price', 'Final Price')</th>
                        <th class="sorting_paging">@sortablelink('total views', 'Total Views')</th>
                        <th class="sorting_paging">@sortablelink('expire date', 'Expire Date')</th>
                        <th class="action_dvv"> Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allrecords as $allrecord)
                    <tr>
                        <th style="width:5%"><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="{{$allrecord->id}}" /></th>
                        <?php  $merchant = DB::table('users')->where(['id'=> $allrecord->merchant_id,'status'=>1])->first(); ?>
                        
                        <td data-title="Full Name">{{$allrecord->name}}</td>
                        <td data-title="Full Name">{{$allrecord->no_of_units}}</td>
                        <td data-title="Email Address">{{CURR }}{{$allrecord->price}}</td>
                        <td data-title="Contact Number">{{$allrecord->discount}}</td>
                        <td data-title="Email Address">{{CURR }}{{$allrecord->final_price}}</td>
                        <td data-title="Contact Number">{{$allrecord->total_views}}</td>
                        <td data-title="Last Updated">{{$allrecord->expire_date}}</td>
                        <td data-title="Action">
                            <div id="loderstatus{{$allrecord->id}}" class="right_action_lo">{{HTML::image("public/img/loading.gif", SITE_TITLE)}}</div>
                           <span class="right_acdc" id="status{{$allrecord->id}}">
                                @if($allrecord->status == '1')
                                <a href="{{ URL::to( 'admin/products/deactivate/'.$allrecord->slug)}}" title="Deactivate" class="deactivate"><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>
                                @else
                                <a href="{{ URL::to( 'admin/products/activate/'.$allrecord->slug)}}" title="Activate" class="activate"><button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button></a>
                                @endif
                            </span>
                          
                             <a href="{{ URL::to( 'admin/products/edit/'.$allrecord->slug)}}" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                             <a href="{{ URL::to( 'admin/products/delete/'.$allrecord->slug)}}" title="Delete" class="btn btn-danger btn-xs action-list delete-list" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fa fa-trash-o"></i></a>
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
<?php 
//echo '<pre>'; print_r($allrecords); exit;
?>
@foreach($allrecords as $allrecord)
<div id="info{!! $allrecord->id !!}" style="display: none;">
    <div class="nzwh-wrapper">
        <fieldset class="nzwh">
            <legend class="head_pop">{!! $allrecord->name!!}</legend>
            <div class="drt">
                <div class="admin_pop"><span>Product Name: </span>  <label>{!! $allrecord->name !!}</label></div>
                <div class="admin_pop"><span>Price: </span>  <label>{!! $allrecord->final_price !!}</label></div>
<!--                <div class="admin_pop"><span>Gender: </span>  <label>{!! $allrecord->gender !!}</label></div>-->
                <div class="admin_pop"><span>Description: </span>  <label>{!! $allrecord->description !!}</label></div>
                @if($allrecord->images != '')
                <?php 
                $image = explode(',',$allrecord->images);
                //print_r($category_id); exit;
                //echo $category_id;
                ?>
                <div class="admin_pop popup_view_images"><span class="popimg">Profile Image</span> <div class="imgsection">@foreach($image as $images){{HTML::image(PRODUCT_SMALL_DISPLAY_PATH.$images, SITE_TITLE,['style'=>"max-width: 200px"])}}@endforeach</div></div>
                @endif
                <div class="admin_pop"><span>Category: </span>  <label>{!! $allrecord->category_id !!}</label></div>
                <div class="admin_pop"><span>Sub Category: </span>  <label>{!! $allrecord->category_id !!}</label></div>
                <div class="admin_pop"><span>Sub Sub Category: </span>  <label>{!! $allrecord->subsubcategory_id !!}</label></div>
                <div class="admin_pop"><span>Brand: </span>  <label>{!! $allrecord->Brand->brand_name !!}</label></div>
                <div class="admin_pop"><span>Short Description: </span>  <label>{!! $allrecord->short_description !!}</label></div>
                <div class="admin_pop"><span>More Description: </span>  <label>{!! $allrecord->more_description !!}</label></div>
                <div class="admin_pop"><span>Discount: </span>  <label>{!! $allrecord->discount !!}</label></div>
                <div class="admin_pop"><span>Address: </span>  <label>{!! $allrecord->address !!}</label></div>
                <div class="admin_pop"><span>Total Views: </span>  <label>{!! $allrecord->total_views !!}</label></div>
                <div class="admin_pop"><span>Expiry Date: </span>  <label>{!! $allrecord->expire_date !!}</label></div>
            </div>
        </fieldset>
    </div>
</div>
@endforeach
@endif