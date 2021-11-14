<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\APIHelpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Order;
use App\Product;

class HomeController extends Controller
{
    public function getdata(Request $request) {
        $productsLessTen = Product::where('store_id', Auth::guard('dashboard')->user()->id)
        ->where('deleted', 0)
        ->where('hidden', 0)
        ->where('remaining_quantity', '<', 10)
        ->select('id')
        ->get();
        
        $order = Order::where('store_id', Auth::guard('dashboard')->user()->id)->select('total_price', 'id')->get();
        if ($request->lang == 'en') {
            $products = Product::where('store_id', Auth::guard('dashboard')->user()->id)->where('deleted', 0)->where('hidden', 0)->select('id', 'remaining_quantity', 'title_en as title', 'final_price', 'price_before_offer', 'offer')->with('mainImage')->get();
        }else {
            $products = Product::where('store_id', Auth::guard('dashboard')->user()->id)->where('deleted', 0)->where('hidden', 0)->select('id', 'remaining_quantity', 'title_en as title', 'final_price', 'price_before_offer', 'offer')->with('mainImage')->get();
        }
        
        $newOrders = Order::where('store_id', Auth::guard('dashboard')->user()->id)
        ->select('id', 'order_number', 'created_at', 'status')
        ->orderBy('id', 'desc')
        ->first();
        if ($newOrders) {
            $newOrders['date'] = $newOrders['created_at']->format('Y-m-d'); 
            $newOrders['time'] = $newOrders['created_at']->format('g:i A');
        }else {
            $newOrders['date'] = ""; 
            $newOrders['time'] = "";
        }
        $data['products_less_than_ten'] = $productsLessTen->count('id');
        $data['total_sales'] = $order->sum('total_price');
        $data['orders_count'] = $order->count('id');
        $data['current_products_count'] = $products->count('id');
        $data['recent_order'] = $newOrders;
        $data['products'] = $products;
        
        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }

    // search
    public function search(Request $request) {
        $validator = Validator::make($request->all(), [
            'searched_number' => 'required'
        ]);

        if ($validator->fails()) {
            $response = APIHelpers::createApiResponse(true , 406 , 'Missing Required Fields' , 'بعض الحقول مفقودة'  , null , $request->lang);
            return response()->json($response , 406);
        }

        $orders = Order::where('order_number', 'like', '%' . $request->searched_number . '%')->where('store_id', Auth::guard('dashboard')->user()->id)->select('id', 'order_number', 'status', 'created_at')->simplePaginate(16);
        $products = Product::where('title_en', 'like', '%' . $request->searched_number . '%')->orWhere('title_ar', 'like', '%' . $request->searched_number . '%')->where('deleted', 0)
        ->where('hidden', 0)
        ->where('store_id', Auth::guard('dashboard')->user()->id)
        ->select('id', 'remaining_quantity', 'title_ar as title', 'final_price', 'price_before_offer', 'offer')->with('mainImage')
        ->simplePaginate(16);

        if (count($orders) > 0) {
            for ($i = 0; $i < count($orders); $i ++) {
                $orders[$i]['type'] = 1;
                $orders[$i]['date'] = $orders[$i]['created_at']->format('Y-m-d'); 
                $orders[$i]['time'] = $orders[$i]['created_at']->format('g:i A');
            }
            $data['result'] = $orders;
        }
        if (count($products) > 0) {
            for ($i = 0; $i < count($products); $i ++) {
                $products[$i]['type'] = 2;
            }
            // $data['type'] = 2;
            $data['result'] = $products;
        }

        

        $response = APIHelpers::createApiResponse(false , 200 , '' , '' , $data , $request->lang);
        return response()->json($response , 200);
    }
}