<?php

namespace App\Http\Controllers;

use App\Ad;
use Illuminate\Http\Request;
use App\Offer;
use App\Product;
use App\Category;
use Illuminate\Support\Facades\Session;
use App\Helpers\APIHelpers;


class OfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['getoffers']]);
    }

    public function getoffers(Request $request){
        $offers_before = Offer::orderBy('sort' , 'ASC')->get();
        $offers = [];
        
        for($i = 0; $i < count($offers_before); $i++){
            if($offers_before[$i]['type'] == 1){
                $result = Product::find($offers_before[$i]['target_id']);
                if($result['deleted'] == 0 && $result['hidden'] == 0){
                    array_push($offers , $offers_before[$i]);
                }
            }else{
                $result = Category::find($offers_before[$i]['target_id']);
                if($result['deleted'] == 0 ){
                    array_push($offers , $offers_before[$i]);
                }
            }


        }

        $new_offers = [];
        for($i = 0; $i < count($offers); $i++){
            array_push($new_offers , $offers[$i]);
            if($offers[$i]->size == 3){
                if(count($offers) > 1 ){
                    if($offers[$i-1]->size != 3){
                        if(count($offers) > $i+1 ){
                            if($offers[$i+1]->size != 3){
                                $offer_element = new \stdClass();
                                $offer_element->id = 0;
                                $offer_element->image  = '';
                                $offer_element->size = 3;
                                $offer_element->type = 0;
                                $offer_element->target_id = 0;
                                $offer_element->sort = 0;
                                $offer_element->created_at = "";
                                $offer_element->updated_at = "";
                                array_push($new_offers , $offer_element);
                            }
                        }
                    }

                }
            }                        
        }
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $new_offers , $request->lang);
        return response()->json($response , 200);
    }

    public function getoffersandroid(Request $request){

        $offers_before = Offer::orderBy('sort' , 'ASC')->get();
        $offers = [];
        
        for($i = 0; $i < count($offers_before); $i++){
            if($offers_before[$i]['type'] == 1){
                $result = Product::find($offers_before[$i]['target_id']);
                if($result['deleted'] == 0 && $result['hidden'] == 0){
                    array_push($offers , $offers_before[$i]);
                }
            }else{
                $result = Category::find($offers_before[$i]['target_id']);
                if($result['deleted'] == 0){
                    array_push($offers , $offers_before[$i]);
                }
            }



        }

        $new_offers = [];
        for($i = 0; $i < count($offers); $i++){
            if($offers[$i]->size == 1 || $offers[$i]->size == 2 ){
                $count = count($new_offers);
                $new_offers[$count] = [];
                array_push($new_offers[$count] , $offers[$i]);
                $offer_element = new \stdClass();
                $offer_element->id = 0;
                $offer_element->image  = '';
                $offer_element->size = $offers[$i]->size;
                $offer_element->type = 0;
                $offer_element->target_id = 0;
                $offer_element->sort = 0;
                $offer_element->created_at = "";
                $offer_element->updated_at = "";
                array_push($new_offers[$count] , $offer_element);
            }

            if($offers[$i]->size == 3){

                if(count($offers) > 1 ){

                    $count_offers = count($new_offers);

                    $last_count = count($new_offers[$count_offers - 1]);
                    
                    if($last_count == 2){
                        $new_offers[$count_offers] = [];
                        array_push($new_offers[$count_offers] , $offers[$i]);
                        if(count($offers) > $i+1 ){
                             if($offers[$i+1]->size != 3){
                                $offer_element = new \stdClass();
                                $offer_element->id = 0;
                                $offer_element->image  = '';
                                $offer_element->size = 3;
                                $offer_element->type = 0;
                                $offer_element->target_id = 0;
                                $offer_element->sort = 0;
                                $offer_element->created_at = "";
                                $offer_element->updated_at = "";
                                array_push($new_offers[$count_offers] , $offer_element);
                            }
                        }else{
                            $offer_element = new \stdClass();
                            $offer_element->id = 0;
                            $offer_element->image  = '';
                            $offer_element->size = 3;
                            $offer_element->type = 0;
                            $offer_element->target_id = 0;
                            $offer_element->sort = 0;
                            $offer_element->created_at = "";
                            $offer_element->updated_at = "";
                            array_push($new_offers[$count_offers] , $offer_element);
                        }
                    }else{
                        array_push($new_offers[$count_offers - 1] , $offers[$i]);
                    }

                }else{
                    $count = count($new_offers);
                    $new_offers[$count] = [];
                    array_push($new_offers[$count] , $offers[$i]);
                    $offer_element = new \stdClass();
                    $offer_element->id = 0;
                    $offer_element->image  = '';
                    $offer_element->size = $offers[$i]->size;
                    $offer_element->type = 0;
                    $offer_element->target_id = 0;
                    $offer_element->sort = 0;
                    $offer_element->created_at = "";
                    $offer_element->updated_at = "";
                    array_push($new_offers[$count] , $offer_element);
                }
                
            }

        }

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $new_offers , $request->lang);
        return response()->json($response , 200);

    }

    public function get_offers(Request $request) {
        Session::put('language',$request->lang);
        $ad = Ad::select('id', 'image', 'type', 'content')->inRandomOrder()->limit(1)->get();
        $data['ad'] = (object)[
            'id' => 0,
            'image' => '',
            'type' => 0,
            'content' => ''
        ];
        if (count($ad) > 0) {
            $data['ad'] = $ad[0];
        }
        $categories = Product::where('recent_offers', 1)->pluck('category_id')->toArray();
        $data['offers'] = Category::whereIn('id', $categories)->where('deleted', 0)->select('id', 'title_' . $request->lang . ' as title')->orderBy('id', 'desc')->get()->makeHidden('offersProducts');


        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

}