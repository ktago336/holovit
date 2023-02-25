<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Cookie;
use Session;
use Redirect;
use Illuminate\Support\Facades\Input;
use Validator;
use DB;
use IsAdmin;
use Mail;
use App\Mail\SendMailable;
use App\Models\Admin;
use App\Models\User;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Block;
use App\Models\Appointment;
use DateTime;
use DateInterval;

class AdminsController extends Controller {

    public function __construct() {
        $this->middleware('adminlogedin', ['only' => ['login', 'forgotPassword']]);
        $this->middleware('is_adminlogin', ['except' => ['logout', 'login', 'forgotPassword']]);
    }

    public function custom(Request $request) {
        $admin_id = Session::get('adminid');

        $todate = $request->get('todate');
        $todate1 = date("Y-m-d 00:00:00", strtotime($todate));
        $todate2 = date("Y-m-d 23:59:59", strtotime($todate));
// $todate = [$todate1, $todate2];

        $fromdate = $request->get('fromdate');
        $fromdate1 = date("Y-m-d 00:00:00", strtotime($fromdate));
        $fromdate2 = date("Y-m-d 23:59:59", strtotime($fromdate));
// $fromdate = [$fromdate1, $fromdate2];
//$label = $request->get('service_ids');
        $query1 = new Appointment();
        $query2 = new Appointment();
        $staff_id = $request->get('staff_id');

        $data = [];
//if($staff_id =='staff'){
        if ($fromdate != '' && $todate != '') {
            $query1 = $query1->where('booking_date_time', '>=', $fromdate1)->where('booking_date_time', '<=', $todate2);
//print_r('blank data');
        } elseif ($fromdate != '') {
            $query1 = $query1->where('booking_date_time', '>=', $fromdate1);
        } elseif ($todate != '') {
            $query1 = $query1->where('booking_date_time', '<=', $todate2);
        }
        if ($admin_id != 1) {
            $staffdata = $query1->select('staff_id', 'service_ids', DB::raw('count(*) as total'))->where('staff_id', '<>', '1')->where('service_ids', '<>', '0')
                            ->where('staff_id', $admin_id)
                            ->groupBy('staff_id')->get();
        } else {
            $staffdata = $query1->select('staff_id', 'service_ids', DB::raw('count(*) as total'))->where('staff_id', '<>', '1')->where('service_ids', '<>', '0')
                            ->groupBy('staff_id')->get();
        }
//}
//if($staff_id =='service'){
        if ($fromdate != '' && $todate != '') {
            $query2 = $query2->where('booking_date_time', '>=', $fromdate1)->where('booking_date_time', '<=', $todate2);
//print_r('blank data');
        } elseif ($fromdate != '') {
            $query2 = $query2->where('booking_date_time', '>=', $fromdate1);
        } elseif ($todate != '') {
            $query2 = $query2->where('booking_date_time', '<=', $todate2);
        }
        $query2->select('service_ids', DB::raw('count(*) as total'))->where('staff_id', '<>', '1')->where('service_ids', '<>', '0')
                ->groupBy('service_ids')->get();
        if ($admin_id != 1) {
            $servicedata = $query2->select('service_ids', 'staff_id', DB::raw('count(*) as total'))->where('staff_id', '<>', '1')->where('service_ids', '<>', '0')
                            ->where('staff_id', $admin_id)
                            ->groupBy('service_ids')->get();
        } else {
            $servicedata = $query2->select('service_ids', 'staff_id', DB::raw('count(*) as total'))->where('staff_id', '<>', '1')->where('service_ids', '<>', '0')
                            ->groupBy('service_ids')->get();
        }
//}
        foreach ($staffdata as $k => $v) {
//print_r($k);exit;
            if (isset($v->Admin)) {
                $staffdata[$k]['staff_name'] = $v->Admin->first_name;
                $staffdata[$k]['profile_image'] = $v->Admin->profile_image;
            } else {
                $staffdata[$k]['staff_name'] = "N/A";
                $staffdata[$k]['profile_image'] = "public/img/noimage.png";
            }
//print_r($staffdata);
// if(isset($k->Admin))
// print_r(json_decode($k->Admin->first_name));
// if(isset($k->Admin))
//     $staffdata['staff_name']=(json_decode($k->Admin))->first_name;
        }
//exit;
        foreach ($servicedata as $k => $v) {
            if (isset($v->Service)) {
                $servicedata[$k]['name'] = $v->Service->name;
                $servicedata[$k]['service_image'] = $v->Service->service_image;
            } else {
                $servicedata[$k]['name'] = "N/A";
                $servicedata[$k]['service_image'] = "public/img/noimage.png";
            }
        }
//exit;
//print_r($servicedata);exit;
        $data['staff'] = $staffdata;
        $data['service'] = $servicedata;

        return json_encode($data);
    }

    public function login() {
        $pageTitle = 'Admin Login';
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'username' => 'required',
                'password' => 'required',
                'g-recaptcha-response' => 'required'
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/login')->withErrors($validator)->withInput(Input::except('password'));
            } else {
                $adminInfo = DB::table('admins')->where('username', $input['username'])->first();
                if (!empty($adminInfo)) {

                    if (password_verify($input['password'], $adminInfo->password)) {
                        if ($adminInfo->status == 0) {
                            $error = 'Your account got temporary disabled.';
                        } else {
                            if (isset($input['remember']) && $input['remember'] == '1') {
                                Cookie::queue('admin_username', $adminInfo->username, time() + 60 * 60 * 24 * 7, "/");
                                Cookie::queue('admin_password', $input['password'], time() + 60 * 60 * 24 * 7, "/");
                                Cookie::queue('admin_remember', '1', time() + 60 * 60 * 24 * 100, "/");
                            } else {
                                Cookie::queue('admin_username', '', time() + 60 * 60 * 24 * 7, "/");
                                Cookie::queue('admin_password', '', time() + 60 * 60 * 24 * 7, "/");
                                Cookie::queue('admin_remember', '', time() + 60 * 60 * 24 * 7, "/");
                            }
                            Session::put('adminid', $adminInfo->id);
                            Session::put('admin_username', $adminInfo->username);
                            return Redirect::to('admin/admins/dashboard');
                        }
                    } else {
                        $error = 'Invalid username or password.';
                    }
                } else {
                    $error = 'Invalid username or password.';
                }
                return Redirect::to('/admin/login')->withErrors($error)->withInput(Input::except('password'));
            }
        }
        return view('admin.admins.login', ['title' => $pageTitle]);
    }

    public function forgotPassword() {
        $pageTitle = 'Admin Forgot Password';
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'email' => 'required|email'
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/admins/forgot-password')->withErrors($validator);
            } else {
                $adminInfo = DB::table('admins')->where('email', $input['email'])->first();
                if (!empty($adminInfo)) {
                    $plainPassword = $this->getRandString(8);
                    $new_password = $this->encpassword($plainPassword);
                    DB::table('admins')->where('id', $adminInfo->id)->update(array('password' => $new_password));

                    $username = $adminInfo->username;
                    $emailId = $adminInfo->email;
                    $emailTemplate = DB::table('emailtemplates')->where('id', 1)->first();
                    $toRepArray = array('[!email!]', '[!username!]', '[!password!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                    $fromRepArray = array($emailId, $username, $plainPassword, HTTP_PATH, SITE_TITLE);
                    $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                    $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                    Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

                    Session::flash('success_message', "A new password has been sent to your email address.");
                    return Redirect::to('admin/admins/login');
                } else {
                    $error = 'Invalid email address, please enter correct email address.';
                }
                return Redirect::to('/admin/admins/forgot-password')->withErrors($error);
            }
        }
        return view('admin.admins.forgotPassword', ['title' => $pageTitle]);
    }

    public function logout() {
        session_start();
        session_destroy();
        Session::forget('adminid');
        Session::save();
        Session::flash('success_message', "Logout successfully.");
        return Redirect::to('admin/admins/login');
    }

    public function dashboard() {
        $pageTitle = 'Admin Dashboard'; 
        $dadhboardData = array();        
        $dadhboardData['users_count'] = DB::table('users')->count();       
        $dadhboardData['merchants_count'] = DB::table('merchants')->count();     
		$dadhboardData['deal_count'] = DB::table('deals')->count();     		
        $dadhboardData['payment_count'] = DB::table('payments')->count();       
        $dadhboardData['order_count'] = DB::table('orders')->count();       
        $dadhboardData['categoryies_count'] = DB::table('categories')->where('parent_id', 0)->count();       
        $dadhboardData['withdrawals_count'] = DB::table('withdrawals')->count();       
          
        //$dadhboardData['services_count'] = DB::table('services')->count();       
        return view('admin.admins.dashboard', ['title'=>$pageTitle, 'actdashboard'=>1, 'dadhboardData'=>$dadhboardData]);
    }

	public function userchart($daycount=2) {
        switch ($daycount) {
            case 0:
                $daycount = 1;
                $today = date('Y-m-d') . ' 23:59:00';
                $lastday = date('Y-m-d') . ' 00:00:00';
                break;
            case 1:
                $daycount = 1;
                $today = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d')))) . ' 23:59:00';
                $lastday = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d')))) . ' 00:00:00';
                break;
            case 2:
                $daycount = 31;
                $today = date('Y-m-d') . ' 23:59:00';
                $lastday = date('Y-m-d', strtotime("-30 day", strtotime(date('Y-m-d')))) . ' 00:00:00';
                break;
            case 3:
                $daycount = 365;
                $today = date('Y-m-d') . ' 23:59:00';
                $lastday = date('Y-m-d', strtotime("-365 day", strtotime(date('Y-m-d')))) . ' 00:00:00';
                break;
            case 4:
                $daycount = 7;
                $today = date('Y-m-d') . ' 23:59:00';
                $lastday = date('Y-m-d', strtotime("-7 day", strtotime(date('Y-m-d')))) . ' 00:00:00';
                break;
        }

        $catArray = array();
        $CTempArray = array();

        if ($daycount == 365) {
            $countUserArray = DB::table('users')
                ->select('created_at as date', DB::raw('count(*) as count'))
                ->where('created_at','<=', $today)         
                ->where('created_at','>=', $lastday)         
                ->groupBy(DB::raw('Month(created_at)'))                    
                ->get()
                ;
          
            foreach ($countUserArray as $row) {
                $CTempArray[date("Y-m", strtotime($row->date))] = $row->count;
            }
            ksort($CTempArray);
            $finalArray = array();
            $catArray = array();
            $strtotime = strtotime($lastday);
            for ($i = 0; $i <= 12; $i++) {
                $value = 0;
                $date = date('Y-m', $strtotime);
                if (array_key_exists($date, $CTempArray)) {
                    $value = $CTempArray[$date];
                }
                $finalArray[] = $value;
                $catArray[] = "'" . date('M', $strtotime) . "'";
                $strtotime = strtotime("+1month", $strtotime);
            }
            
            
        } else {
            $countUserArray = DB::table('users')
                ->select('created_at as date', DB::raw('count(*) as count'))
                ->where('created_at','<=', $today)         
                ->where('created_at','>=', $lastday)         
                ->groupBy(DB::raw('Day(created_at)'))                                 
                ->get()
                ;
            
            foreach ($countUserArray as $row) {
                $CTempArray[date("Y-m-d", strtotime($row->date))] = $row->count;
            }
            ksort($CTempArray);            
            $finalArray = array();
            $strtotime = strtotime($lastday);
            for ($i = 0; $i < $daycount; $i++) {
                $value = 0;
                $date = date('Y-m-d', $strtotime);
                if (array_key_exists($date, $CTempArray)) {
                    $value = $CTempArray[$date];
                }
                $datea = date('Y, m-1, d', $strtotime);
                $finalArray[] = "Date.UTC($datea), " . $value;
                $strtotime = $strtotime + 24 * 3600;
            }
        }        
        return view('elements.admin.chart', ['dayCount'=>$daycount, 'finalArray'=>"[" . implode('],[', $finalArray) . "]", 'catArray'=>implode(', ', $catArray)]);
    }
    
    
    public function userchart1($daycount = 0) {
        $admin_id = Session::get('adminid');
        switch ($daycount) {
            case 0:
                $daycount = 1;
                $today = date('Y-m-d') . ' 23:59:00';
                $lastday = date('Y-m-d') . ' 00:00:00';
                break;
            case 1:
                $daycount = 1;
                $today = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d')))) . ' 23:59:00';
                $lastday = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d')))) . ' 00:00:00';
                break;
            case 2:
                $daycount = 31;
                $today = date('Y-m-d') . ' 23:59:00';
                $lastday = date('Y-m-d', strtotime("-30 day", strtotime(date('Y-m-d')))) . ' 00:00:00';
                break;
            case 3:
                $daycount = 365;
                $today = date('Y-m-d') . ' 23:59:00';
                $lastday = date('Y-m-d', strtotime("-365 day", strtotime(date('Y-m-d')))) . ' 00:00:00';
                break;
            case 4:
                $daycount = 7;
                $today = date('Y-m-d') . ' 23:59:00';
                $lastday = date('Y-m-d', strtotime("-7 day", strtotime(date('Y-m-d')))) . ' 00:00:00';
                break;
            case 5:
                $daycount = 1;
                $today = date('Y-m-d', strtotime("+1 day", strtotime(date('Y-m-d')))) . ' 23:59:00';
                $lastday = date('Y-m-d', strtotime("+1 day", strtotime(date('Y-m-d')))) . ' 00:00:00';
                break;
        }

        $catArray = array();
        $CTempArray = array();

        if ($daycount == 365) {
            if ($admin_id != 1) {
                $countUserArray = DB::table('appointments')
                        ->select('booking_date_time as date', DB::raw('count(*) as count'))
                        ->where('booking_date_time', '<=', $today)
                        ->where('booking_date_time', '>=', $lastday)
                        ->where('staff_id', $admin_id)
                        ->groupBy(DB::raw('Month(booking_date_time)'))
                        ->get();
            } else {
                $countUserArray = DB::table('appointments')
                        ->select('booking_date_time as date', DB::raw('count(*) as count'))
                        ->where('booking_date_time', '<=', $today)
                        ->where('booking_date_time', '>=', $lastday)
                        ->groupBy(DB::raw('Month(booking_date_time)'))
                        ->get();
            }

            foreach ($countUserArray as $row) {
                $CTempArray[date("Y-m", strtotime($row->date))] = $row->count;
            }
            ksort($CTempArray);
            $finalArray = array();
            $catArray = array();
            $strtotime = strtotime($lastday);
            for ($i = 0; $i <= 12; $i++) {
                $value = 0;
                $date = date('Y-m', $strtotime);
                if (array_key_exists($date, $CTempArray)) {
                    $value = $CTempArray[$date];
                }
                $finalArray[] = $value;
                $catArray[] = "'" . date('M', $strtotime) . "'";
                $strtotime = strtotime("+1month", $strtotime);
            }
        } else {
            if ($admin_id != 1) {
                $countUserArray = DB::table('appointments')
                        ->select('booking_date_time as date', DB::raw('count(*) as count'))
                        ->where('booking_date_time', '<=', $today)
                        ->where('booking_date_time', '>=', $lastday)
                        ->where('staff_id', $admin_id)
                        ->groupBy(DB::raw('Day(booking_date_time)'))
                        ->get();
            } else {
                $countUserArray = DB::table('appointments')
                        ->select('booking_date_time as date', DB::raw('count(*) as count'))
                        ->where('booking_date_time', '<=', $today)
                        ->where('booking_date_time', '>=', $lastday)
                        ->groupBy(DB::raw('Day(booking_date_time)'))
                        ->get();
            }

            foreach ($countUserArray as $row) {
                $CTempArray[date("Y-m-d", strtotime($row->date))] = $row->count;
            }
            ksort($CTempArray);
            $finalArray = array();
            $strtotime = strtotime($lastday);
            for ($i = 0; $i < $daycount; $i++) {
                $value = 0;
                $date = date('Y-m-d', $strtotime);
                if (array_key_exists($date, $CTempArray)) {
                    $value = $CTempArray[$date];
                }
                $datea = date('Y, m-1, d', $strtotime);
                $finalArray[] = "Date.UTC($datea), " . $value;
                $strtotime = $strtotime + 24 * 3600;
            }
        }
        return view('elements.admin.chart', ['dayCount' => $daycount, 'finalArray' => "[" . implode('],[', $finalArray) . "]", 'catArray' => implode(', ', $catArray)]);
    }

    public function staffchart1($daycount = 2) {
        switch ($daycount) {
            case 0:
                $daycount = 1;
                $today = date('Y-m-d') . ' 23:59:00';
                $lastday = date('Y-m-d') . ' 00:00:00';
                break;
            case 1:
                $daycount = 1;
                $today = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d')))) . ' 23:59:00';
                $lastday = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d')))) . ' 00:00:00';
                break;
            case 2:
                $daycount = 31;
                $today = date('Y-m-d') . ' 23:59:00';
                $lastday = date('Y-m-d', strtotime("-30 day", strtotime(date('Y-m-d')))) . ' 00:00:00';
                break;
            case 3:
                $daycount = 365;
                $today = date('Y-m-d') . ' 23:59:00';
                $lastday = date('Y-m-d', strtotime("-365 day", strtotime(date('Y-m-d')))) . ' 00:00:00';
                break;
            case 4:
                $daycount = 7;
                $today = date('Y-m-d') . ' 23:59:00';
                $lastday = date('Y-m-d', strtotime("-7 day", strtotime(date('Y-m-d')))) . ' 00:00:00';
                break;
        }

        $catArray = array();
        $CTempArray = array();

        if ($daycount == 365) {
            $countUserArray = DB::table('appointments')
                    ->select('booking_date_time as date', DB::raw('count(*) as count'))
                    ->where('booking_date_time', '<=', $today)
                    ->where('booking_date_time', '>=', $lastday)
                    ->groupBy(DB::raw('Month(booking_date_time)'))
                    ->get()
            ;

            foreach ($countUserArray as $row) {
                $CTempArray[date("Y-m", strtotime($row->date))] = $row->count;
            }
            ksort($CTempArray);
            $finalArray = array();
            $catArray = array();
            $strtotime = strtotime($lastday);
            for ($i = 0; $i <= 12; $i++) {
                $value = 0;
                $date = date('Y-m', $strtotime);
                if (array_key_exists($date, $CTempArray)) {
                    $value = $CTempArray[$date];
                }
                $finalArray[] = $value;
                $catArray[] = "'" . date('M', $strtotime) . "'";
                $strtotime = strtotime("+1month", $strtotime);
            }
        } else {
            $countUserArray = DB::table('appointments')
                    ->select('booking_date_time as date', DB::raw('count(*) as count'))
                    ->where('booking_date_time', '<=', $today)
                    ->where('booking_date_time', '>=', $lastday)
                    ->groupBy(DB::raw('Day(booking_date_time)'))
                    ->get()
            ;

            foreach ($countUserArray as $row) {
                $CTempArray[date("Y-m-d", strtotime($row->date))] = $row->count;
            }
            ksort($CTempArray);
            $finalArray = array();
            $strtotime = strtotime($lastday);
            for ($i = 0; $i < $daycount; $i++) {
                $value = 0;
                $date = date('Y-m-d', $strtotime);
                if (array_key_exists($date, $CTempArray)) {
                    $value = $CTempArray[$date];
                }
                $datea = date('Y, m-1, d', $strtotime);
                $finalArray[] = "Date.UTC($datea), " . $value;
                $strtotime = $strtotime + 24 * 3600;
            }
        }
        return view('elements.admin.staffchart', ['dayCount' => $daycount, 'finalArray' => "[" . implode('],[', $finalArray) . "]", 'catArray' => implode(', ', $catArray)]);
    }

    public function changeUsername() {
        $pageTitle = 'Change Username';
        $activetab = 'actchangeusername';
        $adminInfo = DB::table('admins')->select('admins.username', 'admins.id')->where('id', Session::get('adminid'))->first();
        $input = Input::all();
        if (!empty($input)) {
            $error = '';
            $rules = array(
                'old_username' => 'required|different:new_username',
                'new_username' => 'required',
                'confirm_username' => 'required|same:new_username'
            );
            $customMessages = ['different' => 'You can not change new username same as current username'];
            $validator = Validator::make($input, $rules, $customMessages);
            if ($validator->fails()) {
                return view('admin.admins.changeUsername', ['title' => $pageTitle, $activetab => 1, 'adminInfo' => $adminInfo])->withErrors($validator);
            } else {
                DB::table('admins')->where('id', $adminInfo->id)->update(array('username' => $input['new_username']));
                Session::put('admin_username', $input['new_username']);
                Session::flash('success_message', "Admin username updated successfully.");
                return Redirect::to('admin/admins/change-username');
            }
        }
        return view('admin.admins.changeUsername', ['title' => $pageTitle, $activetab => 1, 'adminInfo' => $adminInfo]);
    }

    public function changePassword() {
        $pageTitle = 'Change Password';
        $activetab = 'actchangepassword';
        $input = Input::all();
        if (!empty($input)) {
            $error = '';
            $rules = array(
                'old_password' => 'required|different:new_password',
                'new_password' => 'required',
                'confirm_password' => 'required|same:new_password',
            );
            $customMessages = ['different' => 'You can not change new password same as current password.'];
            $validator = Validator::make($input, $rules, $customMessages);
            if ($validator->fails()) {
                return view('admin.admins.changePassword', ['title' => $pageTitle, $activetab => 1])->withErrors($validator);
            } else {
                $adminInfo = DB::table('admins')->select('admins.password', 'admins.id')->where('id', Session::get('adminid'))->first();
                if (!password_verify($input['old_password'], $adminInfo->password)) {
                    $error = 'Current password is not correct.';
                    return view('admin.admins.changePassword', ['title' => $pageTitle, $activetab => 1])->withErrors($error);
                } else {
                    $new_password = $this->encpassword($input['new_password']);
                    DB::table('admins')->where('id', $adminInfo->id)->update(array('password' => $new_password));
                    Session::flash('success_message', "Admin password updated successfully.");
                    return Redirect::to('admin/admins/change-password');
                }
            }
        }
        return view('admin.admins.changePassword', ['title' => $pageTitle, $activetab => 1]);
    }

    public function changeEmail() {
        $pageTitle = 'Change Email';
        $activetab = 'actchangeemail';
        $adminInfo = DB::table('admins')->select('admins.email', 'admins.id')->where('id', Session::get('adminid'))->first();
        $input = Input::all();
        if (!empty($input)) {
            $error = '';
            $rules = array(
                'old_email' => 'required|email|different:new_email',
                'new_email' => 'required|email',
                'confirm_email' => 'required|email|same:new_email'
            );
            $customMessages = ['different' => 'You can not change new email same as current email'];
            $validator = Validator::make($input, $rules, $customMessages);
            if ($validator->fails()) {
                return view('admin.admins.changeEmail', ['title' => $pageTitle, $activetab => 1, 'adminInfo' => $adminInfo])->withErrors($validator);
            } else {
                DB::table('admins')->where('id', $adminInfo->id)->update(array('email' => $input['new_email']));
                Session::flash('success_message', "Admin email updated successfully.");
                return Redirect::to('admin/admins/change-email');
            }
        }
        return view('admin.admins.changeEmail', ['title' => $pageTitle, $activetab => 1, 'adminInfo' => $adminInfo]);
    }

    public function siteSettings() {
        $pageTitle = 'Manage Site Settings';
        $activetab = 'actsitesetting';
        $admin_id = Session::get('adminid');
        global $default_buffer_time;
        global $notification_type;
        global $week_days;
        global $time_array;
        $recordInfo = DB::table('settings')->where('id', 1)->first();
//  echo "<pre>";      print_r($recordInfo);exit;
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'site_title' => 'required',
                'company_name' => 'required',
                'contact_number' => 'required',
                'contact_email' => 'required|email',
                'address' => 'required',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/admins/site-settings')->withErrors($validator)->withInput();
            } else {
                if (Input::hasFile('home_logo')) {
                    $file_logo = Input::file('home_logo');
                    $uploadedFileName = $this->uploadImageWithSameName($file_logo, LOGO_IMAGE_UPLOAD_PATH);
                    $input['home_logo'] = $uploadedFileName;
                } else {
                    unset($input['home_logo']);
                }

                if (Input::hasFile('logo')) {
                    $file = Input::file('logo');
                    $uploadedFileName = $this->uploadImageWithSameName($file, LOGO_IMAGE_UPLOAD_PATH);
                    $input['logo'] = $uploadedFileName;
                } else {
                    unset($input['logo']);
                }

                if (Input::hasFile('favicon')) {
                    $file = Input::file('favicon');
                    $uploadedFileName = $this->uploadImageWithSameName($file, LOGO_IMAGE_UPLOAD_PATH);
                    $input['favicon'] = $uploadedFileName;
                } else {
                    unset($input['favicon']);
                }
                /*$input = array_diff_key($input, array_flip(array_keys($week_days)));
                if (!isset($input['whatsapp_notification'])) {
                    $input['whatsapp_notification'] = 0;
                }
                if (!isset($input['sms_notification'])) {
                    $input['sms_notification'] = 0;
                }
                if (!isset($input['email_notification'])) {
                    $input['email_notification'] = 0;
                }
                
                if (!isset($input['fullname_required'])) {
                    $input['fullname_required'] = 0;
                } else {
                    $input['fullname_required'] = 1;
                }
                if (!isset($input['email_required'])) {
                    $input['email_required'] = 0;
                } else {
                    $input['email_required'] = 1;
                }

                if (!isset($input['phone_required'])) {
                    $input['phone_required'] = 0;
                    $input['verifiy_phone'] = 0;
                } else if ((isset($input['phone_required'])) && (!isset($input['verifiy_phone']) )) {
                    $input['phone_required'] = 1;
                    $input['verifiy_phone'] = 0;
                } else if ((isset($input['phone_required'])) && (isset($input['verifiy_phone']) )) {
                    $input['phone_required'] = 1;
                    $input['verifiy_phone'] = 1;
                }*/


                $serialisedData = $this->serialiseFormData($input, 1); //send 1 for edit
                /*if (isset($serialisedData['service_selection_mandatory'])) {
                    $serialisedData['service_selection_mandatory'] = 1;
                } else {
                    $serialisedData['service_selection_mandatory'] = 0;
                }
                if (isset($serialisedData['staff_selection_mandatory'])) {
                    $serialisedData['staff_selection_mandatory'] = 1;
                } else {
                    $serialisedData['staff_selection_mandatory'] = 0;
                }*/
//print_r($serialisedData);exit;
                DB::table('settings')->where('id', $recordInfo->id)->update($serialisedData);
                Session::flash('success_message', "Site settings updated successfully.");

                return Redirect::to('admin/admins/site-settings');
            }
        }
        
            return view('admin.admins.siteSettings', ['title' => $pageTitle, $activetab => 1, 'recordInfo' => $recordInfo, 'default_buffer_time' => $default_buffer_time, 'notification_type' => $notification_type, 'week_days' => $week_days, 'time_array' => $time_array]);
      
    }

    public static function getAdminRoles($adminId = null) {
        $resultUser = Admin::where('id', $adminId)->first();
        if ($adminId == 1) {
            global $staffsubroles;
            $roles = array_keys($staffsubroles);
        } else {
            $roles = explode(',', $resultUser->role_ids);
        }
        return $roles;
    }

    public static function getAdminRolesSub($adminId = null) {
        $resultUser = Admin::where('id', $adminId)->first();
        $subroles = json_decode($resultUser->sub_role_ids, true);
        return $subroles;
    }

    public function staff(Request $request) {
        $pageTitle = 'Manage Merchant';
        $activetab = 'actstaffs';
        $query = new User();
        $query = $query->sortable();

        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                User::whereIn('id', $idList)->update(array('status' => 1, 'activation_status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                User::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                User::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            }
        }
        $query = $query->where(['user_type' => 'merchant']);

        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword) {
                $q->where('first_name', 'like', '%' . $keyword . '%')
                        ->orWhere('last_name', 'like', '%' . $keyword . '%')
                        ->orWhere('username', 'like', '%' . $keyword . '%')
                        ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        }

        $staffs = $query->orderBy('id', 'DESC')->paginate(20);


        if ($request->ajax()) {
            return view('elements.admin.admins.staff', ['allrecords' => $staffs]);
        }
        return view('admin.admins.staff', ['title' => $pageTitle, $activetab => 1, 'allrecords' => $staffs]);
    }

    public function addstaff() {
        $pageTitle = 'Add Merchant';
        $activetab = 'actstaffs';

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:30',
                //'contact' => 'required|min:8',
                //'address' => 'required',
                'email' => 'required|email|unique:admins',
                'username' => 'required|unique:admins',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
                'profile_image' => 'required|mimes:jpeg,png,jpg',
            );
            $customMessages = [
                    //  'contact.required' => 'The contact number field is required field.',
            ];
            $validator = Validator::make($input, $rules, $customMessages);
            if ($validator->fails()) {
                //                return Redirect::to('/admin/admins/addstaff')->withErrors($validator)->with('data', $input);;
                return Redirect::to('/admin/admins/addstaff')->withErrors($validator)->withInput();
            } else {
                if (Input::hasFile('profile_image')) {
                    $file = Input::file('profile_image');
                    $uploadedFileName = $this->uploadImage($file, PROFILE_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, PROFILE_FULL_UPLOAD_PATH, PROFILE_SMALL_UPLOAD_PATH, PROFILE_MW, PROFILE_MH);
                    $input['profile_image'] = $uploadedFileName;
                } else {
                    unset($input['profile_image']);
                }
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['first_name'] . ' ' . $input['last_name'], 'admins');
                $serialisedData['status'] = 1;
                $serialisedData['type'] = 'merchant';
                //                $serialisedData['activation_status'] =  1;
                $serialisedData['password'] = $this->encpassword($input['password']);


                $recordInfo = DB::table('settings')->where('id', 1)->first();

                unset($input['service_ids'][0]);
                //print_r($input['service_ids']);exit;
                if (!empty($input['service_ids'])) {
                    $service_ids = implode(',', $input['service_ids']);
                    $serialisedData['service_ids'] = $service_ids;
                }
                //print_r($serialisedData);exit;
                User::insert($serialisedData);

                $name = $input['first_name'] . ' ' . $input['last_name'];
                $emailId = $input['email'];
                $new_password = $input['password'];

                //                $emailTemplate = DB::table('emailtemplates')->where('id', 2)->first();
                //                $toRepArray = array('[!email!]', '[!name!]', '[!username!]', '[!password!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                //                $fromRepArray = array($emailId, $name, $name, $new_password, HTTP_PATH, SITE_TITLE);
                //                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                //                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                //                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));

                Session::flash('success_message', "Merchant details saved successfully.");
                return Redirect::to('admin/admins/staff');
            }
        }

        return view('admin.admins.addstaff', ['title' => $pageTitle, $activetab => 1]);
    }

    public function editstaff($slug = null) {
        $pageTitle = 'Edit Merchant';
        $activetab = 'actstaffs';

        $recordInfo = User::where('slug', $slug)->first();

        if (empty($recordInfo)) {
            return Redirect::to('admin/admins/staff');
        }

        $input = Input::all();
        if (!empty($input)) {
//              echo "<pre>"; print_r($input);exit;
            $rules = array(
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:30',
//                'contact' => 'required|min:8',
//                'address' => 'required',
                'confirm_password' => 'same:password',
                'profile_image' => 'mimes:jpeg,png,jpg',
            );
            $customMessages = [
//                'contact.required' => 'The contact number field is required field.',
            ];
            $validator = Validator::make($input, $rules, $customMessages);
            if ($validator->fails()) {
                return Redirect::to('/admin/admins/editstaff/' . $slug)->withErrors($validator)->withInput();
            } else {
                if (Input::hasFile('profile_image')) {
                    $file = Input::file('profile_image');
                    $uploadedFileName = $this->uploadImage($file, PROFILE_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, PROFILE_FULL_UPLOAD_PATH, PROFILE_SMALL_UPLOAD_PATH, PROFILE_MW, PROFILE_MH);
                    $input['profile_image'] = $uploadedFileName;
                    @unlink(PROFILE_FULL_UPLOAD_PATH . $recordInfo->profile_image);
                    @unlink(PROFILE_SMALL_UPLOAD_PATH . $recordInfo->profile_image);
                } else {
                    unset($input['profile_image']);
                }
                if ($input['password']) {
                    $input['password'] = $this->encpassword($input['password']);
                } else {
                    unset($input['password']);
                }
                $serialisedData = $this->serialiseFormData($input, 1); //send 1 for editstaff

                unset($input['service_ids'][0]);
//print_r($input['service_ids']);

                if (!empty($input['service_ids'])) {
                    $service_ids = implode(',', $input['service_ids']);
                    $serialisedData['service_ids'] = $service_ids;
                }
                unset($serialisedData['Testpapers']);
                if (($key = array_search('attribute selected', $serialisedData)) !== false) {
                    unset($serialisedData['service_ids'][$key]);
                }
//print_r($serialisedData);exit;
                User::where('id', $recordInfo->id)->update($serialisedData);
                Session::flash('success_message', "Merchant details updated successfully.");
                return Redirect::to('admin/admins/staff');
            }
        }
        $allservices = DB::table('services')->where('status', '1')->get();


        return view('admin.admins.editstaff', ['title' => $pageTitle, $activetab => 1, 'recordInfo' => $recordInfo, 'allservices' => $allservices]);
    }

//get admin or staff unavailable days
    public function getoffdays($slug = null) {
        $off = [];

        if ($slug && $slug != null) {
            $staffdata = Admin::where('slug', $slug)->first();

            if (!empty($staffdata)) {
                $alldays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                $staffdays = explode(',', $staffdata->working_days);
                $off = array_diff($alldays, $staffdays);
            }
        } else {
            $staffdata = Setting::where('id', 1)->first();
            $alldays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($alldays as $ad) {
                if ($staffdata[$ad . "_time_from"] == '' || $staffdata[$ad . "_time_from"] == '') {
                    array_push($off, $ad);
                }
            }
        }
        $offclass = ['monday' => 'td.fc-mon', 'tuesday' => 'td.fc-tue', 'wednesday' => 'td.fc-wed', 'thursday' => 'td.fc-thu', 'friday' => 'td.fc-fri', 'saturday' => 'td.fc-sat', 'sunday' => 'td.fc-sun'];
//print_r($off);
//echo "<br>";
//print_r($offclass);
        $offclassstr = "";
        $delimiter = "";
        foreach ($off as $o) {
            $delimiter = $offclassstr != '' ? ',' : '';
            $offclassstr = $offclassstr . $delimiter . $offclass[$o];
        }
// print_r($offclassstr);
//     exit;
        return $offclassstr;
    }

    public function getslots($starttime, $endtime, $duration, $buffer, $staffid, $slotdate, $isFixedSlot) {
//print_r($isFixedSlot);exit;
        $flag = 0;
        $isfullblock = Block::where('slot_date', $slotdate)->where('staff_id', $staffid)->where('full_day', 1)->where('status', 1)->first();
        if (!empty($isfullblock)) {
            $fullday = [];
            $fullday['isfulldayblock'] = 1;
            return $fullday;
        }
        $blocks = Block::where('slot_date', $slotdate)->where('staff_id', $staffid)->where('status', 1)->get();
        $blockslots = [];
        foreach ($blocks as $b) {
            $s = date('H:i', $b['start_time']);
            $e = date('H:i', $b['end_time']);
            array_push($blockslots, ['start_time' => $s, 'end_time' => $e]);
        }
        $bookedstarttime = [];
//if($isFixedSlot==1){
        $staffappoinments = Appointment::where('booking_date_time', '>=', $slotdate . " 00:00:00")->where('booking_date_time', '<=', $slotdate . " 24:00:00")->where('staff_id', $staffid)->where('status', '<>', 'Canceled')->get();
        foreach ($staffappoinments as $sa) {
            $as = date('H:i', strtotime(explode(" ", $sa['booking_date_time'])[1]));
            array_push($bookedstarttime, $as);
        }
//}


        $start = new DateTime($starttime);
        $end = new DateTime($endtime);
        $interval = new DateInterval("PT" . $duration . "M");
        $breakInterval = new DateInterval("PT" . $buffer . "M");
        $timeslots = [];
        for ($intStart = $start; $intStart < $end; $intStart->add($interval)->add($breakInterval)) {

            $endPeriod = clone $intStart;
            $endPeriod->add($interval);
            if ($endPeriod > $end) {
                $endPeriod = $end;
            }
            $starttime = $intStart->format('H:i');
            $endtime = $endPeriod->format('H:i');
            $timeslot = $starttime . ' - ' . $endtime;
            $skey = array_search($starttime, array_column($blockslots, 'start_time'));
            $akey = array_search($starttime, $bookedstarttime);

//if($isFixedSlot==1){
//$akey=array_search($starttime, $bookedstarttime);

            if ($akey !== false) {
                array_push($timeslots, ['slottime' => $timeslot, 'status' => 'BOOKED']);
                $flag = 1;
//continue;
            }
//}else{
            elseif ($skey !== false) {
                array_push($timeslots, ['slottime' => $timeslot, 'status' => 'BLOCKED']);
//continue;
            } else {
                array_push($timeslots, ['slottime' => $timeslot, 'status' => '0']);
            }
//}
        }
        $timeslotdata["flag"] = $flag;
        $timeslotdata["timeslots"] = $timeslots;

        return $timeslotdata;
    }

    public function getstaffslot(Request $request) {
        $staffslug = $request->get('slug');
// print_r($staffslug);exit;
        $selecteddate = $request->get('date');
        $day = strtolower($request->get('dayname'));

        $sitesettings = Setting::first();
        $isFixedSlot = $sitesettings->fixed_time_slot;
        $staffbuffertime = $sitesettings['buffer_time'];
        $duration = $sitesettings['slot_time'];
        $slots = [];



        if ($staffslug != '') {

            $staffdata = Admin::where('type', 'staff')->where('slug', $staffslug)->first();
            $staff_id = $staffdata['id'];
            $working_days = explode(',', $staffdata['working_days']);
            $start_time = explode(',', $staffdata['start_time']);
            $end_time = explode(',', $staffdata['end_time']);

            $key = array_search($day, $working_days, true);
            if ($key === false) {

                return json_encode($slots);
            }
            $slots = $this->getslots($start_time[$key], $end_time[$key], $duration, $staffbuffertime, $staff_id, $selecteddate, $isFixedSlot);
        } else {

            $staff_id = 1;
            if ($sitesettings[$day . '_time_from'] != "" && $sitesettings[$day . '_time_to'] != "") {
                $slots = $this->getslots($sitesettings[$day . '_time_from'], $sitesettings[$day . '_time_to'], $duration, 0, 1, $selecteddate, $isFixedSlot);
            }
        }

        return json_encode($slots);
//return json_encode([]);
    }

    public function saveblockedslot(Request $request) {
        $staffslug = $request->get('slug');
        if ($staffslug != '' && $staffslug != null) {
            $staffdata = Admin::where('type', 'staff')->where('slug', $staffslug)->first();
            $staff_id = $staffdata->id;
        } else {
            $staff_id = 1;
        }
        $date = $request->get('date');
        $stime = $request->get('stime');
        $etime = $request->get('etime');
        $input = [];
        $input['staff_id'] = $staff_id;
        $input['slot_date'] = $date;
        $input['start_time'] = strtotime($stime);
        $input['end_time'] = strtotime($etime);
        $input['slug'] = $this->createSlug('block', 'blocks');
        ;
        $input['full_day'] = 0;
        $input['status'] = 1;
        $input['created'] = date('Y-m-d H:i');
//print_r($input);exit;
        Block::insert($input);
// print_r($staffslug."-".$date."-".$time);exit;
        return "Slot Block successfully.";
    }

    public function blockfullday(Request $request) {
        $staffslug = $request->get('slug');
        if ($staffslug != '' && $staffslug != null) {
            $staffdata = Admin::where('type', 'staff')->where('slug', $staffslug)->first();
            $staff_id = $staffdata->id;
        } else {
            $staff_id = 1;
        }
//print_r($staff_id);exit;
        $date = $request->get('date');
        $input = [];
        $input['staff_id'] = $staff_id;
        $input['slot_date'] = $date;
        $input['slug'] = $this->createSlug('block', 'blocks');
        ;
        $input['full_day'] = 1;
        $input['status'] = 1;
        $input['created'] = date('Y-m-d H:i');
//print_r($input);exit;
        Block::insert($input);
// print_r($staffslug."-".$date."-".$time);exit;
        return "Full Day Is Blocked successfully.";
    }

    public function managecalender($slug = null) {
        $pageTitle = 'Manage Calender';
        if ($slug) {
            $activetab = 'actstaffs';
        } else {
            $activetab = 'actmanagecalender';
        }
        if ($slug) {
            $recordInfo = Admin::where('slug', $slug)->first();
            if (empty($recordInfo)) {
                return Redirect::to('admin/admins/dashboard');
            }
        } else {
            $recordInfo = Admin::where('id', Session::get('adminid'))->first();
            if (empty($recordInfo)) {
                return Redirect::to('admin/admins/dashboard');
            }
        }
        global $week_days;
        global $time_array;
        $input = Input::all();
        if (!empty($input)) {
//            echo "<pre>"; print_r($input);exit;
            $working_days_arr = array();
            $start_time_arr = array();
            $end_time_arr = array();

            foreach ($week_days as $wd_key => $wd_val) {
                $weekdaytimefrom = $wd_key . "_time_from";
                $weekdaytimeto = $wd_key . "_time_to";
                if ($input[$weekdaytimefrom] && $input[$weekdaytimeto]) {
                    $working_days_arr[] = $wd_key;
                    $start_time_arr[] = $input[$weekdaytimefrom];
                    $end_time_arr[] = $input[$weekdaytimeto];
                }
                unset($input[$weekdaytimefrom]);
                unset($input[$weekdaytimeto]);
                unset($input[$wd_key]);
            }



            $serialisedData = $this->serialiseFormData($input, 1); //send 1 for editstaff
            $serialisedData['working_days'] = implode(',', $working_days_arr);
            $serialisedData['start_time'] = implode(',', $start_time_arr);
            $serialisedData['end_time'] = implode(',', $end_time_arr);

//            echo "sdf<pre>"; print_r($serialisedData);exit;
            Admin::where('id', $recordInfo->id)->update($serialisedData);
            Session::flash('success_message', "Merchant details updated successfully.");
            if ($slug) {
                return Redirect::to('admin/admins/staff/' . $slug);
            } else {
                return Redirect::to('admin/admins/manageavailability');
            }
        }
        $working_days_arr = explode(',', $recordInfo->working_days);
        $start_time_arr = explode(',', $recordInfo->start_time);
        $end_time_arr = explode(',', $recordInfo->end_time);


        foreach ($week_days as $wd_key => $wd_val) {
            $weekdaytimefrom = $wd_key . "_time_from";
            $weekdaytimeto = $wd_key . "_time_to";
            if (in_array($wd_key, $working_days_arr)) {
                $key = array_search($wd_key, $working_days_arr);
                $working_days_arr[] = $wd_key;
                $start_time_arr[] = $recordInfo->$weekdaytimefrom;
                $end_time_arr[] = $recordInfo->$weekdaytimeto;
                $recordInfo->$weekdaytimefrom = $start_time_arr[$key];
                $recordInfo->$weekdaytimeto = $end_time_arr[$key];
            }
        }
        return view('admin.admins.managecalender', ['title' => $pageTitle, $activetab => 1, 'recordInfo' => $recordInfo, 'week_days' => $week_days, 'time_array' => $time_array, 'data' => array()]);
    }

    public function activatestaff($slug = null) {
        if ($slug) {
            Admin::where('slug', $slug)->update(array('status' => '1', 'activation_status' => '1'));
            return view('elements.admin.update_status', ['action' => 'admin/admins/deactivatestaff/' . $slug, 'status' => 1]);
        }
    }

    public function deactivatestaff($slug = null) {
        if ($slug) {
            Admin::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action' => 'admin/admins/activatestaff/' . $slug, 'status' => 0]);
        }
    }

    public function deletestaff($slug = null) {
        if ($slug) {
            Admin::where('slug', $slug)->delete();
            Session::flash('success_message', "Merchant details deleted successfully.");
            return Redirect::to('admin/admins/staff');
        }
    }

    public function deleteimagestaff($slug = null) {
        if ($slug) {
            Admin::where('slug', $slug)->update(array('profile_image' => ''));
            Session::flash('success_message', "Image deleted successfully.");
            return Redirect::to('admin/admins/editstaff/' . $slug);
        }
    }

    public static function getCheckRoles($adminLId, $adminRols, $role = 0) {
        $adminRols = array_filter($adminRols);
        if ($adminLId == 1) {
            return true;
        } else {
            if (in_array($role, $adminRols)) {
                return true;
            }
        }
        return false;
    }

    public static function getCheckRolesSub($adminLId, $checkSubRols, $mrole = 0, $srole = 0) {
        if ($adminLId == 1) {
            return true;
        } else {
            if (array_key_exists($mrole, $checkSubRols) && in_array($srole, $checkSubRols[$mrole])) {
                return true;
            }
        }
        return false;
    }

    public function mycalender($slug = null) {
        $pageTitle = 'Manage Availability';
        $activetab = 'actstaffs';

        $recordInfo = Admin::where('slug', $slug)->first();
        if (empty($recordInfo)) {
            return Redirect::to('admin/admins/staff');
        }
        if (Session::get('adminid') != 1) {
            return Redirect::to('admin');
        }

        $input = Input::all();
        if (!empty($input)) {

//             echo "<pre>"; print_r($input);exit;

            $role_ids = implode(',', $input['role_ids']);
            unset($input['role_ids']);
            unset($input['data']);
            unset($input['sselectedassets']);
            $input['role_ids'] = $role_ids;
            $sub_role_ids = json_encode($input['sub_role_ids']);
            unset($input['sub_role_ids']);
            $input['sub_role_ids'] = $sub_role_ids;
//            echo "<pre>"; print_r($input);exit;
            $serialisedData = $this->serialiseFormData($input, 1); //send 1 for editstaff
            Admin::where('id', $recordInfo->id)->update($serialisedData);
            Session::flash('success_message', "Role assigned successfully");
            return Redirect::to('admin/admins/staff');
        }
        return view('admin.admins.mycalender', ['title' => $pageTitle, $activetab => 1, 'recordInfo' => $recordInfo]);
    }

}

?>