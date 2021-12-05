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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login/{lang}/{v}', [ 'as' => 'login', 'uses' => 'AuthController@login'])->middleware('checkguest');
    Route::post('logout/{lang}/{v}', 'AuthController@logout');
    Route::post('refresh/{lang}/{v}', 'AuthController@refresh');
    Route::post('me/{lang}/{v}', 'AuthController@me');
    Route::post('register/{lang}/{v}' , [ 'as' => 'register', 'uses' => 'AuthController@register'])->middleware('checkguest');
});

Route::get('/invalid/{lang}/{v}', [ 'as' => 'invalid', 'uses' => 'AuthController@invalid']);


// users apis group
Route::group([
    'middleware' => 'api',
    'prefix' => 'user'
], function($router) {
    Route::get('profile/{lang}/{v}' , 'UserController@getprofile');
    Route::put('profile/{lang}/{v}' , 'UserController@updateprofile');
    Route::put('update-email/{lang}/{v}' , 'UserController@updateEmail');
    
    Route::put('resetpassword/{lang}/{v}' , 'UserController@resetpassword');
    Route::put('resetforgettenpassword/{lang}/{v}' , 'UserController@resetforgettenpassword')->middleware('checkguest');
    Route::post('checkphoneexistance/{lang}/{v}' , 'UserController@checkphoneexistance')->middleware('checkguest');
    Route::get('notifications/{lang}/{v}' , 'UserController@notifications');

});


// favorites
Route::group([
    'middleware' => 'api',
    'prefix' => 'favorites'
] , function($router){
    Route::get('/{lang}/{v}' , 'FavoriteController@getfavorites');
    Route::post('/{lang}/{v}' , 'FavoriteController@addtofavorites');
    Route::delete('/{lang}/{v}' , 'FavoriteController@removefromfavorites');
});

// favorites
Route::group([
    'middleware' => 'api',
    'prefix' => 'addresses'
] , function($router){
    Route::get('/{lang}/{v}' , 'AddressController@getaddress');
    Route::get('/belongstoarea/{lang}/{v}' , 'AddressController@selectAddressBelongsToArea');
    Route::post('/{lang}/{v}' , 'AddressController@addaddress');
    Route::delete('/{lang}/{v}' , 'AddressController@removeaddress');
    Route::post('/setdefault/{lang}/{v}' , 'AddressController@setmain');
    Route::get('/areas/{lang}/{v}' , 'AddressController@getareas')->middleware('checkguest');
    Route::get('/all-areas/{governorate}/{lang}/{v}' , 'AddressController@getAllAreas');
    Route::get('/governorates/{lang}/{v}' , 'AddressController@getGovernorates');
    Route::get('/details/{id}/{lang}/{v}' , 'AddressController@getdetails');
    Route::get('/in-polygon/{lang}/{v}' , 'AddressController@getInPolygon');
    
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'orders'
] , function($router){
    Route::post('create/{lang}/{v}' , 'OrderController@create');
    Route::post('categories/{lang}/{v}' , 'OrderController@categories');
    Route::post('buy/{lang}/{v}' , 'OrderController@directBuy');
    Route::post('retrieve/{lang}/{v}' , 'OrderController@retrieve_item');
    Route::get('{lang}/{v}' , 'OrderController@getorders');
    Route::get('expected-periods/{lang}/{v}' , 'OrderController@getExpectedDeliveryPeriod');
    Route::get('{id}/{lang}/{v}' , 'OrderController@orderdetails');
    Route::get('cancel/{item}/{lang}/{v}' , 'OrderController@cancel_item');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'wallets'
] , function($router){
    Route::get('balance/{lang}/{v}' , 'WalletController@getMyWalletBalance');
    Route::post('addbalance/{lang}/{v}' , 'WalletController@addBalanceToWallet');
});

Route::get('offers/{lang}/{v}' , 'OfferController@get_offers')->middleware('checkguest');
Route::get('offers_android/{lang}/{v}' , 'OfferController@get_offers')->middleware('checkguest');

Route::get('delivery_price/{lang}/{v}' , 'AddressController@getdeliveryprice')->middleware('checkguest');

// visitors
Route::group([
    'middleware' => 'api',
    'prefix' => 'visitors'
], function($router){
    
    Route::post('create/{lang}/{v}' , 'VisitorController@create')->middleware('checkguest');
    Route::post('cart/add/{lang}/{v}' , 'VisitorController@add')->middleware('checkguest');
    Route::delete('cart/delete/{lang}/{v}' , 'VisitorController@delete')->middleware('checkguest');
    Route::post('cart/get/{lang}/{v}' , 'VisitorController@get')->middleware('checkguest');
    Route::post('cart/cart-before-pay/{lang}/{v}' , 'VisitorController@getCartBeforePay');
    Route::post('cart/getbeforeorder/{lang}/{v}' , 'VisitorController@get_cart_before_order');
    Route::put('cart/changecount/{lang}/{v}' , 'VisitorController@changecount')->middleware('checkguest');
    Route::post('cart/count/{lang}/{v}' , 'VisitorController@getcartcount')->middleware('checkguest');
    Route::post('discount/{lang}/{v}' , 'VisitorController@getDiscount')->middleware('checkguest');
    
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'categories'
], function($router){
    Route::post('get/{lang}/{v}' , 'CategoryController@getCategories');
    Route::get('slider/{lang}/{v}' , 'CategoryController@getCategoriesSlider');
    Route::get('{category_id}/sub-categories/{lang}/{v}' , 'CategoryController@getSubCategories');
    Route::get('{sub_category_id}/sub-categories-two/{lang}/{v}' , 'CategoryController@getSubTwoCategories');
    Route::get('{sub_category_id}/sub-categories-three/{lang}/{v}' , 'CategoryController@getSubThreeCategories');
    Route::get('{sub_category_id}/sub-categories-four/{lang}/{v}' , 'CategoryController@getSubFourCategories');
    Route::get('{sub_category_id}/sub-categories-five/{lang}/{v}' , 'CategoryController@getSubFiveCategories');
    Route::post('cart/add/{lang}/{v}' , 'VisitorController@add')->middleware('checkguest');
    Route::delete('cart/delete/{lang}/{v}' , 'VisitorController@delete')->middleware('checkguest');
    Route::post('cart/get/{lang}/{v}' , 'VisitorController@get')->middleware('checkguest');
    Route::post('cart/getbeforeorder/{lang}/{v}' , 'VisitorController@get_cart_before_order');
    Route::put('cart/changecount/{lang}/{v}' , 'VisitorController@changecount')->middleware('checkguest');
    Route::post('cart/count/{lang}/{v}' , 'VisitorController@getcartcount')->middleware('checkguest');
});

// get home data
Route::get('sliders/{type}/{lang}/{v}' , 'HomeController@getSlider')->middleware('checkguest');
Route::get('offers/{type}/{lang}/{v}' , 'HomeController@getoffers')->middleware('checkguest');
Route::get('offers-slider/{lang}/{v}' , 'HomeController@getOffersSlider')->middleware('checkguest');
Route::get('offers-page/{type}/{lang}/{v}' , 'HomeController@getOffersPage')->middleware('checkguest');
Route::get('countries/{lang}/{v}' , 'HomeController@getCountries')->middleware('checkguest');
Route::get('categories/{lang}/{v}' , 'CategoryController@getcategories')->middleware('checkguest');
Route::get('sub_categories/{category_id}/{lang}/{v}' , 'CategoryController@get_sub_categories')->middleware('checkguest');


// get home data
Route::get('home/{lang}/{v}' , 'HomeController@getdata')->middleware('checkguest');

// send contact us message
Route::post('/contactus/{lang}/{v}' , 'ContactUsController@SendMessage')->middleware('checkguest');

// get app number
Route::get('/getappnumber/{lang}/{v}' , 'SettingController@getappnumber')->middleware('checkguest');

// get whats app number
Route::get('/getwhatsappnumber/{lang}/{v}' , 'SettingController@getwhatsapp')->middleware('checkguest');

// get social media
Route::get('/getsocialmedia/{lang}/{v}' , 'SettingController@social_media')->middleware('checkguest');


// get products 
Route::get('/products/show/{lang}/{v}' , 'ProductController@getproducts')->middleware('checkguest');

// get store products 
Route::get('/products/store/{storeId}/{lang}/{v}' , 'ProductController@getStoreProducts')->middleware('checkguest');

// get products 
Route::get('/products/{lang}/{v}' , 'ProductController@get_sub_category_products')->middleware('checkguest');

// get products brand
Route::get('/products/brand/{brand_id}/{lang}/{v}' , 'ProductController@getbrandproducts')->middleware('checkguest');

// get product details
Route::get('/products/{id}/{lang}/{v}' , 'ProductController@getdetails')->middleware('checkguest');

// rates
// get rates 
Route::get('/rate/{order_id}/{lang}/{v}' , 'RateController@getrates')->middleware('checkguest');
// add rate
Route::post('/rate/{lang}/{v}' , 'RateController@addrate');

Route::get('/search/{lang}/{v}' , 'SearchByNameController@search')->middleware('checkguest');

// join request
Route::post('/join/request/{lang}/{v}', "SettingController@joinRequest")->middleware('checkguest');


// store
// get store categories
Route::get('/store/categories/{id}/{lang}/{v}', "ShopController@store_categories")->middleware('checkguest');

// get store category product
Route::get('/store/category/{storeId}/{lang}/{v}', "ShopController@get_store_products")->middleware('checkguest');

// filter
// get filter data
Route::get('/filter/{storeId}/{lang}/{v}', "FilterController@get_filter")->middleware('checkguest');

Route::get('/excute' , 'OrderController@execute');
Route::get('/pay/success' , 'OrderController@pay_sucess');
Route::get('/pay/error' , 'OrderController@pay_error');
Route::get('/excute_pay' , 'OrderController@excute_pay');
Route::get('/wallet/excute_pay' , 'WalletController@excute_pay');
