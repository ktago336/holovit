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
use App\Models\Appointment;
use App\Models\Admin;
use App\Models\User;
use App\Models\Notification;
use App\Models\Setting;
use Mail;
use App\Mail\SendMailable;

class RequestsController extends Controller {    
	public function __construct() {
		$this->middleware('is_adminlogin');
	}

	public function index($slug=null,Request $request, $from=null,$to=null)
	{
		$admin_id = Session::get('adminid');
		$pageTitle = 'Manage Requests'; 
		$activetab = 'actrequests';
		$query = new Appointment();
		$query = $query->sortable();
		$query2 = new Service();
		$query2 = $query2->sortable();
		$admin_id = Session::get('adminid');
        $adminRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRoles(Session::get('adminid'));
        $checkSubRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRolesSub(Session::get('adminid'));

		if ($request->has('service') && $request->get('service')!='') {
			// print_r($request->get('service'));exit;
			$keyword = $request->get('service');
			$keywords = $query2->where('name', 'LIKE', '%' . $keyword . '%')->first();
			if(!$keywords){ $service = '';}
			else{$service = $keywords->id;} 
			
			$query = $query->whereHas('Service',function($q) use ($service, $keyword){ 
				if($service!= ''){
				$q->where('service_ids', 'like', '%'.$service.'%');}
				elseif($keyword!= ''){
					$q->where('appointment_number', 'like', '%'.$keyword.'%');}

			});
		}

		if ($request->has('staff') && $request->get('staff')!='') {
			//print_r('have staff');
			$staff = $request->get('staff');
			$query = $query->whereHas('Admin',function($q) use ($staff){
				$q->where('first_name', 'like', '%'.$staff.'%');

			});
		}

		if($request->has('fromdate1') || $request->has('todate1'))
		{ 
			//print_r('in date section');
			if ($request->get('fromdate1') && $request->get('fromdate1')!=''){
				//print_r('have from');
				$from1 = $request->get('fromdate1');
				$from = date("Y-m-d H:i:s", strtotime($from1));
			}
			if ($request->get('todate1') && $request->get('todate1')!=''){
				//print_r('have to');
				$to1 = $request->get('todate1');
				$to = date("Y-m-d H:i:s", strtotime($to1.' +1 day'));
			}
			// if(){			//$to = date();
				$query = $query->where(function($q) use ($from, $to)
				{   
					//print_r('actual query');
					//print_r('from: '.$from."- to: ".$to);
					if($from!='' && $to!=''){ 
						// print_r($from."===".$to);
						$q->whereDate('booking_date_time', '>=', $from)->whereDate('booking_date_time', '<', $to);
					}else{
						if($from!=''){
							$q->whereDate('booking_date_time', '>=', $from);
						}
						if($to!=''){
							$q->whereDate('booking_date_time', '<', $to);
						}
					}


				});
			// }
			
		}



		if($slug != '' && $from == '' && $to == ''){ 
			//print_r('if no date given');
			$startdate = date('Y-m-d 00:00:00');
			$endtdate = date('Y-m-d 23:59:59');
			$range = [$startdate, $endtdate];
			$slugtable = explode("-",$slug);
			if($slugtable[0]=='staff'){
				//print_r('from dashboard with staff');
				$appointments = $query->orderBy('id','DESC')->where('staff_id',$slugtable[1]) ->whereBetween('booking_date_time', $range)->paginate(20);


			}else{
				//print_r('from dashboard with no staff');
				$appointments = $query->orderBy('id','DESC')->where(DB::RAW("FIND_IN_SET($slugtable[1], service_ids)"), '!=', '')->whereBetween('booking_date_time', $range)->paginate(20);
			}
		}else if($slug != '' && $from!= '0' && $to!= '0')
			{   
				$query3 = new Appointment();
				$query3 = $query3->sortable();
				$fromdate1 = date("Y-m-d 00:00:00", strtotime($from));
				$fromdate2 = date("Y-m-d 23:59:59", strtotime($from));
				$todate1 = date("Y-m-d 00:00:00", strtotime($to));
				$todate2 = date("Y-m-d 23:59:59", strtotime($to));

				$slugtable = explode("-",$slug);
				if($slugtable[0]=='staff'){
					$appointments = $query3->orderBy('id','DESC')->where('booking_date_time','>=',$fromdate1)->where('staff_id',$slugtable[1])->where('booking_date_time','<=',$todate2)->paginate(20);

				}
				else{
					$appointments = $query3->orderBy('id','DESC')->where(DB::RAW("FIND_IN_SET($slugtable[1], service_ids)"), '!=', '')->where('booking_date_time','>=',$fromdate1)->where('booking_date_time','<=',$todate2)->paginate(20);
				}

			}
			else if($slug!= '' && $from!= '0')
			{   
				$query1 = new Appointment();
				$query1 = $query1->sortable();
				$fromdate1 = date("Y-m-d 00:00:00", strtotime($from));
				$fromdate2 = date("Y-m-d 23:59:59", strtotime($from));
				$slugtable = explode("-",$slug);
				if($slugtable[0]=='staff'){
					$appointments =  $query1->orderBy('id','DESC')->where('staff_id',$slugtable[1])->whereDate('booking_date_time','>=',$fromdate1)->paginate(20);



				}
				else{
					$appointments = $query1->orderBy('id','DESC')->where(DB::RAW("FIND_IN_SET($slugtable[1], service_ids)"), '!=', '')->whereDate('booking_date_time','>=',$fromdate1)->paginate(20);
				}

			}
			else if($slug!= '' && $to!= '0')
			{   
				$query2 = new Appointment();
				$query2 = $query2->sortable();
				$todate1 = date("Y-m-d 00:00:00", strtotime($to));
				$todate2 = date("Y-m-d 23:59:59", strtotime($to));
				$slugtable = explode("-",$slug);
				if($slugtable[0]=='staff'){
					$appointments = $query2->orderBy('id','DESC')->where('staff_id',$slugtable[1])->whereDate('booking_date_time','<=',$todate2)->paginate(20);

				}
				else{  
					$appointments = $query2->orderBy('id','DESC')->where(DB::RAW("FIND_IN_SET($slugtable[1], service_ids)"), '!=', '')->where('booking_date_time','<=',$todate2)->paginate(20);
				}

			}
			else
			{ 
				if($admin_id != 1 && (!isset($checkSubRols[6]))){
				$appointments = $query->orderBy('created_at','DESC')->where('staff_id',$admin_id)->paginate(20);
				}
				else if($admin_id != 1 && (isset($checkSubRols[6])) && (in_array(2, $checkSubRols[6])) && (!in_array(4, $checkSubRols[6]))){ 
				$appointments = $query->orderBy('created_at','DESC')->where('staff_id',$admin_id)->paginate(20);
				}
				else if($admin_id != 1 && (in_array(2, $checkSubRols[6])) && (in_array(4, $checkSubRols[6]))){ 
				$appointments = $query->orderBy('created_at','DESC')->paginate(20);
				}
				else if($admin_id != 1 && (isset($checkSubRols[6])) && (in_array(4, $checkSubRols[6]))){ 
				$appointments = $query->orderBy('created_at','DESC')->paginate(20);
				}
				else{
					$appointments = $query->orderBy('created_at','DESC')->paginate(20);
				}

			}

			if($request->ajax()){
				return view('elements.admin.requests.index', ['appointments'=>$appointments]);
			}
			return view('admin.requests.index', ['title'=>$pageTitle, $activetab=>1,'appointments'=>$appointments]);
	}

	public function cancle($slug=null)
	{

		if($slug==null){ 
			$slug='';
		}
		$oldappointmentdata=Appointment::where('slug',$slug)->first();
		if($oldappointmentdata->user_id != 0){
		$userData = User::where('id',$oldappointmentdata->user_id)->first();
			}
		
		if(Session::get('adminid')!=1){
			if(Session::get('adminid')!=$oldappointmentdata->staff_id){
	            $moduleid=6;
	            global $staffroles;
	            global $staffsubroles;
	            $adminRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRoles(Session::get('adminid'));
	            $checkSubRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRolesSub(Session::get('adminid'));
	            if(in_array($moduleid, $adminRols)){
	                if((isset($checkSubRols[$moduleid])) && !empty($checkSubRols[$moduleid])){
	                    if(in_array(2, $checkSubRols[$moduleid])){
	                        
	                    }else{
	                    Session::flash('error_message', UNAUTHORIZED_LINK);
	                    return Redirect::to('admin/admins/dashboard');
	                    exit;
	                    }
	                }else{
	                   Session::flash('error_message', UNAUTHORIZED_LINK);
	                    return Redirect::to('admin/admins/dashboard');
	                    exit; 
	                }
	            }else{
	                 Session::flash('error_message', UNAUTHORIZED_LINK);
	                    return Redirect::to('admin/admins/dashboard');
	                    exit;
	            }
            }
        }
		Appointment::where('slug', $slug)->update(array('status' => 'Canceled'));
		if($oldappointmentdata->user_id != 0){
		$from_name = $userData->first_name;
		$user_id = $userData->id;
		$message = 'Your appointment with appoinment number '.$oldappointmentdata->appointment_number.' has been Canceled.';
		$serialisedData['from_name'] = $from_name;
		$serialisedData['user_id'] = $user_id;
		$serialisedData['message'] = $message;
		$serialisedData['status'] = 1;
		$serialisedData['created_at'] = date('Y-m-d H:i:s');
		$serialisedData['updated_at'] = date('Y-m-d H:i:s');
		$serialisedData['slug'] = $this->createSlug($from_name, 'notifications');
		$serialisedData['url'] = 'cancle/' . $serialisedData['slug'];
		//print_r($serialisedData);exit;
		Notification::insert($serialisedData); 
		}

		$settings=Setting::where('id',1)->first();
		$userdata=[];
		$staffslug='';
		$servicename='';

		if(isset($oldappointmentdata->service_ids) && $oldappointmentdata->service_ids!=''){
			$sids=explode(',',$oldappointmentdata->service_ids);
			$servicess=Service::whereIn('id', $sids)->get();
			foreach ($servicess as $ss) {
				if($servicename==''){
					$servicename=$ss->name;
				}else{
					$servicename=$servicename.", ".$ss->name;
				}
			}
		}
		$appoinmentdata=Appointment::where('slug',$slug)->first();
		if($appoinmentdata->staff_id!=1){
			if($appoinmentdata->Admin){
				$staffslug=$appoinmentdata->Admin->slug;
			}
		}

		if($appoinmentdata->user_id!=null && $appoinmentdata->user_id!=0){
			if($appoinmentdata->User){
				$userdata=$appoinmentdata->User;
			}
		}else{
			$userdata['email_address']=$appoinmentdata->guest_email;
			$userdata['first_name']=$appoinmentdata->guest_name;
		}
		if($userdata['first_name']==null || $userdata['first_name']==''){
			$userdata['first_name']='Customer';
		}
		// if($oldappointmentdata->status!=$input['changedstatus'] && $input['changedstatus']!='Pending' && $input['changedstatus']!='Visited' && $input['changedstatus']!='No show'){
			// print_r('expression1 old: '.$oldappointmentdata->status."change : ".$input['changedstatus']);

			if($settings->sms_notification == 1)
			{   global $account_sid;
				global $auth_token;
				global $sms_from;
				global $telephonecc;
				$link = HTTP_PATH . "/about";
				if($appoinmentdata->user_id == 0){
					$to = $appoinmentdata->guest_contact;
					if($to == ''){
						$to = '0';
					}
				}
				if($appoinmentdata->user_id != 0){
					 $to = $userdata->contact;
					if($to == ''){
						$to = '0';
					}
				}
				//print_r($link);exit;
				$name = ucwords($userdata['first_name']);
				$message = 'Dear ' .$name.','."\r\n". 'Unfortunately your booked appointment with appointment number '. $appoinmentdata->appointment_number.' on : '. HTTP_PATH .' for '. SITE_TITLE .' has been Canceled. You can book appointmment here '.$link;
				$account_sid = $account_sid;
				$auth_token = $auth_token;
				//print_r($telephonecc);exit;
				$to = $telephonecc.''.$to;
				//print_r($telephonecc);exit;
				$id = "$account_sid";
				$token = "$auth_token";
				$url = "https://api.twilio.com/2010-04-01/Accounts/" . $account_sid . "/Messages.json";
				$data = array(
					'From' => $sms_from,
					'To' => $to,
					'Body' => $message,
				);
				$post = http_build_query($data);
				$x = curl_init($url);
				curl_setopt($x, CURLOPT_POST, true);
				curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($x, CURLOPT_SSL_VERIFYPEER, true);
				curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
				curl_setopt($x, CURLOPT_POSTFIELDS, $post);
				$result = curl_exec($x);
				curl_close($x);
			}
			if($settings->whatsapp_notification == 1)
			{ 	global $account_sid;
				global $auth_token;
				global $whatsapp_from;
				global $telephonecc;
				$link = HTTP_PATH . "/about";
				if($appoinmentdata->user_id == 0){
					$to = $appoinmentdata->guest_contact;
					if($to == ''){
						$to = '0';
					}
				}
				if($appoinmentdata->user_id != 0){
					 $to = $userdata->contact;
					if($to == ''){
						$to = '0';
					}
				}
				$name = ucwords($userdata['first_name']);
				//print_r($link);exit;
				$message = 'Dear ' .$name.','."\r\n". 'Unfortunately your booked appointment with appointment number '. $appoinmentdata->appointment_number.' on : '. HTTP_PATH .' for '. SITE_TITLE .' has been Canceled. You can book appointmment here '.$link;
            	$account_sid = $account_sid;
            	$auth_token = $auth_token;
	            $to = $telephonecc.''.$to;
	            $id = "$account_sid";
	            $token = "$auth_token";
	            $url = "https://api.twilio.com/2010-04-01/Accounts/" . $account_sid . "/Messages.json";
	            $data = array(
	                'From' => 'whatsapp:'.$whatsapp_from,
	                'To' => 'whatsapp:'.$to,
	                'Body' => $message,
	            );

	            $post = http_build_query($data);
	            $x = curl_init($url);
	            curl_setopt($x, CURLOPT_POST, true);
	            curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
	            curl_setopt($x, CURLOPT_SSL_VERIFYPEER, true);
	            curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	            curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
	            curl_setopt($x, CURLOPT_POSTFIELDS, $post);
	            $result = curl_exec($x);
	            curl_close($x);
			}
			if($settings->email_notification==1){
				// print_r('expression2');
				if($userdata['email_address']!=null || $userdata['email_address']!=''){
					// print_r($appoinmentdata->booking_date_time);
					$date= date("l",strtotime($appoinmentdata->booking_date_time)).", ".date('d F Y H:i',strtotime($appoinmentdata->booking_date_time));
					$booking_number=$appoinmentdata->appointment_number;
					if($servicename==''){
						$servicename='our service';
					}
				    $link = HTTP_PATH . "/requestdetail/".$slug;
				    $name = ucwords($userdata['first_name']);
				    $emailId = $userdata['email_address'];
				    $template=14;
				    // if($input['changedstatus']=='Canceled'){
				    // 	$template=14;
				    // }elseif($input['changedstatus']=='Completed'){
				    // 	$template=16;
				    // }elseif($input['changedstatus']=='Confirmed'){
				    // 	$template=13;
				    // }
				    $emailTemplate = DB::table('emailtemplates')->where('id', $template)->first();
				    $toRepArray = array('[!username!]','[!booking_number!]','[!date!]','[!service!]','[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
				    $fromRepArray = array($name,$booking_number, $date,$servicename,$link, HTTP_PATH, SITE_TITLE);
				    $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
				    $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
				    // print_r("hello : ".$emailBody);exit;
				    Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

				}
			}



		// }

		Session::flash('success_message', "Appointment cancelled successfully. <a href='".HTTP_PATH."/admin/reschedule/".$slug."' class='alert-link'> Click Here To Reschedule</a>");
		return Redirect::to('admin/requests');

	}
	public function getprice(Request $request){
		$serviceid=$request->get('service');
		$selected=Service::where('id',$serviceid)->first();
		return $selected->price;
	}
	public function saveinvoice($slug=''){
		$pageTitle = 'Manage Requests'; 
		$activetab = 'actrequests';
		$input = Input::all();
// print_r($input['total_price']."-".$input['service_ids']);
		Appointment::where('slug', $slug)->update(array('service_ids'=>$input['service_ids'],'total_price' => $input['total_price'],'updated_at'=>date('Y-m-d H:i'),'status'=>'Completed'));
		Session::flash('success_message', "Invoice Updated successfully.");
		return Redirect::to('admin/invoice/'.$slug);
	}
	public function invoice($slug=null){
		$pageTitle = 'Manage Requests'; 
		$activetab = 'actrequests';
		if($slug==null){ 
			$slug='';
		}

		$appoinments=Appointment::where('slug', $slug)->first();
		if(Session::get('adminid')!=1){
			if(Session::get('adminid')!=$appoinments->staff_id){
	            $moduleid=6;
	            global $staffroles;
	            global $staffsubroles;
	            $adminRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRoles(Session::get('adminid'));
	            $checkSubRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRolesSub(Session::get('adminid'));
	            if(in_array($moduleid, $adminRols)){
	                if((!isset($checkSubRols[$moduleid])) || empty($checkSubRols[$moduleid])){
	                    Session::flash('error_message', UNAUTHORIZED_LINK);
	                    return Redirect::to('admin/admins/dashboard');
	                    exit;
	                }
	            }else{
	                 Session::flash('error_message', UNAUTHORIZED_LINK);
	                    return Redirect::to('admin/admins/dashboard');
	                    exit;
	            }
        	}    
           
        }
		$selected_ids=explode(',', $appoinments->service_ids);
		$services=Service::whereIn('id', $selected_ids)
		->get();
		// print_r($appoinments->staff_id);exit;
		if($appoinments->staff_id==1 || $appoinments->staff_id==0){
			$allservices=Service::where('status',1)->get()->toArray();
		}
		else{
			$allservices=[];
			$staffserviceidstr=$appoinments->Admin->service_ids;
			if($staffserviceidstr!=''){
				$staffserviceid=explode(',', $staffserviceidstr);
				$allservices=Service::whereIn('id', $staffserviceid)->get();
			}
			//$allservices=Service::where('status',1)->get()->toArray();
		}
		$oldservices=[];
		foreach ($services as $s) {
			array_push($oldservices, $s->name);
		}   
// print_r($allservices);
// print_r($oldservices);

//          exit;
// print_r($services);exit;
		return view('admin.requests.invoice', ['title'=>$pageTitle, $activetab=>1,'appoinmentdata'=>$appoinments,'services'=>$services,'allservices'=>$allservices,'oldservices'=>$oldservices]);
// print_r($appoinments);exit;
	}
	public function changestatus($slug=null){
		if($slug==null){ 
			$slug='';
		}
		$input = Input::all();

		$settings=Setting::where('id',1)->first();
		$admindata=Admin::where('id',1)->first();
		// print_r($input['service_ids']);exit;
		$selected_services='';

		$oldappointmentdata=Appointment::where('slug',$slug)->first();
		if($oldappointmentdata->user_id != 0){
		$userData = User::where('id',$oldappointmentdata->user_id)->first();
			}

		if(!empty($input['service_ids'])){
			$selected_services=implode(',', $input['service_ids']);
		}
		if($input['next_appointment_date']==null && $input['next_appointment_date']==''){
			Appointment::where('slug', $slug)->update(array('status' => $input['changedstatus'],'service_ids'=>$selected_services));
		}else{
			Appointment::where('slug', $slug)->update(array('status' => $input['changedstatus'],'next_appointment_date'=>$input['next_appointment_date'],'service_ids'=>$selected_services));
		}
		if(($input['changedstatus']=='Canceled') && ($oldappointmentdata->user_id != 0) ){
		$from_name = $userData->first_name;
		$user_id = $userData->id;
		$message = 'Your appointment with appoinment number '.$oldappointmentdata->appointment_number.' has been Canceled.';
		$serialisedData['from_name'] = $from_name;
		$serialisedData['user_id'] = $user_id;
		$serialisedData['message'] = $message;
		$serialisedData['status'] = 1;
		$serialisedData['created_at'] = date('Y-m-d H:i:s');
		$serialisedData['updated_at'] = date('Y-m-d H:i:s');
		$serialisedData['slug'] = $this->createSlug($from_name, 'notifications');
		$serialisedData['url'] = 'cancle/' . $serialisedData['slug'];
		//print_r($serialisedData);exit;
		Notification::insert($serialisedData); 
		}
		if(($input['changedstatus']=='Completed') && ($oldappointmentdata->user_id != 0) ){
		$from_name = $userData->first_name;
		$user_id = $userData->id;
		$message = 'Your appointment with appoinment number '.$oldappointmentdata->appointment_number.' has been Completed.';
		$serialisedData['from_name'] = $from_name;
		$serialisedData['user_id'] = $user_id;
		$serialisedData['message'] = $message;
		$serialisedData['status'] = 1;
		$serialisedData['created_at'] = date('Y-m-d H:i:s');
		$serialisedData['updated_at'] = date('Y-m-d H:i:s');
		$serialisedData['slug'] = $this->createSlug($from_name, 'notifications');
		$serialisedData['url'] = 'complete/' . $serialisedData['slug'];
		//print_r($serialisedData);exit;
		Notification::insert($serialisedData);
		}
		if(($input['changedstatus']=='Confirmed') && ($oldappointmentdata->user_id != 0) ){
		$from_name = $userData->first_name;
		$user_id = $userData->id;
		$message = 'Your appointment with appoinment number '.$oldappointmentdata->appointment_number.' has been Confirmed.';
		$serialisedData['from_name'] = $from_name;
		$serialisedData['user_id'] = $user_id;
		$serialisedData['message'] = $message;
		$serialisedData['status'] = 1;
		$serialisedData['created_at'] = date('Y-m-d H:i:s');
		$serialisedData['updated_at'] = date('Y-m-d H:i:s');
		$serialisedData['slug'] = $this->createSlug($from_name, 'notifications');
		$serialisedData['url'] = 'confirmed/' . $serialisedData['slug'];
		//print_r($serialisedData);exit;
		Notification::insert($serialisedData);
		}

		$link1='';
		if($input['changedstatus']=='Canceled'){
			$link1="<a href='".HTTP_PATH."/admin/reschedule/".$slug."' class='alert-link'> Click Here To Reschedule</a>";
		}
		// print_r($input['service_ids']);exit;
		$userdata=[];
		$staffslug='';
		$servicename='';
		if(isset($input['service_ids']) && $input['service_ids']!=''){
			$servicess=Service::whereIn('id', $input['service_ids'])->get();
			foreach ($servicess as $ss) {
				if($servicename==''){
					$servicename=$ss->name;
				}else{
					$servicename=$servicename.", ".$ss->name;
				}
			}
		}
		$authoritymail=$admindata->email;
		$appoinmentdata=Appointment::where('slug',$slug)->first();
		if($appoinmentdata->staff_id!=1){
			if($appoinmentdata->Admin){
				$staffslug=$appoinmentdata->Admin->slug;
				$authoritymail=$appoinmentdata->Admin->email;
			}
		}
		// print_r($authoritymail);exit;
		if($appoinmentdata->user_id!=null && $appoinmentdata->user_id!=0){
			if($appoinmentdata->User){
				$userdata=$appoinmentdata->User;
			}
		}else{
			$userdata['email_address']=$appoinmentdata->guest_email;
			$userdata['first_name']=$appoinmentdata->guest_name;
		}
		if($userdata['first_name']==null || $userdata['first_name']==''){
			$userdata['first_name']='Customer';
		}
		if($oldappointmentdata->status!=$input['changedstatus'] && $input['changedstatus']!='Pending' && $input['changedstatus']!='Visited' && $input['changedstatus']!='No show'){
			// print_r('expression1 old: '.$oldappointmentdata->status."change : ".$input['changedstatus']);

			if($settings->sms_notification == 1)
			{   global $account_sid;
				global $auth_token;
				global $sms_from;
				global $telephonecc;
				$link = HTTP_PATH . "/about";
				if($appoinmentdata->user_id == 0){
					$to = $appoinmentdata->guest_contact;
					if($to == ''){
						$to = '0';
					}
				}
				if($appoinmentdata->user_id != 0){
					 $to = $userdata->contact;
					if($to == ''){
						$to = '0';
					}
				}
				//print_r($link);exit;
				if($servicename==''){
						$servicename='our service';
					}
					$name = ucwords($userdata['first_name']);

				if($input['changedstatus']=='Canceled'){
				    	$message = 'Dear ' .$name.','."\r\n". 'Unfortunately your booked appointment with appointment number '. $appoinmentdata->appointment_number.' on : '. HTTP_PATH .' for '.  SITE_TITLE .' has been Canceled. You can book appointmment here '.$link;
				    }elseif($input['changedstatus']=='Completed'){
				    	$message = 'Dear '.$name.','."\r\n". 'Thanks for visit. Looking forward to seeing you next time! Always a pleasure to serve you!';
				    }elseif($input['changedstatus']=='Confirmed'){
				    	$message = 'Dear '.$name.','."\r\n". 'This is confirmation from '. HTTP_PATH .' '. SITE_TITLE .' about your appoinment with appointment number '.$appoinmentdata->appointment_number. ' on : '.$appoinmentdata->booking_date_time.' for '.$servicename. '. Please arrive 5 minutes before your scheduled appointment time. If you need to reschedule, please contact us.';
				    }
				$account_sid = $account_sid;
				$auth_token = $auth_token;
				//print_r($telephonecc);exit;
				$to = $telephonecc.''.$to;
				//print_r($telephonecc);exit;
				$id = "$account_sid";
				$token = "$auth_token";
				$url = "https://api.twilio.com/2010-04-01/Accounts/" . $account_sid . "/Messages.json";
				$data = array(
					'From' => $sms_from,
					'To' => $to,
					'Body' => $message,
				);
				$post = http_build_query($data);
				$x = curl_init($url);
				curl_setopt($x, CURLOPT_POST, true);
				curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($x, CURLOPT_SSL_VERIFYPEER, true);
				curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
				curl_setopt($x, CURLOPT_POSTFIELDS, $post);
				$result = curl_exec($x);
				curl_close($x);
			}
			if($settings->whatsapp_notification == 1)
			{ 	global $account_sid;
				global $auth_token;
				global $whatsapp_from;
				global $telephonecc;
				$link = HTTP_PATH . "/about";
				if($appoinmentdata->user_id == 0){
					$to = $appoinmentdata->guest_contact;
					if($to == ''){
						$to = '0';
					}
				}
				if($appoinmentdata->user_id != 0){
					 $to = $userdata->contact;
					if($to == ''){
						$to = '0';
					}
				}
				if($servicename==''){
						$servicename='our service';
					}
					$name = ucwords($userdata['first_name']);

				if($input['changedstatus']=='Canceled'){
				    	$message = 'Dear ' .$name.','."\r\n". 'Unfortunately your booked appointment with appointment number '. $appoinmentdata->appointment_number.' on : '. HTTP_PATH .' for '.  SITE_TITLE .' has been Canceled. You can book appointmment here '.$link;
				    }elseif($input['changedstatus']=='Completed'){
				    	$message = 'Dear '.$name.','."\r\n". 'Thanks for visit. Looking forward to seeing you next time! Always a pleasure to serve you!';
				    }elseif($input['changedstatus']=='Confirmed'){
				    	$message = 'Dear '.$name.','."\r\n". 'This is confirmation from '. HTTP_PATH .' '. SITE_TITLE .' about your appoinment with appointment number '.$appoinmentdata->appointment_number. ' on : '.$appoinmentdata->booking_date_time.' for '.$servicename. '. Please arrive 5 minutes before your scheduled appointment time. If you need to reschedule, please contact us.';
				    }
				//print_r($link);exit;
            	$account_sid = $account_sid;
            	$auth_token = $auth_token;
	            $to = $telephonecc.''.$to;
	            $id = "$account_sid";
	            $token = "$auth_token";
	            $url = "https://api.twilio.com/2010-04-01/Accounts/" . $account_sid . "/Messages.json";
	            $data = array(
	                'From' => 'whatsapp:'.$whatsapp_from,
	                'To' => 'whatsapp:'.$to,
	                'Body' => $message,
	            );

	            $post = http_build_query($data);
	            $x = curl_init($url);
	            curl_setopt($x, CURLOPT_POST, true);
	            curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
	            curl_setopt($x, CURLOPT_SSL_VERIFYPEER, true);
	            curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	            curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
	            curl_setopt($x, CURLOPT_POSTFIELDS, $post);
	            $result = curl_exec($x);
	            curl_close($x);
			}

			if($settings->email_notification==1){
				// print_r('expression2');
				if($userdata['email_address']!=null || $userdata['email_address']!=''){
					// print_r($appoinmentdata->booking_date_time);
					$date= date("l",strtotime($appoinmentdata->booking_date_time)).", ".date('d F Y H:i',strtotime($appoinmentdata->booking_date_time));
					$booking_number=$appoinmentdata->appointment_number;
					if($servicename==''){
						$servicename='our service';
					}
				    $link = HTTP_PATH . "/requestdetail/".$slug;
				    $name = ucwords($userdata['first_name']);
				    $emailId = $userdata['email_address'];
				    $template=11;
				    if($input['changedstatus']=='Canceled'){
				    	$template=14;
				    }elseif($input['changedstatus']=='Completed'){
				    	$template=16;
				    }elseif($input['changedstatus']=='Confirmed'){
				    	$template=13;
				    }
				    $emailTemplate = DB::table('emailtemplates')->where('id', $template)->first();
				    $toRepArray = array('[!username!]','[!booking_number!]','[!date!]','[!service!]','[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
				    $fromRepArray = array($name,$booking_number, $date,$servicename,$link, HTTP_PATH, SITE_TITLE);
				    $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
				    $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
				    // print_r("hello : ".$emailBody);exit;
				    Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

				}
			}

		}
		 // print_r("hello : ".$emailBody);exit;

		Session::flash('success_message', "Appointment deatils updated successfully.".$link1);
// 
		return Redirect::to('admin/requests');
	}

	public function updateappointmentstatus($slug=null){
		$pageTitle = 'Manage Requests'; 
		$activetab = 'actrequests';
		$offdata=[];
		if($slug==null){
			$slug='';
		}

		$appoinmentdata=Appointment::where('slug', $slug)->first();
		if(Session::get('adminid')!=1){
			if(Session::get('adminid')!=$appoinmentdata->staff_id){
	            $moduleid=6;
	            global $staffroles;
	            global $staffsubroles;
	            $adminRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRoles(Session::get('adminid'));
	            $checkSubRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRolesSub(Session::get('adminid'));
	            if(in_array($moduleid, $adminRols)){
	                if((isset($checkSubRols[$moduleid])) && !empty($checkSubRols[$moduleid])){
	                    if(in_array(2, $checkSubRols[$moduleid])){
	                        
	                    }else{
	                    Session::flash('error_message', UNAUTHORIZED_LINK);
	                    return Redirect::to('admin/admins/dashboard');
	                    exit;
	                    }
	                }else{
	                   Session::flash('error_message', UNAUTHORIZED_LINK);
	                    return Redirect::to('admin/admins/dashboard');
	                    exit; 
	                }
	            }else{
	                 Session::flash('error_message', UNAUTHORIZED_LINK);
	                    return Redirect::to('admin/admins/dashboard');
	                    exit;
	            }
            }
        }
		if($appoinmentdata->staff_id==1 || $appoinmentdata->staff_id==0){
			$allservices=Service::where('status',1)->get();
		}else{
			$allservices=[];
			$staffserviceidstr=$appoinmentdata->Admin->service_ids;
			if($staffserviceidstr!=''){
				$staffserviceid=explode(',', $staffserviceidstr);
				$allservices=Service::whereIn('id', $staffserviceid)->get();
			}
			// print_r(explode(',', $staffserviceid));exit;
		}

		return view('admin.requests.changestatus', ['title'=>$pageTitle, $activetab=>1,'appointment'=>$appoinmentdata,'allservices'=>$allservices]);
	}

	public function recancle($slug=null)
	{
		if($slug){ 
			Appointment::where('slug', $slug)->update(array('status' => 'Pending'));
			Session::flash('success_message', "Appointment Reschedule successfully.");
			return Redirect::to('admin/requests');
		}
	}

	public function reschedule($slug=null)
	{
		if($slug){
			$input = Input::all();
//print_r($input['booking_date_time']);exit;        
			Appointment::where('slug', $slug)->update(array('booking_date_time' => $input['booking_date_time']));
			Session::flash('success_message', "Appointment Reschedule successfully.");
			return Redirect::to('admin/requests');
		}
	}

	public function rescheduleappoinment($slug=null){
		$pageTitle = 'Manage Requests'; 
		$activetab = 'actrequests';
		$offdata=[];
		if($slug==null){
			$slug='';
		}

		$appoinmentdata=Appointment::where('slug', $slug)->first();
		if(Session::get('adminid')!=1){
			if(Session::get('adminid')!=$appoinmentdata->staff_id){
	            $moduleid=6;
	            global $staffroles;
	            global $staffsubroles;
	            $adminRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRoles(Session::get('adminid'));
	            $checkSubRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRolesSub(Session::get('adminid'));
	            if(in_array($moduleid, $adminRols)){
	                if((isset($checkSubRols[$moduleid])) && !empty($checkSubRols[$moduleid])){
	                    if(in_array(2, $checkSubRols[$moduleid])){
	                        
	                    }else{
	                    Session::flash('error_message', UNAUTHORIZED_LINK);
	                    return Redirect::to('admin/admins/dashboard');
	                    exit;
	                    }
	                }else{
	                   Session::flash('error_message', UNAUTHORIZED_LINK);
	                    return Redirect::to('admin/admins/dashboard');
	                    exit; 
	                }
	            }else{
	                 Session::flash('error_message', UNAUTHORIZED_LINK);
	                    return Redirect::to('admin/admins/dashboard');
	                    exit;
	            }
            }
        }
		$settings=Setting::where('id',1)->first();
//print_r($settings);exit;
//print_r($appoinmentdata->Admin->slug);exit;
// $staffdata
//echo"<pre>";
//print_r($appoinmentdata);exit;
		if($appoinmentdata->staff_id!=1){
// $activetab = 'actstaffs';
			$offdata=$this->getoffdays($appoinmentdata->Admin->slug);
		}else{
			$offdata=$this->getoffdays();
		}
// print_r($offdata);exit;
		return view('admin.requests.reschedule', ['title'=>$pageTitle, $activetab=>1,'appoinmentdata'=>$appoinmentdata,'settings'=>$settings,"offdays"=>$offdata]);
//Appointment::where('slug', $slug)->update(array('booking_date_time' => $input['booking_date_time']));
	}
	public function saverescheduledata($slug=null,Request $request){

		$datetime=$request->get('rescheduleDate');
		$formatdate=$request->get('formatdate');
		$slug=$request->get('slug');
		if($slug==null){
			$slug='';
		}
		$oldappointmentdata=Appointment::where('slug',$slug)->first();
		if($oldappointmentdata->user_id != 0){
		$userData = User::where('id',$oldappointmentdata->user_id)->first();
			}
		Appointment::where('slug', $slug)->update(array('booking_date_time' => $datetime,'updated_at'=>date('Y-m-d H:i'),'status'=>'Confirmed'));
		
		$newdata=Appointment::where('slug',$slug)->first();
		if($oldappointmentdata->user_id != 0){
			//print_r($oldappointmentdata->user_id);exit;
		$from_name = $userData->first_name;
		$user_id = $userData->id;
		$message = 'Your appointment with appoinment number '.$newdata->appointment_number.' has been Reschedule for the date '.$newdata->booking_date_time.'.';
		$serialisedData['from_name'] = $from_name;
		$serialisedData['user_id'] = $user_id;
		$serialisedData['message'] = $message;
		$serialisedData['status'] = 1;
		$serialisedData['created_at'] = date('Y-m-d H:i:s');
		$serialisedData['updated_at'] = date('Y-m-d H:i:s');
		$serialisedData['slug'] = $this->createSlug($from_name, 'notifications');
		$serialisedData['url'] = 'reschedule/' . $serialisedData['slug'];
		//print_r($serialisedData);exit;
		Notification::insert($serialisedData); 
		}

		// Session::flash('success_message', "Appointment Reschedule successfully on ".$formatdate);
		$settings=Setting::where('id',1)->first();
		$userdata=[];
		$staffslug='';
		$servicename='';

		if(isset($oldappointmentdata->service_ids) && $oldappointmentdata->service_ids!=''){
			$sids=explode(',',$oldappointmentdata->service_ids);
			$servicess=Service::whereIn('id', $sids)->get();
			foreach ($servicess as $ss) {
				if($servicename==''){
					$servicename=$ss->name;
				}else{
					$servicename=$servicename.", ".$ss->name;
				}
			}
		}
		$appoinmentdata=Appointment::where('slug',$slug)->first();
		if($appoinmentdata->staff_id!=1){
			if($appoinmentdata->Admin){
				$staffslug=$appoinmentdata->Admin->slug;
			}
		}

		if($appoinmentdata->user_id!=null && $appoinmentdata->user_id!=0){
			if($appoinmentdata->User){
				$userdata=$appoinmentdata->User;
			}
		}else{
			$userdata['email_address']=$appoinmentdata->guest_email;
			$userdata['first_name']=$appoinmentdata->guest_name;
		}
		if($userdata['first_name']==null || $userdata['first_name']==''){
			$userdata['first_name']='Customer';
		}
		// if($oldappointmentdata->status!=$input['changedstatus'] && $input['changedstatus']!='Pending' && $input['changedstatus']!='Visited' && $input['changedstatus']!='No show'){
			// print_r('expression1 old: '.$oldappointmentdata->status."change : ".$input['changedstatus']);
			if($settings->sms_notification == 1)
			{   global $account_sid;
				global $auth_token;
				global $sms_from;
				global $telephonecc;
				$link = HTTP_PATH . "/about";
				if($appoinmentdata->user_id == 0){
					$to = $appoinmentdata->guest_contact;
					if($to == ''){
						$to = '0';
					}
				}
				if($appoinmentdata->user_id != 0){
					 $to = $userdata->contact;
					if($to == ''){
						$to = '0';
					}
				}
				//print_r($link);exit;
				$name = ucwords($userdata['first_name']);
				$message = 'Dear ' .$name.','."\r\n". 'Your appointment with appointment number '. $appoinmentdata->appointment_number.' on : '. HTTP_PATH .' for a '. SITE_TITLE .' has been rescheduled on date '.$appoinmentdata->booking_date_time .'successfully.';
				$account_sid = $account_sid;
				$auth_token = $auth_token;
				//print_r($telephonecc);exit;
				$to = $telephonecc.''.$to;
				//print_r($telephonecc);exit;
				$id = "$account_sid";
				$token = "$auth_token";
				$url = "https://api.twilio.com/2010-04-01/Accounts/" . $account_sid . "/Messages.json";
				$data = array(
					'From' => $sms_from,
					'To' => $to,
					'Body' => $message,
				);
				$post = http_build_query($data);
				$x = curl_init($url);
				curl_setopt($x, CURLOPT_POST, true);
				curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($x, CURLOPT_SSL_VERIFYPEER, true);
				curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
				curl_setopt($x, CURLOPT_POSTFIELDS, $post);
				$result = curl_exec($x);
				curl_close($x);
			}
			if($settings->whatsapp_notification == 1)
			{ 	global $account_sid;
				global $auth_token;
				global $whatsapp_from;
				global $telephonecc;
				$link = HTTP_PATH . "/about";
				if($appoinmentdata->user_id == 0){
					$to = $appoinmentdata->guest_contact;
					if($to == ''){
						$to = '0';
					}
				}
				if($appoinmentdata->user_id != 0){
					 $to = $userdata->contact;
					if($to == ''){
						$to = '0';
					}
				}
				//print_r($link);exit;
				$name = ucwords($userdata['first_name']);
				$message = 'Dear ' .$name.','."\r\n". 'Your appointment with appointment number '. $appoinmentdata->appointment_number.' on : '. HTTP_PATH .' for a '. SITE_TITLE .' has been rescheduled on date '.$appoinmentdata->booking_date_time .' successfully.';
            	$account_sid = $account_sid;
            	$auth_token = $auth_token;
	            $to = $telephonecc.''.$to;
	            $id = "$account_sid";
	            $token = "$auth_token";
	            $url = "https://api.twilio.com/2010-04-01/Accounts/" . $account_sid . "/Messages.json";
	            $data = array(
	                'From' => 'whatsapp:'.$whatsapp_from,
	                'To' => 'whatsapp:'.$to,
	                'Body' => $message,
	            );

	            $post = http_build_query($data);
	            $x = curl_init($url);
	            curl_setopt($x, CURLOPT_POST, true);
	            curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
	            curl_setopt($x, CURLOPT_SSL_VERIFYPEER, true);
	            curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	            curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
	            curl_setopt($x, CURLOPT_POSTFIELDS, $post);
	            $result = curl_exec($x);
	            curl_close($x);
			}

			if($settings->email_notification==1){
				// print_r('expression2');
				if($userdata['email_address']!=null || $userdata['email_address']!=''){
					// print_r($appoinmentdata->booking_date_time);
					$date= date("l",strtotime($appoinmentdata->booking_date_time)).", ".date('d F Y H:i',strtotime($appoinmentdata->booking_date_time));
					$booking_number=$appoinmentdata->appointment_number;
					if($servicename==''){
						$servicename='our service';
					}
				    $link = HTTP_PATH . "/requestdetail/".$slug;
				    $name = ucwords($userdata['first_name']);
				    $emailId = $userdata['email_address'];
				    $template=15;
				    // if($input['changedstatus']=='Canceled'){
				    // 	$template=14;
				    // }elseif($input['changedstatus']=='Completed'){
				    // 	$template=16;
				    // }elseif($input['changedstatus']=='Confirmed'){
				    // 	$template=13;
				    // }
				    $emailTemplate = DB::table('emailtemplates')->where('id', $template)->first();
				    $toRepArray = array('[!username!]','[!booking_number!]','[!date!]','[!service!]','[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
				    $fromRepArray = array($name,$booking_number,$date,$servicename,$link, HTTP_PATH, SITE_TITLE);
				    $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
				    $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
				    // print_r("hello : ".$emailBody);exit;
				    Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

				}
			}

		// }
			// exit;
			Session::flash('success_message', "Appointment Reschedule successfully on ".$formatdate);
		return "Appointment Reschedule successfully on ".$formatdate;
// Redirect::to('admin/requests');
//print_r('date: '.$date.' format date: '.$formatdate.' slug: '.$slug);
	}

	public function getoffdays($slug=null){
		$off=[];

		if($slug && $slug!=null){
			$staffdata =Admin::where('slug',$slug)->first();

			if(!empty($staffdata)){
				$alldays=['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
				$staffdays=explode(',',$staffdata->working_days);
				$off=array_diff($alldays,$staffdays);
			}

		}else{
			$staffdata =Setting::where('id',1)->first();
			$alldays=['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
			foreach($alldays as $ad){
				if($staffdata[$ad."_time_from"]=='' || $staffdata[$ad."_time_from"]==''){
					array_push($off,$ad); 
				}
			}
		}
		$offclass=['monday'=>'td.fc-mon','tuesday'=>'td.fc-tue','wednesday'=>'td.fc-wed','thursday'=>'td.fc-thu','friday'=>'td.fc-fri','saturday'=>'td.fc-sat','sunday'=>'td.fc-sun'];
//print_r($off);
//echo "<br>";
//print_r($offclass);
		$offclassstr="";
		$delimiter="";
		foreach ($off as $o){
			$delimiter=$offclassstr!=''?',':'';
			$offclassstr=$offclassstr.$delimiter.$offclass[$o];
		}
// print_r($offclassstr);
//     exit;
		return $offclassstr;
	}

	public function assignstaff(Request $request, $slug=null)
	{

		$staff = $request['textval'];
		//print_r($request->get('textval'));
		$oldappointmentdata=Appointment::where('id',$staff)->first();

		DB::table('appointments')
		->where('id', $staff)
		->update(array('staff_id' => $slug));
		//print_r($slug);exit;
		$staffinfo=Admin::where('id',$slug)->first();
		//print_r($staffinfo);exit;
		//Appointment::where('slug', $slug)->update(array('status' => 'Canceled'));

		$settings=Setting::where('id',1)->first();
		$userdata=[];
		$staffslug='';
		$servicename='';

		if(isset($oldappointmentdata->service_ids) && $oldappointmentdata->service_ids!=''){
			$sids=explode(',',$oldappointmentdata->service_ids);
			$servicess=Service::whereIn('id', $sids)->get();
			foreach ($servicess as $ss) {
				if($servicename==''){
					$servicename=$ss->name;
				}else{
					$servicename=$servicename.", ".$ss->name;
				}
			}
		}
		$appoinmentdata=Appointment::where('id',$staff)->first();
		if($appoinmentdata->staff_id!=1){
			if($appoinmentdata->Admin){
				$staffslug=$appoinmentdata->Admin->slug;
			}
		}

		if($appoinmentdata->user_id!=null && $appoinmentdata->user_id!=0){
			if($appoinmentdata->User){
				$userdata=$appoinmentdata->User;
			}
		}else{
			$userdata['email_address']=$appoinmentdata->guest_email;
			$userdata['first_name']=$appoinmentdata->guest_name;
		}
		if($userdata['first_name']==null || $userdata['first_name']==''){
			$userdata['first_name']='Customer';
		}
		// if($oldappointmentdata->status!=$input['changedstatus'] && $input['changedstatus']!='Pending' && $input['changedstatus']!='Visited' && $input['changedstatus']!='No show'){
			// print_r('expression1 old: '.$oldappointmentdata->status."change : ".$input['changedstatus']);

			if($settings->sms_notification == 1)
			{   global $account_sid;
				global $auth_token;
				global $sms_from;
				global $telephonecc;
				$link = HTTP_PATH . "/about";
				if($appoinmentdata->user_id == 0){
					$to = $appoinmentdata->guest_contact;
					if($to == ''){
						$to = '0';
					}
				}
				if($appoinmentdata->user_id != 0){
					 $to = $userdata->contact;
					if($to == ''){
						$to = '0';
					}
				}
				$name = ucwords($userdata['first_name']);
				if($servicename==''){
						$servicename='our service';
					}
				$emailIdcustomer = $userdata['email_address'];
				
				$message = 'Dear ' .$name.','."\r\n". 'Your booked appointment details are below,' ."\r\n". 'Booking Number :  '. $appoinmentdata->appointment_number.'. ' ."\r\n". ' Email Address : '. $emailIdcustomer .'. ' ."\r\n". ' Service : '.$servicename. '. ' ."\r\n". ' Booking Date : '.$appoinmentdata->booking_date_time.'.';
				$account_sid = $account_sid;
				$auth_token = $auth_token;
				//print_r($telephonecc);exit;
				$to = $telephonecc.''.$to;
				//print_r($telephonecc);exit;
				$id = "$account_sid";
				$token = "$auth_token";
				$url = "https://api.twilio.com/2010-04-01/Accounts/" . $account_sid . "/Messages.json";
				$data = array(
					'From' => $sms_from,
					'To' => $to,
					'Body' => $message,
				);
				$post = http_build_query($data);
				$x = curl_init($url);
				curl_setopt($x, CURLOPT_POST, true);
				curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($x, CURLOPT_SSL_VERIFYPEER, true);
				curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
				curl_setopt($x, CURLOPT_POSTFIELDS, $post);
				$result = curl_exec($x);
				curl_close($x);
			}
			if($settings->whatsapp_notification == 1)
			{ 	global $account_sid;
				global $auth_token;
				global $whatsapp_from;
				global $telephonecc;
				$link = HTTP_PATH . "/about";
				if($appoinmentdata->user_id == 0){
					$to = $appoinmentdata->guest_contact;
					if($to == ''){
						$to = '0';
					}
				}
				if($appoinmentdata->user_id != 0){
					 $to = $userdata->contact;
					if($to == ''){
						$to = '0';
					}
				}
				
				$name = ucwords($userdata['first_name']);
				if($servicename==''){
						$servicename='our service';
					}
				$emailIdcustomer = $userdata['email_address'];
				
				$message = 'Dear ' .$name.','."\r\n". 'Your booked appointment details are below,' ."\r\n". 'Booking Number :  '. $appoinmentdata->appointment_number.'. ' ."\r\n". ' Email Address : '. $emailIdcustomer .'. ' ."\r\n". ' Service : '.$servicename. '. ' ."\r\n". ' Booking Date : '.$appoinmentdata->booking_date_time.'.';
            	$account_sid = $account_sid;
            	$auth_token = $auth_token;
	            $to = $telephonecc.''.$to;
	            $id = "$account_sid";
	            $token = "$auth_token";
	            $url = "https://api.twilio.com/2010-04-01/Accounts/" . $account_sid . "/Messages.json";
	            $data = array(
	                'From' => 'whatsapp:'.$whatsapp_from,
	                'To' => 'whatsapp:'.$to,
	                'Body' => $message,
	            );

	            $post = http_build_query($data);
	            $x = curl_init($url);
	            curl_setopt($x, CURLOPT_POST, true);
	            curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
	            curl_setopt($x, CURLOPT_SSL_VERIFYPEER, true);
	            curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	            curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
	            curl_setopt($x, CURLOPT_POSTFIELDS, $post);
	            $result = curl_exec($x);
	            curl_close($x);
			}

			if($settings->email_notification==1){
				// print_r('expression2');
				if($userdata['email_address']!=null || $userdata['email_address']!=''){
					// print_r($appoinmentdata->booking_date_time);
					$date= date("l",strtotime($appoinmentdata->booking_date_time)).", ".date('d F Y H:i',strtotime($appoinmentdata->booking_date_time));
					$booking_number=$appoinmentdata->appointment_number;
					if($servicename==''){
						$servicename='our service';
					}
					$staffname=ucfirst($appoinmentdata->Admin->first_name);
				    $link = HTTP_PATH . "/requestdetail/".$slug;
				    $name = ucwords($userdata['first_name']);
				    $emailId = $appoinmentdata->Admin->email;
				    $emailIdcustomer=$userdata['email_address'];
				    // $contact=$userdata['contact'];
				    $template=10;
				    // if($input['changedstatus']=='Canceled'){
				    // 	$template=14;
				    // }elseif($input['changedstatus']=='Completed'){
				    // 	$template=16;
				    // }elseif($input['changedstatus']=='Confirmed'){
				    // 	$template=13;
				    // }
				    $emailTemplate = DB::table('emailtemplates')->where('id', $template)->first();
				    $toRepArray = array('[!name!]','[!staffname!]','[!booking_number!]', '[!email!]', '[!service!]', '[!booking_date_time!]','[!HTTP_PATH!]', '[!SITE_TITLE!]');
                    $fromRepArray = array($name,$staffname,$booking_number, $emailIdcustomer, $servicename,$date, HTTP_PATH, SITE_TITLE);
				    $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
				    $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
				    // print_r("hello : ".$emailBody);exit;
				    Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

				}
			}
	//Session::flash('success_message', "Staff assign successfully for the appointment.");
	}

	public function dshservice($slug=null)
    { // print_r($slug);exit;
    	$pageTitle = 'Manage Requests'; 
    	$activetab = 'actrequests';
    	$query = new Appointment();
    	$query = $query->sortable();

    	$startdate = date('Y-m-d 00:00:00');
    	$endtdate = date('Y-m-d 23:59:59');
    	$range = [$startdate, $endtdate];
    	$appointments = $query->orderBy('id','DESC')->where(DB::RAW("FIND_IN_SET($slug, service_ids)"), '!=', '')->whereBetween('booking_date_time', $range)->paginate(20);

    	return view('admin.requests.index', ['title'=>$pageTitle, $activetab=>1,'appointments'=>$appointments]);
    }

    public function dshstaff($slug=null)
    {
    	$pageTitle = 'Manage Requests'; 
    	$activetab = 'actrequests';
    	$query = new Appointment();
    	$query = $query->sortable();

    	$startdate = date('Y-m-d 00:00:00');
    	$endtdate = date('Y-m-d 23:59:59');
    	$range = [$startdate, $endtdate];
    	$appointments = $query->orderBy('id','DESC')->where('staff_id',$slug) ->whereBetween('booking_date_time', $range)->paginate(20);

    	return view('admin.requests.index', ['title'=>$pageTitle, $activetab=>1,'appointments'=>$appointments]);
    }

}
?>