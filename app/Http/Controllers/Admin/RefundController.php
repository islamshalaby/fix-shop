<?php
namespace App\Http\Controllers\Admin;

use App\Area;
use App\Http\Controllers\Admin\AdminController;
use App\Order;
use App\OrderItem;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Retrieve;
use App\Shop;
use App\Wallet;

class RefundController extends AdminController{

    // index
    public function show(Request $request) {
        $data['shops'] = Shop::orderBy('name', 'asc')->get();
        $data['areas'] = Area::where('deleted', 0)->orderBy('title_ar', 'asc')->get();
        $data['refunds'] = Retrieve::orderBy('id', 'desc');
        if (isset($request->from)) {
            $data['from'] = $request->from;
            $data['to'] = $request->to;
            $data['refunds'] = $data['refunds']->whereBetween('created_at', array($request->from, $request->to))->get();
            $refunds = Retrieve::whereBetween('created_at', array($request->from, $request->to))->pluck('item_id');
        }elseif(isset($request->method)) {
            $data['method'] = $request->method;
            $orders = Order::where('payment_method', $request->method)->pluck('id');
            $oItems = OrderItem::whereIn('order_id', $orders)->pluck('id');
            $data['refunds'] = $data['refunds']->whereIn('item_id', $oItems)->get();
            $refunds = Retrieve::whereIn('item_id', $oItems)->pluck('item_id');
        }elseif($request->shop){
            $data['shop'] = $request->shop;
            $data['refunds'] = $data['refunds']->where('store_id', $request->shop)->get();
            $refunds = Retrieve::where('store_id', $request->shop)->pluck('item_id');
        }elseif($request->area){
            $data['area'] = $request->area;
            $data['refunds'] = Retrieve::join('order_items', 'order_items.id', '=', 'retrieves.item_id')
            ->leftjoin('orders', function($join) {
                $join->on('orders.id', '=', 'order_items.order_id');
            })
            ->leftjoin('user_addresses', function($join) {
                $join->on('user_addresses.id', '=', 'orders.address_id');
            })
            ->where('user_addresses.area_id', $request->area)
            ->select('retrieves.*', 'order_items.order_id', 'user_addresses.id as addressid', 'orders.address_id', 'user_addresses.area_id')
            ->orderBy('id', 'desc')
            ->get();
            
            $refunds = Retrieve::join('order_items', 'order_items.id', '=', 'retrieves.item_id')
            ->leftjoin('orders', function($join) {
                $join->on('orders.id', '=', 'order_items.order_id');
            })
            ->leftjoin('user_addresses', function($join) {
                $join->on('user_addresses.id', '=', 'orders.address_id');
            })
            ->where('user_addresses.area_id', $request->area)
            ->pluck('item_id');
        }elseif($request->status){
            $data['status'] = $request->status;
            $data['refunds'] = Retrieve::join('order_items', 'order_items.id', '=', 'retrieves.item_id')
            ->where('order_items.status', $request->status)
            ->select('retrieves.*')
            ->orderBy('id', 'desc')
            ->get();
            $refunds = Retrieve::join('order_items', 'order_items.id', '=', 'retrieves.item_id')
            ->where('order_items.status', $request->status)->pluck('item_id');
        }else {
            $data['refunds'] = $data['refunds']->get();
            $refunds = Retrieve::pluck('item_id');
        }
        
        $data['sum_price'] = OrderItem::whereIn('id', $refunds)->sum('final_price');
        $data['sum_price'] = number_format((float)$data['sum_price'], 3, '.', '');
        
        
        return view('admin.refunds', ['data' => $data]);
    }

    // details
    public function details(Retrieve $refund) {
        $refund->update(['admin_seen' => 1]);
        $data['refund'] = $refund;

        return view('admin.refund_details', ['data' => $data]);
    }

    // accept refund
    public function accept(Retrieve $refund) {
        $refund->item->update(['status' => 6]);
        $order_status = 0;
        $main_order_cancel = 0;

        if ($refund->item->order->main['payment_method'] == 3 || $refund->item->order->main['payment_method'] == 1) {
            $walletUser = Wallet::where('user_id', $refund->item->order->main['user_id'])->first();
            if ($walletUser) {
                $walletUser['balance'] = $walletUser['balance'] + ($refund->item['count'] * $refund->item['final_price']);
                $walletUser->save();
            }else {
                Wallet::create([
                    'user_id' => $refund->item->order->main['user_id'],
                    'balance' => $refund->item['count'] * $refund->item['final_price']
                ]);
            }
        }

        for ($i = 0; $i < count($refund->item->order->oItems); $i ++) {
            if ( $refund->item->order->oItems[$i]->status == 6 ) {
                $order_status ++;
            }
        }

        if ($order_status == count($refund->item->order->oItems)) {
            $refund->item->order->update(['status' => 6]);
        }

        for ($n = 0; $n < count($refund->item->order->main->orders); $n ++) {
            if ($refund->item->order->main->orders[$n]->status == 6) {
                $main_order_cancel ++;
            }
        }

        if ($main_order_cancel == count($refund->item->order->main->orders)) {
            $refund->item->order->main->update(['status' => 4]);
        }

        return redirect()->back();
    }

    // reject refund
    public function reject(Retrieve $refund) {
        $refund->item->update(['status' => 7]);
        $order_status = 0;
        $main_order_cancel = 0;

        for ($i = 0; $i < count($refund->item->order->oItems); $i ++) {
            if ( $refund->item->order->oItems[$i]->status == 7 ) {
                $order_status ++;
            }
        }

        if ($order_status == count($refund->item->order->oItems)) {
            $refund->item->order->update(['status' => 7]);
        }

        for ($n = 0; $n < count($refund->item->order->main->orders); $n ++) {
            if ($refund->item->order->main->orders[$n]->status == 7) {
                $main_order_cancel ++;
            }
        }

        if ($main_order_cancel == count($refund->item->order->main->orders)) {
            $refund->item->order->main->update(['status' => 1]);
        }

        return redirect()->back();
    }

    // reveived refund
    public function received(Retrieve $refund) {
        $refund->item->update(['status' => 8, 'refunded_at' => date("Y-m-d H:i:s")]);
        $refund->item->product->remaining_quantity = $refund->item->product->remaining_quantity + $refund->item->count;
        $refund->item->product->refund_count = $refund->item->product->refund_count + $refund->item->count;
        $refund->item->product->save();

        $order_status = 0;
        $main_order_cancel = 0;

        for ($i = 0; $i < count($refund->item->order->oItems); $i ++) {
            if ( $refund->item->order->oItems[$i]->status == 8 ) {
                $order_status ++;
            }
        }

        if ($order_status == count($refund->item->order->oItems)) {
            $refund->item->order->update(['status' => 8]);
        }

        for ($n = 0; $n < count($refund->item->order->main->orders); $n ++) {
            if ($refund->item->order->main->orders[$n]->status == 4 || $refund->item->order->main->orders[$n]->status == 8) {
                $main_order_cancel ++;
            }
        }

        if ($main_order_cancel == count($refund->item->order->main->orders)) {
            $refund->item->order->main->update(['status' => 4]);
        }

        // $walletUser = Wallet::where('user_id', $refund->item->order->main['user_id'])->first();

        // if ($walletUser) {
        //     $walletUser['balance'] = $walletUser['balance'] + ($refund->item['count'] * $refund->item['final_price']);
        //     $walletUser->save();
        // }else {
        //     Wallet::create([
        //         'user_id' => $refund->item->order->main['user_id'],
        //         'balance' => $refund->item['count'] * $refund->item['final_price']
        //     ]);
        // }



        return redirect()->back();
    }
}