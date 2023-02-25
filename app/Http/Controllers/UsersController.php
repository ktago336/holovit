<?php

namespace App\Http\Controllers;

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
use App\Models\Admin;
use App\Models\Deal;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Wallet;

class UsersController extends Controller {

    public function __construct() {
        $this->middleware('userlogedin', ['only' => ['login', 'forgotPassword', 'resetPassword', 'register']]);
        $this->middleware('is_userlogin', ['except' => ['logout', 'login', 'forgotPassword', 'resetPassword', 'redirectToGoogle', 'handleGoogleCallback', 'redirectToFacebook', 'handleFacebookCallback', 'redirectToLinkedin', 'handleLinkedinCallback', 'register', 'sociallogin', 'emailConfirmation', 'publicprofile', 'myprofile','orderDetail','wishlist']]);
    }

    public function login() {
        $pageTitle = 'Login';
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'username' => 'required',
                'password' => 'required'
            );
            //print_r($input);exit();
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/login')->withErrors($validator)->withInput(Input::except('password'));
            } else {

                $userInfo = User::where('email_address', $input['username'])->orWhere('contact', $input['username'])->first();
                if (!empty($userInfo)) {
                    if (password_verify($input['password'], $userInfo->password)) {
                        if ($userInfo->status == 1 && $userInfo->activation_status == 1) {
                            if (isset($input['user_remember']) && $input['user_remember'] == '1') {
                                Cookie::queue('user_username', $userInfo->email_address, time() + 60 * 60 * 24 * 7, "/");
                                Cookie::queue('user_username', $userInfo->contact, time() + 60 * 60 * 24 * 7, "/");
                                Cookie::queue('user_password', $input['password'], time() + 60 * 60 * 24 * 7, "/");
                                Cookie::queue('user_remember', '1', time() + 60 * 60 * 24 * 100, "/");
                            } else {
                                Cookie::queue('user_username', '', time() + 60 * 60 * 24 * 7, "/");
                                Cookie::queue('user_password', '', time() + 60 * 60 * 24 * 7, "/");
                                Cookie::queue('user_remember', '', time() + 60 * 60 * 24 * 7, "/");
                            }
                            Session::put('user_id', $userInfo->id);
                            Session::put('user_name', ucwords($userInfo->first_name . ' ' . $userInfo->last_name));
                            Session::put('email_address', $userInfo->email_address);
                            Session::put('contact', $userInfo->contact);

                            return Redirect::to('users/myaccount');
                        } else if ($userInfo->activation_status == 0) {
                            $error = 'You need to activate your account before login.';
                        } else if ($userInfo->status == 0 && $userInfo->activation_status == 1) {
                            $error = 'Your account might have been temporarily disabled. Please contact us for more details.';
                        }
                    } else {
                        $error = 'Invalid email address or password.';
                    }
                } else {
                    $error = 'Invalid email address or password.';
                }
                return Redirect::to('/login')->withErrors($error)->withInput(Input::except('password'));
            }
        }
        return view('users.login', ['title' => $pageTitle]);
    }

    public function checknotifications() {
        $notifications = Notification::where(['user_id' => Session::get('user_id'), 'status' => 1])->select(['from_name', 'slug', 'created_at', 'message'])->orderBy('id', 'DESC')->limit(10)->get();
        if (count($notifications) > 0) {
            $data = array();
            $i = 0;
            foreach ($notifications as $notification) {
                $data[$i]['url'] = $notification->slug;
                $data[$i]['timeago'] = $notification->created_at->diffForHumans();
                $data[$i]['from_name'] = $notification->from_name;
                $data[$i]['message'] = $notification->message;
                $i++;
            }
            echo json_encode($data);
        } else {
            return '1';
        }
    }

    public function forgotPassword() {
        //exit;
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
                $userInfo = User::where('email_address', $input['email_address'])->first();
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
                    
                   //echo'sent'; exit;
                    Session::flash('success_message', "A link to reset your password was sent to your email address.");
                    return Redirect::to('/login');
                } else {
                    $error = 'Your email is not registered with ' . SITE_TITLE . '. Please enter correct email or register on ' . SITE_TITLE;
                }
                return Redirect::to('/forgot-password')->withErrors($error);
            }
        }
        return view('users.forgotPassword', ['title' => $pageTitle]);
    }

    public function resetPassword($ukey = null) {
        $pageTitle = 'Reset Password';
        $userInfo = User::where('unique_key', $ukey)->first();
        if ($userInfo && $userInfo->forget_password_status == 1) {
            $input = Input::all();
            if (!empty($input)) {
                $rules = array(
                    'password' => 'required|min:8',
                    'confirm_password' => 'required|same:password',
                );
                $validator = Validator::make($input, $rules);
                if ($validator->fails()) {
                    return Redirect::to('/reset-password/' . $ukey)->withErrors($validator);
                } elseif (password_verify($input['password'], $userInfo->password)) {
                    return Redirect::to('/reset-password/' . $ukey)->withErrors('You cannot put your old password as new password, please another password.');
                } else {
                    $new_password = $this->encpassword($input['password']);
                    User::where('id', $userInfo->id)->update(array('forget_password_status' => 0, 'password' => $new_password));
                    Session::flash('success_message', "Your Password has been updated successfully. Please Login");
                    return Redirect::to('/login');
                }
            }
            return view('users.resetPassword', ['title' => $pageTitle]);
        } else {
            Session::flash('error_message', "You have already use this link!");
            return Redirect::to('/login');
        }
    }

    public function register() {
        
      //  print_r('hie');exit;
        
        $pageTitle = 'Register';
        $input = Input::all();
        if (!empty($input)) {
//           echo '<pre>'; print_r($input);exit;
            $rules = array(
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:20',
                'email_address' => 'required|email|unique:users',
                'password' => 'required|min:8',
//                'contact'=>'required|unique:users',
//                'address'=>'required',
                'confirm_password' => 'required|same:password',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/register')->withErrors($validator)->withInput(Input::except('password'));
            } else {
                unset($input['g-recaptcha-response']);
                unset($input['terms']);
                $input['first_name'] = ucfirst(trim($input['first_name']));
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['first_name'] . ' ' . $input['last_name'], 'users');
                $serialisedData['user_type'] = 'customer';
                $serialisedData['status'] = 0;
                $serialisedData['password'] = $this->encpassword($input['password']);
                $uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
                $serialisedData['unique_key'] = $uniqueKey;
//                echo '<pre>';print_r($serialisedData);
                User::insert($serialisedData);

                $link = HTTP_PATH . "/email-confirmation/" . $uniqueKey;
                $name = $input['first_name'];
                $emailId = $input['email_address'];
                $new_password = $input['password'];

                $emailTemplate = DB::table('emailtemplates')->where('id', 3)->first();
                $toRepArray = array('[!email!]', '[!username!]', '[!password!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($emailId, $name, $new_password, $link, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));

                Session::flash('success_message', "We have sent you an account activation link by email. Please check your spam folder if you do not receive the email within the next few minutes.");
                return Redirect::to('/login');
            }
        }
        return view('users.register', ['title' => $pageTitle]);
    }

    public function emailConfirmation($ukey = null) {
        $userInfo = User::where('unique_key', $ukey)->first();
        if ($userInfo) {
            if ($userInfo->activation_status == 1) {
                Session::flash('error_message', "You have already use this link!");
            } else {
                User::where('id', $userInfo->id)->update(array('activation_status' => 1, 'status' => 1));
                Session::flash('success_message', "Your Account has been verified Successfully! Please Login");
            }
        } else {
            Session::flash('error_message', "Invalide URL!");
        }
        return Redirect::to('/login');
    }

    public function logout() {
        Session::forget('user_id');
        Session::forget('user_name');
        Session::forget('email_address');
        Session::save();
        Session::flash('success_message', "Logout successfully.");
        return Redirect::to('/login');
    }

    public function dashboard() {
        return Redirect::to('/users/myaccount');
        $pageTitle = 'User Dashboard';
        $recordInfo = User::where('id', Session::get('user_id'))->first();

        return view('users.dashboard', ['title' => $pageTitle, 'recordInfo' => $recordInfo, 'dashboardAct' => 'active']);
    }

    public function myaccount() {
        $pageTitle = 'My Account';
        $recordInfo = User::where('id', Session::get('user_id'))->first();

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:20',
                'email_address' => 'required|email|unique:users,email_address,' . $recordInfo->id,
//                'password' => 'min:8',
//                'confirm_password' => 'same:password',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/users/myaccount')->withErrors($validator)->withInput();
            } else {
//                echo '<pre>';
//                print_r($input);
                if ($input['old_password'] && !password_verify($input['old_password'], $recordInfo->password)) {
                   return Redirect::to('/users/myaccount')->withErrors('Your current password did not matched, please try with correct password.')->withInput();  
                }
                if ($input['password'] && password_verify($input['password'], $recordInfo->password)) {
                   return Redirect::to('/users/myaccount')->withErrors('You cannot put your old password as new password, please another password.')->withInput();  
                }
                $updata = array('first_name'=>$input['first_name'],'last_name'=>$input['last_name'],'email_address'=>$input['email_address'],'contact'=>$input['contact']);
                
                if ($input['password']) {
                    $new_password = $this->encpassword($input['password']);
                   
                    $updata['password'] = $new_password;
                } 
//                echo '<pre>';
//                print_r($updata);
//                die;
                User::where('id', $recordInfo->id)->update($updata);
                Session::flash('success_message', "Your profile has been updated successfully.");
                return Redirect::to('/users/myaccount');
            }
        }

        return view('users.myaccount', ['title' => $pageTitle, 'recordInfo' => $recordInfo, 'myaccountAct' => 'active']);
    }
    
    public function editprofile() {
        $pageTitle = 'Edit Profile';
        $recordInfo = User::where('id', Session::get('user_id'))->first();

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:20',
                'email_address' => 'required|email|unique:users,email_address,' . $recordInfo->id,
//                'password' => 'min:8',
//                'confirm_password' => 'same:password',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/users/editprofile')->withErrors($validator)->withInput();
            } else {
//                echo '<pre>';
//                print_r($input);
                if ($input['old_password'] && !password_verify($input['old_password'], $recordInfo->password)) {
                   return Redirect::to('/users/myaccount')->withErrors('Your current password did not matched, please try with correct password.')->withInput();  
                }
                if ($input['password'] && password_verify($input['password'], $recordInfo->password)) {
                   return Redirect::to('/users/myaccount')->withErrors('You cannot put your old password as new password, please another password.')->withInput();  
                }
                $updata = array('first_name'=>$input['first_name'],'last_name'=>$input['last_name'],'email_address'=>$input['email_address'],'contact'=>$input['contact']);
                
                if ($input['password']) {
                    $new_password = $this->encpassword($input['password']);
                   
                    $updata['password'] = $new_password;
                } 
//                echo '<pre>';
//                print_r($updata);
//                die;
                User::where('id', $recordInfo->id)->update($updata);
                Session::flash('success_message', "Your profile has been updated successfully.");
                return Redirect::to('/users/myaccount');
            }
        }

        return view('users.editprofile', ['title' => $pageTitle, 'recordInfo' => $recordInfo, 'myaccountAct' => 'active']);
    }

    public function myprofile() {
        $pageTitle = 'MY Account';
        $recordInfo = User::where('id', Session::get('user_id'))->first();

        return view('users.myprofile', ['title' => $pageTitle, 'recordInfo' => $recordInfo, 'myaccountAct' => 'active']);
    }

    public function uploadprofileimage() {
        $input = Input::all();
        //print_r($input);exit;
        if (Input::hasFile('profile_image')) {
            $file = Input::file('profile_image');
            $uploadedFileName = $this->uploadImage($file, PROFILE_FULL_UPLOAD_PATH);
            $this->resizeImage($uploadedFileName, PROFILE_FULL_UPLOAD_PATH, PROFILE_SMALL_UPLOAD_PATH, PROFILE_MW, PROFILE_MH);
            $recordInfo = User::select('profile_image')->where('id', Session::get('user_id'))->first();
            @unlink(PROFILE_FULL_UPLOAD_PATH . $recordInfo->profile_image);
            @unlink(PROFILE_SMALL_UPLOAD_PATH . $recordInfo->profile_image);
            User::where('id', Session::get('user_id'))->update(array('profile_image' => $uploadedFileName));
            echo $uploadedFileName;
        }
    }

    public function updatedata(Request $request) {
        if ($request->has('statusnameid')) {
            User::where('id', Session::get('user_id'))->update(array('user_status' => $request->get('statusnameid')));
        } elseif ($request->has('contact')) {
            User::where('id', Session::get('user_id'))->update(array('contact' => $request->input('contact')));
        } elseif ($request->has('countrynameid')) {
            $countryInfo = DB::table('countries')->where('name', $request->get('countrynameid'))->first();
            User::where('id', Session::get('user_id'))->update(array('country_id' => $countryInfo->id, 'city' => $request->get('city_id'), 'zipcode' => $request->get('zipcode_id')));
        } else if ($request->has('description')) {
            User::where('id', Session::get('user_id'))->update(array('description' => $request->input('description')));
        } else if ($request->get('lang_name')) {
            $lang_name = $request->get('lang_name');
            $lang_level = $request->get('lang_level');
            $lang_old = $request->get('lang_old');
            $recordInfo = User::where('id', Session::get('user_id'))->first();
            $languagesArray = array();
            if ($recordInfo->languages) {
                $languagesArray = json_decode($recordInfo->languages, TRUE);
                if ($lang_old) {
                    $languagesArray[$lang_old]['lang_name'] = $lang_name;
                    $languagesArray[$lang_old]['lang_level'] = $lang_level;
                } else {
                    $lang_key = str_replace(' ', '_', strtolower($lang_name));
                    if (array_key_exists($lang_key, $languagesArray)) {
                        $lang_key = $lang_key . '_' . rand(0, 99);
                    }
                    $languagesArray[$lang_key]['lang_name'] = $lang_name;
                    $languagesArray[$lang_key]['lang_level'] = $lang_level;
                }
            } else {
                $lang_key = str_replace(' ', '_', strtolower($lang_name));
                $languagesArray[$lang_key]['lang_name'] = $lang_name;
                $languagesArray[$lang_key]['lang_level'] = $lang_level;
            }
            User::where('id', Session::get('user_id'))->update(array('languages' => json_encode($languagesArray)));
        } else if ($request->get('deletekey')) {
            $recordInfo = User::where('id', Session::get('user_id'))->first();
            $languagesArray = json_decode($recordInfo->languages, TRUE);
            unset($languagesArray[$request->get('deletekey')]);
            User::where('id', Session::get('user_id'))->update(array('languages' => json_encode($languagesArray)));
        } else if ($request->get('skill_ids')) {
            $recordInfo = User::where('id', Session::get('user_id'))->first();
            $skillsArray = array();
            if ($recordInfo->skills) {
                $skillsArray = explode(',', $recordInfo->skills);
            }
            $skillsArray[] = $request->get('skill_ids');
            User::where('id', Session::get('user_id'))->update(array('skills' => implode(',', $skillsArray)));
        } else if ($request->get('deleteskill')) {
            $recordInfo = User::where('id', Session::get('user_id'))->first();
            $skillsArray = explode(',', $recordInfo->skills);
            $assArray = array_combine($skillsArray, $skillsArray);
            unset($assArray[$request->get('deleteskill')]);
            User::where('id', Session::get('user_id'))->update(array('skills' => implode(',', $assArray)));
        } else if ($request->get('university_name')) {
            $name = $request->get('name');
            $university_name = $request->get('university_name');
            $qual_name = $request->get('qual_name');
            $stream_name = $request->get('stream_name');
            $year = $request->get('year');
            $edu_old = $request->get('edu_old');
            $recordInfo = User::where('id', Session::get('user_id'))->first();
            $educationsArray = array();
            if ($recordInfo->educations) {
                $educationsArray = json_decode($recordInfo->educations, TRUE);
                if ($edu_old) {
                    $educationsArray[$edu_old]['name'] = $name;
                    $educationsArray[$edu_old]['university_name'] = $university_name;
                    $educationsArray[$edu_old]['qual_name'] = $qual_name;
                    $educationsArray[$edu_old]['stream_name'] = $stream_name;
                    $educationsArray[$edu_old]['year'] = $year;
                } else {
                    $edu_key = str_replace(' ', '_', strtolower($stream_name));
                    if (array_key_exists($edu_key, $educationsArray)) {
                        $edu_key = $edu_key . '_' . rand(0, 99);
                    }
                    $educationsArray[$edu_key]['name'] = $name;
                    $educationsArray[$edu_key]['university_name'] = $university_name;
                    $educationsArray[$edu_key]['qual_name'] = $qual_name;
                    $educationsArray[$edu_key]['stream_name'] = $stream_name;
                    $educationsArray[$edu_key]['year'] = $year;
                }
            } else {
                $edu_key = str_replace(' ', '_', strtolower($stream_name));
                $educationsArray[$edu_key]['name'] = $name;
                $educationsArray[$edu_key]['university_name'] = $university_name;
                $educationsArray[$edu_key]['qual_name'] = $qual_name;
                $educationsArray[$edu_key]['stream_name'] = $stream_name;
                $educationsArray[$edu_key]['year'] = $year;
            }
            User::where('id', Session::get('user_id'))->update(array('educations' => json_encode($educationsArray)));
        } else if ($request->get('deleteedu')) {
            $recordInfo = User::where('id', Session::get('user_id'))->first();
            $educationsArray = json_decode($recordInfo->educations, TRUE);
            unset($educationsArray[$request->get('deleteedu')]);
            User::where('id', Session::get('user_id'))->update(array('educations' => json_encode($educationsArray)));
        } else if ($request->get('certificate_name')) {
            $certificate_name = $request->get('certificate_name');
            $certificate_from = $request->get('certificate_from');
            $year = $request->get('year');
            $cert_old = $request->get('cert_old');
            $recordInfo = User::where('id', Session::get('user_id'))->first();
            $ecertificationArray = array();
            if ($recordInfo->certifications) {
                $ecertificationArray = json_decode($recordInfo->certifications, TRUE);
                if ($cert_old) {
                    $ecertificationArray[$cert_old]['certificate_name'] = $certificate_name;
                    $ecertificationArray[$cert_old]['certificate_from'] = $certificate_from;
                    $ecertificationArray[$cert_old]['year'] = $year;
                } else {
                    $cert_key = str_replace(' ', '_', strtolower($certificate_name));
                    if (array_key_exists($cert_key, $ecertificationArray)) {
                        $cert_key = $cert_key . '_' . rand(0, 99);
                    }
                    $ecertificationArray[$cert_key]['certificate_name'] = $certificate_name;
                    $ecertificationArray[$cert_key]['certificate_from'] = $certificate_from;
                    $ecertificationArray[$cert_key]['year'] = $year;
                }
            } else {
                $cert_key = str_replace(' ', '_', strtolower($certificate_name));
                $ecertificationArray[$cert_key]['certificate_name'] = $certificate_name;
                $ecertificationArray[$cert_key]['certificate_from'] = $certificate_from;
                $ecertificationArray[$cert_key]['year'] = $year;
            }
            User::where('id', Session::get('user_id'))->update(array('certifications' => json_encode($ecertificationArray)));
        } else if ($request->get('deletecert')) {
            $recordInfo = User::where('id', Session::get('user_id'))->first();
            $educationsArray = json_decode($recordInfo->certifications, TRUE);
            unset($educationsArray[$request->get('deletecert')]);
            User::where('id', Session::get('user_id'))->update(array('certifications' => json_encode($educationsArray)));
        }
    }

    public function settings() {
        $pageTitle = 'Manage Settings';
        $recordInfo = User::where('id', Session::get('user_id'))->first();
        return view('users.settings', ['title' => $pageTitle, 'recordInfo' => $recordInfo, 'settingsAct' => 'active']);
    }

    public function updatesettings(Request $request) {
        $recordInfo = User::where('id', Session::get('user_id'))->first();
        //print_r($recordInfo);exit;
        if ($request->has('old_password')) {
            $old_password = $request->get('old_password');
            $newpassword = $request->get('newpassword');
            if (!password_verify($old_password, $recordInfo->password)) {
                echo 'Current password is not correct.';
            } else if ($old_password == $newpassword) {
                echo 'You can not change new password same as current password';
                exit;
            } else {
                $new_password = $this->encpassword($newpassword);
                User::where('id', Session::get('user_id'))->update(array('password' => $new_password));
                echo '1';
            }
        }
        if ($request->has('first_name')) {
            $first_name = $request->get('first_name');
            $last_name = $request->get('last_name');
            $contact = $request->get('contact');
            $address = $request->get('address');
            User::where('id', Session::get('user_id'))->update(array('first_name' => $first_name, 'last_name' => $last_name, 'contact' => $contact, 'address' => $address));
            echo '1';
        }
    }

    public function myrequests() {
        $pageTitle = 'MY Requests';
        $query = new Appointment();
        $query = $query->sortable();
        $user_id = Session::get('user_id');

        $appointments = $query->orderBy('id', 'DESC')->where('user_id', $user_id)->paginate(10);

        $recordInfo = User::where('id', Session::get('user_id'))->first();
        //print_r($appointments);exit;
        return view('users.myrequests', ['title' => $pageTitle, 'appointments' => $appointments, 'myrequests' => 'active', 'recordInfo' => $recordInfo]);
    }

    

    public function buyercontacts() {
        $pageTitle = 'View Buyer Contacts';
        $allrecords = Myorder::where('seller_id', Session::get('user_id'))->groupBy('buyer_id')->get();
        return view('users.buyercontacts', ['title' => $pageTitle, 'allrecords' => $allrecords]);
    }

    public function sellercontacts() {
        $pageTitle = 'View Seller Contacts';
        $allrecords = Myorder::where('buyer_id', Session::get('user_id'))->groupBy('seller_id')->get();
        return view('users.sellercontacts', ['title' => $pageTitle, 'allrecords' => $allrecords]);
    }

    public function publicprofile($slug) {
        $recordInfo = User::where('slug', $slug)->first();
        if (!$recordInfo) {
            return Redirect::to('dashboard');
        }
        $pageTitle = $recordInfo->first_name . ' ' . $recordInfo->last_name . ' Public Profile';

        $skillsList = DB::table('skills')->where('status', 1)->orderBy('name', 'ASC')->pluck('name', 'id')->all();
        $countryLists = DB::table('countries')->where('status', 1)->orderBy('name', 'ASC')->pluck('name', 'name')->all();
        $qualificationsLists = DB::table('qualifications')->where('status', 1)->orderBy('name', 'ASC')->pluck('name', 'name')->all();
        $mygigs = Gig::where(['status' => 1, 'user_id' => $recordInfo->id])->orderBy('id', 'DESC')->limit(9)->get();
        $myreviews = Review::where(['status' => 1, 'user_id' => $recordInfo->id])->orderBy('id', 'DESC')->limit(10)->get();
        $mysavegigs = $this->getSavedGigs();

        $date1 = date('Y-m-d', strtotime("-30 days"));
        $sellingOrders = DB::table('myorders')
                ->select('seller_id', 'id', DB::raw('sum(total_amount) as total_sum'))
                ->where('seller_id', '=', Session::get('user_id'))
                ->where('created_at', '>=', $date1)
                ->get();

        $topRatedInfo = DB::table('reviews')->where(['otheruser_id' => Session::get('user_id')])->where('rating', '>', 4)->pluck(DB::raw('count(*) as total'), 'id')->all();

        return view('users.publicprofile', ['title' => $pageTitle, 'recordInfo' => $recordInfo, 'topRatedInfo' => $topRatedInfo, 'sellingOrders' => $sellingOrders, 'skillsList' => $skillsList, 'countryLists' => $countryLists, 'qualificationsLists' => $qualificationsLists, 'mygigs' => $mygigs, 'myreviews' => $myreviews, 'mysavegigs' => $mysavegigs]);
    }
    
    public function orderDetail($slug) {
		$recordInfo = Order::where('slug', $slug)->first();
		
        if (!$recordInfo) {
           return Redirect::to('dashboard');
        }
		//$barc = $this->barcode( BARCODE_FULL_UPLOAD_PATH . $recordInfo->voucher_number.'.png', $recordInfo->voucher_number);
		$dealidarr = explode(',',$recordInfo->deals_id);
		$deals = DB::table('deals')->whereIn('id', $dealidarr)->orderBy('id', 'ASC')->pluck('deal_name', 'id')->all();
		//$deals = json_decode(json_encode($deals),true);
		//print_r($deals);exit;
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

        return view('users.orderDetail', ['title' => $pageTitle,'single_order'=>$recordInfo,'deals'=>$deals, 'myorders' => 'active']);
    }
    public function wishlist() {
//        $recordInfo = User::where('slug', $slug)->first();
//        if (!$recordInfo) {
//            return Redirect::to('dashboard');
//        }
        $pageTitle = 'My Wishlist';
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

        return view('users.wishlist', ['title' => $pageTitle]);
    }
	
	public function myOrders(Request $request) {
        $pageTitle = 'My Orders';
        $query = new Order();
        $query = $query->sortable();
        $user_id = Session::get('user_id');
        $recordInfo = User::where(['id'=>$user_id])->first();

        $orders = $query->orderBy('created_at', 'DESC')->where(['user_id'=>$user_id,'status'=>1])->paginate(10);
        //print_r($appointments);exit;
		if ($request->ajax()) {
            return view('elements.users.myorders', ['orders' => $orders]);
        }
        return view('users.myorders', ['title' => $pageTitle, 'orders' => $orders, 'myorders' => 'active', 'recordInfo' => $recordInfo]);
    }
	public function mypayments(Request $request) {
        $pageTitle = 'My Requests';
        $query = new Payment();
        $query = $query->sortable();
        $user_id = Session::get('user_id');

        $payments = $query->orderBy('id', 'DESC')->where('user_id', $user_id)->paginate(10);

        $recordInfo = User::where('id', Session::get('user_id'))->first();
        if ($request->ajax()) {
            return view('elements.users.mypayments', ['payments' => $payments]);
        }
        return view('users.mypayments', ['title' => $pageTitle, 'payments' => $payments, 'mypayments' => 'active', 'recordInfo' => $recordInfo]);
    }
	
	public function mywallet(Request $request) { 
	    $pageTitle = 'My Wallet';
        $activetab = 'mywallet';
        $query = new Wallet();
		$user_id = Session::get('user_id');
		$userInfo = User::where('id', $user_id)->first();
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
		$user_id = Session::get('user_id');
        $wallet = $query->where(['user_id'=>$user_id, 'status'=>1])->where('source', '!=' ,'Pay via Paypal')->orderBy('id', 'DESC')->paginate(10);

        if ($request->ajax()) {
            return view('elements.users.mywallet',['merchant'=>$merchant]);
        }
        return view('users.mywallet', ['userInfo' => $userInfo, 'title' => $pageTitle, 'mywallet' => 'active','wallet'=>$wallet]);
    }

    public function addmoney() { //echo "sdfd"; exit;
        $pageTitle = 'Add Balance';
        $activetab = 'mywallet';
		$user_id = Session::get('user_id');
		$adminInfo = Admin::where('id', 1)->first();
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                // 'name' => 'required',
                'description' => 'required',
                //'images' => 'required',
            );
            $productid = array();
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('merchant/deals/add')->withErrors($validator)->withInput();
            } else {

                $imagesArray = array();
                $files = Input::file('images');
                if ($files && count($files) > 0) {
                    foreach ($files as $file) {
                        if ($file) {
                            $uploadedFileName = $this->uploadImage($file, DEAL_FULL_UPLOAD_PATH);
                            //$this->resizeImage($uploadedFileName, DEAL_FULL_UPLOAD_PATH, DEAL_SMALL_UPLOAD_PATH, DEAL_MW, DEAL_MH);
                            $imagesArray[] = $uploadedFileName;
                        }
                    }
                    $imagesArray = implode(',', $imagesArray);
                } else {
                    $imagesArray = '';
                }
                if (!isset($input['type'])) {
                    $input['type'] = 1;
                }
                $receiptType = $input['type'];
                switch ($receiptType) {
                    case 1:
                        $productid='';
                    case 0:
                        if (isset($input['product_id']) && $input['product_id'] != '') {
                            $productid = implode(',', array_filter($input['product_id']));
                        }
                }
				
			if(isset($input['amenitie_id']) && $input['amenitie_id'] != ''){
                $amenitieid = implode(',',$input['amenitie_id']);
           } 
//print_r($amenitieid); exit;
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['deal_name'], 'deals');
                //$serialisedData['merchant_id'] = Session::get('user_id');
                $serialisedData['images'] = $imagesArray;
                $serialisedData['product_id'] = $productid;
				$serialisedData['amenitie_id'] = $amenitieid;
                $serialisedData['status'] = 1;
                Deal::insert($serialisedData);
                Session::flash('success_message', "Deal details saved successfully.");
                return Redirect::to('merchant/deals');
            }
        }
        return view('users.addmoney', ['title' => $pageTitle, 'mywallet' => 'active', 'adminInfo'=>$adminInfo]);
    }
	
	public function payviapaypal(Request $request) {
        $pageTitle = 'Payment With PayPal';
		$user_id = Session::get('user_id');
		$adminInfo = Admin::where('id', 1)->first();
        if($adminInfo->paypal_url){
            $paypal_url = $adminInfo->paypal_url;
            $paypal_email = $adminInfo->paypal_email;
        }else{
			$paypal_url = PAYPAL_URL;
            $paypal_email = $adminInfo->paypal_email?$adminInfo->paypal_email:PAYPAL_EMAIL;
        }
		
		$input = Input::all();
		$amount = 0;
        if (!empty($input)) {
            $rules = array(
                 'amount' => 'required',
                //'description' => 'required',
                //'images' => 'required',
            );
            $productid = array();
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('users/addmoney')->withErrors($validator)->withInput();
            } else {
				if($input['amount'] > 0){
					$amount = $input['amount'];
				}
				
				$paymentNumber = 'PAYMENT-DEPOSIT' . time();
				
				
				
				$total_amount = $amount;
				
				$product_name = SITE_TITLE;

				$currencyID = urlencode('USD');
				$paymentType = urlencode('Sale');    // or 'Sale' //Authorization

				$totalAmt = urlencode($amount);
				$currency = urlencode('USD');

				$settingsInfo = DB::table('settings')->where('id', 1)->first();
				$paypal_url = PAYPALURL;
				if($settingsInfo->payment_mode == 1){
					$paypal_url = PAYPALURLLIVE;
				}
				$paypal_email = $settingsInfo->paypal_email_address;

		
		
				
				$admin_commission = $amount*$adminInfo->deposit_commission/100+$adminInfo->deposit_fixed_commission;
				$total_amount = $amount+$admin_commission;
				//echo $total_amount;exit;
				$serialisedData = array();
				$serialisedData['total_amount'] = $total_amount;
				$serialisedData['amount'] = $amount;
				$serialisedData['admin_commission'] = $admin_commission;
				$serialisedData['description'] = $input['description'];
				$serialisedData['add_minus'] = 1;
				$serialisedData['type'] = 0;
				$serialisedData['source'] = 'Deposit via PayPal';
				$serialisedData['user_id'] = $user_id;
				$serialisedData['status'] = 0;
				$slug = $this->createSlug('deposit'.$user_id.time(), 'wallets');
				$serialisedData['slug'] = $slug;
				$serialisedData['trn_id'] = $paymentNumber;
				Wallet::insert($serialisedData);

			}
		} else {
            $this->redirect('/homes/error');
        }
		$product_name = SITE_TITLE.' Wallet';

		$currencyID = urlencode('USD');
		$paymentType = urlencode('Sale');    // or 'Sale' //Authorization

		$totalAmt = urlencode($total_amount);
		$currency = urlencode('USD');

		$settingsInfo = DB::table('settings')->where('id', 1)->first();
		$paypal_url = PAYPALURL;
		if($settingsInfo->payment_mode == 1){
			$paypal_url = PAYPALURLLIVE;
		}
		$paypal_email = $settingsInfo->paypal_email_address;
		return view('users.payviapaypal', ['paypal_url'=>$paypal_url,'paypal_email'=>$paypal_email,'title' => $pageTitle, 'amount' => $totalAmt, 'currency' => $currency, 'item_number' => $slug, 'product_name' => $product_name, 'success_url' => HTTP_PATH . '/users/paypalsuccess/' . $slug, 'cancel_url' => HTTP_PATH . '/users/paypalcancel/' . $slug]);
		
	}
	
	
    
    public function paypalcancel(Request $request, $slug = null) {
        Session::flash('error_message', "Sorry, your payment could not be completed, please try again!");
        
        $recordInfo = Wallet::where('slug', $slug)->first();
        
		if($recordInfo){
			//set status as cancelled or delete order
			//Wallet::where('id', $recordInfo->id)->update(array('order_status'=>'2','status'=>'1'));
			Wallet::where('id', $recordInfo->id)->delete();
		}else{
			Session::flash('error_message', "something went wrong!");
			return Redirect::to('/login');
		}
        return Redirect('/users/mywallet');
    }

    public function paypalsuccess(Request $request, $slug = null) {
		//echo "<pre>"; print_r($$slug);
		//print_r($_REQUEST);
		//exit;
        $pageTitle = 'Payment With PayPal';
        
		if($slug){
			$recordInfo = Wallet::where('slug', $slug)->first();
		}elseif($_REQUEST['item_number']){
			$recordInfo = Wallet::where('slug', $_REQUEST['item_number'])->first();
		}
		if($recordInfo->status == 0){
			//print_r($recordInfo);exit;
			$total_amount = $recordInfo->total_amount;
			$wallet_amount = $recordInfo->amount;
			$wallet_id = $recordInfo->id;
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
				$serialisedData = array();
				$serialisedData['user_id'] = $recordInfo->user_id;
				$serialisedData['wallet_id'] = $recordInfo->id;
				$slug = $this->createSlug(bin2hex(openssl_random_pseudo_bytes(30)), 'payments');
				$serialisedData['slug'] = $slug;
				$serialisedData['status'] = 1;
				$serialisedData['amount'] = $total_amount;
				$serialisedData['transaction_id'] = $wallet_trn_id;
				Payment::insert($serialisedData);
				
				$userDetail = User::where('id', $recordInfo->user_id)->first();
				$updated_balance = $userDetail->wallet_balance+$wallet_amount;
				
				User::where('id', $recordInfo->user_id)->update(array('wallet_balance'=>$updated_balance)); 
				Wallet::where('id', $wallet_id)->update(array('status'=>'1','trn_id'=>$wallet_trn_id));                

				
				Session::flash('success_message', "Money added successfully.");
				return Redirect('/users/mywallet');
			}

		}else{
			Session::flash('success_message', "Unauthorized access!");
			return Redirect('/users/mywallet');
		}
    }



}

?>
