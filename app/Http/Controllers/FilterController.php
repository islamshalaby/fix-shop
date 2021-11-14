<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\APIHelpers;
use App\Category;
use App\Product;
use App\MultiOptionValue;



class FilterController extends Controller
{
    // get filter data
    public function get_filter(Request $request, $storeId) {
        if($request->lang == 'en'){
            $data['categories'] = Category::join('products','products.category_id', '=', 'categories.id')
            ->where('products.store_id', $storeId)
            ->select('categories.id', 'categories.title_en as title')
            ->groupBy('categories.id')
            ->groupBy('categories.title_en')
            ->orderBy('id', 'desc')->get();
            $data['sizes'] = MultiOptionValue::select('value_en as size', 'id')->where('multi_option_id', 8)->get();
            $data['type'] = [(object)['name' => 'Ready-made', 'id' => 1], (object)['name' => 'Tailoring', 'id' => 2]];
        }else {
            $data['categories'] = Category::join('products','products.category_id', '=', 'categories.id')
            ->where('products.store_id', $storeId)
            ->select('categories.id', 'categories.title_ar as title')
            ->groupBy('categories.id')
            ->groupBy('categories.title_ar')
            ->orderBy('id', 'desc')->get();
            $data['sizes'] = MultiOptionValue::select('value_ar as size', 'id')->where('multi_option_id', 8)->get();
            $data['type'] = [(object)['name' => 'جاهزة', 'id' => 1], (object)['name' => 'تفصال', 'id' => 2]];
        }

        $data['min_price'] = Product::where('deleted', 0)->where('hidden', 0)->where('store_id', $storeId)->min('final_price');
        $data['max_price'] = Product::where('deleted', 0)->where('hidden', 0)->where('store_id', $storeId)->max('final_price');
        

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }
}