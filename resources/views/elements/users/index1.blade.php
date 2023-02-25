<div class="admin_loader" id="loaderID">{{HTML::image("public/img/website_load.svg", SITE_TITLE)}}</div>
			@if(!$merchant->isEmpty())
<div class="hp">
		  <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
    {{ Form::open(array('method' => 'post', 'id' => 'actionFrom')) }}
    <div class="topn_rightd ddpagingshorting" id="pagingLinks" align="right">
        <div class="panel-heading" style="align-items:center;">
            {{$merchant->appends(Input::except('_token'))->render()}}

        </div>
    </div>
		<div class="table_responsive">
                <div class="table_dcf">
                    <div class="tr_tables">
<div class="td_tables"></div>
                        <div class="td_tables">@sortablelink('deal_name', 'Deal Name')</div>
                        <div class="td_tables">@sortablelink('discount', 'Discount')</div>

                        <div class="td_tables">@sortablelink('expire_date', 'Expire Date')</div>
                        <div class="td_tables">@sortablelink('created_on', 'Created On')</div>
                        <div class="td_tables">Action</div>

                    </div>
					<?php //print_r($merchant); exit;?>
					<?php foreach($merchant as $merchants){ ?>
                    <div class="tr_tables2">
<div data-title="Select Deals" class="td_tables2">
<input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="{{$merchants->id}}" />
</div>
                                            <div data-title="Address Title" class="td_tables2">
                                               <?php echo $merchants->deal_name; ?> 
                                            </div>
                                           
                                            <div data-title="Total Amount" class="td_tables2">
                                               <?php echo $merchants->discount; ?>% 
                                            </div>
                                           
                                            <div data-title="Address Title" class="td_tables2">
                                                                                              <?php echo $merchants->expire_date; ?> 
                                            </div>
                                            <div data-title="Created" class="td_tables2">
                                                <?php echo $merchants->created_at; ?>
                                            </div>
                                        
                                            <div data-title="Action" class="td_tables2">
                                         
                                                 <div class="actions">
												 <?php // if($merchants->status == '1'){ ?>
												            <!--<a href="{{URL('/merchant/deals/deactivate/'.$merchants->slug)}}" title="Deactivate" class="deactivate"><button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button></a>-->
												 <?php //}else{ ?>
												 <!--<a href="{{URL('/merchant/deals/activate/'.$merchants->slug)}}" title="Activate" class="activate"><button class="btn btn-danger btn-xs"><i class="fa fa-ban"></i></button></a>-->
												 <?php //} ?>
                                                            <a href="{{URL('/merchant/deals/edit/'.$merchants->slug)}}" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>  
                                                             <a href="{{URL('/merchant/deals/delete/'.$merchants->slug)}}" class="btn btn-primary btn-xs" onclick="return confirm('Are you sure you want to delete?')"><i class="fa fa-trash-o"></i></a>
                                                             <!--<a href="#" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i></a>-->                          

                                                          </div>
                                                       
                                                </div>
                                 
                                            </div>
											<?php } ?>
                                    </div>
									
									</div>
									<div class="search_frm">
        <button type="button" name="chkRecordId" onclick="checkAll(true);"  class="btn btn-info">Select All</button>
        <button type="button" name="chkRecordId" onclick="checkAll(false);" class="btn btn-info">Unselect All</button>
        <?php global $accountStatus; ?>
       
        <div class="list_sel">{{Form::select('action', $accountStatus,null, ['class' => 'small form-control','placeholder' => 'Action for selected record', 'id' => 'action'])}}</div>
        <button type="submit" class="small btn btn-success btn-cons btn-info" onclick="return ajaxActionFunction();" id="submit_action">OK</button>
    </div>  
    {{ Form::close()}}
            </div>
			
@else 
<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
<div class="admin_no_record">No record found.</div>
@endif