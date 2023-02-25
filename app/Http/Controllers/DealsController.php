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
use App\Models\Order;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\Merchant;
use App\Models\Deal;
use App\Models\User;
use App\Models\Wallet;

class DealsController extends Controller {

    public function __construct() {
        $this->middleware('userlogedin', ['only' => ['listing']]);
        $this->middleware('is_userlogin', ['except' => ['search','detail','setLocation']]);
    }

	public function search(Request $request, $slug = null, $area = null) { //echo $slug; exit;
        $pageTitle = 'Merchant List';
        $page = 1;
        if(Session::has('session_city_id')){
			$city_id = Session::get('session_city_id');
			$selected_city = DB::table('cities')->where(['id' => $city_id, 'status' => 1])->first();
			if(!$selected_city){
				$selected_city = DB::table('cities')->where(['status' => 1])->first();
				//return Redirect::to('/'.$selected_city->slug.'/'.$slug);
			}
		}else{
			$selected_city = DB::table('cities')->where(['status' => 1])->first();
			//return Redirect::to('/'.$selected_city->slug.'/'.$slug);
		}
		
		$query = new Merchant();
		$query = $query->sortable()->with('currentDeal');
        $search_keyword = '';
		$order_type = 'new';
		$business_type_id = '';
		$sub_category_id_arr = '';
		
		$active_category_id_arr = DB::table('categories')->where(['parent_id' => 0, 'status' => 1])->select(['id','id'])->get();
		$active_category_id_arr = array_filter(json_decode(json_encode($active_category_id_arr),true));
		
		if ($slug) {
			
			$navcategories = DB::table('categories')->where(['slug' => $slug, 'status' => 1])->first();
			if(!empty($navcategories)){
				
				//$category_id = $navcategories->id;
				if($navcategories->parent_id > 0){
					$sub_category_id = $navcategories->id;
					$business_type_id = $navcategories->parent_id;
					$query = $query->whereRaw("FIND_IN_SET($sub_category_id, service_ids)");
					$sub_category_id_arr = DB::table('categories')->where(['parent_id' => $navcategories->parent_id, 'status' => 1])->select(['parent_id', 'id','slug','category_name'])->get();
					$sub_category_id_arr = array_filter(json_decode(json_encode($sub_category_id_arr),true));
					//echo "<pre>"; print_r($sub_category_id_arr); die();
				}else{
					$business_type_id = $navcategories->id;
					$query = $query->where('business_type', $business_type_id);
					$sub_category_id_arr = DB::table('categories')->where(['parent_id' => $navcategories->id, 'status' => 1])->select('parent_id', 'id','slug','category_name')->get();
					$sub_category_id_arr = array_filter(json_decode(json_encode($sub_category_id_arr),true));
					
				}
				
				
					
				//$query = $query->where(function($q) use ($keyword) {
				//	$q->where('first_name', 'like', '%' . $keyword . '%')
				//			->orWhere('last_name', 'like', '%' . $keyword . '%')
				//			->orWhere('email_address', 'like', '%' . $keyword . '%');
				//});
			}else{
				return Redirect::to('/deals/search');
			}
			
			//$merchants = Merchant::with('currentDeal')->where([
			//'business_type' =>$business_type_id ,
			//'status' => 1
			//])->orderBy('busineess_name', 'ASC')->get();
			
        }else{
            
            $query = $query->whereIn('business_type', $active_category_id_arr);
			
		}
		
		
		
			
			
		
		
		
		
        
		 //echo '<pre>'; print_r($merchants); exit;
        $query = $query->where('merchants.status', 1);
	    $query = $query->where('merchants.city_id', $selected_city->id);
        $query->join('deals', 'deals.merchant_id', '=', 'merchants.id')
			->where('deals.status', 1)
			->whereDate('expire_date', '>=', date('Y-m-d'))
			->groupBy('merchants.id')
			->select('deals.id','deals.final_price','merchants.*');
			
			
		$navcategories = array();
        
        //$tast = $request->get('search');
        //echo $tast; exit;
        //$input = Input::all();
		//echo "<pre>"; print_r($request->all()); die();
		$requestData        =   $request->all();
		
		if(isset($requestData['keyword'])){ 
			$search_keyword = $request->get('keyword');
			//$avl_location = DB::table('locations')->where('location_name', 'like', '%'.$search_keyword.'%')->select('id')->get();
			//print_r($avl_location); exit;
			//if(!empty($avl_location)){
			//$query = $query->whereIn('location_id', $avl_location['id'])->orWhere('category_id', $requestData['parent_id']);
			//}
			//print_r($query); exit;
			
			$query = $query->where(function($q) use ($search_keyword) {
				$q->where('busineess_name', 'like', '%' . $search_keyword . '%');
			});
		}
		
		
		
		
		
		
		//search menus for categories
		//$merchants_locality_ids = DB::table('merchants')->where(['status' => 1, 'city_id'=>$selected_city->id])->groupBy('locality_id')->orderBy('locality_id', 'ASC')->pluck('locality_id', 'count(id)');
		$menuquery1 = Merchant::with('currentDeal')->whereHas('currentDeal', function ($query22) {
    $query22->where('status', '=', 1);
});			
		$menuquery2 = Merchant::with('currentDeal')->whereHas('currentDeal', function ($query21) {
    $query21->where('status', '=', 1);
});
		$menuquery3 = Merchant::with('currentDeal')->whereHas('currentDeal', function ($query23) {
    $query23->where('status', '=', 1);
});
		if($search_keyword){
			//echo $search_keyword;exit;
			$menuquery1 = $menuquery1->where('busineess_name', 'like', '%' . $search_keyword . '%');
			$menuquery2 = $menuquery2->where('busineess_name', 'like', '%' . $search_keyword . '%');
			$menuquery3 = $menuquery3->where('busineess_name', 'like', '%' . $search_keyword . '%');
		}
		
		
		
		if(isset($sub_category_id)){
			//echo $sub_category_id;
			//$merchants_category_ids = $menuquery1->where(['status' => 1, 'city_id'=>$selected_city->id])->whereRaw("FIND_IN_SET($sub_category_id, service_ids)")->select('business_type', DB::raw('count(*) as total'))->groupBy('business_type')->orderBy('total', 'DESC')->pluck('total', 'business_type');
		}elseif(isset($business_type_id)){
			//$merchants_category_ids = $menuquery1->where(['status' => 1, 'business_type'=>$business_type_id, 'city_id'=>$selected_city->id])->select('business_type', DB::raw('count(*) as total'))->groupBy('business_type')->orderBy('total', 'DESC')->pluck('total', 'business_type');
		}else{
			//$merchants_category_ids = $menuquery1->where(['status' => 1, 'city_id'=>$selected_city->id])->select('business_type', DB::raw('count(*) as total'))->groupBy('business_type')->orderBy('total', 'DESC')->pluck('total', 'business_type');
		}
		$subcategory_merchants = array();
		$subcategory_arr = array();
		if((isset($sub_category_id) && $sub_category_id>0) || (isset($business_type_id) && $business_type_id>0)){
			//print_r($sub_category_id_arr);exit;
			foreach($sub_category_id_arr as $sub_cat){
				//echo $sub_cat_id;
				$sub_cat_id = $sub_cat['id'];
				$subcat_mers = array();
				//$menuquery3 = $menuquery1;
				$subcat_mers = array_filter(json_decode(json_encode($menuquery3->where(['status' => 1, 'city_id'=>$selected_city->id])->whereRaw("FIND_IN_SET($sub_cat_id, service_ids)")->select('business_type', DB::raw('count(*) as total'))->groupBy('business_type')->orderBy('total', 'DESC')->pluck('total')),true));
				if($subcat_mers){
					$subcategory_merchants[$sub_cat_id] = $subcat_mers[0];
				}
				
				$subcategory_arr[$sub_cat_id] = $sub_cat;
			}
		}
		//echo "<pre>"; print_r($subcategory_arr);exit;
		//exit;
		
		$merchants_category_ids = $menuquery1->where(['status' => 1, 'city_id'=>$selected_city->id])->select('business_type', DB::raw('count(*) as total'))->groupBy('business_type')->orderBy('total', 'DESC')->pluck('total', 'business_type');
		$category_merchants = array_filter(json_decode(json_encode($merchants_category_ids),true));
		//echo "<pre>"; print_r($subcategory_merchants);exit;
		
		//if($business_type_id || $sub_category_id){
			
			
		//	$sub_category_merchants = array_filter(json_decode(json_encode($merchants_category_ids),true));
		//}
		//print_r($category_merchants);exit;
		$categories_obj = DB::table('categories')->where(['status' => 1])->whereIn('id',array_keys($category_merchants))->select('id','category_name','slug')->orderBy('category_name', 'ASC')->get();
		$cate_arr = array_filter(json_decode(json_encode($categories_obj),true)); 
		$categories = array();
		foreach($cate_arr as $catkey=>$catvals){
			$categories[$catvals['id']] = $catvals;
		}
		//echo '<pre>'; print_r($categories); exit;
		//search menus for locality
		//$merchants_locality_ids = $menuquery->where(['status' => 1, 'city_id'=>$selected_city->id])->groupBy('locality_id')->orderBy('locality_id', 'ASC')->pluck('locality_id', 'count(id)');
		if(isset($sub_category_id)){
			$merchants_locality_ids = $menuquery2->where(['status' => 1, 'city_id'=>$selected_city->id])->whereRaw("FIND_IN_SET($sub_category_id,service_ids)")->select('locality_id', DB::raw('count(*) as total'))->groupBy('locality_id')->orderBy('total', 'DESC')->pluck('total', 'locality_id');
		}elseif(isset($business_type_id)){
			$merchants_locality_ids = $menuquery2->where(['status' => 1, 'business_type'=>$business_type_id, 'city_id'=>$selected_city->id])->select('locality_id', DB::raw('count(*) as total'))->groupBy('locality_id')->orderBy('total', 'DESC')->pluck('total', 'locality_id');
		}else{
			$merchants_locality_ids = $menuquery2->where(['status' => 1, 'city_id'=>$selected_city->id])->select('locality_id', DB::raw('count(*) as total'))->groupBy('locality_id')->orderBy('total', 'DESC')->pluck('total', 'locality_id');
		}
		$locality_merchants = array_filter(json_decode(json_encode($merchants_locality_ids),true));
		$localities_obj = DB::table('localities')->where(['status' => 1])->whereIn('id',array_keys($locality_merchants))->orderBy('locality_name', 'ASC')->pluck('locality_name', 'id');
		$localities = array_filter(json_decode(json_encode($localities_obj),true)); 
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		if(isset($requestData['locality_ids'])){ 
			$search_localityids = $request->get('locality_ids');
			//print_r($search_localityids);exit;
			//$avl_location = DB::table('locations')->where('location_name', 'like', '%'.$search_keyword.'%')->select('id')->get();
			//print_r($avl_location); exit;
			//if(!empty($avl_location)){
			$query = $query->whereIn('locality_id', $search_localityids);
			//}
			//print_r($query); exit;
			
			
		}
		
		if(isset($requestData['locality_ids'])){ 
			$search_localityids = $request->get('locality_ids');
			//print_r($search_localityids);exit;
			//$avl_location = DB::table('locations')->where('location_name', 'like', '%'.$search_keyword.'%')->select('id')->get();
			//print_r($avl_location); exit;
			//if(!empty($avl_location)){
			$query = $query->whereIn('locality_id', $search_localityids);
			//}
			//print_r($query); exit;
			
			
		}
		if(isset($requestData['order_type'])){ 
			$order_type = $request->get('order_type');
			//print_r($search_localityids);exit;
			//$avl_location = DB::table('locations')->where('location_name', 'like', '%'.$search_keyword.'%')->select('id')->get();
			//print_r($avl_location); exit;
			//if(!empty($avl_location)){
			//$query = $query->whereIn('locality_id', $search_localityids);
			//}
			//print_r($query); exit;
			
			
		}
        //if (isset($requestData['subcategory_id']) && $requestData['subcategory_id'] > 0 ) { //echo "vcbvcb"; exit;
        //    $query = $query->where(['subcategory_id' => $requestData['subcategory_id'], 
		//	'category_id' => $requestData['parent_id']
		//	]);
        //    //print_r($query); exit;
        //}
		
	
		//if (isset($requestData['subsubcat_id']) && $requestData['subsubcat_id'] > 0 ) {	
        //    $query =   $query->where( ['subsubcategory_id' => $requestData['subsubcat_id']] );
		//}
        //print_r($query); exit;
        if ($request->has('page')) {
            $page = $request->get('page');
        } else {
            $page = 1;
        }
		if($order_type == 'nearme'){
			$query->orderBy('id', 'DESC');
		}elseif($order_type == 'lth'){
			
            $query->orderBy('deals.final_price', 'ASC');
		}elseif($order_type == 'htl'){
			$query->orderBy('deals.final_price', 'DESC');
		}elseif($order_type == 'popular'){
			/*$query->leftJoin('orders', 'orders.merchant_id', '=', 'merchants.id')
			//->whereRaw('ostatus', 1)
			//->whereDate('expire_date', '>=', date('Y-m-d'))
            ->orderBy('total', 'DESC')
			->groupBy('merchants.id')
			->select('orders.*','merchants.*')
			->addSelect(DB::raw("COUNT(`tbl_orders`.`id`) AS `total`, `tbl_orders`.`status` as `ostatus`"))->whereRaw('ostatus', 1);
			*/
			$query->orderBy('total_orders', 'DESC');			
		}else{
			$query->orderBy('deals.id', 'DESC');
			
			
		}
        $limit = 16;
		
        $merchants = $query->paginate($limit, ['*'], 'page', $page); //echo '<pre>'; print_r($products); exit;
		
		//echo "<pre>"; print_r($merchants);
		
		
		//exit;
		
		
		//echo "<pre>"; print_r($merchants);exit;
        if ($request->ajax()) {
			//echo '<pre>'; print_r($merchants); exit;
            return view('elements.deals.search', ['page_name' => "search", 'title' => $pageTitle, 'navcategories' => $navcategories,'merchants'=>$merchants, 'categories' => $categories, 'category_merchants' => $category_merchants,'locality_merchants'=>$locality_merchants, 'localities'=>$localities,'selected_city'=>$selected_city, 'slug'=>$slug, 'search_keyword'=>$search_keyword, 'order_type'=>$order_type, 'subcategory_arr'=>$subcategory_arr, 'subcategory_merchants'=>$subcategory_merchants, 'business_type_id'=>$business_type_id]);
        }
        return view('deals.search', ['page_name' => "search", 'title' => $pageTitle, 'navcategories' => $navcategories,'merchants'=>$merchants, 'categories' => $categories, 'category_merchants' => $category_merchants,'locality_merchants'=>$locality_merchants, 'localities'=>$localities,'selected_city'=>$selected_city, 'slug'=>$slug, 'search_keyword'=>$search_keyword, 'order_type'=>$order_type, 'subcategory_arr'=>$subcategory_arr, 'subcategory_merchants'=>$subcategory_merchants, 'business_type_id'=>$business_type_id]);
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

	public function detail(Request $request, $slug = null) {
        
        $pageTitle = __('message.View Merchant Deals');

        $recordInfo = Merchant::where('slug', $slug)->first();

        //$gigCount = DB::table('myorders')->where('gig_id', $recordInfo->id)->where('status', 1)->count();

//        echo '<pre>';print_r($recordInfo);exit;
        if (empty($recordInfo)) {
            return Redirect::to('deals/search');
        }
        $userInfo = array();
        if (isset($recordInfo->User->slug)) {
            $userInfo = User::where('slug', $recordInfo->User->slug)->first();
        }

        $pageTitle = $recordInfo->busineess_name;

        //$query = new Review();
        //$query = $query->with('Myorder');
        //$query = $query->where('status', 1);

        $merchant_id = $recordInfo->id;
        /*$query = $query->whereHas('Myorder', function($q) use ($gig_id) {
            $q->where('gig_id', $gig_id)->where('as_a', 'seller');
        });

        $gigreviews = $query->orderBy('id', 'DESC')->limit(10)->get();

        $date1 = date('Y-m-d', strtotime("-30 days"));
        $sellingOrders = DB::table('myorders')
                ->select('seller_id', 'id', DB::raw('sum(total_amount) as total_sum'))
                ->where('seller_id', '=', Session::get('user_id'))
                ->where('created_at', '>=', $date1)
                ->get();

        $topRatedInfo = DB::table('reviews')->where(['otheruser_id' => Session::get('user_id')])->where('rating', '>', 4)->pluck(DB::raw('count(*) as total'), 'id')->all();
*/

        return view('deals.detail', ['title' => $pageTitle, 'recordInfo' => $recordInfo]);
    }

	public function ordersummary(Request $request, $slug = null, $offerinfo = null) {
       
        $recordInfo = Merchant::where('slug', $slug)->first();
		
		$user_id = Session::get('user_id');

        $userInfo = User::where('id', $user_id)->first();
		
        if (empty($recordInfo) || !$offerinfo) {
            return Redirect::to('deals/search');
        }
		$is_error = 1;
		$offerarr = explode('-',$offerinfo);
		if($offerarr){
			foreach($offerarr as $dealinfo){
				if($dealinfo){
					$dealinfo = explode('_',$dealinfo);
					if(isset($dealinfo[0]) && isset($dealinfo[1])){
						$deal_id = $dealinfo[0];
						$qty = $dealinfo[1];
						$dealInfo = Deal::where(['id'=>$deal_id, 'merchant_id'=>$recordInfo->id, 'status'=>1])->first();
						if($dealInfo && $qty > 0){
							$orders[$dealInfo->id] = $qty;
							$is_error = 0;
						}else{
							$is_error = 2;
						}
					}else{
						$is_error = 3;
					}
				}else{
					$is_error = 4;
				}
			}
		}else{
			$is_error = 5;
		}
		if($is_error >= 1){ //echo $is_error; exit;
			return Redirect::to('/deals/search');
		}else{//exit;
			//continue
			$pageTitle = 'Order Summary: '.$recordInfo->busineess_name;
			return view('deals.ordersummary', ['title' => $pageTitle, 'userInfo' => $userInfo, 'recordInfo' => $recordInfo, 'orders' => $orders, 'offerinfo' => $offerinfo]);
		}
    }

	public function generateorder(Request $request, $slug = null, $offerinfo = null) {//exit;
	
		$input = Input::all();
        if (!empty($input)) {
			if(isset($input['couponcode'])){
				//code here
			}
			//echo "<pre>"; print_r($input);exit;
			
			$user_id = Session::get('user_id');
			$userInfo = User::where('id', $user_id)->first();
			
			$recordInfo = Merchant::where('slug', $slug)->first();
			
			if (empty($recordInfo) || !$offerinfo) {
				return Redirect::to('deals/search');
			}
			
			$order_number = '';
			$deals_id_arry = array();	
			$deals_quantity_arr = array();
			$deals_base_price_arr = array();
			$deals_final_price_arr = array();
			$convenience_fees = 0;	
			$coupon_id = 0;
			$coupon_discount = 0;
			$coupon_discount_price = 0;	
			$order_summary_url = '';
			$total_price = 0;
			$amount = 0;	
			$order_status = 0;
			$status = 0;
			$slug = '';	
			$sub_total = 0;
			
			$is_error = 1;
			$offerarr = explode('-',$offerinfo);
			if($offerarr){
				foreach($offerarr as $dealinfo){
					if($dealinfo){
						$dealinfo = explode('_',$dealinfo);
						if(isset($dealinfo[0]) && isset($dealinfo[1])){
							$deal_id = $dealinfo[0];
							$qty = $dealinfo[1];
							$dealInfo = Deal::where(['id'=>$deal_id, 'merchant_id'=>$recordInfo->id, 'status'=>1])->first();
							if($dealInfo && $qty > 0){
								$orders[$dealInfo->id] = $qty;
								$deals_id_arry[] = $dealInfo->id;
								$deals_quantity_arr[] = $qty;
								$deals_base_price_arr[] = $dealInfo->voucher_price;
								$deals_final_price_arr[] = $dealInfo->final_price;
								$sub_total = $sub_total+$dealInfo->final_price*$qty;
								$is_error = 0;
							}else{
								$is_error = 1;
							}
						}else{
							$is_error = 1;
						}
					}else{
						$is_error = 1;
					}
				}
			}else{
				$is_error = 1;
			}
			if($is_error == 1){
				return Redirect::to('/deals/search');
			}else{
				
				
				$merchant_id = $recordInfo->id;
				$order_number = rand(111,999).$merchant_id.$user_id.rand(111,999);
				$voucher_number = "LSGOV".time().$user_id.$merchant_id;
				$deals_id = implode(',',$deals_id_arry);	
				$deals_quantity = implode(',',$deals_quantity_arr);
				$deals_base_price = implode(',',$deals_base_price_arr);
				$deals_final_price = implode(',',$deals_final_price_arr);
				$convenience_fees = $recordInfo->convenience_fees;	
				$coupon_id = 0;
				$coupon_discount = 0;
				$coupon_discount_price = 0;	
				$order_summary_url = $offerinfo;
				$total_price = $sub_total + $convenience_fees - $coupon_discount_price;
				$merchant_amount = $sub_total;
				$amount = $sub_total;	
				$order_status = 0;
				$status = 0;
				$slug = $this->createSlug($order_number.$user_id.$merchant_id, 'orders');
				
				$barc = $this->barcode( BARCODE_FULL_UPLOAD_PATH . $voucher_number.'.png', $voucher_number);
				
						
				$serialisedData = $this->serialiseFormData();	
				$serialisedData['user_id'] = $user_id;
				$serialisedData['merchant_id'] = $merchant_id;
				$serialisedData['order_number'] = $order_number;
				$serialisedData['voucher_number'] = $voucher_number;
				$serialisedData['deals_id'] = $deals_id;
				$serialisedData['deals_quantity'] = $deals_quantity;
				$serialisedData['deals_base_price'] = $deals_base_price;
				$serialisedData['deals_final_price'] = $deals_final_price;
				$serialisedData['convenience_fees'] = $convenience_fees;
				$serialisedData['coupon_id'] = $coupon_id;
				$serialisedData['coupon_discount'] = $coupon_discount;
				$serialisedData['coupon_discount_price'] = $coupon_discount_price;
				$serialisedData['order_summary_url'] = $order_summary_url;
				$serialisedData['total_price'] = $total_price;
				$serialisedData['amount'] = $amount;
				$serialisedData['order_status'] = $order_status;
				$serialisedData['status'] = $status;
				$serialisedData['slug'] = $slug;
				
				
				if($input['payment_type'] == 2){
					//groupon clone wallet
					if($userInfo->wallet_balance < $total_price){
						Session::flash('error_message', "You have unsufficient balance. Please upgrade your wallet amount!");
						return Redirect::to('/users/mywallet');
					}else{
						
						//deduct amount from user's wallet
						$updated_balance = $userInfo->wallet_balance-$total_price;
						if($updated_balance >=0){
							
							User::where('id', $userInfo->id)->update(array('wallet_balance'=>$updated_balance)); 
							
							Order::insert($serialisedData);	
							
							$orderInfo = Order::where('order_number', $order_number)->first();
							$order_id = $orderInfo->id;
							$wallet_trn_id = 'LSGPAY'.$order_number;
							$paymenttype = "Wallet";
							
							$serialisedData = array();
							$serialisedData['total_amount'] = $total_price;
							$serialisedData['amount'] = $merchant_amount;
							$serialisedData['admin_commission'] = $convenience_fees;
							$serialisedData['description'] = "Deal Payment";
							$serialisedData['add_minus'] = 0;
							$serialisedData['type'] = 0;
							$serialisedData['source'] = 'Pay via wallet';
							$serialisedData['user_id'] = $user_id;
							//$serialisedData['merchant_id'] = $merchant_id;
							$serialisedData['order_id'] = $orderInfo->id;
							$serialisedData['status'] = 1;
							$slug = $this->createSlug('pay'.$user_id.time(), 'wallets');
							$serialisedData['slug'] = $slug;
							$serialisedData['trn_id'] = $wallet_trn_id;
							Wallet::insert($serialisedData);
							$walletInfo = Wallet::where('slug', $slug)->first();
							
							
							$serialisedData = array();
							$serialisedData['user_id'] = $user_id;
							$serialisedData['merchant_id'] = $merchant_id;
							$serialisedData['wallet_id'] = $walletInfo->id;
							$slug = $this->createSlug(bin2hex(openssl_random_pseudo_bytes(30)), 'payments');
							$serialisedData['slug'] = $slug;
							$serialisedData['order_number'] = $order_number;
							$serialisedData['status'] = 1;
							$serialisedData['payment_mode'] = "Wallet";
							$serialisedData['amount'] = $total_price;
							$serialisedData['order_id'] = $orderInfo->id;
							$serialisedData['transaction_id'] = $wallet_trn_id;
							Payment::insert($serialisedData);
							

							Order::where('id', $order_id)->update(array('order_status'=>'1','status'=>'1'));    

							$total_orders = $recordInfo->total_orders;		
							Merchant::where('id', $merchant_id)->update(array('total_orders'=>$total_orders)); 
							// Email sent to login user
							$loginuser = $userInfo->first_name . ' ' . $userInfo->last_name;
							$user_email = $userInfo->email_address;
							$user_contact = $userInfo->contact;
							$amount = CURR . $total_price;
							$transactionId = $wallet_trn_id;
							$datetime = date('M d, Y');
							$title = $orderInfo->Merchant->busineess_name;
							$amountseller = $orderInfo->amount;
							
							$user_order_detail_link = HTTP_PATH.'/users/orderdetail/'.$orderInfo->slug;
							
							$email = $emailId = $userInfo->email_address;
							$emailTemplate = DB::table('emailtemplates')->where('id', 8)->first();
							$toRepArray = array('[!name!]', '[!title!]', '[!order_no!]', '[!total_price!]', '[!transactionId!]', '[!paymenttype!]', '[!link!]', '[!datetime!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
							$fromRepArray = array($loginuser, $title, $order_number, $amount, $transactionId, $paymenttype, $user_order_detail_link, $datetime, HTTP_PATH, SITE_TITLE);
							$emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
							$emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
							//Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
							

							// Email sent to admin user
							$adminInfo = DB::table('admins')->where('id', 1)->first();
							$emailId = $adminInfo->email;
							$emailTemplate = DB::table('emailtemplates')->where('id', 7)->first();
							$toRepArray = array('[!name!]', '[!title!]', '[!email!]', '[!contact!]', '[!amount!]', '[!transactionId!]', '[!paymenttype!]', '[!datetime!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
							$fromRepArray = array($loginuser, $title, $email, $user_contact, $amount, $transactionId, $paymenttype, $datetime, HTTP_PATH, SITE_TITLE);
							$emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
							$emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
							//Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

							// Email sent to seller user
							/*$merchant_order_detail_link = HTTP_PATH.'/merchants/orderdetail/'.$orderInfo->slug;
							$sellerInfo = $orderInfo->Merchant;
							$emailId = $sellerInfo->email_address;
							$merchant_name = $sellerInfo->first_name . ' ' . $sellerInfo->last_name;
							$emailTemplate = DB::table('emailtemplates')->where('id', 11)->first();
							$toRepArray = array('[!merchant_name!]', '[!name!]', '[!title!]', '[!email!]', '[!contact!]', '[!amount!]', '[!transactionId!]', '[!paymenttype!]', '[!datetime!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
							$fromRepArray = array($merchant_name, $loginuser, $title, $email, $user_contact, $amountseller, $transactionId, $paymenttype, $datetime, $merchant_order_detail_link, HTTP_PATH, SITE_TITLE);
							$emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
							$emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
							//Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));*/
							//Session::flash('success_message', "Your order has submitted succeessfully.");
							return Redirect('/thank/'.$order_number);

						}
						
						echo "wallmetn amoutn not";
						
						exit;
						
					}				
					
					//echo "Continue....<pre>"; print_r($input);exit;
				}else{
					//echo '<pre>';print_r($serialisedData);exit;
					Order::insert($serialisedData);	
					
					return Redirect('/payments/paywithpaypal/'.$slug);

				}
				
				echo "wallmetn amoutn not2";
						
						exit;
				
				
				
			}
			echo "wallmetn amoutn not4";
						
						exit;
			Session::flash('error_message', "something went wrong!");
			return Redirect::to('/deals/search');

        }else{
			//echo "2"; exit;
			Session::flash('error_message', "something went wrong!");
			return Redirect::to('/deals/search');
		}
    }
	public function setLocation(Request $request){
		//print_r($_REQUEST);exit;
		
		if ($request->post()) {
			if ($request->has('city_id') && $request->get('city_id') > 0) {
				$city_id = $request->get('city_id');
				$selected_city = DB::table('cities')->where(['id' => $city_id, 'status' => 1])->first();
			} else {
				$merchants_citie_ids = DB::table('merchants')->where(['status' => 1])->groupBy('city_id')->orderBy('city_id', 'ASC')->pluck('city_id');
				
				$cityids = array_filter(json_decode(json_encode($merchants_citie_ids),true));
				$city_id = $cityids[0];
				$selected_city = DB::table('cities')->where(['id' => $city_id, 'status' => 1])->first();
			}
			
			Cookie::queue('cookie_city_id', $selected_city->id, time() + 60 * 60 * 24 * 7, "/");
			Cookie::queue('cookie_city_slug', $selected_city->slug, time() + 60 * 60 * 24 * 7, "/");
			Cookie::queue('cookie_city_name', $selected_city->name, time() + 60 * 60 * 24 * 7, "/");
			Session::put('session_city_id', $selected_city->id);
			Session::put('session_city_slug', $selected_city->slug);
			Session::put('session_city_name', $selected_city->name);
			
			return Redirect::to('/deals/search');
		}
		//print_r(Cookie::get('city_id'));
	}
	
	public function barcode( $filepath="", $text="0", $size="20", $orientation="horizontal", $code_type="code128", $print=true, $SizeFactor=1 ) {
    	$code_string = "";
    	// Translate the $text into barcode the correct $code_type
    	if ( in_array(strtolower($code_type), array("code128", "code128b")) ) {
    		$chksum = 104;
    		// Must not change order of array elements as the checksum depends on the array's key to validate final code
    		$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","\`"=>"111422","a"=>"121124","b"=>"121421","c"=>"141122","d"=>"141221","e"=>"112214","f"=>"112412","g"=>"122114","h"=>"122411","i"=>"142112","j"=>"142211","k"=>"241211","l"=>"221114","m"=>"413111","n"=>"241112","o"=>"134111","p"=>"111242","q"=>"121142","r"=>"121241","s"=>"114212","t"=>"124112","u"=>"124211","v"=>"411212","w"=>"421112","x"=>"421211","y"=>"212141","z"=>"214121","{"=>"412121","|"=>"111143","}"=>"111341","~"=>"131141","DEL"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","FNC 4"=>"114131","CODE A"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
    		$code_keys = array_keys($code_array);
    		$code_values = array_flip($code_keys);
    		for ( $X = 1; $X <= strlen($text); $X++ ) {
    			$activeKey = substr( $text, ($X-1), 1);
    			$code_string .= $code_array[$activeKey];
    			$chksum=($chksum + ($code_values[$activeKey] * $X));
    		}
    		$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
    
    		$code_string = "211214" . $code_string . "2331112";
    	} elseif ( strtolower($code_type) == "code128a" ) {
    		$chksum = 103;
    		$text = strtoupper($text); // Code 128A doesn't support lower case
    		// Must not change order of array elements as the checksum depends on the array's key to validate final code
    		$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","NUL"=>"111422","SOH"=>"121124","STX"=>"121421","ETX"=>"141122","EOT"=>"141221","ENQ"=>"112214","ACK"=>"112412","BEL"=>"122114","BS"=>"122411","HT"=>"142112","LF"=>"142211","VT"=>"241211","FF"=>"221114","CR"=>"413111","SO"=>"241112","SI"=>"134111","DLE"=>"111242","DC1"=>"121142","DC2"=>"121241","DC3"=>"114212","DC4"=>"124112","NAK"=>"124211","SYN"=>"411212","ETB"=>"421112","CAN"=>"421211","EM"=>"212141","SUB"=>"214121","ESC"=>"412121","FS"=>"111143","GS"=>"111341","RS"=>"131141","US"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","CODE B"=>"114131","FNC 4"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
    		$code_keys = array_keys($code_array);
    		$code_values = array_flip($code_keys);
    		for ( $X = 1; $X <= strlen($text); $X++ ) {
    			$activeKey = substr( $text, ($X-1), 1);
    			$code_string .= $code_array[$activeKey];
    			$chksum=($chksum + ($code_values[$activeKey] * $X));
    		}
    		$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
    
    		$code_string = "211412" . $code_string . "2331112";
    	} elseif ( strtolower($code_type) == "code39" ) {
    		$code_array = array("0"=>"111221211","1"=>"211211112","2"=>"112211112","3"=>"212211111","4"=>"111221112","5"=>"211221111","6"=>"112221111","7"=>"111211212","8"=>"211211211","9"=>"112211211","A"=>"211112112","B"=>"112112112","C"=>"212112111","D"=>"111122112","E"=>"211122111","F"=>"112122111","G"=>"111112212","H"=>"211112211","I"=>"112112211","J"=>"111122211","K"=>"211111122","L"=>"112111122","M"=>"212111121","N"=>"111121122","O"=>"211121121","P"=>"112121121","Q"=>"111111222","R"=>"211111221","S"=>"112111221","T"=>"111121221","U"=>"221111112","V"=>"122111112","W"=>"222111111","X"=>"121121112","Y"=>"221121111","Z"=>"122121111","-"=>"121111212","."=>"221111211"," "=>"122111211","$"=>"121212111","/"=>"121211121","+"=>"121112121","%"=>"111212121","*"=>"121121211");
    
    		// Convert to uppercase
    		$upper_text = strtoupper($text);
    
    		for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
    			$code_string .= $code_array[substr( $upper_text, ($X-1), 1)] . "1";
    		}
    
    		$code_string = "1211212111" . $code_string . "121121211";
    	} elseif ( strtolower($code_type) == "code25" ) {
    		$code_array1 = array("1","2","3","4","5","6","7","8","9","0");
    		$code_array2 = array("3-1-1-1-3","1-3-1-1-3","3-3-1-1-1","1-1-3-1-3","3-1-3-1-1","1-3-3-1-1","1-1-1-3-3","3-1-1-3-1","1-3-1-3-1","1-1-3-3-1");
    
    		for ( $X = 1; $X <= strlen($text); $X++ ) {
    			for ( $Y = 0; $Y < count($code_array1); $Y++ ) {
    				if ( substr($text, ($X-1), 1) == $code_array1[$Y] )
    					$temp[$X] = $code_array2[$Y];
    			}
    		}
    
    		for ( $X=1; $X<=strlen($text); $X+=2 ) {
    			if ( isset($temp[$X]) && isset($temp[($X + 1)]) ) {
    				$temp1 = explode( "-", $temp[$X] );
    				$temp2 = explode( "-", $temp[($X + 1)] );
    				for ( $Y = 0; $Y < count($temp1); $Y++ )
    					$code_string .= $temp1[$Y] . $temp2[$Y];
    			}
    		}
    
    		$code_string = "1111" . $code_string . "311";
    	} elseif ( strtolower($code_type) == "codabar" ) {
    		$code_array1 = array("1","2","3","4","5","6","7","8","9","0","-","$",":","/",".","+","A","B","C","D");
    		$code_array2 = array("1111221","1112112","2211111","1121121","2111121","1211112","1211211","1221111","2112111","1111122","1112211","1122111","2111212","2121112","2121211","1121212","1122121","1212112","1112122","1112221");
    
    		// Convert to uppercase
    		$upper_text = strtoupper($text);
    
    		for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
    			for ( $Y = 0; $Y<count($code_array1); $Y++ ) {
    				if ( substr($upper_text, ($X-1), 1) == $code_array1[$Y] )
    					$code_string .= $code_array2[$Y] . "1";
    			}
    		}
    		$code_string = "11221211" . $code_string . "1122121";
    	}
    
    	// Pad the edges of the barcode
    	$code_length = 20;
    	if ($print) {
    		$text_height = 30;
    	} else {
    		$text_height = 0;
    	}
    	
    	for ( $i=1; $i <= strlen($code_string); $i++ ){
    		$code_length = $code_length + (integer)(substr($code_string,($i-1),1));
            }
    
    	if ( strtolower($orientation) == "horizontal" ) {
    		$img_width = $code_length*$SizeFactor;
    		$img_height = $size;
    	} else {
    		$img_width = $size;
    		$img_height = $code_length*$SizeFactor;
    	}
    
    	$image = imagecreate($img_width, $img_height + $text_height);
    	$black = imagecolorallocate ($image, 0, 0, 0);
    	$white = imagecolorallocate ($image, 255, 255, 255);
    
    	imagefill( $image, 0, 0, $white );
    	if ( $print ) {
    		imagestring($image, 5, 31, $img_height, $text, $black );
    	}
    
    	$location = 10;
    	for ( $position = 1 ; $position <= strlen($code_string); $position++ ) {
    		$cur_size = $location + ( substr($code_string, ($position-1), 1) );
    		if ( strtolower($orientation) == "horizontal" )
    			imagefilledrectangle( $image, $location*$SizeFactor, 0, $cur_size*$SizeFactor, $img_height, ($position % 2 == 0 ? $white : $black) );
    		else
    			imagefilledrectangle( $image, 0, $location*$SizeFactor, $img_width, $cur_size*$SizeFactor, ($position % 2 == 0 ? $white : $black) );
    		$location = $cur_size;
    	}
    	//return $image;
    	// Draw barcode to the screen or save in a file
    	if ( $filepath=="" ) {
    		header ('Content-type: image/png');
    		imagepng($image);
    		imagedestroy($image);
    	} else {
    		imagepng($image,$filepath);
    		imagedestroy($image);		
    	}
    }
    

}



?>
