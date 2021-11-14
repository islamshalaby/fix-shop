<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use App\Imports\SerialImport;
use JD\Cloudder\Facades\Cloudder;
use App\Product;
use App\Category;
use App\Option;
use App\Brand;
use App\SubCategory;
use App\ProductImage;
use Cloudinary;
use App\HomeSection;
use App\HomeElement;
use App\Serial;
use App\ControlOffer;
use App\Country;
use App\ProductProperty;
use App\ProductMultiOption;
use App\OptionValue;
use App\ProductCountry;
use App\Shop;
use App\ProductType;
use App\SubFiveCategory;
use App\SubFourCategory;
use App\SubThreeCategory;
use App\SubTwoCategory;
use App\Helpers\APIHelpers;
use App\ProductVip;
use App\Vip;
use Excel;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;

class ProductController extends AdminController{

    // show products
    public function show(Request $request) {
        $data['categories'] = Category::where('deleted', 0)->orderBy('id', 'desc')->get();
        $data['brands'] = Brand::where('deleted', 0)->orderBy('id', 'desc')->get();
        $data['stores'] = Shop::where('status', 1)->orderBy('id', 'desc')->get();
        $data['products'] = Product::where('deleted', 0);
        $data['expire'] = 'no';
        if($request->expire){
            $data['products'] = $data['products']->where('remaining_quantity' , '<' , 10);
            $data['expire'] = 'soon';
        }
        if($request->category){
            $data['products'] = $data['products']->where('category_id' , $request->category);
        }
        if($request->type){
            $data['products'] = $data['products']->where('like_card' , $request->type);
        }
        $data['products'] = $data['products']->orderBy('id' , 'desc')->get();
        
        $data['encoded_products'] = json_encode($data['products']);
        return view('admin.products', ['data' => $data]);
    }

    // fetch category brands
    public function fetch_category_brands(Category $category) {
        $rows = $category->brands;

        $data = json_decode(($rows));

        return response($data, 200);
    }

    // fetch brand sub categories
    public function fetch_brand_sub_categories(Brand $brand) {
        $rows = $brand->subCategories;

        $data = json_decode(($rows));

        return response($data, 200);
    }

    // fetch sub category products
    public function sub_category_products(SubCategory $subCategory) {
        $rows = Product::where('sub_category_id', $subCategory->id)->with('images', 'category')->get();
        $data = json_decode(($rows));

        return response($data, 200);
    }

    // edit get
    public function EditGet(Product $product) {
        $data['product'] = $product;
        $data['barcode'] = uniqid();
        $data['categories'] = Category::where('deleted', 0)->orderBy('id', 'desc')->get();
        $data['category'] = Category::findOrFail($data['product']['category_id']);
        $data['countries'] = Country::orderBy('country_name', 'asc')->get();
        $data['product_countries'] = ProductCountry::where('product_id', $product->id)->pluck('country_id')->toArray();
        $data['product_countries_name'] = json_encode($data['product_countries']);
        $data['vips'] = Vip::orderBy('id', 'desc')->get();
        if (!empty($data['product']['sub_category_id'])) {
            $data['sub_categories'] = SubCategory::where('deleted', 0)->where('category_id', $data['product']['category_id'])->orderBy('id', 'desc')->get();
        }
        
        if (!empty($data['product']['sub_category_two_id'])) {
            $data['sub_two_categories'] = SubTwoCategory::where('deleted', 0)->where('sub_category_id', $data['product']['sub_category_id'])->orderBy('id', 'desc')->get();
        }

        if (!empty($data['product']['sub_category_three_id'])) {
            $data['sub_three_categories'] = SubThreeCategory::where('deleted', 0)->where('sub_category_id', $data['product']['sub_category_two_id'])->orderBy('id', 'desc')->get();
        }

        if (!empty($data['product']['sub_category_four_id'])) {
            $data['sub_four_categories'] = SubFourCategory::where('deleted', 0)->where('sub_category_id', $data['product']['sub_category_three_id'])->orderBy('id', 'desc')->get();
        }

        if (!empty($data['product']['sub_category_five_id'])) {
            $data['sub_five_categories'] = SubFiveCategory::where('sub_category_id', $data['product']['sub_category_four_id'])->where('deleted', 0)->orderBy('id', 'desc')->get();
        }
        $data['options'] = [];
        $data['product_options'] = [];
        $data['Home_sections'] = HomeSection::where('type', 4)->get();
        $data['Home_sections_ids'] = HomeSection::where('type', 4)->pluck('id');
        $data['elements'] = HomeElement::where('element_id', $product->id)->whereIn('home_id', $data['Home_sections_ids'])->pluck('home_id')->toArray();
        $data['property_values'] = $data['product']->values()->select('option_values.id', 'option_values.option_id')->get();
        $data['multi_options'] = $data['product']->multiOptions()->pluck('multi_option_value_id')->toArray();
        $data['encoded_multi_options'] = json_encode($data['multi_options']);
        $data['multi_options_id'] = $data['product']->multiOptions()->pluck('multi_option_id')->toArray();
        $data['encoded_multi_options_id'] = json_encode($data['multi_options_id']);
        
        
        
        return view('admin.product_edit', ['data' => $data]);
    }

    // edit post
    public function EditPost(Request $request, Product $product) {
        $request->validate([
            'barcode' => 'unique:products,barcode,' . $product->id . '|max:255|nullable',
            'stored_number' => 'unique:products,stored_number,' . $product->id . '|max:255|nullable',
            'title_en' => 'required',
            'title_ar' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'category_id' => 'required'
        ]);
        $product_post = $request->except(['images', 'option', 'value_en', 'value_ar', 'home_section', 'option_id', 'property_value_id', 'multi_option_id', 'multi_option_value_id', 'final_price', 'total_amount', 'remaining_amount', 'price_after_discount', 'barcodes', 'stored_numbers']);
        if (empty($product_post['brand_id'])) {
            $product_post['brand_id'] = 0;
        }

        if (isset($request->home_section) && !empty($request->home_section)) {
            $data['Home_sections_ids'] = HomeSection::where('type', 4)->pluck('id')->toArray();
            $data['elements'] = HomeElement::where('element_id', $product->id)->whereIn('home_id', $data['Home_sections_ids'])->select('id')->first();
            if (!empty($data['elements'])) {
                $data['product_element'] = HomeElement::findOrFail($data['elements']['id']);

                $data['product_element']->update(['home_id'=>$request->home_section]);
            }else {
                HomeElement::create(['home_id'=>$request->home_section, 'element_id' => $product->id]);
            }
            
        }
        
        if (isset($product_post['offer'])) {
            $price_before = number_format((double)$product_post['price_before_offer'], 3, '.', '');
            $discount_value = (double)$product_post['offer_percentage'] / 100;
            $price_value = $price_before * $discount_value;
            $final_price = $price_before - $price_value;
            $product_post['final_price'] = number_format((double)$final_price, 3, '.', '');
        }
        if (isset($product_post['offer'])) {
            $product_post['offer'] = 1;
        }else {
            $product_post['offer'] = 0;
            $product_post['offer_percentage'] = 0;
            $product_post['price_before_offer'] = "0.000";
        }
        $product_post['installation_cost'] = number_format((double)$request->installation_cost, 3, '.', '');
        $product_post['sub_category_id'] = 0;
        $product_post['sub_category_two_id'] = 0;
        $product_post['sub_category_three_id'] = 0;
        $product_post['sub_category_four_id'] = 0;
        $product_post['sub_category_five_id'] = 0;
        if ($request->sub_category_id1) {
            $product_post['sub_category_id'] = $request->sub_category_id1;
        }
        if ($request->sub_category_id2) {
            $product_post['sub_category_two_id'] = $request->sub_category_id2;
        }
        if ($request->sub_category_id3) {
            $product_post['sub_category_three_id'] = $request->sub_category_id3;
        }
        if ($request->sub_category_id4) {
            $product_post['sub_category_four_id'] = $request->sub_category_id4;
        }
        if ($request->sub_category_id5) {
            $product_post['sub_category_five_id'] = $request->sub_category_id5;
        }
        $product->update($product_post);
        if ( $images = $request->file('images') ) {
            foreach ($images as $image) {
                $image_name = $image->getRealPath();
                $imagereturned = Cloudinary::upload($image_name);
                $image_id = $imagereturned->getPublicId();
                $image_format = $imagereturned->getExtension();    
                $image_new_name = $image_id.'.'.$image_format;
                ProductImage::create(["image" => $image_new_name, "product_id" => $product->id]);
            }
            $productImages = ProductImage::where('product_id', $product->id)->select('id', 'main')->get();
            for ($t =0; $t < count($productImages); $t ++) {
                if ($t == 0) {
                    $productImages[$t]->update(['main' => 1]);
                }else {
                    $productImages[$t]->update(['main' => 0]);
                }
            }
        }

        if (isset($product->options) && count($product->options) > 0) {
            $product->options()->delete();
        }

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
                if ($request->property_value_id[$i] != "empty") {
                    if ($request->property_value_id[$i] == 0) {
                        $option_val = OptionValue::create([
                            'option_id' => $request->option_id[$i],
                            'value_en' => $request->another_option_en[$i],
                            'value_ar' => $request->another_option_ar[$i]
                        ]);
                        $post_option['value_id'] = $option_val["id"];
                        ProductProperty::create($post_option);
                    }else {
                        $post_option['value_id'] = $request->property_value_id[$i];
                        ProductProperty::create($post_option);
                    }
                }
            }
        }

        if (isset($request->total_amount) && is_array($request->total_amount) && isset($request->multi_option_id) && $request->multi_option_id != "none") {
            if (count($product->multiOptions) > 0) {
                $product->multiOptions()->delete();
            }
            
            for ($n = 0; $n < count($request->total_amount); $n ++) {
                $barcode = "";
                $stored_number = "";

                if (isset($request->barcodes[$n])) {
                    $barcode = $request->barcodes[$n];
                }

                if (isset($request->stored_numbers[$n])) {
                    $stored_number = $request->stored_numbers[$n];
                }
                if (isset($request->offer)) {
                    $final_price = $request->price_after_discount[$n];
                    $before_discount = $request->final_price[$n];
                }else {
                    $final_price = $request->final_price[$n];
                    $before_discount = $request->final_price[$n];
                }
                ProductMultiOption::create([
                    'product_id' => $product->id,
                    'multi_option_id' => $request->multi_option_id,
                    'multi_option_value_id' => $request->multi_option_value_id[$n],
                    'final_price' => $final_price,
                    'price_before_offer' => $before_discount,
                    'barcode' => $barcode,
                    'stored_number' => $stored_number
                ]);
            }

            if (isset($request->offer)) {
                $product->update([
                    'offer' => 1,
                    'offer_percentage' => (double)$request->offer_percentage,
                    'multi_options' => 1,
                    'final_price' => $request->price_after_discount[0],
                    'price_before_offer' => $request->final_price[0]
                ]);
            }else {
                $selected_prod_data['offer'] = 0;
                $selected_prod_data['offer_percentage'] = 0;
                $selected_prod_data['price_before_offer'] = 0;
                $product->update([
                    'offer' => 0,
                    'offer_percentage' => 0,
                    'multi_options' => 1,
                    'final_price' => $request->final_price[0],
                    'price_before_offer' => $request->final_price[0]
                ]);
            }
        }else {
            if (count($product->multiOptions) > 0) {
                $product->multiOptions()->delete();
            }
            if (isset($request->offer)) {
                $price_before = (double)$request->price_before_offer;
                $discount_value = (double)$request->offer_percentage / 100;
                $price_value = $price_before * $discount_value;
                $selected_prod_data['final_price'] = $price_before - $price_value;
            }
    
            if (!isset($request->offer)) {
                $selected_prod_data['final_price'] = $request->price_before_offer;
            }
    
            if (isset($request->offer)) {
                $selected_prod_data['offer'] = 1;
                $selected_prod_data['offer_percentage'] = (double)$request->offer_percentage;
            }else {
                $selected_prod_data['offer'] = 0;
                $selected_prod_data['offer_percentage'] = 0;
                $selected_prod_data['price_before_offer'] = $request->price_before_offer;
            }
            $selected_prod_data['multi_options'] = 0;
            $product->update($selected_prod_data);
        }

        return redirect()->route('products.index');
        
    }

    // fetch category products
    public function fetch_category_products(Category $category) {
        $rows = Product::where('category_id', $category->id)->with('images', 'category', 'store', 'multiOptionss', 'mainImage')->get();
        $data = json_decode(($rows));
        
        return response($data, 200);
    }

    // fetch brand products
    public function fetch_brand_products(Brand $brand) {
        $rows = Product::where('brand_id', $brand->id)->with('images', 'category')->get();
        $data = json_decode(($rows));


        return response($data, 200);
    }

    // delete product image
    public function delete_product_image(ProductImage $productImage) {
        $image = $productImage->image;
        $publicId = substr($image, 0 ,strrpos($image, "."));    
        Cloudinary::destroy($publicId);
        $productImage->delete();

        return redirect()->back();
    }

    // details
    public function details(Product $product) {
        $data['product'] = $product;

        return view('admin.product_details', ['data' => $data]);
    }

    // delete
    public function delete(Product $product) {
        
        $product->update(['deleted' => 1]);
        $control_offer = ControlOffer::where('offer_id', $product->id)->get();
        if (!empty($control_offer)) {
            for ($n = 0; $n < count($control_offer); $n ++) {
                $control_offer[$n]->delete();
            }
        }
        
        $home_section = HomeSection::where('type', 4)->pluck('id')->toArray();
        $home_element = HomeElement::whereIn('home_id', $home_section)->where('element_id', $product->id)->get();
        if (!empty($home_element)) {
            for ($i =0; $i < count($home_element); $i ++) {
                $home_element[$i]->delete();
            }
            
        }

        return redirect()->back();
    }

    // fetch category options
    public function fetch_category_options(Category $category) {
        $rows = $category->optionsWithValues;
        $data = json_decode(($rows));

        return response($data, 200);
    }

    // fetch sub category multi options
    public function fetch_sub_category_multi_options(Category $category) {
        $rows = $category->multiOptionsWithValues;
        $data = json_decode(($rows));

        return response($data, 200);
    }

    // product search
    public function product_search(Request $request) {
        $data['categories'] = Category::where('deleted', 0)->orderBy('id', 'desc')->get();
        if (isset($request->name)) {
            $data['products'] = Product::with('images')->where('title_en', 'like', '%' . $request->name . '%')
                                ->orWhere('title_ar', 'like', '%' . $request->name . '%')->get();
            return view('admin.searched_products', ['data' => $data]);
        }else {
            return view('admin.product_search', ['data' => $data]);
        }
    }

    // update quantity
    public function update_quantity(Request $request, Product $product) {
        $total_quatity = (int)$request->remaining_quantity + (int)$product->total_quatity;
        $remaining_quantity = (int)$request->remaining_quantity + (int)$product->remaining_quantity;
        $product->update(['total_quatity' => $total_quatity, 'remaining_quantity' => $remaining_quantity]);

        return redirect()->back();
    }

    // update quantity
    public function update_quantity_m_option(Request $request, ProductMultiOption $option) {
        $product = Product::find($option->product_id);
        $product->update([
            'total_quatity' => (int)$request->remaining_quantity + (int)$product->total_quatity,
            'remaining_quantity' => (int)$request->remaining_quantity + (int)$product->remaining_quantity
            ]);
        
        $total_quatity = (int)$request->remaining_quantity + (int)$option->total_quatity;
        $remaining_quantity = (int)$request->remaining_quantity + (int)$option->remaining_quantity;
        $option->update(['total_quatity' => $total_quatity, 'remaining_quantity' => $remaining_quantity]);

        return redirect()->back();
    }

    // add get
    public function addGet(Request $request) {
        $data['categories'] = Category::where('deleted', 0)->orderBy('id', 'desc')->get();
        $data['types'] = ProductType::orderBy('id', 'desc')->get();
        $data['Home_sections'] = HomeSection::where('type', 4)->get();
        $data['countries'] = Country::orderBy('country_name', 'asc')->get();
        $data['vips'] = Vip::orderBy('id', 'desc')->get();
        $data['stores'] = Shop::get();
        $data['barcode'] = uniqid();

        if (isset($request->cat)) {
            $data['cat'] = Category::findOrFail($request->cat);
        }

        return view('admin.product_form', ['data' => $data]);
    }

    // add post
    public function addPost(Request $request) {
        $request->validate([
            'barcode' => 'unique:products,barcode|max:255|nullable',
            'stored_number' => 'unique:products,stored_number|max:255|nullable',
            'title_en' => 'required',
            'title_ar' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'category_id' => 'required'
        ]);
        
        $product_post = $request->except(['images', 'option', 'value_en', 'value_ar', 'home_section', 'option_id', 'property_value_id', 'multi_option_id', 'multi_option_value_id', 'total_quatity', 'remaining_quantity', 'final_price', 'total_amount', 'remaining_amount', 'price_after_discount', 'barcodes', 'stored_numbers']);
        if ($request->sub_category_id1) {
            $product_post['sub_category_id'] = $request->sub_category_id1;
        }
        if ($request->sub_category_id2) {
            $product_post['sub_category_two_id'] = $request->sub_category_id2;
        }
        if ($request->sub_category_id3) {
            $product_post['sub_category_three_id'] = $request->sub_category_id3;
        }
        if ($request->sub_category_id4) {
            $product_post['sub_category_four_id'] = $request->sub_category_id4;
        }
        if ($request->sub_category_id5) {
            $product_post['sub_category_five_id'] = $request->sub_category_id5;
        }
        $product_post['reviewed'] = 1;
        
        $createdProduct = Product::create($product_post);

        if (isset($request->home_section)) {
            HomeElement::create(['home_id' => $request->home_section, 'element_id' => $createdProduct['id']]);
        }

        if ( $images = $request->file('images') ) {
            foreach ($images as $image) {
                $image_name = $image->getRealPath();
                $imagereturned = Cloudinary::upload($image_name);
                $image_id = $imagereturned->getPublicId();
                $image_format = $imagereturned->getExtension();   
                $image_new_name = $image_id.'.'.$image_format;
                ProductImage::create(["image" => $image_new_name, "product_id" => $createdProduct['id']]);
            }

            $productImages = ProductImage::where('product_id', $createdProduct['id'])->select('id', 'main')->get();
            for ($t =0; $t < count($productImages); $t ++) {
                if ($t == 0) {
                    $productImages[$t]->update(['main' => 1]);
                }else {
                    $productImages[$t]->update(['main' => 0]);
                }
            }
        }

        if (isset($request->option_id) 
        && count($request->option_id) > 0 
        && isset($request->property_value_id) 
        && count($request->property_value_id) > 0
        && count($request->option_id) == count($request->property_value_id)) {
            for ($i = 0; $i < count($request->option_id); $i ++) {
                $post_option['product_id'] = $createdProduct['id'];
                $post_option['option_id'] = $request->option_id[$i];
                if ($request->property_value_id[$i] != "empty") {
                    if ($request->property_value_id[$i] == 0) {
                        $option_val = OptionValue::create([
                            'option_id' => $request->option_id[$i],
                            'value_en' => $request->another_option_en[$i],
                            'value_ar' => $request->another_option_ar[$i]
                        ]);
                        $post_option['value_id'] = $option_val["id"];
                        ProductProperty::create($post_option);
                    }else {
                        $post_option['value_id'] = $request->property_value_id[$i];
                        ProductProperty::create($post_option);
                    }
                }
            }
        }

        $selected_product = Product::where('id', $createdProduct['id'])->first();
        
        $vat_value = (double)$request->vat_value;
        if (isset($request->offer)) {
            $price_before = number_format((double)$request->price_before_offer, 3, '.', '');
            $discount_value = (double)$request->offer_percentage / 100;
            $price_value = $price_before * $discount_value;
            $final_price = $price_before - $price_value;
            $selected_prod_data['final_price'] = number_format($final_price, 3, '.', '');
        }

        if (!isset($request->offer)) {
            $selected_prod_data['final_price'] = number_format((double)$request->price_before_offer, 3, '.', '');
        }

        if (isset($request->offer)) {
            $selected_prod_data['offer'] = 1;
            $selected_prod_data['offer_percentage'] = (double)$request->offer_percentage;
        }else {
            $selected_prod_data['offer'] = 0;
            $selected_prod_data['offer_percentage'] = 0;
            $selected_prod_data['price_before_offer'] = number_format((double)$request->price_before_offer, 3, '.', '');
        }
        $selected_prod_data['installation_cost'] = number_format((double)$request->installation_cost, 3, '.', '');
        $selected_prod_data['total_quatity'] = $request->total_quatity;
        $selected_prod_data['remaining_quantity'] = $request->remaining_quantity;
        $selected_product->update($selected_prod_data);
        

        return redirect()->route('products.index')
                ->with('success', __('messages.product_added_success'));
    }

    // get products by subcat
    public function get_product_by_sub_cat(Request $request) {
        $data['products'] = Product::with('images')->where('deleted' , 0)->where('remaining_quantity' , '<' , 10)->where('category_id', $request->cat)->get();
        $data['cat'] = $request->cat;

        return view('admin.searched_products', ['data' => $data]);
    }

    // fetch sub categories by category
    public function fetch_sub_categories_by_category(Category $category) {
        $rows = SubCategory::where('deleted', 0)->where('category_id', $category->id)->get();

        $data = json_decode($rows);
        return response($data, 200);
    }

    // fetch sub categories any level
    public function fetchSubCategoriesAnyLevels($id, $number) {
        $data = [];
        switch ($number) {
            case 1:
              $row = Category::where('id', $id)->first();
              break;
            case 2:
                $row = SubCategory::where('id', $id)->first();
              break;
            case 3:
                $row = SubTwoCategory::where('id', $id)->first();
              break;
            case 4:
                $row = SubThreeCategory::where('id', $id)->first();
            break;
            case 5:
                $row = SubFourCategory::where('id', $id)->first();
            break;
          }
        
        if ($row && $row->subCategories) {
            $data = $row->subCategories;
        }

        $data = json_decode($data);
        return response($data, 200);
    }

    // fetch products by store
    public function fetch_products_by_store(Shop $store) {
        $rows = Product::where('deleted', 0)->where('hidden', 0)->where('store_id', $store->id)->with('images', 'category', 'store', 'multiOptionss')->get();
        // dd($rows);
        $data = json_decode($rows);
        return response($data, 200);
    }

    // visibility status product
    public function visibility_status_product(Product $product, $status) {
        $product->update(['hidden' => $status]);
        if ($status == 1) {
            $control_offer = ControlOffer::where('offer_id', $product->id)->get();
            if (!empty($control_offer)) {
                for ($n = 0; $n < count($control_offer); $n ++) {
                    $control_offer[$n]->delete();
                }
            }
            
            $home_section = HomeSection::where('type', 4)->pluck('id')->toArray();
            $home_element = HomeElement::whereIn('home_id', $home_section)->where('element_id', $product->id)->get();
            if (!empty($home_element)) {
                for ($i =0; $i < count($home_element); $i ++) {
                    $home_element[$i]->delete();
                }
                
            }
        }
        
        

        return redirect()->back();
    }

    public function validate_barcode_unique($type, $text) {
        
        if ($type == 'barcode') {
            $product = ProductMultiOption::where('barcode', $text)->first();
        }else {
            $product = ProductMultiOption::where('stored_number', $text)->first();
        }
        

        if (!empty($product)) {
            return response("0", 200);
        }

        return response("1", 200);
    }

    public function review_product(Product $product, $status) {
        $product->update(['reviewed' => $status]);

        return redirect()->back();
    }

    // add to offers
    public function addToOffers(Request $request) {
        $product = Product::where('id', $request->product_id)->select('id', 'recent_offers', 'choose_for_you')->first();
        // $post = [];
        if ($request->type == 1) {
            $product->recent_offers = 1;
        }

        if ($request->type == 2) {
            $product->choose_for_you = 1;
        }
        
        $product->save();

        if ($request->type == 1) {
            return redirect()->route('products.getOffers', ['recent'])->with('success', __('messages.added_successfully'));
        }else {
            return redirect()->route('products.getOffers', ['choose'])->with('success', __('messages.added_successfully'));
        }
    }

    // remove from offer
    public function removeFromOffers(Request $request) {
        $product = Product::where('id', $request->product_id)->first();

        if ($request->type == 1) {
            $product->recent_offers = 0;
        }

        if ($request->type == 2) {
            $product->choose_for_you = 0;
        }
        
        $product->save();

        if ($request->type == 1) {
            return redirect()->route('products.getOffers', ['recent'])->with('success', __('messages.removed_successfully'));
        }else {
            return redirect()->route('products.getOffers', ['choose'])->with('success', __('messages.removed_successfully'));
        }
        
    }

    // get offers
    public function getOffers(Request $request) {
        $data = Product::where('deleted', 0)->where('recent_offers', 1)->get();

        return view('admin.products_offers', compact(['data']));
    }


    // add - remove offer
    public function addRemoveOffer(Request $request) {
        $product = Product::where('id', $request->id)->select('id', 'recent_offers')->first();
        $product->update(['recent_offers' => $request->action]);

        return redirect()->route('products.getOffers')->with('success', __('messages.updated_successfully'));
    }
    
}