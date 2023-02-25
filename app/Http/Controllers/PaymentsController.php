<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\SendMailable;
use Mail;
use DB;
use Redirect;
use Session;
use App\Models\Payment;
use App\Models\Order;
use App\Models\User;
use App\Models\Admin;
use App\Models\Wallet;
use App\Models\Merchant;

class PaymentsController extends Controller {
    
    
    
    /*     * **Pay With PayPal** */

    public function paywithpaypal(Request $request, $slug = null) {
        $pageTitle = 'Payment With PayPal';
		$user_id = Session::get('user_id');
        $recordInfo = Order::where(['slug'=> $slug, 'status'=> 0, 'user_id'=> $user_id])->first();
		//echo "<pre>";print_r($recordInfo);exit;
		if($recordInfo){
			$total_amount = $recordInfo->total_price;
			$order_id = $recordInfo->id;
			$product_name = SITE_TITLE;

			$currencyID = urlencode('USD');
			$paymentType = urlencode('Sale');    // or 'Sale' //Authorization

			$totalAmt = urlencode($recordInfo->total_price);
			$currency = urlencode('USD');

			$settingsInfo = DB::table('settings')->where('id', 1)->first();
			$paypal_url = PAYPALURL;
			if($settingsInfo->payment_mode == 1){
				$paypal_url = PAYPALURLLIVE;
			}
			$paypal_email = $settingsInfo->paypal_email_address;

			return view('payments.paywithpaypal', ['paypal_url'=>$paypal_url,'paypal_email'=>$paypal_email,'title' => $pageTitle, 'amount' => $totalAmt, 'currency' => $currency, 'item_number' => $recordInfo->order_number, 'product_name' => $product_name, 'success_url' => HTTP_PATH . '/payments/success/' . $recordInfo->order_number, 'cancel_url' => HTTP_PATH . '/payments/paypalcancel/' . $recordInfo->order_number]);
		}else{
			Session::flash('error_message', "something went wrong!");
			return Redirect::to('/login');
		}
	}
    
    public function paypalcancel(Request $request, $order_number = null) {
        Session::flash('error_message', "Sorry, your payment could not be completed, please try again!");
        
        
        $recordInfo = Order::where('order_number', $order_number)->first();
        //print_r($recordInfo);exit;
        
		if($recordInfo){
			//set status as cancelled or delete order
			Order::where('id', $recordInfo->id)->update(array('order_status'=>'2','status'=>'1'));
			//Order::where('id', $recordInfo->id)->delete();
		}else{
		    
		    exit;
			Session::flash('error_message', "something went wrong!");
			return Redirect::to('/login');
		}
		//echo '/deals/ordersummary/'.$recordInfo->Merchant->slug.'/'.$recordInfo->order_summary_url;exit;
		
        return Redirect('/deals/ordersummary/'.$recordInfo->Merchant->slug.'/'.$recordInfo->order_summary_url);
    }

    public function success(Request $request, $order_number = null) {
		//echo "<pre>"; print_r($order_number);
		//print_r($_REQUEST);
		//exit;
        $pageTitle = 'Payment With PayPal';
        
		if($order_number){
			$recordInfo = Order::where('order_number', $order_number)->first();
		}elseif($_REQUEST['item_number']){
			$recordInfo = Order::where('order_number', $_REQUEST['item_number'])->first();
		}
        //print_r($recordInfo);exit;
        $total_amount = $recordInfo->total_price;
        $order_id = $recordInfo->id;
        //$booking_date_time = $recordInfo->booking_date_time;

        $currencyID = urlencode('USD');
        $paymentType = urlencode('Sale');    // or 'Sale' //Authorization

        if (isset($_REQUEST['txn_id'])) {
            $transactionId = $_REQUEST['txn_id'];
            $amountPaid = $_REQUEST['mc_gross'];
        } elseif ($_REQUEST['tx']) {
            $transactionId = $_REQUEST['tx'];
            $amountPaid = $_REQUEST['amt'];
        }
        $st = 'completed';
//echo '<pre>';print_r($transactionId);exit;
        $wallet_trn_id = $transactionId;
        $paymenttype = 'PayPal';

        $amount = $amountPaid;

        if ($transactionId) {
			
			Order::where('id', $recordInfo->id)->update(array('order_status'=>'1','status'=>'1'));
			$total_orders = $recordInfo->Merchant->total_orders;	
			Merchant::where('id', $recordInfo->Merchant->id)->update(array('total_orders'=>$total_orders)); 
			
            $serialisedData = array();
            $serialisedData['user_id'] = $recordInfo->user_id;
			$serialisedData['merchant_id'] = $recordInfo->merchant_id;
			$slug = $this->createSlug(bin2hex(openssl_random_pseudo_bytes(30)), 'payments');
            $serialisedData['slug'] = $slug;
            $serialisedData['order_number'] = $recordInfo->order_number;
            $serialisedData['status'] = 1;
            $serialisedData['amount'] = $total_amount;
            $serialisedData['order_id'] = $order_id;
            $serialisedData['transaction_id'] = $wallet_trn_id;
            Payment::insert($serialisedData);
            

			$total_price= $total_amount;
			$merchant_amount= $recordInfo->amount;
			$convenience_fees= $recordInfo->convenience_fees;
			$user_id = $recordInfo->user_id;
			$merchant_id = $recordInfo->merchant_id;
			$wallet_trn_id = 'LSGPAY'.$recordInfo->order_number;
			$serialisedData = array();
			$serialisedData['total_amount'] = $total_price;
			$serialisedData['amount'] = $merchant_amount;
			$serialisedData['admin_commission'] = $convenience_fees;
			$serialisedData['description'] = "Deal Payment";
			$serialisedData['add_minus'] = 0;
			$serialisedData['type'] = 0;
			$serialisedData['source'] = 'Pay via Paypal';
			$serialisedData['user_id'] = $user_id;
			//$serialisedData['merchant_id'] = $merchant_id;
			$serialisedData['order_id'] = $recordInfo->id;
			$serialisedData['status'] = 1;
			$slug = $this->createSlug('pay'.$user_id.time(), 'wallets');
			$serialisedData['slug'] = $slug;
			$serialisedData['trn_id'] = $wallet_trn_id;
			Wallet::insert($serialisedData);

            // Email sent to login user
//            $gigInfo = Gig::where('id', $recordInfo->gig_id)->first();
            $loginUserInfo = $recordInfo->User;
            $loginuser = $loginUserInfo->first_name . ' ' . $loginUserInfo->last_name;
			$user_email = $loginUserInfo->email_address;
			$user_contact = $loginUserInfo->contact;
            $amount = CURR . $total_amount;
            $transactionId = $wallet_trn_id;
            $datetime = date('M d, Y');
            $title = $recordInfo->Merchant->busineess_name;
			
			$user_order_detail_link = HTTP_PATH.'/users/orderdetail/'.$recordInfo->slug;
			
            $email = $emailId = $loginUserInfo->email_address;
            $emailTemplate = DB::table('emailtemplates')->where('id', 8)->first();
            $toRepArray = array('[!name!]', '[!title!]', '[!order_no!]', '[!total_price!]', '[!transactionId!]', '[!paymenttype!]', '[!link!]', '[!datetime!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
            $fromRepArray = array($loginuser, $title, $order_number, $amount, $transactionId, $paymenttype, $user_order_detail_link, $datetime, HTTP_PATH, SITE_TITLE);
            $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
            $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
            Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
			

            // Email sent to admin user
            /*$adminInfo = DB::table('admins')->where('id', 1)->first();
            $emailId = $adminInfo->email;
            $emailTemplate = DB::table('emailtemplates')->where('id', 7)->first();
            $toRepArray = array('[!name!]', '[!title!]', '[!email!]', '[!contact!]', '[!amount!]', '[!transactionId!]', '[!paymenttype!]', '[!datetime!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
            $fromRepArray = array($loginuser, $title, $email, $user_contact, $amount, $transactionId, $paymenttype, $datetime, HTTP_PATH, SITE_TITLE);
            $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
            $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
            Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));*/

            // Email sent to seller user
			$merchant_order_detail_link = HTTP_PATH.'/merchants/orderdetail/'.$recordInfo->slug;
            $sellerInfo = $recordInfo->Merchant;
            $emailId = $sellerInfo->email_address;
			$amountseller = $recordInfo->amount;
            $merchant_name = $sellerInfo->first_name . ' ' . $sellerInfo->last_name;
            $emailTemplate = DB::table('emailtemplates')->where('id', 11)->first();
            $toRepArray = array('[!merchant_name!]', '[!name!]', '[!title!]', '[!email!]', '[!contact!]', '[!amount!]', '[!transactionId!]', '[!paymenttype!]', '[!datetime!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
            $fromRepArray = array($merchant_name, $loginuser, $title, $email, $user_contact, $amountseller, $transactionId, $paymenttype, $datetime, $merchant_order_detail_link, HTTP_PATH, SITE_TITLE);
            $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
            $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
            //Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
//
//            Cart::where('id', $recordInfo->id)->delete();
            Session::flash('success_message', "Your order has submitted succeessfully.");
            return Redirect('/thank/'.$order_number);
        }
    }

}