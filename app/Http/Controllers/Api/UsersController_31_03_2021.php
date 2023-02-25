<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Cookie;
use Session;
use Redirect;
use Input;
use Validator;
use DB;
use Mail;
use App\Mail\SendMailable;
use Socialite;
use App\Models\User;
use App\Models\Admin;
use App\Models\Deal;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Review;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\Banner;
use App\Models\Setting;
use App\Models\City;
use App\Models\State;
use App\Models\Contact;

class UsersController extends Controller {

    public function __construct() {

    }

    public function logindata($userCheck) {
        $data = array();
        $data['user_id'] = $userCheck->id;
        $data['first_name'] = $userCheck->first_name;
        $data['last_name'] = $userCheck->last_name;
        $data['email_address'] = $userCheck->email_address;
        $data['contact'] = $userCheck->contact;
        $token = $this->setToken($userCheck);
        $data['token'] = $token;
        return $data;
    }

   public function dashboard() {
        $tokenData = $this->requestAuthentication('POST', 1);
        $user_id = $tokenData['user_id'];    
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $categories = DB::table('categories')->where('id', $userData['category_id'])->first();  
    $query = new Merchant();
    $query = $query->sortable()->with('currentDeal');
        if(!empty($categories)){
            if($categories->parent_id > 0){
                $sub_category_id = $categories->id;
                $business_type_id = $categories->parent_id;
		if(($userData['city_id'] !='')){
                $query = $query->whereRaw("FIND_IN_SET($sub_category_id, service_ids)")->where('city_id',$userData['city_id']);}
		else{$query = $query->whereRaw("FIND_IN_SET($sub_category_id, service_ids)");}
                $sub_category_id_arr = DB::table('categories')->where(['parent_id' => $categories->parent_id, 'status' => 1])->select(['parent_id', 'id','slug','category_name'])->get();
                $sub_category_id_arr = array_filter(json_decode(json_encode($sub_category_id_arr),true));
            }
        else{
        $business_type_id = $categories->id;
		if(($userData['city_id']!='')){
                $query = $query->where('business_type', $business_type_id)->where('city_id',$userData['city_id']);}
		else{$query = $query->where('business_type', $business_type_id);}
                $sub_category_id_arr = DB::table('categories')->where(['parent_id' => $categories->id, 'status' => 1])->select('parent_id', 'id','slug','category_name')->get();
                $sub_category_id_arr = array_filter(json_decode(json_encode($sub_category_id_arr),true));
        
        }
        }
    $i=0;$sum= 0;$j=1;
        $merchants = $query->paginate(20);
    foreach($merchants as $details[$i]){
    $data['response_data'][$i]['id'] = $details[$i]['id'];
    $data['response_data'][$i]['busineess_name'] = $details[$i]['busineess_name'];
    if(isset($details[$i]->City)){$data['response_data'][$i]['city_name'] = $details[$i]->City->name;}
    else{$data['response_data'][$i]['city_name'] = '';}
    if(isset($details[$i]->currentDeal)){$data['response_data'][$i]['deal_name'] = $details[$i]->currentDeal->deal_name;}
    else{$data['response_data'][$i]['deal_name'] = '';}
    if(isset($details[$i]->currentDeal)){$data['response_data'][$i]['final_price'] = $details[$i]->currentDeal->final_price;}
    else{$data['response_data'][$i]['final_price'] = '';}
    if($details[$i]['service_ids']){$sids=explode(',',$details[$i]['service_ids']);
    $serviceobj = DB::table('services')->where(['status' => 1])->whereIn('id', $sids)->orderBy('name', 'ASC')->pluck('name', 'id');
    $data['response_data'][$i]['services'] = implode(', ',json_decode(json_encode($serviceobj),true));
    }
    else{$data['response_data'][$i]['services'] = '';}
    if($details[$i]['profile_image']){ $image = explode(',', $details[$i]['profile_image']);
    $data['response_data'][$i]['profile_image'] = $image[0];
    }
    else{$data['response_data'][$i]['profile_image'] = '';}
    $data['response_data'][$i]['bought'] = count($details[$i]->allOrder);
    $reviewdata = DB::table('reviews')->where('merchant_id',$details[$i]['id'])->get();
    if(isset($reviewdata)){
    foreach($reviewdata as $info){
            $sum = $sum + $info->rating;
            $j++;
            }
    //print_r($j);exit;
        if($j>1){
          $average = $sum / ($j-1);
          $avg_img = ceil($average);
    $j++;
    $data['response_data'][$i]['rating'] = $avg_img;
    }
        else{$data['response_data'][$i]['rating'] = 0;}
    }
     
    $i++;   
    }
	if(empty($data['response_data'])){$data['response_data'] = array();}
        $data['response_status'] = 'success';
        $data['response_msg'] = '';
        echo json_encode($data);
    }


    public function register() {
        $this->requestAuthentication('POST');
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        if ($userData['user_type'] == 'customer') {
            $errorr = 0;
            $userInfo = User::where('email_address', $userData['email_address'])->first();
            if (!empty($userInfo)) {
                $this->errorOutputResult('Email address already exist.');
            }
            $serialisedData['first_name'] = $userData['first_name'];
            $serialisedData['last_name'] = $userData['last_name'];
            $serialisedData['user_type'] = $userData['user_type'];
            $serialisedData['email_address'] = $userData['email_address'];
            //$serialisedData['contact'] = $userData['contact'];
            $serialisedData['device_id'] = $userData['device_id'];
            $serialisedData['device_type'] = $userData['device_type'];
            $serialisedData['password'] = $this->encpassword($userData['password']);
            $serialisedData['slug'] = $this->createSlug($userData['first_name'] . ' ' .$userData['last_name'], 'users');
            $serialisedData['activation_status'] = 0;
            $serialisedData['user_status'] = "Offline";
            $serialisedData['status'] = 0;
            $serialisedData['created_at'] = date('Y-m-d H:i:s');
            $serialisedData['updated_at'] = date('Y-m-d H:i:s');
            $uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
            $serialisedData['unique_key'] = $uniqueKey;
	 
            if(User::insert($serialisedData)) {
                $last_row = DB::table('users')->orderBy('id', 'DESC')->first();
                    //mail send
                $userId = $last_row ->id;        
                $link = HTTP_PATH . "/email-confirmation/" . $uniqueKey;
                $name =  $userData['first_name'] . ' ' .  $userData['last_name'];
                $emailId =  $userData['email_address'];
                $new_password =  $userData['password'];
		
                $emailTemplate = DB::table('emailtemplates')->where('id', 3)->first();
                $toRepArray = array('[!email!]', '[!username!]', '[!password!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($emailId, $name, $new_password, $link, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
                $data['response_data']['user_id'] = $userId;
                $data['response_msg'] = 'We have sent you an account activation link by email. Please check your spam folder if you do not receive the email within the next few minutes.';
		$data['response_status'] = 'success';
    
                echo json_encode($data);
                exit;
            }
        } 
    }

    public function login() {
        $this->requestAuthentication('POST');
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $email = $userData['email_address'];
        $password = $userData['password'];
        $device_type = $userData['device_type'];
        $device_id = $userData['device_id'];

        $userInfo = User::where('email_address', $email)->first();        
        if (!empty($userInfo)) {
            if (password_verify($password, $userInfo->password)) {
                if ($userInfo->status == 1 && $userInfo->activation_status == 1) {
                    //print_r($userInfo);exit;
                    $data = $this->logindata($userInfo);
	            $token = $data['token'];
                    User::where('id', $userInfo->id)->update(array('device_type' => $device_type, 'device_id' => $device_id, 'token' => $token));
                    $this->successOutputResult('login sucessfully', json_encode($data));
                } else if ($userInfo->status == 1 && $userInfo->activation_status == 0) {
                    $error = 'You need to activate your account before login.';
                } else if ($userInfo->status == 0 && $userInfo->activation_status == 0) {
                    $error = 'Your account might have been temporarily disabled. Please contact us for more details.';
                }
            } else {
                $error = 'Invalid email or password.';
            }
            $this->errorOutputResult($error);
        } else {
            $this->errorOutputResult('You entered wrong username or password.');
        }
        
    }  


    public function forgotPassword() {
        $this->requestAuthentication('POST');

        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        //print_r($userData);exit;
        $userInfo = User::where('email_address', $userData['email_address'])->first();
        if (!empty($userInfo)) {
            $uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
            User::where('id', $userInfo->id)->update(array('forget_password_status' => 1, 'unique_key' => $uniqueKey));

            $link = HTTP_PATH . "/reset-password/" . $uniqueKey;
            $name = ucwords($userInfo->first_name . ' ' . $userInfo->last_name);
            $emailId = $userInfo->email_address;
            $emailTemplate = DB::table('emailtemplates')->where('id', 4)->first();
            $toRepArray = array('[!username!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
            $fromRepArray = array($name, $link, HTTP_PATH, SITE_TITLE);
            $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
            $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
	    //print_r($emailId);exit;
            Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));	
             $msgString = "A link to reset your password was sent to your email address.";
                echo $this->successOutput($msgString);
                exit;
        }
        else
        {
            $msgstring = "Please enter valid email address.";
                    echo $this->errorOutputResult($msgstring);
                    exit;
        }
        
    }

    public function changepassword() {
        $tokenData = $this->requestAuthentication('POST');
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $old_password = $userData['old_password'];
        $newpassword = $userData['new_password'];
        $recordInfo = User::where('id', $user_id)->first();
        if (!password_verify($old_password, $recordInfo->password)) {
            $this->errorOutputResult('Current password is not correct.');
        } else if ($old_password == $newpassword) {
            $this->errorOutputResult('You can not change new password same as current password.');
        } else {
            $new_password = $this->encpassword($newpassword);
            User::where('id', $user_id)->update(array('password' => $new_password));
            $this->successOutput('Your Password has been changed successfully.');
        }       

    }

     public function editprofile() {
        $tokenData = $this->requestAuthentication('POST');
        $userid = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $serialisedData = $userData;
        User::where('id',$userid)->update($serialisedData);
        $userInfo = User::where('id', $userid )->first();                           
        $userDetails['user_id'] = $userInfo->id;
        $userDetails['user_type'] = $userInfo->user_type;
        $userDetails['first_name'] = $userInfo->first_name;
        $userDetails['last_name'] = $userInfo->last_name;
        $userDetails['email_address'] = $userInfo->email_address;
        if($userInfo->contact != ''){
        $userDetails['contact'] = $userInfo->contact;}
        else{$userDetails['contact'] = "";}

        $data['response_data'] = $userDetails;
        $data['response_status'] = 'success';
        $data['response_msg'] = 'Your Details Updated Successfully';
        echo json_encode($data);
        exit;
    }

    public function getprofile() {
        $tokenData = $this->requestAuthentication('GET', 1);
        $user_id = $tokenData['user_id'];      
        $userInfo = User::where('id', $user_id)->first();   
                            
        $userDetails['user_id'] = $userInfo->id;
        $userDetails['user_type'] = $userInfo->user_type;
        $userDetails['first_name'] = $userInfo->first_name;
        $userDetails['last_name'] = $userInfo->last_name;
        if($userInfo->contact != ''){
        $userDetails['contact'] = $userInfo->contact;}
        else{$userDetails['contact'] = "";}
        $userDetails['email_address'] = $userInfo->email_address;
        

        $data['response_data'] = $userDetails;
        $data['response_status'] = 'success';
        $data['response_msg'] = '';
        echo json_encode($data);
    }

    public function myorders()
    {
        $tokenData = $this->requestAuthentication('GET');
        $userid = $tokenData['user_id'];
        $ordersData = Order::where('user_id',$userid)->get();
        $data['response_data'] = array();
        $i = 0;
        foreach ($ordersData as $details[$i]) { 
            $data['response_data'][$i]['id'] = $details[$i]['id'];
            $data['response_data'][$i]['order_number'] = $details[$i]['order_number'];
            $data['response_data'][$i]['voucher_number'] = $details[$i]['voucher_number'];
            $data['response_data'][$i]['amount'] = $details[$i]['amount'];
            if($details[$i]['is_voucher_redeemed'] == 1){$data['response_data'][$i]['is_voucher_redeemed'] = "Redeemed";}
            else {$data['response_data'][$i]['is_voucher_redeemed'] = "Pending";}
            $data['response_data'][$i]['created_at'] = $details[$i]['created_at']->format('M d Y');
            $i++;
        }
        if (!empty($data)) {
            $data['response_status'] = 'success';
            $data['response_msg'] = '';
            echo json_encode($data);
            exit;
        }
        else {
            echo $this->errorOutputResult('No orders are found!');
            exit;
        }
        exit;
    }

    public function mywallet()
    {
        $tokenData = $this->requestAuthentication('GET');
        $userid = $tokenData['user_id'];
	$userInfo = User::where('id', $userid)->first();
        $walletsData = Wallet::where('user_id',$userid)->get();
        $data['response_data'] = array();
        $i = 0;
	$detailsarray = array();
        foreach ($walletsData as $details[$i]) { 
	    $detailsarray[] = array('id'=>$details[$i]['id'],'amount_type'=>$details[$i]['source'],'amount'=>$details[$i]['amount'],'transaction_id'=>$details[$i]['trn_id'],'created_at'=>$details[$i]['created_at']->format('M d Y'));
	     $data['response_data']['Wallet'] = $detailsarray;          
            //$data['response_data']['Wallet'][$i]['id'] = $details[$i]['id'];
            //$data['response_data']['Wallet'][$i]['amount_type'] = $details[$i]['source'];
            //$data['response_data'][$i]['amount'] = $details[$i]['amount'];
            //$data['response_data'][$i]['transaction_id'] = $details[$i]['trn_id'];
            //$data['response_data'][$i]['created_at'] = $details[$i]['created_at']->format('M d Y');
            $i++;
	  
        }
	

        if (!empty($data)) {
            $data['response_data']['wallet_balance'] = $userInfo->wallet_balance;
            $data['response_status'] = 'success';
            $data['response_msg'] = '';
            echo json_encode($data);
            exit;
        }
        else {
            echo $this->errorOutputResult('No records are found!');
            exit;
        }
        exit;
    }

    public function mypayments()
    {
        $tokenData = $this->requestAuthentication('GET');
        $userid = $tokenData['user_id'];
        $paymentsData = Payment::where('user_id',$userid)->get();
        $data['response_data'] = array();
        $i = 0;
        foreach ($paymentsData as $details[$i]) {
            $ordersData = Order::where('user_id',$userid)->get(); 
            $data['response_data'][$i]['id'] = $details[$i]['id'];
            if(isset($details[$i]->Order->order_number)){$data['response_data'][$i]['order_number'] = $details[$i]->Order->order_number;}
            else{$data['response_data'][$i]['order_number'] = '';}
            $data['response_data'][$i]['transaction_id'] = $details[$i]['transaction_id'];
	    if(isset($details[$i]->Merchant->busineess_name)){$data['response_data'][$i]['merchant_name'] = $details[$i]->Merchant->busineess_name;}
            else { $data['response_data'][$i]['merchant_name'] = '';}
            if($details[$i]['payment_mode'] != ''){$data['response_data'][$i]['payment_mode'] = $details[$i]['payment_mode'];}
            else { $data['response_data'][$i]['payment_mode'] = "Paypal";}
            $data['response_data'][$i]['amount'] = $details[$i]['amount'];
            $data['response_data'][$i]['created_at'] = $details[$i]['created_at']->format('M d Y');
            $i++;
        }
        if (!empty($data)) {
            $data['response_status'] = 'success';
            $data['response_msg'] = '';
            echo json_encode($data);
            exit;
        }
        else {
            echo $this->errorOutputResult('No Payments history are found!');
            exit;
        }
        exit;
    }

    public function categorylist()
    {
        $tokenData = $this->requestAuthentication('GET', 1);
        $user_id = $tokenData['user_id'];
        $categoryData = Category::where('parent_id',0)->where('status',1)->get();
        $data['response_data'] = array();
        $i = 0;
        foreach ($categoryData as $details[$i]) { 
         $data['response_data'][$i]['id'] = $details[$i]['id'];
         $data['response_data'][$i]['category_name'] = $details[$i]['category_name'];
         $data['response_data'][$i]['category_image'] = $details[$i]['category_image'];
         if($details[$i]['category_desc'] == ""){ $data['response_data'][$i]['category_desc'] = "";}
         else { $data['response_data'][$i]['category_desc'] = $details[$i]['category_desc'];}
         $data['response_data'][$i]['is_feature'] = $details[$i]['is_feature'];
         $data['response_data'][$i]['slug'] = $details[$i]['slug'];
         $data['response_data'][$i]['status'] = $details[$i]['status'];
         $data['response_data'][$i]['is_deleted'] = $details[$i]['is_deleted'];
         $data['response_data'][$i]['created_at'] = $details[$i]['created_at']->format('M d, Y');
         $data['response_data'][$i]['updated_at'] = $details[$i]['updated_at']->format('M d, Y');
         $i++;   
        }
        if (!empty($data)) {
            $data['response_status'] = 'success';
            $data['response_msg'] = '';
            echo json_encode($data);
            exit;
        }
        else {
            echo $this->errorOutputResult('No Category found!');
            exit;
        }
        exit;
    }

    public function subcategorylist()
    {
        $tokenData = $this->requestAuthentication('POST', 1);
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $subcategoryData = Category::where('parent_id',$userData['id'])->where('status',1)->get();
        $data['response_data'] = array();
        $i = 0;
        foreach ($subcategoryData as $details[$i]) {
         $data['response_data'][$i]['id'] = $details[$i]['id'];
         $data['response_data'][$i]['category_name'] = $details[$i]['category_name'];
         $data['response_data'][$i]['category_image'] = $details[$i]['category_image'];
         if($details[$i]['category_desc'] == ""){ $data['response_data'][$i]['category_desc'] = "";}
         else { $data['response_data'][$i]['category_desc'] = $details[$i]['category_desc'];}
         $data['response_data'][$i]['is_feature'] = $details[$i]['is_feature'];
         $data['response_data'][$i]['slug'] = $details[$i]['slug'];
         $data['response_data'][$i]['status'] = $details[$i]['status'];
         $data['response_data'][$i]['is_deleted'] = $details[$i]['is_deleted'];
         $data['response_data'][$i]['created_at'] = $details[$i]['created_at']->format('M d, Y');
         $data['response_data'][$i]['updated_at'] = $details[$i]['updated_at']->format('M d, Y');
         $i++;   
        }
        if (!empty($data)) {
            $data['response_status'] = 'success';
            $data['response_msg'] = '';
            echo json_encode($data);
            exit;
        }
        else {
            echo $this->errorOutputResult('No Category found!');
            exit;
        }
        exit;
    }

    public function addmoney()
    {
        $tokenData = $this->requestAuthentication('POST', 1);
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);

        $deal = "deposit";
        $serialisedData['description'] = $userData['description'];
        $serialisedData['user_id'] = $user_id;
        $serialisedData['total_amount'] = $userData['total_amount'];
        $serialisedData['amount'] = $userData['amount'];
        $serialisedData['trn_id'] = $userData['transaction_id'];
        $serialisedData['slug'] = $this->createSlug($deal, 'deals');
        $serialisedData['created_at'] = date('Y-m-d H:i:s');
        $serialisedData['updated_at'] = date('Y-m-d H:i:s');
        $serialisedData['status'] = 1;
        $serialisedData['add_minus'] = 1;
        $serialisedData['type'] = 0;
	$serialisedData['admin_commission'] = 0.00;
        $serialisedData['source'] = "Deposit via PayPal";
        Wallet::insert($serialisedData);

        $last_row = DB::table('wallets')->orderBy('id', 'DESC')->first();
        $serialisedData1['user_id'] = $user_id;
        $serialisedData1['wallet_id'] = $last_row->id;
        $serialisedData1['amount'] = $userData['amount'];
        $serialisedData1['transaction_id'] = $userData['transaction_id'];
        $serialisedData1['created_at'] = date('Y-m-d H:i:s');
        $serialisedData1['updated_at'] = date('Y-m-d H:i:s');
        $serialisedData1['status'] = 1;
        $slug = $this->createSlug(bin2hex(openssl_random_pseudo_bytes(30)), 'payments');
        $serialisedData1['slug'] = $slug;
        Payment::insert($serialisedData1);

	$userDetail = User::where('id', $user_id)->first();
        $updated_balance = $userDetail->wallet_balance + $userData['amount'];
        User::where('id', $user_id)->update(array('wallet_balance'=>$updated_balance)); 
        Wallet::where('id', $last_row->id)->update(array('status'=>'1'));

        $data['response_data'] = '';
        $data['response_msg'] = 'Money added to wallet successfully';
        $data['response_status'] = 'success';
        echo json_encode($data);
        exit;
    }

     public function orderdetails()
    {
        $tokenData = $this->requestAuthentication('POST', 1);
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $orderdetails = Order::where('id',$userData['id'])->first();

        $data['response_data']['order_number'] = $orderdetails->order_number;
        $data['response_data']['voucher_number'] = $orderdetails->voucher_number;
        if($orderdetails->is_voucher_redeemed == 1) { $data['response_data']['status'] = "Redeemed";}
        else { $data['response_data']['status'] = "Pending"; }
        $data['response_data']['date_time'] = $orderdetails->created_at->format('Y-m-d H:s:i');
        $data['response_data']['busineess_name'] = $orderdetails->Merchant->busineess_name;
        $data['response_data']['address'] = $orderdetails->Merchant->address;
        if($orderdetails->Payment->transaction_id != ''){$data['response_data']['transaction_id'] = $orderdetails->Payment->transaction_id;}
        else{$data['response_data']['transaction_id'] = 'N/A';}
        if($orderdetails->Payment->payment_mode != ''){$data['response_data']['Payment']['payment_mode'] = $orderdetails->Payment->payment_mode;}
        else{$data['response_data']['payment_mode'] = 'Paypal';}
        if(isset($orderdetails->Payment->status)){$data['response_data']['status'] = 'Paid';}
        else{$data['response_data']['status'] = 'Pending';}
        if($orderdetails->Payment->created_at != ''){$data['response_data']['date_time'] = $orderdetails->Payment->created_at->format('Y-m-d H:s:i');}
        else{$data['response_data']['date_time'] = 'N/A';}

        $dealsidarr = explode(',',$orderdetails->deals_id);
        $dealsbparr = explode(',',$orderdetails->deals_base_price);
        $dealsfparr = explode(',',$orderdetails->deals_final_price);
        $dealsqtyparr = explode(',',$orderdetails->deals_quantity);
        foreach ($dealsidarr as $key=>$dealid) {  
	    $dealsdetails = Deal::where('id',$dealid)->first();
	     $data['response_data']['Deal'][$key] =array('deal_name'=>$dealsdetails->deal_name,'deal_price'=>$dealsfparr[$key],'quantity'=>$dealsqtyparr[$key],'sub_total'=>($dealsfparr[$key]*$dealsqtyparr[$key]));
	 
	    //$data['response_data']['Deal']['deal_name'] = $dealsdetails->deal_name;
            //$data['response_data']['Deal']['deal_price'] = $dealsfparr[$key];
            //$data['response_data']['Deal']['quantity'] = $dealsqtyparr[$key];
            //$data['response_data']['Deal']['sub_total'] = ($dealsfparr[$key]*$dealsqtyparr[$key]);
        }

        $data['response_data']['total'] = ($orderdetails->total_price - $orderdetails->convenience_fees);
        $data['response_data']['convenience_fees'] = $orderdetails->convenience_fees;
        $data['response_data']['grand_total'] = $orderdetails->total_price;
        $data['response_msg'] = '';
        $data['response_status'] = 'success';
        echo json_encode($data);
        exit;
    }

    public function review()
    {
        $tokenData = $this->requestAuthentication('POST', 1);
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userDetails = json_decode($reqData, true);
        $userData = User::where('id', $user_id)->first();
        $recordInfo = Order::where('id', $userDetails ['id'])->first();

        $isAlreadyRated = Review::where('user_id',$user_id)->where('order_id',$recordInfo['id'])->first();
        if($isAlreadyRated)
        {
            $data['response_data']= '';
            $data['response_msg'] = 'Already Review send for this order';
            $data['response_status'] = 'success';
            echo json_encode($data);
            exit;
        }

        $serialisedData['order_id'] = $recordInfo['id'];
        $serialisedData['slug'] = time();
        $serialisedData['status'] = 1;
        $serialisedData['comment'] = $userDetails['comment'];
        $serialisedData['rating'] = $userDetails['rating'];
        $serialisedData['user_id'] = $user_id;
        $serialisedData['merchant_id'] = $recordInfo['merchant_id'];
        $serialisedData['created_at'] = date('Y-m-d H:i:s');
        $serialisedData['updated_at'] =  date('Y-m-d H:i:s');
        Review::insert($serialisedData);

        $name = $recordInfo['Merchant']['name'];
        $user_name = $userData['first_name'].' '.$userData['last_name'];
        $order_no = $recordInfo['order_number'];
        $comment = $userDetails ['comment'];
        $emailId = $recordInfo['Merchant']['email_address'];

        $emailTemplate = DB::table('emailtemplates')->where('id', 20)->first();
        $toRepArray = array('[!name!]', '[!user_name!]', '[!order_no!]', '[!comment!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
        $fromRepArray = array($name, $user_name, $order_no, $comment, HTTP_PATH, SITE_TITLE);
        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
        Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

        $data['response_data']['rating'] = $userDetails['rating'];
        $data['response_data']['comment'] = $userDetails['comment'];
        $data['response_msg'] = 'Review send successfully';
        $data['response_status'] = 'success';
        echo json_encode($data);
        exit;
    }

    public function dealdetails()
    {        
        $tokenData = $this->requestAuthentication('POST', 1);
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
	//print_r($reqData );exit;
        $dealsdata = Merchant::where('id', $userData['id'])->first();
        $data['response_data']['id'] = $dealsdata->id;
	$alldealsdata = $dealsdata->allDeal;
	$k = 0;
	foreach($alldealsdata as $dealinfo[$k]){
	$data['response_data']['Deal'][$k]['id'] = $dealinfo[$k]['id'];	
        $data['response_data']['Deal'][$k]['deal_name'] = $dealinfo[$k]['deal_name'];
        $data['response_data']['Deal'][$k]['voucher_price'] = $dealinfo[$k]['voucher_price'];
        $data['response_data']['Deal'][$k]['final_price'] = $dealinfo[$k]['final_price'];
        $data['response_data']['Deal'][$k]['valid_for'] = $dealinfo[$k]['valid_for'];
        if(isset($dealinfo[$k]['description'])){$data['response_data']['Deal'][$k]['description'] = $dealinfo[$k]['description'];}
        else{$data['response_data']['Deal'][$k]['description'] = '';}
	if($dealinfo[$k]['deal_start_time']){
        $startdatetime = strtotime(date('Y-m-d').' '.$dealinfo[$k]['deal_start_time']);
        $enddatetime = strtotime(date('Y-m-d').' '.$dealinfo[$k]['deal_end_time']);
        if(date('i',$startdatetime) > 0){$start_timing = date('h:i A',$startdatetime);}else{$start_timing = date('h A',$startdatetime);}
        if(date('i',$enddatetime) > 0){$end_timing = date('h:i A',$enddatetime);
        }else{$end_timing = date('h A',$enddatetime);}
        $data['response_data']['Deal'][$k]['deal_start_time'] = $start_timing;
        $data['response_data']['Deal'][$k]['deal_end_time'] = $end_timing;
        }
	$k++;
        }
        $data['response_data']['busineess_name'] = $dealsdata->busineess_name;
        if(isset($dealsdata->about_us)){$data['response_data']['about_us'] = $dealsdata->about_us;}
        else{$data['response_data']['about_us'] = '';}
        if(isset($dealsdata->city_id)){$data['response_data']['city_name'] = $dealsdata->City->name;}
        else{$data['response_data']['city_name'] = '';}
	if($dealsdata->zipcode != ''){$data['response_data']['zip_code'] = $dealsdata->zipcode;}
	else{$data['response_data']['zip_code'] = '';}
        $data['response_data']['contact'] = $dealsdata->contact;
	$data['response_data']['cost_for_two'] = '2,600';
        $data['response_data']['profile_image'] = explode(',',$dealsdata->profile_image);
	if(isset($dealsdata->facebook_link)){$data['response_data']['facebook_link'] = trim($dealsdata->facebook_link);}
        else{$data['response_data']['facebook_link'] = '';}
        if(isset($dealsdata->instagram_link)){$data['response_data']['instagram_link'] = $dealsdata->instagram_link;}
        else{$data['response_data']['instagram_link'] = '';}
        if(isset($dealsdata->linkedin_link)){$data['response_data']['linkedin_link'] = $dealsdata->linkedin_link;}
        else{$data['response_data']['linkedin_link'] = '';}
        if(isset($dealsdata->twitter_link)){$data['response_data']['twitter_link'] = $dealsdata->twitter_link;}
        else{$data['response_data']['twitter_link'] = '';}
        if(isset($dealsdata->google_link)){$data['response_data']['google_link'] = $dealsdata->google_link;}
        else{$data['response_data']['google_link'] = '';}
        if(isset($dealsdata->youtube_link)){$data['response_data']['youtube_link'] = $dealsdata->youtube_link;}
        else{$data['response_data']['youtube_link'] = '';}
	global $week_days;
        $working_days_arr = explode(',',$dealsdata->working_days);
        $start_time_arr = explode(',',$dealsdata->start_time);
        $end_time_arr = explode(',',$dealsdata->end_time);
        $i = 0;$j=0;
	
        foreach($week_days as $wd_key[$i]=>$wd_val[$i]){
            if(in_array($wd_key[$i], $working_days_arr)){
                //$data['response_data']['Daytime'][$i]['days'] = $wd_val[$i];
                //$data['response_data']['Daytime'][$i]['start_time'] = date("h:i A",strtotime($start_time_arr[array_search ($wd_key[$i],$working_days_arr)]));
                //$data['response_data']['Daytime'][$i]['end_time'] = date("h:i A",strtotime($end_time_arr[array_search ($wd_key[$i],$working_days_arr)]));
        $days = $wd_val[$i];
        $start_time = date("h:i A",strtotime($start_time_arr[array_search ($wd_key[$i],$working_days_arr)]));
        $end_time = date("h:i A",strtotime($end_time_arr[array_search ($wd_key[$i],$working_days_arr)]));
        
            }
	else{
		$days= $wd_val[$i];$start_time = '';$end_time = '';
	}
           
        $data['response_data']['daytime'][$i] = array('days' => $days,'start_time' => $start_time, 'end_time' => $end_time);
            $i++;
        }
	$data['response_data']['wallet_amount'] = $dealsdata->wallet_balance;
        $data['response_data']['photo'] = explode(',',$dealsdata->profile_image);
        $data['response_msg'] = '';
        $data['response_status'] = 'success';
        echo json_encode($data);
        exit;
    }

     public function purchase()
    {
        $tokenData = $this->requestAuthentication('POST', 1);
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
	//$purchaseData = unset($reqData);
        $userData = json_decode($reqData, true);
	//print_r("purchase");exit;
	$settingInfo = Setting::where('id', 1)->first();
        $recordInfo = Merchant::where('id', $userData['merchant_id'])->first();
	$userInfo = User::where('id', $user_id)->first();

        $merchant_id = $userData['merchant_id'];
        $order_number = rand(111,999).$merchant_id.$user_id.rand(111,999);
        $voucher_number = "LSGOV".time().$user_id.$merchant_id;
        $convenience_fees = $recordInfo->convenience_fees;  
        $coupon_id = 0;$updated_balance =0;
        $sub_total = 0;$deals_id = '';$deals_quantity = '';$deals_base_price ='';$deals_final_price ='';
        $coupon_discount = 0;
        $coupon_discount_price = 0; 
	$offerinfo = '15_1';
	$ii =0;
	foreach($userData['deals'] as $dealsid[$ii]){
		if($ii == 0){$deals_id = $dealsid[$ii]['id'];
		$deals_quantity = $dealsid[$ii]['quantity'];
		$deals_base_price = $dealsid[$ii]['base_price'];
		$deals_final_price = $dealsid[$ii]['final_price'];}
		else{$deals_id = $deals_id.','.$dealsid[$ii]['id'];
		$deals_quantity = $deals_quantity.','.$dealsid[$ii]['quantity'];
		$deals_base_price = $deals_base_price.','.$dealsid[$ii]['base_price'];
		$deals_final_price = $deals_final_price.','.$dealsid[$ii]['final_price'];}
		$deal_id = $dealsid[$ii]['id'];
		$qty = $dealsid[$ii]['quantity'];
		$dealInfo = Deal::where(['id'=>$deal_id, 'merchant_id'=>$merchant_id, 'status'=>1])->first();
		if($dealInfo && $qty > 0){
		  $sub_total = $sub_total + $dealInfo->final_price * $qty;
		}
		$ii++;
	}
	$order_summary_url = $offerinfo;
        $total_price = $sub_total + $convenience_fees - $coupon_discount_price;
        $merchant_amount = $sub_total;
        $amount = $sub_total;
        $percentage_commision_price = ($settingInfo->percentage_commision_price / 100); 
        $get_admin_commission = ($percentage_commision_price * $total_price);
        if($get_admin_commission < ($settingInfo->fixed_commision_price)){
            $get_admin_commission =  $settingInfo->fixed_commision_price;
        }
        $admin_commission   = $get_admin_commission;
        $merchant_amount = $total_price - $admin_commission;
        $order_status = 0;
        $status = 0;
        $slug = $this->createSlug($order_number.$user_id.$merchant_id, 'orders');
        
        $barc = $this->barcode( BARCODE_FULL_UPLOAD_PATH . $voucher_number.'.png', $voucher_number);

        $serialisedData = $this->serialiseFormData();   
        $serialisedData['user_id'] = $user_id;
        $serialisedData['merchant_id'] = $merchant_id;
        $serialisedData['order_number'] = $order_number;
        $serialisedData['voucher_number'] = $voucher_number;
        $serialisedData['deals_id'] = $deals_id;
        $serialisedData['deals_quantity'] = $deals_quantity;
        $serialisedData['deals_base_price'] = $deals_base_price;
        $serialisedData['deals_final_price'] = $deals_final_price;
        $serialisedData['convenience_fees'] = $convenience_fees;
        $serialisedData['coupon_id'] = $coupon_id;
        $serialisedData['coupon_discount'] = $coupon_discount;
        $serialisedData['coupon_discount_price'] = $coupon_discount_price;
        //$serialisedData['order_summary_url'] = $order_summary_url;
        $serialisedData['total_price'] = $total_price;
        $serialisedData['amount'] = $merchant_amount;
        $serialisedData['admin_commission'] = $convenience_fees;
        $serialisedData['order_status'] = $order_status;
        $serialisedData['status'] = $status;
        $serialisedData['slug'] = $slug;

	if(isset($userData['payment_type'])){
        if(($userData['payment_type'] == 'Wallet') && ($userInfo->wallet_balance < $total_price))
	{
	  	$data['response_data'] = '';
        	$data['response_msg'] = 'You have unsufficient balance. Please upgrade your wallet amount!';
        	$data['response_status'] = 'error';
        	echo json_encode($data);
        	exit;	
	}
	else{
		if($userData['payment_type'] == 'Wallet'){
		$updated_balance = $userInfo->wallet_balance - $total_price;
		User::where('id', $userInfo->id)->update(array('wallet_balance'=>$updated_balance));
		}
		if($userData['payment_type'] == 'Paypal'){$update_bal = 0;}
		if($updated_balance >=0 || $update_bal == 0){
		   Order::insert($serialisedData);
		   $orderInfo = Order::where('order_number', $order_number)->first();
		   $order_id = $orderInfo->id;
						$wallet_trn_id = 'LSGPAY'.$order_number;
						$paymenttype = "Wallet";
						
						$serialisedData1 = $this->serialiseFormData(); 
						$serialisedData1['total_amount'] = $total_price;
						$serialisedData1['amount'] = $merchant_amount;
						//$serialisedData1['admin_commission'] = $convenience_fees;
						$serialisedData1['admin_commission'] = $admin_commission;
						$serialisedData1['description'] = "Deal Payment";
						$serialisedData1['add_minus'] = 0;
						$serialisedData1['type'] = 0;
						if($userData['payment_type'] == 'Wallet'){$source = "Pay via wallet";}
						else{$source = "Pay via Paypal";}
						$serialisedData1['source'] = $source;
						$serialisedData1['user_id'] = $user_id;
						//$serialisedData1['merchant_id'] = $merchant_id;
						$serialisedData1['order_id'] = $orderInfo->id;
						$serialisedData1['status'] = 1;
						$slug = $this->createSlug('pay'.$user_id.time(), 'wallets');
						$serialisedData1['slug'] = $slug;
						$serialisedData1['trn_id'] = $wallet_trn_id;
						//print_r($serialisedData1);exit;
						Wallet::insert($serialisedData1);
						$walletInfo = Wallet::where('slug', $slug)->first();	

						$serialisedData2 = $this->serialiseFormData(); 
						$serialisedData2['user_id'] = $user_id;
						$serialisedData2['merchant_id'] = $merchant_id;
						if($userData['payment_type'] == 'Wallet'){$serialisedData2['wallet_id'] = $walletInfo->id;
						}
						else{$serialisedData2['wallet_id'] = 0;}
						$slug = $this->createSlug(bin2hex(openssl_random_pseudo_bytes(30)), 'payments');
						$serialisedData2['slug'] = $slug;
						$serialisedData2['order_number'] = $order_number;
						$serialisedData2['status'] = 1;
						if($userData['payment_type'] == 'Wallet'){$mode = "Wallet";}
						else{$mode = "";}
						$serialisedData2['payment_mode'] = $mode;
						$serialisedData2['amount'] = $total_price;
						$serialisedData2['order_id'] = $orderInfo->id;
						$serialisedData2['transaction_id'] = $wallet_trn_id;
						//print_r($serialisedData2);exit;
						Payment::insert($serialisedData2);
						Order::where('id', $order_id)->update(array('order_status'=>'1','status'=>'1'));    

						$total_orders = $recordInfo->total_orders;		
						Merchant::where('id', $merchant_id)->update(array('total_orders'=>$total_orders)); 
		}
	}
	}
	
	    $data['response_data'] = '';
            $data['response_msg'] = 'you have purchase order successfully';
            $data['response_status'] = 'success';
            echo json_encode($data);
            exit;
    }

    public function details()
    {
        $tokenData = $this->requestAuthentication('GET', 1);
        $user_id = $tokenData['user_id'];
        //$reqData = $_POST['data'];
        //$userData = json_decode($reqData, true);
        $categoryData = Category::where('parent_id','>',0)->where('status',1)->where('is_feature',1)->whereNotNull('category_image')->get();
        $banners = Banner::where('status', 1)->whereNotNull('banner_image')->orderBy('id', 'ASC')->get();
	$topBrands = Merchant::where([ 'status' => 1])->whereNotNull('profile_image')->select(['id','profile_image','slug'])->orderBy('total_orders', 'DESC')->take(8)->get();
	
	$featCategories = Category::where([ 'status' => 1, 'parent_id' => 0])->whereNotNull('category_image')->orderBy('category_name', 'ASC')->get();
	$dealsliders = array();
	foreach($featCategories  as $cat){
	$query = new Merchant();
	//$query = $query->sortable()->with(['currentDeal']);
			
	$query = $query->where('merchants.status', 1);
	$query = $query->where('merchants.business_type', $cat->id);
	$query->join('localities', 'localities.id', '=', 'merchants.locality_id');
	$query->join('categories', 'categories.id', '=', 'merchants.business_type');
	$query->join('deals', 'deals.merchant_id', '=', 'merchants.id')->where('deals.status', 1)->whereDate('expire_date', '>=', date('Y-m-d'))->groupBy('merchants.id')
				->select('deals.id','deals.voucher_price', 'deals.discount', 'deals.final_price', 'merchants.business_type', 'merchants.busineess_name', 'merchants.profile_image', 'merchants.slug', 'localities.locality_name', 'categories.slug as catslug', 'categories.category_name');

	$query->orderBy('merchants.id', 'DESC');
	//$limit = 8;		
	$dealsliders[$cat->id] = $query->get();
	//$dealsliders = $query->get();
	} 
	
	$data['response_data'] = array();
        $i = 0;$j=0;$k=0;$l=0;
        foreach ($categoryData as $details[$i]) { 
         $data['response_data']['category_list'][$i]['id'] = $details[$i]['id'];
         $data['response_data']['category_list'][$i]['category_name'] = $details[$i]['category_name'];
         $data['response_data']['category_list'][$i]['category_image'] = $details[$i]['category_image'];
         if($details[$i]['category_desc'] == ""){ $data['response_data']['category_list'][$i]['category_desc'] = "";}
         else { $data['response_data']['category_list'][$i]['category_desc'] = $details[$i]['category_desc'];}
         $data['response_data']['category_list'][$i]['is_feature'] = $details[$i]['is_feature'];
         $data['response_data']['category_list'][$i]['slug'] = $details[$i]['slug'];
         $data['response_data']['category_list'][$i]['status'] = $details[$i]['status'];
         $data['response_data']['category_list'][$i]['is_deleted'] = $details[$i]['is_deleted'];
         $data['response_data']['category_list'][$i]['created_at'] = $details[$i]['created_at']->format('M d, Y');
         $data['response_data']['category_list'][$i]['updated_at'] = $details[$i]['updated_at']->format('M d, Y');
         $i++;   
        }
	foreach ($banners as $banner[$j]) { 
	     $data['response_data']['banner_list'][$j]['id'] = $banner[$j]['id'];
             $data['response_data']['banner_list'][$j]['title'] = $banner[$j]['title'];
             $data['response_data']['banner_list'][$j]['banner_image'] = $banner[$j]['banner_image'];
             $data['response_data']['banner_list'][$j]['banner_url'] = $banner[$j]['banner_url'];
	     $j++;
	}
	foreach ($topBrands as $brands[$k]) { 
	     $data['response_data']['top_brands_list'][$k]['id'] = $brands[$k]['id'];
             $data['response_data']['top_brands_list'][$k]['slug'] = $brands[$k]['slug'];
             $data['response_data']['top_brands_list'][$k]['profile_image'] = $brands[$k]['profile_image'];
             $k++;
	}
	foreach($dealsliders as $dealvalue){
    	foreach( $dealvalue as $d){
	$merchatimgarr = explode(',',$d['profile_image']);
	$detailsarray[] = array('id'=>$d['id'],'busineess_name'=>$d['busineess_name'],'locality_name'=>$d['locality_name'],'voucher_price'=>['voucher_price'],'final_price'=>$d['final_price'],'discount'=>$d['discount'],'profile_image'=>$merchatimgarr[0]);
	$data['response_data']['deals_list'] = $detailsarray; 
        //print_r($d['busineess_name']);
      
    	}
   	}
	foreach ($dealsliders as $deals[$l]) { 
	   //$merchatimgarr = explode(',',$deals['profile_image']); 
	   //$data['response_data']['deals_list'][$l]['id'] = $deals[$l]['id'];
	   //$data['response_data']['deals_list'][$l]['busineess_name'] = $deals[$l]['busineess_name'];
	   //$data['response_data']['deals_list'][$l]['locality_name'] = $deals[$l]['locality_name'];
	   //$data['response_data']['deals_list'][$l]['voucher_price'] = $deals[$l]['voucher_price'];
	  // $data['response_data']['deals_list'][$l]['final_price'] = $deals[$l]['final_price'];
	  // $data['response_data']['deals_list'][$l]['discount'] = $deals[$l]['discount'];
	   //$data['response_data']['deals_list']['profile_image'] = $merchatimgarr[0];
	   $l++;
	}
        if (!empty($data)) {
            $data['response_status'] = 'success';
            $data['response_msg'] = '';
            echo json_encode($data);
            exit;
        }
        else {
            echo $this->errorOutputResult('No Details are found!');
            exit;
        }
        exit;
    }



    public function barcode( $filepath="", $text="0", $size="20", $orientation="horizontal", $code_type="code128", $print=true, $SizeFactor=1 ) {
    	$code_string = "";
    	// Translate the $text into barcode the correct $code_type
    	if ( in_array(strtolower($code_type), array("code128", "code128b")) ) {
    		$chksum = 104;
    		// Must not change order of array elements as the checksum depends on the array's key to validate final code
    		$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","\`"=>"111422","a"=>"121124","b"=>"121421","c"=>"141122","d"=>"141221","e"=>"112214","f"=>"112412","g"=>"122114","h"=>"122411","i"=>"142112","j"=>"142211","k"=>"241211","l"=>"221114","m"=>"413111","n"=>"241112","o"=>"134111","p"=>"111242","q"=>"121142","r"=>"121241","s"=>"114212","t"=>"124112","u"=>"124211","v"=>"411212","w"=>"421112","x"=>"421211","y"=>"212141","z"=>"214121","{"=>"412121","|"=>"111143","}"=>"111341","~"=>"131141","DEL"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","FNC 4"=>"114131","CODE A"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
    		$code_keys = array_keys($code_array);
    		$code_values = array_flip($code_keys);
    		for ( $X = 1; $X <= strlen($text); $X++ ) {
    			$activeKey = substr( $text, ($X-1), 1);
    			$code_string .= $code_array[$activeKey];
    			$chksum=($chksum + ($code_values[$activeKey] * $X));
    		}
    		$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
    
    		$code_string = "211214" . $code_string . "2331112";
    	} elseif ( strtolower($code_type) == "code128a" ) {
    		$chksum = 103;
    		$text = strtoupper($text); // Code 128A doesn't support lower case
    		// Must not change order of array elements as the checksum depends on the array's key to validate final code
    		$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","NUL"=>"111422","SOH"=>"121124","STX"=>"121421","ETX"=>"141122","EOT"=>"141221","ENQ"=>"112214","ACK"=>"112412","BEL"=>"122114","BS"=>"122411","HT"=>"142112","LF"=>"142211","VT"=>"241211","FF"=>"221114","CR"=>"413111","SO"=>"241112","SI"=>"134111","DLE"=>"111242","DC1"=>"121142","DC2"=>"121241","DC3"=>"114212","DC4"=>"124112","NAK"=>"124211","SYN"=>"411212","ETB"=>"421112","CAN"=>"421211","EM"=>"212141","SUB"=>"214121","ESC"=>"412121","FS"=>"111143","GS"=>"111341","RS"=>"131141","US"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","CODE B"=>"114131","FNC 4"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
    		$code_keys = array_keys($code_array);
    		$code_values = array_flip($code_keys);
    		for ( $X = 1; $X <= strlen($text); $X++ ) {
    			$activeKey = substr( $text, ($X-1), 1);
    			$code_string .= $code_array[$activeKey];
    			$chksum=($chksum + ($code_values[$activeKey] * $X));
    		}
    		$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
    
    		$code_string = "211412" . $code_string . "2331112";
    	} elseif ( strtolower($code_type) == "code39" ) {
    		$code_array = array("0"=>"111221211","1"=>"211211112","2"=>"112211112","3"=>"212211111","4"=>"111221112","5"=>"211221111","6"=>"112221111","7"=>"111211212","8"=>"211211211","9"=>"112211211","A"=>"211112112","B"=>"112112112","C"=>"212112111","D"=>"111122112","E"=>"211122111","F"=>"112122111","G"=>"111112212","H"=>"211112211","I"=>"112112211","J"=>"111122211","K"=>"211111122","L"=>"112111122","M"=>"212111121","N"=>"111121122","O"=>"211121121","P"=>"112121121","Q"=>"111111222","R"=>"211111221","S"=>"112111221","T"=>"111121221","U"=>"221111112","V"=>"122111112","W"=>"222111111","X"=>"121121112","Y"=>"221121111","Z"=>"122121111","-"=>"121111212","."=>"221111211"," "=>"122111211","$"=>"121212111","/"=>"121211121","+"=>"121112121","%"=>"111212121","*"=>"121121211");
    
    		// Convert to uppercase
    		$upper_text = strtoupper($text);
    
    		for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
    			$code_string .= $code_array[substr( $upper_text, ($X-1), 1)] . "1";
    		}
    
    		$code_string = "1211212111" . $code_string . "121121211";
    	} elseif ( strtolower($code_type) == "code25" ) {
    		$code_array1 = array("1","2","3","4","5","6","7","8","9","0");
    		$code_array2 = array("3-1-1-1-3","1-3-1-1-3","3-3-1-1-1","1-1-3-1-3","3-1-3-1-1","1-3-3-1-1","1-1-1-3-3","3-1-1-3-1","1-3-1-3-1","1-1-3-3-1");
    
    		for ( $X = 1; $X <= strlen($text); $X++ ) {
    			for ( $Y = 0; $Y < count($code_array1); $Y++ ) {
    				if ( substr($text, ($X-1), 1) == $code_array1[$Y] )
    					$temp[$X] = $code_array2[$Y];
    			}
    		}
    
    		for ( $X=1; $X<=strlen($text); $X+=2 ) {
    			if ( isset($temp[$X]) && isset($temp[($X + 1)]) ) {
    				$temp1 = explode( "-", $temp[$X] );
    				$temp2 = explode( "-", $temp[($X + 1)] );
    				for ( $Y = 0; $Y < count($temp1); $Y++ )
    					$code_string .= $temp1[$Y] . $temp2[$Y];
    			}
    		}
    
    		$code_string = "1111" . $code_string . "311";
    	} elseif ( strtolower($code_type) == "codabar" ) {
    		$code_array1 = array("1","2","3","4","5","6","7","8","9","0","-","$",":","/",".","+","A","B","C","D");
    		$code_array2 = array("1111221","1112112","2211111","1121121","2111121","1211112","1211211","1221111","2112111","1111122","1112211","1122111","2111212","2121112","2121211","1121212","1122121","1212112","1112122","1112221");
    
    		// Convert to uppercase
    		$upper_text = strtoupper($text);
    
    		for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
    			for ( $Y = 0; $Y<count($code_array1); $Y++ ) {
    				if ( substr($upper_text, ($X-1), 1) == $code_array1[$Y] )
    					$code_string .= $code_array2[$Y] . "1";
    			}
    		}
    		$code_string = "11221211" . $code_string . "1122121";
    	}
    
    	// Pad the edges of the barcode
    	$code_length = 20;
    	if ($print) {
    		$text_height = 30;
    	} else {
    		$text_height = 0;
    	}
    	
    	for ( $i=1; $i <= strlen($code_string); $i++ ){
    		$code_length = $code_length + (integer)(substr($code_string,($i-1),1));
            }
    
    	if ( strtolower($orientation) == "horizontal" ) {
    		$img_width = $code_length*$SizeFactor;
    		$img_height = $size;
    	} else {
    		$img_width = $size;
    		$img_height = $code_length*$SizeFactor;
    	}
    
    	$image = imagecreate($img_width, $img_height + $text_height);
    	$black = imagecolorallocate ($image, 0, 0, 0);
    	$white = imagecolorallocate ($image, 255, 255, 255);
    
    	imagefill( $image, 0, 0, $white );
    	if ( $print ) {
    		imagestring($image, 5, 31, $img_height, $text, $black );
    	}
    
    	$location = 10;
    	for ( $position = 1 ; $position <= strlen($code_string); $position++ ) {
    		$cur_size = $location + ( substr($code_string, ($position-1), 1) );
    		if ( strtolower($orientation) == "horizontal" )
    			imagefilledrectangle( $image, $location*$SizeFactor, 0, $cur_size*$SizeFactor, $img_height, ($position % 2 == 0 ? $white : $black) );
    		else
    			imagefilledrectangle( $image, 0, $location*$SizeFactor, $img_width, $cur_size*$SizeFactor, ($position % 2 == 0 ? $white : $black) );
    		$location = $cur_size;
    	}
    	//return $image;
    	// Draw barcode to the screen or save in a file
    	if ( $filepath=="" ) {
    		header ('Content-type: image/png');
    		imagepng($image);
    		imagedestroy($image);
    	} else {
    		imagepng($image,$filepath);
    		imagedestroy($image);		
    	}
    }

   public function citylist()
     {
        $headers = $_SERVER;
        if(isset($headers['HTTP_KEY'])){
            $apiKey = $headers['HTTP_KEY'];
        } 
        if($apiKey != API_KEY) {
            echo $this->errorOutputResult("Unauthorized Access.");
            exit;
        }

        $merchants_citie_ids = DB::table('merchants')->where(['status' => 1])->groupBy('city_id')->orderBy('city_id', 'ASC')->pluck('city_id', 'city_id');

        $cityids = array_filter(json_decode(json_encode($merchants_citie_ids),true));
        $cities_obj = DB::table('cities')->where(['status' => 1])->whereIn('id',$cityids)->orderBy('name', 'ASC')->get();
        $cities = array_filter(json_decode(json_encode($cities_obj),true));
        // print_r($cities);exit;

        // $reqData = $_POST['data'];
        // $userData = json_decode($reqData, true);
        // $cityData = City::where('status',1)->where('state_id',$userData['id'])->get();
            $data['response_data'] = array();
            $i = 0;
        foreach ($cities as $details[$i]) {
            $stateData = State::where('status',1)->where('id',$details[$i]['state_id'])->first();
            $data['response_data'][$i]['id'] = $details[$i]['id'];
            $data['response_data'][$i]['state_id'] = $details[$i]['state_id'];
            $data['response_data'][$i]['state'] = $stateData['name'];
            $data['response_data'][$i]['name'] = $details[$i]['name'];
            $data['response_data'][$i]['slug'] = $details[$i]['slug'];
            $data['response_data'][$i]['status'] = $details[$i]['status'];
            //$data['response_data'][$i]['category_desc'] = $details[$i]['category_desc'];
            $i++;
        }

        $data['response_msg'] = '';
            $data['response_status'] = 'success';
            echo json_encode($data);
            exit;
     } 

      public function contactus() {
        $this->requestAuthentication('POST');
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $name = $userData['name'];
        $email = $userData['email'];
        $contact = $userData['contact'];
        $message = nl2br($userData['message']);

        $settings = DB::table('admins')->where('id', 1)->first();
        $emailId = $settings->email;

        $emailTemplate = DB::table('emailtemplates')->where('id', 6)->first();
        $toRepArray = array('[!name!]', '[!email!]', '[!contact!]', '[!message!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
        $fromRepArray = array($name, $email, $contact, $message, HTTP_PATH, SITE_TITLE);
        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
        Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
         // print_r($emailBody);   
        // Session::flash('success_message', "");
        $data['response_data'] = "";
        $data['response_msg'] = 'Your enquiry sent to us successfully, our team will contact you soon.';
        $data['response_status'] = 'success';

        echo json_encode($data);
        exit;
    }
}

?>