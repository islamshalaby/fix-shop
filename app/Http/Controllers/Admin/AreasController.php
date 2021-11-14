<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Area;
use App\DeliveryArea;
use App\Governorate;
use App\Shop;

class AreasController extends AdminController{
    // get all areas
    public function show(){
        $data['areas'] = Area::where('deleted', 0)->orderBy('title_ar' , 'asc')->get();
        return view('admin.areas' , ['data' => $data]);
    }

    // add get
    public function addGet() {
        $data['governorates'] = Governorate::where('deleted', 0)->get();
        return view('admin.area_form', compact('data'));
    }

    // add post
    public function AddPost(Request $request){
        $post = $request->validate([
            'title_en' => 'required',
            'title_ar' => 'required',
            'governorate_id' => 'required'
        ]);
        Area::create($post);
        return redirect()->route('areas.index')->with('success', __('messages.added_successfully'));
    }

    // get edit page
    public function EditGet(Area $area){
        $data['area'] = $area;
        $data['governorates'] = Governorate::where('deleted', 0)->get();
        return view('admin.area_edit' , ['data' => $data ]);
    }

    // edit area
    public function EditPost(Request $request, Area $area){
        $post = $request->validate([
            'title_en' => "required",
            "title_ar" => "required",
            "governorate_id" => "required"
        ]);
        // if (isset($post['lat']) && isset($post['long'])) {
        //     $geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $post['lat'] . ',' . $post['long'] . '&sensor=true&key=AIzaSyCMSfq40Bo2KuQvQVSQE1gmmgJdxEbDS0Y&libraries');
        //     $output= json_decode($geocode);
        //     // echo "<pre>";
        //     // print_r($output);
        //     // echo "</pre>";
        //     // dd();
        //     $post['formatted_address'] = $output->results[2]->formatted_address;
        // }
        // dd($post);
        $area->update($post);

        return redirect()->route('areas.index')->with('success', __('messages.updated_successfully'));
    }

    // delete
    public function delete(Area $area) {
        $area->update(['deleted' => 1]);

        return redirect()->back();
    }

    // details
    public function details(Area $area) {
        $data['area'] = $area;
        // dd($data['area']->stores);
        return view('admin.area_details', ['data' => $data]);
    }

    // delete delivery area
    public function deleteDeliveryArea(DeliveryArea $cost) {
        $cost->delete();

        return redirect()->back()->with('success', __('messages.deleted_successfully'));
    }

    // get add delivery by area
    public function getAddDeliveryByArea($area) {
        $data['stores'] = Shop::where('status', 1)->orderBy('id', 'desc')->get();
        $data['area_id'] = $area;
        $data['area'] = Area::where('id', $area)->first();

        return view('admin.deliver_cost_areas_form', compact('data'));
    }

    // get add delivery by governorate
    public function getAddDeliveryByGovernorate($governorate) {
        $data['stores'] = Shop::where('status', 1)->orderBy('id', 'desc')->get();
        $areas = Area::where('deleted', 0)->where('governorate_id', $governorate)->pluck('id')->toArray();
        $data['governorate_id'] = $governorate;
        $data['governorate'] = Governorate::where('id', $governorate)->first();

        return view('admin.deliver_cost_governorates_form', compact('data'));
    }

    // get delivery costs by area
    public function deliver_cost_areas() {
        $data['areas'] = Area::where('deleted', 0)->orderBy('title_ar', 'asc')->get();

        return view('admin.deliver_cost_areas', compact('data'));
    }

    // get add delivery costs by governorate
    public function addDeliveryCostByGovernorate() {
        $data['governorates'] = Governorate::where('deleted', 0)->orderBy('id', 'desc')->get();

        return view('admin.deliver_cost_governorates', compact('data'));
    }

    // post add delivery costs
    public function add_deliver_cost_post(Request $request) {
        $post = $request->validate([
            "delivery_cost" => 'required',
            "estimated_arrival_time" => "required",
            "area_id" => "required",
            "store_id" => "required"
        ]);
        $deliveryArea = DeliveryArea::where('area_id', $request->area_id)->where('store_id', $request->store_id)->first();
        if ($deliveryArea) {
            $deliveryArea->update($post);
        }else {
            DeliveryArea::create($post);
        }

        return redirect()->back()->with('success', __('messages.added_successfully'));
    }

    // post add delivery costs by governorate
    public function add_deliver_cost_post_by_governorate(Request $request) {
        $post = $request->validate([
            "delivery_cost" => 'required',
            "estimated_arrival_time" => "required",
            "store_id" => "required",
            "governorate_id" => "required"
        ]);
        $areas = Area::where('deleted', 0)->where('governorate_id', $post['governorate_id'])->pluck('id')->toArray();
        $store = Shop::where('id', $post['store_id'])->first();
        $store->areas()->delete();
        
        for ($i = 0; $i < count($areas); $i ++) {
            $post['area_id'] = $areas[$i];

            DeliveryArea::create($post);
        }

        return redirect()->back()->with('success', __('messages.added_successfully'));
    }

    // show delivery costs by area
    public function show_delivery_costs(Area $area) {
        $data['area'] = $area;
        $data['costs'] = DeliveryArea::where('area_id', $area->id)->get();
        $areas = DeliveryArea::where('area_id', $area->id)->count();
        $stores = Shop::where('status', 1)->count();
        
        $data['show_add'] = true;
        if ($areas == $stores) {
            $data['show_add'] = false;
        }

        // dd($data['show_add']);

        return view('admin.delivery_costs', ['data' => $data]);
    }

    // get edit delivery cost
    public function edit_delivery_cost_get(Area $area, DeliveryArea $cost) {
        $data['area'] = $area;
        
        $data['cost'] = $cost;

        return view('admin.deliver_cost_edit', ['data' => $data]);
    }

    // post edit deliver cost
    public function edit_delivery_cost_post(Request $request, Area $area, DeliveryArea $cost) {
        $post = $request->all();

        $cost->update($post);

        return redirect()->route('areas.show.delivercost', $area->id);
    }

    // delete delivery cost
    public function deleteDeliveryCost(DeliveryArea $cost) {
        $cost->delete();

        return redirect()->back()->with('success', __('messages.deleted_successfully'));
    }

    // fetch stores by area
    public function fetchStoresByArea($area) {
        $deliveryArea = DeliveryArea::where('area_id', $area)->pluck('store_id')->toArray();

        $rows = Shop::whereNotIn('id', $deliveryArea)->where('status', 1)->select('id', 'name')->orderBy('id', 'desc')->get();
        
        $data = json_decode(($rows));
        
        return response($data, 200);
    }

    // get add governorate
    public function getAddGovernorate() {
        return view('admin.governorate_form');
    }

    // post add governorate
    public function postAddGovernorate(Request $request) {
        $post = $request->validate([
            'title_en' => 'required',
            'title_ar' => 'required'
        ]);

        Governorate::create($post);

        return redirect()->route('areas.governorates.index')->with('success', __('messages.added_successfully'));
    }

    // get governorates
    public function getGovernorates() {
        $data['governorates'] = Governorate::where('deleted', 0)->orderBy('id', 'desc')->get();

        return view('admin.governorates', compact('data'));
    }

    // get edit governorate
    public function getEditGovernorate(Governorate $governorate) {
        $data['governorate'] = $governorate;

        return view('admin.governorate_edit', compact('data'));
    }

    // post edit governorate
    public function postEditGovernorate(Governorate $governorate, Request $request) {
        $post = $request->validate([
            'title_en' => "required",
            "title_ar" => "required"
        ]);

        $governorate->update($post);

        return redirect()->route('areas.governorates.index')->with('success', __('messages.updated_successfully'));
    }

    // get delete governorate
    public function getDeleteGovernorate(Governorate $governorate) {
        $governorate->update(['deleted' => 1]);

        return redirect()->back()->with('success', __('messages.deleted_successfully'));
    }

    // get governorates details
    public function getGovernorateDetails(Governorate $governorate) {
        $data['governorate'] = $governorate;

        return view('admin.governorate_details', compact('data'));
    }

    public function test() {
        
        $geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng=29.3035363,47.8223596&sensor=true&key=AIzaSyCMSfq40Bo2KuQvQVSQE1gmmgJdxEbDS0Y&libraries');
        // dd($geocode);
        $output= json_decode($geocode);

        echo "<pre>";
        print_r($output->results);
        echo "</pre>";
    }
}