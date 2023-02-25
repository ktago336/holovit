@extends('layouts.home')
@section('content')
<section class="listing_deal">
  <div class="container">
   
    
  <div class="panel panel-default">
   <div class="row"> 
    <div class="col-md-3">
    <div class="panel-heading">
      <div class="panel-title">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#1" data-toggle="tab"><i class="fa fa-shopping-bag"></i> My Orders</a>
          </li>
          <li><a href="#2" data-toggle="tab"> <i class="fa fa-heart"></i> Wish List</a>
          </li>
          <li><a href="#3" data-toggle="tab">  Solution</a>
          </li>
        </ul>
      </div>
    </div>
    </div>
    <div class="col-md-9">
    <div class="panel-body">
      <div class="tab-content ">
        <div class="tab-pane active" id="1">
        <div class="informetion_top">
        <div class="tatils_0t1">My Order</div>
        <div class="informetion_bx">
            <div class="informetion_bxes">
                <div class="table_dcf">
                    <div class="tr_tables">

                        <div class="td_tables">Order Number</div>
                        <div class="td_tables">Total Amount</div>

                        <div class="td_tables">Status</div>
                        <div class="td_tables">Placed Date/Time</div>
                        <div class="td_tables">Action</div>

                    </div>
                    <div class="tr_tables2">

                                            <div data-title="Address Title" class="td_tables2">
                                               OrderNo1584083290 
                                            </div>
                                           
                                            <div data-title="Total Amount" class="td_tables2">
                                               $80.5 
                                            </div>
                                           
                                            <div data-title="Address Title" class="td_tables2">
                                                                                                Pending 
                                            </div>
                                            <div data-title="Created" class="td_tables2">
                                                3/13/20, 5:38 PM
                                            </div>
                                        
                                            <div data-title="Action" class="td_tables2">
                                         
                                                 <div class="actions">
                                                            <a href="#" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>  
                                                             <a href="#" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i></a>  
                                                             <a href="#" class="btn btn-primary btn-xs"><i class="fa fa-trash-o"></i></a>
                                                             <a href="#" class="btn btn-primary btn-xs"><i class="fa fa-search"></i></a>                          

                                                          </div>
                                                       
                                                </div>
                                 
                                            </div>
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
</section>







@endsection