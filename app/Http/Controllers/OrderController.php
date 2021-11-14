<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\UserAddress;
use App\Visitor;
use App\Product;
use App\Cart;
use App\Order;
use App\OrderItem;
use App\DeliveryArea;
use Illuminate\Support\Facades\Validator;
use App\Helpers\APIHelpers;
use App\Shop;
use App\ProductMultiOption;
use App\MainOrder;
use App\Wallet;
use App\Setting;
use App\Retrieve;
use App\Currency;
use App\OrderSerial;
use App\StoreNotification;
use App\ProductVip;
use App\Serial;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api' , ['except' => ['execute', 'pay_sucess', 'pay_error', 'excute_pay']]);
    }

    public function create(Request $request) {
        if (!$request->header('uniqueid') || empty($request->header('uniqueid'))) {
            $response = APIHelpers::createApiResponse(true , 406 , 'unique_id required header' , 'unique_id required header' , null , $request->lang);
            return response()->json($response , 406);
        }

        $visitor = Visitor::where('unique_id', $request->header('uniqueid'))->select('id', 'country_code')->first();
        
        if ($visitor && !empty($visitor->country_code)) {
            $currency = $visitor->country->currency_en;
            $currency = Currency::where('from', "usd")->where('to', 'kwd')->first();
            if (isset($currency['id'])) {
                if (!$currency->updated_at->isToday()) {
                    $result = APIHelpers::converCurruncy2("usd", 'kwd');
                    if(isset($result['value'])){
                        $currency->update(['value' => $result['value'], 'updated_at' => Carbon::now()]);
                        $currency = Currency::where('from', "usd")->where('to', 'kwd')->first();
                    }
                }
            }else {
                $result = APIHelpers::converCurruncy2("usd", 'kwd');
                
                if(isset($result['value']) && !$currency){
                    $result = APIHelpers::converCurruncy2("usd", 'kwd');
                    $currency = Currency::create(['value' => $result['value'], "from" => "usd", "to" => 'kwd']);
                }
            }
            $cart = Cart::where('visitor_id', $visitor->id)->get();
            
            if (count($cart) > 0) {
                for ($i = 0; $i < count($cart); $i++) {
                    $product = Product::where('deleted', 0)->where('hidden', 0)->where('id', $cart[$i]->product_id)->first();
                    if ($cart[$i]->product->remaining_quantity < $cart[$i]['count'] && count($product->serials) < $cart[$i]['count']) {
                        $response = APIHelpers::createApiResponse(true , 406 , 'Remaining Quantity is not enough' , 'الكمية المتبقية غير كافية' , null , $request->lang);
                        return response()->json($response , 406);
                    }
                }
            }
            $total = 0.000;

            if (count($cart) > 0) {
                for ($i = 0; $i < count($cart); $i ++) {
                    $product = Product::where('deleted', 0)->where('hidden', 0)->where('id', $cart[$i]->product_id)->first();
                    $price = $product['final_price'];
                    if (!empty(auth()->user()->vip_id)) {
                        $productVip = ProductVip::where('vip_id', auth()->user()->vip_id)->where('product_id', $product['id'])->first();
                        if ($productVip) {
                            $priceOffer = $price * ($productVip->percentage / 100);
                            $price = ($price  * $cart[$i]['count']) - ($priceOffer  * $cart[$i]['count']);
                        }
                    }else {
                        $price = $price * $cart[$i]['count'];
                    }
                    $total = $price + $total;
                }
            }

            $root_url = $request->root();
            $user = auth()->user();
            
            $path='https://apitest.myfatoorah.com/v2/SendPayment';
            $token="bearer rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
            $headers = array(
                'Authorization:' .$token,
                'Content-Type:application/json'
            );
            $price = $total;
            
            $request->payment_method = 1;
            $call_back_url = $root_url . "/api/excute?user_id=".$user->id."&unique_id=".$request->header('uniqueid')."&payment_method=".$request->payment_method."&price=" . number_format((float)$price, 3, '.', '');
			

            $error_url = $root_url . "/api/pay/error";
            $fields =array(
                "CustomerName" => $user->name,
                "NotificationOption" => "LNK",
                "InvoiceValue" => $price,
                "CallBackUrl" => $call_back_url,
                "ErrorUrl" => $error_url,
                "Language" => "AR",
                "CustomerEmail" => $user->email,
                "CustomerMobile" => substr($user->phone, 4),
                "DisplayCurrencyIso" => "USD"
            ); 

            $payload =json_encode($fields);
            $curl_session =curl_init();
            curl_setopt($curl_session,CURLOPT_URL, $path);
            curl_setopt($curl_session,CURLOPT_POST, true);
            curl_setopt($curl_session,CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl_session,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl_session,CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_session,CURLOPT_IPRESOLVE, CURLOPT_IPRESOLVE);
            curl_setopt($curl_session,CURLOPT_POSTFIELDS, $payload);
            
            $result=curl_exec($curl_session);
            
            curl_close($curl_session);
            $result = json_decode($result);
            
            $data['url'] = $result->Data->InvoiceURL;

            $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
            return response()->json($response , 200);
        }else {
            $response = APIHelpers::createApiResponse(true , 406 , 'Visitor is not exist or code country is empty' , 'Visitor is not exist or code country is empty' , null , $request->lang);
            return response()->json($response , 406);
        }
        
    }
    
    public function execute(Request $request){
        $visitor = Visitor::where('unique_id', $request->unique_id)->select('id', 'country_code')->first();
        if ($visitor && !empty($visitor->country_code)) {
            $currency = $visitor->country->currency_en;
            $currency = Currency::where('from', "usd")->where('to', 'kwd')->first();
            if (isset($currency['id'])) {
                if (!$currency->updated_at->isToday()) {
                    $result = APIHelpers::converCurruncy2("usd", 'kwd');
                    if(isset($result['value'])){
                        $currency->update(['value' => $result['value'], 'updated_at' => Carbon::now()]);
                        $currency = Currency::where('from', "usd")->where('to', 'kwd')->first();
                    }
                }
                
            }else {
                $result = APIHelpers::converCurruncy2("usd", 'kwd');
                // dd($result);
                if(isset($result['value']) && !$currency){
                    $result = APIHelpers::converCurruncy2("usd", 'kwd');
                    $currency = Currency::create(['value' => $result['value'], "from" => "usd", "to" => 'kwd']);
                }
            }
            $cart = Cart::where('visitor_id', $visitor->id)->get();
            
            $now = Carbon::now();
            $lastOrder = Order::orderBy('id', 'desc')->first();
            $orderNumber = $now->year . $now->month . $now->day . "01";
            if ($lastOrder) {
                $subSOrder = (int)$lastOrder->id + 1;
                if ($subSOrder < 9) {
                    $subSOrder = '0' . $subSOrder;
                }
                $orderNumber = $now->year . $now->month . $now->day . $subSOrder;
            }
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $request->user_id,
                'payment_method' => 1,
                'country_code' => $visitor->country_code,
                'status' => 1
            ]);
            $user = User::where('id', $request->user_id)->first();
            
            if (count($cart) > 0) {
                for ($i = 0; $i < count($cart); $i++) {
                    $product = Product::where('deleted', 0)->where('hidden', 0)->where('id', $cart[$i]['product_id'])->first();
                    $price = $product['final_price'];
                    $priceBOffer = $product['price_before_offer'];
                    if (!empty($user->vip_id)) {
                        $productVip = ProductVip::where('vip_id', $user->vip_id)->where('product_id', $product['id'])->first();
                        if ($productVip) {
                            $priceOffer = $price * ($productVip->percentage / 100);
                            $price = $price - $priceOffer;
                            $priceBOffer = $product['final_price'];
                            $product['offer_percentage'] = $productVip->percentage;
                        }
                    }
                    $oItem = OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'price_before_offer' => number_format((float)$priceBOffer, 3, '.', ''),
                        'final_price' => number_format((float)$price, 3, '.', ''),
                        'discount' => $product['offer_percentage'],
                        'count' => $cart[$i]['count'],
                        'status' => 1
                    ]);
                    // get valid product serials
                    $path='http://athath-ads.tk/api/serials/valid';
                    $fields =array(
                        'product_id' => $product->id
                    );
                    $result = APIHelpers::fetchApi($path, $fields, 'json', 'post');
                    
                    $serials = $result->data;
                    if (count($serials) > 0) {
                        for ($s = 0; $s < $cart[$i]['count']; $s ++) {
                            $oItemSerials = OrderSerial::create([
                                'order_id' => $oItem->id,
                                'serial_id' => $serials[$s]->id,
                                'serial' => $serials[$s]->serial,
                                'serial_number' => $serials[$s]->serial_number,
                                'valid_to' => $serials[$s]->valid_to,
                                'product_id' => $product->id
                            ]);

                            // update sold serials
                            $path='http://athath-ads.tk/api/serials/update-serial-bought';
                            $fields =array(
                                'serial_id' => $serials[$s]->id
                            );
                            APIHelpers::fetchApi($path, $fields, 'json', 'post');

                            $product->remaining_quantity = $product->remaining_quantity - 1;
                            $product->sold_count = $product->sold_count + 1;
                            $product->save();
                        }
                    }
                    $cart[$i]->delete();
                }
            }
            if (count($order->oItems) > 0) {
                $order->update([
                    'subtotal_price' => $request->price,
                    'total_price' => $request->price
                ]);
            }
            $pluckSerials = OrderItem::where('order_id', $order->id)->pluck('id')->toArray();
            $orderSerials['serials'] = OrderSerial::whereIn('order_id', $pluckSerials)->get();
            $orderSerials['order'] = $order;
            Mail::send('order_details_mail', $orderSerials, function($message) use ($user) {
                $message->to($user->email, $user->name)->subject
                   ('Order Details');
                $message->from('modaapp9@gmail.com','Al thuraya');
             });
            
            
            return redirect('api/pay/success');
        }else {
            $response = APIHelpers::createApiResponse(true , 406 , 'Visitor is not exist or code country is empty' , 'Visitor is not exist or code country is empty' , null , $request->lang);
            return response()->json($response , 406);
        }
    }

    public function directBuy(Request $request) {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required'
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة'  , null , $request->lang);
            return response()->json($response , 406);
        }

        if (!$request->header('uniqueid') || empty($request->header('uniqueid'))) {
            $response = APIHelpers::createApiResponse(true , 406 , 'unique_id required header' , 'unique_id required header' , null , $request->lang);
            return response()->json($response , 406);
        }

        $visitor = Visitor::where('unique_id', $request->header('uniqueid'))->select('country_code')->first();
        if ($visitor && !empty($visitor->country_code)) {
            $currency = $visitor->country->currency_en;
            $toCurr = trim(strtolower($currency));
            if ($toCurr == "usd") {
                $currency = ["value" => 1];
            }else {
                $currency = Currency::where('from', "usd")->where('to', $toCurr)->first();
            }
            
            if (isset($currency['id'])) {
                if (!$currency->updated_at->isToday()) {
                    $result = APIHelpers::converCurruncy2("usd", $toCurr);
                    if(isset($result['value'])){
                        $currency->update(['value' => $result['value'], 'updated_at' => Carbon::now()]);
                        $currency = Currency::where('from', "usd")->where('to', $toCurr)->first();
                    }
                }
            }else {
                if(!$currency){
                    $result = APIHelpers::converCurruncy2("usd", $toCurr);
                    $currency = Currency::create(['value' => $result['value'], "from" => "usd", "to" => $toCurr]);
                }
            }
            $product = Product::where('id', $request->product_id)->first();
            $price = $product['final_price'];
            
            if (!empty(auth()->user()->vip_id)) {
                $productVip = ProductVip::where('vip_id', auth()->user()->vip_id)->where('product_id', $product['id'])->first();
                if ($productVip) {
                    $priceOffer = $price * ($productVip->percentage / 100);
                    $price = $price - $priceOffer;
                }
            }
            
            $root_url = $request->root();
            $user = auth()->user();
            
            $path='https://apitest.myfatoorah.com/v2/SendPayment';
            $token="bearer rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
            $headers = array(
                'Authorization:' .$token,
                'Content-Type:application/json'
            );
            
            $request->payment_method = 1;
            $call_back_url = $root_url."/api/excute_pay?user_id=".$user->id."&unique_id=".$request->header('uniqueid')."&payment_method=1&product_id=".$request->product_id . "&price=" . number_format((float)$price, 3, '.', '');

            $error_url = $root_url."/api/pay/error";
            $fields =array(
                "CustomerName" => $user->name,
                "NotificationOption" => "LNK",
                "InvoiceValue" => $price,
                "CallBackUrl" => $call_back_url,
                "ErrorUrl" => $error_url,
                "Language" => "AR",
                "CustomerEmail" => $user->email,
                "CustomerMobile" => substr($user->phone, 4),
                "DisplayCurrencyIso" => "USD"
            ); 

            $payload =json_encode($fields);
            $curl_session =curl_init();
            curl_setopt($curl_session,CURLOPT_URL, $path);
            curl_setopt($curl_session,CURLOPT_POST, true);
            curl_setopt($curl_session,CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl_session,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl_session,CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_session,CURLOPT_IPRESOLVE, CURLOPT_IPRESOLVE);
            curl_setopt($curl_session,CURLOPT_POSTFIELDS, $payload);
            
            $result=curl_exec($curl_session);
            curl_close($curl_session);
            $result = json_decode($result);

            $data['url'] = $result->Data->InvoiceURL;

            $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
            return response()->json($response , 200);
        }else {
            $response = APIHelpers::createApiResponse(true , 406 , 'Visitor is not exist or code country is empty' , 'Visitor is not exist or code country is empty' , null , $request->lang);
            return response()->json($response , 406);
        }
        
    }

    public function excute_pay(Request $request){
        $user = User::find($request->user_id);
        $visitor  = Visitor::where('unique_id' , $request->unique_id)->first();
        
        $now = Carbon::now();
        $lastOrder = Order::orderBy('id', 'desc')->first();
        $orderNumber = $now->year . $now->month . $now->day . "01";
        if ($lastOrder) {
            $subSOrder = (int)$lastOrder->id + 1;
            if ($subSOrder < 9) {
                $subSOrder = '0' . $subSOrder;
            }
            $orderNumber = $now->year . $now->month . $now->day . $subSOrder;
        }
        $product = Product::where('id', $request->product_id)->first();
        $price = $product['final_price'];
        $priceBOffer = $product['price_before_offer'];
        
        if (!empty($user->vip_id)) {
            $productVip = ProductVip::where('vip_id', $user->vip_id)->where('product_id', $product['id'])->first();
            if ($productVip) {
                $priceOffer = $price * ($productVip->percentage / 100);
                $price = $price - $priceOffer;
                $priceBOffer = $product['final_price'];
                $product['offer_percentage'] = $productVip->percentage;
            }
        }
        
        
        $order = Order::create([
            'user_id' => $request->user_id,
            'payment_method' => 1,
            'subtotal_price' => number_format((float)$request->price, 3, '.', ''),
            'total_price' => number_format((float)$request->price, 3, '.', ''),
            'status' => 1,
            'order_number' => $orderNumber
        ]);

        $oItem = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $request->product_id,
            'price_before_offer' => number_format((float)$priceBOffer, 3, '.', ''),
            'final_price' => number_format((float)$price, 3, '.', ''),
            'discount' => $product['offer_percentage'],
            'count' => 1,
            'status' => 1
        ]);

        // get valid product serials
        $path='http://athath-ads.tk/api/serials/valid';
        $fields =array(
            'product_id' => $product->id
        );
        $result = APIHelpers::fetchApi($path, $fields, 'json', 'post');
        $serials = $result->data;

        $oItemSerials = OrderSerial::create([
            'order_id' => $oItem->id,
            'serial_id' => $serials[0]->id,
            'serial' => $serials[0]->serial,
            'serial_number' => $serials[0]->serial_number,
            'valid_to' => $serials[0]->valid_to,
            'product_id' => $product->id
        ]);

        // update sold serials
        $path='http://athath-ads.tk/api/serials/update-serial-bought';
        $fields =array(
            'serial_id' => $serials[0]->id
        );
        APIHelpers::fetchApi($path, $fields, 'json', 'post');

        $product->remaining_quantity = $product->remaining_quantity - 1;
        $product->sold_count = $product->sold_count + 1;
        $product->save();

        $pluckSerials = OrderItem::where('order_id', $order->id)->pluck('id')->toArray();
        $orderSerials['serials'] = OrderSerial::whereIn('order_id', $pluckSerials)->get();
        $orderSerials['order'] = $order;
        Mail::send('order_details_mail', $orderSerials, function($message) use ($user) {
            $message->to($user->email, $user->name)->subject
                ('Order Details');
            $message->from('modaapp9@gmail.com','Al thuraya');
        });

        return redirect('api/pay/success'); 
    }

    public function getorders(Request $request){
        $user_id = auth()->user()->id;
        
        $orders = $this->getMyOrders($user_id, $request);
        
        
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $orders , $request->lang);
        return response()->json($response , 200);
    }

    public function pay_sucess(){
        return "Please wait ...";
    }

    public function pay_error(){
        return "Please wait ...";
    }
    
    public function orderdetails(Request $request){
        if (!$request->header('uniqueid') || empty($request->header('uniqueid'))) {
            $response = APIHelpers::createApiResponse(true , 406 , 'uniqueid required header' , 'uniqueid required header' , null , $request->lang);
            return response()->json($response , 406);
        }
        $visitor = Visitor::where('unique_id', $request->header('uniqueid'))->select('country_code')->first();
        if ($visitor && !empty($visitor->country_code)) {
            $currency = $visitor->country->currency_en;
            $toCurr = trim(strtolower($currency));
            if ($toCurr == "usd") {
                $currency = ["value" => 1];
            }else {
                $currency = Currency::where('from', "usd")->where('to', $toCurr)->first();
            }
            if (isset($currency['id'])) {
                if (!$currency->updated_at->isToday()) {
                    $result = APIHelpers::converCurruncy2("usd", $toCurr);
                    if(isset($result['value'])){
                        $currency->update(['value' => $result['value'], 'updated_at' => Carbon::now()]);
                        $currency = Currency::where('from', "usd")->where('to', $toCurr)->first();
                    }
                }
                
            }else {
                
                if(!$currency){
                    $result = APIHelpers::converCurruncy2("usd", $toCurr);
                    $currency = Currency::create(['value' => $result['value'], "from" => "usd", "to" => $toCurr]);
                }
            }
            $currencySympol = $visitor->country->currency_en;
            if ($request->lang == 'ar') {
                $currencySympol = $visitor->country->currency_ar;
            }
            $order_id = $request->id;
            $order = $this->getOrderDetail($order_id, $request);
            $order->total_price = $order->total_price * $currency['value'] . " " . $currencySympol;
            $order->total_price = number_format((float)$order->total_price, 3, '.', '') . " " . $currencySympol;

            if (count($order->items) > 0) {
                $order->items->map(function($row) use ($currency, $currencySympol){
                    
                    $price = $row->final_price * $currency['value'];
                    $priceBOffer = $row->price_before_offer * $currency['value'];
                    $data['offer'] = 1;
                    $data['offer_percentage'] = $row->discount;
                    

                    $row->final_price = number_format((float)$price, 3, '.', '') . " " . $currencySympol;
                    $row->price_before_offer = number_format((float)$priceBOffer, 3, '.', '') . " " . $currencySympol;

                    return $row;
                });
            }

            $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $order , $request->lang);
            return response()->json($response , 200);
        }else {
            $response = APIHelpers::createApiResponse(true , 406 , 'Visitor is not exist or code country is empty' , 'Visitor is not exist or code country is empty' , null , $request->lang);
            return response()->json($response , 406);
        }
    }

    public function cancel_item(Request $request, OrderItem $item) {
        if ($item->status != 1) {
            $response = APIHelpers::createApiResponse(true , 406 , 'cant cancel this item' , 'لا يمكن الغاء هذا العنصر' , null , $request->lang);
            return response()->json($response , 406);
        }
        $dcost = $item->order['delivery_cost'];
        
        $item->update([
            'status' => 4,// canceled
            'final_price' => '0.000',
            'price_before_offer' => '0.000'
        ]);

        $item->product->remaining_quantity = $item->product->remaining_quantity + $item->count;
        $item->product->sold_count = $item->product->sold_count - $item->count;
        $item->product->save();
        
        $orderStatus = $item->order->status;
        $dSubCostT = $item->order['delivery_cost'];
        $dSubCost = 0;
        
        if (count($item->order->canceledItems) == count($item->order->oItems)) {
            $orderStatus = 4;
            $dSubCost = $item->order->delivery_cost;
            $totalP = '0.000';
        }else {
            $totalP = $item->order->oItems->sum('final_price') + $item->order->delivery_cost;
        }
        $dMainC = $item->order->main->delivery_cost - $dSubCost;
        $item->order->main->update(['delivery_cost' => number_format((float)$dMainC, 3, '.', '')]);
        $subTP = $item->order->oItems->sum('final_price');
        $subTP = number_format((float)$subTP, 3, '.', '');
        $totalP = number_format((float)$totalP, 3, '.', '');
        $convertedDSub = $item->order->delivery_cost - $dSubCost;
        $convertedDSub = number_format((float)$convertedDSub, 3, '.', '');
        
        $item->order->update([
            'subtotal_price' => $subTP,
            'total_price' => $totalP,
            'delivery_cost' => $convertedDSub,
            'status' => $orderStatus
        ]);
        
        $cancelStatus = $item->order->main->status;
        $dMainCost = 0;
        if (count($item->order->main->canceledOrders) == count($item->order->main->orders)) {
            $cancelStatus = 4;
            $dMainCost = $item->order->main->delivery_cost;
        }
        $subTotal = $item->order->main->orders->sum('subtotal_price');
        $subTotal = number_format((float)$subTotal, 3, '.', '');
        $totalPrice = $item->order->main->orders->sum('total_price');
        $totalPrice = number_format((float)$totalPrice, 3, '.', '');
        
        $item->order->main->update([
            'subtotal_price' => $subTotal,
            'total_price' => $totalPrice,
            'status' => $cancelStatus
        ]);

        if ($item->order->main['payment_method'] == 3 || $item->order->main['payment_method'] == 1) {
            $walletUser = Wallet::where('user_id', $item->order->main['user_id'])->first();
            
            if (count($item->order->canceledItems) != count($item->order->oItems)) {
                $dcost = 0;
            }
            
            if ($walletUser) {
                $walletUser['balance'] = $walletUser['balance'] + ($item['count'] * $item->product['final_price']) + $dcost;
                $walletUser->save();
            }else {
                Wallet::create([
                    'user_id' => $item->order->main['user_id'],
                    'balance' => ($item['count'] * $item->product['final_price']) + $dcost
                ]);
            }
        }
        

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , '' , $request->lang);
        return response()->json($response , 200);
    }

    public function retrieve_item(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'reason' => 'required'
        ]);
        

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة' , null , $request->lang);
            return response()->json($response , 406);
        }

        $item = OrderItem::select('id', 'status', 'product_id', 'order_id')->where('id', $request->item_id)->first();
        // dd($item);
        if ($item->status != 3) {
            $response = APIHelpers::createApiResponse(true , 406 , 'cant retrieve this item' , 'لا يمكن إسترجاع هذا العنصر' , null , $request->lang);
            return response()->json($response , 406);
        }
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
        $refund_number = substr(str_shuffle(uniqid() . $str) , -9);
        $post = $request->all();
        $post['user_id'] = auth()->user()->id;
        $post['store_id'] = $item->product->store_id;
        $post['refund_number'] = $refund_number;
        Retrieve::create($post);

        if ($request->lang == 'en') {
            $notificationTitle = "Refund Order";
            $notificationBody = auth()->user()->name . " wants to retrieve product";
        }else {
            $notificationTitle = "طلب إسترجاع";
            $notificationBody = auth()->user()->name . " يريد أن يسترجع منتج";
        }

        $notification = new StoreNotification();
        $notification->title = $notificationTitle;
        $notification->body = $notificationBody;
        $notification->store_id = $item->order->store->id;
        $notification->save();
        $order['order_id'] = $item->order->main->id;
        $notificationss = APIHelpers::send_notification($notification->title , $notification->body , null , $order , [$item->order->store->fcm_token]);
        
        $item->update(['status' => 5]);


        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , '' , $request->lang);
        return response()->json($response , 200);
    }

    public function categories(Request $request) {
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
              'deviceId' => '44e18cfe3a4f330dce24c79953065fe6',
              'email' => 'hr@al-thurya.com',
              'password' => 'c6d91cc1451994002053dd1b77a0ec98',
              'securityCode' => '82714d02186c1cdd4c9dc809820c35df',
              'categoryId' => $request->cat_id,
              'langId' => '1'),
            CURLOPT_HTTPHEADER => array(
            //   "Content-Type: application/x-www-form-urlencoded"
            ),
          ));
          
          $response = curl_exec($curl);
          
          curl_close($curl);
          $response = json_decode($response);

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $response , $request->lang);
        return response()->json($response , 200);
    }

}