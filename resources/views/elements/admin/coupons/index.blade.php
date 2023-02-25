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
            <div class="topn_left">Coupons List</div>
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
                         <th class="sorting_paging">@sortablelink('merchant name', 'Merchant Name')</th>
                         <th class="sorting_paging">@sortablelink('product name', 'Product Name')</th>
                        <th class="sorting_paging">@sortablelink('coupon code', 'Coupon Code')</th>
                        <th class="sorting_paging">@sortablelink('discount offer', 'Discount Offer')</th>
                        <th class="sorting_paging">@sortablelink('start date', 'Start Date')</th>
                        <th class="sorting_paging">@sortablelink('end date', 'End Date')</th>
                        <th class="action_dvv"> Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allrecords as $allrecord)
                    <tr>
                        <th style="width:5%"><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="{{$allrecord->id}}" /></th>
                         <?php  $merchant = DB::table('users')->where(['status'=>1,'id'=>$allrecord->user_id])->first(); ?>
                        <td data-title="Title">{{$merchant->store_name}}</td>
                        <?php 
                        //$pr = Coupon::with('Product')->get('product_id');
                       //echo '<pre>';
                       // print_r($pr); exit;
//                        $productname =array();
//                        $product_name = array();
//                        $productsname = DB::table('products')->where(['status'=>1])->orderBy('name', 'ASC')->pluck('name','id');
//                        //print_r($product_name); exit;
//                        foreach($productsname as $key => $product_names){
//                            $prdkey = $key;
//                            $product_name[] = $prdkey;
//                            $productnamess = $product_names;
//                            $productname[] = $productnamess;
//                        }
//                        $dealproduct = explode(',',$allrecord->product_id);
                        ?>
                        <td data-title="Title"> {{$allrecord->product_id}}</td>
                        <td data-title="Coupon Code">{{$allrecord->coupon_code}}</td>
                        <td data-title="Discount Offer">{{CURR }}{{$allrecord->discount_offer}}</td>
                        <td data-title="Start Date">{{$allrecord->start_date}}</td>
                        <td data-title="End Date">{{$allrecord->end_date}}</td>
                        <td data-title="Action">
                            <div id="loderstatus{{$allrecord->id}}" class="right_action_lo">{{HTML::image("public/img/loading.gif", SITE_TITLE)}}</div>
                           <span class="right_acdc" id="status{{$allrecord->id}}">
                                @if($allrecord->status == '1')
                                <a href="{{ URL::to( 'admin/coupons/deactivate/'.$allrecord->slug)}}" title="Deactivate" class="deactivate"><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>
                                @else
                                <a href="{{ URL::to( 'admin/coupons/activate/'.$allrecord->slug)}}" title="Activate" class="activate"><button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button></a>
                                @endif
                            </span>
                          
                             <a href="{{ URL::to( 'admin/coupons/edit/'.$allrecord->slug)}}" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                             <a href="{{ URL::to( 'admin/coupons/delete/'.$allrecord->slug)}}" title="Delete" class="btn btn-danger btn-xs action-list delete-list" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fa fa-trash-o"></i></a>
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
                <div class="admin_pop"><span>Merchant Name: </span>  <label>{!! $merchant->store_name !!}</label></div>
                <div class="admin_pop"><span>Coupon Code: </span>  <label>{!! $allrecord->coupon_code !!}</label></div>
                <div class="admin_pop"><span>Description: </span>  <label>{!! $allrecord->description !!}</label></div>
                <div class="admin_pop"><span>Discount Offer: </span>  <label>{!! $allrecord->discount_offer !!}</label></div>
                <div class="admin_pop"><span>Start Date: </span>  <label>{!! $allrecord->start_date !!}</label></div>
                <div class="admin_pop"><span>End Date: </span>  <label>{!! $allrecord->end_date !!}</label></div>
            </div>
        </fieldset>
    </div>
</div>
@endforeach
@endif