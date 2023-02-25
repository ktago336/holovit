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
use App\Models\Location;
use App\Models\Country;
use Mail;
use App\Mail\SendMailable;

class LocationsController extends Controller {

    public function __construct() {
        $this->middleware('is_adminlogin');
    }

    public function index(Request $request) {
        $pageTitle = 'Manage Locations';
        $activetab = 'actlocations';
        $query = new Location();
        $query = $query->sortable();

        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                Location::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                Location::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                Location::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            }
        }

        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword) {
                $q->where('location_name', 'like', '%' . $keyword . '%');
            });
        }

        $locations = $query->orderBy('id', 'DESC')->paginate(15);
        if ($request->ajax()) {
            return view('elements.admin.locations.index', ['allrecords' => $locations]);
        }
        return view('admin.locations.index', ['title' => $pageTitle, $activetab => 1, 'allrecords' => $locations]);
    }

    public function add() {
        $pageTitle = 'Add Location';
        $activetab = 'actlocations';

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'location_name' => 'required|unique:locations',
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/locations/add')->withErrors($validator)->withInput();
            } else {

                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['location_name'], 'locations');
                $serialisedData['status'] = 1;
//                echo '<pre>';print_r($serialisedData);die;
                Location::insert($serialisedData);

                Session::flash('success_message', "Location details saved successfully.");
                return Redirect::to('admin/locations');
            }
        }
        return view('admin.locations.add', ['title' => $pageTitle, $activetab => 1]);
    }

    public function edit($slug = null) {
        $pageTitle = 'Edit Location';
        $activetab = 'actlocations';

        $recordInfo = Location::where('slug', $slug)->first();
        if (empty($recordInfo)) {
            return Redirect::to('admin/locations');
        }

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'location_name' => 'required|unique:locations,location_name,' . $recordInfo->id,
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/locations/edit/' . $slug)->withErrors($validator)->withInput();
            } else {
//                print_r($input);die;
                $serialisedData = $this->serialiseFormData($input, 1); //send 1 for edit 
                Location::where('id', $recordInfo->id)->update($serialisedData);                
                Session::flash('success_message', "Location details updated successfully.");
                return Redirect::to('admin/locations');
            }
        }
        return view('admin.locations.edit', ['title' => $pageTitle, $activetab => 1, 'recordInfo' => $recordInfo]);
    }

    public function activate($slug = null) {
        if ($slug) {
            Location::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.admin.update_status', ['action' => 'admin/locations/deactivate/' . $slug, 'status' => 1]);
        }
    }

    public function deactivate($slug = null) {
        if ($slug) {
            Location::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action' => 'admin/locations/activate/' . $slug, 'status' => 0]);
        }
    }

    public function delete($slug = null) {

        if ($slug) {
            Location::where('slug', $slug)->delete();
            Session::flash('success_message', "Location details deleted successfully.");
            return Redirect::to('admin/locations');
        }
    }


}

?>