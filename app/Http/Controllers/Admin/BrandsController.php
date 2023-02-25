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
use App\Models\Brand;
use App\Models\Country;
use Mail;
use App\Mail\SendMailable;

class BrandsController extends Controller {

    public function __construct() {
        $this->middleware('is_adminlogin');
    }

    public function index(Request $request) {
        $pageTitle = 'Manage Brands';
        $activetab = 'actbrands';
        $query = new Brand();
        $query = $query->sortable();

        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                Brand::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                Brand::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                Brand::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            }
        }

        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword) {
                $q->where('brand_name', 'like', '%' . $keyword . '%');
            });
        }

        $brands = $query->orderBy('id', 'DESC')->paginate(15);
        if ($request->ajax()) {
            return view('elements.admin.brands.index', ['allrecords' => $brands]);
        }
        return view('admin.brands.index', ['title' => $pageTitle, $activetab => 1, 'allrecords' => $brands]);
    }

    public function add() {
        $pageTitle = 'Add Brand';
        $activetab = 'actbrands';

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'brand_name' => 'required|unique:brands',
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/brands/add')->withErrors($validator)->withInput();
            } else {

                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['brand_name'], 'brands');
                $serialisedData['status'] = 1;
                Brand::insert($serialisedData);
                Session::flash('success_message', "Brand details saved successfully.");
                return Redirect::to('admin/brands');
            }
        }
        return view('admin.brands.add', ['title' => $pageTitle, $activetab => 1]);
    }

    public function edit($slug = null) {
        $pageTitle = 'Edit Brand';
        $activetab = 'actbrands';

        $recordInfo = Brand::where('slug', $slug)->first();
        if (empty($recordInfo)) {
            return Redirect::to('admin/brands');
        }

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'brand_name' => 'required|unique:brands,brand_name,' . $recordInfo->id,
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/brands/edit/' . $slug)->withErrors($validator)->withInput();
            } else {
                $serialisedData = $this->serialiseFormData($input, 1); 
                Brand::where('id', $recordInfo->id)->update($serialisedData);                
                Session::flash('success_message', "Brand details updated successfully.");
                return Redirect::to('admin/brands');
            }
        }
        return view('admin.brands.edit', ['title' => $pageTitle, $activetab => 1, 'recordInfo' => $recordInfo]);
    }

    public function activate($slug = null) {
        if ($slug) {
            Brand::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.admin.update_status', ['action' => 'admin/brands/deactivate/' . $slug, 'status' => 1]);
        }
    }

    public function deactivate($slug = null) {
        if ($slug) {
            Brand::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action' => 'admin/brands/activate/' . $slug, 'status' => 0]);
        }
    }

    public function delete($slug = null) {

        if ($slug) {
            Brand::where('slug', $slug)->delete();
            Session::flash('success_message', "Brand details deleted successfully.");
            return Redirect::to('admin/brands');
        }
    }


}

?>