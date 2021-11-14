<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Order;
use App\UserAddress;
use App\User;
use App\OrderItem;
use App\Area;

class OrderController extends Controller
{
    // get orders
    public function getOrders(Request $request) {
        $data['store_id'] = Auth::guard('dashboard')->user()->id;
        $data['area_id'] = "";
        $data['from'] = "";
        $data['to'] = "";
        $data['method'] = "";
        $data['order_status'] = "";
        if(isset($request->status) && $request->status != 0) {
            $statusArray = [1, 2, 5];
            if ($request->status == 2) {
                $statusArray = [3, 4, 6, 7, 8, 9];
            }
            $data['status'] = $request->status;
            $data['orders'] = Order::whereIn('status', $statusArray)->where('store_id', Auth::guard('dashboard')->user()->id);
        }else {
            $data['orders'] = Order::join('user_addresses', 'user_addresses.id', '=', 'orders.address_id')
                ->where('store_id', Auth::guard('dashboard')->user()->id);
            if (isset($request->area_id)) {
                $data['orders'] = $data['orders']
                ->where('area_id', $request->area_id);
                $data['area_id'] = $request->area_id;
            }
            if(isset($request->from) && isset($request->to)) {
                $data['from'] = $request->from;
                $data['to'] = $request->to;
                $data['orders'] = $data['orders']->whereBetween('orders.created_at', array($request->from, $request->to));
            }
            if(isset($request->method)) {
                $data['method'] = $request->method;
                $data['orders'] = $data['orders']->where('orders.payment_method', $request->method);
            }
            if(isset($request->order_status)) {
                $statusArray = [1, 2, 5];
                if ($request->order_status == 2) {
                    $statusArray = [3, 4, 6, 7, 8, 9];
                }
                $data['order_status'] = $request->order_status;
                if ($request->order_status != 0) {
                    $data['orders'] = $data['orders']->whereIn('orders.status', $statusArray);
                }
            }
        }
        
        $data['orders'] = $data['orders']->select('orders.*')->orderBy('id', 'desc')->simplePaginate(16);
        
        for ($i = 0; $i < count($data['orders']); $i ++) {
            if (in_array($data['orders'][$i]['status'], [1, 2, 5])) {
                $data['orders'][$i]['status'] = 1;
            }
            if (in_array($data['orders'][$i]['status'], [3, 4, 6, 7, 8, 9])) {
                $data['orders'][$i]['status'] = 2;
            }
            $data['orders'][$i]['date'] = $data['orders'][$i]['created_at']->format('Y-m-d'); 
            $data['orders'][$i]['time'] = $data['orders'][$i]['created_at']->format('g:i A');
        }

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
        
    }

    // order details
    public function orderDetails(Request $request, Order $order) {
        if ($order->store_id != Auth::guard('dashboard')->user()->id) {
            $response = APIHelpers::createApiResponse(true , 406 , 'this user has no access to this order' , 'هذا المستخدم ليس له صلة بهذا الطلب'  , null , $request->lang);
            return response()->json($response , 406);
        }
        $data['order'] = $order->where('id', $order->id)->select('id', 'order_number', 'created_at', 'total_price', 'subtotal_price', 'delivery_cost', 'payment_method', 'status')->first();
        // dd($data['order']);
        $data['order']['date'] = $data['order']['created_at']->format('Y-m-d'); 
        $data['order']['time'] = $data['order']['created_at']->format('g:i A');

        $data['order']['address'] = $order->address;
        $area = Area::where('id', $order->address->area_id)->select('title_' . $request->lang . ' as title')->first();
        // dd($area);
        $data['order']['address']['area_name'] = $area['title'];
        $items = $order->oItems;
        $allItems = [];
        for ($i = 0; $i < count($items); $i ++) {
            if ($request->lang == 'en') {
                $product = $items[$i]->product_with_select_en;
            }else {
                $product = $items[$i]->product_with_select_ar;
            }
            if (!empty($items[$i]->delivered_at)) {
                $deliveredAt = $items[$i]->delivered_at->format('d/m/Y');
            }else {
                $deliveredAt = "";
            }
            $item = [
                'status' => $items[$i]->status,
                'item_id' => $items[$i]->id,
                'store_name' => Auth::guard('dashboard')->user()->name,
                'main_image' => $product->mainImage->image,
                'delivered_at' => $deliveredAt
            ];

            $newItem = (object) array_merge($product->toArray(), $item);
            
            array_push($allItems, $newItem);
        }
        $data['order']['items'] = $allItems;

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // update order status
    public function updateOrderStatus(Request $request, Order $order) {
        if ($order->store_id != Auth::guard('dashboard')->user()->id) {
            $response = APIHelpers::createApiResponse(true , 406 , 'this user has no access to this order' , 'هذا المستخدم ليس له صلة بهذا الطلب'  , null , $request->lang);
            return response()->json($response , 406);
        }
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة'  , null , $request->lang);
            return response()->json($response , 406);
        }
        $order = $order->makeHidden('oItems');
        $order->update(['status' => $request->status]);

        for ($i = 0; $i < count($order->oItems); $i ++) {
            if ($request->status == 3) {
                $order->oItems[$i]->update(['status' => $request->status, 'delivered_at' => date("Y-m-d H:i:s")]);
            }else {
                if (in_array($request->status, [4, 9])) {
                    $order->oItems[$i]->product->sold_count = $order->oItems[$i]->product->sold_count - $order->oItems[$i]->count;
                    $order->oItems[$i]->save();
                }
                $order->oItems[$i]->update(['status' => $request->status]);
            }
            
        }

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $order , $request->lang);
        return response()->json($response , 200);
    }

    // update item status
    public function updateItemStatus(Request $request, OrderItem $item) {
        
        $item->update(['status' => $request->status]);
        if (in_array($request->status, [4, 9])) {
            $item->product->sold_count = $item->product->sold_count - $item->count;
            $item->product->save();
        }
        $order_status = 0;
        $main_order_inprogress = 0;
        $main_order_delivered = 0;
        
        
        for ($i = 0; $i < count($item->order->oItems); $i ++) {
            if ( $item->order->oItems[$i]->status == $request->status ) {
                $order_status ++;
            }
        }

        if ($order_status == count($item->order->oItems)) {
            $item->order->update(['status' => $item->order->oItems[0]->status]);
        }
        


        for ($n =0; $n < count($item->order->main->orders); $n ++) {
            if(in_array($request->status, [1, 2])) {
                if ( in_array($item->order->main->orders[$n]->status, [1, 2]) ) {
                    $main_order_inprogress ++;
                }
            }else if($request->status == 3) {
                if ( in_array($item->order->main->orders[$n]->status, [3, 4, 7]) ) {
                    $main_order_delivered ++;
                }
            }
        }

        if ($order_status == count($item->order->main->orders)) {
            $item->order->main->update(['status' => 1]);
        }

        if ($main_order_delivered == count($item->order->main->orders)) {
            $item->order->main->update(['status' => 3]);
        }
        
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $item , $request->lang);
        return response()->json($response , 200);
    }

    // order address details
    public function orderAddressDetails(Request $request, UserAddress $address) {
        $data['address'] = $address;
        $area = Area::where('id', $data['address']['area_id'])->select('title_' . $request->lang . ' as title')->first();
        // dd($area);
        $data['address']['area_name'] = $area['title'];
        $data['address']['user'] = User::select('id', 'name', 'phone')->find($address->user_id);

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // get products orders
    public function getProductsOrders(Request $request) {
        Session::put('api_lang',$request->lang);
        $data['area_id'] = "";
        $data['from'] = "";
        $data['to'] = "";
        $data['method'] = "";
        $data['order_status'] = "";
        $orders = Order::where('store_id', Auth::guard('dashboard')->user()->id)->pluck('id')->toArray();
        $data['orders'] = OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')->whereIn('order_id', $orders);
        if(isset($request->area_id)){
            $data['area'] = Area::where('id', $request->area_id)->select('id', 'title_' . $request->lang . ' as title')->first();
            $data['area_id'] = $request->area_id;
            $data['orders'] = $data['orders']
            ->leftjoin('user_addresses', function($join) {
                $join->on('user_addresses.id', '=', 'orders.address_id');
            })
            ->where('area_id', $request->area_id)
            ->whereIn('order_items.order_id', $orders);
        }
        if(isset($request->from) && isset($request->to)){
            $data['from'] = $request->from;
            $data['to'] = $request->to;
            $data['orders'] = $data['orders']->whereBetween('order_items.created_at', array($request->from, $request->to));
        }
        if(isset($request->method)){
            $data['method'] = $request->method;
            $data['orders'] = $data['orders']
            ->where('orders.payment_method', $request->method);
        }
        if(isset($request->order_status)){
            $data['order_status'] = $request->order_status;
            $data['orders'] = $data['orders']->where('status', $request->order_status)->whereIn('order_id', $orders);
        }

        $data['sum_price'] = $data['orders']->sum('final_price');
        $data['sum_price'] = number_format((float)$data['sum_price'], 3, '.', '');
        $data['orders'] = $data['orders']->select('order_items.id', 'order_items.count', 'order_items.final_price', 'order_items.created_at', 'order_items.status', 'order_items.order_id', 'order_items.product_id')->with(['product_data', 'order_data'])->orderBy('order_items.id', 'desc')->simplePaginate(16);
        $data['sum_total'] = 0;
        for ($i = 0; $i < count($data['orders']); $i ++) {
            $data['sum_total'] = $data['sum_total'] + ($data['orders'][$i]['final_price'] * $data['orders'][$i]['count']);
        }
        $data['sum_total'] = number_format((float)$data['sum_total'], 3, '.', '');

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }
}