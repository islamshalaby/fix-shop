<?php
namespace App\Http\Controllers\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ContactUs;
use App\User;
use App\Ad;
use App\Category;
use App\Product;
use App\Brand;
use App\SubCategory;
use App\Offer;
use App\HomeSection;
use App\Area;
use App\Order;
use App\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends ShopController{

    // get home
    public function show(){
        $data['products_less_than_ten'] = Product::where('store_id', Auth::user()->id)->where('deleted' , 0)->where('remaining_quantity' , '<' , 10)->count();
        $data['most_sold_products']=OrderItem::join('products','products.id', '=','order_items.product_id')
        ->where('orders.store_id', Auth::user()->id)
        ->where('orders.status', 2)
        ->leftjoin('orders', function($join) {
            $join->on('orders.id', '=', 'order_items.order_id');
        })
        ->leftjoin('product_multi_options', function($join) {
            $join->on('product_multi_options.id', '=', 'order_items.option_id');
        })
        ->select('products.id', 'products.multi_options', 'products.remaining_quantity', 'products.title_en','products.title_ar', DB::raw('SUM(count) as cnt'))
        ->addSelect('orders.status')
        ->groupBy('orders.status')
        ->groupBy('order_items.product_id')
        ->groupBy('order_items.option_id')
        ->groupBy('products.id')
        ->groupBy('products.multi_options')
		->groupBy('products.title_en')
        ->groupBy('products.title_ar')
        ->groupBy('products.final_price')
		->groupBy('products.remaining_quantity')
        ->groupBy('products.price_before_offer')
        ->groupBy('product_multi_options.id')
        ->groupBy('product_multi_options.product_id')
        ->groupBy('product_multi_options.final_price')
		->groupBy('product_multi_options.remaining_quantity')
		->groupBy('product_multi_options.price_before_offer')
        ->orderBy('cnt', 'desc')->take(7)->get();

        $data['recent_orders'] = Order::where('store_id', Auth::user()->id)->orderBy('id' , 'desc')->take(7)->get();
        
        $data['in_progress_orders'] = Order::where('store_id', Auth::user()->id)->where('status', 1)->sum('total_price');
        $data['canceled_orders'] = Order::where('store_id', Auth::user()->id)->where('status', 3)->sum('total_price');
        $data['delivered_orders'] = Order::where('store_id', Auth::user()->id)->where('status', 2)->sum('total_price');
        $data['total_value'] = (double)$data['in_progress_orders'] + (double)$data['canceled_orders'] + (double)$data['delivered_orders'];

        $data['monthly_canceled_orders'] = Order::where('store_id', Auth::user()->id)->select('id', 'created_at')
        ->where('status', 3)
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('m'); // grouping by months
        });
        $data['canceled_orders_count'] = [];
        $data['canceled_orders_arr'] = [];

        foreach ($data['monthly_canceled_orders'] as $key => $value) {
            $data['canceled_orders_count'][(int)$key] = count($value);
        }

        for($i = 1; $i <= 12; $i++){
            if(!empty($data['canceled_orders_count'][$i])){
                $data['canceled_orders_arr'][$i] = $data['canceled_orders_count'][$i];    
            }else{
                $data['canceled_orders_arr'][$i] = 0;    
            }
        }

        $data['monthly_completed_orders'] = Order::where('store_id', Auth::user()->id)->select('id', 'created_at')
        ->where('status', 2)
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('m'); // grouping by months
        });
        $data['completed_orders_count'] = [];
        $data['completed_orders_arr'] = [];

        foreach ($data['monthly_completed_orders'] as $key => $value) {
            $data['completed_orders_count'][(int)$key] = count($value);
        }

        for($i = 1; $i <= 12; $i++){
            if(!empty($data['completed_orders_count'][$i])){
                $data['completed_orders_arr'][$i] = $data['completed_orders_count'][$i];    
            }else{
                $data['completed_orders_arr'][$i] = 0;    
            }
        }

        $data['monthly_Inprogress_orders'] = Order::where('store_id', Auth::user()->id)->select('id', 'created_at')
        ->where('status', 1)
        ->get()
        ->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('m'); // grouping by months
        });
        $data['Inprogress_orders_count'] = [];
        $data['Inprogress_orders_arr'] = [];

        foreach ($data['monthly_Inprogress_orders'] as $key => $value) {
            $data['Inprogress_orders_count'][(int)$key] = count($value);
        }

        for($i = 1; $i <= 12; $i++){
            if(!empty($data['Inprogress_orders_count'][$i])){
                $data['Inprogress_orders_arr'][$i] = $data['Inprogress_orders_count'][$i];    
            }else{
                $data['Inprogress_orders_arr'][$i] = 0;    
            }
        }

        $data['delivered_orders_cost'] = Order::where('store_id', Auth::user()->id)->where('status', 2)->sum('total_price');

        return view('shop.home', ['data' => $data]);
    }

}