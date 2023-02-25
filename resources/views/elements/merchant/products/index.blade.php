<div class="admin_loader" id="loaderID">{{HTML::image("public/img/website_load.svg", SITE_TITLE)}}</div>

@if(!$allrecords->isEmpty())
<div class="hp">
    <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
    {{ Form::open(array('method' => 'post', 'id' => 'actionFrom')) }}
    <div class="topn_rightd ddpagingshorting" id="pagingLinks" align="right">
        <div class="panel-heading" style="align-items:center;">
            {{$allrecords->appends(Input::except('_token'))->render()}}

        </div>
    </div> 
    <div class="table_responsive"> 
    <div class="table_dcf"> 
        <div class="tr_tables ddpagingshorting">

            <div class="td_tables"></div>
            
            
           <div class="td_tables">@sortablelink('name', 'Product Name')</div>
           <div class="td_tables">@sortablelink('Category.category_name', 'Category')</div>
           <div class="td_tables">@sortablelink('no_of_units  ', 'No Of Units')</div>
           <div class="td_tables">@sortablelink('price', 'Price')</div>
           <div class="td_tables">@sortablelink('created_at', 'Created On')</div>
            <div class="td_tables">Action</div>

        </div>
        @foreach($allrecords as $allrecord)
        <div class="tr_tables2">

            <div data-title="Select Deals" class="td_tables2"><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="{{$allrecord->id}}" /></div>        
            <div data-title="Address Title" class="td_tables2">
                {{$allrecord->name}}
            </div>

            <div data-title="Total Amount" class="td_tables2">
                {{$allrecord->Category->category_name}}
            </div>

            <div data-title="Address Title" class="td_tables2">
                {{$allrecord->no_of_units}}
            </div>
            <div data-title="Created" class="td_tables2">
                {{CURR }}{{$allrecord->price}}
            </div>
            <div data-title="Created" class="td_tables2">
                {{$allrecord->created_at}}
            </div>

            <div data-title="Action" class="td_tables2">

                <div class="actions">

                    <div id="loderstatus{{$allrecord->id}}" class="right_action_lo">{{HTML::image("public/img/loading.gif", SITE_TITLE)}}</div>
                    <span class="right_acdc" id="status{{$allrecord->id}}">
                        @if($allrecord->status == '1')
                        <a href="{{ URL::to( 'products/deactivate/'.$allrecord->slug)}}" title="Deactivate" class="deactivate">
                            <button class="btn btn-primary btn-xs"><i class="fa fa-check"></i></button>
                        </a>
                        @else
                        <a href="{{ URL::to( 'products/activate/'.$allrecord->slug)}}" title="Activate" class="activate"><button class="btn btn-primary btn-xs"><i class="fa fa-ban"></i></button></a>
                        @endif
                    </span>

                    <a href="{{ URL::to( 'products/edit/'.$allrecord->slug)}}" title="Edit" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                    <a href="{{ URL::to( 'products/delete/'.$allrecord->slug)}}" title="Delete" class="btn btn-primary btn-xs action-list delete-list" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fa fa-trash-o"></i></a>
                    <a href="#info{!! $allrecord->id !!}" title="View" class="btn btn-primary btn-xs" rel='facebox'><i class="fa fa-eye"></i></a>








<!--                <a href="#" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>  
<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i></a>  
<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-trash-o"></i></a>
<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-search"></i></a>                          -->

                </div>

            </div>


        </div>
        @endforeach
    </div>
  </div>
    <div class="search_frm">
        <button type="button" name="chkRecordId" onclick="checkAll(true);"  class="btn btn-info">Select All</button>
        <button type="button" name="chkRecordId" onclick="checkAll(false);" class="btn btn-info">Unselect All</button>
        <?php global $accountStatus; ?>
        <?php
        /*
          if ($adminLId != 1) {
          if (isset($checkSubRols[5])) {
          if ($adminLId == 1 || in_array(2, $checkSubRols[5]) && in_array(3, $checkSubRols[5])) {
          global $accountStatus;
          }
          if ($adminLId == 1 || in_array(2, $checkSubRols[5]) && (!in_array(3, $checkSubRols[5]))) {
          unset($accountStatus['Delete']);
          }

          if ($adminLId == 1 || in_array(3, $checkSubRols[5]) && (!in_array(2, $checkSubRols[5]))) {
          unset($accountStatus['Activate']);
          unset($accountStatus['Deactivate']);
          }

          if (!in_array(3, $checkSubRols[5]) && (!in_array(2, $checkSubRols[5]))) {
          unset($accountStatus['Activate']);
          unset($accountStatus['Deactivate']);
          unset($accountStatus['Delete']);
          }
          }
          } */
        ?>
        <div class="list_sel">{{Form::select('action', $accountStatus,null, ['class' => 'small form-control','placeholder' => 'Action for selected record', 'id' => 'action'])}}</div>
        <button type="submit" class="small btn btn-success btn-cons btn-info" onclick="return ajaxActionFunction();" id="submit_action">OK</button>
    </div>  
    {{ Form::close()}}
</div>

@else 
<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
<div class="admin_no_record">No record found.</div>
@endif

<?php /* ?>
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
  <?php //$products = DB::table('products')->where(['status'=>1,'id'=>$allrecord->product_id])->first(); print_r($products); exit;?>
  <div class="admin_pop"><span>Product Name: </span>  <label>{!! $allrecord->product_id !!}</label></div>
  <div class="admin_pop"><span>Price: </span>  <label>{!! $allrecord->final_price !!}</label></div>
  <div class="admin_pop"><span>Discount: </span>  <label>{!! $allrecord->discount !!}</label></div>
  <!--                <div class="admin_pop"><span>Gender: </span>  <label>{!! $allrecord->gender !!}</label></div>-->
  <div class="admin_pop"><span>Description: </span>  <label>{!! $allrecord->description !!}</label></div>
  @if($allrecord->images != '')
  <?php
  $image = explode(',', $allrecord->images);
  //print_r($category_id); exit;
  //echo $category_id;
  ?>
  <div class="admin_pop popup_view_images"><span class="popimg">Profile Image</span> <div class="imgsection">@foreach($image as $images){{HTML::image(DEAL_SMALL_DISPLAY_PATH.$images, SITE_TITLE,['style'=>"max-width: 200px"])}}@endforeach</div></div>
  @endif
  <div class="admin_pop"><span>Deal Name: </span>  <label>{!! $allrecord->deal_name !!}</label></div>

  <div class="admin_pop"><span>Expiry Date: </span>  <label>{!! $allrecord->expire_date !!}</label></div>
  </div>
  </fieldset>
  </div>
  </div>
  @endforeach
  @endif
  <?php */ ?>
