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
use App\Models\Merchant;
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
        $query = new Merchant();
        $query = $query->sortable();

        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                Merchant::whereIn('id', $idList)->update(array('status' => 1, 'activation_status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                Merchant::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                Merchant::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            }
        }
        //$query = $query->where(['user_type' => 'merchant']);

        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword) {
                $q->where('busineess_name', 'like', '%' . $keyword . '%')
                        //->orWhere('store_name', 'like', '%' . $keyword . '%')
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

        $business_category = DB::table('categories')->where(['parent_id'=> 0,'status' => 1])->orderBy('category_name', 'ASC')->pluck('category_name', 'id');
		$country = DB::table('countries')->where(['status' => 1])->orderBy('name', 'ASC')->pluck('name', 'id');
		   
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                /*'first_name' => 'required|max:20',
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
                'confirm_password' => 'required|same:password'*/
                'name' => 'required',
                'busineess_name' => 'required',
                'contact' => 'required|min:8',
                'address' => 'required',
                'email_address' => 'required|email|unique:merchants',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
            );
            $customMessages = [
                    //  'contact.required' => 'The contact number field is required field.',
            ];
            $validator = Validator::make($input, $rules, $customMessages);
            if ($validator->fails()) {
                //                return Redirect::to('/admin/admins/addmerchant')->withErrors($validator)->with('data', $input);;
                return Redirect::to('/admin/admins/addmerchant')->withErrors($validator)->withInput();
            } else {
                $business_name = $input['busineess_name'];
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['name'], 'merchants');
                $serialisedData['status'] = 1;
                
                //                $serialisedData['activation_status'] =  1;
                $serialisedData['password'] = $this->encpassword($input['password']);


                $recordInfo = DB::table('settings')->where('id', 1)->first();


                Merchant::insert($serialisedData);

                
//mail to merchant

                $name = $input['name'];
                $emailId = $input['email_address'];
                $contact = $input['contact'];

                $new_password = $input['password'];
                
                $link = HTTP_PATH . "/merchant/login";

                $emailTemplate = DB::table('emailtemplates')->where('id', 2)->first();
                $toRepArray = array('[!email!]', '[!username!]', '[!business_name!]', '[!contact!]', '[!password!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($emailId, $name, $business_name, $contact, $new_password, $link, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
				Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));

                Session::flash('success_message', "Merchant details saved successfully.");
                return Redirect::to('admin/admins/merchant');
            }
        }

        return view('admin.users.addmerchant', ['title' => $pageTitle, $activetab => 1,'country'=>$country,'business_category'=>$business_category]);
    }

    public function editmerchant($slug = null) {
        $pageTitle = 'Edit Merchant';
        $activetab = 'actmerchants';

        $recordInfo = Merchant::where('slug', $slug)->first();
        
        $country = DB::table('countries')->where(['status' => 1])->orderBy('name', 'ASC')->pluck('name', 'id');
		$state = DB::table('states')->where(['status' => 1,'country_id' => $recordInfo->country_id])->orderBy('name', 'ASC')->pluck('name', 'id');
		$city = DB::table('cities')->where(['status' => 1,'state_id' => $recordInfo->state_id])->orderBy('name', 'ASC')->pluck('name', 'id');
		$locality = DB::table('localities')->where(['status' => 1,'city_id' => $recordInfo->city_id])->orderBy('locality_name', 'ASC')->pluck('locality_name', 'id');

        //print_r($state);exit;
		global $week_days;
		global $time_array;
		
        if (empty($recordInfo)) {
            return Redirect::to('admin/admins/merchant');
        }
// echo "<pre>"; print_r($recordInfo);exit;
        $input = Input::all();
        if (!empty($input)) {
//              echo "<pre>"; print_r($input);exit;
            $rules = array(
                /*'first_name' => 'required|max:20',
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
                'confirm_password' => 'same:password'*/
                'name' => 'required',
                'busineess_name' => 'required',
                'contact' => 'required|min:8',
                'address' => 'required',
            );
            $customMessages = [
//                'contact.required' => 'The contact number field is required field.',
            ];
            $validator = Validator::make($input, $rules, $customMessages);
            if ($validator->fails()) {
                return Redirect::to('/admin/admins/editmerchant/' . $slug)->withErrors($validator)->withInput();
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
                
                $updata = array('about_us'=>$input['about_us'],'city_id'=>$input['city_id'],'locality_id'=>$input['locality_id'],'address'=>$input['address'],'zipcode'=>$input['zipcode'],'busineess_name'=>$input['busineess_name'],'name'=>$input['name'],'email_address'=>$input['email_address'],'profile_image'=>$imagesArray);
				
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
				if ($input['password']) {
                   $new_password = $this->encpassword($input['password']);
                     $updata['password'] = $new_password;
                } else {
                    unset($input['password']);
                }
				
                $updata['facebook_link'] = $input['facebook_link'];
				$updata['instagram_link'] = $input['instagram_link'];
				$updata['linkedin_link'] = $input['linkedin_link'];
				$updata['twitter_link'] = $input['twitter_link'];
				$updata['youtube_link'] = $input['youtube_link'];
                
                //$serialisedData = $this->serialiseFormData($input, 1); //send 1 for editmerchant

                Merchant::where('id', $recordInfo->id)->update($updata);
                //print_r($updata);exit;
                Session::flash('success_message', "Merchant details updated successfully.");
                return Redirect::to('admin/admins/merchant');
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


        return view('admin.users.editmerchant', ['title' => $pageTitle, $activetab => 1, 'recordInfo' => $recordInfo, 'week_days' => $week_days, 'time_array' => $time_array, 'country' => $country, 'state' => $state, 'city' => $city, 'locality' => $locality, 'allservices' => $allservices]);
    }

    public function activatemerchant($slug = null) {
        if ($slug) {
            
            $recordInfo = Merchant::where('slug', $slug)->first();
            Merchant::where('slug', $slug)->update(array('status' => '1', 'activation_status' => '1'));
            
            if($recordInfo->activation_status !=1){
                $name = $recordInfo->name;
                $emailId = $recordInfo->email_address;
                $business_name = $recordInfo->busineess_name;
                $emailTemplate = DB::table('emailtemplates')->where('id', 19)->first();
                $toRepArray = array('[!email!]', '[!name!]', '[!username!]', '[!business_name!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($emailId, $name, $name, $business_name, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));
            }else{
                
            }
            return view('elements.admin.update_status', ['action' => 'admin/admins/deactivatemerchant/' . $slug, 'status' => 1]);
        }
    }

    public function deactivatemerchant($slug = null) {
        if ($slug) {
            Merchant::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action' => 'admin/admins/activatemerchant/' . $slug, 'status' => 0]);
        }
    }

    public function deletemerchant($slug = null) {
        if ($slug) {
            Merchant::where('slug', $slug)->delete();
            Session::flash('success_message', "Merchant details deleted successfully.");
            return Redirect::to('admin/admins/merchant');
        }
    }

    public function deleteimagemerchant($slug = null, $imagename = null) {
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
            Session::flash('success_message', "Image deleted successfully.");
           
        }
         return Redirect::to('admin/admins/editmerchant/'.$slug);
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
            Session::flash('success_message', "Image deleted successfully.");
            
        }
        return Redirect::to('merchant/user/myaccount/');
       
    }

}

?>