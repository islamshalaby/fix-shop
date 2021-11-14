<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Area;
use App\DeliveryArea;


class DeliveryCostController extends ShopController{
    // index
    public function show() {
        $data['costs'] = DeliveryArea::join('areas', 'areas.id', '=', 'delivery_areas.area_id')
        ->where('shops.id', Auth::user()->id)
        ->where('areas.deleted', 0)
        ->leftjoin('shops', function($join) {
            $join->on('shops.id', '=', 'delivery_areas.store_id');
        })
        ->select('delivery_areas.delivery_cost', 'areas.title_en', 'areas.title_ar', 'delivery_areas.id as costId')
        ->groupBy('delivery_areas.delivery_cost')
        ->groupBy('areas.title_en')
        ->groupBy('areas.title_ar')
        ->groupBy('delivery_areas.id')
        ->get();

        
        return view('shop.delivery_costs' , ['data' => $data]); 
    }

    public function AddGet() {
        $areas = DeliveryArea::where('store_id', Auth::user()->id)->pluck('area_id')->toArray();
        $data['areas'] = Area::select('title_en', 'title_ar', 'id')->whereNotIn('id', $areas)->where('deleted', 0)->get();

        return view('shop.delivery_cost_form' , ['data' => $data]); 
    }

    public function AddPost(Request $request) {
        $request->validate([
            'area_id' => 'required|numeric|min:1',
            'delivery_cost' => 'required|numeric|min:1'
        ]);
        $post = $request->all();
        $post['store_id'] = Auth::user()->id;
        DeliveryArea::create($post);

        return redirect()->route('delivery_costs.store.index');
    }

    public function EditGet(DeliveryArea $cost) {
        $data['cost'] = DeliveryArea::where('store_id', Auth::user()->id)->where('id', $cost->id)->first();

        if (isset($data['cost']['id'])) {
            return view('shop.delivery_cost_edit' , ['data' => $data]); 
        }else {
            return abort('404');
        }
        
    }

    public function EditPost(Request $request, DeliveryArea $cost) {
        $request->validate([
            'delivery_cost' => 'required|numeric|min:1'
        ]);
        $post = $request->all();
        
        $cost->update($post);

        return redirect()->route('delivery_costs.store.index');
    }
}