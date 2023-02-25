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
use App\Models\BusinessType;
use App\Models\Country;
use Mail;
use App\Mail\SendMailable;

class BusinessTypesController extends Controller {

    public function __construct() {
        $this->middleware('is_adminlogin');
    }

    public function index(Request $request) {
        $pageTitle = 'Manage Business Types';
        $activetab = 'actbusiness_types';
        $query = new BusinessType();
        $query = $query->sortable();

        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                BusinessType::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                BusinessType::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                BusinessType::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            }
        }

        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%');
            });
        }

        $business_types = $query->orderBy('id', 'DESC')->paginate(15);
        if ($request->ajax()) {
            return view('elements.admin.business_types.index', ['allrecords' => $business_types]);
        }
        return view('admin.business_types.index', ['title' => $pageTitle, $activetab => 1, 'allrecords' => $business_types]);
    }

    public function add() {
        $pageTitle = 'Add Business Type';
        $activetab = 'actbusiness_types';

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'name' => 'required|unique:business_types',
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/business_types/add')->withErrors($validator)->withInput();
            } else {

                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['name'], 'business_types');
                $serialisedData['status'] = 1;
                BusinessType::insert($serialisedData);
                Session::flash('success_message', "Business Type details saved successfully.");
                return Redirect::to('admin/business_types');
            }
        }
        return view('admin.business_types.add', ['title' => $pageTitle, $activetab => 1]);
    }

    public function edit($slug = null) {
        $pageTitle = 'Edit Business Type';
        $activetab = 'actbusiness_types';

        $recordInfo = BusinessType::where('slug', $slug)->first();
        if (empty($recordInfo)) {
            return Redirect::to('admin/business_types');
        }

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'name' => 'required|unique:business_types,name,' . $recordInfo->id,
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/business_types/edit/' . $slug)->withErrors($validator)->withInput();
            } else {
                $serialisedData = $this->serialiseFormData($input, 1); 
                BusinessType::where('id', $recordInfo->id)->update($serialisedData);                
                Session::flash('success_message', "Business Type details updated successfully.");
                return Redirect::to('admin/business_types');
            }
        }
        return view('admin.business_types.edit', ['title' => $pageTitle, $activetab => 1, 'recordInfo' => $recordInfo]);
    }

    public function activate($slug = null) {
        if ($slug) {
            BusinessType::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.admin.update_status', ['action' => 'admin/business_types/deactivate/' . $slug, 'status' => 1]);
        }
    }

    public function deactivate($slug = null) {
        if ($slug) {
            BusinessType::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action' => 'admin/business_types/activate/' . $slug, 'status' => 0]);
        }
    }

    public function delete($slug = null) {

        if ($slug) {
            BusinessType::where('slug', $slug)->delete();
            Session::flash('success_message', "Business Type details deleted successfully.");
            return Redirect::to('admin/business_types');
        }
    }


}

?>