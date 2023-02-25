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
                    <h4>Payment History</h4>
                    <div class="edit-info-sec">  
                        @if(!$payments->isEmpty())
                        <div class="topn">
                            <div class="topn_left"></div>
                            <div class="topn_rightd ddpagingshorting" id="pagingLinks" align="right">
                                <div class="panel-heading" style="align-items:center;">
                                    {{$payments->appends(Input::except('_token'))->render()}}
                                </div>
                            </div>                
                        </div>
                        <div class="tbl-resp-listing">
                            <table class="table table-bordered table-striped table-condensed cf">
                                <thead class="cf ddpagingshorting">
                                    <tr>
                                        <th class="">Services</th>
                                        <th class="">Transaction ID</th>
                                        <!-- <th class="sorting_paging">@sortablelink('user_id', 'Customer Name')</th> -->
                                        <th class="">Staff Name</th>
                                        <th class="">Appointment Date Time</th>
                                        <th class="">Payment Status</th>
                                        <th class="">Price</th>
                                        <!--<th class="sorting_paging">@sortablelink('created_at', 'Date')</th>-->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr>
                                        <?php
                                        if(isset($payment->Appointment->service_ids)){
                                            $service_ids = explode(",", $payment->Appointment->service_ids);
                                            
                                            foreach ($service_ids as $service_id) {
                                                $users = DB::table('services')->where('id', $service_id)->first();

                                                $a[]= $users->name;
                                            }
                                        }
                                        
                                        ?> 

                                        <td data-title="Servicename">
                                            @if(isset($a))
                                            {{implode(', ',$a)}}
                                            @else
                                            {{'N/A'}}
                                            @endif
                                        </td>
                                        <td data-title="Transaction ID">{{$payment->transaction_id}}</td>
                                        <td data-title="Staff Name">
                                            @if(isset($payment->Appointment->Admin->id) && ($payment->Appointment->Admin->id!='1'))
                                            {{$payment->Appointment->Admin->first_name}}
                                            @else
                                            {{'N/A'}}
                                            @endif
                                        </td>
                                        <td data-title="Appointment Date Time">
                                            @if(isset($payment->Appointment->booking_date_time))
                                            {{$payment->Appointment->booking_date_time}}
                                            @else
                                            {{'N/A'}}
                                            @endif
                                            </td>
                                        <td data-title="Payment Status">
                                            @if(isset($payment->Appointment->payment_status))
                                            {{$payment->Appointment->payment_status}}
                                            @else
                                            {{'N/A'}}
                                            @endif
                                        </td>
                                        <td data-title="Price">{{'$'.$payment->amount}}</td>
                                        <!--<td data-title="Date">{{$payment->created_at->format('M d, Y')}}</td>-->
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