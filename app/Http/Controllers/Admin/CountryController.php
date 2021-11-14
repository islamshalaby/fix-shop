<?php
namespace App\Http\Controllers\Admin;

use App\Country;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Cloudinary;

class CountryController extends AdminController{
    // show
    public function show() {
        $data['countries'] = Country::OrderBy('country_name', 'asc')->get();

        return view('admin.countries', compact('data'));
    }

    // edit
    public function edit(Country $country) {
        $data['country'] = $country;

        return view('admin.country_edit', compact('data'));
    }

    // update
    public function update(Request $request, Country $country) {
        $post = $request->all();
        if($request->file('icon')){
            // $image = $country->icon;
            // if ($image) {
            //     $publicId = substr($image, 0 ,strrpos($image, "."));    
            //     Cloudder::delete($publicId);
            // }
            
            $image_name = $request->file('icon')->getRealPath();
            $imagereturned = Cloudinary::upload($image_name);
            $image_id = $imagereturned->getPublicId();
            $image_format = $imagereturned->getExtension();   
            $image_new_name = $image_id.'.'.$image_format;
            $post['icon'] = $image_new_name;
        }
        $country->update($post);

        return redirect()->route('countries.show');
    }
}