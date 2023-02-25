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

Route::get('/', 'HomesController@index');
//Route::get('/', 'HomesController@home');
Route::any('/about', 'HomesController@about');
Route::any('/services', 'HomesController@services');
Route::any('/experts', 'HomesController@experts');
Route::any('/expertdetail/{slug}', 'HomesController@expertdetail');
Route::any('/selectservice/{slug}', 'HomesController@selectservice');
Route::any('/selectservice', 'HomesController@selectservice');
Route::any('/saveselectservice', 'HomesController@selectservice');
Route::any('/updateselectservice/{slug}', 'HomesController@updateselectservice');
Route::any('/selectdatetime/{slug}', 'HomesController@selectdatetime');
Route::any('/bookappointment/{slug}', 'HomesController@bookappointment');
Route::any('/blog', 'HomesController@blog');
Route::any('/testimonial', 'HomesController@testimonial');
Route::any('/contact', 'HomesController@contact');
Route::any('/contactus', 'HomesController@contactus');
Route::any('/appointment', 'HomesController@appointment');
Route::any('/getstaff/{slug}', 'HomesController@getstaff');
Route::any('/getservicesdata/{slug}', 'HomesController@getservicesdata');

Route::any('/login', 'UsersController@login');
Route::any('/logout', 'UsersController@logout');
Route::any('/register', 'UsersController@register');
Route::get('/email-confirmation/{ukey}', 'UsersController@emailConfirmation');
Route::any('/forgot-password', 'UsersController@forgotPassword');
Route::any('/reset-password/{ukey}', 'UsersController@resetPassword');
Route::any('/users/dashboard', 'UsersController@dashboard');
Route::any('/users/myaccount', 'UsersController@myaccount');
Route::post('/users/uploadprofileimage', 'UsersController@uploadprofileimage');
Route::post('/users/updatedata', 'UsersController@updatedata');
Route::any('/users/settings', 'UsersController@settings');
Route::any('/users/myrequests', 'UsersController@myrequests');
Route::any('/users/updatesettings', 'UsersController@updatesettings');

Route::get('/privacy-policy', 'PagesController@index');
Route::get('/terms-and-condition', 'PagesController@index');
Route::get('/about-us', 'PagesController@index');
Route::any('/contact-us', 'PagesController@contactus');

Route::any('/pages/checlapi', 'PagesController@checlapi');


#Route::get('/sendemail', 'HomesController@sendmail');
Route::group(['prefix' => 'admin','namespace'=>'Admin'], function()
{
    Route::any('/', 'AdminsController@login');
    Route::any('login', 'AdminsController@login');
    Route::any('admins/login', 'AdminsController@login');
    Route::get('admins/logout', 'AdminsController@logout');
    Route::get('admins/dashboard', 'AdminsController@dashboard');
    Route::get('admins/userchart/{daycount}', 'AdminsController@userchart');
    Route::any('admins/change-username', 'AdminsController@changeUsername');
    Route::any('admins/change-password', 'AdminsController@changePassword');
    Route::any('admins/change-email', 'AdminsController@changeEmail');
    Route::any('admins/forgot-password', 'AdminsController@forgotPassword');
    Route::any('admins/site-settings', 'AdminsController@siteSettings');
    
    Route::any('/admins/staff', 'AdminsController@staff');
    Route::any('/admins/addstaff', 'AdminsController@addstaff');    
    Route::any('/admins/editstaff/{slug}', 'AdminsController@editstaff');
    Route::get('/admins/activatestaff/{slug}', 'AdminsController@activatestaff');
    Route::get('/admins/deactivatestaff/{slug}', 'AdminsController@deactivatestaff');
    Route::get('/admins/deletestaff/{slug}', 'AdminsController@deletestaff');
    Route::get('/admins/deleteimagestaff/{slug}', 'AdminsController@deleteimagestaff');
    Route::any('/admins/managerolestaff/{slug}', 'AdminsController@managerolestaff');
    Route::any('/admins/getAdminRoles/{slug}', 'AdminsController@getAdminRoles');
    Route::any('/admins/getAdminRolesSub/{slug}', 'AdminsController@getAdminRolesSub');
    Route::any('/admins/getCheckRoles/{slug}', 'AdminsController@getCheckRoles');
    Route::any('/admins/getCheckRolesSub/{slug}', 'AdminsController@getCheckRolesSub');
    
    Route::any('/users', 'UsersController@index');
    Route::any('/users/add', 'UsersController@add');    
    Route::any('/users/edit/{slug}', 'UsersController@edit');
    Route::get('/users/activate/{slug}', 'UsersController@activate');
    Route::get('/users/deactivate/{slug}', 'UsersController@deactivate');
    Route::get('/users/delete/{slug}', 'UsersController@delete');
    Route::get('/users/deleteimage/{slug}', 'UsersController@deleteimage');

    Route::any('/services', 'ServicesController@index');
    Route::any('/services/add', 'ServicesController@add');    
    Route::any('/services/edit/{slug}', 'ServicesController@edit');
    Route::any('/services/activate/{slug}', 'ServicesController@activate');
    Route::any('/services/deactivate/{slug}', 'ServicesController@deactivate');
    Route::any('/services/delete/{slug}', 'ServicesController@delete');
    Route::any('/services/deleteimage/{slug}', 'ServicesController@deleteimage');

   Route::any('/requests', 'RequestsController@index'); 
   
      
    Route::any('/pages', 'PagesController@index');  
    Route::any('/pages/edit/{slug}', 'PagesController@edit');
    Route::any('/pages/pageimages', 'PagesController@pageimages');
    
   
});