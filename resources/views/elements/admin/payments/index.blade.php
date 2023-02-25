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
            <div class="topn_left">Payments List</div>
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
                        <!--<th style="width:5%">#</th>-->
                         
                        <th class="sorting_paging">@sortablelink('order_id', 'Payment For')</th>
                        <th class="sorting_paging">@sortablelink('User.first_name', 'Customer Name')</th>
                        <th class="sorting_paging">@sortablelink('order_number', 'Order Number')</th>
						<th class="sorting_paging">@sortablelink('transaction_id', 'Transaction ID')</th>
                        <th class="sorting_paging">@sortablelink('amount', 'Total Paid Amount')</th>
                        <th class="sorting_paging">@sortablelink('payment_mode', 'Payment Via')</th>
                        <th class="sorting_paging">@sortablelink('created_at', 'Payment Date')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allrecords as $allrecord)
                    <tr>
                        <!--<th style="width:5%"><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="{{$allrecord->id}}" /></th>-->
                        <td data-title="Payment For">{{($allrecord->order_id > 0)?"Purchase Deal":"Upgrade Wallet"}}</td>
						<td data-title="Customer Name">{{$allrecord->User->first_name.' '.$allrecord->User->last_name}}</td>
                        <td data-title="Order Number">{{$allrecord->order_number?'#'.$allrecord->order_number:"N/A"}}</td>
                        <td data-title="Transaction ID">{{'#'.$allrecord->transaction_id}}</td>
                        <td data-title="Total Paid Amount">{{CURR }}{{$allrecord->amount}}</td>
                        <td data-title="Payment Via">{{$allrecord->payment_mode?$allrecord->payment_mode:"Paypal"}}</td>
                        <td data-title="Payment Date">{{$allrecord->created_at}}</td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <?php /* ?><div class="search_frm">
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
            </div>   <?php */?> 
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
<?php /* ?>@foreach($allrecords as $allrecord)
<div id="info{!! $allrecord->id !!}" style="display: none;">
    <div class="nzwh-wrapper">
        <fieldset class="nzwh">
            <legend class="head_pop">{!! '#'.$allrecord->order_number!!}</legend>
            <div class="drt">
                <div class="admin_pop"><span>Product Name: </span>  <label>{!! $allrecord->name !!}</label></div>
                <div class="admin_pop"><span>Price: </span>  <label>{!! $allrecord->final_price !!}</label></div>
                <div class="admin_pop"><span>Description: </span>  <label>{!! $allrecord->description !!}</label></div>
                @if($allrecord->images != '')
                <?php 
                //$image = explode(',',$allrecord->images);
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
@endforeach<?php */ ?>
@endif