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
use App\Models\Service;
use App\Models\Order;
use App\Models\Deal;
use App\Models\Merchant;
use App\Models\Payment;
use App\Models\Admin;
use App\Models\User;
use App\Models\Notification;
use App\Models\Setting;
use Mail;
use App\Mail\SendMailable;

class PaymentsController extends Controller {    
	public function __construct() {
		$this->middleware('is_adminlogin');
	}

	public function index(Request $request)
	{
		$pageTitle = 'Manage Payments'; 
        $activetab = 'actpayments';
        $query = new Payment();
        $query = $query->sortable();
        
        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                Payment::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                Payment::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                Payment::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            } 
        }
        
        if ($request->has('keyword')) { 
            $keyword = $request->get('keyword');
            $query = $query->where(function($q) use ($keyword){
                $q->where('order_number', 'like', '%'.$keyword.'%')
                ->orWhere('transaction_id', 'like', '%'.$keyword.'%')
			   ;
            });
        }
        
        $payments = $query->orderBy('id','DESC')->paginate(20);
		if($request->ajax()){
			return view('elements.admin.payments.index', ['allrecords'=>$payments]);
		}
		return view('admin.payments.index', ['title'=>$pageTitle, $activetab=>1,'allrecords'=>$payments]);
	}

	public function details($slug) {
        $recordInfo = Payment::where('slug', $slug)->first();
		
        if (!$recordInfo) {
           return Redirect::to('dashboard');
        }
		$activetab = 'actpayments';
		$dealidarr = explode(',',$recordInfo->deals_id);
		$deals = DB::table('deals')->whereIn('id', $dealidarr)->orderBy('id', 'ASC')->pluck('deal_name', 'id')->all();
		//echo "<pre>";//$deals = json_decode(json_encode($deals),true);
		//print_r($recordInfo);exit;
        $pageTitle = 'Payment Details';
//
//        $skillsList = DB::table('skills')->where('status', 1)->orderBy('name', 'ASC')->pluck('name', 'id')->all();
//        $countryLists = DB::table('countries')->where('status', 1)->orderBy('name', 'ASC')->pluck('name', 'name')->all();
//        $qualificationsLists = DB::table('qualifications')->where('status', 1)->orderBy('name', 'ASC')->pluck('name', 'name')->all();
//        $mygigs = Gig::where(['status' => 1, 'user_id' => $recordInfo->id])->orderBy('id', 'DESC')->limit(9)->get();
//        $myreviews = Review::where(['status' => 1, 'user_id' => $recordInfo->id])->orderBy('id', 'DESC')->limit(10)->get();
//        $mysavegigs = $this->getSavedGigs();
//
//        $date1 = date('Y-m-d', strtotime("-30 days"));
//        $sellingPayments = DB::table('mypayments')
//                ->select('seller_id', 'id', DB::raw('sum(total_amount) as total_sum'))
//                ->where('seller_id', '=', Session::get('user_id'))
//                ->where('created_at', '>=', $date1)
//                ->get();
//
//        $topRatedInfo = DB::table('reviews')->where(['otheruser_id' => Session::get('user_id')])->where('rating', '>', 4)->pluck(DB::raw('count(*) as total'), 'id')->all();

        return view('admin.payments.details', [$activetab=>1, 'title' => $pageTitle,'single_order'=>$recordInfo,'deals'=>$deals, 'mypayments' => 'active']);
    }
	
	
}
?>