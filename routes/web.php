<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

//force the route into https
URL::forceScheme('https');

Route::get('maintenance', 'HomeController@maintenance')->name('maintenance');

Route::view('/privacy-policy', 'auth.privacy_policy')->name('privacy-policy');
// ------------------------- Authentication --------------------------
Auth::routes();

// pop up when success regiter
Route::get('register/success','Auth\RegisterController@registerSuccess')->name('register-success');

// pop up when success regiter
Route::get('register/success','Auth\RegisterController@registerSuccess')->name('register-success');

//confirm the registration
Route::get('register/verify/{activation_code}','UserController@confirm');

//resend activation link
Route::post('register/resend','UserController@resend')->name('email-resend');

// ------------------------ Independent Pages ------------------------------------

//privacy policy
Route::view('/privacy-policy', 'auth.privacy_policy')->name('privacy-policy');

//thumbnails
Route::get('thumbs/{size}/{path}', 'ImageController@thumbnail')->where('path', '.*')->name('image-show');

//home page
Route::get('', 'HomeController@index')->name('home');

// show popup
Route::get('popup/show/{id}/{slug}', 'SettingController@showPopup')->name('popup-show');

// show detail image file
Route::get('view','MemberController@viewImage');

// FAQ Page
Route::get('frequently-asked-questions', 'HomeController@faq')->name('faq');

//sitemap
Route::get('sitemap', 'SitemapController@store')->name('sitemap');

// ----------------------------------------- Product ----------------------------------------
Route::group(['prefix' => 'product', 'middleware' => 'auth'], function () 
{
    Route::get('index', 'ProductController@index')->name('product-index');
    Route::get('create', 'ProductController@create')->name('product-create');
    Route::post('store', 'ProductController@store')->name('product-store');
    Route::get('edit/{id}', 'ProductController@edit')->name('product-edit');
    Route::post('update/{id}', 'ProductController@update')->name('product-update');
    Route::get('delete/{id}', 'ProductController@delete')->name('product-delete');
});

// ----------------------------------------- Product Package ----------------------------------------
Route::group(['prefix' => 'package', 'middleware' => 'auth'], function () 
{  
    Route::get('index', 'ProductPackageController@index')->name('package-index');
    Route::get('create', 'ProductPackageController@create')->name('package-create');
    Route::post('load-file', 'ProductPackageController@packageLoad')->name('package-load');
    Route::post('store', 'ProductPackageController@store')->name('package-store');
    Route::get('print/{id}', 'ProductPackageController@packagePrint')->name('package-print');
    Route::get('delete/{id}', 'ProductPackageController@delete')->name('package-delete');
    Route::get('edit/{id}', 'ProductPackageController@edit')->name('package-edit');
    Route::post('update/{id}', 'ProductPackageController@update')->name('package-update');
    Route::get('product/{id}', 'ProductPackageController@show')->name('product-show');
});


// ----------------------------------------- Product Category ----------------------------------------
Route::group(['prefix' => 'product-category', 'middleware' => 'auth'], function () {
    Route::get('index', 'ProductCategoryController@index')->name('product-category-index');
    Route::get('create', 'ProductCategoryController@create')->name('product-category-create');
    Route::post('store', 'ProductCategoryController@store')->name('product-category-store');
    Route::get('edit/{id}', 'ProductCategoryController@edit')->name('product-category-edit');
    Route::post('update/{id}', 'ProductCategoryController@update')->name('product-category-update');
    Route::get('delete/{id}', 'ProductCategoryController@delete')->name('product-category-delete');
    
});

//------------------- MEMBER ROUTES -----------------------------------------------------
// show member profile pop up
Route::get('profile/public/{id}', 'MemberController@publicProfile')->where('id', '[0-9]+')->name('member-public-profile');

Route::group(['prefix' => 'member'], function () 
{
    Route::group(['middleware' => 'auth'], function()
    {
        // show user member profile pop up
        Route::get('profile/mini/{id}', 'MemberController@miniProfile')->where('id', '[0-9]+')->name('member-mini-profile');

        // show users dashboard
        Route::get('dashboard', 'MemberController@dashboard')->name('member-dashboard');

        // show users activities
        Route::get('activity', 'MemberController@activity')->name('member-activity');

        // change password
        Route::get('password', 'PasswordController@edit')->name('change-password');

        // change password
        Route::post('password', 'PasswordController@update')->name('change-password');

        //member profile
        Route::get('my-profile', 'MemberController@myProfile');

        //member edit profile
        Route::get('edit-profile', 'MemberController@editProfile')->name('edit-profile');

        //member edit profile
        Route::post('edit-profile', 'MemberController@updateProfile')->name('edit-profile');

        //subscribe member
        Route::get('subscribe', 'MemberController@subscribeMember')->name('member-subscribe');

        //unsubscribe member
        Route::get('unsubscribe', 'MemberController@unsubscribeMember')->name('member-unsubscribe');

        //show subscriber list
        Route::get('{id}/subscriber/list', 'MemberController@showSubscriberList')->name('member-subscriber-list');

        //show member that they subscribe
        Route::get('{id}/subscribe/list', 'MemberController@showSubscribeList')->name('member-subscribe-list');

        //block member
        Route::get('block', 'MemberController@blockMember')->name('member-block');

        //unblock member
        Route::get('unblock', 'MemberController@unblockMember')->name('member-unblock');   

        //only for admin 
        Route::group(['middleware' => 'member'], function () 
        {
    		// login as member
    		Route::get('loginas/{id}','MemberController@loginAs')->name('member-login-as');  //login as

            // edit employee
            Route::get('edit/{id}', 'MemberController@edit')->name('member-edit');//edit

            // save edit employee
            Route::post('edit/{id}', 'MemberController@update')->name('member-edit');  //update

            // download member list excel
            Route::get('download', 'MemberController@download')->name('member-download');

            // make member list excel
            //Route::get('make', 'MemberController@makeExcel')->name('member-list-make');

            // show and search employee list
            Route::get('list', 'MemberController@index')->name('member-list');

            // show and search employee list through ajax
            Route::get('list/data/', 'MemberController@indexAjax');

            // show and search employee list
            Route::post('list', 'MemberController@index')->name('member-list');

            // show users profile
            Route::get('profile/{id}', 'MemberController@show')->where('id', '[0-9]+')->name('member-profile');

            // add other point to member
            Route::post('add/point', 'MemberController@addPoint')->name('member-add-point');

            // member summary
            Route::get('summary', 'MemberController@summary')->name('member-summary');

            // change password
            Route::post('change/password', 'PasswordController@updateMember')->name('member-password');

            // member status (AJAX call)
            Route::get('status', 'MemberController@status')->name('member-status');

            // add new member
            Route::get('create', 'MemberController@create')->name('member-create');
            Route::post('create', 'MemberController@store')->name('member-create');
        });
    });
});

// ----------------------------------------- NOTIFICATION ----------------------------------------
Route::group(['prefix' => 'notification'], function()
{
    Route::group(['middleware' => 'auth'], function()
    {
        //get more notification
        Route::get('{member_id}/more', 'NotificationController@getNotification')->name('get-notification-more');

        //see all notifications
        Route::get('{member_id}/all', 'NotificationController@getAllNotifications')->name('get-notification-all');

        //read notif
        Route::get('{member_id}/read/{id}', 'NotificationController@readNotif')->name('read-notification');
    
        // blast notification
        // Route::group(['middleware' => 'notification'], function ()
        // {
            Route::get('create', 'NotificationController@create')->name('create-notification');
            Route::post('create', 'NotificationController@store')->name('create-notification');
            Route::get('create/member', 'NotificationController@getMemberAjax')->name('blast-notification-ajax-member');
            Route::get('create/recipient', 'NotificationController@getRecipientAjax')->name('blast-notification-ajax-recipient');
            Route::post('student-of-the-month/store', 'NotificationController@storeStudentOfTheMonthNotification')->name('store-sotm-notification');
        // });

        Route::post('invoice-push-notification', 'NotificationController@invoicePushNotification')->name('invoice-push-notification');
    });
});

Route::group(['middleware' => 'auth'], function ()
{
    Route::get('search', 'UserController@search')->name('user-search');

    Route::group(['prefix' => 'promo'], function () {
        Route::get('list', 'Backend\PromoController@index')->name('promo-list');
        Route::get('create', 'Backend\PromoController@create')->name('promo-create');
        Route::post('store', 'Backend\PromoController@store')->name('promo-store');
        Route::get('edit/{id}', 'Backend\PromoController@edit')->name('promo-edit');
        Route::post('update/{id}', 'Backend\PromoController@update')->name('promo-update');
        Route::delete('deletePromo/{id}', 'Backend\PromoController@destroy')->name('promo-delete');
    });
});