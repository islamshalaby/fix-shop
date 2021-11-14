<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use App\ProductCountry;
use App\Category;
use App\HomeSection;
use App\Vip;
use App\Country;
use App\Product;
use App\ProductVip;
use Carbon\Carbon;
use App\ProductImage;
use App\HomeElement;
use App\Serial;
use App\Transaction;
use Cloudinary;

class LikeCardController extends AdminController{

    // Generate your multidimensional array from the linear array
    public function GenerateNavArray($arr, $parent = 0)
    {
        $pages = Array();
        foreach($arr as $page)
        {
            if($page['categoryParentId'] == $parent)
            {
                $page['sub'] = isset($page['sub']) ? $page['sub'] : $this->GenerateNavArray($arr, $page['id']);
                $pages[] = $page;
            }
        }
        return $pages;
    }

    // loop the multidimensional array recursively to generate the HTML
    public function GenerateNavHTML($nav)
    {
        $html = '';
        foreach($nav as $page)
        {
            $html .= '<ul><li>';
            $html .= '<a class="child-products" href="' . route('likeCard.products', $page['id']) . '">' . $page['categoryName'] . '</a>';
            $html .= $this->GenerateNavHTML($page['childs']);
            $html .= '</li></ul>';
        }
        return $html;
    }

    // get merchant categories
    public function getMerchantCategories(Request $request) {
        $lang = 2;
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://taxes.like4app.com/online/categories",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array(
            'deviceId' => env("LIKECARD_DEVICE_ID"),
            'email' => env("LIKECARD_EMAIL"),
            'password' => env("LIKECARD_PASSWORD"),
            'securityCode' => env("LIKECARD_SECURITY_CODE"),
            'langId' => $lang
        )
        ));

        $response = curl_exec($curl);

        $nav = json_decode($response)->data;
        $nav = json_decode(json_encode($nav), true);
        $navarray = $this->GenerateNavArray($nav);
        $data = $this->GenerateNavHTML($navarray);

        return view('admin.like_card', compact('data'));
    }

    // get like card
    public function getLikeCard() {
        return view('admin.like_card');
    }

    // get like card products
    public function getLikeCardProducts(Request $request) {
        $catId = $request->cat_id;

        $lang = 1;
        // if (App::isLocale('ar')) {
        //     $lang = 2;
        // }
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://taxes.like4app.com/online/products",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array(
            'deviceId' => env("LIKECARD_DEVICE_ID"),
            'email' => env("LIKECARD_EMAIL"),
            'password' => env("LIKECARD_PASSWORD"),
            'securityCode' => env("LIKECARD_SECURITY_CODE"),
            'langId' => $lang,
            'categoryId' => $catId
        )
        ));

        $response = curl_exec($curl);

        $data = json_decode($response);
        

        return view('admin.like_card_products', compact('data'));
    }

    // get product to buy
    public function getProductToBuy(Request $request) {
        $likeProduct = Product::where('product_id', $request->id)->select('id')->first();

        if ($likeProduct) {
            return view('admin.product_exist.blade');
        }else {
            $lang = 2;
        
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://taxes.like4app.com/online/products",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array(
                'deviceId' => env("LIKECARD_DEVICE_ID"),
                'email' => env("LIKECARD_EMAIL"),
                'password' => env("LIKECARD_PASSWORD"),
                'securityCode' => env("LIKECARD_SECURITY_CODE"),
                'langId' => $lang,
                'ids[]' => $request->id
            )
            ));
    
            $response = curl_exec($curl);
    
            $data['product'] = json_decode($response);
            
            $data['categories'] = Category::where('deleted', 0)->orderBy('id', 'desc')->get();
            $data['Home_sections'] = HomeSection::where('type', 4)->get();
            $data['countries'] = Country::orderBy('country_name', 'asc')->get();
            $data['vips'] = Vip::orderBy('id', 'desc')->get();
    
            return view('admin.like_product_details', compact('data'));
        }
        

    }

    // generate like card hash
    public function generateHash($time){
        $email = strtolower(env("LIKECARD_EMAIL"));
        $phone = env('LIKECARD_PHONE');
        $key = env('LIKECARD_HASH_KEY');
        return hash('sha256',$time.$email.$phone.$key);
    }

    // post buy & add product
    public function postBuyAndAddProduct(Request $request) {
        $request->validate([
            'barcode' => 'unique:products,barcode|max:255|nullable',
            'stored_number' => 'unique:products,stored_number|max:255|nullable',
            'title_en' => 'required',
            'title_ar' => 'required',
            'description_ar' => 'required',
            'description_en' => 'required',
            'category_id' => 'required'
        ]);

        // check balance
        $lang = 2;
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://taxes.like4app.com/online/check_balance/",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array(
            'deviceId' => env("LIKECARD_DEVICE_ID"),
            'email' => env("LIKECARD_EMAIL"),
            'password' => env("LIKECARD_PASSWORD"),
            'securityCode' => env("LIKECARD_SECURITY_CODE"),
            'langId' => $lang
        )
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $balance = json_decode($response);
        
        if (($balance && $balance->balance > $request->like_product_price) || $request->product_id == 376) {
            $orderSuccess = false;
            for ($i = 0; $i < $request->amount; $i ++) {
                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => "https://taxes.like4app.com/online/create_order",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => array(
                    'deviceId' => env("LIKECARD_DEVICE_ID"),
                    'email' => env("LIKECARD_EMAIL"),
                    'password' => env("LIKECARD_PASSWORD"),
                    'securityCode' => env("LIKECARD_SECURITY_CODE"),
                    'langId' => $lang,
                    'time' => Carbon::now()->timestamp,
                    'hash' => $this->generateHash(Carbon::now()->timestamp),
                    'referenceId' => env('LIKECARD_HASH_KEY'),
                    'productId' => $request->product_id,
                    'quantity' => 1
                    
                )
                ));
        
                $response = curl_exec($curl);
                curl_close($curl);
                $order = json_decode($response);
                if ($order && $order->response == 1) {
                    $orderSuccess = true;

                    // save serials
                    $path='http://athath-ads.tk/api/serials/likecard-serial';
                    $token='$2y$12$ZtgKLOyfvyXH33JE67Ei0.qupt771t62d21M4/OJumBmsZ1bexxpNo';
                    $headers = array(
                        'Authorization:' .$token,
                        'Content-Type:application/json'
                    );
                    $fields =array(
                        'product_id' => $request->product_id,
                        'serial' => $order->serials[0]->serialCode,
                        'valid_to' => $order->serials[0]->validTo,
                        'serial_number' => $order->serials[0]->serialNumber
                    );
                    $payload =json_encode($fields);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $path);
                    curl_setopt($ch, CURLOPT_POST,1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    
                    $result1=json_decode(curl_exec($ch));
                    curl_close ($ch);
                    
                    // save transaction
                    Transaction::create([
                        'like_order_id' => $order->orderId,
                        'price' => $order->orderPrice,
                        'product' => $order->productName,
                        'like_product_id' => $request->product_id,
                        'date' => $order->orderDate,
                        'like_serial_id' => $order->serials[0]->serialId,
                        'serial_code' => $order->serials[0]->serialCode,
                        'serial_number' => $order->serials[0]->serialNumber,
                        'valid_to' => $order->serials[0]->validTo
                    ]);
                }else {
                    return redirect()->back();
                }
            }

            if ($orderSuccess) {
                $product_post = $request->except(['image', 'option', 'value_en', 'value_ar', 'home_section', 'option_id', 'property_value_id', 'multi_option_id', 'multi_option_value_id', 'total_quatity', 'remaining_quantity', 'final_price', 'total_amount', 'remaining_amount', 'price_after_discount', 'barcodes', 'stored_numbers', 'amount']);
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
                $product_post['hidden'] = 1;
                $product_post['like_card'] = 1;
                
                $createdProduct = Product::create($product_post);


        
                if ( $request->vip_id && count($request->vip_id) > 0 && $request->vip_percentage && !empty($request->vip_percentage[0])) {
                    for ($v = 0; $v < count($request->vip_id); $v ++) {
                        ProductVip::create([
                            "vip_id" => $request->vip_id[$v],
                            "product_id" => $createdProduct->id,
                            "percentage" => $request->vip_percentage[$v]
                        ]);
                    }
                }
        
                if ($request->countries && count($request->countries) > 0) {
                    for ($c = 0; $c < count($request->countries); $c ++) {
                        ProductCountry::create([
                            "product_id" => $createdProduct->id,
                            "country_id" => $request->countries[$c],
                            "price" => $request->country_price[$c]
                        ]);
                    }
                }
        
                if (isset($request->home_section)) {
                    HomeElement::create(['home_id' => $request->home_section, 'element_id' => $createdProduct['id']]);
                }
        
                if ( $images = $request->file('images') ) {
                    // dd($request->file('images') );
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
                }else {
                    // dd($request->image );
                    $image = $request->image;
                    $imagereturned = Cloudinary::upload($image);
                    $front_image_id = $imagereturned->getPublicId();
                    $front_image_format = $imagereturned->getExtension();
                    $front_image_new_name = $front_image_id.'.'.$front_image_format;
                    $image = $front_image_new_name;
                    ProductImage::create(["image" => $image, "product_id" => $createdProduct['id']]);
        
                    $productImages = ProductImage::where('product_id', $createdProduct['id'])->select('id', 'main')->get();
                    for ($t =0; $t < count($productImages); $t ++) {
                        if ($t == 0) {
                            $productImages[$t]->update(['main' => 1]);
                        }else {
                            $productImages[$t]->update(['main' => 0]);
                        }
                    }
                }
                
                $selected_product = Product::where('id', $createdProduct['id'])->first();
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
                $selected_prod_data['total_quatity'] = $request->total_quatity;
                $selected_prod_data['remaining_quantity'] = $request->remaining_quantity;
                $selected_product->update($selected_prod_data);

                // update transaction to product in database
                Transaction::where('like_product_id', $request->product_id)->select('id', 'product_id')->get()
                ->map(function ($row) use ($createdProduct) {
                    $row->product_id = $createdProduct['id'];
                    $row->save();
                });

                // update serials to product in database
                $path2='http://athath-ads.tk/api/serials/update-serial-likecard';
                $fields2 =array(
                    'like_product_id' => $request->product_id,
                    'product_id' => $createdProduct['id']
                );
                $payload2 =json_encode($fields2);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $path2);
                curl_setopt($ch, CURLOPT_POST,1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload2);
                curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                
                $result=json_decode(curl_exec($ch));
                curl_close ($ch);
                $currentProduct = Product::where('id', $createdProduct['id'])->first();
                $currentProduct->remaining_quantity = $result->data->count_valid;
                $currentProduct->total_quatity = $result->data->count_all;
                $currentProduct->save();
            }

            return redirect()->route('products.index')
            ->with('success', __('messages.product_added_success'));
        }else {
            return redirect()->back();
        }
        

        
    }

}