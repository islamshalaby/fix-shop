<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\MainOrder;
use App\MultiOption;
use App\OrderItem;
use App\Area;
use App\Order;
use App\Shop;
use App\UserAddress;
use App\SizeDetail;
use App\Wallet;
use PDF;


class OrderController extends AdminController{
    // get all orders
    public function show(Request $request){
        $data['orders'] = Order::orderBy('id', 'desc');
        if ($request->to) {
            $data['orders'] = $data['orders']->whereBetween('orders.created_at', array($request->from, $request->to));
        }
        if ($request->order_status2) {
            if ($request->order_status2 == 'opened') {
                $data['orders'] = $data['orders']->where('status', 1);
            }else {
                $data['orders'] = $data['orders']->whereIn('status', [2, 3]);
            }
        }
        $data['sum_price'] = $data['orders']->sum('subtotal_price');
        $data['sum_delivery'] = $data['orders']->sum('delivery_cost');
        $data['sum_total'] = $data['orders']->sum('total_price');
        $data['orders'] = $data['orders']->get();
        
        return view('admin.orders' , ['data' => $data]);
    }

    // update status
    public function updateStatus(Order $order, $status) {
        $order->status = $status;
        $order->save();
        OrderItem::where('order_id', $order->id)->update(['status' => $status]);

        return redirect()->back()
        ->with('success', __('messages.order_updated'));
    }

    // get sub orders
    public function showSubOrders(Request $request) {
        if (isset($request->order_status)) {
            $statusArray = [1, 2, 5];
            if ($request->order_status == 'closed') {
                $statusArray = [3, 4, 6, 7, 8, 9];
            }
            $data['order_status'] = $request->order_status;
            $data['orders'] = Order::whereIn('status', $statusArray);
        }else{
            $data['orders'] = Order::join('user_addresses', 'user_addresses.id', '=', 'orders.address_id');
            if(isset($request->area_id)){
                $data['area'] = Area::where('id', $request->area_id)->select('id', 'title_en', 'title_ar')->first();
                $data['area_id'] = $request->area_id;
                $data['orders'] = $data['orders']->where('area_id', $request->area_id);
            }
            if(isset($request->from) && isset($request->to)){
                $data['from'] = $request->from;
                $data['to'] = $request->to;
                $data['orders'] = $data['orders']->whereBetween('orders.created_at', array($request->from, $request->to));
            }
            if(isset($request->method)){
                $data['method'] = $request->method;
                $data['orders'] = $data['orders']->where('orders.payment_method', $request->method);
            }
            if(isset($request->order_status2)){
                $statusArray = [1, 2, 5];
                if ($request->order_status2 == 'closed') {
                    $statusArray = [3, 4, 6, 7, 8, 9];
                }
                $data['order_status2'] = $request->order_status2;
                $data['orders'] = $data['orders']->whereIn('orders.status', $statusArray);
            }
            if(isset($request->shop)){
                $data['shop'] = $request->shop;
                $data['orders'] = $data['orders']->where('orders.store_id', $request->shop);
            }
        }
        
        $data['shops'] = Shop::orderBy('name', 'desc')->get();
        $data['areas'] = Area::where('deleted', 0)->orderBy('title_ar', 'asc')->get();
        $data['sum_price'] = $data['orders']->sum('subtotal_price');
        $data['sum_delivery'] = $data['orders']->sum('delivery_cost');
        $data['sum_total'] = $data['orders']->sum('total_price');
        $data['orders'] = $data['orders']->select('orders.*')->orderBy('orders.id', 'desc')->get();

        return view('admin.sub_orders' , ['data' => $data]);
    }

    // get delivery reports
    public function showDeliveryReports(Request $request) {
        if (isset($request->order_status)) {
            $status = 1;
            if ($request->order_status == 'delivered') {
                $status = 2;
            }
            $data['order_status'] = $request->order_status;
            $data['orders'] = Order::where('status', $status)->orderBy('id' , 'desc');
        }else{
            $data['orders'] = Order::whereIn('status', [1, 2])->orderBy('id', 'desc');
            
            if(isset($request->from) && isset($request->to)){
                $data['from'] = $request->from;
                $data['to'] = $request->to;
                $data['orders'] = $data['orders']->whereBetween('orders.created_at', array($request->from, $request->to));
            }
            
            if(isset($request->order_status2)){
                $data['order_status2'] = $request->order_status2;
                $data['orders'] = $data['orders']->where('status', $request->order_status2);
            }
            
        }
        
        $data['sum_price'] = $data['orders']->sum('subtotal_price');
        $data['sum_delivery'] = $data['orders']->sum('delivery_cost');
        $data['sum_total'] = $data['orders']->sum('total_price');
        $data['orders'] = $data['orders']->get();

        return view('admin.delivery_reports' , ['data' => $data]);
    }

    // show products orders
    public function showProductsOrders(Request $request) {
        $data['orders'] = OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id');
        
        if(isset($request->from) && isset($request->to)){
            $data['from'] = $request->from;
            $data['to'] = $request->to;
            $data['orders'] = $data['orders']->whereBetween('order_items.created_at', array($request->from, $request->to));
        }
        
        if(isset($request->order_status2)){
            $data['order_status2'] = $request->order_status2;
            $data['orders'] = $data['orders']->where('order_items.status', $request->order_status2);
        }
        
        $data['sum_price'] = $data['orders']->sum('final_price');
        $data['sum_price'] = number_format((float)$data['sum_price'], 3, '.', '');
        $data['orders'] = $data['orders']->select('order_items.*')->orderBy('id', 'desc')->get();
        $data['sum_total'] = 0;
        for ($i = 0; $i < count($data['orders']); $i ++) {
            $data['sum_total'] = $data['sum_total'] + ($data['orders'][$i]['final_price'] * $data['orders'][$i]['count']);
        }
        $data['sum_total'] = number_format((float)$data['sum_total'], 3, '.', '');
        

        return view('admin.products_orders' , ['data' => $data]);
    }

    // cancel | delivered order
    public function action_order(MainOrder $order, $status) {
        $order->update(['status' => $status]);
        for ($i = 0; $i < count($order->orders); $i ++) {
            if ($status == 2) {
                if ($order->orders[$i]['status'] == 1) {
                    $order->orders[$i]->update(['status' => 2]);
                }
            }
        }

        return redirect()->back();
    }


    // action sub order
    public function action_sub_order(Request $request, Order $order) {
        
        $order->update(['status' => $request->status]);

        for ($i = 0; $i < count($order->oItems); $i ++) {
            $order->oItems[$i]->update(['status' => $request->status]);
        }

        return redirect()->back();
    }

    

    // details
    public function details(Order $order) {
        $data['order'] = $order;

        return view('admin.order_details', ['data' => $data]);
    }

    // details
    public function subOrdersDetails(Order $order) {
        $data['order'] = $order;

        return view('admin.sub_order_details', ['data' => $data]);
    }

    // order items actions
    public function order_items_actions(Request $request, OrderItem $item) {
        $item->update(['status' => $request->status]);
        $order_inprogress = 0;
        $order_delivered = 0;
        $main_order_inprogress = 0;
        $main_order_delivered = 0;
        
        for ($i = 0; $i < count($item->order->oItems); $i ++) {
            if(in_array($request->status, [1, 2])) {
                if ( in_array($item->order->oItems[$i]->status, [1, 2]) ) {
                    $order_inprogress ++;
                }
            }else if($request->status == 3) {
                if ( in_array($item->order->oItems[$i]->status, [3, 4, 7]) ) {
                    $order_delivered ++;
                }
            }
        }

        

        if ($order_inprogress == count($item->order->oItems)) {
            $item->order->update(['status' => 1]);
        }
        
        if ($order_delivered == count($item->order->oItems)) {
            $item->order->update(['status' => 3]);
        }

        for ($n =0; $n < count($item->order->main->orders); $n ++) {
            if(in_array($request->status, [1])) {
                if ( in_array($item->order->main->orders[$n]->status, [1]) ) {
                    $main_order_inprogress ++;
                }
            }else if($request->status == 3) {
                if ( in_array($item->order->main->orders[$n]->status, [3, 4, 7]) ) {
                    $main_order_delivered ++;
                }
            }
        }

        if ($order_inprogress == count($item->order->main->orders)) {
            $item->order->main->update(['status' => 1]);
        }
        
        if ($order_delivered == count($item->order->main->orders)) {
            $item->order->main->update(['status' => 3]);
        }

        return redirect()->back();
    }

    public function order_actions(Request $request, Order $item) {
        $order_inprogress = 0;
        $order_delivered = 0;
        $item->update(['status' => $request->status]);
        // dd($item);
        
        for ($i = 0; $i < count($item->oItems); $i ++) {
            if (!in_array($item->oItems[$i]->status, [3, 4, 9])) {
                $item->oItems[$i]->status = $request->status;
                $item->oItems[$i]->save();
            }
        }

        if (count($item->main->canceledOrders) + count($item->main->deliveredOrders) == count($item->main->orders) || count($item->main->deliveredOrders) == count($item->main->orders)) {
            $item->main->update(['status' => 3]);
        }elseif (count($item->main->canceledOrders) == count($item->main->orders)) {
            $item->main->update(['status' => 9]);
        }else {
            // dd("gfhghfg");
            $item->main->update(['status' => 1]);
        }

        return redirect()->back();
    }

    // filter orders
    public function filter_orders(Request $request, $status) {
        if (isset($request->area_id)) {
            $addresses = UserAddress::with('orders')->where('area_id', $request->area_id)->get();
            $data['sum_price'] = 0;
            $data['sum_delivery'] = 0;
            $data['sum_total'] = 0;
            $orders = [];
            if (count($addresses) > 0) {
                foreach ($addresses as $address) {
                    if (count($address->orders) > 0) {
                        foreach($address->orders as $order) {
                            if ($order->status == $status) {
                                $data['sum_price'] += $order->subtotal_price;
                                $data['sum_delivery'] += $order->delivery_cost;
                                $data['sum_total'] += $order->total_price;
                                array_push($orders, $order);
                            }
                        }
                    }
                }
            }
            $data['orders'] = $orders;
            $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
            $data['area'] = Area::findOrFail($request->area_id);
        }elseif(isset($request->from)) {
            $data['orders'] = MainOrder::where('status', $status)->whereBetween('created_at', array($request->from, $request->to))->get();
            $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
            $data['sum_price'] = MainOrder::whereBetween('created_at', array($request->from, $request->to))->sum('subtotal_price');
            $data['sum_delivery'] = MainOrder::whereBetween('created_at', array($request->from, $request->to))->sum('delivery_cost');
            $data['sum_total'] = MainOrder::whereBetween('created_at', array($request->from, $request->to))->sum('total_price');
        }else if(isset($request->method)) {
            $data['orders'] = MainOrder::where('status', $status)->where('payment_method', $request->method)->get();
            $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
            $data['sum_price'] = MainOrder::where('status', $status)->where('payment_method', $request->method)->sum('subtotal_price');
            $data['sum_delivery'] = MainOrder::where('status', $status)->where('payment_method', $request->method)->sum('delivery_cost');
            $data['sum_total'] = MainOrder::where('status', $status)->where('payment_method', $request->method)->sum('total_price');
            $data['method'] = $request->method;
        }else if(isset($request->sub_number)) {
            $data['orders'] = MainOrder::where('status', $status)->whereHas('orders', function($q) use($request) {
                $q->where('order_number', 'like','%' . $request->sub_number . '%');
            })->get();
            $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
            $data['sum_price'] = MainOrder::where('status', $status)->whereHas('orders', function($q) use($request) {
                $q->where('order_number', 'like','%' . $request->sub_number . '%');
            })->sum('subtotal_price');
            $data['sum_delivery'] = MainOrder::where('status', $status)->whereHas('orders', function($q) use($request) {
                $q->where('order_number', 'like','%' . $request->sub_number . '%');
            })->sum('delivery_cost');
            $data['sum_total'] = MainOrder::where('status', $status)->whereHas('orders', function($q) use($request) {
                $q->where('order_number', 'like','%' . $request->sub_number . '%');
            })->sum('total_price');
        }else {
            $data['orders'] = MainOrder::where('status', $status)->get();
            $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
            $data['sum_price'] = MainOrder::where('status', $status)->sum('subtotal_price');
            $data['sum_delivery'] = MainOrder::where('status', $status)->sum('delivery_cost');
            $data['sum_total'] = MainOrder::where('status', $status)->sum('total_price');
        }
        

        return view('admin.orders' , ['data' => $data]);
    }

    // fetch orders by area
    public function fetch_orders_by_area(Request $request) {
        $addresses = UserAddress::with('orders')->where('area_id', $request->area_id)->get();
        
        $orders = [];
        $data['sum_price'] = 0;
        $data['sum_delivery'] = 0;
        $data['sum_total'] = 0;
        if (count($addresses) > 0) {
            foreach ($addresses as $address) {
                if (count($address->orders) > 0) {
                    foreach($address->orders as $order) {
                        $data['sum_price'] += $order->subtotal_price;
                        $data['sum_delivery'] += $order->delivery_cost;
                        $data['sum_total'] += $order->total_price;
                        array_push($orders, $order);
                    }
                }
            }
        }
        $data['orders'] = $orders;
        $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
        $data['area'] = Area::findOrFail($request->area_id);
        return view('admin.orders' , ['data' => $data]);
    }

    // fetch order date range
    public function fetch_orders_date(Request $request) {
        $data['orders'] = MainOrder::whereBetween('created_at', array($request->from, $request->to))->get();
        $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
        $data['from'] = '';
        $data['to'] = '';
        if (isset($request->from)) {
            $data['from'] = $request->from;
            $data['to'] = $request->to;
        }
        $data['sum_price'] = MainOrder::whereBetween('created_at', array($request->from, $request->to))->sum('subtotal_price');
        $data['sum_delivery'] = MainOrder::whereBetween('created_at', array($request->from, $request->to))->sum('delivery_cost');
        $data['sum_total'] = MainOrder::whereBetween('created_at', array($request->from, $request->to))->sum('total_price');
        return view('admin.orders' , ['data' => $data]);
    }

    // fetch order payment method
    public function fetch_order_payment_method(Request $request) {
        $data['orders'] = MainOrder::where('payment_method', $request->method)->get();
        $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
        $data['sum_price'] = MainOrder::where('payment_method', $request->method)->sum('subtotal_price');
        $data['sum_delivery'] = MainOrder::where('payment_method', $request->method)->sum('delivery_cost');
        $data['sum_total'] = MainOrder::where('payment_method', $request->method)->sum('total_price');
        $data['method'] = $request->method;

        return view('admin.orders' , ['data' => $data]);
    }

    // fetch order by sub sorder number
    public function fetch_order_by_sub_order_number(Request $request) {
        $data['orders'] = MainOrder::whereHas('orders', function($q) use($request) {
            $q->where('order_number', 'like','%' . $request->sub_number . '%');
        })->get();
        $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
        $data['sum_price'] = MainOrder::whereHas('orders', function($q) use($request) {
            $q->where('order_number', 'like','%' . $request->sub_number . '%');
        })->sum('subtotal_price');
        $data['sum_delivery'] = MainOrder::whereHas('orders', function($q) use($request) {
            $q->where('order_number', 'like','%' . $request->sub_number . '%');
        })->sum('delivery_cost');
        $data['sum_total'] = MainOrder::whereHas('orders', function($q) use($request) {
            $q->where('order_number', 'like','%' . $request->sub_number . '%');
        })->sum('total_price');

        return view('admin.orders' , ['data' => $data]);
    }

    // get invoice
    public function getInvoice(Request $request, Order $order) {
        $data['order'] = $order;
        if($request->has('download')){
            $pdf = PDF::loadView('admin.invoice_pdf', ['data' => $data]);
            
            return $pdf->stream('download.pdf');
        }

        return view('admin.invoice', ['data' => $data]);
    }

    // order size details
    public function order_size_details(OrderItem $item) {
        $data['size'] = $item->size;

        return view('admin.size_details', ['data' => $data]);
    }

    // cancel order from admin
    public function cancelOrder($type, $orderId) {
        if ($type == 'main') {
            $main = MainOrder::where('id', $orderId)->first();

            // fetch deserved total cost to add them to user wallet
            $deservedTotal = Order::where('main_id', $orderId)->whereNotIn('status', [3, 4, 9])->sum('total_price');


            // send money to user
            if (in_array($main->payment_method, [1, 3])) {
                $walletUser = Wallet::where('user_id', $main->user_id)->first();

                if ($walletUser) {
                    $walletUser->update(['balance' => $deservedTotal + $walletUser->balance]);
                }else {
                    Wallet::create(['balance' => $deservedTotal, 'user_id' => $main->user_id]);
                }
            }

            // update order if its status not in 3, 4, 9
            for ($i =0; $i < count($main->orders_with_select); $i ++) {
                if (!in_array($main->orders_with_select[$i]->status, [3, 4, 9])) {
                    $main->orders_with_select[$i]->update(['subtotal_price' => '0.000',
                    'delivery_cost' => '0.000',
                    'total_price' => '0.000',
                    'status' => 9]);

                    // update order_item
                    for ($n = 0; $n < count($main->orders_with_select[$i]->oItems); $n ++) {
                        $main->orders_with_select[$i]->oItems[$n]->update(['final_price' => '0.000',
                        'price_before_offer' => '0.000',
                        'status' => 9]);
                    }
                }
            }

            // fetch sum (total - subtotal - delivery) 
            $totalSubOrders = Order::where('main_id', $orderId)->where('status', 4)->sum('total_price');
            $subTotalSubOrders = Order::where('main_id', $orderId)->where('status', 4)->sum('subtotal_price');
            $deliverySubOrders = Order::where('main_id', $orderId)->where('status', 4)->sum('delivery_cost');
            $pluckStatus = Order::where('main_id', $orderId)->where('status', 4)->pluck('status')->toArray();
            $status = 9;

            // if there is suborder with status 3 make main status 3
            if (in_array(3, $pluckStatus)) {
                $status = 3;
            }

            // update main
            $main->update(['subtotal_price' => $subTotalSubOrders,
             'delivery_cost' => $deliverySubOrders,
            'total_price' => $totalSubOrders,
            'status' => $status]
            );


        }elseif ($type == 'order') {
            $order = Order::where('id', $orderId)->first();

            

            /**
             * update status on main - order - order_item
             */

            // $orderSubtotal = $order->subtotal_price;
            // $orderDelivery = $order->delivery_cost;
            // $orderTotal = $order->total_price;
            
            

            // update order_item
            for ($i = 0; $i < count($order->oItems); $i ++) {
                if (!in_array($order->oItems[$i]->status, [3, 4, 9])) {
                    $order->oItems[$i]->update(['final_price' => '0.000',
                    'price_before_offer' => '0.000',
                    'status' => 9]);
                }
            }

            $totalItem = 0.000;
            $subOrderDelivery = $order->delivery_cost;
            for ($n = 0; $n < count($order->oItems); $n ++) {
                $finalPrice = $order->oItems[$n]->final_price * $order->oItems[$n]->count;
                $totalItem = $totalItem + $finalPrice;
            }

            $pluckItems = OrderItem::where('order_id', $orderId)->pluck('status')->toArray();
            // dd($pluckItems);
            $status = 9;

            if (in_array(3, $pluckItems)) {
                $status = 3;
            }else {
                $subOrderDelivery = 0.000;
            }

            // update order
            $order->update(['subtotal_price' => $totalItem,
            'delivery_cost' => $subOrderDelivery,
            'total_price' => $totalItem + $subOrderDelivery,
            'status' => $status]);

            // send money to user
            if (in_array($order->payment_method, [1, 3])) {
                $walletUser = Wallet::where('user_id', $order->user_id)->first();

                if ($walletUser) {
                    $walletUser->update(['balance' => $order->total_price + $walletUser->balance]);
                }else {
                    Wallet::create(['balance' => $order->total_price, 'user_id' => $order->user_id]);
                }
            }

            $main = MainOrder::where('id', $order->main_id)->first();

            if (count($main->canceledOrders) == count($main->orders)) {
                $order->main->update(['subtotal_price' => '0.000',
                'delivery_cost' => '0.000',
               'total_price' => '0.000',
               'status' => 9]);
            }elseif (count($main->canceledOrders) + count($main->deliveredOrders) == count($main->orders)) {
                $order->main->update(['subtotal_price' => $main->orders->sum('subtotal_price'),
                'delivery_cost' => $main->orders->sum('delivery_cost'),
               'total_price' => $main->orders->sum('total_price'),
               'status' => 3]);
            }else {
                $order->main->update(['subtotal_price' => $main->orders->sum('subtotal_price'),
                'delivery_cost' => $main->orders->sum('delivery_cost'),
               'total_price' => $main->orders->sum('total_price')]);
            }
        }else {
            $orderItem = OrderItem::where('id', $orderId)->first();
            $orderItemPrice = $orderItem->final_price;
            
            // update order_item
            $orderItem->update(['final_price' => '0.000',
            'price_before_offer' => '0.000',
            'status' => 9]);

            // send money to user
            if (in_array($orderItem->order->payment_method, [1, 3])) {
                $walletUser = Wallet::where('user_id', $orderItem->order->user_id)->first();

                if (count($orderItem->order->canceledItems) == count($orderItem->order->oItems)) {
                    if ($walletUser) {
                        $walletUser->update(['balance' => $orderItem->order->total_price + $walletUser->balance]);
                    }else {
                        Wallet::create(['balance' => $orderItem->order->total_price, 'user_id' => $orderItem->order->user_id]);
                    }
                }else {
                    if ($walletUser) {
                        $walletUser->update(['balance' => $orderItemPrice + $walletUser->balance]);
                    }else {
                        Wallet::create(['balance' => $orderItemPrice, 'user_id' => $orderItem->order->user_id]);
                    }
                }
            }
            $orderSubtotal = $orderItem->order->subtotal_price;
            $orderTotal = $orderItem->order->total_price;
            $orderDelivery = $orderItem->order->delivery_cost;

            // update order
            if (count($orderItem->order->canceledItems) == count($orderItem->order->oItems)) {
                $orderItem->order->update(['subtotal_price' => '0.000',
                'delivery_cost' => '0.000',
                'total_price' => '0.000',
                'status' => 9]);
            }else {
                $subOrderStatus = $orderItem->order->status;
                if (count($orderItem->order->deliveredOrders) + count($orderItem->order->canceledItems) 
                == count($orderItem->order->oItems)) {
                    $subOrderStatus = 3;
                } 
                $orderItem->order->update(['subtotal_price' => $orderItem->order->subtotal_price - $orderItemPrice,
                'status' => $subOrderStatus,
                'total_price' => $orderItem->order->total_price - $orderItemPrice]);
            }

            // update main
            if (count($orderItem->order->main->canceledOrders) == count($orderItem->order->main->orders)) {
                $orderItem->order->main->update(['subtotal_price' => '0.000',
                'delivery_cost' => '0.000',
               'total_price' => '0.000',
               'status' => 9]);
            }elseif (count($orderItem->order->main->canceledOrders) + count($orderItem->order->main->deliveredOrders) == count($orderItem->order->main->orders)) {
                $orderItem->order->main->update(['subtotal_price' => $orderItem->order->main->orders->sum('subtotal_price'),
                'delivery_cost' => $orderItem->order->main->orders->sum('delivery_cost'),
               'total_price' => $orderItem->order->main->orders->sum('total_price'),
               'status' => 3]);
            }else {
                $orderItem->order->main->update(['subtotal_price' => $orderItem->order->main->subtotal_price - $orderSubtotal,
                'delivery_cost' => $orderItem->order->main->delivery_cost - $orderDelivery,
               'total_price' => $orderItem->order->main->total_price - $orderTotal]);
            }
        }

        return redirect()->back();
    }

}