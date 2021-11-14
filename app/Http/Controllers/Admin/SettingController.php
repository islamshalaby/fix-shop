<?php
namespace App\Http\Controllers\Admin;

use App\Coordinate;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Cloudinary;
use App\Setting;

class SettingController extends AdminController{

    // get setting
    public function GetSetting(){
        $data['setting'] = Setting::find(1);
        $polygons = [];
        
        $areas = Coordinate::groupBy('area')->pluck('area')->toArray();
        for ($i = 0; $i < count($areas); $i ++) {
            $polygon = Coordinate::where('area', $areas[$i])->get();
            array_push($polygons, $polygon);
        }
        $data['polygon'] = $polygons;
        
        return view('admin.setting' , ['data' => $data]);
    }

    // post setting
    public function PostSetting(Request $request){
        $setting = Setting::find(1);
        if($request->file('logo')){

            $logo_name = $request->file('logo')->getRealPath();
            $imagereturned = Cloudinary::upload($logo_name);
            $image_id = $imagereturned->getPublicId();
            $image_format = $imagereturned->getExtension();
            $logo_new_name = $image_id.'.'.$image_format;
            $setting->logo = $logo_new_name;
        }
        $setting->app_name_en = $request->app_name_en;
        $setting->app_name_ar = $request->app_name_ar;
        $setting->email = $request->email;
        $setting->phone = $request->phone;
        $setting->app_phone = $request->app_phone;
        $setting->address_en = $request->address_en;
        $setting->address_ar = $request->address_ar;
        $setting->app_name_ar = $request->app_name_ar;
        $setting->facebook = $request->facebook;
        $setting->youtube = $request->youtube;
        $setting->twitter = $request->twitter;
        $setting->instegram = $request->instegram;
        $setting->snap_chat = $request->snap_chat;
        $setting->map_url = $request->map_url;
        $setting->latitude = $request->latitude;
        $setting->longitude = $request->longitude;
        $setting->delivery_cost = $request->delivery_cost;
        //about app

        if($request->file('about_image')){
            $about_image = $request->file('about_image')->getRealPath();
            $imagereturned = Cloudinary::upload($about_image);
            $image_id = $imagereturned->getPublicId();
            $image_format = $imagereturned->getExtension();
            $about_image_name = $image_id.'.'.$image_format;
            $setting->about_image = $about_image_name;
        }

        if ($request->lat && $request->lng) {
            $coord = Coordinate::orderBy('id', 'desc')->first();
            $area = 0;
            if ($coord) {
                $area = $coord->area + 1;
            }
            for ($i = 0; $i < count($request->lat); $i ++) {
                Coordinate::create([
                    'lat' => $request->lat[$i],
                    'lng' => $request->lng[$i],
                    'area' => $area
                ]);
            }
        }
        $setting->about_title = $request->about_title;
        $setting->about_desc = $request->about_desc;
        $setting->about_footer = $request->about_footer;
        $setting->save();
        return  back();
    }
}
