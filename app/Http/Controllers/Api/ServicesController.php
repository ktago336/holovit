<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Cookie;
use Session;
use Redirect;
use Input;
use Validator;
use DB;
use Mail;
use App\Mail\SendMailable;
use Socialite;
use App\Models\User;
use App\Models\Service;
use App\Models\Servicesoffer;
use App\Models\Servicemessage;
use App\Models\Myorder;
use App\Models\Image;
use App\Models\Gig;
use App\Models\Review;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Wallet;

class ServicesController extends Controller {

    public function __construct() {
        
    }

    public function create() {
        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $title = $userData['title'];

        if (isset($_FILES['attachment']) && $_FILES['attachment']['tmp_name'] != '') {
            $file = $_FILES['attachment'];
            $file = Input::file('attachment');

            $uploadedFileName = $this->uploadImage($file, SERVICE_FULL_UPLOAD_PATH);

            $userData['attachment'] = $uploadedFileName;
        } else {
            unset($userData['attachment']);
        }

        $serialisedData = $this->serialiseFormData($userData);
        $serialisedData['slug'] = $this->createSlug($title, 'services');
        $serialisedData['status'] = 1;
        $serialisedData['user_id'] = $userid;
//        echo '<pre>';print_r($tokenData);print_r($serialisedData);die;
        Service::insert($serialisedData);

        $this->successOutput('Your service request posted successfully.');
    }

    public function edit() {
        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $title = $userData['title'];
        $id = $userData['id'];

        if (isset($_FILES['attachment']) && $_FILES['attachment']['tmp_name'] != '') {
            $file = $_FILES['attachment'];
            $file = Input::file('attachment');

            $uploadedFileName = $this->uploadImage($file, SERVICE_FULL_UPLOAD_PATH);

            $userData['attachment'] = $uploadedFileName;
        } else {
            unset($userData['attachment']);
        }

        $serialisedData = $this->serialiseFormData($userData);
        $serialisedData['slug'] = $this->createSlug($title, 'services');
        $serialisedData['status'] = 1;
//        $serialisedData['user_id'] =  $userid;

        Service::where('id', $id)->update($serialisedData);

        $this->successOutput('Your service request edit successfully.');
    }

    public function delete() {
        $tokenData = $this->requestAuthentication('POST', 0);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $id = $userData['id'];
        if ($id) {
            Service::where('id', $id)->delete();
            $this->successOutput('Service Deleted Successfully');
        }
    }

    public function listing() {

        $tokenData = $this->requestAuthentication('GET', 1);

        $userid = $tokenData['user_id'];
//        $data = $_POST['jsonData'];
//        $userData = json_decode($data, true);
        global $serviceDays;


        $query = new Service();
        //$query = $query->with('User');
//        $query = $query->where('status', 1);        
        $query = $query->where('user_id', $userid);

        $serviceLists = $query->orderBy('id', 'DESC')->get();
        $i = 0;
        $data = array();
        if (!empty($serviceLists)) {
            foreach ($serviceLists as $key => $val) {
                $data[$i]['id'] = $val->id;
                $data[$i]['title'] = $val->title;
                $data[$i]['name'] = $val->User->first_name . ' ' . $val->User->last_name;
                $data[$i]['description'] = $val->description;
                $data[$i]['category_id'] = $val->Category->id;
                $data[$i]['category'] = $val->Category->name;
                if ($val->subcategory_id) {
                    $data[$i]['subcategory_id'] = $val->subcategory_id;
                    $data[$i]['subcategory'] = $val->Subcategory->name;
                } else {
                    $data[$i]['subcategory_id'] = '';
                    $data[$i]['subcategory'] = '';
                }

                $data[$i]['price'] = $val->price;
                $data[$i]['offers'] = count($val->Servicesoffer);
                $data[$i]['date'] = $val->created_at->format('d M, Y');
                if ($val->day) {
                    $data[$i]['delivered_in'] = $serviceDays[$val->day];
                } else {
                    $data[$i]['delivered_in'] = 0;
                }

                $data[$i]['attachment'] = $val->attachment;
                $data[$i]['status'] = $val->status;

                $i++;
            }
        }


        $this->successOutputResult('Services Listng', json_encode($data));
    }

    public function activelist() {

        $tokenData = $this->requestAuthentication('GET', 0);
        $userid = $tokenData['user_id'];

        global $serviceDays;

        $query = new Service();
        $query = $query->where('user_id', '!=', $userid)->where('status', 1);

        $serviceLists = $query->orderBy('id', 'DESC')->get();
        $i = 0;
        $data = array();
        if (!empty($serviceLists)) {
            foreach ($serviceLists as $key => $val) {
                $data[$i]['id'] = $val->id;
                $data[$i]['title'] = $val->title;
                $data[$i]['name'] = $val->User ? $val->User->first_name . ' ' . $val->User->last_name : '';
                $data[$i]['description'] = $val->description;
                $data[$i]['category'] = $val->Category ? $val->Category->name : '';
                $data[$i]['subcategory'] = $val->Subcategory ? $val->Subcategory->name : '';
                $data[$i]['price'] = $val->price;
                $data[$i]['offers'] = count($val->Servicesoffer);
                $data[$i]['date'] = $val->created_at->format('d M, Y');
                $data[$i]['delivered_in'] = $serviceDays[$val->day];
                $data[$i]['attachment'] = $val->attachment;
                $data[$i]['buyer_image'] = $val->User ? $val->User->profile_image : '';

                $i++;
            }
        }


        $this->successOutputResult('Active Services Listing', json_encode($data));
    }

    public function detail() {
        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $id = $userData['id'];
        global $serviceDays;

        $recordInfo = Service::where('id', $id)->first();

        $data = array();
        $data['id'] = $recordInfo->id;
        $data['date'] = $recordInfo->created_at->format('d M, Y');
        $data['buyer_name'] = $recordInfo->User->first_name . ' ' . $recordInfo->User->last_name;
        $data['buyer_id'] = $recordInfo->User->id;
        $data['title'] = $recordInfo->title;
        $data['category'] = $recordInfo->Category->name;
        $data['subcategory'] = $recordInfo->Subcategory->name;


        $data['description'] = $recordInfo->description;
        $data['buyer_image'] = $recordInfo->User->profile_image;
        $data['buyer_average_rating'] = $recordInfo->User->average_rating;
        $data['buyer_total_review'] = $recordInfo->User->total_review;
        $data['about_seller'] = $recordInfo->User->description;
        $data['posted_on'] = $recordInfo->created_at->diffForHumans();
        $data['delivered_in'] = $serviceDays[$recordInfo->day];
        $data['offers'] = count($recordInfo->Servicesoffer);
        $data['price'] = $recordInfo->price;
        $data['attachment'] = $recordInfo->attachment;

        $farray = array();
        $langArray = array();
        if (isset($recordInfo->User->city) && $recordInfo->User->city != '') {
            $farray[] = $recordInfo->User->city;
        }
        if (isset($recordInfo->User->Country->name) && $recordInfo->User->Country->name != '') {
            $farray[] = $recordInfo->User->Country->name;
        }
        $data['buyer_from'] = implode(', ', $farray);
        $data['member_since'] = date('F Y', strtotime($recordInfo->User->created_at));
        if ($recordInfo->User->languages) {
            foreach (json_decode($recordInfo->User->languages) as $key => $lang) {
                $langArray[$key] = $lang->lang_name;
            }
        }
        $data['languages'] = implode(', ', $langArray);
        //$userid = $recordInfo->User->id;
        $query = new Servicesoffer();
        $query = $query->where('user_id', $userid);
        $query = $query->where('service_id', $recordInfo->id);
        $offer = $query->orderBy('id', 'DESC')->first();

        if ($offer) {
            $val = $offer;
            //echo '<pre>';print_r($val);
            $i = 'offer';
            if ($val->Service->price > 0) {
                $price = number_format($val->Service->price, 2);
            } else {
                $price = 'N/A';
            }
            $data[$i]['budget'] = $price;
            $data[$i]['offer'] = number_format($val->amount, 2);
            $data[$i]['delivered_in'] = $val->deliver_in;
            $data[$i]['revisions'] = $val->revisions;
            $data[$i]['message'] = $val->message;
        } else {
            $i = 'offer';
            $data[$i]['budget'] = '';
            $data[$i]['offer'] = '';
            $data[$i]['delivered_in'] = '';
            $data[$i]['revisions'] = '';
            $data[$i]['message'] = '';
        }

        $this->successOutputResult('Request Detail', json_encode($data));
        exit;
    }

    public function offersentlist() {

        $tokenData = $this->requestAuthentication('GET', 1);
        $userid = $tokenData['user_id'];

        $query = new Servicesoffer();
        $query = $query->where('user_id', $userid);

        $allrecords = $query->orderBy('id', 'DESC')->get();
        $i = 0;
        $data = array();
        if (!empty($allrecords)) {
            foreach ($allrecords as $key => $val) {
                $data[$i]['id'] = $val->id;
                $data[$i]['title'] = $val->Service?$val->Service->title:'';
                $data[$i]['name'] = $val->User?$val->User->first_name . ' ' . $val->User->last_name:'';
                if ($val->Service && $val->Service->price > 0) {
                    $price = CURR . number_format($val->Service->price, 2);
                } else {
                    $price = 'N/A';
                }
                $data[$i]['budget'] = $price;
                $data[$i]['offer'] = CURR . number_format($val->amount, 2);
                $data[$i]['date'] = $val->created_at->format('d M, Y');
                $data[$i]['delivered_in'] = $val->deliver_in;
                $data[$i]['revisions'] = $val->revisions;
                if ($val->Service && $val->Service->status == 5 && ($val->status == 0 || $val->status == 2)) {
                    $status = 'Rejected';
                } elseif ($val->Service && $val->Service->status == 5 && $val->status == 1) {
                    $status = 'Accepted';
                } else {
                    $status = 'Pending';
                }

                $data[$i]['status'] = $status;
                $i++;
            }
        }


        $this->successOutputResult('Sent Offer Listing', json_encode($data));
    }

    public function offersent() {

        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $data = $_POST['jsonData'];
        $userData = json_decode($data, true);
        $service_id = $userData['service_id'];
        $amount = $userData['amount'];
        $deliver_in = $userData['deliver_in'];
        $revisions = $userData['revisions'];
        $message = $userData['message'];

        $serviceInfo = Service::where('id', $service_id)->first();
        if ($serviceInfo) {
            $loginUserInfo = User::where('id', $userid)->first();
            $oldoffer = Servicesoffer::where(['user_id' => $userid, 'service_id' => $serviceInfo->id])->first();
            if ($oldoffer) {
                $serialisedData = $this->serialiseFormData($userData);
                unset($serialisedData['service_slug']);
                Servicesoffer::where('id', $oldoffer->id)->update($serialisedData);
            } else {

                $serialisedData = $this->serialiseFormData($userData);
                $serialisedData['slug'] = bin2hex(openssl_random_pseudo_bytes(25));
                $serialisedData['status'] = 0;
                $serialisedData['service_user_id'] = $serviceInfo->user_id;
                $serialisedData['service_id'] = $serviceInfo->id;
                $serialisedData['time'] = time();
                $serialisedData['user_id'] = $userid;
                unset($serialisedData['service_slug']);
//                echo '<pre>';print_r($serialisedData);die;
                Servicesoffer::insert($serialisedData);

                $title = $serviceInfo->title;
                $username = $serviceInfo->User?$serviceInfo->User->first_name . ' ' . $serviceInfo->User->last_name:'';
                $name = $loginUserInfo->first_name . ' ' . $loginUserInfo->last_name;
                $amount = CURR . $serialisedData['amount'];
                $deliver_in = $serialisedData['deliver_in'] . 'dyas';
                $message = nl2br($serialisedData['message']);

                $emailId = $serviceInfo->User?$serviceInfo->User->email_address:'';
                if($emailId){
                  $emailTemplate = DB::table('emailtemplates')->where('id', 7)->first();
                $toRepArray = array('[!username!]', '[!title!]', '[!name!]', '[!amount!]', '[!deliver_in!]', '[!message!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
                $fromRepArray = array($username, $title, $name, $amount, $deliver_in, $message, HTTP_PATH, SITE_TITLE);
                $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
                $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
                Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));   
                }
               
            }
        }


        $this->successOutput('Offer Send Successfully');
    }


    public function markcompleted() {

        $tokenData = $this->requestAuthentication('POST', 0);
        $userid = $tokenData['user_id'];
        $data = $_POST['jsonData'];
        $userData = json_decode($data, true);
        $service_id = $userData['service_id'];

        $servicesofferInfo = Servicesoffer::where('id', $service_id)->first();
        $serviceInfo = Service::where('id', $servicesofferInfo->service_id)->first();

        Service::where('id', $serviceInfo->id)->update(['is_completed' => 1]);

        $settingsInfo = DB::table('settings')->where('id', 1)->first();
        $admin_commission = $settingsInfo->admin_commission;

        $amount = $servicesofferInfo->amount;
        $admin_commission = round($amount * $admin_commission / 100, 2);
        $revenue = $amount - $admin_commission;

        $transactionId = strtoupper(date('Ymd') . bin2hex(openssl_random_pseudo_bytes(5)));
        $serialisedData = array();
        $serialisedData['user_id'] = $servicesofferInfo->user_id;
        $serialisedData['service_id'] = $servicesofferInfo->service_id;
        $serialisedData['amount'] = $amount;
        $serialisedData['revenue'] = $revenue;
        $serialisedData['admin_commission'] = number_format($admin_commission, 2, '.', '');
        $serialisedData['trn_id'] = $transactionId;
        $serialisedData['slug'] = bin2hex(openssl_random_pseudo_bytes(20));
        $serialisedData['type'] = 0;
        $serialisedData['add_minus'] = 1;
        $serialisedData['status'] = 1;
        $serialisedData['source'] = 'From Request: <b>' . $serviceInfo->title . '</b>';
        Wallet::insert($serialisedData);

        $loginUserInfo = User::where('id', $userid)->first();
        $loginuser = $loginUserInfo->first_name . ' ' . $loginUserInfo->last_name;
        $selleruser = $servicesofferInfo->User->first_name . ' ' . $servicesofferInfo->User->last_name;
        $title = $serviceInfo->title;

        $emailId = $servicesofferInfo->User->email_address;
        $emailTemplate = DB::table('emailtemplates')->where('id', 19)->first();
        $toRepArray = array('[!username!]', '[!title!]', '[!loginuser!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
        $fromRepArray = array($selleruser, $title, $loginuser, HTTP_PATH, SITE_TITLE);
        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
        Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

        $datetime = date('m d, Y');
        $amount = CURR . number_format($amount, 2);
        $emailId = $servicesofferInfo->User->email_address;
        $emailTemplate = DB::table('emailtemplates')->where('id', 20)->first();
        $toRepArray = array('[!username!]', '[!title!]', '[!amount!]', '[!transactionId!]', '[!datetime!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
        $fromRepArray = array($selleruser, $title, $amount, $transactionId, $datetime, HTTP_PATH, SITE_TITLE);
        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
        Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

        $this->successOutput('Offer Completed Successfully');
    }

    public function viewoffer() {
        $tokenData = $this->requestAuthentication('POST', 0);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $id = $userData['id'];
        global $serviceDays;

        $recordInfo = Service::where('id', $id)->first();

        $data = array();
        $data['id'] = $recordInfo->id;
        $data['date'] = $recordInfo->created_at->format('d M, Y');
        $data['buyer_name'] = $recordInfo->User->first_name . ' ' . $recordInfo->User->last_name;
        $data['buyer_id'] = $recordInfo->User->id;
        $data['title'] = $recordInfo->title;


        $data['description'] = $recordInfo->description;
        $data['buyer_image'] = $recordInfo->User->profile_image;
        $data['buyer_average_rating'] = $recordInfo->User->average_rating;
        $data['buyer_total_review'] = $recordInfo->User->total_review;
        $data['about_seller'] = $recordInfo->User->description;
        $data['posted_on'] = $recordInfo->created_at->diffForHumans();
        $data['delivered_in'] = $serviceDays[$recordInfo->day];
        $data['offers'] = count($recordInfo->Servicesoffer);
        $data['price'] = $recordInfo->price;
        $data['attachment'] = $recordInfo->attachment;

        $farray = array();
        if (isset($recordInfo->User->city) && $recordInfo->User->city != '') {
            $farray[] = $recordInfo->User->city;
        }
        if (isset($recordInfo->User->Country->name) && $recordInfo->User->Country->name != '') {
            $farray[] = $recordInfo->User->Country->name;
        }
        $data['buyer_from'] = implode(', ', $farray);
        $data['member_since'] = date('F Y', strtotime($recordInfo->User->created_at));
        if ($recordInfo->User->languages) {
            foreach (json_decode($recordInfo->User->languages) as $key => $lang) {
                $langArray[$key] = $lang->lang_name;
            }
        }
        $data['languages'] = implode(', ', $langArray);

        $alloffers = Servicesoffer::where('service_id', $recordInfo->id)->orderBy('status', 'DESC')->orderBy('id', 'DESC')->get();
        if ($alloffers) {
            $i = 0;
            global $offerstatusbuyer;
            foreach ($alloffers as $alloffer) {
                $data['offer'][$i]['id'] = $alloffer->id;
                $data['offer'][$i]['offer_date'] = $alloffer->created_at->format('d M, Y');
                $data['offer'][$i]['user_name'] = $alloffer->User->first_name . ' ' . $alloffer->User->last_name;
                $data['offer'][$i]['offer_amount'] = $alloffer->amount;
                $data['offer'][$i]['deliver_in'] = $alloffer->deliver_in;
                $data['offer'][$i]['revisions'] = $alloffer->revisions;
                $data['offer'][$i]['message'] = $alloffer->message;
                $data['offer'][$i]['status'] = $offerstatusbuyer[$alloffer->status];
                $i++;
            }
        }

        $this->successOutputResult('Request Offers', json_encode($data));
        exit;
    }

    public function workplace() {
        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
        $id = $userData['id'];
        global $serviceDays;

        $servicesofferInfo = Servicesoffer::where('id', $id)->first();
        $recordInfo = Service::where('id', $servicesofferInfo->service_id)->first();


        $data = array();

        $data['id'] = $recordInfo->id;
        $data['service_title'] = $recordInfo->service_title;
        $data['category_name'] = $recordInfo->Category->name;
        $data['subcategory_name'] = $recordInfo->Subcategory->name;
        $data['date'] = $recordInfo->created_at->format('d M, Y');
        $data['buyer_name'] = $servicesofferInfo->User->first_name . ' ' . $servicesofferInfo->User->last_name;
        $data['buyer_id'] = $servicesofferInfo->User->id;
        $data['title'] = $recordInfo->title;
        if ($recordInfo->price) {
            $budget = number_format($recordInfo->price, 2);
        } else {
            $budget = 'N/A';
        }
        $data['budget'] = $budget;

        $settingsInfo = DB::table('settings')->where('id', 1)->first();
        $admin_commission = $settingsInfo->admin_commission;
        $servicefree = round(($servicesofferInfo->amount * $admin_commission / 100), 2);
        $payamount = $servicesofferInfo->amount + $servicefree;
//        echo $userid;die;

        $amountArray = $this->getWallerAmount($userid);
        $wall_bal = number_format($amountArray['availableforwithdraw'], 2);

        $data['description'] = $recordInfo->description;
        $data['buyer_image'] = $servicesofferInfo->User->profile_image;
        $data['buyer_average_rating'] = $servicesofferInfo->User->average_rating;
        $data['buyer_total_review'] = $servicesofferInfo->User->total_review;
        $data['about_seller'] = $servicesofferInfo->User->description;
        $data['posted_on'] = $recordInfo->created_at->diffForHumans();
        $data['delivered_in'] = $servicesofferInfo->deliver_in;
        $data['revision'] = $servicesofferInfo->revisions;
        $data['offers'] = count($recordInfo->Servicesoffer);
        $data['offer_amount'] = $servicesofferInfo->amount;
        $data['service_fee'] = $servicefree;
        $data['total_amount'] = $payamount;
        $data['wallet_balance'] = $wall_bal;
        $data['offer_date'] = $servicesofferInfo->created_at->format('d M, Y h:iA');
        $data['offer_message'] = $servicesofferInfo->message;
        $data['attachment'] = $recordInfo->attachment;




        $farray = array();
        if (isset($servicesofferInfo->User->city) && $servicesofferInfo->User->city != '') {
            $farray[] = $servicesofferInfo->User->city;
        }
        if (isset($servicesofferInfo->User->Country->name) && $servicesofferInfo->User->Country->name != '') {
            $farray[] = $servicesofferInfo->User->Country->name;
        }
        $data['buyer_from'] = implode(', ', $farray);
        $data['member_since'] = date('F Y', strtotime($servicesofferInfo->User->created_at));
        $langArray = array();
        if ($servicesofferInfo->User->languages) {
            foreach (json_decode($servicesofferInfo->User->languages) as $key => $lang) {
                $langArray[$key] = $lang->lang_name;
            }
        }
        $data['languages'] = implode(', ', $langArray);

        $this->successOutputResult('Request Detail', json_encode($data));
        exit;
    }

    public function sendmessage() {

        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $data = $_POST['jsonData'];
        $userData = json_decode($data, true);
        $id = $userData['serviceoffer_id'];
        $message = $userData['message'];

        $servicesofferInfo = Servicesoffer::where('id', $id)->first();
        $serviceInfo = Service::where('id', $servicesofferInfo->service_id)->first();
//        echo '<pre>offer'; print_r($servicesofferInfo);
//        echo '<pre>serv'; print_r($serviceInfo);die;
        if ($serviceInfo) {
            $sender_id = $userid;
            if ($servicesofferInfo->service_user_id == $userid) {
                $receiver_id = $servicesofferInfo->user_id;
                $sender_name = $serviceInfo->User->first_name . ' ' . $serviceInfo->User->last_name;
            } else {
                $receiver_id = $servicesofferInfo->service_user_id;
                $sender_name = $servicesofferInfo->User->first_name . ' ' . $servicesofferInfo->User->last_name;
            }

            if (isset($_FILES['attachment']) && $_FILES['attachment']['tmp_name'] != '') {
                $file = $_FILES['attachment'];
                $file = Input::file('attachment');
                $uploadedFileName = $this->uploadImage($file, GIG_MSG_FULL_UPLOAD_PATH);
                $attachment = $uploadedFileName;
            } else {
                $attachment = '';
            }

            $serialisedData = array();
            $serialisedData['service_id'] = $serviceInfo->id;
            $serialisedData['servicesoffer_id'] = $servicesofferInfo->id;
            $serialisedData['sender_id'] = $sender_id;
            $serialisedData['receiver_id'] = $receiver_id;
            $serialisedData['message'] = $message;
            $serialisedData['attachment'] = $attachment;
            $serialisedData['status'] = 0;
            $serialisedData['time'] = time();
            $serialisedData['slug'] = $serviceInfo->id . $sender_id . $receiver_id . time() . rand(10, 99);
            $serialisedData = $this->serialiseFormData($serialisedData);
            Servicemessage::insert($serialisedData);
        }
        $data = $this->getmessages($userid, $id);

        $this->successOutputResult('Your messages sent successfully.', json_encode($data));
    }

    public function getmessages($userid, $servicesoffer_id) {
        $servicemessages = Servicemessage::where('servicesoffer_id', $servicesoffer_id)->orderBy('id', 'ASC')->get();
        $i = 0;
        $data = array();
        if ($servicemessages) {
            foreach ($servicemessages as $message) {
                if ($message->receiver_id == $userid) {
                    if ($message->Sender && $message->Sender->profile_image) {
                        $data[$i]['user_image'] = $message->Sender->profile_image;
                    } else {
                        $data[$i]['user_image'] = '';
                    }
                } else {
                    if ($message->Receiver && $message->Receiver->profile_image) {
                        $data[$i]['user_image'] = $message->Receiver->profile_image;
                    } else {
                        $data[$i]['user_image'] = '';
                    }
                }
                $data[$i]['user_name'] = $message->Sender ? $message->Sender->first_name . ' ' . $message->Sender->last_name : "";
                $data[$i]['time'] = $message->created_at->format('d M, Y h:iA');
                $data[$i]['message'] = $message->message;
                $data[$i]['sender_id'] = $message->sender_id;
                $data[$i]['receiver_id'] = $message->receiver_id;
                if ($message->attachment && file_exists(GIG_MSG_FULL_UPLOAD_PATH . $message->attachment)) {
                    $data[$i]['attachment'] = substr($message->attachment, 9);
                } else {
                    $data[$i]['attachment'] = '';
                }
                $i++;
            }
        }
        return $data;
    }

    public function messagelist() {

        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $reqData = $_POST['jsonData'];
        $userData = json_decode($reqData, true);
//        print_r($userData);
//        echo $userData['serviceoffer_id'];
//        die;
        $servicesoffer_id = $userData['serviceoffer_id'];

        $data = $this->getmessages($userid, $servicesoffer_id);


        $this->successOutputResult('Message Listing', json_encode($data));
    }

      
    
    public function acceptrejectoffer() {

        $tokenData = $this->requestAuthentication('POST', 1);
        $userid = $tokenData['user_id'];
        $data = $_POST['jsonData'];
        $reqData = json_decode($data, true);
        $serviceoffer_id = $reqData['serviceoffer_id'];
        $type = $reqData['type'];

        if ($type == 1) {

            $this->paymentonacceptoffer($userid, $reqData);
//            $servicesofferInfo = Servicesoffer::where('service_id', $service_id)->first();
//            $serviceInfo = Service::where('id', $servicesofferInfo->service_id)->first();
//
//            Servicesoffer::where('id', $servicesofferInfo->id)->update(array('status'=>1));
//
//            $title = $serviceInfo->title;
//            $username = $servicesofferInfo->User->first_name . ' ' . $servicesofferInfo->User->last_name;
//
//            $emailId = $servicesofferInfo->User->email_address;
//            $emailTemplate = DB::table('emailtemplates')->where('id', 9)->first();
//            $toRepArray = array('[!username!]', '[!title!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
//            $fromRepArray = array($username, $title, HTTP_PATH, SITE_TITLE);
//            $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
//            $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
//            Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));
        } elseif ($type == 2) {
            $servicesofferInfo = Servicesoffer::where('id', $serviceoffer_id)->first();
            $serviceInfo = Service::where('id', $servicesofferInfo->service_id)->first();

            Servicesoffer::where('id', $serviceoffer_id)->update(array('status' => 2));

            $title = $serviceInfo->title;
            $username = $servicesofferInfo->User->first_name . ' ' . $servicesofferInfo->User->last_name;

            $emailId = $servicesofferInfo->User->email_address;
            $emailTemplate = DB::table('emailtemplates')->where('id', 8)->first();
            $toRepArray = array('[!username!]', '[!title!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
            $fromRepArray = array($username, $title, HTTP_PATH, SITE_TITLE);
            $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
            $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
            Mail::to($emailId)->send(new SendMailable($emailBody, $emailSubject));

            $this->successOutput('Offer Rejected Successfully');
        }
    }

    public function paymentonacceptoffer($userid, $reqData) {
//        $tokenData = $this->requestAuthentication('POST', 1);
//        $userid = $tokenData['user_id'];

        $data = $_POST['jsonData'];
        $reqData = json_decode($data, true);
        $serviceoffer_id = $reqData['serviceoffer_id'];
        $servicesofferInfo = Servicesoffer::where('id', $serviceoffer_id)->first();
        $serviceInfo = Service::where('id', $servicesofferInfo->service_id)->first();
        $isPayByWallet = $reqData['isPayByWallet'];

        $siteSettings = DB::table('settings')->where('id', 1)->first();
        $admin_commision = $siteSettings->admin_commission;

        $service_id = $servicesofferInfo->service_id;
        $adminAmount = round(($servicesofferInfo->amount * $admin_commision / 100), 2);
        $total_amount = $servicesofferInfo->amount + $adminAmount;

        $pay_type = "";
        $transaction_id = '';
        if ($isPayByWallet) {
            $pay_type = 'Wallet';
            $transaction_id = strtoupper(bin2hex(openssl_random_pseudo_bytes(8)));

            // Deduct amount to buyer wallet        
            $serialisedData = array();
            $serialisedData['user_id'] = $userid;
            $serialisedData['service_id'] = $service_id;
            $serialisedData['amount'] = $servicesofferInfo->amount;
            $serialisedData['revenue'] = -$total_amount;
            $serialisedData['admin_commission'] = $adminAmount;
            $serialisedData['trn_id'] = $transaction_id;
            $serialisedData['slug'] = bin2hex(openssl_random_pseudo_bytes(20));
            $serialisedData['type'] = 1;
            $serialisedData['add_minus'] = 0;
            $serialisedData['source'] = 'Pay for Service Accept: <b>' . $serviceInfo->title . '</b>';
            $serialisedData['status'] = 1;
            Wallet::insert($serialisedData);
        } else {
            $pay_type = 'Paypal';
            $transaction_id = $reqData['nonce_id'];

            $serialisedData = array();
            $serialisedData['user_id'] = $userid;
            $serialisedData['order_slug'] = bin2hex(openssl_random_pseudo_bytes(30));
            $serialisedData['order_number'] = $transaction_id;
            $serialisedData['slug'] = bin2hex(openssl_random_pseudo_bytes(30));
            $serialisedData['status'] = 1;
            $serialisedData['amount'] = $total_amount;
            $serialisedData['service_id'] = $service_id;
            $serialisedData['serviceoffer_id'] = $serviceoffer_id;
            $serialisedData['transaction_id'] = $transaction_id;
            Payment::insert($serialisedData);
        }
        Servicesoffer::where('id', $serviceoffer_id)->update(array('status' => 1, 'total_amount' => $total_amount, 'admin_amount' => $adminAmount));
        Service::where('id', $service_id)->update(array('status' => 5, 'payment_status' => 1, 'pay_type' => $pay_type, 'serviceoffer_slug' => $servicesofferInfo->slug));

        // Email sent to login user
        $loginUserInfo = User::where('id', $userid)->first();
        $loginuser = $loginUserInfo->first_name . ' ' . $loginUserInfo->last_name;
        $amount = CURR . $total_amount;
        $datetime = date('M d, Y');
        $title = $serviceInfo->title;

        $emailId = $loginUserInfo->email_address;
        $emailTemplate = DB::table('emailtemplates')->where('id', 10)->first();
        $toRepArray = array('[!username!]', '[!title!]', '[!amount!]', '[!transactionId!]', '[!paymenttype!]', '[!datetime!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
        $fromRepArray = array($loginuser, $title, $amount, $transaction_id, $pay_type, $datetime, HTTP_PATH, SITE_TITLE);
        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
            Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));
        // Email sent to admin user
        $adminInfo = DB::table('admins')->where('id', 1)->first();
        $emailId = $adminInfo->email;
        $emailTemplate = DB::table('emailtemplates')->where('id', 11)->first();
        $toRepArray = array('[!username!]', '[!title!]', '[!amount!]', '[!transactionId!]', '[!paymenttype!]', '[!datetime!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
        $fromRepArray = array($loginuser, $title, $amount, $transaction_id, $pay_type, $datetime, HTTP_PATH, SITE_TITLE);
        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
            Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));
        // Email sent to seller user
        $sellerInfo = User::where('id', $servicesofferInfo->user_id)->first();
        $emailId = $sellerInfo->email_address;
        $sellername = $sellerInfo->first_name . ' ' . $sellerInfo->last_name;

        $emailTemplate = DB::table('emailtemplates')->where('id', 12)->first();
        $toRepArray = array('[!username!]', '[!title!]', '[!amount!]', '[!transactionId!]', '[!paymenttype!]', '[!datetime!]', '[!sellername!]', '[!HTTP_PATH!]', '[!SITE_TITLE!]');
        $fromRepArray = array($loginuser, $title, $amount, $transaction_id, $pay_type, $datetime, $sellername, HTTP_PATH, SITE_TITLE);
        $emailSubject = str_replace($toRepArray, $fromRepArray, $emailTemplate->subject);
        $emailBody = str_replace($toRepArray, $fromRepArray, $emailTemplate->template);
            Mail::to($emailId)->send(new SendMailable($emailBody,$emailSubject));

        $this->successOutput('Offer Accepted Successfully');
    }

}

?>