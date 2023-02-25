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
use App\Models\Category;
use App\Models\Country;
use Mail;
use App\Mail\SendMailable;

class CategoriesController extends Controller {

    public function __construct() {
        $this->middleware('is_adminlogin');
    }

    public function index(Request $request) {
        $pageTitle = 'Manage Categories';
        $activetab = 'actcategories';
        $query = new Category();
        $query = $query->sortable();
        $query = $query->where('parent_id', 0);

        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                Category::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                Category::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                Category::whereIn('id', $idList)->delete();
                Category::whereIn('parent_id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            }
        }

        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword) {
                $q->where('category_name', 'like', '%' . $keyword . '%');
            });
        }

        $categories = $query->orderBy('id', 'DESC')->paginate(15);
        if ($request->ajax()) {
            return view('elements.admin.categories.index', ['allrecords' => $categories]);
        }
        return view('admin.categories.index', ['title' => $pageTitle, $activetab => 1, 'allrecords' => $categories]);
    }

    public function add() {
        $pageTitle = 'Add Category';
        $activetab = 'actcategories';

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'category_name' => 'required|unique:categories',
                'category_image' => 'required|mimes:jpeg,png,jpg|dimensions:width=39,height=39|max:'.MAX_IMAGE_UPLOAD_SIZE_VAL,
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/categories/add')->withErrors($validator)->withInput();
            } else {

                if (Input::hasFile('category_image')) {
                    $file = Input::file('category_image');
                    $uploadedFileName = $this->uploadImage($file, CATEGORY_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, CATEGORY_FULL_UPLOAD_PATH, CATEGORY_SMALL_UPLOAD_PATH, CATEGORY_MW, CATEGORY_MH);
                    $input['category_image'] = $uploadedFileName;
                } else {
                    unset($input['category_image']);
                }

                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['category_name'], 'categories');
                $serialisedData['status'] = 1;
//                echo '<pre>';print_r($serialisedData);die;
                Category::insert($serialisedData);

                Session::flash('success_message', "Category details saved successfully.");
                return Redirect::to('admin/categories');
            }
        }
        return view('admin.categories.add', ['title' => $pageTitle, $activetab => 1]);
    }

    public function edit($slug = null) {
        $pageTitle = 'Edit Category';
        $activetab = 'actcategories';

        $recordInfo = Category::where('slug', $slug)->first();
        if (empty($recordInfo)) {
            return Redirect::to('admin/categories');
        }

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'category_name' => 'required|unique:categories,category_name,' . $recordInfo->id,
                'category_image' => 'mimes:jpeg,png,jpg|dimensions:width=39,height=39|max:'.MAX_IMAGE_UPLOAD_SIZE_VAL,
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/categories/edit/' . $slug)->withErrors($validator)->withInput();
            } else {
                if (Input::hasFile('category_image')) {
                    $file = Input::file('category_image');
                    $uploadedFileName = $this->uploadImage($file, CATEGORY_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, CATEGORY_FULL_UPLOAD_PATH, CATEGORY_SMALL_UPLOAD_PATH, CATEGORY_MW, CATEGORY_MH);
                    $input['category_image'] = $uploadedFileName;
                    @unlink(CATEGORY_FULL_UPLOAD_PATH . $recordInfo->category_image);
                    @unlink(CATEGORY_SMALL_UPLOAD_PATH . $recordInfo->category_image);
                } else {
                    unset($input['category_image']);
                }
                if(isset($input['is_feature']) && $input['is_feature']){
                    //
                }else{
                    $input['is_feature'] = 0;
                }

                $serialisedData = $this->serialiseFormData($input, 1); //send 1 for edit 
                Category::where('id', $recordInfo->id)->update($serialisedData);
                Session::flash('success_message', "Category details updated successfully.");
                return Redirect::to('admin/categories');
            }
        }
        return view('admin.categories.edit', ['title' => $pageTitle, $activetab => 1, 'recordInfo' => $recordInfo]);
    }

    public function activate($slug = null) {
        if ($slug) {
            Category::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.admin.update_status', ['action' => 'admin/categories/deactivate/' . $slug, 'status' => 1]);
        }
    }

    public function deactivate($slug = null) {
        if ($slug) {
            Category::where('slug', $slug)->update(array('status' => '0'));
            $parentInfo = Category::where('slug', $slug)->first();
            if (!empty($parentInfo)) {
                Category::where('parent_id', $parentInfo->id)->update(array('status' => '0'));
            }
            return view('elements.admin.update_status', ['action' => 'admin/categories/activate/' . $slug, 'status' => 0]);
        }
    }

    public function delete($slug = null) {

        if ($slug) {
            Category::where('slug', $slug)->delete();
            Session::flash('success_message', "Category details deleted successfully.");
            return Redirect::to('admin/categories');
        }
    }

    // sub categories 


    public function subindex(Request $request, $pslug = null) {
        $parentInfo = Category::where('slug', $pslug)->first();
        if (empty($parentInfo)) {
            return Redirect::to('admin/categories');
        }
//        print_r($request);
//        die;
        $pageTitle = 'Manage Sub Categories';
        $activetab = 'actcategories';
        $query = new Category();
        $query = $query->sortable();
        $query = $query->where('parent_id', $parentInfo->id);

        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                Category::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                Category::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                Category::whereIn('id', $idList)->delete();
                Category::whereIn('parent_id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            }
        }

        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword) {
                $q->where('category_name', 'like', '%' . $keyword . '%');
            });
        }

        $categories = $query->orderBy('id', 'DESC')->paginate(15);
        if ($request->ajax()) {
            return view('elements.admin.categories.indexsub', ['allrecords' => $categories, 'parentInfo' => $parentInfo]);
        }
        return view('admin.categories.indexsub', ['title' => $pageTitle, $activetab => 1, 'allrecords' => $categories, 'parentInfo' => $parentInfo]);
    }

    public function addsub($pslug = null) {
        $parentInfo = Category::where('slug', $pslug)->first();
        if (empty($parentInfo)) {
            return Redirect::to('admin/categories');
        }
        $pageTitle = 'Add Sub Category';
        $activetab = 'actcategories';

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'category_name' => 'required|unique:categories',
                'category_image' => 'required|mimes:jpeg,png,jpg|dimensions:width=358,height=378|max:'.MAX_IMAGE_UPLOAD_SIZE_VAL,
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/categories/addsub/' . $pslug)->withErrors($validator)->withInput();
            } else {
                if (Input::hasFile('category_image')) {
                    $file = Input::file('category_image');
                    $uploadedFileName = $this->uploadImage($file, CATEGORY_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, CATEGORY_FULL_UPLOAD_PATH, CATEGORY_SMALL_UPLOAD_PATH, CATEGORY_MW, CATEGORY_MH);
                    $input['category_image'] = $uploadedFileName;
                } else {
                    unset($input['category_image']);
                }

                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['category_name'], 'categories');
                $serialisedData['status'] = 1;
                $serialisedData['parent_id'] = $parentInfo->id;
                Category::insert($serialisedData);

                Session::flash('success_message', "Sub Category details saved successfully.");
                return Redirect::to('admin/categories/subcategories/' . $pslug);
            }
        }
        return view('admin.categories.addsub', ['title' => $pageTitle, $activetab => 1, 'parentInfo' => $parentInfo]);
    }

    public function editsub($pslug = null, $slug = null) {
        $parentInfo = Category::where('slug', $pslug)->first();
        if (empty($parentInfo)) {
            return Redirect::to('admin/categories');
        }
        $pageTitle = 'Edit Sub Category';
        $activetab = 'actcategories';

        $recordInfo = Category::where('slug', $slug)->first();
        if (empty($recordInfo)) {
            return Redirect::to('admin/categories/subcategories/' . $pslug);
        }

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'category_name' => 'required|unique:categories,category_name,' . $recordInfo->id,
                'category_image' => 'mimes:jpeg,png,jpg|dimensions:width=358,height=378|max:'.MAX_IMAGE_UPLOAD_SIZE_VAL,
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/categories/editsub/' . $pslug . '/' . $slug)->withErrors($validator)->withInput();
            } else {
                if (Input::hasFile('category_image')) {
                    $file = Input::file('category_image');
                    $uploadedFileName = $this->uploadImage($file, CATEGORY_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, CATEGORY_FULL_UPLOAD_PATH, CATEGORY_SMALL_UPLOAD_PATH, CATEGORY_MW, CATEGORY_MH);
                    $input['category_image'] = $uploadedFileName;
                    @unlink(CATEGORY_FULL_UPLOAD_PATH . $recordInfo->category_image);
                    @unlink(CATEGORY_SMALL_UPLOAD_PATH . $recordInfo->category_image);
                } else {
                    unset($input['category_image']);
                }
                if(isset($input['is_feature']) && $input['is_feature']){
                    //
                }else{
                    $input['is_feature'] = 0;
                }
                $serialisedData = $this->serialiseFormData($input, 1); //send 1 for edit
                Category::where('id', $recordInfo->id)->update($serialisedData);
                Session::flash('success_message', "Sub Category details updated successfully.");
                return Redirect::to('admin/categories/subcategories/' . $pslug);
            }
        }
        return view('admin.categories.editsub', ['title' => $pageTitle, $activetab => 1, 'recordInfo' => $recordInfo, 'parentInfo' => $parentInfo]);
    }

    public function activatesub($slug = null) {
        if ($slug) {
            Category::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.admin.update_status', ['action' => 'admin/categories/deactivate/' . $slug, 'status' => 1]);
        }
    }

    public function deactivatesub($slug = null) {
        if ($slug) {
            Category::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action' => 'admin/categories/activate/' . $slug, 'status' => 0]);
        }
    }

    public function deletesub($pslug = null, $slug = null) {
        if ($slug) {
            $recordInfo = Category::where('slug', $slug)->first();
            Category::where('id', $recordInfo->id)->delete();
            Category::where('parent_id', $recordInfo->id)->delete();
            Session::flash('success_message', "Sub Category details deleted successfully.");
            return Redirect::to('admin/categories/subcategories/' . $pslug);
        }
    }

    // sub sub categories 


    public function subsubindex(Request $request, $pslug = null) {
        $parentInfo = Category::where('slug', $pslug)->first();
        if (empty($parentInfo)) {
            return Redirect::to('admin/categories');
        }
        $pparentInfo = Category::where('id', $parentInfo->parent_id)->first();
//        echo '<pre>'; print_r($parentInfo);
//        print_r($pparentInfo);
//        die;
        $pageTitle = 'Manage Categories';
        $activetab = 'actcategories';
        $query = new Category();
        $query = $query->sortable();
        $query = $query->where('parent_id', $parentInfo->id);

        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                Category::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                Category::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                Category::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            }
        }

        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword) {
                $q->where('category_name', 'like', '%' . $keyword . '%');
            });
        }

        $categories = $query->orderBy('id', 'DESC')->paginate(15);
        if ($request->ajax()) {
            return view('elements.admin.categories.indexsubsub', ['allrecords' => $categories, 'parentInfo' => $parentInfo, 'pparentInfo' => $pparentInfo]);
        }
        return view('admin.categories.indexsubsub', ['title' => $pageTitle, $activetab => 1, 'allrecords' => $categories, 'parentInfo' => $parentInfo, 'pparentInfo' => $pparentInfo]);
    }

    public function addsubsub($pslug = null) {
        $parentInfo = Category::where('slug', $pslug)->first();
        if (empty($parentInfo)) {
            return Redirect::to('admin/categories');
        }
        $pparentInfo = Category::where('id', $parentInfo->parent_id)->first();
        $pageTitle = 'Add Category';
        $activetab = 'actcategories';

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'category_name' => 'required|unique:categories',
                'category_image' => 'mimes:jpeg,png,jpg'
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/categories/addsubsub/' . $pslug)->withErrors($validator)->withInput();
            } else {

                if (Input::hasFile('category_image')) {
                    $file = Input::file('category_image');
                    $uploadedFileName = $this->uploadImage($file, CATEGORY_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, CATEGORY_FULL_UPLOAD_PATH, CATEGORY_SMALL_UPLOAD_PATH, CATEGORY_MW, CATEGORY_MH);
                    $input['category_image'] = $uploadedFileName;
                } else {
                    unset($input['category_image']);
                }

                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['category_name'], 'categories');
                $serialisedData['status'] = 1;
                $serialisedData['parent_id'] = $parentInfo->id;
                Category::insert($serialisedData);

                Session::flash('success_message', "Category details saved successfully.");
                return Redirect::to('admin/categories/subsubcategories/' . $pslug);
            }
        }
        return view('admin.categories.addsubsub', ['title' => $pageTitle, $activetab => 1, 'parentInfo' => $parentInfo, 'pparentInfo' => $pparentInfo]);
    }

    public function editsubsub($pslug = null, $slug = null) {
        $parentInfo = Category::where('slug', $pslug)->first();
        if (empty($parentInfo)) {
            return Redirect::to('admin/categories');
        }
        $pparentInfo = Category::where('id', $parentInfo->parent_id)->first();
        $pageTitle = 'Edit Category';
        $activetab = 'actcategories';

        $recordInfo = Category::where('slug', $slug)->first();
        if (empty($recordInfo)) {
            return Redirect::to('admin/categories/subsubcategories/' . $pslug);
        }

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'category_name' => 'required|unique:categories,category_name,' . $recordInfo->id,
                'category_image' => 'mimes:jpeg,png,jpg'
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/admin/categories/editsubsub/' . $pslug . '/' . $slug)->withErrors($validator)->withInput();
            } else {
                if (Input::hasFile('category_image')) {
                    $file = Input::file('category_image');
                    $uploadedFileName = $this->uploadImage($file, CATEGORY_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, CATEGORY_FULL_UPLOAD_PATH, CATEGORY_SMALL_UPLOAD_PATH, CATEGORY_MW, CATEGORY_MH);
                    $input['category_image'] = $uploadedFileName;
                    @unlink(CATEGORY_FULL_UPLOAD_PATH . $recordInfo->category_image);
                    @unlink(CATEGORY_SMALL_UPLOAD_PATH . $recordInfo->category_image);
                } else {
                    unset($input['category_image']);
                }
                $serialisedData = $this->serialiseFormData($input, 1); //send 1 for edit
                Category::where('id', $recordInfo->id)->update($serialisedData);
                Session::flash('success_message', "Category details updated successfully.");
                return Redirect::to('admin/categories/subsubcategories/' . $pslug);
            }
        }
        return view('admin.categories.editsubsub', ['title' => $pageTitle, $activetab => 1, 'recordInfo' => $recordInfo, 'parentInfo' => $parentInfo, 'pparentInfo' => $pparentInfo]);
    }

    public function activatesubsub($slug = null) {
        if ($slug) {
            Category::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.admin.update_status', ['action' => 'admin/categories/deactivate/' . $slug, 'status' => 1]);
        }
    }

    public function deactivatesubsub($slug = null) {
        if ($slug) {
            Category::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action' => 'admin/categories/activate/' . $slug, 'status' => 0]);
        }
    }

    public function deletesubsub($pslug = null, $slug = null) {
        if ($slug) {
            Category::where('slug', $slug)->delete();
            Session::flash('success_message', "Category details deleted successfully.");
            return Redirect::to('admin/categories/subsubcategories/' . $pslug);
        }
    }

    public function deleteimage(Request $request, $slug = null) {
        if ($slug) {
            $return = str_replace(HTTP_PATH . '/', '', Input::get('return'));
            Category::where('slug', $slug)->update(array('category_image' => ''));
            Session::flash('success_message', "Image deleted successfully.");
            return Redirect::to($return);
        }
    }

}

?>