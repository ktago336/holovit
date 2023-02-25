<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Url;
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
use App\Models\Verification;
use App\Models\Merchant;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\Service;
use App\Models\Wallet;
use App\Models\Withdrawal;

use App\Http\Requests\LoginRequest;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class UsersController extends Controller {
 
	
	 

    public function __construct() {  
        //$this->middleware('userlogedin', ['only' => ['login', 'forgotPassword', 'resetPassword', 'register', 'add_states', 'add_cities', 'add_localities']]);
		$this->middleware('guest')->except('logout');
		parent::__construct();
    }

	
	public function logout() { 
		Auth::guard('merchant')->logout();       
        Session::flash('success_message', "Logout successfully.");
        return redirect('merchant/login');
    }

    public function dashboard() {    
		return redirect('merchant/user/myaccount');	
        $pageTitle = 'User Dashboard';
        return view('merchant.dashboard', ['title' => $pageTitle]);
    }
    public function dashboard1() {    
        $pageTitle = 'User Dashboard';
        return view('merchant.dashboard1', ['title' => $pageTitle]);
    }

	public function register() {
	  //  exit;
        $pageTitle = 'Register';
        $input = Input::all();
		$admin_detail = DB::table('admins')->where(['id'=> 1,'type'=>'admin','status' => 1])->first();
		//print_r($admin_detail); exit;
		$business_category = DB::table('categories')->where(['parent_id'=> 0,'status' => 1])->orderBy('category_name', 'ASC')->pluck('category_name', 'id');
		$country = DB::table('countries')->where(['status' => 1])->orderBy('name', 'ASC')->pluck('name', 'id');
		//echo"<pre>";print_r($country);
        if (!empty($input)) {
			$contact_number = $input['contact'];
			//$check_verification = DB::table('verifications')->where(['merchant_number'=>$contact_number])->first();
			//Verification::where(['merchant_number'=>$contact_number])->delete();
			
//           echo '<pre>'; print_r($input);exit;
            $rules = array(
               // 'first_name' => 'required|max:20',
                //'last_name' => 'required|max:20',
                  'email_address' => 'required|email|unique:merchants',
                  'password' => 'required|min:8',
//                'contact'=>'required|unique:users',
//                'address'=>'required',
                  'confirm_password' => 'required|same:password',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/merchant/register')->withErrors($validator)->withInput(Input::except('password'));
            //} 
            //elseif($check_verification == ''){ 
			//    Session::flash('error_message', "please verify number first");
			//    return Redirect::to('/merchant/user/register')->withErrors($validator)->withInput(Input::except('password'));
			} else {
			    
			    //echo "created";exit;
                unset($input['g-recaptcha-response']);
                unset($input['terms']);
                $input['name'] = ucfirst(trim($input['name']));
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['name'], 'merchants');
                $serialisedData['status'] = 0;
                $serialisedData['activation_status'] = 0;
				//$serialisedData['is_number_verified'] = 1;
				$serialisedData['password'] = $this->encpassword($input['password']);
                //$serialisedData['password'] = $this->encpassword($input['password']);
                $uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
                $serialisedData['unique_key'] = $uniqueKey;
                //echo '<pre>';print_r($serialisedData);
                Merchant::insert($serialisedData);

                $link = HTTP_PATH . "/email-confirmation/" . $uniqueKey;
                $name = $input['name'];
				$shop_name = $input['busineess_name'];
                $emailId = $admin_detail->email;
				$user_emailId = $input['email_address'];
				$contact = $input['contact'];
               // $new_password = $input['password'];
                $emailTemplate = DB::table('emailtemplates')->where('id', 17)->first();
                $toRepArray = array('[!Name!]','[!shop_name!]','[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($name,$shop_name,$link, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));
//mail to merchant

                $link = HTTP_PATH . "/merchant/email-confirmation/" . $uniqueKey;
                $name = $input['name'];
                $emailId2 = $input['email_address'];
                
               // echo '<pre>';print_r($input);exit;
                
                //$new_password = $input['password'];

                $emailTemplate = DB::table('emailtemplates')->where('id', 18)->first();
                $toRepArray = array('[!email!]', '[!username!]', '[!business_name!]', '[!contact!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($emailId2, $name, $shop_name, $contact, $link, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
				Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));
				
								// $info = array(
        //             'name' => "Logicspice"
        //         );
                
				//  Mail::send(['text' => 'mail'], $info, function ($message)
    //             {
    //                 $message->to('shivani.wagh@logicspice.com', $emailBody)
    //                     ->subject($emailSubject);
    //                 $message->from('social.test@logicspice.com', 'Logicspice');
    //             });
				
                Session::flash('success_message', "Your business account has been created successfully. Please wait for the admin approval.");
                return Redirect::to('/merchant/login');
            }
        }
        return view('merchant.user.register', ['title' => $pageTitle,'country'=>$country,'business_category'=>$business_category]);
}
    public function add_states(Request $request) { 
         $country_id =  $request->get('country_id');
    	 //echo $country_id; exit;
             //$subcategory = array();
            if(!empty($country_id)){
             $state = DB::table('states')->where(['country_id'=> $country_id,'status'=>1])->orderBy('name', 'ASC')->pluck('name','id');
    		 //print_r($state); exit;
             echo '<option select="selected" value="">Select state</option>';
             foreach($state as $value=> $states){
                 echo '<option value="'.$value.'">'.$states.'</option>';
             } exit;
        }	
    }
    public function add_cities(Request $request) { 
         $state_id =  $request->get('state_id');
    	 //echo $country_id; exit;
             //$subcategory = array();
            if(!empty($state_id)){
             $city = DB::table('cities')->where(['state_id'=> $state_id,'status'=>1])->orderBy('name', 'ASC')->pluck('name','id');
    		 //print_r($state); exit;
             echo '<option select="selected" value="">Select city</option>';
             foreach($city as $value=> $cities){
                 echo '<option value="'.$value.'">'.$cities.'</option>';
             } exit;
        }	
    }
    public function add_localities(Request $request) { 
         $city_id =  $request->get('city_id');
    	// echo $city_id; exit;
             //$subcategory = array();
            if(!empty($city_id)){
             $locality = DB::table('localities')->where(['city_id'=> $city_id,'status'=>1])->orderBy('locality_name', 'ASC')->pluck('locality_name','id');
    		// print_r($locality); exit;
             echo '<option select="selected" value="">Select locality</option>';
             foreach($locality as $value=> $localities){
                 echo '<option value="'.$value.'">'.$localities.'</option>';
             } 
        }	
        exit;
    }
    public function verify_number(Request $request){
    	$number = $request->input('number');
    	$otp = $request->input('otp');
    	if($number !='' && $otp !=''){
          $data = array('merchant_number'=>$number,'otp'=>$otp);
         $result = Verification::insert($data);
    	} else{
    		//echo "error";
    	}
    	//return view('elements.merchant.otpcheck');
    	echo '<input class="form-control required", id="check_number", type="text" value="" placeholder="Enter your otp">';
    }
    public function otp_check(Request $request){ 
    	$check_otp = $request->get('check_otp');
    	$match_number = $request->get('match_number');
    	//echo $match_number; exit;
    	$otpverify = DB::table('verifications')->where(['merchant_number'=>$match_number,'otp'=>$check_otp])->first(); 
    	if($otpverify !=''){
    	echo '<p>number verified successfully.</p>';
    	
    	}else{
    		   return view('elements.merchant.otpcheck');
    	    //echo '<p>Inserted wrong OTP.</p>';
    		
    	}
    	
    }
    
    public function get_states(Request $request) { 
         $country_id =  $request->get('country_id');
    	 //echo $country_id; exit;
             //$subcategory = array();
            if(!empty($country_id)){
             $state = DB::table('states')->where(['country_id'=> $country_id,'status'=>1])->orderBy('name', 'ASC')->pluck('name','id');
    		 //print_r($state); exit;
             echo '<option select="selected" value="">Select state</option>';
             foreach($state as $value=> $states){
                 echo '<option value="'.$value.'">'.$states.'</option>';
             } exit;
        }	
    }
    public function get_cities(Request $request) { 
         $state_id =  $request->get('state_id');
    	 //echo $country_id; exit;
             //$subcategory = array();
            if(!empty($state_id)){
             $city = DB::table('cities')->where(['state_id'=> $state_id,'status'=>1])->orderBy('name', 'ASC')->pluck('name','id');
    		 //print_r($state); exit;
             echo '<option select="selected" value="">Select city</option>';
             foreach($city as $value=> $cities){
                 echo '<option value="'.$value.'">'.$cities.'</option>';
             } exit;
        }	
    }
    public function get_localities(Request $request) { 
         $city_id =  $request->get('city_id');
    	// echo $city_id; exit;
             //$subcategory = array();
            if(!empty($city_id)){
             $locality = DB::table('localities')->where(['city_id'=> $city_id,'status'=>1])->orderBy('locality_name', 'ASC')->pluck('locality_name','id');
    		// print_r($locality); exit;
             echo '<option select="selected" value="">Select locality</option>';
             foreach($locality as $value=> $localities){
                 echo '<option value="'.$value.'">'.$localities.'</option>';
             } 
        }	
        exit;
    }

  public function emailConfirmation($ukey = null) { 
        $userInfo = Merchant::where('unique_key', $ukey)->first();
        if ($userInfo) {
            if ($userInfo->activation_status == 1) {
                Session::flash('error_message', "You have already use this link!");
            } else {
                Merchant::where('id', $userInfo->id)->update(array('activation_status' => 1, 'status' => 1));
                Session::flash('success_message', "Your Account has been verified Successfully! Please Login");
            }
        } else {
            Session::flash('error_message', "Invalide URL!");
        }
        return Redirect::to('/merchant/login');
    }
	
	public function myaccount() { 
        $pageTitle = 'My Account';
		$user_id = Auth::guard('merchant')->user()->id;
        $recordInfo = Merchant::where(['id'=>$user_id])->first();
		
		$country = DB::table('countries')->where(['status' => 1])->orderBy('name', 'ASC')->pluck('name', 'id');
		$state = DB::table('states')->where(['status' => 1,'country_id' => $recordInfo->country_id])->orderBy('name', 'ASC')->pluck('name', 'id');
		$city = DB::table('cities')->where(['status' => 1,'state_id' => $recordInfo->state_id])->orderBy('name', 'ASC')->pluck('name', 'id');
		$locality = DB::table('localities')->where(['status' => 1,'city_id' => $recordInfo->city_id])->orderBy('locality_name', 'ASC')->pluck('locality_name', 'id');
		//print_r($state);exit;
		global $week_days;
		global $time_array;
		
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'busineess_name' => 'required',
                'name' => 'required',
                'email_address' => 'required|email|unique:users,email_address,' . $recordInfo->id,
//                'password' => 'min:8',
//                'confirm_password' => 'same:password',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/merchant/user/myaccount')->withErrors($validator)->withInput();
            } else {
				if($input['service_ids'][0] == '0 services selected')
				{
					$validator3 = 'Please select atleast one of the service';
					return Redirect::to('/merchant/user/myaccount/')->withErrors($validator3)->withInput();
				}
				$image = explode(',', $recordInfo['profile_image']);
				//print_r($image); exit;
                $imagesArray = array();
                if ($image) {
                    $imagesArray = array_filter($image);
                }
                if (Input::hasFile('profile_image')) { 
                    $files = Input::file('profile_image');
					//echo'<pre>';print_r($files); exit;
                    if (count($files) > 0) {
                        foreach ($files as $file) {
                            if ($file) {
                                $uploadedFileName = $this->uploadImage($file, MERCHANT_FULL_UPLOAD_PATH);
                                $this->resizeImage($uploadedFileName, MERCHANT_FULL_UPLOAD_PATH, MERCHANT_SMALL_UPLOAD_PATH, MERCHANT_MW, MERCHANT_MH);
                                $imagesArray[] = $uploadedFileName;
                                @unlink(MERCHANT_FULL_UPLOAD_PATH . $recordInfo->images);
                                @unlink(MERCHANT_SMALL_UPLOAD_PATH . $recordInfo->images);
                            }
                        }
                        $imagesArray = implode(',', $imagesArray);
						
                    } else {
                        $imagesArray = '';
                    }
                } else {
                    if ($imagesArray) {
                        $imagesArray = implode(',', $imagesArray);
                    } else {
                        $imagesArray = '';
                    }
                }
//                echo '<pre>';
//                print_r($input);
                if ($input['old_password'] && !password_verify($input['old_password'], $recordInfo->password)) {
                   return Redirect::to('/merchant/user/myaccount')->withErrors('Your current password did not matched, please try with correct password.')->withInput();  
                }
                if ($input['password'] && password_verify($input['password'], $recordInfo->password)) {
                   return Redirect::to('/merchant/user/myaccount')->withErrors('You cannot put your old password as new password, please another password.')->withInput();  
                }
                $updata = array('about_us'=>$input['about_us'],'city_id'=>$input['city_id'],'locality_id'=>$input['locality_id'],'address'=>$input['address'],'zipcode'=>$input['zipcode'],'busineess_name'=>$input['busineess_name'],'name'=>$input['name'],'email_address'=>$input['email_address'],'contact'=>$input['contact'],'profile_image'=>$imagesArray);
				
				$working_days_arr = array();
				$start_time_arr = array();
				$end_time_arr = array();

				foreach($week_days as $wd_key=>$wd_val){
					$weekdaytimefrom = $wd_key."_time_from";
					$weekdaytimeto = $wd_key."_time_to";
					if($input[$weekdaytimefrom] && $input[$weekdaytimeto]){
						$working_days_arr[]= $wd_key;
						$start_time_arr[]= $input[$weekdaytimefrom];
						$end_time_arr[]= $input[$weekdaytimeto];
					}
					unset($input[$weekdaytimefrom]);
					unset($input[$weekdaytimeto]);
					unset($input[$wd_key]);
				}
				if(count($working_days_arr)== 7){
					$updata['is_available_all_days'] = 1;
				}else{
					$updata['is_available_all_days'] = 0;
				}
				$updata['working_days'] = implode(',',$working_days_arr);
				$updata['start_time'] = implode(',',$start_time_arr);
				$updata['end_time'] = implode(',',$end_time_arr);
				
				//update services
				unset($input['service_ids'][0]);
				//print_r($input['service_ids']);

				if (!empty($input['service_ids'])) {
					$service_ids = implode(',', $input['service_ids']);
					$updata['service_ids'] = $service_ids;
				}
				
				if (($key = array_search('attribute selected', $updata)) !== false) {
				//unset($updata['service_ids'][$key]);
				}
				//Social links
				$updata['facebook_link'] = $input['facebook_link'];
				$updata['instagram_link'] = $input['instagram_link'];
				$updata['linkedin_link'] = $input['linkedin_link'];
				$updata['twitter_link'] = $input['twitter_link'];
				$updata['youtube_link'] = $input['youtube_link'];
				
				
				
				
				

                //$updata = array('busineess_name'=>$input['busineess_name'],'name'=>$input['name'],'email_address'=>$input['email_address'],'profile_image'=>$imagesArray);
                
				if ($input['password']) {
                    $new_password = $this->encpassword($input['password']);
                   
                    $updata['password'] = $new_password;
                } 
//                echo '<pre>';
//                print_r($updata);
//                die;
                Merchant::where('id', $recordInfo->id)->update($updata);
                Session::flash('success_message', "Your profile has been updated successfully.");
                return Redirect::to('/merchant/user/myaccount');
            }
        }
		
		$working_days_arr = explode(',',$recordInfo->working_days);
		$start_time_arr = explode(',',$recordInfo->start_time);
		$end_time_arr = explode(',',$recordInfo->end_time);


		foreach($week_days as $wd_key=>$wd_val){
			$weekdaytimefrom = $wd_key."_time_from";
			$weekdaytimeto = $wd_key."_time_to";
			if(in_array($wd_key, $working_days_arr)){
				$key = array_search($wd_key, $working_days_arr);
				$working_days_arr[]= $wd_key;
				$start_time_arr[]= $recordInfo->$weekdaytimefrom;
				$end_time_arr[]= $recordInfo->$weekdaytimeto;
				$recordInfo->$weekdaytimefrom = $start_time_arr[$key];
				$recordInfo->$weekdaytimeto = $end_time_arr[$key];
			}
		}
		$allservices = DB::table('categories')->where(['parent_id'=>$recordInfo->business_type])->pluck('category_name', 'id')->all();
        //print_r($allservices);exit;
		return view('merchant.user.myaccount', ['title' => $pageTitle, 'recordInfo' => $recordInfo, 'myaccountAct' => 'active', 'week_days' => $week_days, 'time_array' => $time_array, 'country' => $country, 'state' => $state, 'city' => $city, 'locality' => $locality, 'allservices' => $allservices]);
    }
    
    public function editprofile() { 
        $pageTitle = 'Edit Profile';
		$user_id = Auth::guard('merchant')->user()->id;
        $recordInfo = Merchant::where(['id'=>$user_id])->first();
		
		//$country = DB::table('countries')->where(['status' => 1])->orderBy('name', 'ASC')->pluck('name', 'id');
        $country = DB::table('countries')->where(['status' => 1,'id' => $recordInfo->country_id])->orderBy('name', 'ASC')->pluck('name', 'id');
		$state = DB::table('states')->where(['status' => 1,'id' => $recordInfo->state_id])->orderBy('name', 'ASC')->pluck('name', 'id');
		$city = DB::table('cities')->where(['status' => 1,'state_id' => $recordInfo->state_id])->orderBy('name', 'ASC')->pluck('name', 'id');
		$locality = DB::table('localities')->where(['status' => 1,'city_id' => $recordInfo->city_id])->orderBy('locality_name', 'ASC')->pluck('locality_name', 'id');
		$business_category = DB::table('categories')->where(['parent_id'=> 0])->orderBy('category_name', 'ASC')->pluck('category_name', 'id');
		//print_r($state);print_r($city);
		global $week_days;
		global $time_array;
		
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'busineess_name' => 'required',
                'name' => 'required',
                'email_address' => 'required|email|unique:users,email_address,' . $recordInfo->id,
//                'password' => 'min:8',
//                'confirm_password' => 'same:password',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/merchant/users/editprofile')->withErrors($validator)->withInput();
            } else {
				if($input['service_ids'][0] == '0 services selected')
				{
					$validator3 = 'Please select atleast one of the service';
					return Redirect::to('/merchant/users/editprofile/')->withErrors($validator3)->withInput();
				}
				$image = explode(',', $recordInfo['profile_image']);
				//print_r($image); exit;
                $imagesArray = array();
                if ($image) {
                    $imagesArray = array_filter($image);
                }
                if (Input::hasFile('profile_image')) { 
                    $files = Input::file('profile_image');
					//echo'<pre>';print_r($files); exit;
                    if (count($files) > 0) {
                        foreach ($files as $file) {
                            if ($file) {
                                $uploadedFileName = $this->uploadImage($file, MERCHANT_FULL_UPLOAD_PATH);
                                $this->resizeImage($uploadedFileName, MERCHANT_FULL_UPLOAD_PATH, MERCHANT_SMALL_UPLOAD_PATH, MERCHANT_MW, MERCHANT_MH);
                                $imagesArray[] = $uploadedFileName;
                                @unlink(MERCHANT_FULL_UPLOAD_PATH . $recordInfo->images);
                                @unlink(MERCHANT_SMALL_UPLOAD_PATH . $recordInfo->images);
                            }
                        }
                        $imagesArray = implode(',', $imagesArray);
						
                    } else {
                        $imagesArray = '';
                    }
                } else {
                    if ($imagesArray) {
                        $imagesArray = implode(',', $imagesArray);
                    } else {
                        $imagesArray = '';
                    }
                }
//                echo '<pre>';
//                print_r($input);
                if ($input['old_password'] && !password_verify($input['old_password'], $recordInfo->password)) {
                   return Redirect::to('/merchant/users/editprofile')->withErrors('Your current password did not matched, please try with correct password.')->withInput();  
                }
                if ($input['password'] && password_verify($input['password'], $recordInfo->password)) {
                   return Redirect::to('/merchant/users/editprofile')->withErrors('You cannot put your old password as new password, please another password.')->withInput();  
                }
                $updata = array('about_us'=>$input['about_us'],'city_id'=>$input['city_id'],'locality_id'=>$input['locality_id'],'address'=>$input['address'],'zipcode'=>$input['zipcode'],'busineess_name'=>$input['busineess_name'],'name'=>$input['name'],'email_address'=>$input['email_address'],'contact'=>$input['contact'],'profile_image'=>$imagesArray);
				
				$working_days_arr = array();
				$start_time_arr = array();
				$end_time_arr = array();

				foreach($week_days as $wd_key=>$wd_val){
					$weekdaytimefrom = $wd_key."_time_from";
					$weekdaytimeto = $wd_key."_time_to";
					if($input[$weekdaytimefrom] && $input[$weekdaytimeto]){
						$working_days_arr[]= $wd_key;
						$start_time_arr[]= $input[$weekdaytimefrom];
						$end_time_arr[]= $input[$weekdaytimeto];
					}
					unset($input[$weekdaytimefrom]);
					unset($input[$weekdaytimeto]);
					unset($input[$wd_key]);
				}
				if(count($working_days_arr)== 7){
					$updata['is_available_all_days'] = 1;
				}else{
					$updata['is_available_all_days'] = 0;
				}
				$updata['working_days'] = implode(',',$working_days_arr);
				$updata['start_time'] = implode(',',$start_time_arr);
				$updata['end_time'] = implode(',',$end_time_arr);
				
				//update services
				unset($input['service_ids'][0]);
				//print_r($input['service_ids']);

				if (!empty($input['service_ids'])) {
					$service_ids = implode(',', $input['service_ids']);
					$updata['service_ids'] = $service_ids;
				}
				
				if (($key = array_search('attribute selected', $updata)) !== false) {
				//unset($updata['service_ids'][$key]);
				}
				//Social links
				$updata['facebook_link'] = $input['facebook_link'];
				$updata['instagram_link'] = $input['instagram_link'];
				$updata['linkedin_link'] = $input['linkedin_link'];
				$updata['twitter_link'] = $input['twitter_link'];
				$updata['youtube_link'] = $input['youtube_link'];
				
				
				
				
				

                //$updata = array('busineess_name'=>$input['busineess_name'],'name'=>$input['name'],'email_address'=>$input['email_address'],'profile_image'=>$imagesArray);
                
				if ($input['password']) {
                    $new_password = $this->encpassword($input['password']);
                   
                    $updata['password'] = $new_password;
                } 
//                echo '<pre>';
//                print_r($updata);
//                die;
                Merchant::where('id', $recordInfo->id)->update($updata);
                Session::flash('success_message', "Your profile has been updated successfully.");
                return Redirect::to('/merchant/user/myaccount');
            }
        }
		
		$working_days_arr = explode(',',$recordInfo->working_days);
		$start_time_arr = explode(',',$recordInfo->start_time);
		$end_time_arr = explode(',',$recordInfo->end_time);


		foreach($week_days as $wd_key=>$wd_val){
			$weekdaytimefrom = $wd_key."_time_from";
			$weekdaytimeto = $wd_key."_time_to";
			if(in_array($wd_key, $working_days_arr)){
				$key = array_search($wd_key, $working_days_arr);
				$working_days_arr[]= $wd_key;
				$start_time_arr[]= $recordInfo->$weekdaytimefrom;
				$end_time_arr[]= $recordInfo->$weekdaytimeto;
				$recordInfo->$weekdaytimefrom = $start_time_arr[$key];
				$recordInfo->$weekdaytimeto = $end_time_arr[$key];
			}
		}
		$allservices = DB::table('categories')->where(['status'=>'1','parent_id'=>$recordInfo->business_type])->get();
        //print_r($allservices);exit;
		return view('merchant.user.editprofile', ['title' => $pageTitle, 'recordInfo' => $recordInfo, 'myaccountAct' => 'active', 'week_days' => $week_days, 'time_array' => $time_array, 'country' => $country, 'state' => $state, 'city' => $city, 'locality' => $locality, 'allservices' => $allservices,'business_category'=>$business_category]);
    }
	public function deleteprofileimageedit($slug = null, $imagename = null) { 	
        if ($slug) {
            $propInfo = DB::table('merchants')->where('slug', $slug)->first();
            $imagesArray = explode(',', $propInfo->profile_image);
            $imageKey = array_search($imagename, $imagesArray);
            unset($imagesArray[$imageKey]);
            if ($imagename) {
                @unlink(MERCHANT_FULL_UPLOAD_PATH . $imagename);
                @unlink(MERCHANT_SMALL_UPLOAD_PATH . $imagename);
            }
            $impldeName = implode(',', $imagesArray);
            Merchant::where('slug', $slug)->update(array('profile_image' => $impldeName));
            Session::flash('success_message', "Profile image deleted successfully.");
            return Redirect::to('merchant/user/myaccount/');
        }
    }
	
	public function forgotPassword() {
	    
	    
        $pageTitle = 'Forgot Password';
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'email_address' => 'required|email'
            );
            //print_r($input);exit;
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/forgot-password')->withErrors($validator);
            } else {
                $userInfo = Merchant::where('email_address', $input['email_address'])->first();
                if (!empty($userInfo)) {
                    $uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
                    Merchant::where('id', $userInfo->id)->update(array('forget_password_status' => 1, 'unique_key' => $uniqueKey));

                    $link = HTTP_PATH . "/merchant/reset-password/" . $uniqueKey;
                    $name = ucwords($userInfo->first_name . ' ' . $userInfo->last_name);
                    $emailId = $userInfo->email_address;
                    $emailTemplate = DB::table('emailtemplates')->where('id', 4)->first();
                    $toRepArray = array('[!username!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                    $fromRepArray = array($name, $link, HTTP_PATH, SITE_TITLE);
                    $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                    $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                    Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
                    Session::flash('success_message', "A link to reset your password was sent to your email address.");
                    return Redirect::to('/merchant/login');
                } else {
                    $error = 'Your email is not registered with ' . SITE_TITLE . '. Please enter correct email or register on ' . SITE_TITLE;
                }
                return Redirect::to('/merchant/forgot-password')->withErrors($error);
            }
        }
        return view('merchant.user.forgotPassword', ['title' => $pageTitle]);
    }

    public function resetPassword($ukey = null) {
        $pageTitle = 'Reset Password';
        $userInfo = Merchant::where('unique_key', $ukey)->first();
        if ($userInfo && $userInfo->forget_password_status == 1) {
            $input = Input::all();
            if (!empty($input)) {
                $rules = array(
                    'password' => 'required|min:8',
                    'confirm_password' => 'required|same:password',
                );
                $validator = Validator::make($input, $rules);
                if ($validator->fails()) {
                    return Redirect::to('/merchant/reset-password/' . $ukey)->withErrors($validator);
                } elseif (password_verify($input['password'], $userInfo->password)) {
                    return Redirect::to('/merchant/reset-password/' . $ukey)->withErrors('You cannot put your old password as new password, please another password.');
                } else {
                    $new_password = $this->encpassword($input['password']);
                    Merchant::where('id', $userInfo->id)->update(array('forget_password_status' => 0, 'password' => $new_password));
                    Session::flash('success_message', "Your Password has been updated successfully. Please Login");
                    return Redirect::to('/merchant/login');
                }
            }
            return view('merchant.user.resetPassword', ['title' => $pageTitle]);
        } else {
            Session::flash('error_message', "You have already use this link!");
            return Redirect::to('/merchant/login');
        }
    }

    public function myOrders(Request $request) {
        $pageTitle = 'My Orders';
        $query = new Order();
        $query = $query->sortable();
        $user_id = Auth::guard('merchant')->user()->id;
        $recordInfo = Merchant::where(['id'=>$user_id])->first();

        $orders = $query->orderBy('created_at', 'DESC')->where(['merchant_id'=>$user_id, 'is_voucher_redeemed'=>1])->paginate(10);
        if ($request->ajax()) {
            return view('elements.merchant.users.myorders', ['orders' => $orders]);
        }
        return view('merchant.user.myorders', ['title' => $pageTitle, 'orders' => $orders, 'myorders' => 'active', 'recordInfo' => $recordInfo]);
    }
	public function myPayments(Request $request) {
        $pageTitle = 'My Payments';
        $query = new Payment();
        $query = $query->sortable();
        $user_id = Auth::guard('merchant')->user()->id;
        $recordInfo = Merchant::where(['id'=>$user_id])->first();

        $payments = $query->orderBy('created_at', 'DESC')->where('merchant_id', $user_id)->paginate(10);
        if ($request->ajax()) {
            return view('elements.merchant.users.mypayments', ['payments' => $payments]);
        }
        return view('merchant.user.mypayments', ['title' => $pageTitle, 'payments' => $payments, 'mypayments' => 'active', 'recordInfo' => $recordInfo]);
    }
	
	public function orderDetail($slug) {
        $recordInfo = Order::where('slug', $slug)->first();
		
        if (!$recordInfo) {
           return Redirect::to('dashboard');
        }
		
		$dealidarr = explode(',',$recordInfo->deals_id);
		$deals = DB::table('deals')->whereIn('id', $dealidarr)->orderBy('id', 'ASC')->pluck('deal_name', 'id')->all();
		//echo "<pre>";//$deals = json_decode(json_encode($deals),true);
		//print_r($recordInfo);exit;
        $pageTitle = 'Order Details';
//
//        $skillsList = DB::table('skills')->where('status', 1)->orderBy('name', 'ASC')->pluck('name', 'id')->all();
//        $countryLists = DB::table('countries')->where('status', 1)->orderBy('name', 'ASC')->pluck('name', 'name')->all();
//        $qualificationsLists = DB::table('qualifications')->where('status', 1)->orderBy('name', 'ASC')->pluck('name', 'name')->all();
//        $mygigs = Gig::where(['status' => 1, 'user_id' => $recordInfo->id])->orderBy('id', 'DESC')->limit(9)->get();
//        $myreviews = Review::where(['status' => 1, 'user_id' => $recordInfo->id])->orderBy('id', 'DESC')->limit(10)->get();
//        $mysavegigs = $this->getSavedGigs();
//
//        $date1 = date('Y-m-d', strtotime("-30 days"));
//        $sellingOrders = DB::table('myorders')
//                ->select('seller_id', 'id', DB::raw('sum(total_amount) as total_sum'))
//                ->where('seller_id', '=', Session::get('user_id'))
//                ->where('created_at', '>=', $date1)
//                ->get();
//
//        $topRatedInfo = DB::table('reviews')->where(['otheruser_id' => Session::get('user_id')])->where('rating', '>', 4)->pluck(DB::raw('count(*) as total'), 'id')->all();

        return view('merchant.user.orderdetail', ['title' => $pageTitle,'single_order'=>$recordInfo,'deals'=>$deals, 'myorders' => 'active']);
    }
	
	public function verifyVoucher(Request $request){
		$error = '';
		$number = $request->input('voucher_number');
		if($number){
			$orderInfo = Order::where('voucher_number', $number)->first();
			if (!empty($orderInfo) && $orderInfo->order_status == 1) {
				$uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
				if($orderInfo->is_voucher_redeemed == 0){
					echo 1;exit;
				}else{
					$error = 'This voucher ID has been already redeemed!';
				}
			} else {
				$error = 'Please enter correct voucher ID';
			}
		}else{
			$error = 'Please enter voucher ID';
		}
		echo $error;	
		//return view('elements.merchant.otpcheck');
		exit;
	}
	public function redeemVoucher() {
		
		$pageTitle = 'Redeem Voucher';
        $input = Input::all();
		$user_id = Auth::guard('merchant')->user()->id;
        $recordInfo = Merchant::where(['id'=>$user_id])->first();
        if (!empty($input)) {
            $rules = array(
                'voucher_number' => 'required'
            );
            //print_r($input);exit;
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/merchant/redeem-voucher')->withErrors($validator);
            } else {
                $orderInfo = Order::where('voucher_number', $input['voucher_number'])->first();
                if (!empty($orderInfo) && $orderInfo->merchant_id == $user_id && $orderInfo->order_status == 1) {
					if($orderInfo->is_voucher_redeemed == 0){
						//$uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
						Order::where('id', $orderInfo->id)->update(array('is_voucher_redeemed' => 1));
						
						Wallet::where('order_id', $orderInfo->id)->update(array('merchant_id' => $user_id));
						
						
						$merchant_amount = $orderInfo->amount;;
						$total_earned_amount = $recordInfo->total_earned_amount+$merchant_amount;
						$wallet_balance = $recordInfo->wallet_balance+$merchant_amount;
						Merchant::where('id', $user_id)->update(array('total_earned_amount' => $total_earned_amount, 'wallet_balance' => $wallet_balance));
						
						Payment::where('order_id', $orderInfo->id)->update(array('merchant_id' => $user_id));
						$order_number = $orderInfo->order_number;
						$amount = $orderInfo->amount;
						$voucher_number = $orderInfo->voucher_number;
						
						$link = HTTP_PATH . "/merchant/order-detail/" . $orderInfo->slug;
						$name = ucwords($orderInfo->User->first_name . ' ' . $orderInfo->User->last_name);
						$emailId = $orderInfo->User->email_address;
						$emailTemplate = DB::table('emailtemplates')->where('id', 4)->first();
						$toRepArray = array('[!username!]', '[!order_number!]', '[!amount!]', '[!voucher_number!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
						$fromRepArray = array($name, $order_number, $amount, $voucher_number, $link, HTTP_PATH, SITE_TITLE);
						$emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
						$emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
						Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
						Session::flash('success_message', "Voucher redeemed successfully!");
						return Redirect::to('/merchant/orderdetail/'.$orderInfo->slug);
					}else{
						$error = 'This voucher ID has been already redeemed!';
					}
                } else {
                    $error = 'Please enter correct voucher ID';
                }
                return Redirect::to('/merchant/redeem-voucher')->withErrors($error);
            }
        }
        return view('merchant.user.redeem_voucher', ['title' => $pageTitle]);
	}
	
	public function mywallet(Request $request) { 
	    $pageTitle = 'My Wallet';
        $activetab = 'mywallet';
        $query = new Wallet();
		$user_id = Auth::guard('merchant')->user()->id;
        $recordInfo = Merchant::where(['id'=>$user_id])->first();
        
        $query = $query->sortable();
		  if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                Deal::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                Deal::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                Deal::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            }
        }
        $query = $query->whereHas('Order',function($q){
                   $q->where('id', '>', 0);
            });
        
		$wallet = $query->where(['merchant_id'=>$user_id, 'status'=>1])->orderBy('id', 'DESC')->paginate(10);

        if ($request->ajax()) {
            return view('elements.merchant.users.mywallet',['merchant'=>$merchant]);
        }
        return view('merchant.user.mywallet', ['userInfo' => $recordInfo, 'title' => $pageTitle, 'mywallet' => 'active','wallet'=>$wallet]);
    }
	
	public function sendwithdrawrequest() { //echo "sdfd"; exit;
        $pageTitle = 'Send Withdraw Request';
        $activetab = 'mywallet';
		$adminInfo = Admin::where('id', 1)->first();
		
		$user_id = Auth::guard('merchant')->user()->id;
        $recordInfo = Merchant::where(['id'=>$user_id])->first();
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'amount' => 'required|lte:'.$recordInfo->wallet_balance,
                'description' => 'required',
            );
			$customMessages = [
                'amount.lte' => "You can't withdraw amount more than available balance.",
            ];
            $validator = Validator::make($input, $rules, $customMessages);
            if ($validator->fails()) {
                return Redirect::to('merchant/sendwithdrawrequest')->withErrors($validator)->withInput();
            } else {

                $amount= $input['amount'];
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug('withdraw'.$user_id.time(), 'withdrawals');
                $serialisedData['merchant_id'] = $user_id;
                $serialisedData['status'] = 0;
                $withdraw_id = Withdrawal::insertGetId($serialisedData);
				
				$updated_balance = $recordInfo->wallet_balance-$amount;
                Merchant::where('id', $user_id)->update(array('wallet_balance' => $updated_balance));

				$serialisedData = array();
				$serialisedData['withdrawal_id'] = $withdraw_id;
				$serialisedData['merchant_id'] = $user_id;
                $serialisedData["total_amount"] = $amount;
                $serialisedData["amount"] = $amount;
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
				
                Session::flash('success_message', "Your withdraw request sent successfully!");
                return Redirect::to('merchant/mywallet');
            }
        }
        return view('merchant.user.sendwithdrawrequest', ['title' => $pageTitle, 'mywallet' => 'active', 'userInfo'=>$recordInfo]);
    }
	
	

    
}
?>
