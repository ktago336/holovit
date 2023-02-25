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
use App\Models\Service;
use Mail;
use App\Mail\SendMailable;
use App\Models\Appointment;

class ServicesController extends Controller {    
    public function __construct() {
        $this->middleware('is_adminlogin');
    }
    
    public function index(Request $request){
        $pageTitle = 'Manage Services'; 
        $activetab = 'actservices';
        $query = new Service();
        $query = $query->sortable();
        
        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                Service::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                Service::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                Service::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            } 
        }
        
        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword){
                $q->where('name', 'like', '%'.$keyword.'%')
                ->orWhere('description', 'like', '%'.$keyword.'%');
                
            });
        }
        
        $users = $query->orderBy('id','DESC')->paginate(20);
        //print_r($users);exit;
        if($request->ajax()){
            return view('elements.admin.services.index', ['allrecords'=>$users]);
        }
        $admin_id = Session::get('adminid');
        $adminRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRoles(Session::get('adminid'));
            $checkSubRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRolesSub(Session::get('adminid'));
        if($admin_id==1){
        return view('admin.services.index', ['title'=>$pageTitle, $activetab=>1,'allrecords'=>$users]);}
        else{
            if(isset($checkSubRols[5]) && ((in_array(2, $checkSubRols[5])) || (in_array(3, $checkSubRols[5])) || (in_array(4, $checkSubRols[5])))){ 
            return view('admin.services.index', ['title'=>$pageTitle, $activetab=>1,'allrecords'=>$users]);}
        else{  
            Session::flash('error_message', UNAUTHORIZED_LINK);
            return Redirect::to('admin/admins/dashboard');
        }
    }
    }

    public function add(){
        $pageTitle = 'Add Service'; 
        $activetab = 'actservices';
        
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'name' => 'required0',
                'description' => 'required',
                'price' => 'required',
                'minutes' => 'required|digits',
                'service_image' => 'required|mimes:jpeg,png,jpg',
            );
            $validator = Validator::make($input, $rules);             
            // if ($validator->fails()) {
            //     return Redirect::to('/admin/services/add')->withErrors($validator)->withInput();
            // } else {

                if (Input::hasFile('service_image')) {
                    $file = Input::file('service_image');
                    $uploadedFileName = $this->uploadImage($file, SERVICE_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, SERVICE_FULL_UPLOAD_PATH, SERVICE_SMALL_UPLOAD_PATH, SERVICE_MW, SERVICE_MH);
                    $input['service_image'] = $uploadedFileName;
                }else{
                    unset($input['service_image']);
                } 
                               
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['name'], 'services');
                $serialisedData['status'] =  0;
                //print_r($serialisedData);exit;
                Service::insert($serialisedData); 
                
                Session::flash('success_message', "Service details saved successfully.");
                return Redirect::to('admin/services');
            //}           
        }
        $admin_id = Session::get('adminid');
        $adminRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRoles(Session::get('adminid'));
            $checkSubRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRolesSub(Session::get('adminid')); 
        if($admin_id == 1){           
        return view('admin.services.add', ['title'=>$pageTitle, $activetab=>1]);}
        else{
            if(isset($checkSubRols[5]) && (in_array(2, $checkSubRols[5]))){ 
            return view('admin.services.add', ['title'=>$pageTitle, $activetab=>1]);}
        else{  
            Session::flash('error_message', UNAUTHORIZED_LINK);
            return Redirect::to('admin/admins/dashboard');
        }
    }
    }
    
    public function edit($slug=null){
        $pageTitle = 'Edit Services'; 
        $activetab = 'actservices';
        
        $recordInfo = Service::where('slug', $slug)->first();
        if (empty($recordInfo)) {
            return Redirect::to('admin/services');
        }
        
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'name' => 'required0',
                'description' => 'required',
                'price' => 'required',
                'minutes' => 'required|digits',
                'service_image' => 'required|mimes:jpeg,png,jpg',
               
            );
             
            // $validator = Validator::make($input, $rules);             
            // if ($validator->fails()) {
            //     return Redirect::to('/admin/services/edit/'.$slug)->withErrors($validator)->withInput();
            // } else {
                if (Input::hasFile('service_image')) { 
                    $file = Input::file('service_image');
                    $uploadedFileName = $this->uploadImage($file, SERVICE_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, SERVICE_FULL_UPLOAD_PATH, SERVICE_SMALL_UPLOAD_PATH, SERVICE_MW, SERVICE_MH);
                    $input['service_image'] = $uploadedFileName;
                    @unlink(SERVICE_FULL_UPLOAD_PATH.$recordInfo->service_image);
                    @unlink(SERVICE_SMALL_UPLOAD_PATH.$recordInfo->service_image);
                }else{
                    unset($input['service_image']);
                }
                $serialisedData = $this->serialiseFormData($input, 1); //send 1 for edit
                //print_r($serialisedData);exit;
                Service::where('id', $recordInfo->id)->update($serialisedData);
                Session::flash('success_message', "Services details updated successfully.");
                return Redirect::to('admin/services');
            //}           
        }   
        $admin_id = Session::get('adminid');
        $adminRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRoles(Session::get('adminid'));
            $checkSubRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRolesSub(Session::get('adminid'));
        if($admin_id == 1){         
        return view('admin.services.edit', ['title'=>$pageTitle, $activetab=>1, 'recordInfo'=>$recordInfo]);}
        else{
            if(isset($checkSubRols[5]) && (in_array(2, $checkSubRols[5]))){ 
            return view('admin.services.edit', ['title'=>$pageTitle, $activetab=>1, 'recordInfo'=>$recordInfo]);}
        else{  
            Session::flash('error_message', UNAUTHORIZED_LINK);
            return Redirect::to('admin/admins/dashboard');
        }
    }
    }
    
    public function activate($slug=null){
        if($slug){
            Service::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.admin.update_status', ['action'=>'admin/services/deactivate/' . $slug, 'status'=>1]);
        }
    }
    public function deactivate($slug=null){
        if($slug){
            Service::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action'=>'admin/services/activate/' . $slug, 'status'=>0]);
        }
    }
    
    public function delete($slug=null){
        if($slug){
            Service::where('slug', $slug)->delete();
            Session::flash('success_message', "Service details deleted successfully.");
            return Redirect::to('admin/services');
        }
    } 

    public function deleteimage($slug=null){
        if($slug){
            Service::where('slug', $slug)->update(array('service_image' => ''));
            Session::flash('success_message', "Image deleted successfully.");
            return Redirect::to('admin/services/edit/'.$slug);
        }
    } 
    
    public function cancle($slug=null)
    {
        if($slug){ 
            Appointment::where('slug', $slug)->update(array('status' => 'Canceled'));
            Session::flash('success_message', "Appointment cancled successfully.");
            return Redirect::to('admin/requests');
        }
    }
       
}
?>