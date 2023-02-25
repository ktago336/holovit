<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

//Route::get('/', function () {
//    return view('welcome');
//});
Auth::routes();

Route::group(['middleware' => ['merchant.unauthenticate'], 'prefix' => 'merchant', 'namespace' => 'Merchant', 'as'=>'merchant.'], function() {
	 Route::get('/', 'LoginController@login')->name('login');
	 Route::get('/login', 'LoginController@login')->name('login');
	 Route::post('login', 'LoginController@loginSave')->name('login.save'); 
	 Route::any('/register', 'UsersController@register');
	 Route::get('/email-confirmation/{ukey}', 'UsersController@emailConfirmation');
	 Route::any('/forgot-password', 'UsersController@forgotPassword');
	 Route::any('/reset-password/{ukey}', 'UsersController@resetPassword');
	 Route::any('/users/add_states', 'UsersController@add_states');
	 Route::any('/users/add_cities', 'UsersController@add_cities');
	 Route::any('/users/add_localities', 'UsersController@add_localities');

	 
	 Route::any('/users/verify_number', 'UsersController@verify_number');
	 Route::any('/users/otp_check', 'UsersController@otp_check');
	 
	 
});
Route::group(['middleware' => ['merchant.authenticate'], 'prefix' => 'merchant', 'namespace' => 'Merchant', 'as'=>'merchant.'], function() {
Route::get('/dashboard', 'UsersController@dashboard')->name('dashboard');
Route::get('/dashboard1', 'UsersController@dashboard1')->name('dashboard1');
Route::get('/logout', 'UsersController@logout')->name('logout');
Route::any('/deals/add', 'DealsController@add');
Route::any('/user/myaccount', 'UsersController@myaccount');
Route::any('/users/myaccount', 'UsersController@myaccount');
Route::get('/user/deleteprofileimageedit/{slug}/{image}', 'UsersController@deleteprofileimageedit');
Route::any('/user/index', 'UsersController@index');
//Route::any('/users/add_states', 'UsersController@add_states');
//Route::any('/users/add_cities', 'UsersController@add_cities');
//Route::any('/users/add_localities', 'UsersController@add_localities');
Route::any('/users/get_states', 'UsersController@get_states');
Route::any('/users/get_cities', 'UsersController@get_cities');
Route::any('/users/get_localities', 'UsersController@get_localities');
Route::any('/users/editprofile', 'UsersController@editprofile');
Route::any('/user/editprofile', 'UsersController@editprofile');


Route::any('/redeem-voucher', 'UsersController@redeemVoucher');
Route::post('/verify-voucher', 'UsersController@verifyVoucher');
Route::any('/order-detail', 'UsersController@orderDetail');
Route::any('/myorders', 'UsersController@myOrders');
Route::any('/mypayments', 'UsersController@myPayments');
Route::any('/mywallet', 'UsersController@mywallet');
Route::any('/sendwithdrawrequest', 'UsersController@sendwithdrawrequest');

Route::any('/deals', 'DealsController@index');
Route::any('/deals/edit/{slug}', 'DealsController@edit');
Route::get('/deals/delete/{slug}', 'DealsController@delete');
Route::get('/deals/deactivate/{slug}', 'DealsController@deactivate');
Route::get('/deals/activate/{slug}', 'DealsController@activate');
Route::get('/deals/deleteimageedit/{slug}/{image}', 'DealsController@deleteimageedit');

Route::get('/orderdetail/{slug}', 'UsersController@orderDetail');

});

Route::get('/', 'HomesController@index');
//Route::get('/', 'HomesController@home');
Route::any('/about', 'HomesController@about');
Route::any('/services', 'HomesController@services');
Route::any('/experts', 'HomesController@experts');
Route::any('/experts/{slug}', 'HomesController@experts');
Route::any('/expertdetail/{slug}', 'HomesController@expertdetail');
Route::any('/thank/{slug}', 'HomesController@thank');
Route::any('/selectdatetime/{slug}', 'HomesController@selectdatetime');
Route::any('/bookappointment/{slug}', 'HomesController@bookappointment');
Route::any('/bookedappointment', 'HomesController@bookedappointment');
Route::any('/blog', 'HomesController@blog');
Route::any('/testimonial', 'HomesController@testimonial');
Route::any('/contact', 'HomesController@contact');
Route::any('/contactus', 'HomesController@contactus');
Route::any('/appointment', 'HomesController@appointment');
Route::any('/never', 'HomesController@never');
Route::any('/setLocationInSession', 'HomesController@setLocationInSession');

Route::any('/work', 'HomesController@work');
Route::any('/requestdetail/{slug?}', 'HomesController@index');
Route::any('/verifyphoneofuser', 'HomesController@verifyphoneofuser');


Route::any('/login', 'UsersController@login');
Route::any('/logout', 'UsersController@logout');
Route::any('/register', 'UsersController@register');
Route::get('/email-confirmation/{ukey}', 'UsersController@emailConfirmation');
Route::any('/forgot-password', 'UsersController@forgotPassword');
Route::any('/reset-password/{ukey}', 'UsersController@resetPassword');
Route::any('/users/dashboard', 'UsersController@dashboard');
Route::any('/users/myaccount', 'UsersController@myaccount');
Route::any('/users/myprofile', 'UsersController@myprofile');
Route::post('/users/uploadprofileimage', 'UsersController@uploadprofileimage');
Route::post('/users/updatedata', 'UsersController@updatedata');
Route::any('/users/settings', 'UsersController@settings');
Route::any('/users/myorders', 'UsersController@myorders');
Route::any('/users/updatesettings', 'UsersController@updatesettings');
Route::get('/check-new-notification', 'UsersController@checknotifications');
Route::any('/users/editprofile', 'UsersController@editprofile');

Route::get('/privacy-policy', 'PagesController@index');
Route::get('/terms-and-condition', 'PagesController@index');
Route::get('/about-us', 'PagesController@index');
Route::any('/universal-fine-print', 'PagesController@index');
Route::any('/sitemap', 'PagesController@index');
Route::any('/how-it-works', 'PagesController@index');
Route::any('/contact-us', 'PagesController@contactus');


Route::any('/pages/checlapi', 'PagesController@checlapi');

Route::any('/users/mypayments', 'UsersController@mypayments');
Route::any('/users/mywallet', 'UsersController@mywallet');
Route::any('/users/addmoney', 'UsersController@addmoney');
Route::any('/users/payviapaypal', 'UsersController@payviapaypal');
Route::any('/users/paypalsuccess', 'UsersController@paypalsuccess');
Route::any('/users/paypalsuccess/{slug}', 'UsersController@paypalsuccess');
Route::any('/users/paypalcancel', 'UsersController@paypalcancel');

Route::any('/browse', 'ProductsController@listing');
Route::any('/browse/{slug}', 'ProductsController@listing');

//Route::any('/merchants/dashboard', 'MerchantsController@dashboard');

//product routing front
Route::any('/products/category', 'ProductsController@category');

Route::any('/products', 'ProductsController@search');
Route::any('/products/search/{slug}', 'ProductsController@search');
Route::any('/products/search/{slug}/{subcatslug}', 'ProductsController@search');
Route::any('/products/searchsubcat', 'ProductsController@searchsubcat');

Route::any('/products/listing', 'ProductsController@index');
Route::any('/products/add', 'ProductsController@add');
Route::any('/products/edit/{slug}', 'ProductsController@edit');
Route::get('/products/activate/{slug}', 'ProductsController@activate');
Route::get('/products/deactivate/{slug}', 'ProductsController@deactivate');
Route::get('/products/delete/{slug}', 'ProductsController@delete');
Route::get('/products/deleteimageedit/{slug}/{image}', 'ProductsController@deleteimageedit');


//Route::any('/merchants/dashboard', 'MerchantsController@dashboard');

//Route::any('/deals', 'DealsController@index');
Route::any('/deals/add', 'DealsController@add');
Route::any('/deals/edit/{slug}', 'DealsController@edit');
Route::get('/deals/activate/{slug}', 'DealsController@activate');
Route::get('/deals/deactivate/{slug}', 'DealsController@deactivate');
Route::get('/deals/delete/{slug}', 'DealsController@delete');
Route::get('/deals/deleteimageedit/{slug}/{image}', 'DealsController@deleteimageedit');

Route::any('/deals/search/{slug}', 'DealsController@search');
Route::any('/deals/search', 'DealsController@search');
Route::any('/deals/detail/{slug}', 'DealsController@detail');
Route::get('/deals/ordersummary/{slug}/{offerinfo}', 'DealsController@ordersummary');
Route::any('/deals/generateorder/{slug}/{offerinfo}', 'DealsController@generateorder');
Route::any('payments/paywithpaypal/{slug}', 'PaymentsController@paywithpaypal');
Route::any('/payments/success', 'PaymentsController@success');
Route::any('/payments/success/{slug}', 'PaymentsController@success');
Route::any('/payments/paypalcancel/{slug}', 'PaymentsController@paypalcancel');
Route::any('/deals/setlocation', 'DealsController@setLocation');



Route::get('/users/orderdetail/{slug}', 'UsersController@orderDetail');
Route::get('/users/wishlist', 'UsersController@wishlist');



#Route::get('/sendemail', 'HomesController@sendmail');
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function() {
    Route::any('/', 'AdminsController@login');
    Route::any('login', 'AdminsController@login');
    Route::any('admins/login', 'AdminsController@login');
    Route::get('admins/logout', 'AdminsController@logout');
    Route::any('admins/dashboard', 'AdminsController@dashboard');
    Route::any('admins/custom', 'AdminsController@custom');
    Route::get('admins/userchart/{daycount}', 'AdminsController@userchart');
    Route::any('admins/change-username', 'AdminsController@changeUsername');
    Route::any('admins/change-password', 'AdminsController@changePassword');
    Route::any('admins/change-email', 'AdminsController@changeEmail');
    Route::any('admins/forgot-password', 'AdminsController@forgotPassword');
    Route::any('admins/site-settings', 'AdminsController@siteSettings');
    Route::any('admins/why-buy', 'AdminsController@whyBuy');

//    Route::any('/admins/merchant', 'AdminsController@staff');
//    Route::any('/admins/addmerchant', 'AdminsController@addstaff');
//    Route::any('/admins/editmerchant/{slug}', 'AdminsController@editstaff');
//    Route::get('/admins/activatemerchant/{slug}', 'AdminsController@activatestaff');
//    Route::get('/admins/deactivatemerchant/{slug}', 'AdminsController@deactivatestaff');
//    Route::get('/admins/deletemerchant/{slug}', 'AdminsController@deletestaff');
//    Route::get('/admins/deleteimagemerchant/{slug}', 'AdminsController@deleteimagestaff');
    Route::any('/admins/managerolemerchant/{slug}', 'AdminsController@managerolestaff');
    Route::any('/admins/getAdminRoles/{slug}', 'AdminsController@getAdminRoles');
    Route::any('/admins/getAdminRolesSub/{slug}', 'AdminsController@getAdminRolesSub');
    Route::any('/admins/getCheckRoles/{slug}', 'AdminsController@getCheckRoles');
    Route::any('/admins/getCheckRolesSub/{slug}', 'AdminsController@getCheckRolesSub');

    Route::any('/admins/getstaffslot/{slug}', 'AdminsController@getstaffslot');
    Route::any('/admins/saveblockedslot', 'AdminsController@saveblockedslot');
    Route::any('/admins/blockfullday', 'AdminsController@blockfullday');
    Route::any('/admins/managecalender/{slug}', 'AdminsController@managecalender');
    Route::any('/users', 'UsersController@index');
    Route::any('/users/add', 'UsersController@add');
    Route::any('/users/edit/{slug}', 'UsersController@edit');
    Route::get('/users/activate/{slug}', 'UsersController@activate');
    Route::get('/users/deactivate/{slug}', 'UsersController@deactivate');
    Route::get('/users/delete/{slug}', 'UsersController@delete');
    Route::get('/users/deleteimage/{slug}', 'UsersController@deleteimage');
    //merchant user
    Route::any('/admins/merchant', 'UsersController@merchant');
    Route::any('/admins/addmerchant', 'UsersController@addmerchant');
    Route::any('/admins/editmerchant/{slug}', 'UsersController@editmerchant');
    Route::get('/admins/activatemerchant/{slug}', 'UsersController@activatemerchant');
    Route::get('/admins/deactivatemerchant/{slug}', 'UsersController@deactivatemerchant');
    Route::get('/admins/deletemerchant/{slug}', 'UsersController@deletemerchant');
    Route::get('/admins/deleteimagemerchant/{slug}/{image}', 'UsersController@deleteimagemerchant');
    Route::any('/admins/deleteprofileimageedit/{slug}/{image}', 'UsersController@deleteprofileimageedit');

	
	/***********countries ***************/
   Route::any('/countries', 'CountriesController@index');
    Route::any('/countries/add', 'CountriesController@add');    
    Route::any('/countries/edit/{slug}', 'CountriesController@edit');
    Route::get('/countries/activate/{slug}', 'CountriesController@activate');
    Route::get('/countries/deactivate/{slug}', 'CountriesController@deactivate');
    Route::get('/countries/delete/{slug}', 'CountriesController@delete');
    
    /***********states ***************/
    
    Route::any('/states/{slug}', 'StatesController@index');
    Route::any('/states/add/{slug}', 'StatesController@add');    
    Route::any('/states/edit/{slug}', 'StatesController@edit');
    Route::get('/states/activate/{slug}', 'StatesController@activate');
    Route::get('/states/deactivate/{slug}', 'StatesController@deactivate');
    Route::get('/states/delete/{slug}', 'StatesController@delete');
    
   /*********** cities ***************/
    
    Route::any('/cities/{slug}', 'CitiesController@index');
    Route::any('/cities/add/{slug}', 'CitiesController@add');    
    Route::any('/cities/edit/{slug}', 'CitiesController@edit');
    Route::get('/cities/activate/{slug}', 'CitiesController@activate');
    Route::get('/cities/deactivate/{slug}', 'CitiesController@deactivate');
    Route::get('/cities/delete/{slug}', 'CitiesController@delete');
	
	/*********** cities ***************/
    
    Route::any('/localities/{slug}', 'LocalitiesController@index');
    Route::any('/localities/add/{slug}', 'LocalitiesController@add');    
    Route::any('/localities/edit/{slug}', 'LocalitiesController@edit');
    Route::get('/localities/activate/{slug}', 'LocalitiesController@activate');
    Route::get('/localities/deactivate/{slug}', 'LocalitiesController@deactivate');
    Route::get('/localities/delete/{slug}', 'LocalitiesController@delete');

    Route::any('/services', 'ServicesController@index');
    Route::any('/services/add', 'ServicesController@add');
    Route::any('/services/edit/{slug}', 'ServicesController@edit');
    Route::any('/services/activate/{slug}', 'ServicesController@activate');
    Route::any('/services/deactivate/{slug}', 'ServicesController@deactivate');
    Route::any('/services/delete/{slug}', 'ServicesController@delete');
    Route::any('/services/deleteimage/{slug}', 'ServicesController@deleteimage');
	
    Route::any('/testimonials', 'TestimonialsController@index');
    Route::any('/testimonials/add', 'TestimonialsController@add');
    Route::any('/testimonials/edit/{slug}', 'TestimonialsController@edit');
    Route::get('/testimonials/activate/{slug}', 'TestimonialsController@activate');
    Route::get('/testimonials/deactivate/{slug}', 'TestimonialsController@deactivate');
    Route::get('/testimonials/delete/{slug}', 'TestimonialsController@delete');
    // categories routing   
    Route::any('/categories', 'CategoriesController@index');
    Route::any('/categories/add', 'CategoriesController@add');
    Route::any('/categories/edit/{slug}', 'CategoriesController@edit');
    Route::get('/categories/activate/{slug}', 'CategoriesController@activate');
    Route::get('/categories/deactivate/{slug}', 'CategoriesController@deactivate');
    Route::get('/categories/delete/{slug}', 'CategoriesController@delete');
    Route::any('/categories/subcategories/{slug}', 'CategoriesController@subindex');
    Route::any('/categories/addsub/{slug}', 'CategoriesController@addsub');
    Route::any('/categories/editsub/{pslug}/{slug}', 'CategoriesController@editsub');
    Route::get('/categories/activatesub/{slug}', 'CategoriesController@activatesub');
    Route::get('/categories/deactivatesub/{slug}', 'CategoriesController@deactivatesub');
    Route::get('/categories/deletesub/{pslug}/{slug}', 'CategoriesController@deletesub');
    Route::any('/categories/subsubcategories/{slug}', 'CategoriesController@subsubindex');
    Route::any('/categories/addsubsub/{slug}', 'CategoriesController@addsubsub');
    Route::any('/categories/editsubsub/{pslug}/{slug}', 'CategoriesController@editsubsub');
    Route::get('/categories/activatesubsub/{slug}', 'CategoriesController@activatesubsub');
    Route::get('/categories/deactivatesubsub/{slug}', 'CategoriesController@deactivatesubsub');
    Route::get('/categories/deletesubsub/{pslug}/{slug}', 'CategoriesController@deletesubsub');
    Route::any('/categories/deleteimage/{slug}', 'CategoriesController@deleteimage');
    // brands routing   
    Route::any('/brands', 'BrandsController@index');
    Route::any('/brands/add', 'BrandsController@add');
    Route::any('/brands/edit/{slug}', 'BrandsController@edit');
    Route::get('/brands/activate/{slug}', 'BrandsController@activate');
    Route::get('/brands/deactivate/{slug}', 'BrandsController@deactivate');
    Route::get('/brands/delete/{slug}', 'BrandsController@delete');
    // locations routing   
    Route::any('/locations', 'LocationsController@index');
    Route::any('/locations/add', 'LocationsController@add');
    Route::any('/locations/edit/{slug}', 'LocationsController@edit');
    Route::get('/locations/activate/{slug}', 'LocationsController@activate');
    Route::get('/locations/deactivate/{slug}', 'LocationsController@deactivate');
    Route::get('/locations/delete/{slug}', 'LocationsController@delete');
    // deals routing   
    Route::any('/deals', 'DealsController@index');
    Route::any('/deals/add', 'DealsController@add');
    Route::any('/deals/edit/{slug}', 'DealsController@edit');
    Route::get('/deals/activate/{slug}', 'DealsController@activate');
    Route::get('/deals/deactivate/{slug}', 'DealsController@deactivate');
    Route::get('/deals/delete/{slug}', 'DealsController@delete');
    Route::any('/deals/add_subcategory', 'DealsController@add_subcategory');
    Route::any('/deals/add_sub_subcategory', 'DealsController@add_sub_subcategory');
    Route::get('/deals/deleteimageedit/{slug}/{image}', 'DealsController@deleteimageedit');
    Route::get('/deals/deletevideoedit/{slug}/{video}', 'DealsController@deletevideoedit');
    // deals routing   
    Route::any('/business_types', 'BusinessTypesController@index');
    Route::any('/business_types/add', 'BusinessTypesController@add');
    Route::any('/business_types/edit/{slug}', 'BusinessTypesController@edit');
    Route::get('/business_types/activate/{slug}', 'BusinessTypesController@activate');
    Route::get('/business_types/deactivate/{slug}', 'BusinessTypesController@deactivate');
    Route::get('/business_types/delete/{slug}', 'BusinessTypesController@delete');

    Route::any('/pages', 'PagesController@index');

    Route::any('/reports/{slug?}', 'ReportsController@index');
    Route::any('/pages/edit/{slug}', 'PagesController@edit');
    Route::any('/pages/pageimages', 'PagesController@pageimages');

    // coupons routing   
    Route::any('/coupons', 'CouponsController@index');
    Route::any('/coupons/edit/{slug}', 'CouponsController@edit');
    Route::get('/coupons/activate/{slug}', 'CouponsController@activate');
    Route::get('/coupons/deactivate/{slug}', 'CouponsController@deactivate');
    Route::get('/coupons/delete/{slug}', 'CouponsController@delete');

    // products routing 
    Route::any('/products', 'ProductsController@index');
    Route::any('/products/add', 'ProductsController@add');
    Route::any('/products/edit/{slug}', 'ProductsController@edit');
    Route::get('/products/activate/{slug}', 'ProductsController@activate');
    Route::get('/products/deactivate/{slug}', 'ProductsController@deactivate');
    Route::get('/products/delete/{slug}', 'ProductsController@delete');
    Route::any('/products/add_subcategory', 'ProductsController@add_subcategory');
    Route::any('/products/add_sub_subcategory', 'ProductsController@add_sub_subcategory');
    Route::get('/products/deleteimageedit/{slug}/{image}', 'ProductsController@deleteimageedit');
    Route::get('/products/deletevideoedit/{slug}/{video}', 'ProductsController@deletevideoedit');
	
	// orders routing 
	Route::any('/orders', 'OrdersController@index');
	Route::get('/orders/{slug}', 'OrdersController@details');
	
	Route::any('/payments', 'PaymentsController@index');
	
	Route::any('/requests', 'RequestsController@index');
    Route::any('/requests/{slug}/{from?}/{to?}', 'RequestsController@index');
    Route::any('/services/cancle/{slug}', 'RequestsController@cancle');
    Route::any('/services/cancle', 'RequestsController@cancle');
    Route::any('/services/recancle/{slug}', 'RequestsController@recancle');
    Route::any('/services/reschedule/{slug}', 'RequestsController@reschedule');
    Route::any('/assignstaff/{slug}', 'RequestsController@assignstaff');
    Route::any('/dshservice/{slug}', 'RequestsController@dshservice');
    Route::any('/dshstaff/{slug}', 'RequestsController@dshstaff');
    Route::any('/reschedule/{slug}', 'RequestsController@rescheduleappoinment');
    Route::any('/reschedule', 'RequestsController@rescheduleappoinment');
    Route::any('/saverescheduledata', 'RequestsController@saverescheduledata');
    Route::any('/changestatus/{slug}', 'RequestsController@changestatus');
    Route::any('/changestatus', 'RequestsController@changestatus');
    Route::any('/updatestatus/{slug}', 'RequestsController@updateappointmentstatus');
    Route::any('/updatestatus', 'RequestsController@updateappointmentstatus');
    Route::any('/invoice/{slug?}', 'RequestsController@invoice');
    Route::any('/saveinvoice/{slug?}', 'RequestsController@saveinvoice');
    Route::any('/getprice', 'RequestsController@getprice');
	
	// banners routing   
    Route::any('/banners', 'BannersController@index');
    Route::any('/banners/add', 'BannersController@add');
    Route::any('/banners/edit/{slug}', 'BannersController@edit');
    Route::get('/banners/activate/{slug}', 'BannersController@activate');
    Route::get('/banners/deactivate/{slug}', 'BannersController@deactivate');
    Route::get('/banners/delete/{slug}', 'BannersController@delete');
    Route::any('/banners/deleteimage/{slug}', 'BannersController@deleteimage');
    
    // Withdrawals routing   
    Route::any('/wallets/withdrawals/{slug?}', 'WalletsController@withdrawals');
	Route::any('/wallets/changestatus/{id}/{status}', 'WalletsController@changestatus');
	Route::any('/wallets/createrequest/{slug?}', 'WalletsController@createrequest');
});

 
