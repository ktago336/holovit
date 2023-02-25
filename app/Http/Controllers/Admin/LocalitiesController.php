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
use App\Models\Locality;
use App\Models\City;

use Mail;
use App\Mail\SendMailable;

class LocalitiesController extends Controller {    
    public function __construct() {
        $this->middleware('is_adminlogin');
    }
    
    public function index($slug=null,Request $request){
        $pageTitle = 'Manage Localities'; 
        $activetab = 'actlocalities';
        $query = new Locality();
        $query = $query->sortable();
        $cityData = City::where('slug', $slug)->first();
		
        $stateData = State::where('id', $cityData->state_id)->first();
		//print_r($cityData);exit;
        $countryData = Country::where('id', $stateData->country_id)->first(); 
        
       
        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                Locality::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                Locality::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                Locality::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            } 
        }
        
        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword){
                $q->where('locality_name', 'like', '%'.$keyword.'%');
            });
        }
        
        $localities = $query->where(['city_id'=>$cityData->id])->orderBy('id','DESC')->paginate(20);
        if($request->ajax()){
            return view('elements.admin.localities.index', ['allrecords'=>$localities]);
        }
        return view('admin.localities.index', ['title'=>$pageTitle, $activetab=>1,'allrecords'=>$localities,'cityData'=>$cityData,'stateData'=>$stateData,'countryData'=>$countryData]);
    }

    public function add($slug=null){
        $pageTitle = 'Add Locality'; 
        $activetab = 'actlocalities';
        $cityData = City::where('slug', $slug)->first();
        $stateData = State::where('id', $cityData->state_id)->first();
        $countryData = Country::where('id', $stateData->country_id)->first(); 
         
        $input = Input::all();
        
        if (!empty($input)) {
            $rules = array(
                'locality_name' => 'required|unique:localities',
            );
            $validator = Validator::make($input, $rules);             
            if ($validator->fails()) {
                return Redirect::to('/admin/localities/add')->withErrors($validator)->withInput();
            } else {
                $input['locality_name'] = ucfirst($input['locality_name']);
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['locality_name'], 'localities');
                $serialisedData['city_id'] = $cityData['id'] ;
                $n['status'] =  1;
                Locality::insert($serialisedData); 
                Session::flash('success_message', "Locality saved successfully.");
                return Redirect::to('admin/localities/'.$cityData['slug']);
            }           
        }        
        return view('admin.localities.add', ['title'=>$pageTitle, $activetab=>1,'cityData'=>$cityData,'stateData'=>$stateData,'countryData'=>$countryData]);
    }
    
    public function edit($slug=null){
        $pageTitle = 'Edit Locality'; 
        $activetab = 'actlocalities';
        
        $recordInfo = Locality::where('slug', $slug)->first();
		$cityData = City::where('id', $recordInfo->city_id)->first();
        $stateData = State::where('id', $cityData->state_id)->first();
        $countryData = Country::where('id', $stateData->country_id)->first(); 
        
        if (empty($recordInfo)) {
            return Redirect::to('admin/localities');
        }
        
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'locality_name' => 'required|unique:localities,locality_name,'.$recordInfo->id,
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/localities/edit/'.$slug)->withErrors($validator)->withInput();
            } else {
                $serialisedData = $this->serialiseFormData($input, 1); //send 1 for edit
                Locality::where('id', $recordInfo->id)->update($serialisedData);
                Session::flash('success_message', "Locality updated successfully.");
                return Redirect::to('admin/localities/'.$cityData['slug']);
            }           
        }        
        return view('admin.localities.edit', ['title'=>$pageTitle, $activetab=>1, 'recordInfo'=>$recordInfo,'slug'=>$slug,'cityData'=>$cityData,'stateData'=>$stateData,'countryData'=>$countryData]);
    }
    
    public function activate($slug=null){
        if($slug){
            Locality::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.admin.update_status', ['action'=>'admin/localities/deactivate/' . $slug, 'status'=>1]);
        }
    }
    public function deactivate($slug=null){
        if($slug){
            Locality::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action'=>'admin/localities/activate/' . $slug, 'status'=>0]);
        }
    }
    
    public function delete($slug=null){
        if($slug){
             $localityInfo = Locality::where('slug', $slug)->first();
			 $cityData = City::where('id', $localityInfo->city_id)->first();
            Locality::where('slug', $slug)->delete();
            Session::flash('success_message', "Locality deleted successfully.");
            return Redirect::to('admin/localities/'.$cityData['slug']);
        }
    }    
}
?>