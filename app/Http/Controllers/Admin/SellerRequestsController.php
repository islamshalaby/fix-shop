<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use App\Seller;
use App\Shop;

class SellerRequestsController extends AdminController{
    // get all contact us messages
    public function show(){
        $data['sellers'] = Seller::orderBy('id' , 'desc')->get();
        return view('admin.sellers' , ['data' => $data]);   
    }

    // seller request details
    public function details(Seller $seller){
        $stores = Shop::pluck('seller_id')->toArray();
        if (in_array($seller->id, $stores)) {
            $data['exist'] = true;
        }else {
            $data['exist'] = false;
        }
        $seller->seen = 1;
        $seller->save();
        $data['seller'] = $seller;
        return view('admin.seller_details' , ['data' => $data]);   
    }
}