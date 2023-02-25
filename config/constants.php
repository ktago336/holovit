<?php

$siteSetting =  App\Http\Controllers\Controller::getSiteSetting();

//print_r($siteSetting);exit;
define('SITE_TITLE', $siteSetting->site_title);
define('TITLE_FOR_LAYOUT', ' :: ' . SITE_TITLE);
define('HTTP_PATH', "https://holovit.ru");
define("BASE_PATH", $_SERVER['DOCUMENT_ROOT']);
//define('MAIL_FROM', 'social.test@logicspice.com');

define('MAIL_FROM', 'support@holovit.ru');
define('API_KEY', 'LSA0E14C740345345O14UPO9946N');

define('CAPTCHA_KEY', '6LfUJ40kAAAAALMwuUQj96zaMPXDTm6RmJmLCWdv');
define('SECRET_KEY', '6LfUJ40kAAAAAI3oGHh_xcoutksypDmUbrCC_-X-');
define('IS_LIVE', 0);
define('IS_DEMO', 0);
define('CURR', '$');
define('CURRENCY', 'USD');
define('MAX_IMAGE_UPLOAD_SIZE_DISPLAY', '2MB');
define('MAX_VIDEO_UPLOAD_SIZE_DISPLAY', '5MB');
define('MAX_IMAGE_UPLOAD_SIZE_VAL', 2048);

global $accountStatus;
$accountStatus = array(
    'Activate' => "Activate",
    'Deactivate' => "Deactivate",
    'Delete' => "Delete",
);

global $vouchers;
$vouchers = array(
    'cash_voucher' => "Cash Voucher",
    'online_voucher' => "Online Voucher",
);

global $default_buffer_time;
$default_buffer_time = array(
    '10' => "10 Min",
    '15' => "15 Min",
    '20' => "20 Min",
    '25' => "25 Min",
    '30' => "30 Min",
);

global $notification_type;
$notification_type = array(
    'whatsapp_notification' => "WhatsApp Notification",
    'sms_notification' => "SMS Notification",
    'email_notification' => "Email Notification",
);

global $registration_type;
$registration_type = array(
    'fullname_required' => "FullName",
    'email_required' => "Email",
    'phone_required' => "Phone",
);

global $week_days;
$week_days = array(
    'monday' => "Monday",
    'tuesday' => "Tuesday",
    'wednesday' => "Wednesday",
    'thursday' => "Thursday",
    'friday' => "Friday",
    'saturday' => "Saturday",
    'sunday' => "Sunday",
);

global $change_status;
$change_status = array(
    'Pending' => "text-primary",
    'Canceled' => "text-danger",
    'Completed' => "text-success",
    'No show' => "text-warning",
    'Confirmed' => "text-info",
    'Visited'=>"Visited",
);

global $popular_time;
$popular_time = array(
    'evening' => "Evening",
    'morning' => "Morning",
    'after_noon' => "After Noon",
    'Night' => "Night",
);


global $time_array;
$time_array = array(
    '01:00 AM' => "01:00 AM",
    '02:00 AM' => "02:00 AM",
    '03:00 AM' => "03:00 AM",
    '04:00 AM' => "04:00 AM",
    '05:00 AM' => "05:00 AM",
    '06:00 AM' => "06:00 AM",
    '07:00 AM' => "07:00 AM",
    '08:00 AM' => "08:00 AM",
    '09:00 AM' => "09:00 AM",
    '10:00 AM' => "10:00 AM",
    '11:00 AM' => "11:00 AM",
    '12:00 PM' => "12:00 PM",
    '01:00 PM' => "01:00 PM",
    '02:00 PM' => "02:00 PM",
    '03:00 PM' => "03:00 PM",
    '04:00 PM' => "04:00 PM",
    '05:00 PM' => "05:00 PM",
    '06:00 PM' => "06:00 PM",
    '07:00 PM' => "07:00 PM",
    '08:00 PM' => "08:00 PM",
    '09:00 PM' => "09:00 PM",
    '10:00 PM' => "10:00 PM",
    '11:00 PM' => "11:00 PM",
    '12:00 AM' => "12:00 AM",
    
);

global $time_array;
$time_array = array(
    '01:00' => "01:00 AM",
    '02:00' => "02:00 AM",
    '03:00' => "03:00 AM",
    '04:00' => "04:00 AM",
    '05:00' => "05:00 AM",
    '06:00' => "06:00 AM",
    '07:00' => "07:00 AM",
    '08:00' => "08:00 AM",
    '09:00' => "09:00 AM",
    '10:00' => "10:00 AM",
    '11:00' => "11:00 AM",
    '12:00' => "12:00 PM",
    '13:00' => "01:00 PM",
    '14:00' => "02:00 PM",
    '15:00' => "03:00 PM",
    '16:00' => "04:00 PM",
    '17:00' => "05:00 PM",
    '18:00' => "06:00 PM",
    '19:00' => "07:00 PM",
    '20:00' => "08:00 PM",
    '21:00' => "09:00 PM",
    '22:00' => "10:00 PM",
    '23:00' => "11:00 PM",
    '00:00' => "00:00 AM",
    
);

global $time_array;
$time_array = array(
    '01:00:00' => "01:00 AM",
    '02:00:00' => "02:00 AM",
    '03:00:00' => "03:00 AM",
    '04:00:00' => "04:00 AM",
    '05:00:00' => "05:00 AM",
    '06:00:00' => "06:00 AM",
    '07:00:00' => "07:00 AM",
    '08:00:00' => "08:00 AM",
    '09:00:00' => "09:00 AM",
    '10:00:00' => "10:00 AM",
    '11:00:00' => "11:00 AM",
    '12:00:00' => "12:00 PM",
    '13:00:00' => "01:00 PM",
    '14:00:00' => "02:00 PM",
    '15:00:00' => "03:00 PM",
    '16:00:00' => "04:00 PM",
    '17:00:00' => "05:00 PM",
    '18:00:00' => "06:00 PM",
    '19:00:00' => "07:00 PM",
    '20:00:00' => "08:00 PM",
    '21:00:00' => "09:00 PM",
    '22:00:00' => "10:00 PM",
    '23:00:00' => "11:00 PM",
    '00:00:00' => "00:00 AM",
    
);


global $default_time;
$default_time = array(
    '08:00' => "08:00",
    '09:00' => "09:00",
    '10:00' => "10:00",
    '11:00' => "11:00",
    '12:00' => "12:00",
    '13:00' => "13:00",
    '14:00' => "14:00",
    '15:00' => "15:00",
    '16:00' => "16:00",
    '17:00' => "17:00",
    '18:00' => "18:00",
    '19:00' => "19:00",
    '20:00' => "20:00",
    '21:00' => "21:00",
    '22:00' => "22:00",
);

global $how_you_hear_about_us;
$how_you_hear_about_us = array(
    'through a friend' => "Through a friend",
    'social media' => "Social Media",
    'radio' => "Radio",
    'billboards and banners' => "Billboards and Banners",
    'i already know about the platform' => "I already know about the platform",
);


define('LOGO_IMAGE_UPLOAD_PATH', BASE_PATH . '/public/files/logo/');
define('LOGO_IMAGE_DISPLAY_PATH', HTTP_PATH . '/public/files/logo/');
define('HOME_LOGO_PATH', LOGO_IMAGE_DISPLAY_PATH.$siteSetting->home_logo);

//define('LOGO_PATH', LOGO_IMAGE_DISPLAY_PATH.$siteSetting->logo);
define('LOGO_PATH',  HTTP_PATH .'/public/files/logo/logo.png');

define('FAVICON_PATH', LOGO_IMAGE_DISPLAY_PATH.$siteSetting->favicon);

define('CK_IMAGE_UPLOAD_PATH', BASE_PATH . '/public/files/ckeditorimages/');
define('CK_IMAGE_DISPLAY_PATH', HTTP_PATH . '/public/files/ckeditorimages/');
define('IMAGE_EXT', 'image/gif, image/jpeg, image/png');
define('VIDEO_EXT', 'video/mov, video/mp4, video/3gp, video/ogg, video/avi');
define('DOC_EXT', '.pdf,.doc,.docx');
/* ******* profile image path ****** */
define('PROFILE_FULL_UPLOAD_PATH', BASE_PATH . '/public/files/users/full/');
define('PROFILE_SMALL_UPLOAD_PATH', BASE_PATH . '/public/files/users/small/');
define('PROFILE_FULL_DISPLAY_PATH', HTTP_PATH . '/public/files/users/full/');
define('PROFILE_SMALL_DISPLAY_PATH', HTTP_PATH . '/public/files/users/small/');
define('PROFILE_MW', 250);
define('PROFILE_MH', 250);

/* ******* fabric image path ****** */
define('FABRIC_FULL_UPLOAD_PATH', BASE_PATH . '/public/files/fabrics/full/');
define('FABRIC_SMALL_UPLOAD_PATH', BASE_PATH . '/public/files/fabrics/small/');
define('FABRIC_FULL_DISPLAY_PATH', HTTP_PATH . '/public/files/fabrics/full/');
define('FABRIC_SMALL_DISPLAY_PATH', HTTP_PATH . '/public/files/fabrics/small/');
define('FABRIC_MW', 250);
define('FABRIC_MH', 250);

/* ******* product image path ****** */
define('PRODUCT_FULL_UPLOAD_PATH', BASE_PATH . '/public/files/product/full/');
define('PRODUCT_SMALL_UPLOAD_PATH', BASE_PATH . '/public/files/product/small/');
define('PRODUCT_FULL_DISPLAY_PATH', HTTP_PATH . '/public/files/product/full/');
define('PRODUCT_SMALL_DISPLAY_PATH', HTTP_PATH . '/public/files/product/small/');
define('PRODUCT_MW', 250);
define('PRODUCT_MH', 250);

/** upload image for the servive    **/
define('SERVICE_FULL_UPLOAD_PATH', BASE_PATH . '/public/files/service/full/');
define('SERVICE_SMALL_UPLOAD_PATH', BASE_PATH . '/public/files/service/small/');
define('SERVICE_FULL_DISPLAY_PATH', HTTP_PATH . '/public/files/service/full/');
define('SERVICE_SMALL_DISPLAY_PATH', HTTP_PATH . '/public/files/service/small/');
define('SERVICE_MW', 250);
define('SERVICE_MH', 250);

/** upload image for the Category    **/
define('CATEGORY_FULL_UPLOAD_PATH', BASE_PATH . '/public/files/category/full/');
define('CATEGORY_SMALL_UPLOAD_PATH', BASE_PATH . '/public/files/category/small/');
define('CATEGORY_FULL_DISPLAY_PATH', HTTP_PATH . '/public/files/category/full/');
define('CATEGORY_SMALL_DISPLAY_PATH', HTTP_PATH . '/public/files/category/small/');
define('CATEGORY_MW', 250);
define('CATEGORY_MH', 250);

/* ******* testimonials image path ****** */
define('TESTIMONIAL_FULL_UPLOAD_PATH', BASE_PATH . '/public/files/testimonials/full/');
define('TESTIMONIAL_SMALL_UPLOAD_PATH', BASE_PATH . '/public/files/testimonials/small/');
define('TESTIMONIAL_FULL_DISPLAY_PATH', HTTP_PATH . '/public/files/testimonials/full/');
define('TESTIMONIAL_SMALL_DISPLAY_PATH', HTTP_PATH . '/public/files/testimonials/small/');
define('TESTIMONIAL_MW', 250);
define('TESTIMONIAL_MH', 250);

/* ******* deals image path ****** */
define('DEAL_FULL_UPLOAD_PATH', BASE_PATH . '/public/files/deal/full/');
define('DEAL_SMALL_UPLOAD_PATH', BASE_PATH . '/public/files/deal/small/');
define('DEAL_FULL_DISPLAY_PATH', HTTP_PATH . '/public/files/deal/full/');
define('DEAL_SMALL_DISPLAY_PATH', HTTP_PATH . '/public/files/deal/small/');
define('DEAL_MW', 250);
define('DEAL_MH', 250);

/* ******* merchant image path ****** */
define('MERCHANT_FULL_UPLOAD_PATH', BASE_PATH . '/public/files/merchant/full/');
define('MERCHANT_SMALL_UPLOAD_PATH', BASE_PATH . '/public/files/merchant/small/');
define('MERCHANT_FULL_DISPLAY_PATH', HTTP_PATH . '/public/files/merchant/full/');
define('MERCHANT_SMALL_DISPLAY_PATH', HTTP_PATH . '/public/files/merchant/small/');
define('MERCHANT_MW', 250);
define('MERCHANT_MH', 250);

/* ******* product video path ****** */
define('PRODUCTVIDEO_FULL_UPLOAD_PATH', BASE_PATH . '/public/files/productvideo/full/');
define('PRODUCTVIDEO_SMALL_UPLOAD_PATH', BASE_PATH . '/public/files/productvideo/small/');
define('PRODUCTVIDEO_FULL_DISPLAY_PATH', HTTP_PATH . '/public/files/productvideo/full/');
define('PRODUCTVIDEO_SMALL_DISPLAY_PATH', HTTP_PATH . '/public/files/productvideo/small/');
define('PRODUCTVIDEO_MW', 610);
define('PRODUCTVIDEO_MH', 360);

/** upload image for the Banners    **/
define('BANNER_FULL_UPLOAD_PATH', BASE_PATH . '/public/files/banner/full/');
define('BANNER_SMALL_UPLOAD_PATH', BASE_PATH . '/public/files/banner/small/');
define('BANNER_FULL_DISPLAY_PATH', HTTP_PATH . '/public/files/banner/full/');
define('BANNER_SMALL_DISPLAY_PATH', HTTP_PATH . '/public/files/banner/small/');
define('BANNER_MW', 250);
define('BANNER_MH', 250);

/* ******* voucher barcode image path ****** */
define('BARCODE_FULL_UPLOAD_PATH', BASE_PATH . '/public/files/vouchercode/');
define('BARCODE_FULL_DISPLAY_PATH', HTTP_PATH . '/public/files/vouchercode/');

global $staffroles;
$staffroles =  array(
    '1' => ' Configuration',
    '2' => ' Staff',
    '3' => ' Users',
    '4' => ' Pages',
    '5' => ' Services',
    '6' => ' Requests',
    '7' => ' Reports',
    '8' => ' Testimonials',
);

global $staffsubroles;
$staffsubroles =  array(
    '1' => array('2'=>'Edit'),
    '2' => array('2'=>'Edit','3'=>'Delete', '4'=>'View Only'),
    '3' => array('2'=>'Edit','3'=>'Delete', '4'=>'View Only'),
    '4' => array('2'=>'Edit','4'=>'View Only'),
    '5' => array('2'=>'Edit','3'=>'Delete','4'=>'View Only'),
    '6' => array('2'=>'Edit','4'=>'View Only'),
    '7' => array('4'=>'View Only'),
    '8' => array('2'=>'Edit','3'=>'Delete', '4'=>'View Only'),
);
global $colours;
$colours =  array(
    '1' => ' #0084ff',
    '2' => ' #FF4500',
    '3' => ' #BA55D3',
    '4' => ' #228B22',
    '5' => ' #DC143C',
    '6' => ' #8D99D3',
    '7' => ' #33BB75',
    '8' => ' #FF5733',
    '9' => ' #B042D9',
    '10' => '#DAA939',
);

define('PAYPAL_EMAIL', 'alok.tiwari@logicspice.com'); 
define('PAYPALURL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
define('PAYPALURLLIVE', 'https://www.paypal.com/cgi-bin/webscr');

global $account_sid;
$account_sid='AC61c41ef1c55a3ffbaa9f00ea4ae4015b';

global $auth_token;
$auth_token='ebcc521b88cb24ffc501d0b4c28ad392';

global $whatsapp_from;
$whatsapp_from='+14155238886';

global $sms_from;
$sms_from='+19175255097';

define("UNAUTHORIZED_LINK", "You dont have permision to access this link.");
global $telephonecc;
$telephonecc='+91';

define('PAYPAL_EMAIL', 'alok.tiwari@logicspice.com'); 
define('PAYPAL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');

global $withdrawal_status;
$withdrawal_status  = array(
	'0' => 'Pending',
	'1' => 'Under Progress',
	'2' => 'Completed',
	'3' => 'Rejected',
);
?>
