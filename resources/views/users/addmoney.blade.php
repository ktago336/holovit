@extends('layouts.inner')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<?php 
$percentage_commission = $adminInfo->deposit_commission;
$fixed_commission = $adminInfo->deposit_fixed_commission;

?>
<script>
    $(document).ready(function () {
        $('#walletform').validate();
        $('#WalletAmount').keyup(function(){
            //calculate servicefee and total payble amount
            var amount = parseFloat($('#WalletAmount').val());
            if(amount > 0){
                var percentage_commission = parseFloat("<?php echo $percentage_commission; ?>");
                var fixed_commission = parseFloat("<?php echo $fixed_commission; ?>");
                var admin_commission = parseFloat(parseFloat(amount*percentage_commission/100+fixed_commission).toFixed(2));
                var total_amount = amount+admin_commission;
                //$('#WalletAmount').val()
                $('#WalletAdminCommission').val(admin_commission);
                $('#WalletTotalAmount').val(total_amount);
            }else{
                $('#WalletAdminCommission').val(0);
                $('#WalletTotalAmount').val(0);
            }
                
        });
    });
	
</script>
<section class="listing_deal">
    <div class="container">


        <div class="panel panel-default">
            <div class="row"> 
                <div class="col-md-3">
                    @include('elements.left_menu')
                </div>
                <div class="col-md-9">
                    <div class="panel-body">
                        <div class="tab-pane" id="2">
                            <div class="add_deal">
                                <div class="informetion_top">
                                    <div class="tatils_0t1"> Add Money</div>
                                     <div class="er_mes">
                                    <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
                                </div>
                                    {{ Form::open(array('method' => 'post', 'id' => 'walletform', 'enctype' => "multipart/form-data")) }}
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Enter Amount ({{CURR}})<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::text('amount', null, ['id'=>'WalletAmount', 'min'=>1, 'class'=>'form-control required number', 'placeholder'=>'Enter Amount', 'autocomplete' => 'off'])}}
                                        </div>
                                    </div>
									<div class="form-group">
                                        <label class="col-sm-2 control-label">Service Fee ({{CURR}})<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::text('admin_commission', null, ['id'=>'WalletAdminCommission', 'class'=>'form-control required number', 'placeholder'=>'Service Fee', 'autocomplete' => 'off','readonly'])}}
                                        </div>
                                    </div> 
									<div class="form-group">
                                        <label class="col-sm-2 control-label">Total Amount ({{CURR}})<span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::text('total_amount', null, ['id'=>'WalletTotalAmount', 'class'=>'form-control required number', 'placeholder'=>'Total Amount', 'autocomplete' => 'off','readonly'])}}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Description <span class="require">*</span></label>
                                        <div class="col-sm-10">
                                            {{Form::textarea('description', null, ['class'=>'form-control required', 'placeholder'=>'Enter Description', 'autocomplete' => 'off', 'rows'=>4])}}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">&nbsp;</label>
                                        <div class="col-sm-10">
											<a href="javascript:void(0)" class="btn btn-primary" onclick="paybypaypal()">Pay Now</a>
                                            <!--{{Form::submit('Submit', ['class' => 'btn btn-info'])}}-->
                                            {{Form::reset('Reset', ['class' => 'btn btn-default canlcel_le'])}}

                                        </div>

                                    </div>
                                    {{ Form::close()}}
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function paybypaypal() {
        $('#walletform').attr("action", "<?php echo HTTP_PATH . '/users/payviapaypal' ?>");
        $('#walletform').submit();
    }
   

</script>
@endsection
