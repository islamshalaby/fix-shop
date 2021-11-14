<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use App\GoldPrice;


class GoldPriceController extends AdminController{
    // show
    public function show() {
        $data['gold_prices'] = GoldPrice::orderBy('id' , 'desc')->get();
        return view('admin.gold_prices' , ['data' => $data]);
    }

    // type : get -> to add new
    public function AddGet(){
        return view('admin.gold_price_form');
    }

    // type : post -> to add new
    public function AddPost(Request $request){
        $post = $request->all();

        GoldPrice::create($post);
        return redirect()->route('gold_prices.index');
    }

    // type : get -> to edit
    public function EditGet(GoldPrice $gold){
        $data['gold'] = $gold;

        return view('admin.gold_price_edit', ['data' => $data]);
    }

    // type : post -> to edit
    public function EditPost(Request $request, GoldPrice $gold){
        $post = $request->all();
        $gold->update($post);

        return redirect()->route('gold_prices.index');
    }

    // delete
    public function delete(GoldPrice $gold) {
        $gold->delete();

        return redirect()->back();
    }
}