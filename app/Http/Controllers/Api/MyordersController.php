<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\SendMailable;
use Mail;
use DB;
use Redirect;
use Session;
use Input;
use Response;
use App\Models\Payment;
use App\Models\Servicesoffer;
use App\Models\Service;
use App\Models\Wallet;
use App\Models\Myorder;
use App\Models\Review;
use App\Models\Gig;
use App\Models\Gigmessage;
use App\Models\Notification;
use App\Models\User;

class MyordersController extends Controller {

//    public function __construct() {
//        $this->middleware('is_userlogin', ['except' => ['']]);
//    }

    public function sellingorders() {
        $tokenData = $this->requestAuthentication('GET', 1);
        $userid = $tokenData['user_id'];
        $allrecords = Myorder::where('seller_id', $userid)->orderBy('id', 'DESC')->get();
        $dd = 0;
        global $gigOrderStatus;
        $data = array();
        if (!empty($allrecords)) {
            foreach ($allrecords as $allrecord) {
                $data[$dd]['id'] = $allrecord->id;
                $data[$dd]['date'] = $allrecord->created_at->format('d M, Y');
                if (isset($allrecord->Buyer->first_name)) {
                    $data[$dd]['buyer_name'] = $allrecord->Buyer->first_name . ' ' . $allrecord->Buyer->last_name;
                } else {
                    $data[$dd]['buyer_name'] = '';
                }
                if (isset($allrecord->Gig->title)) {
                    $data[$dd]['title'] = $allrecord->Gig->title;
                } else {
                    $data[$dd]['title'] = '';
                }
                if ($allrecord->pay_type === 'Wallet')
                    $data[$dd]['order_id'] = $allrecord->wallet_trn_id;
                else
                    $data[$dd]['order_id'] = $allrecord->paypal_trn_id;
                
                $data[$dd]['isuserrated'] = $allrecord->is_seller_rate;
                $data[$dd]['amount'] = CURR . $allrecord->revenue;
                $data[$dd]['status'] = $gigOrderStatus[$allrecord->status];
                $dd++;
            }
        }
        $this->successOutputResult('Selling orders', json_encode($data));
    }

    public function buyingorders() {
        $tokenData = $this->requestAuthentication('GET', 1);
        $userid = $tokenData['user_id'];
        $allrecords = Myorder::where('buyer_id', $userid)->orderBy('id', 'DESC')->get();
        $dd = 0;
        global $gigOrderStatus;
        $data = array();
        if (!empty($allrecords)) {
            foreach ($allrecords as $allrecord) {
                $data[$dd]['id'] = $allrecord->id;
                $data[$dd]['date'] = $allrecord->created_at->format('d M, Y');
                if (isset($allrecord->Seller->first_name)) {
                    $data[$dd]['seller_name'] = $allrecord->Seller->first_name . ' ' . $allrecord->Seller->last_name;
                } else {
                    $data[$dd]['seller_name'] = '';
                }
                if (isset($allrecord->Gig->title)) {
                    $data[$dd]['title'] = $allrecord->Gig->title;
                } else {
                    $data[$dd]['title'] = '';
                }
                if ($allrecord->pay_type === 'Wallet')
                    $data[$dd]['order_id'] = $allrecord->wallet_trn_id;
                else
                    $data[$dd]['order_id'] = $allrecord->paypal_trn_id;
                
                $data[$dd]['isuserrated'] = $allrecord->is_buyer_rate;
                $data[$dd]['amount'] = CURR . $allrecord->revenue;
                $data[$dd]['status'] = $gigOrderStatus[$allrecord->status];
                $data[$dd]['package'] = $allrecord->package;
                $dd++;
            }
        }
        $this->successOutputResult('Buying orders', json_encode($data));
    }
    
    public function orderdetail() {
        $tokenData = $this->requestAuthentication('POST', 0);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $order_id = $userData['order_id'];
        $isbuyingorder = $userData['isbuyingorder'];
        global $gigOrderStatus;

        $orderInfo = Myorder::where('id', $order_id)->first();
        
        $data = array();
        $data['id'] = $orderInfo->id;
        $data['date'] = $orderInfo->created_at->format('d M, Y');        
        if (isset($orderInfo->Gig->title)) {
            $data['title'] = $orderInfo->Gig->title;
        } else {
            $data['title'] = '';
        }
        if ($orderInfo->pay_type === 'Wallet')
            $data['order_id'] = $orderInfo->wallet_trn_id;
        else
            $data['order_id'] = $orderInfo->paypal_trn_id;

        $data['gig_id'] = $orderInfo->gig_id;
        $data['description'] = $orderInfo->Gig->description;
        $data['posted_on'] = $orderInfo->created_at->diffForHumans();
        $data['amount'] = CURR . $orderInfo->revenue;
        $data['status'] = $gigOrderStatus[$orderInfo->status];
        $data['package'] = $orderInfo->package;        
        $data['payment_type'] = $orderInfo->pay_type;
        
        $userdataorder = array();       
        if ($isbuyingorder)
        {
            $userdataorder = $orderInfo->Seller;
            $data['user_id'] = $orderInfo->seller_id;              
        }
        else
        {
            $userdataorder = $orderInfo->Buyer;
            $data['user_id'] = $orderInfo->buyer_id;
        }
        
        $data['user_image'] = $userdataorder->profile_image;
        $data['user_average_rating'] = $userdataorder->average_rating;
        $data['user_total_review'] = $userdataorder->total_review;
        $data['about_user'] = $userdataorder->description;
        if (isset($userdataorder->first_name)) {
            $data['user_name'] = $userdataorder->first_name . ' ' . $userdataorder->last_name;
        } else {
            $data['user_name'] = '';
        }
         
        $farray = array();
        if (isset($userdataorder->city) && $userdataorder->city != '') {
            $farray[] = $userdataorder->city;
        }
        if (isset($userdataorder->Country->name) && $userdataorder->Country->name != '') {
            $farray[] = $userdataorder->Country->name;
        }

        $data['member_since'] = date('F Y', strtotime($userdataorder->created_at));
        
        $langArray = array();
        if ($userdataorder->languages) {
            foreach (json_decode($userdataorder->languages) as $key => $lang) {
                $langArray[$key] = $lang->lang_name;
            }
        }

        $data['user_from'] = implode(', ', $farray);
        $data['languages'] = implode(', ', $langArray);

        $this->successOutputResult('Order Detail', json_encode($data));
        exit;
    }

    /*     * ********************** Wallet ************** */

    public function earnings() {
        $tokenData = $this->requestAuthentication('GET', 1);
        $userid = $tokenData['user_id'];
        $allrecords = Wallet::where('user_id', $userid)->orderBy('id', 'DESC')->get();
        $amountArray = $this->getWallerAmount($userid);

        $dd = 0;
        $walletdata = array();
        if (!empty($allrecords)) {
            $fromarray = array('<b>', '</b>');
            $toarray = array('', '');
            foreach ($allrecords as $allrecord) {
                $walletdata[$dd]['id'] = $allrecord->id;
                $walletdata[$dd]['date'] = $allrecord->created_at->format('d M, Y h:i A');
                $walletdata[$dd]['source'] = str_replace($fromarray, $toarray, $allrecord->source);
                if ($allrecord->status == 1)
                    $walletdata[$dd]['transaction_id'] = $allrecord->trn_id;
                else
                    $walletdata[$dd]['transaction_id'] = '';

                if ($allrecord->status == 0)
                    $walletdata[$dd]['status'] = 'Pending';
                elseif ($allrecord->status == 1)
                    $walletdata[$dd]['status'] = 'Approved';
                else
                    $walletdata[$dd]['status'] = 'Rejected';

                if ($allrecord->type == 4)
                    $amount = '-' . CURR . number_format(-$allrecord->revenue, 2);
                elseif ($allrecord->revenue < 0)
                    $amount = '-' . CURR . number_format(-$allrecord->revenue, 2);
                else
                    $amount = '+' . CURR . number_format($allrecord->revenue, 2);

                $walletdata[$dd]['amount'] = $amount;
                $dd++;
            }
        }
        $data = array();

        $cdata['0']['name'] = 'Net Income';
        $cdata['0']['amount'] = CURR . number_format($amountArray['netincome'], 2);
        $cdata['1']['name'] = 'Withdrawn';
        $cdata['1']['amount'] = CURR . number_format(-$amountArray['withdrawn'], 2);
        $cdata['2']['name'] = 'Used for Purchases';
        $cdata['2']['amount'] = CURR . number_format(-$amountArray['userforpurchase'], 2);
        $cdata['3']['name'] = 'Pending Clearance';
        $cdata['3']['amount'] = CURR . number_format(-$amountArray['pendingclearance'], 2);
        $cdata['4']['name'] = 'Available for Withdrawal';
        $cdata['4']['amount'] = CURR . number_format($amountArray['availableforwithdraw'], 2);



//        $edata['netincome'] = CURR.number_format($amountArray['netincome'], 2);
//        $edata['withdrawn'] = CURR.number_format(-$amountArray['withdrawn'], 2);
//        $edata['userforpurchase'] = CURR.number_format(-$amountArray['userforpurchase'], 2);
//        $edata['pendingclearance'] = CURR.number_format(-$amountArray['pendingclearance'], 2);
//        $edata['availableforwithdraw'] = CURR.number_format($amountArray['availableforwithdraw'], 2);
        $siteSettings = DB::table('settings')->where('id', 1)->first();
//        $edata['minimum_withdraw_amount'] = CURR.$siteSettings->minimum_withdraw_amount;
        $cdata['5']['name'] = 'Minimum Withdraw Amount';
        $cdata['5']['amount'] = CURR . $siteSettings->minimum_withdraw_amount;
        $data['earning_main'] = $cdata;
        $data['earning_records'] = $walletdata;
        $this->successOutputResult('My earnings', json_encode($data));
    }
    
    public function paymenthistory() {
        $tokenData = $this->requestAuthentication('GET', 1);
        $userid = $tokenData['user_id'];
        $allrecords  = Payment::where('user_id', $userid)->orderBy('id', 'DESC')->get();

        $dd = 0;
        $data =array();
        if (!empty($allrecords)) {
            foreach ($allrecords as $allrecord) {
                $data[$dd]['id'] = $allrecord->id;
                $data[$dd]['user_name'] = $allrecord->User?$allrecord->User->first_name.' '.$allrecord->User->last_name:'';
                $data[$dd]['date'] = $allrecord->created_at->format('d M, Y h:i A');
                if($allrecord->gig_id){
                    $title = $allrecord->Gig?$allrecord->Gig->title:'';
                }elseif($allrecord->service_id){
                    $title = $allrecord->Service?$allrecord->Service->title:'';
                }
                $data[$dd]['title'] = $title;
                $data[$dd]['amount'] = $allrecord->amount;
                $data[$dd]['transaction_id'] = $allrecord->transaction_id;
                if($allrecord->status){
                    $status = 'Completed';
                }else{
                    $status = 'Pending';
                }
                $data[$dd]['status'] = $status;
                
                $dd++;
            }
        }
        
        $this->successOutputResult('Payment History', json_encode($data));
    }
   
   
     public function rateandreview() {
        
        $tokenData = $this->requestAuthentication('POST', 0);
        $userid = $tokenData['user_id'];
        $data = $_POST['jsonData'];
        $userData = json_decode($data, true);
        $id = $userData['order_id'];
        
        $myorderInfo  = Myorder::where('id', $id)->first();
                                  
        $serialisedData = array();
        $serialisedData['as_a'] = $userData['rateto'];
        $serialisedData['user_id'] = $userData['ratetouser_id'];
        $serialisedData['otheruser_id'] = $userid;
        $serialisedData['rating'] = $userData['rating'];
        $serialisedData['comment'] = $userData['message'];
        $serialisedData['myorder_id'] = $id;
        $serialisedData['servicesoffer_id'] = 0;        
        $serialisedData['status'] = 1;
        $serialisedData['slug'] = bin2hex(openssl_random_pseudo_bytes(10));
        $serialisedData = $this->serialiseFormData($serialisedData);
        Review::insert($serialisedData); 
        
        $gigInfo = Gig::where('id', $myorderInfo->gig_id)->first();
        $loginUserInfo = User::where('id', $userid)->first();
        $loginuser = $loginUserInfo->first_name . ' ' . $loginUserInfo->last_name;
        $selleruser = '';
        $emailId = '';
        if ($userData['rateto'] == 'seller')
        {
            $selleruser = $myorderInfo->Seller->first_name . ' ' . $myorderInfo->Seller->last_name;
            $emailId = $myorderInfo->Seller->email_address;
        }
        else
        {
            $selleruser = $myorderInfo->Buyer->first_name . ' ' . $myorderInfo->Buyer->last_name;
            $emailId = $myorderInfo->Buyer->email_address;
        }
        $title = $gigInfo->title;
        
        $emailTemplate = DB::table('emailtemplates')->where('id', 17)->first();
        $toRepArray = array('[!username!]', '[!title!]', '[!loginuser!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
        $fromRepArray = array($selleruser, $title, $loginuser, HTTP_PATH, SITE_TITLE);
        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
//        Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
        
        if ($userData['rateto'] == 'seller')
        {
            Myorder::where('id', $myorderInfo->id)->update(array('is_buyer_rate'=>1));
            $this->updateUserRating($myorderInfo->seller_id, 'seller');
        }
        else
        {
            Myorder::where('id', $myorderInfo->id)->update(array('is_seller_rate'=>1));
            $this->updateUserRating($myorderInfo->buyer_id, 'buyer');
        }

        $this->successOutput('Your rating is submit successfully.');
    }

}

?>