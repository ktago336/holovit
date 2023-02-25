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
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Models\Merchant;
use App\Models\Payment;
use App\Models\Admin;
use App\Models\User;
use App\Models\Notification;
use App\Models\Setting;
use Mail;
use App\Mail\SendMailable;

class WalletsController extends Controller {    
	public function __construct() {
		$this->middleware('is_adminlogin');
	}
	
	public function withdrawals(Request $request, $slug = null)
	{
		$pageTitle = 'Manage Withdrawal'; 
        $activetab = 'actwithdrawals';
        $query = new Withdrawal();
        $query = $query->sortable();
		$merchantinfo = '';
		if($slug){
			$merchantinfo = Merchant::where(['slug'=>$slug])->first();
			if($merchantinfo && $merchantinfo->id > 0){
				$query = $query->where(['merchant_id'=>$merchantinfo->id]);
			}else{
				return Redirect::to('admin/wallets/withdrawals');
			}
			
		}
        
        if ($request->has('chkRecordId') && $request->has('action')) {
            $idList = $request->get('chkRecordId');
            $action = $request->get('action');
            if ($action == "Activate") {
                Withdrawal::whereIn('id', $idList)->update(array('status' => 1));
                Session::flash('success_message', "Records are activated successfully.");
            } else if ($action == "Deactivate") {
                Withdrawal::whereIn('id', $idList)->update(array('status' => 0));
                Session::flash('success_message', "Records are deactivated successfully.");
            } else if ($action == "Delete") {
                Withdrawal::whereIn('id', $idList)->delete();
                Session::flash('success_message', "Records are deleted successfully.");
            } 
        }
        
        if ($request->has('keyword')) { 
            $keyword = $request->get('keyword');
  
            $query = $query->whereHas('Merchant',function($q) use ($keyword){
                   $q->where('busineess_name', 'like', '%'.$keyword.'%');
            });
        }
        
        $withdrawals = $query->orderBy('id','DESC')->paginate(20);
		if($request->ajax()){
			return view('elements.admin.wallets.withdrawals', ['allrecords'=>$withdrawals]);
		}
		return view('admin.wallets.withdrawals', ['title'=>$pageTitle, $activetab=>1,'allrecords'=>$withdrawals,'merchantinfo'=>$merchantinfo]);
	}
	
	public function changestatus($id = null, $status= null) {
        //echo "id:$id, value=$status";exit;
        $this->layout = '';
		
		$withdrawalDetails = Withdrawal::where('id', $id)->first();
		
        Withdrawal::where('id', $id)->update(array('status' => $status));
                
        if($status == 1 || $status == 2){
            $wallettype = 10;
			if($status== 2){
				$updated_balance = $withdrawalDetails->Merchant->withdraw_amount+$withdrawalDetails->amount;
				Merchant::where('id', $withdrawalDetails->merchant_id)->update(array('withdraw_amount' => $updated_balance));
			
			}
        }elseif($status == 3){
            $updated_balance = $withdrawalDetails->Merchant->wallet_balance+$withdrawalDetails->amount;
			Merchant::where('id', $withdrawalDetails->merchant_id)->update(array('wallet_balance' => $updated_balance));
			$wallettype = 11;
        }else{
			$wallettype = 9;
        }
		Wallet::where('withdrawal_id', $id)->update(array('type' => $wallettype));
        return view('elements.admin.change_withdrawal_status', ['id' => $id, 'status' => $status]);
    }
	
	public function createrequest($slug = null) {
        $pageTitle = 'Create Withdraw Reqeust';
        $activetab = 'actwithdrawals';
        $merchant = DB::table('merchants')->where(['status'=>1])->where('wallet_balance','>=',1)->orderBy('busineess_name', 'ASC')->pluck('busineess_name','id');
        $input = Input::all();
		$merchantinfo = '';
		if($slug){
			$merchantinfo = Merchant::where(['slug'=>$slug])->first();
			if($merchantinfo && $merchantinfo->id > 0){
			}else{
				return Redirect::to('admin/wallets/withdrawals');
			}
		}
        if (!empty($input)) {
            $rules = array(
                'amount' => 'required|lte:1',
                'description' => 'required',
                'merchant_id' => 'required',
            );
            $customMessages = [
                'amount.gte' => "You can't withdraw amount more than available balance.",
                'amount.lte' => "Please enter amount more than equal to 1.",
            ];
            $validator = Validator::make($input, $rules, $customMessages);
            if ($validator->fails()) {
                return Redirect::to('/admin/wallets/createrequest/'.$slug)->withErrors($validator)->withInput();
            } else {
				
                $amount= $input['amount'];
				$merchat_id= $input['merchant_id'];
				
				$recordInfo = Merchant::where(['id'=>$merchat_id])->first();
				
				if($amount > $recordInfo->wallet_balance){
					Session::flash('error_message', "You can't withdraw amount more than available balance(".CURR.$recordInfo->wallet_balance.").");
					return Redirect::to('/admin/wallets/createrequest/'.$slug)->withInput();
				}
				
				
                $serialisedData = $this->serialiseFormData($input);
                $serialisedData['slug'] = $this->createSlug('withdraw'.$merchat_id.time(), 'withdrawals');
                $serialisedData['merchant_id'] = $merchat_id;
                $serialisedData['status'] = 1;
				
				//echo "<pre>"; print_r($serialisedData);exit;
                $withdraw_id = Withdrawal::insertGetId($serialisedData);
				
				$updated_balance = $recordInfo->wallet_balance-$amount;
                Merchant::where('id', $merchat_id)->update(array('wallet_balance' => $updated_balance));

				$serialisedData = array();
				$serialisedData['withdrawal_id'] = $withdraw_id;
				$serialisedData['merchant_id'] = $merchat_id;
                $serialisedData["total_amount"] = $amount;
                $serialisedData["amount"] = $amount;
                $serialisedData["admin_commission"] = 0;
                $serialisedData["source"] = 'Withdraw form wallet';
                $serialisedData["add_minus"] = 1;
                $serialisedData["type"] = 9;
                $serialisedData["user_id"] = 0;
                $serialisedData["status"] = 1;          
                $mslug = $this->createSlug('withdraw'.$merchat_id.time(), 'wallets');
				$serialisedData['slug'] = $mslug;
                $paymentNumber = 'WITHDRAW' . $merchat_id . time();
                $serialisedData['trn_id'] = $paymentNumber;
				Wallet::insert($serialisedData);

                Session::flash('success_message', "Withdraw request created successfully.");
                return Redirect::to('admin/wallets/withdrawals');
            }
        }
        return view('admin.wallets.createrequest', ['title' => $pageTitle, $activetab => 1, 'merchant' => $merchant,'merchantinfo'=>$merchantinfo]);
    }


	
}
?>