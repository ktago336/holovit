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
//use App\Models\Message;
//use App\Models\Category;
//use App\Models\Service;
//use App\Models\Myorder;
//use App\Models\Image;
//use App\Models\Gig;
//use App\Models\Review;
//use App\Models\Notification;
//use App\Models\Payment;
//use App\Models\Wallet;

class UsersController extends Controller {

    public function __construct() {
//        $this->middleware('userlogedin', ['only' => ['login', 'forgotPassword', 'resetPassword', 'register']]);
//        $this->middleware('is_userlogin', ['except' => ['logout', 'login','forgotPassword', 'resetPassword', 'redirectToGoogle', 'handleGoogleCallback', 'redirectToFacebook', 'handleFacebookCallback', 'redirectToLinkedin', 'handleLinkedinCallback', 'register', 'sociallogin','emailConfirmation', 'publicprofile']]);
    }

    public function login() {
        $this->requestAuthentication('POST');
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $email = $userData['email'];
        $password = $userData['password'];
        $device_type = $userData['device_type'];
        $device_id = $userData['device_id'];

        $userInfo = User::where('email_address', $email)->first();
        if (!empty($userInfo)) {
            if (password_verify($password, $userInfo->password)) {
                if ($userInfo->status == 1 && $userInfo->activation_status == 1) {
                    //print_r($userInfo);exit;
                    $data = $this->logindata($userInfo);
                    User::where('id', $userInfo->id)->update(array('device_type' => $device_type, 'device_id' => $device_id));
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

    public function logindata($userCheck) {
        $data = array();
        $data['user_id'] = $userCheck->id;
        $data['first_name'] = $userCheck->first_name;
        $data['last_name'] = $userCheck->last_name;
        $data['email_address'] = $userCheck->email_address;
        $data['contact'] = $userCheck->contact;
        $data['profile_image'] = $userCheck->profile_image;
        $token = $this->setToken($userCheck);
        $data['token'] = $token;
        return $data;
    }

    public function dashboard() {
        $tokenData = $this->requestAuthentication('POST', 1);
        echo '<pre>';
        print_r($tokenData);
        exit;

        $userCheck = $this->Users->find()->where(['Users.id' => $userid])->first();
    }

    public function forgotPassword() {
        $this->requestAuthentication('POST');
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $emailAddress = $userData['email'];
        $userInfo = User::where('email_address', $emailAddress)->first();
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
            Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

            $this->successOutput('A link to reset your password was sent to your email address.');
        } else {
            $this->errorOutputResult('Email address you have entered is not found in our database. Please enter correct email address.');
        }
    }

    public function register() {
        $this->requestAuthentication('POST');
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $email = $userData['email'];
        $device_type = $userData['device_type'];
        $device_id = $userData['device_id'];
        if ($userData['type'] == 'normal') {
            $input = $userData;
            $errorr = 0;
            $userInfo = User::where('email_address', $email)->first();
            if (!empty($userInfo)) {
                $this->errorOutputResult('Email address already exist.');
            }
            $input['first_name'] = ucfirst(trim($input['first_name']));
            $input['last_name'] = ucfirst(trim($input['last_name']));
            $input['email_address'] = ucfirst(trim($email));
            $serialisedData = $this->serialiseFormData($input);
            $serialisedData['slug'] = $this->createSlug($input['first_name'] . ' ' . $input['last_name'], 'users');
            $serialisedData['status'] = 0;
            $serialisedData['password'] = $this->encpassword($input['password']);
            $uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
            $serialisedData['unique_key'] = $uniqueKey;
            unset($serialisedData['type']);
            unset($serialisedData['email']);
            User::insert($serialisedData);

            $link = HTTP_PATH . "/email-confirmation/" . $uniqueKey;
            $name = $input['first_name'] . ' ' . $input['last_name'];
            $emailId = $input['email'];
            $new_password = $input['password'];

            $emailTemplate = DB::table('emailtemplates')->where('id', 3)->first();
            $toRepArray = array('[!email!]', '[!username!]', '[!password!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
            $fromRepArray = array($emailId, $name, $new_password, $link, HTTP_PATH, SITE_TITLE);
            $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
            $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
            Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

            $this->successOutput('We have sent you an account activation link by email. Please check your spam folder if you do not receive the email within the next few minutes.');
        } else {
            $source = $userData['type'];
            $userInfo = User::where('email_address', $email)->first();
            if (!empty($userInfo)) {
                $data = $this->logindata($userInfo);
                User::where('id', $userInfo->id)->update(array('device_type' => $device_type, 'device_id' => $device_id));
                $this->successOutputResult('login sucessfully', json_encode($data));
            } else {
                $input = $userData;
                $serialisedData = array();
                $input['first_name'] = ucfirst(trim($input['first_name']));
                $input['last_name'] = ucfirst(trim($input['last_name']));
                $input['email_address'] = ucfirst(trim($email));
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['first_name'] . ' ' . $input['last_name'], 'users');
                $serialisedData['status'] = 1;
                $serialisedData['activation_status'] = 1;
                $password = bin2hex(openssl_random_pseudo_bytes(4));
                $serialisedData['password'] = $this->encpassword($password);
                $uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
                $serialisedData['unique_key'] = $uniqueKey;
                unset($serialisedData['type']);
                unset($serialisedData['email']);
                User::insert($serialisedData);

                $userInfo = User::where('email_address', $email)->first();
                $name = $input['first_name'] . ' ' . $input['last_name'];
                $emailId = $input['email'];
                $login_type = $source;

                $emailTemplate = DB::table('emailtemplates')->where('id', 5)->first();
                $toRepArray = array('[!email!]', '[!name!]', '[!username!]', '[!password!]', '[!login_type!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($emailId, $name, $name, $password, $login_type, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

                $data = $this->logindata($userInfo);
                $this->successOutputResult('login sucessfully', json_encode($data));
            }
        }
    }

    public function changepassword() {
        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $old_password = $userData['old_password'];
        $newpassword = $userData['new_password'];
        $recordInfo = User::where('id', $userid)->first();
        if (!password_verify($old_password, $recordInfo->password)) {
            $this->errorOutputResult('Current password is not correct.');
        } else if ($old_password == $newpassword) {
            $this->errorOutputResult('You can not change new password same as current password.');
        } else {
            $new_password = $this->encpassword($newpassword);
            User::where('id', Session::get('user_id'))->update(array('password' => $new_password));
            $this->successOutput('Your Password has been changed successfully.');
        }
    }

    public function setpaypalemail() {
        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $paypal_email = $userData['paypalemail'];
        User::where('id', $userid)->update(array('paypal_email' => $paypal_email));
        $this->successOutput('Your Password has been changed successfully.');
    }

    public function getcountrylist() {
        $tokenData = $this->requestAuthentication('GET', 0);
        $countryLists = DB::table('countries')->where('status', 1)->orderBy('name', 'ASC')->pluck('name', 'id')->all();
        $i = 0;
        $data = array();
        if (!empty($countryLists)) {
            foreach ($countryLists as $key => $val) {
                $data[$i]['id'] = $key;
                $data[$i]['name'] = $val;
                $i++;
            }
        }
        $this->successOutputResult('Country Listng', json_encode($data));
    }

    public function getprofile() {
        $tokenData = $this->requestAuthentication('GET', 1);

        $userid = $tokenData['user_id'];
        $recordInfo = User::where('id', $userid)->first();
        $data = array();
        $data['id'] = $recordInfo->id;
        $data['first_name'] = $recordInfo->first_name;
        $data['last_name'] = $recordInfo->last_name;
        $data['email_address'] = $recordInfo->email_address;
        $data['contact'] = $recordInfo->contact;
        $data['profile_image'] = $recordInfo->profile_image;
        $data['city'] = $recordInfo->city;
        $data['country_id'] = $recordInfo->country_id;
        $data['zipcode'] = $recordInfo->zipcode;
        $data['description'] = $recordInfo->description;
        $langArray = array();
        if ($recordInfo->languages) {
            $dd = 0;
            foreach (json_decode($recordInfo->languages) as $key => $lang) {
                $langArray[$dd]['key'] = $key;
                $langArray[$dd]['lang_name'] = $lang->lang_name;
                $langArray[$dd]['lang_level'] = $lang->lang_level;
                $dd++;
            }
        }
        $data['languages'] = $langArray;
        $skillsList = DB::table('skills')->where('status', 1)->orderBy('name', 'ASC')->pluck('name', 'id')->all();
        $skillsArray = array();
        if ($recordInfo->skills) {
            $dd = 0;
            foreach (explode(',', $recordInfo->skills) as $skillid) {
                $skillsArray[$dd]['id'] = $skillid;
                $skillsArray[$dd]['name'] = $skillsList[$skillid];
                $dd++;
            }
        }
        $data['skills'] = $skillsArray;
        $educationArray = array();
        if ($recordInfo->educations) {
            $dd = 0;
            foreach (json_decode($recordInfo->educations) as $key => $edu) {
                $educationArray[$dd]['key'] = $key;
                $educationArray[$dd]['qual_name'] = $edu->qual_name;
                $educationArray[$dd]['stream_name'] = $edu->stream_name;
                $educationArray[$dd]['university_name'] = $edu->university_name;
                $educationArray[$dd]['country_name'] = $edu->country_name;
                $educationArray[$dd]['year'] = $edu->year;
                $dd++;
            }
        }
        $data['educations'] = $educationArray;
        $certificationArray = array();
        if ($recordInfo->certifications) {
            $dd = 0;
            foreach (json_decode($recordInfo->certifications) as $key => $edu) {
                $certificationArray[$dd]['key'] = $key;
                $certificationArray[$dd]['certificate_name'] = $edu->certificate_name;
                $certificationArray[$dd]['certificate_from'] = $edu->certificate_from;
                $certificationArray[$dd]['year'] = $edu->year;
                $dd++;
            }
        }
        $data['certifications'] = $certificationArray;
        $this->successOutputResult('login sucessfully', json_encode($data));
    }

    public function editprofile() {
        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $input = $userData;
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['tmp_name'] != '') {
            $file = $_FILES['profile_image'];
            $file = Input::file('profile_image');
            $uploadedFileName = $this->uploadImage($file, PROFILE_FULL_UPLOAD_PATH);
            $this->resizeImage($uploadedFileName, PROFILE_FULL_UPLOAD_PATH, PROFILE_SMALL_UPLOAD_PATH, PROFILE_MW, PROFILE_MH);
            $input['profile_image'] = $uploadedFileName;
            $recordInfo = User::where('id', $userid)->first();
            if ($recordInfo->profile_image) {
                @unlink(PROFILE_FULL_UPLOAD_PATH . $recordInfo->profile_image);
                @unlink(PROFILE_SMALL_UPLOAD_PATH . $recordInfo->profile_image);
            }
        } else {
            unset($input['profile_image']);
        }

        $serialisedData = $this->serialiseFormData($input, 1); //send 1 for edit
        User::where('id', $userid)->update($serialisedData);
        $this->successOutput('Profile details updated successfully.');
    }

    public function changepicture() {
        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $input = $userData;
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['tmp_name'] != '') {
            $file = $_FILES['profile_image'];
            $file = Input::file('profile_image');
            $uploadedFileName = $this->uploadImage($file, PROFILE_FULL_UPLOAD_PATH);
            $this->resizeImage($uploadedFileName, PROFILE_FULL_UPLOAD_PATH, PROFILE_SMALL_UPLOAD_PATH, PROFILE_MW, PROFILE_MH);
            $input['profile_image'] = $uploadedFileName;
            $recordInfo = User::where('id', $userid)->first();
            if ($recordInfo->profile_image) {
                @unlink(PROFILE_FULL_UPLOAD_PATH . $recordInfo->profile_image);
                @unlink(PROFILE_SMALL_UPLOAD_PATH . $recordInfo->profile_image);
            }
        } else {
            unset($input['profile_image']);
        }

        $serialisedData = $this->serialiseFormData($input, 1); //send 1 for edit
        User::where('id', $userid)->update($serialisedData);
//        $this->successOutput('Profile image updated successfully.');
        $this->successOutputResult('Profile image updated successfully.', json_encode($uploadedFileName));
    }

    public function sellingorders() {
        echo 'ff';
        exit;
        $tokenData = $this->requestAuthentication('GET', 1);
        $userid = $tokenData['user_id'];
//        echo $userid;exit;
//        $allrecords  = Myorder::where('seller_id', Session::get('user_id'))->orderBy('id', 'DESC')->get();
//        return view('myorders.sellingorders', ['title' => $pageTitle, 'allrecords'=>$allrecords]);  
    }

    public function getcategorylist() {
        $tokenData = $this->requestAuthentication('GET', 0);
        $categoryLists = Category::where('status', 1)->where('parent_id', 0)->orderBy('name', 'ASC')->select('name', 'id', 'image', 'description')->get();
        //echo '<pre>';print_r($categoryLists);
        //$categoryLists  = DB::table('categories')->where('status', 1)->orderBy('name', 'ASC')->pluck('name','id')->all();    
        $i = 0;
        $data = array();
        if (!empty($categoryLists)) {
            foreach ($categoryLists as $key => $val) {
                $data[$i]['id'] = $val['id'];
                $data[$i]['name'] = $val['name'];
                $data[$i]['image'] = $val['image'];
                $data[$i]['description'] = str_replace('<br>', ' ', $val['description']);
                $i++;
            }
        }
        $this->successOutputResult('Category Listng', json_encode($data));
    }

    public function getsubcategorylist() {
        //$tokenData = $this->requestAuthentication('POST', 1);
        $data = $_POST['jsonData'];
        $userData = json_decode($data, true);
        $parent_id = $userData['id'];



//        $query = new Category();
//        $query = $query->with('categories');
//        $query = $query->where('status', 1);
//        $query = $query->where('parent_id', $parent_id);
//        $categoryLists  = $query->orderBy('name', 'ASC')->all();  
        $categoryLists = Category::where('status', 1)->where('parent_id', $parent_id)->orderBy('name', 'ASC')->pluck('name', 'id')->all();
        $i = 0;
        $data = array();
        if (!empty($categoryLists)) {
            foreach ($categoryLists as $key => $val) {
                $data[$i]['id'] = $key;
                $data[$i]['name'] = $val;
                $i++;
            }
        }
        $this->successOutputResult('Sub Category Listng', json_encode($data));
    }

    public function getskilllist() {
        $tokenData = $this->requestAuthentication('GET', 0);
        $skillLists = DB::table('skills')->where('status', 1)->orderBy('name', 'ASC')->pluck('name', 'id')->all();
        $i = 0;
        $data = array();
        if (!empty($skillLists)) {
            foreach ($skillLists as $key => $val) {
                $data[$i]['id'] = $key;
                $data[$i]['name'] = $val;
                $i++;
            }
        }
        $this->successOutputResult('Skill Listng', json_encode($data));
    }

    /*     * ***Language Listing***** */

    public function getlanguagelist() {
        $tokenData = $this->requestAuthentication('GET', 0);
        global $searchLanguageArray;
        $i = 0;
        $data = array();
        if (!empty($searchLanguageArray)) {
            foreach ($searchLanguageArray as $key => $val) {
                $data[$i]['id'] = $key;
                $data[$i]['name'] = $val;
                $i++;
            }
        }
        $this->successOutputResult('Language Listng', json_encode($data));
    }

    public function gettopgigs() {
        $tokenData = $this->requestAuthentication('GET', 0);

//        $gigsLists  = DB::table('gigs')->where('status', 1)->orderBy('title', 'ASC')->all();    
        $gigsLists = Gig::where('status', 1)->orderBy('title', 'DESC')->pluck('title', 'id')->all();
        $i = 0;
        $data = array();
        if (!empty($gigsLists)) {
            foreach ($gigsLists as $key => $val) {
                $data[$i]['id'] = $key;
                $data[$i]['title'] = $val;
                $images = DB::table('images')->where('status', 1)->where('gig_id', $key)->pluck('name', 'id')->all();
                $j = 0;
                if (!empty($images)) {
                    foreach ($images as $key1 => $val1) {
                        $data[$i]['image'][$j] = $val1;
                        $j++;
                    }
                } else {
                    $data[$i]['image'][] = '';
                }

                $i++;
            }
        }


        $this->successOutputResult('Gigs Listng', json_encode($data));
    }

    public function getgigslisting() {

        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $data = $_POST['jsonData'];
        $userData = json_decode($data, true);
        $cat_id = $userData['cat_id'];
        $subcat_id = $userData['subcat_id'];
        $price_min = $userData['price_min'];
        $price_max = $userData['price_max'];
        $delivery_time = $userData['delivery_time'];
        $language = $userData['language'];
        $country_id = $userData['country_id'];
        $keyword = isset($userData['keyword']) ? $userData['keyword'] : '';

        $query = new Gig();
        //$query = $query->with('User');
        $query = $query->where('status', 1);

        if ($cat_id) {
            $catInfo = Category::where('id', $cat_id)->first();
            if (empty($catInfo)) {
                return Redirect::to('gigs');
            } else {
                $query = $query->where('category_id', $cat_id);
            }
        }
        if ($keyword) {
            $query = $query->where('title', 'like', '%' . $keyword . '%');
        }

        if ($subcat_id && $subcat_id > 0) {
            $query = $query->where('subcategory_id', $subcat_id);
        }

        if ($price_min && $price_min != '') {
            $query = $query->where('basic_price', '>=', $price_min);
        }
        if ($price_max && $price_max != '') {
            $query = $query->where('basic_price', '<=', $price_max);
        }
        if ($delivery_time && $delivery_time > 0) {
            $query = $query->where('basic_delivery', '<=', $delivery_time);
        }
        if ($language && $language != '') {
            $langaugeArray = $language;
            $query = $query->whereHas('User', function($q) use ($langaugeArray) {
                $first = array_shift($langaugeArray);
                $q = $q->where('languages', 'like', '%' . $first . '%');
                if (count($langaugeArray) > 0) {
                    foreach ($langaugeArray as $langn) {
                        $q = $q->orWhere('languages', 'like', '%' . $langn . '%');
                    }
                }
            });
        }

        if ($country_id && $country_id > 0) {
            $query = $query->whereHas('User', function($q) use ($country_id) {
                $q->where('country_id', $country_id);
            });
        }

        $gigsLists = $query->orderBy('title', 'ASC')->get();
        //$gigsLists  = Gig::where('status', 1)->orderBy('title', 'ASC')->pluck('title','id')->all(); 
        $i = 0;
        $data = array();
        if (!empty($gigsLists)) {
            foreach ($gigsLists as $key => $val) {
                $data[$i]['id'] = $val->id;
                $data[$i]['title'] = $val->title;
                $data[$i]['name'] = $val->User->first_name . ' ' . $val->User->last_name;
                $data[$i]['average_rating'] = $val->User->average_rating;
                $data[$i]['total_review'] = $val->User->total_review;
                $data[$i]['basic_price'] = $val->basic_price;
                $data[$i]['like'] = 0;
                $mysavegigs = $this->getSavedGig($userid);
                if (in_array($val->id, $mysavegigs)) {
                    $data[$i]['like'] = 1;
                }
                $images = DB::table('images')->where('status', 1)->where('gig_id', $val->id)->pluck('name', 'id')->all();
                $j = 0;
                if (!empty($images)) {
                    foreach ($images as $key1 => $val1) {
                        $data[$i]['image'][$j] = $val1;
                        $j++;
                    }
                } else {
                    $data[$i]['image'][0] = '';
                }

                $i++;
            }
        }


        $this->successOutputResult('Gigs Listng', json_encode($data));
    }

    public function gigdetail() {
        //echo '<pre>';print_r($headers);
        $headers = apache_request_headers();
        if(isset($headers['token']) && $headers['token'] !=''){
                $token = $headers['token'];
            }else{
                $token = $headers['Token'];
            }
            
            if($token){
                $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
            }else{
                $tokenData = $this->requestAuthentication('POST', 0);
        $userid = $tokenData['user_id'];
            }
        
//        echo '<pre>';print_r($tokenData);die;
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $id = $userData['id'];

        $recordInfo = Gig::where('id', $id)->first();

        $query = new Review();
        $query = $query->with('Myorder');
        $query = $query->where('status', 1);
        if (empty($recordInfo)) {
            $this->errorOutputResult('Gig not found.');
            exit;
        }

        $gig_id = $recordInfo->id;
        $query = $query->whereHas('Myorder', function($q) use ($gig_id) {
            $q->where('gig_id', $gig_id)->where('as_a', 'seller');
        });

        $gigreviews = $query->orderBy('id', 'DESC')->limit(10)->get();
        $settingsInfo = DB::table('settings')->where('id', 1)->first();
        $admin_commission = $settingsInfo->admin_commission;

        $amountArray = $this->getWallerAmount($userid);
        $wall_bal = number_format($amountArray['availableforwithdraw'], 2);

//        echo '<pre>';
//        print_r($recordInfo);
        $data = array();
        $data['id'] = $recordInfo->id;
        $data['date'] = $recordInfo->created_at->format('d M, Y');
        $data['seller_name'] = $recordInfo->User->first_name . ' ' . $recordInfo->User->last_name;
        $data['seller_id'] = $recordInfo->User->id;
        $data['title'] = $recordInfo->title;

        $data['admin_commission'] = number_format($admin_commission, 2, '.', '');
        $data['wallet_balance'] = $wall_bal;

        $data['basic_title'] = $recordInfo->basic_title;
        $data['basic_description'] = $recordInfo->basic_description;
        $data['basic_delivery'] = $recordInfo->basic_delivery;
        $data['basic_revision'] = $recordInfo->basic_revision;
        $data['basic_price'] = $recordInfo->basic_price;

        $data['standard_title'] = $recordInfo->standard_title;
        $data['standard_description'] = $recordInfo->standard_description;
        $data['standard_delivery'] = $recordInfo->standard_delivery;
        $data['standard_revision'] = $recordInfo->standard_revision;
        $data['standard_price'] = $recordInfo->standard_price;

        $data['premium_title'] = $recordInfo->premium_title;
        $data['premium_description'] = $recordInfo->premium_description;
        $data['premium_delivery'] = $recordInfo->premium_delivery;
        $data['premium_revision'] = $recordInfo->premium_revision;
        $data['premium_price'] = $recordInfo->premium_price;

        $data['description'] = $recordInfo->description;
        $data['seller_image'] = $recordInfo->User->profile_image;
        $data['seller_average_rating'] = $recordInfo->User->average_rating;
        $data['seller_total_review'] = $recordInfo->User->total_review;
        $data['about_seller'] = $recordInfo->User->description;
        $data['like'] = 0;
        $mysavegigs = $this->getSavedGig($userid);
        if (in_array($gig_id, $mysavegigs)) {
            $data['like'] = 1;
        }
        $farray = array();
        if (isset($recordInfo->User->city) && $recordInfo->User->city != '') {
            $farray[] = $recordInfo->User->city;
        }
        if (isset($recordInfo->User->Country->name) && $recordInfo->User->Country->name != '') {
            $farray[] = $recordInfo->User->Country->name;
        }
        $data['seller_from'] = implode(', ', $farray);
        $data['member_since'] = date('F Y', strtotime($recordInfo->User->created_at));
        $langArray = array();
        if ($recordInfo->User->languages) {
            foreach (json_decode($recordInfo->User->languages) as $key => $lang) {
                $langArray[$key] = $lang->lang_name;
            }
        }
        $data['languages'] = implode(', ', $langArray);

        $images = DB::table('images')->where('status', 1)->where('gig_id', $recordInfo->id)->pluck('name', 'id')->all();
        $j = 0;
        if (!empty($images)) {
            foreach ($images as $key1 => $val1) {
                $data['image'][$j] = $val1;
                $j++;
            }
        } else {
            $data['image'][] = '';
        }

        $f = 0;
        $r = 0;
        if (!empty($gigreviews)) {
            foreach ($gigreviews as $key2 => $val2) {
                $data['review'][$r]['as_a'] = $val2->as_a;
                $data['review'][$r]['name'] = $val2->Otheruser->first_name . ' ' . $val2->Otheruser->last_name;
                $data['review'][$r]['image'] = $val2->Otheruser->profile_image;
                $data['review'][$r]['rating'] = $val2->rating;
                $data['review'][$r]['comment'] = $val2->comment;
                $data['review'][$r]['post_date'] = date('F Y', strtotime($val2->created_at));
                $r++;
            }
        } else {
            $data['review'][] = '';
        }

        if (!empty($recordInfo->Gigfaq) && count($recordInfo->Gigfaq) > 0) {
            foreach ($recordInfo->Gigfaq as $key3 => $val3) {
                $data['faq'][$f]['id'] = $val3->id;
                $data['faq'][$f]['question'] = $val3->question;
                $data['faq'][$f]['answer'] = $val3->answer;
                $data['faq'][$f]['post_date'] = date('F Y', strtotime($val3->created_at));
                $f++;
            }
        } else {
            $data['faq'] = [];
        }
//        echo '<pre>';
//        print_r($data);
//        exit;
//        return view('gigs.detail', ['title' => $pageTitle, 'recordInfo' => $recordInfo, 'gigreviews' => $gigreviews]);
//echo '<pre>';print_r($recordInfo->Gigfaq);exit;
        $this->successOutputResult('Gigs Listng', json_encode($data));
        exit;
    }

    public function gethomedetail() {
        $tokenData = $this->requestAuthentication('GET', 0);
        $userid = isset($tokenData['user_id']) ? $tokenData['user_id'] : 0;
        $gigsLists = Gig::where('status', 1)->orderBy('title', 'DESC')->limit(10)->get();
        $i = 0;
        $data = array();

        if (!$gigsLists->isEmpty()) {
            foreach ($gigsLists as $key => $val) {
                $data['gigs'][$i]['id'] = $val->id;
                $data['gigs'][$i]['title'] = $val->title;
                $data['gigs'][$i]['name'] = $val->User->first_name . ' ' . $val->User->last_name;
                $data['gigs'][$i]['average_rating'] = $val->User->average_rating;
                $data['gigs'][$i]['total_review'] = $val->User->total_review;
                $data['gigs'][$i]['basic_price'] = $val->basic_price;
                $data['gigs'][$i]['like'] = 0;

                if ($val->User->profile_image) {
                    $data['gigs'][$i]['seller_image'] = $val->User->profile_image;
                } else {
                    $data['gigs'][$i]['seller_image'] = '';
                }

                if ($userid) {
                    $mysavegigs = $this->getSavedGig($userid);
                    if (in_array($val->id, $mysavegigs)) {
                        $data['gigs'][$i]['like'] = 1;
                    }
                } else {
                    $data['gigs'][$i]['like'] = 0;
                }

                $images = DB::table('images')->where('status', 1)->where('gig_id', $val->id)->pluck('name', 'id')->all();
                $j = 0;
                if (!empty($images)) {
                    foreach ($images as $key1 => $val1) {
                        $data['gigs'][$i]['image'][$j] = $val1;
                        $j++;
                    }
                } else {
                    $data['gigs'][$i]['image'] = [];
                }
                $i++;
            }
        } else {
            $data['gigs'] = [];
        }

        $categoryLists = Category::where('status', 1)->where('parent_id', 0)->orderBy('name', 'ASC')->select('name', 'id', 'image', 'description')->limit(3)->get();
        $i = 0;

        if (!$categoryLists->isEmpty()) {
            foreach ($categoryLists as $key => $val) {
                $data['category'][$i]['id'] = $val['id'];
                $data['category'][$i]['name'] = $val['name'];
                $data['category'][$i]['image'] = $val['image'];
                $data['category'][$i]['description'] = str_replace('<br>', ' ', $val['description']);
                $i++;
            }
        } else {
            $data['category'] = [];
        }

        $this->successOutputResult('Gigs Listng', json_encode($data));
    }

    public function liked() {

        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $id = $userData['id'];

        $recordInfo = Gig::where('id', $id)->first();
        $type = $userData['type'];
        if ($type == 1) {
            $mysavegigsAA = DB::table('savedgigs')->where(['user_id' => $userid])->first();
            if ($mysavegigsAA) {
                $mysavegigs = array();
                if ($mysavegigsAA->gig_ids) {
                    $mysavegigs = explode(',', $mysavegigsAA->gig_ids);
                }
                if (!in_array($id, $mysavegigs)) {
                    $mysavegigs[] = $id;
                }
                $gigidss = implode(',', $mysavegigs);
                DB::table('savedgigs')->where('id', $mysavegigsAA->id)->update(['gig_ids' => $gigidss]);
            } else {
                $serialisedData = array();
                $serialisedData['user_id'] = $userid;
                $serialisedData['gig_ids'] = $id;
                $serialisedData = $this->serialiseFormData($serialisedData);
                DB::table('savedgigs')->insert($serialisedData);
            }
        } else {
            $mysavegigsAA = DB::table('savedgigs')->where(['user_id' => $userid])->first();
            $mysavegigs = array();
            if ($mysavegigsAA->gig_ids) {
                $mysavegigs = explode(',', $mysavegigsAA->gig_ids);
            }
            if (($key = array_search($id, $mysavegigs)) !== false) {
                unset($mysavegigs[$key]);
            }
            $gigidss = implode(',', $mysavegigs);
            DB::table('savedgigs')->where('id', $mysavegigsAA->id)->update(['gig_ids' => $gigidss]);
        }

        $mysavegigs = array();
        if ($userid) {
            $mysavegigsAA = DB::table('savedgigs')->where(['user_id' => $userid])->first();
            if ($mysavegigsAA) {
                if ($mysavegigsAA->gig_ids) {
                    $mysavegigs = explode(',', $mysavegigsAA->gig_ids);
                }
            }
        }

        $this->successOutputResult('Status changed successfully', json_encode($mysavegigs));
        exit;
    }

    public function getSavedGig($userid = null) {
        $mysavegigs = array();
        if ($userid) {
            $mysavegigsAA = DB::table('savedgigs')->where(['user_id' => $userid])->first();
            if ($mysavegigsAA) {
                if ($mysavegigsAA->gig_ids) {
                    $mysavegigs = explode(',', $mysavegigsAA->gig_ids);
                }
            }
        }
        return $mysavegigs;
    }

    public function getsavedgigs() {

        $tokenData = $this->requestAuthentication('GET', 1);
        $userid = $tokenData['user_id'];

        $mysavegigsAA = DB::table('savedgigs')->where(['user_id' => $userid])->first();
        $mysavegigs = array(0);
        if ($mysavegigsAA) {
            if ($mysavegigsAA->gig_ids) {
                $mysavegigs = explode(',', $mysavegigsAA->gig_ids);
            }
        }

        $gigsLists = Gig::whereIn('id', $mysavegigs)->get();
        $i = 0;
        $data = array();
        if (!empty($gigsLists)) {
            foreach ($gigsLists as $key => $val) {
                $data[$i]['id'] = $val->id;
                $data[$i]['title'] = $val->title;
                $data[$i]['name'] = $val->User->first_name . ' ' . $val->User->last_name;
                $data[$i]['average_rating'] = $val->User->average_rating;
                $data[$i]['total_review'] = $val->User->total_review;
                $data[$i]['basic_price'] = $val->basic_price;
                $data[$i]['like'] = 0;
                $mysavegigs = $this->getSavedGig($userid);
                if (in_array($val->id, $mysavegigs)) {
                    $data[$i]['like'] = 1;
                }
                $images = DB::table('images')->where('status', 1)->where('gig_id', $val->id)->pluck('name', 'id')->all();
                $j = 0;
                if (!empty($images)) {
                    foreach ($images as $key1 => $val1) {
                        $data[$i]['image'][$j] = $val1;
                        $j++;
                    }
                } else {
                    $data[$i]['image'][0] = '';
                }

                $i++;
            }
        }


        $this->successOutputResult('Saved Gigs Listng', json_encode($data));
    }

    public function gigslisting() {

        $tokenData = $this->requestAuthentication('GET', 1);
        $userid = $tokenData['user_id'];

        $query = new Gig();
        //$query = $query->with('User');
        $query = $query->where('status', 1);
        $query = $query->where('user_id', $userid);

        $gigsLists = $query->orderBy('title', 'ASC')->get();
        $i = 0;
        $data = array();
        if (!empty($gigsLists)) {
            foreach ($gigsLists as $key => $val) {
                $data[$i]['id'] = $val->id;
                $data[$i]['title'] = $val->title;
                $data[$i]['name'] = $val->User->first_name . ' ' . $val->User->last_name;
                $data[$i]['average_rating'] = $val->User->average_rating;
                $data[$i]['total_review'] = $val->User->total_review;
                $data[$i]['basic_price'] = $val->basic_price;
                $data[$i]['like'] = 0;
                $mysavegigs = $this->getSavedGig($userid);
                if (in_array($val->id, $mysavegigs)) {
                    $data[$i]['like'] = 1;
                }
                $images = DB::table('images')->where('status', 1)->where('gig_id', $val->id)->pluck('name', 'id')->all();
                $j = 0;
                if (!empty($images)) {
                    foreach ($images as $key1 => $val1) {
                        $data[$i]['image'][$j] = $val1;
                        $j++;
                    }
                } else {
                    $data[$i]['image'][0] = '';
                }

                $i++;
            }
        }


        $this->successOutputResult('My Gigs Listng', json_encode($data));
    }

    public function delete() {
        $tokenData = $this->requestAuthentication('POST', 0);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $id = $userData['id'];
        if ($id) {
            Gig::where('id', $id)->delete();
            $this->successOutput('Gig Delete Successfully');
        }
    }

    public function buyercontact() {
        $tokenData = $this->requestAuthentication('POST', 0);
        $userid = $tokenData['user_id'];
        $allrecords = Myorder::where('seller_id', $userid)->groupBy('buyer_id')->get();

        $i = 0;
        $data = array();
        if (!empty($allrecords)) {
            foreach ($allrecords as $key => $val) {
                $data[$i]['id'] = $val->id;
                $data[$i]['user_id'] = $val->Buyer->id;
                $data[$i]['profile_image'] = $val->Buyer->profile_image;
                $data[$i]['name'] = $val->Buyer->first_name . ' ' . $val->Buyer->last_name;
                $data[$i]['contact'] = $val->Buyer->contact;
                $data[$i]['member_since'] = $val->Buyer->created_at->format('d M, Y');
                $data[$i]['contact'] = $val->Buyer->contact;
                $farray = array();
                if (isset($val->Buyer->city) && $val->Buyer->city != '') {
                    $farray[] = $val->Buyer->city;
                }
                if (isset($val->Buyer->Country->name) && $val->Buyer->Country->name != '') {
                    $farray[] = $val->Buyer->Country->name;
                }
                $data[$i]['user_from'] = implode(', ', $farray);

                $i++;
            }
        }


        $this->successOutputResult('Buyer Contact Listng', json_encode($data));
    }

    public function sellercontact() {
        $tokenData = $this->requestAuthentication('POST', 0);
        $userid = $tokenData['user_id'];
        $allrecords = Myorder::where('seller_id', $userid)->groupBy('seller_id')->get();

        $i = 0;
        $data = array();
        if (!empty($allrecords)) {
            foreach ($allrecords as $key => $val) {
                $data[$i]['id'] = $val->id;
                $data[$i]['user_id'] = $val->Seller->id;
                $data[$i]['profile_image'] = $val->Seller->profile_image;
                $data[$i]['name'] = $val->Seller->first_name . ' ' . $val->Seller->last_name;
                $data[$i]['contact'] = $val->Seller->contact;
                $data[$i]['member_since'] = $val->Seller->created_at->format('d M, Y');
                $data[$i]['contact'] = $val->Seller->contact;
                $farray = array();
                if (isset($val->Seller->city) && $val->Seller->city != '') {
                    $farray[] = $val->Seller->city;
                }
                if (isset($val->Seller->Country->name) && $val->Seller->Country->name != '') {
                    $farray[] = $val->Seller->Country->name;
                }
                $data[$i]['user_from'] = implode(', ', $farray);

                $i++;
            }
        }


        $this->successOutputResult('Seller Contact Listng', json_encode($data));
    }

    public function viewprofile() {
        $tokenData = $this->requestAuthentication('POST', 0);

//        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $userid = $userData['user_id'];
        $is_gigs = $userData['is_gigs'];

        $recordInfo = User::where('id', $userid)->first();
        $data = array();
        $data['id'] = $recordInfo->id;
        $data['first_name'] = $recordInfo->first_name;
        $data['last_name'] = $recordInfo->last_name;
        $data['email_address'] = $recordInfo->email_address;
        $data['contact'] = $recordInfo->contact;
        $data['profile_image'] = $recordInfo->profile_image;
        $data['city'] = $recordInfo->city;
        $data['country_id'] = $recordInfo->country_id;
        $data['zipcode'] = $recordInfo->zipcode;
        $data['description'] = $recordInfo->description;
        $data['from'] = $recordInfo->city;
        $data['average_rating'] = $recordInfo->average_rating;
        $data['member_since'] = date('F Y', strtotime($recordInfo->created_at));
        $langArray = array();
        if ($recordInfo->languages) {
            $dd = 0;
            foreach (json_decode($recordInfo->languages) as $key => $lang) {
                $langArray[] = $lang->lang_name;
                $dd++;
            }
        }
        $data['languages'] = implode(',', $langArray);
        $query = new Gig();
        $query = $query->where('status', 1);
        $query = $query->where('user_id', $userid);

        $gigsList = $query->orderBy('title', 'ASC')->get();
        $gigsArray = array();
        if (!empty($gigsList)) {
            $dd = 0;
            foreach ($gigsList as $gigs) {
                $gigsArray[$dd]['id'] = $gigs->id;
                $gigsArray[$dd]['title'] = $gigs->title;
                $gigsArray[$dd]['average_rating'] = $gigs->User->average_rating;
                $gigsArray[$dd]['total_review'] = $gigs->User->total_review;
                $gigsArray[$dd]['basic_price'] = $gigs->basic_price;
                $images = DB::table('images')->where('status', 1)->where('gig_id', $gigs->id)->pluck('name', 'id')->all();
                $j = 0;
                if (!empty($images)) {
                    foreach ($images as $key1 => $val1) {
                        $gigsArray[$dd]['image'][$j] = $val1;
                        $j++;
                    }
                } else {
                    $gigsArray[$dd]['image'][0] = '';
                }
                $dd++;
            }
        }
        $data['gigs'] = $gigsArray;
        $review = array();
        if (empty($is_gigs)) {
            $query = new Review();
            $query = $query->where('status', 1);
            $query = $query->where('user_id', $userid);

            $myreviews = $query->orderBy('id', 'DESC')->get();

            $r = 0;
            if (!empty($myreviews)) {
                foreach ($myreviews as $key2 => $val2) {
                    $review[$r]['as_a'] = $val2->as_a;
                    $review[$r]['name'] = $val2->Otheruser->first_name . ' ' . $val2->Otheruser->last_name;
                    $review[$r]['image'] = $val2->Otheruser->profile_image;
                    $review[$r]['rating'] = $val2->rating;
                    $review[$r]['comment'] = $val2->comment;
                    $review[$r]['post_date'] = date('F Y', strtotime($val2->created_at));
                    $r++;
                }
            }
        }
        $data['review'] = $review;
        $this->successOutputResult('View Profile', json_encode($data));
    }

    public function getnotification() {
        $tokenData = $this->requestAuthentication('GET', 1);
        $userid = $tokenData['user_id'];
        $allrecords = Notification::where('user_id', $userid)->orderBy('id', 'DESC')->limit(100)->get();

        $i = 0;
        $data = array();
        if (!empty($allrecords)) {
            foreach ($allrecords as $key => $val) {
                $data[$i]['id'] = $val->id;
                $data[$i]['from_name'] = $val->from_name;
                $data[$i]['message'] = $val->message;
                $data[$i]['url'] = $val->url;
                $data[$i]['status'] = $val->status;
                $data[$i]['created'] = $val->created_at->format('d M, Y');

                $i++;
            }
        }


        $this->successOutputResult('Notification Listng', json_encode($data));
    }

    public function getbuyercontacts() {
        $tokenData = $this->requestAuthentication('GET', 1);
        $userid = $tokenData['user_id'];
        $allrecords = Myorder::where('seller_id', $userid)->groupBy('buyer_id')->get();
//        echo '<pre>';
//        print_r($allrecords);
//        die;
        $i = 0;
        $data = array();
        if (!empty($allrecords)) {
            foreach ($allrecords as $key => $val) {
                if ($val->Buyer) {
                    $farray = array();
                    if (isset($val->Buyer->city) && $val->Buyer->city != '') {
                        $farray[] = $val->Buyer->city;
                    }
                    if (isset($val->Buyer->Country->name) && $val->Buyer->Country->name != '') {
                        $farray[] = $val->Buyer->Country->name;
                    }
                    $data[$i]['user_id'] = $val->buyer_id;
                    $data[$i]['user_image'] = $val->Buyer ? $val->Buyer->profile_image : '';
                    $data[$i]['user_name'] = $val->Buyer ? $val->Buyer->first_name . ' ' . $val->Buyer->last_name : '';
                    $data[$i]['contact'] = $val->Buyer ? $val->Buyer->contact : '';
                    $data[$i]['user_from'] = implode(', ', $farray);
                    $data[$i]['member_since'] = $val->Buyer ? date('F Y', strtotime($val->Buyer->created_at)) : '';


                    $i++;
                }
            }
        }


        $this->successOutputResult('Buyer Contact', json_encode($data));
    }

    public function getsellercontacts() {
        $tokenData = $this->requestAuthentication('GET', 1);
        $userid = $tokenData['user_id'];
        $allrecords = Myorder::where('buyer_id', $userid)->groupBy('buyer_id')->get();

        $i = 0;
        $data = array();
        if (!empty($allrecords)) {

            foreach ($allrecords as $key => $val) {
                if ($val->Seller) {
                    $farray = array();
                    if (isset($val->Seller->city) && $val->Seller->city != '') {
                        $farray[] = $val->Seller->city;
                    }
                    if (isset($val->Seller->Country->name) && $val->Seller->Country->name != '') {
                        $farray[] = $val->Seller->Country->name;
                    }
                    $data[$i]['user_id'] = $val->seller_id;
                    $data[$i]['user_image'] = $val->Seller ? $val->Seller->profile_image : '';
                    $data[$i]['user_name'] = $val->Seller ? $val->Seller->first_name . ' ' . $val->Seller->last_name : '';
                    $data[$i]['contact'] = $val->Seller ? $val->Seller->contact : '';
                    $data[$i]['user_from'] = implode(', ', $farray);
                    $data[$i]['member_since'] = $val->Seller ? date('F Y', strtotime($val->Seller->created_at)) : '';


                    $i++;
                }
            }
        }


        $this->successOutputResult('Seller Contact', json_encode($data));
    }

    public function getofferedgig() {
        $tokenData = $this->requestAuthentication('GET', 1);
        $userid = $tokenData['user_id'];
        $allrecords = Gig::where('offer_user', $userid)->orderBy('id', 'DESC')->get();

        $i = 0;
        $data = array();
        if (!empty($allrecords)) {
            foreach ($allrecords as $key => $val) {
                $userdata = $val->User;

                $data[$i]['id'] = $val->id;
                $data[$i]['title'] = $val->title;
                $data[$i]['name'] = $userdata ? $userdata->first_name . ' ' . $userdata->last_name : '';
                $data[$i]['average_rating'] = $userdata ? $userdata->average_rating : '';
                $data[$i]['total_review'] = $userdata ? $userdata->total_review : '';
                $data[$i]['basic_price'] = $val->basic_price;
                $data[$i]['youtube_image'] = $val->youtube_image;
                $images = DB::table('images')->where('status', 1)->where('gig_id', $val->id)->pluck('name', 'id')->all();
                $j = 0;
                if (!empty($images)) {
                    foreach ($images as $key1 => $val1) {
                        $data[$i]['image'][$j] = $val1;
                        $j++;
                    }
                } else {
                    $data[$i]['image'][0] = '';
                }

                $data[$i]['offer_status'] = $val->offer_status;



                $i++;
            }
        }

        $this->successOutputResult('Offered Gig', json_encode($data));
    }

    public function getmyofferedgig() {
        $tokenData = $this->requestAuthentication('GET', 1);
        $userid = $tokenData['user_id'];
        $allrecords = Gig::where('user_id', $userid)->where('type_gig', 'offer')->orderBy('id', 'DESC')->get();

        $i = 0;
        $data = array();
        if (!empty($allrecords)) {
            foreach ($allrecords as $key => $val) {
                $userdata = $val->User;

                $data[$i]['id'] = $val->id;
                $data[$i]['title'] = $val->title;
                $data[$i]['name'] = $userdata ? $userdata->first_name . ' ' . $userdata->last_name : '';
                $data[$i]['average_rating'] = $userdata ? $userdata->average_rating : '';
                $data[$i]['total_review'] = $userdata ? $userdata->total_review : '';
                $data[$i]['basic_price'] = $val->basic_price;
                $data[$i]['youtube_image'] = $val->youtube_image;
                $images = DB::table('images')->where('status', 1)->where('gig_id', $val->id)->pluck('name', 'id')->all();
                $j = 0;
                if (!empty($images)) {
                    foreach ($images as $key1 => $val1) {
                        $data[$i]['image'][$j] = $val1;
                        $j++;
                    }
                } else {
                    $data[$i]['image'][0] = '';
                }

                $data[$i]['offer_status'] = $val->offer_status;



                $i++;
            }
        }

        $this->successOutputResult('Offered Gig', json_encode($data));
    }

    public function createoffer() {
        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $gig_id = $userData['gig_id'];
        $data = array();

        $recordInfo = Gig::where('id', $gig_id)->first();

        $serialisedData['id'] = '';
        $serialisedData['basic_description'] = $userData['description'];
        $serialisedData['basic_price'] = $userData['basic_price'];
        $serialisedData['basic_delivery'] = $userData['basic_delivery'];
        $serialisedData['expiry'] = $userData['expiry'];
        $serialisedData['one_delivery'] = 1;
        $serialisedData['standard_title'] = '';
        $serialisedData['standard_description'] = '';
        $serialisedData['standard_delivery'] = '';
        $serialisedData['standard_revision'] = '';
        $serialisedData['standard_price'] = '';
        $serialisedData['premium_title'] = '';
        $serialisedData['premium_description'] = '';
        $serialisedData['premium_delivery'] = '';
        $serialisedData['premium_revision'] = '';
        $serialisedData['premium_price'] = '';
        $serialisedData['title'] = $recordInfo->title;
        $serialisedData['category_id'] = $recordInfo->category_id;
        $serialisedData['subcategory_id'] = $recordInfo->subcategory_id;
        $serialisedData['tags'] = $recordInfo->tags;
        $serialisedData['description'] = $recordInfo->description;
        $serialisedData['photo'] = $recordInfo->photo;
        $serialisedData['youtube_url'] = $recordInfo->youtube_url;
        $serialisedData['youtube_image'] = $recordInfo->youtube_image;
        $serialisedData['pdf_doc'] = $recordInfo->pdf_doc;

        $slug = $this->createSlug($recordInfo->title, 'gigs');
        $serialisedData['slug'] = $slug;
        $serialisedData['user_id'] = $userid;
        $serialisedData['type_gig'] = 'offer';
        $serialisedData['offer_user'] = $userData['offer_user_id'];

        $userInfo = User::where('id', $userData['offer_user_id'])->first();
//        echo '<pre>';print_r($serialisedData);die;
        Gig::insert($serialisedData);

        $gigId = DB::getPdo()->lastInsertId();

        if ($recordInfo->Image) {
            foreach ($recordInfo->Image as $gigimage) {
                if (isset($gigimage->name) && !empty($gigimage->name)) {
                    $path = GIG_FULL_UPLOAD_PATH . $gigimage->name;
                    if (file_exists($path) && !empty($gigimage->name)) {
                        $uploadedFileName = $gigimage->name;
                        $uploadedFileNew = $gigimage->name . '-' . time();
                        $success = \File::copy(GIG_FULL_UPLOAD_PATH . '/' . $uploadedFileName, GIG_FULL_UPLOAD_PATH . '/' . $uploadedFileNew);

                        $this->resizeImage($uploadedFileNew, GIG_FULL_UPLOAD_PATH, GIG_SMALL_UPLOAD_PATH, GIG_MW, GIG_MH);

                        $serialisedImgData = array();

                        $serialisedImgData['gig_id'] = $gigId;
                        $serialisedImgData['name'] = $uploadedFileName;
                        $serialisedImgData['status'] = 1;
                        $serialisedImgData['main'] = 0;

                        Image::insert($serialisedImgData);
                    }
                }
            }
        }

        $name = ucwords($userInfo->first_name . ' ' . $userInfo->last_name);
        $username = ucwords($recordInfo->User->first_name . ' ' . $recordInfo->User->last_name);
        $price = '$' . $userData['basic_price'];
        $duedate = date('d M Y', strtotime($userData['expiry']));
        $item = $recordInfo->title;
        $emailId = $userInfo->email_address;

        $emailTemplate = DB::table('emailtemplates')->where('id', 22)->first();
        $toRepArray = array('[!username!]', '[!name!]', '[!duedate!]', '[!item!]', '[!price!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
        $fromRepArray = array($username, $name, $duedate, $item, $price, HTTP_PATH, SITE_TITLE);
        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
        //Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

        $this->successOutputResult('Gig details saved successfully.', json_encode($data));
    }

    public function acceptreject() {
        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $gig_id = $userData['gig_id'];
        $type = $userData['type'];
        $data = array();

        $recordInfo = Gig::where('id', $gig_id)->first();

        if ($type == 1) {
            $userInfo = User::where('id', $recordInfo->offer_user)->first();
            DB::table('gigs')->where('id', $recordInfo->id)->update(array('offer_status' => 1));
            $username = ucwords($userInfo->first_name . ' ' . $userInfo->last_name);
            $name = ucwords($recordInfo->User->first_name . ' ' . $recordInfo->User->last_name);
            $price = '$' . $recordInfo->basic_price;
            $item = $recordInfo->title;
            $emailId = $recordInfo->User->email_address;

            $emailTemplate = DB::table('emailtemplates')->where('id', 23)->first();
            $toRepArray = array('[!username!]', '[!name!]', '[!item!]', '[!price!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
            $fromRepArray = array($username, $name, $item, $price, HTTP_PATH, SITE_TITLE);
            $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
            $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
            //Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
            $message = "Custom offer accepted successfully.";
        } elseif ($type == 2) {
            $userInfo = User::where('id', $recordInfo->offer_user)->first();
            DB::table('gigs')->where('id', $recordInfo->id)->update(array('offer_status' => 2));

            $username = ucwords($userInfo->first_name . ' ' . $userInfo->last_name);
            $name = ucwords($recordInfo->User->first_name . ' ' . $recordInfo->User->last_name);
            $price = '$' . $recordInfo->basic_price;
            $item = $recordInfo->title;
            $emailId = $recordInfo->User->email_address;

            $emailTemplate = DB::table('emailtemplates')->where('id', 24)->first();
            $toRepArray = array('[!username!]', '[!name!]', '[!item!]', '[!price!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
            $fromRepArray = array($username, $name, $item, $price, HTTP_PATH, SITE_TITLE);
            $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
            $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
//            Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
            $message = "Custom offer rejected successfully.";
        } elseif ($type == 3) {
            $userInfo = User::where('id', $recordInfo->offer_user)->first();
            DB::table('gigs')->where('id', $recordInfo->id)->update(array('offer_status' => 3));

            $name = ucwords($userInfo->first_name . ' ' . $userInfo->last_name);
            $username = ucwords($recordInfo->User->first_name . ' ' . $recordInfo->User->last_name);
            $price = '$' . $recordInfo->basic_price;
            $item = $recordInfo->title;
            $emailId = $userInfo->email_address;

            $emailTemplate = DB::table('emailtemplates')->where('id', 25)->first();
            $toRepArray = array('[!username!]', '[!name!]', '[!item!]', '[!price!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
            $fromRepArray = array($username, $name, $item, $price, HTTP_PATH, SITE_TITLE);
            $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
            $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
//            Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
            $message = "Custom offer rejected successfully.";
        }

        if ($type == 3) {
            $allrecords = Gig::where('user_id', $userid)->where('type_gig', 'offer')->orderBy('id', 'DESC')->get();
        }

        if ($type == 1 || $type == 2) {
            $allrecords = Gig::where('offer_user', $userid)->orderBy('id', 'DESC')->get();
        }
        $i = 0;
        $data = array();
        if (!empty($allrecords)) {
            foreach ($allrecords as $key => $val) {
                $userdata = $val->User;

                $data[$i]['id'] = $val->id;
                $data[$i]['title'] = $val->title;
                $data[$i]['name'] = $userdata ? $userdata->first_name . ' ' . $userdata->last_name : '';
                $data[$i]['average_rating'] = $userdata ? $userdata->average_rating : '';
                $data[$i]['total_review'] = $userdata ? $userdata->total_review : '';
                $data[$i]['basic_price'] = $val->basic_price;
                $data[$i]['youtube_image'] = $val->youtube_image;
                $images = DB::table('images')->where('status', 1)->where('gig_id', $val->id)->pluck('name', 'id')->all();
                $j = 0;
                if (!empty($images)) {
                    foreach ($images as $key1 => $val1) {
                        $data[$i]['image'][$j] = $val1;
                        $j++;
                    }
                } else {
                    $data[$i]['image'][0] = '';
                }

                $data[$i]['offer_status'] = $val->offer_status;

                $i++;
            }
        }


        $this->successOutputResult($message, json_encode($data));
    }

    public function getconversation() {

        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $receiver_id = $userData['user_id'];
        $data = array();

        if ($receiver_id) {
            $query = new Message();
            //$query = $query->with('Buyer');
            $query = $query->where('sender_id', $userid);
            $query->where('receiver_id', '=', $receiver_id);
            $query->orWhere('receiver_id', $userid);
            $query->where('sender_id', '=', $receiver_id);
            $messages = $query->orderBy('id', 'ASC')->get();
            if (!$messages->isEmpty()) {
                $i = 0;
                foreach ($messages as $user) {
                    if ($user->sender_id == $userid && $user->receiver_id) {
                        if ($user->Receiver) {
                            $data[$i]['user_id'] = $user->Receiver->id;
                            $data[$i]['sender_id'] = $user->Sender->id;
                            $data[$i]['name'] = $user->Receiver->first_name . ' ' . $user->Receiver->last_name;
                            $data[$i]['attachment'] = $user->attachment;
                            $data[$i]['message'] = $user->message;
                            $data[$i]['time'] = $user->created_at->format('d M, Y h:iA');
                            $data[$i]['profile_image'] = $user->Receiver->profile_image;
                            $data[$i]['user_status'] = $user->Receiver->user_status;
                            $i++;
                        }
                    }
                    if ($user->receiver_id == $userid) {
                        if ($user->Sender) {
                            $data[$i]['user_id'] = $user->Sender->id;
                            $data[$i]['sender_id'] = $user->Sender->id;
                            $data[$i]['name'] = $user->Sender->first_name . ' ' . $user->Sender->last_name;
                            $data[$i]['attachment'] = $user->attachment;
                            $data[$i]['message'] = $user->message;
                            $data[$i]['time'] = $user->created_at->format('d M, Y h:iA');
                            $data[$i]['profile_image'] = $user->Sender->profile_image;
                            $data[$i]['user_status'] = $user->Sender->user_status;
                            $i++;
                        }
                    }
                }
            }
        } else {

            $users = Message::where('sender_id', $userid)->orWhere('receiver_id', $userid)->orderBy('id', 'ASC')->get();
            $userValue = array();
            $userData = array();
            if ($users) {
                $i = 0;
                foreach ($users as $user) {
                    $userData[$i]['id'] = $user->id;
                    $userData[$i]['message'] = $user->message;
                    if ($user->sender_id == $userid && $user->receiver_id) {
                        if ($user->Receiver) {
                            $userData[$i]['user_id'] = $user->receiver_id;
                            $userData[$i]['sender_id'] = $user->Sender->id;
                            $userData[$i]['name'] = $user->Receiver->first_name . ' ' . $user->Receiver->last_name;
                            $userData[$i]['time'] = $user->created_at->format('d M, Y h:iA');
                            $userData[$i]['attachment'] = $user->attachment;
                            $userData[$i]['user_status'] = $user->Receiver->user_status;
                            $userData[$i]['profile_image'] = $user->Receiver->profile_image;
                            $usId = $user->receiver_id;
                        }
                    }
                    if ($user->receiver_id == $userid) {
                        if ($user->Sender) {
                            $userData[$i]['user_id'] = $user->sender_id;
                            $userData[$i]['sender_id'] = $user->Sender->id;
                            $userData[$i]['name'] = $user->Sender->first_name . ' ' . $user->Sender->last_name;
                            $userData[$i]['user_status'] = $user->Sender->user_status;
                            $userData[$i]['profile_image'] = $user->Sender->profile_image;
                            $userData[$i]['time'] = $user->created_at->format('d M, Y h:iA');
                            $userData[$i]['attachment'] = $user->attachment;
                            $usId = $user->sender_id;
                        }
                    }
                    $userValue[$usId] = $userData[$i];
                    $i++;
                }
            }

            $data = array_values($userValue);
        }


        $this->successOutputResult('Message Listing', json_encode($data));
    }

    public function sendmessage() {
        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $data = $_POST['jsonData'];
        $userData = json_decode($data, true);
        $receiver_id = $userData['user_id'];
        $message = $userData['message'];

        $receiverInfo = User::where('id', $receiver_id)->first();
        $emailId = $receiverInfo->email_address;
        $name = $receiverInfo->first_name . ' ' . $receiverInfo->last_name;

        $senderInfo = User::where('id', $userid)->first();
        $senderemailId = $senderInfo->email_address;
        $sendername = $senderInfo->first_name . ' ' . $senderInfo->last_name;

        if (isset($_FILES['attachment']) && $_FILES['attachment']['tmp_name'] != '') {
            $file = $_FILES['attachment'];
            $file = Input::file('attachment');
            $uploadedFileName = $this->uploadImage($file, DOCUMENT_UPLOAD_PATH);
            $attachment = $uploadedFileName;
        } else {
            $attachment = '';
        }

        $serialisedData = array();
        $serialisedData['sender_id'] = $userid;
        $serialisedData['receiver_id'] = $receiver_id;
        $serialisedData['message'] = $message;
        $serialisedData['attachment'] = $attachment;
        $serialisedData['status'] = 0;
        $serialisedData['time'] = time();
        $serialisedData['slug'] = $userid . $receiver_id . time() . rand(10, 99);
        $serialisedData = $this->serialiseFormData($serialisedData);
        Message::insert($serialisedData);

        $serialisedData = array();
        $serialisedData['from_name'] = $sendername;
        $serialisedData['user_id'] = $receiver_id;
        $serialisedData['message'] = $message;
        $serialisedData['url'] = 'messages/message/' . $senderInfo->slug;
        $serialisedData['status'] = 0;
        $serialisedData = $this->serialiseFormData($serialisedData);
        $serialisedData['slug'] = bin2hex(openssl_random_pseudo_bytes(5)) . time() . rand(10, 99);
        Notification::insert($serialisedData);

        $datetime = date('M d, Y');

        $loginUserInfo = User::where('id', $userid)->first();
        $loginuser = $loginUserInfo->first_name . ' ' . $loginUserInfo->last_name;


        $emailTemplate = DB::table('emailtemplates')->where('id', 21)->first();
        $toRepArray = array('[!username!]', '[!datetime!]', '[!name!]', '[!message!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
        $fromRepArray = array($loginuser, $datetime, $name, $message, HTTP_PATH, SITE_TITLE);
        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
//        Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
        $data = array();
        $query = new Message();
        //$query = $query->with('Buyer');
        $query = $query->where('sender_id', $userid);
        $query->where('receiver_id', '=', $receiver_id);
        $query->orWhere('receiver_id', $userid);
        $query->where('sender_id', '=', $receiver_id);
        $messages = $query->orderBy('id', 'ASC')->get();
        if (!$messages->isEmpty()) {
            $i = 0;

            foreach ($messages as $user) {
                if ($user->sender_id == $userid && $user->receiver_id) {
                    if ($user->Receiver) {
                        $data[$i]['user_id'] = $user->Receiver->id;
                        $data[$i]['sender_id'] = $user->Sender->id;
                        $data[$i]['name'] = $user->Receiver->first_name . ' ' . $user->Receiver->last_name;
                        $data[$i]['attachment'] = $user->attachment;
                        $data[$i]['message'] = $user->message;
                        $data[$i]['time'] = $user->created_at->format('d M, Y h:iA');
                        $data[$i]['profile_image'] = $user->Receiver->profile_image;
                        $data[$i]['user_status'] = $user->Receiver->user_status;
                        $i++;
                    }
                }
                if ($user->receiver_id == $userid) {
                    if ($user->Sender) {
                        $data[$i]['user_id'] = $user->Sender->id;
                        $data[$i]['sender_id'] = $user->Sender->id;
                        $data[$i]['name'] = $user->Sender->first_name . ' ' . $user->Sender->last_name;
                        $data[$i]['attachment'] = $user->attachment;
                        $data[$i]['message'] = $user->message;
                        $data[$i]['time'] = $user->created_at->format('d M, Y h:iA');
                        $data[$i]['profile_image'] = $user->Sender->profile_image;
                        $data[$i]['user_status'] = $user->Sender->user_status;
                        $i++;
                    }
                }
            }
        }

        $this->successOutputResult('Your messages sent successfully.', json_encode($data));
    }

    public function sendNonceToServer() {
        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];

        $data = $_POST['jsonData'];
        $reqData = json_decode($data, true);
        $gig_id = $reqData['gig_id'];
        $type = $reqData['package'];
        $isPayByWallet = $reqData['isPayByWallet'];

        $gigInfo = Gig::where('id', $gig_id)->first();

        $settingsInfo = DB::table('settings')->where('id', 1)->first();
        $admin_commision = $settingsInfo->admin_commission;
        $commision_admin = $settingsInfo->commission_admin;
        $extra_amount = 0;
        $amtname = $type . '_price';
        $amount = $gigInfo->$amtname;
        $revenue = $amount + $extra_amount;
        $servicefee = round(($revenue * $admin_commision / 100), 2);
        $commission_amount = round(($revenue * $commision_admin / 100), 2);
        $total_amount = $revenue + $servicefee;

        $pay_type = "";
        $transaction_id = '';
        if ($isPayByWallet) {
            $pay_type = 'Wallet';
            $transaction_id = strtoupper(bin2hex(openssl_random_pseudo_bytes(8)));

            // Deduct amount to buyer wallet
            $serialisedData = array();
            $serialisedData['user_id'] = $userid;
            $serialisedData['gig_id'] = $gigInfo->id;
            $serialisedData['amount'] = $total_amount;
            $serialisedData['revenue'] = -$total_amount;
            $serialisedData['admin_commission'] = 0;
            $serialisedData['trn_id'] = $transaction_id;
            $serialisedData['slug'] = bin2hex(openssl_random_pseudo_bytes(20));
            $serialisedData['type'] = 5;
            $serialisedData['add_minus'] = 0;
            $serialisedData['source'] = 'Pay for Gig: <b>' . $gigInfo->title . '</b>';
            $serialisedData['status'] = 1;
            Wallet::insert($serialisedData);
        } else {
            $pay_type = 'Paypal';
            $transaction_id = $reqData['nonce_id'];

            $serialisedData = array();
            $serialisedData['user_id'] = $userid;
            $serialisedData['order_slug'] = bin2hex(openssl_random_pseudo_bytes(30));
            $serialisedData['order_number'] = $transaction_id;
            $serialisedData['slug'] = bin2hex(openssl_random_pseudo_bytes(30));
            $serialisedData['status'] = 1;
            $serialisedData['amount'] = $total_amount;
            $serialisedData['gig_id'] = $gig_id;
            $serialisedData['transaction_id'] = $transaction_id;
            Payment::insert($serialisedData);
        }

        $serialisedData = array();
        $serialisedData['buyer_id'] = $userid;
        $serialisedData['gig_id'] = $gigInfo->id;
        $serialisedData['seller_id'] = $gigInfo->user_id;
        $serialisedData['package'] = $type;
        $serialisedData['amount'] = $amount;
        $serialisedData['extra_amount'] = $extra_amount;
        $serialisedData['total_amount'] = $total_amount;
        $serialisedData['revenue'] = $revenue;
        $serialisedData['admin_amount'] = $servicefee;
        $serialisedData['admin_commission'] = $commission_amount;
        $serialisedData['quantity'] = 1;
        $serialisedData['pay_type'] = $pay_type;
        if ($isPayByWallet) {
            $serialisedData['wallet_trn_id'] = $transaction_id;
        } else {
            $serialisedData['paypal_trn_id'] = $transaction_id;
        }

        $serialisedData['status'] = 1;
        $serialisedData['slug'] = bin2hex(openssl_random_pseudo_bytes(20));
        $serialisedData = $this->serialiseFormData($serialisedData);
        Myorder::insert($serialisedData);

        // Add amount to seller wallet
        $serialisedData = array();
        $serialisedData['user_id'] = $gigInfo->user_id;
        $serialisedData['gig_id'] = $gigInfo->id;
        $serialisedData['amount'] = $total_amount;
        $serialisedData['revenue'] = $revenue - $commission_amount;
        $serialisedData['admin_commission'] = $servicefee;
        $serialisedData['commission_admin'] = $commission_amount;
        $serialisedData['trn_id'] = $transaction_id;
        $serialisedData['slug'] = bin2hex(openssl_random_pseudo_bytes(20));
        $serialisedData['type'] = 6;
        $serialisedData['add_minus'] = 1;
        $serialisedData['source'] = 'From Gig: <b>' . $gigInfo->title . '</b>';
        $serialisedData['status'] = 1;
        Wallet::insert($serialisedData);
        $amountseller = CURR . $revenue;

        // Email sent to login user
        $loginUserInfo = User::where('id', $userid)->first();
        $loginuser = $loginUserInfo->first_name . ' ' . $loginUserInfo->last_name;
        $amount = CURR . $total_amount;
        $transactionId = $transaction_id;
        $datetime = date('M d, Y');
        $title = $gigInfo->title;
        $emailId = $loginUserInfo->email_address;
        $emailTemplate = DB::table('emailtemplates')->where('id', 13)->first();
        $toRepArray = array('[!username!]', '[!title!]', '[!amount!]', '[!transactionId!]', '[!paymenttype!]', '[!datetime!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
        $fromRepArray = array($loginuser, $title, $amount, $transaction_id, $pay_type, $datetime, HTTP_PATH, SITE_TITLE);
        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
        Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
        // Email sent to admin user
        $adminInfo = DB::table('admins')->where('id', 1)->first();
        $emailId = $adminInfo->email;
        $emailTemplate = DB::table('emailtemplates')->where('id', 14)->first();
        $toRepArray = array('[!username!]', '[!title!]', '[!amount!]', '[!transactionId!]', '[!paymenttype!]', '[!datetime!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
        $fromRepArray = array($loginuser, $title, $amount, $transaction_id, $pay_type, $datetime, HTTP_PATH, SITE_TITLE);
        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
        Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
        // Email sent to seller user
        $sellerInfo = User::where('id', $gigInfo->user_id)->first();
        $emailId = $sellerInfo->email_address;
        $sellername = $sellerInfo->first_name . ' ' . $sellerInfo->last_name;

        $emailTemplate = DB::table('emailtemplates')->where('id', 15)->first();
        $toRepArray = array('[!username!]', '[!title!]', '[!amount!]', '[!transactionId!]', '[!paymenttype!]', '[!datetime!]', '[!sellername!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
        $fromRepArray = array($loginuser, $title, $amountseller, $transaction_id, $pay_type, $datetime, $sellername, HTTP_PATH, SITE_TITLE);
        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
        Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

        $this->successOutput('Payment is done successfuly');
    }

    public function markasordercompleted() {
        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];

        $data = $_POST['jsonData'];
        $reqData = json_decode($data, true);
        $order_id = $reqData['order_id'];
        $allrecord = Myorder::where('id', $order_id)->first();
        Myorder::where('id', $order_id)->update(array('status' => 2));

        $gigInfo = Gig::where('id', $allrecord->gig_id)->first();
        $loginUserInfo = User::where('id', $userid)->first();
        $loginuser = $loginUserInfo->first_name . ' ' . $loginUserInfo->last_name;
        $selleruser = $allrecord->Seller->first_name . ' ' . $allrecord->Seller->last_name;
        $title = $gigInfo->title;

        $emailId = $allrecord->Seller->email_address;
        $emailTemplate = DB::table('emailtemplates')->where('id', 16)->first();
        $toRepArray = array('[!username!]', '[!title!]', '[!loginuser!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
        $fromRepArray = array($selleruser, $title, $loginuser, HTTP_PATH, SITE_TITLE);
        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
        Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

//        echo '<pre>';print_r($emailId);die;

        $this->successOutput('Gig marked as completed successfully.');
    }

    public function withdrawrequest() {
        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];

        $data = $_POST['jsonData'];
        $reqData = json_decode($data, true);
        $amount = $reqData['amount'];

        $siteSettings = DB::table('settings')->where('id', 1)->first();
        $amountArray = $this->getWallerAmount($userid);
        if ($amount < $siteSettings->minimum_withdraw_amount) {
            $this->errorOutputResult('You can not send withdraw amount request less than minimum withdraw amount.');
        } elseif ($amount > $amountArray['availableforwithdraw']) {
            $this->errorOutputResult('You can not send withdraw request more than available balance.');
        } else {
            $isOldRequest = Wallet::where(['user_id' => $userid, 'type' => 2])->first();
            if ($isOldRequest) {
                $this->errorOutputResult('You can send only one request withdraw at a time.');
            } else {
                $serialisedData = array();
                $serialisedData['user_id'] = $userid;
                $serialisedData['service_id'] = 0;
                $serialisedData['amount'] = $amount;
                $serialisedData['revenue'] = -$amount;
                $serialisedData['admin_commission'] = 0;
                $serialisedData['slug'] = bin2hex(openssl_random_pseudo_bytes(20));
                $serialisedData['type'] = 2;
                $serialisedData['add_minus'] = 0;
                $serialisedData['source'] = 'Withdraw Amount</b>';
                Wallet::insert($serialisedData);
                $this->successOutput('Your withdraw amount request sent successfully and waiting for admin approval.');
            }
        }
    }

}

?>