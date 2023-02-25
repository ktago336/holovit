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
use App\Models\Setting;
use Mail;
use App\Mail\SendMailable;

class ReportsController extends Controller {    
    public function __construct() {
        $this->middleware('is_adminlogin');
    }
    
    public function indexold(Request $request, $slug=null){ 

        $admin_id = Session::get('adminid');
        $pageTitle = 'Manage Requests'; 
        $activetab = 'actreports';
        $query = new Appointment();
        $query = $query->sortable();
        
        if($request->has('fromsearch') || $request->has('tosearch'))
        { 
            
            if ($request->get('fromsearch') || $request->get('fromsearch')!=''){
                
                $from1 = $request->get('fromsearch');
                $from = date("Y-m-d H:i:s", strtotime($from1));
            }else{$from = '0';}
            if ($request->get('tosearch') || $request->get('tosearch')!=''){
                
                $to1 = $request->get('tosearch');
                $to = date("Y-m-d H:i:s", strtotime($to1.' +1 day'));
            }else{$to = '0';}
            
                
                $query = $query->where(function($q) use ($from, $to)
                {   
                   
                    
                    if($from!='' && $from!='0' && $to!='' && $to!='0'){ 
                        
                        $q->where('booking_date_time', '>=', $from)->where('booking_date_time', '<', $to);
                    }
                        else if($from!='0' && $to == '0'){
                            
                            $q->whereDate('booking_date_time', '>=', $from);
                        }
                        else if($to !='0' && $from =='0'){
                           
                            $q->whereDate('booking_date_time', '<', $to);
                        }
                    


                });
            
            
        }


        if($slug == 'monthly' && (!$request->has('fromsearch') && !$request->has('tosearch')))
        {  
            $firstday = date('Y-m-01 00:00:00');
            $lastday = date('Y-m-t 23:59:59');
            if($admin_id != 1){
                $appointments =  $query->where('status','Completed')->whereDate('booking_date_time', '>=', $firstday)->whereDate('booking_date_time', '<=', $lastday)->where('staff_id',$admin_id)->paginate(20);
            }
            else{
                $appointments =  $query->where('status','Completed')->whereDate('booking_date_time', '>=', $firstday)->whereDate('booking_date_time', '<=', $lastday)->paginate(20);
            }
        }
        if(($slug == 'daily') && (!$request->has('fromsearch') && !$request->has('tosearch')))
        { 
            $todate = date('Y-m-d 00:00:00');
            $fromdate = date('Y-m-d 23:59:59');
            if($admin_id != 1){ 
                $appointments = $query->orderBy('id','DESC')->where('status','Completed')->whereDate('booking_date_time', '>=', $todate)->whereDate('booking_date_time', '<=', $fromdate)->where('staff_id',$admin_id)->paginate(20);}
                else{
                    $appointments = $query->orderBy('id','DESC')->where('status','Completed')->whereDate('booking_date_time', '>=', $todate)->whereDate('booking_date_time', '<=', $fromdate)->paginate(20);
                }
        }

        else
        { 
            if($admin_id != 1){
            $appointments = $query->orderBy('booking_date_time','DESC')->where('status','Completed')->where('staff_id',$admin_id)
                ->paginate(20);}
                else{
                    $appointments = $query->orderBy('booking_date_time','DESC')->where('status','Completed')
                ->paginate(20);
                }
        }

        if($request->ajax()){ 
            return view('elements.admin.reports.index', ['appointments'=>$appointments]);
        }
        else{ 
            return view('admin.reports.index', ['title'=>$pageTitle, $activetab=>1,'appointments'=>$appointments]);
        }

    }

     public function index(Request $request, $slug=null){ 

        $admin_id = Session::get('adminid');
        $pageTitle = 'Manage Requests'; 
        $activetab = 'actreports';
        $query = new Appointment();
        $query = $query->sortable();
        $adminRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRoles(Session::get('adminid'));
            $checkSubRols = app('App\Http\Controllers\Admin\AdminsController')->getAdminRolesSub(Session::get('adminid'));

        
        if(($request->has('fromsearch') && $request->get('fromsearch')!='') || ($request->has('tosearch')!='' &&  $request->get('tosearch')!=''))
        { 
            
            if ($request->get('fromsearch') || $request->get('fromsearch')!=''){
                
                $from1 = $request->get('fromsearch');
                $from = date("Y-m-d H:i:s", strtotime($from1));
            }else{$from = '0';}
            if ($request->get('tosearch') || $request->get('tosearch')!=''){
                
                $to1 = $request->get('tosearch');
                $to = date("Y-m-d H:i:s", strtotime($to1.' +1 day'));
            }else{$to = '0';}
            
                
                $query = $query->where(function($q) use ($from, $to)
                {   
                   
                    
                    if($from!='' && $from!='0' && $to!='' && $to!='0'){ 
                        
                        $q->where('booking_date_time', '>=', $from)->where('booking_date_time', '<', $to);
                    }
                        else if($from!='0' && $to == '0'){
                            
                            $q->whereDate('booking_date_time', '>=', $from);
                        }
                        else if($to !='0' && $from =='0'){
                           
                            $q->whereDate('booking_date_time', '<', $to);
                        }
                    


                });
            
            
        }


        if($request->has('searchingby')!='' && $request->get('searchingby')=='monthly'  && ($request->has('fromsearch')!='' && $request->has('tosearch')!=''))
        {  
             // print_r("monthly");exit;
            $firstday = date('Y-m-01 00:00:00');
            $lastday = date('Y-m-t 23:59:59');
            if($admin_id != 1){
                $appointments =  $query->where('status','Completed')->whereDate('booking_date_time', '>=', $firstday)->whereDate('booking_date_time', '<=', $lastday)->where('staff_id',$admin_id)->paginate(20);
            }
            else{
                $appointments =  $query->where('status','Completed')->whereDate('booking_date_time', '>=', $firstday)->whereDate('booking_date_time', '<=', $lastday)->paginate(20);
            }
        }
        if($request->has('searchingby')!='' && $request->get('searchingby')=='daily' && ($request->has('fromsearch')!='' && $request->has('tosearch')!=''))
        { 
             // print_r("daily");exit;
            $todate = date('Y-m-d 00:00:00');
            $fromdate = date('Y-m-d 23:59:59');
            if($admin_id != 1){ 
                $appointments = $query->orderBy('id','DESC')->where('status','Completed')->whereDate('booking_date_time', '>=', $todate)->whereDate('booking_date_time', '<=', $fromdate)->where('staff_id',$admin_id)->paginate(20);}
                else{
                    $appointments = $query->orderBy('id','DESC')->where('status','Completed')->whereDate('booking_date_time', '>=', $todate)->whereDate('booking_date_time', '<=', $fromdate)->paginate(20);
                }
        }

        else
        { 
            if($admin_id != 1 && (!isset($checkSubRols[7]))){
            $appointments = $query->orderBy('booking_date_time','DESC')->where('status','Completed')->where('staff_id',$admin_id)
                ->paginate(20);}
                else if(isset($checkSubRols[7]) && (in_array(4, $checkSubRols[7]))){
                    $appointments = $query->orderBy('booking_date_time','DESC')->where('status','Completed')
                ->paginate(20);
                }
                else{
                    $appointments = $query->orderBy('booking_date_time','DESC')->where('status','Completed')
                ->paginate(20);
                }
        }

        if($request->ajax()){ 
            // print_r($request->all());
            return view('elements.admin.reports.index', ['appointments'=>$appointments]);
        }
        else{ 
            return view('admin.reports.index', ['title'=>$pageTitle, $activetab=>1,'appointments'=>$appointments]);
        }

    }

}
?>