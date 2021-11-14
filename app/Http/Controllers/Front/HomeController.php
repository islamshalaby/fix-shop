<?php
namespace App\Http\Controllers\Front;

use App\Ad;
use App\ContactUs;
use App\Country;
use App\Http\Controllers\Controller;
use App\WebVisitor;
use Stevebauman\Location\Facades\Location;
use Illuminate\Http\Request;
use App\ProductVip;
use App\Favorite;
use App\Product;
use App\SlideradText;
use App\Setting;
use App\OrderSerial;
Use App\OrderItem;
use App\Cart;
use App\Order;
use App\Currency;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Mail;
use App\Helpers\APIHelpers;

class HomeController extends Controller{
    protected $webVisitor;
    protected $currency;
    protected $cart;
    protected $totalAdded;
    protected $totalKwd;
    protected $all_currency_data;

    
    // get home
    public function index(Request $request){
        Parent::getCartData($request);
        if ($this->webVisitor) {
            $webVisitor = $this->webVisitor;
            $data['currency'] = $this->currency;
            $toCurr = $webVisitor->country->currency_en;
            $currency = $this->gSliderAdetCurrency($toCurr);
            $request->type = 'top';
            $data['top_sliders'] = SlideradText::has('ad', '>', 0)->inRandomOrder()->get();
            
            $data['ads'] = Ad::where('place', 4)->select('id', 'image', 'type', 'content')->inRandomOrder()->limit(1)->get();
            $request->lang = 'ar';
            $data['categories'] = $this->getCats($request, 'web');
            $request->type = 'recent';
            $data['recent_offers'] = $this->getOffersTypes($request);
            for ($i = 0; $i < count($data['recent_offers']); $i ++) {
                if ($data['recent_offers'][$i]->main_image) {
                    $data['recent_offers'][$i]->main_image = $data[$i]->main_image->image;
                }else {
                    if (count($data['recent_offers'][$i]->images) > 0) {
                        $data['recent_offers'][$i]->main_image = $data['recent_offers'][$i]->images[0]->image;
                    }
                }

                $price = $data['recent_offers'][$i]['final_price'] * $currency['value'];
                $priceBOffer = $data['recent_offers'][$i]['price_before_offer'] * $currency['value'];

                if(auth()->guard('user')->user()){

                    if (!empty(auth()->guard('user')->user()->vip_id)) {

                        $productVip = ProductVip::where('vip_id', auth()->guard('user')->user()->vip_id)->where('product_id', $data['recent_offers'][$i]['id'])->first();
                        if ($productVip) {
                            $priceOffer = $price * ($productVip->percentage / 100);
                            $price = $price - $priceOffer;
                            $priceBOfferOffer = $priceBOffer * ($productVip->percentage / 100);
                            $priceBOffer = $priceBOffer - $priceBOfferOffer;
                            $data['recent_offers'][$i]['offer'] = 1;
                            $data['recent_offers'][$i]['offer_percentage'] = $productVip->percentage;
                        }

                    }
                    $user_id = auth()->guard('user')->user()->id;

                    $prevfavorite = Favorite::where('product_id' , $data['recent_offers'][$i]['id'])->where('user_id' , $user_id)->first();
                    if($prevfavorite){
                        $data['recent_offers'][$i]['favorite'] = true;
                    }else{
                        $data['recent_offers'][$i]['favorite'] = false;
                    }

                }else{
                    $data['recent_offers'][$i]['favorite'] = false;
                }
                $data['recent_offers'][$i]['final_price'] = number_format((float)$price, 3, '.', '');
                $data['recent_offers'][$i]['price_before_offer'] = number_format((float)$priceBOffer, 3, '.', '');
            }
            $data['recent_product'] = Product::where('deleted', 0)->where('hidden', 0)->select('id', 'title_' . $request->lang . ' as title', 'offer', 'final_price', 'price_before_offer', 'offer_percentage', 'created_at', 'brief_' . $request->lang . ' as brief')->orderBy('created_at', 'desc')->limit(5)->get()->makeHidden('images');
            for ($i = 0; $i < count($data['recent_product']); $i ++) {
                if ($data['recent_product'][$i]->main_image) {
                    $data['recent_product'][$i]->main_image = $data['recent_product'][$i]->main_image->image;
                }else {
                    if (count($data['recent_product'][$i]->images) > 0) {
                        $data['recent_product'][$i]->main_image = $data['recent_product'][$i]->images[0]->image;
                    }
                }

                $price = $data['recent_product'][$i]['final_price'] * $currency['value'];
                $priceBOffer = $data['recent_product'][$i]['price_before_offer'] * $currency['value'];

                if(auth()->guard('user')->user()){

                    if (!empty(auth()->guard('user')->user()->vip_id)) {

                        $productVip = ProductVip::where('vip_id', auth()->guard('user')->user()->vip_id)->where('product_id', $data['recent_product'][$i]['id'])->first();
                        if ($productVip) {
                            $priceOffer = $price * ($productVip->percentage / 100);
                            $price = $price - $priceOffer;
                            $priceBOfferOffer = $priceBOffer * ($productVip->percentage / 100);
                            $priceBOffer = $priceBOffer - $priceBOfferOffer;
                            $data['recent_product'][$i]['offer'] = 1;
                            $data['recent_product'][$i]['offer_percentage'] = $productVip->percentage;
                        }

                    }
                    $user_id = auth()->guard('user')->user()->id;

                    $prevfavorite = Favorite::where('product_id' , $data['recent_product'][$i]['id'])->where('user_id' , $user_id)->first();
                    if($prevfavorite){
                        $data['recent_product'][$i]['favorite'] = true;
                    }else{
                        $data['recent_product'][$i]['favorite'] = false;
                    }

                }else{
                    $data['recent_product'][$i]['favorite'] = false;
                }
                $data['recent_product'][$i]['final_price'] = number_format((float)$price, 3, '.', '');
                $data['recent_product'][$i]['price_before_offer'] = number_format((float)$priceBOffer, 3, '.', '');
            }
            return view('front.index-ar', compact('data'));
        }else {
            
            return redirect()->route('front.failed');
        }
    }

    public function about_us(){
        return view('front.about');
    }
    public function products(){
        return view('front.products-list');
    }
    public function categories(){
        return view('front.categories');
    }
    public function my_requests(){
        return view('front.my-requests');
    }

    public function order_details(){
        return view('front.order-details');
    }
    public function change_password(){
        return view('front.change-password');
    }
    public function forgot_password(){
        return view('front.forgot-password');
    }

    public function contact(){
        return view('front.contact');
    }
    public function favorite(){
        return view('front.favorite');
    }

    public function login(){
        return view('front.login');
    }
    public function register(){
        return view('front.register');
    }
    public function Payment(){
        return view('front.Payment');
    }

    public function product_details(){
        return view('front.product-details');
    }

    public function cart(){
        return view('front.cart');
    }

    public function products_list(){
        return view('front.products_list');
    }


    // get home
    public function index_ar(){
        return view('front.index-ar');
    }

    public function about_us_ar(Request $request){
        Parent::getCartData($request);
        $data = Setting::find(1);
        return view('front.about-ar',compact('data'));
    }

    // get products
    public function products_ar(Request $request){
        Parent::getCartData($request);
        if ($this->webVisitor) {
            $webVisitor = $this->webVisitor;
            $currency_data['currency'] = $this->currency;
            $web_image =  ad::where('place',3)->inRandomOrder()->limit(1)->get();
            $toCurr = $webVisitor->country->currency_en;
            $currency = $this->gSliderAdetCurrency($toCurr);
            $request->lang = 'ar';
            $data = Product::where('deleted', 0)->where('hidden', 0);
            if ($request->category_id) {
                $data = $data->where('category_id', $request->category_id);
            }
            if ($request->sub_category_id) {
                $data = $data->where('sub_category_id', $request->sub_category_id);
            }
            if ($request->sub_category_two_id) {
                $data = $data->where('sub_category_two_id', $request->sub_category_two_id);
            }
            if ($request->sub_category_three_id) {
                $data = $data->where('sub_category_three_id', $request->sub_category_three_id);
            }
            if ($request->sub_category_four_id) {
                $data = $data->where('sub_category_four_id', $request->sub_category_four_id);
            }
            if ($request->sub_category_five_id) {
                $data = $data->where('sub_category_five_id', $request->sub_category_five_id);
            }
            
            $data = $data->select('id', 'title_ar as title', 'offer', 'final_price', 'price_before_offer', 'offer_percentage', 'brief_' . $request->lang . ' as brief')->orderBy('id','desc')->paginate(12);
            $data->makeHidden('images');
            if (count($data) > 0) {
                for ($i = 0; $i < count($data); $i ++) {
                    if ($data[$i]->main_image) {
                        $data[$i]->main_image = $data[$i]->main_image->image;
                    }else {
                        if (count($data[$i]->images) > 0) {
                            $data[$i]->main_image = $data[$i]->images[0]->image;
                        }
                    }
                    $price = $data[$i]['final_price'] * $currency['value'];
                    $priceBOffer = $data[$i]['price_before_offer'] * $currency['value'];

                    $user = auth()->guard('user')->user();
                    if($user){
                        if (!empty($user->vip_id)) {
                            $productVip = ProductVip::where('vip_id', $user->vip_id)->where('product_id', $data[$i]['id'])->first();
                            if ($productVip) {
                                $priceOffer = $price * ($productVip->percentage / 100);
                                $price = $price - $priceOffer;
                                $priceBOfferOffer = $priceBOffer * ($productVip->percentage / 100);
                                $priceBOffer = $priceBOffer - $priceBOfferOffer;
                                $data[$i]['offer'] = 1;
                                $data[$i]['offer_percentage'] = $productVip->percentage;
                            }
                        }
                        $favorite = Favorite::where('user_id' , $user->id)->where('product_id' , $data[$i]['id'])->first();
                        if($favorite){
                            $data[$i]['favorite'] = true;
                        }else{
                            $data[$i]['favorite'] = false;
                        }
                    }else{
                        $data[$i]['favorite'] = false;
                    }
                    $data[$i]['final_price'] = number_format((float)$price, 3, '.', '');
                    $data[$i]['price_before_offer'] = number_format((float)$priceBOffer, 3, '.', '');
                }
            }

            return view('front.products-list-ar',compact('data','currency_data','web_image'));
        }else {
            
            return redirect()->route('front.failed');
        }
    }

    // get offers
    public function offers(Request $request){
        Parent::getCartData($request);
        $request->lang = 'ar';
        $request->type = 'all';
        if (env('APP_ENV') == 'local') {
            $ip = "62.114.215.35";
        }else {
            $ip = $request->ip();
        }

        $web_image =  ad::where('place',3)->inRandomOrder()->limit(1)->get();

        $currency_data['currency'] = Country::where('country_code', 'KWD')->first();
        if ($position = Location::get($ip)) {
            $webVisitor = WebVisitor::where('ip', $ip)->first();
            if ($webVisitor) {
                $webVisitor->update(['country_code' => $position->countryCode]);
            }else {
                $webVisitor = WebVisitor::create(['country_code' => $position->countryCode, 'ip' => $ip]);
            }
            $currency_data['currency'] = Country::where('country_code', $position->countryCode)->first();
        }

        $toCurr = $webVisitor->country->currency_en;
        $currency = $this->gSliderAdetCurrency($toCurr);
        $data['recent_offers'] = $this->getOffersTypes($request);
        for ($i = 0; $i < count($data['recent_offers']); $i ++) {
            if ($data['recent_offers'][$i]->main_image) {
                $data['recent_offers'][$i]->main_image = $data[$i]->main_image->image;
            }else {
                if (count($data['recent_offers'][$i]->images) > 0) {
                    $data['recent_offers'][$i]->main_image = $data['recent_offers'][$i]->images[0]->image;
                }
            }

            $price = $data['recent_offers'][$i]['final_price'] * $currency['value'];
            $priceBOffer = $data['recent_offers'][$i]['price_before_offer'] * $currency['value'];

            if(auth()->guard('user')->user()){

                if (!empty(auth()->guard('user')->user()->vip_id)) {

                    $productVip = ProductVip::where('vip_id', auth()->guard('user')->user()->vip_id)->where('product_id', $data['recent_offers'][$i]['id'])->first();
                    if ($productVip) {
                        $priceOffer = $price * ($productVip->percentage / 100);
                        $price = $price - $priceOffer;
                        $priceBOfferOffer = $priceBOffer * ($productVip->percentage / 100);
                        $priceBOffer = $priceBOffer - $priceBOfferOffer;
                        $data['recent_offers'][$i]['offer'] = 1;
                        $data['recent_offers'][$i]['offer_percentage'] = $productVip->percentage;
                    }
                }
                $user_id = auth()->guard('user')->user()->id;

                $prevfavorite = Favorite::where('product_id' , $data['recent_offers'][$i]['id'])->where('user_id' , $user_id)->first();
                if($prevfavorite){
                    $data['recent_offers'][$i]['favorite'] = true;
                }else{
                    $data['recent_offers'][$i]['favorite'] = false;
                }

            }else{
                $data['recent_offers'][$i]['favorite'] = false;
            }
            $data['recent_offers'][$i]['final_price'] = number_format((float)$price, 3, '.', '');
            $data['recent_offers'][$i]['price_before_offer'] = number_format((float)$priceBOffer, 3, '.', '');
        }

        return view('front.offers-list-ar',compact('data','currency_data','web_image'));
    }
    public function categories_ar(){
        return view('front.categories-ar');
    }
    public function my_requests_ar(){
        return view('front.my-requests-ar');
    }

    public function order_details_ar(){
        return view('front.order-details-ar');
    }
    public function change_password_ar(){
        return view('front.change-password-ar');
    }
    public function forgot_password_ar(){
        return view('front.forgot-password-ar');
    }

    public function contact_ar(Request $request){
        Parent::getCartData($request);
        $data = Setting::find(1);
        return view('front.contact-ar',compact('data'));
    }

    public function store_contact_us(Request $request){
        $this->validate(\request(),
        [
            'phone' => 'required',
            'message' => 'required'
        ]);
        $input = $request->all();

        ContactUs::create($input);

        Alert::success('تم الارسال بنجاح', 'يرجى انتظار رد الادارة');
        
        return redirect()->back();
    }

    public function favorite_ar(Request $request){
        Parent::getCartData($request);
        return view('front.favorite-ar');
    }

    public function login_ar(Request $request){
        Parent::getCartData($request);
        return view('front.login-ar');
    }
    public function register_ar(Request $request){
        Parent::getCartData($request);
        return view('front.register-ar');
    }
    public function Payment_ar(){
        return view('front.Payment-ar');
    }

    public function product_details_ar(Request $request,$id){
        Parent::getCartData($request);
        if ($this->webVisitor) {
            $webVisitor = $this->webVisitor;
            $currency_data['currency'] = $this->currency;
            $request->lang = 'ar';
            $toCurr = $webVisitor->country->currency_en;
            $currency = $this->gSliderAdetCurrency($toCurr);

            $data = Product::where('id', $id)->select('id', 'title_ar as title', 'offer', 'description_ar as description', 'final_price', 'price_before_offer', 'offer_percentage', 'category_id')->first()->makeHidden('category');

            if ($request->lang == 'en') {
                $data['category_name'] = $data->category->title_en;
            }else {
                $data['category_name'] = $data->category->title_ar;
            }
            $price = $data['final_price'] * $currency['value'];
            $priceBOffer = $data['price_before_offer'] * $currency['value'];
            if(auth()->guard('user')->user()){
                $user_id = auth()->guard('user')->user()->id;
                if (!empty(auth()->guard('user')->user()->vip_id)) {

                    $productVip = ProductVip::where('vip_id', auth()->guard('user')->user()->vip_id)->where('product_id', $data['id'])->first();
                    if ($productVip) {
                        $priceOffer = $price * ($productVip->percentage / 100);
                        $price = $price - $priceOffer;
                        $priceBOfferOffer = $priceBOffer * ($productVip->percentage / 100);
                        $priceBOffer = $priceBOffer - $priceBOfferOffer;
                        $data['offer'] = 1;
                        $data['offer_percentage'] = $productVip->percentage;
                    }

                }
                $prevfavorite = Favorite::where('product_id' , $data['id'])->where('user_id' , $user_id)->first();
                if($prevfavorite){
                    $data['favorite'] = true;
                }else{
                    $data['favorite'] = false;
                }

            }else{
                $data['favorite'] = false;
            }
            $data['final_price'] = number_format((float)$price, 3, '.', '');
            $data['price_before_offer'] = number_format((float)$priceBOffer, 3, '.', '');
            for ($k = 0; $k < count($data->images); $k ++) {
                $data['images'][$k] = $data->images[$k]['image'];
            }

            $request->type = 'recent';
            $recent_offers = $this->getOffersTypes($request, $data['id']);

            for ($i = 0; $i < count($recent_offers); $i ++) {
                if ($recent_offers[$i]->main_image) {
                    $recent_offers[$i]->main_image = $data[$i]->main_image->image;
                }else {
                    if (count($recent_offers[$i]->images) > 0) {
                        $recent_offers[$i]->main_image = $recent_offers[$i]->images[0]->image;
                    }
                }

                if(auth()->guard('user')->user()){

                    if (!empty(auth()->guard('user')->user()->vip_id)) {

                        $productVip = ProductVip::where('vip_id', auth()->guard('user')->user()->vip_id)->where('product_id', $data['id'])->first();
                        if ($productVip) {
                            $priceOffer = $price * ($productVip->percentage / 100);
                            $price = $price - $priceOffer;
                            $priceBOfferOffer = $priceBOffer * ($productVip->percentage / 100);
                            $priceBOffer = $priceBOffer - $priceBOfferOffer;
                            $recent_offers[$i]['offer'] = 1;
                            $recent_offers[$i]['offer_percentage'] = $productVip->percentage;
                        }
                    }
                    $user_id = auth()->guard('user')->user()->id;

                    $prevfavorite = Favorite::where('product_id' , $recent_offers[$i]['id'])->where('user_id' , $user_id)->first();
                    if($prevfavorite){
                        $recent_offers[$i]['favorite'] = true;
                    }else{
                        $recent_offers[$i]['favorite'] = false;
                    }

                }else{
                    $recent_offers[$i]['favorite'] = false;
                }

                $price = $recent_offers[$i]['final_price'] * $currency['value'];
                $priceBOffer = $recent_offers[$i]['price_before_offer'] * $currency['value'];

                $recent_offers[$i]['final_price'] = number_format((float)$price, 3, '.', '');
                $recent_offers[$i]['price_before_offer'] = number_format((float)$priceBOffer, 3, '.', '');
            }

            return view('front.product-details-ar',compact('data','currency_data' ,'recent_offers'));
        }else {
            
            return redirect()->route('front.failed');
        }
    }

    public function cart_ar(){
        return view('front.cart-ar');
    }

    public function products_list_ar(){
        return view('front.products_list-ar');
    }


    // like product
    public function likeProduct(Request $request) {
        $user_id = auth()->guard('user')->user()->id;
        $prevfavorite = Favorite::where('product_id' , $request->product_id)->where('user_id' , $user_id)->first();

        if ($prevfavorite) {
            $prevfavorite->delete();

            toast('تم حذف المنتج من المفضلة', 'warning');
            return redirect()->back();
        }else {
            Favorite::create([
                'product_id' => $request->product_id,
                'user_id' => $user_id
            ]);

            toast('تم إضافة المنتج للمفضلة', 'success');
            return redirect()->back();
        }
    }

    // get favorites
    public function getFavorites(Request $request) {
        Parent::getCartData($request);
        if ($this->webVisitor) {
            $webVisitor = $this->webVisitor;
            $currency_data['currency'] = $this->currency;
            $toCurr = $webVisitor->country->currency_en;
            $currency = $this->gSliderAdetCurrency($toCurr);
            $web_image =  ad::where('place',3)->inRandomOrder()->limit(1)->get();
            $favorites = Favorite::where('user_id', auth()->guard('user')->user()->id)->pluck('product_id')->toArray();
            $request->lang = 'ar';
            $data = Product::whereIn('id', $favorites)->where('deleted', 0)->where('hidden', 0)->select('id', 'title_ar as title', 'offer', 'final_price', 'price_before_offer', 'offer_percentage', 'brief_' . $request->lang . ' as brief')->orderBy('id','desc')->paginate(12);
            $data->makeHidden('images');
            if (count($data) > 0) {
                for ($i = 0; $i < count($data); $i ++) {
                    if ($data[$i]->main_image) {
                        $data[$i]->main_image = $data[$i]->main_image->image;
                    }else {
                        if (count($data[$i]->images) > 0) {
                            $data[$i]->main_image = $data[$i]->images[0]->image;
                        }
                    }
                    $price = $data[$i]['final_price'] * $currency['value'];
                    $priceBOffer = $data[$i]['price_before_offer'] * $currency['value'];

                    $user = auth()->guard('user')->user();
                    if($user){
                        if (!empty($user->vip_id)) {
                            $productVip = ProductVip::where('vip_id', $user->vip_id)->where('product_id', $data[$i]['id'])->first();
                            if ($productVip) {
                                $priceOffer = $price * ($productVip->percentage / 100);
                                $price = $price - $priceOffer;
                                $priceBOfferOffer = $priceBOffer * ($productVip->percentage / 100);
                                $priceBOffer = $priceBOffer - $priceBOfferOffer;
                                $data[$i]['offer'] = 1;
                                $data[$i]['offer_percentage'] = $productVip->percentage;
                            }
                        }
                        $favorite = Favorite::where('user_id' , $user->id)->where('product_id' , $data[$i]['id'])->first();
                        if($favorite){
                            $data[$i]['favorite'] = true;
                        }else{
                            $data[$i]['favorite'] = false;
                        }
                    }else{
                        $data[$i]['favorite'] = false;
                    }
                    $data[$i]['final_price'] = number_format((float)$price, 3, '.', '');
                    $data[$i]['price_before_offer'] = number_format((float)$priceBOffer, 3, '.', '');
                }
            }


            return view('front.favorite-ar', compact(['data', 'web_image', 'currency_data']));
        }

    }

    // add to cart
    public function addToCart(Request $request) {
        Parent::getCartData($request);
        $ip = Parent::getIp($request);
        $visitor = WebVisitor::where('ip', $ip)->first();
        $product = Product::where('id', $request->product_id)->select('remaining_quantity')->first();
        $cart = Cart::where('web_visitor_id', $visitor->id)->where('product_id', $request->product_id)->first();
        if ( ($cart && $product->remaining_quantity >= ($cart->count + $request->count)) || (!$cart && $product->remaining_quantity >= $request->count)) {
            if ($cart) {
                $cart->update([
                    'count' => $cart->count + $request->count
                ]);
            }else {
                Cart::create([
                    'web_visitor_id' => $visitor->id,
                    'product_id' => $request->product_id,
                    'count' => $request->count
                ]);
            }
            
    
            toast('تم إضافة المنتج إلى العربه', 'success');
        }else {
            toast('الكمية المتاحة لا تكفى', 'error');
        }


        return redirect()->back();
    }

    // remove from cart
    public function removeFromCart(Request $request) {
        Cart::where('id', $request->id)->first()->delete();

        toast('تم حذف المنتج من العربه', 'warning');

        return redirect()->back();
    }

    // get cart
    public function getCart(Request $request) {
        Parent::getCartData($request);
        $request->lang = 'ar';
        $cart = $this->cart;
        $currency = $this->all_currency_data;
        $data['cart'] = [];
        $data['total'] = '0.000';
        if (count($cart) > 0) {
            for ($i = 0; $i < count($cart); $i ++) {
                $product = Product::where('id', $cart[$i]['product_id'])
                ->select('id', 'title_' . $request->lang . ' as title', 'offer', 'final_price', 'price_before_offer', 'offer_percentage', 'category_id')
                ->first()
                ->makeHidden('images');
                $product['count'] = $cart[$i]['count'];
                $price = $product['final_price'] * $product['count'] * $currency['value'];
                
                $priceBOffer = $product['price_before_offer'] * $currency['value'];
                
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
                            $price = $price - $priceOffer;
                            $priceBOffer = $product['final_price'] * $currency['value'];
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
                
                $product['final_price'] = number_format((float)$price, 3, '.', '');
                $product['price_before_offer'] = number_format((float)$priceBOffer, 3, '.', '');
                $data['total'] = $data['total'] + $product['final_price'];
                array_push($data['cart'], $product);
            }
        }
        
        
        return view('front.cart-ar', compact('data'));
    }

    // update count
    public function updateCount(Request $request) {
        $cart = Cart::where('id', $request->id)->first();
    
        if ($request->count > $cart->product->remaining_quantity) {
            toast('الكمية المتاحة لا تكفى', 'error');
        }else {
            $cart->update(['count' => $request->count]);
            Alert::toast('تم تحديث الكمية بنجاح', 'success');
        }
        

        return redirect()->back();
    }

    // get payment
    public function getPayment(Request $request) {
        Parent::getCartData($request);
        return view('front.Payment-ar');
    }

    // get search products
    public function getSearchProducts(Request $request) {
        Parent::getCartData($request);
        if ($this->webVisitor) {
            $webVisitor = $this->webVisitor;
            $currency_data['currency'] = $this->currency;
            $web_image =  ad::where('place',3)->inRandomOrder()->limit(1)->get();
            $toCurr = $webVisitor->country->currency_en;
            $currency = $this->gSliderAdetCurrency($toCurr);
            $search = $request->product;
            $request->lang = 'ar';
            
            $data = Product::where('products.deleted', 0)
                ->where('products.hidden', 0)
                ->Where(function($query) use ($search) {
                    $query->Where('products.title_en', 'like', '%' . $search . '%')->orWhere('products.title_ar', 'like', '%' . $search . '%');
                });

            if ($request->category != 0) {
                $data = $data->where('category_id', $request->category);
            }

            $data = $data->select('id', 'title_ar as title', 'offer', 'final_price', 'price_before_offer', 'offer_percentage', 'brief_' . $request->lang . ' as brief')
            ->paginate(12);
            $data->makeHidden('images');
            if (count($data) > 0) {
                for ($i = 0; $i < count($data); $i ++) {
                    if ($data[$i]->main_image) {
                        $data[$i]->main_image = $data[$i]->main_image->image;
                    }else {
                        if (count($data[$i]->images) > 0) {
                            $data[$i]->main_image = $data[$i]->images[0]->image;
                        }
                    }
                    $price = $data[$i]['final_price'] * $currency['value'];
                    $priceBOffer = $data[$i]['price_before_offer'] * $currency['value'];
    
                    $user = auth()->guard('user')->user();
                    if($user){
                        if (!empty($user->vip_id)) {
                            $productVip = ProductVip::where('vip_id', $user->vip_id)->where('product_id', $data[$i]['id'])->first();
                            if ($productVip) {
                                $priceOffer = $price * ($productVip->percentage / 100);
                                $price = $price - $priceOffer;
                                $priceBOfferOffer = $priceBOffer * ($productVip->percentage / 100);
                                $priceBOffer = $priceBOffer - $priceBOfferOffer;
                                $data[$i]['offer'] = 1;
                                $data[$i]['offer_percentage'] = $productVip->percentage;
                            }
                        }
                        $favorite = Favorite::where('user_id' , $user->id)->where('product_id' , $data[$i]['id'])->first();
                        if($favorite){
                            $data[$i]['favorite'] = true;
                        }else{
                            $data[$i]['favorite'] = false;
                        }
                    }else{
                        $data[$i]['favorite'] = false;
                    }
                    $data[$i]['final_price'] = number_format((float)$price, 3, '.', '');
                    $data[$i]['price_before_offer'] = number_format((float)$priceBOffer, 3, '.', '');
                }
            }
    
            return view('front.products-list-ar',compact('data','currency_data','web_image'));
        }else {
            return redirect()->route('front.failed');
        }
    }

    // connection failed
    public function connection_fail() {
        return view('front.connection_fail');
    }

    // request order
    public function requestOrder(Request $request) {
        Parent::getCartData($request);
        $root_url = $request->root();
        $user = auth()->guard('user')->user();
        $webVisitor = $this->webVisitor;
        
        $cart = Cart::where('web_visitor_id', $webVisitor->id)->get();
        
        if (count($cart) > 0) {
            $cartWarning = false;
            for ($i = 0; $i < count($cart); $i ++) {
                if ($cart[$i]->count > $cart[$i]->product->remaining_quantity) {
                    $cart[$i]->update(['count' => $cart[$i]->product->remaining_quantity]);
                    $cartWarning = true;
                }
            }

            if ($cartWarning) {
                Alert::warning('لم يتم إستكمال الطلب', 'عذراً هناك بعض المنتجات الكمية المشتراه منها غير متوفرة حالياً لذلك تم تحديثها بالكمية المتاحة');
                return redirect()->back();
            }
        }
        $path='https://apitest.myfatoorah.com/v2/SendPayment';
        $token="bearer rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
        $headers = array(
            'Authorization:' .$token,
            'Content-Type:application/json'
        );

        $total = 0.000;

        if (count($cart) > 0) {
            for ($i = 0; $i < count($cart); $i ++) {
                $product = Product::where('deleted', 0)->where('hidden', 0)->where('id', $cart[$i]->product_id)->first();
                $price = $product['final_price'];
                
                if (!empty($user->vip_id)) {
                    $productVip = ProductVip::where('vip_id', $user->vip_id)->where('product_id', $product['id'])->first();
                    if ($productVip) {
                        $priceOffer = $price * ($productVip->percentage / 100);
                        $price = ($price  * $cart[$i]['count']) - ($priceOffer  * $cart[$i]['count']);
                    }
                }else {
                    $price = $price * $cart[$i]['count'];
                }
                
                $total = $price + $total;
            }
        }
        

        $price = $total;
        
        $request->payment_method = 1;
        $call_back_url = $root_url . "/payment/execute?payment_method=".$request->payment_method . "&email=" . $request->email . "&price=" . number_format((float)$price, 3, '.', '');
        $error_url = $root_url . "/payment/failed";
        $name = $user->name;
        if (empty($name)) {
            $name = $request->email;
        }
        $fields =array(
            "CustomerName" => $name,
            "NotificationOption" => "LNK",
            "InvoiceValue" => $price,
            "CallBackUrl" => $call_back_url,
            "ErrorUrl" => $error_url,
            "Language" => "AR",
            "CustomerEmail" => $request->email,
            "CustomerMobile" => substr($user->phone, 4),
            "DisplayCurrencyIso" => "USD"
        );
        $payload =json_encode($fields);
        $curl_session =curl_init();
        curl_setopt($curl_session,CURLOPT_URL, $path);
        curl_setopt($curl_session,CURLOPT_POST, true);
        curl_setopt($curl_session,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_session,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl_session,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_session,CURLOPT_IPRESOLVE, CURLOPT_IPRESOLVE);
        curl_setopt($curl_session,CURLOPT_POSTFIELDS, $payload);
        
        $result=curl_exec($curl_session);
        curl_close($curl_session);
        $result = json_decode($result);
        
        $data['url'] = $result->Data->InvoiceURL;

        return redirect($data['url']);
    }

    // excute order (success)
    public function excuteOrder(Request $request) {
        Parent::getCartData($request);
        $visitor = WebVisitor::where('ip', Parent::getIp($request))->first();
        $now = Carbon::now();
        $lastOrder = Order::orderBy('id', 'desc')->first();
        $orderNumber = $now->year . $now->month . $now->day . "01";
        if ($lastOrder) {
            $subSOrder = (int)$lastOrder->id + 1;
            if ($subSOrder < 9) {
                $subSOrder = '0' . $subSOrder;
            }
            $orderNumber = $now->year . $now->month . $now->day . $subSOrder;
        }
        $user = auth()->guard('user')->user();
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'payment_method' => 1,
            'country_code' => $visitor->country_code,
            'status' => 1
        ]);
        $cart = Cart::where('web_visitor_id', $visitor->id)->get();
        if (count($cart) > 0) {
            for ($i = 0; $i < count($cart); $i++) {
                $product = Product::where('deleted', 0)->where('hidden', 0)->where('id', $cart[$i]['product_id'])->first();
                $price = $product['final_price'];
                $priceBOffer = $product['price_before_offer'];
                if (!empty($user->vip_id)) {
                    $productVip = ProductVip::where('vip_id', $user->vip_id)->where('product_id', $product['id'])->first();
                    if ($productVip) {
                        $priceOffer = $price * ($productVip->percentage / 100);
                        $price = $price - $priceOffer;
                        $priceBOffer = $product['final_price'];
                        $product['offer_percentage'] = $productVip->percentage;
                    }
                }
                $oItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price_before_offer' => number_format((float)$priceBOffer, 3, '.', ''),
                    'final_price' => number_format((float)$price, 3, '.', ''),
                    'discount' => $product['offer_percentage'],
                    'count' => $cart[$i]['count'],
                    'status' => 1
                ]);
                // get valid product serials
                $path='http://athath-ads.tk/api/serials/valid';
                $fields =array(
                    'product_id' => $product->id
                );
                $result = APIHelpers::fetchApi($path, $fields, 'json', 'post');
                $serials = $result->data;
                
                if (count($serials) > 0) {
                    for ($s = 0; $s < $cart[$i]['count']; $s ++) {
                        $oItemSerials = OrderSerial::create([
                            'order_id' => $oItem->id,
                            'serial_id' => $serials[$s]->id,
                            'serial' => $serials[$s]->serial,
                            'serial_number' => $serials[$s]->serial_number,
                            'valid_to' => $serials[$s]->valid_to,
                            'product_id' => $product->id
                        ]);
                        
                        // update sold serials
                        $path='http://athath-ads.tk/api/serials/update-serial-bought';
                        $fields =array(
                            'serial_id' => $serials[$s]->id
                        );
                        $result = APIHelpers::fetchApi($path, $fields, 'json', 'post');
                        
                        $product->remaining_quantity = $product->remaining_quantity - 1;
                        $product->sold_count = $product->sold_count + 1;
                        $product->save();
                    }
                }
                $cart[$i]->delete();
            }
        }
        
        if (count($order->oItems) > 0) {
            $order->update([
                'subtotal_price' => $request->price,
                'total_price' => $request->price
            ]);
        }
        
        $pluckSerials = OrderItem::where('order_id', $order->id)->pluck('id')->toArray();
        $orderSerials['serials'] = OrderSerial::whereIn('order_id', $pluckSerials)->get();
        $orderSerials['order'] = $order;
        
        Mail::send('order_details_mail', $orderSerials, function($message) use ($user, $request) {
            $message->to($request->email, $user->name)->subject
                ('Order Details');
            $message->from('modaapp9@gmail.com','Al thuraya');
        });

        Alert::success('عملية شراء ناجحة', 'تم إرسال الأكواد المشتراه إلى بريدك الإلكترونى');

        return redirect()->route('front.home');
    }

    // failed payment
    public function failedPayment() {
        Alert::error('عملية شراء غير ناجحة', 'من فضلك تأكد من أن بيانات بطاقتك صحيحة و أنها سارية');
        return redirect()->route('front.home');
    }

    // get sub categories
    public function getSubcategories(Request $request) {
        Parent::getCartData($request);
        $request->lang = 'ar';
        $web_image =  ad::where('place',3)->inRandomOrder()->limit(1)->get();
        if ($request->category_id && $request->sub_category_id && $request->sub_category_two_id && $request->sub_category_three_id && $request->sub_category_four_id) {
            $data['sub_categories'] = $this->getSubCategoriesFive($request, 'web');
        }elseif ($request->category_id && $request->sub_category_id && $request->sub_category_two_id && $request->sub_category_three_id) {
            $data['sub_categories'] = $this->getSubCategoriesFour($request, 'web');
        }elseif ($request->category_id && $request->sub_category_id && $request->sub_category_two_id) {
            $data['sub_categories'] = $this->getSubCategoriesThree($request, 'web');
        }elseif ($request->category_id && $request->sub_category_id) {
            $data['sub_categories'] = $this->getSubCategoriesTwo($request, 'web');
        }elseif ($request->category_id) {
            $data['sub_categories'] = $this->getSubCategoriesOne($request, 'web');
        }

        return view('front.categories-ar', compact(['data', 'web_image']));
    }

    // get user orders
    public function getUserOrders(Request $request) {
        Parent::getCartData($request);
        if ($this->webVisitor) {
            $request->lang = 'ar';
            $user_id = auth()->guard('user')->user()->id;
            $data['orders'] = $this->getMyOrders($user_id, $request);
    
            return view('front.my-requests-ar', compact('data'));
        }else {
            return redirect()->route('front.failed');
        }
        
    }

    // get order details
    public function getOrderDetails(Request $request) {
        Parent::getCartData($request);
        if ($this->webVisitor) {
            $webVisitor = $this->webVisitor;
            $currency_data['currency'] = $this->currency;
            $toCurr = $webVisitor->country->currency_en;
            $data['currency'] = $this->gSliderAdetCurrency($toCurr);
            $request->lang = 'ar';
            $order_id = $request->id;
            $data['order'] = $this->getOrderDetail($order_id, $request);
            $data['order']->total_price = $data['order']->total_price * $data['currency']['value'];
            $data['order']->total_price = number_format((float)$data['order']->total_price, 3, '.', '');
            if (count($data['order']->items) > 0) {
                $data['order']->items->map(function ($row) use ($data) {
                    
                    $row->final_price = $row->final_price * $data['currency']['value'];
                    
                    $row->final_price = number_format((float)$row->final_price, 3, '.', '');
                    $row->price_before_offer = $row->price_before_offer * $data['currency']['value'];
                    $row->price_before_offer = number_format((float)$row->price_before_offer, 3, '.', '');
                    return $row;
                });
            }
        }else {
            return redirect()->route('front.failed');
        }
        

        return view('front.order-details-ar', compact('data', 'currency_data'));
    }
}
