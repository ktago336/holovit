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
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Notification;

use App\Http\Requests\LoginRequest;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller {

	use AuthenticatesUsers;
	
	//protected $redirectTo = '/merchant/dashboard';

    public function __construct() {  
		$this->middleware('guest')->except('logout');
    }

    public function login() { 
        $pageTitle = 'Login';       
        return view('merchant.user.login', ['title' => $pageTitle]);
    }
	
	public function loginSave(LoginRequest $request)
    { 	
        if(Auth::guard('merchant')->validate(['email_address' => $request->username, 'password' => $request->password])) {
            $user = Auth::guard('merchant')->getLastAttempted();   
            if ($user->activation_status == 0) {
                return back()->withErrors('Please wait for the admin approval of your account!');
            } else if ($user->status == 0) {
                return back()->withErrors('Your account might have been temporarily disabled. Please contact us for more details.');
            }
                        
                        
            Auth::guard('merchant')->login($user);
            //return redirect()->route('merchant.user.myaccount');
            return Redirect::to('merchant/user/myaccount');

        } else {
            return back()->withErrors('Invalide email or password');
        }
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
                    Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
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

    

    public function myaccount() {
        $pageTitle = 'MY Account';
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
                return Redirect::to('/merchant/user/myaccount')->withErrors($validator)->withInput();
            } else {
//                echo '<pre>';
//                print_r($input);
                if ($input['old_password'] && !password_verify($input['old_password'], $recordInfo->password)) {
                   return Redirect::to('/merchant/user/myaccount')->withErrors('Your current password did not matched, please try with correct password.')->withInput();  
                }
                if ($input['password'] && password_verify($input['password'], $recordInfo->password)) {
                   return Redirect::to('/merchant/user/myaccount')->withErrors('You cannot put your old password as new password, please another password.')->withInput();  
                }
                $updata = array('first_name'=>$input['first_name'],'last_name'=>$input['last_name'],'email_address'=>$input['email_address']);
                
                if ($input['password']) {
                    $new_password = $this->encpassword($input['password']);
                   
                    $updata['password'] = $new_password;
                } 
//                echo '<pre>';
//                print_r($updata);
//                die;
                User::where('id', $recordInfo->id)->update($updata);
                Session::flash('success_message', "Your profile has been updated successfully.");
                return Redirect::to('/merchant/user/myaccount');
            }
        }

        return view('users.myaccount', ['title' => $pageTitle, 'recordInfo' => $recordInfo, 'myaccountAct' => 'active']);
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

    public function mypayments() {
        $pageTitle = 'My Requests';
        $query = new Payment();
        $query = $query->sortable();
        $user_id = Session::get('user_id');

        $payments = $query->orderBy('id', 'DESC')->where('user_id', $user_id)->paginate(10);

        $recordInfo = User::where('id', Session::get('user_id'))->first();
        //print_r($appointments);exit;
        return view('users.mypayments', ['title' => $pageTitle, 'payments' => $payments, 'mypayments' => 'active', 'recordInfo' => $recordInfo]);
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
            return Redirect::to('myaccount');
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
//        $recordInfo = User::where('slug', $slug)->first();
//        if (!$recordInfo) {
//            return Redirect::to('dashboard');
//        }
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

        return view('users.orderDetail', ['title' => $pageTitle]);
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

}

?>
