@extends('layouts.admin')
@section('content')
<?php
$parent_id = Session::get('parent_id');
$adminLId = Session::get('adminid');
$adminRols = App\Http\Controllers\Admin\AdminsController::getAdminRoles(Session::get('adminid'));
$checkSubRols = App\Http\Controllers\Admin\AdminsController::getAdminRolesSub(Session::get('adminid'));
//print_r($category);
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>Orders Details (Order Number: #{{$single_order->order_number}})</h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::to('admin/admins/dashboard')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li class="active">Orders</li>
        </ol>
    </section>

    <section class="content margin_top">
        <div class="box box-info">
            <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
            <div class="m_content" id="listID">
                <div class="">
       
        <div class="order_informetion informetion_bx">
            <div class="informetion_bxes"> 
			<div class="informetion_bxes" id="listID">
			
			
			
			
			
			<div class="admin_loader" id="loaderID">{{HTML::image("public/img/website_load.svg", SITE_TITLE)}}</div>
			@if($single_order)
<div class="hp">
		  <div class="ersu_message">@include('elements.admin.errorSuccessMessage')</div>
    {{ Form::open(array('method' => 'post', 'id' => 'actionFrom')) }}
			
			
			
			
			<div class="panel-body panel-body_ful" id="div_print">
                                    <?php //pr($single_order); die; ?>
                                    <?php if (isset($single_order)) {
                                    ?>
                                    <div id="printdiv1" class="print-div">
                                        <div id="printdiv" class="print-div">
                                            
                                            <div class="form-group">
                                                <div class="user-sec">
                                                    <div class="user-title">Order Details</div>
                                                    <div class="user-sec-in">
                                                        <div class="user-sec-left">Order Number</div>
                                                        <div class="user-sec-right"><?php echo $single_order->order_number; ?></div>
                                                    </div>
													<div class="user-sec-in">
                                                        <div class="user-sec-left">Voucher ID</div>
                                                        <div class="user-sec-right"><?php echo $single_order->voucher_number; ?></div>
                                                    </div>
                                                    <div class="user-sec-in">
                                                        <div class="user-sec-left">Voucher Status</div>
                                                        <div class="user-sec-right">
                                                            <?php echo $single_order->is_voucher_redeemed?"Redeemed":"Pending"; ?>
                                                        </div>
                                                    </div>
                                                    <div class="user-sec-in">
                                                        <div class="user-sec-left">Placed Date/Time</div>
                                                        <div class="user-sec-right"><?php echo $single_order->created_at; ?></div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
											<div class="form-group">
                                                <div class="user-sec">
                                                    <div class="user-title">Customer Details</div>
                                                    <div class="user-sec-in">
                                                        <div class="user-sec-left">Customer Name</div>
                                                        <div class="user-sec-right"><?php echo $single_order->User->first_name.' '.$single_order->User->last_name; ?></div>
                                                    </div>
													<div class="user-sec-in">
                                                        <div class="user-sec-left">Email Address</div>
                                                        <div class="user-sec-right"><?php echo $single_order->User->email_address; ?></div>
                                                    </div>
													<div class="user-sec-in">
                                                        <div class="user-sec-left">Contact Number</div>
                                                        <div class="user-sec-right"><?php echo $single_order->User->contact?$single_order->User->contact:'N/A'; ?></div>
                                                    </div>
                                                </div>
                                            </div>
											<div class="form-group">
                                                <div class="user-sec">
                                                    <div class="user-title">Merchant Details</div>
                                                    <div class="user-sec-in">
                                                        <div class="user-sec-left">Business Name</div>
                                                        <div class="user-sec-right"><?php echo $single_order->Merchant->busineess_name; ?></div>
                                                    </div>
													<div class="user-sec-in">
                                                        <div class="user-sec-left">Address</div>
                                                        <div class="user-sec-right"><?php echo $single_order->Merchant->address; ?></div>
                                                    </div>
                                                </div>
                                            </div>
											<div class="form-group">
                                                <div class="user-sec">
                                                    <div class="user-title">Payment Details</div>
                                                    <div class="user-sec-in">
                                                        <div class="user-sec-left">Transaction ID</div>
                                                        <div class="user-sec-right"><?php echo $single_order->Payment->transaction_id; ?></div>
                                                    </div>
													<div class="user-sec-in">
                                                        <div class="user-sec-left">Payment Method</div>
                                                        <div class="user-sec-right"><?php echo $single_order->Payment->payment_mode?$single_order->Payment->payment_mode:'Paypal'; ?></div>
                                                    </div>
                                                    <div class="user-sec-in">
                                                        <div class="user-sec-left">Payment Status</div>
                                                        <div class="user-sec-right">
                                                            <?php echo $single_order->Payment->status?"Paid":"Pending"; ?>
                                                        </div>
                                                    </div>
                                                    <div class="user-sec-in">
                                                        <div class="user-sec-left">Payment Date/Time</div>
                                                        <div class="user-sec-right"><?php echo $single_order->Payment->created_at; ?></div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="order-sec user-sec">
                                                    <div class="order-title user-title">Deals Details</div>
                                                    <div class="order-table-sec"  style="margin-left:-13px;">
                                                        <div class="order-table-head">
                                                            <div class="order-table-head-in">Deal Name</div>
                                                            <!--<div class="order-table-head-in">Base Price</div>-->
															<div class="order-table-head-in">Deal Price</div>
                                                            <div class="order-table-head-in">Quantity</div>
                                                            <div class="order-table-head-in">Sub Total</div>
                                                        </div>
                                                        <?php
                                                        if ($single_order->deals_id) {
															$dealsidarr = explode(',',$single_order->deals_id);
															$dealsbparr = explode(',',$single_order->deals_base_price);
															$dealsfparr = explode(',',$single_order->deals_final_price);
															$dealsqtyparr = explode(',',$single_order->deals_quantity);
                                                        foreach ($dealsidarr as $key=>$dealid) {
                                                        ?>
                                                        <div class="order-table-middel">
                                                            <div class="order-table-middel-in">
                                                                {{$deals[$dealid]}}
                                                            </div>
															<!--<div class="order-table-middel-in">{{CURR.$dealsbparr[$key]}}</div>-->
															<div class="order-table-middel-in">{{CURR.$dealsfparr[$key]}}</div>
															<div class="order-table-middel-in">{{$dealsqtyparr[$key]}}</div>
                                                            <div class="order-table-middel-in">{{CURR.($dealsfparr[$key]*$dealsqtyparr[$key])}}</div>
                                                        </div>
                                                        <!--                                        <div class="order-table-middel">
                                                            <div class="order-table-middel-in">
                                                                <div class="menucmtilet">
                                                                    fgfg
                                                                </div>
                                                                <div class="menucmt">
                                                                    <img src="https://demo.imagetowebpage.com/jardimverde/webroot/files/products/full/a1bb4_fdccb_student.png" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="order-table-middel-in">USD 2,000.00</div>
                                                            <div class="order-table-middel-in">1</div>
                                                            <div class="order-table-middel-in">USD 2,000.00</div>
                                                        </div>-->
                                                        <?php }
                                                        }
                                                        ?>
                                                        <div class="order-table-end">
                                                            <div class="order-table-end-in" style="border-right:0px;">Total</div>
                                                            <div class="order-table-end-in" style="border-right:0px;">&nbsp;</div>
                                                            <div class="order-table-end-in">&nbsp;</div>
                                                            <div class="order-table-middel-in-g"><?php echo CURR . ($single_order->amount-$single_order->convenience_fees); ?></div>
                                                        </div>
                                                        <!--                                        <div class="order-table-end">
                                                            <div class="order-table-end-in" style="border-right:0px;">Tax</div>
                                                            <div class="order-table-end-in" style="border-right:0px;">&nbsp;</div>
                                                            <div class="order-table-end-in">&nbsp;</div>
                                                            <div class="order-table-middel-in-g">0</div>
                                                        </div>-->
                                                        <div class="order-table-end">
                                                            <div class="order-table-end-in" style="border-right:0px;">Convenience Fees</div>
                                                            <div class="order-table-end-in" style="border-right:0px;">&nbsp;</div>
                                                            <div class="order-table-end-in">&nbsp;</div>
                                                            <div class="order-table-middel-in-g"><?php echo CURR . $single_order->convenience_fees; ?></div>
                                                        </div>
                                                        <div class="order-table-end">
                                                            <div class="order-table-end-in" style="border-right:0px;">Grand Total</div>
                                                            <div class="order-table-end-in" style="border-right:0px;">&nbsp;</div>
                                                            <div class="order-table-end-in">&nbsp;</div>
                                                            <div class="order-table-middel-in-g"><?php echo CURR . $single_order->amount; ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                           <?php /*         
                                    <div id="printdiv2" class="print-div">
                                        <div id="printdiv22" class="print-div">
                                            
                                            <div class="form-group">
                                                <div class="user-sec">
                                                    <div class="user-title">Order Details</div>
                                                    <div class="user-sec-in">
                                                        <div class="user-sec-left">Order Number</div>
                                                        <div class="user-sec-right"><?php echo $single_order->order_no; ?></div>
                                                    </div>
                                                    <div class="user-sec-in">
                                                        <div class="user-sec-left">Status</div>
                                                        <div class="user-sec-right">
                                                            <?php
                                                            global $order_status;
                                                            echo $order_status[$single_order->order_status];
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="user-sec-in">
                                                        <div class="user-sec-left"><?php echo __d('common', 'Placed Date/Time'); ?></div>
                                                        <div class="user-sec-right"><?php echo $single_order->created; ?></div>
                                                    </div>
                                                    <div class="user-sec-in">
                                                        <div class="user-sec-left"><?php echo __d('common', 'Reward Points'); ?></div>
                                                        <div class="user-sec-right">
                                                            <?php
                                                            $sum = 0;
                                                            if (isset($single_order->Orderitems)) {
                                                            foreach ($single_order->Orderitems as $key => $value) {
                                                            $sum+= $value->rewards_points;
                                                            }
                                                            //echo 'You Total Reward points are'.'  '.$sum;
                                                            echo __d('common', 'You Total Reward points are') . '  ' . $single_order->total_reward_points;
                                                            } else {
                                                            echo __d('common', 'No reward oints available.');
                                                            }
                                                        ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="user-sec">
                                                    <div class="user-title"><?php echo __d('common', 'Delivery Address Details'); ?></div>
                                                    <div class="user-sec-in">
                                                        <div class="user-sec-left"><?php echo __d('common', 'Name'); ?></div>
                                                        <div class="user-sec-right"><?php echo $single_order->shipping_name; ?></div>
                                                    </div>
                                                    <div class="user-sec-in">
                                                        <div class="user-sec-left"><?php echo __d('common', 'Address'); ?></div>
                                                        <div class="user-sec-right"><?php echo $single_order->shipping_address; ?></div>
                                                    </div>
                                                    <div class="user-sec-in">
                                                        <div class="user-sec-left"><?php echo __d('common', 'Email'); ?></div>
                                                        <div class="user-sec-right"><?php echo $single_order->shipping_email; ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="form-group">
                                                <div class="order-sec user-sec">
                                                    <div class="order-title user-title"><?php echo __d('common', 'Items Details'); ?></div>
                                                    <div class="order-table-sec">
                                                        <div class="order-table-head">
                                                            <div class="order-table-head-in"><?php echo __d('common', 'Item'); ?></div>
                                                            <div class="order-table-head-in"><?php echo __d('common', 'Base Price'); ?></div>
                                                            <div class="order-table-head-in"><?php echo __d('common', 'Quantity'); ?></div>
                                                            <div class="order-table-head-in"><?php echo __d('common', 'Sub Total'); ?></div>
                                                        </div>
                                                        <?php
                                                        if ($single_order->Orderitems) {
                                                        foreach ($single_order->Orderitems as $products_detail) {
                                                        ?>
                                                        <div class="order-table-middel">
                                                            <div class="order-table-middel-in">
                                                                <div class="menucmtilet">
                                                                    <?php echo $products_detail->product_title; ?>
                                                                </div>
                                                                <div class="menucmt">
                                                                    <?php
                                                                    if (!empty($products_detail->image)) {
                                                                    $image = $products_detail->image;
                                                                    $filePath = UPLOAD_THUMB_PRODUCT_IMAGE_PATH . $image;
                                                                    if (file_exists($filePath) && $image) {
                                                                    echo $this->Html->image(DISPLAY_THUMB_PRODUCT_IMAGE_PATH . $image, array('alt' => '', 'class' => 'image', 'url' => ['controller' => 'products', 'action' => 'details', $products_detail->slug]));
                                                                    }
                                                                    ?>
                                                                    <?php ?>
                                                                    <?php
                                                                    } else {
                                                                    echo $this->Html->image('no_image1.png', array('alt' => '', 'url' => ['controller' => 'products', 'action' => 'details', $products_detail->slug]));
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="order-table-middel-in"><?php echo CURR . $products_detail->price; ?></div>
                                                            <div class="order-table-middel-in"><?php echo $products_detail->quantity; ?></div>
                                                            <div class="order-table-middel-in"><?php echo CURR . $products_detail->subtotal; ?></div>
                                                        </div>
                                                        <!--                                        <div class="order-table-middel">
                                                            <div class="order-table-middel-in">
                                                                <div class="menucmtilet">
                                                                    fgfg
                                                                </div>
                                                                <div class="menucmt">
                                                                    <img src="https://demo.imagetowebpage.com/jardimverde/webroot/files/products/full/a1bb4_fdccb_student.png" alt="">
                                                                </div>
                                                            </div>
                                                            <div class="order-table-middel-in">USD 2,000.00</div>
                                                            <div class="order-table-middel-in">1</div>
                                                            <div class="order-table-middel-in">USD 2,000.00</div>
                                                        </div>-->
                                                        <?php }
                                                        }
                                                        ?>
                                                        <div class="order-table-end">
                                                            <div class="order-table-end-in" style="border-right:0px;"><?php echo __d('common', 'Total'); ?></div>
                                                            <div class="order-table-end-in" style="border-right:0px;">&nbsp;</div>
                                                            <div class="order-table-end-in">&nbsp;</div>
                                                            <div class="order-table-middel-in-g"><?php echo CURR . $single_order->total_amount; ?></div>
                                                        </div>
                                                        <!--                                        <div class="order-table-end">
                                                            <div class="order-table-end-in" style="border-right:0px;">Tax</div>
                                                            <div class="order-table-end-in" style="border-right:0px;">&nbsp;</div>
                                                            <div class="order-table-end-in">&nbsp;</div>
                                                            <div class="order-table-middel-in-g">0</div>
                                                        </div>-->
                                                        <div class="order-table-end">
                                                            <div class="order-table-end-in" style="border-right:0px;"><?php echo __d('common', 'Delivery Charge'); ?></div>
                                                            <div class="order-table-end-in" style="border-right:0px;">&nbsp;</div>
                                                            <div class="order-table-end-in">&nbsp;</div>
                                                            <div class="order-table-middel-in-g"><?php echo CURR . $single_order->shipping_charge; ?></div>
                                                        </div>
                                                        <div class="order-table-end">
                                                            <div class="order-table-end-in" style="border-right:0px;"><?php echo __d('common', 'Grand Total'); ?></div>
                                                            <div class="order-table-end-in" style="border-right:0px;">&nbsp;</div>
                                                            <div class="order-table-end-in">&nbsp;</div>
                                                            <div class="order-table-middel-in-g"><?php echo CURR . $single_order->total_amount; ?></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                    
                                    

*/?>



                                      


                                    <!--<div class="form-group" id="print_sec">
                                        <div class="user-sec">
                                            <div class="user-sec-in">
                                                <form>
                                                    <div class="user-sec-right in_upt">
                                                        <div class="in_upt in_upt_res" style="width:100% !important;">
                                                            <a href="javascript:void(0);"onclick="chkCancel()" class="btn btn btn-primary">Cancel</a>
                                                            <a title="Print This Order" class="icon-5 print btn btn-primary" href="javascript:void(0)" onclick="myFunction()">Print Order</a>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>-->
                                    <div class="chan_pich" style="width: 20%">
                                    </div>
                                    <?php } ?>
                                </div>
			
			
			
	{{ Form::close()}}
            </div>
			
@else 
<div id="listingJS" style="display: none;" class="alert alert-success alert-block fade in"></div>
<div class="admin_no_record">No record found.</div>
@endif		
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			</div>
        </div>
		</div>
    </div>
            </div>
        </div>
    </section>
</div>
@endsection