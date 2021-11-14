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
// auth
Route::group([
    'middleware' => 'dashboard',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login/{lang}/{v}', [ 'as' => 'login', 'uses' => 'AuthController@login'])->middleware('checkguest');
    Route::post('logout/{lang}/{v}', 'AuthController@logout');
    // Route::post('refresh/{lang}/{v}', 'AuthController@refresh');
    Route::post('me/{lang}/{v}', 'AuthController@me');
});

// user
Route::group([
    'middleware' => 'dashboard',
    'prefix' => 'user'
], function ($router) {
    // get home data
    Route::get('profile/{lang}/{v}' , 'UserController@getProfile');

    // update profile
    Route::put('profile/{lang}/{v}' , 'UserController@updateprofile');

    // update logo & cover
    Route::put('update-logo-cover/{lang}/{v}' , 'UserController@updateLogo');

    // reset password
    Route::put('resetpassword/{lang}/{v}' , 'UserController@resetpassword');

    // get areas
    Route::get('areas/{lang}/{v}' , 'UserController@getAreas');

    // get covered areas
    Route::get('covered-areas/{lang}/{v}' , 'UserController@getCoveredAreas');

    // update delivery areas
    Route::post('deliverytime/{lang}/{v}' , 'UserController@updateDeliveryAreas');

    // delete delivery area
    Route::delete('deliverytime/{area_id}/{lang}/{v}' , 'UserController@deleteDeliveryArea');

    // get notifications
    Route::get('notifications/{lang}/{v}' , 'UserController@getNotifications');
});

// home
Route::group([
    'middleware' => 'dashboard',
    'prefix' => 'home'
], function ($router) {
    // get home data
    Route::get('{lang}/{v}' , 'HomeController@getdata');

    // get search
    Route::get('search/{lang}/{v}' , 'HomeController@search');
});

// products
Route::group([
    'middleware' => 'dashboard',
    'prefix' => 'products'
], function ($router) {
    // get products
    Route::get('{lang}/{v}' , 'ProductController@getProducts');

    // add product
    Route::post('add/{lang}/{v}' , 'ProductController@addProduct');

    // upload images
    Route::post('uploadimages/{lang}/{v}' , 'ProductController@uploadImages');

    // update primary image
    Route::put('primaryimage/{lang}/{v}', 'ProductController@primaryImage');

    // delete product image
    Route::delete('deleteimage/{lang}/{v}', 'ProductController@deleteImage');

    // delete product
    Route::delete('delete/{lang}/{v}', 'ProductController@deleteProduct');
    
    // add amount
    Route::put('addamount/{product}/{lang}/{v}', 'ProductController@addAmount');

    // update product
    Route::put('update/{product}/{lang}/{v}', 'ProductController@updateProduct');

    // get product data
    Route::get('{product}/{lang}/{v}', 'ProductController@getProduct');
});

// get categories
Route::get('categories/{lang}/{v}' , 'ProductController@getCategories');

// get types
Route::get('types/{lang}/{v}' , 'ProductController@getTypes');

// get properties
Route::get('properties/{category}/{lang}/{v}' , 'ProductController@getOptions');

// orders
Route::group([
    'middleware' => 'dashboard',
    'prefix' => 'orders'
], function ($router) {
    // get orders
    Route::get('{lang}/{v}' , 'OrderController@getOrders');

    // get products orders
    Route::get('products-orders/{lang}/{v}' , 'OrderController@getProductsOrders');

    // order details
    Route::get('details/{order}/{lang}/{v}' , 'OrderController@orderDetails');

    // order address details
    Route::get('address/{address}/{lang}/{v}' , 'OrderController@orderAddressDetails');

    // update order status
    Route::put('status/{order}/{lang}/{v}' , 'OrderController@updateOrderStatus');

    // update item status
    Route::put('status/item/{item}/{lang}/{v}' , 'OrderController@updateItemStatus');
});

// refunds
Route::group([
    'middleware' => 'dashboard',
    'prefix' => 'refunds'
], function ($router) {
    // get refunds
    Route::get('{lang}/{v}' , 'RefundController@getRefunds');

    // refund details
    Route::get('details/{order}/{lang}/{v}' , 'RefundController@refundDetails');

    // accept refunds
    Route::put('update/{order}/{lang}/{v}' , 'RefundController@acceptRefunds');
});

