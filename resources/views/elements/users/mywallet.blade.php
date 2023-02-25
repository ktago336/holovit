<div class="admin_loader" id="loaderID">{{HTML::image("public/img/website_load.svg", SITE_TITLE)}}</div>
			@if(!$wallet->isEmpty())
<div class="hp">
		  <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
    {{ Form::open(array('method' => 'post', 'id' => 'actionFrom')) }}
    <div class="topn_rightd ddpagingshorting" id="pagingLinks" align="right">
        <div class="panel-heading" style="align-items:center;">
            {{$wallet->appends(Input::except('_token'))->render()}}

        </div>
    </div>
		<div class="table_responsive">
                <div class="table_dcf">
                    <div class="tr_tables">
						<div class="td_tables">@sortablelink('source', 'Amount Type')</div>
						<div class="td_tables">@sortablelink('amount', 'Amount')</div>
                        <div class="td_tables">@sortablelink('trn_id', 'Transaction ID')</div>
                        <div class="td_tables">@sortablelink('created', 'Created On')</div>

                    </div>
					<?php //print_r($merchant); exit;?>
					<?php foreach($wallet as $merchants){ ?>
                    <div class="tr_tables2">
                                            <div data-title="Address Title" class="td_tables2">
                                               <?php echo $merchants->source; ?> 
                                            </div>
                                           
                                            <div data-title="Total Amount" class="td_tables2">
                                               <?php echo CURR.$merchants->amount; ?>
                                            </div>
                                           
                                            <div data-title="Address Title" class="td_tables2">
                                                 <?php echo "#".$merchants->trn_id; ?> 
                                            </div>
                                            <div data-title="Created" class="td_tables2">
                                                <?php echo $merchants->created_at; ?>
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