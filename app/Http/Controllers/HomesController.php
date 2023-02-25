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
use DateTime;
use DateInterval;
use App\Models\Merchant;
use App\Models\Setting;

class HomesController extends Controller {
    
    public function __construct() {
        if(!Session::get('session_city_id')){
            
            $merchants_citie_ids = DB::table('merchants')->where(['status' => 1])->groupBy('city_id')->orderBy('city_id', 'ASC')->pluck('city_id', 'city_id');

            $cityids = array_filter(json_decode(json_encode($merchants_citie_ids),true));
            $cities_obj = DB::table('cities')->where(['status' => 1])->whereIn('id',$cityids)->orderBy('name', 'ASC')->pluck('name', 'id');
            $cities = array_filter(json_decode(json_encode($cities_obj),true)); 
            
        	Session::put('session_city_id', array_keys($cities)[0]);
        }
    	
    }

    public function index() {
      
     // $new_password = $this->encpassword('holovit_admin');
   //   print_r($new_password);exit;
        
        $pageTitle = 'Welcome';
		
		//Header Categories/Business Type
        $featCategories = DB::table('categories')->where([ 'status' => 1, 'parent_id' => 0])->whereNotNull('category_image')->orderBy('category_name', 'ASC')->get()->toArray();
        
		//Banners
		$banners = DB::table('banners')->where([ 'status' => 1])->whereNotNull('banner_image')->orderBy('id', 'ASC')->get()->toArray();
        
		//Services
		$services = DB::table('categories')->where([ 'status' => 1])->where('parent_id','>',0)->where('is_feature',1)->whereNotNull('category_image')->orderBy('category_name', 'ASC')->get()->toArray();
		
		//echo "<pre>"; print_r($services);exit;
		
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
		
		
		//Deals Slider
		$dealsliders = array();
		foreach($featCategories  as $cat){
			$query = new Merchant();
			$query = $query->sortable()->with(['currentDeal']);
			
			$query = $query->where('merchants.status', 1);
			$query = $query->where('merchants.city_id', $selected_city->id);
			$query = $query->where('merchants.city_id', $selected_city->id);
			$query = $query->where('merchants.business_type', $cat->id);
			$query->join('localities', 'localities.id', '=', 'merchants.locality_id');
			$query->join('categories', 'categories.id', '=', 'merchants.business_type');
			$query->join('deals', 'deals.merchant_id', '=', 'merchants.id')
				->where('deals.status', 1)
				->whereDate('expire_date', '>=', date('Y-m-d'))
				->groupBy('merchants.id')
				->select('deals.voucher_price', 'deals.discount', 'deals.final_price', 'merchants.business_type', 'merchants.busineess_name', 'merchants.profile_image', 'merchants.slug', 'localities.locality_name', 'categories.slug as catslug', 'categories.category_name');

			$query->orderBy('merchants.id', 'DESC');
			$limit = 8;
			//$merchant = $query->paginate(5);
			$dealsliders[$cat->id] = $query->get()->toArray();
		}
		
		//Why Buy
		$settings = Setting::where('id', 1)->first();
        
		//Top Brands
		$topBrands = DB::table('merchants')->take(8)->where([ 'status' => 1])->whereNotNull('profile_image')->select(['profile_image','slug'])->orderBy('total_orders', 'DESC')->get()->toArray();
		
		//echo "<pre>"; print_r($topBrands);exit;
		
		//Available In Cities
		//$availableInCities = DB::table('merchants')->join('cities', 'cities.id', '=', 'merchants.city_id')->where(['cities.status' => 1, 'merchants.status' => 1])->whereNotNull('profile_image')->select(['merchants.id','city_id','cities.name','cities.slug'])->orderBy('total_orders', 'DESC')->groupBy('merchants.city_id')->get()->toArray();
		
		//echo "<pre>"; print_r($availableInCities);exit;
		//echo "<pre>"; print_r($dealsliders);exit;
        //echo Session::get('never');die;
        return view('homes.index', ['title' => $pageTitle, 'fixheader' => 1,'featCategories'=>$featCategories,'banners'=>$banners,'services'=>$services,'dealsliders'=>$dealsliders,'topBrands'=>$topBrands,'whyBuy'=>$settings]);



        //$pageTitle = 'Welcome';
        // $featCategories = DB::table('categories')->where([ 'status' => 1])->whereNotNull('category_image')->orderBy('category_name', 'ASC')->get()->toArray();
        //return view('homes.index', ['title' => $pageTitle, 'fixheader' => 1,'featCategories'=>$featCategories]);
    }

    public function home() {

        $pageTitle = 'Welcome';
    }

    public function thank($slug = null) {

        $pageTitle = 'Thanks';
        return view('homes.thank', ['title' => $pageTitle, 'slug' => $slug]);
    }

    public function work() {
        $pageTitle = 'All Works';
        return view('homes.work', ['title' => $pageTitle]);
    }

    public function about() {
        $settings = Setting::where('id', 1)->first();
        $staffdropdown = Admin::where('id', '<>', 1)->where('status', 1)->get()->pluck('first_name', 'id');
        // print_r($staffdropdown);exit;
        $pageTitle = 'About';
        return view('homes.about', ['title' => $pageTitle, 'setting' => $settings, 'staffdropdown' => $staffdropdown]);
    }

    public function services() {
        $pageTitle = 'Services';
        $services = DB::table('services')->where('status', '1')->get();

        return view('homes.services', ['title' => $pageTitle, 'services' => $services]);
    }

    public function experts($slug = null, Request $request) {
        $pageTitle = 'Our Experts';
        $activetab = 'actusers';
        $query = new Admin();
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

    public function expertdetail($slug = null) {
        $pageTitle = "Expert's Detail";
        $expert = Admin::where('type', 'staff')->where('slug', $slug)->where('status', '1')->first();
        $sids = explode(',', $expert->service_ids);
        $services = Service::where('status', 1)->whereIn('id', $sids)
                ->get();
        $servicesName = "";
        foreach ($services as $service) {
            $servicesName = ($servicesName == "") ? $servicesName . $service['name'] : $servicesName . "," . $service['name'];
        }

        return view('homes.expertdetail', ['title' => $pageTitle, 'expert' => $expert, 'services' => $services, 'serviceNames' => $servicesName]);
    }

    public function bookappointment($slug = null) {
        $pageTitle = 'About';
        return view('homes.book_appointment', ['title' => $pageTitle]);
    }

    public function blog() {
        $pageTitle = 'Blog';
        return view('homes.blog', ['title' => $pageTitle]);
    }

    public function testimonial(Request $request) {
        $pageTitle = 'Testimonial';

        $activetab = 'testimonials';
        $query = new Testimonial();
        $query = $query->sortable();
        $testimonils = $query->where('status', '1')->orderBy('id', 'DESC')->paginate(12);
        if ($request->ajax()) {
            return view('homes.testimonils', ['testimonils' => $testimonils]);
        }
        return view('homes.testimonial', ['title' => $pageTitle, $activetab => 1, 'testimonils' => $testimonils]);
    }

    public function contact() {
        $pageTitle = 'Contact';
        return view('homes.contact', ['title' => $pageTitle]);
    }

    public function contactus() {
        $pageTitle = 'Contact';
        $input = Input::all();
        if (!empty($input)) {

            $rules = array(
                'first_name' => 'required|max:20',
                'last_name' => 'required|max:20',
                'phone' => 'required|number|unique:contacts',
                'password' => 'required|min:8',
                'email_address' => 'required|email',
                'message' => 'required',
            );

            $serialisedData = $this->serialiseFormData($input);
            $serialisedData['slug'] = $this->createSlug($input['first_name'] . ' ' . $input['last_name'], 'contacts');

            Contact::insert($serialisedData);
            return Redirect('/');
        }
        return view('homes.contact', ['title' => $pageTitle]);
    }

    public function appointment() {
        $pageTitle = 'Appointment';
        $settings = DB::table('settings')->first();

        $input = Input::all();
        if (!empty($input)) {
            $rules = array(
                'user_id' => 'required',
                'email_address' => 'required|email',
                'contact' => 'required|number',
                'service_ids' => 'required',
                'booking_date_time' => 'required',
                'staff_id' => 'required',
                'description' => 'required',
            );

            $users = DB::table('users')->where('email_address', $input['email_address'])->first();
            $services = DB::table('services')->where('id', $input['service_ids'])->first();

            if ($settings->user_registration == '1') {
                if (!$users) {
                    $userinput = $input;
                    unset($userinput['service_ids']);
                    unset($userinput['booking_date_time']);
                    unset($userinput['staff_id']);
                    unset($userinput['description']);
                    $userinput['first_name'] = $userinput['user_id'];
                    $userinput['password'] = $userinput['first_name'] . '@123';
                    unset($userinput['user_id']);
                    $serialisedData = $this->serialiseFormData($userinput);
                    $serialisedData['slug'] = $this->createSlug($userinput['first_name'], 'users');
                    $serialisedData['status'] = 0;
                    $serialisedData['user_status'] = 'Offline';
                    //$serialisedData['password'] = $input['password'];
                    $serialisedData['password'] = $this->encpassword($userinput['password']);
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
                    Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

                    Session::flash('success_message', "We have sent you an account activation link by email. Please check your spam folder if you do not receive the email within the next few minutes.");

                    $users = DB::table('users')->orderBy('id', 'desc')->first();
                    $serialisedData = $this->serialiseFormData($input);
                    unset($serialisedData['email_address']);
                    unset($serialisedData['contact']);
                    $serialisedData['user_id'] = $users->id;
                    $serialisedData['slug'] = $this->createSlug($users->first_name . ' ' . $users->last_name, 'appointments');
                    $serialisedData['booking_date_time'] = date("Y-m-d H:i:s", strtotime($serialisedData['booking_date_time']));
                    $serialisedData['total_price'] = $services->price;
                    $serialisedData['status'] = 'Pending';
                    $appointment_number = $this->getUniqueModelId($tablename = 'appointments', $fieldname = 'appointment_number', $number_of_digits = 8, $token_type = '1', $prefix = 'ABS');
                    $serialisedData['appointment_number'] = $appointment_number;
                    Appointment::insert($serialisedData);
                    $last = DB::table('appointments')->orderBy('id', 'DESC')->first();
                    $adminInfo = DB::table('admins')->first();
                    if ($last->staff_id == 1) {
                        $name = $input['user_id'];
                        $emailId = $adminInfo->email;
                        $contact = $input['contact'];
                        $serviceinfo = DB::table('services')->select('name')->where('id', $input['service_ids'])->first();
                        $service = $serviceinfo->name;
                        $booking_date_time = date("Y-m-d H:i:s", strtotime($input['booking_date_time']));

                        $emailTemplate = DB::table('emailtemplates')->where('id', 10)->first();
                        $toRepArray = array('[!name!]', '[!email!]', '[!contact!]', '[!service!]', '[!booking_date_time!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                        $fromRepArray = array($name, $emailId, $contact, $service, $booking_date_time, HTTP_PATH, SITE_TITLE);
                        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                        Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
                    }

                    return json_encode($last);
                } else {
                    $serialisedData = $this->serialiseFormData($input);
                    unset($serialisedData['email_address']);
                    unset($serialisedData['contact']);
                    $serialisedData['user_id'] = $users->id;
                    $serialisedData['slug'] = $this->createSlug($users->first_name . ' ' . $users->last_name, 'appointments');
                    $appointment_number = $this->getUniqueModelId($tablename = 'appointments', $fieldname = 'appointment_number', $number_of_digits = 8, $token_type = '1', $prefix = 'ABS');
                    $serialisedData['appointment_number'] = $appointment_number;
                    $serialisedData['booking_date_time'] = date("Y-m-d H:i:s", strtotime($serialisedData['booking_date_time']));
                    $serialisedData['total_price'] = $services->price;
                    $serialisedData['status'] = 'Pending';


                    Appointment::insert($serialisedData);

                    $last = DB::table('appointments')->orderBy('id', 'DESC')->first();
                    $adminInfo = DB::table('admins')->first();

                    if ($last->staff_id == 1) {
                        $name = $input['user_id'];
                        $emailId = $adminInfo->email;
                        $contact = $input['contact'];
                        $serviceinfo = DB::table('services')->select('name')->where('id', $input['service_ids'])->first();
                        $service = $serviceinfo->name;
                        $booking_date_time = date("Y-m-d H:i:s", strtotime($input['booking_date_time']));

                        $emailTemplate = DB::table('emailtemplates')->where('id', 10)->first();
                        $toRepArray = array('[!name!]', '[!email!]', '[!contact!]', '[!service!]', '[!booking_date_time!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                        $fromRepArray = array($name, $emailId, $contact, $service, $booking_date_time, HTTP_PATH, SITE_TITLE);
                        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);

                        Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
                    }
                    $session = "Your appointment details save succeessfully";

                    return json_encode($last);
                }
            } else {

                $serialisedData = $this->serialiseFormData($input);
                unset($serialisedData['email_address']);
                unset($serialisedData['contact']);
                $serialisedData['slug'] = $this->createSlug($input['user_id'] . ' ', 'appointments');
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

                if ($last->staff_id == 1) {
                    $name = $input['user_id'];
                    $emailId = $adminInfo->email;
                    $contact = $input['contact'];
                    $serviceinfo = DB::table('services')->select('name')->where('id', $input['service_ids'])->first();
                    $service = $serviceinfo->name;
                    $booking_date_time = date("Y-m-d H:i:s", strtotime($input['booking_date_time']));

                    $emailTemplate = DB::table('emailtemplates')->where('id', 10)->first();
                    $toRepArray = array('[!name!]', '[!email!]', '[!contact!]', '[!service!]', '[!booking_date_time!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                    $fromRepArray = array($name, $emailId, $contact, $service, $booking_date_time, HTTP_PATH, SITE_TITLE);
                    $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                    $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);

                    Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));
                }

                return json_encode($last);
            }
        }


        return view('homes.about', ['title' => $pageTitle]);
    }

    public function never(Request $request) {
        Session::put('never', 1);
        Session::save();
//        echo Session::get('never');
        exit;
    }

    public function setLocationInSession(Request $request) {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ipAddr = trim($ip);
                }
            }
        }
        if (empty($ipAddr)) {
            $ipAddr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Unknown';
        }

        $country = @unserialize(file_get_contents("http://ip-api.com/php/" . $ipAddr));
        $countryName = $country['country'];
        $regionName = $country['city'];

        if ($countryName != "" && $regionName != "") {
            echo "1";
            $location = DB::table('locations')
                    ->where('location_name', 'like', $countryName)
                    ->orWhere('location_name', 'like', $regionName)
                    ->first();

            if ($location) {
//                echo "1";
                Session::put('countryName', $country['country']);
                Session::put('regionName', $country['city']);
                Session::put('locationid', $location->id);
                Session::save();
            }

            exit;
        } else {
            echo "0";
            exit;
        }
        exit;
    }

}

?>