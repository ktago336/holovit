@extends('layouts.merchant_inner')
@section('content')
<section class="listing_deal">
  <div class="container">
   
    
  <div class="panel panel-default">
   <div class="row"> 
    <div class="col-md-3">
     @include('elements.merchant_left_menu')
    </div>
    
    <div class="col-md-9">
    <div class="panel-body">
      <div class="tab-content ">
        <div class="tab-pane active" id="1">
        <div class="informetion_top">
        <div class="tatils_0t1">
		My Orders
		<div class="add-list">
          <!--<a href="{{ URL::to( 'merchant/deals/add')}}"><i class="fa fa-plus"></i>Add Deals</a>-->
        </div>
		</div>
        <div class="informetion_bx">
            <div class="informetion_bxes"> 
			<div class="informetion_bxes" id="listID">
			@include('elements.merchant.users.myorders')
			</div>
        </div>
		</div>
    </div>
        </div>
        <div class="tab-pane" id="2">
     hfgghghghghghghghghghghghgh
        </div>
        <div class="tab-pane" id="3">
         ghjghjghjgjghjghj
        </div>
      </div>
    </div>
  </div>
  </div>
</div>
</div>
  </div>
</section>







@endsection