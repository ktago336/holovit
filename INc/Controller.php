<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Image;
use DB;
use Session;
use App\Models\Wallet;
use JWTFactory;
use JWTAuth;
use App\Models\User;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function encpassword($passwordPlain = 0) {
       return password_hash($passwordPlain, PASSWORD_DEFAULT);
    }
    
    public function serialiseFormData($data=array(), $isEdit=0){
        $formData = array();
        unset($data['_token']);
        unset($data['_method']);
        unset($data['confirm_password']);
        if($isEdit == 0){
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    public function uploadImageWithSameName($file, $upload_path=null){
        $orgName = $file->getClientOriginalName();
        $newFileName = $orgName;
        $file->move($upload_path, $newFileName);
        return $newFileName;
    }
    public function uploadImage($file, $upload_path=null){
        $orgName = $file->getClientOriginalName();
        $newFileName = bin2hex(openssl_random_pseudo_bytes(4)).'_'.$orgName;
        $file->move($upload_path, $newFileName);
        return $newFileName;
    }
    
    public function image_type_to_extension($imagetype) {
        if (empty($imagetype))
            return false;
        switch ($imagetype) {
            case IMAGETYPE_GIF : return 'gif';
            case IMAGETYPE_JPEG : return 'jpg';
            case IMAGETYPE_PNG : return 'png';
            case IMAGETYPE_SWF : return 'swf';
            case IMAGETYPE_PSD : return 'psd';
            case IMAGETYPE_BMP : return 'bmp';
            case IMAGETYPE_TIFF_II : return 'tiff';
            case IMAGETYPE_TIFF_MM : return 'tiff';
            case IMAGETYPE_JPC : return 'jpc';
            case IMAGETYPE_JP2 : return 'jp2';
            case IMAGETYPE_JPX : return 'jpf';
            case IMAGETYPE_JB2 : return 'jb2';
            case IMAGETYPE_SWC : return 'swc';
            case IMAGETYPE_IFF : return 'aiff';
            case IMAGETYPE_WBMP : return 'wbmp';
            case IMAGETYPE_XBM : return 'xbm';
            default : return false;
        }
    }
    
    public function resizeImage($uploadedFileName, $imgFolder, $thumbfolder, $newWidth = false, $newHeight = false, $quality = 75, $bgcolor = false) {
        $img = $imgFolder . $uploadedFileName;
        $newName = $uploadedFileName;
        $dest = $thumbfolder.$newName;
        list($oldWidth, $oldHeight, $type) = getimagesize($img);
        $ext = $this->image_type_to_extension($type);
        if ($newWidth OR $newHeight) {
            $widthScale = 2;
            $heightScale = 2;

            if ($newWidth)
                $widthScale = $newWidth / $oldWidth;
            if ($newHeight)
                $heightScale = $newHeight / $oldHeight;
            //debug("W: $widthScale  H: $heightScale<br>");
            if ($widthScale < $heightScale) {
                $maxWidth = $newWidth;
                $maxHeight = false;
            } elseif ($widthScale > $heightScale) {
                $maxHeight = $newHeight;
                $maxWidth = false;
            } else {
                $maxHeight = $newHeight;
                $maxWidth = $newWidth;
            }

            if ($maxWidth > $maxHeight) {
                $applyWidth = $maxWidth;
                $applyHeight = ($oldHeight * $applyWidth) / $oldWidth;
            } elseif ($maxHeight > $maxWidth) {
                $applyHeight = $maxHeight;
                $applyWidth = ($applyHeight * $oldWidth) / $oldHeight;
            } else {
                $applyWidth = $maxWidth;
                $applyHeight = $maxHeight;
            }
          
            $startX = 0;
            $startY = 0;
                 
            switch ($ext) {
                case 'gif' :
                    $oldImage = imagecreatefromgif($img);
                    break;
                case 'png' :
                    $oldImage = imagecreatefrompng($img);
                    break;
                case 'jpg' :
                case 'jpeg' :
                    $oldImage = imagecreatefromjpeg($img);
                    break;
                default :
                    return false;
                    break;
            }
            //create new image
            $newImage = imagecreatetruecolor($applyWidth, $applyHeight);
            imagecopyresampled($newImage, $oldImage, 0, 0, $startX, $startY, $applyWidth, $applyHeight, $oldWidth, $oldHeight);
            switch ($ext) {
                case 'gif' :
                    imagegif($newImage, $dest, $quality);
                    break;
                case 'png' :
                    imagepng($newImage, $dest, 8);
                    break;
                case 'jpg' :
                case 'jpeg' :
                    imagejpeg($newImage, $dest, $quality);
                    break;
                default :
                    return false;
                    break;
            }
            imagedestroy($newImage);
            imagedestroy($oldImage);
            if (!$newName) {
                unlink($img);
                rename($dest, $img);
            }
            return true;
        }
        
    }
    
    public function resizeImage_std($newFileName, $from_path=null, $to_path, $max_width=null, $max_height=null){
        list($width, $height)  = getimagesize($from_path . $newFileName);
        $image = Image::make($from_path . $newFileName);
        if($width > $height){
            if($max_width < $width){
                $image->resize($max_width, null, function ($constraint) { $constraint->aspectRatio();});
            }            
        }else{
            if($max_height < $height){
                $image->resize(null, $max_height, function ($constraint) { $constraint->aspectRatio();});
            } 
        }
        $image->save($to_path.$newFileName);
        return;
    }
    
    public function createSlug($slug=null,$tablename=null, $fieldname='slug'){
        $slug = filter_var($slug, FILTER_SANITIZE_STRING);
        $slug = str_replace(' ', '-', strtolower($slug));
        $isSlugExist = DB::table($tablename)->where($fieldname,$slug)->first();               
        if (!empty($isSlugExist)) {
            $slug = $slug.'-'.bin2hex(openssl_random_pseudo_bytes(6));
            $this->createSlug($slug, $tablename, $fieldname);
        }
        return $slug;
    }
    
    public function getRandString($length) {
        $length = ceil($length/2);
        return bin2hex(openssl_random_pseudo_bytes($length));
    }
    
    public function getSiteSetting() {   
        return mysqli_query(mysqli_connect('localhost', env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE')), 'SELECT * FROM tbl_settings WHERE id=1')->fetch_object();
    }
    
    public function getWallerAmount($userId) {
        $netincome = Wallet::select(DB::raw('SUM(revenue) as revenue'))->where(['user_id'=>$userId, 'add_minus'=>1])->whereIn('type', [0,6])->first()->revenue;
        $withdrawn = Wallet::select(DB::raw('SUM(revenue) as revenue'))->where(['user_id'=>$userId, 'add_minus'=>0, 'type'=>3])->first()->revenue;
        $userforpurchase = Wallet::select(DB::raw('SUM(revenue) as revenue'))->where(['user_id'=>$userId, 'add_minus'=>0, 'type'=>5])->first()->revenue;
        $pendingclearance = Wallet::select(DB::raw('SUM(revenue) as revenue'))->where(['user_id'=>$userId, 'add_minus'=>0, 'type'=>2])->first()->revenue;
        $availableforwithdraw = $netincome + $withdrawn + $userforpurchase + $pendingclearance;
        $amountArray = array();
        $expectedearnings = 0;
        $amountArray['netincome']  = $netincome;
        $amountArray['withdrawn']  = $withdrawn;
        $amountArray['userforpurchase']  = $userforpurchase;
        $amountArray['pendingclearance']  = $pendingclearance;
        $amountArray['availableforwithdraw']  = $availableforwithdraw;
        $amountArray['expectedearnings']  = $expectedearnings;
        return $amountArray;
    }
    
    
    public function PPHttpPost($methodName, $nvpStr) {        
        $environment = 'sandbox';
        $apiUsername = 'bhuvanesh.sharma001_api1.logicspice.com';
        $apiPassword = 'ZVDKLTGJ6NDBLEF8';
        $apiSignature = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AwLyGCmVrSyhRhr9z8HrPg4vTYwK';

        $API_UserName = urlencode($apiUsername);
        $API_Password = urlencode($apiPassword);
        $API_Signature = urlencode($apiSignature);

        $API_Endpoint = "https://api-3t.paypal.com/nvp";
        if ("sandbox" === $environment || "beta-sandbox" === $environment) {
            $API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
        }
        $version = urlencode('51.0');

        // Set the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $nvpreq = "METHOD=$methodName&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
        $httpResponse = curl_exec($ch);

        if (!$httpResponse) {
            exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')');
        }

        // Extract the response details.
        $httpResponseAr = explode("&", $httpResponse);
        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if (sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }

        if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
        }
        return $httpParsedResponseAr;
    }
    
    public function updateUserRating($userid, $asa='seller') {
       $overallrating =  DB::table('reviews')->select(DB::raw('AVG(rating) as rating'), DB::raw('count(*) as reviewcnt'))->where('user_id', $userid)->first(); 
       $allRate = $overallrating->rating;
       $allRwCnt = $overallrating->reviewcnt;
       if($asa == 'seller'){
            $sellerallrating =  DB::table('reviews')->select(DB::raw('AVG(rating) as rating'), DB::raw('count(*) as reviewcnt'))->where(['user_id'=>$userid, 'as_a'=>'seller'])->first(); 
            $selRate = $sellerallrating->rating;
            $selRwCnt = $sellerallrating->reviewcnt;
            DB::table('users')->where('id', $userid)->update(array('average_rating'=>$allRate, 'total_review'=>$allRwCnt, 'seller_rating'=>$selRate, 'seller_count'=>$selRwCnt));
       }else{
            $buyerallrating =  DB::table('reviews')->select(DB::raw('AVG(rating) as rating'), DB::raw('count(*) as reviewcnt'))->where(['user_id'=>$userid, 'as_a'=>'buyer'])->first(); 
            $buyRate = $buyerallrating->rating;
            $buyRwCnt = $buyerallrating->reviewcnt;
            DB::table('users')->where('id', $userid)->update(array('average_rating'=>$allRate, 'total_review'=>$allRwCnt, 'buyer_rating'=>$buyRate, 'buyer_count'=>$buyRwCnt));
       }
       return;
    }
    
    public function getSavedGigs(){
        $mysavegigs = array();
        if(Session::get('user_id')){
            $mysavegigsAA  = DB::table('savedgigs')->where(['user_id'=>Session::get('user_id')])->first();
            if($mysavegigsAA){
                if($mysavegigsAA->gig_ids){
                    $mysavegigs = explode(',', $mysavegigsAA->gig_ids);
                }
            }
        }
        return $mysavegigs;
    }
    
    /************************* API functions *****************************/
    public function requestAuthentication($mth='GET', $checkToken=0) {
        $reqMethod = $_SERVER["REQUEST_METHOD"];
        
        if($reqMethod != $mth){
            echo $this->errorOutputResult('bad request.');
            exit;
        }
        
        $headers = apache_request_headers();
        if(isset($headers['key'])){
            $apiKey = $headers['key'];
        }elseif(isset($headers['Key'])){
            $apiKey = $headers['Key'];
        }
        
        if ($apiKey != API_KEY) {
            echo $this->errorOutputResult('Unauthorized Access.');
            exit;
        }
        
        if($checkToken == 1){
            if(isset($headers['token']) && $headers['token'] !=''){
                $token = $headers['token'];
            }else{
                $token = $headers['Token'];
            }
            
            $tokenData = $this->verifyToken($token);
            $tokenData = (array)$tokenData;
            
            if(isset($tokenData['error']) && $tokenData['error'] == 1){
                echo $this->errorOutputResult($tokenData['msg']);
                exit;
            } 
            return $tokenData;
        }
        
        if($checkToken == 0){            
            if(isset($headers['token'])){
                $token = $headers['token'];
                $tokenData = $this->verifyToken($token);
                $tokenData = (array)$tokenData;
                if(isset($tokenData['error']) && $tokenData['error'] == 1){
                    echo $this->errorOutputResult($tokenData['msg']);
                    exit;
                }
                return $tokenData;
            }            
        }
        return;
    }
    
    public function setToken($userCheck = null) {
        return JWTAuth::fromUser($userCheck);
    }    
    public function verifyToken($token = null) {
        $tokenData  = JWTAuth::toUser($token);
        if (!empty($tokenData)) {
            $data = array();
            $data['user_id']  = $tokenData->id;
            $data['user_name']  = $tokenData->first_name.' '.$tokenData->last_name;
            $data['email_address']  = $tokenData->email_address;
            return $data;
        }else{
            exit;
        }
    }    
    
    public function errorOutputResult($errormsg = null) {
        echo '{"response_status":"error","response_msg":"' . $errormsg . '","response_data":""}'; exit;
    }
     
    public function successOutputResult($successmsg = null, $response_data='') {
        $response_data = str_replace(":null,", ':"",', $response_data);
        $response_data = str_replace(":null", ':""', $response_data);
        echo '{"response_status":"success","response_msg":"' . $successmsg . '","response_data":'.$response_data.'}'; exit;
    }
    
    public function successOutput($successmsg = null) {
        echo '{"response_status":"success","response_msg":"' . $successmsg . '","response_data":""}'; exit;
    }    
    
    public function errorOutput($errormsg = null) {
        echo  '{"message":"error","description":"' . $errormsg . '"}'; exit;
    }
}