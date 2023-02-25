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
                         <th class="sorting_paging">@sortablelink('Merchant.busineess_name', 'Merchant')</th>
                         <!--<th class="sorting_paging">@sortablelink('product name', 'Product Name')</th>-->
                        <th class="sorting_paging">@sortablelink('name', 'Deal Name')</th>
                        <th class="sorting_paging">@sortablelink('price', 'Price')</th>
                        <th class="sorting_paging">@sortablelink('discount', 'Discount')</th>
                        <th class="sorting_paging">@sortablelink('final price', 'Final Price')</th>
                        <th class="sorting_paging">@sortablelink('expire date', 'Expire Date')</th>
                        <th class="action_dvv"> Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allrecords as $allrecord)
                    <tr>
                        <th style="width:5%"><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="{{$allrecord->id}}" /></th>
                        <?php  $merchant = DB::table('users')->where(['id'=> $allrecord->merchant_id,'status'=>1])->first(); ?>
                        <td data-title="Full Name">{{isset($allrecord->Merchant->busineess_name)?$allrecord->Merchant->busineess_name:'N/A'}}</td>
                        <!--<td data-title="Full Name">{{$allrecord->product_id}}</td>-->
                        <td data-title="Full Name">{{$allrecord->deal_name}}</td>
                        <td data-title="Email Address">{{CURR }}{{$allrecord->voucher_price}}</td>
                        <td data-title="Contact Number">{{$allrecord->discount.'%'}}</td>
                        <td data-title="Email Address">{{CURR }}{{$allrecord->final_price}}</td>
                        <td data-title="Last Updated">{{$allrecord->expire_date}}</td>
                        <td data-title="Action">
                            <div id="loderstatus{{$allrecord->id}}" class="right_action_lo">{{HTML::image("public/img/loading.gif", SITE_TITLE)}}</div>
                           <span class="right_acdc" id="status{{$allrecord->id}}">
                                @if($allrecord->status == '1')
                                <a href="{{ URL::to( 'admin/deals/deactivate/'.$allrecord->slug)}}" title="Deactivate" class="deactivate"><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>
                                @else
                                <a href="{{ URL::to( 'admin/deals/activate/'.$allrecord->slug)}}" title="Activate" class="activate"><button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button></a>
                                @endif
                            </span>
                          
                             <?php /*<a href="{{ URL::to( 'admin/deals/edit/'.$allrecord->slug)}}" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>*/?>
                             <a href="{{ URL::to( 'admin/deals/delete/'.$allrecord->slug)}}" title="Delete" class="btn btn-danger btn-xs action-list delete-list" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fa fa-trash-o"></i></a>
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
            <legend class="head_pop">{!! $allrecord->deal_name!!}</legend>
            <div class="drt">
                <?php  //$products = DB::table('products')->where(['status'=>1,'id'=>$allrecord->product_id])->first(); print_r($products); exit;?>
                <div class="admin_pop"><span>Deal Name: </span>  <label>{!! $allrecord->deal_name !!}</label></div>
				<div class="admin_pop"><span>Voucher Type: </span>  <label><?php global $vouchers;?> {!! $allrecord->voucher_type !!}</label></div>
				<div class="admin_pop"><span>Voucher Price: </span>  <label>{!! CURR.$allrecord->voucher_price !!}</label></div>
                <div class="admin_pop"><span>Discount: </span>  <label>{!! $allrecord->discount.'%' !!}</label></div>
				<div class="admin_pop"><span>Final Price: </span>  <label>{!! CURR.$allrecord->final_price !!}</label></div>
				<div class="admin_pop"><span>Most Popular Time Of the day: </span>  <label><?php global $popular_time;?> {!! isset($popular_time[$allrecord->popular_time])?$popular_time[$allrecord->popular_time]:$allrecord->popular_time !!}</label></div>
				<div class="admin_pop"><span>Amenities: </span>  <label><?php 
				if($allrecord->amenitie_id)
				{
				     $amenitie_id_arr = explode(',',$allrecord->amenitie_id);
    				$am_arr = array();
    				foreach($amenitie_id_arr as $val){
    				    if(isset($amenitie[$val])){
    				        $am_arr[] = $amenitie[$val];
    				    }
    					    
    				}
        				if(count($am_arr) > 0){
        				    echo implode(', ',$am_arr);
        				}
    				
				    }
				   
				
				?> </label></div>
				<div class="admin_pop"><span>Deal Start Time: </span>  <label><?php global $time_array;?> {!! isset($time_array[$allrecord->deal_start_time])?$time_array[$allrecord->deal_start_time]:'N/A' !!}</label></div>
				<div class="admin_pop"><span>Deal End Time: </span>  <label><?php global $time_array;?> {!! isset($time_array[$allrecord->deal_end_time])?$time_array[$allrecord->deal_end_time]:'N/A' !!}</label></div>
                <div class="admin_pop"><span>Description: </span>  <label>{!! $allrecord->description !!}</label></div>
                @if($allrecord->images != '')
                <?php 
                $image = explode(',',$allrecord->images);
                //print_r($category_id); exit;
                //echo $category_id;
                ?>
                <div class="admin_pop popup_view_images"><span class="popimg">Profile Image</span> <div class="imgsection">@foreach($image as $images){{HTML::image(DEAL_FULL_DISPLAY_PATH.$images, SITE_TITLE,['style'=>"max-width: 200px"])}}@endforeach</div></div>
                @endif
                
                <div class="admin_pop"><span>Expiry Date: </span>  <label>{!! $allrecord->expire_date !!}</label></div>
            </div>
        </fieldset>
    </div>
</div>
@endforeach
@endif