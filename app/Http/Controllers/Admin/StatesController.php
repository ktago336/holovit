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
use App\Models\Country;
use App\Models\State;

use Mail;
use App\Mail\SendMailable;

class StatesController extends Controller {    
    public function __construct() {
        $this->middleware('is_adminlogin');
    }
    
    public function index($slug=null,Request $request){
        $pageTitle = 'Manage States'; 
        $activetab = 'actstates';
        $query = new State();
        $query = $query->sortable();
        $countryData = Country::where('slug', $slug)->first();
         
        
       
        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                State::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                State::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                State::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            } 
        }
        
        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword){
                $q->where('name', 'like', '%'.$keyword.'%');
            });
        }
        
        $countries = $query->where(['country_id'=>$countryData->id])->orderBy('id','DESC')->paginate(20);
        if($request->ajax()){
            return view('elements.admin.states.index', ['allrecords'=>$countries]);
        }
        return view('admin.states.index', ['title'=>$pageTitle, $activetab=>1,'allrecords'=>$countries,'countryData'=>$countryData]);
    }

    public function add($slug=null){
        $pageTitle = 'Add State'; 
        $activetab = 'actstates';
        $countryData = Country::where('slug', $slug)->first();
         
        $input = Input::all();
        
        if (!empty($input)) {
            $rules = array(
                'name' => 'required|unique:states',
            );
            $validator = Validator::make($input, $rules);             
            if ($validator->fails()) {
                return Redirect::to('/admin/states/add/'.$slug)->withErrors($validator)->withInput();
            } else {
                $input['name'] = ucfirst($input['name']);
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['name'], 'states');
                $serialisedData['country_id'] = $countryData['id'] ;
                $serialisedData['status'] =  1;
                State::insert($serialisedData); 
                Session::flash('success_message', "State saved successfully.");
                return Redirect::to('admin/states/'.$countryData['slug']);
            }           
        }        
        return view('admin.states.add', ['title'=>$pageTitle, $activetab=>1,'countryData'=>$countryData]);
    }
    
    public function edit($slug=null){
        $pageTitle = 'Edit State'; 
        $activetab = 'actstates';
        
        $recordInfo = State::where('slug', $slug)->first();
        $countryData = Country::where('id', $recordInfo->country_id)->first();
        if (empty($recordInfo)) {
            return Redirect::to('admin/states');
        }
        
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'name' => 'required|unique:states,name,'.$recordInfo->id,
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/states/edit/'.$slug)->withErrors($validator)->withInput();
            } else {
                $serialisedData = $this->serialiseFormData($input, 1); //send 1 for edit
                State::where('id', $recordInfo->id)->update($serialisedData);
                Session::flash('success_message', "State updated successfully.");
                return Redirect::to('admin/states/'.$countryData->slug);
            }           
        }        
        return view('admin.states.edit', ['title'=>$pageTitle, $activetab=>1, 'recordInfo'=>$recordInfo,'slug'=>$slug, 'countryData'=>$countryData]);
    }
    
    public function activate($slug=null){
        if($slug){
            State::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.admin.update_status', ['action'=>'admin/states/deactivate/' . $slug, 'status'=>1]);
        }
    }
    public function deactivate($slug=null){
        if($slug){
            State::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action'=>'admin/states/activate/' . $slug, 'status'=>0]);
        }
    }
    
    public function delete($slug=null){
        if($slug){
             $stateInfo = State::where('slug', $slug)->first();
			 $countryInfo = Country::where('id', $stateInfo->country_id)->first();
            State::where('slug', $slug)->delete();
            Session::flash('success_message', "State deleted successfully.");
            return Redirect::to('admin/states/'.$countryInfo['slug']);
        }
    }    
}
?>