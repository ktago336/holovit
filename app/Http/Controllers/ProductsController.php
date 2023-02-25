<?php

namespace App\Http\Controllers;

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
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Notification;

class ProductsController extends Controller {

    public function __construct() {
        $this->middleware('userlogedin', ['only' => ['listing']]);
        $this->middleware('is_userlogin', ['except' => ['search']]);
    }

    public function index(Request $request) {
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
            $query = $query->where(function($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        $users = $query->orderBy('id', 'DESC')->paginate(5);

//        echo '<pre>';
//        print_r($users);exit;
        if ($request->ajax()) {
            return view('elements.products.index', ['allrecords' => $users]);
        }
        return view('products.index', ['title' => $pageTitle, 'myproducts' => 'active', 'allrecords' => $users]);
    }

    public function add() {
        $pageTitle = 'Add Product';
        $activetab = 'actproducts';
        $brand = DB::table('brands')->where(['status' => 1])->orderBy('brand_name', 'ASC')->pluck('brand_name', 'id');
        $location = DB::table('locations')->where(['status' => 1])->orderBy('location_name', 'ASC')->pluck('location_name', 'id');
        $category = DB::table('categories')->where(['parent_id' => 0, 'status' => 1])->orderBy('category_name', 'ASC')->pluck('category_name', 'id');
        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'name' => 'required',
                'description' => 'required',
                'price' => 'required',
                'images' => 'required|mimes:jpeg,png,jpg',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/products/add')->withErrors($validator)->withInput();
            } else {

                if (isset($input['subcategory_id'])) {
                    $subcategory = $input['subcategory_id'];
                } else {
                    $subcategory = '';
                }
                if (isset($input['subsubcategory_id'])) {
                    $subsubcategory = $input['subsubcategory_id'];
                } else {
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
                } else {
                    $imagesArray = '';
                }
                if (!isset($input['videos'])) {
                    $videoArray = array();
                    $videoArray = '';
                } else {
                    $videoArray = array();
                    $vfiles = Input::file('videos');
                    if (count($vfiles) > 0) {
                        foreach ($vfiles as $vfile) {
                            if ($vfile) {
                                $uploadedFileName = $this->uploadImage($vfile, PRODUCTVIDEO_FULL_UPLOAD_PATH);
                                //$this->resizeImage($uploadedFileName, PRODUCTVIDEO_FULL_UPLOAD_PATH, PRODUCTVIDEO_SMALL_UPLOAD_PATH, PRODUCTVIDEO_MW, PRODUCTVIDEO_MH);
                                $videoArray[] = $uploadedFileName;
                            }
                        }
                        $videoArray = implode(',', $videoArray);
                        //print_r($videoArray); exit;
                    } else {
                        $videoArray = '';
                    }
                }
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug($input['name'], 'products');
                $serialisedData['images'] = $imagesArray;
                $serialisedData['videos'] = $videoArray;
                $serialisedData['subcategory_id'] = $subcategory;
                $serialisedData['subsubcategory_id'] = $subsubcategory;
                $serialisedData['status'] = 0;
                $serialisedData['merchant_id'] = Session::get('user_id');
                Product::insert($serialisedData);
                Session::flash('success_message', "Product details saved successfully.");
                return Redirect::to('products');
            }
        }
        return view('products.add', ['title' => $pageTitle, 'myproducts' => 'active', $activetab => 1, 'category' => $category, 'brand' => $brand, 'location' => $location]);
    }

    public function edit($slug = null) {
        $pageTitle = 'Edit Products';
        $activetab = 'actproducts';
        $brand = DB::table('brands')->where(['status' => 1])->orderBy('brand_name', 'ASC')->pluck('brand_name', 'id');
        $location = DB::table('locations')->where(['status' => 1])->orderBy('location_name', 'ASC')->pluck('location_name', 'id');
        $category = DB::table('categories')->where(['parent_id' => 0, 'status' => 1])->orderBy('category_name', 'ASC')->pluck('category_name', 'id');

        $recordInfo = Product::where('slug', $slug)->first();
        if (empty($recordInfo)) {
            return Redirect::to('products');
        }
        $subcategory = DB::table('categories')->where(['parent_id' => $recordInfo->category_id, 'status' => 1])->orderBy('category_name', 'ASC')->pluck('category_name', 'id');
        $subsubcategory = DB::table('categories')->where(['parent_id' => $recordInfo->subcategory_id, 'status' => 1])->orderBy('category_name', 'ASC')->pluck('category_name', 'id');

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'name' => 'required',
                'description' => 'required',
                'price' => 'required',
//                'images' => 'required|mimes:jpeg,png,jpg',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return Redirect::to('/products/edit/' . $slug)->withErrors($validator)->withInput();
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
                                $uploadedFileName = $this->uploadImage($file, PRODUCT_FULL_UPLOAD_PATH);
                                $this->resizeImage($uploadedFileName, PRODUCT_FULL_UPLOAD_PATH, PRODUCT_SMALL_UPLOAD_PATH, PRODUCT_MW, PRODUCT_MH);
                                $imagesArray[] = $uploadedFileName;
                                @unlink(PRODUCT_FULL_UPLOAD_PATH . $recordInfo->images);
                                @unlink(PRODUCT_SMALL_UPLOAD_PATH . $recordInfo->images);
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

                // edit video
                $video = explode(',', $recordInfo['videos']);
                $videosArray = array();
                if ($video) {
                    $videosArray = array_filter($video);
                }
                if (Input::hasFile('videos')) {
                    $files = Input::file('videos');
                    //print_r(count($files));exit;
                    if (count($files) > 0) {
                        foreach ($files as $file) {
                            if ($file) {
                                $uploadedFileName = $this->uploadImage($file, PRODUCTVIDEO_FULL_UPLOAD_PATH);
                                //$this->resizeImage($uploadedFileName, PRODUCTVIDEO_FULL_UPLOAD_PATH, PRODUCTVIDEO_SMALL_UPLOAD_PATH, PRODUCTVIDEO_MW, PRODUCTVIDEO_MH);
                                $videosArray[] = $uploadedFileName;
                                @unlink(PRODUCTVIDEO_FULL_UPLOAD_PATH . $recordInfo->videos);
                                //@unlink(PRODUCTVIDEO_SMALL_UPLOAD_PATH . $recordInfo->videos);
                            }
                        }
                        $videosArray = implode(',', $videosArray);
                    } else {
                        $videosArray = '';
                    }
                } else {
                    if ($videosArray) {
                        $videosArray = implode(',', $videosArray);
                    } else {
                        $videosArray = '';
                    }
                }

//                echo "<pre>"; print_r($input);exit;
                $serialisedData = $this->serialiseFormData($input); //send 1 for edit
                $serialisedData['videos'] = $videosArray;
                $serialisedData['images'] = $imagesArray;
                Product::where('id', $recordInfo->id)->update($serialisedData);
                //echo "<pre>"; print_r($serialisedData);exit;
                Session::flash('success_message', "Products details updated successfully.");
                return Redirect::to('products/edit/' . $slug);
            }
        }
        return view('products.edit', ['title' => $pageTitle, 'myproducts' => 'active', 'recordInfo' => $recordInfo, 'category' => $category, 'brand' => $brand, 'location' => $location, 'subcategory' => $subcategory, 'subsubcategory' => $subsubcategory]);
    }

    public function activate($slug = null) {
        if ($slug) {
            Product::where('slug', $slug)->update(array('status' => '1'));
            return view('elements.update_status', ['action' => 'products/deactivate/' . $slug, 'status' => 1]);
        }
    }

    public function deactivate($slug = null) {
        if ($slug) {
            Product::where('slug', $slug)->update(array('status' => '0'));
            return view('elements.update_status', ['action' => 'products/activate/' . $slug, 'status' => 0]);
        }
    }

    public function delete($slug = null) {
        if ($slug) {
            Product::where('slug', $slug)->delete();
            Session::flash('success_message', "Product details deleted successfully.");
            return Redirect::to('products');
        }
    }

    public function deleteimageedit($slug = null, $imagename = null) {
        if ($slug) {
            $propInfo = DB::table('products')->where('slug', $slug)->first();
            $imagesArray = explode(',', $propInfo->images);
            $imageKey = array_search($imagename, $imagesArray);
            unset($imagesArray[$imageKey]);
            if ($imagename) {
                @unlink(DEAL_FULL_UPLOAD_PATH . $imagename);
                @unlink(DEAL_SMALL_UPLOAD_PATH . $imagename);
            }
            $impldeName = implode(',', $imagesArray);
            Product::where('slug', $slug)->update(array('images' => $impldeName));
            Session::flash('success_message', "Product image deleted successfully.");
            return Redirect::to('products/edit/' . $slug);
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

//    public function category(){ 
//        $pageTitle = 'Product Category';
//        $recordInfo = DB::table('categories')->where(['parent_id' => 0, 'status' => 1])->orderBy('category_name', 'ASC')->get();
//
//        return view('products.category', ['title' => $pageTitle,'recordInfo' => $recordInfo,]);
//    }

    public function search(Request $request, $slug = null) {
        $pageTitle = 'Product Category';
        $page = 1;
        $navcategories = DB::table('categories')->where(['slug' => $slug, 'status' => 1])->orderBy('category_name', 'ASC')->get();
        $query = new Product();
		$query = $query->sortable();
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
       // $tast = $request->get('subcategory_id');
        //echo $tast; exit;
        //$input = Input::all();
		//echo "<pre>"; print_r($request->all()); die();
		$requestData        =   $request->all();
		
        if (isset($requestData['subcategory_id']) && $requestData['subcategory_id'] > 0 ) { //echo "vcbvcb"; exit;
            $query = $query->where(['subcategory_id' => $requestData['subcategory_id'], 
			'category_id' => $requestData['parent_id']
			]);
            //print_r($query); exit;
        }
		
	
		if (isset($requestData['subsubcat_id']) && $requestData['subsubcat_id'] > 0 ) {	
            $query =   $query->where( ['subsubcategory_id' => $requestData['subsubcat_id']] );
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
			//echo '<pre>'; print_r($products); exit;
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

                $product = \DB::table("deals")
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
