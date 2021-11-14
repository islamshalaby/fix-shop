<?php

namespace App\Http\Controllers\Dashboard;

use App\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use Illuminate\Support\Facades\DB;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Option;
use App\ProductType;
use App\Product;
use App\OptionValue;
use App\ProductImage;
use App\ProductProperty;

class ProductController extends Controller
{
    // get products
    public function getProducts(Request $request) {
        $validator = Validator::make($request->all(), [
            'section' => 'required',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة'  , null , $request->lang);
            return response()->json($response , 406);
        }

        $data['section'] = $request->section;

        if ($request->lang == 'en') {
            $types = ProductType::select('id', 'type_en as title')->get();
            $data['types'] = [];
            for ($p = 0; $p < count($types); $p ++) {
                $single = (object)[
                    "id" => $types[$p]['id'],
                    "title" => $types[$p]['title'],
                    "selected" => false
                ];

                array_push($data['types'], $single);
            }

            $zero = (object)[
                "id" => 0,
                "title" => "الكل",
                "selected" => false
            ];
            array_unshift($data['types'], $zero);
            
            if ($request->type == 0) {
                $query = Product::where('deleted', 0)
                ->where('hidden', 0)
                ->where('store_id', Auth::guard('dashboard')->user()->id)
                ->select('id', 'remaining_quantity', 'title_en as title', 'final_price', 'price_before_offer', 'offer', 'barcode', 'stored_number')->with('mainImage');
            }else {
                $query = Product::where('deleted', 0)
                ->where('hidden', 0)
                ->where('store_id', Auth::guard('dashboard')->user()->id)
                ->where('type', $request->type)
                ->select('id', 'remaining_quantity', 'title_en as title', 'final_price', 'price_before_offer', 'offer', 'barcode', 'stored_number')->with('mainImage');
            }

        }else {
            $types = ProductType::select('id', 'type_ar as title')->get();
            $data['types'] = [];
            for ($p = 0; $p < count($types); $p ++) {
                $single = (object)[
                    "id" => $types[$p]['id'],
                    "title" => $types[$p]['title'],
                    "selected" => false
                ];

                array_push($data['types'], $single);
            }
            $zero = (object)[
                "id" => 0,
                "title" => "الكل",
                "selected" => false
            ];
            array_unshift($data['types'], $zero);
            if ($request->type == 0) {
                $query = Product::where('deleted', 0)
                ->where('hidden', 0)
                ->where('store_id', Auth::guard('dashboard')->user()->id)
                ->select('id', 'remaining_quantity', 'title_ar as title', 'final_price', 'price_before_offer', 'offer', 'barcode', 'stored_number')->with('mainImage');
            }else {
                $query = Product::where('deleted', 0)
                ->where('hidden', 0)
                ->where('store_id', Auth::guard('dashboard')->user()->id)
                ->where('type', $request->type)
                ->select('id', 'remaining_quantity', 'title_ar as title', 'final_price', 'price_before_offer', 'offer', 'barcode', 'stored_number')->with('mainImage');
            }
            
        }
        
        for($i = 0; $i < count($data['types']); $i ++) {
            $data['types'][$i]->selected = false;
            if ($request->type == $data['types'][$i]->id) {
                $data['types'][$i]->selected = true;
            }
        }

        
        
        if ($request->section == 0) {
            $data['products'] = $query->simplePaginate(16);
            $data['products_number'] = $data['products']->count('id');
        }else if($request->section == 1) {
            $data['products'] = $query->where('reviewed', 1)->simplePaginate(16);
            $data['products_number'] = $data['products']->count('id');
        } else if($request->section == 2) {
            $data['products'] = $query->where('remaining_quantity', 0)->simplePaginate(16);
            $data['products_number'] = $data['products']->count('id');
        }else if($request->section == 3) {
            $data['products'] = $query->where('remaining_quantity', '<', 10)->simplePaginate(16);
            $data['products_number'] = $data['products']->count('id');
        }
        

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // add product
    public function addProduct(Request $request) {
        $post = $request->all();
        $validator = Validator::make($request->all(), [
            'barcode' => 'unique:products,barcode|max:255|nullable',
            'title_en' => 'required',
            'description_en' => 'required',
            'title_ar' => 'required',
            'description_ar' => 'required',
            'category_id' => 'required',
            'type' => 'required',
            'total_quatity' => 'required',
            'remaining_quantity' => 'required',
            'final_price' => 'required',
            'main_image' => 'required'
        ]);

        if (!$request->title_en) {
            $response = APIHelpers::createApiResponse(true , 406 , 'English Title is Required Field' , 'اسم المنتج بالإنجليزية حقل مطلوب'  , null , $request->lang);
            return response()->json($response , 406);
        }

        if (!$request->title_ar) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Arabic Title is Required Field' , 'اسم المنتج بالعربية حقل مطلوب'  , null , $request->lang);
            return response()->json($response , 406);
        }

        if (!$request->description_en) {
            $response = APIHelpers::createApiResponse(true , 406 , 'English Description is Required Field' , 'الوصف بالإنجليزية حقل مطلوب'  , null , $request->lang);
            return response()->json($response , 406);
        }

        if (!$request->description_ar) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Arabic Description is Required Field' , 'الوصف بالعربية حقل مطلوب'  , null , $request->lang);
            return response()->json($response , 406);
        }

        if (!$request->category_id) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Category is Required Field' , 'القسم حقل مطلوب'  , null , $request->lang);
            return response()->json($response , 406);
        }

        if (!$request->type) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Type is Required Field' , 'النوع حقل مطلوب'  , null , $request->lang);
            return response()->json($response , 406);
        }

        if (!$request->total_quatity) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Total Quantity is Required Field' , 'إجمالى الكمية حقل مطلوب'  , null , $request->lang);
            return response()->json($response , 406);
        }

        if (!$request->remaining_quantity) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Remaining Quantity is Required Field' , 'الكمية المتبقية حقل مطلوب'  , null , $request->lang);
            return response()->json($response , 406);
        }

        if (!$request->final_price) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Price is Required Field' , 'السعر حقل مطلوب'  , null , $request->lang);
            return response()->json($response , 406);
        }

        if (!$request->main_image) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Main Image is Required Field' , 'الصورة الرئيسية حقل مطلوب'  , null , $request->lang);
            return response()->json($response , 406);
        }

        $product = Product::where('barcode', $request->barcode)->where('barcode', '!=', '')->select("id")->first();
        if ($product) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Barcode must be Unique' , 'الباركود يجب ان لا يتكرر'  , null , $request->lang);
            return response()->json($response , 406);
        }

        // if ($validator->fails()) {
        //     $response = APIHelpers::createApiResponse(true , 406 , 'title_en & description_en & title_ar & description_ar & title_ar & category_id & type & total_quatity & remaining_quantity & final_price & main_image Required Fields' , 'title_en & description_en & title_ar & description_ar & title_ar & category_id & type & total_quatity & remaining_quantity & final_price & main_image Required Fields'  , null , $request->lang);
        //     return response()->json($response , 406);
        // }

        if ($request->total_quatity < $request->remaining_quantity) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Total Quantity Must Be >= Remaining Quantity' , 'إجمالى الكمية يجب ان يكون أكبر من أو يساوى الكمية المتبقية'  , null , $request->lang);
            return response()->json($response , 406);
        }

        if (isset($post['discount']) && !empty($post['discount']) && $post['discount'] != 0) {
            $post['offer'] = 1;
            $post['offer_percentage'] = $request->discount;
            $discountValue = $post['final_price'] * ($request->discount / 100);
            $post['price_before_offer'] = $discountValue + $post['final_price'];
        }else {
            $post['offer'] = 0;
            $post['offer_percentage'] = 0;
            $post['price_before_offer'] = 0;
        }
        $post['store_id'] = Auth::guard('dashboard')->user()->id;
        $product = Product::create($post);

        $mainImage = $request->main_image;
        Cloudder::upload($mainImage, null);
        $front_imageereturned = Cloudder::getResult();
        $front_image_id = $front_imageereturned['public_id'];
        $front_image_format = $front_imageereturned['format'];    
        $front_image_new_name = $front_image_id.'.'.$front_image_format;
        $postMainImage['image'] = $front_image_new_name;
        $postMainImage['product_id'] = $product->id;
        $postMainImage['main'] = 1;
        ProductImage::create($postMainImage);
        // dd($request->option_id);
        if (isset($request->option_id) 
        && count($request->option_id) > 0 
        && isset($request->property_value_id) 
        && count($request->property_value_id) > 0
        && count($request->option_id) == count($request->property_value_id)) {
            
            for ($i = 0; $i < count($request->option_id); $i ++) {
                $post_option['product_id'] = $product->id;
                $post_option['option_id'] = $request->option_id[$i];
                $post_option['value_id'] = $request->property_value_id[$i];
                ProductProperty::create($post_option);
            }
        }


        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , ["product_id" => $product->id] , $request->lang);
        return response()->json($response , 200);
    }

    // update product
    public function updateProduct(Request $request, Product $product) {

        if ($product->store_id != Auth::guard('dashboard')->user()->id) {
            $response = APIHelpers::createApiResponse(true , 406 , 'this user has no access to this product' , 'هذا المستخدم ليس له صلة بهذا المنتج'  , null , $request->lang);
            return response()->json($response , 406);
        }
        $post = $request->all();
        $validator = Validator::make($request->all(), [
            'barcode' => 'unique:products,barcode,' . $product->id . '|max:255|nullable',
            'title_en' => 'filled',
            'title_ar' => 'filled',
            'description_ar' => 'filled',
            'description_en' => 'filled',
            'category_id' => 'filled',
            'type' => 'filled',
            'total_quatity' => 'filled',
            'remaining_quantity' => 'filled',
            'final_price' => 'filled'
        ]);
        
        if ($request->main_image) {
            $allImages = ProductImage::where('product_id', $product->id)->get();
            if (count($allImages) > 0) {
                for ($s =0; $s < count($allImages); $s ++) {
                    $allImages[$s]['main'] = 0;
                    $allImages[$s]->save();
                }
            }
            $mainImage = $request->main_image;
            Cloudder::upload($mainImage, null);
            $front_imageereturned = Cloudder::getResult();
            $front_image_id = $front_imageereturned['public_id'];
            $front_image_format = $front_imageereturned['format'];    
            $front_image_new_name = $front_image_id.'.'.$front_image_format;
            $postMainImage['image'] = $front_image_new_name;
            $postMainImage['product_id'] = $product->id;
            $postMainImage['main'] = 1;
            ProductImage::create($postMainImage);
        }

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة'  , null , $request->lang);
            return response()->json($response , 406);
        }

        if (isset($post['discount']) && !empty($post['discount']) && $post['discount'] != 0) {
            $post['offer'] = 1;
            $post['offer_percentage'] = $request->discount;
            $discountValue = $post['final_price'] * ($request->discount / 100);
            $post['price_before_offer'] = $discountValue + $post['final_price'];
        }else {
            $post['offer'] = 0;
            $post['offer_percentage'] = 0;
            $post['price_before_offer'] = 0;
        }
        $product->update($post);

        if (isset($request->option_id) 
        && count($request->option_id) > 0 
        && isset($request->property_value_id) 
        && count($request->property_value_id) > 0
        && count($request->option_id) == count($request->property_value_id)) {
            if (count($product->productProperties) > 0) {
                $product->productProperties()->delete();
            }
            for ($i = 0; $i < count($request->option_id); $i ++) {
                $post_option['product_id'] = $product->id;
                $post_option['option_id'] = $request->option_id[$i];
                $post_option['value_id'] = $request->property_value_id[$i];
                ProductProperty::create($post_option);
            }
        }

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $product , $request->lang);
        return response()->json($response , 200);
    }

    // primary image
    public function primaryImage(Request $request) {
        $validator = Validator::make($request->all(), [
            'image_id' => 'required|exists:product_images,id'
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة'  , null , $request->lang);
            return response()->json($response , 406);
        }
        $image = ProductImage::find($request->image_id);
        if ($image->product->store_id != Auth::guard('dashboard')->user()->id) {
            $response = APIHelpers::createApiResponse(true , 406 , 'this user has no access to this product' , 'هذا المستخدم ليس له صلة بهذا المنتج'  , null , $request->lang);
            return response()->json($response , 406);
        }
        $images = ProductImage::where('product_id', $image['product_id'])->select('id', 'main', 'image')->get()->makeVisible('id');

        for ($i = 0; $i < count($images); $i ++) {
            if ($images[$i]['id'] == $request->image_id) {
                $images[$i]->update(['main' => 1]);
            }else {
                $images[$i]->update(['main' => 0]);
            }
        }

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $images , $request->lang);
        return response()->json($response , 200);
    }

    // get product
    public function getProduct(Request $request, Product $product) {
        if ($product->store_id != Auth::guard('dashboard')->user()->id) {
            $response = APIHelpers::createApiResponse(true , 406 , 'this user has no access to this product' , 'هذا المستخدم ليس له صلة بهذا المنتج'  , null , $request->lang);
            return response()->json($response , 406);
        }
        
        $data['product'] = $product->where('id', $product->id)->select('id', 'title_en', 'title_ar', 'category_id', 'description_en', 'description_ar', 'offer_percentage', 'price_before_offer', 'final_price', 'offer', 'total_quatity', 'remaining_quantity', 'type', 'stored_number', 'barcode')->first();
        if ($request->lang == 'en') {
            $data['product']['types'] = ProductType::orderBy('id', 'desc')->select('id', 'type_en as type')->get();
            $data['product']['categories'] = Category::where('deleted', 0)->select('id', 'title_en as title')->get();
            for ($k = 0; $k < count($data['product']['types']); $k ++) {
                $data['product']['types'][$k]['selected'] = false;
                if ($data['product']['type'] == $data['product']['types'][$k]['id']) {
                    $data['product']['types'][$k]['selected'] = true;
                }
            }
            for ($m = 0; $m < count($data['product']['categories']); $m ++) {
                $data['product']['categories'][$m]['selected'] = false;
                if ($data['product']['category_id'] == $data['product']['categories'][$m]['id']) {
                    $data['product']['categories'][$m]['selected'] = true;
                }
            }
            $props = $product->category->optionsEn;
            
            $prodProps = ProductProperty::where('product_id', $product->id)->get();
           
            for ($i = 0; $i < count($props); $i ++) {
                $props[$i]['value_id'] = 0;
                if (count($prodProps) > 0) {
                    for ($g = 0; $g < count($prodProps); $g ++) {
                        if ($prodProps[$g]['option_id'] == $props[$i]['option_id']) {
                            $props[$i]['value_id'] = $prodProps[$g]['value_id'];
                        }
                    }
                }
                $props[$i]['values'] = OptionValue::where('option_id', $props[$i]['option_id'])->select('id as value_id', 'value_' . $request->lang . ' as value')->get();
                for ($n = 0; $n < count($props[$i]['values']); $n ++) {
                    $props[$i]['values'][$n]['selected'] = false;
                }
            }
            $data['product']['properties'] = $props;
            for ($i = 0; $i < count($data['product']['properties']); $i ++) {
                $data['product']['properties'][$i]['values'] = OptionValue::where('option_id', $data['product']['properties'][$i]['option_id'])->select('id as value_id', 'value_en as value')->get();
                $val = ['value' => ''];
                if ($data['product']['properties'][$i]['value_id'] != 0) {
                    $val = OptionValue::where('id', $data['product']['properties'][$i]['value_id'])->select('value_en as value')->first();
                }
                $data['product']['properties'][$i]['value_name'] = $val['value'];
                for ($n = 0; $n < count($data['product']['properties'][$i]['values']); $n ++) {
                    $data['product']['properties'][$i]['values'][$n]['selected'] = false;
                    if ($data['product']['properties'][$i]['value_id'] == $data['product']['properties'][$i]['values'][$n]['value_id']) {
                        $data['product']['properties'][$i]['values'][$n]['selected'] = true;
                    }
                }
            }
        }else {
            $data['product']['types'] = ProductType::orderBy('id', 'desc')->select('id', 'type_ar as type')->get();
            $data['product']['categories'] = Category::where('deleted', 0)->select('id', 'title_ar as title')->get();
            for ($k = 0; $k < count($data['product']['types']); $k ++) {
                $data['product']['types'][$k]['selected'] = false;
                if ($data['product']['type'] == $data['product']['types'][$k]['id']) {
                    $data['product']['types'][$k]['selected'] = true;
                }
            }
            for ($m = 0; $m < count($data['product']['categories']); $m ++) {
                $data['product']['categories'][$m]['selected'] = false;
                if ($data['product']['category_id'] == $data['product']['categories'][$m]['id']) {
                    $data['product']['categories'][$m]['selected'] = true;
                }
            }
            
            $props = $product->category->optionsAr;
            
            $prodProps = ProductProperty::where('product_id', $product->id)->get();
           
            for ($i = 0; $i < count($props); $i ++) {
                $props[$i]['value_id'] = 0;
                if (count($prodProps) > 0) {
                    for ($g = 0; $g < count($prodProps); $g ++) {
                        if ($prodProps[$g]['option_id'] == $props[$i]['option_id']) {
                            $props[$i]['value_id'] = $prodProps[$g]['value_id'];
                        }
                    }
                }
                $props[$i]['values'] = OptionValue::where('option_id', $props[$i]['option_id'])->select('id as value_id', 'value_' . $request->lang . ' as value')->get();
                for ($n = 0; $n < count($props[$i]['values']); $n ++) {
                    $props[$i]['values'][$n]['selected'] = false;
                }
            }
            $data['product']['properties'] = $props;
            
            // dd($data['product']['properties']);
            for ($i = 0; $i < count($data['product']['properties']); $i ++) {
                $data['product']['properties'][$i]['values'] = OptionValue::where('option_id', $data['product']['properties'][$i]['option_id'])->select('id as value_id', 'value_ar as value')->get();
                $val = ['value' => ''];
                if ($data['product']['properties'][$i]['value_id'] != 0) {
                    $val = OptionValue::where('id', $data['product']['properties'][$i]['value_id'])->select('value_ar as value')->first();
                }
                $data['product']['properties'][$i]['value_name'] = $val['value'];
                for ($n = 0; $n < count($data['product']['properties'][$i]['values']); $n ++) {
                    $data['product']['properties'][$i]['values'][$n]['selected'] = false;
                    if ($data['product']['properties'][$i]['value_id'] == $data['product']['properties'][$i]['values'][$n]['value_id']) {
                        $data['product']['properties'][$i]['values'][$n]['selected'] = true;
                    }
                }
            }
        }
        $type = ProductType::where('id', $data['product']['type'])->select('type_' . $request->lang . ' as title')->first();
        $data['product']['type_name'] = $type['title'];
        $category = Category::where('id', $product['category_id'])->select('title_' . $request->lang . ' as title')->first();
        $data['product']['category_name'] = $category['title'];
        
        $data['product']['images'] = $product->images->makeVisible('id');
        

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // delete product image
    public function deleteImage(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'image_id' => 'required|exists:product_images,id'
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة'  , null , $request->lang);
            return response()->json($response , 406);
        }

        $image = ProductImage::find($request->image_id);
        if ($image->product->store_id != Auth::guard('dashboard')->user()->id) {
            $response = APIHelpers::createApiResponse(true , 406 , 'this user has no access to this product' , 'هذا المستخدم ليس له صلة بهذا المنتج'  , null , $request->lang);
            return response()->json($response , 406);
        }
        $publicId = substr($image, 0 ,strrpos($image, "."));    
        Cloudder::delete($publicId);
        $image->delete();
        $productImageArray = ProductImage::where('product_id', $image['product_id'])->pluck('main')->toArray();
        if (count($productImageArray) > 0 && !in_array(1, $productImageArray)) {
            $latestImages = ProductImage::where('product_id', $image['product_id'])->first();
            $latestImages->update(['main' => 1]);
        }

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , (object)[] , $request->lang);
        return response()->json($response , 200);
    }

    // add amount
    public function addAmount(Request $request, Product $product) {
        if ($product->store_id != Auth::guard('dashboard')->user()->id) {
            $response = APIHelpers::createApiResponse(true , 406 , 'this user has no access to this product' , 'هذا المستخدم ليس له صلة بهذا المنتج'  , null , $request->lang);
            return response()->json($response , 406);
        }
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0|not_in:0'
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة'  , null , $request->lang);
            return response()->json($response , 406);
        }
        $amount = $request->amount;
        $newAmount = $product->remaining_quantity + $amount;
        $product->update(['remaining_quantity' => $newAmount]);

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $product , $request->lang);
        return response()->json($response , 200);
    }

    // delete product
    public function deleteProduct(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id'
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة'  , null , $request->lang);
            return response()->json($response , 406);
        }

        $product = Product::where('id', $request->product_id)->select('store_id', 'deleted', 'id')->first();
        if ($product->store_id != Auth::guard('dashboard')->user()->id) {
            $response = APIHelpers::createApiResponse(true , 406 , 'this user has no access to this product' , 'هذا المستخدم ليس له صلة بهذا المنتج'  , null , $request->lang);
            return response()->json($response , 406);
        }

        $product->update(['deleted' => 1]);

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , "" , $request->lang);
        return response()->json($response , 200);
    }

    // upload images
    public function uploadImages(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'images' => 'required'
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة'  , null , $request->lang);
            return response()->json($response , 406);
        }

        for ($i = 0; $i < count($request->images); $i ++) {
            $image = $request->images[$i];
            Cloudder::upload($image, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];    
            $image_new_name = $image_id.'.'.$image_format;
            $product_image = new ProductImage();
            $product_image->image = $image_new_name;
            $product_image->product_id = $request->product_id;
            $product_image->save();
        }

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , "" , $request->lang);
        return response()->json($response , 200);
    }

    // get categories
    public function getCategories(Request $request) {
        $data = Category::where('deleted', 0)->select('id', 'title_' . $request->lang . ' as title')->orderBy('id', 'desc')->get();

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // get types
    public function getTypes(Request $request) {
        $data = ProductType::orderBy('id', 'desc')->select('id', 'type_' . $request->lang . ' as title')->get();

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // get options
    public function getOptions(Request $request, Category $category) {
        if ($request->lang == 'en') {
            $data['properties'] = $category->optionsEn;
        }else {
            $data['properties'] = $category->optionsAr;
        }

        for ($i = 0; $i < count($data['properties']); $i ++) {
            $data['properties'][$i]['value_id'] = 0;
            $data['properties'][$i]['values'] = OptionValue::where('option_id', $data['properties'][$i]['option_id'])->select('id as value_id', 'value_' . $request->lang . ' as value')->get();
            for ($n = 0; $n < count($data['properties'][$i]['values']); $n ++) {
                $data['properties'][$i]['values'][$n]['selected'] = false;
            }
        }
        

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);

    }

    
}