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
use App\Models\Coupon;
use Mail;
use App\Mail\SendMailable;
//use App\Models\Appointment;
//use App\Models\Category;

class CouponsController extends Controller {    
    public function __construct() {
        $this->middleware('is_adminlogin');
    }
    
    public function index(Request $request){
        $pageTitle = 'Manage Coupons'; 
        $activetab = 'actcoupons';
        $query = new Coupon();
        $query = $query->sortable();
        
        
        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                Coupon::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                Coupon::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                Coupon::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            } 
        }
      
        
        if ($request->has('keyword')) { 
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword){
                $q->where('coupon_code', 'like', '%'.$keyword.'%');
                
            });
        }
        
        $users = $query->orderBy('id','DESC')->paginate(20);
        
        /// $pr = Coupon::with('Product')->get('product_id');

      // echo '<pre>';
     // print_r($users);exit;
        if($request->ajax()){
            return view('elements.admin.coupons.index',['allrecords'=>$users]);
        }
       return view('admin.coupons.index',['title'=>$pageTitle, $activetab=>1,'allrecords'=>$users]);
    }

//    public function add(){
//        $pageTitle = 'Add Deal'; 
//        $activetab = 'actdeals';
//        $merchant = DB::table('users')->where(['status'=>1,'user_type'=>'merchant'])->orderBy('store_name', 'ASC')->pluck('store_name','id');
//        $brand = DB::table('brands')->where(['status'=>1])->orderBy('brand_name', 'ASC')->pluck('brand_name','id');
//        $location = DB::table('locations')->where(['status'=>1])->orderBy('location_name', 'ASC')->pluck('location_name','id');
//        $category = DB::table('categories')->where(['parent_id'=> 0,'status'=>1])->orderBy('category_name', 'ASC')->pluck('category_name','id');
//        //echo '<pre>';
//       // print_r($category); exit;
//        $input = Input::all();
//        if (!empty($input)) {
//            $rules = array(
//                'name' => 'required0',
//                'description' => 'required',
//                'price' => 'required',
//                'minutes' => 'required|digits',
//                'images' => 'required|mimes:jpeg,png,jpg',
//            );
//            $validator = Validator::make($input, $rules);             
//            // if ($validator->fails()) {
//            //     return Redirect::to('/admin/deals/add')->withErrors($validator)->withInput();
//            // } else {
//
//                //if (Input::hasFile('images')) {
//           
//            if(isset($input['subcategory_id'])){
//                 $subcategory = $input['subcategory_id'];
//            }else{
//                $subcategory = '';
//            }
//            if(isset($input['subsubcategory_id'])){
//                 $subsubcategory = $input['subsubcategory_id'];
//            }else{
//                $subsubcategory = '';
//            }
//                    $imagesArray = array();
//                    $files = Input::file('images');
//                     if (count($files) > 0) {
//                          foreach ($files as $file) {
//                         if ($file) {     
//                    $uploadedFileName = $this->uploadImage($file, DEAL_FULL_UPLOAD_PATH);
//                    $this->resizeImage($uploadedFileName, DEAL_FULL_UPLOAD_PATH, DEAL_SMALL_UPLOAD_PATH, DEAL_MW, DEAL_MH);
//                    $imagesArray[] = $uploadedFileName;
//                          }
//                          }
//                    $imagesArray = implode(',', $imagesArray);
//                }else{
//                    $imagesArray = '';
//                } 
//                if(!isset($input['videos'])){
//                    $videoArray = array();
//                    $videoArray ='';
//                }else{
//                $videoArray = array();
//                $vfiles = Input::file('videos');
//                    if (count($vfiles) > 0) {
//                          foreach ($vfiles as $vfile) {
//                         if ($vfile) {     
//                    $uploadedFileName = $this->uploadImage($vfile, DEALVIDEO_FULL_UPLOAD_PATH);
//                    $this->resizeImage($uploadedFileName, DEALVIDEO_FULL_UPLOAD_PATH, DEALVIDEO_SMALL_UPLOAD_PATH, DEALVIDEO_MW, DEALVIDEO_MH);
//                    $videoArray[] = $uploadedFileName;
//                          }
//                          }
//                    $videoArray = implode(',', $videoArray);
//                    //print_r($videoArray); exit;
//                }else{
//                    $videoArray = '';
//                } 
//                }
//                $serialisedData = $this->serialiseFormData($input);
//                $serialisedData['slug'] = $this->createSlug($input['name'], 'deals');
//                $serialisedData['images'] = $imagesArray;
//                $serialisedData['videos'] = $videoArray;
//                $serialisedData['subcategory_id'] = $subcategory;
//                $serialisedData['subsubcategory_id'] = $subsubcategory;
//                $serialisedData['status'] =  0;
//                //echo '<pre>';
//                //print_r($serialisedData);exit;
//                Deal::insert($serialisedData); 
//                
//                Session::flash('success_message', "Deal details saved successfully.");
//                return Redirect::to('admin/deals');
//            //}           
//        }
// 
//        return view('admin.deals.add', ['title'=>$pageTitle, $activetab=>1,'merchant'=>$merchant,'category'=>$category,'brand'=>$brand,'location'=>$location]);
//     
//    }
    
    public function edit($slug=null){
        $pageTitle = 'Edit Coupon'; 
        $activetab = 'actcoupons';
        $product_name = DB::table('products')->where(['status'=>1])->orderBy('name', 'ASC')->pluck('name','id'); 
      // print_r($product_name); exit;
        $recordInfo = Coupon::where('slug', $slug)->first();
        if (empty($recordInfo)) {
            return Redirect::to('admin/coupons');
        }
       //echo '<pre>'; print_r($subcategory); exit;
        $input = Input::all();
        //echo '<pre>'; print_r($input); exit;
        if (!empty($input)) {
            $rules = array(
                'coupon_code' => 'required0',
                'description' => 'required',
                'title' => 'required',
                'expiry_date' => 'required',
                'discount_offer' => 'required',
                'description' => 'required',
                //'images' => 'required|mimes:jpeg,png,jpg',
               
            );
             
             $validator = Validator::make($input, $rules); 
             
            if(isset($input['product_id']) && $input['product_id'] != ''){
               $productid = implode(',',$input['product_id']);
           } 
            // if ($validator->fails()) {
            //     return Redirect::to('/admin/deals/edit/'.$slug)->withErrors($validator)->withInput();
            // } else {
                
                // edit video
                $serialisedData = $this->serialiseFormData($input); //send 1 for edit
                $serialisedData['product_id'] = $productid;
                Coupon::where('id', $recordInfo->id)->update($serialisedData);
                Session::flash('success_message', "Coupons details updated successfully.");
                return Redirect::to('admin/coupons');
            //}           
        }   
      return view('admin.coupons.edit', ['title'=>$pageTitle, $activetab=>1, 'recordInfo'=>$recordInfo,'product_name'=>$product_name]);
    }
    
    public function activate($slug=null){
        if($slug){
            Coupon::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.admin.update_status', ['action'=>'admin/coupons/deactivate/' . $slug, 'status'=>1]);
        }
    }
    public function deactivate($slug=null){ 
        if($slug){
            Coupon::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action'=>'admin/coupons/activate/' . $slug, 'status'=>0]);
        }
    }
    
    public function delete($slug=null){
        if($slug){
            Coupon::where('slug', $slug)->delete();
            Session::flash('success_message', "Coupon details deleted successfully.");
            return Redirect::to('admin/coupons');
        }
    } 

    public function deleteimage($slug=null){
        if($slug){
            Deal::where('slug', $slug)->update(array('service_image' => ''));
            Session::flash('success_message', "Image deleted successfully.");
            return Redirect::to('admin/deals/edit/'.$slug);
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
            $propInfo = DB::table('deals')->where('slug', $slug)->first();
            $imagesArray = explode(',', $propInfo->images);
            $imageKey = array_search($imagename, $imagesArray);
            unset($imagesArray[$imageKey]);
            if ($imagename) {
                @unlink(DEAL_FULL_UPLOAD_PATH . $imagename);
                @unlink(DEAL_SMALL_UPLOAD_PATH . $imagename);
            }
            $impldeName = implode(',', $imagesArray);
            Deal::where('slug', $slug)->update(array('images' => $impldeName));
            Session::flash('success_message', "Deal image deleted successfully.");
            return Redirect::to('admin/deals/edit/'.$slug);
        }
    }
     public function deletevideoedit($slug = null, $videoname = null) {
             if ($slug) {
            $propInfo = DB::table('deals')->where('slug', $slug)->first();
            $videosArray = explode(',', $propInfo->videos);
            $videoKey = array_search($videoname, $videosArray);
            unset($videosArray[$videoKey]);
            if ($videoname) {
                @unlink(DEALVIDEO_FULL_UPLOAD_PATH . $videoname);
                @unlink(DEALVIDEO_SMALL_UPLOAD_PATH . $videoname);
            }
            $impldeName = implode(',', $videosArray);
            Deal::where('slug', $slug)->update(array('videos' => $impldeName));
            Session::flash('success_message', "Deal video deleted successfully.");
            return Redirect::to('admin/deals/edit/'.$slug);
        }
     }
    
}
?>