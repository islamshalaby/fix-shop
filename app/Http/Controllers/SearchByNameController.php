<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Address;
use App\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Helpers\APIHelpers;
use App\Favorite;
use App\Currency;
use App\ProductVip;

class SearchByNameController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => []]);
    }

    public function Search(Request $request)
    {
        $search = $request->search;

        $data = Product::where('products.deleted', 0)
        ->where('products.hidden', 0)
        ->Where(function($query) use ($search) {
            $query->Where('products.title_en', 'like', '%' . $search . '%')->orWhere('products.title_ar', 'like', '%' . $search . '%');
        })
        ->select('id', 'title_' . $request->lang . ' as title', 'offer', 'final_price', 'price_before_offer', 'offer_percentage')
        ->simplePaginate(12);
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
                $price = $data[$i]['final_price'];
                $priceBOffer = $data[$i]['price_before_offer'];

                $user = auth()->user();
                if($user){
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


        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang) ;
        return response()->json($response , 200);
       
    }
}
