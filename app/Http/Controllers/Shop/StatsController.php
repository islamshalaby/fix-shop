<?php
namespace App\Http\Controllers\Shop;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon;
use App\User;
use App\Ad;
use App\Category;
use App\ContactUs;
use App\Product;
use App\Offer;
use App\Area;
use App\Order;
use App\MainOrder;
use App\OrderItem;
use Illuminate\Support\Facades\Auth;

class StatsController extends ShopController{
    public function show() {
        $data['products'] = Product::where('store_id', Auth::user()->id)->where('deleted', 0)->count();
        $data['sub_orders'] = Order::where('store_id', Auth::user()->id)->count();
        $data['in_progress_orders'] = Order::where('store_id', Auth::user()->id)->where('status', 1)->count();
        $data['canceled_orders'] = Order::where('store_id', Auth::user()->id)->where('status', 3)->count();
        $data['delivered_orders'] = Order::where('store_id', Auth::user()->id)->where('status', 2)->count();
        $data['delivered_orders_cost'] = Order::where('store_id', Auth::user()->id)->where('status', 2)->sum('total_price');
        $data['canceled_orders_cost'] = Order::where('store_id', Auth::user()->id)->where('status', 3)->sum('total_price');
        $data['in_progress_orders_cost'] = Order::where('store_id', Auth::user()->id)->where('status', 1)->sum('total_price');
        $data['total_orders_cost'] = Order::where('store_id', Auth::user()->id)->sum('total_price');
        $data['cash_orders_cost'] = Order::where('store_id', Auth::user()->id)->where('payment_method', 3)->where('status', 2)->sum('total_price');
        $data['key_net_orders_cost'] = Order::where('store_id', Auth::user()->id)->where('payment_method', 1)->where('status', 2)->sum('total_price');
        $data['today_orders'] = Order::where('store_id', Auth::user()->id)->whereDate('created_at', Carbon::today())->count();
        $data['products_today_count'] = Product::where('store_id', Auth::user()->id)->whereDate('created_at', Carbon::today())->where('deleted', 0)->count();
        $data['in_progress_orders_today_count'] = Order::where('store_id', Auth::user()->id)->where('status', 1)->whereDate('created_at', Carbon::today())->count();
        $data['canceled_orders_today_count'] = Order::where('store_id', Auth::user()->id)->where('status', 3)->whereDate('updated_at', Carbon::today())->count();
        $data['delivered_orders_today_count'] = Order::where('store_id', Auth::user()->id)->where('status', 2)->whereDate('updated_at', Carbon::today())->count();
        $data['today_delivered_orders_cost'] = Order::where('store_id', Auth::user()->id)->where('status', 2)->whereDate('updated_at', Carbon::today())->sum('total_price');
        $data['today_canceled_orders_cost'] = Order::where('store_id', Auth::user()->id)->where('status', 3)->whereDate('updated_at', Carbon::today())->sum('total_price');
        $data['today_in_progress_orders_cost'] = Order::where('store_id', Auth::user()->id)->where('status', 1)->whereDate('created_at', Carbon::today())->sum('total_price');
        $data['today_cash_cost'] = Order::where('store_id', Auth::user()->id)->where('payment_method', 3)->where('status', 2)->whereDate('created_at', Carbon::today())->sum('total_price');
        $data['today_key_net_cost'] = Order::where('store_id', Auth::user()->id)->where('payment_method', 1)->where('status', 2)->whereDate('created_at', Carbon::today())->sum('total_price');
        $data['most_sold_products']=OrderItem::join('products','products.id', '=','order_items.product_id')
        ->where('order_items.status', 2)
        ->where('orders.store_id', Auth::user()->id)
        ->leftjoin('orders', function($join) {
            $join->on('orders.id', '=', 'order_items.order_id');
        })
        ->select('products.id','products.title_en','products.title_ar', DB::raw('SUM(count) as cnt'))
        ->groupBy('order_items.product_id')
        ->groupBy('products.id')
		->groupBy('products.title_en')
		->groupBy('products.title_ar')
        ->orderBy('cnt', 'desc')->take(3)->get();
        
        $data['most_areas_order'] = Order::join('user_addresses', 'user_addresses.id', '=', 'orders.address_id')
        ->where('orders.store_id', Auth::user()->id)
        ->join('areas as ar1', 'user_addresses.area_id', '=', 'ar1.id')
        ->select('user_addresses.id', 'user_addresses.area_id', 'ar1.title_en', 'ar1.title_ar', 'ar1.id',  DB::raw('COUNT(orders.id) as cnt'))
        ->groupBy('user_addresses.id')
        ->groupBy('user_addresses.area_id')
        ->groupBy('ar1.title_en')
        ->groupBy('ar1.title_ar')
        ->groupBy('ar1.id')
        ->orderBy('cnt', 'desc')->take(3)->get();
        
        return view('shop.stats', ['data' => $data]);
    }
}