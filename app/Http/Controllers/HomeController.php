<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use App\Favorite;;
use App\Product;
use App\Ad;
use App\SliderAd;
use App\Currency;
use App\ProductVip;
use App\Country;
use App\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Constraint\Count;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['getdata', 'getSlider', 'getOffersSlider', 'getoffers', 'getOffersPage']]);
    }

    // get home sliders
    public function getSlider(Request $request) {
        $ads = $this->getSlidersTypes($request);

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $ads , $request->lang);
        return response()->json($response , 200);
    }

    // get home offers (recent - choose for you)
    public function getoffers(Request $request){
        if (!$request->header('uniqueid') || empty($request->header('uniqueid'))) {
            $response = APIHelpers::createApiResponse(true , 406 , 'uniqueid required header' , 'uniqueid required header' , null , $request->lang);
            return response()->json($response , 406);
        }
        $visitor = Visitor::where('unique_id', $request->header('uniqueid'))->select('country_code')->first();
        if ($visitor && !empty($visitor->country_code)) {
            $currency = $visitor->country->currency_en;
            $toCurr = trim(strtolower($currency));
        
            if ($toCurr == "usd") {
                $currency = ["value" => 1];
            }else {
                $currency = Currency::where('from', "usd")->where('to', $toCurr)->first();
            }
            if (isset($currency['id'])) {
                if (!$currency->updated_at->isToday()) {
                    $result = APIHelpers::converCurruncy2("usd", $toCurr);
                    if(isset($result['value'])){
                        $currency->update(['value' => $result['value'], 'updated_at' => Carbon::now()]);
                        $currency = Currency::where('from', "usd")->where('to', $toCurr)->first();
                    }
                    
                }
                
            }else {
                $result = APIHelpers::converCurruncy2("usd", $toCurr);
                // dd($result);
                if(isset($result['value']) && !$currency){
                    $result = APIHelpers::converCurruncy2("usd", $toCurr);
                    $currency = Currency::create(['value' => $result['value'], "from" => "usd", "to" => $toCurr]);
                }
            }

            $data = $this->getOffersTypes($request);
    
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
                
                if(auth()->user()){
                    
                    if (!empty(auth()->user()->vip_id)) {
                        
                        $productVip = ProductVip::where('vip_id', auth()->user()->vip_id)->where('product_id', $data[$i]['id'])->first();
                        if ($productVip) {
                            $priceOffer = $price * ($productVip->percentage / 100);
                            $price = $price - $priceOffer;
                            $priceBOfferOffer = $priceBOffer * ($productVip->percentage / 100);
                            $priceBOffer = $priceBOffer - $priceBOfferOffer;
                            $data[$i]['offer'] = 1;
                            $data[$i]['offer_percentage'] = $productVip->percentage;
                        }
    
                    }
                    $user_id = auth()->user()->id;
        
                    $prevfavorite = Favorite::where('product_id' , $data[$i]['id'])->where('user_id' , $user_id)->first();
                    if($prevfavorite){
                        $data[$i]['favorite'] = true;
                    }else{
                        $data[$i]['favorite'] = false;
                    }
        
                }else{
                    $data[$i]['favorite'] = false;
                }
                $currencySympol = $visitor->country->currency_en;
                if ($request->lang == 'ar') {
                    $currencySympol = $visitor->country->currency_ar;
                }
                $data[$i]['final_price'] = number_format((float)$price, 3, '.', '') . " " . $currencySympol;
                $data[$i]['price_before_offer'] = number_format((float)$priceBOffer, 3, '.', '') . " " . $currencySympol;
            }
            
            $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
            return response()->json($response , 200);
        }else {
            $response = APIHelpers::createApiResponse(true , 406 , 'Visitor is not exist or code country is empty' , 'Visitor is not exist or code country is empty' , null , $request->lang);
            return response()->json($response , 406);
        }
        
    }

    // get offers slider
    public function getOffersSlider(Request $request) {
        $ads = Ad::select('id', 'image', 'type', 'content')->inRandomOrder()->limit(3)->get();

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $ads , $request->lang);
        return response()->json($response , 200);
    }

    // get offers page
    public function getOffersPage(Request $request) {
        if (!$request->header('uniqueid') || empty($request->header('uniqueid'))) {
            $response = APIHelpers::createApiResponse(true , 406 , 'uniqueid required header' , 'uniqueid required header' , null , $request->lang);
            return response()->json($response , 406);
        }
        $visitor = Visitor::where('unique_id', $request->header('uniqueid'))->select('country_code')->first();
        if ($visitor && !empty($visitor->country_code)) {
            $currency = $visitor->country->currency_en;
            $toCurr = trim(strtolower($currency));
        
            if ($toCurr == "usd") {
                $currency = ["value" => 1];
            }else {
                $currency = Currency::where('from', "usd")->where('to', $toCurr)->first();
            }

            if (isset($currency['id'])) {
                if (!$currency->updated_at->isToday()) {
                    $result = APIHelpers::converCurruncy2("usd", $toCurr);
                    if(isset($result['value'])){
                        $currency->update(['value' => $result['value'], 'updated_at' => Carbon::now()]);
                        $currency = Currency::where('from', "usd")->where('to', $toCurr)->first();
                    }
                    
                }
                
            }else {
                $result = APIHelpers::converCurruncy2("usd", $toCurr);
                // dd($result);
                if(isset($result['value']) && !$currency){
                    $result = APIHelpers::converCurruncy2("usd", $toCurr);
                    $currency = Currency::create(['value' => $result['value'], "from" => "usd", "to" => $toCurr]);
                }
            }

            if ($request->type == 'recent') {
                $data = Product::where('deleted', 0)->where('hidden', 0)->where('recent_offers', 1)->select('id', 'title_' . $request->lang . ' as title', 'offer', 'final_price', 'price_before_offer', 'offer_percentage')->simplePaginate(12);
            }else {
                $data = Product::where('deleted', 0)->where('hidden', 0)->where('choose_for_you', 1)->select('id', 'title_' . $request->lang . ' as title', 'offer', 'final_price', 'price_before_offer', 'offer_percentage')->simplePaginate(12);
            }
            if ($data && count($data) > 0) {
                $data->makeHidden('images');
            }

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

                if(auth()->user()){
                    $user_id = auth()->user()->id;
                    if (!empty(auth()->user()->vip_id)) {
                        $productVip = ProductVip::where('vip_id', auth()->user()->vip_id)->where('product_id', $data[$i]['id'])->first();
                        if ($productVip) {
                            $priceOffer = $price * ($productVip->percentage / 100);
                            $price = $price - $priceOffer;
                            $priceBOfferOffer = $priceBOffer * ($productVip->percentage / 100);
                            $priceBOffer = $priceBOffer - $priceBOfferOffer;
                            $data[$i]['offer'] = 1;
                            $data[$i]['offer_percentage'] = $productVip->percentage;
                        }
                    }
                    $prevfavorite = Favorite::where('product_id' , $data[$i]['id'])->where('user_id' , $user_id)->first();
                    if($prevfavorite){
                        $data[$i]['favorite'] = true;
                    }else{
                        $data[$i]['favorite'] = false;
                    }
        
                }else{
                    $data[$i]['favorite'] = false;
                }
                $currencySympol = $visitor->country->currency_en;
                if ($request->lang == 'ar') {
                    $currencySympol = $visitor->country->currency_ar;
                }
                $data[$i]['final_price'] = number_format((float)$price, 3, '.', '') . " " . $currencySympol;
                $data[$i]['price_before_offer'] = number_format((float)$priceBOffer, 3, '.', '') . " " . $currencySympol;
            }
            
            $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
            return response()->json($response , 200);

        }else {
            $response = APIHelpers::createApiResponse(true , 406 , 'Visitor is not exist or code country is empty' , 'Visitor is not exist or code country is empty' , null , $request->lang);
            return response()->json($response , 406);
        }
    }


    // get countries
    public function getCountries(Request $request) {
        $data = Country::select('id', 'country_name', 'currency_' . $request->lang . ' as currency', 'icon')->orderBy('country_name', 'asc')->get();

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }
}
