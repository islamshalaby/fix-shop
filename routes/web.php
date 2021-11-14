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
// Web site
Route::get('/setlocale/{locale}',function($lang){
    Session::put('locale',$lang);
    return redirect()->back();
});




// Dashboard Routes
Route::group([
    'middleware'=>'language',
    'prefix' => "admin-panel",
    'namespace' => "Admin"
] , function($router){

    Route::get('' ,'HomeController@show');
    Route::get('login' ,  [ 'as' => 'adminlogin', 'uses' => 'AdminController@getlogin']);
    Route::post('login' , 'AdminController@postlogin');
    Route::get('logout' , 'AdminController@logout');
    Route::get('profile' , 'AdminController@profile');
    Route::post('profile' , 'AdminController@updateprofile');
    Route::get('databasebackup' , 'AdminController@backup');

    // Users routes for dashboard
    Route::group([
        'prefix' => 'users',
    ] , function($router){
            Route::get('add' , 'UserController@AddGet');
            Route::post('add' , 'UserController@AddPost');
            Route::get('show' , 'UserController@show');
            Route::get('edit/{id}' , 'UserController@edit');
            Route::post('edit/{id}' , 'UserController@EditPost');
            Route::get('details/{id}' , 'UserController@details')->name("users.details");
            Route::post('send_notifications/{id}' , 'UserController@SendNotifications');
            Route::get('block/{id}' , 'UserController@block');
            Route::get('active/{id}' , 'UserController@active');
            Route::get('addresses/{user}' , 'UserController@get_user_addresses')->name('user.addresses');
            Route::get('address/details/{address}' , 'UserController@address_details')->name('address.details');
            Route::put('update/vip', 'UserController@assignVip')->name('user.assignVip');
        }
    );

    // Users routes for dashboard
    Route::group([
        'prefix' => 'countries',
    ] , function($router){
            Route::get('show' , 'CountryController@show')->name("countries.show");
            Route::get('edit/{country}' , 'CountryController@edit')->name("countries.edit");
            Route::post('edit/{country}' , 'CountryController@update');

        }
    );

    // admins routes for dashboard
    Route::group([
        'prefix' => "managers",
    ], function($router){
        Route::get('add' , 'ManagerController@AddGet');
        Route::post('add' , 'ManagerController@AddPost');
        Route::get('show' , 'ManagerController@show');
        Route::get('edit/{id}' , 'ManagerController@edit');
        Route::post('edit/{id}' , 'ManagerController@EditPost');
        Route::get('delete/{id}' , 'ManagerController@delete');
    });

    // App Pages For Dashboard
    Route::group([
        'prefix' => 'app_pages'
    ] , function($router){
        Route::get('aboutapp' , 'AppPagesController@GetAboutApp');
        Route::post('aboutapp' , 'AppPagesController@PostAboutApp');
        Route::get('termsandconditions' , 'AppPagesController@GetTermsAndConditions');
        Route::post('termsandconditions' , 'AppPagesController@PostTermsAndConditions');
        Route::get('privacy' , 'AppPagesController@privacy');
        Route::post('privacy' , 'AppPagesController@PostPrivacy');
        
        Route::get('deliveryinformation' , 'AppPagesController@GetDeliveryInformation');
        Route::post('deliveryinformation' , 'AppPagesController@PostDeliveryInformation');
        Route::get('returnpolicy' , 'AppPagesController@GetReturnPolicy');
        Route::post('returnpolicy' , 'AppPagesController@PostReturnPolicy');
    });

    // Setting Route
    Route::get('settings' , 'SettingController@GetSetting');
    Route::post('settings' , 'SettingController@PostSetting');

    // Rates
    Route::get('rates' , 'RateController@Getrates');
   Route::get('rates/active/{id}' , 'RateController@activeRate');

   // discounts
   Route::resource('discounts', 'DiscountController');

    // meta tags Route
    Route::get('meta_tags' , 'MetaTagController@getMetaTags');
    Route::post('meta_tags' , 'MetaTagController@postMetaTags');

    // Ads Route
    Route::group([
        "prefix" => "ads"
    ],function($router){
        Route::get('add' , 'AdController@AddGet');
        Route::post('add' , 'AdController@AddPost');
        Route::get('show' , 'AdController@show')->name('ads.index');
        Route::get('edit/{id}' , 'AdController@EditGet')->name('ads.edit');
        Route::post('edit/{id}' , 'AdController@EditPost');
        Route::get('details/{id}' , 'AdController@details');
        Route::get('delete/{id}' , 'AdController@delete');
        Route::get('fetchproducts' , 'AdController@fetch_products')->name("products.fetch");
        Route::get('fetchcategories' , 'AdController@fetch_categories')->name("categories.fetch");
        Route::get('fetchstorecategories/{store}' , 'AdController@fetch_store_categories');
        Route::get('fetchstores' , 'AdController@fetch_stores')->name("stores.fetch");
    });

    // Like card Route
    Route::group([
        "prefix" => "products/like-card"
    ], function($router){

        Route::get('' , 'LikeCardController@getLikeCard')->name('likeCard.index');
        Route::get('cats' , 'LikeCardController@getMerchantCategories')->name('likeCard.categories');
        Route::get('products/{cat_id}' , 'LikeCardController@getLikeCardProducts')->name('likeCard.products');
        Route::get('product/{id}' , 'LikeCardController@getProductToBuy')->name('likeCard.product.details');
        Route::post('product/{id}' , 'LikeCardController@postBuyAndAddProduct');
         
    });

    // Categories Route
    // Route::group([
    //     "prefix" => "categories"
    // ], function($router){
    //      Route::get('add' , 'CategoryController@AddGet');
    //      Route::post('add' , 'CategoryController@AddPost');
    //      Route::get('show' , 'CategoryController@show')->name('categories.index');
    //      Route::get('edit/{id}' , 'CategoryController@EditGet');
    //      Route::post('edit/{id}' , 'CategoryController@EditPost');
    //      Route::get('delete/{id}' , 'CategoryController@delete');
    //      Route::get('details/{category}' , 'CategoryController@details')->name('categories.details');
    // });

    // Categories Route
    Route::group(["prefix" => "categories","namespace" => "categories"], function($router){
        Route::get('add' , 'CategoryController@AddGet');
        Route::post('add' , 'CategoryController@AddPost');
        Route::get('show' , 'CategoryController@show')->name('categories.index');
        Route::get('edit/{id}' , 'CategoryController@EditGet');
        Route::post('edit/{id}' , 'CategoryController@EditPost');
        Route::get('delete/{id}' , 'CategoryController@delete');
        Route::get('show_home/{category}/{status}' , 'CategoryController@showInHome')->name('show.category.home');
        Route::get('products/{category}' , 'CategoryController@category_products')->name('category.products');
   });

    // Sub Categories Route
    Route::group(["prefix" => "categories","namespace" => "categories"], function($router){
        Route::resource('sub_cat', 'SubCategoryController');
        Route::get('sub_cat/create/{id}' , 'SubCategoryController@create')->name("sub_cat.create.new");
        Route::post('sub_cat/update/{id}' , 'SubCategoryController@update')->name("sub_cat.update.new");
        Route::get('sub_cat/delete/{id}' , 'SubCategoryController@destroy')->name("sub_cat.delete");
    });

    // Sub two Categories Route
    Route::group(["prefix" => "categories","namespace" => "categories"], function($router){
        Route::resource('sub_two_cat', 'SubTwoCategoryController');
        Route::get('sub_two_cat/create/{id}' , 'SubTwoCategoryController@create')->name("sub_two_cat.create.new");
        Route::post('sub_two_cat/update/{id}' , 'SubTwoCategoryController@update')->name("sub_two_cat.update.new");
        Route::get('sub_two_cat/delete/{id}' , 'SubTwoCategoryController@destroy')->name("sub_two_cat.delete");
    });
    // Sub three Categories Route
    Route::group(["prefix" => "categories","namespace" => "categories"], function($router){
        Route::resource('sub_three_cat', 'SubThreeCategoryController');
        Route::get('sub_three_cat/create/{id}' , 'SubThreeCategoryController@create')->name("sub_three_cat.create.new");
        Route::post('sub_three_cat/update/{id}' , 'SubThreeCategoryController@update')->name("sub_three_cat.update.new");
        Route::get('sub_three_cat/delete/{id}' , 'SubThreeCategoryController@destroy')->name("sub_three_cat.delete");
    });
    // Sub four Categories Route
    Route::group(["prefix" => "categories","namespace" => "categories"], function($router){
        Route::resource('sub_four_cat', 'SubFourCategoryController');
        Route::get('sub_four_cat/create/{id}' , 'SubFourCategoryController@create')->name("sub_four_cat.create.new");
        Route::post('sub_four_cat/update/{id}' , 'SubFourCategoryController@update')->name("sub_four_cat.update.new");
        Route::get('sub_four_cat/delete/{id}' , 'SubFourCategoryController@destroy')->name("sub_four_cat.delete");
    });
    // Sub five Categories Route
    Route::group(["prefix" => "categories","namespace" => "categories"], function($router){
        Route::resource('sub_five_cat', 'SubFiveCategoryController');
        Route::get('sub_five_cat/create/{id}' , 'SubFiveCategoryController@create')->name("sub_five_cat.create.new");
        Route::post('sub_five_cat/update/{id}' , 'SubFiveCategoryController@update')->name("sub_five_cat.update.new");
        Route::get('sub_five_cat/delete/{id}' , 'SubFiveCategoryController@destroy')->name("sub_five_cat.delete");
    });


    // Home Sections Route
    Route::group([
        "prefix" => "home_sections"
    ], function($router){
         Route::get('show' , 'HomeSectionsController@show')->name('home_sections.index');
         Route::post('sort' , 'HomeSectionsController@updateSectionsSorting')->name('home_sections.sort');
         Route::get('add' , 'HomeSectionsController@AddGet')->name('home_sections.add');
         Route::get('fetch/{element}' , 'HomeSectionsController@fetchData');
         Route::post('add' , 'HomeSectionsController@AddPost');
         Route::get('edit/{homeSection}' , 'HomeSectionsController@EditGet')->name('home_sections.edit');
         Route::post('edit/{homeSection}' , 'HomeSectionsController@EditPost');
         Route::get('delete/{homeSection}' , 'HomeSectionsController@delete')->name('home_sections.delete');
         Route::get('details/{homeSection}' , 'HomeSectionsController@details')->name('home_sections.details');
    });

    // Home Offers Route
    Route::group([
        "prefix" => "offers"
    ], function($router){
         Route::get('show' , 'OffersController@show')->name('offers.index');
         Route::get('fetch/{type}' , 'OffersController@fetchType')->name('fetch.type');
         Route::get('add' , 'OffersController@AddGet')->name('offers.add');
         Route::post('add' , 'OffersController@AddPost');
         Route::post('sort' , 'OffersController@updateOffersSorting')->name('offers.sort');
         Route::get('edit/{offer}' , 'OffersController@EditGet')->name('offers.edit');
         Route::post('edit/{offer}' , 'OffersController@EditPost');
         Route::get('delete/{offer}' , 'OffersController@delete')->name('offers.delete');
         Route::get('details/{offer}' , 'OffersController@details')->name('offers.details');
    });

    // Home Properties Route
    Route::group([
        "prefix" => "properties"
    ], function($router){
         Route::get('show' , 'OptionsController@show')->name('options.index');
         Route::get('add' , 'OptionsController@AddGet')->name('options.add');
         Route::post('add' , 'OptionsController@AddPost');
         Route::get('edit/{option}' , 'OptionsController@EditGet')->name('options.edit');
         Route::post('edit/{option}' , 'OptionsController@EditPost');
         Route::get('delete/{option}' , 'OptionsController@delete')->name('options.delete');
    });

    // Home Properties Route
    Route::group([
        "prefix" => "properties_categories"
    ], function($router){
         Route::get('show' , 'OptionsController@show_properties_categories')->name('options.categories.index');
         Route::get('add' , 'OptionsController@addGetPropertyCategory')->name('options.categories.add');
         Route::post('add' , 'OptionsController@addPostPropertiesCategory');
         Route::get('edit/{category}' , 'OptionsController@editGetPropertiesCategory')->name('options.categories.edit');
         Route::post('edit/{category}' , 'OptionsController@editPostPropertiesCategory');
         Route::get('delete/{category}' , 'OptionsController@deletePropertiesCategory')->name('options.categories.delete');
    });

        // Home Multiple Options Route
        Route::group([
            "prefix" => "multi_options"
        ], function($router){
             Route::get('show' , 'MultiOptionController@show')->name('multi_options.index');
             Route::get('add' , 'MultiOptionController@AddGet')->name('multi_options.add');
             Route::post('add' , 'MultiOptionController@AddPost');
             Route::get('edit/{option}' , 'MultiOptionController@EditGet')->name('multi_options.edit');
             Route::get('edit/mvalue/{mvalue}' , 'MultiOptionController@edit_m_option_value')->name('multi_options_value.edit');
             Route::post('edit/{option}' , 'MultiOptionController@EditPost');
             Route::post('edit/mvalue/{mvalue}' , 'MultiOptionController@edit_m_option_value_post');
             Route::get('delete/{option}' , 'MultiOptionController@delete')->name('multi_options.delete');
             Route::get('delete/mvalue/{mvalue}' , 'MultiOptionController@delete_m_option_value')->name('multi_options.delete.mvalue');
        });

     // Orders Route
     Route::group([
        "prefix" => "orders"
    ], function($router){
         Route::get('show' , 'OrderController@show')->name('orders.index');
         Route::get('sub-orders' , 'OrderController@showSubOrders')->name('orders.subOrders.index');
         Route::get('delivery-reports' , 'OrderController@showDeliveryReports')->name('orders.deliveryReports.index');
         Route::get('products-orders' , 'OrderController@showProductsOrders')->name('orders.productsOrders.index');
         Route::get('action/items/{item}' , 'OrderController@order_items_actions')->name('orders.items.action');
         Route::get('action/order/{item}' , 'OrderController@order_actions')->name('orders.subo.action');
         Route::get('action/sub/{order}' , 'OrderController@action_sub_order')->name('orders.sub.action');
         Route::get('action/{order}/{status}' , 'OrderController@action_order')->name('orders.action');
         Route::get('cancel/{type}/{orderId}' , 'OrderController@cancelOrder')->name('orders.cancel');
         Route::get('details/{order}' , 'OrderController@details')->name('orders.details');
         Route::get('details-sub-order/{order}' , 'OrderController@subOrdersDetails')->name('orders.sub_order.details');
         Route::get('filter/{status}' , 'OrderController@filter_orders')->name('orders.filter');
         Route::get('fetchbyarea' , 'OrderController@fetch_orders_by_area')->name('orders.fetchbyarea');
         Route::get('fetchbydate' , 'OrderController@fetch_orders_date')->name('orders.fetchbydate');
         Route::get('fetchbypayment' , 'OrderController@fetch_order_payment_method')->name('orders.fetchbypayment');
         Route::get('fetchbysubnumber' , 'OrderController@fetch_order_by_sub_order_number')->name('orders.fetchbysubnumber');
         Route::get('invoice/{order}' , 'OrderController@getInvoice')->name('orders.invoice');
         Route::get('size/details/{item}' , 'OrderController@order_size_details')->name('orders.size.details');
    });



    // Areas Route
    Route::group([
        "prefix" => "areas"
    ], function($router){
         Route::get('show' , 'AreasController@show')->name('areas.index');
         Route::get('show-governorates' , 'AreasController@getGovernorates')->name('areas.governorates.index');
         Route::get('add' , 'AreasController@AddGet')->name('areas.add');
         Route::post('add' , 'AreasController@AddPost');
         Route::get('add-governorates' , 'AreasController@getAddGovernorate')->name('areas.governorates.add');
         Route::post('add-governorates' , 'AreasController@postAddGovernorate');
         Route::get('edit/{area}' , 'AreasController@EditGet')->name('areas.edit');
         Route::post('edit/{area}' , 'AreasController@EditPost');
         Route::get('edit-governorates/{governorate}' , 'AreasController@getEditGovernorate')->name('areas.governorates.edit');
         Route::post('edit-governorates/{governorate}' , 'AreasController@postEditGovernorate');
         Route::get('delete/{area}' , 'AreasController@delete')->name('areas.delete');
         Route::get('delete-governorates/{governorate}' , 'AreasController@getDeleteGovernorate')->name('areas.governorates.delete');
         Route::get('details/{area}' , 'AreasController@details')->name('areas.details');
         Route::get('delete-delivery-area/{cost}' , 'AreasController@deleteDeliveryArea')->name('areas.delivery.delete');
         Route::get('details-governorates/{governorate}' , 'AreasController@getGovernorateDetails')->name('areas.governorates.details');
    });

    // Areas Route
    Route::group([
        "prefix" => "deliver-cost"
    ], function($router){
        Route::get('show/deliverycost/{area}' , 'AreasController@show_delivery_costs')->name('areas.show.delivercost');
        Route::get('deliverycost' , 'AreasController@deliver_cost_areas')->name('areas.byArea.delivercost');
        Route::get('add/deliverycost/{area}' , 'AreasController@getAddDeliveryByArea')->name('areas.add.byArea.delivercost');
        Route::post('add/deliverycost/{area}' , 'AreasController@add_deliver_cost_post');
        Route::get('edit/deliverycost/{area}/{cost}' , 'AreasController@edit_delivery_cost_get')->name('edit_delivery_cost_get');
        Route::post('edit/deliverycost/{area}/{cost}' , 'AreasController@edit_delivery_cost_post');
        Route::get('delete/deliverycost/{cost}' , 'AreasController@deleteDeliveryCost')->name('delete_delivery_cost_get');
        Route::get('fetch-stores-by-area/{area}' , 'AreasController@fetchStoresByArea');
        Route::get('add-by-governorate' , 'AreasController@addDeliveryCostByGovernorate')->name('areas.add.deliveryCostByGovernorate');
        Route::get('adddelivery-by-governorate/{governorate}' , 'AreasController@getAddDeliveryByGovernorate')->name('areas.adddelivery.deliveryCostByGovernorate');
        Route::post('adddelivery-by-governorate/{governorate}' , 'AreasController@add_deliver_cost_post_by_governorate');
    });

    // Brands Route
    Route::group([
        "prefix" => "brands"
    ], function($router){
         Route::get('show' , 'BrandController@show')->name('brands.index');
         Route::get('add' , 'BrandController@AddGet')->name('brands.add');
         Route::post('add' , 'BrandController@AddPost');
         Route::get('edit/{brand}' , 'BrandController@EditGet')->name('brands.edit');
         Route::post('edit/{brand}' , 'BrandController@EditPost');
         Route::get('delete/{brand}' , 'BrandController@delete')->name('brands.delete');
         Route::get('details/{brand}' , 'BrandController@details')->name('brands.details');
    });

    // Refund Route
    Route::group([
        "prefix" => "refund"
    ], function($router){
         Route::get('show' , 'RefundController@show')->name('refund.index');
         Route::get('details/{refund}' , 'RefundController@details')->name('refund.details');
         Route::get('accept/{refund}' , 'RefundController@accept')->name('refund.accept');
         Route::get('reject/{refund}' , 'RefundController@reject')->name('refund.reject');
         Route::get('received/{refund}' , 'RefundController@received')->name('refund.received');
    });

    // Wallet route
    Route::group([
        "prefix" => "wallet"
    ], function($router){
         Route::get('show' , 'WalletController@show')->name('wallet.index');
         Route::get('transactions/{wallet}' , 'WalletController@transactions')->name('wallet.transactions');
    });

    // Product types Route
    Route::group([
        "prefix" => "product_type"
    ], function($router){
         Route::get('show' , 'ProductTypeController@show')->name('product_type.index');
         Route::get('add' , 'ProductTypeController@AddGet')->name('product_type.add');
         Route::post('add' , 'ProductTypeController@AddPost');
         Route::get('edit/{type}' , 'ProductTypeController@EditGet')->name('product_type.edit');
         Route::post('edit/{type}' , 'ProductTypeController@EditPost');
         Route::get('delete/{type}' , 'ProductTypeController@delete')->name('product_type.delete');
    });



    // Sub Categories Route
    Route::group([
        "prefix" => "sub_categories"
    ], function($router){
         Route::get('show' , 'SubCategoryController@show')->name('sub_categories.index');
         Route::get('add' , 'SubCategoryController@AddGet')->name('sub_categories.add');
         Route::post('add' , 'SubCategoryController@AddPost');
         Route::get('fetchbrand/{category}' , 'SubCategoryController@fetchBrands')->name('fetch.brands');
         Route::get('edit/{subCategory}' , 'SubCategoryController@EditGet')->name('sub_categories.edit');
         Route::post('edit/{subCategory}' , 'SubCategoryController@EditPost');
         Route::get('details/{subCategory}' , 'SubCategoryController@details')->name('sub_categories.details');
         Route::get('delete/{subCategory}' , 'SubCategoryController@delete')->name('sub_categories.delete');
    });

    // Products Route
    Route::group([
        "prefix" => "products"
    ], function($router){
         Route::get('show' , 'ProductController@show')->name('products.index');
         Route::get('fetchbrands/{category}' , 'ProductController@fetch_category_brands');
         Route::get('fetchsubcats/{id}/{number}' , 'ProductController@fetchSubCategoriesAnyLevels');
         Route::get('fetchsubcategories/{brand}' , 'ProductController@fetch_brand_sub_categories');
         Route::get('fetchproducts/{subCategory}' , 'ProductController@sub_category_products');
         Route::get('fetchcategoryproducts/{category}' , 'ProductController@fetch_category_products');
         Route::get('fetchproductsbystore/{store}' , 'ProductController@fetch_products_by_store');
         Route::get('fetchbrandproducts/{brand}' , 'ProductController@fetch_brand_products');
         Route::get('add-to-offers/{product_id}/{type}' , 'ProductController@addToOffers')->name('products.addToOffers');
         Route::get('removed-from-offers/{product_id}/{type}' , 'ProductController@removeFromOffers')->name('products.removedFromOffers');
         Route::get('offers' , 'ProductController@getOffers')->name('products.getOffers');
         Route::get('fetchcategoryoptions/{category}' , 'ProductController@fetch_category_options');
         Route::get('fetchsubcategorymultioptions/{category}' , 'ProductController@fetch_sub_category_multi_options');
         Route::get('validatebarcodeunique/{type}/{text}' , 'ProductController@validate_barcode_unique');
         Route::get('edit/{product}' , 'ProductController@EditGet')->name('products.edit');
         Route::post('edit/{product}' , 'ProductController@EditPost');
         Route::get('delete/productimage/{productImage}' , 'ProductController@delete_product_image')->name("productImage.delete");
         Route::get('details/{product}' , 'ProductController@details')->name('products.details');
         Route::get('delete/{product}' , 'ProductController@delete')->name('products.delete');
         Route::get('search' , 'ProductController@product_search')->name('products.search');
         Route::get('searched' , 'ProductController@product_search');
         Route::post('update/quantity/{product}' , 'ProductController@update_quantity')->name('update.quantity');
         Route::post('update/quantity/option/{option}' , 'ProductController@update_quantity_m_option')->name('option.update.quantity');
         Route::get('add' , 'ProductController@AddGet')->name('products.add');
         Route::post('add' , 'ProductController@AddPost');
         Route::get('hide/{product}/{status}' , 'ProductController@visibility_status_product')->name('products.visibility.status');
         Route::get('getbysubcat' , 'ProductController@get_product_by_sub_cat')->name('products.getbysubcat');
         Route::get('fetchsubcategorybycategory/{category}' , 'ProductController@fetch_sub_categories_by_category');
         Route::get('review/{product}/{status}' , 'ProductController@review_product')->name('products.review');
         Route::get('update-action/{id}/{action}' , 'ProductController@addRemoveOffer')->name('products.offer.update_action');
         

    });


    // Contact Us Messages Route
    Route::group([
        "prefix" => "contact_us"
    ] , function($router){
        Route::get('' , 'ContactUsController@show');
        Route::get('details/{id}' , 'ContactUsController@details');
        Route::get('delete/{id}' , 'ContactUsController@delete');
    });

    // Seller requests Messages Route
    Route::group([
        "prefix" => "seller_requests"
    ] , function($router){
        Route::get('/' , 'SellerRequestsController@show')->name('sellers.requests.show');
        Route::get('details/{seller}' , 'SellerRequestsController@details')->name('sellers.requests.details');
    });

    // Shops Route
    Route::group([
        "prefix" => "shops"
    ] , function($router){
        Route::get('/' , 'ShopController@index')->name('shops.index');
        Route::get('add' , 'ShopController@AddGet')->name('shops.add');
        Route::post('add' , 'ShopController@AddPost');
        Route::get('edit/{store}' , 'ShopController@EditGet')->name('shops.edit');
        Route::post('edit/{store}' , 'ShopController@EditPost');
        Route::get('details/{store}' , 'ShopController@details')->name('shops.details');
        Route::get('action/{store}/{status}' , 'ShopController@action')->name('shops.action');
    });

     // Gold Prices Route
     Route::group([
        "prefix" => "gold_prices"
    ], function($router){
         Route::get('add' , 'GoldPriceController@AddGet')->name('gold_prices.add');
         Route::post('add' , 'GoldPriceController@AddPost');
         Route::get('edit/{gold}' , 'GoldPriceController@EditGet')->name('gold_prices.edit');
         Route::post('edit/{gold}' , 'GoldPriceController@EditPost');
         Route::get('show' , 'GoldPriceController@show')->name('gold_prices.index');
         Route::get('delete/{gold}', 'GoldPriceController@delete')->name('gold_prices.delete');

    });

    // Offer Control Route
    Route::group([
        "prefix" => "offers_control"
    ], function($router){
         Route::get('add' , 'OffersControlController@AddGet')->name('offers_control.add');
         Route::post('add' , 'OffersControlController@AddPost');
         Route::post('sort' , 'OffersControlController@updateOffersSorting')->name('offers_control.sort');
         Route::get('edit/{section}' , 'OffersControlController@EditGet')->name('offers_control.edit');
         Route::post('edit/{section}' , 'OffersControlController@EditPost');
         Route::get('show' , 'OffersControlController@show')->name('offers_control.index');
         Route::get('details/{section}' , 'OffersControlController@details')->name('offers_control.details');
         Route::get('delete/{section}' , 'OffersControlController@delete')->name('offers_control.delete');
    });

    // stats Messages Route
    Route::group([
        "prefix" => "statistics"
    ] , function($router){
        Route::get('' , 'StatsController@show')->name("statistics.index");
    });

    // stats Messages Route
    Route::group([
        "prefix" => "sliders"
    ] , function($router){
        Route::get('' , 'SliderController@show')->name("sliders.show");
        Route::get('add' , 'SliderController@AddGet')->name("sliders.add");
        Route::post('add' , 'SliderController@AddPost');
        Route::get('edit/{slider}' , 'SliderController@EditGet')->name("sliders.edit");
        Route::post('edit/{slider}' , 'SliderController@EditPost')->name('sliders.update');
        Route::get('details/{slider}' , 'SliderController@details')->name("sliders.details");
        Route::get('web' , 'SliderController@getWebSlider')->name("sliders.web.show");
        Route::get('web/add' , 'SliderController@getAddWebSlider')->name("sliders.web.add");
        Route::get('web/edit/{slider}' , 'SliderController@getEditWebSlider')->name("sliders.web.edit");
        Route::post('web/edit/{slider}' , 'SliderController@updateWebSlider');
        Route::get('web/details/{slider}' , 'SliderController@getWebSliderDetails')->name('sliders.web.details');
        
        
        Route::post('web/add' , 'SliderController@postAddWebSlider')->name("sliders.post.web.add");

    });

    // Notifications Route
    Route::group([
        "prefix" => "notifications"
    ], function($router){
        Route::get('show' , 'NotificationController@show');
        Route::get('details/{id}' , 'NotificationController@details');
        Route::get('delete/{id}' , 'NotificationController@delete');
        Route::get('send' , 'NotificationController@getsend');
        Route::post('send' , 'NotificationController@send');
        Route::get('resend/{id}' , 'NotificationController@resend');
    });

    // Store Notifications Route
    Route::group([
        "prefix" => "store_notifications"
    ], function($router){
        Route::get('show' , 'StoreNotificationController@show');
        Route::get('details/{id}' , 'StoreNotificationController@details');
        Route::get('delete/{id}' , 'StoreNotificationController@delete');
        Route::get('send' , 'StoreNotificationController@getsend');
        Route::post('send' , 'StoreNotificationController@send');
        Route::get('resend/{id}' , 'StoreNotificationController@resend');
    });

});



// Web View Routes
Route::group([
    'prefix' => "webview"
] , function($router){
    Route::get('aboutapp/{lang}' , 'WebViewController@getabout');
    Route::get('termsandconditions/{lang}' , 'WebViewController@gettermsandconditions' )->name('terms');
    Route::get('returnpolicy/{lang}' , 'WebViewController@returnpolicy');
    Route::get('privacypolicy/{lang}' , 'WebViewController@privacypolicy')->name('privacy');
    
    Route::get('deliveryinformation/{lang}' , 'WebViewController@deliveryinformation');
    Route::get('pdfview/{order}','WebViewController@getStoreInvoice')->name('webview.store.invoice');
    Route::get('pdfview/main/{order}','WebViewController@getInvoice')->name('webview.invoice');
    Route::get('orders-report-pdfview/{id}','WebViewController@getSalesReport');
    Route::get('orders-report-pdfview-admin','WebViewController@getSalesReportAdmin')->name('webview.salesReport');
    Route::get('sales-report-pdfview/{id}','WebViewController@getSalesReport2');
    Route::get('sales-report-pdfview-admin','WebViewController@getSalesReport2Admin')->name('webview.salesReport2');
    Route::get('main-report-pdfview-admin','WebViewController@getMainOrdersReport')->name('webview.mainReport');
    Route::get('delivery-report-pdfview-admin','WebViewController@getDeliveryReport')->name('webview.deliveryReport');
    Route::get('test', 'WebViewController@test');

});

// Shop Dashboard Routes
Route::group([
    'middleware'=>'language',
    'prefix' => "store-panel",
    'namespace' => "Shop"
] , function($router){
    Route::get('' ,'HomeController@show');
    Route::get('login' ,  [ 'as' => 'shoplogin', 'uses' => 'ShopController@getlogin']);
    Route::post('login' , 'ShopController@postlogin');
    Route::get('logout' , 'ShopController@logout');
    Route::get('profile' , 'ShopController@profile');
    Route::get('personal-data' , 'ShopController@personal_data_get')->name('personal.data');
    Route::post('profile' , 'ShopController@updateprofile');
    Route::post('personal-data' , 'ShopController@personal_data_post');


    // Products Route
    Route::group([
        "prefix" => "products"
    ], function($router){
        Route::get('show' , 'ProductController@show')->name('products.store.index');
         Route::get('fetchbrands/{category}' , 'ProductController@fetch_category_brands');
         Route::get('fetchsubcategories/{brand}' , 'ProductController@fetch_brand_sub_categories');
         Route::get('fetchproducts/{subCategory}' , 'ProductController@sub_category_products');
         Route::get('fetchcategoryproducts/{category}' , 'ProductController@fetch_category_products');
         Route::get('fetchbrandproducts/{brand}' , 'ProductController@fetch_brand_products');
         Route::get('fetchcategoryoptions/{category}' , 'ProductController@fetch_category_options');
         Route::get('fetchsubcategorymultioptions/{category}' , 'ProductController@fetch_sub_category_multi_options');
         Route::get('validatebarcodeunique/{type}/{text}' , 'ProductController@validate_barcode_unique');
         Route::get('edit/{product}' , 'ProductController@EditGet')->name('products.store.edit');
         Route::post('edit/{product}' , 'ProductController@EditPost');
         Route::get('delete/productimage/{productImage}' , 'ProductController@delete_product_image')->name("productImage.store.delete");
         Route::get('details/{product}' , 'ProductController@details')->name('products.store.details');
         Route::get('delete/{product}' , 'ProductController@delete')->name('products.store.delete');
         Route::get('search' , 'ProductController@product_search')->name('products.store.search');
         Route::get('searched' , 'ProductController@product_search');
         Route::post('update/quantity/{product}' , 'ProductController@update_quantity')->name('update.store.quantity');
         Route::post('update/quantity/option/{option}' , 'ProductController@update_quantity_m_option')->name('option.store.update.quantity');
         Route::get('add' , 'ProductController@AddGet')->name('products.store.add');
         Route::post('add' , 'ProductController@AddPost');
         Route::get('hide/{product}/{status}' , 'ProductController@visibility_status_product')->name('products.store.visibility.status');
         Route::get('getbysubcat' , 'ProductController@get_product_by_sub_cat')->name('products.store.getbysubcat');
         Route::get('fetchsubcategorybycategory/{category}' , 'ProductController@fetch_sub_categories_by_category');
    });

    Route::group([
        'prefix' => "delivery_costs"
    ] , function($router){
        Route::get('show' , 'DeliveryCostController@show')->name('delivery_costs.store.index');
        Route::get('add' , 'DeliveryCostController@AddGet')->name('delivery_costs.store.add');
        Route::post('add' , 'DeliveryCostController@AddPost');
        Route::get('edit/{cost}' , 'DeliveryCostController@EditGet')->name('delivery_costs.store.edit');
        Route::post('edit/{cost}' , 'DeliveryCostController@EditPost');
    });

    // Orders Route
    Route::group([
        "prefix" => "orders"
    ], function($router){
         Route::get('show' , 'OrderController@show')->name('orders.store.index');
         Route::get('action/{order}/{status}' , 'OrderController@action_order')->name('orders.store.action');
         Route::get('action/items/{item}/{status}' , 'OrderController@order_items_actions')->name('orders.store.items.action');
         Route::get('details/{order}' , 'OrderController@details')->name('orders.store.details');
         Route::get('filter/{status}' , 'OrderController@filter_orders')->name('orders.store.filter');
         Route::get('fetchbyarea' , 'OrderController@fetch_orders_by_area')->name('orders.store.fetchbyarea');
         Route::get('fetchbydate' , 'OrderController@fetch_orders_date')->name('orders.store.fetchbydate');
         Route::get('fetchbypayment' , 'OrderController@fetch_order_payment_method')->name('orders.store.fetchbypayment');
         Route::get('fetchbysubnumber' , 'OrderController@fetch_order_by_sub_order_number')->name('orders.store.fetchbysubnumber');
         Route::get('invoice/{order}' , 'OrderController@getInvoice')->name('orders.store.invoice');
         Route::get('size/details/{item}' , 'OrderController@order_size_details')->name('orders.store.size.details');
    });

    // Home Multiple Options Route
    Route::group([
        "prefix" => "multi_options"
    ], function($router){
         Route::get('show' , 'MultiOptionController@show')->name('multi_options.store.index');
         Route::get('edit/{option}' , 'MultiOptionController@EditGet')->name('multi_options.store.edit');
         Route::post('edit/{option}' , 'MultiOptionController@EditPost');
    });

        // stats Messages Route
        Route::group([
            "prefix" => "statistics"
        ] , function($router){
            Route::get('' , 'StatsController@show')->name("statistics.store.index");
        });
});
