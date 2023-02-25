<?php

namespace App\Http\Controllers\Merchant;;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route; 
use App\Url;
use Cookie;
use Session;
use Redirect;
use Input;
use Validator;
use DB;
use Mail;
use App\Mail\SendMailable;
use Socialite;
use App\Models\Deal;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\Amenitie;


use App\Http\Requests\LoginRequest;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class DealsController extends Controller {

    public function __construct() {
        //$this->middleware('userlogedin', ['only' => ['listing','index']]);
        //$this->middleware('is_userlogin', ['except' => ['search']]);
		
    }

    public function index(Request $request) { 
	    $pageTitle = 'Manage Deals';
        $activetab = 'actdeals';
        $query = new Deal();
        $query = $query->sortable();
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
		$user_id = Auth::guard('merchant')->user()->id;
        $merchant = $query->where(['merchant_id'=>$user_id])->orderBy('id', 'DESC')->paginate(5);

        if ($request->ajax()) {
            return view('elements.merchant.deals.index',['merchant'=>$merchant]);
        }
        return view('merchant.deals.index', ['title' => $pageTitle, 'mydeals' => 'active','merchant'=>$merchant]);
    }

    public function add() { //echo "sdfd"; exit;
        $pageTitle = 'Add Deal';
        $activetab = 'actdeals';
        $amenitie = DB::table('amenities')->where(['status' => 1])->orderBy('amenitie_name', 'ASC')->pluck('amenitie_name', 'id');
        $location = DB::table('locations')->where(['status' => 1])->orderBy('location_name', 'ASC')->pluck('location_name', 'id');
        $category = DB::table('categories')->where(['parent_id' => 0, 'status' => 1])->orderBy('category_name', 'ASC')->pluck('category_name', 'id');
        $product_name = DB::table('products')->where(['status' => 1, 'merchant_id' => Session::get('user_id')])->orderBy('name', 'ASC')->pluck('name', 'id');
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                 'deal_name' => 'required',
                //'description' => 'required',
                //'images' => 'required',
            );
            $productid = array();
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('merchant/deals/add')->withErrors($validator)->withInput();
            } else {

                $imagesArray = array();
                $files = Input::file('images');
                if ($files && count($files) > 0) {
                    foreach ($files as $file) {
                        if ($file) {
                            $uploadedFileName = $this->uploadImage($file, DEAL_FULL_UPLOAD_PATH);
                            //$this->resizeImage($uploadedFileName, DEAL_FULL_UPLOAD_PATH, DEAL_SMALL_UPLOAD_PATH, DEAL_MW, DEAL_MH);
                            $imagesArray[] = $uploadedFileName;
                        }
                    }
                    $imagesArray = implode(',', $imagesArray);
                } else {
                    $imagesArray = '';
                }
                if (!isset($input['type'])) {
                    $input['type'] = 1;
                }
                $receiptType = $input['type'];
                switch ($receiptType) {
                    case 1:
                        $productid='';
                    case 0:
                        if (isset($input['product_id']) && $input['product_id'] != '') {
                            $productid = implode(',', array_filter($input['product_id']));
                        }
                }
				$amenitieid = '';
			if(isset($input['amenitie_id']) && $input['amenitie_id'] != ''){
                $amenitieid = implode(',',$input['amenitie_id']);
           } 
//print_r($amenitieid); exit;
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['deal_name'], 'deals');
                //$serialisedData['merchant_id'] = Session::get('user_id');
                $serialisedData['images'] = $imagesArray;
                $serialisedData['product_id'] = $productid;
				$serialisedData['amenitie_id'] = $amenitieid;
				$serialisedData['final_price'] = $input['voucher_price']-($input['voucher_price']*$input['discount']/100);
                $serialisedData['status'] = 1;
                Deal::insert($serialisedData);
                Session::flash('success_message', "Deal details saved successfully.");
                return Redirect::to('merchant/deals');
            }
        }
        return view('merchant.deals.add', ['title' => $pageTitle, 'mydeals' => 'active', 'product_name' => $product_name,'amenitie' => $amenitie]);
    }

    public function edit($slug = null) {
        $pageTitle = 'Edit Deals';
        $activetab = 'actdeals';
		 $amenitie = DB::table('amenities')->where(['status' => 1])->orderBy('amenitie_name', 'ASC')->pluck('amenitie_name', 'id');
        $product_name = DB::table('products')->where(['status' => 1, 'merchant_id' => Session::get('user_id')])->orderBy('name', 'ASC')->pluck('name', 'id');
        $recordInfo = Deal::where('slug', $slug)->first();
		//echo "<pre>"; print_r($recordInfo);exit;
        if (empty($recordInfo)) {
            return Redirect::to('deals');
        }
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'deal_name' => 'required',
                //'description' => 'required',
//                'images' => 'required|mimes:jpeg,png,jpg',
            );
			//echo "<pre>"; print_r($input);exit;
            $productid = array();
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/merchant/deals/edit/' . $slug)->withErrors($validator)->withInput();
            } else {
                $image = explode(',', $recordInfo['images']);
                $imagesArray = array();
                if ($image) {
                    $imagesArray = array_filter($image);
                }
                if (Input::hasFile('images')) {
                    $files = Input::file('images');
                    if (count($files) > 0) {
                        foreach ($files as $file) {
                            if ($file) {
                                $uploadedFileName = $this->uploadImage($file, DEAL_FULL_UPLOAD_PATH);
                                //$this->resizeImage($uploadedFileName, DEAL_FULL_UPLOAD_PATH, DEAL_SMALL_UPLOAD_PATH, DEAL_MW, DEAL_MH);
                                $imagesArray[] = $uploadedFileName;
                                @unlink(DEAL_FULL_UPLOAD_PATH . $recordInfo->images);
                                @unlink(DEAL_SMALL_UPLOAD_PATH . $recordInfo->images);
                            }
                        }
                        $imagesArray = implode(',', $imagesArray);
                    } else {
                        $imagesArray = '';
                    }
                } else {
                    if ($imagesArray) {
                        $imagesArray = implode(',', $imagesArray);
                    } else {
                        $imagesArray = '';
                    }
                }
                if (!isset($input['type'])) {
                    $input['type'] = 1;
                }
                $receiptType = $input['type'];
                switch ($receiptType) {
                    case 1:
                        $productid = '';
                        break;
                    case 2:
                        if (isset($input['product_id']) && $input['product_id'] != '') {
                            $productid = implode(',', array_filter($input['product_id']));
                        }else{
                            $productid = '';
                        }
                }
                $amenitieid = '';
				if(isset($input['amenitie_id']) && $input['amenitie_id'] != ''){
                $amenitieid = implode(',',array_filter($input['amenitie_id']));
           } 
//                echo "<pre>"; print_r($input);exit;
                $serialisedData = $this->serialiseFormData($input, 1); //send 1 for edit 
                $serialisedData['images'] = $imagesArray;
                $serialisedData['product_id'] = $productid;
				$serialisedData['amenitie_id'] = $amenitieid;
				$serialisedData['final_price'] = $input['voucher_price']-($input['voucher_price']*$input['discount']/100);
                Deal::where('id', $recordInfo->id)->update($serialisedData);
                //echo "<pre>"; print_r($serialisedData);exit;
                Session::flash('success_message', "Deals details updated successfully.");
                return Redirect::to('merchant/deals');
            }
        }
        return view('merchant.deals.edit', ['title' => $pageTitle, 'mydeals' => 'active', 'recordInfo' => $recordInfo, 'product_name' => $product_name,'amenitie' => $amenitie]);
    }
    
    public function activate($slug=null){
        if($slug){
            Deal::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.merchant.update_status', ['action'=>'merchant/deals/deactivate/'. $slug, 'status'=>1]);
        }
    }
    
    public function deactivate($slug=null){ 
        if($slug){
            Deal::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.merchant.update_status', ['action'=>'merchant/deals/activate/'. $slug, 'status'=>0]);
        }
    }
    
    public function delete($slug=null){
        if($slug){
            Deal::where('slug', $slug)->delete();
            Session::flash('success_message', "Deal details deleted successfully.");
            return Redirect::to('merchant/deals');
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
            return Redirect::to('merchant/deals/edit/'.$slug);
        }
    }

    public function listing($slug = null, Request $request) {
        $pageTitle = 'Our Product';
        $activetab = 'actusers';
        $query = new Product();
        $query = $query->sortable();

        // $query1 = new Appointment();
        // $query1 = $query1->sortable();

        $query2 = new Service();
        $query2 = $query2->sortable();

        $services = Service::where('status', 1)->get()->toArray();
        $servicesIdName = array();
        foreach ($services as $service) {
            $servicesIdName[$service['id']] = $service['name'];
        }

        if ($request->get('keyword') != '' && $request->get('keyword1') != '') {
            $keyword = $request->get('keyword');
            $service = $request->get('keyword1');
            $keywords = $query2->where('name', 'LIKE', '%' . $service . '%')->first();
            if (!$keywords) {
                $keyword1 = '';
            } else {
                $keyword1 = $keywords->id;
            }
            //print_r($keyword1);
            $query = $query->where(function($q) use ($keyword, $keyword1) {
                $q->where('first_name', 'like', '%' . $keyword . '%')
                        ->where('service_ids', 'like', '%' . $keyword1 . '%');
            });
        } else {
            if ($request->get('keyword') != '' && $request->get('keyword1') == '') {
                $keyword = $request->get('keyword');
                $query = $query->where(function($q) use ($keyword) {
                    $q->where('first_name', 'like', '%' . $keyword . '%')
                            ->orWhere('last_name', 'like', '%' . $keyword . '%');
                });
            }
            if ($request->get('keyword') == '' && $request->get('keyword1') != '') {
                $service = $request->get('keyword1');
                $keywords = $query2->where('name', 'LIKE', '%' . $service . '%')->first();
                //print_r(count($keywords));
                if (!$keywords) {
                    $keyword1 = '0';
                } else {
                    $keyword1 = $keywords->id;
                }
                $query = $query->where(function($q) use ($keyword1) {
                    $q->where('service_ids', 'like', '%' . $keyword1 . '%');
                });
            }
        }

        if ($slug == null) {
            $experts = $query->where('type', 'staff')->where('status', '1')->orderBy('id', 'DESC')->paginate(12);
        } else {
            $staff = Admin::select(DB::raw("CONCAT(',',service_ids,',') AS service,id"))->having('service', 'like', '%,' . $slug . ',%')->get()->toArray();
            $sids = array_column($staff, 'id');
            $experts = $query->whereIn('id', $sids)->where('type', 'staff')->where('status', '1')->orderBy('id', 'DESC')->paginate(12);
        }


        $servicesName = array();
        foreach ($experts as $expert) {
            $sids = explode(',', $expert->service_ids);
            $sname = "";
            foreach ($sids as $sid) {
                if (isset($servicesIdName[$sid])) {
                    $sname = ($sname == "") ? $sname . $servicesIdName[$sid] : $sname . "," . $servicesIdName[$sid];
                }
            }
            $expert->service_names = $sname;
        }
        if ($request->ajax()) {
            return view('homes.expertlist', ['experts' => $experts]);
        }
        return view('homes.experts', ['title' => $pageTitle, $activetab => 1, 'experts' => $experts, 'slug' => $slug]);
    }

    public function search(Request $request, $slug = null) {
        $pageTitle = 'Product Category';
        $page = 1;
        $navcategories = DB::table('categories')->where(['slug' => $slug, 'status' => 1])->orderBy('category_name', 'ASC')->get();
        $query = new Product();
        $query = $query->where('status', 1);
        $categories = array();
        if ($slug) {
            $categories = DB::table('categories')->where(['slug' => $slug, 'status' => 1])->first();
            if (empty($categories)) {
                return Redirect::to('/products/search');
            } else {
                $category_id = $categories->id;
                $query = $query->where('category_id', $categories->id);
            }
        }
        $tast = $request->get('subcategory_id');
        //echo $tast; exit;
        //$input = Input::all();
        if ($request->has('subcategory_id') && $request->get('subcategory_id') > 0) {
            $query = $query->where(['subcategory_id' => $request->get('subcategory_id'), 'category_id' => $request->get('parent_id'),
                'subsubcategory_id' => $request->get('subsubcat_id')]);
            //print_r($query); exit;
        }
        //print_r($query); exit;
        if ($request->has('page')) {
            $page = $request->get('page');
        } else {
            $page = 1;
        }

        $limit = 16;
        $products = $query->paginate($limit, ['*'], 'page', $page); //echo '<pre>'; print_r($products); exit;


        if ($request->ajax()) {
            return view('elements.products.search', ['title' => $pageTitle, 'products' => $products, 'categories' => $categories, 'navcategories' => $navcategories]);
        }
        return view('products.search', ['title' => $pageTitle, 'products' => $products, 'categories' => $categories, 'navcategories' => $navcategories]);
    }

    public function searchsubcats(Request $request) {
        $var = $request->get('slug');
        $subcat = $request->get('subcat');
        $parent_id = $request->get('parent_id');
        // echo $var.'/'.$subcat.'/'.$parent_id; exit;
        $products = DB::table('products')->where(['category_id' => $parent_id, 'subcategory_id' => $subcat, 'subsubcategory_id' => $var, 'status' => 1])->orderBy('name', 'ASC')->get();
        echo '<div class="row" >';
        if (!empty($products)) {
            foreach ($products as $product) {
                echo '<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 fl-column">';
                echo '<a class="card-main card-main--equal-height cursor-pointer" same-height="" href="">';
                echo '<div class="card-main__content card-main__content--lg">';

                echo '<div class="fl-row fl-row--gutter">';
                echo '<div class="fl-column">';
                echo '<h2 class="card-main__heading">';
                echo $product->name;
                $location = DB::table('locations')->where(['id' => $product->location_id, 'status' => 1])->first();
                echo'<span class="card-main__value m_locality line-height-xs display-inline-block font-weight-regular display-block margin-top-xs">';
                echo $location->location_name;
                echo '</span>';

                echo '</h2>  </div>';

                $is_set_image = 0;
                $image = explode(',', $product->images);

                foreach ($image as $key => $images) {
                    echo ' <div class="fl-column">';
//                                                {{HTML::image(PRODUCT_SMALL_DISPLAY_PATH.$images, SITE_TITLE,['style'=>"max-width: 90px; max-height:54px;",'class'=>"card-main__img"])}}
                    echo '</div>';

                    $is_set_image = 1;
                    break;
                }

                ' </div>';

                $deal = \DB::table("deals")
                        ->select("deals.*")
                        ->where(['status' => 1])
                        ->whereRaw("find_in_set($product->id, product_id)")
                        ->first();

                if (!empty($deal)) {
                    echo '<div class="card-list margin-top-s">
                                         <div class="card-main">
                                                
                                               <span class="tag tag--delight tag--small txt-uppercase bg-delight-1">Deals</span>
                                                <span class="card-main__value txt-primary ellipsis line-height-primary font-weight-semibold ellipsis--sm">{{$deal->deal_name}}</span>
                                                <span class="card-main__value txt-primary line-height-primary font-weight-semibold"> from
                                                  <i class="fa fa-inr"></i>';
                    echo $deal->final_price;
                    echo'</span>';


                    echo '</div>
                                        </div>';
                } else {
                    '<div class="card-list margin-top-s">
                                            <div class="card-main">
                                                
                                                <span class="tag tag--delight tag--small txt-uppercase bg-delight-1">No Deal</span>
                                               <span class="card-main__value txt-primary ellipsis line-height-primary font-weight-semibold ellipsis--sm">Deals Not available for this product</span>
                                                <span class="card-main__value txt-primary line-height-primary font-weight-semibold"> from
                                                 <i class="fa fa-inr"></i>';
                    echo $product->price;
                    echo '</span>
                                                
                                                
                                            </div>
                                        </div>
     } 
                                   </div>
                                    <div class="card-main__footer">
                                        
                                        <div class="section content-footer">
                                            <div class="">
                                                
                                                <span class="card-main__field line-height-xs font-weight-semibold">Services - </span>
                                                <span class="card-main__value line-height-xs font-weight-semibold ellipsis">
                                                    Hair Color, Hair Spa, Hair Wash, Hair Styling, Keratin, Rebonding, Smoothening, Bleach, Cleanup, De-Tan, Facial, Scrub, Threading, Shave, Beard Trim, Pedicure, Polish, Manicure, Waxing, Bridal Package, Makeup, Dress Draping, Skin Treatment
                                                </span>
                                                
                                            </div>
                                        </div>
                                        <div class="section bg-primary content-footer border-radius--bottom">
                                            <div class="fl-row fl-row--middle">
                                                <div class="fl-column">
                                                    <p class="card-main__field line-height-xs font-weight-semibold txt-secondary bought-count">415 Bought</p>
                                                </div>
                                                <div class="fl-column">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                           </div>';
                }
            }
        }
        echo'</div>';
    }
    
    

//    public function searchsubcat(Request $request,$slug=null){  
//        $var =  $request->get('slug'); 
//        $subcat = $request->get('subcat');
//        $parent_id = $request->get('parent_id');
//        $categories = DB::table('categories')->where(['slug' => $slug, 'status' => 1])->orderBy('category_name', 'ASC')->get();
//       // echo $var.'/'.$subcat.'/'.$parent_id; exit;
//        $products = DB::table('products')->where(['category_id'=>$parent_id,'subcategory_id'=>$subcat,'subsubcategory_id' => $var, 'status' => 1])->orderBy('name', 'ASC')->get(); 
//        
//        //echo '<pre>';print_r($products); exit;
//   if($request->ajax()){ 
//              
//        return view('elements.products.search',['products'=>$products]);
//    }
//     return view('products.search',['title'=>$pageTitle,'categories'=>$categories]);
//   
//}
}

?>
