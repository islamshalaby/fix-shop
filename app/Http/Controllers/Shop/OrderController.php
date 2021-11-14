<?php
namespace App\Http\Controllers\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Order;
use App\MultiOption;
use App\OrderItem;
use App\Area;
use App\UserAddress;
use App\SizeDetail;
use Illuminate\Support\Facades\Auth;


class OrderController extends ShopController{
    // get all orders
    public function show(Request $request){
        $data['orders'] = Order::where('store_id', Auth::user()->id)->orderBy('id' , 'desc')->get();
        $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
        $data['sum_price'] = Order::where('store_id', Auth::user()->id)->sum('subtotal_price');
        $data['sum_delivery'] = Order::where('store_id', Auth::user()->id)->sum('delivery_cost');
        $data['sum_total'] = Order::where('store_id', Auth::user()->id)->sum('total_price');
        
        return view('shop.orders' , ['data' => $data]);
    }

    // cancel | delivered order
    public function action_order(Order $order, $status) {
        $order->update(['status' => $status]);
        foreach($order->orders as $sub_order) {
            foreach($sub_order->oItems as $item) {
                $item->status = $status;
                $item->save();
                if ($status == 3) {
                    if ($order->status == 2) {
                        $item->product->sold_count = $item->product->sold_count - $item->count;
                    }
                    $item->product->remaining_quantity = $item->product->remaining_quantity + $item->count;
                    $item->product->save();
                    if ($item->option_id != 0) {
                        $item->product->mOptionsWhere($item->option_id)
                        ->update(['remaining_quantity' => $item->product->mOptionsWhere($item->option_id)->remaining_quantity + $item->count]);
                    }
                }
                if ($status == 2) {
                    $item->product->sold_count = $item->product->sold_count + $item->count;
                    $item->product->save();
                }
            }
        }

        return redirect()->back();
    }

    

    // details
    public function details(Order $order) {
        $data['order'] = Order::where('store_id', Auth::user()->id)->where('id', $order->id)->first();
        $data['m_option'] = MultiOption::find(8);
        if (isset($data['order']['order_number'])) {
            return view('shop.order_details', ['data' => $data]);
        }else {
            return abort('404');
        }
        
    }

    // order items actions
    public function order_items_actions(OrderItem $item, $status) {
        $item->update(['status' => $status]);
        $order = Order::where('id', $item->order->main_id)->first();
        if ($status == 3) {
            if ($order->status == 2) {
                $item->product->sold_count = $item->product->sold_count - $item->count;
            }
            if ($item->option_id != 0) {
                $item->product->remaining_quantity = $item->product->remaining_quantity + $item->count;
                $item->product->mOptionsWhere($item->option_id)->remaining_quantity = $item->product->mOptionsWhere($item->option_id)->remaining_quantity + $item->count;
                $item->product->mOptionsWhere($item->option_id)->update(['remaining_quantity' => $item->product->mOptionsWhere($item->option_id)->remaining_quantity + $item->count]);
                $order->update(['subtotal_price' => $order->subtotal_price - ($item->product->mOptionsWhere($item->option_id)->final_price * $item->count), 'total_price' => $order->total_price - ($item->product->mOptionsWhere($item->option_id)->final_price * $item->count) ]);
                $item->order->update(['subtotal_price' => $item->order->subtotal_price - ($item->product->mOptionsWhere($item->option_id)->final_price * $item->count), 'total_price' => $item->order->total_price - ($item->product->mOptionsWhere($item->option_id)->final_price * $item->count)]);
            }else {
                $item->product->remaining_quantity = $item->product->remaining_quantity + $item->count;
                $order->update(['subtotal_price' => $order->subtotal_price - ($item->product->final_price * $item->count), 'total_price' => $order->total_price - ($item->product->final_price * $item->count)]);
                $item->order->update(['subtotal_price' => $item->order->subtotal_price - ($item->product->final_price * $item->count), 'total_price' => $item->order->total_price - ($item->product->final_price * $item->count)]);
            }
            $item->product->save();
            
        }

        if ($status == 2) {
            $item->product->update(['sold_count' => $item->product->sold_count + $item->count]);
        }
        
        $sub_orders_status = [];
        
        
        foreach($order->orders as $sub_order) {
            $item_status = $sub_order->oItems->pluck('status');
            for ($i = 0; $i < count($item_status); $i ++) {
                array_push($sub_orders_status, $item_status[$i]);
            }
        }

        if (in_array(1, $sub_orders_status)) {
            $order->update(['status' => 1]);
        }else if(in_array(3, $sub_orders_status) && array_count_values($sub_orders_status)[3] == count($sub_orders_status)) {
            $order->update(['status' => 3]);
        }else {
            $order->update(['status' => 2]);
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
                    if (count($address->subOrders(Auth::user()->id)) > 0) {
                        foreach($address->subOrders(Auth::user()->id) as $order) {
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
            $data['orders'] = Order::where('status', $status)->where('store_id', Auth::user()->id)->where('from_deliver_date', '>=', $request->from)->where('to_deliver_date', '<=', $request->to)->get();
            $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
            $data['from'] = '';
            $data['to'] = '';
            if (isset($request->from)) {
                $data['from'] = $request->from;
                $data['to'] = $request->to;
            }
            $data['sum_price'] = Order::where('status', $status)->where('store_id', Auth::user()->id)->where('from_deliver_date', '>=', $request->from)->where('to_deliver_date', '<=', $request->to)->sum('subtotal_price');
            $data['sum_delivery'] = Order::where('status', $status)->where('store_id', Auth::user()->id)->where('from_deliver_date', '>=', $request->from)->where('to_deliver_date', '<=', $request->to)->sum('delivery_cost');
            $data['sum_total'] = Order::where('status', $status)->where('store_id', Auth::user()->id)->where('from_deliver_date', '>=', $request->from)->where('to_deliver_date', '<=', $request->to)->sum('total_price');
        }else if(isset($request->method)) {
            $data['orders'] = Order::where('store_id', Auth::user()->id)->where('status', $status)->where('payment_method', $request->method)->get();
            $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
            $data['sum_price'] = Order::where('store_id', Auth::user()->id)->where('status', $status)->where('payment_method', $request->method)->sum('subtotal_price');
            $data['sum_delivery'] = Order::where('store_id', Auth::user()->id)->where('status', $status)->where('payment_method', $request->method)->sum('delivery_cost');
            $data['sum_total'] = Order::where('store_id', Auth::user()->id)->where('status', $status)->where('payment_method', $request->method)->sum('total_price');
            $data['method'] = $request->method;
        }else {
            $data['orders'] = Order::where('store_id', Auth::user()->id)->where('status', $status)->get();
            $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
            $data['sum_price'] = Order::where('store_id', Auth::user()->id)->where('status', $status)->sum('subtotal_price');
            $data['sum_delivery'] = Order::where('store_id', Auth::user()->id)->where('status', $status)->sum('delivery_cost');
            $data['sum_total'] = Order::where('store_id', Auth::user()->id)->where('status', $status)->sum('total_price');
        }
        

        return view('shop.orders' , ['data' => $data]);
    }

    // fetch orders by area
    public function fetch_orders_by_area(Request $request) {
        $addresses = UserAddress::where('area_id', $request->area_id)->get();

        $orders = [];
        $data['sum_price'] = 0;
        $data['sum_delivery'] = 0;
        $data['sum_total'] = 0;
        
        if (count($addresses) > 0) {
            foreach ($addresses as $address) {
                if (count($address->subOrders(Auth::user()->id)) > 0) {
                    foreach($address->subOrders(Auth::user()->id) as $order) {
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
        return view('shop.orders' , ['data' => $data]);
    }

    // fetch order date range
    public function fetch_orders_date(Request $request) {
        $data['orders'] = Order::where('store_id', Auth::user()->id)->where('from_deliver_date', '>=', $request->from)->where('to_deliver_date', '<=', $request->to)->get();
        $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
        $data['from'] = '';
        $data['to'] = '';
        if (isset($request->from)) {
            $data['from'] = $request->from;
            $data['to'] = $request->to;
        }
        $data['sum_price'] = Order::where('store_id', Auth::user()->id)->where('from_deliver_date', '>=', $request->from)->where('to_deliver_date', '<=', $request->to)->sum('subtotal_price');
        $data['sum_delivery'] = Order::where('store_id', Auth::user()->id)->where('from_deliver_date', '>=', $request->from)->where('to_deliver_date', '<=', $request->to)->sum('delivery_cost');
        $data['sum_total'] = Order::where('store_id', Auth::user()->id)->where('from_deliver_date', '>=', $request->from)->where('to_deliver_date', '<=', $request->to)->sum('total_price');
        return view('shop.orders' , ['data' => $data]);
    }

    // fetch order payment method
    public function fetch_order_payment_method(Request $request) {
        $data['orders'] = Order::where('store_id', Auth::user()->id)->where('payment_method', $request->method)->get();
        $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
        $data['sum_price'] = Order::where('store_id', Auth::user()->id)->where('payment_method', $request->method)->sum('subtotal_price');
        $data['sum_delivery'] = Order::where('store_id', Auth::user()->id)->where('payment_method', $request->method)->sum('delivery_cost');
        $data['sum_total'] = Order::where('store_id', Auth::user()->id)->where('payment_method', $request->method)->sum('total_price');
        $data['method'] = $request->method;

        return view('shop.orders' , ['data' => $data]);
    }

    // fetch order by sub sorder number
    public function fetch_order_by_sub_order_number(Request $request) {
        $data['orders'] = Order::whereHas('orders', function($q) use($request) {
            $q->where('order_number', 'like','%' . $request->sub_number . '%');
        })->get();
        $data['areas'] = Area::where('deleted', 0)->orderBy('id', 'desc')->get();
        $data['sum_price'] = Order::whereHas('orders', function($q) use($request) {
            $q->where('order_number', 'like','%' . $request->sub_number . '%');
        })->sum('subtotal_price');
        $data['sum_delivery'] = Order::whereHas('orders', function($q) use($request) {
            $q->where('order_number', 'like','%' . $request->sub_number . '%');
        })->sum('delivery_cost');
        $data['sum_total'] = Order::whereHas('orders', function($q) use($request) {
            $q->where('order_number', 'like','%' . $request->sub_number . '%');
        })->sum('total_price');

        return view('shop.orders' , ['data' => $data]);
    }

    // get invoice
    public function getInvoice(Order $order) {
        $data['order'] = Order::where('store_id', Auth::user()->id)->where('id', $order->id)->first();
        
        if (isset($data['order']['order_number'])) {
            return view('shop.invoice', ['data' => $data]);
        }else {
            return abort('404');
        }
    }

    // order size details
    public function order_size_details(OrderItem $item) {
        $data['size'] = $item->size;

        return view('shop.size_details', ['data' => $data]);
    }
}