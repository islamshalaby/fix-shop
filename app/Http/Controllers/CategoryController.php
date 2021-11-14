<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\APIHelpers;
use App\Category;
use App\SubCategory;
use App\SubFiveCategory;
use App\SubFourCategory;
use App\SubThreeCategory;
use App\SubTwoCategory;
use Illuminate\Support\Facades\Session;
use App\Ad;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['getMerchantCategories', 'getCategoriesSlider']]);
    }
	
	public function getCategories(Request $request) {
        Session::put('language',$request->lang);
        $lang = $request->lang;
        $root_url = $request->root();
        // dd($lang);
        $data = Category::where('deleted', 0)->has('products', '>', 0)->select('id', 'title_' . $lang . ' as title', 'image');
        if ($request->show && $request->show != 'all') {
            $data = $data->take(6);
        }
        $data = $data->get()->makeHidden(['subCategories', 'offersProducts', 'offers'])
        ->map(function ($cat) use ($root_url, $request) {
            
            $url['sub_cat'] = $root_url . '/api/categories/'. $cat->id . '/sub-categories/' . $request->lang .'/v1';
            $url['product'] = $root_url . '/api/products/show/en/v1' . '?category_id=' . $cat->id;
            
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

        

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // get subcategories
    public function getSubCategories(Request $request) {
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
        $data['sub_categories'] = SubCategory::where('deleted' , 0)->where('category_id' , $request->category_id)->has('products', '>', 0)->select('id' , 'image' , 'title_en as title', 'category_id')->get()
        ->makeHidden('subCategories')
        ->map(function($sCat) use ($request){
            $sCat->next_level = false;
            $root_url = $request->root();
            $sCat->url = $root_url . '/api/products/show/en/v1?category_id=' . $sCat->category_id . '&sub_category_id=' . $sCat->id;
            if (count($sCat->subCategories) > 0) {
                $hasProducts = false;
                for ($i = 0; $i < count($sCat->subCategories); $i ++) {
                    if (count($sCat->subCategories[$i]->products) > 0) {
                        $hasProducts = true;
                    }
                }

                if ($hasProducts) {
                    $sCat->next_level = true;
                    $sCat->url = $root_url . '/api/categories/' . $sCat->id . '/sub-categories-two/en/v1';
                }
                
            }

            return $sCat;
        });
        
            
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // get subcategories two
    public function getSubTwoCategories(Request $request) {
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
        $data['sub_categories'] = SubTwoCategory::where('deleted' , 0)->where('sub_category_id' , $request->sub_category_id)->has('products', '>', 0)->select('id' , 'image' , 'title_en as title', 'sub_category_id')->get()
        ->makeHidden(['subCategories', 'category'])
        ->map(function($sCat) use ($request){
            $sCat->next_level = false;
            $root_url = $request->root();
            $sCat->url = $root_url . '/api/products/show/en/v1?category_id=' . $sCat->category->category_id . '&sub_category_id=' . $sCat->sub_category_id . '&sub_category_two_id=' . $sCat->id;
            if (count($sCat->subCategories) > 0) {
                $hasProducts = false;
                for ($i = 0; $i < count($sCat->subCategories); $i ++) {
                    if (count($sCat->subCategories[$i]->products) > 0) {
                        $hasProducts = true;
                    }
                }
                if ($hasProducts) {
                    $sCat->next_level = true;
                    $sCat->url = $root_url . '/api/categories/' . $sCat->id . '/sub-categories-three/en/v1';
                }
                
            }

            return $sCat;
        });
        
            
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // get subcategories three
    public function getSubThreeCategories(Request $request) {
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
        $data['sub_categories'] = SubThreeCategory::where('deleted' , 0)->where('sub_category_id' , $request->sub_category_id)->has('products', '>', 0)->select('id' , 'image' , 'title_en as title', 'sub_category_id')->get()
        ->makeHidden(['subCategories', 'category'])
        ->map(function($sCat) use ($request){
            $sCat->next_level = false;
            $root_url = $request->root();
            $sCat->url = $root_url . '/api/products/show/en/v1?category_id=' . $sCat->category->category->category_id . '&sub_category_id=' . $sCat->category->category->id . '&sub_category_two_id=' . $sCat->sub_category_id . '&sub_category_three_id=' . $sCat->id;
            if (count($sCat->subCategories) > 0) {
                $hasProducts = false;
                for ($i = 0; $i < count($sCat->subCategories); $i ++) {
                    if (count($sCat->subCategories[$i]->products) > 0) {
                        $hasProducts = true;
                    }
                }

                if ($hasProducts) {
                    $sCat->next_level = true;
                    $sCat->url = $root_url . '/api/categories/' . $sCat->id . '/sub-categories-four/en/v1';
                }

            }

            return $sCat;
        });
        
            
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // get subcategories four
    public function getSubFourCategories(Request $request) {
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
        $data['sub_categories'] = SubFourCategory::where('deleted' , 0)->where('sub_category_id' , $request->sub_category_id)->has('products', '>', 0)->select('id' , 'image' , 'title_en as title', 'sub_category_id')->get()
        ->makeHidden(['subCategories', 'category'])
        ->map(function($sCat) use ($request){
            $sCat->next_level = false;
            $root_url = $request->root();
            $sCat->url = $root_url . '/api/products/show/en/v1?category_id=' . $sCat->category->category->category->category_id 
            . '&sub_category_id=' . $sCat->category->category->category->id 
            . '&sub_category_two_id=' . $sCat->category->sub_category_id 
            . '&sub_category_three_id=' . $sCat->sub_category_id 
            . '&sub_category_four_id=' . $sCat->id;
            if (count($sCat->subCategories) > 0) {
                $hasProducts = false;
                for ($i = 0; $i < count($sCat->subCategories); $i ++) {
                    if (count($sCat->subCategories[$i]->products) > 0) {
                        $hasProducts = true;
                    }
                }

                if ($hasProducts) {
                    $sCat->next_level = true;
                    $sCat->url = $root_url . '/api/categories/' . $sCat->id . '/sub-categories-five/en/v1';
                }
                
            }

            return $sCat;
        });
        
            
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // get subcategories five
    public function getSubFiveCategories(Request $request) {
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
        $data['sub_categories'] = SubFiveCategory::where('deleted' , 0)->where('sub_category_id' , $request->sub_category_id)->has('products', '>', 0)->select('id' , 'image' , 'title_en as title', 'sub_category_id')->get()
        ->makeHidden(['subCategories', 'category'])
        ->map(function($sCat) use ($request){
            $sCat->next_level = false;
            $root_url = $request->root();
            $sCat->url = $root_url . '/api/products/show/en/v1?category_id=' . $sCat->category->category->category->category->category_id 
            . '&sub_category_id=' . $sCat->category->category->category->category->id 
            . '&sub_category_two_id=' . $sCat->category->category->sub_category_id 
            . '&sub_category_three_id=' . $sCat->category->sub_category_id 
            . '&sub_category_four_id=' . $sCat->sub_category_id
            . '&sub_category_five_id=' . $sCat->id;

            return $sCat;
        });
        
            
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // get categories slider
    public function getCategoriesSlider(Request $request) {
        $ads = $this->getCategorySlider();

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $ads , $request->lang);
        return response()->json($response , 200);
    }

}    