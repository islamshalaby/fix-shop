<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\APIHelpers;
use App\Visitor;
use App\Cart;
use App\Favorite;
use App\Product;
use App\Currency;
use App\Discount;
use App\ProductVip;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class VisitorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['create' , 'add' , 'delete' , 'get' , 'changecount' , 'getcartcount', 'getDiscount']]);
    }

    // create visitor 
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required',
            // 'fcm_token' => "required",
            'type' => 'required', // 1 -> iphone ---- 2 -> android
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة' , null , $request->lang);
            return response()->json($response , 406);
        }

        $last_visitor = Visitor::where('unique_id' , $request->unique_id)->first();
        if($last_visitor){
            if ($request->fcm_token) {
                $last_visitor->fcm_token = $request->fcm_token;
                $last_visitor->save();
            }
            $visitor = $last_visitor;
        }else{
            $visitor = new Visitor();
            $visitor->unique_id = $request->unique_id;
            if ($request->fcm_token) {
                $visitor->fcm_token = $request->fcm_token;
            }
            $visitor->type = $request->type;
            $visitor->save();
        }


        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $visitor , $request->lang);
        return response()->json($response , 200);
    }

    // add to cart
    public function add(Request $request){
        if ($request->lang == 'en') {
            $messages = [
                'unique_id.required' => "Unique id is required field",
                'product_id.required' => "Product id is required field",
                'product_id.exists' => 'Product is not exist'
            ];
        }else {
            $messages = [
                'unique_id.required' => "الرقم التعريفى للجوال حقل مطلوب",
                'product_id.required' => "الرقم التعريفى للمنتج حقل مطلوب",
                'product_id.exists' => 'المنتج غير موجود'
            ];
        }
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required',
            'product_id' => 'required|exists:products,id'
        ], $messages);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , $validator->messages()->first() , $validator->messages()->first() , null , $request->lang);
            return response()->json($response , 406);
        }

        $product = Product::find($request->product_id);
        

        $visitor = Visitor::where('unique_id' , $request->unique_id)->first();
        
        if($visitor){
            
            $cart = Cart::where('visitor_id' , $visitor->id)->where('product_id' , $request->product_id)->first();
           
            
            if($cart){
                if($product->remaining_quantity < $cart->count + 1){
                    $response = APIHelpers::createApiResponse(true , 406 , 'The remaining amount of the product is not enough' , 'الكميه المتبقيه من المنتج غير كافيه'  , null , $request->lang);
                    return response()->json($response , 406);
                }
                $count = $cart->count;
                $cart->count = $count + 1;
                $cart->save();
            }else{
                if($product->remaining_quantity < 1){
                    $response = APIHelpers::createApiResponse(true , 406 , 'The remaining amount of the product is not enough' , 'الكميه المتبقيه من المنتج غير كافيه'  , null , $request->lang);
                    return response()->json($response , 406);
                }
                $cart = new Cart();
                $cart->count = 1;
                $cart->product_id = $request->product_id;
                $cart->visitor_id = $visitor->id;
                $cart->save();
            }
            

            $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $cart , $request->lang);
            return response()->json($response , 200);

        }else{
            $response = APIHelpers::createApiResponse(true , 406 , 'This Unique Id Not Registered' , 'This Unique Id Not Registered' , null , $request->lang);
            return response()->json($response , 406);
        }

    }

    // remove from cart
    public function delete(Request $request){
        if ($request->lang == 'en') {
            $messages = [
                'unique_id.required' => "Unique id is required field",
                'product_id.required' => "Product id is required field"
            ];
        }else {
            $messages = [
                'unique_id.required' => "الرقم التعريفى للجوال حقل مطلوب",
                'product_id.required' => "الرقم التعريفى للمنتج حقل مطلوب"
            ];
        }
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required',
            'product_id' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , $validator->messages()->first() , $validator->messages()->first() , null , $request->lang);
            return response()->json($response , 406);
        }

        $visitor = Visitor::where('unique_id' , $request->unique_id)->first();
        if($visitor){
            
            $cart = Cart::where('product_id' , $request->product_id)->where('visitor_id' , $visitor->id)->first();
            
            $cart->delete();

            $response = APIHelpers::createApiResponse(false , 200 , '' , '' , null , $request->lang);
            return response()->json($response , 200);

        }else{
            $response = APIHelpers::createApiResponse(true , 406 , 'This Unique Id Not Registered' , 'This Unique Id Not Registered' , null , $request->lang);
            return response()->json($response , 406);
        }
    }

    // get cart
    public function get(Request $request){
        if ($request->lang == 'en') {
            $messages = [
                'unique_id.required' => "Unique id is required field"
            ];
        }else {
            $messages = [
                'unique_id.required' => "الرقم التعريفى للجوال حقل مطلوب"
            ];
        }
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , $validator->messages()->first() , $validator->messages()->first() , null , $request->lang);
            return response()->json($response , 406);
        }
        
        $visitor = Visitor::where('unique_id' , $request->unique_id)->first();
        
        if ($visitor) {
            $visitor_id =  $visitor['id'];
            $data['cart'] = [];
            $data['total'] = '0.000';
            $data['subtotal'] = '0.000';
            $data['products_count'] = 0;
            
            $cart = Cart::where('visitor_id' , $visitor_id)->select('product_id as id' , 'count')->get();
            if (count($cart) > 0) {
                for ($i = 0; $i < count($cart); $i ++) {
                    $product = Product::where('id', $cart[$i]['id'])
                    ->select('id', 'title_' . $request->lang . ' as title', 'offer', 'final_price', 'price_before_offer', 'offer_percentage', 'installation_cost')
                    ->first()
                    ->makeHidden(['images', 'installation_cost']);
                    $product['count'] = $cart[$i]['count'];
                    $price = $product['final_price'];
                    $priceBOffer = $product['price_before_offer'];
                    
                    if ($product->main_image) {
                        $product->main_image = $product->main_image->image;
                    }else {
                        if (count($product->images) > 0) {
                            $product->main_image = $product->images[0]->image;
                        }
                    }
                    $user = auth()->user();
                    if($user){
                        $favorite = Favorite::where('user_id' , $user->id)->where('product_id' , $product->id)->first();
                        if($favorite){
                            $product->favorite = true;
                        }else{
                            $product->favorite = false;
                        }
                    }else{
                        $product->favorite = false;
                    }
                    $data['subtotal'] = $data['subtotal'] + ($price * $cart[$i]['count']);
                    $data['total'] = $data['total'] + (($price + $product['installation_cost']) * $cart[$i]['count']);
                    $data['total'] = number_format((float)$data['total'], 3, '.', '');
                    $product['final_price'] = number_format((float)$price, 3, '.', '');
                    $product['final_price'] = number_format((float)$product['final_price'], 3, '.', '');
                    $product['price_before_offer'] = number_format((float)$priceBOffer, 3, '.', '');
                    $product['delivery_installation_cost'] = number_format((float)$product['installation_cost'], 3, '.', '');
                    $data['products_count'] ++;
                    array_push($data['cart'], $product);
                }
            }
            $data['subtotal'] = number_format((float)$data['subtotal'], 3, '.', '');
            $data['total'] = number_format((float)$data['total'], 3, '.', '');
            
            $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
            return response()->json($response , 200);
        }else {
            $response = APIHelpers::createApiResponse(true , 406 , 'Visitor is not exist' , 'Visitor is not exist' , null , $request->lang);
            return response()->json($response , 406);
        }
    }

    // get cart count 
    public function getcartcount(Request $request){
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required',
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة' , null , $request->lang);
            return response()->json($response , 406);
        }

        $visitor = Visitor::where('unique_id' , $request->unique_id)->first();
        if($visitor){
            $visitor_id =  $visitor['id'];
            $cart = Cart::where('visitor_id' , $visitor_id)->select('product_id as id' , 'count')->get();
            $count['count'] = count($cart);

            $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $count , $request->lang);
            return response()->json($response , 200);

        }else{
            $response = APIHelpers::createApiResponse(true , 406 , 'This Unique Id Not Registered' , 'This Unique Id Not Registered' , null , $request->lang);
            return response()->json($response , 406);
        }
    }

    // change count
    public function changecount(Request $request){
        if ($request->lang == 'en') {
            $messages = [
                'unique_id.required' => "Unique id is required field",
                'product_id.required' => "Product id is required field",
                'product_id.exists' => 'Product is not exist',
                'new_count.required' => 'New count is required field'
            ];
        }else {
            $messages = [
                'unique_id.required' => "الرقم التعريفى للجوال حقل مطلوب",
                'product_id.required' => "الرقم التعريفى للمنتج حقل مطلوب",
                'product_id.exists' => 'المنتج غير موجود',
                'new_count.required' => 'العدد الجديد حقل مطلوب'
            ];
        }
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required',
            'product_id' => 'required|exists:products,id',
            'new_count' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , $validator->messages()->first() , $validator->messages()->first()  , null , $request->lang);
            return response()->json($response , 406);
        }

        $visitor = Visitor::where('unique_id' , $request->unique_id)->first();
        
        $product = Product::find($request->product_id);
        if($product->remaining_quantity < $request->new_count){
            $response = APIHelpers::createApiResponse(true , 406 , 'The remaining amount of the product is not enough' , 'الكميه المتبقيه من المنتج غير كافيه'  , null , $request->lang);
            return response()->json($response , 406);
        }
        
        
        if($visitor){
            $cart = Cart::where('product_id' , $request->product_id)->where('visitor_id' , $visitor->id)->first()->makeHidden('option_id');
            
            if (isset($cart->count)) {
                $cart->count = $request->new_count;
                $cart->save();
                $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $cart , $request->lang);
                return response()->json($response , 200);
            }else {
                $response = APIHelpers::createApiResponse(true , 406 , 'This product is not exist in cart' , 'هذا المنتج غير موجود بالعربة' , null , $request->lang);
                return response()->json($response , 406);
            }
        }else{
            $response = APIHelpers::createApiResponse(true , 406 , 'This Unique Id Not Registered' , 'This Unique Id Not Registered' , null , $request->lang);
            return response()->json($response , 406);
        }
    }

    // get discount
    public function getDiscount(Request $request) {
        if ($request->lang == 'en') {
            $messages = [
                'unique_id.required' => "Unique id is required field",
            ];
        }else {
            $messages = [
                'unique_id.required' => "الرقم التعريفى للجوال حقل مطلوب",
            ];
        }
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required',
        ], $messages);

        $visitor = Visitor::select('id')->where('unique_id', $request->unique_id)->first();

        $cartCount = Cart::where('visitor_id', $visitor->id)->sum('count');
        $discount = Discount::where('min_products_number', '<=', $cartCount)->where('max_products_number', '>=', $cartCount)->select('value')->first();
        $percentage = 0;
        if ($discount) {
            $percentage = $discount->value;
        }
        $data = [
            'discount' => $percentage,
            'products_count' => $cartCount
        ];

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }
}