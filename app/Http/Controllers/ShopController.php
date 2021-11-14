<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\APIHelpers;
use App\Shop;
use App\Slider;
use App\Category;
use App\Product;


class ShopController extends Controller {
    // store categories
    public function store_categories(Request $request, $id) {
        $data['store'] = Shop::select('id', 'cover')->where('id', $id)->first()->makeHidden('custom');
        if (!isset($data['store']['id'])) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Store does not exist' , 'هذا المتجر غير موجود' , null , $request->lang);
            return response()->json($response , 406);
        }
        if($request->lang == 'en'){
            $data['categories'] = Category::join('products','products.category_id', '=', 'categories.id')
            ->where('products.store_id', $data['store']['id'])
            ->select('categories.id', 'categories.title_en as title', 'categories.image')
            ->groupBy('categories.id')
            ->groupBy('categories.title_en')
            ->groupBy('categories.image')
            ->orderBy('id', 'desc')->get();
        }else {
            $data['categories'] = Category::join('products','products.category_id', '=', 'categories.id')
            ->where('products.store_id', $data['store']['id'])
            ->select('categories.id', 'categories.title_ar as title', 'categories.image')
            ->groupBy('categories.id')
            ->groupBy('categories.title_ar')
            ->groupBy('categories.image')
            ->orderBy('id', 'desc')->get();
        }
        

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // get store products
    public function get_store_products(Request $request, $storeId) {
        $categoryId = $request->query('category_id');
        $size = $request->query('size_id');
        $type = $request->query('type');
        $from = $request->query('from');
        $to = $request->query('to');
        $slider = Slider::where('type', 2)->first();
        $data['slider'] = $slider->ads;
        if($request->lang == 'en'){
            $data['categories'] = Category::join('products','products.category_id', '=', 'categories.id')
            ->where('products.store_id', $storeId)
            ->select('categories.id', 'categories.title_en as title')
            ->groupBy('categories.id')
            ->groupBy('categories.title_en')
            ->orderBy('id', 'desc')->get();
        }else {
            $data['categories'] = Category::join('products','products.category_id', '=', 'categories.id')
            ->where('products.store_id', $storeId)
            ->select('categories.id', 'categories.title_ar as title')
            ->groupBy('categories.id')
            ->groupBy('categories.title_ar')
            ->orderBy('id', 'desc')->get();
        }
        if (empty($size) && empty($type) && (empty($from) && empty($to))) {
            if ($categoryId == 'all') {
                if($request->lang == 'en'){
                    $products = Product::select('id', 'title_en as title' , 'category_id', 'final_price', 'price_before_offer', 'offer' , 'type', 'remaining_quantity', 'offer_percentage', 'multi_options' )->where('deleted' , 0)->where('hidden' , 0)->where('store_id' , $storeId)->simplePaginate(10);
                }else {
                    $products = Product::select('id', 'title_ar as title' , 'category_id', 'final_price', 'price_before_offer', 'offer' , 'type', 'remaining_quantity', 'offer_percentage', 'multi_options' )->where('deleted' , 0)->where('hidden' , 0)->where('store_id' , $storeId)->simplePaginate(10);
                }
            }else {
                if($request->lang == 'en'){
                    $products = Product::select('id', 'title_en as title' , 'category_id', 'final_price', 'price_before_offer', 'offer' , 'type', 'remaining_quantity', 'offer_percentage', 'multi_options' )->where('deleted' , 0)->where('hidden' , 0)->where('store_id' , $storeId)->where('category_id' , $categoryId)->simplePaginate(10);
                    
                }else {
                    $products = Product::select('id', 'title_ar as title' , 'category_id', 'final_price', 'price_before_offer', 'offer' , 'type', 'remaining_quantity', 'offer_percentage', 'multi_options' )->where('deleted' , 0)->where('hidden' , 0)->where('store_id' , $storeId)->where('category_id' , $categoryId)->simplePaginate(10);
                    
                }
            }
            
        }else {
            if (!empty($categoryId) && !empty($size) && !empty($type) && !empty($from) && !empty($to)) {
                if($request->lang == 'en'){
                    $products = Product::select('id', 'title_en as title' , 'category_id', 'final_price', 'price_before_offer', 'offer' , 'type', 'remaining_quantity', 'offer_percentage', 'multi_options' )->where('deleted', 0)->where('hidden', 0)->where('category_id', $categoryId)->where('store_id' , $storeId)->where('type', $type)->whereBetween('final_price', [$from , $to])->whereHas('multiOptions', function($q) use ($size) {
                        $q->where('multi_option_value_id', $size);
                    })->simplePaginate(10);
                }else {
                    $products = Product::select('id', 'title_ar as title' , 'category_id', 'final_price', 'price_before_offer', 'offer' , 'type', 'remaining_quantity', 'offer_percentage', 'multi_options' )->where('deleted', 0)->where('hidden', 0)->where('category_id', $categoryId)->where('store_id' , $storeId)->where('type', $type)->whereBetween('final_price', [$from , $to])->whereHas('multiOptions', function($q) use ($size) {
                        $q->where('multi_option_value_id', $size);
                    })->simplePaginate(10);
                }
            }else if (!empty($categoryId) && !empty($size) && !empty($type)) {
                if($request->lang == 'en'){
                    $products = Product::select('id', 'title_en as title' , 'category_id', 'final_price', 'price_before_offer', 'offer' , 'type', 'remaining_quantity', 'offer_percentage', 'multi_options' )->where('deleted', 0)->where('hidden', 0)->where('category_id', $categoryId)->where('store_id' , $storeId)->where('type', $type)->whereHas('multiOptions', function($q) use ($size) {
                        $q->where('multi_option_value_id', $size);
                    })->simplePaginate(10);
                }else {
                    $products = Product::select('id', 'title_ar as title' , 'category_id', 'final_price', 'price_before_offer', 'offer' , 'type', 'remaining_quantity', 'offer_percentage', 'multi_options' )->where('deleted', 0)->where('hidden', 0)->where('category_id', $categoryId)->where('store_id' , $storeId)->where('type', $type)->whereHas('multiOptions', function($q) use ($size) {
                        $q->where('multi_option_value_id', $size);
                    })->simplePaginate(10);
                }
            }else if (!empty($categoryId) && !empty($size)) {
                if($request->lang == 'en'){
                    $products = Product::select('id', 'title_en as title' , 'category_id', 'final_price', 'price_before_offer', 'offer' , 'type', 'remaining_quantity', 'offer_percentage', 'multi_options' )->where('deleted', 0)->where('hidden', 0)->where('category_id', $categoryId)->where('store_id' , $storeId)->whereHas('multiOptions', function($q) use ($size) {
                        $q->where('multi_option_value_id', $size);
                    })->simplePaginate(10);
                }else {
                    $products = Product::select('id', 'title_ar as title' , 'category_id', 'final_price', 'price_before_offer', 'offer' , 'type', 'remaining_quantity', 'offer_percentage', 'multi_options' )->where('deleted', 0)->where('hidden', 0)->where('category_id', $categoryId)->where('store_id' , $storeId)->whereHas('multiOptions', function($q) use ($size) {
                        $q->where('multi_option_value_id', $size);
                    })->simplePaginate(10);
                }
            }else if(!empty($size)) {
                if($request->lang == 'en'){
                    $products = Product::select('id', 'title_en as title' , 'category_id', 'final_price', 'price_before_offer', 'offer' , 'type', 'remaining_quantity', 'offer_percentage', 'multi_options' )->where('deleted', 0)->where('hidden', 0)->where('store_id' , $storeId)->whereHas('multiOptions', function($q) use ($size) {
                        $q->where('multi_option_value_id', $size);
                    })->simplePaginate(10);
                }else {
                    $products = Product::select('id', 'title_ar as title' , 'category_id', 'final_price', 'price_before_offer', 'offer' , 'type', 'remaining_quantity', 'offer_percentage', 'multi_options' )->where('deleted', 0)->where('hidden', 0)->where('store_id' , $storeId)->whereHas('multiOptions', function($q) use ($size) {
                        $q->where('multi_option_value_id', $size);
                    })->simplePaginate(10);
                }
            }
        }


        $products->makeHidden(['multiOptions', 'mainImage']);
            
        if (count($products) > 0) {
            for ($i = 0; $i < count($products); $i ++) {
                $products[$i]['image'] = "";
                if (!empty($products[$i]->mainImage)) {
                    $products[$i]['image'] = $products[$i]->mainImage['image'];
                }
                if ($products[$i]['multi_options'] == 1) {
                    if (count($products[$i]['multiOptions']) > 0) {
                        $products[$i]['final_price'] = $products[$i]['multiOptions'][0]['final_price'];
                        $products[$i]['price_before_offer'] = $products[$i]['multiOptions'][0]['price_before_offer'];
                    }
                }
                unset($products[$i]['multi_options']);
            }
        }

        if (count($data['categories']) > 0) {
            for ($n = 0; $n < count($data['categories']); $n ++) {
                $data['categories'][$n]['selected'] = false;
                if ($data['categories'][$n]['id'] == $categoryId) {
                    $data['categories'][$n]['selected'] = true;
                }
            }
        }
       
        
        $data['store_id'] = $storeId;
    
        $data['products'] = $products;
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

}