@extends('layouts.newhome')
@section('content')
<style type="text/css">
    .pagination {
    display: inline-block;
}
.ddpagingshorting .pagination > li {
    display: inline-block;
}
.page-link {
     position: relative; 
     display: block;
    }
</style>
<?php
use App\Models\Admin;
?>
<section class="profile-section">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-4 col-lg-3 col-xl-3">
                
                <div class="account-menu test">
                    @include('elements.left_menu')
                </div>
            </div>
                
            <div class="col-xs-12 col-md-12 col-lg-12 col-xl-9">
                <div class="my-profile-part">
                    <h4>My Appointment Requests</h4>
                    <div class="edit-info-sec">  
                    @if(!$appointments->isEmpty())
                    <div class="topn">
                    <div class="topn_left"></div>
                    <div class="topn_rightd ddpagingshorting" id="pagingLinks" align="right">
                    <div class="panel-heading" style="align-items:center;">
                    {{$appointments->appends(Input::except('_token'))->render()}}
                    </div>
                    </div>                
                    </div>
                    <div class="tbl-resp-listing">
                    <table class="table table-bordered table-striped table-condensed cf">
                    <thead class="cf ddpagingshorting">
                    <tr>
                        <th class="sorting_paging">@sortablelink('appointment_number', 'Appointment Number')</th>
                        <th class="">Service</th>
                        <!-- <th class="sorting_paging">@sortablelink('user_id', 'Customer Name')</th> -->
                        <th class="">Staff Name</th>
                        <th class="">Booking Date Time</th>
                        <th class="">Description</th>
                        <th class="">Status</th>
                         <th class="">Price</th>
                        <th class="">Date</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($appointments as $appointment)
                    <tr>
                        <td data-title="Servicename">
                            {{$appointment->appointment_number}}
                        </td>
                       <?php 
                              if($appointment->service_ids!='' && $appointment->service_ids!=0){
                               $appointment->service_ids = explode(",",$appointment->service_ids);
                               $a = '';
                               foreach($appointment->service_ids as $appointment->service_ids){
                                   $services = DB::table('services')->where('id',$appointment->service_ids)->first();
                                   
                                     $a.= $services->name.", ";

                               }
                             }else{
                              $a='N/A';
                             }
                             ?> 

                        <td data-title="Servicename">
                            {{rtrim($a, ", ")}}
                        </td>
                        <td data-title="Staffname">
                            @if($appointment->Admin->id!='1')
                            {{$appointment->Admin->first_name}}
                            @else
                            {{'N/A'}}
                            @endif
                        </td>
                        <td data-title="Booking Date Time">{{$appointment->booking_date_time}}</td>
                        <td data-title="Description">{{$appointment->description}}</td>
                        <?php global $change_status; ?>
                        <td data-title="Description" class="{{ $change_status[$appointment->status]}}">{{$appointment->status}}</td>
                        <td data-title="Price">{{$appointment->total_price}}</td>
                        <td data-title="Date">{{$appointment->created_at->format('M d, Y')}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                    </table>  
                     </div>       
                    </div>
                    @else  
                    <div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
                    <div class="admin_no_record">No record found.</div>
                    @endif 
                </div>           
            </div>   
                               
        </div>
    </div>
</section>
@endsection