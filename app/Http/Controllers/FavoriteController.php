<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Favorite;
use App\Category;
use App\Product;
use App\Currency;
use App\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Helpers\APIHelpers;


class FavoriteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => []]);
    }

    public function addtofavorites(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields or product does not exist' , 'بعض الحقول مفقودة او المنتج غير موجود'  , null , $request->lang);
            return response()->json($response , 406);
        }
        $user_id = auth()->user()->id;
        
        $prevfavorite = Favorite::where('product_id' , $request->product_id)->where('user_id' , $user_id)->first();
        if($prevfavorite){
            $response = APIHelpers::createApiResponse(true , 406 , 'This product added to favorite list before' , 'تم إضافه هذا المنتج للمفضله من قبل'  , null , $request->lang);
            return response()->json($response , 406);
        }

        $favorite = new Favorite();
        $favorite->product_id = $request->product_id;
        $favorite->user_id = $user_id;
        $favorite->save();

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $favorite , $request->lang);
        return response()->json($response , 200);
    }

    public function removefromfavorites(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة'  , null , $request->lang);
            return response()->json($response , 406);
        }

        $user_id = auth()->user()->id;
        $favorite = Favorite::where('product_id' , $request->product_id)->where('user_id' , $user_id)->first();
        if($favorite){
            $favorite->delete();
            $deleted = (object)["product_id" => 0, "user_id" => 0, "updated_at" => "", "created_at" => "", "id" => 0];
            $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $deleted , $request->lang);
            return response()->json($response , 200);

        }else{
            $response = APIHelpers::createApiResponse(false , 200 , '' , '' , [] , $request->lang);
            return response()->json($response , 200);
        }

    }

    public function getfavorites(Request $request){
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
            $user_id = auth()->user()->id;
            $favorites = Favorite::where('user_id' , $user_id)->pluck('product_id')->toArray();
            
            if($request->lang == 'en'){
                $products = Product::whereIn('id', $favorites)->where('deleted' , 0)
                ->where('hidden' , 0)
                ->where('remaining_quantity', '>', 0)
                ->select('id', 'title_en as title' , 'final_price' , 'price_before_offer' , 'offer' , 'offer_percentage' , 'category_id' )
                ->get()
                ->makeHidden('images');
            }else{
                $products = Product::whereIn('id', $favorites)
                ->where('deleted' , 0)
                ->where('hidden' , 0)
                ->where('remaining_quantity', '>', 0)
                ->select('id', 'title_ar as title' , 'final_price' , 'price_before_offer' , 'offer' , 'offer_percentage' , 'category_id' )
                ->get()
                ->makeHidden('images');
            }
            
            for($i =0 ; $i < count($products); $i++){
                $products[$i]['favorite'] = true;
                if($request->lang == 'en'){
                    $products[$i]['category_name'] = Category::where('id' , $products[$i]['category_id'])->pluck('title_en as title')->first();
                }else{
                    $products[$i]['category_name'] = Category::where('id' , $products[$i]['category_id'])->pluck('title_ar as title')->first();
                }
                
                $price = $products[$i]['final_price'] * $currency['value'];
                $priceBOffer = $products[$i]['price_before_offer'] * $currency['value'];
                $currencySympol = $visitor->country->currency_en;
                if ($request->lang == 'ar') {
                    $currencySympol = $visitor->country->currency_ar;
                }
                $products[$i]['final_price'] = number_format((float)$price, 3, '.', '') . " " . $currencySympol;
                $products[$i]['price_before_offer'] = number_format((float)$priceBOffer, 3, '.', '') . " " . $currencySympol;
                if ($products[$i]->main_image) {
                    $products[$i]->main_image = $products[$i]->main_image->image;
                }else {
                    if (count($products[$i]->images) > 0) {
                        $products[$i]->main_image = $products[$i]->images[0]->image;
                    }
                }
            }
            $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $products , $request->lang);
            return response()->json($response , 200);
        }else {
            $response = APIHelpers::createApiResponse(true , 406 , 'Visitor is not exist or code country is empty' , 'Visitor is not exist or code country is empty' , null , $request->lang);
            return response()->json($response , 406);
        }
    }

}