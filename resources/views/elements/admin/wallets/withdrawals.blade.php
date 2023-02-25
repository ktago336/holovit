<style>
.my_text{
    font-size: 12px;
    margin-left: 55px;
    margin-top:-20px;
        }
</style>
<script type="text/javascript">
   
    function changestatus(status, id, current_status) {
   // alert(value+' '+id);
        if(confirm("Are you sure you want to change status?")){
            $("#loaderIDAct" + id).show();
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
            $.ajax({
                type: "POST",
                url: "<?php echo HTTP_PATH; ?>/admin/wallets/changestatus/" + id + "/" + status,
                cache: false,
                success: function(responseText) {
                    $("#loaderIDAct" + id).hide();
                    $("#status" + id).html(responseText);
                }
            });
        }else{
            $("#WithdrawalStatus" + id).val(current_status);
        }
        
    }
</script>
<div class="admin_loader" id="loaderID">{{HTML::image("public/img/website_load.svg", SITE_TITLE)}}</div>
@if(!$allrecords->isEmpty())
<div class="panel-body marginzero">
    <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
    {{ Form::open(array('method' => 'post', 'id' => 'actionFrom')) }}
    <section id="no-more-tables" class="lstng-section">
        <div class="topn">
            <div class="topn_left">Withdrawals List</div>
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
                         
                        <th class="sorting_paging">@sortablelink('Merchant.busineess_name', 'Business Name')</th>
                        <th class="sorting_paging">@sortablelink('amount', 'Requested Amount')</th>
                        <th class="sorting_paging">@sortablelink('description', 'Description')</th>
						<th class="sorting_paging">Status</th>
                        <th class="sorting_paging">@sortablelink('created_at', 'Created')</th>
                    </tr>
                </thead>
                <tbody>
				<?php global $withdrawal_status;; ?>
                    @foreach($allrecords as $allrecord)
                    <tr>
                        <!--<th style="width:5%"><input type="checkbox" onclick="javascript:isAllSelect(this.form);" name="chkRecordId[]" value="{{$allrecord->id}}" /></th>-->
						<td data-title="Business Name">{{isset($allrecord->Merchant->busineess_name)?$allrecord->Merchant->busineess_name:'N/A'}}</td>
                        <td data-title="Requested Amount">{{CURR.$allrecord->amount}}</td>
                        <td data-title="Description">{{$allrecord->description}}</td>
                        <td data-title="Status">
							<div id="loderstatus{{$allrecord->id}}" class="right_action_lo">{{HTML::image("public/img/loading.gif", SITE_TITLE)}}</div>
							<span id="status{{$allrecord->id}}">
									@if($allrecord->status == 0 || $allrecord->status == 1)
                                        {{Form::select('status',$withdrawal_status, $allrecord->status, ['class'=>'form-control required ', 'empty'=>false, 'autocomplete' => 'off', 'id' => 'WithdrawalStatus'.$allrecord->id, 'onchange' => "changestatus(this.value,'" . $allrecord->id . "','" . $allrecord->status . "')"])}}
									@else
										{{$withdrawal_status[$allrecord->status]}}
									@endif
							</span>
						</td>
                        
                        <td data-title="Payment Date">{{$allrecord->created_at}}</td>
						
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
        </div>
    </section>
    {{ Form::close()}}
</div>         
</div> 
@else 
<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
<div class="admin_no_record">No record found.</div>
@endif
