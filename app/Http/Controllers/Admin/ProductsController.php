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
use App\Models\Product;
use Mail;
use App\Mail\SendMailable;
//use App\Models\Appointment;
//use App\Models\Category;

class ProductsController extends Controller {    
    public function __construct() {
        $this->middleware('is_adminlogin');
    }
    
    public function index(Request $request){
        $pageTitle = 'Manage Products'; 
        $activetab = 'actproducts';
        $query = new Product();
        $query = $query->sortable();
        
        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                Product::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                Product::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                Product::whereIn('id', $idList)->delete();
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

//        echo '<pre>';
//        print_r($users);exit;
        if($request->ajax()){
            return view('elements.admin.products.index',['allrecords'=>$users]);
        }
       return view('admin.products.index',['title'=>$pageTitle, $activetab=>1,'allrecords'=>$users]);
    }

    public function add(){
        $pageTitle = 'Add Products'; 
        $activetab = 'actproducts';
        $merchant = DB::table('users')->where(['status'=>1,'user_type'=>'merchant'])->orderBy('store_name', 'ASC')->pluck('store_name','id');
        $brand = DB::table('brands')->where(['status'=>1])->orderBy('brand_name', 'ASC')->pluck('brand_name','id');
        $location = DB::table('locations')->where(['status'=>1])->orderBy('location_name', 'ASC')->pluck('location_name','id');
        $category = DB::table('categories')->where(['parent_id'=> 0,'status'=>1])->orderBy('category_name', 'ASC')->pluck('category_name','id');
        //echo '<pre>';
       // print_r($category); exit;
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'name' => 'required0',
                'description' => 'required',
                'price' => 'required',
                'minutes' => 'required|digits',
                'images' => 'required|mimes:jpeg,png,jpg',
            );
            $validator = Validator::make($input, $rules);             
            // if ($validator->fails()) {
            //     return Redirect::to('/admin/deals/add')->withErrors($validator)->withInput();
            // } else {

                //if (Input::hasFile('images')) {
           
            if(isset($input['subcategory_id'])){
                 $subcategory = $input['subcategory_id'];
            }else{
                $subcategory = '';
            }
            if(isset($input['subsubcategory_id'])){
                 $subsubcategory = $input['subsubcategory_id'];
            }else{
                $subsubcategory = '';
            }
                    $imagesArray = array();
                    $files = Input::file('images');
                     if (count($files) > 0) {
                          foreach ($files as $file) {
                         if ($file) {     
                    $uploadedFileName = $this->uploadImage($file, PRODUCT_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, PRODUCT_FULL_UPLOAD_PATH, PRODUCT_SMALL_UPLOAD_PATH, PRODUCT_MW, PRODUCT_MH);
                    $imagesArray[] = $uploadedFileName;
                          }
                          }
                    $imagesArray = implode(',', $imagesArray);
                }else{
                    $imagesArray = '';
                } 
                if(!isset($input['videos'])){
                    $videoArray = array();
                    $videoArray ='';
                }else{
                $videoArray = array();
                $vfiles = Input::file('videos');
                    if (count($vfiles) > 0) {
                          foreach ($vfiles as $vfile) {
                         if ($vfile) {     
                    $uploadedFileName = $this->uploadImage($vfile, PRODUCTVIDEO_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, PRODUCTVIDEO_FULL_UPLOAD_PATH, PRODUCTVIDEO_SMALL_UPLOAD_PATH, PRODUCTVIDEO_MW, PRODUCTVIDEO_MH);
                    $videoArray[] = $uploadedFileName;
                          }
                          }
                    $videoArray = implode(',', $videoArray);
                    //print_r($videoArray); exit;
                }else{
                    $videoArray = '';
                } 
                }
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['name'], 'products');
                $serialisedData['images'] = $imagesArray;
                $serialisedData['videos'] = $videoArray;
                $serialisedData['subcategory_id'] = $subcategory;
                $serialisedData['subsubcategory_id'] = $subsubcategory;
                $serialisedData['status'] =  0;
                //echo '<pre>';
                //print_r($serialisedData);exit;
                Product::insert($serialisedData); 
                
                Session::flash('success_message', "Products details saved successfully.");
                return Redirect::to('admin/products');
            //}           
        }
 
        return view('admin.products.add', ['title'=>$pageTitle, $activetab=>1,'merchant'=>$merchant,'category'=>$category,'brand'=>$brand,'location'=>$location]);
     
    }
    
    public function edit($slug=null){
        $pageTitle = 'Edit Products'; 
        $activetab = 'actproducts';
        $merchant = DB::table('users')->where(['status'=>1,'user_type'=>'merchant'])->orderBy('store_name', 'ASC')->pluck('store_name','id');
        $brand = DB::table('brands')->where(['status'=>1])->orderBy('brand_name', 'ASC')->pluck('brand_name','id');
        $location = DB::table('locations')->where(['status'=>1])->orderBy('location_name', 'ASC')->pluck('location_name','id');
        $category = DB::table('categories')->where(['parent_id'=> 0,'status'=>1])->orderBy('category_name', 'ASC')->pluck('category_name','id');
        
        $recordInfo = Product::where('slug', $slug)->first();
        if (empty($recordInfo)) {
            return Redirect::to('admin/products');
        }
       $subcategory = DB::table('categories')->where(['parent_id'=> $recordInfo->category_id,'status'=>1])->orderBy('category_name', 'ASC')->pluck('category_name','id');
       $subsubcategory = DB::table('categories')->where(['parent_id'=> $recordInfo->subcategory_id,'status'=>1])->orderBy('category_name', 'ASC')->pluck('category_name','id');
       //echo '<pre>'; print_r($subcategory); exit;
        $input = Input::all();
        //echo '<pre>'; print_r($input); exit;
        if (!empty($input)) {
            $rules = array(
                'name' => 'required0',
                'description' => 'required',
                'price' => 'required',
                'minutes' => 'required|digits',
                //'images' => 'required|mimes:jpeg,png,jpg',
               
            );
             
             $validator = Validator::make($input, $rules);             
            // if ($validator->fails()) {
            //     return Redirect::to('/admin/deals/edit/'.$slug)->withErrors($validator)->withInput();
            // } else {
             $image = explode(',',$recordInfo['images']); 
              $imagesArray = array();
                if($image){
                    $imagesArray = array_filter($image);
                }
                if (Input::hasFile('images')) { 
                    $files = Input::file('images');
                     if (count($files) > 0) {
                         foreach ($files as $file) {
                             if ($file) {
                    $uploadedFileName = $this->uploadImage($file, PRODUCT_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, PRODUCT_FULL_UPLOAD_PATH, PRODUCT_SMALL_UPLOAD_PATH, PRODUCT_MW, PRODUCT_MH);
                    $imagesArray[] = $uploadedFileName;
                    @unlink(PRODUCT_FULL_UPLOAD_PATH.$recordInfo->images);
                    @unlink(PRODUCT_SMALL_UPLOAD_PATH.$recordInfo->images);
                              }
                         }
                         $imagesArray = implode(',', $imagesArray);
                     }else { 
                        $imagesArray = '';
                    }
                }else{ 
                    if($imagesArray){
                        $imagesArray = implode(',', $imagesArray);
                    }else{ 
                        $imagesArray = '';
                    }
                }
                
                // edit video
                 $video = explode(',',$recordInfo['videos']); 
              $videosArray = array();
                if($video){
                    $videosArray = array_filter($video);
                }
                if (Input::hasFile('videos')) { 
                    $files = Input::file('videos');
                     if (count($files) > 0) {
                         foreach ($files as $file) {
                             if ($file) {
                    $uploadedFileName = $this->uploadImage($file, PRODUCTVIDEO_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, PRODUCTVIDEO_FULL_UPLOAD_PATH, PRODUCTVIDEO_SMALL_UPLOAD_PATH, PRODUCTVIDEO_MW, PRODUCTVIDEO_MH);
                    $videosArray[] = $uploadedFileName;
                    @unlink(PRODUCTVIDEO_FULL_UPLOAD_PATH.$recordInfo->videos);
                    @unlink(PRODUCTVIDEO_SMALL_UPLOAD_PATH.$recordInfo->videos);
                              }
                         }
                         $videosArray = implode(',', $videosArray);
                     }else { 
                        $videosArray = '';
                    }
                }else{ 
                    if($videosArray){
                        $videosArray = implode(',', $videosArray);
                    }else{ 
                        $videosArray = '';
                    }
                }
                
                $serialisedData = $this->serialiseFormData($input); //send 1 for edit
                $serialisedData['videos'] = $videosArray;
                $serialisedData['images'] = $imagesArray;
                Product::where('id', $recordInfo->id)->update($serialisedData);
                Session::flash('success_message', "Products details updated successfully.");
                return Redirect::to('admin/products');
            //}           
        }   
      return view('admin.products.edit', ['title'=>$pageTitle, $activetab=>1, 'recordInfo'=>$recordInfo,'merchant'=>$merchant,'category'=>$category,'brand'=>$brand,'location'=>$location,'subcategory'=>$subcategory,'subsubcategory'=>$subsubcategory]);
    }
    
    public function activate($slug=null){
        if($slug){
            Product::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.admin.update_status', ['action'=>'admin/products/deactivate/' . $slug, 'status'=>1]);
        }
    }
    public function deactivate($slug=null){ 
        if($slug){
            Product::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action'=>'admin/products/activate/' . $slug, 'status'=>0]);
        }
    }
    
    public function delete($slug=null){
        if($slug){
            Product::where('slug', $slug)->delete();
            Session::flash('success_message', "Product details deleted successfully.");
            return Redirect::to('admin/products');
        }
    } 

    public function deleteimage($slug=null){
        if($slug){
            Product::where('slug', $slug)->update(array('service_image' => ''));
            Session::flash('success_message', "Image deleted successfully.");
            return Redirect::to('admin/products/edit/'.$slug);
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
       
    public function add_subcategory(Request $request){
         $cats =  $request->get('cats');
         //$subcategory = array();
        if(!empty($cats)){
         $subcategory = DB::table('categories')->where(['parent_id'=> $cats,'status'=>1])->orderBy('category_name', 'ASC')->pluck('category_name','id');
         echo '<option select="selected" value="">Select Subcategories</option>';
         foreach($subcategory as $value=> $subcategorys){
             echo '<option value="'.$value.'">'.$subcategorys.'</option>';
         } exit;
    }
    
}
public function add_sub_subcategory(Request $request){
    $subcats =  $request->get('subcats');
      if(!empty($subcats)){
         $sub_subcategory = DB::table('categories')->where(['parent_id'=> $subcats,'status'=>1])->orderBy('category_name', 'ASC')->pluck('category_name','id');
         echo '<option select="selected" value="">Select Sub Subcategories</option>';
         foreach($sub_subcategory as $value=> $sub_subcategorys){
             echo '<option value="'.$value.'">'.$sub_subcategorys.'</option>';
         } exit;
    }
}

  public function deleteimageedit($slug = null, $imagename = null) {
        if ($slug) {
            $propInfo = DB::table('products')->where('slug', $slug)->first();
            $imagesArray = explode(',', $propInfo->images);
            $imageKey = array_search($imagename, $imagesArray);
            unset($imagesArray[$imageKey]);
            if ($imagename) {
                @unlink(PRODUCT_FULL_UPLOAD_PATH . $imagename);
                @unlink(PRODUCT_SMALL_UPLOAD_PATH . $imagename);
            }
            $impldeName = implode(',', $imagesArray);
            Product::where('slug', $slug)->update(array('images' => $impldeName));
            Session::flash('success_message', "Product image deleted successfully.");
            return Redirect::to('admin/products/edit/'.$slug);
        }
    }
     public function deletevideoedit($slug = null, $videoname = null) {
             if ($slug) {
            $propInfo = DB::table('products')->where('slug', $slug)->first();
            $videosArray = explode(',', $propInfo->videos);
            $videoKey = array_search($videoname, $videosArray);
            unset($videosArray[$videoKey]);
            if ($videoname) {
                @unlink(PRODUCTVIDEO_FULL_UPLOAD_PATH . $videoname);
                @unlink(PRODUCTVIDEO_SMALL_UPLOAD_PATH . $videoname);
            }
            $impldeName = implode(',', $videosArray);
            Product::where('slug', $slug)->update(array('videos' => $impldeName));
            Session::flash('success_message', "Product video deleted successfully.");
            return Redirect::to('admin/products/edit/'.$slug);
        }
     }
    
}
?>