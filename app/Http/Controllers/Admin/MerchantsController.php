<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cookie;
use Session;
use Redirect;
use Input;
use Validator;
use DB;
use IsAdmin;
use App\Models\User;
use Mail;
use App\Mail\SendMailable;

class UsersController extends Controller {

    public function __construct() {
        $this->middleware('is_adminlogin');
    }

    public function index(Request $request) {
        $pageTitle = 'Manage Users';
        $activetab = 'actusers';
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

        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword) {
                $q->where('first_name', 'like', '%' . $keyword . '%')
                        ->orWhere('last_name', 'like', '%' . $keyword . '%')
                        ->orWhere('email_address', 'like', '%' . $keyword . '%');
            });
        }

        $users = $query->orderBy('id', 'DESC')->paginate(20);
		//echo '<pre>';print_r($users); exit;
        if ($request->ajax()) {
            return view('elements.admin.users.index', ['allrecords' => $users]);
        }

        return view('admin.users.index', ['title' => $pageTitle, $activetab => 1, 'allrecords' => $users]);
    }

    public function add() {
        $pageTitle = 'Add User';
        $activetab = 'actusers';

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:30',
                'contact' => 'required|min:8',
                'address' => 'required',
                'email_address' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
                'profile_image' => 'required|mimes:jpeg,png,jpg',
            );
            $customMessages = [
                'contact.required' => 'The contact number field is required field.',
            ];
            $validator = Validator::make($input, $rules, $customMessages);
            if ($validator->fails()) {
                return Redirect::to('/admin/users/add')->withErrors($validator)->withInput();
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
                $serialisedData['slug'] = $this->createSlug($input['first_name'] . ' ' . $input['last_name'], 'users');
                $serialisedData['status'] = 1;
                $serialisedData['activation_status'] = 1;
                $serialisedData['password'] = $this->encpassword($input['password']);
                User::insert($serialisedData);

                $name = $input['first_name'] . ' ' . $input['last_name'];
                $emailId = $input['email_address'];
                $new_password = $input['password'];

                $emailTemplate = DB::table('emailtemplates')->where('id', 2)->first();
                $toRepArray = array('[!email!]', '[!name!]', '[!username!]', '[!password!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($emailId, $name, $name, $new_password, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

                Session::flash('success_message', "User details saved successfully.");
                return Redirect::to('admin/users');
            }
        }
        return view('admin.users.add', ['title' => $pageTitle, $activetab => 1]);
    }

    public function edit($slug = null) {
        $pageTitle = 'Edit User';
        $activetab = 'actusers';

        $recordInfo = User::where('slug', $slug)->first();
        if (empty($recordInfo)) {
            return Redirect::to('admin/users');
        }

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:30',
                'contact' => 'required|min:8',
                'address' => 'required',
                'confirm_password' => 'same:password',
                'profile_image' => 'mimes:jpeg,png,jpg',
            );
            $customMessages = [
                'contact.required' => 'The contact number field is required field.',
            ];
            $validator = Validator::make($input, $rules, $customMessages);
            if ($validator->fails()) {
                return Redirect::to('/admin/users/edit/' . $slug)->withErrors($validator)->withInput();
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
                $serialisedData = $this->serialiseFormData($input, 1); //send 1 for edit
                User::where('id', $recordInfo->id)->update($serialisedData);
                Session::flash('success_message', "User details updated successfully.");
                return Redirect::to('admin/users');
            }
        }
        return view('admin.users.edit', ['title' => $pageTitle, $activetab => 1, 'recordInfo' => $recordInfo]);
    }

    public function activate($slug = null) {
        if ($slug) {
            User::where('slug', $slug)->update(array('status' => '1', 'activation_status' => '1'));
            return view('elements.admin.update_status', ['action' => 'admin/users/deactivate/' . $slug, 'status' => 1]);
        }
    }

    public function deactivate($slug = null) {
        if ($slug) {
            User::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action' => 'admin/users/activate/' . $slug, 'status' => 0]);
        }
    }

    public function delete($slug = null) {
        if ($slug) {
            User::where('slug', $slug)->delete();
            Session::flash('success_message', "User details deleted successfully.");
            return Redirect::to('admin/users');
        }
    }

    public function deleteimage($slug = null) {
        if ($slug) {
            User::where('slug', $slug)->update(array('profile_image' => ''));
            Session::flash('success_message', "Image deleted successfully.");
            return Redirect::to('admin/users/edit/' . $slug);
        }
    }

    public function merchant(Request $request) {
        $pageTitle = 'Manage Merchant';
        $activetab = 'actmerchants';
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
                        ->orWhere('busineess_name', 'like', '%' . $keyword . '%')
                        ->orWhere('store_name', 'like', '%' . $keyword . '%')
                        ->orWhere('email_address', 'like', '%' . $keyword . '%');
            });
        }

        $merchants = $query->orderBy('id', 'DESC')->paginate(20);


        if ($request->ajax()) {
            return view('elements.admin.users.merchant', ['allrecords' => $merchants]);
        }
        return view('admin.users.merchant', ['title' => $pageTitle, $activetab => 1, 'allrecords' => $merchants]);
    }

    public function addmerchant() {
        $pageTitle = 'Add Merchant';
        $activetab = 'actmerchants';

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:30',
                'contact' => 'required|min:8',
                'email_address' => 'required|email|unique:users',
                'business_type' => 'required',
                'busineess_name' => 'required',
                'store_name' => 'required',
                'address' => 'required',
                'country' => 'required',
                'state' => 'required',
                'zipcode' => 'required',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password'
            );
            $customMessages = [
                    //  'contact.required' => 'The contact number field is required field.',
            ];
            $validator = Validator::make($input, $rules, $customMessages);
            if ($validator->fails()) {
                //                return Redirect::to('/admin/admins/addmerchant')->withErrors($validator)->with('data', $input);;
                return Redirect::to('/admin/admins/addmerchant')->withErrors($validator)->withInput();
            } else {

                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['first_name'] . ' ' . $input['last_name'], 'admins');
                $serialisedData['status'] = 1;
                $serialisedData['user_type'] = 'merchant';
                //                $serialisedData['activation_status'] =  1;
                $serialisedData['password'] = $this->encpassword($input['password']);


                $recordInfo = DB::table('settings')->where('id', 1)->first();


                User::insert($serialisedData);

                $name = $input['first_name'] . ' ' . $input['last_name'];
                $emailId = $input['email_address'];
                $new_password = $input['password'];

                //                $emailTemplate = DB::table('emailtemplates')->where('id', 2)->first();
                //                $toRepArray = array('[!email!]', '[!name!]', '[!username!]', '[!password!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                //                $fromRepArray = array($emailId, $name, $name, $new_password, HTTP_PATH, SITE_TITLE);
                //                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                //                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                //                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));

                Session::flash('success_message', "Merchant details saved successfully.");
                return Redirect::to('admin/admins/merchant');
            }
        }

        return view('admin.users.addmerchant', ['title' => $pageTitle, $activetab => 1]);
    }

    public function editmerchant($slug = null) {
        $pageTitle = 'Edit Merchant';
        $activetab = 'actmerchants';

        $recordInfo = User::where('slug', $slug)->first();

        if (empty($recordInfo)) {
            return Redirect::to('admin/admins/merchant');
        }
// echo "<pre>"; print_r($recordInfo);exit;
        $input = Input::all();
        if (!empty($input)) {
//              echo "<pre>"; print_r($input);exit;
            $rules = array(
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:30',
                'contact' => 'required|min:8',
                'email_address' => 'required|email|unique:users,email_address,' . $recordInfo->id,
                'business_type' => 'required',
                'busineess_name' => 'required',
                'store_name' => 'required',
                'address' => 'required',
                'country' => 'required',
                'state' => 'required',
                'zipcode' => 'required',
                'confirm_password' => 'same:password'
            );
            $customMessages = [
//                'contact.required' => 'The contact number field is required field.',
            ];
            $validator = Validator::make($input, $rules, $customMessages);
            if ($validator->fails()) {
                return Redirect::to('/admin/admins/editmerchant/' . $slug)->withErrors($validator)->withInput();
            } else {

                if ($input['password']) {
                    $input['password'] = $this->encpassword($input['password']);
                } else {
                    unset($input['password']);
                }
                $serialisedData = $this->serialiseFormData($input, 1); //send 1 for editmerchant
//print_r($serialisedData);exit;
                User::where('id', $recordInfo->id)->update($serialisedData);
                Session::flash('success_message', "Merchant details updated successfully.");
                return Redirect::to('admin/admins/merchant');
            }
        }
        $allservices = DB::table('services')->where('status', '1')->get();


        return view('admin.users.editmerchant', ['title' => $pageTitle, $activetab => 1, 'recordInfo' => $recordInfo, 'allservices' => $allservices]);
    }

    public function activatemerchant($slug = null) {
        if ($slug) {
            Admin::where('slug', $slug)->update(array('status' => '1', 'activation_status' => '1'));
            return view('elements.admin.update_status', ['action' => 'admin/admins/deactivatemerchant/' . $slug, 'status' => 1]);
        }
    }

    public function deactivatemerchant($slug = null) {
        if ($slug) {
            Admin::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action' => 'admin/admins/activatemerchant/' . $slug, 'status' => 0]);
        }
    }

    public function deletemerchant($slug = null) {
        if ($slug) {
            Admin::where('slug', $slug)->delete();
            Session::flash('success_message', "Merchant details deleted successfully.");
            return Redirect::to('admin/admins/merchant');
        }
    }

    public function deleteimagemerchant($slug = null) {
        if ($slug) {
            Admin::where('slug', $slug)->update(array('profile_image' => ''));
            Session::flash('success_message', "Image deleted successfully.");
            return Redirect::to('admin/admins/editmerchant/' . $slug);
        }
    }

}

?>