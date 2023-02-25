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
use App\Models\Banner;
use Mail;
use App\Mail\SendMailable;

class BannersController extends Controller {

    public function __construct() {
        $this->middleware('is_adminlogin');
    }

    public function index(Request $request) {
        $pageTitle = 'Manage Banners';
        $activetab = 'actbanners';
        $query = new Banner();
        $query = $query->sortable();

        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                Banner::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                Banner::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                Banner::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            }
        }

        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%');
            });
        }

        $banners = $query->orderBy('id', 'DESC')->paginate(15);
        if ($request->ajax()) {
            return view('elements.admin.banners.index', ['allrecords' => $banners]);
        }
        return view('admin.banners.index', ['title' => $pageTitle, $activetab => 1, 'allrecords' => $banners]);
    }

    public function add() {
        $pageTitle = 'Add Banner';
        $activetab = 'actbanners';

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'title' => 'required|unique:banners',
				'banner_url' => 'required',
                'banner_image' => 'required|mimes:jpeg,png,jpg|dimensions:width=1920,height=400|max:'.MAX_IMAGE_UPLOAD_SIZE_VAL,
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/banners/add')->withErrors($validator)->withInput();
            } else {

                if (Input::hasFile('banner_image')) {
                    $file = Input::file('banner_image');
                    $uploadedFileName = $this->uploadImage($file, BANNER_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, BANNER_FULL_UPLOAD_PATH, BANNER_SMALL_UPLOAD_PATH, BANNER_MW, BANNER_MH);
                    $input['banner_image'] = $uploadedFileName;
                } else {
                    unset($input['banner_image']);
                }

                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['title'], 'banners');
                $serialisedData['status'] = 1;
//                echo '<pre>';print_r($serialisedData);die;
                Banner::insert($serialisedData);

                Session::flash('success_message', "Banner details saved successfully.");
                return Redirect::to('admin/banners');
            }
        }
        return view('admin.banners.add', ['title' => $pageTitle, $activetab => 1]);
    }

    public function edit($slug = null) {
        $pageTitle = 'Edit Banner';
        $activetab = 'actbanners';

        $recordInfo = Banner::where('slug', $slug)->first();
        if (empty($recordInfo)) {
            return Redirect::to('admin/banners');
        }

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'title' => 'required|unique:banners,title,' . $recordInfo->id,
				'banner_url' => 'required',
                'banner_image' => 'mimes:jpeg,png,jpg|dimensions:width=1920,height=400|max:'.MAX_IMAGE_UPLOAD_SIZE_VAL,
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/banners/edit/' . $slug)->withErrors($validator)->withInput();
            } else {
                if (Input::hasFile('banner_image')) {
                    $file = Input::file('banner_image');
                    $uploadedFileName = $this->uploadImage($file, BANNER_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, BANNER_FULL_UPLOAD_PATH, BANNER_SMALL_UPLOAD_PATH, BANNER_MW, BANNER_MH);
                    $input['banner_image'] = $uploadedFileName;
                    @unlink(BANNER_FULL_UPLOAD_PATH . $recordInfo->banner_image);
                    @unlink(BANNER_SMALL_UPLOAD_PATH . $recordInfo->banner_image);
                } else {
                    unset($input['banner_image']);
                }

                $serialisedData = $this->serialiseFormData($input, 1); //send 1 for edit 
                Banner::where('id', $recordInfo->id)->update($serialisedData);
                Session::flash('success_message', "Banner details updated successfully.");
                return Redirect::to('admin/banners');
            }
        }
        return view('admin.banners.edit', ['title' => $pageTitle, $activetab => 1, 'recordInfo' => $recordInfo]);
    }

    public function activate($slug = null) {
        if ($slug) {
            Banner::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.admin.update_status', ['action' => 'admin/banners/deactivate/' . $slug, 'status' => 1]);
        }
    }

    public function deactivate($slug = null) {
        if ($slug) {
            Banner::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action' => 'admin/banners/activate/' . $slug, 'status' => 0]);
        }
    }

    public function delete($slug = null) {

        if ($slug) {
            Banner::where('slug', $slug)->delete();
            Session::flash('success_message', "Banner details deleted successfully.");
            return Redirect::to('admin/banners');
        }
    }

    public function deleteimage(Request $request, $slug = null) {
        if ($slug) {
            $return = str_replace(HTTP_PATH . '/', '', Input::get('return'));
            Banner::where('slug', $slug)->update(array('banner_image' => ''));
            Session::flash('success_message', "Image deleted successfully.");
            return Redirect::to($return);
        }
    }

}

?>