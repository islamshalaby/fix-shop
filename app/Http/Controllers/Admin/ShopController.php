<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Shop;
use App\Seller;

class ShopController extends AdminController{
    // get create shop
    public function AddGet(Request $request) {
        $seller_ids = Shop::pluck('seller_id')->toArray();
        $data['sellers'] = Seller::select('id', 'shop')->whereNotIn('id', $seller_ids)->get();
        if (isset($request->seller)) {
            $data['seller'] = $request->seller;
        }

        return view('admin.shop_form', ['data' => $data]);
    }

    // post create shop
    public function AddPost(Request $request) {
        $post = $request->all();
        $request->validate([
            'email' => 'required|unique:shops,email',
            'password' => 'required',
            'seller_id' => 'required|unique:shops,seller_id'
        ]);
        
        if($request->file('logo')){
            $image_name = $request->file('logo')->getRealPath();
            Cloudder::upload($image_name, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];    
            $image_new_name = $image_id.'.'.$image_format;
            $post['logo'] = $image_new_name;
        }
        if($request->file('cover')){
            $cover_name = $request->file('cover')->getRealPath();
            Cloudder::upload($cover_name, null);
            $coverreturned = Cloudder::getResult();
            $cover_id = $coverreturned['public_id'];
            $cover_format = $coverreturned['format'];    
            $cover_new_name = $cover_id.'.'.$cover_format;
            $post['cover'] = $cover_new_name;
        }
        $seller = Seller::select('shop')->where('id', $request->seller_id)->first();
        $post['name'] = $seller['shop'];
        $post['logo'] = $image_new_name;
        $post['password'] = Hash::make($request->password);
        $show_home = 0;
        if (isset($request->show_home)) {
            $show_home = 1;
        }
        $post['show_home'] = $show_home;
        Shop::create($post);

        return redirect()->route('shops.index');
    }

    // edit get
    public function EditGet(Shop $store) {
        $data['store'] = $store;

        return view('admin.shop_edit', ['data' => $data]);
    }

    // edit post
    public function EditPost(Request $request, Shop $store) {
        $post = $request->all();
        $request->validate([
            'email' => 'required|unique:shops,email,' . $store->id
        ]);

        if($request->file('logo')){
            $logo = $store->logo;
            $publicId = substr($logo, 0 ,strrpos($logo, "."));    
            Cloudder::delete($publicId);
            $image_name = $request->file('logo')->getRealPath();
            Cloudder::upload($image_name, null);
            $imagereturned = Cloudder::getResult();
            $image_id = $imagereturned['public_id'];
            $image_format = $imagereturned['format'];    
            $image_new_name = $image_id.'.'.$image_format;
            $post['logo'] = $image_new_name;
        }
        if($request->file('cover')){
            $cover = $store->cover;
            $publicId = substr($cover, 0 ,strrpos($cover, "."));    
            Cloudder::delete($publicId);
            $cover_name = $request->file('cover')->getRealPath();
            Cloudder::upload($cover_name, null);
            $coverreturned = Cloudder::getResult();
            $cover_id = $coverreturned['public_id'];
            $cover_format = $coverreturned['format'];    
            $cover_new_name = $cover_id.'.'.$cover_format;
            $post['cover'] = $cover_new_name;
        }

        if (isset($request->password) && !empty($request->password)) {
            $post['password'] = Hash::make($request->password);
        }else {
            $post['password'] = $store->password;
        }
        $show_home = 0;
        if (isset($request->show_home)) {
            $show_home = 1;
        }
        $post['show_home'] = $show_home;
        $store->update($post);

        return redirect()->route('shops.index');
    }

    // store details
    public function details(Shop $store) {
        $data['store'] = $store;

        return view('admin.shop_details', ['data' => $data]);
    }

    // action
    public function action(Shop $store, $status) {
        $store->update(['status' => $status]);

        return redirect()->back();
    }

    // show shops
    public function index() {
        $data['shops'] = Shop::get();

        return view('admin.shops', ['data' => $data]);
    }
}