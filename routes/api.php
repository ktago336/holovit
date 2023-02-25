<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/users/login', 'Api\UsersController@login');
Route::post('/users/logindata', 'Api\UsersController@logindata');
Route::post('/users/forgotPassword', 'Api\UsersController@forgotPassword');
Route::post('/users/register', 'Api\UsersController@register');
Route::any('/users/dashboard', 'Api\UsersController@dashboard');
Route::post('/users/editprofile', 'Api\UsersController@editprofile');
Route::post('/users/changepassword', 'Api\UsersController@changepassword');
Route::get('/users/myorders', 'Api\UsersController@myorders');
Route::get('/users/mywallet', 'Api\UsersController@mywallet');
Route::get('/users/mypayments', 'Api\UsersController@mypayments');
Route::get('/users/getprofile', 'Api\UsersController@getprofile');
Route::get('/users/categorylist', 'Api\UsersController@categorylist');
Route::post('/users/subcategorylist', 'Api\UsersController@subcategorylist');
Route::post('/users/addmoney', 'Api\UsersController@addmoney');
Route::post('/users/orderdetails', 'Api\UsersController@orderdetails');
Route::post('/users/coupondetails', 'Api\UsersController@coupondetails');
Route::post('/users/dashboardlist', 'Api\UsersController@dashboardlist');
Route::post('/users/review', 'Api\UsersController@review');
Route::post('/users/dealdetails', 'Api\UsersController@dealdetails');
Route::get('/users/details', 'Api\UsersController@details');
Route::post('/users/purchase', 'Api\UsersController@purchase');
Route::get('/users/citylist', 'Api\UsersController@citylist');
Route::post('/users/contactus', 'Api\UsersController@contactus');
Route::get('/users/deleteaccount', 'Api\UsersController@deleteaccount');

/// for merchant ///
Route::post('/merchants/login', 'Api\MerchantsController@login');
Route::post('/merchants/logindata', 'Api\MerchantsController@logindata');
Route::post('/merchants/forgotPassword', 'Api\MerchantsController@forgotPassword');
Route::post('/merchants/register', 'Api\MerchantsController@register');
Route::post('/merchants/dashboard', 'Api\MerchantsController@dashboard');
Route::post('/merchants/editprofile', 'Api\MerchantsController@editprofile');
Route::post('/merchants/changepassword', 'Api\MerchantsController@changepassword');
Route::get('/merchants/getprofile', 'Api\MerchantsController@getprofile');
Route::get('/merchants/mywallet', 'Api\MerchantsController@mywallet');
Route::get('/merchants/myorders', 'Api\MerchantsController@myorders');
Route::get('/merchants/mydeals', 'Api\MerchantsController@mydeals');
Route::post('/merchants/orderdetails', 'Api\MerchantsController@orderdetails');
Route::post('/merchants/verifyVoucher', 'Api\MerchantsController@verifyVoucher');
Route::post('/merchants/deletedeal', 'Api\MerchantsController@deletedeal');
Route::post('/merchants/editdeal', 'Api\MerchantsController@editdeal');
Route::post('/merchants/adddeal', 'Api\MerchantsController@adddeal');
Route::post('/merchants/redeemVoucher', 'Api\MerchantsController@redeemVoucher');
Route::get('/merchants/businesslist', 'Api\MerchantsController@businesslist');
Route::get('/merchants/countrylist', 'Api\MerchantsController@countrylist');
Route::post('/merchants/statelist', 'Api\MerchantsController@statelist');
Route::post('/merchants/citylist', 'Api\MerchantsController@citylist');
Route::post('/merchants/servicelist', 'Api\MerchantsController@servicelist');
Route::get('/merchants/amenitieslist', 'Api\MerchantsController@amenitieslist');
Route::post('/merchants/localitylist', 'Api\MerchantsController@localitylist');
Route::post('/merchants/dealdetails', 'Api\MerchantsController@dealdetails');
Route::post('/merchants/withdrawrequest', 'Api\MerchantsController@withdrawrequest');
Route::post('/merchants/deleteimagedeal', 'Api\MerchantsController@deleteimagedeal');
Route::post('/merchants/deleteprofileimage', 'Api\MerchantsController@deleteprofileimage');
Route::get('/merchants/getconfiguration', 'Api\MerchantsController@getconfiguration');
Route::get('/merchants/deleteaccount', 'Api\MerchantsController@deleteaccount');

Route::post('/users/changepicture', 'Api\UsersController@changepicture');
Route::get('/users/getcategorylist', 'Api\UsersController@getcategorylist');
Route::post('/users/getsubcategorylist', 'Api\UsersController@getsubcategorylist');
Route::get('/users/getskilllist', 'Api\UsersController@getskilllist');
Route::get('/users/getlanguagelist', 'Api\UsersController@getlanguagelist');
Route::get('/users/gettopgigs', 'Api\UsersController@gettopgigs');
Route::any('/users/gigdetail', 'Api\UsersController@gigdetail');
Route::any('/users/getgigslisting', 'Api\UsersController@getgigslisting');
Route::any('/users/gigslisting', 'Api\UsersController@gigslisting');
Route::any('/users/gethomedetail', 'Api\UsersController@gethomedetail');
Route::any('/users/liked', 'Api\UsersController@liked');
Route::any('/users/delete', 'Api\UsersController@delete');
Route::any('/users/getsavedgigs', 'Api\UsersController@getsavedgigs');
Route::any('/users/buyercontact', 'Api\UsersController@buyercontact');
Route::any('/users/sellercontact', 'Api\UsersController@sellercontact');
Route::any('/users/viewprofile', 'Api\UsersController@viewprofile');
Route::any('/users/getnotification', 'Api\UsersController@getnotification');
Route::any('/users/getbuyercontacts', 'Api\UsersController@getbuyercontacts');
Route::any('/users/getsellercontacts', 'Api\UsersController@getsellercontacts');
Route::any('/users/getofferedgig', 'Api\UsersController@getofferedgig');
Route::any('/users/getconversation', 'Api\UsersController@getconversation');
Route::any('/users/sendmessage', 'Api\UsersController@sendmessage');

Route::get('/myorders/sellingorders', 'Api\MyordersController@sellingorders');
Route::get('/myorders/buyingorders', 'Api\MyordersController@buyingorders');
Route::any('/myorders/earnings', 'Api\MyordersController@earnings');
Route::any('/myorders/paymenthistory', 'Api\MyordersController@paymenthistory');
Route::any('/myorders/orderdetail', 'Api\MyordersController@orderdetail');
Route::any('/myorders/rateandreview', 'Api\MyordersController@rateandreview');

Route::any('/services/create', 'Api\ServicesController@create');
Route::any('/services/edit', 'Api\ServicesController@edit');
Route::any('/services/delete', 'Api\ServicesController@delete');
Route::any('/services/listing', 'Api\ServicesController@listing');
Route::any('/services/detail', 'Api\ServicesController@detail');
Route::any('/services/activelist', 'Api\ServicesController@activelist');
Route::any('/services/offersentlist', 'Api\ServicesController@offersentlist');
Route::any('/services/offersent', 'Api\ServicesController@offersent');
Route::any('/services/acceptrejectoffer', 'Api\ServicesController@acceptrejectoffer');
Route::any('/services/markcompleted', 'Api\ServicesController@markcompleted');
Route::any('/services/viewoffer', 'Api\ServicesController@viewoffer');
Route::any('/services/workplace', 'Api\ServicesController@workplace');
Route::any('/services/sendmessage', 'Api\ServicesController@sendmessage');
Route::any('/services/messagelist', 'Api\ServicesController@messagelist');


//old
Route::post('/users/login', 'Api\UsersController@login');
Route::post('/users/forgotPassword', 'Api\UsersController@forgotPassword');
Route::post('/users/register', 'Api\UsersController@register');


Route::get('/users/dashboard', 'Api\UsersController@dashboard');
Route::post('/users/changepassword', 'Api\UsersController@changepassword');
Route::post('/users/setpaypalemail', 'Api\UsersController@setpaypalemail');
Route::get('/users/getcountrylist', 'Api\UsersController@getcountrylist');
Route::get('/users/getprofile', 'Api\UsersController@getprofile');
Route::post('/users/editprofile', 'Api\UsersController@editprofile');
Route::post('/users/changepicture', 'Api\UsersController@changepicture');
Route::get('/users/getcategorylist', 'Api\UsersController@getcategorylist');
Route::post('/users/getsubcategorylist', 'Api\UsersController@getsubcategorylist');
Route::get('/users/getskilllist', 'Api\UsersController@getskilllist');
Route::get('/users/getlanguagelist', 'Api\UsersController@getlanguagelist');
Route::get('/users/gettopgigs', 'Api\UsersController@gettopgigs');
Route::any('/users/gigdetail', 'Api\UsersController@gigdetail');
Route::any('/users/getgigslisting', 'Api\UsersController@getgigslisting');
Route::any('/users/gigslisting', 'Api\UsersController@gigslisting');
Route::any('/users/gethomedetail', 'Api\UsersController@gethomedetail');
Route::any('/users/liked', 'Api\UsersController@liked');
Route::any('/users/delete', 'Api\UsersController@delete');
Route::any('/users/getsavedgigs', 'Api\UsersController@getsavedgigs');
Route::any('/users/buyercontact', 'Api\UsersController@buyercontact');
Route::any('/users/sellercontact', 'Api\UsersController@sellercontact');
Route::any('/users/viewprofile', 'Api\UsersController@viewprofile');
Route::any('/users/getnotification', 'Api\UsersController@getnotification');
Route::any('/users/getbuyercontacts', 'Api\UsersController@getbuyercontacts');
Route::any('/users/getsellercontacts', 'Api\UsersController@getsellercontacts');
Route::any('/users/getofferedgig', 'Api\UsersController@getofferedgig');
Route::any('/users/getconversation', 'Api\UsersController@getconversation');
Route::any('/users/sendmessage', 'Api\UsersController@sendmessage');

Route::get('/myorders/sellingorders', 'Api\MyordersController@sellingorders');
Route::get('/myorders/buyingorders', 'Api\MyordersController@buyingorders');
Route::any('/myorders/earnings', 'Api\MyordersController@earnings');
Route::any('/myorders/paymenthistory', 'Api\MyordersController@paymenthistory');
Route::any('/myorders/orderdetail', 'Api\MyordersController@orderdetail');
Route::any('/myorders/rateandreview', 'Api\MyordersController@rateandreview');

Route::any('/services/create', 'Api\ServicesController@create');
Route::any('/services/edit', 'Api\ServicesController@edit');
Route::any('/services/delete', 'Api\ServicesController@delete');
Route::any('/services/listing', 'Api\ServicesController@listing');
Route::any('/services/detail', 'Api\ServicesController@detail');
Route::any('/services/activelist', 'Api\ServicesController@activelist');
Route::any('/services/offersentlist', 'Api\ServicesController@offersentlist');
Route::any('/services/offersent', 'Api\ServicesController@offersent');
Route::any('/services/acceptrejectoffer', 'Api\ServicesController@acceptrejectoffer');
Route::any('/services/markcompleted', 'Api\ServicesController@markcompleted');
Route::any('/services/viewoffer', 'Api\ServicesController@viewoffer');
Route::any('/services/workplace', 'Api\ServicesController@workplace');
Route::any('/services/sendmessage', 'Api\ServicesController@sendmessage');
Route::any('/services/messagelist', 'Api\ServicesController@messagelist');


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
