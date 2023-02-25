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

class DealsController extends Controller {

    public function __construct() {
        $this->middleware('userlogedin', ['only' => ['listing']]);
        $this->middleware('is_userlogin', ['except' => ['search','detail','setLocation']]);
    }

	public function search(Request $request, $city_slug = null, $slug = null) { //echo $slug; exit;
        $pageTitle = 'Product Category';
        $page = 1;
		
		if($city_slug){
			$selected_city = DB::table('cities')->where(['slug' => $city_slug, 'status' => 1])->first();
			if(!$selected_city){
				$selected_city = DB::table('cities')->where(['status' => 1])->first();
				return Redirect::to('/'.$selected_city->slug.'/'.$slug);
			}
		}else{
			$selected_city = DB::table('cities')->where(['status' => 1])->first();
			return Redirect::to('/'.$selected_city->slug.'/'.$slug);
		}
		Cookie::queue('cookie_city_id', $selected_city->id, time() + 60 * 60 * 24 * 7, "/");
		Cookie::queue('cookie_city_slug', $selected_city->slug, time() + 60 * 60 * 24 * 7, "/");
		Cookie::queue('cookie_city_name', $selected_city->name, time() + 60 * 60 * 24 * 7, "/");
		Session::put('session_city_id', $selected_city->id);
		Session::put('session_city_slug', $selected_city->slug);
		Session::put('session_city_name', $selected_city->name);
        
		 //echo '<pre>'; print_r($merchants); exit;
        $query = new Merchant();
		$query = $query->sortable()->with('currentDeal');
        $query = $query->where('status', 1);
	    
		$query = $query->where('city_id', $selected_city->id);
		
        $categories = array();
		$navcategories = array();
        if ($slug) {
			
			$navcategories = DB::table('categories')->where(['slug' => $slug, 'status' => 1])->first();
			if(!empty($navcategories)){
				$business_type_id = $navcategories->id;
				//$category_id = $navcategories->id;
				$query = $query->where('business_type', $business_type_id);
			}else{
				return Redirect::to('/'.$city_slug);
			}
			
			//$merchants = Merchant::with('currentDeal')->where([
			//'business_type' =>$business_type_id ,
			//'status' => 1
			//])->orderBy('busineess_name', 'ASC')->get();
			
        }
        //$tast = $request->get('search');
        //echo $tast; exit;
        //$input = Input::all();
		//echo "<pre>"; print_r($request->all()); die();
		$requestData        =   $request->all();
		
		if(isset($requestData['search'])){ 
			$search_keyword = $request->get('search');
			$avl_location = DB::table('locations')->where('location_name', 'like', '%'.$search_keyword.'%')->select('id')->get();
			//print_r($avl_location); exit;
			if(!empty($avl_location)){
			$query = $query->whereIn('location_id', $avl_location['id'])->orWhere('category_id', $requestData['parent_id']);
			}
			//print_r($query); exit;
		}
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
        $merchants = $query->paginate($limit, ['*'], 'page', $page); //echo '<pre>'; print_r($products); exit;

		//echo "<pre>"; print_r($merchants);exit;
        if ($request->ajax()) {
			//echo '<pre>'; print_r($products); exit;
            return view('elements.deals.search', ['title' => $pageTitle, 'products' => $products, 'categories' => $categories, 'navcategories' => $navcategories,'merchants'=>$merchants]);
        }
        return view('deals.search', ['title' => $pageTitle, 'navcategories' => $navcategories,'merchants'=>$merchants]);
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
			return view('deals.ordersummary', ['title' => $pageTitle, 'recordInfo' => $recordInfo, 'orders' => $orders, 'offerinfo' => $offerinfo]);
		}
    }

	public function generateorder(Request $request, $slug = null, $offerinfo = null) {//exit;
	
		$input = Input::all();
        if (!empty($input)) {
			if(isset($input['couponcode'])){
				//code here
			}
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
				
				$user_id = Session::get('user_id');
				$merchant_id = $recordInfo->id;
				$order_number = bin2hex(openssl_random_pseudo_bytes(6));;
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
				$amount = $total_price;	
				$order_status = 0;
				$status = 0;
				$slug = $this->createSlug($order_number.$user_id.$merchant_id, 'orders');
				
						
				$serialisedData = $this->serialiseFormData();	
				$serialisedData['user_id'] = $user_id;
				$serialisedData['merchant_id'] = $merchant_id;
				$serialisedData['order_number'] = $order_number;
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
				//echo '<pre>';print_r($serialisedData);exit;
				Order::insert($serialisedData);	
				return Redirect('/payments/paywithpaypal/'.$slug);
			}
			Session::flash('error_message', "something went wrong!");
			return Redirect::to('/login');

        }else{
			//echo "2"; exit;
			Session::flash('error_message', "something went wrong!");
			return Redirect::to('/login');
		}
    }
	public function setLocation(Request $request){
		//print_r($_REQUEST);exit;
		
		if ($request->ajax()) {
			if ($request->has('city_id')) {
				$city_id = $request->get('city_id');
			} else {
				$city_id = '';
			}
			Cookie::queue('city_id', $city_id, time() + 60 * 60 * 24 * 7, "/");
			echo Cookie::get('city_id');;
		}exit;
		//print_r(Cookie::get('city_id'));
	}

}



?>
