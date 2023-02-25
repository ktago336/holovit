<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;
use DB;
use Session;
use Input;
use Validator;
use Redirect;
use App\Models\Gig;
use App\Models\User;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Block;
use App\Models\Admin;
use App\Models\Contact;
use App\Models\Myorder;
use App\Mail\SendMailable;
use App\Models\Appointment;
use App\Models\Testimonial;
use DateTime;
use DateInterval;

class HomesController extends Controller {
    
    public function index(){ 
        $pageTitle = 'Welcome';
        $settings=Setting::where('id',1)->first();
        $experts=Admin::where('type','staff')->where('status','1')->take(10)->get();
        $services=Service::where('status',1)->get()->toArray();
        
        $userInfo = '';
        if(Session::get('user_id')){
            $userInfo = User::where('id', Session::get('user_id'))->first();
        }
        $servicesIdName=array();
        foreach ($services as $service) {
            $servicesIdName[$service['id']]=$service['name'];
        }
        $servicesName=array();
        foreach ($experts as $expert) {
            $sids=explode(',',$expert->service_ids);
            $sname="";
            foreach ($sids as $sid) {
                if(isset($servicesIdName[$sid])){
                $sname=($sname=="") ? $sname.$servicesIdName[$sid]:$sname.",".$servicesIdName[$sid];        
                }
            }
            $expert->service_names=$sname;
             
        }
        $staffdropdown=Admin::where('id','<>',1)->where('status',1)->get()->pluck('first_name','id');
        $services = DB::table('services')->where('status', '1')->get();
        $categories = array();
        
        $testimonils = DB::table('testimonials')->where('status', 1)->orderBy('id', 'DESC')->limit(6)->get();
        
        return view('homes.index', ['title' => $pageTitle, 'fixheader'=>1,'allrecords'=>$categories,'experts'=>$experts, 'services' => $services,'setting'=>$settings,'staffdropdown'=>$staffdropdown,'userInfo'=>$userInfo, 'testimonils'=>$testimonils]); 
    }

    public function getstaff($slug=null){
       if($slug){
           $staff = Admin::select(DB::raw("CONCAT(',',service_ids,',') AS service,id"))->having('service','like','%,'.$slug.',%')->where('type','staff')->get()->toArray();
       }else{
           $staff = Admin::select(DB::raw("CONCAT(',',service_ids,',') AS service,id"))->where('type','staff')->get()->toArray();
       }
        
        $sids = array_column($staff, 'id');
        
        $staff_id=Admin::whereIn('id',$sids)->where('status',1)->get()->pluck('first_name','id');
        //$staff_id[''] = "Select Staff";
       
        return json_encode($staff_id);
        
    }
    public function getservicesdata($slug=null){
       
       $selected_ids=explode(",",$slug);
       $services=Service::where('status',1)->whereIn('id', $selected_ids)
                    ->get();
        $servicesName="";
        $total_charges=0;
        $total_min=0;
        foreach ($services as $service) {
            $servicesName=($servicesName=="") ? $servicesName.$service['name']:$servicesName.", ".$service['name']; 
            $total_charges=$total_charges+$service['price'];
             $total_min=$total_min+$service['minutes'];
        }
        $selected_services = array('names' =>$servicesName,'total'=>$total_charges,'duration'=> $total_min);

       
       return json_encode($selected_services);
        
    }
    public function getslots($starttime,$endtime,$duration,$buffer,$staffid,$slotdate,$isFixedSlot){
       //print_r($isFixedSlot);exit;
        $isfullblock=Block::where('slot_date',$slotdate)->where('staff_id',$staffid)->where('full_day',1)->where('status',1)->first();
        if(!empty($isfullblock)){
            return [];
        }
        $blocks=Block::where('slot_date',$slotdate)->where('staff_id',$staffid)->where('status',1)->get();
        $blockslots=[];
        foreach ($blocks as $b) {
            $s=date('H:i',$b['start_time']);
            $e=date('H:i',$b['end_time']);
            array_push($blockslots,['start_time'=>$s,'end_time'=>$e]);
        }
        $bookedstarttime=[];
        if($isFixedSlot==1){
            $staffappoinments=Appointment::where('booking_date_time','>=',$slotdate." 00:00:00")->where('booking_date_time','<=',$slotdate." 24:00:00")->where('staff_id',$staffid)->where('status','<>','Canceled')->get();
            foreach ($staffappoinments as $sa) {
                $as=date('H:i',strtotime(explode(" ", $sa['booking_date_time'])[1]));
                array_push($bookedstarttime,$as);
            }
        }

        
        $start = new DateTime($starttime);
        $end = new DateTime($endtime);
        $interval = new DateInterval("PT" . $duration. "M");
        $breakInterval = new DateInterval("PT" . $buffer. "M");
        $timeslots=[];
        for ($intStart = $start; 
             $intStart < $end; 
             $intStart->add($interval)->add($breakInterval)){

               $endPeriod = clone $intStart;
               $endPeriod->add($interval);
               if ($endPeriod > $end) {
                 $endPeriod=$end;
               }
               $starttime =  $intStart->format('H:i');
               $endtime = $endPeriod->format('H:i');
               $timeslot = $starttime.' - '.$endtime;
               $skey = array_search($starttime, array_column($blockslots, 'start_time'));
               if($isFixedSlot==1){
                   $akey=array_search($starttime, $bookedstarttime);
                   
                   if($akey !== false || $skey !== false){
                        continue;
                    }
                }else{
                    if($skey !== false){
                        continue;
                    }
                }
                array_push($timeslots,$timeslot);
               
            }
             
           return $timeslots;
    }
    public function getslotdata($slug=null,Request $request){
      
        $staffslug=$request->get('slug');
        $selecteddate=$request->get('date');
        $day=strtolower($request->get('dayname'));
        $isFixedSlot=$request->get('isFixedSlot');
        $sitesettings=Setting::first();
        $staffbuffertime=$sitesettings['buffer_time'];
        $duration=$sitesettings['slot_time'];
        $slots=[];
        
        
      
        if($staffslug!=''){
             
            $staffdata=Admin::where('type','staff')->where('slug',$staffslug)->first();
            $staff_id=$staffdata['id'];
            $working_days=explode(',',$staffdata['working_days']);
            $start_time=explode(',',$staffdata['start_time']);
            $end_time=explode(',',$staffdata['end_time']);
            
            $key=array_search($day,$working_days,true);
            if($key === false){
                
                return json_encode($slots);
            }
            $slots=$this->getslots($start_time[$key],$end_time[$key],$duration,$staffbuffertime,$staff_id,$selecteddate,$isFixedSlot);
           
        }
        else{
            
            $staff_id=1;
            if($sitesettings[$day.'_time_from']!="" && $sitesettings[$day.'_time_to']!=""){
            $slots=$this->getslots($sitesettings[$day.'_time_from'],$sitesettings[$day.'_time_to'],$duration,0,1,$selecteddate,$isFixedSlot);
            }
            
        }

       return json_encode($slots);
        
    }

    public function home(){ 

       $pageTitle = 'Welcome';   
       
    }
    public function thank($slug=null){ 

       $pageTitle = 'Thanks';  
       return view('homes.thank', ['title' => $pageTitle,'slug'=>$slug]); 
       
    }

    public function work()
    {
        $pageTitle = 'All Works';
        return view('homes.work', ['title' => $pageTitle]);
    }

    public function about()
    {
        $settings=Setting::where('id',1)->first();
        $staffdropdown=Admin::where('id','<>',1)->where('status',1)->get()->pluck('first_name','id');
        // print_r($staffdropdown);exit;
    	$pageTitle = 'About';
    	return view('homes.about', ['title' => $pageTitle,'setting'=>$settings,'staffdropdown'=>$staffdropdown]); 
    }

    public function services()
    {
        $pageTitle = 'Services';
        $services = DB::table('services')->where('status', '1')->get();
      
        return view('homes.services', ['title' => $pageTitle, 'services' => $services]); 
    }

    public function experts($slug=null,Request $request)
    {
        $pageTitle = 'Our Experts';
        $activetab = 'actusers';
        $query = new Admin();
        $query = $query->sortable();

        // $query1 = new Appointment();
        // $query1 = $query1->sortable();

         $query2 = new Service();
         $query2 = $query2->sortable();
        
        $services=Service::where('status',1)->get()->toArray();
        $servicesIdName=array();
        foreach ($services as $service) {
            $servicesIdName[$service['id']]=$service['name'];
        }

        if($request->get('keyword')!='' && $request->get('keyword1')!=''){
        	$keyword = $request->get('keyword');
        	$service = $request->get('keyword1');
        	$keywords = $query2->where('name', 'LIKE', '%' . $service . '%')->first();
        	if(!$keywords){
               $keyword1 = '';
             }else{$keyword1 = $keywords->id;} 
            //print_r($keyword1);
            $query = $query->where( function($q) use ($keyword, $keyword1){
                    $q->where('first_name', 'like', '%'.$keyword.'%')
                    ->where('service_ids', 'like', '%'.$keyword1.'%');
                    
            });

        }else{
            if($request->get('keyword')!='' && $request->get('keyword1')==''){
                $keyword = $request->get('keyword');
                $query = $query->where(function($q) use ($keyword){
                    $q->where('first_name', 'like', '%'.$keyword.'%')
                    ->orWhere('last_name', 'like', '%'.$keyword.'%');
                    
                });
            }
             if($request->get('keyword')=='' && $request->get('keyword1')!=''){
                $service = $request->get('keyword1');
        	   $keywords = $query2->where('name', 'LIKE', '%' . $service . '%')->first();
                //print_r(count($keywords));
                if(!$keywords){
        	   $keyword1 = '0';
             }else{$keyword1 = $keywords->id;} 
                $query = $query->where( function($q) use ($keyword1){
                    $q->where('service_ids', 'like', '%'.$keyword1.'%');
            });
            }

        }
        
        if($slug==null){
            $experts = $query->where('type','staff')->where('status','1')->orderBy('id','DESC')->paginate(12);
        }else{
            $staff = Admin::select(DB::raw("CONCAT(',',service_ids,',') AS service,id"))->having('service','like','%,'.$slug.',%')->get()->toArray();
            $sids = array_column($staff, 'id');
            $experts = $query->whereIn('id',$sids)->where('type','staff')->where('status','1')->orderBy('id','DESC')->paginate(12);
        }
        
        
        $servicesName=array();
        foreach ($experts as $expert) {
            $sids=explode(',',$expert->service_ids);
            $sname="";
            foreach ($sids as $sid) {
                if(isset($servicesIdName[$sid])){
                $sname=($sname=="") ? $sname.$servicesIdName[$sid]:$sname.",".$servicesIdName[$sid];        
                }
            }
            $expert->service_names=$sname;
             
        }
        if($request->ajax()){ 
            return view('homes.expertlist', ['experts'=>$experts]);
        }
        return view('homes.experts', ['title' => $pageTitle,$activetab=>1,'experts'=>$experts,'slug'=>$slug]); 
    }

    public function expertdetail($slug=null)
    {
        $pageTitle = "Expert's Detail";
        $expert=Admin::where('type','staff')->where('slug',$slug)->where('status','1')->first();
        $sids=explode(',',$expert->service_ids);
        $services=Service::where('status',1)->whereIn('id', $sids)
                    ->get();
        $servicesName="";
        foreach ($services as $service) {
            $servicesName=($servicesName=="") ? $servicesName.$service['name']:$servicesName.",".$service['name']; 
        }
        
        return view('homes.expertdetail', ['title' => $pageTitle,'expert'=>$expert,'services'=>$services,'serviceNames'=>$servicesName]); 
    }

    public function selectservice($slug=null)
    {
        $pageTitle = "Select Service";
        $sitesettings=Setting::first();
        $sslug='0';
        if($slug!='0' && $slug!=null){
            
            $expert=Admin::where('type','staff')->where('slug',$slug)->where('status','1')->first();
            $sids=explode(',',$expert->service_ids);
            $services=Service::where('status',1)->whereIn('id', $sids)
                        ->get();
            $sslug=$expert->slug;
            
        }
        else{
           
            $expert=[];
            $services=Service::where('status',1)->get();
        }
        $userInfo = '';
        if(Session::get('user_id')){
            $userInfo = User::where('id', Session::get('user_id'))->first();
        }
        
        $input = Input::all();
        if (!empty($input)) {
            
            $users = DB::table('users')->where('email_address', $input['email'])->first();
            $payment_method = $input['payment_method'];
            
            if($sitesettings->user_registration == '1')
            { 
                
                if(!$users)
                {
                    $userinput = [];
                    
                    $userinput['first_name']=$input['first_name'];
                    $userinput['last_name']=$input['last_name'];
                    $userinput['contact']=$input['contact'];
                    $userinput['email_address']=$input['email'];
                    $userinput['password'] = $userinput['first_name'].'@123';
                    
                    $serialisedData = $this->serialiseFormData($userinput);
                    $serialisedData['slug'] = $this->createSlug($userinput['first_name'], 'users');
                    $serialisedData['status'] =  0;
                    $serialisedData['user_status'] =  'Offline';
                    
                    $serialisedData['password'] =  $this->encpassword($userinput['password']);
                    $uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
                    $serialisedData['unique_key'] = $uniqueKey;
                   
                    $inserted_id=User::insertGetId($serialisedData);
                    
                    

                    $link = HTTP_PATH . "/email-confirmation/" . $uniqueKey;
                    $name = $userinput['first_name'];
                    $emailId = $userinput['email_address'];
                    $new_password = $userinput['password'];
                   
                    $emailTemplate = DB::table('emailtemplates')->where('id', 9)->first();
                    $toRepArray = array('[!email!]', '[!username!]', '[!password!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                    $fromRepArray = array($emailId, $name, $new_password, $link, HTTP_PATH, SITE_TITLE);
                    $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                    $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                    //commented
                    Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));
                    
                    Session::flash('success_message', "We have sent you an account activation link by email. Please check your spam folder if you do not receive the email within the next few minutes.");

                    $users=User::where('id',$inserted_id)->first();
                    if(!empty($expert)){
                        $staff_id=$expert->id;
                    }else{
                        $staff_id=1;
                    }
                    $appoinment_sids=implode(',', $input['service_ids']);
                    $appoinment_services=Service::whereIn('id',$input['service_ids'])->get();
                    $total_charges=0;
                    $snames="";
                    foreach ($appoinment_services as $as) {
                       $total_charges=$total_charges+$as->price;
                       $snames=$snames.",".$as->name;
                    }
                    //$users = DB::table('users')->orderBy('id', 'desc')->first();
                    //$serialisedData = $this->serialiseFormData($input);
                     $serialisedData1['staff_id'] = $staff_id;
                     $serialisedData1['user_id'] = $users->id;
                     $serialisedData1['service_ids'] = $appoinment_sids;
                     $appslug = $this->createSlug($users->first_name.' '.$users->last_name, 'appointments');
                     $serialisedData1['slug'] = $appslug;
                     $appointment_number = $this->getUniqueModelId($tablename = 'appointments', $fieldname = 'appointment_number', $number_of_digits = 8, $token_type = '1', $prefix = 'ABS');
                     $serialisedData1['appointment_number'] = $appointment_number;
                     $serialisedData1['created_at'] = date('Y-m-d H:i:s');
                     $serialisedData1['updated_at'] = date('Y-m-d H:i:s');
                     $serialisedData1['total_price'] = $total_charges;
                     $serialisedData1['description'] =$input['description'];
                     $serialisedData1['status'] = 'Pending';
                     if($payment_method == 'paypal'){
                        $serialisedData1['payment_status'] = 'Pending';
                     }else{
                        $serialisedData1['payment_status'] = 'Cash';
                     }
                     $serialisedData1['booking_date_time'] = $input['selected_date']." ".$input['start_time'];

                    $last_id=Appointment::insertGetId($serialisedData1);
                    $last = Appointment::where('id',$last_id)->first();
                    $adminInfo = DB::table('admins')->first();
                    if($last->staff_id == 1)
                    {
                        $name = $input['first_name'];
                        $emailId = $adminInfo->email;
                        $contact = $input['contact'];
                        
                        $service = $snames;
                        $booking_date_time = date("Y-m-d H:i:s", strtotime($input['selected_date']." ".$input['start_time']));
                  
                        $emailTemplate = DB::table('emailtemplates')->where('id', 10)->first();
                        $toRepArray = array('[!name!]', '[!email!]', '[!contact!]', '[!service!]', '[!booking_date_time!]','[!HTTP_PATH!]', '[!SITE_TITLE!]');
                        $fromRepArray = array($name, $emailId, $contact, $service,$booking_date_time, HTTP_PATH, SITE_TITLE);
                        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                        //commented
                        Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));   
                    }
                    //get total amount
                    if($payment_method == 'paypal'){
                        return Redirect('/payments/paywithpaypal/'.$appslug);
                    }else{
                        Session::flash('success_message', "Your appointment details save succeessfully");
                        return Redirect('/thank/'.$input['selected_date']." ".$input['start_time']);
                    }
                }else{
                    
                    if(!empty($expert)){
                        $staff_id=$expert->id;
                    }else{
                        $staff_id=1;
                    }
                    $appoinment_sids=implode(',', $input['service_ids']);
                    $appoinment_services=Service::whereIn('id',$input['service_ids'])->get();
                    $total_charges=0;
                    $snames="";
                    foreach ($appoinment_services as $as) {
                       $total_charges=$total_charges+$as->price;
                        $snames=$snames.",".$as->name;
                    }
                    $serialisedData2['staff_id'] = $staff_id;
                     $serialisedData2['user_id'] = $users->id;
                     $serialisedData2['service_ids'] = $appoinment_sids;
                     $serialisedData2['created_at'] = date('Y-m-d H:i:s');
                     $serialisedData2['updated_at'] = date('Y-m-d H:i:s');
                     $serialisedData2['total_price'] = $total_charges;
                     $serialisedData2['description'] =$input['description'];
                     $serialisedData2['status'] = 'Pending';
                     $serialisedData2['booking_date_time'] = $input['selected_date']." ".$input['start_time'];
                    $appslug = $this->createSlug($users->first_name.' '.$users->last_name, 'appointments');
                     $serialisedData2['slug'] = $appslug;
                     $appointment_number = $this->getUniqueModelId($tablename = 'appointments', $fieldname = 'appointment_number', $number_of_digits = 8, $token_type = '1', $prefix = 'ABS');
                     $serialisedData2['appointment_number'] = $appointment_number;
                     if($payment_method == 'paypal'){
                        $serialisedData2['payment_status'] = 'Pending';
                     }else{
                        $serialisedData2['payment_status'] = 'Cash';
                     }
                    $last_id=Appointment::insertGetId($serialisedData2);
                    $last = Appointment::where('id',$last_id)->first();
                    $adminInfo = DB::table('admins')->first();

                    
                    // if($last->staff_id == 1)
                    // {
                        // $name = $input['user_id'];
                        $name =$input['first_name'];
                        $emailId = $adminInfo->email;
                        $contact = $input['contact'];
                        //$serviceinfo = DB::table('services')->select('name')->where('id', $input['service_ids'])->first();
                        $service = $snames;
                        $booking_date_time = date("Y-m-d H:i:s", strtotime($input['selected_date']." ".$input['start_time']));
                  
                        $emailTemplate = DB::table('emailtemplates')->where('id', 10)->first();
                        $toRepArray = array('[!name!]', '[!email!]', '[!contact!]', '[!service!]', '[!booking_date_time!]','[!HTTP_PATH!]', '[!SITE_TITLE!]');
                        $fromRepArray = array($name, $emailId, $contact, $service,$booking_date_time, HTTP_PATH, SITE_TITLE);
                        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                        //print_r($emailBody);exit;
                        //commented
                        Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));   
                    // }
                    if($payment_method == 'paypal'){
                        return Redirect('/payments/paywithpaypal/'.$appslug);
                    }else{
                        Session::flash('success_message', "Your appointment details save succeessfully");
                        return Redirect('/thank/'.$input['selected_date']." ".$input['start_time']);
                    }
                }//if user old end
                return Redirect('/');
            }
            else
            {
                //print_r($input);exit;
                if(!empty($expert)){
                        $staff_id=$expert->id;
                }else{
                    $staff_id=1;
                }
                $appoinment_sids=implode(',', $input['service_ids']);
                $appoinment_services=Service::whereIn('id',$input['service_ids'])->get();
                $total_charges=0;
                $snames="";
                foreach ($appoinment_services as $as) {
                   $total_charges=$total_charges+$as->price;
                    $snames=$snames.",".$as->name;
                }
                $serialisedData4['guest_name']=$input['first_name']." ".$input['last_name'];
                $serialisedData4['guest_contact']=$input['contact'];
                $serialisedData4['guest_email']=$input['email'];
                //$serialisedData4['password'] = $userinput['first_name'].'@123';
                $serialisedData4['staff_id'] = $staff_id;
                $serialisedData4['user_id'] = 0;
                if(Session::get('user_id')){
                    $serialisedData4['user_id'] = Session::get('user_id');
                }else{
                    $userInfo = User::where('email_address', $input['email'])->first();
                    if($userInfo){
                        $serialisedData4['user_id'] = $userInfo->id;
                    }
                }
                $serialisedData4['service_ids'] = $appoinment_sids;
                $serialisedData4['created_at'] = date('Y-m-d H:i:s');
                $serialisedData4['updated_at'] = date('Y-m-d H:i:s');
                $serialisedData4['total_price'] = $total_charges;
                $serialisedData4['description'] =$input['description'];
                $serialisedData4['status'] = 'Pending';
                $serialisedData4['booking_date_time'] = $input['selected_date']." ".$input['start_time'];
                $appslug = $this->createSlug($input['first_name']." ".$input['last_name'], 'appointments');
                $serialisedData4['slug'] = $appslug;
                $appointment_number = $this->getUniqueModelId($tablename = 'appointments', $fieldname = 'appointment_number', $number_of_digits = 8, $token_type = '1', $prefix = 'ABS');
                $serialisedData4['appointment_number'] = $appointment_number;
                if($payment_method == 'paypal'){
                    $serialisedData4['payment_status'] = 'Pending';
                 }else{
                    $serialisedData4['payment_status'] = 'Cash';
                 }
                $last_id=Appointment::insertGetId($serialisedData4);
                $last = Appointment::where('id',$last_id)->first();
                $adminInfo = DB::table('admins')->first();
                
                if($last->staff_id == 1)
                {
                $name = $input['first_name']." ".$input['last_name'];
                $emailId = $adminInfo->email;
                $contact = $input['contact'];
                // $serviceinfo = DB::table('services')->select('name')->where('id', $input['service_ids'])->first();
                $service = $snames;
                $booking_date_time = date("Y-m-d H:i:s", strtotime($input['selected_date']." ".$input['start_time'])); 
          
                $emailTemplate = DB::table('emailtemplates')->where('id', 10)->first();
                $toRepArray = array('[!name!]', '[!email!]', '[!contact!]', '[!service!]', '[!booking_date_time!]','[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($name, $emailId, $contact, $service,$booking_date_time, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                //print_r($emailBody);exit;
                //commented
                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));

                Session::flash('success_message', "Your guest appointment details save succeessfully");   
                }
                if($payment_method == 'paypal'){
                    return Redirect('/payments/paywithpaypal/'.$appslug);
                }else{
                    Session::flash('success_message', "Your appointment details save succeessfully");
                    return Redirect('/thank/'.$input['selected_date']." ".$input['start_time']);
                }

            } 


        }
        
    
        $offdata=[];
        if($slug){
            $offdata=$this->getoffdays($slug);
        }else{
            $offdata=$this->getoffdays();
        }

        return view('homes.selectservice', ['title' => $pageTitle,'expert'=>$expert,'services'=>$services,'staff_slug'=>$slug,'userInfo'=>$userInfo,"offdays"=>$offdata]); 
    }
    
    //get admin or staff unavailable days
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

    public function bookappointment($slug=null)
    {
        $pageTitle = 'About';
        return view('homes.book_appointment', ['title' => $pageTitle]); 
    }

   

    public function blog()
    {
        $pageTitle = 'Blog';
        return view('homes.blog', ['title' => $pageTitle]); 
    }
    public function testimonial(Request $request)
    {
        $pageTitle = 'Testimonial';
        
        $activetab = 'testimonials';
        $query = new Testimonial();
        $query = $query->sortable();
        $testimonils = $query->where('status','1')->orderBy('id','DESC')->paginate(12);
        if($request->ajax()){ 
            return view('homes.testimonils', ['testimonils'=>$testimonils]);
        }
        return view('homes.testimonial', ['title' => $pageTitle,$activetab=>1,'testimonils'=>$testimonils]); 
    }
    public function contact()
    {
        $pageTitle = 'Contact';
        return view('homes.contact', ['title' => $pageTitle]); 
    }

    public function contactus()
    {
        $pageTitle = 'Contact';
        $input = Input::all();
        if (!empty($input)) {
            
            $rules = array(
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:20',
                'phone' => 'required|number|unique:contacts',
                'password' => 'required|min:8',
                'email_address'=>'required|email',
                'message'=>'required',
            );
            
            $serialisedData = $this->serialiseFormData($input);
            $serialisedData['slug'] = $this->createSlug($input['first_name'].' '.$input['last_name'], 'contacts');
           
            Contact::insert($serialisedData);
        return Redirect('/');
        }
        return view('homes.contact', ['title' => $pageTitle]);
    }

    public function appointment()
    {   
        $pageTitle = 'Appointment';
        $settings = DB::table('settings')->first();
       
        $input = Input::all();
        if (!empty($input)) 
        {
            $rules = array(
                'user_id' => 'required',
                'email_address' => 'required|email',
                'contact' => 'required|number',
                'service_ids' => 'required',
                'booking_date_time'=>'required',
                'staff_id'=>'required',
                'description' => 'required',
            );
            
            $users = DB::table('users')->where('email_address', $input['email_address'])->first();
            $services = DB::table('services')->where('id', $input['service_ids'])->first();
            
            if($settings->user_registration == '1')
            { 
            if(!$users)
            {  
                $userinput = $input;
                unset($userinput['service_ids']);
                unset($userinput['booking_date_time']);
                unset($userinput['staff_id']);
                unset($userinput['description']);
                $userinput['first_name'] = $userinput['user_id'];
                $userinput['password'] = $userinput['first_name'].'@123';
                unset($userinput['user_id']);
                $serialisedData = $this->serialiseFormData($userinput);
                $serialisedData['slug'] = $this->createSlug($userinput['first_name'], 'users');
                $serialisedData['status'] =  0;
                $serialisedData['user_status'] =  'Offline';
                //$serialisedData['password'] = $input['password'];
                $serialisedData['password'] =  $this->encpassword($userinput['password']);
                $uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
                $serialisedData['unique_key'] = $uniqueKey;
               
                User::insert($serialisedData);

                //send mail to customer for registration  

                $link = HTTP_PATH . "/email-confirmation/" . $uniqueKey;
                $name = $userinput['first_name'];
                $emailId = $userinput['email_address'];
                $new_password = $userinput['password'];
               
                $emailTemplate = DB::table('emailtemplates')->where('id', 9)->first();
                $toRepArray = array('[!email!]', '[!username!]', '[!password!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($emailId, $name, $new_password, $link, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));
                
                Session::flash('success_message', "We have sent you an account activation link by email. Please check your spam folder if you do not receive the email within the next few minutes.");

                $users = DB::table('users')->orderBy('id', 'desc')->first();
                $serialisedData = $this->serialiseFormData($input);
                unset($serialisedData['email_address']);
                unset($serialisedData['contact']);
                $serialisedData['user_id'] = $users->id;
                $serialisedData['slug'] = $this->createSlug($users->first_name.' '.$users->last_name, 'appointments');
                $serialisedData['booking_date_time'] = date("Y-m-d H:i:s", strtotime($serialisedData['booking_date_time']));
                $serialisedData['total_price'] = $services->price;
                $serialisedData['status'] = 'Pending';
                $appointment_number = $this->getUniqueModelId($tablename = 'appointments', $fieldname = 'appointment_number', $number_of_digits = 8, $token_type = '1', $prefix = 'ABS');
                $serialisedData['appointment_number'] = $appointment_number;
                Appointment::insert($serialisedData);
                $last = DB::table('appointments')->orderBy('id', 'DESC')->first();
                $adminInfo = DB::table('admins')->first();
                if($last->staff_id == 1)
                {
                $name = $input['user_id'];
                $emailId = $adminInfo->email;
                $contact = $input['contact'];
                $serviceinfo = DB::table('services')->select('name')->where('id', $input['service_ids'])->first();
                $service = $serviceinfo->name;
                $booking_date_time = date("Y-m-d H:i:s", strtotime($input['booking_date_time']));
          
                $emailTemplate = DB::table('emailtemplates')->where('id', 10)->first();
                $toRepArray = array('[!name!]', '[!email!]', '[!contact!]', '[!service!]', '[!booking_date_time!]','[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($name, $emailId, $contact, $service,$booking_date_time, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));	
                }
                
                return json_encode($last);
                
            }
            else
            {	
                $serialisedData = $this->serialiseFormData($input);
                unset($serialisedData['email_address']);
                unset($serialisedData['contact']);
                $serialisedData['user_id'] = $users->id;
                $serialisedData['slug'] = $this->createSlug($users->first_name.' '.$users->last_name, 'appointments');
                $appointment_number = $this->getUniqueModelId($tablename = 'appointments', $fieldname = 'appointment_number', $number_of_digits = 8, $token_type = '1', $prefix = 'ABS');
                $serialisedData['appointment_number'] = $appointment_number;
                 $serialisedData['booking_date_time'] = date("Y-m-d H:i:s", strtotime($serialisedData['booking_date_time']));  
                $serialisedData['total_price'] = $services->price;
                $serialisedData['status'] = 'Pending';
                 
                
                Appointment::insert($serialisedData);

                $last = DB::table('appointments')->orderBy('id', 'DESC')->first();
                $adminInfo = DB::table('admins')->first();
                
                if($last->staff_id == 1)
                {
                $name = $input['user_id'];
                $emailId = $adminInfo->email;
                $contact = $input['contact'];
                $serviceinfo = DB::table('services')->select('name')->where('id', $input['service_ids'])->first();
                $service = $serviceinfo->name;
                $booking_date_time = date("Y-m-d H:i:s", strtotime($input['booking_date_time']));
          
                $emailTemplate = DB::table('emailtemplates')->where('id', 10)->first();
                $toRepArray = array('[!name!]', '[!email!]', '[!contact!]', '[!service!]', '[!booking_date_time!]','[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($name, $emailId, $contact, $service,$booking_date_time, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
               
                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));	
                }
                $session = "Your appointment details save succeessfully";
                
                return json_encode($last);

            }
            }
            else
            {
               
                $serialisedData = $this->serialiseFormData($input);
                unset($serialisedData['email_address']);
                unset($serialisedData['contact']);
                $serialisedData['slug'] = $this->createSlug($input['user_id'].' ', 'appointments');
                $appointment_number = $this->getUniqueModelId($tablename = 'appointments', $fieldname = 'appointment_number', $number_of_digits = 8, $token_type = '1', $prefix = 'ABS');
                $serialisedData['appointment_number'] = $appointment_number;
                 $serialisedData['booking_date_time'] = date("Y-m-d H:i:s", strtotime($serialisedData['booking_date_time']));  
                $serialisedData['total_price'] = $services->price;
                $serialisedData['status'] = 'Pending';
                $serialisedData['guest_name'] = $input['user_id'];
                $serialisedData['guest_email'] = $input['email_address'];
                $serialisedData['guest_contact'] = $input['contact'];
                //print_r($serialisedData);exit;
                Appointment::insert($serialisedData);

                $last = DB::table('appointments')->orderBy('id', 'DESC')->first();

                $adminInfo = DB::table('admins')->first();
              
                if($last->staff_id == 1)
                {
                $name = $input['user_id'];
                $emailId = $adminInfo->email;
                $contact = $input['contact'];
                $serviceinfo = DB::table('services')->select('name')->where('id', $input['service_ids'])->first();
                $service = $serviceinfo->name;
                $booking_date_time = date("Y-m-d H:i:s", strtotime($input['booking_date_time'])); 
          
                $emailTemplate = DB::table('emailtemplates')->where('id', 10)->first();
                $toRepArray = array('[!name!]', '[!email!]', '[!contact!]', '[!service!]', '[!booking_date_time!]','[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($name, $emailId, $contact, $service,$booking_date_time, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
              
                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));

                 
                
                }
                
                return json_encode($last);
            } 
        }
        

        return view('homes.about', ['title' => $pageTitle]); 
    }

    public function saveappointment(Request $request)
    {  
        $pageTitle = 'Appointment';
        $settings = DB::table('settings')->first();

        $data = $request->all();
        $input = $data;
        //print_r($input);exit;
        // if (!empty($input)) {
        //     $rules = array(
        //         'user_id' => 'required',
        //         'email_address' => 'required',
        //         'contact' => 'required',
        //         'service_ids' => 'required',
        //         'booking_date_time' => 'required',
        //         'staff_id' => 'required',
        //         'description' => 'required'
        //     );
        //     }
        // if($input['email_address']==null){
        //     $users=[];
        // }
        $users = DB::table('users')->where('email_address', $input['email_address'])->first();
        $services = DB::table('services')->where('id', $input['service_ids'])->first();
 
        if($settings->user_registration == '1')
            { 
            if(!$users)
            {  
                $userinput = $input;
                unset($userinput['service_ids']);
                unset($userinput['booking_date_time']);
                unset($userinput['staff_id']);
                unset($userinput['description']);
                $userinput['first_name'] = $userinput['user_id'];
                $userinput['password'] = $userinput['first_name'].'@123';
                unset($userinput['user_id']);
                $serialisedData = $this->serialiseFormData($userinput);
                $serialisedData['slug'] = $this->createSlug($userinput['first_name'], 'users');
                $serialisedData['status'] =  0;
                $serialisedData['user_status'] =  'Offline';
                //$serialisedData['password'] = $input['password'];
                $serialisedData['password'] =  $this->encpassword($userinput['password']);
                $uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
                $serialisedData['unique_key'] = $uniqueKey;
                
                User::insert($serialisedData);

                //send mail to customer for registration  

                $link = HTTP_PATH . "/email-confirmation/" . $uniqueKey;
                $name = $userinput['first_name'];
                $emailId = $userinput['email_address'];
                $new_password = $userinput['password'];
               
                $emailTemplate = DB::table('emailtemplates')->where('id', 9)->first();
                $toRepArray = array('[!email!]', '[!username!]', '[!password!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($emailId, $name, $new_password, $link, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                //commented
                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));
                
                Session::flash('success_message', "We have sent you an account activation link by email. Please check your spam folder if you do not receive the email within the next few minutes.");

                $users = DB::table('users')->orderBy('id', 'desc')->first();
                $serialisedData = $this->serialiseFormData($input);
                unset($serialisedData['email_address']);
                unset($serialisedData['contact']);
                $serialisedData['user_id'] = $users->id;
                $serialisedData['slug'] = $this->createSlug($users->first_name.' '.$users->last_name, 'appointments');
                $appointment_number = $this->getUniqueModelId($tablename = 'appointments', $fieldname = 'appointment_number', $number_of_digits = 8, $token_type = '1', $prefix = 'ABS');
                $serialisedData['appointment_number'] = $appointment_number;
                $serialisedData['booking_date_time'] = date("Y-m-d H:i:s", strtotime($serialisedData['booking_date_time']));
                $serialisedData['total_price'] = $services->price;
                $serialisedData['status'] = 'Pending';
                
                Appointment::insert($serialisedData);
                $last = DB::table('appointments')->orderBy('id', 'DESC')->first();
                $adminInfo = DB::table('admins')->first();
                if($last->staff_id == 1)
                {
                $name = $input['user_id'];
                $emailId = $adminInfo->email;
                $contact = $input['contact'];
                $serviceinfo = DB::table('services')->select('name')->where('id', $input['service_ids'])->first();
                $service = $serviceinfo->name;
                $booking_date_time = date("Y-m-d H:i:s", strtotime($input['booking_date_time']));
          
                $emailTemplate = DB::table('emailtemplates')->where('id', 10)->first();
                $toRepArray = array('[!name!]', '[!email!]', '[!contact!]', '[!service!]', '[!booking_date_time!]','[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($name, $emailId, $contact, $service,$booking_date_time, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                //commented
                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));   
                }
                $session = "Your appointment details save succeessfully";
                $last->bookeddateformat=date("l",strtotime($last->booking_date_time)).", ".date('d F Y H:i',strtotime($last->booking_date_time));
                return json_encode($last);
                
            }
            else
            {   
                $serialisedData = $this->serialiseFormData($input);
                unset($serialisedData['email_address']);
                unset($serialisedData['contact']);
                $serialisedData['user_id'] = $users->id;
                $serialisedData['slug'] = $this->createSlug($users->first_name.' '.$users->last_name, 'appointments');
                $appointment_number = $this->getUniqueModelId($tablename = 'appointments', $fieldname = 'appointment_number', $number_of_digits = 8, $token_type = '1', $prefix = 'ABS');
                $serialisedData['appointment_number'] = $appointment_number;
                 $serialisedData['booking_date_time'] = date("Y-m-d H:i:s", strtotime($serialisedData['booking_date_time']));  
                 //print_r($services);exit;
                $serialisedData['total_price'] = $services->price;
                $serialisedData['status'] = 'Pending';
                 
               
                Appointment::insert($serialisedData);

                $last = DB::table('appointments')->orderBy('id', 'DESC')->first();
                $adminInfo = DB::table('admins')->first();
                
                if($last->staff_id == 1)
                {
                $name = $input['user_id'];
                $emailId = $adminInfo->email;
                $contact = $input['contact'];
                $serviceinfo = DB::table('services')->select('name')->where('id', $input['service_ids'])->first();
                $service = $serviceinfo->name;
                $booking_date_time = date("Y-m-d H:i:s", strtotime($input['booking_date_time']));
          
                $emailTemplate = DB::table('emailtemplates')->where('id', 10)->first();
                $toRepArray = array('[!name!]', '[!email!]', '[!contact!]', '[!service!]', '[!booking_date_time!]','[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($name, $emailId, $contact, $service,$booking_date_time, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                //commented
                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));   
                }
                $session = "Your appointment details save succeessfully";
                $last->bookeddateformat=date("l",strtotime($last->booking_date_time)).", ".date('d F Y H:i',strtotime($last->booking_date_time));
                return json_encode($last);

            }
            }
            else
            {
                
                $serialisedData = $this->serialiseFormData($input);
                unset($serialisedData['email_address']);
                unset($serialisedData['contact']);
                $serialisedData['slug'] = $this->createSlug($input['user_id'].' ', 'appointments');
                $appointment_number = $this->getUniqueModelId($tablename = 'appointments', $fieldname = 'appointment_number', $number_of_digits = 8, $token_type = '1', $prefix = 'ABS');
                $serialisedData['appointment_number'] = $appointment_number;
                 $serialisedData['booking_date_time'] = date("Y-m-d H:i:s", strtotime($serialisedData['booking_date_time']));  
                $serialisedData['total_price'] = $services->price;
                $serialisedData['status'] = 'Pending';
                $serialisedData['guest_name'] = $input['user_id'];
                $serialisedData['guest_email'] = $input['email_address'];
                $serialisedData['guest_contact'] = $input['contact'];
               
                Appointment::insert($serialisedData);

                $last = DB::table('appointments')->orderBy('id', 'DESC')->first();

                $adminInfo = DB::table('admins')->first();
                
                if($last->staff_id == 1)
                {
                $name = $input['user_id'];
                $emailId = $adminInfo->email;
                $contact = $input['contact'];
                $serviceinfo = DB::table('services')->select('name')->where('id', $input['service_ids'])->first();
                $service = $serviceinfo->name;
                $booking_date_time = date("Y-m-d H:i:s", strtotime($input['booking_date_time'])); 
          
                $emailTemplate = DB::table('emailtemplates')->where('id', 10)->first();
                $toRepArray = array('[!name!]', '[!email!]', '[!contact!]', '[!service!]', '[!booking_date_time!]','[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($name, $emailId, $contact, $service,$booking_date_time, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                
                //commented
                Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));

                
                }
                $last->bookeddateformat=date("l",strtotime($last->booking_date_time)).", ".date('d F Y H:i',strtotime($last->booking_date_time));
                return json_encode($last);  
            } 
      
    
    }
    public function savebookappointment(Request $request){
        $data = $request->all();  
        //print_r($data);exit;
        $sitesettings=Setting::first();
        $staffadmin=0;
        $flag=0;
        $staffadmin=$data['staff_id'];
        //print_r($staffadmin);exit;
        $serviceinfo=[];
        //user registration compulsory
        if($sitesettings->user_registration=='1'){
            $input=$data;
            $users = DB::table('users')->where('email_address', $input['email_address'])->orWhere('contact', $input['contact'])->first();
            $bookingInfo=[];
            $userInfo=[];
            //print_r($users);exit;
            if(!empty($users)){ //if user already exist
                $bookingInfo['user_id']=$users->id;
            }
            else{ //if user already not exist
                $userinfo['first_name']=$input['name'];
                $userinfo['email_address']=$input['email_address'];
                $userinfo['contact']=$input['contact'];
                $temppass=str_replace(' ','_',trim($input['name']))."@1234";
                $userinfo['password'] =  $this->encpassword($temppass);
                $userinfo['slug'] = $this->createSlug($input['name'], 'users');
                $userinfo['status']=0;
                $userinfo['activation_status']=0;
                $userinfo['created_at']=date('Y-m-d H:i');
                $userinfo['updated_at']=date('Y-m-d H:i');
                $userinfo['user_status'] =  'Offline';
                
                $uniqueKey = bin2hex(openssl_random_pseudo_bytes(25));
                $userinfo['unique_key'] = $uniqueKey;
               
                $inserted_id=User::insertGetId($userinfo);
                $bookingInfo['user_id']=$inserted_id;
                if($userinfo['email_address']!='' && $userinfo['email_address']!=null)
                {
                    $link = HTTP_PATH . "/email-confirmation/" . $uniqueKey;
                    $name = $userinfo['first_name'];
                    $emailId = $userinfo['email_address'];
                    $new_password = $temppass;
                   
                    $emailTemplate = DB::table('emailtemplates')->where('id', 9)->first();
                    $toRepArray = array('[!email!]', '[!username!]', '[!password!]', '[!link!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                    $fromRepArray = array($emailId, $name, $new_password, $link, HTTP_PATH, SITE_TITLE);
                    $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                    $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                    //commented
                    Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));
                    
                    Session::flash('success_message', "We have sent you an account activation link by email. Please check your spam folder if you do not receive the email within the next few minutes.");
                }

                //$userinfo['slug']=$input['name'];
            }
            
            if ($input['service_ids']=='' || $input['service_ids']==null || $input['service_ids']==0) {
                $bookingInfo['service_ids']=0;
                $bookingInfo['total_price']=0;
            }else{
                $bookingInfo['service_ids']=$input['service_ids'];
                $serviceinfo = DB::table('services')->select('id','name','price')->where('id', $input['service_ids'])->first();
                // print_r($serviceinfo);exit;
                $bookingInfo['total_price']=$serviceinfo->price;
            }
           
            //$bookingInfo['user_id']='';
            $bookingInfo['staff_id']=$input['staff_id'];
            //$bookingInfo['guest_name']='';
            //$bookingInfo['guest_email']='';
            //$bookingInfo['guest_contact']='';
            $bookingInfo['booking_date_time']=date('Y-m-d H:i',strtotime($input['booking_date_time']));
            $bookingInfo['status']='Pending';
            $bookingInfo['slug']=$this->createSlug($input['name'],'appointments');
            $appointment_number = $this->getUniqueModelId($tablename = 'appointments', $fieldname = 'appointment_number', $number_of_digits = 8, $token_type = '1', $prefix = 'ABS');
            $bookingInfo['appointment_number'] = $appointment_number;
            $bookingInfo['description']=$input['description'];
            $bookingInfo['created_at']=date('Y-m-d H:i');
            $bookingInfo['updated_at']=date('Y-m-d H:i');
            $last_id=Appointment::insertGetId($bookingInfo);
            $last = Appointment::where('id',$last_id)->first();
            $flag=1;
        }
        else{ //user registration Not compulsory
            $input=$data;
            //$serviceinfo=[];
            $bookingInfo=[];
            if ($input['service_ids']=='' || $input['service_ids']==null || $input['service_ids']==0) {
                $bookingInfo['service_ids']=0;
                $bookingInfo['total_price']=0;
            }else{
                $bookingInfo['service_ids']=$input['service_ids'];
                $serviceinfo = DB::table('services')->select('id','name','price')->where('id', $input['service_ids'])->first();
                $bookingInfo['total_price']=$serviceinfo->price;
            }
            //$bookingInfo['user_id']='';
            $bookingInfo['staff_id']=$input['staff_id'];
            $bookingInfo['guest_name']=$input['name'];
            $bookingInfo['guest_email']=$input['email_address'];
            $bookingInfo['guest_contact']=$input['contact'];
            $bookingInfo['booking_date_time']=date('Y-m-d H:i',strtotime($input['booking_date_time']));
            $bookingInfo['status']='Pending';
            $bookingInfo['slug']=$this->createSlug($bookingInfo['guest_name'],'appointments');
            $appointment_number = $this->getUniqueModelId($tablename = 'appointments', $fieldname = 'appointment_number', $number_of_digits = 8, $token_type = '1', $prefix = 'ABS');
            $bookingInfo['appointment_number'] = $appointment_number;
            $bookingInfo['description']=$input['description'];
            $bookingInfo['created_at']=date('Y-m-d H:i');
            $bookingInfo['updated_at']=date('Y-m-d H:i');
            $last_id=Appointment::insertGetId($bookingInfo);
            $last = Appointment::where('id',$last_id)->first();
            $flag=1;
            
        }
        if($flag==1){
            $bookeddateformat=date("l",strtotime($data['booking_date_time'])).", ".date('d F Y H:i',strtotime($data['booking_date_time']));    
            // if($staffadmin == 1)
            // {
            // echo "<pre>";
            // print_r($last);exit;
                $adminInfo = $last->Admin;
                $booking_number=$last->appointment_number;
                 // print_r($adminInfo);exit
                 // print_r($data);exit;
                $name = $data['name'];
                $staffname='';
                if($last->staff_id==1){
                    $staffname='Admin';
                }else{
                    $staffname='Staff';
                    if($last->Admin->first_name!=''){
                        $staffname=ucfirst($last->Admin->first_name);
                    }
                }
                $emailId = $adminInfo->email;
                $emailIdcustomer = $data['email_address'];
                $contact = $data['contact'];
                // $serviceinfo = DB::table('services')->select('name')->where('id', $input['service_ids'])->first();
                $service = (!empty($serviceinfo))?$serviceinfo->name:'N/A';
                $booking_date_time = date("Y-m-d H:i:s", strtotime($data['booking_date_time'])); 
                if($sitesettings->email_notification==1){
                    $emailTemplate = DB::table('emailtemplates')->where('id', 10)->first();
                    $toRepArray = array('[!name!]','[!staffname!]','[!booking_number!]', '[!email!]', '[!contact!]', '[!service!]', '[!booking_date_time!]','[!HTTP_PATH!]', '[!SITE_TITLE!]');
                    $fromRepArray = array($name,$staffname,$booking_number, $emailIdcustomer, $contact, $service,$booking_date_time, HTTP_PATH, SITE_TITLE);
                    $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                    $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                    //print_r($emailBody);exit;
                    //commented
                    Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));
                    if($last->user_id!=null && $last->user_id!=0){
                            if($last->User){
                                $userdata=$last->User;
                            }
                        }else{
                            $userdata['email_address']=$last->guest_email;
                            $userdata['first_name']=$last->guest_name;
                        }
                        if($userdata['first_name']==null || $userdata['first_name']==''){
                            $userdata['first_name']='Customer';
                        }
                }
                Session::flash('success_message', "Your guest appointment details save succeessfully");   
            // }
            return json_encode(['bookeddateformat'=>$bookeddateformat,'appointment_number'=>$appointment_number]);
        }
        exit;      
        // print_r($data);exit;
    } 
    public function getslotsstart($starttime,$endtime,$duration,$buffer,$staffid,$slotdate,$isFixedSlot){
       //print_r($isFixedSlot);exit;
        $isfullblock=Block::where('slot_date',$slotdate)->where('staff_id',$staffid)->where('full_day',1)->where('status',1)->first();
        if(!empty($isfullblock)){
            return [];
        }
        $blocks=Block::where('slot_date',$slotdate)->where('staff_id',$staffid)->where('status',1)->get();
        $blockslots=[];
        foreach ($blocks as $b) {
            $s=date('H:i',$b['start_time']);
            $e=date('H:i',$b['end_time']);
            array_push($blockslots,['start_time'=>$s,'end_time'=>$e]);
        }
        $bookedstarttime=[];
        if($isFixedSlot==1){
            $staffappoinments=Appointment::where('booking_date_time','>=',$slotdate." 00:00:00")->where('booking_date_time','<=',$slotdate." 24:00:00")->where('staff_id',$staffid)->where('status','<>','Canceled')->get();
            foreach ($staffappoinments as $sa) {
                $as=date('H:i',strtotime(explode(" ", $sa['booking_date_time'])[1]));
                array_push($bookedstarttime,$as);
            }
        }

        
        $start = new DateTime($starttime);
        $end = new DateTime($endtime);
        $interval = new DateInterval("PT" . $duration. "M");
        $breakInterval = new DateInterval("PT" . $buffer. "M");
        $timeslots=[];
        for ($intStart = $start; 
             $intStart < $end; 
             $intStart->add($interval)->add($breakInterval)){

               $endPeriod = clone $intStart;
               $endPeriod->add($interval);
               if ($endPeriod > $end) {
                 $endPeriod=$end;
               }
               $starttime =  $intStart->format('H:i');
               $endtime = $endPeriod->format('H:i');
               $timeslot = $starttime;
               $skey = array_search($starttime, array_column($blockslots, 'start_time'));
               if($isFixedSlot==1){
                   $akey=array_search($starttime, $bookedstarttime);
                   
                   if($akey !== false || $skey !== false){
                        continue;
                    }
                }else{
                    if($skey !== false){
                        continue;
                    }
                }
                array_push($timeslots,$timeslot);
               
            }
             
           return $timeslots;
    }
    public function getslotstartdata($slug=null,Request $request){
      //print_r('{"expression":"1"}');exit;
        $staffslug=$request->get('slug');
        $selecteddate=$request->get('date');
        $day=strtolower($request->get('dayname'));
        $isFixedSlot=$request->get('isFixedSlot');
        $sitesettings=Setting::first();
        $staffbuffertime=$sitesettings['buffer_time'];
        $duration=$sitesettings['slot_time'];
        $slots=[];
        
      
        if($staffslug!=0 && $staffslug!=''){
            // print_r("choose staff".$staffslug);exit;
            $staffdata=Admin::where('type','staff')->where('id',$staffslug)->first();
            $staff_id=$staffdata['id'];
            $working_days=explode(',',$staffdata['working_days']);
            $start_time=explode(',',$staffdata['start_time']);
            $end_time=explode(',',$staffdata['end_time']);
            
            $key=array_search($day,$working_days,true);
            if($key === false){
                
                return json_encode($slots);
            }
            $slots=$this->getslotsstart($start_time[$key],$end_time[$key],$duration,$staffbuffertime,$staff_id,$selecteddate,$sitesettings['fixed_time_slot']);
           
        }
        else{
            //print_r("admin".$staffslug);exit;
            
            $staff_id=1;
            if($sitesettings[$day.'_time_from']!="" && $sitesettings[$day.'_time_to']!=""){
            $slots=$this->getslotsstart($sitesettings[$day.'_time_from'],$sitesettings[$day.'_time_to'],$duration,0,1,$selecteddate,$sitesettings['fixed_time_slot']);
            }
            
        }

       return json_encode($slots);
        
    }
}
?>