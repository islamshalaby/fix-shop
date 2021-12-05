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
        
        $user_id = auth()->user()->id;
        $favorites = Favorite::where('user_id' , $user_id)->pluck('product_id')->toArray();
        
        if($request->lang == 'en'){
            $products = Product::whereIn('id', $favorites)->where('deleted' , 0)
            ->where('hidden' , 0)
            ->where('remaining_quantity', '>', 0)
            ->select('id', 'title_en as title' , 'final_price' , 'price_before_offer' , 'offer' , 'offer_percentage' )
            ->simplePaginate(20);
            $products->makeHidden('images');
        }else{
            $products = Product::whereIn('id', $favorites)
            ->where('deleted' , 0)
            ->where('hidden' , 0)
            ->where('remaining_quantity', '>', 0)
            ->select('id', 'title_ar as title' , 'final_price' , 'price_before_offer' , 'offer' , 'offer_percentage')
            ->simplePaginate(20);
            $products->makeHidden('images');
        }
        
        $products->map(function ($row) {
            if ($row->main_image) {
                $row->main_image = $row->main_image->image;
            }else {
                $row->main_image = $row->images[0]->image;
            }
            

            return $row;
        });
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $products , $request->lang);
        return response()->json($response , 200);
        
    }

}