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
use App\Models\City;
use App\Models\State;

use Mail;
use App\Mail\SendMailable;

class CitiesController extends Controller {    
    public function __construct() {
        $this->middleware('is_adminlogin');
    }
    
    public function index($slug=null,Request $request){
        $pageTitle = 'Manage Cities'; 
        $activetab = 'actcities';
        $query = new City();
        $query = $query->sortable();
        $stateData = State::where('slug', $slug)->first();
        $countryData = Country::where('id', $stateData->country_id)->first();
        
       
        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                City::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                City::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                City::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            } 
        }
        
        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword){
                $q->where('name', 'like', '%'.$keyword.'%');
            });
        }
        
        $cities = $query->where(['state_id'=>$stateData->id])->orderBy('id','DESC')->paginate(20);
        if($request->ajax()){
            return view('elements.admin.cities.index', ['allrecords'=>$cities]);
        }
        return view('admin.cities.index', ['title'=>$pageTitle, $activetab=>1,'allrecords'=>$cities,'stateData'=>$stateData,'countryData'=>$countryData]);
    }

    public function add($slug=null){
        $pageTitle = 'Add City'; 
        $activetab = 'actcities';
        $stateData = State::where('slug', $slug)->first();
        $countryData = Country::where('id', $stateData->country_id)->first(); 
        $input = Input::all();
        
        if (!empty($input)) {
            $rules = array(
                'name' => 'required|unique:cities',
            );
            $validator = Validator::make($input, $rules);             
            if ($validator->fails()) {
                return Redirect::to('/admin/cities/add/'.$slug)->withErrors($validator)->withInput();
            } else {
                $input['name'] = ucfirst($input['name']);
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['name'], 'cities');
                $serialisedData['state_id'] = $stateData['id'] ;
                $serialisedData['status'] =  1;
                City::insert($serialisedData); 
                Session::flash('success_message', "City saved successfully.");
                return Redirect::to('admin/cities/'.$stateData['slug']);
            }           
        }        
        return view('admin.cities.add', ['title'=>$pageTitle, $activetab=>1, 'stateData'=>$stateData, 'countryData'=>$countryData]);
    }
    
    public function edit($slug=null){
        $pageTitle = 'Edit City'; 
        $activetab = 'actcities';
        
        $recordInfo = City::where('slug', $slug)->first();
		$stateData = State::where('id', $recordInfo->state_id)->first();
        $countryData = Country::where('id', $stateData->country_id)->first();
		
        if (empty($recordInfo)) {
            return Redirect::to('admin/cities');
        }
        
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'name' => 'required|unique:cities,name,'.$recordInfo->id,
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/cities/edit/'.$slug)->withErrors($validator)->withInput();
            } else {
                $serialisedData = $this->serialiseFormData($input, 1); //send 1 for edit
                City::where('id', $recordInfo->id)->update($serialisedData);
                Session::flash('success_message', "City updated successfully.");
                return Redirect::to('admin/cities/'.$stateData['slug']);
            }           
        }        
        return view('admin.cities.edit', ['title'=>$pageTitle, $activetab=>1, 'recordInfo'=>$recordInfo, 'stateData'=>$stateData, 'countryData'=>$countryData,'slug'=>$slug]);
    }
    
    public function activate($slug=null){
        if($slug){
            City::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.admin.update_status', ['action'=>'admin/cities/deactivate/' . $slug, 'status'=>1]);
        }
    }
    public function deactivate($slug=null){
        if($slug){
            City::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action'=>'admin/cities/activate/' . $slug, 'status'=>0]);
        }
    }
    
    public function delete($slug=null){
        if($slug){
             $cityInfo = City::where('slug', $slug)->first();
			 $stateData = State::where('id', $cityInfo->state_id)->first();
            City::where('slug', $slug)->delete();
            Session::flash('success_message', "City deleted successfully.");
            return Redirect::to('admin/cities/'.$stateData['slug']);
        }
    }    
}
?>