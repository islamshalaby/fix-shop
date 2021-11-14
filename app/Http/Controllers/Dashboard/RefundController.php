<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\MainOrder;
use App\OrderItem;
use App\Product;
use App\Wallet;

class RefundController extends Controller
{
    // get refunds
    public function getRefunds(Request $request) {
        $validator = Validator::make($request->all(), [
            'section' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة'  , null , $request->lang);
            return response()->json($response , 406);
        }


        $query = OrderItem::select('id', 'status', 'order_id', 'created_at', 'refunded_at')->whereHas('order', function($q) {
            $q->where('store_id', Auth::guard('dashboard')->user()->id);
        });
        

        if ($request->section != 0) {
            $data['refunds'] = $query->where('status', $request->section)->get()->makeHidden(['order', 'refund', 'refunded_at', 'created_at', 'order_id']);
        }else {
            $data['refunds'] = $query->whereBetween('status', [5, 8])->get()->makeHidden(['order', 'refund', 'refunded_at', 'created_at', 'order_id']);
        }

        if (count($data['refunds']) > 0) {
            //dd(count($data['refunds']));
            for ($i = 0; $i < count($data['refunds']); $i ++) {
                $refundRequestDate = $data['refunds'][$i]->refund->created_at;
                $data['refunds'][$i]['id'] = $data['refunds'][$i]->order->main->id;
                $data['refunds'][$i]['order_number'] = $data['refunds'][$i]->order->main->main_order_number;
                
                $datetime1 = Carbon::parse($data['refunds'][$i]['created_at']);
                $datetime2 = Carbon::parse(Carbon::now());
                $interval = $datetime1->diff($datetime2);
                $data['refunds'][$i]['refund_request_period'] = $interval;
                $refundDate = "";
                if(!empty($data['refunds'][$i]['refunded_at'])) {
                    $refundDate = $data['refunds'][$i]['refunded_at']->format('d/m/Y');
                }
                $data['refunds'][$i]['received_at'] = $refundDate;
                
                
            }
        }
        $data['count'] = count($data['refunds']);


        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // refund details
    public function refundDetails(Request $request, MainOrder $order) {
        $data['order'] = $order->select('id', 'payment_method', 'subtotal_price', 'delivery_cost', 'total_price')->first()->makeHidden('orders');
        $data['order']['refunds'] = [];
        $totalCost = 0;
        for ($i = 0; $i < count($data['order']->orders); $i ++) {
            $refunds = [];
            $totalCost = $totalCost + $data['order']->orders[$i]->oItemsRefunded->sum('final_price');
            for ($n = 0; $n < count($data['order']->orders[$i]->oItemsRefunded); $n ++) {
                if ($request->lang == 'en') {
                    $product = Product::where('id', $data['order']->orders[$i]->oItemsRefunded[$n]->product_id)->select('id', 'title_en as title', 'store_id', 'offer', 'final_price', 'price_before_offer')->first()->makeHidden(['mainImage', 'store']);
                }else {
                    $product = Product::where('id', $data['order']->orders[$i]->oItemsRefunded[$n]->product_id)->select('id', 'title_ar as title', 'store_id', 'offer', 'final_price', 'price_before_offer')->first()->makeHidden(['mainImage', 'store']);
                }
                $product['image'] = $product->mainImage->image;
                $product['store_name'] = $product->store->name;
                $refundReason = "";
                // var_dump($data['order']->orders[$i]->oItemsRefunded[$n]['id']);
                if ($data['order']->orders[$i]->oItemsRefunded[$n]->refund) {
                    $refundReason = $data['order']->orders[$i]->oItemsRefunded[$n]->refund;
                }
                $item = (object)[
                    'created_at' => $data['order']->orders[$i]->oItemsRefunded[$n]->created_at,
                    'received_at' => $data['order']->orders[$i]->oItemsRefunded[$n]->refunded_at,
                    'refund_products_count' => $data['order']->orders[$i]->oItemsRefunded->sum('count'),
                    'refund_reason' => $refundReason,
                    'status' => $data['order']->orders[$i]->oItemsRefunded[$n]->status,
                    'product' => $product
                ];

                array_push($refunds, $item);
            }
        }
        $data['order']['refunds'] = $refunds;
        $data['total_cost'] = $totalCost;
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // accept refunds
    public function acceptRefunds(Request $request, MainOrder $order) {
        for ($i = 0; $i < count($order->orders); $i ++) {
            for ($n = 0; $n < count($order->orders[$i]->oItemsRefunded); $n ++) {
                $order->orders[$i]->oItemsRefunded[$n]->update(['status' => 6]);
                if ($order['payment_method'] == 3 || $order['payment_method'] == 1) {
                    $walletUser = Wallet::where('user_id', $order['user_id'])->first();
                    if ($walletUser) {
                        $walletUser['balance'] = $walletUser['balance'] + ($order->orders[$i]->oItemsRefunded[$n]['count'] * $order->orders[$i]->oItemsRefunded[$n]['final_price']);
                        $walletUser->save();
                    }else {
                        Wallet::create([
                            'user_id' => $order['user_id'],
                            'balance' => $order->orders[$i]->oItemsRefunded[$n]['count'] * $order->orders[$i]->oItemsRefunded[$n]['final_price']
                        ]);
                    }
                }
            }
        }
        

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , [] , $request->lang);
        return response()->json($response , 200);
    }
}