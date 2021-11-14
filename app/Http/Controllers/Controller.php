<?php

namespace App\Http\Controllers;

use App\Currency;
use App\Helpers\APIHelpers;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\SliderAd;
use App\Ad;
use App\Category;
use App\Product;
use App\Setting;
use App\SubCategory;
use App\SubTwoCategory;
use App\SubThreeCategory;
use App\SubFourCategory;
use App\SubFiveCategory;
use App\MetaTag;
use App\Order;
use App\OrderItem;
use App\Favorite;
use App\Cart;
use App\WebVisitor;
use App\Country;
use App\ProductVip;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected $settings;
    protected $ip;
    public $cats;
    protected $meta;

    public function __construct()
    {
        $lang = 'ar';
        // settings
        $this->settings = Setting::where('id', 1)->first();

        view()->share('settings', $this->settings);
    }

    // cart data
    public function getCartData(Request $request) {
        $request->lang = 'ar';
        // set / update ip address & address location
        $ip = $this->getIp($request);

        $currency_data['currency'] = Country::where('country_code', 'KWD')->first();

        if ($position = Location::get($ip)) {
            $webVisitor = WebVisitor::where('ip', $ip)->first();

            if ($webVisitor) {
                $webVisitor->update(['country_code' => $position->countryCode]);
            }else {
                $webVisitor = WebVisitor::create(['country_code' => $position->countryCode, 'ip' => $ip]);
            }
            $currency_data['currency'] = Country::where('country_code', $position->countryCode)->first();
            $this->webVisitor = $webVisitor;
            $this->currency = $currency_data['currency'];

            // reget settings data
            $this->settings = Setting::where('id', 1)->first();

            $all_currency_data = $this->gSliderAdetCurrency($webVisitor->country->currency_en);
            $this->all_currency_data = $all_currency_data;

            $this->cart = Cart::where('web_visitor_id', $webVisitor->id)->get();
            $this->totalAdded = '0.000';
            $this->totalKwd = '0.000';
            $cart = $this->cart;
            $data['cart'] = [];
            
            if (count($cart) > 0) {
                for ($i = 0; $i < count($cart); $i ++) {
                    $product = Product::where('id', $cart[$i]['product_id'])
                    ->select('id', 'title_' . $request->lang . ' as title', 'offer', 'final_price', 'price_before_offer', 'offer_percentage', 'category_id')
                    ->first()
                    ->makeHidden('images');
                    $product['count'] = $cart[$i]['count'];
                    $price = $product['final_price'];
                    $originalPrice = $product['final_price'] * $all_currency_data['value'] * $cart[$i]['count'];
                    $priceBOffer = $product['price_before_offer'] * $all_currency_data['value'] * $cart[$i]['count'];
                    if ($product->main_image) {
                        $product->main_image = $product->main_image->image;
                    }else {
                        if (count($product->images) > 0) {
                            $product->main_image = $product->images[0]->image;
                        }
                    }
                    $user = auth()->guard('user')->user();
                    if($user){
                        
                        if (!empty($user->vip_id)) {
                            $productVip = ProductVip::where('vip_id', $user->vip_id)->where('product_id', $product['id'])->first();
                            
                            if ($productVip) {
                                $priceOffer = $price * ($productVip->percentage / 100);
                                $originalPriceOffer = $originalPrice * ($productVip->percentage / 100);
                                $price = $price - $priceOffer;
                                $originalPrice = $originalPrice - $originalPriceOffer;
                                $priceBOffer = $product['final_price'] * $all_currency_data['value'];
                                $product['offer'] = 1;
                                $product['offer_percentage'] = $productVip->percentage;
                            }
                        }
                        
                        $favorite = Favorite::where('user_id' , $user->id)->where('product_id' , $product->id)->first();
                        if($favorite){
                            $product->favorite = true;
                        }else{
                            $product->favorite = false;
                        }
                    }else{
                        $product->favorite = false;
                    }
                    
                    $this->totalAdded = $this->totalAdded + (number_format((float)$price, 3, '.', '') * $all_currency_data['value'] * $cart[$i]['count']);
                    $this->totalAdded = number_format((float)$this->totalAdded, 3, '.', '');
                    $this->totalKwd = $this->totalKwd + $originalPrice;
                    
                    $product['final_price'] = number_format((float)$price, 3, '.', '') * $all_currency_data['value'];
                    $product['final_price'] = number_format((float)$product['final_price'], 3, '.', '');
                    
                    $product['price_before_offer'] = number_format((float)$priceBOffer, 3, '.', '');
                    $product['product_id'] = $product['id'];
                    $product['id'] = $cart[$i]['id'];
                    array_push($data['cart'], $product);
                }
            }
            $this->meta = MetaTag::find(1);
            $this->totalAdded = number_format((float)$this->totalAdded, 3, '.', '');
            view()->share('settings', $this->settings);
            view()->share('carts', $data['cart']);
            view()->share('totalAdded', $this->totalAdded);
            view()->share('currency', $this->currency);
            view()->share('meta', $this->meta);
            view()->share('all_currency_data', $this->all_currency_data);
        }
    }

    public function getIp($request) {
        if (env('APP_ENV') == 'local') {
            $this->ip = "62.114.215.35";
        }else {
            $this->ip = $request->ip();
        }

        return $this->ip;
    }


    public function upload($request)
    {
        $resizedVideo = cloudinary()->uploadVideo($request->getRealPath(), [
            'folder' => 'uploads',
            'transformation' => [
                'width' => 350,
                'height' => 200
            ]
        ]);

        return $resizedVideo;
    }

    // get currency
    public function gSliderAdetCurrency($currency)
    {
        $toCurr = trim(strtolower($currency));
        if ($toCurr == "usd") {
            $currency = ["value" => 1];
        } else {
            $currency = Currency::where('from', "usd")->where('to', $toCurr)->first();
        }

        if (isset($currency['id'])) {
            if (!$currency->updated_at->isToday()) {
                $result = APIHelpers::converCurruncy2("usd", $toCurr);
                if (isset($result['value'])) {
                    $currency->update(['value' => $result['value'], 'updated_at' => Carbon::now()]);
                    $currency = Currency::where('from', "usd")->where('to', $toCurr)->first();
                }
            }

        } else {
            if (!$currency) {
                $result = APIHelpers::converCurruncy2("usd", $toCurr);
                $currency = Currency::create(['value' => $result['value'], "from" => "usd", "to" => $toCurr]);
            }
        }

        return $currency;
    }

    // get category slider
    public function getCategorySlider()
    {
        $ids = SliderAd::where('slider_id', 6)->pluck('ad_id')->toArray();
        $ads = Ad::whereIn('id', $ids)->select('id', 'image', 'type', 'content')->get();

        return $ads;
    }

    // get categories
    public function getCats($request, $type)
    {
        $lang = $request->lang;
        $root_url = $request->root();
        
        $cats = Category::where('deleted', 0)->has('products', '>', 0)->select('id', 'title_' . $lang . ' as title', 'image')->get()->makeHidden('subCategories')
                ->map(function ($cat) use ($root_url, $request, $type) {
                    if ($type == 'api') {
                        $url['sub_cat'] = $root_url . '/api/categories/'. $cat->id . '/sub-categories/' . $request->lang .'/v1';
                        $url['product'] = $root_url . '/api/products/show/en/v1' . '?category_id=' . $cat->id;
                    } else {
                        $url['sub_cat'] = $root_url . '/sub-categories' . '?category_id=' . $cat->id;
                        $url['product'] = $root_url . '/products_ar' . '?category_id=' . $cat->id;
                    }
                    $cat->next_level = false;
                    $cat->url = $url['product'];
                    if ($cat->subCategories && count($cat->subCategories) > 0) {
                        $hasProducts = false;
                        for ($i = 0; $i < count($cat->subCategories); $i++) {
                            if (count($cat->subCategories[$i]->products) > 0) {
                                $hasProducts = true;
                            }
                        }

                        if ($hasProducts) {
                            $cat->next_level = true;
                            $cat->url = $url['sub_cat'];
                        }

                    }

                    return $cat;
                });


        return $cats;
    }

    // get offers
    public function getOffersTypes($request, $not=0)
    {
        if ($request->type == 'recent') {
            $offers = Product::where('deleted', 0)->where('hidden', 0)->where('recent_offers', 1);
            if ($not != 0) {
                $offers = $offers->where('id', '!=', $not);
            }
            $offers = $offers->select('id', 'title_' . $request->lang . ' as title', 'offer', 'final_price', 'price_before_offer', 'offer_percentage', 'brief_' . $request->lang . ' as brief')->inRandomOrder()->limit(5)->get()->makeHidden('images');
        } elseif ($request->type == 'all') {
            $offers = Product::where('deleted', 0)->where('hidden', 0)->select('id', 'title_' . $request->lang . ' as title', 'offer', 'final_price', 'price_before_offer', 'offer_percentage', 'brief_' . $request->lang . ' as brief')->paginate(12);
            $offers->makeHidden('images');
        } else {
            $offers = Product::where('deleted', 0)->where('hidden', 0)->where('choose_for_you', 1)->select('id', 'title_' . $request->lang . ' as title', 'offer', 'final_price', 'price_before_offer', 'offer_percentage', 'brief_' . $request->lang . ' as brief')->inRandomOrder()->limit(5)->get()->makeHidden('images');
        }

        return $offers;
    }

    // get sliders
    public function getSlidersTypes($request)
    {
        $ids = SliderAd::where('slider_id', 5)->pluck('ad_id')->toArray();
        if ($request->type == 'top') {
            $ids = SliderAd::where('slider_id', 3)->pluck('ad_id')->toArray();
        }

        $ads = Ad::whereIn('id', $ids)->select('id', 'image', 'type', 'content')->get();

        return $ads;
    }

    // get sub categories
    public function getSubCategoriesOne($request, $type) {
        $lang = $request->lang;
        $root_url = $request->root();
        
        $data = SubCategory::where('deleted' , 0)->where('category_id' , $request->category_id)->has('products', '>', 0)->select('id' , 'image' , 'title_' . $lang . ' as title', 'category_id')->get()
        ->makeHidden('subCategories')
        ->map(function($sCat) use ($type, $root_url, $lang){
            $sCat->next_level = false;
            if ($type == 'api') {
                $url['sub_cat'] = $root_url . '/api/categories/' . $sCat->id . '/sub-categories-two/'. $lang . '/v1';
                $url['product'] = $root_url . '/api/products/show/' . $lang . '/v1'. '?category_id=' . $sCat->category_id . '&sub_category_id=' . $sCat->id;
            }else {
                $url['sub_cat'] = $root_url . '/sub-categories' . '?category_id=' . $sCat->category_id . '&sub_category_id=' . $sCat->id;
                $url['product'] = $root_url . '/products_ar' . '?category_id=' . $sCat->category_id . '&sub_category_id=' . $sCat->id;
            }
            $sCat->url = $url['product'] ;
            if (count($sCat->subCategories) > 0) {
                $hasProducts = false;
                for ($i = 0; $i < count($sCat->subCategories); $i ++) {
                    if (count($sCat->subCategories[$i]->products) > 0) {
                        $hasProducts = true;
                    }
                }

                if ($hasProducts) {
                    $sCat->next_level = true;
                    $sCat->url = $url['sub_cat'];
                }
                
            }

            return $sCat;
        });

        return $data;
    }

    // get sub categories 2
    public function getSubCategoriesTwo($request, $type) {
        $lang = $request->lang;
        $root_url = $request->root();
        
        $data = SubTwoCategory::where('deleted' , 0)->where('sub_category_id' , $request->sub_category_id)->has('products', '>', 0)->select('id' , 'image' , 'title_' . $lang . ' as title', 'sub_category_id')->get()
        ->makeHidden(['subCategories', 'category'])
        ->map(function($sCat) use ($type, $root_url, $lang){
            $sCat->next_level = false;
            if ($type == 'api') {
                $url['sub_cat'] = $root_url . '/api/categories/' . $sCat->id . '/sub-categories-three/en/v1';
                $url['product'] = $root_url . '/api/products/show/' . $lang . '/v1?category_id=' . $sCat->category->category_id . '&sub_category_id=' . $sCat->sub_category_id . '&sub_category_two_id=' . $sCat->id;
            }else {
                $url['sub_cat'] = $root_url . '/sub-categories' . '?category_id=' . $sCat->category->category_id . '&sub_category_id=' . $sCat->id . '&sub_category_two_id=' . $sCat->sub_category_id;
                $url['product'] = $root_url . '/products_ar' . '?category_id=' . $sCat->category->category_id . '&sub_category_id=' . $sCat->sub_category_id . '&sub_category_two_id=' . $sCat->id;
            }
            $sCat->url = $url['product'] ;
            if (count($sCat->subCategories) > 0) {
                $hasProducts = false;
                for ($i = 0; $i < count($sCat->subCategories); $i ++) {
                    if (count($sCat->subCategories[$i]->products) > 0) {
                        $hasProducts = true;
                    }
                }

                if ($hasProducts) {
                    $sCat->next_level = true;
                    $sCat->url = $url['sub_cat'];
                }
                
            }

            return $sCat;
        });

        return $data;
    }

    // get sub categories 3
    public function getSubCategoriesThree($request, $type) {
        $lang = $request->lang;
        $root_url = $request->root();
        
        $data = SubThreeCategory::where('deleted' , 0)->where('sub_category_id' , $request->sub_category_id)->has('products', '>', 0)->select('id' , 'image' , 'title_' . $lang . ' as title', 'sub_category_id')->get()
        ->makeHidden(['subCategories', 'category'])
        ->map(function($sCat) use ($type, $root_url, $lang){
            $sCat->next_level = false;
            if ($type == 'api') {
                $url['sub_cat'] = $root_url . '/api/categories/' . $sCat->id . '/sub-categories-four/en/v1';
                $url['product'] = $root_url . '/api/products/show/' . $lang . '/v1?category_id=' . $sCat->category->category->category_id . '&sub_category_id=' . $sCat->category->category->id . '&sub_category_two_id=' . $sCat->sub_category_id . '&sub_category_three_id=' . $sCat->id;
            }else {
                $url['sub_cat'] = $root_url . '/sub-categories' . '?category_id=' . $sCat->category->category->category_id . '&sub_category_id=' . $sCat->id . '&sub_category_two_id=' . $sCat->sub_category_id . '&sub_category_three_id=' . $sCat->category->category->id;
                $url['product'] = $root_url . '/products_ar' . '?category_id=' . $sCat->category->category->category_id . '&sub_category_id=' . $sCat->category->category->id . '&sub_category_two_id=' . $sCat->sub_category_id . '&sub_category_three_id=' . $sCat->id;
            }
            $sCat->url = $url['product'] ;
            if (count($sCat->subCategories) > 0) {
                $hasProducts = false;
                for ($i = 0; $i < count($sCat->subCategories); $i ++) {
                    if (count($sCat->subCategories[$i]->products) > 0) {
                        $hasProducts = true;
                    }
                }

                if ($hasProducts) {
                    $sCat->next_level = true;
                    $sCat->url = $url['sub_cat'];
                }
                
            }

            return $sCat;
        });

        return $data;
    }

    // get sub categories 4
    public function getSubCategoriesFour($request, $type) {
        $lang = $request->lang;
        $root_url = $request->root();
        
        $data = SubFourCategory::where('deleted' , 0)->where('sub_category_id' , $request->sub_category_id)->has('products', '>', 0)->select('id' , 'image' , 'title_' . $lang . ' as title', 'sub_category_id')->get()
        ->makeHidden(['subCategories', 'category'])
        ->map(function($sCat) use ($type, $root_url, $lang){
            $sCat->next_level = false;
            if ($type == 'api') {
                $url['sub_cat'] = $root_url . '/api/categories/' . $sCat->id . '/sub-categories-five/en/v1';
                $url['product'] = $root_url . '/api/products/show/' . $lang . '/v1?category_id=' . $sCat->category->category->category->category_id 
                . '&sub_category_id=' . $sCat->category->category->category->id 
                . '&sub_category_two_id=' . $sCat->category->sub_category_id 
                . '&sub_category_three_id=' . $sCat->sub_category_id 
                . '&sub_category_four_id=' . $sCat->id;
            }else {
                $url['sub_cat'] = $root_url . '/sub-categories' . '?category_id=' . $sCat->category->category->category->category_id 
                . '&sub_category_id=' . $sCat->id 
                . '&sub_category_two_id=' . $sCat->category->sub_category_id 
                . '&sub_category_three_id=' . $sCat->sub_category_id 
                . '&sub_category_four_id=' . $sCat->category->category->category->id;
                $url['product'] = $root_url . '/products_ar' . '?category_id=' . $sCat->category->category->category->category_id 
                . '&sub_category_id=' . $sCat->category->category->category->id 
                . '&sub_category_two_id=' . $sCat->category->sub_category_id 
                . '&sub_category_three_id=' . $sCat->sub_category_id 
                . '&sub_category_four_id=' . $sCat->id;
            }
            $sCat->url = $url['product'] ;
            if (count($sCat->subCategories) > 0) {
                $hasProducts = false;
                for ($i = 0; $i < count($sCat->subCategories); $i ++) {
                    if (count($sCat->subCategories[$i]->products) > 0) {
                        $hasProducts = true;
                    }
                }

                if ($hasProducts) {
                    $sCat->next_level = true;
                    $sCat->url = $url['sub_cat'];
                }
                
            }

            return $sCat;
        });

        return $data;
    }

    // get sub categories 5
    public function getSubCategoriesFive($request, $type) {
        $lang = $request->lang;
        $root_url = $request->root();
        
        $data = SubFiveCategory::where('deleted' , 0)->where('sub_category_id' , $request->sub_category_id)->has('products', '>', 0)->select('id' , 'image' , 'title_' . $lang . ' as title', 'sub_category_id')->get()
        ->makeHidden(['subCategories', 'category'])
        ->map(function($sCat) use ($type, $root_url, $lang){
            $sCat->next_level = false;
            if ($type == 'api') {
                $url['sub_cat'] = '';
                $url['product'] = $root_url . '/api/products/show/' . $lang . '/v1?category_id=' . $sCat->category->category->category->category->category_id 
                . '&sub_category_id=' . $sCat->category->category->category->category->id 
                . '&sub_category_two_id=' . $sCat->category->category->sub_category_id 
                . '&sub_category_three_id=' . $sCat->category->sub_category_id 
                . '&sub_category_four_id=' . $sCat->sub_category_id
                . '&sub_category_five_id=' . $sCat->id;
            }else {
                $url['sub_cat'] = '';
                $url['product'] = $root_url . '/products_ar' . '?category_id=' . $sCat->category->category->category->category->category_id 
                . '&sub_category_id=' . $sCat->category->category->category->category->id 
                . '&sub_category_two_id=' . $sCat->category->category->sub_category_id 
                . '&sub_category_three_id=' . $sCat->category->sub_category_id 
                . '&sub_category_four_id=' . $sCat->sub_category_id
                . '&sub_category_five_id=' . $sCat->id;
            }
            $sCat->url = $url['product'] ;
            

            return $sCat;
        });

        return $data;
    }

    // get user orders
    public function getMyOrders($user_id, $request) {
        $orders = Order::where('user_id' , $user_id)->select('id' , 'order_number' , 'created_at')->orderBy('id' , 'desc')->get()->makeHidden('created_at')
        ->map(function ($row) use ($request) {
            if ($request->lang == 'ar') {
                $row->date = $row->created_at->translatedFormat("a g:i - j F Y");
            }else {
                $row->date = $row->created_at->format("j F Y - g:i a");
            }
            
            return $row;
        });

        return $orders;
    }

    // get order details
    public function getOrderDetail($order_id, $request) {
        $order = Order::select('id', 'order_number', 'total_price', 'country_code', 'created_at')->where('id', $order_id)->first()->makeHidden(['country_code', 'products', 'created_at']);
        $order->total_price = number_format((float)$order->total_price, 3, '.', '');
        if ($request->lang == 'ar') {
            $order->date = $order->created_at->translatedFormat("a g:i - j F Y");
        }else {
            $order->date = $order->created_at->format("j F Y - g:i a");
        }
        $order['items_count'] = $order->products->sum('count');
        $order['items'] = OrderItem::where('order_id', $request->id)->select('id', 'order_id', 'product_id', 'final_price', 'discount', 'price_before_offer', 'count')->orderBy('id', 'desc')->get()
        ->makeHidden(['product', 'order_id'])
        ->map(function($row) use ($request) {
            $productName = $row->product->title_en;
            $categoryName = $row->product->category->title_en;
            if ($request->lang == 'ar') {
                $productName = $row->product->title_ar;
                $categoryName = $row->product->category->title_ar;
            }
            $row->title = $productName;
            $row->category_name = $categoryName;
            $image = "";
            if ($row->product->mainImage) {
                $image = $row->product->mainImage->image;
            }
            $row->image = $image;

            return $row;
        });

        return $order;
    }
}
