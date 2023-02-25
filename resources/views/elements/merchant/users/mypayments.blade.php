<div class="admin_loader" id="loaderID">{{HTML::image("public/img/website_load.svg", SITE_TITLE)}}</div>
			@if(!$payments->isEmpty())
<div class="hp">
		  <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
    {{ Form::open(array('method' => 'post', 'id' => 'actionFrom')) }}
    <div class="topn_rightd ddpagingshorting" id="pagingLinks" align="right">
        <div class="panel-heading" style="align-items:center;">
            {{$payments->appends(Input::except('_token'))->render()}}

        </div>
    </div>
		<div class="table_responsive">
                <div class="table_dcf">
                    <div class="tr_tables">
						<!--<div class="td_tables"></div>-->
                        <div class="td_tables">@sortablelink('Order.order_number', 'Order Number')</div>
						<div class="td_tables">@sortablelink('voucher_number', 'Transaction ID')</div>
                        <div class="td_tables">@sortablelink('first_name', 'Customer Name')</div>
						<div class="td_tables">@sortablelink('payment_mode', 'Method')</div>
						<div class="td_tables">@sortablelink('busineess_name', 'Paid Amount')</div>
                        <div class="td_tables">@sortablelink('created_on', 'Created On')</div>
                        <div class="td_tables">Action</div>

                    </div>
					<?php //print_r($merchant); exit;?>
					<?php foreach($payments as $payment){ ?>
                    <div class="tr_tables2">
<!--<div data-title="Select Deals" class="td_tables2">
<input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="{{$payment->id}}" />
</div>-->
                                            <div data-title="Order Number" class="td_tables2">
                                               <?php echo $payment->Order->order_number; ?> 
                                            </div>
                                            <div data-title="Transaction ID" class="td_tables2">
                                                <?php echo $payment->transaction_id; ?> 
                                            </div>
											<div data-title="Merchant Name" class="td_tables2">
                                                <?php echo $payment->User->first_name.' '.$payment->User->last_name; ?> 
                                            </div>
											<div data-title="Method" class="td_tables2">
                                                <?php echo $payment->payment_mode?$payment->payment_mode:'Paypal'; ?> 
                                            </div>
                                            <div data-title="Paid Amount" class="td_tables2">
                                               <?php echo CURR.$payment->amount; ?> 
                                            </div>
											<div data-title="Created" class="td_tables2">
                                                <?php echo $payment->created_at; ?>
                                            </div>
											<div data-title="Action" class="td_tables2">
                                         
                                                 <div class="actions">
										
                                                            <a href="{{URL('/users/orderdetail/'.$payment->slug)}}" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>  
                                    
                                                          </div>
                                                       
                                                </div>
                                 
                                            </div>
											<?php } ?>
                                    </div>
									
									</div>
									<!--<div class="search_frm">
        <button type="button" name="chkRecordId" onclick="checkAll(true);"  class="btn btn-info">Select All</button>
        <button type="button" name="chkRecordId" onclick="checkAll(false);" class="btn btn-info">Unselect All</button>
        <?php global $accountStatus; ?>
       
        <div class="list_sel">{{Form::select('action', $accountStatus,null, ['class' => 'small form-control','placeholder' => 'Action for selected record', 'id' => 'action'])}}</div>
        <button type="submit" class="small btn btn-success btn-cons btn-info" onclick="return ajaxActionFunction();" id="submit_action">OK</button>
    </div>  -->
    {{ Form::close()}}
            </div>
			
@else 
<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
<div class="admin_no_record">No record found.</div>
@endif