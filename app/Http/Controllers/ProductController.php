<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\APIHelpers;
use App\Product;
use App\Currency;
use Carbon\Carbon;
use App\ProductVip;
use App\Favorite;
use App\Visitor;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['getbrandproducts', 'get_sub_category_products', 'getStoreProducts']]);
    }

    // product details
    public function getdetails(Request $request, $id){

        $data = Product::where('id', $id)->select('id', 'title_' . $request->lang . ' as title', 'offer', 'description_' . $request->lang . ' as description', 'final_price', 'price_before_offer', 'offer_percentage', 'category_id', 'installation_cost')->first()->makeHidden('category');
        
        $price = $data['final_price'];
        $priceBOffer = $data['price_before_offer'];
        if(auth()->user()){
            $user_id = auth()->user()->id;
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
        $data['delivery_installation_cost'] = number_format((float)$data->installation_cost, 3, '.', '');
        $total = $data['final_price'] + $data['delivery_installation_cost'];
        $data['total'] = number_format((float)$total, 3, '.', '');
        for ($k = 0; $k < count($data->images); $k ++) {
            $data['images'][$k] = $data->images[$k]['image'];
        }

        $data['related_products'] = Product::where('deleted', 0)
            ->where('hidden', 0)
            ->where('reviewed', 1)
            ->where('remaining_quantity', '>', 0)
            ->where('id', '!=', $data['id'])
            ->where('category_id', $data['category_id'])
            ->select('id', 'title_' . $request->lang . ' as title', 'final_price', 'price_before_offer', 'offer_percentage')
            ->orderBy('id', 'desc')
            ->inRandomOrder()->limit(5)
            ->get()->makeHidden('mainImage');

        if (count($data['related_products']) > 0) {
            for ($r = 0; $r < count($data['related_products']); $r ++) {
                $priceR = $data['related_products'][$r]['final_price'];
                $priceBOfferR = $data['related_products'][$r]['price_before_offer'];
                if(auth()->user()){
                    $user_id = auth()->user()->id;
                    $prevfavorite = Favorite::where('product_id' , $data['related_products'][$r]['id'])->where('user_id' , $user_id)->first();
                    if($prevfavorite){
                        $data['related_products'][$r]['favorite'] = true;
                    }else{
                        $data['related_products'][$r]['favorite'] = false;
                    }
                    $data['related_products'][$r]['final_price'] = number_format((float)$priceR, 3, '.', '');
                    $data['related_products'][$r]['price_before_offer'] = number_format((float)$priceBOfferR, 3, '.', '');
                }else{
                    $data['related_products'][$r]['favorite'] = false;
                }
                if ($data['related_products'][$r]->mainImage) {
                    $data['related_products'][$r]['image'] = $data['related_products'][$r]->mainImage['image'];
                }else {
                    $data['related_products'][$r]['image'] = "";
                }
                $data['related_products'][$r]['final_price'] = number_format((float)$data['related_products'][$r]['final_price'], 3, '.', '');
                $data['related_products'][$r]['price_before_offer'] = number_format((float)$data['related_products'][$r]['price_before_offer'], 3, '.', '');
                
            }
        }
        

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
        
    }

    // get products
    public function getproducts(Request $request){

        $data = Product::where('deleted', 0)->where('hidden', 0);
            
        if (isset($request->category_id)) {
            $data = $data->where('category_id', $request->category_id);
        }
        
        if (isset($request->sub_category_id) && $request->sub_category_id != 0) {
            $data = $data->where('sub_category_id', $request->sub_category_id);
        }

        if (isset($request->sub_category_two_id) && $request->sub_category_two_id != 0) {
            $data = $data->where('sub_category_two_id', $request->sub_category_two_id);
        }

        if (isset($request->sub_category_three_id) && $request->sub_category_three_id != 0) {
            $data = $data->where('sub_category_three_id', $request->sub_category_three_id);
        }

        if (isset($request->sub_category_four_id) && $request->sub_category_four_id != 0) {
            $data = $data->where('sub_category_four_id', $request->sub_category_four_id);
        }

        if (isset($request->sub_category_five_id) && $request->sub_category_five_id != 0) {
            $data = $data->where('sub_category_five_id', $request->sub_category_five_id);
        }

        $data = $data->select('id', 'title_' . $request->lang . ' as title', 'offer', 'final_price', 'price_before_offer', 'offer_percentage')->orderBy('id','desc')->simplePaginate(12);
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
                
                $data[$i]['final_price'] = number_format((float)$data[$i]['final_price'], 3, '.', '');
                $data[$i]['price_before_offer'] = number_format((float)$data[$i]['price_before_offer'], 3, '.', '');
            }
        }

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    

}