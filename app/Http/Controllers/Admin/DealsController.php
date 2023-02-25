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
use App\Models\Deal;
use Mail;
use App\Mail\SendMailable;
//use App\Models\Appointment;
//use App\Models\Category;

class DealsController extends Controller {    
    public function __construct() {
        $this->middleware('is_adminlogin');
    }
    
    public function index(Request $request){
        $pageTitle = 'Manage Deals'; 
        $activetab = 'actdeals';
        $query = new Deal();
        $query = $query->sortable();
		
        $amenitie = DB::table('amenities')->where(['status' => 1])->orderBy('amenitie_name', 'ASC')->pluck('amenitie_name', 'id');
        
        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                Deal::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                Deal::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                Deal::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            } 
        }
        
        if ($request->has('keyword')) { 
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword){
                $q->where('deal_name', 'like', '%'.$keyword.'%');
                
            });
        }
        
        $users = $query->orderBy('id','DESC')->paginate(20);

//        echo '<pre>';
//        print_r($users);exit;
        if($request->ajax()){
            return view('elements.admin.deals.index',['allrecords'=>$users,'amenitie' => $amenitie]);
        }
       return view('admin.deals.index',['title'=>$pageTitle, $activetab=>1,'allrecords'=>$users,'amenitie' => $amenitie]);
    }

    public function add(){ 
        $pageTitle = 'Add Deal'; 
        $activetab = 'actdeals';
        $merchant = DB::table('users')->where(['status'=>1,'user_type'=>'merchant'])->orderBy('store_name', 'ASC')->pluck('store_name','id');
        $brand = DB::table('brands')->where(['status'=>1])->orderBy('brand_name', 'ASC')->pluck('brand_name','id');
        $location = DB::table('locations')->where(['status'=>1])->orderBy('location_name', 'ASC')->pluck('location_name','id');
        $category = DB::table('categories')->where(['parent_id'=> 0,'status'=>1])->orderBy('category_name', 'ASC')->pluck('category_name','id');
        $product_name = DB::table('products')->where(['status'=>1])->orderBy('name', 'ASC')->pluck('name','id'); 
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'name' => 'required0',
                'description' => 'required',
                'price' => 'required',
                'images' => 'required',
            );
            $productid = array();
            $validator = Validator::make($input, $rules);             
             if ($validator->fails()) {
                 return Redirect::to('/admin/deals/add')->withErrors($validator)->withInput();
            

                //if (Input::hasFile('images')) {
             
               
                 } else { 
          
                    $imagesArray = array();
                    $files = Input::file('images');
                     if ($files && count($files) > 0) {
                          foreach ($files as $file) {
                         if ($file) {     
                    $uploadedFileName = $this->uploadImage($file, DEAL_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, DEAL_FULL_UPLOAD_PATH, DEAL_SMALL_UPLOAD_PATH, DEAL_MW, DEAL_MH);
                    $imagesArray[] = $uploadedFileName;
                          }
                          }
                    $imagesArray = implode(',', $imagesArray);
                }else{
                    $imagesArray = '';
                } 
                 if (!isset($input['status']))
                {  
                    $input['status'] = '';
                }
                          $receiptType = $input['status'];
             switch ($receiptType) { 
                case 1: 
                 $productlist =  DB::table('products')->where(['status'=>1])->orderBy('name', 'ASC')->pluck('name','id');
                 foreach($productlist as $key=> $productlists){
                     $productkey = $key;
                     $productid[] = $productkey;
                 }
                  $productid = implode(',', $productid);
                 break;
                
                 //echo $productid; exit;
                 case 2:
                      if(isset($input['product_id']) && $input['product_id'] != ''){
               $productid = implode(',',$input['product_id']);
           } 
           
                      }
             
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['deal_name'], 'deals');
                $serialisedData['images'] = $imagesArray;
                $serialisedData['product_id'] = $productid;
                $serialisedData['status'] =  0;
               // echo '<pre>';
                //print_r($input);
               // print_r($serialisedData);
               // exit;
                Deal::insert($serialisedData); 
                
                Session::flash('success_message', "Deal details saved successfully.");
                return Redirect::to('admin/deals');
            //}           
       
        }}
        return view('admin.deals.add', ['title'=>$pageTitle, $activetab=>1,'merchant'=>$merchant,'product_name'=>$product_name]);
     
    }
    
    public function edit($slug=null){
        $pageTitle = 'Edit Deals'; 
        $activetab = 'actdeals';
        $merchant = DB::table('users')->where(['status'=>1,'user_type'=>'merchant'])->orderBy('store_name', 'ASC')->pluck('store_name','id');
        $product_name = DB::table('products')->where(['status'=>1])->orderBy('name', 'ASC')->pluck('name','id');
        
        $recordInfo = Deal::where('slug', $slug)->first();
        if (empty($recordInfo)) {
            return Redirect::to('admin/deals');
        }
       //echo '<pre>'; print_r($subcategory); exit;
        $input = Input::all();
        //echo '<pre>'; print_r($input); exit;
        if (!empty($input)) {
            $rules = array(
                'name' => 'required0',
                'description' => 'required',
                'price' => 'required',
//                'images' => 'required|mimes:jpeg,png,jpg',
               
            );
              $productid = array();
             $validator = Validator::make($input, $rules);             
             if ($validator->fails()) {
                 return Redirect::to('/admin/deals/edit/'.$slug)->withErrors($validator)->withInput();
             } else {
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
                    $uploadedFileName = $this->uploadImage($file, DEAL_FULL_UPLOAD_PATH);
                    $this->resizeImage($uploadedFileName, DEAL_FULL_UPLOAD_PATH, DEAL_SMALL_UPLOAD_PATH, DEAL_MW, DEAL_MH);
                    $imagesArray[] = $uploadedFileName;
                    @unlink(DEAL_FULL_UPLOAD_PATH.$recordInfo->images);
                    @unlink(DEAL_SMALL_UPLOAD_PATH.$recordInfo->images);
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
                     if (!isset($input['status']))
                {  
                    $input['status'] = '';
                }
                          $receiptType = $input['status'];
             switch ($receiptType) { 
                case 1: 
                 $productlist =  DB::table('products')->where(['status'=>1])->orderBy('name', 'ASC')->pluck('name','id');
                 foreach($productlist as $key=> $productlists){
                     $productkey = $key;
                     $productid[] = $productkey;
                 }
                  $productid = implode(',', $productid);
                 break;
                
                 //echo $productid; exit;
                 case 2:
                      if(isset($input['product_id']) && $input['product_id'] != ''){
               $productid = implode(',',$input['product_id']);
           } 
           
                      }
                
                $serialisedData = $this->serialiseFormData($input); //send 1 for edit
                $serialisedData['images'] = $imagesArray;
                $serialisedData['product_id'] = $productid;
                Deal::where('id', $recordInfo->id)->update($serialisedData);
                Session::flash('success_message', "Deals details updated successfully.");
                return Redirect::to('admin/deals');
            //}           
        }  } 
      return view('admin.deals.edit', ['title'=>$pageTitle, $activetab=>1, 'recordInfo'=>$recordInfo,'merchant'=>$merchant,'product_name'=>$product_name]);
    }
    
    public function activate($slug=null){
        if($slug){
            Deal::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.admin.update_status', ['action'=>'admin/deals/deactivate/' . $slug, 'status'=>1]);
        }
    }
    public function deactivate($slug=null){ 
        if($slug){
            Deal::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.admin.update_status', ['action'=>'admin/deals/activate/' . $slug, 'status'=>0]);
        }
    }
    
    public function delete($slug=null){
        if($slug){
            Deal::where('slug', $slug)->delete();
            Session::flash('success_message', "Deal details deleted successfully.");
            return Redirect::to('admin/deals');
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
     
     public function getallproduct(){
          $product = DB::table('products')->where(['status'=>1])->orderBy('name', 'ASC')->pluck('name','id');
//           if(!empty($product)){
//               echo '<input type="checkbox" class="" ';
//           }
     }
    
}
?>