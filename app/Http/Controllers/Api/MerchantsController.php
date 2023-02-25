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
use App\Models\Merchant;
use App\Models\Admin;
use App\Models\Deal;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Review;
use App\Models\Setting;
use App\Models\Category;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Amenitie;
use App\Models\Locality;
use App\Models\Withdrawal;
use App;


class MerchantsController extends Controller {

    public function __construct() {

    }

    public function logindata($userCheck) {
        $data = array();
        $data['user_id'] = $userCheck->id;
        $data['first_name'] = $userCheck->first_name;
        $data['last_name'] = $userCheck->last_name;
        $data['email_address'] = $userCheck->email_address;
        $data['contact'] = $userCheck->contact;
	$data['business_type'] = $userCheck->business_type;
	$data['busineess_name'] = $userCheck->busineess_name;
        if($userCheck->token != ''){
            $token = $userCheck->token;
        }
        else{
          $token = $this->setToken($userCheck);
        }
        $data['token'] = $token;
        return $data;
    }

    public function register() { 
        $this->requestAuthenticationn('POST');
        if (isset($_REQUEST['data'])){
            $values = trim($_REQUEST['data']);
        }
        $userData = json_decode($values, true);
        //$userData = $values;
        
        $serialisedData = array();
        if (isset($userData['busineess_name'])) {
            $serialisedData['busineess_name'] = $userData['busineess_name'];
        } else {
            $serialisedData['busineess_name'] = '';
        }
        if (isset($userData['country_id'])) {
            $serialisedData['country_id'] = $userData['country_id'];
        } else {
            $serialisedData['country_id'] = '';
        }
        if (isset($userData['state_id'])) {
            $serialisedData['state_id'] = $userData['state_id'];
        } else {
            $serialisedData['state_id'] = '';
        }
        if (isset($userData['city_id'])) {
            $serialisedData['city_id'] = $userData['city_id'];
        } else {
            $serialisedData['city_id'] = '';
        }
        if (isset($userData['business_type'])) {
            $serialisedData['business_type'] = $userData['business_type'];
        } else {
            $serialisedData['business_type'] = '';
        }
        if (isset($userData['name'])) {
            $serialisedData['name'] = $userData['name'];
        } else {
            $serialisedData['name'] = '';
        }
        if (isset($userData['contact'])) {
            $serialisedData['contact'] = $userData['contact'];
        } else {
            $serialisedData['contact'] = '';
        }
        if (isset($userData['email_address'])) {
            $serialisedData['email_address'] = $userData['email_address'];
        } else {
            $serialisedData['email_address'] = '';
        }
        if (isset($userData['password'])) {
            $serialisedData['password'] = $userData['password'];
        } else {
            $serialisedData['password'] = '';
        }
        if (isset($userData['source_of_info_about_us'])) {
            $serialisedData['source_of_info_about_us'] = $userData['source_of_info_about_us'];
        } else {
            $serialisedData['source_of_info_about_us'] = '';
        }
        if (isset($userData['source_of_info_about_us'])) {
            $serialisedData['source_of_info_about_us'] = $userData['source_of_info_about_us'];
        } else {
            $serialisedData['source_of_info_about_us'] = '';
        }
        if (isset($userData['device_id'])) {
            $serialisedData['device_id'] = $userData['device_id'];
        } else {
            $serialisedData['device_id'] = '';
        }
        if (isset($userData['device_type'])) {
            $serialisedData['device_type'] = $userData['device_type'];
        } else {
            $serialisedData['device_type'] = '';
        }
        

        $msgString = '';
        if($serialisedData) 
        {
            if(trim($serialisedData["busineess_name"]) == '') {
                $msgString .= 'Buisness name is required field.';
            }
            if (trim($serialisedData["country_id"]) == '') {
                $msgString .= 'Country is required field.';
            }
            if (empty($serialisedData["city_id"])) {
                $msgString .= 'City is required field.';
            }
            if (trim($serialisedData["business_type"]) == '') {
                $msgString .= 'Business type is required field.';
            }
            if (trim($serialisedData["name"]) == '') {
                $msgString .= 'Name is required field.';
            }
            if(trim($serialisedData["contact"]) == '') {
                $msgString .= 'Contact is required field.';
            }
            if (trim($serialisedData["email_address"]) == '') {
                $msgString .= 'Email Address is required field.';
            }
            if (empty($serialisedData["source_of_info_about_us"])) {
                $msgString .= 'How did you hear about this is required field.';
            }
            
            if (isset($msgString) && $msgString != '') {
                echo $this->errorOutputResult($msgString);
                exit;
            } else {
                
                $serialisedData['password'] = $this->encpassword($serialisedData['password']);
                $serialisedData['slug'] = $this->createSlug($serialisedData['name'], 'users');
                $serialisedData['activation_status'] = 0;
                $serialisedData['user_status'] = "Offline";
                $serialisedData['status'] = 0;
                $serialisedData['created_at'] = date('Y-m-d H:i:s');
                $serialisedData['updated_at'] = date('Y-m-d H:i:s');
                $uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
                $serialisedData['unique_key'] = $uniqueKey;
                //print_r($serialisedData);exit;
                if(Merchant::insert($serialisedData)) {
                $last_row = DB::table('merchants')->orderBy('id', 'DESC')->first();
                    
                $userId = $last_row ->id;
        
                //mail to admin
                $admin_detail = DB::table('admins')->where(['id'=> 1,'type'=>'admin','status' => 1])->first();
                $link = HTTP_PATH . "/email-confirmation/" . $uniqueKey;
                $name = $serialisedData["name"];
                $shop_name = $serialisedData['busineess_name'];
                $emailId = $admin_detail->email;
                $user_emailId = $serialisedData['email_address'];
                $contact = $serialisedData['contact'];
                $emailTemplate = DB::table('emailtemplates')->where('id', 17)->first();
                $toRepArray = array('[!Name!]','[!shop_name!]','[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($name,$shop_name,$link, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));

                //mail to merchant
                $link = HTTP_PATH . "/merchant/email-confirmation/" . $uniqueKey;
                $name = $serialisedData['name'];
                $emailId = $serialisedData['email_address'];
                //$new_password = $input['password'];

                $emailTemplate = DB::table('emailtemplates')->where('id', 18)->first();
                $toRepArray = array('[!email!]', '[!username!]', '[!business_name!]', '[!contact!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($emailId, $name, $shop_name, $contact, $link, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));

                $data['response_data']['user_id'] = $userId;
                $data['response_msg'] = 'Your Business account has been created successfully. Please wait for the admin approval.';

                $data['response_status'] = 'success';
        
                echo json_encode($data);
                exit;
                }exit;
            }
        }
    }

    public function login() {
        $this->requestAuthenticationn('POST');
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $email = $userData['email_address'];
        $password = $userData['password'];
        $device_type = $userData['device_type'];
        $device_id = $userData['device_id'];

        $userInfo = Merchant::where('email_address', $email)->first();        
        if (!empty($userInfo)) {
            if (password_verify($password, $userInfo->password)) {
                if ($userInfo->status == 1 && $userInfo->activation_status == 1) {
                    //print_r($userInfo);exit;
                    $data = $this->logindata($userInfo);
                $token = $data['token'];
                    Merchant::where('id', $userInfo->id)->update(array('device_type' => $device_type, 'device_id' => $device_id, 'token' => $token));
                    $this->successOutputResult('Login sucessfully', json_encode($data));
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
        $tokenData = $this->requestAuthenticationn('POST');
	
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        //print_r($userData);exit;
        $userInfo = Merchant::where('email_address', $userData['email_address'])->first();
        if (!empty($userInfo)) {
            $uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
            Merchant::where('id', $userInfo->id)->update(array('forget_password_status' => 1, 'unique_key' => $uniqueKey));

            $link = HTTP_PATH . "/reset-password/" . $uniqueKey;
            $name = ucwords($userInfo->name);
            $emailId = $userInfo->email_address;
            $emailTemplate = DB::table('emailtemplates')->where('id', 4)->first();
            $toRepArray = array('[!username!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
            $fromRepArray = array($name, $link, HTTP_PATH, SITE_TITLE);
            $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
            $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
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
        $tokenData = $this->requestAuthenticationn('POST');
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $recordInfo = Merchant::where('id', $user_id)->first();
        $serialisedData = array();
        $msgString = '';
        if (isset($userData['old_password'])) {
           $serialisedData["old_password"] = $userData['old_password'];
        } else {
            $serialisedData["old_password"] = '';
        }
        if (isset($userData['new_password'])) {
            $serialisedData["new_password"] = $userData['new_password'];
        } else {
            $serialisedData["new_password"] = '';
        }
        if ($serialisedData) {
            if (trim($serialisedData["old_password"]) == '') {
                $msgString .= 'Old Password is required field.';
            }
            if (trim($serialisedData["new_password"]) == '') {
                $msgString .= 'New Password is required field.';
            }
        }
        if (!password_verify($serialisedData["old_password"], $recordInfo->password)) {
            $this->errorOutputResult('Old password is not correct.');
        } else if ($serialisedData["old_password"] == $serialisedData["new_password"]) {
            $this->errorOutputResult('You can not change new password same as current password.');
        } else {
            $new_password = $this->encpassword($serialisedData["new_password"]);
            Merchant::where('id', $user_id)->update(array('password' => $new_password));
            $this->successOutput('Your Password has been changed successfully.');
        }

    }
    public function editprofile() {

        $tokenData = $this->requestAuthenticationn('POST');
        $userid = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $recordInfo = Merchant::where(['id'=>$userid])->first();
        $serialisedData = $userData;
        $i = 0;$service_id = ''; 
        if(isset($userData['service_ids'])){
        foreach($userData['service_ids'] as $serviceid[$i]){ 
        if($i == 0){$service_id = $serviceid[$i];}
        else{$service_id = $service_id.','.$serviceid[$i];}
        $i++;
        }
        }
        $count = $userData['imagecount'];
        $image = explode(',', $recordInfo['profile_image']);
                
                $imagesArray = array();
                if ($image) {
                    $imagesArray = array_filter($image);
                }
        if($count >= 1){
            for ($i = 1; $i <= $count; $i++) {
                if (isset($_FILES["profile_image$i"]) && $_FILES["profile_image$i"] != '') {
                    $file = Input::file("profile_image$i");
                    $uploadedFileName = $this->uploadImage($file, MERCHANT_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, MERCHANT_FULL_UPLOAD_PATH, MERCHANT_SMALL_UPLOAD_PATH, MERCHANT_MW, MERCHANT_MH);
                    $imagesArray[] = $uploadedFileName;
                }
            }
            $imagesArray = implode(',', $imagesArray);
        }
        else{
            $imagesArray = '';
        }

        $j=0;
        $starttime = "";
        $endtime = "";$working_days = "";
        if(isset($userData['work'])){
            foreach ($userData['work'] as $key=>$value) {
                if($value==0){
                   unset($userData['working_days'][$key]);
                   unset($userData['start_time'][$key]);
                   unset($userData['end_time'][$key]);
                }else{
                     // $userData['working_days'][$key]=$userData['working_days'][$key].":00";
                     $userData['start_time'][$key] = date("H:i:s", strtotime($userData['start_time'][$key]));
                     $userData['end_time'][$key] = date("H:i:s", strtotime($userData['end_time'][$key]));
                   // unset($userData['start_time'][$key]);
                   // unset($userData['end_time'][$key]);
                }
            }
        }

        $working_days = implode(",", $userData['working_days']);
        $starttime = implode(",", $userData['start_time']);
        $endtime = implode(",", $userData['end_time']);
        //print_r($starttime);exit;
        $serialisedData['working_days'] = $working_days;
        $serialisedData['start_time'] = $starttime;
        $serialisedData['end_time'] = $endtime;
        unset($serialisedData['imagecount']);
        unset($serialisedData['work']);
        $serialisedData['profile_image'] = $imagesArray;
        $serialisedData['service_ids'] = $service_id;
        Merchant::where('id',$userid)->update($serialisedData);
        $userInfo = Merchant::where('id', $userid )->first();  
        $userDetails['user_id'] = $userInfo->id;
        $userDetails['user_type'] = "Merchant";
        $userDetails['busineess_name'] = $userInfo->busineess_name;
        $userDetails['email_address'] = $userInfo->email_address;
        if($userInfo->contact != ''){
        $userDetails['contact'] = $userInfo->contact;}
        else{$userDetails['contact'] = "";}

        $data['response_data'] = $userDetails;
        $data['response_status'] = 'success';
        $data['response_msg'] = 'Your Details Updated successfully.';
        echo json_encode($data);
        exit;
    }


    public function editprofileold() {
        $tokenData = $this->requestAuthenticationn('POST');
        $userid = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
	$recordInfo = Merchant::where(['id'=>$userid])->first();
        $serialisedData = $userData;
	$i =0;$service_id = '';
    	foreach($userData['service_ids'] as $serviceid[$i]){
    	if($i == 0){$service_id = $serviceid[$i]['id'];}
    	else{$service_id = $service_id.','.$serviceid[$i]['id'];}
    	$i++;
    	}
	$count = $userData['imagecount'];
	$image = explode(',', $recordInfo['profile_image']);
				
                $imagesArray = array();
                if ($image) {
                    $imagesArray = array_filter($image);
                }
	if($count >= 1)
    	{
    	for ($i = 1; $i <= $count; $i++) {
    		if (isset($_FILES["profile_image$i"]) && $_FILES["profile_image$i"] != '') {
		$file = Input::file("profile_image$i");
		$uploadedFileName = $this->uploadImage($file, MERCHANT_FULL_UPLOAD_PATH);
		$this->resizeImage($uploadedFileName, MERCHANT_FULL_UPLOAD_PATH, MERCHANT_SMALL_UPLOAD_PATH, MERCHANT_MW, MERCHANT_MH);
		$imagesArray[] = $uploadedFileName;
    	}
    	}
	$imagesArray = implode(',', $imagesArray);
	}
	else{$imagesArray = '';}

	$j=0;
	$starttime = "";
        $endtime = "";$working_days = "";
	foreach($userData['working'] as $details[$j]){		
		if($j==0){$working_days = $details[$j]['working_days'];$starttime = $details[$j]['start_time'];$endtime = $details[$j]['end_time'];}
		else{$working_days = $working_days.",".$details[$j]['working_days'];
			$starttime = $starttime.",".$details[$j]['start_time'];
			$endtime = $endtime.",".$details[$j]['end_time'];}
	$j++;
	}
        $serialisedData['working_days'] = $working_days;
        $serialisedData['start_time'] = $starttime;
        $serialisedData['end_time'] = $endtime;
	unset($serialisedData['imagecount']);
	unset($serialisedData['working']);
	$serialisedData['profile_image'] = $imagesArray;
        $serialisedData['service_ids'] = $service_id;
        Merchant::where('id',$userid)->update($serialisedData);
        $userInfo = Merchant::where('id', $userid )->first();  
	$userDetails['user_id'] = $userInfo->id;
        $userDetails['user_type'] = "Merchant";
        $userDetails['busineess_name'] = $userInfo->busineess_name;
        $userDetails['email_address'] = $userInfo->email_address;
        if($userInfo->contact != ''){
        $userDetails['contact'] = $userInfo->contact;}
        else{$userDetails['contact'] = "";}

        $data['response_data'] = $userDetails;
        $data['response_status'] = 'success';
        $data['response_msg'] = '';
        echo json_encode($data);
        exit;
    }

    public function getprofile() {
        $tokenData = $this->requestAuthenticationn('GET');
        $user_id = $tokenData['user_id'];      
	$week_days = array('monday' => "monday",'tuesday' => "tuesday",'wednesday' => "wednesday",'thursday' => "thursday",'friday' => "friday",'saturday' => "saturday",'sunday' => "sunday",);
        $userInfo = Merchant::where('id', $user_id)->first(); 
        //print_r($userInfo->BusinessType);exit;  
        $countryData = DB::table('countries')->where('id', $userInfo->country_id)->first();
	$stateData = DB::table('states')->where('id', $userInfo->state_id)->first();
	$cityData = DB::table('cities')->where('id', $userInfo->city_id)->first(); 

	$localityData = DB::table('localities')->where('id', $userInfo->locality_id)->first();
    $businesstypes = DB::table('business_types')->where('id', $userInfo->business_type)->first();    
	$allservices = DB::table('categories')->where(['status'=>'1','parent_id'=>$userInfo->business_type])->pluck('category_name', 'id')->all();
	$data['response_data'] = array();
        $userDetails['user_id'] = $userInfo->id;
	$userDetails['busineess_id'] = $userInfo->business_type;
        $userDetails['busineess_name'] = $userInfo->busineess_name;
        $userDetails['business_type'] =$userInfo->BusinessType->category_name;
        $userDetails['address'] = $userInfo->address;
	$userDetails['locality_id'] = $userInfo->locality_id;
        if(isset($userInfo->zipcode)){$userDetails['zip_code'] = $userInfo->zipcode;}else{$userDetails['zip_code'] ='';}
        $userDetails['country'] = $countryData->name;
	$userDetails['state_id'] = $userInfo->state_id;
        $userDetails['state'] = $stateData->name;
	$userDetails['city_id'] = $userInfo->city_id;
        $userDetails['city'] = $cityData->name;
        if(($userInfo->locality_id != 0)){$userDetails['locality'] = $localityData->locality_name;}else{$userDetails['locality'] ='';}
        if(isset($userInfo->contact)){$userDetails['contact'] = $userInfo->contact;}else{$userDetails['contact'] ='';}
        if($userInfo->service_ids!='' && $userInfo->service_ids!=0 ){$convert_to_array = explode(",", $userInfo->service_ids);$s_nam_arr = array();foreach($convert_to_array as $convert_to_arr){$s_nam_arr[] = isset($allservices[$convert_to_arr])?$allservices[$convert_to_arr]:"";}
	$userDetails['service'] = implode(',',array_filter($s_nam_arr));}
    else{$userDetails['service'] ='';}
        if(isset($userInfo->name)){$userDetails['name'] = $userInfo->name;}else{$userDetails['name'] ='';}
        if(isset($userInfo->email_address)){$userDetails['email_address'] = $userInfo->email_address;}else{$userDetails['email_address'] ='';}
        // $userDetails['address'] = $userInfo->address;
	if(isset($userInfo->service_ids)){$service_arr = explode(",",$userInfo->service_ids);$userDetails['service_ids'] = $service_arr;}else{$userDetails['service_ids'] ='';}
        if(isset($userInfo->profile_image)){$img_arr = explode(",",$userInfo->profile_image);$userDetails['profile_image'] = $img_arr;}else{$userDetails['profile_image'] ='';}
        $userDetails['category_id'] = $userInfo->business_type;
        $userDetails['category'] =$userInfo->BusinessType->category_name;
        if($userInfo->service_ids!='' && $userInfo->service_ids!=0 ){$convert_to_array = explode(",", $userInfo->service_ids);$s_nam_arr = array();foreach($convert_to_array as $convert_to_arr){$s_nam_arr[] = isset($allservices[$convert_to_arr])?$allservices[$convert_to_arr]:"";}
    $userDetails['subcategories'] = implode(',',array_filter($s_nam_arr));}
    else{$userDetails['subcategories'] ='';}
        if(isset($userInfo->about_us)){$userDetails['about_us'] = $userInfo->about_us;}else{$userDetails['about_us'] ='';}
        if(isset($userInfo->facebook_link)){$userDetails['facebook_link'] = $userInfo->facebook_link;}else{$userDetails['facebook_link'] ='';}
        if(isset($userInfo->instagram_link)){$userDetails['instagram_link'] = $userInfo->instagram_link;}else{$userDetails['instagram_link'] ='';}
        if(isset($userInfo->twitter_link)){$userDetails['twitter_link'] = $userInfo->twitter_link;}else{$userDetails['twitter_link'] ='';}
        if(isset($userInfo->youtube_link)){$userDetails['youtube_link'] = $userInfo->youtube_link;}else{$userDetails['youtube_link'] ='';}
        if(isset($userInfo->linkedin_link)){$userDetails['linkedin_link'] = $userInfo->linkedin_link;}else{$userDetails['linkedin_link'] ='';}
        //$userDetails['linkedin_link'] = $userInfo->id;
	
	
	//if(isset($userInfo->working_days)){
	$days = explode(",",$userInfo['working_days']);
    	$start_time = explode(",",$userInfo['start_time']);
    	$end_time = explode(",",$userInfo['end_time']);
	foreach($week_days as $wd){
        $daydata=[];
        $key=array_search($wd,$days);
        $daydata['working_days']=$wd;
        $daydata['start_time']=($key===FALSE)?'0':$start_time[$key];
        $daydata['end_time']=($key===FALSE)?'0':$end_time[$key];
	    $daydata['work']=($key===FALSE)?'0':'1';
	//if($daydata['work'] != '0'){echo $key;}
    //if($daydata['work'] != '0'){echo "1";}
	//array_push($data['response_data'], $daydata);
    if(isset($wd)){if($daydata['start_time'] != '0'){$userDetails['working_days'][] = $daydata['working_days'];}}else{$userDetails['working_days']="";}
	if(isset($wd)){if($daydata['start_time'] != '0'){$userDetails['start_time'][] = date('h:i A', strtotime($daydata['start_time']));}}else{$userDetails['start_time']="";}
	if(isset($wd)){if($daydata['end_time'] != '0'){$userDetails['end_time'][] = date('h:i A', strtotime($daydata['end_time']));}}else{$userDetails['end_time']="";}
	if(isset($wd)){if($daydata['start_time'] != '0'){$userDetails['work'][] = $daydata['work'];}}else{$userDetails['work']="";}
	}
	//}
        
        //print_r($userDetails);exit;
        $data['response_data'] = $userDetails;
        $data['response_status'] = 'success';
        $data['response_msg'] = '';
        echo json_encode($data);
    }

    public function mydeals()
    {
        $tokenData = $this->requestAuthenticationn('GET');
        $user_id = $tokenData['user_id'];
        $query = new Deal();
        $query = $query->sortable();
       $dealsData= $query->where(['merchant_id'=>$user_id])->orderBy('id', 'DESC')->get();
        $data['response_data'] = array();
        $i = 0;
        foreach ($dealsData as $details[$i]) { 
            $data['response_data'][$i]['id'] = $details[$i]['id'];
            $data['response_data'][$i]['deal_name'] = $details[$i]['deal_name'];
            $data['response_data'][$i]['discount'] = $details[$i]['discount'];
            $data['response_data'][$i]['expiry_date'] = $details[$i]['expire_date'];
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
            echo $this->errorOutputResult('No deals are found!');
            exit;
        }
        exit;
    }

    public function myorders()
    {
        $tokenData = $this->requestAuthenticationn('GET');
        $userid = $tokenData['user_id'];
       $settingData = Setting::where(['id'=>1])->first();
       if($settingData->order_listed == 0){
        $ordersData = Order::where('merchant_id',$userid)->orderBy('created_at', 'DESC')->get();}
       else{$ordersData = Order::where('merchant_id',$userid)->where("is_voucher_redeemed",1)->orderBy('created_at', 'DESC')->get();}
        $data['response_data'] = array();
        $i = 0;
        foreach ($ordersData as $details[$i]) { 
            $data['response_data'][$i]['id'] = $details[$i]['id'];
            $data['response_data'][$i]['order_number'] = $details[$i]['order_number'];
        $data['response_data'][$i]['customer_name'] = $details[$i]->User->first_name.' '.$details[$i]->User->last_name;         
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
        $tokenData = $this->requestAuthenticationn('GET');
        $user_id = $tokenData['user_id'];
       $recordInfo = Merchant::where(['id'=>$user_id])->first();
       $query = new Wallet();
       $query = $query->sortable();
       $walletData = $query->where(['merchant_id'=>$user_id])->whereHas('Order',function($q){
                   $q->where('id', '>', 0);
            })->orderBy('id', 'DESC')->get();
       $data['response_data'] = array();
        $i = 0;
        foreach ($walletData  as $details[$i]) { 
       $data['response_data']['Wallet'][$i]['id'] = $details[$i]['id'];
       $data['response_data']['Wallet'][$i]['order_number'] = isset($details[$i]->Order)?$details[$i]->Order->order_number:"";
       $data['response_data']['Wallet'][$i]['customer_name'] = isset($details[$i]->User)?$details[$i]->User->first_name.' '. $details[$i]->User->last_name:"";
       $data['response_data']['Wallet'][$i]['amount'] = $details[$i]['amount'];
       $data['response_data']['Wallet'][$i]['transaction_id'] = $details[$i]['trn_id'];
       $data['response_data']['Wallet'][$i]['created_date'] = $details[$i]['created_at']->format('M d Y');    
       $i++;
       }
       $data['response_data']['wallet_balance']=$recordInfo['wallet_balance'];
       $data['response_data']['total_earned_amount']=$recordInfo['total_earned_amount'];
       if (!empty($data)) {
            $data['response_status'] = 'success';
            $data['response_msg'] = '';
            echo json_encode($data);
            exit;
        }
        else {
            echo $this->errorOutputResult('No Records are found!');
            exit;
        }
        exit;
    }

    public function orderdetails()
    {
        $tokenData = $this->requestAuthenticationn('POST', 1);
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $orderdetails = Order::where('id',$userData['id'])->first();
	    //print_r($orderdetails);exit;
        $data['response_data']['order_number'] = $orderdetails->order_number;
        $data['response_data']['voucher_number'] = $orderdetails->voucher_number;
        if($orderdetails->is_voucher_redeemed == 1) { $data['response_data']['status'] = "Redeemed";}
        else { $data['response_data']['status'] = "Pending"; }
        $data['response_data']['date_time'] = $orderdetails->created_at->format('Y-m-d H:s:i');
        $data['response_data']['customer_name'] = $orderdetails->User->first_name.' '.$orderdetails->User->last_name;
        $data['response_data']['email_address'] = $orderdetails->User->email_address;
       $data['response_data']['contact'] = $orderdetails->User->contact;
        if($orderdetails->Payment->transaction_id){ if($orderdetails->Payment->transaction_id != ''){$data['response_data']['Payment']['transaction_id'] = $orderdetails->Payment->transaction_id;}}
        else{$data['response_data']['Payment']['transaction_id'] = 'N/A';}
        if($orderdetails->Payment->payment_mode){ if($orderdetails->Payment->payment_mode != ''){$data['response_data']['Payment']['payment_mode'] = $orderdetails->Payment->payment_mode;} }
        else{$data['response_data']['Payment']['payment_mode'] = 'Paypal';}
        if($orderdetails->Payment->status){ if(isset($orderdetails->Payment->status)){$data['response_data']['Payment']['status'] = 'Paid';}}
        else{$data['response_data']['Payment']['status'] = 'Pending';}
        if($orderdetails->Payment->created_at){  if($orderdetails->Payment->created_at != ''){$data['response_data']['Payment']['date_time'] = $orderdetails->Payment->created_at->format('Y-m-d H:s:i');}}
        else{$data['response_data']['Payment']['date_time'] = 'N/A';}

        $dealsidarr = explode(',',$orderdetails->deals_id);
        $dealsbparr = explode(',',$orderdetails->deals_base_price);
        $dealsfparr = explode(',',$orderdetails->deals_final_price);
        $dealsqtyparr = explode(',',$orderdetails->deals_quantity);
        //print_r($orderdetails->deals_id);exit;
        foreach ($dealsidarr as $key=>$dealid) {  
        $dealsdetails = Deal::where('id',$dealid)->first();
         $data['response_data']['Deal'][$key] =array('deal_name'=>$dealsdetails->deal_name,'deal_price'=>$dealsfparr[$key],'quantity'=>$dealsqtyparr[$key],'sub_total'=>($dealsfparr[$key]*$dealsqtyparr[$key]));
     
        //$data['response_data']['Deal']['deal_name'] = $dealsdetails->deal_name;
            //$data['response_data']['Deal']['deal_price'] = $dealsfparr[$key];
            //$data['response_data']['Deal']['quantity'] = $dealsqtyparr[$key];
            //$data['response_data']['Deal']['sub_total'] = ($dealsfparr[$key]*$dealsqtyparr[$key]);
        }

        $data['response_data']['total'] = $orderdetails->amount;
        $data['response_data']['convenience_fees'] = $orderdetails->convenience_fees;
        $data['response_data']['grand_total'] = $orderdetails->amount;
        $data['response_msg'] = '';
        $data['response_status'] = 'success';
        echo json_encode($data);
        exit;
    }
    
    public function verifyVoucher()
{
	$tokenData = $this->requestAuthenticationn('POST', 1);
    $user_id = $tokenData['user_id'];
    $reqData = $_POST['data'];
    $userData = json_decode($reqData, true);
	$orderInfo = Order::where('voucher_number', $userData['voucher_number'])->first();
	//print_r($orderInfo );exit;
	if(!empty($orderInfo) && $orderInfo->order_status == 1) 
	{
		$uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
		if($orderInfo->is_voucher_redeemed == 0){
		$data['response_status'] = 'success';
		$data['response_msg'] = 'Voucher Verified Succeessfully!';
        $data['response_data'] = '';
        echo json_encode($data);
        exit;
		}else{
		$data['response_status'] = 'success';
		$data['response_msg'] = 'This voucher ID has been already redeemed!';
        $data['response_data'] = '';
        echo json_encode($data);
        exit;
		}
	}
	else{
		$data['response_status'] = 'success';
		$data['response_msg'] = 'Please enter correct Voucher ID';
        $data['response_data'] = '';
        echo json_encode($data);
        exit;
	} 
}

    public function redeemVoucher()
{
	$tokenData = $this->requestAuthenticationn('POST', 1);
    $user_id = $tokenData['user_id'];
    $reqData = $_POST['data'];
    $userData = json_decode($reqData, true);
	$recordInfo = Merchant::where(['id'=>$user_id])->first();
	$orderInfo = Order::where('voucher_number', $userData['voucher_number'])->first();
    if (!empty($orderInfo) && $orderInfo->merchant_id == $user_id && $orderInfo->order_status == 1) 
    {
	if($orderInfo->is_voucher_redeemed == 0)
	{
	Order::where('id', $orderInfo->id)->update(array('is_voucher_redeemed' => 1));
	Wallet::where('order_id', $orderInfo->id)->update(array('merchant_id' => $user_id));
	$merchant_amount = $orderInfo->amount;
	$total_earned_amount = $recordInfo->total_earned_amount+$merchant_amount;
	$wallet_balance = $recordInfo->wallet_balance+$merchant_amount;
	Merchant::where('id', $user_id)->update(array('total_earned_amount' => $total_earned_amount, 'wallet_balance' => $wallet_balance));
	Payment::where('order_id', $orderInfo->id)->update(array('merchant_id' => $user_id));
	$data['response_data'] = '';
	$data['response_msg'] = 'Voucher Redeemed successfully!';
	$data['response_status'] = 'success';
	echo json_encode($data);
	exit;
	}
	else{$error = 'This Voucher ID has been already redeemed!';}
	}
	else {$error = 'Please enter correct Voucher ID';}
	$data['response_data'] = '';
    $data['response_msg'] = $error;
    $data['response_status'] = 'error';
    echo json_encode($data);
    exit;
}

     public function deletedeal()
    {
        $tokenData = $this->requestAuthenticationn('POST', 1);
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
	Deal::where('id', $userData['id'])->delete();
	$data['response_data'] = '';
        $data['response_msg'] = 'Your Deal deleted successfully';
        $data['response_status'] = 'success';
        echo json_encode($data);
        exit;
    }

     public function editdeal()
    {
        $tokenData = $this->requestAuthenticationn('POST', 1);
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
	    $recordInfo = Deal::where('id', $userData['id'])->first();
	    $i =0;$amenitie_id = '';
        // print_r($userData['amenitie_id']);exit;
	    foreach($userData['amenitie_id'] as $amenitieid[$i]){
	    if($i == 0){
         $amenitie_id = $amenitieid[$i];
        }
	    else{
         $amenitie_id = $amenitie_id.','.$amenitieid[$i];}
	     $i++;
	    }
	    $count = $userData['imagecount'];
	    $image = explode(',', $recordInfo['images']);
        $imagesArray = array();
        if (!empty($image)) {
            $imagesArray = array_filter($image);
        }else{
            $imagesArray = '';
        }
    	if($count >= 1){
        	for ($i = 1; $i <= $count; $i++) {
        		if (isset($_FILES["images$i"]) && $_FILES["images$i"] != '') {
        		$file = Input::file("images$i");
        		$uploadedFileName = $this->uploadImage($file, DEAL_FULL_UPLOAD_PATH);
        		$imagesArray[] = $uploadedFileName;
        	   }
        	}
	       $imagesArray = implode(',', $imagesArray);
    	}else{
            $imagesArray = implode(',', $imagesArray);
        }
	    

    	unset($userData['imagecount']);
    	$serialisedData = $this->serialiseFormData($userData);
    	$serialisedData['images'] = $imagesArray;
    	$serialisedData['status'] = 1;
        $serialisedData['deal_start_time'] = date("H:i:s", strtotime($userData['deal_start_time']));
        $serialisedData['deal_end_time'] = date("H:i:s", strtotime($userData['deal_end_time']));
    	$serialisedData['merchant_id'] = $user_id;
    	$serialisedData['slug'] = $this->createSlug($userData['deal_name'], 'deals');
    	$serialisedData['final_price'] = $userData['voucher_price']-($userData['voucher_price']*$userData['discount']/100);
    	$serialisedData['amenitie_id'] = $amenitie_id;
	
	   Deal::where('id', $userData['id'])->update($serialisedData);
	   $data['response_data'] = '';
       $data['response_msg'] = 'Your Deal updated successfully';
       $data['response_status'] = 'success';
       echo json_encode($data);
       exit;
    }

    public function adddeal()
    {
        $tokenData = $this->requestAuthenticationn('POST', 1);
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
	//print_r($_FILES);exit;
	$i =0;$amenitie_id = '';

	// foreach($userData['amenitie_id'] as $amenitieid[$i]){
	// if($i == 0){$amenitie_id = $amenitieid[$i]['id'];}
	// else{$amenitie_id = $amenitie_id.','.$amenitieid[$i]['id'];}
	// $i++;
	// }

    foreach($userData['amenitie_id'] as $amenitieid[$i]){
    if($i == 0){
     $amenitie_id = $amenitieid[$i];
    }
    else{
     $amenitie_id = $amenitie_id.','.$amenitieid[$i];}
     $i++;
    }

	$count = $userData['imagecount'];
	$imagesArray = array();
	if($count >= 1)
    	{
    	for ($i = 1; $i <= $count; $i++) {
    		if (isset($_FILES["images$i"]) && $_FILES["images$i"] != '') {
		$file = Input::file("images$i");
		$uploadedFileName = $this->uploadImage($file, DEAL_FULL_UPLOAD_PATH);
		$imagesArray[] = $uploadedFileName;
    	}
    	}
	$imagesArray = implode(',', $imagesArray);
	}
	else{$imagesArray = '';}
	
	unset($userData['imagecount']);
	$serialisedData = $this->serialiseFormData($userData);
	$serialisedData['images'] = $imagesArray;
	$serialisedData['status'] = 1;
	$serialisedData['type'] = 1;
    $serialisedData['deal_start_time'] = date("H:i:s", strtotime($userData['deal_start_time']));
    $serialisedData['deal_end_time'] = date("H:i:s", strtotime($userData['deal_end_time']));
	$serialisedData['merchant_id'] = $user_id;
	$serialisedData['slug'] = $this->createSlug($userData['deal_name'], 'deals');
	$serialisedData['final_price'] = $userData['voucher_price']-($userData['voucher_price']*$userData['discount']/100);
	$serialisedData['amenitie_id'] = $amenitie_id;
	//print_r($serialisedData);exit;
	Deal::insert($serialisedData);
	$data['response_data'] = '';
        $data['response_msg'] = 'Your Deal added successfully';
        $data['response_status'] = 'success';
        echo json_encode($data);
        exit;
    }

     public function countrylist()
     {
	$headers = $_SERVER;
        if(isset($headers['HTTP_KEY'])){
            $apiKey = $headers['HTTP_KEY'];
        } 
        if($apiKey != API_KEY) {
            echo $this->errorOutputResult("Unauthorized Access.");
            exit;
        }
        $countryData = Country::where('status',1)->get();
        $data['response_data'] = array();
        $i = 0;
	foreach ($countryData as $details[$i]) {
	//print_r($details[$i]);exit;
	$data['response_data'][$i]['id'] = $details[$i]['id'];
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

     public function statelist()
     {
	$headers = $_SERVER;
        if(isset($headers['HTTP_KEY'])){
            $apiKey = $headers['HTTP_KEY'];
        } 
        if($apiKey != API_KEY) {
            echo $this->errorOutputResult("Unauthorized Access.");
            exit;
        }
	$reqData = $_POST['data'];
        $userData = json_decode($reqData, true);

        $stateData = State::where('status',1)->where('country_id',$userData['id'])->get();
        $data['response_data'] = array();
        $i = 0;
	foreach ($stateData as $details[$i]) {
	$countryData = Country::where('status',1)->where('id',$details[$i]['country_id'])->first();
	$data['response_data'][$i]['id'] = $details[$i]['id'];
	$data['response_data'][$i]['country_id'] = $details[$i]['country_id'];
	$data['response_data'][$i]['country'] = $countryData['name'];
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
	$reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $cityData = City::where('status',1)->where('state_id',$userData['id'])->get();
        $data['response_data'] = array();
        $i = 0;
	foreach ($cityData as $details[$i]) {
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

     public function businesslist()
     { 
	$headers = $_SERVER;
        if(isset($headers['HTTP_KEY'])){
            $apiKey = $headers['HTTP_KEY'];
        } 
        if($apiKey != API_KEY) {
            echo $this->errorOutputResult("Unauthorized Access.");
            exit;
        }
        $businessData = Category::where('parent_id',0)->where('status',1)->get();
        $data['response_data'] = array();
        $i = 0;
	foreach ($businessData as $details[$i]) {

	$data['response_data'][$i]['id'] = $details[$i]['id'];
	$data['response_data'][$i]['category_name'] = $details[$i]['category_name'];
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

      public function servicelist()
     {
	$headers = $_SERVER;
        if(isset($headers['HTTP_KEY'])){
            $apiKey = $headers['HTTP_KEY'];
        } 
        if($apiKey != API_KEY) {
            echo $this->errorOutputResult("Unauthorized Access.");
            exit;
        }
	$reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $serviceData = Category::where('status',1)->where('parent_id',$userData['id'])->get();
        $data['response_data'] = array();
        $i = 0;
	foreach ($serviceData as $details[$i]) {
	$data['response_data'][$i]['id'] = $details[$i]['id'];
	$data['response_data'][$i]['service_name'] = $details[$i]['category_name'];
	$data['response_data'][$i]['slug'] = $details[$i]['slug'];
	$data['response_data'][$i]['status'] = $details[$i]['status'];
	$i++;
	}

	$data['response_msg'] = '';
        $data['response_status'] = 'success';
        echo json_encode($data);
        exit;
     }

    
     
     public function amenitieslist()
     {
        $tokenData = $this->requestAuthenticationn('GET', 1);
        $user_id = $tokenData['user_id'];
    	$amenitieData = Amenitie::where('status',1)->get();
    	$data['response_data'] = array();
        
        $i = 0;
    	foreach ($amenitieData as $details[$i]) {
    	$data['response_data'][$i]['id'] = $details[$i]['id'];
    	$data['response_data'][$i]['amenitie_name'] = $details[$i]['amenitie_name'];
    	$data['response_data'][$i]['status'] = $details[$i]['status'];
    	$i++;
    	}
    	$data['response_msg'] = '';
        $data['response_status'] = 'success';
        echo json_encode($data);
        exit;
     }

     public function localitylist()
     {
    	$tokenData = $this->requestAuthenticationn('POST', 1);
        $user_id = $tokenData['user_id'];
    	$reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
    	$localityData = Locality::where('city_id',$userData['id'])->get();
    	$data['response_data'] = array();
        $i = 0;
    	foreach ($localityData as $details[$i]) {
        	$data['response_data'][$i]['id'] = $details[$i]['id'];
            $data['response_data'][$i]['city_id'] = $details[$i]['city_id'];
        	$data['response_data'][$i]['locality_name'] = $details[$i]['locality_name'];
        	$data['response_data'][$i]['status'] = $details[$i]['status'];
            $data['response_data'][$i]['slug'] = $details[$i]['slug'];
        	$i++;
    	}
    	$data['response_msg'] = '';
        $data['response_status'] = 'success';
        echo json_encode($data);
        exit;
     }

     function dealdetails()
     {
	    $tokenData = $this->requestAuthenticationn('POST', 1);
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
    	$dealData = Deal::where('id',$userData['id'])->first();
    	$data['response_data']['deal_name'] = $dealData->deal_name;
    	if($dealData->voucher_type == 'cash_voucher'){$data['response_data']['voucher_type'] = "Cash Voucher";}
    	if($dealData->voucher_type == 'online_voucher'){$data['response_data']['voucher_type'] = "Online Voucher";}
    	$data['response_data']['voucher_price'] = $dealData->voucher_price;
    	$data['response_data']['popular_time'] = $dealData->popular_time;
    	$data['response_data']['deal_start_time'] = date('h:i A', strtotime($dealData->deal_start_time));
    	$data['response_data']['deal_end_time'] = date('h:i A', strtotime($dealData->deal_end_time));
    	$data['response_data']['discount'] = $dealData->discount;
    	$data['response_data']['expire_date'] = $dealData->expire_date;
    	$data['response_data']['valid_for'] = $dealData->valid_for;
    	$data['response_data']['description'] = $dealData->description;
    	$data['response_data']['images'] = explode(',', $dealData->images);
    	$aminity_id = explode(',', $dealData->amenitie_id);
    	$i =0;
    	foreach($aminity_id as $aminity[$i]){
    	$aminity_data = Amenitie::where('id',$aminity[$i])->first();
    	if($i == 0){
            $aname = $aminity_data['amenitie_name'];
            $aid=$aminity_data['id'];
        }
    	else{$aname = $aname.','.$aminity_data['amenitie_name'];
            $aid=$aid.','.$aminity_data['id'];
        }
    	$i++;
    	}
        $data['response_data']['aminities'] = explode(",", $aname);
    	$data['response_data']['aminities_id'] = explode(",", $aid);
    	$data['response_msg'] = '';
            $data['response_status'] = 'success';
            echo json_encode($data);
            exit;
    }

     public function withdrawrequest()
    { 
        $tokenData = $this->requestAuthenticationn('POST', 1);
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $recordInfo = Merchant::where('id', $user_id)->first();
        // print_r($recordInfo["wallet_balance"]);
        // exit;
        $msgString="";
        if($recordInfo->wallet_balance==0){
            $msgString .= "Your Wallet Balance is 0 so you can't Withdraw Amount.";
        }else{
            if(trim($userData["amount"]) == '') {
                $msgString .= "Please enter amount.";
            }elseif(is_numeric(trim($userData["amount"]))){
                if(trim($userData["amount"])<=0){
                    $msgString .= "Amount must be atleast 1.";
                }elseif(trim($userData["amount"])>$recordInfo['wallet_balance']){
                    $msgString .= "You can't Withdraw amount more than Wallet Balance.";
                }
            }else{
                $msgString .= 'Please enter Valid amount.';
            }
        }    
        if (isset($msgString) && $msgString != '') {
            echo $this->errorOutputResult($msgString);
            exit;
        } else {
                $amount= $userData['amount'];
                $serialisedData = [];
                $serialisedData['slug'] = $this->createSlug('withdraw'.$user_id.time(), 'withdrawals');
                $serialisedData['merchant_id'] = $user_id;
                $serialisedData['status'] = 0;
                $serialisedData['amount'] = $amount;
                $serialisedData['description'] =$userData['description'];
                $serialisedData['updated_at'] =date("Y-m-d H:i:s");
                $withdraw_id = Withdrawal::insertGetId($serialisedData);
                
                $updated_balance = $recordInfo->wallet_balance-$amount;
                Merchant::where('id', $user_id)->update(array('wallet_balance' => $updated_balance));

                $serialisedData = array();
                $serialisedData['withdrawal_id'] = $withdraw_id;
                $serialisedData['merchant_id'] = $user_id;
                $serialisedData["total_amount"] = $amount;
                $serialisedData["amount"] = $amount;
                $serialisedData["description"] = $userData['description'];
                $serialisedData["admin_commission"] = 0;
                $serialisedData["source"] = 'Withdraw form wallet';
                $serialisedData["add_minus"] = 1;
                $serialisedData["type"] = 9;
                $serialisedData["user_id"] = 0;
                $serialisedData["status"] = 1;          
                $mslug = $this->createSlug('withdraw'.$user_id.time(), 'wallets');
                $serialisedData['slug'] = $mslug;
                $paymentNumber = 'WITHDRAW' . $user_id . time();
                $serialisedData['trn_id'] = $paymentNumber;
                Wallet::insert($serialisedData);
                $data['response_data'] = "";
                $data['response_status'] = 'success';
                $data['response_msg'] = 'Your withdraw request sent successfully!';
                 echo json_encode($data);
                // Session::flash('success_message', "Your withdraw request sent successfully!");
        }

    }

    public function deleteimagedeal()
    { 
        $tokenData = $this->requestAuthenticationn('POST', 1);
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $propInfo = DB::table('deals')->where('id', $userData['id'])->first();
        $imagename = $userData['image'];
        $imagesArray = explode(',', $propInfo->images);
        $imageKey = array_search($imagename, $imagesArray);
        unset($imagesArray[$imageKey]);
        if ($imagename) {
            @unlink(DEAL_FULL_UPLOAD_PATH . $imagename);
            @unlink(DEAL_SMALL_UPLOAD_PATH . $imagename);
        }
        $impldeName = implode(',', $imagesArray);
        Deal::where('id', $userData['id'])->update(array('images' => $impldeName));
        $data['response_data'] = "";
        $data['response_status'] = 'success';
        $data['response_msg'] = 'Deal image deleted successfully!';
        echo json_encode($data);
        exit;
    }

     public function deleteprofileimage()
    { 
        $tokenData = $this->requestAuthenticationn('POST', 1);
        $user_id = $tokenData['user_id'];
        $reqData = $_POST['data'];
        $userData = json_decode($reqData, true);
        $propInfo = DB::table('merchants')->where('id', $user_id)->first();
        $imagename = $userData['image'];
        $imagesArray = explode(',', $propInfo->profile_image);
        $imageKey = array_search($imagename, $imagesArray);
        unset($imagesArray[$imageKey]);
        if ($imagename) {
            @unlink(MERCHANT_FULL_UPLOAD_PATH . $imagename);
            @unlink(MERCHANT_SMALL_UPLOAD_PATH . $imagename);
        }
        $impldeName = implode(',', $imagesArray);
        Merchant::where('id', $user_id)->update(array('profile_image' => $impldeName));
        $data['response_data'] = "";
        $data['response_status'] = 'success';
        $data['response_msg'] = 'Profile image deleted successfully!';
        echo json_encode($data);
        exit;
    }

    public function getconfiguration()
    {

        $adminInfo = DB::table('admins')->where('id', 1)->first();
        //$data['response_data']['deposit_commission'] = $adminInfo['Admin']['deposit_commission'];
        //$data['response_data']['deposit_fixed_commission'] = $adminInfo['Admin']['deposit_fixed_commission'];

        $userDetails['deposit_commission'] = $adminInfo->deposit_commission;
        $userDetails['deposit_fixed_commission'] = $adminInfo->deposit_fixed_commission;

        $data['response_data'] = $userDetails;
        $data['response_status'] = 'success';
        $data['response_msg'] = '';
        echo json_encode($data);
        exit;


    }
    
	public function deleteaccount() {
        $tokenData = $this->requestAuthenticationn('GET', 1);
        
        $userid = $tokenData['user_id'];
        if ($userid) {
            Merchant::where('id', $userid)->delete();
            $this->successOutput('Account Delete Successfully');
        }else{
            $this->errorOutputResult('Invalid Configuration!');
            exit;   
        }
    }
}

?>